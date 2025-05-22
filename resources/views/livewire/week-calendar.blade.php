<div class="p-4 m-4 bg-midnight-dark text-white shadow-md rounded-lg flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <button wire:click="goToPreviousWeek"
            class="px-4 py-2 bg-royal text-midnight font-semibold rounded hover:bg-yellow-400 transition">
            Prev
        </button>

        <h2 class="text-xl font-bold text-white">{{ $weekLabel }}</h2>

        <button wire:click="goToNextWeek"
            class="px-4 py-2 bg-royal text-midnight font-semibold rounded hover:bg-yellow-400 transition">
            Next
        </button>
    </div>

    <!-- Weekdays -->
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
        <div wire:click="showDayModal('{{ $dayData['date']->toDateString() }}')"
            class="border rounded-lg p-2 text-left h-full flex flex-col cursor-pointer transition
                       {{ $dayData['isToday'] ? 'bg-midnight border-royal ring-2 ring-royal' : 'bg-gray-900 border-gray-700 hover:border-royal' }}">

            <div class="font-semibold text-lg">{{ $dayData['date']->format('d') }}</div>

            <!-- All Tasks as Tags -->
            <div class="mt-2 text-sm flex-grow flex flex-wrap gap-2 items-start overflow-auto">
                @foreach($dayData['tasks'] as $task)
                <span class="
                    px-3 py-1 rounded-full font-semibold whitespace-nowrap transition
                    {{ $task->completed
                        ? 'bg-gray-700 text-gray-300 line-through'
                        : 'bg-royal text-midnight'
                    }}
                ">
                    {{ $task->title }}
                </span>
                @endforeach
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

            <livewire:task-list :date="$selectedDay->toDateString()" />
        </div>
    </div>
    @endif
</div>
