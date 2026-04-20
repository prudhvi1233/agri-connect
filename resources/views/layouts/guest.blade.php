<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
            }
            @keyframes float-delayed {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-15px) rotate(-5deg); }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
                50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6); }
            }
            @keyframes slow-zoom {
                0% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(40px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-float-delayed { animation: float-delayed 8s ease-in-out infinite; }
            .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
            .animate-slow-zoom { animation: slow-zoom 20s ease-out forwards; }
            .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            
            .glass-card {
                background: rgba(15, 20, 25, 0.65);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.15);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
                border-top: 1px solid rgba(255, 255, 255, 0.25);
                border-left: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="antialiased min-h-screen relative overflow-x-hidden overflow-y-auto bg-black">
        
        <!-- Full Screen Background Image -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <img src="{{ asset('login.png') }}" alt="Background" class="w-full h-full object-cover opacity-60 animate-slow-zoom">
            <!-- Overlay to make form readable -->
            <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-emerald-900/40 to-black/80 mix-blend-overlay"></div>
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <!-- Animated Floating Orbs in Background -->
        <div class="fixed z-0 top-1/4 left-1/4 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl animate-float pointer-events-none"></div>
        <div class="fixed z-0 bottom-1/4 right-1/4 w-80 h-80 bg-green-400/20 rounded-full blur-3xl animate-float-delayed pointer-events-none"></div>
        <div class="fixed z-0 top-1/2 right-1/3 w-40 h-40 bg-teal-300/20 rounded-full blur-3xl animate-float pointer-events-none" style="animation-delay: 2s;"></div>

        <!-- Main Content -->
        <div class="relative z-10 w-full max-w-md mx-auto px-6 py-12 min-h-screen flex flex-col justify-center">
            
            <!-- Branding Header -->
            <div class="mb-8 text-center animate-fade-in-up" style="opacity: 0;">
                <a href="/" class="inline-flex items-center justify-center group flex-col">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg animate-pulse-glow">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="mt-4 text-3xl font-bold text-white tracking-wide drop-shadow-md">AgriConnect</h1>
                    <p class="text-emerald-300 mt-1 text-xs font-semibold tracking-[0.2em] drop-shadow uppercase">Empowering Farmers</p>
                </a>
            </div>

            <!-- The Form Card -->
            <div class="w-full glass-card rounded-3xl p-8 relative overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-emerald-900/60 transform hover:-translate-y-1 animate-fade-in-up" style="opacity: 0; animation-delay: 150ms;">
                <!-- Glossy reflection overlay -->
                <div class="absolute top-0 inset-x-0 h-1/2 bg-gradient-to-b from-white/5 to-transparent pointer-events-none"></div>
                
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center text-white/50 text-xs">
                &copy; {{ date('Y') }} AgriConnect. All rights reserved.
            </div>

        </div>
    </body>
</html>
