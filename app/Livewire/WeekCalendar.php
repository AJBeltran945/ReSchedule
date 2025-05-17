<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class WeekCalendar extends Component
{
    public $startOfWeek;

    public function mount()
    {
        $this->startOfWeek = now()->startOfWeek(Carbon::MONDAY);
    }

    public function goToPreviousWeek()
    {
        $this->startOfWeek = Carbon::parse($this->startOfWeek)->subWeek();
    }

    public function goToNextWeek()
    {
        $this->startOfWeek = Carbon::parse($this->startOfWeek)->addWeek();
    }

    public function render()
    {
        $calendar = [];

        $current = Carbon::parse($this->startOfWeek);
        for ($i = 0; $i < 7; $i++) {
            $calendar[] = $current->copy();
            $current->addDay();
        }

        return view('livewire.week-calendar', [
            'calendar' => $calendar,
            'weekLabel' => Carbon::parse($this->startOfWeek)->format('d M') . ' - ' . Carbon::parse($this->startOfWeek)->addDays(6)->format('d M Y'),
        ]);
    }
}
