<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('My Orders') }}
        </h2>
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

            <!-- Filters and Search -->
            <div class="mb-6 glass-card rounded-xl p-4 shadow-md">
                <form method="GET" action="{{ route('farmer.orders') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by crop name or buyer..." 
                               class="w-full bg-white/5 border border-white/20 text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 rounded-lg backdrop-blur-sm transition-all duration-300 px-4 py-2.5">
                    </div>

                    <!-- Delivery Status Filter -->
                    <div>
                        <select name="delivery_status" class="glass-select px-4 py-2.5">
                            <option value="" class="bg-gray-900 text-gray-200">All Deliveries</option>
                            <option value="pending" {{ request('delivery_status') == 'pending' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Pending Delivery</option>
                            <option value="delivered" {{ request('delivery_status') == 'delivered' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Delivered</option>
                        </select>
                    </div>

                    <!-- Contract Status Filter -->
                    <div>
                        <select name="status" class="glass-select px-4 py-2.5">
                            <option value="" class="bg-gray-900 text-gray-200">All Statuses</option>
                            <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Signed</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Completed</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <select name="sort" class="glass-select px-4 py-2.5">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Order Date</option>
                            <option value="delivery_date" {{ request('sort') == 'delivery_date' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Delivery Date</option>
                            <option value="total_amount" {{ request('sort') == 'total_amount' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Amount</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-2.5 glass-button-primary">
                        Apply Filters
                    </button>

                    @if(request('search') || request('delivery_status') || request('status') || request('sort'))
                    <a href="{{ route('farmer.orders') }}" class="px-6 py-2.5 glass-button-secondary">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            <!-- Orders Table -->
            <div class="glass-card rounded-xl shadow-lg overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="glass-table">
                        <thead>
                            <tr style="background: linear-gradient(to right, #16a34a, #22c55e);">
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Order ID</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Contract</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Buyer</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Crop</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Quantity</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Total</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Delivery Location</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Delivery Date</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Status</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($orders as $order)
                            @php
                                $isDelivered = $order->delivery_date && \Carbon\Carbon::parse($order->delivery_date)->isPast();
                            @endphp
                            <tr class="hover:bg-white/5 transition-colors duration-200">
                                <td class="py-4 px-4 align-middle">
                                    <span class="font-mono text-sm font-semibold text-emerald-400">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="font-mono text-sm text-blue-400">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div>
                                        <p class="font-semibold text-gray-100">{{ $order->buyer->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->buyer->email ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="font-medium text-gray-100">{{ $order->crop_name ?? 'N/A' }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-gray-300">{{ number_format($order->agreed_quantity ?? 0, 2) }} kg</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="font-bold text-emerald-400">₹{{ number_format($order->total_amount ?? 0, 2) }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-sm text-gray-300">{{ $order->delivery_location ?? 'N/A' }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-sm text-gray-300">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : '-' }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @if($isDelivered)
                                        <span class="glass-badge glass-badge-success">
                                            Delivered
                                        </span>
                                    @else
                                        <span class="glass-badge glass-badge-warning">
                                            Processing
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- View Details -->
                                        <a href="{{ route('farmer.order-show', $order) }}" 
                                           class="inline-flex items-center px-3 py-1.5 glass-button-primary text-xs">
                                            View
                                        </a>

                                        <!-- Update Delivery Status -->
                                        @if(in_array($order->status, ['signed', 'active']))
                                            <button type="button" 
                                                    onclick="showDeliveryModal({{ $order->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-medium rounded hover:shadow-blue-500/30 hover:scale-[1.02] transition-all duration-300 border border-blue-500/30">
                                                Update Status
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <p class="text-gray-400 text-lg font-medium">No orders found</p>
                                    <p class="text-gray-400 text-sm mt-2">Active contracts will appear here as orders</p>
                                    <a href="{{ route('farmer.my-contracts') }}" class="inline-block mt-4 px-6 py-2.5 glass-button-primary">
                                        View My Contracts
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="px-6 py-4 bg-transparent border-t border-white/5">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delivery Status Modal -->
    <div id="deliveryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="glass-card rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold mb-4 text-gray-100">Update Delivery Status</h3>
            <form id="deliveryForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Current Status</label>
                    <select name="delivery_status" required class="w-full glass-select px-4 py-2.5">
                        <option value="preparing" class="bg-gray-900 text-gray-200">Preparing Order</option>
                        <option value="ready" class="bg-gray-900 text-gray-200">Ready for Pickup</option>
                        <option value="shipped" class="bg-gray-900 text-gray-200">Shipped</option>
                        <option value="delivered" class="bg-gray-900 text-gray-200">Delivered</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 glass-button-primary font-bold py-2.5 rounded-lg">
                        Update Status
                    </button>
                    <button type="button" onclick="hideDeliveryModal()" class="flex-1 glass-button-secondary font-bold py-2.5 rounded-lg">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showDeliveryModal(orderId) {
            document.getElementById('deliveryModal').classList.remove('hidden');
            document.getElementById('deliveryForm').action = '/farmer/contracts/' + orderId + '/update-delivery';
        }

        function hideDeliveryModal() {
            document.getElementById('deliveryModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
