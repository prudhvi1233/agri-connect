<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">Chat with {{ $contract->buyer->name ?? 'N/A' }}</h2>
            <a href="{{ route('farmer.messages.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">← Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card rounded-xl shadow-lg overflow-hidden" style="height: 70vh; display: flex; flex-direction: column;">
                
                <!-- Contract Info Bar -->
                <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white p-4">
                    <p class="font-bold">{{ $contract->buyer->name ?? 'N/A' }} - {{ $contract->crop_name ?? 'N/A' }}</p>
                    <p class="text-sm opacity-90">Contract #{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-transparent" id="messagesContainer">
                    @forelse($contract->messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-md">
                                <div class="{{ $message->sender_id === auth()->id() ? 'bg-gradient-to-br from-emerald-600/20 to-green-600/20 border border-emerald-500/30 rounded-lg' : 'glass-card border' }} rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($message->sender_id !== auth()->id())
                                            <p class="text-xs font-bold {{ $message->sender_id === auth()->id() ? 'text-emerald-100' : 'text-gray-300' }}">
                                                {{ $message->sender->name }}
                                            </p>
                                        @endif
                                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $message->sender->role === 'farmer' ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30' }}">
                                            {{ ucfirst($message->sender->role) }}
                                        </span>
                                    </div>
                                    <p>{{ $message->body }}</p>
                                    @if($message->attachment)
                                        <a href="{{ route('messages.download', $message) }}" class="inline-block mt-2 text-sm underline">📎 Download Attachment</a>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                                    {{ $message->created_at->format('M d, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-400">No messages yet</p>
                            <p class="text-gray-400 text-sm">Start the conversation</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Form -->
                <div class="border-t p-4 glass-card">
                    <form method="POST" action="{{ route('farmer.messages.store', $contract) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <textarea name="body" rows="2" required class="w-full border-white/20 rounded-lg" placeholder="Type your message..."></textarea>
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="file" name="attachment" class="hidden" onchange="showFileName(this)">
                                    <span class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">📎 Attach File</span>
                                </label>
                                <span id="fileName" class="ml-2 text-sm text-gray-300"></span>
                            </div>
                            <button type="submit" class="px-6 py-2  font-bold rounded-lg hover:bg-emerald-700">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Scroll to bottom on page load
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;

        function showFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : '';
            document.getElementById('fileName').textContent = fileName;
        }
    </script>

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
                <p class="text-white font-semibold">Message Sent Successfully!</p>
                <p class="text-white/80 text-sm">{{ session('success') }}</p>
            </div>
            <button onclick="closeSuccessPopup()" class="flex-shrink-0 text-white/70 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

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
