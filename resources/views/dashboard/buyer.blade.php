<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            {{ __('Buyer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
            <div class="glass-alert-success">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <div class="glass-card-premium p-8">
                <div class="flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-emerald-600 to-green-600 p-8 rounded-2xl text-white shadow-xl mb-8">
                    <div>
                        <h3 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}</h3>
                        <p class="text-emerald-100 font-medium">Browse the marketplace or create direct contracts with farmers.</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-4">
                        <a href="{{ route('listings.index') }}" class="px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-bold rounded-xl shadow-lg transition-all transform hover:scale-105 hover:bg-white/30 border border-white/30">
                            Explore Marketplace
                        </a>
                        <a href="{{ route('contracts.create') }}" class="px-6 py-3 bg-white text-emerald-700 font-bold rounded-xl shadow-lg transition-all transform hover:scale-105 border border-white/50">
                            Create Contract
                        </a>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-white mb-6">Your Contracts</h3>
                
                @if($contracts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($contracts as $contract)
                            <div class="glass-card p-6 hover:shadow-2xl transition-all relative overflow-hidden group">
                                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-green-600"></div>
                                <h4 class="text-xl font-bold text-white mt-2">{{ $contract->crop_name ?? $contract->listing->crop_name }}</h4>
                                <p class="text-sm border-b border-white/10 pb-4 mb-4 text-gray-300">Farmer: <span class="font-semibold text-white">{{ $contract->farmer->name }}</span></p>
                                
                                <div class="flex justify-between items-center mb-4 px-2">
                                    <div class="text-left">
                                        <p class="text-xs text-gray-400 uppercase tracking-wide">Agreed Price</p>
                                        <p class="font-bold text-white">₹{{ number_format($contract->agreed_price, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400 uppercase tracking-wide">Quantity</p>
                                        <p class="font-bold text-white">{{ $contract->agreed_quantity }} {{ $contract->listing->unit ?? 'units' }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <span class="glass-badge {{ $contract->status == 'signed' || $contract->status == 'active' ? 'glass-badge-success' : 'glass-badge-warning' }}">
                                        {{ $contract->status }}
                                    </span>
                                </div>
                                
                                <a href="{{ route('contracts.show', $contract) }}" class="block w-full py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-medium rounded-xl transition-all hover:shadow-lg hover:scale-[1.02] text-center">
                                    Manage Contract
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 border-2 border-dashed border-white/20 rounded-2xl glass-card-subtle">
                        <p class="text-gray-300 text-lg mb-4">You have no active contracts or proposals.</p>
                        <a href="{{ route('listings.index') }}" class="text-emerald-400 hover:text-emerald-300 font-medium">Head to the marketplace to get started →</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
