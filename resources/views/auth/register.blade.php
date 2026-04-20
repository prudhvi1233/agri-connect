<x-guest-layout>
    <!-- Avatar Icon -->
    <div class="flex justify-center mb-4">
        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
            <svg class="w-8 h-8 text-white/80" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-3">
        @csrf

        <!-- Name -->
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <svg class="w-3.5 h-3.5 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                <x-input-label for="name" :value="__('Full Name')" class="text-white/80 font-medium text-xs" />
            </div>
            <x-text-input id="name" class="block w-full bg-transparent border-b-2 border-white/30 text-white placeholder-white/40 focus:outline-none focus:border-emerald-400 px-2 py-2 text-sm transition-colors" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <svg class="w-3.5 h-3.5 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                </svg>
                <x-input-label for="email" :value="__('Email ID')" class="text-white/80 font-medium text-xs" />
            </div>
            <x-text-input id="email" class="block w-full bg-transparent border-b-2 border-white/30 text-white placeholder-white/40 focus:outline-none focus:border-emerald-400 px-2 py-2 text-sm transition-colors" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <svg class="w-3.5 h-3.5 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <x-input-label for="password" :value="__('Password')" class="text-white/80 font-medium text-xs" />
            </div>
            <div class="relative">
                <x-text-input id="password" class="block w-full bg-transparent border-b-2 border-white/30 text-white placeholder-white/40 focus:outline-none focus:border-emerald-400 px-2 py-2 pr-10 text-sm transition-colors"
                                type="password"
                                name="password"
                                required autocomplete="new-password" 
                                placeholder="Create a password" />
                <button type="button" onclick="togglePassword('password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); z-index: 10;" class="text-white/50 hover:text-white/90 transition-colors focus:outline-none">
                    <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <svg class="w-3.5 h-3.5 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white/80 font-medium text-xs" />
            </div>
            <div class="relative">
                <x-text-input id="password_confirmation" class="block w-full bg-transparent border-b-2 border-white/30 text-white placeholder-white/40 focus:outline-none focus:border-emerald-400 px-2 py-2 pr-10 text-sm transition-colors"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" 
                                placeholder="Confirm your password" />
                <button type="button" onclick="togglePassword('password_confirmation')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); z-index: 10;" class="text-white/50 hover:text-white/90 transition-colors focus:outline-none">
                    <svg id="password_confirmation-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Role -->
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <svg class="w-3.5 h-3.5 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                </svg>
                <x-input-label for="role" :value="__('I am a')" class="text-white/80 font-medium text-xs" />
            </div>
            <select id="role" name="role" class="block w-full bg-transparent border-b-2 border-white/30 text-white focus:outline-none focus:border-emerald-400 px-2 py-2 text-sm transition-colors cursor-pointer" required>
                <option value="farmer" class="bg-gray-800">🌾 Farmer</option>
                <option value="buyer" class="bg-gray-800">🛒 Buyer</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1" />
        </div>

        <!-- Phone -->
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <svg class="w-3.5 h-3.5 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                </svg>
                <x-input-label for="phone" :value="__('Phone')" class="text-white/80 font-medium text-xs" />
            </div>
            <x-text-input id="phone" class="block w-full bg-transparent border-b-2 border-white/30 text-white placeholder-white/40 focus:outline-none focus:border-emerald-400 px-2 py-2 text-sm transition-colors" type="text" name="phone" :value="old('phone')" placeholder="Enter your phone number" />
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        <!-- Address -->
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <svg class="w-3.5 h-3.5 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <x-input-label for="address" :value="__('Address')" class="text-white/80 font-medium text-xs" />
            </div>
            <textarea id="address" name="address" rows="2" class="block w-full bg-transparent border-b-2 border-white/30 text-white placeholder-white/40 focus:outline-none focus:border-emerald-400 px-2 py-2 text-sm transition-colors resize-none" placeholder="Enter your address">{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="pt-1">
            <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-blue-500 text-white font-semibold py-2.5 px-4 rounded-xl hover:from-emerald-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 focus:ring-offset-transparent transform hover:scale-[1.02] transition-all duration-200 shadow-lg text-sm">
                {{ __('CREATE ACCOUNT') }}
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-3">
            <p class="text-xs text-white/60">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">
                    Sign In
                </a>
            </p>
        </div>
    </form>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-eye');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</x-guest-layout>
