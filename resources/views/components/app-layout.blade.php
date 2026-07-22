<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Museum MK Lesmana') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <script>
            window.addEventListener('pageshow', function (event) {
                if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                    window.location.reload();
                }
            });
        </script>
        <div class="min-h-screen flex flex-col bg-gray-100">
            @guest
                @include('layouts.navigation_public')
            @else
                @include('layouts.navigation')
            @endguest

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 flex flex-col justify-start">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
