<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user       = $request->user();
        $preference = $user->preference; // may be null if not set

        return view('frontend.profile.edit', compact('user', 'preference'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        // 1) Build a validator for both profile and preferences
        $validator = Validator::make(
            $request->all(),
            array_merge(
            // Profile fields
                [
                    'name'  => ['required','string','max:255'],
                    'email' => ['required','string','email','max:255'],
                ],
                // Preference fields (we’ll handle next-day logic in after() hook)
                [
                    'sleep_time'       => ['required','date_format:H:i'],
                    'wake_time'        => ['required','date_format:H:i','after:sleep_time'],

                    'breakfast_time'   => ['required','date_format:H:i','after:wake_time','before:lunch_time'],
                    'lunch_time'       => ['required','date_format:H:i','after:breakfast_time','before:dinner_time'],
                    'dinner_time'      => ['required','date_format:H:i','after:lunch_time'],

                    'study_time_start' => ['required','date_format:H:i','after:wake_time','before:study_time_end'],
                    'study_time_end'   => ['required','date_format:H:i','after:study_time_start'],
                ]
            ),
            // Custom messages
            [
                'wake_time.after'            => 'Wake time must be later than sleep time.',
                'breakfast_time.after'       => 'Breakfast must be after you wake up.',
                'breakfast_time.before'      => 'Breakfast must be before lunch.',
                'lunch_time.after'           => 'Lunch must come after breakfast.',
                'lunch_time.before'          => 'Lunch must be before dinner.',
                'dinner_time.after'          => 'Dinner must come after lunch.',
                'study_time_start.after'     => 'Study start must be after wake time.',
                'study_time_start.before'    => 'Study start must be before study end.',
                'study_time_end.after'       => 'Study end must be after study start.',
            ]
        );

        // 2) Add more complex “roll‐and‐check” rules
        $validator->after(function ($v) use ($request) {
            $sleep      = Carbon::createFromFormat('H:i', $request->sleep_time);
            $wake       = Carbon::createFromFormat('H:i', $request->wake_time);
            $breakfast  = Carbon::createFromFormat('H:i', $request->breakfast_time);
            $lunch      = Carbon::createFromFormat('H:i', $request->lunch_time);
            $dinner     = Carbon::createFromFormat('H:i', $request->dinner_time);
            $studyStart = Carbon::createFromFormat('H:i', $request->study_time_start);
            $studyEnd   = Carbon::createFromFormat('H:i', $request->study_time_end);

            // Roll sleep & wake onto the same night
            if ($sleep->greaterThan($wake)) {
                // went to bed before midnight → wake next day
                $wake->addDay();
            } else {
                // post-midnight sleep → roll both to tomorrow
                $sleep->addDay();
                $wake->addDay();
            }

            // a) Sleep duration between 6h and 12h
            $mins = $wake->diffInMinutes($sleep);
            if ($mins < 360) {
                $v->errors()->add('wake_time', 'You must sleep at least 6 hours.');
            }
            if ($mins > 720) {
                $v->errors()->add('wake_time', 'Sleep cannot exceed 12 hours.');
            }

            // b) Breakfast ≥ 30m after wake
            if ($breakfast->diffInMinutes($wake) < 30) {
                $v->errors()->add('breakfast_time', 'Breakfast should be at least 30 minutes after waking.');
            }

            // c) Lunch ≥ 3h after breakfast
            if ($lunch->diffInHours($breakfast) < 3) {
                $v->errors()->add('lunch_time', 'Lunch should be at least 3 hours after breakfast.');
            }

            // d) Dinner ≥ 3h after lunch
            if ($dinner->diffInHours($lunch) < 3) {
                $v->errors()->add('dinner_time', 'Dinner should be at least 3 hours after lunch.');
            }

            // e) Dinner must be before sleep
            if ($dinner->greaterThanOrEqualTo($sleep)) {
                $v->errors()->add('dinner_time', 'Dinner must be before your sleep time.');
            }

            // f) Study block ≥ 30m and before sleep
            if ($studyEnd->diffInMinutes($studyStart) < 30) {
                $v->errors()->add('study_time_end', 'Study session must be at least 30 minutes long.');
            }
            if ($studyEnd->greaterThanOrEqualTo($sleep)) {
                $v->errors()->add('study_time_end', 'Study end must be before your sleep time.');
            }
        });

        // 3) If validation fails, redirect back with errors & old input
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // 4) Persist: split out user vs preference data
        $data     = $validator->validated();
        $userData = Arr::only($data, ['name','email']);
        $prefData = Arr::only($data, [
            'sleep_time','wake_time',
            'breakfast_time','lunch_time','dinner_time',
            'study_time_start','study_time_end',
        ]);

        $user = $request->user();
        $user->update($userData);
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();
        }

        // Update or create the preferences record
        $user->preference()->updateOrCreate([], $prefData);

        return redirect()
            ->route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
