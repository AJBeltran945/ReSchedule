<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function index()
    {
        $histories = TaskHistory::where('user_id', Auth::id())->get();

        $weekly = $histories->groupBy(function ($h) {
            return $h->completed_at->startOfWeek()->format('Y-m-d');
        });

        return view('stats.index', compact('weekly'));
    }
}
