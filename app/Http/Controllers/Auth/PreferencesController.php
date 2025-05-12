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
            'sleep_time' => 'required|date_format:H:i',
            'wake_time' => 'required|date_format:H:i',
            'breakfast_time' => 'required|date_format:H:i',
            'lunch_time' => 'required|date_format:H:i',
            'dinner_time' => 'required|date_format:H:i',
            'study_time_start' => 'required|date_format:H:i',
            'study_time_end' => 'required|date_format:H:i',
        ]);

        Auth::user()->preference()->create($request->all());

        return redirect()->route('frontend.dashboard');
    }
}
