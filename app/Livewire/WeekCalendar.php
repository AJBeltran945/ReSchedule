<?php

namespace App\Livewire;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WeekCalendar extends Component
{
    public $startOfWeek;
    public $selectedDay;
    public $showModal = false;

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

    public function showDayModal($date)
    {
        $this->selectedDay = Carbon::parse($date);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        $calendar = [];
        $current = Carbon::parse($this->startOfWeek);
        $userId = Auth::id();

        for ($i = 0; $i < 7; $i++) {
            $tasks = Task::whereDate('start_date', $current->toDateString())
                ->where('user_id', $userId)
                ->where('completed', false)
                ->with(['type'])
                ->orderBy('start_date', 'asc')
                ->get();

            $calendar[] = [
                'date' => $current->copy(),
                'isToday' => $current->isToday(),
                'tasks' => $tasks,
            ];

            $current->addDay();
        }

        return view('livewire.week-calendar', [
            'calendar' => $calendar,
            'weekLabel' => Carbon::parse($this->startOfWeek)->format('d M') . ' - ' . Carbon::parse($this->startOfWeek)->addDays(6)->format('d M Y'),
            'selectedDay' => $this->selectedDay,
        ]);
    }
}
