<x-guest-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tell us about your routine</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4">
        <form method="POST" action="{{ route('preferences.store') }}">
            @csrf

            @foreach ([
                'sleep_time' => 'Sleep Time',
                'wake_time' => 'Wake Time',
                'breakfast_time' => 'Breakfast Time',
                'lunch_time' => 'Lunch Time',
                'dinner_time' => 'Dinner Time',
                'study_time_start' => 'Study Start Time',
                'study_time_end' => 'Study End Time',
            ] as $name => $label)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                    <input type="time" name="{{ $name }}" required class="mt-1 block w-full border rounded px-3 py-2">
                    @error($name)
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div class="mt-6">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save and Continue
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
