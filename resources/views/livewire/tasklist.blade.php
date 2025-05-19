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


        {{-- Task Type + Time of Day (inline) --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:gap-4">
            <div class="w-full sm:w-1/2">
                <label class="block text-sm font-medium text-gray-700">Task Type</label>
                <select wire:model="type_task_id" wire:change="onTypeChange" class="mt-1 block w-full border rounded p-2">
                    <option value="">-- Select Type --</option>
                    @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('type_task_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="w-full sm:w-1/2">
                <label class="block text-sm font-medium text-gray-700">Preferred Time of Day</label>
                <select wire:model="preferred_time_block" class="mt-1 block w-full border rounded p-2">
                    <option value="">-- Select Time Block --</option>
                    <option value="morning">Morning</option>
                    <option value="afternoon">Afternoon</option>
                    <option value="evening">Evening</option>
                </select>
            </div>
        </div>

        {{-- Manual Interval + Duration Checkboxes (inline) --}}
        @if((int) $type_task_id === 1 || (int) $type_task_id === 3)
        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6">
            <label class="inline-flex items-center mt-2">
                <input
                    type="checkbox"
                    wire:change="onManualIntervalChange($event.target.checked)"
                    class="form-checkbox h-4 w-4 text-blue-600"
                    @checked($addDuration) />
                <span class="ml-2 text-sm text-gray-700">Add Time interval</span>
            </label>

            <label class="inline-flex items-center mt-2">
                <input
                    type="checkbox"
                    wire:change="onAddDurationChange($event.target.checked)"
                    class="form-checkbox h-4 w-4 text-blue-600" />
                <span class="ml-2 text-sm text-gray-700">Add Duration</span>
            </label>
        </div>
        @endif

        {{-- Time Interval --}}
        @if((int) $type_task_id === 2 || ((int) $type_task_id === 1 && $manualInterval) || ((int) $type_task_id === 3 && $manualInterval))
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="time" wire:model="start_time" class="mt-1 block w-full border rounded p-2" />
                @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="time" wire:model="end_time" class="mt-1 block w-full border rounded p-2" />
                @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif

        @if(((int) $type_task_id === 1 && $addDuration) || ((int) $type_task_id === 3 && $addDuration))
        <div>
            <label class="block text-sm font-medium text-gray-700">Duration</label>
            <input
                type="text"
                wire:model="duration"
                placeholder="e.g. 2h 30min, 1h, 45min"
                class="mt-1 block w-full border rounded p-2" />
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
    <div class="border p-2 mb-2 rounded shadow-sm flex justify-between items-center">
        <div>
            <div class="font-semibold">{{ $task->title }}</div>
            <div class="text-sm text-gray-600">{{ $task->type->name }}</div>
        </div>
        <button
            wire:click="delete({{ $task->id }})"
            class="text-red-600 hover:text-red-800 text-sm ml-4"
            onclick="return confirm('Are you sure you want to delete this task?')">
            Delete
        </button>
    </div>
    @empty
    <p class="text-sm text-gray-500">No tasks found for this day.</p>
    @endforelse

</div>
