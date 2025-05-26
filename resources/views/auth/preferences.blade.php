<x-guest-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">Tell us about your routine</h2>
    </x-slot>

    <form method="POST" action="{{ route('preferences.store') }}">
        @csrf

        @foreach ([
        'sleep_time' => 'Sleep Time',
        'wake_time' => 'Wake Time',
        'breakfast_time' => 'Breakfast Time',
        'lunch_time' => 'Lunch Time',
        'dinner_time' => 'Dinner Time',
        'study_time_start' => 'Busy Start Time',
        'study_time_end' => 'Busy End Time',
        ] as $name => $label)
        <div class="mt-4">
            <label class="block text-sm font-medium text-white">{{ $label }}</label>
            <input type="time" name="{{ $name }}" required
                class="mt-1 block w-full bg-midnight text-white border border-gray-600 rounded-lg px-4 py-3 text-lg
                               focus:outline-none focus:ring-2 focus:ring-royal focus:border-royal" />
            @error($name)
            <div class="text-red-400 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        @endforeach

        <div class="mt-6">
            <button type="submit"
                class="bg-royal text-midnight px-6 py-3 rounded-lg font-semibold text-lg hover:bg-yellow-400 hover:text-black transition">
                Save and Continue
            </button>
        </div>
    </form>
</x-guest-layout>
