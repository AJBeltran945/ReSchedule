<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskType;
use App\Models\UserPreference;
use Carbon\Carbon;
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
    public $manualInterval = false;
    public $addDuration = false;
    public $related_task_id = null;
    public $start_time = '';
    public $end_time = '';
    public $preferred_time_block = '';

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
        $this->start_time = '';
        $this->end_time = '';
    }

    public function onAddDurationChange($checked)
    {
        $this->addDuration = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
    }
    public function onManualIntervalChange($checked)
    {
        $this->manualInterval = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
    }

    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_task_id' => 'required|exists:task_types,id',
        ];

        if ((int)$this->type_task_id === 1 && $this->manualInterval) {
            $rules['start_time'] = 'nullable|date_format:H:i';
            $rules['end_time'] = 'nullable|date_format:H:i|after:start_time';
        }

        if ((int)$this->type_task_id === 1 && $this->duration) {
            $rules['duration'] = [
                'required',
                'regex:/^((\d{1,2}h\s?)?(\d{1,2}min)?)$/'
            ];
        }

        if ((int)$this->type_task_id === 2) {
            $rules['start_time'] = 'nullable|date_format:H:i';
            $rules['end_time'] = 'nullable|date_format:H:i|after:start_time';
        }

        if ((int)$this->type_task_id === 3) {
            if ($this->manualInterval) {
                $rules['start_time'] = 'nullable|date_format:H:i';
                $rules['end_time'] = 'nullable|date_format:H:i|after:start_time';
            }
            if ($this->duration) {
                $rules['duration'] = [
                    'required',
                    'regex:/^((\d{1,2}h\s?)?(\d{1,2}min)?)$/'
                ];
            }
            $rules['related_task_id'] = 'required|exists:tasks,id';
        }

        $this->validate($rules);

        $durationMinutes = 30;
        if ($this->duration) {
            $durationMinutes = 0;
            if (preg_match('/(\d{1,2})h/', $this->duration, $hours)) {
                $durationMinutes += ((int)$hours[1]) * 60;
            }
            if (preg_match('/(\d{1,2})min/', $this->duration, $mins)) {
                $durationMinutes += (int)$mins[1];
            }
        }

        $preference = UserPreference::where('user_id', Auth::id())->first();

        if (!$this->start_time && !$this->end_time) {
            [$start, $end] = $this->getSuggestedTimeSlot($preference, $durationMinutes, $this->preferred_time_block);
            $this->start_time = $start;
            $this->end_time = $end;
        }

        $startDateTime = $this->start_time ? $this->date . ' ' . $this->start_time . ':00' : null;
        $endDateTime = $this->end_time ? $this->date . ' ' . $this->end_time . ':00' : null;

        Task::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'type_task_id' => $this->type_task_id,
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'related_task_id' => $this->related_task_id,
            'completed' => false,
        ]);

        $this->reset([
            'title',
            'description',
            'type_task_id',
            'start_time',
            'end_time',
            'addDuration',
            'manualInterval',
            'related_task_id',
            'duration',
            'preferred_time_block',
            'showForm'
        ]);
    }

    private function getSuggestedTimeSlot($preference, $durationMinutes = 30, $preferredBlock = null)
    {
        $busy = collect();
        $parse = fn($time) => Carbon::parse($time);

        if ($preference) {
            if ($preference->sleep_time && $preference->wake_time) {
                $busy->push([
                    'start' => $parse($preference->sleep_time)->format('H:i'),
                    'end' => $parse($preference->wake_time)->format('H:i')
                ]);
            }
            foreach (['breakfast_time', 'lunch_time', 'dinner_time'] as $meal) {
                if ($preference->$meal) {
                    $start = $parse($preference->$meal);
                    $busy->push([
                        'start' => $start->format('H:i'),
                        'end' => $start->copy()->addMinutes(30)->format('H:i')
                    ]);
                }
            }
            if ($preference->study_time_start && $preference->study_time_end) {
                $busy->push([
                    'start' => $parse($preference->study_time_start)->format('H:i'),
                    'end' => $parse($preference->study_time_end)->format('H:i')
                ]);
            }
        }

        $userTasks = Task::where('user_id', Auth::id())
            ->whereDate('start_date', $this->date)
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get();

        foreach ($userTasks as $task) {
            $busy->push([
                'start' => Carbon::parse($task->start_date)->format('H:i'),
                'end' => Carbon::parse($task->end_date)->format('H:i')
            ]);
        }

        $day = Carbon::parse($this->date)->startOfDay();

        $startWindow = $preference && $preference->wake_time ? $parse($preference->wake_time)->copy() : $day->copy()->addHours(7);
        $endWindow = $preference && $preference->sleep_time ? $parse($preference->sleep_time)->copy() : $day->copy()->addHours(22);

        $totalMinutes = $startWindow->diffInMinutes($endWindow);
        $blockSize = (int)($totalMinutes / 3);

        dd($totalMinutes);

        $blocks = [
            'morning' => [$startWindow->copy(), $startWindow->copy()->addMinutes($blockSize)],
            'afternoon' => [$startWindow->copy()->addMinutes($blockSize), $startWindow->copy()->addMinutes($blockSize * 2)],
            'evening' => [$startWindow->copy()->addMinutes($blockSize * 2), $endWindow]
        ];

        [$blockStart, $blockEnd] = $blocks[$preferredBlock] ?? [$startWindow, $endWindow];
        $slot = $blockStart->copy();

        while ($slot->copy()->addMinutes($durationMinutes)->lte($blockEnd)) {
            $slotEnd = $slot->copy()->addMinutes($durationMinutes);
            $overlaps = $busy->filter(function ($block) use ($slot, $slotEnd, $day) {
                $start = $day->copy()->setTimeFromTimeString($block['start']);
                $end = $day->copy()->setTimeFromTimeString($block['end']);
                return $start->lt($slotEnd) && $end->gt($slot);
            });

            if ($overlaps->isEmpty()) {
                return [$slot->format('H:i'), $slotEnd->format('H:i')];
            }

            $slot->addMinutes(15);
        }

        return ['09:00', Carbon::createFromTimeString('09:00')->addMinutes($durationMinutes)->format('H:i')];
    }


    public function delete($taskId)
    {
        $task = Task::where('id', $taskId)
            ->where('user_id', Auth::id())
            ->first();

        if ($task) {
            $task->delete();
        }
    }

    public function render()
    {
        $tasks = Task::with('type')
            ->where('user_id', Auth::id())
            ->whereDate('start_date', $this->date)
            ->where('completed', false)
            ->get();

        $types = TaskType::all();
        $userTasks = Task::where('user_id', Auth::id())
            ->where('completed', false)
            ->get();

        return view('livewire.tasklist', compact('tasks', 'types', 'userTasks'));
    }
}
