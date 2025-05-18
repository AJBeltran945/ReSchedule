<div class="h-[650px] overflow-y-auto">
    @forelse($tasks as $task)
    <div class="border p-2 mb-2 rounded shadow-sm">
        <div class="font-semibold">{{ $task->title }}</div>
        <div class="text-sm text-gray-600">{{ $task->type->name }}</div>
    </div>
    @empty
    <p class="text-sm text-gray-500">No tasks found for this day.</p>
    @endforelse
</div>
