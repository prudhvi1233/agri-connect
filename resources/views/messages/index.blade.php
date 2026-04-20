<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">{{ __('Messages') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card rounded-xl shadow-lg overflow-hidden">
                @if($contracts->count() > 0)
                    <div class="divide-y">
                        @foreach($contracts as $contract)
                            <a href="{{ route('messages.show', $contract) }}" class="block p-6 hover:bg-blue-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg">{{ $contract->farmer->name }}</h3>
                                        <p class="text-sm text-gray-300">{{ $contract->crop_name }} - Contract #{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        @if($contract->messages->count() > 0)
                                            <p class="text-sm text-gray-400 mt-2 truncate">{{ $contract->messages->first()->body }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if($contract->messages->count() > 0)
                                            <p class="text-xs text-gray-400">{{ $contract->messages->first()->created_at->diffForHumans() }}</p>
                                        @endif
                                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold bg-blue-900/50 text-blue-300">
                                            {{ $contract->messages->count() }} messages
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <p class="text-gray-400 text-lg">No conversations yet</p>
                        <p class="text-gray-400 text-sm mt-2">Start a conversation from your contracts page</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
