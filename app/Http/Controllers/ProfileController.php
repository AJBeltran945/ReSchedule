<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user       = $request->user();
        $preference = $user->preference;

        // Define your form fields here instead of in the view:
        $fields = [
            'sleep_time'        => 'Sleep Time',
            'wake_time'         => 'Wake Time',
            'breakfast_time'    => 'Breakfast Time',
            'lunch_time'        => 'Lunch Time',
            'dinner_time'       => 'Dinner Time',
            'study_time_start'  => 'Study Start Time',
            'study_time_end'    => 'Study End Time',
        ];

        return view('frontend.profile.edit', compact('user', 'preference', 'fields'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array_merge(
                [
                    'name'  => ['required','string','max:255'],
                    'email' => ['required','string','email','max:255'],
                ],
                [
                    'sleep_time'       => ['required','date_format:H:i'],
                    'wake_time'        => ['required','date_format:H:i','after:sleep_time'],

                    'breakfast_time'   => ['required','date_format:H:i','after:wake_time','before:lunch_time'],
                    'lunch_time'       => ['required','date_format:H:i','after:breakfast_time','before:dinner_time'],
                    'dinner_time'      => ['required','date_format:H:i','after:lunch_time'],

                    'study_time_start' => ['required','date_format:H:i','after:wake_time','before:study_time_end'],
                    'study_time_end'   => ['required','date_format:H:i','after:study_time_start'],

                    'is_subscribed'    => ['nullable','boolean'],
                ]
            ),
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

                'is_subscribed.boolean'    => 'Invalid value for email notifications setting.',
            ]
        );

        $validator->after(function ($v) use ($request) {
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

            $mins = $wake->diffInMinutes($sleep);
            if ($mins < 360) {
                $v->errors()->add('wake_time', 'You must sleep at least 6 hours.');
            }
            if ($mins > 720) {
                $v->errors()->add('wake_time', 'Sleep cannot exceed 12 hours.');
            }

            if ($dinner->greaterThanOrEqualTo($sleep)) {
                $v->errors()->add('dinner_time', 'Dinner must be before your sleep time.');
            }

            if ($studyEnd->greaterThanOrEqualTo($sleep)) {
                $v->errors()->add('study_time_end', 'Work hours must be before your sleep time.');
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data     = $validator->validated();
        $userData = Arr::only($data, ['name','email']);

        $prefData = [
            'sleep_time'        => $data['sleep_time'],
            'wake_time'         => $data['wake_time'],
            'breakfast_time'    => $data['breakfast_time'],
            'lunch_time'        => $data['lunch_time'],
            'dinner_time'       => $data['dinner_time'],
            'study_time_start'  => $data['study_time_start'],
            'study_time_end'    => $data['study_time_end'],
            'is_subscribed'     => $request->has('is_subscribed'),
        ];

        $user = $request->user();
        $user->update($userData);

        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();
        }

        $user->preference()
            ->updateOrCreate(
                ['user_id' => $user->id],
                $prefData
            );

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
