<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Procurement Tracker</h1>
            <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">Manage and track all procurement orders</p>
        </div>
        <a href="{{ route('procurements.create') }}"
           class="text-sm px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition font-medium">
            + New Procurement
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" wire:model.live="search" placeholder="Search procurement #"
                       class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
            </div>
            <div>
                <select wire:model.live="statusFilter"
                        class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="text" wire:model.live="itemSearch" placeholder="Search item name or brand..."
                       class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
            </div>
            <div>
                <input type="date" wire:model.live="dateFrom" placeholder="From date"
                       class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
            </div>
            <div>
                <input type="date" wire:model.live="dateTo" placeholder="To date"
                       class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
            </div>
        </div>
    </div>

    {{-- Table --}}
    {{-- overflow-hidden removed from outer div; overflow-x-auto alone handles horizontal scroll --}}
    <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Procurement #</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Date Prepared</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Delivery Deadline</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total Amount</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Items</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($procurements as $procurement)
                        <tr class="border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                            <td class="py-3 px-4 font-mono text-xs">
                                <a href="{{ route('procurements.show', $procurement) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $procurement->procurement_number }}
                                </a>
                            </td>
                            <td class="py-3 px-4">{{ $procurement->date_prepared->format('M d, Y') }}</td>
                            <td class="py-3 px-4">{{ $procurement->delivery_deadline?->format('M d, Y') ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-0.5">
                                    <span class="text-sm">₱</span>
                                    <span class="font-mono">{{ number_format($procurement->total_amount, 2) }}</span>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $statusColors = [
                                        'Draft'     => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 prime:bg-gray-100 prime:text-gray-800',
                                        'Submitted' => 'bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300 prime:bg-blue-50 prime:text-blue-800',
                                        'Approved'  => 'bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-50 prime:text-green-800',
                                        'Ordered'   => 'bg-purple-50 text-purple-800 dark:bg-purple-950 dark:text-purple-300 prime:bg-purple-50 prime:text-purple-800',
                                        'Delivered' => 'bg-teal-50 text-teal-800 dark:bg-teal-950 dark:text-teal-300 prime:bg-teal-50 prime:text-teal-800',
                                        'Cancelled' => 'bg-red-50 text-red-800 dark:bg-red-950 dark:text-red-400 prime:bg-red-50 prime:text-red-700',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$procurement->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $procurement->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">{{ $procurement->items->count() }}</td>
                            <td class="py-3 px-4 text-right">
                                {{--
                                    Dropdown uses position:fixed with viewport coords to escape
                                    overflow clipping from the overflow-x-auto scroll container.
                                    toggle() reads the button's getBoundingClientRect() to set
                                    exact top/right values before opening.
                                --}}
                                <div class="inline-block text-left"
                                     x-data="{
                                         open: false,
                                         top: 0,
                                         right: 0,
                                         above: false,
                                         toggle() {
                                             const btn = $refs.trigger;
                                             const rect = btn.getBoundingClientRect();
                                             this.above = (window.innerHeight - rect.bottom) < 180;
                                             this.top = this.above ? rect.top : rect.bottom + 4;
                                             this.right = window.innerWidth - rect.right;
                                             this.open = !this.open;
                                         }
                                     }"
                                     @click.outside="open = false"
                                     @keydown.escape.window="open = false">

                                    <button x-ref="trigger"
                                            @click="toggle()"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-[var(--surface-3)] dark:hover:text-[var(--text-1)] prime:hover:bg-green-50 prime:hover:text-gray-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="5" r="1.5"/>
                                            <circle cx="12" cy="12" r="1.5"/>
                                            <circle cx="12" cy="19" r="1.5"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         :style="above
                                             ? `position:fixed; bottom:${window.innerHeight - top + 4}px; right:${right}px; transform-origin: bottom right;`
                                             : `position:fixed; top:${top}px; right:${right}px; transform-origin: top right;`"
                                         class="z-50 w-44 rounded-lg bg-white dark:bg-[var(--surface-2)] prime:bg-white border border-gray-200 dark:border-[var(--border)] prime:border-green-900 shadow-lg py-1"
                                         style="display: none;">

                                        <a href="{{ route('procurements.show', $procurement) }}"
                                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-[var(--text-2)] prime:text-gray-700 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </a>

                                        <a href="{{ route('procurements.print', $procurement) }}" target="_blank"
                                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-[var(--text-2)] prime:text-gray-700 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                            </svg>
                                            Print
                                        </a>

                                        @if($procurement->status === 'Draft')
                                            <a href="{{ route('procurements.edit', $procurement) }}"
                                               class="flex items-center gap-2 px-3 py-2 text-sm text-blue-600 dark:text-blue-400 prime:text-blue-600 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>

                                            <div class="border-t border-gray-100 dark:border-[var(--border)] prime:border-green-100 my-1"></div>

                                            <button wire:click="delete({{ $procurement->id }})"
                                                    wire:confirm="Are you sure you want to delete this procurement?"
                                                    @click="open = false"
                                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 prime:text-red-600 hover:bg-red-50 dark:hover:bg-red-950 prime:hover:bg-red-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">
                                No procurements found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-[var(--border)] prime:border-green-900">
            {{ $procurements->links() }}
        </div>
    </div>
</div>