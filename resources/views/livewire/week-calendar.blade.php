<div class="p-4">
    <div class="flex justify-between items-center mb-4">
        <button wire:click="goToPreviousWeek" class="px-4 py-2 bg-gray-200 rounded">Prev</button>
        <h2 class="text-xl font-bold">{{ $weekLabel }}</h2>
        <button wire:click="goToNextWeek" class="px-4 py-2 bg-gray-200 rounded">Next</button>
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
            @php $isToday = $day->isToday(); @endphp
            <div class="border rounded-lg h-32 p-2 text-left {{ $isToday ? 'bg-blue-100 ring-2 ring-blue-400' : 'bg-white' }}">
                <div class="font-semibold">{{ $day->format('d') }}</div>
                <!-- Placeholder for tasks -->
                <div class="text-sm mt-2 text-gray-600">
                    {{-- Task content --}}
                </div>
            </div>
        @endforeach
    </div>
</div>
