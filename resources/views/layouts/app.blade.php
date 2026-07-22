<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Museum MK Lesmana') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="font-sans antialiased bg-gray-100 overflow-x-hidden">

<div
    x-data="{ sidebarOpen: false }"
    class="min-h-screen bg-gray-100"
>

    <!-- HEADER -->
    <header class="fixed top-0 left-0 w-full z-50">
        @guest
            @include('layouts.navigation_public')
        @else
            @include('layouts.navigation')
        @endguest
    </header>

    <!-- SIDEBAR -->
    @if(auth()->check() && strtolower(auth()->user()->role) === 'pengelola')
        @include('layouts.sidebar')
    @endif

    <!-- MAIN CONTENT -->
<!-- MAIN CONTENT -->
    <main
        class="pt-16 flex-1 flex flex-col transition-all duration-300 ease-in-out"
        :class="sidebarOpen ? 'ml-64' : 'ml-0'"
    >
        <div class="w-full">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </div>
    </main>

</div>
@stack('scripts')
</body>
</html>