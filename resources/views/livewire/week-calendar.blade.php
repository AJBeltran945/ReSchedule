<div class=" p-4 m-4 bg-yellow-300 shadow-md rounded-lg flex flex-col">
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

    <!-- Full-height grid -->
    <div class="grid grid-cols-7 gap-2 text-center flex-grow h-[650px]">
        @foreach($calendar as $day)
            @php $isToday = $day->isToday(); @endphp
            <div class="border rounded-lg p-2 text-left h-full flex flex-col {{ $isToday ? 'bg-blue-100 ring-2 ring-blue-400' : 'bg-white' }}">
                <div class="font-semibold">{{ $day->format('d') }}</div>
                <div class="text-sm mt-2 text-gray-600 flex-grow">
                    {{-- Task content --}}
                </div>
            </div>
        @endforeach
    </div>
</div>
