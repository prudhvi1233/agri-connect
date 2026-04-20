<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Listing Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card overflow-hidden shadow-2xl sm:rounded-3xl border border-white/5 flex flex-col md:flex-row">
                
                <!-- Info Section -->
                <div class="p-8 md:p-12 md:w-2/3">
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="px-3 py-1 glass-badge glass-badge-success">{{ $listing->status }}</span>
                    </div>
                    <h3 class="text-4xl font-extrabold text-white mb-6">{{ $listing->crop_name }}</h3>
                    
                    <p class="text-gray-200 text-lg leading-relaxed mb-8 break-words">
                        {{ $listing->description ?: 'No additional description provided.' }}
                    </p>

                    <div class="glass-card-subtle rounded-2xl p-6 border border-white/10 grid grid-cols-2 gap-y-6">
                        <div>
                            <p class="text-sm font-medium text-gray-300 uppercase tracking-widest">Quantity</p>
                            <p class="mt-1 text-2xl font-bold text-white">{{ $listing->quantity }} {{ $listing->unit }}</p>
                        </div>
                         <div>
                            <p class="text-sm font-medium text-gray-300 uppercase tracking-widest">Expected Price</p>
                            <p class="mt-1 text-2xl font-bold text-emerald-400">₹{{ number_format($listing->expected_price, 2) }}</p>
                        </div>
                         <div>
                            <p class="text-sm font-medium text-gray-300 uppercase tracking-widest">Harvest Date</p>
                            <p class="mt-1 text-lg font-semibold text-gray-100">{{ $listing->harvest_date ? \Carbon\Carbon::parse($listing->harvest_date)->format('F d, Y') : 'To be determined' }}</p>
                        </div>
                         <div>
                            <p class="text-sm font-medium text-gray-300 uppercase tracking-widest">Farmer</p>
                            <p class="mt-1 text-lg font-semibold text-gray-100">{{ $listing->farmer->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Section (Propose Contract) side panel -->
                <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 md:w-1/3 p-8 md:p-12 text-white flex flex-col justify-center shadow-inner border-l border-white/10">
                    @if(auth()->user()->role === 'buyer')
                        <h4 class="text-3xl font-bold mb-4">Interested?</h4>
                        <p class="text-emerald-100 mb-8 text-lg">Send a contract proposal to lock in this produce before it's gone.</p>
                        
                        <form method="POST" action="{{ route('contracts.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                            
                            <div>
                                <x-input-label for="agreed_price" value="Your Offer Price (₹)" class="text-white" />
                                <x-text-input id="agreed_price" class="block mt-1 w-full bg-white/10 border-white/30 text-white placeholder-white/60 focus:ring-2 focus:ring-white/50 focus:border-white" type="number" step="0.01" name="agreed_price" value="{{ $listing->expected_price }}" required />
                            </div>

                            <div>
                                <x-input-label for="agreed_quantity" value="Requested Quantity ({{ $listing->unit }})" class="text-white" />
                                <x-text-input id="agreed_quantity" class="block mt-1 w-full bg-white/10 border-white/30 text-white placeholder-white/60 focus:ring-2 focus:ring-white/50 focus:border-white" type="number" step="0.01" name="agreed_quantity" value="{{ $listing->quantity }}" required />
                            </div>

                            <button type="submit" class="w-full mt-6 glass-button-primary font-bold py-4 rounded-xl text-lg">
                                Propose Contract
                            </button>
                        </form>
                    @elseif(auth()->id() === $listing->farmer_id)
                        <h4 class="text-3xl font-bold mb-4">Your Listing</h4>
                        <p class="text-emerald-100 mb-8">This is how buyers will see your listing. You can modify it from your dashboard.</p>
                        <a href="{{ route('listings.edit', $listing) }}" class="inline-block w-full text-center glass-button-secondary font-bold py-3 px-6 rounded-xl">
                            Edit Listing
                        </a>
                    @else
                        <h4 class="text-3xl font-bold mb-4">Farmer View</h4>
                        <p class="text-emerald-100">You must be registered as a Buyer to propose contracts for this listing.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
