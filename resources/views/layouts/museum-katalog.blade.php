<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Katalog Koleksi Museum' }} — Museum MK Lesmana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full bg-gradient-to-b from-slate-50 via-white to-slate-100 font-sans text-slate-800 antialiased">
    @include('katalog-museum.partials.nav')
    <main class="min-h-[calc(100vh-5rem)]">
        @yield('content')
    </main>
    @include('katalog-museum.partials.footer')
</body>
</html>
