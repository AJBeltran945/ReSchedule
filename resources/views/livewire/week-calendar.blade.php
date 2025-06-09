<div class="h-screen p-4 m-4 sm:p-6 sm:m-6 bg-midnight-dark text-white shadow-md rounded-lg flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <button wire:click="goToPreviousWeek"
                class="px-3 py-1 sm:px-4 sm:py-2 bg-royal text-midnight font-semibold rounded hover:bg-yellow-400 transition text-sm sm:text-base">
            Prev
        </button>

        <h2 class="text-lg sm:text-xl font-bold text-white">{{ $weekLabel }}</h2>

        <button wire:click="goToNextWeek"
                class="px-3 py-1 sm:px-4 sm:py-2 bg-royal text-midnight font-semibold rounded hover:bg-yellow-400 transition text-sm sm:text-base">
            Next
        </button>
    </div>

    <!-- Weekdays (visible on desktop only) -->
    <div class="hidden lg:grid grid-cols-7 gap-2 text-center font-bold mb-2 text-royal text-sm md:text-base">
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
        <div>Sun</div>
    </div>

    <!-- Calendar Grid: single column on phones/tablets, 7 columns on desktop -->
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-2 text-center flex-1 overflow-auto">
        @foreach($calendar as $dayData)
            <div wire:click="showDayModal('{{ $dayData['date']->toDateString() }}')"
                 class="border rounded-lg p-2 sm:p-3 text-left flex flex-col cursor-pointer transition
                       {{ $dayData['isToday']
                           ? 'bg-midnight border-royal ring-2 ring-royal'
                           : 'bg-gray-900 border-gray-700 hover:border-royal'
                       }} h-auto lg:h-[650px]">

                <!-- Day Number -->
                <div class="font-semibold text-base sm:text-lg flex-shrink-0">
                    {{ $dayData['date']->format('d') }}
                </div>

                <!-- Task List -->
                <div class="mt-2 text-xs sm:text-sm flex flex-wrap gap-2 items-start overflow-auto">
                    @foreach($dayData['tasks'] as $task)
                        <span
                            class="px-2 py-0.5 sm:px-3 sm:py-1 rounded-full font-semibold whitespace-nowrap transition"
                            style="background-color: {{ $task->priority->color }}; color: #fff; {{ $task->completed ? 'text-decoration: line-through; opacity: 0.7;' : '' }}">
                            {{ $task->title }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-midnight rounded-lg p-4 sm:p-6 w-full max-w-sm sm:max-w-lg md:max-w-2xl relative text-white shadow-lg">
                <button wire:click="closeModal"
                        class="absolute top-2 right-2 text-gray-400 hover:text-royal text-xl font-bold transition">
                    ✕
                </button>

                <h2 class="text-lg sm:text-xl font-bold mb-4 text-royal">
                    Tasks for {{ $selectedDay->format('l, F j, Y') }}
                </h2>

                <livewire:task-list :date="$selectedDay->toDateString()" />
            </div>
        </div>
    @endif
</div>
