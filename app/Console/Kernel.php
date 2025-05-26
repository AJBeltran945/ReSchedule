<?php

namespace App\Console;

use App\Models\User;
use App\Notifications\DailyTaskSummary;
use App\Notifications\WeeklyTaskDigest;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected function schedule(Schedule $schedule)
    {
        // 1) Daily summary at 7 AM for subscribed users:
        $schedule->call(function () {
            $today = Carbon::today()->toDateString();

            User::subscribed()
                ->with(['tasks' => function ($q) use ($today) {
                    $q->whereDate('start_date', $today);
                }])
                ->get()
                ->filter->tasks->isNotEmpty()
                ->each(function (User $user) {
                    $user->notify(new DailyTaskSummary($user->tasks));
                });
        })->dailyAt('07:00');

        // 2) Weekly digest every Monday at 7 AM for subscribed users:
        $schedule->call(function () {
            $start = Carbon::now()->startOfDay();
            $end   = Carbon::now()->addWeek()->endOfDay();

            User::subscribed()
                ->with(['tasks' => function ($q) use ($start, $end) {
                    $q->whereBetween('start_date', [$start, $end]);
                }])
                ->get()
                ->filter->tasks->isNotEmpty()
                ->each(function (User $user) {
                    $user->notify(new WeeklyTaskDigest($user->tasks));
                });
        })->weeklyOn(1, '07:00');  // 1 = Monday
    }


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
