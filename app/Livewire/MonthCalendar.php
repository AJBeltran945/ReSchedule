<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class MonthCalendar extends Component
{
    public $month;
    public $year;

    public function mount()
    {
        $now = now();
        $this->month = $now->month;
        $this->year = $now->year;
    }

    public function goToPreviousMonth()
    {
        $date = Carbon::create($this->year, $this->month)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function goToNextMonth()
    {
        $date = Carbon::create($this->year, $this->month)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function render()
    {
        $date = Carbon::create($this->year, $this->month, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
        $calendar = [];

        $current = $date->copy()->subDays($startDayOfWeek - 1);

        for ($i = 0; $i < 42; $i++) {
            $calendar[] = $current->copy();
            $current->addDay();
        }

        return view('livewire.month-calendar', [
            'calendar' => $calendar,
            'daysInMonth' => $daysInMonth,
            'currentMonthName' => $date->format('F'),
        ]);
    }
}
