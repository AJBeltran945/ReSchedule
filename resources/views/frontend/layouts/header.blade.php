<header x-data="{ open: false }" class="bg-midnight-dark text-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Left: Logo -->
            <div class="flex items-center flex-shrink-0">
                <a href="{{ route('welcome') }}" class="text-2xl font-bold hover:text-royal transition">
                    ReSchedule
                </a>
            </div>

            <!-- Center: Navigation Links -->
            <div class="hidden lg:flex justify-center flex-1">
                <nav class="flex items-center space-x-6 text-sm font-medium">
                    <x-nav-link :href="route('home.month')" :active="request()->routeIs('home.month')" class="hover:text-royal">
                        {{ __('Month View') }}
                    </x-nav-link>
                    <x-nav-link :href="route('home.week')" :active="request()->routeIs('home.week')" class="hover:text-royal">
                        {{ __('Week View') }}
                    </x-nav-link>
                </nav>
            </div>

            <!-- Right: Notifications and Dropdown -->
            <div class="flex items-center space-x-4">
                <livewire:notifications-menu />

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white hover:text-royal focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ml-2 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="hover:text-royal">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();" class="hover:text-royal">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>
</header>
