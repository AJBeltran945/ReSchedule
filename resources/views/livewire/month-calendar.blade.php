<div class="p-4 m-4 bg-yellow-300 shadow-md rounded-lg flex flex-col">
    <div class="flex justify-between items-center mb-4">
        <button wire:click="goToPreviousMonth" class="px-4 py-2 bg-gray-200 rounded">Prev</button>
        <h2 class="text-xl font-bold">{{ $currentMonthName }} {{ $year }}</h2>
        <button wire:click="goToNextMonth" class="px-4 py-2 bg-gray-200 rounded">Next</button>
    </div>

    <div class="grid grid-cols-7 gap-2 text-center font-bold mb-2">
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
        <div>Sun</div>
    </div>

    <!-- Full-month grid: 6 weeks x 7 days = 42 days -->
    <div class="grid grid-cols-7 gap-2 text-center flex-grow h-[650px]">
        @foreach($calendar as $dayData)
        <div wire:click="showDayModal('{{ $dayData['day']->toDateString() }}')"
            class="border rounded-lg p-2 text-left h-full flex flex-col cursor-pointer
                {{ !$dayData['isCurrentMonth'] ? 'text-gray-400' : '' }}
                {{ $dayData['isToday'] ? 'bg-blue-100 border-blue-500 ring-2 ring-blue-400' : 'bg-white' }}">
            <div class="font-semibold">{{ $dayData['day']->day }}</div>
            <div class="text-sm mt-2 text-gray-600 flex-grow"></div>
        </div>
        @endforeach
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg relative">
            <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>

            {{-- Include another blade view --}}
            @include('frontend.tasks.index', ['day' => $selectedDay])
        </div>
    </div>
    @endif
</div>
