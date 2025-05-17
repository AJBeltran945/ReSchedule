<div class="p-4">
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

    <div class="grid grid-cols-7 gap-2 text-center">
        @foreach($calendar as $day)
        <div class="{{ $day->month != $month ? 'text-gray-400' : '' }}">
            {{ $day->day }}
        </div>
        @endforeach
    </div>
</div>
