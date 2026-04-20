<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">Order Details</h2>
            <a href="{{ route('farmer.orders') }}" class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-colors">← Back to Orders</a>
        </div>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card rounded-xl shadow-lg overflow-hidden">
                
                <!-- Order Header -->
                <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-white">Order #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
                            <p class="text-emerald-100 mt-1">Contract #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-4 py-2 glass-card/20 rounded-full text-sm font-bold">
                                @if($order->delivery_date && \Carbon\Carbon::parse($order->delivery_date)->isPast())
                                    Delivered
                                @else
                                    Processing
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-100">Order Information</h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Buyer Info -->
                        <div class="space-y-3">
                            <p><span class="text-gray-300">Buyer Name:</span></p>
                            <p class="font-bold">{{ $order->buyer->name ?? 'N/A' }}</p>
                            
                            <p class="text-gray-300">Contact:</p>
                            <p class="font-bold">{{ $order->buyer->email ?? 'N/A' }}</p>
                            
                            <p class="text-gray-300">Delivery Location:</p>
                            <p class="font-bold">{{ $order->delivery_location ?? 'N/A' }}</p>
                        </div>

                        <!-- Crop Details -->
                        <div class="space-y-3">
                            <p><span class="text-gray-300">Crop Name:</span></p>
                            <p class="font-bold">{{ $order->crop_name ?? 'N/A' }}</p>
                            
                            <p class="text-gray-300">Quantity:</p>
                            <p class="font-bold">{{ $order->agreed_quantity ?? 0 }} units</p>
                            
                            <p class="text-gray-300">Delivery Date:</p>
                            <p class="font-bold">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Pricing Section -->
                    <div class="mt-6 p-4 bg-transparent rounded-lg">
                        <h4 class="font-bold mb-3">Pricing Details</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-300">Price per Unit:</span>
                                <span class="font-bold">₹{{ number_format($order->price_per_unit ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-300">Total Quantity:</span>
                                <span class="font-bold">{{ $order->agreed_quantity ?? 0 }} units</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between text-lg">
                                <span class="font-bold">Total Amount:</span>
                                <span class="font-bold text-emerald-600">₹{{ number_format($order->total_amount ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="mt-6">
                        <h4 class="font-bold mb-3">Payment Progress</h4>
                        @php
                            $totalPaid = $order->payments->where('status', 'completed')->sum('amount');
                            $paymentPercentage = $order->total_amount > 0 ? ($totalPaid / $order->total_amount) * 100 : 0;
                        @endphp
                        <div class="bg-gray-200 rounded-full h-4 mb-2">
                            <div class="bg-emerald-600 h-4 rounded-full" style="width: {{ $paymentPercentage }}%"></div>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-300">Paid: ₹{{ number_format($totalPaid, 2) }}</span>
                            <span class="font-bold">{{ number_format($paymentPercentage, 1) }}%</span>
                        </div>
                    </div>

                    <!-- Contract Dates -->
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="p-4 bg-blue-500/10 border border-blue-500/20 backdrop-blur-md rounded-xl">
                            <p class="text-sm text-gray-400 mb-1">Contract Start</p>
                            <p class="font-bold text-white">{{ $order->contract_start_date ? \Carbon\Carbon::parse($order->contract_start_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-purple-500/10 border border-purple-500/20 backdrop-blur-md rounded-xl">
                            <p class="text-sm text-gray-400 mb-1">Contract End</p>
                            <p class="font-bold text-white">{{ $order->contract_end_date ? \Carbon\Carbon::parse($order->contract_end_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Additional Terms -->
                    @if($order->additional_terms)
                        <div class="mt-6 p-5 bg-yellow-500/10 border-l-4 border-yellow-500 backdrop-blur-md rounded-r-xl">
                            <h4 class="font-bold mb-2 text-white">Additional Terms</h4>
                            <p class="text-gray-300 whitespace-pre-line">{{ $order->additional_terms }}</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="border-t p-6 bg-transparent flex flex-col gap-4">
                    <div class="flex gap-4">
                        <button onclick="showDeliveryModal({{ $order->id }}, '{{ $order->delivery_date }}')" 
                                class="flex-1 px-6 py-3 glass-button-primary font-bold rounded-lg">
                            📦 Update Delivery Status
                        </button>
                        <a href="{{ route('contracts.show', $order) }}" 
                           class="flex-1 px-6 py-3 glass-button-secondary font-bold rounded-lg text-center">
                            💬 Chat with Buyer
                        </a>
                    </div>
                    
                    @if(in_array($order->status, ['signed', 'active', 'negotiating']))
                        <form method="POST" action="{{ route('contracts.complete', $order) }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Mark this order as delivered and complete the contract?')" 
                                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold rounded-lg hover:shadow-blue-500/30 hover:scale-[1.02] transition-all duration-300 border border-blue-500/30">
                                ✅ Mark as Delivered & Complete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Status Update Modal -->
    <div id="deliveryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-100">Update Delivery Status</h3>
                <form id="deliveryForm" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Delivery Status</label>
                            <select name="delivery_status" id="delivery_status" class="w-full bg-gray-900 border border-white/20 text-white focus:ring-emerald-500 focus:border-emerald-500 rounded-lg" required>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Notes (Optional)</label>
                            <textarea name="delivery_notes" rows="3" class="w-full bg-gray-900 border border-white/20 text-white placeholder-gray-500 focus:ring-emerald-500 focus:border-emerald-500 rounded-lg" placeholder="Add any delivery notes..."></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="flex-1 px-4 py-2 glass-button-primary font-bold rounded-lg transition-transform hover:scale-[1.02]">
                            Update Status
                        </button>
                        <button type="button" onclick="closeDeliveryModal()" class="flex-1 px-4 py-2 bg-white/10 hover:bg-white/20 text-white transition-colors rounded-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showDeliveryModal(orderId, deliveryDate) {
            const modal = document.getElementById('deliveryModal');
            const form = document.getElementById('deliveryForm');
            form.action = `/farmer/contracts/${orderId}/update-delivery`;
            
            // Set default status based on delivery date
            const today = new Date().toISOString().split('T')[0];
            const statusSelect = document.getElementById('delivery_status');
            if (deliveryDate < today) {
                statusSelect.value = 'delivered';
            } else {
                statusSelect.value = 'processing';
            }
            
            modal.classList.remove('hidden');
        }

        function closeDeliveryModal() {
            document.getElementById('deliveryModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
