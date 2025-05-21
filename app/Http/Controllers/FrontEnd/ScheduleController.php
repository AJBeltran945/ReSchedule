<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Task;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $start = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $end = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $tasks = Task::where('user_id', $user->id)
            ->whereBetween('start_date', [$start, $end])
            ->orderBy('start_date')
            ->get();

        return view('schedule.index', compact('tasks', 'start', 'end'));
    }
}
