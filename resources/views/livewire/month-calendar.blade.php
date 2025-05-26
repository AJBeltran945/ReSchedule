<div class="p-10 m-10 bg-midnight-dark text-white shadow-md rounded-lg flex flex-col">
    <!-- Month Navigation -->
    <div class="flex justify-between items-center mb-4">
        <button wire:click="goToPreviousMonth"
            class="px-4 py-2 bg-royal text-midnight font-semibold rounded hover:bg-yellow-400 transition">
            Prev
        </button>

        <h2 class="text-xl font-bold text-white">
            {{ $currentMonthName }} {{ $year }}
        </h2>

        <button wire:click="goToNextMonth"
            class="px-4 py-2 bg-royal text-midnight font-semibold rounded hover:bg-yellow-400 transition">
            Next
        </button>
    </div>

    <!-- Weekday Headers -->
    <div class="grid grid-cols-7 gap-2 text-center font-bold mb-2 text-royal">
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
        <div
            wire:click="showDayModal('{{ $dayData['day']->toDateString() }}')"
            class="border rounded-lg p-2 text-left h-full flex flex-col cursor-pointer transition
                       {{ !$dayData['isCurrentMonth'] ? 'text-gray-500' : 'text-white' }}
                       {{ $dayData['isToday'] ? 'bg-midnight border-royal ring-2 ring-royal' : 'bg-gray-900 border-gray-700 hover:border-royal' }}">

            <!-- Day Number -->
            <div class="font-semibold text-lg">
                {{ $dayData['day']->day }}
            </div>

            <!-- Task List -->
            <div class="mt-2 text-xs flex flex-wrap gap-1 items-start overflow-hidden">
                @foreach($dayData['tasks']->take(3) as $task)
                    <span
                        class="px-2 py-0.5 rounded-full text-xs font-semibold leading-tight whitespace-nowrap"
                        style="background-color: {{ $task->priority->color }}; color: #fff; {{ $task->completed ? 'text-decoration: line-through; opacity: 0.7;' : '' }}"
                    >
                        {{ $task->title }}
                    </span>
                @endforeach

                @if($dayData['tasks']->count() > 3)
                <span class="px-2 py-0.5 bg-gray-600 text-white rounded-full text-xs font-semibold whitespace-nowrap">
                    +{{ $dayData['tasks']->count() - 3 }} more
                </span>
                @endif
            </div>

        </div>
        @endforeach
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-midnight rounded-lg p-6 w-full max-w-2xl relative text-white shadow-lg">
            <button wire:click="closeModal"
                class="absolute top-2 right-2 text-gray-400 hover:text-royal text-xl font-bold transition">
                ✕
            </button>

            <h2 class="text-xl font-bold mb-4 text-royal">
                Tasks for {{ $selectedDay->format('l, F j, Y') }}
            </h2>

            {{-- Livewire Component for Task List --}}
            <livewire:task-list :date="$selectedDay->toDateString()" />
        </div>
    </div>
    @endif
</div>
