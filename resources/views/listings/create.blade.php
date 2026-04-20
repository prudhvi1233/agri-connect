<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Post New Crop Listing') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card overflow-hidden shadow-xl sm:rounded-3xl border border-white/5">
                <div class="p-8 sm:p-12">
                    <div class="mb-8 text-center">
                        <h3 class="text-3xl font-extrabold text-white">Crop Details</h3>
                        <p class="text-gray-400 mt-2">Fill in the information below to list your crop on the marketplace.</p>
                    </div>

                    <form method="POST" action="{{ route('listings.store') }}" class="space-y-6">
                        @csrf
                        
                        <div>
                            <x-input-label for="crop_name" value="Crop Name" />
                            <x-text-input id="crop_name" class="block mt-1 w-full" type="text" name="crop_name" :value="old('crop_name')" required autofocus placeholder="e.g. Organic Wheat" />
                            <x-input-error :messages="$errors->get('crop_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full bg-white/5 border border-white/20 text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 rounded-lg backdrop-blur-sm transition-all duration-300 px-4 py-2.5" placeholder="Provide details about the crop quality, farming methods, etc.">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="quantity" value="Quantity Available" />
                                <x-text-input id="quantity" class="block mt-1 w-full" type="number" step="0.01" name="quantity" :value="old('quantity')" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="unit" value="Unit of Measurement" />
                                <select id="unit" name="unit" class="block mt-1 w-full glass-select px-4 py-2.5">
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Kilograms (kg)</option>
                                    <option value="tons" {{ old('unit') == 'tons' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Tons</option>
                                    <option value="lbs" {{ old('unit') == 'lbs' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Pounds (lbs)</option>
                                    <option value="bushels" {{ old('unit') == 'bushels' ? 'selected' : '' }} class="bg-gray-900 text-gray-200">Bushels</option>
                                </select>
                                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="expected_price" value="Expected Price (₹)" />
                                <x-text-input id="expected_price" class="block mt-1 w-full" type="number" step="0.01" name="expected_price" :value="old('expected_price')" required />
                                <p class="text-xs text-gray-400 mt-1">Total price for the specified quantity.</p>
                                <x-input-error :messages="$errors->get('expected_price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="harvest_date" value="Estimated Harvest Date" />
                                <x-text-input id="harvest_date" class="block mt-1 w-full" type="date" name="harvest_date" :value="old('harvest_date')" />
                                <x-input-error :messages="$errors->get('harvest_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/5 flex items-center justify-end space-x-4">
                            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 glass-button-secondary">
                                Cancel
                            </a>
                            <x-primary-button class="glass-button-primary">
                                {{ __('Create Listing') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
