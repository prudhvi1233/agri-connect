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

            <!-- Filters -->
            <div class="mb-6 glass-card rounded-xl p-4 shadow-md">
                <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by crop name or farmer..." 
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
                    <a href="{{ route('orders.index') }}" class="px-6 py-2.5 glass-button-secondary">
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
                            <tr class="bg-gradient-to-r from-blue-600 to-green-600 text-white">
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Order ID</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Farmer</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Crop</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Quantity</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Total Amount</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Delivery Date</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Status</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Delivery</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($orders as $order)
                            <tr class="hover:bg-white/5 transition-colors duration-200">
                                <td class="py-4 px-4">
                                    <span class="font-mono text-sm font-semibold text-emerald-400">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-semibold text-gray-100">{{ $order->farmer->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->farmer->email ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-medium text-gray-100">{{ $order->crop_name ?? 'N/A' }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-300">{{ number_format($order->agreed_quantity ?? 0, 2) }} kg</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-emerald-400">₹{{ number_format($order->total_amount ?? 0, 2) }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-sm text-gray-300">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : '-' }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $statusColors = [
                                            'signed' => 'glass-badge-success',
                                            'active' => 'glass-badge-info',
                                            'completed' => 'glass-badge-purple',
                                        ];
                                    @endphp
                                    <span class="glass-badge {{ $statusColors[$order->status] ?? 'bg-white/10 text-gray-300 border border-white/20' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($order->delivery_status === 'delivered')
                                        <span class="glass-badge glass-badge-success">Delivered</span>
                                    @elseif($order->delivery_status === 'shipped')
                                        <span class="glass-badge glass-badge-info">Shipped</span>
                                    @elseif($order->delivery_status === 'ready')
                                        <span class="glass-badge glass-badge-purple">Ready</span>
                                    @else
                                        <span class="glass-badge glass-badge-warning">{{ ucfirst($order->delivery_status ?? 'Processing') }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- View Details -->
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="inline-flex items-center px-3 py-1.5 glass-button-primary text-xs"
                                           title="View Details">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>

                                        <!-- Download Invoice -->
                                        <a href="{{ route('orders.invoice', $order) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-xs font-medium rounded hover:shadow-green-500/30 hover:scale-[1.02] transition-all duration-300 border border-green-500/30"
                                           title="Download Invoice">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Invoice
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <p class="text-gray-400 text-lg font-medium">No orders found</p>
                                    <p class="text-gray-400 text-sm mt-2">Active contracts will appear here as orders</p>
                                    <a href="{{ route('contracts.index') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        View Contracts
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
</x-app-layout>
