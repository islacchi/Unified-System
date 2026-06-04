@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">

    <div class="flex items-center gap-2 mb-5">
        <span class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500">My Account</span>
        <span class="text-gray-300 dark:text-gray-600 prime:text-gray-300">›</span>
        <span class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500">Edit Profile</span>
    </div>

    @if(session('message'))
        <div class="mb-4 bg-green-50 dark:bg-green-950 prime:bg-green-50 border border-green-200 dark:border-green-800 prime:border-green-200 text-green-800 dark:text-green-400 prime:text-green-800 text-sm px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 dark:bg-red-950 prime:bg-red-50 border border-red-200 dark:border-red-800 prime:border-red-200 text-red-800 dark:text-red-400 prime:text-red-800 text-sm px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-[#111111] prime:bg-white border border-gray-200 dark:border-red-900 prime:border-green-200 rounded-xl p-6">

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 bg-gray-50 dark:bg-[#1a1a1a] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 bg-gray-50 dark:bg-[#1a1a1a] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
            </div>

            {{-- Password reset section --}}
            <div class="border-t border-gray-100 dark:border-[#2a2a2a] prime:border-green-100 pt-4 mt-4">
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-3">
                    Change Password <span class="font-normal normal-case ml-1">(leave blank to keep current)</span>
                </p>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">New Password</label>
                        <input type="password" name="password"
                               placeholder="Min. 8 characters"
                               class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 bg-gray-50 dark:bg-[#1a1a1a] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                               placeholder="Repeat new password"
                               class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 bg-gray-50 dark:bg-[#1a1a1a] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500 transition">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="submit"
                        class="w-full py-2.5 text-sm font-semibold rounded-lg bg-gray-900 hover:bg-gray-800 dark:bg-red-600 dark:hover:bg-red-700 prime:bg-green-600 prime:hover:bg-green-700 text-white transition">
                    Save Changes
                </button>
                <a href="{{ url()->previous() }}"
                   class="w-full py-2.5 text-sm font-medium rounded-lg text-center border border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 text-gray-500 dark:text-gray-400 prime:text-gray-500 hover:bg-gray-50 dark:hover:bg-[#1a1a1a] prime:hover:bg-green-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection