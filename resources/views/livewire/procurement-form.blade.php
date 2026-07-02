<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $procurementId ? 'Edit Procurement' : 'New Procurement' }}</h1>
            <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">{{ $procurementId ? 'Update procurement details' : 'Select items from awarded RFQs or add manually to create a procurement order' }}</p>
        </div>
        <a href="{{ route('procurements.index') }}"
           class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 hover:text-gray-900 dark:hover:text-[var(--text-1)] prime:hover:text-gray-900 border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:hover:border-[var(--accent)] prime:hover:border-green-400 px-4 py-2 rounded-lg transition">
            ← Back to tracker
        </a>
    </div>

    <form wire:submit.prevent="save">

        {{-- Section 1: Procurement Information --}}
        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6 mb-4">
            <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide mb-4">Procurement Information</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Procurement Number</label>
                    <input type="text" wire:model="procurement_number" readonly
                           class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-[var(--surface-2)]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Date Prepared <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="date_prepared"
                           class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                    @error('date_prepared') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Delivery Deadline</label>
                    <input type="date" wire:model="delivery_deadline"
                           class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                    @error('delivery_deadline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Prepared By <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="prepared_by" readonly
                           class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-[var(--surface-2)]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Status</label>
                    <select wire:model="status"
                            class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                        <option value="Draft">Draft</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Approved">Approved</option>
                        <option value="Ordered">Ordered</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 mb-1">Notes</label>
                    <textarea wire:model="notes" rows="3"
                              class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500"></textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section 2: RFQ Picker --}}
        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide">Add Items from Awarded RFQs</p>
                <button type="button" wire:click="toggleRfqPicker"
                        class="text-sm px-4 py-2 rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                    {{ $showRfqPicker ? 'Close Picker' : 'Browse RFQs' }}
                </button>
            </div>

            @if($showRfqPicker)
                <div class="border border-gray-200 dark:border-[var(--border)] prime:border-green-900 rounded-lg p-4 mb-4">
                    <input type="text" wire:model.live="rfqSearch" placeholder="Search RFQ number or agency name..."
                           class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">

                    @if($selectedRfqId)
                        <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-950 prime:bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-300 prime:text-blue-800">
                                Selected RFQ: <strong>{{ $selectedRfqId }}</strong>
                            </p>
                            <button type="button" wire:click="addRfqItems" wire:loading.attr="disabled"
                                    class="mt-2 text-sm px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Add Items to Procurement
                            </button>
                        </div>
                    @endif

                    <div class="max-h-64 overflow-y-auto">
                        @forelse($awardedRfqs as $rfq)
                            <div wire:click="selectRfq({{ $rfq->id }})"
                                 class="p-3 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 cursor-pointer border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100 last:border-0 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">{{ $rfq->rfq_number }}</p>
                                        <p class="text-xs text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 mt-0.5">{{ $rfq->agency->name ?? 'Unknown Agency' }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-800 dark:bg-green-950 dark:text-green-300 prime:bg-green-50 prime:text-green-800">
                                        {{ $rfq->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 dark:text-[var(--text-3)] prime:text-gray-400 mt-1">
                                    {{ $rfq->items->count() }} items &middot; Deadline: {{ $rfq->deadline?->format('M d, Y') ?? 'N/A' }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 p-4 text-center">No awarded RFQs found.</p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>

        {{-- Section 3: Procurement Items --}}
        <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs font-medium text-gray-400 dark:text-[var(--accent)] prime:text-green-700 uppercase tracking-wide">Items</p>
                <button type="button" wire:click="addItem"
                        class="text-sm px-4 py-2 rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition">
                    + Add Item
                </button>
            </div>

            @if(count($items) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Agency</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Brand</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Description <span class="text-red-500">*</span></th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Unit <span class="text-red-500">*</span></th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Qty <span class="text-red-500">*</span></th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Unit Price</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Total</th>
                                <th class="text-left py-2 px-2 font-medium text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500">Status</th>
                                <th class="py-2 px-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $index => $item)
                                <tr class="border-b border-gray-100 dark:border-[var(--border)] prime:border-green-100">
                                    <td class="py-2 px-2">
                                        <select wire:model="items.{{ $index }}.agency_id"
                                                class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                                            <option value="">Select agency...</option>
                                            @foreach($agencies as $agency)
                                                <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 px-2">
                                        <input type="text" wire:model="items.{{ $index }}.brand"
                                               placeholder="Brand"
                                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                                    </td>
                                    <td class="py-2 px-2">
                                        <input type="text" wire:model="items.{{ $index }}.item_description"
                                               placeholder="Item description" required
                                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                                        @error("items.{$index}.item_description") <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                                    </td>
                                    <td class="py-2 px-2">
                                        <input type="text" wire:model="items.{{ $index }}.unit"
                                               placeholder="Unit" required
                                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                                        @error("items.{$index}.unit") <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                                    </td>
                                    <td class="py-2 px-2">
                                        <input type="number" wire:model="items.{{ $index }}.quantity"
                                               placeholder="Qty" required step="0.01" min="0"
                                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                                        @error("items.{$index}.quantity") <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                                    </td>
                                    <td class="py-2 px-2">
                                        <input type="number" wire:model="items.{{ $index }}.unit_price"
                                               placeholder="0.00" step="0.01" min="0"
                                               class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] dark:placeholder-[var(--text-3)] prime:text-gray-900 prime:placeholder-green-600 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                                    </td>
                                    <td class="py-2 px-2 text-right font-mono text-xs">
                                        {{ number_format((float) ($item['unit_price'] ?? 0) * (float) ($item['quantity'] ?? 0), 2) }}
                                    </td>
                                    <td class="py-2 px-2">
                                        <select wire:model="items.{{ $index }}.status"
                                                class="w-full border border-gray-200 dark:border-[var(--border)] prime:border-green-900 dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:text-gray-900 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-[var(--accent)] prime:focus:ring-green-500">
                                            <option value="Pending">Pending</option>
                                            <option value="Ordered">Ordered</option>
                                            <option value="Delivered">Delivered</option>
                                            <option value="Received">Received</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </td>
                                    <td class="py-2 px-2 text-center">
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                                class="text-red-500 hover:text-red-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200 dark:border-[var(--border)] prime:border-green-900">
                                <td colspan="6" class="py-3 px-2 text-right font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">Total Amount:</td>
                                <td class="py-3 px-2 text-right font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900">
                                    <span class="inline-flex items-center justify-end gap-0.5">
                                        <span class="text-sm">₱ </span>
                                        <span class="font-mono">{{ number_format(collect($this->items)->sum(fn($item) => (float) ($item['unit_price'] ?? 0) * (float) ($item['quantity'] ?? 0)), 2) }}</span>
                                    </span>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-[var(--text-3)] prime:text-gray-500 text-center py-8">No items added yet. Add items manually or select from awarded RFQs above.</p>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('procurements.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition text-sm">
                Cancel
            </a>
            <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-medium disabled:opacity-50">
                <span wire:loading.remove>Save Procurement</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mt-4 p-3 bg-green-50 dark:bg-green-950 prime:bg-green-50 border border-green-200 dark:border-green-800 prime:border-green-200 rounded-lg">
                <p class="text-sm text-green-800 dark:text-green-300 prime:text-green-800">{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mt-4 p-3 bg-red-50 dark:bg-red-950 prime:bg-red-50 border border-red-200 dark:border-red-800 prime:border-red-200 rounded-lg">
                <p class="text-sm text-red-800 dark:text-red-300 prime:text-red-800">{{ session('error') }}</p>
            </div>
        @endif
    </form>
</div>