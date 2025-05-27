<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Daily Routine Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Tell us the times you sleep, eat and study so we can help plan your tasks around them.') }}
        </p>
    </header>

    <form method="POST"
          action="{{ route('profile.update') }}"
          class="grid grid-cols-1 sm:grid-cols-3 gap-6 w-full">
        @csrf
        @method('PUT')

        @foreach($fields as $field => $label)
            <div class="flex flex-col">
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

        {{-- Hidden name/email fields --}}
        <input type="hidden" name="name"  value="{{ old('name',  $user->name)  }}" />
        <input type="hidden" name="email" value="{{ old('email', $user->email) }}" />

        {{-- Button spans all 3 columns --}}
        <div class="col-span-full flex justify-end">
            <x-primary-button>
                {{ __('Save Preferences') }}
            </x-primary-button>
        </div>
    </form>

</section>
