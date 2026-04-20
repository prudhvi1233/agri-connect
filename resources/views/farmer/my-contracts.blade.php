<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('My Contracts') }}
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

            <!-- Filters and Search -->
            <div class="mb-6 glass-card-premium rounded-xl p-6">
                <form method="GET" action="{{ route('farmer.my-contracts') }}" class="flex flex-col md:flex-row gap-4">
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
                            <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }} class="bg-gray-900 text-white">Signed</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} class="bg-gray-900 text-white">Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }} class="bg-gray-900 text-white">Completed</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <select name="sort" class="glass-select px-4 py-3">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }} class="bg-gray-900 text-white">Date Created</option>
                            <option value="delivery_date" {{ request('sort') == 'delivery_date' ? 'selected' : '' }} class="bg-gray-900 text-white">Delivery Date</option>
                            <option value="total_amount" {{ request('sort') == 'total_amount' ? 'selected' : '' }} class="bg-gray-900 text-white">Total Amount</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all duration-300 shadow-lg">
                        Apply Filters
                    </button>

                    @if(request('search') || request('status') || request('sort'))
                    <a href="{{ route('farmer.my-contracts') }}" class="px-6 py-3 glass-button-secondary">
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
                            <tr style="background: linear-gradient(to right, #2563eb, #16a34a);">
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Contract ID</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Buyer</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Crop</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Quantity</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Price/Unit</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Total</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Duration</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Delivery</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Payment</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Status</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($contracts as $contract)
                                @php
                                    $totalPaid = $contract->payments->where('status', 'completed')->sum('amount');
                                    $paymentPercentage = $contract->total_amount > 0 ? ($totalPaid / $contract->total_amount) * 100 : 0;
                                @endphp
                            <tr class="hover:bg-white/5 transition-colors duration-200">
                                <td class="py-4 px-4 align-middle">
                                    <span class="font-mono text-sm font-semibold text-blue-400">#{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div>
                                        <p class="font-semibold text-white hover:text-emerald-400 transition-colors duration-200 cursor-default">{{ $contract->buyer->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $contract->buyer->email ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="font-medium text-white">{{ $contract->crop_name ?? 'N/A' }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-gray-300">{{ number_format($contract->agreed_quantity ?? 0, 2) }} kg</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-gray-300">₹{{ number_format($contract->price_per_unit ?? 0, 2) }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="font-bold text-white">₹{{ number_format($contract->total_amount ?? 0, 2) }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @if($contract->contract_start_date && $contract->contract_end_date)
                                        <p class="text-xs text-gray-300">{{ \Carbon\Carbon::parse($contract->contract_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($contract->contract_end_date)->format('M d, Y') }}</p>
                                    @else
                                        <p class="text-xs text-gray-400">-</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @if($contract->delivery_status === 'delivered')
                                        <span class="glass-badge-success">Delivered</span>
                                    @elseif($contract->delivery_status === 'shipped')
                                        <span class="glass-badge-info">Shipped</span>
                                    @elseif($contract->delivery_status === 'ready')
                                        <span class="glass-badge-purple">Ready</span>
                                    @else
                                        <span class="glass-badge-warning">{{ ucfirst($contract->delivery_status ?? 'Processing') }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div>
                                        <p class="text-sm font-semibold text-white">₹{{ number_format($totalPaid, 2) }}</p>
                                        <div class="w-full bg-white/10 rounded-full h-2 mt-1">
                                            <div class="bg-gradient-to-r from-emerald-500 to-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $paymentPercentage }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">{{ number_format($paymentPercentage, 0) }}% paid</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @php
                                        $statusColors = [
                                            'signed' => 'glass-badge-info',
                                            'active' => 'glass-badge-success',
                                            'completed' => 'glass-badge-purple',
                                        ];
                                    @endphp
                                    <span class="{{ $statusColors[$contract->status] ?? 'glass-badge' }}">
                                        {{ $contract->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- View Details -->
                                        <a href="{{ route('contracts.show', $contract) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-semibold rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                                            View
                                        </a>

                                        <!-- Update Delivery Status (if active) -->
                                        @if(in_array($contract->status, ['signed', 'active', 'completed', 'negotiating']))
                                            <button type="button" 
                                                    onclick="showDeliveryModal({{ $contract->id }}, '{{ $contract->delivery_status }}')"
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-xs font-semibold rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                                                Update Delivery
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="py-12 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-200 text-lg font-medium">No active contracts</p>
                                    <p class="text-gray-300 text-sm mt-2">Accepted contracts will appear here</p>
                                    <a href="{{ route('farmer.contract-requests') }}" class="inline-block mt-4 px-6 py-3 glass-button-primary font-semibold rounded-xl">
                                        View Contract Requests
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

    <!-- Delivery Status Modal -->
    <div id="deliveryModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="glass-modal rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl border border-white/20">
            <h3 class="text-2xl font-bold mb-6 text-white">Update Delivery Status</h3>
            <form id="deliveryForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-300 mb-3">Current Status</label>
                    <select name="delivery_status" required class="glass-select w-full px-4 py-3">
                        <option value="processing" class="bg-gray-900 text-white">Processing</option>
                        <option value="preparing" class="bg-gray-900 text-white">Preparing Order</option>
                        <option value="ready" class="bg-gray-900 text-white">Ready for Pickup</option>
                        <option value="shipped" class="bg-gray-900 text-white">Shipped</option>
                        <option value="delivered" class="bg-gray-900 text-white">Delivered</option>
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-bold py-3 rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all duration-300 shadow-lg">
                        Update Status
                    </button>
                    <button type="button" onclick="hideDeliveryModal()" class="flex-1 glass-button-secondary py-3">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showDeliveryModal(contractId, currentStatus) {
            document.getElementById('deliveryModal').classList.remove('hidden');
            document.getElementById('deliveryForm').action = '/farmer/contracts/' + contractId + '/update-delivery';
            
            if (currentStatus) {
                document.querySelector('select[name="delivery_status"]').value = currentStatus;
            } else {
                document.querySelector('select[name="delivery_status"]').value = 'processing';
            }
        }

        function hideDeliveryModal() {
            document.getElementById('deliveryModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
