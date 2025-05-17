<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Optional: redirect if logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('home'); // resources/views/home.blade.php
    }
}
