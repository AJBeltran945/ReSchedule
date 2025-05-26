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
        $validator = Validator::make($request->all(), [
            'sleep_time'        => 'required|date_format:H:i',
            'wake_time'         => 'required|date_format:H:i|after:sleep_time',

            'breakfast_time'    => 'required|date_format:H:i|after:wake_time|before:lunch_time',
            'lunch_time'        => 'required|date_format:H:i|after:breakfast_time|before:dinner_time',

            'dinner_time'       => 'required|date_format:H:i|after:lunch_time',

            'study_time_start'  => 'required|date_format:H:i|after:wake_time|before:study_time_end',

            'study_time_end'    => 'required|date_format:H:i|after:study_time_start',

            'is_subscribed'     => 'nullable|boolean',
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

        $validator->after(function ($validator) use ($request) {
            $sleep      = Carbon::createFromFormat('H:i', $request->sleep_time);
            $wake       = Carbon::createFromFormat('H:i', $request->wake_time);
            $breakfast  = Carbon::createFromFormat('H:i', $request->breakfast_time);
            $lunch      = Carbon::createFromFormat('H:i', $request->lunch_time);
            $dinner     = Carbon::createFromFormat('H:i', $request->dinner_time);
            $studyStart = Carbon::createFromFormat('H:i', $request->study_time_start);
            $studyEnd   = Carbon::createFromFormat('H:i', $request->study_time_end);

            if ($sleep->greaterThan($wake)) {
                $wake->addDay();
            } else {
                $sleep->addDay();
                $wake->addDay();
            }

            $sleepMins = $wake->diffInMinutes($sleep);
            if ($sleepMins < 360) {
                $validator->errors()->add('wake_time', 'You must sleep at least 6 hours.');
            }
            if ($sleepMins > 720) {
                $validator->errors()->add('wake_time', 'Sleep cannot exceed 12 hours.');
            }

            // meals & study spacing
            if ($lunch->diffInHours($breakfast) < 3) {
                $validator->errors()->add('lunch_time', 'Lunch should be at least 3 hours after breakfast.');
            }

            if ($dinner->diffInHours($lunch) < 3) {
                $validator->errors()->add('dinner_time', 'Dinner should be at least 3 hours after lunch.');
            }

            if ($dinner->greaterThanOrEqualTo($sleep)) {
                $validator->errors()->add('dinner_time', 'Dinner must be before your sleep time.');
            }

            if ($studyEnd->greaterThanOrEqualTo($sleep)) {
                $validator->errors()->add('study_time_end', 'Study end must be before your sleep time.');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedTime = $validator->validated();
        $data= [
            'sleep_time'        => $validatedTime['sleep_time'],
            'wake_time'         => $validatedTime['wake_time'],
            'breakfast_time'    => $validatedTime['breakfast_time'],
            'lunch_time'        => $validatedTime['lunch_time'],
            'dinner_time'       => $validatedTime['dinner_time'],
            'study_time_start'  => $validatedTime['study_time_start'],
            'study_time_end'    => $validatedTime['study_time_end'],

            'is_subscribed'     => $request->has('is_subscribed'),
        ];

        Auth::user()
            ->preference()
            ->create($data);

        return redirect()->route('home.month');
    }
}
