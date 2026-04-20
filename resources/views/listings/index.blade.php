<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Marketplace') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-10 text-center">
                <h2 class="text-4xl font-extrabold text-white tracking-tight sm:text-5xl">Fresh from the Farm</h2>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-200">Discover quality produce directly from reliable farmers. Secure your supply today.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($listings as $listing)
                    <div class="glass-card rounded-3xl shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden flex flex-col h-full border border-white/5">
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-2xl font-bold text-white">{{ $listing->crop_name }}</h3>
                                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                </div>
                            </div>
                            
                            <p class="text-gray-200 text-sm mb-6 line-clamp-3">{{ $listing->description }}</p>
                            
                            <div class="space-y-3 mb-6 glass-card-subtle p-4 rounded-xl">
                                <div class="flex justify-between">
                                    <span class="text-gray-300 text-sm font-medium">Quantity</span>
                                    <span class="font-semibold text-white">{{ $listing->quantity }} {{ $listing->unit }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300 text-sm font-medium">Expected Price</span>
                                    <span class="font-bold text-emerald-400">₹{{ number_format($listing->expected_price, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300 text-sm font-medium">Harvest Date</span>
                                    <span class="font-semibold text-white">{{ $listing->harvest_date ? \Carbon\Carbon::parse($listing->harvest_date)->format('M d, Y') : 'TBD' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300 text-sm font-medium">Farmer</span>
                                    <span class="font-semibold text-white">{{ $listing->farmer->name }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 pb-6 pt-0 mt-auto">
                            <a href="{{ route('listings.show', $listing) }}" class="block w-full text-center px-4 py-3 glass-button-primary text-sm font-medium rounded-xl">
                                View Details & Propose
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 glass-card rounded-3xl shadow-sm text-center border-2 border-dashed border-white/10">
                        <svg class="mx-auto h-16 w-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h3 class="text-xl font-medium text-white">No crops available</h3>
                        <p class="mt-2 text-gray-200">Farmers haven't posted any listings yet. Check back soon!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
