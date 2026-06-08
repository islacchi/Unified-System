@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">

    <div class="flex items-center gap-2 mb-6">
        <span class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500">Settings</span>
        <span class="text-gray-300 dark:text-gray-600 prime:text-gray-300">›</span>
        <span class="text-sm text-gray-900 dark:text-gray-100 prime:text-gray-900 font-medium">System Defaults</span>
    </div>

    @if(session('message'))
        <div class="mb-4 bg-green-50 dark:bg-green-950 prime:bg-green-50 border border-green-200 dark:border-green-800 prime:border-green-200 text-green-800 dark:text-green-400 prime:text-green-800 text-sm px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-[#1a1f2e] prime:bg-white border border-gray-200 dark:border-[#333d55] prime:border-green-200 rounded-xl overflow-hidden shadow-sm">
        <form method="POST" action="{{ route('settings.update') }}" class="px-6 py-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900 uppercase tracking-wide mb-1">Default Password for New Staff</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400 mb-3">
                    The password that is set when admin resets a user's password or creates a new staff account.
                </p>
                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Default Password</label>
                <input type="text" name="default_password" value="{{ old('default_password', $defaultPassword) }}"
                       class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-white dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-blue-500/50 prime:focus:ring-green-500/50 transition placeholder-gray-400">
                @error('default_password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-gray-100 dark:border-[#2a3042] prime:border-green-100 pt-5">
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ url()->previous() }}"
                       class="px-5 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 text-gray-600 dark:text-gray-400 prime:text-gray-600 hover:bg-gray-50 dark:hover:bg-[#222736] prime:hover:bg-green-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold rounded-lg bg-gray-900 hover:bg-gray-800 dark:bg-blue-600 dark:hover:bg-blue-700 prime:bg-green-600 prime:hover:bg-green-700 text-white transition shadow-sm">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection