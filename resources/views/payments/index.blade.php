<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">{{ __('Payment History') }}</h2>
            <div class="glass-card px-4 py-2 rounded-lg border border-emerald-500/30">
                <p class="text-sm text-emerald-300">Total Paid</p>
                <p class="text-xl font-bold text-emerald-400">₹{{ number_format($totalPaid, 2) }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 glass-alert-success">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Filters -->
            <div class="mb-6 glass-card rounded-xl p-4 shadow-md">
                <form method="GET" action="{{ route('payments.index') }}" class="flex gap-4">
                    <select name="status" class="glass-select px-4 py-2.5">
                        <option value="" class="bg-gray-900 text-gray-200">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Pending</option>
                    </select>
                    <select name="type" class="glass-select px-4 py-2.5">
                        <option value="" class="bg-gray-900 text-gray-200">All Types</option>
                        <option value="advance" {{ request('type') == 'advance' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Advance</option>
                        <option value="final" {{ request('type') == 'final' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Final</option>
                    </select>
                    <button type="submit" class="px-6 py-2.5 glass-button-primary">Apply</button>
                    @if(request('status') || request('type'))
                        <a href="{{ route('payments.index') }}" class="px-6 py-2.5 glass-button-secondary">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Payments Table -->
            <div class="glass-card rounded-xl shadow-lg overflow-hidden">
                <table class="glass-table">
                    <thead>
                        <tr class="bg-gradient-to-r from-emerald-600 to-blue-600 text-white">
                            <th class="py-4 px-4 text-left">Transaction ID</th>
                            <th class="py-4 px-4 text-left">Contract</th>
                            <th class="py-4 px-4 text-left">Farmer</th>
                            <th class="py-4 px-4 text-left">Type</th>
                            <th class="py-4 px-4 text-left">Amount</th>
                            <th class="py-4 px-4 text-left">Method</th>
                            <th class="py-4 px-4 text-left">Date</th>
                            <th class="py-4 px-4 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-white/5 transition-colors duration-200">
                            <td class="py-4 px-4 font-mono text-sm text-blue-400">{{ $payment->transaction_id }}</td>
                            <td class="py-4 px-4 text-gray-300">@if($payment->contract) #{{ str_pad($payment->contract->id, 4, '0', STR_PAD_LEFT) }} @else N/A @endif</td>
                            <td class="py-4 px-4 text-gray-100">@if($payment->contract && $payment->contract->farmer) {{ $payment->contract->farmer->name }} @else N/A @endif</td>
                            <td class="py-4 px-4">
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
                            <td class="py-4 px-4 font-bold text-emerald-400">₹{{ number_format($payment->amount, 2) }}</td>
                            <td class="py-4 px-4 text-gray-300">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td class="py-4 px-4 text-gray-300">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                            <td class="py-4 px-4">
                                @if($payment->status == 'completed')
                                    <span class="glass-badge glass-badge-success">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                @elseif($payment->status == 'pending')
                                    <span class="glass-badge glass-badge-warning">
                                        Processing
                                    </span>
                                @else
                                    <span class="glass-badge glass-badge-warning">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="py-12 text-center text-gray-400">No payments found</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($payments->hasPages())
                <div class="px-6 py-4 bg-transparent">{{ $payments->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
