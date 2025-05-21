<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <livewire:styles />
</head>

<body class="min-h-screen flex flex-col">
    @include('frontend.layouts.header')

    <!-- Page Wrapper -->
    <div class="bg-blue flex-grow">
        <!-- Page Content -->
        <main class="flex-grow min-h-full">
            @yield('content')
        </main>
    </div>

    @include('frontend.layouts.footer')
    <livewire:scripts />
</body>

</html>
