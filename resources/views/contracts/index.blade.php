<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('My Contracts') }}
            </h2>
            <a href="{{ route('contracts.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:shadow-lg transition-all">
                + New Contract
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Status Badges -->
            <div class="mb-6 grid grid-cols-2 md:grid-cols-5 gap-4">
                <a href="{{ route('contracts.index') }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ !request('status') ? 'border-blue-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300">All Contracts</p>
                    <p class="text-2xl font-bold text-white">{{ $statusCounts['all'] }}</p>
                </a>
                <a href="{{ route('contracts.index', ['status' => 'pending']) }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ request('status') == 'pending' ? 'border-blue-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300">Pending</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $statusCounts['pending'] }}</p>
                </a>
                <a href="{{ route('contracts.index', ['status' => 'active']) }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ request('status') == 'active' ? 'border-green-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $statusCounts['active'] }}</p>
                </a>
                <a href="{{ route('contracts.index', ['status' => 'completed']) }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ request('status') == 'completed' ? 'border-purple-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300">Completed</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $statusCounts['completed'] }}</p>
                </a>
                <a href="{{ route('contracts.index', ['status' => 'cancelled']) }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ request('status') == 'cancelled' ? 'border-red-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300">Rejected</p>
                    <p class="text-2xl font-bold text-red-600">{{ $statusCounts['rejected'] }}</p>
                </a>
            </div>

            <!-- Filters and Search -->
            <div class="mb-6 glass-card rounded-xl p-4 shadow-md">
                <form method="GET" action="{{ route('contracts.index') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by crop name or farmer..." 
                               class="w-full bg-white/5 border border-white/20 text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 rounded-lg backdrop-blur-sm transition-all duration-300 px-4 py-2.5">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="status" class="glass-select px-4 py-2.5">
                            <option value="" class="bg-gray-900 text-gray-200">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Pending</option>
                            <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Signed</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Cancelled</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <select name="sort" class="glass-select px-4 py-2.5">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Date Created</option>
                            <option value="delivery_date" {{ request('sort') == 'delivery_date' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Delivery Date</option>
                            <option value="total_amount" {{ request('sort') == 'total_amount' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Total Amount</option>
                        </select>
                    </div>

                    <div>
                        <select name="direction" class="glass-select px-4 py-2.5">
                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Newest First</option>
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Oldest First</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-2.5 glass-button-primary">
                        Apply Filters
                    </button>

                    @if(request('search') || request('status') || request('sort'))
                    <a href="{{ route('contracts.index') }}" class="px-6 py-2.5 glass-button-secondary">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            <!-- Contracts Table -->
            <div class="glass-card rounded-xl shadow-lg overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="glass-table">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Contract ID</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Farmer</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Crop</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Quantity</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Price/Unit</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Total</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Delivery</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Duration</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Status</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($contracts as $contract)
                            <tr class="hover:bg-white/5 transition-colors duration-200">
                                <td class="py-4 px-4">
                                    <span class="font-mono text-sm font-semibold text-blue-400">#{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-semibold text-gray-100">{{ $contract->farmer->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $contract->farmer->email }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-medium text-gray-100">{{ $contract->crop_name }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-300">{{ number_format($contract->agreed_quantity, 2) }} kg</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-300">₹{{ number_format($contract->price_per_unit, 2) }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-emerald-400">₹{{ number_format($contract->total_amount, 2) }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-sm text-gray-300">{{ $contract->delivery_date ? \Carbon\Carbon::parse($contract->delivery_date)->format('M d, Y') : '-' }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    @if($contract->contract_start_date && $contract->contract_end_date)
                                        <p class="text-xs text-gray-300">{{ \Carbon\Carbon::parse($contract->contract_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($contract->contract_end_date)->format('M d, Y') }}</p>
                                    @else
                                        <p class="text-xs text-gray-400">-</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'glass-badge-info',
                                            'proposed' => 'glass-badge-warning',
                                            'negotiating' => 'glass-badge-warning',
                                            'signed' => 'glass-badge-success',
                                            'active' => 'glass-badge-success',
                                            'completed' => 'glass-badge-purple',
                                            'cancelled' => 'glass-badge-danger',
                                        ];
                                    @endphp
                                    <span class="glass-badge {{ $statusColors[$contract->status] ?? 'bg-white/10 text-gray-300 border border-white/20' }}">
                                        {{ $contract->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- View Details -->
                                        <a href="{{ route('contracts.show', $contract) }}" 
                                           class="inline-flex items-center px-3 py-1.5 glass-button-primary text-xs"
                                           title="View Details">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>

                                        <!-- Edit (only if pending) -->
                                        @if($contract->status === 'pending')
                                            <a href="{{ route('contracts.edit', $contract) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white text-xs font-medium rounded hover:shadow-amber-500/30 hover:scale-[1.02] transition-all duration-300 border border-amber-500/30"
                                               title="Edit Contract">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                        @endif

                                        <!-- Cancel (if pending) -->
                                        @if(in_array($contract->status, ['pending', 'proposed']))
                                            <form method="POST" action="{{ route('contracts.cancel', $contract) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel this contract?');">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-600 to-red-700 text-white text-xs font-medium rounded hover:shadow-red-500/30 hover:scale-[1.02] transition-all duration-300 border border-red-500/30"
                                                        title="Cancel Contract">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Download (if active/signed/completed) -->
                                        @if(in_array($contract->status, ['signed', 'active', 'completed']))
                                            <a href="{{ route('contracts.download', $contract) }}" target="_blank"
                                               class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-xs font-medium rounded hover:shadow-green-500/30 hover:scale-[1.02] transition-all duration-300 border border-green-500/30"
                                               title="Download Agreement">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                PDF
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-400 text-lg font-medium">No contracts found</p>
                                    <p class="text-gray-400 text-sm mt-2">Create your first contract to get started</p>
                                    <a href="{{ route('contracts.create') }}" class="inline-block mt-4 px-6 py-2.5 glass-button-primary">
                                        Create Contract
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($contracts->hasPages())
                <div class="px-6 py-4 bg-transparent border-t border-white/5">
                    {{ $contracts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
