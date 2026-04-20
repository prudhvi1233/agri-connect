<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgriConnect - Contract Farming Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-gray-200 antialiased relative selection:bg-emerald-500/30">
    <!-- Premium Video Background -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover opacity-60 scale-105">
            <source src="{{ asset('vid1.mp4') }}" type="video/mp4">
        </video>
        <!-- Dark gradient overlay for readability -->
        <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-emerald-900/30 to-black/90"></div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="relative z-10 w-full flex flex-col min-h-screen">
        <!-- Navigation -->
        <nav class="glass-card shadow-lg sticky top-0 z-50 border-b border-white/10 m-4 rounded-2xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">AgriConnect</span>
                </div>
                <div class="hidden md:flex items-center space-x-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 text-green-700 font-medium hover:bg-green-50 rounded-lg transition-all">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2.5 text-gray-700 font-medium hover:bg-transparent rounded-lg transition-all">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-3 px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-lg hover:shadow-lg hover:scale-105 transition-all">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6 leading-tight">
                    Secure Contracts for
                    <span class="bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">Better Farming</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 mb-10 leading-relaxed">
                    Connect farmers with guaranteed buyers. Ensure stable income, transparent agreements, and secure payments through our comprehensive contract farming platform.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all text-lg">
                            Start Farming Today
                        </a>
                    @endif
                    <a href="#features" class="px-8 py-4 glass-card text-gray-700 font-semibold rounded-xl shadow-md hover:shadow-lg border border-white/10 hover:border-green-300 transition-all text-lg">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 relative">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-2xl border-t border-white/5 z-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Why Choose AgriConnect?</h2>
                <p class="text-xl text-gray-300">Comprehensive tools to transform contract farming</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-card p-8 rounded-2xl hover:shadow-emerald-500/20 hover:scale-[1.02] transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Secure Contracts</h3>
                    <p class="text-gray-300 leading-relaxed">Create, negotiate, and sign digital contracts with full transparency and legal backing for both farmers and buyers.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card p-8 rounded-2xl hover:shadow-blue-500/20 hover:scale-[1.02] transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Secure Payments</h3>
                    <p class="text-gray-300 leading-relaxed">Process payments securely through our platform with transparent transaction tracking and timely disbursements.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-8 rounded-2xl hover:shadow-purple-500/20 hover:scale-[1.02] transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Direct Communication</h3>
                    <p class="text-gray-300 leading-relaxed">Built-in messaging system enables transparent negotiation and communication between farmers and buyers.</p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card p-8 rounded-2xl hover:shadow-amber-500/20 hover:scale-[1.02] transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-amber-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Marketplace</h3>
                    <p class="text-gray-300 leading-relaxed">Browse available crops, compare prices, and find the best farming opportunities all in one place.</p>
                </div>

                <!-- Feature 5 -->
                <div class="glass-card p-8 rounded-2xl hover:shadow-red-500/20 hover:scale-[1.02] transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-red-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Price Transparency</h3>
                    <p class="text-gray-300 leading-relaxed">Real-time market prices and fair negotiation tools ensure both parties get the best deal.</p>
                </div>

                <!-- Feature 6 -->
                <div class="glass-card p-8 rounded-2xl hover:shadow-teal-500/20 hover:scale-[1.02] transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-teal-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Income Stability</h3>
                    <p class="text-gray-300 leading-relaxed">Guaranteed contracts provide farmers with predictable income and reduce market uncertainties.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 relative">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-3xl border-t border-white/5 z-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">How It Works</h2>
                <p class="text-xl text-gray-300">Simple steps to get started with contract farming</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center glass-card p-6 border-white/5 hover:border-emerald-500/30 transition-all duration-300 hover:-translate-y-2">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/20">
                        <span class="text-3xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Register</h3>
                    <p class="text-gray-300">Sign up as a farmer or buyer with your details</p>
                </div>

                <div class="text-center glass-card p-6 border-white/5 hover:border-emerald-500/30 transition-all duration-300 hover:-translate-y-2">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/20">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">List or Browse</h3>
                    <p class="text-gray-300">Farmers list crops, buyers browse marketplace</p>
                </div>

                <div class="text-center glass-card p-6 border-white/5 hover:border-emerald-500/30 transition-all duration-300 hover:-translate-y-2">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/20">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Negotiate</h3>
                    <p class="text-gray-300">Discuss terms and negotiate contracts directly</p>
                </div>

                <div class="text-center glass-card p-6 border-white/5 hover:border-emerald-500/30 transition-all duration-300 hover:-translate-y-2">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/20">
                        <span class="text-3xl font-bold text-white">4</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Sign & Pay</h3>
                    <p class="text-gray-300">Sign contracts and process secure payments</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/90 to-green-900/90 backdrop-blur-md z-0"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Transform Your Farming Business?</h2>
            <p class="text-xl text-green-100 mb-10">Join thousands of farmers and buyers already benefiting from secure contract farming</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-block px-10 py-5 glass-card text-green-700 font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all text-lg">
                    Get Started Free
                </a>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black/90 backdrop-blur-3xl border-t border-white/10 text-gray-300 py-12 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">AgriConnect</span>
                    </div>
                    <p class="text-gray-400">Empowering farmers with secure contracts and guaranteed markets for sustainable agriculture.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-emerald-400 transition-colors">Features</a></li>
                        <li><a href="{{ route('listings.index') }}" class="hover:text-emerald-400 transition-colors">Marketplace</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="hover:text-emerald-400 transition-colors">Get Started</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: info@agriconnect.com</li>
                        <li>Phone: +1 (555) 123-4567</li>
                        <li>Address: 123 Farm Road, Agri City</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 mt-8 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} AgriConnect. All rights reserved.</p>
            </div>
        </div>
    </footer>
    </div>
</body>
</html>
