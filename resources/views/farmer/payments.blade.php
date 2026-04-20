<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Payment History') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Earnings -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-6 shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Earnings</p>
                            <p class="text-3xl font-bold mt-2">₹{{ number_format($totalEarnings, 2) }}</p>
                        </div>
                        <div class="glass-card/20 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Completed Payments -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-6 shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Completed Payments</p>
                            <p class="text-3xl font-bold mt-2">{{ $completedPayments }}</p>
                        </div>
                        <div class="glass-card/20 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-6 shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium">Pending Payments</p>
                            <p class="text-3xl font-bold mt-2">{{ $pendingPayments }}</p>
                        </div>
                        <div class="glass-card/20 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-6 glass-card rounded-xl p-4 shadow-md">
                <form method="GET" action="{{ route('farmer.payments') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Status Filter -->
                    <div>
                        <select name="status" class="glass-select px-4 py-2.5">
                            <option value="" class="bg-gray-900 text-gray-200">All Statuses</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Completed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Pending</option>
                        </select>
                    </div>

                    <!-- Payment Type Filter -->
                    <div>
                        <select name="type" class="glass-select px-4 py-2.5">
                            <option value="" class="bg-gray-900 text-gray-200">All Types</option>
                            <option value="advance" {{ request('type') == 'advance' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Advance</option>
                            <option value="final" {{ request('type') == 'final' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Final</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-2.5 glass-button-primary">
                        Apply Filters
                    </button>

                    @if(request('status') || request('type'))
                    <a href="{{ route('farmer.payments') }}" class="px-6 py-2.5 glass-button-secondary">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            <!-- Payments Table -->
            <div class="glass-card rounded-xl shadow-lg overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="glass-table">
                        <thead>
                            <tr style="background: linear-gradient(to right, #16a34a, #2563eb);">
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Transaction ID</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Contract</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Buyer</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Amount</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Type</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Method</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Date</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white">Status</th>
                                <th class="py-4 px-4 font-semibold text-sm uppercase tracking-wider text-white text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-white/5 transition-colors duration-200">
                                <td class="py-4 px-4 align-middle">
                                    <span class="font-mono text-sm font-semibold text-blue-400">{{ $payment->transaction_id }}</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @if($payment->contract)
                                        <span class="font-mono text-sm text-emerald-400">#{{ str_pad($payment->contract->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @if($payment->contract && $payment->contract->buyer)
                                        <div>
                                            <p class="font-semibold text-gray-100">{{ $payment->contract->buyer->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $payment->contract->buyer->email }}</p>
                                        </div>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="font-bold text-emerald-400">₹{{ number_format($payment->amount, 2) }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @if($payment->payment_type == 'advance')
                                        <span class="glass-badge glass-badge-info">
                                            {{ ucfirst($payment->payment_type) }}
                                        </span>
                                    @else
                                        <span class="glass-badge glass-badge-purple">
                                            {{ ucfirst($payment->payment_type) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-gray-300">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <p class="text-sm text-gray-300">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    @if($payment->status == 'completed')
                                        <span class="glass-badge glass-badge-success">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    @else
                                        <span class="glass-badge glass-badge-warning">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-middle text-right">
                                    @if($payment->status == 'pending')
                                        <form action="{{ route('payments.confirm', $payment) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-400 hover:to-green-500 text-white text-xs font-bold rounded-lg shadow-lg hover:shadow-green-500/50 transition-all duration-300" onclick="return confirm('Confirm you have securely received this exact amount via QR/UPI?')">
                                                Money Received
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-400 text-lg font-medium">No payments received yet</p>
                                    <p class="text-gray-400 text-sm mt-2">Payments from buyers will appear here</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payments->hasPages())
                <div class="px-6 py-4 bg-transparent border-t border-white/5">
                    {{ $payments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
