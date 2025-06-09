<div class="h-[650px] overflow-y-auto p-4 text-white">

    {{-- Toggle Button --}}
    <button wire:click="toggleForm" class="mb-4 px-4 py-2 bg-royal text-midnight font-semibold rounded hover:bg-yellow-400 transition">
        {{ $showForm ? 'Cancel' : 'New Task' }}
    </button>

    {{-- Dynamic Form --}}
    @if($showForm)
    <div class="mb-6 space-y-4 bg-midnight p-4 rounded shadow text-white">

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium">Title</label>
            <input type="text" wire:model="title" class="mt-1 block w-full bg-gray-900 text-white border border-gray-600 rounded p-2 focus:ring-royal focus:border-royal" />
            @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea wire:model="description" class="mt-1 block w-full bg-gray-900 text-white border border-gray-600 rounded p-2 focus:ring-royal focus:border-royal"></textarea>
            @error('description') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Task Type + Preferred Time --}}
        <div class="flex flex-col sm:flex-row sm:gap-4">
            <div class="{{ (int)$type_task_id === 2 ? 'w-full' : 'w-full sm:w-1/2' }}">
                <label class="block text-sm font-medium">Task Type</label>
                <select wire:model="type_task_id" wire:change="onTypeChange"
                        class="mt-1 block w-full bg-gray-900 text-white border border-gray-600 rounded p-2 focus:ring-royal focus:border-royal">
                    <option value="">-- Select Type --</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('type_task_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            @if((int)$type_task_id !== 2)
                <div class="w-full sm:w-1/2">
                    <label class="block text-sm font-medium">Preferred Time of Day</label>
                    <select wire:model="preferred_time_block"
                            class="mt-1 block w-full bg-gray-900 text-white border border-gray-600 rounded p-2 focus:ring-royal focus:border-royal">
                        <option value="">-- Select Time Block --</option>
                        <option value="morning">Morning</option>
                        <option value="afternoon">Afternoon</option>
                        <option value="evening">Evening</option>
                    </select>
                </div>
            @endif
        </div>



        {{-- Checkboxes --}}
        @if((int) $type_task_id === 1 || (int) $type_task_id === 3)
        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6">
            <label class="inline-flex items-center mt-2">
                <input type="checkbox" wire:change="onManualIntervalChange($event.target.checked)"
                    class="form-checkbox h-4 w-4 text-royal bg-gray-900 border-gray-600">
                <span class="ml-2 text-sm">Add Time interval</span>
            </label>

            <label class="inline-flex items-center mt-2">
                <input type="checkbox" wire:change="onAddDurationChange($event.target.checked)"
                    class="form-checkbox h-4 w-4 text-royal bg-gray-900 border-gray-600">
                <span class="ml-2 text-sm">Add Duration</span>
            </label>
        </div>
        @endif

        {{-- Time Interval --}}
        @if((int) $type_task_id === 2 || ((int) $type_task_id === 1 && $manualInterval) || ((int) $type_task_id === 3 && $manualInterval))
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Start Time</label>
                <input type="time" wire:model="start_time" class="mt-1 block w-full bg-gray-900 text-white border border-gray-600 rounded p-2 focus:ring-royal focus:border-royal" />
                @error('start_time') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">End Time</label>
                <input type="time" wire:model="end_time" class="mt-1 block w-full bg-gray-900 text-white border border-gray-600 rounded p-2 focus:ring-royal focus:border-royal" />
                @error('end_time') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif

        {{-- Duration --}}
        @if(((int) $type_task_id === 1 && $addDuration) || ((int) $type_task_id === 3 && $addDuration))
        <div>
            <label class="block text-sm font-medium">Duration</label>
            <input type="text" wire:model="duration" placeholder="e.g. 2h 30min, 1h, 45min"
                class="mt-1 block w-full bg-gray-900 text-white border border-gray-600 rounded p-2 focus:ring-royal focus:border-royal" />
            @error('duration') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>
        @endif

        {{-- Related Task --}}
        @if((int) $type_task_id === 3)
        <div>
            <label class="block text-sm font-medium">Related Task</label>
            <select wire:model="related_task_id"
                class="mt-1 block w-full bg-gray-900 text-white border border-gray-600 rounded p-2 focus:ring-royal focus:border-royal">
                <option value="">-- Select Related Task --</option>
                @foreach($userTasks as $task)
                <option value="{{ $task->id }}">{{ $task->title }}</option>
                @endforeach
            </select>
            @error('related_task_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>
        @endif

        {{-- Submit --}}
        <form wire:submit.prevent="{{ $editingTaskId ? 'update' : 'save' }}">
            <button type="submit"
                    class="mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
            >
                {{ $editingTaskId ? 'Update Task' : 'Save Task' }}
            </button>
        </form>
    </div>
    @endif

    @forelse($tasks as $task)
        <div
            class="p-4 mb-3 rounded-lg shadow-lg flex justify-between items-center text-sm"
            style="background-color: {{ $task->completed ? '#6b7280' : $task->priority->color }}">

            <!-- Left: Task Title + Type -->
            <div class="flex-1">
                <div class="text-base font-extrabold text-white leading-tight {{ $task->completed ? 'opacity-70 line-through' : '' }}">
                    {{ $task->title }}
                </div>

                @unless($task->completed)
                    <div class="text-xs text-white/90 font-medium tracking-wide">
                        @if(isset($task->relatedTask))
                            <span class="text-white">Connected to: {{ $task->relatedTask->title }}</span>
                        @else
                            {{ $task->type->name }}
                        @endif
                    </div>
                @endunless
            </div>

            <!-- Center: Time Block -->
            @unless($task->completed)
                <div class="text-xs text-white/80 text-right mx-4 min-w-[80px]">
                    @if($task->start_date && $task->end_date)
                        {{ \Carbon\Carbon::parse($task->start_date)->format('H:i') }}
                        →
                        {{ \Carbon\Carbon::parse($task->end_date)->format('H:i') }}
                    @endif
                </div>
            @endunless

            <!-- Right: Actions -->
            <div class="flex items-center gap-2">
                @if($task->completed)
                    <!-- Un-complete -->
                    <button
                        wire:click.stop="toggleComplete({{ $task->id }})"
                        title="Mark as not completed"
                        class="text-white hover:text-yellow-400"
                    >
                        <x-heroicon-o-arrow-uturn-left class="w-5 h-5" />
                    </button>
                @else
                    <!-- Complete -->
                    <button
                        wire:click.stop="toggleComplete({{ $task->id }})"
                        title="Mark as completed"
                        class="text-white hover:text-green-400"
                    >
                        <x-heroicon-o-check class="w-5 h-5" />
                    </button>

                    <!-- Edit -->
                    <button
                        wire:click.stop="edit({{ $task->id }})"
                        title="Edit task"
                        class="text-white hover:text-blue-400"
                    >
                        <x-heroicon-o-pencil class="w-5 h-5" />
                    </button>
                @endif

                <!-- Delete (always available) -->
                <button
                    wire:click.stop="delete({{ $task->id }})"
                    onclick="return confirm('Are you sure you want to delete this task?')"
                    title="Delete task"
                    class="text-white hover:text-red-500"
                >
                    <x-heroicon-o-trash class="w-5 h-5" />
                </button>
            </div>
        </div>
    @empty
        <p class="text-gray-400 text-sm">No tasks for this day.</p>
    @endforelse
</div>
