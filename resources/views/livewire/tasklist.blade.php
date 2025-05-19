<div class="h-[650px] overflow-y-auto">
    {{-- Toggle Button --}}
    <button wire:click="toggleForm" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        {{ $showForm ? 'Cancel' : 'New Task' }}
    </button>

    {{-- Dynamic Form --}}
    @if($showForm)
    <div class="mb-6 space-y-3 bg-white p-4 rounded shadow">
        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" wire:model="title" class="mt-1 block w-full border rounded p-2" />
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea wire:model="description" class="mt-1 block w-full border rounded p-2"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Task Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Task Type</label>
            <select wire:model="type_task_id" wire:change="onTypeChange" class="mt-1 block w-full border rounded p-2">
                <option value="">-- Select Type --</option>
                @foreach($types as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            @error('type_task_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Optional Duration (Type 1 or 3) --}}
        @if((int) $type_task_id === 1 || (int) $type_task_id === 3)
        <div>
            <label class="inline-flex items-center mt-2">
                <input
                    type="checkbox"
                    wire:change="onAddDurationChange($event.target.checked)"
                    class="form-checkbox h-4 w-4 text-blue-600"
                    @checked($addDuration) />
                <span class="ml-2 text-sm text-gray-700">Add Duration</span>
            </label>
        </div>
        @endif

        {{-- Duration Field --}}
        @if((int) $type_task_id === 2 || ((int) $type_task_id === 1 && $addDuration) || ((int) $type_task_id === 3 && $addDuration))
        <div>
            <label class="block text-sm font-medium text-gray-700">Time Dedication</label>
            <input type="text" wire:model="duration" placeholder="e.g. 2h or 30m" class="mt-1 block w-full border rounded p-2" />
            @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        @endif

        {{-- Related Task (Type 3 only) --}}
        @if((int) $type_task_id === 3)
        <div>
            <label class="block text-sm font-medium text-gray-700">Related Task</label>
            <select wire:model="related_task_id" class="mt-1 block w-full border rounded p-2">
                <option value="">-- Select Related Task --</option>
                @foreach($userTasks as $task)
                <option value="{{ $task->id }}">{{ $task->title }}</option>
                @endforeach
            </select>
            @error('related_task_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        @endif

        {{-- Submit Button --}}
        <form wire:submit.prevent="save">
            <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Save Task
            </button>
        </form>
    </div>
    @endif

    {{-- Task list --}}
    @forelse($tasks as $task)
    <div class="border p-2 mb-2 rounded shadow-sm">
        <div class="font-semibold">{{ $task->title }}</div>
        <div class="text-sm text-gray-600">{{ $task->type->name }}</div>
    </div>
    @empty
    <p class="text-sm text-gray-500">No tasks found for this day.</p>
    @endforelse
</div>
