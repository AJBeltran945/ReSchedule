<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ReSchedule</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        midnight: '#280137',
                        'midnight-dark': '#1c012a',
                        royal: '#FADA5E'
                    }
                }
            }
        }
    </script>
</head>

<body class="antialiased bg-midnight text-white">
    <header class="fixed top-0 left-0 w-full z-50 bg-midnight-dark text-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ url('/') }}"
                    class="text-2xl font-bold transform transition-transform duration-300 hover:scale-110 hover:text-royal">
                    ReSchedule
                </a>

                <!-- Language + Auth -->
                <div class="w-42 flex items-center gap-4">
                    <!-- Language Switcher -->
                    <nav>
                        <ul class="flex space-x-4">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    class="transform transition-transform duration-300 hover:scale-110 hover:text-royal uppercase">
                                    {{ $localeCode }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-midnight">
        <section class="relative pt-32 pb-20 px-6 sm:px-12 overflow-hidden">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <!-- Text Content -->
                <div class="space-y-6">
                    <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight">
                        Organize your time <span class="text-royal">in an intelligent way</span>
                    </h1>
                    <p class="text-lg text-white text-opacity-80 max-w-lg">
                        ReSchedule helps you plan your tasks according to your availability and priorities. Automate your productivity.
                    </p>
                    <div class="flex space-x-4">
                        @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                            <a href="{{ url('/home/month') }}"
                                class="border border-white hover:bg-royal hover:text-midnight px-6 py-3 rounded-xl text-lg font-semibold transform hover:scale-105 transition-all duration-300">
                                Dashboard
                            </a>
                            @else
                            <a href="{{ route('login') }}"
                                class="border border-white hover:bg-royal hover:text-midnight px-6 py-3 rounded-xl text-lg font-semibold transform hover:scale-105 transition-all duration-300">
                                Log in
                            </a>
                            @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="bg-royal hover:bg-yellow-400 text-midnight px-6 py-3 rounded-xl text-lg font-semibold transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg">
                                Register
                            </a>
                            @endif
                            @endauth
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative w-full h-72 sm:h-96 lg:h-[500px]">
                    <img src="{{ asset('images/Logo_noBG.png') }}" alt="Hero Image"
                        class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
                </div>
            </div>
        </section>
    </div>
</body>

</html>
