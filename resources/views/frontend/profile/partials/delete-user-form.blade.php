<section class="py-2">
    <header class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-medium text-gray-900 dark:text-gray-100">
                {{ __('Delete Account') }}
            </h2>
            <p class="text-xs text-gray-600 dark:text-gray-400">
                {{ __('Once deleted, all data is gone forever. Download anything you need first.') }}
            </p>
        </div>
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="text-sm py-1 px-3"
        >
            {{ __('Delete') }}
        </x-danger-button>
    </header>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4 space-y-4">
            @csrf
            @method('delete')

            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ __('Confirm deletion') }}
            </h3>
            <p class="text-xs text-gray-600 dark:text-gray-400">
                {{ __('Enter your password to permanently delete your account.') }}
            </p>

            <div>
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="flex justify-end space-x-2">
                <x-secondary-button x-on:click="$dispatch('close')" size="sm">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button size="sm">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
