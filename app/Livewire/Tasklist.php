<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskType;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Tasklist extends Component
{
    public $date;
    public $showForm = false;

    public $title = '';
    public $description = '';
    public $type_task_id = null;
    public $duration = '';
    public $addDuration = false;
    public $related_task_id = null;

    public function mount($date)
    {
        $this->date = $date;
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
    }

    public function onTypeChange()
    {
        $this->addDuration = false;
        $this->duration = '';
        $this->related_task_id = null;
    }

    public function onAddDurationChange($checked)
    {
        $this->addDuration = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
    }

    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_task_id' => 'required|exists:task_types,id',
        ];

        if ((int)$this->type_task_id === 1 && $this->addDuration) {
            $rules['duration'] = 'required|string|max:20';
        }

        if ((int)$this->type_task_id === 2) {
            $rules['duration'] = 'required|string|max:20';
        }

        if ((int)$this->type_task_id === 3) {
            $rules['duration'] = 'nullable|string|max:20';
            $rules['related_task_id'] = 'required|exists:tasks,id';
        }

        $this->validate($rules);

        Task::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'type_task_id' => $this->type_task_id,
            'start_date' => $this->date,
            'end_date' => null,
            'related_task_id' => $this->related_task_id,
            'completed' => false,
        ]);

        $this->reset(['title', 'description', 'type_task_id', 'duration', 'addDuration', 'related_task_id', 'showForm']);
    }

    public function render()
    {
        $tasks = Task::with('type')
            ->where('user_id', Auth::id())
            ->whereDate('start_date', $this->date)
            ->get();

        $types = TaskType::all();
        $userTasks = Task::where('user_id', Auth::id())
            ->whereDate('start_date', $this->date)
            ->get();

        return view('livewire.tasklist', compact('tasks', 'types', 'userTasks'));
    }
}
