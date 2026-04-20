<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Create New Contract') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <div class="glass-card overflow-hidden shadow-2xl sm:rounded-3xl border border-white/5">
                <div class="p-8 sm:p-12">
                    <div class="mb-8">
                        <h3 class="text-3xl font-extrabold text-white mb-2">Create Farming Contract</h3>
                        <p class="text-gray-300">Fill in the contract details below. The farmer will review and approve your contract.</p>
                    </div>

                    <form method="POST" action="{{ route('contracts.store-contract') }}" class="space-y-8" id="contractForm">
                        @csrf
                        
                        <!-- Section 1: Farmer & Crop Details -->
                        <div class="glass-card-subtle p-6 rounded-2xl border border-blue-500/20">
                            <h4 class="text-xl font-bold text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Farmer & Crop Details
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="farmer_id" value="Select Farmer" />
                                    <select id="farmer_id" name="farmer_id" class="block mt-1 w-full glass-select px-4 py-2.5" required>
                                        <option value="" class="bg-gray-900 text-gray-200">-- Choose a Farmer --</option>
                                        @foreach($farmers as $farmer)
                                            <option value="{{ $farmer->id }}" {{ old('farmer_id') == $farmer->id ? 'selected' : '' }} class="bg-gray-900 text-gray-200">
                                                {{ $farmer->name }} ({{ $farmer->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('farmer_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="crop_name" value="Crop Name" />
                                    <x-text-input id="crop_name" class="block mt-1 w-full" type="text" name="crop_name" :value="old('crop_name')" required placeholder="e.g. Organic Wheat, Basmati Rice" />
                                    <x-input-error :messages="$errors->get('crop_name')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Quantity & Pricing -->
                        <div class="glass-card-subtle p-6 rounded-2xl border border-emerald-500/20">
                            <h4 class="text-xl font-bold text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Quantity & Pricing
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="quantity_required" value="Quantity Required (kg)" />
                                    <x-text-input id="quantity_required" class="block mt-1 w-full" type="number" step="0.01" name="quantity_required" :value="old('quantity_required')" required min="1" />
                                    <x-input-error :messages="$errors->get('quantity_required')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="price_per_unit" value="Price Per Unit (₹)" />
                                    <x-text-input id="price_per_unit" class="block mt-1 w-full" type="number" step="0.01" name="price_per_unit" :value="old('price_per_unit')" required min="0" />
                                    <x-input-error :messages="$errors->get('price_per_unit')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label value="Total Amount (₹)" />
                                    <div class="mt-1 p-3 glass-card rounded-xl border-2 border-emerald-500/30 text-2xl font-bold text-emerald-400" id="totalAmount">
                                        ₹0.00
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Auto-calculated</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Delivery Details -->
                        <div class="glass-card-subtle p-6 rounded-2xl border border-amber-500/20">
                            <h4 class="text-xl font-bold text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                                Delivery Details
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="delivery_date" value="Delivery Date" />
                                    <x-text-input id="delivery_date" class="block mt-1 w-full" type="date" name="delivery_date" :value="old('delivery_date')" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" />
                                    <x-input-error :messages="$errors->get('delivery_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="delivery_location" value="Delivery Location" />
                                    <x-text-input id="delivery_location" class="block mt-1 w-full" type="text" name="delivery_location" :value="old('delivery_location')" required placeholder="e.g. Warehouse 5, Market Road" />
                                    <x-input-error :messages="$errors->get('delivery_location')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Payment Terms -->
                        <div class="glass-card-subtle p-6 rounded-2xl border border-purple-500/20">
                            <h4 class="text-xl font-bold text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Payment Terms
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="advance_percentage" value="Advance Percentage (%)" />
                                    <x-text-input id="advance_percentage" class="block mt-1 w-full" type="number" step="0.01" name="advance_percentage" :value="old('advance_percentage', 50)" required min="0" max="100" />
                                    <x-input-error :messages="$errors->get('advance_percentage')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label value="Advance Amount (₹)" />
                                    <div class="mt-1 p-3 glass-card rounded-xl border-2 border-purple-500/30 text-xl font-bold text-purple-400" id="advanceAmount">
                                        ₹0.00
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Auto-calculated</p>
                                </div>

                                <div>
                                    <x-input-label value="Final Payment (₹)" />
                                    <div class="mt-1 p-3 glass-card rounded-xl border-2 border-purple-500/30 text-xl font-bold text-purple-400" id="finalPayment">
                                        ₹0.00
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Auto-calculated</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Contract Period -->
                        <div class="glass-card-subtle p-6 rounded-2xl border border-teal-500/20">
                            <h4 class="text-xl font-bold text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Contract Period
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="contract_start_date" value="Contract Start Date" />
                                    <x-text-input id="contract_start_date" class="block mt-1 w-full" type="date" name="contract_start_date" :value="old('contract_start_date')" required />
                                    <x-input-error :messages="$errors->get('contract_start_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="contract_end_date" value="Contract End Date" />
                                    <x-text-input id="contract_end_date" class="block mt-1 w-full" type="date" name="contract_end_date" :value="old('contract_end_date')" required />
                                    <x-input-error :messages="$errors->get('contract_end_date')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Additional Terms -->
                        <div class="glass-card-subtle p-6 rounded-2xl border border-white/10">
                            <h4 class="text-xl font-bold text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Additional Terms & Conditions
                            </h4>
                            
                            <div>
                                <textarea id="additional_terms" name="additional_terms" rows="5" class="block mt-1 w-full bg-white/5 border border-white/20 text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 rounded-lg backdrop-blur-sm transition-all duration-300 px-4 py-2.5 resize-none" placeholder="Enter any additional terms, quality requirements, penalties, or special conditions...">{{ old('additional_terms') }}</textarea>
                                <p class="text-sm text-gray-400 mt-2">Optional: Include quality standards, delivery conditions, penalty clauses, etc.</p>
                                <x-input-error :messages="$errors->get('additional_terms')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-end gap-4">
                            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center px-6 py-3 glass-button-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 glass-button-primary">
                                Create & Send to Farmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Auto-Calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity_required');
            const priceInput = document.getElementById('price_per_unit');
            const advancePercentageInput = document.getElementById('advance_percentage');
            
            const totalAmountDisplay = document.getElementById('totalAmount');
            const advanceAmountDisplay = document.getElementById('advanceAmount');
            const finalPaymentDisplay = document.getElementById('finalPayment');

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

            // Calculate on page load if values exist
            calculateAmounts();
        });
    </script>
</x-app-layout>
