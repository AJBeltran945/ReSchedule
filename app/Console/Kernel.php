<?php

namespace App\Console;

use App\Models\User;
use App\Notifications\DailyTaskSummary;
use App\Notifications\WeeklyTaskDigest;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected function schedule(Schedule $schedule)
    {
        // Daily tasks at 7 AM
        $schedule->call(function () {
            $users = User::with(['tasks' => function ($query) {
                $query->whereDate('start_date', today());
            }])->get();

            foreach ($users as $user) {
                if ($user->tasks->isNotEmpty()) {
                    $user->notify(new DailyTaskSummary($user->tasks));
                }
            }
        })->dailyAt('07:00');

        // Weekly digest every Monday at 7 AM
        $schedule->call(function () {
            $users = User::with(['tasks' => function ($query) {
                $query->whereBetween('start_date', [now(), now()->addDays(7)]);
            }])->get();

            foreach ($users as $user) {
                if ($user->tasks->isNotEmpty()) {
                    $user->notify(new WeeklyTaskDigest($user->tasks));
                }
            }
        })->weeklyOn(1, '07:00');
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
