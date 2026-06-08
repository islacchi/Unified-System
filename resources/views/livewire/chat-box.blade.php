<div class="fixed bottom-4 right-4 z-50" x-data="{ open: @entangle('open') }">

    {{-- Toggle button --}}
    <button @click="open = !open; $wire.markAsRead()"
            class="relative w-12 h-12 rounded-full bg-gray-900 dark:bg-[var(--accent)] prime:bg-green-600 text-white shadow-lg flex items-center justify-center hover:scale-105 transition-transform">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 16c0 1.1-.9 2-2 2H7l-4 4V6a2 2 0 012-2h14a2 2 0 012 2v10z"/>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center font-bold">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Chat window --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         style="display:none; width: 360px; height: 520px"
         class="absolute bottom-14 right-0 bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl shadow-2xl border border-gray-200 dark:border-[var(--border)] prime:border-green-200 overflow-hidden flex flex-col">

        {{-- Header --}}
        <div class="px-4 py-3 border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100 bg-gray-50 dark:bg-[var(--surface-2)] prime:bg-green-50">
            <p class="text-sm font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Team Chat</p>

            {{-- Channel tabs --}}
            <div class="flex items-center gap-1 mt-2 overflow-x-auto scrollbar-hide">
                <button wire:click="setReceiver(null)"
                        class="shrink-0 px-2.5 py-1 rounded-full text-xs font-medium transition
                            {{ $receiverId === null
                                ? 'bg-gray-900 text-white dark:bg-[var(--accent)] prime:bg-green-600 prime:text-white'
                                : 'text-gray-500 hover:bg-gray-200 dark:text-[var(--text-3)] dark:hover:bg-[var(--surface-3)] prime:text-gray-500 prime:hover:bg-green-100' }}">
                    All
                </button>
                @foreach($users as $user)
                    <button wire:click="setReceiver({{ $user->id }})"
                            class="relative shrink-0 px-2.5 py-1 rounded-full text-xs font-medium transition
                                {{ $receiverId === $user->id
                                    ? 'bg-gray-900 text-white dark:bg-[var(--accent)] prime:bg-green-600 prime:text-white'
                                    : 'text-gray-500 hover:bg-gray-200 dark:text-[var(--text-3)] dark:hover:bg-[var(--surface-3)] prime:text-gray-500 prime:hover:bg-green-100' }}">
                        {{ $user->name }}
                        @if(isset($unreadPerUser[$user->id]) && $unreadPerUser[$user->id] > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">
                                {{ $unreadPerUser[$user->id] }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3"
             wire:poll.4000ms="$refresh"
             x-init="$nextTick(() => $el.scrollTop = $el.scrollHeight)"
             @chat-sent.window="$nextTick(() => $el.scrollTop = $el.scrollHeight)"
             id="chat-messages">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} gap-2">
                    @if($message->sender_id !== auth()->id())
                        <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-[var(--surface-3)] prime:bg-green-100 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-[var(--text-2)] prime:text-green-700 shrink-0 mt-1">
                            {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="max-w-[70%]">
                        @if($message->sender_id !== auth()->id())
                            <p class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mb-0.5 ml-1">{{ $message->sender->name }}</p>
                        @endif
                        <div class="px-3 py-2 rounded-2xl text-sm
                            {{ $message->sender_id === auth()->id()
                                ? 'bg-gray-900 dark:bg-[var(--accent)] prime:bg-green-600 text-white rounded-br-sm'
                                : 'bg-gray-100 dark:bg-[var(--surface-3)] prime:bg-green-50 text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900 rounded-bl-sm' }}">
                            {{ $message->body }}
                        </div>
                        <p class="text-xs text-gray-300 dark:text-[var(--text-3)] prime:text-gray-300 mt-0.5 {{ $message->sender_id === auth()->id() ? 'text-right mr-1' : 'ml-1' }}">
                            {{ $message->created_at->format('h:i A') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="flex items-center justify-center h-full">
                    <p class="text-sm text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400">No messages yet. Say hi! 👋</p>
                </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="px-4 py-3 border-t border-gray-100 dark:border-[var(--border)] prime:border-green-100">
            <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                <input wire:model="body"
                       type="text"
                       placeholder="{{ $receiverId === null ? 'Message everyone...' : 'Message ' . ($users->find($receiverId)?->name ?? '') . '...' }}"
                       class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-200 bg-gray-50 dark:bg-[var(--surface-2)] prime:bg-white text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900 placeholder-gray-400 dark:placeholder-[var(--text-3)] focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-[var(--accent)] prime:focus:ring-green-400 transition">
                <button type="submit"
                        class="w-8 h-8 rounded-lg bg-gray-900 dark:bg-[var(--accent)] prime:bg-green-600 text-white flex items-center justify-center hover:opacity-90 transition shrink-0">
                    <svg class="w-4 h-4 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>