<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-emerald-800 leading-tight">
            {{ __('Farmer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-white/50 text-emerald-700 p-4 rounded shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-100">My Crop Listings</h3>
                <div class="flex gap-3">
                    <a href="{{ route('farmer.contract-requests') }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-full shadow-lg transition-all transform hover:scale-105">
                        View Contract Requests
                    </a>
                    <a href="{{ route('listings.create') }}" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-full shadow-lg transition-all transform hover:scale-105">
                        + Add New Listing
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($listings as $listing)
                    <div class="glass-card backdrop-blur-lg border border-white/10 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-xl font-bold text-white">{{ $listing->crop_name }}</h4>
                            <span class="px-3 py-1 bg-emerald-900/50 text-emerald-300 rounded-full text-xs font-bold uppercase">{{ $listing->status }}</span>
                        </div>
                        <p class="text-gray-300 mb-2 truncate">{{ $listing->description ?? 'No description provided.' }}</p>
                        <div class="space-y-1 mb-4 text-sm text-gray-400">
                            <p><span class="font-semibold">Quantity:</span> {{ $listing->quantity }} {{ $listing->unit }}</p>
                            <p><span class="font-semibold">Expected Price:</span> ₹{{ number_format($listing->expected_price, 2) }} / {{ $listing->unit }}</p>
                            <p><span class="font-semibold">Harvest:</span> {{ $listing->harvest_date ? \Carbon\Carbon::parse($listing->harvest_date)->format('M d, Y') : 'TBD' }}</p>
                        </div>
                        <a href="{{ route('listings.show', $listing) }}" class="inline-block mt-2 text-emerald-600 hover:text-emerald-800 font-medium group">
                            View Details <span class="group-hover:translate-x-1 transition-transform inline-block">→</span>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 glass-card rounded-2xl shadow-sm border border-dashed border-emerald-300">
                        <svg class="mx-auto h-12 w-12 text-emerald-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <p class="text-gray-400 text-lg">You haven't added any listings yet.</p>
                    </div>
                @endforelse
            </div>

            <!-- Active Contracts -->
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-100 mb-6">Contract Agreements</h3>
                <div class="glass-card rounded-2xl shadow-xl overflow-hidden border border-white/5">
                    @if($contracts->count() > 0)
                        <table class="glass-table">
                            <thead>
                                <tr class="">
                                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Crop</th>
                                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Buyer</th>
                                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Price/Qty</th>
                                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Status</th>
                                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-emerald-50">
                                @foreach($contracts as $contract)
                                    <tr class="hover:glass-card/5 transition">
                                        <td class="py-4 px-6 text-gray-100 font-medium">{{ $contract->crop_name ?? ($contract->listing ? $contract->listing->crop_name : 'N/A') }}</td>
                                        <td class="py-4 px-6 text-gray-300">{{ $contract->buyer->name }}</td>
                                        <td class="py-4 px-6 text-gray-300">₹{{ number_format($contract->total_amount ?? ($contract->agreed_price * $contract->agreed_quantity), 2) }} for {{ $contract->agreed_quantity }} {{ $contract->listing->unit ?? 'kg' }}</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                                {{ $contract->status == 'signed' || $contract->status == 'active' ? 'bg-green-900/50 text-green-300' : ($contract->status == 'pending' ? 'bg-blue-900/50 text-blue-300' : 'bg-yellow-900/50 text-yellow-300') }}">
                                                {{ $contract->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <a href="{{ route('contracts.show', $contract) }}" class="text-emerald-600 hover:text-emerald-800 font-medium">Manage</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center text-gray-400">
                            No contracts initiated yet.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
