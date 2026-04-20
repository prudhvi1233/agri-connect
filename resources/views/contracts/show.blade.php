<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Contract Agreement') }} #{{ $contract->id }}
        </h2>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Contract Overview -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Status Card -->
                    <div class="glass-card rounded-3xl shadow-lg p-6 border border-white/5 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4">
                             <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider
                                        @if($contract->status === 'completed')
                                            glass-badge glass-badge-info
                                        @elseif(in_array($contract->status, ['signed', 'active']))
                                            glass-badge glass-badge-success
                                        @elseif($contract->status === 'cancelled')
                                            glass-badge glass-badge-danger
                                        @else
                                            glass-badge glass-badge-warning
                                        @endif">
                                        @if($contract->status === 'completed')
                                            ✓ Completed
                                        @elseif($contract->status === 'cancelled')
                                            ✕ Cancelled
                                        @else
                                            {{ $contract->status }}
                                        @endif
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-6">Agreement Terms</h3>
                        
                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="text-gray-400 font-medium">Crop</p>
                                <p class="font-bold text-lg text-gray-100">{{ $contract->crop_name ?? ($contract->listing ? $contract->listing->crop_name : 'N/A') }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-400 font-medium">Farmer</p>
                                    <p class="font-semibold text-gray-100">{{ $contract->farmer->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 font-medium">Buyer</p>
                                    <p class="font-semibold text-gray-100">{{ $contract->buyer->name }}</p>
                                </div>
                            </div>
                            @if($contract->price_per_unit)
                            <div class="glass-card-subtle p-4 rounded-xl border border-blue-500/20 mt-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-300 font-medium">Price Per Unit</span>
                                    <span class="font-bold text-blue-400">₹{{ number_format($contract->price_per_unit, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300 font-medium">Quantity</span>
                                    <span class="font-bold text-white">{{ $contract->agreed_quantity }} {{ $contract->listing ? $contract->listing->unit ?? 'kg' : 'kg' }}</span>
                                </div>
                            </div>
                            @endif
                            <div class="glass-card-subtle p-4 rounded-xl border border-white/10 mt-4 space-y-2">
                                <div class="flex justify-between pt-2 border-t border-white/10">
                                    <span class="text-gray-200 font-bold">Total Value</span>
                                    <span class="font-black text-emerald-400 text-lg">₹{{ number_format($contract->total_amount ?? ($contract->agreed_price * $contract->agreed_quantity), 2) }}</span>
                                </div>
                            </div>
                            @if($contract->delivery_location)
                            <div class="mt-4">
                                <p class="text-gray-400 font-medium">Delivery Location</p>
                                <p class="font-semibold text-gray-100">{{ $contract->delivery_location }}</p>
                            </div>
                            @endif
                            @if($contract->advance_percentage)
                            <div class="glass-card-subtle p-4 rounded-xl border border-purple-500/20 mt-4 space-y-2">
                                <p class="text-sm font-bold text-purple-300 mb-2">Payment Schedule</p>
                                <div class="flex justify-between">
                                    <span class="text-gray-300 font-medium">Advance ({{ $contract->advance_percentage }}%)</span>
                                    <span class="font-bold text-purple-400">₹{{ number_format($contract->advance_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300 font-medium">Final Payment</span>
                                    <span class="font-bold text-purple-400">₹{{ number_format($contract->final_payment, 2) }}</span>
                                </div>
                            </div>
                            @endif
                            @if($contract->contract_start_date || $contract->contract_end_date)
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                @if($contract->contract_start_date)
                                <div>
                                    <p class="text-gray-400 font-medium">Start Date</p>
                                    <p class="font-semibold text-gray-100">{{ \Carbon\Carbon::parse($contract->contract_start_date)->format('M d, Y') }}</p>
                                </div>
                                @endif
                                @if($contract->contract_end_date)
                                <div>
                                    <p class="text-gray-400 font-medium">End Date</p>
                                    <p class="font-semibold text-gray-100">{{ \Carbon\Carbon::parse($contract->contract_end_date)->format('M d, Y') }}</p>
                                </div>
                                @endif
                            </div>
                            @endif
                            @if($contract->additional_terms)
                            <div class="mt-4">
                                <p class="text-gray-300 font-medium">Additional Terms</p>
                                <p class="text-sm text-gray-200 whitespace-pre-line">{{ $contract->additional_terms }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-8 space-y-3">
                            @if($contract->status === 'pending' && auth()->id() === $contract->farmer_id)
                                <div class="space-y-3">
                                    <div class="glass-card-subtle border border-blue-500/20 rounded-xl p-4 text-center mb-4">
                                        <p class="text-blue-300 font-medium">Contract Awaiting Your Approval</p>
                                        <p class="text-sm text-blue-400 mt-1">Review the terms and accept or reject this contract</p>
                                    </div>
                                    <form method="POST" action="{{ route('contracts.sign', $contract) }}">
                                        @csrf
                                        <button type="submit" class="w-full glass-button-success font-bold py-3 px-4 rounded-xl">
                                            ✓ Accept Contract
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('contracts.sign', $contract) }}" onsubmit="return confirm('Are you sure you want to reject this contract?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full glass-button-danger font-bold py-3 px-4 rounded-xl">
                                            ✕ Reject Contract
                                        </button>
                                    </form>
                                </div>
                            @elseif($contract->status === 'pending' && auth()->id() === $contract->buyer_id)
                                <div class="glass-card-subtle border border-blue-500/20 rounded-xl p-4 text-center">
                                    <p class="text-blue-300 font-medium">Waiting for farmer approval</p>
                                    <p class="text-sm text-blue-400 mt-1">The farmer will review and accept/reject this contract</p>
                                </div>
                            @elseif($contract->status === 'proposed' || $contract->status === 'negotiating')
                                <form method="POST" action="{{ route('contracts.sign', $contract) }}">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Are you sure you want to officially sign this agreement?')" class="w-full glass-button-primary font-bold py-3 px-4 rounded-xl">
                                        Accept & Sign Contract
                                    </button>
                                </form>
                            @endif

                            @if($contract->status === 'signed' || $contract->status === 'active')
                                @if(auth()->id() === $contract->buyer_id)
                                    @php
                                        $totalAmount = $contract->total_amount ?? $contract->agreed_price;
                                        $advancePaid = $contract->payments->where('payment_type', 'advance')->where('status', 'completed')->sum('amount');
                                        $finalPaid = $contract->payments->where('payment_type', 'final')->where('status', 'completed')->sum('amount');
                                        $totalPaid = $advancePaid + $finalPaid;
                                        $advanceDue = $contract->advance_amount ?? 0;
                                        $finalDue = $contract->final_payment ?? 0;
                                    @endphp

                                    @if($totalPaid < $totalAmount)
                                        <div class="mt-4 p-6 glass-card border border-emerald-500/20 rounded-xl space-y-4">
                                            @if($advancePaid > 0)
                                                <div class="bg-emerald-500/20 border border-emerald-500/50 rounded-lg p-3 text-center">
                                                    <p class="text-emerald-300 font-bold">Advance Received!</p>
                                                    <p class="text-sm text-emerald-100">Balance amount of ₹{{ number_format($finalDue, 2) }} is pending.</p>
                                                </div>
                                            @endif

                                            <h4 class="font-bold text-white mb-2 text-center text-lg">Scan to Pay securely</h4>
                                            
                                            <!-- Farmer QR Code -->
                                            <div class="bg-white p-4 rounded-xl max-w-[200px] mx-auto shadow-inner flex items-center justify-center">
                                                <!-- Uses a public image if available, else falls back to a generic generated QR code -->
                                                <img src="{{ asset('images/farmer_qr.png') }}" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=farmer@upi&pn=Farmer'" alt="Farmer UPI QR Code" class="w-full h-auto rounded-lg">
                                            </div>

                                            <div class="space-y-3 pt-4 border-t border-white/10">
                                                @if($advancePaid == 0)
                                                    <form method="POST" action="{{ route('payments.store', $contract) }}">
                                                        @csrf
                                                        <input type="hidden" name="payment_method" value="upi">
                                                        <input type="hidden" name="payment_type" value="advance">
                                                        <input type="hidden" name="amount" value="{{ $advanceDue }}">
                                                        <button type="submit" onclick="return confirm('Please confirm you have scanned the QR code and completed the advance payment.')" class="w-full glass-button-primary font-bold py-2 px-4 rounded-lg">
                                                            Pay Advance (₹{{ number_format($advanceDue, 2) }})
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('payments.store', $contract) }}">
                                                        @csrf
                                                        <input type="hidden" name="payment_method" value="upi">
                                                        <input type="hidden" name="payment_type" value="final">
                                                        <input type="hidden" name="amount" value="{{ $totalAmount }}">
                                                        <button type="submit" onclick="return confirm('Please confirm you have scanned the QR code and completed the full payment.')" class="w-full bg-transparent hover:bg-emerald-600/20 border border-emerald-500/50 text-white font-bold py-2 px-4 rounded-lg transition">
                                                            Pay Full Amount (₹{{ number_format($totalAmount, 2) }})
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('payments.store', $contract) }}">
                                                        @csrf
                                                        <input type="hidden" name="payment_method" value="upi">
                                                        <input type="hidden" name="payment_type" value="final">
                                                        <input type="hidden" name="amount" value="{{ $finalDue }}">
                                                        <button type="submit" onclick="return confirm('Please confirm you have scanned the QR code and completed the balance payment.')" class="w-full glass-button-primary font-bold py-2 px-4 rounded-lg">
                                                            Pay Balance (₹{{ number_format($finalDue, 2) }})
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-4 p-4 glass-card-subtle bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-center">
                                            <p class="text-emerald-400 font-bold text-lg">Payment Complete ✓</p>
                                        </div>
                                    @endif
                                @endif
                            @endif

                            @if(in_array($contract->status, ['signed', 'active', 'negotiating']))
                                <!-- Mark as Completed Button -->
                                <form method="POST" action="{{ route('contracts.complete', $contract) }}" class="mt-4">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Are you sure the product has been delivered and you want to mark this contract as completed?')" class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-blue-500/30 hover:scale-[1.02] transition-all duration-300 border border-blue-500/30">
                                        ✅ Mark as Delivered & Complete Contract
                                    </button>
                                </form>
                                <p class="text-xs text-gray-400 mt-2 text-center">Click this after product delivery is confirmed</p>
                            @endif

                        </div>
                    </div>
                    
                    <!-- Payment History -->
                    <div class="glass-card rounded-3xl shadow-lg p-6 border border-white/5">
                        <h3 class="text-lg font-bold text-white mb-4">Payment Record</h3>
                        @forelse($contract->payments as $payment)
                            <div class="flex justify-between items-center py-2 border-b last:border-0 border-white/5">
                                <div>
                                    <p class="font-semibold text-gray-100">₹{{ number_format($payment->amount, 2) }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                </div>
                                <span class="text-xs font-bold glass-badge glass-badge-success">Paid</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No payments have been made yet.</p>
                        @endforelse
                    </div>

                </div>

                <!-- Negotiation / Chat -->
                <div class="lg:col-span-2">
                    <div class="glass-card rounded-3xl shadow-lg border border-white/5 h-full flex flex-col">
                        <div class="p-6 border-b border-white/5 bg-transparent rounded-t-3xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold text-white">
                                        @if($contract->status === 'completed')
                                            Message History
                                        @elseif($contract->status === 'cancelled')
                                            Communication Closed
                                        @else
                                            Communication Room
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-400 mt-1">
                                        @if($contract->status === 'completed')
                                            Product delivered - Chat history for reference
                                        @elseif($contract->status === 'cancelled')
                                            This contract has been cancelled
                                        @else
                                            Chat with the {{ auth()->id() === $contract->buyer_id ? 'farmer' : 'buyer' }} privately
                                        @endif
                                    </p>
                                </div>
                                @if(!in_array($contract->status, ['cancelled', 'completed']))
                                    <span class="glass-badge-info px-3 py-1 rounded-full text-xs font-bold">
                                        💬 Active
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex-grow p-6 overflow-y-auto min-h-[400px] max-h-[500px] space-y-4 bg-transparent/50">
                            @forelse($contract->messages as $msg)
                                @if($msg->sender)
                                <div class="flex w-full {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[75%] rounded-2xl p-4 shadow-sm {{ $msg->sender_id === auth()->id() ? 'bg-gradient-to-br from-emerald-600/20 to-green-600/20 border border-emerald-500/30 text-gray-100 rounded-br-sm' : 'glass-card border border-white/10 text-gray-100 rounded-bl-sm' }}">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-sm font-bold opacity-75">{{ $msg->sender->name }}</p>
                                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $msg->sender->role === 'farmer' ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30' }}">
                                                {{ ucfirst($msg->sender->role) }}
                                            </span>
                                        </div>
                                        <p class="text-base">{{ $msg->body }}</p>
                                        <p class="text-xs opacity-50 text-right mt-2">{{ $msg->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @endif
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    <p>No messages yet. Start the conversation!</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="p-6 border-t border-white/5 glass-card rounded-b-3xl">
                            @if(!in_array($contract->status, ['cancelled']))
                                <form method="POST" action="{{ route('contracts.negotiate', $contract) }}">
                                    @csrf
                                    <div class="flex flex-col space-y-3">
                                        <textarea name="body" rows="2" class="w-full border-white/20 focus:border-white/50 focus:ring-emerald-500 rounded-xl shadow-sm resize-none glass-input" placeholder="Type your message here..." required></textarea>
                                        
                                        @if(in_array($contract->status, ['proposed', 'negotiating']))
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <div class="flex-1">
                                                <input type="number" step="0.01" name="suggested_price" placeholder="Propose New Price (optional)" class="w-full text-sm glass-input rounded-lg">
                                            </div>
                                            <div class="flex-1">
                                                <input type="number" step="0.01" name="suggested_quantity" placeholder="Propose New Qty (optional)" class="w-full text-sm glass-input rounded-lg">
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <button type="submit" class="glass-button-primary font-bold py-2 px-6 rounded-lg whitespace-nowrap self-end">
                                            Send Message
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="text-center p-4 bg-transparent rounded-xl text-gray-300 font-medium">
                                    This contract has been cancelled. Communication is closed.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Success Message Popup -->
    @if(session('success'))
    <div id="successPopup" class="fixed top-6 right-6 z-50 animate-slide-in">
        <div class="glass-card border border-emerald-500/30 bg-gradient-to-r from-emerald-600/90 to-green-600/90 backdrop-blur-xl rounded-xl shadow-2xl p-4 flex items-center gap-3 min-w-[300px]">
            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-white font-semibold">Success!</p>
                <p class="text-white/80 text-sm">{{ session('success') }}</p>
            </div>
            <button onclick="closeSuccessPopup()" class="flex-shrink-0 text-white/70 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <script>
        // Auto-close success popup after 4 seconds
        setTimeout(function() {
            closeSuccessPopup();
        }, 4000);

        function closeSuccessPopup() {
            const popup = document.getElementById('successPopup');
            if (popup) {
                popup.style.opacity = '0';
                popup.style.transform = 'translateX(100%)';
                popup.style.transition = 'all 0.3s ease-out';
                setTimeout(function() {
                    popup.remove();
                }, 300);
            }
        }

        // Auto-scroll chat to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const chatContainer = document.querySelector('.flex-grow.p-6.overflow-y-auto');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });
    </script>

    <style>
        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-slide-in {
            animation: slide-in 0.4s ease-out;
        }
    </style>
</x-app-layout>
