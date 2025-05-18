<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Tasklist extends Component
{
    public $date;

    public function mount($date)
    {
        $this->date = $date;
    }

    public function render()
    {
        $tasks = Task::with('type')
            ->where('user_id', Auth::id())
            ->whereDate('start_date', $this->date)
            ->get();

        return view('livewire.tasklist', compact('tasks'));
    }
}
