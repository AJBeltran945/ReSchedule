<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferencesController extends Controller
{
    public function create()
    {
        return view('auth.preferences');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Must be a valid HH:MM
            'sleep_time'        => 'required|date_format:H:i',
            // Wake must come after sleep
            'wake_time'         => 'required|date_format:H:i|after:sleep_time',

            // Meals must fall in order
            'breakfast_time'    => 'required|date_format:H:i|after:wake_time|before:lunch_time',
            'lunch_time'        => 'required|date_format:H:i|after:breakfast_time|before:dinner_time',
            'dinner_time'       => 'required|date_format:H:i|after:lunch_time|before:sleep_time',

            // Study block must sit between wake and sleep, and end after it starts
            'study_time_start'  => 'required|date_format:H:i|after:wake_time|before:study_time_end',
            'study_time_end'    => 'required|date_format:H:i|after:study_time_start|before:sleep_time',
        ], [
            'wake_time.after'              => 'Wake time must be later than sleep time.',
            'breakfast_time.after'         => 'Breakfast must be after you wake up.',
            'breakfast_time.before'        => 'Breakfast must be before lunch.',
            'lunch_time.after'             => 'Lunch must come after breakfast.',
            'lunch_time.before'            => 'Lunch must be before dinner.',
            'dinner_time.after'            => 'Dinner must come after lunch.',
            'dinner_time.before'           => 'Dinner must be before you go to sleep.',
            'study_time_start.after'       => 'Study start must be after wake time.',
            'study_time_start.before'      => 'Study start must be before study end.',
            'study_time_end.after'         => 'Study end must be after study start.',
            'study_time_end.before'        => 'Study end must be before your sleep time.',
        ]);

        Auth::user()
            ->preference()
            ->create($request->only([
                'sleep_time',
                'wake_time',
                'breakfast_time',
                'lunch_time',
                'dinner_time',
                'study_time_start',
                'study_time_end',
            ]));

        return redirect()->route('home.month');
    }
}
