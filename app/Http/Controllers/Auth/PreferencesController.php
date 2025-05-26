<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PreferencesController extends Controller
{
    public function create()
    {
        return view('auth.preferences');
    }

    public function store(Request $request)
    {
        // 1) Build validator with basic format + ordering rules
        $validator = Validator::make($request->all(), [
            'sleep_time'        => 'required|date_format:H:i',
            'wake_time'         => 'required|date_format:H:i|after:sleep_time',

            'breakfast_time'    => 'required|date_format:H:i|after:wake_time|before:lunch_time',
            'lunch_time'        => 'required|date_format:H:i|after:breakfast_time|before:dinner_time',

            // remove before:sleep_time here so we can handle next‐day logic
            'dinner_time'       => 'required|date_format:H:i|after:lunch_time',

            'study_time_start'  => 'required|date_format:H:i|after:wake_time|before:study_time_end',
            // remove before:sleep_time here as well
            'study_time_end'    => 'required|date_format:H:i|after:study_time_start',
        ], [
            'wake_time.after'              => 'Wake time must be later than sleep time.',
            'breakfast_time.after'         => 'Breakfast must be after you wake up.',
            'breakfast_time.before'        => 'Breakfast must be before lunch.',
            'lunch_time.after'             => 'Lunch must come after breakfast.',
            'lunch_time.before'            => 'Lunch must be before dinner.',
            'dinner_time.after'            => 'Dinner must come after lunch.',
            'study_time_start.after'       => 'Study start must be after wake time.',
            'study_time_start.before'      => 'Study start must be before study end.',
            'study_time_end.after'         => 'Study end must be after study start.',
        ]);

        // 2) Add “business logic” constraints in an after‐validation hook
        $validator->after(function ($validator) use ($request) {
            $sleep      = Carbon::createFromFormat('H:i', $request->sleep_time);
            $wake       = Carbon::createFromFormat('H:i', $request->wake_time);
            $breakfast  = Carbon::createFromFormat('H:i', $request->breakfast_time);
            $lunch      = Carbon::createFromFormat('H:i', $request->lunch_time);
            $dinner     = Carbon::createFromFormat('H:i', $request->dinner_time);
            $studyStart = Carbon::createFromFormat('H:i', $request->study_time_start);
            $studyEnd   = Carbon::createFromFormat('H:i', $request->study_time_end);

            // Roll sleep/wake onto the same “night”
            if ($sleep->greaterThan($wake)) {
                // you slept at, say, 23:00 and wake at 07:00 next day
                $wake->addDay();
            } else {
                // you slept after midnight (00:00 → wake 08:00)
                $sleep->addDay();
                $wake->addDay();
            }

            // a) Sleep between 6h and 12h
            $sleepMins = $wake->diffInMinutes($sleep);
            if ($sleepMins < 360) {
                $validator->errors()->add('wake_time', 'You must sleep at least 6 hours.');
            }
            if ($sleepMins > 720) {
                $validator->errors()->add('wake_time', 'Sleep cannot exceed 12 hours.');
            }

            // b) Breakfast ≥ 30m after wake
            if ($breakfast->diffInMinutes($wake) < 30) {
                $validator->errors()->add('breakfast_time', 'Breakfast should be at least 30 minutes after waking.');
            }

            // c) Lunch ≥ 3h after breakfast
            if ($lunch->diffInHours($breakfast) < 3) {
                $validator->errors()->add('lunch_time', 'Lunch should be at least 3 hours after breakfast.');
            }

            // d) Dinner ≥ 3h after lunch
            if ($dinner->diffInHours($lunch) < 3) {
                $validator->errors()->add('dinner_time', 'Dinner should be at least 3 hours after lunch.');
            }

            // e) Dinner must come before (rolled) sleep
            if ($dinner->greaterThanOrEqualTo($sleep)) {
                $validator->errors()->add('dinner_time', 'Dinner must be before your sleep time.');
            }

            // f) Study block ≥ 30m
            if ($studyEnd->diffInMinutes($studyStart) < 30) {
                $validator->errors()->add('study_time_end', 'Study session must be at least 30 minutes long.');
            }

            // g) Study end before sleep
            if ($studyEnd->greaterThanOrEqualTo($sleep)) {
                $validator->errors()->add('study_time_end', 'Study end must be before your sleep time.');
            }
        });

        // 3) Redirect back with errors if validation failed
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 4) Persist validated preferences
        Auth::user()
            ->preference()
            ->create($validator->validated());

        return redirect()->route('home.month');
    }
}
