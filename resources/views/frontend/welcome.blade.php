<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="antialiased">
    <header class="fixed top-0 left-0 w-full z-50 bg-gray-800 text-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ url('/') }}"
                    class="text-2xl font-bold transform transition-transform duration-300 hover:scale-110 hover:text-blue-400">
                    AJ
                </a>

                <!-- Right Side: Language + Auth -->
                <div class="w-42 flex items-center gap-4">

                    <!-- Language Switcher -->
                    <nav>
                        <ul class="flex">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    class="transform transition-transform duration-300 hover:scale-110 hover:text-blue-400 uppercase">
                                    {{ $localeCode }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </nav>

                    <!-- Auth Links -->
                    @if (Route::has('login'))
                    <div class="flex items-center space-x-4">
                        @auth
                        <a href="{{ url('/home') }}"
                            class="font-semibold text-gray-300 hover:text-white">
                            Dashboard
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                            class="font-semibold text-gray-300 hover:text-white">
                            Log in
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="font-semibold text-gray-300 hover:text-white">
                            Register
                        </a>
                        @endif
                        @endauth
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </header>


    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">

        <div class="max-w-7xl mx-auto p-6 lg:p-8">

            <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                <div class="text-center text-sm sm:text-left">
                    &nbsp;
                </div>

                <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </div>
        </div>
    </div>
</body>

</html>
