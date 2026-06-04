@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">

    <div class="flex items-center gap-2 mb-5">
        <a href="{{ route('users.index') }}"
           class="text-sm text-gray-400 dark:text-gray-500 prime:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 prime:hover:text-gray-700 transition">
            Users
        </a>
        <span class="text-gray-300 dark:text-gray-600 prime:text-gray-300">›</span>
        <span class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500">Add User</span>
    </div>

    <div class="bg-white dark:bg-[#1a1f2e] prime:bg-white border border-gray-200 dark:border-[#333d55] prime:border-green-200 rounded-xl p-6">

        <form method="POST" action="{{ route('users.store') }}" class="space-y-4" autocomplete="off">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" autocomplete="new-name"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-gray-50 dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="new-email"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-gray-50 dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Role</label>
                <select name="role"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-gray-50 dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Password</label>
                <input type="password" name="password" autocomplete="new-password"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-gray-50 dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-gray-50 dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="submit"
                       class="w-full py-2.5 text-sm font-semibold rounded-lg bg-gray-900 hover:bg-gray-800 dark:bg-[var(--accent)] dark:hover:bg-[var(--accent-h)] prime:bg-green-600 prime:hover:bg-green-700 text-white transition">
                    Create User
                </button>
                <a href="{{ route('users.index') }}"
                   class="w-full py-2.5 text-sm font-medium rounded-lg text-center border border-gray-200 dark:border-[#333d55] prime:border-green-200 text-gray-500 dark:text-gray-400 prime:text-gray-500 hover:bg-gray-50 dark:hover:bg-[#222736] prime:hover:bg-green-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection