<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Daily Routine Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Tell us the times you sleep, eat and study so we can help plan your tasks around them.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        @php
            $fields = [
                'sleep_time'        => 'Sleep Time',
                'wake_time'         => 'Wake Time',
                'breakfast_time'    => 'Breakfast Time',
                'lunch_time'        => 'Lunch Time',
                'dinner_time'       => 'Dinner Time',
                'study_time_start'  => 'Study Start Time',
                'study_time_end'    => 'Study End Time',
            ];
        @endphp

        @foreach($fields as $field => $label)
            <div>
                <x-input-label for="{{ $field }}" :value="__($label)" />
                <x-text-input
                    id="{{ $field }}"
                    name="{{ $field }}"
                    type="time"
                    required
                    value="{{ old($field, optional($preference)->{$field}?->format('H:i')) }}"
                    class="mt-1 block w-full"
                />
                <x-input-error :messages="$errors->get($field)" class="mt-2" />
            </div>
        @endforeach

        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Save Preferences') }}
            </x-primary-button>
        </div>
    </form>
</section>
