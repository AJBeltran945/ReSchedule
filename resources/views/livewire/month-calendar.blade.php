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
            @php
                $isToday = $day->isToday(); // assuming $day is a Carbon instance
                $isCurrentMonth = $day->month === $month;
            @endphp
            <div class="border rounded-lg h-24 p-2 text-left relative {{ $isCurrentMonth ? '' : 'text-gray-400' }} {{ $isToday ? 'bg-blue-100 border-blue-500 ring-2 ring-blue-400' : 'bg-white' }}">
                <div class="font-semibold">{{ $day->day }}</div>
                <!-- You can add tasks here in future -->
                <div class="text-sm mt-2 text-gray-600">
                    <!-- Example task placeholder -->
                    <p>Task 1</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
