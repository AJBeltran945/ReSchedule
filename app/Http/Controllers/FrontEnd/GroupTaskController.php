<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class GroupTaskController extends Controller
{
    public function index()
    {
        $groupTasks = Task::whereHas('users', function ($q) {
            $q->where('users.id', auth()->id());
        })->get();

        return view('group-tasks.index', compact('groupTasks'));
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load('users', 'comments.user');
        return view('group-tasks.show', compact('task'));
    }

    public function addMember(Request $request, Task $task)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $task->users()->attach($user->id);
        return back()->with('success', 'User added to group task.');
    }
}
