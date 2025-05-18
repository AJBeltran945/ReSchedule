<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class MonthCalendar extends Component
{
    public $month;
    public $year;
    public $selectedDay;
    public $showModal = false;

    public function showDayModal($date)
    {
        $this->selectedDay = Carbon::parse($date);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

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
        $startDayOfWeek = $date->dayOfWeekIso; // Monday = 1
        $calendar = [];

        $current = $date->copy()->subDays($startDayOfWeek - 1);

        for ($i = 0; $i < 42; $i++) {
            $calendar[] = [
                'day' => $current->copy(),
                'isToday' => $current->isToday(),
                'isCurrentMonth' => $current->month === $this->month,
            ];
            $current->addDay();
        }

        return view('livewire.month-calendar', [
            'calendar' => $calendar,
            'currentMonthName' => $date->format('F'),
            'year' => $this->year,
        ]);
    }
}
