<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Order Details') }} - #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
            <a href="{{ route('orders.index') }}" class="px-4 py-2 glass-button-secondary">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Order Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-500/10 border border-blue-500/20 p-4 rounded-lg backdrop-blur-sm">
                            <p class="text-sm text-gray-300">Order ID</p>
                            <p class="text-xl font-bold text-blue-400">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="bg-green-500/10 border border-green-500/20 p-4 rounded-lg backdrop-blur-sm">
                            <p class="text-sm text-gray-300">Status</p>
                            <p class="text-xl font-bold text-green-400 uppercase">{{ $order->status }}</p>
                        </div>
                    </div>

                    <!-- Farmer Details -->
                    <div class="border-t border-white/10 pt-4">
                        <h3 class="text-lg font-bold mb-3 text-gray-100">Farmer Details</h3>
                        <p class="font-semibold text-gray-100">{{ $order->farmer->name ?? 'N/A' }}</p>
                        <p class="text-gray-400">{{ $order->farmer->email ?? 'N/A' }}</p>
                    </div>

                    <!-- Crop Details -->
                    <div class="border-t border-white/10 pt-4">
                        <h3 class="text-lg font-bold mb-3 text-gray-100">Crop Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div><p class="text-sm text-gray-400">Crop Name</p><p class="font-semibold text-gray-100">{{ $order->crop_name ?? 'N/A' }}</p></div>
                            <div><p class="text-sm text-gray-400">Quantity</p><p class="font-semibold text-gray-100">{{ number_format($order->agreed_quantity ?? 0, 2) }} kg</p></div>
                            <div><p class="text-sm text-gray-400">Price per Unit</p><p class="font-semibold text-gray-100">₹{{ number_format($order->price_per_unit ?? 0, 2) }}</p></div>
                            <div><p class="text-sm text-gray-400">Total Amount</p><p class="font-bold text-2xl text-emerald-400">₹{{ number_format($order->total_amount ?? 0, 2) }}</p></div>
                        </div>
                    </div>

                    <!-- Delivery -->
                    <div class="border-t border-white/10 pt-4">
                        <h3 class="text-lg font-bold mb-3 text-gray-100">Delivery Information</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div><p class="text-sm text-gray-400">Delivery Date</p><p class="font-semibold text-gray-100">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') : 'N/A' }}</p></div>
                            <div><p class="text-sm text-gray-400">Location</p><p class="font-semibold text-gray-100">{{ $order->delivery_location ?? 'N/A' }}</p></div>
                            <div><p class="text-sm text-gray-400 mb-1">Live Status</p>
                                <span class="glass-badge {{ $order->delivery_status === 'delivered' ? 'glass-badge-success' : 'glass-badge-warning' }}">
                                    {{ ucfirst($order->delivery_status ?? 'Processing') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div class="border-t border-white/10 pt-4">
                        <h3 class="text-lg font-bold mb-3 text-gray-100">Payment History</h3>
                        @if($order->payments->count() > 0)
                            <div class="space-y-2">
                                @foreach($order->payments as $payment)
                                    <div class="flex justify-between p-3 bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-100">{{ $payment->payment_type }} Payment</p>
                                            <p class="text-sm text-gray-400">{{ $payment->transaction_id }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-100">₹{{ number_format($payment->amount, 2) }}</p>
                                            <p class="text-sm text-green-400">{{ $payment->status }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400">No payments made yet</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="border-t border-white/10 pt-4">
                        <div class="flex flex-col gap-4">
                            <div class="flex gap-4">
                                <a href="{{ route('payments.create', $order) }}" class="flex-1 text-center px-6 py-3 glass-button-primary">
                                    Make Payment
                                </a>
                                <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="flex-1 text-center px-6 py-3 glass-button-secondary">
                                    Download Invoice
                                </a>
                            </div>
                            <div class="flex gap-4">
                                <a href="{{ route('contracts.show', $order) }}" class="flex-1 text-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-lg hover:shadow-purple-500/30 hover:scale-[1.02] transition-all duration-300 border border-purple-500/30">
                                    💬 Chat with Farmer
                                </a>
                                @if(in_array($order->status, ['signed', 'active', 'negotiating']))
                                    <form method="POST" action="{{ route('contracts.complete', $order) }}" class="flex-1">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Confirm that you have received the product and want to mark this contract as completed?')" 
                                                class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold rounded-lg hover:shadow-blue-500/30 hover:scale-[1.02] transition-all duration-300 border border-blue-500/30">
                                            ✅ Confirm Delivery & Complete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
