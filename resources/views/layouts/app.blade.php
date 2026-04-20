<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AgriConnect') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes slow-zoom {
                0% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            .animate-slow-zoom { 
                animation: slow-zoom 30s ease-out forwards; 
            }
        </style>
    </head>
    <body class="antialiased bg-black text-gray-200 min-h-screen flex flex-col pt-16">
        <!-- Full Screen Background Image -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
            <img src="{{ asset('theme1.png') }}" alt="Background" class="w-full h-full object-cover opacity-50 animate-slow-zoom">
            <!-- Multi-layer gradient overlay for better readability -->
            <div class="absolute inset-0 bg-gradient-to-br from-black/85 via-emerald-950/70 to-black/85"></div>
            <div class="absolute inset-0 bg-black/50"></div>
            <!-- Vignette effect for focus -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40"></div>
        </div>

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="glass-card-premium mx-4 sm:mx-6 lg:mx-8 mt-6">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow pb-12 w-full">
            {{ $slot }}
        </main>
    </body>
</html>
