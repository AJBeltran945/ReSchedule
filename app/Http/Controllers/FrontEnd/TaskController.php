<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\Priority;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create()
    {
        return view('frontend.tasks.create', [
            'taskTypes' => TaskType::all(),
            'priorities' => Priority::all(),
            'tasks' => Task::all(),
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_task_id' => 'required|exists:task_types,id',
            'priority_id' => 'required|exists:priorities,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'related_task_id' => 'nullable|exists:tasks,id',
            'completed' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id(); // associate with current user
        $validated['completed'] = $request->has('completed'); // checkbox value

        Task::create($validated);

        return redirect()->route('frontend.tasks.create')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $types = TaskType::all();
        $priorities = Priority::all();
        return view('tasks.edit', compact('task', 'types', 'priorities'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_task_id' => 'required|exists:task_types,id',
            'priority_id' => 'required|exists:priorities,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'related_task_id' => 'nullable|exists:tasks,id',
        ]);

        $task->update($data);
        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return back()->with('success', 'Task deleted.');
    }
}
