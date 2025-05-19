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

        // 1. Collect busy periods in minutes since midnight
        if ($preference) {
            if ($preference->sleep_time && $preference->wake_time) {
                $busy->push([
                    'start' => $parse($preference->sleep_time)->hour * 60,
                    'end' => $parse($preference->wake_time)->hour * 60
                ]);
            }

            foreach (['breakfast_time', 'lunch_time', 'dinner_time'] as $meal) {
                if ($preference->$meal) {
                    $start = $parse($preference->$meal)->hour * 60 + $parse($preference->$meal)->minute;
                    $busy->push([
                        'start' => $start,
                        'end' => $start + 30
                    ]);
                }
            }

            if ($preference->study_time_start && $preference->study_time_end) {
                $start = $parse($preference->study_time_start)->hour * 60 + $parse($preference->study_time_start)->minute;
                $end = $parse($preference->study_time_end)->hour * 60 + $parse($preference->study_time_end)->minute;
                $busy->push([
                    'start' => $start,
                    'end' => $end
                ]);
            }
        }

        // 2. Include user tasks in busy time
        $userTasks = Task::where('user_id', Auth::id())
            ->whereDate('start_date', $this->date)
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get();

        foreach ($userTasks as $task) {
            $start = Carbon::parse($task->start_date);
            $end = Carbon::parse($task->end_date);
            $busy->push([
                'start' => $start->hour * 60 + $start->minute,
                'end' => $end->hour * 60 + $end->minute
            ]);
        }

        // 3. Define the working window (start to end) in minutes
        $startHour = $preference && $preference->wake_time
            ? $parse($preference->wake_time)->hour
            : 7;

        $endHour = $preference && $preference->sleep_time
            ? $parse($preference->sleep_time)->hour
            : 22;

        $startMinutes = $startHour === 24 ? 0 : $startHour * 60;
        $endMinutes = $endHour === 24 ? 1440 : $endHour * 60;

        if ($endMinutes <= $startMinutes) {
            $endMinutes += 1440; // Wrap to next day
        }

        $totalMinutes = $endMinutes - $startMinutes;
        $blockSize = (int)($totalMinutes / 3);

        $blocks = [
            'morning' => [$startMinutes, $startMinutes + $blockSize],
            'afternoon' => [$startMinutes + $blockSize, $startMinutes + $blockSize * 2],
            'evening' => [$startMinutes + $blockSize * 2, $endMinutes]
        ];

        [$blockStart, $blockEnd] = $blocks[$preferredBlock] ?? [$startMinutes, $endMinutes];
        $slot = $blockStart;

        while ($slot + $durationMinutes <= $blockEnd) {
            $slotEnd = $slot + $durationMinutes;

            $overlaps = $busy->filter(function ($block) use ($slot, $slotEnd) {
                return $block['start'] < $slotEnd && $block['end'] > $slot;
            });

            if ($overlaps->isEmpty()) {
                return [
                    Carbon::today()->addMinutes($slot)->format('H:i'),
                    Carbon::today()->addMinutes($slotEnd)->format('H:i')
                ];
            }

            $slot += 15;
        }

        // Fallback if no available slot
        // return [
        //     '09:00',
        //     Carbon::createFromTimeString('09:00')->addMinutes($durationMinutes)->format('H:i')
        // ];
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
