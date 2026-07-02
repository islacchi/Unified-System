<div>
    <template x-teleport="body">
        <div x-show="show"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
             style="display: none;">
            <div class="bg-white dark:bg-[var(--surface)] prime:bg-white rounded-xl border border-gray-200 dark:border-[var(--border)] prime:border-green-900 p-6 max-w-md w-full mx-4 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-[var(--text-1)] prime:text-gray-900 mb-2" x-text="title"></h3>
                <p class="text-sm text-gray-600 dark:text-[var(--text-3)] prime:text-gray-500 mb-6" x-text="message"></p>
                <div class="flex items-center justify-end gap-3">
                    <button type="button"
                            @click="$wire.cancel()"
                            class="px-4 py-2 rounded-lg border border-gray-200 dark:border-[var(--border)] prime:border-green-900 text-gray-700 dark:text-[var(--text-2)] prime:text-gray-900 hover:bg-gray-50 dark:hover:bg-[var(--surface-3)] prime:hover:bg-green-50 transition text-sm">
                        Cancel
                    </button>
                    <button type="button"
                            @click="$wire.confirm()"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-medium">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>