<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">{{ __('Make Payment') }}</h2>
            <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium border border-white/20 rounded-lg transition-colors">← Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Global Error Display -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl glass-card border border-red-500/50 bg-red-500/10 backdrop-blur-md">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <ul class="list-disc list-inside text-red-300 text-sm font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="glass-card rounded-xl shadow-lg p-6">
                <!-- Contract Info -->
                <div class="bg-white/5 border border-white/10 p-5 rounded-xl mb-6 backdrop-blur-md">
                    <h3 class="font-bold text-lg mb-2 text-white">Contract #{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</h3>
                    <p class="text-gray-300">{{ $contract->crop_name }} - {{ number_format($contract->agreed_quantity, 2) }} kg</p>
                    <p class="text-2xl font-bold text-blue-400 mt-2">Total: ₹{{ number_format($contract->total_amount, 2) }}</p>
                </div>

                <!-- Payment Summary -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-green-500/10 border border-green-500/20 p-5 rounded-xl backdrop-blur-md">
                        <p class="text-sm text-gray-400 mb-1">Advance Amount</p>
                        <p class="text-2xl font-bold text-white">₹{{ number_format($advanceAmount, 2) }}</p>
                        <p class="text-sm text-green-400 mt-2 font-medium">Paid: ₹{{ number_format($advancePaid, 2) }}</p>
                    </div>
                    <div class="bg-purple-500/10 border border-purple-500/20 p-5 rounded-xl backdrop-blur-md">
                        <p class="text-sm text-gray-400 mb-1">Final Payment</p>
                        <p class="text-2xl font-bold text-white">₹{{ number_format($finalPayment, 2) }}</p>
                        <p class="text-sm text-purple-400 mt-2 font-medium">Paid: ₹{{ number_format($finalPaid, 2) }}</p>
                    </div>
                </div>

                <!-- Payment Form -->
                <form method="POST" action="{{ route('payments.store', $contract) }}" id="paymentForm">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Payment Type -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300">Payment Type *</label>
                            <select name="payment_type" id="payment_type" required class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white focus:ring-green-500 focus:border-green-500 p-2.5" onchange="updateAmount()">
                                @if($advancePaid == 0)
                                    <option value="advance">Advance Payment (₹{{ number_format($advanceAmount, 2) }})</option>
                                    <option value="full">Full Payment (₹{{ number_format($contract->total_amount, 2) }})</option>
                                @endif
                                @if($finalPaid == 0 && $advancePaid > 0)
                                    <option value="final">Final Payment (₹{{ number_format($finalPayment, 2) }})</option>
                                @endif
                            </select>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300">Amount (₹) *</label>
                            <input type="number" name="amount" id="amount" step="0.01" required value="{{ $advancePaid == 0 ? $advanceAmount : $finalPayment }}" class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:ring-green-500 focus:border-green-500" placeholder="Enter amount">
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-300">Payment Method *</label>
                            <select name="payment_method" id="payment_method" required class="w-full bg-gray-900 border border-gray-700 rounded-lg text-white focus:ring-green-500 focus:border-green-500 p-2.5" onchange="togglePaymentFields()">
                                <option value="">Select method...</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="upi">UPI</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>

                        <!-- Card Number Field -->
                        <div id="card_field" class="hidden">
                            <label class="block text-sm font-medium mb-2 text-gray-300">Card Number</label>
                            <input type="text" name="card_number" id="card_number" maxlength="19" placeholder="1234 5678 9012 3456" class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- UPI ID Field & QR Code -->
                        <div id="upi_field" class="hidden space-y-4">
                            <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-center flex flex-col items-center">
                                <p class="text-sm text-gray-300 mb-4 font-medium">Scan QR to Pay via external app</p>
                                <div class="bg-white p-3 rounded-xl inline-block shadow-lg">
                                    <!-- Generating static QR for the UPI ID -->
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=7416004523@ibl" alt="UPI QR Code" class="w-48 h-48">
                                </div>
                                <p class="text-lg font-bold text-white mt-4 tracking-wide">7416004523@ibl</p>
                                <p class="text-sm text-yellow-500 mt-2 font-semibold">Payment must be made using this QR code only.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-300">Your UPI ID (For Verification)</label>
                                <input type="text" name="upi_id" placeholder="yourname@upi" class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-500 text-white font-bold py-3 rounded-lg shadow-[0_0_15px_rgba(34,197,94,0.4)] transition-all duration-300">
                            Pay & Process
                        </button>
                        <a href="{{ route('payments.index') }}" class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white font-medium border border-white/10 rounded-lg text-center transition-colors">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateAmount() {
            const type = document.getElementById('payment_type').value;
            const amountInput = document.getElementById('amount');
            if (type === 'advance') {
                amountInput.value = {{ $advanceAmount }};
            } else if (type === 'final') {
                amountInput.value = {{ $finalPayment }};
            } else if (type === 'full') {
                amountInput.value = {{ $contract->total_amount }};
            }
        }

        function togglePaymentFields() {
            const method = document.getElementById('payment_method').value;
            const isCard = ['credit_card', 'debit_card'].includes(method);
            const isUpi = method === 'upi';
            
            document.getElementById('card_field').classList.toggle('hidden', !isCard);
            document.getElementById('card_number').required = isCard;

            document.getElementById('upi_field').classList.toggle('hidden', !isUpi);
            document.querySelector('[name="upi_id"]').required = isUpi;
        }
    </script>
</x-app-layout>
