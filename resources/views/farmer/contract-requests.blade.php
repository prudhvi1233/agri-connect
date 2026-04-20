<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Contract Requests') }}
        </h2>
    </x-slot>

    <style>
        /* Custom Scrollbar for Tables */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.4);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.6);
        }
    </style>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 glass-alert-success">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 glass-alert-danger">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Status Badges -->
            <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('farmer.contract-requests') }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ !request('status') ? 'border-emerald-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300 font-medium">All Requests</p>
                    <p class="text-2xl font-bold text-white">{{ $statusCounts['all'] }}</p>
                </a>
                <a href="{{ route('farmer.contract-requests', ['status' => 'pending']) }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ request('status') == 'pending' ? 'border-blue-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300 font-medium">Pending</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $statusCounts['pending'] }}</p>
                </a>
                <a href="{{ route('farmer.contract-requests', ['status' => 'active']) }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ request('status') == 'active' ? 'border-emerald-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300 font-medium">Accepted</p>
                    <p class="text-2xl font-bold text-emerald-400">{{ $statusCounts['accepted'] }}</p>
                </a>
                <a href="{{ route('farmer.contract-requests', ['status' => 'cancelled']) }}" class="glass-card rounded-xl p-4 shadow-md hover:shadow-lg transition-all border-2 {{ request('status') == 'cancelled' ? 'border-red-500' : 'border-transparent' }}">
                    <p class="text-sm text-gray-300 font-medium">Rejected</p>
                    <p class="text-2xl font-bold text-red-400">{{ $statusCounts['rejected'] }}</p>
                </a>
            </div>

            <!-- Filters and Search -->
            <div class="mb-6 glass-card-premium rounded-xl p-6">
                <form method="GET" action="{{ route('farmer.contract-requests') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by crop name or buyer..." 
                               class="w-full glass-input px-4 py-3 text-white placeholder-gray-400">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="status" class="glass-select px-4 py-3">
                            <option value="" class="bg-gray-900 text-white">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="bg-gray-900 text-white">Pending</option>
                            <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }} class="bg-gray-900 text-white">Signed</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} class="bg-gray-900 text-white">Active</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }} class="bg-gray-900 text-white">Cancelled</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <select name="sort" class="glass-select px-4 py-3">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }} class="bg-gray-900 text-white">Date Received</option>
                            <option value="delivery_date" {{ request('sort') == 'delivery_date' ? 'selected' : '' }} class="bg-gray-900 text-white">Delivery Date</option>
                            <option value="total_amount" {{ request('sort') == 'total_amount' ? 'selected' : '' }} class="bg-gray-900 text-white">Total Amount</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all duration-300 shadow-lg">
                        Apply Filters
                    </button>

                    @if(request('search') || request('status') || request('sort'))
                    <a href="{{ route('farmer.contract-requests') }}" class="px-6 py-3 glass-button-secondary">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            <!-- Requests Table -->
            <div class="glass-card rounded-xl shadow-lg overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="glass-table">
                        <thead>
                            <tr style="background: linear-gradient(to right, #16a34a, #2563eb);">
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Contract ID</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Buyer</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Crop</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Quantity</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Price/Unit</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Total</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Delivery</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Duration</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Status</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($requests as $request)
                            <tr class="hover:bg-white/5 transition-colors duration-200">
                                <td class="py-4 px-4 align-middle">
                                    <span class="font-mono text-sm font-semibold text-emerald-400">#{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div>
                                        <p class="font-semibold text-white hover:text-emerald-400 transition-colors duration-200 cursor-default">{{ $request->buyer->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $request->buyer->email ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="font-medium text-white">{{ $request->crop_name ?? 'N/A' }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-gray-300">{{ number_format($request->agreed_quantity ?? 0, 2) }} kg</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-gray-300">₹{{ number_format($request->price_per_unit ?? 0, 2) }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="font-bold text-white">₹{{ number_format($request->total_amount ?? 0, 2) }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-sm text-gray-300">{{ $request->delivery_date ? \Carbon\Carbon::parse($request->delivery_date)->format('M d, Y') : '-' }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @if($request->contract_start_date && $request->contract_end_date)
                                        <p class="text-xs text-gray-300">{{ \Carbon\Carbon::parse($request->contract_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($request->contract_end_date)->format('M d, Y') }}</p>
                                    @else
                                        <p class="text-xs text-gray-400">-</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @php
                                        $statusColors = [
                                            'pending' => 'glass-badge-info',
                                            'signed' => 'glass-badge-success',
                                            'active' => 'glass-badge-success',
                                            'cancelled' => 'glass-badge-danger',
                                        ];
                                    @endphp
                                    <span class="{{ $statusColors[$request->status] ?? 'glass-badge' }}">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- View Details -->
                                        <a href="{{ route('contracts.show', $request) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-semibold rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                                            View
                                        </a>

                                        <!-- Action buttons for pending contracts -->
                                        @if($request->status === 'pending')
                                            <!-- Accept -->
                                            <form method="POST" action="{{ route('farmer.contract-accept', $request) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-xs font-semibold rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-300"
                                                        onclick="return confirm('Are you sure you want to accept this contract?')">
                                                    Accept
                                                </button>
                                            </form>

                                            <!-- Reject -->
                                            <form method="POST" action="{{ route('farmer.contract-reject', $request) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white text-xs font-semibold rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-300"
                                                        onclick="return confirm('Are you sure you want to reject this contract?')">
                                                    Reject
                                                </button>
                                            </form>

                                            <!-- Request Modification -->
                                            <button type="button" 
                                                    onclick="showModificationModal({{ $request->id }})"
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-amber-700 text-white text-xs font-semibold rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                                                Modify
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-200 text-lg font-medium">No contract requests</p>
                                    <p class="text-gray-300 text-sm mt-2">Contract proposals from buyers will appear here</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($requests->hasPages())
                <div class="px-6 py-4 bg-transparent border-t border-white/5">
                    {{ $requests->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modification Request Modal -->
    <div id="modificationModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="glass-modal rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl border border-white/20">
            <h3 class="text-2xl font-bold mb-6 text-white">Request Contract Modification</h3>
            <form id="modificationForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-300 mb-3">Modification Notes</label>
                    <textarea name="modification_notes" rows="4" required 
                              class="w-full glass-input px-4 py-3 text-white placeholder-gray-400 resize-none"
                              placeholder="Describe the changes you'd like to request..."></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-bold py-3 rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all duration-300 shadow-lg">
                        Send Request
                    </button>
                    <button type="button" onclick="hideModificationModal()" class="flex-1 glass-button-secondary py-3">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showModificationModal(contractId) {
            document.getElementById('modificationModal').classList.remove('hidden');
            document.getElementById('modificationForm').action = '/farmer/contracts/' + contractId + '/modify';
        }

        function hideModificationModal() {
            document.getElementById('modificationModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
