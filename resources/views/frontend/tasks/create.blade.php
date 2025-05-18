@extends('frontend.layouts.app')

@section('content')
<form method="POST" action="{{ route('task.store') }}" class="space-y-6 max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    @csrf

    <!-- Title -->
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
    </div>

    <!-- Task Type -->
    <div>
        <label for="type_task_id" class="block text-sm font-medium text-gray-700">Task Type</label>
        <select name="type_task_id" id="type_task_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach($taskTypes as $type)
            <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Priority -->
    <div>
        <label for="priority_id" class="block text-sm font-medium text-gray-700">Priority</label>
        <select name="priority_id" id="priority_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach($priorities as $priority)
            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Start Date -->
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <!-- End Date -->
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
        <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <!-- Related Task -->
    <div>
        <label for="related_task_id" class="block text-sm font-medium text-gray-700">Related Task</label>
        <select name="related_task_id" id="related_task_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">None</option>
            @foreach($tasks as $task)
            <option value="{{ $task->id }}">{{ $task->title }}</option>
            @endforeach
        </select>
    </div>

    <!-- Completed -->
    <div class="flex items-center">
        <input type="checkbox" name="completed" id="completed" value="1" class="mr-2">
        <label for="completed" class="text-sm text-gray-700">Mark as completed</label>
    </div>

    <!-- Submit -->
    <div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            Create Task
        </button>
    </div>
</form>
@endsection
