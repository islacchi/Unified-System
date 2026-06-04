@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6">

    <div class="flex items-center gap-2 mb-6">
        <span class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500">My Account</span>
        <span class="text-gray-300 dark:text-gray-600 prime:text-gray-300">›</span>
        <span class="text-sm text-gray-900 dark:text-gray-100 prime:text-gray-900 font-medium">Edit Profile</span>
    </div>

    @if(session('message'))
        <div class="mb-4 bg-green-50 dark:bg-green-950 prime:bg-green-50 border border-green-200 dark:border-green-800 prime:border-green-200 text-green-800 dark:text-green-400 prime:text-green-800 text-sm px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
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

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-[#1a1f2e] prime:bg-white border border-gray-200 dark:border-[#2a2a3a] prime:border-green-200 rounded-xl overflow-hidden shadow-sm">

            {{-- Profile Header --}}
            <div class="px-6 py-5 border-b border-gray-100 dark:border-[#2a2a3a] prime:border-green-100" x-data="{ showModal: false }">
                <div class="flex items-center gap-4">
                    <div class="relative shrink-0">
                        <div class="w-14 h-14 rounded-full bg-gray-900 dark:bg-[#2a2a3a] prime:bg-green-100 flex items-center justify-center text-lg font-bold text-white dark:text-gray-200 prime:text-green-700 ring-2 ring-gray-200 dark:ring-[#3a3a4a] prime:ring-green-200 cursor-pointer hover:ring-gray-400 dark:hover:ring-white prime:hover:ring-green-300 transition"
                             @click="showModal = true">
                            @if($user->avatar)
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover">
                            @else
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            @endif
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-[#1a1f2e] prime:border-white {{ $user->isAdmin() ? 'bg-red-500' : 'bg-green-500' }}"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900">{{ $user->name }}</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 prime:text-gray-500 mt-0.5">{{ $user->email }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $user->isAdmin()
                                    ? 'bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-400 prime:bg-green-100 prime:text-green-800'
                                    : 'bg-gray-100 text-gray-600 dark:bg-[#2a2a3a] dark:text-gray-400 prime:bg-gray-100 prime:text-gray-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $user->isAdmin() ? 'bg-red-500' : 'bg-gray-400' }}"></span>
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400">Joined {{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Modal --}}
                <div x-show="showModal"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display:none"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
                     @click="showModal = false">
                    <div class="relative max-w-lg mx-4" @click.stop>
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-full rounded-xl shadow-2xl">
                        <button @click="showModal = false"
                                class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-gray-900 dark:bg-red-600 prime:bg-green-600 text-white flex items-center justify-center text-sm font-bold shadow-lg hover:scale-110 transition">
                            ✕
                        </button>
                    </div>
                </div>
            </div>

            {{-- Change Photo --}}
            <div class="px-6 py-5 border-b border-gray-100 dark:border-[#2a2a3a] prime:border-green-100">
                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900 uppercase tracking-wide mb-3">Change Photo</p>
                <div class="relative" x-data="{ fileName: '' }">
                    <input type="file" name="avatar" accept="image/*"
                           @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="flex items-center gap-3 w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-[#2a2a3a] prime:border-green-200 bg-white dark:bg-[#1a1f2e] prime:bg-white hover:border-gray-400 dark:hover:border-[#3a3a4a] prime:hover:border-green-400 transition pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 prime:text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-700 dark:text-gray-300 prime:text-gray-700 truncate" x-text="fileName || 'Click to upload a photo'"></span>
                    </div>
                </div>
                @error('avatar') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400 mt-2">Accepted: JPG, JPEG, PNG, WebP. Max 2MB.</p>
            </div>

            {{-- Account Details --}}
            <div class="px-6 py-5 border-b border-gray-100 dark:border-[#2a2a3a] prime:border-green-100">
                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900 uppercase tracking-wide mb-3">Account Details</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#2a2a3a] prime:border-green-200 bg-white dark:bg-[#1a1f2e] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-blue-500/50 prime:focus:ring-green-500/50 transition placeholder-gray-400">
                        @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#2a2a3a] prime:border-green-200 bg-white dark:bg-[#1a1f2e] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-blue-500/50 prime:focus:ring-green-500/50 transition placeholder-gray-400">
                        @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="px-6 py-5 border-b border-gray-100 dark:border-[#2a2a3a] prime:border-green-100">
                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900 uppercase tracking-wide mb-1">Change Password</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400 mb-4">Leave blank to keep your current password</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">New Password</label>
                        <input type="password" name="password" placeholder="Min. 8 characters"
                               class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#2a2a3a] prime:border-green-200 bg-white dark:bg-[#1a1f2e] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-blue-500/50 prime:focus:ring-green-500/50 transition">
                        @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Repeat password"
                               class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#2a2a3a] prime:border-green-200 bg-white dark:bg-[#1a1f2e] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-blue-500/50 prime:focus:ring-green-500/50 transition">
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4">
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ url()->previous() }}"
                       class="px-5 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-[#2a2a3a] prime:border-green-200 text-gray-600 dark:text-gray-400 prime:text-gray-600 hover:bg-gray-50 dark:hover:bg-[#222736] prime:hover:bg-green-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold rounded-lg bg-gray-900 hover:bg-gray-800 dark:bg-blue-600 dark:hover:bg-blue-700 prime:bg-green-600 prime:hover:bg-green-700 text-white transition shadow-sm">
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection