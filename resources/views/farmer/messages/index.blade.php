<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card rounded-xl shadow-lg overflow-hidden">
                @if($contracts->count() > 0)
                    <div class="divide-y">
                        @foreach($contracts as $contract)
                            <a href="{{ route('farmer.messages.show', $contract) }}" class="block p-6 hover:bg-transparent transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg">{{ $contract->buyer->name ?? 'N/A' }}</h3>
                                        <p class="text-sm text-gray-300">{{ $contract->crop_name ?? 'N/A' }} - Contract #{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        @if($contract->messages->count() > 0)
                                            <p class="text-sm text-gray-400 mt-2 truncate">{{ $contract->messages->first()->body }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if($contract->messages->count() > 0)
                                            <p class="text-xs text-gray-400">{{ $contract->messages->first()->created_at->diffForHumans() }}</p>
                                        @endif
                                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-900/50 text-emerald-300">
                                            {{ $contract->messages->count() }} messages
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12v.01"></path>
                        </svg>
                        <p class="text-gray-400 text-lg">No conversations yet</p>
                        <p class="text-gray-400 text-sm mt-2">Messages from buyers will appear here</p>
                        <a href="{{ route('farmer.contract-requests') }}" class="inline-block mt-4 px-6 py-2  font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                            View Contract Requests
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
