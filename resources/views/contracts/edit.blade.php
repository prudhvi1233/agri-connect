<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Edit Contract') }} - #{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
            <a href="{{ route('contracts.show', $contract) }}" class="px-4 py-2 glass-button-secondary font-medium rounded-lg">
                ← Back to Contract
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="glass-card-subtle border-l-4 border-amber-500 rounded-lg p-4 mb-6">
                <p class="text-amber-300 font-medium">⚠️ Editing Contract</p>
                <p class="text-sm text-amber-400 mt-1">You are editing a pending contract. Changes will be sent to the farmer for review.</p>
            </div>

            <form method="POST" action="{{ route('contracts.update', $contract) }}" id="contractForm">
                @csrf
                @method('PUT')

                <!-- Farmer Selection -->
                <div class="glass-card rounded-xl p-6 shadow-lg border border-white/5 mb-6">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Select Farmer
                    </h3>

                    <div>
                        <label for="farmer_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Farmer <span class="text-red-400">*</span>
                        </label>
                        <select name="farmer_id" id="farmer_id" required
                                class="w-full glass-input rounded-lg">
                            <option value="" class="bg-gray-900 text-gray-200">Select a farmer...</option>
                            @foreach($farmers as $farmer)
                                <option value="{{ $farmer->id }}" {{ $contract->farmer_id == $farmer->id ? 'selected' : '' }} class="bg-gray-900 text-gray-200">
                                    {{ $farmer->name }} ({{ $farmer->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Crop Details -->
                <div class="glass-card rounded-xl p-6 shadow-lg border border-white/5 mb-6">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"></path>
                        </svg>
                        Crop Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="crop_name" class="block text-sm font-medium text-gray-300 mb-2">
                                Crop Name <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="crop_name" id="crop_name" 
                                   value="{{ old('crop_name', $contract->crop_name) }}"
                                   required
                                   class="w-full glass-input rounded-lg">
                        </div>

                        <div>
                            <label for="quantity_required" class="block text-sm font-medium text-gray-300 mb-2">
                                Quantity Required (kg) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="quantity_required" id="quantity_required" 
                                   value="{{ old('quantity_required', $contract->agreed_quantity) }}"
                                   min="1" step="0.01" required
                                   class="w-full glass-input rounded-lg">
                        </div>

                        <div>
                            <label for="price_per_unit" class="block text-sm font-medium text-gray-300 mb-2">
                                Price per Unit (₹) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="price_per_unit" id="price_per_unit" 
                                   value="{{ old('price_per_unit', $contract->price_per_unit) }}"
                                   min="0" step="0.01" required
                                   class="w-full glass-input rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Delivery Details -->
                <div class="glass-card rounded-xl p-6 shadow-lg border border-white/5 mb-6">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                        Delivery Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="delivery_date" class="block text-sm font-medium text-gray-300 mb-2">
                                Delivery Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="delivery_date" id="delivery_date" 
                                   value="{{ old('delivery_date', $contract->delivery_date) }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                                   class="w-full glass-input rounded-lg">
                        </div>

                        <div>
                            <label for="delivery_location" class="block text-sm font-medium text-gray-300 mb-2">
                                Delivery Location <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="delivery_location" id="delivery_location" 
                                   value="{{ old('delivery_location', $contract->delivery_location) }}"
                                   required
                                   class="w-full glass-input rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Payment Terms -->
                <div class="glass-card rounded-xl p-6 shadow-lg border border-white/5 mb-6">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Payment Terms
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="advance_percentage" class="block text-sm font-medium text-gray-300 mb-2">
                                Advance Payment (%) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="advance_percentage" id="advance_percentage" 
                                   value="{{ old('advance_percentage', $contract->advance_percentage) }}"
                                   min="0" max="100" required
                                   class="w-full glass-input rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Calculated Amounts
                            </label>
                            <div class="glass-card-subtle rounded-lg p-3 border border-white/10">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-300">Total Amount:</span>
                                        <span id="totalAmountDisplay" class="font-bold text-white">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-300">Advance Amount:</span>
                                        <span id="advanceAmountDisplay" class="font-bold text-emerald-400">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-300">Final Payment:</span>
                                        <span id="finalPaymentDisplay" class="font-bold text-blue-400">₹0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Duration -->
                <div class="glass-card rounded-xl p-6 shadow-lg border border-white/5 mb-6">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Contract Duration
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="contract_start_date" class="block text-sm font-medium text-gray-300 mb-2">
                                Contract Start Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="contract_start_date" id="contract_start_date" 
                                   value="{{ old('contract_start_date', $contract->contract_start_date) }}"
                                   required
                                   class="w-full glass-input rounded-lg">
                        </div>

                        <div>
                            <label for="contract_end_date" class="block text-sm font-medium text-gray-300 mb-2">
                                Contract End Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="contract_end_date" id="contract_end_date" 
                                   value="{{ old('contract_end_date', $contract->contract_end_date) }}"
                                   required
                                   class="w-full glass-input rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Additional Terms -->
                <div class="glass-card rounded-xl p-6 shadow-lg border border-white/5 mb-6">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Additional Terms
                    </h3>

                    <div>
                        <label for="additional_terms" class="block text-sm font-medium text-gray-300 mb-2">
                            Terms & Conditions (Optional)
                        </label>
                        <textarea name="additional_terms" id="additional_terms" rows="5"
                                  class="w-full glass-input rounded-lg resize-none"
                                  placeholder="Quality standards, packaging requirements, penalty clauses, etc.">{{ old('additional_terms', $contract->additional_terms ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between items-center glass-card rounded-xl p-6 shadow-lg border border-white/5">
                    <a href="{{ route('contracts.index') }}" class="px-6 py-3 glass-button-secondary font-medium rounded-lg">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 glass-button-primary font-bold rounded-lg">
                        Update Contract
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Auto-Calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity_required');
            const priceInput = document.getElementById('price_per_unit');
            const advancePercentageInput = document.getElementById('advance_percentage');
            
            const totalAmountDisplay = document.getElementById('totalAmountDisplay');
            const advanceAmountDisplay = document.getElementById('advanceAmountDisplay');
            const finalPaymentDisplay = document.getElementById('finalPaymentDisplay');

            function calculateAmounts() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const pricePerUnit = parseFloat(priceInput.value) || 0;
                const advancePercentage = parseFloat(advancePercentageInput.value) || 0;

                const totalAmount = quantity * pricePerUnit;
                const advanceAmount = (totalAmount * advancePercentage) / 100;
                const finalPayment = totalAmount - advanceAmount;

                totalAmountDisplay.textContent = '₹' + totalAmount.toFixed(2);
                advanceAmountDisplay.textContent = '₹' + advanceAmount.toFixed(2);
                finalPaymentDisplay.textContent = '₹' + finalPayment.toFixed(2);
            }

            quantityInput.addEventListener('input', calculateAmounts);
            priceInput.addEventListener('input', calculateAmounts);
            advancePercentageInput.addEventListener('input', calculateAmounts);

            // Initial calculation
            calculateAmounts();
        });
    </script>
</x-app-layout>
