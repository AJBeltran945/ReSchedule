<div class="p-4 m-4 bg-yellow-300 shadow-md rounded-lg flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <button wire:click="goToPreviousWeek" class="px-4 py-2 bg-gray-200 rounded">Prev</button>
        <h2 class="text-xl font-bold">{{ $weekLabel }}</h2>
        <button wire:click="goToNextWeek" class="px-4 py-2 bg-gray-200 rounded">Next</button>
    </div>

    <!-- Weekdays -->
    <div class="grid grid-cols-7 gap-2 text-center font-bold mb-2">
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
        <div>Sun</div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-2 text-center flex-grow h-[650px]">
        @foreach($calendar as $dayData)
        <div wire:click="showDayModal('{{ $dayData['date']->toDateString() }}')"
            class="border rounded-lg p-2 text-left h-full flex flex-col cursor-pointer
                       {{ $dayData['isToday'] ? 'bg-blue-100 ring-2 ring-blue-400' : 'bg-white' }}">
            <div class="font-semibold">{{ $dayData['date']->format('d') }}</div>

            <!-- All tasks preview (no limit) -->
            <div class="mt-2 text-xs flex-grow space-y-1 overflow-auto">
                @foreach($dayData['tasks'] as $task)
                <div class="truncate {{ $task->completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                    • {{ $task->title }}
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl relative">
            <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>
            <h2 class="text-xl font-bold mb-4">Tasks for {{ $selectedDay->format('l, F j, Y') }}</h2>
            <livewire:task-list :date="$selectedDay->toDateString()" />
        </div>
    </div>
    @endif
</div>
