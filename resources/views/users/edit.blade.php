@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6">

    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('users.index') }}"
           class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 prime:hover:text-gray-700 transition">
            Users
        </a>
        <span class="text-gray-300 dark:text-gray-600 prime:text-gray-300">›</span>
        <span class="text-sm text-gray-900 dark:text-gray-100 prime:text-gray-900 font-medium">Edit — {{ $user->name }}</span>
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

    <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ========== LEFT COLUMN: Photo ========== --}}
            <div x-data="{ showModal: false, preview: '{{ $user->avatarUrl() }}', fileName: '' }" class="space-y-4">
                <div class="bg-white dark:bg-[#1a1f2e] prime:bg-white border border-gray-200 dark:border-[#333d55] prime:border-green-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900 mb-4">Profile Photo</h3>

                    {{-- Avatar preview --}}
                    <div class="flex flex-col items-center mb-5">
                        <div class="relative shrink-0 mb-3">
                            <img :src="preview"
                                 alt="{{ $user->name }}"
                                 @click="showModal = true"
                                 class="w-24 h-24 rounded-full object-cover ring-4 ring-gray-100 dark:ring-[#2a3042] prime:ring-green-100 hover:ring-gray-300 dark:hover:ring-white prime:hover:ring-green-300 transition cursor-pointer">
                            <span class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full border-[3px] border-white dark:border-[#1a1f2e] prime:border-white {{ $user->isAdmin() ? 'bg-red-500' : 'bg-green-500' }}"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 prime:text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400 mt-0.5">{{ $user->email }}</p>
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
                            <img :src="preview" alt="{{ $user->name }}" class="w-full rounded-xl shadow-2xl">
                            <button @click="showModal = false"
                                    class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-gray-900 dark:bg-red-600 prime:bg-green-600 text-white flex items-center justify-center text-sm font-bold shadow-lg hover:scale-110 transition">
                                ✕
                            </button>
                        </div>
                    </div>

                    {{-- Upload zone --}}
                    <div class="relative">
                        <input type="file" name="avatar" accept="image/*"
                               @change="
                                   const file = $event.target.files[0];
                                   if (file) {
                                       fileName = file.name;
                                       preview = URL.createObjectURL(file);
                                   }
                               "
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex items-center gap-3 w-full px-4 py-3 rounded-lg border-2 border-dashed border-gray-200 dark:border-[#333d55] prime:border-green-200 hover:border-gray-400 dark:hover:border-blue-500 prime:hover:border-green-400 bg-gray-50 dark:bg-[#222736] prime:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 prime:text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 prime:text-gray-700" x-text="fileName || 'Upload new photo'"></p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400 mt-0.5">JPG, PNG, WebP — Max 2MB</p>
                            </div>
                        </div>
                    </div>
                    @error('avatar') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ========== RIGHT COLUMN: Account Details ========== --}}
            <div class="space-y-4">
                {{-- Account Details Card --}}
                <div class="bg-white dark:bg-[#1a1f2e] prime:bg-white border border-gray-200 dark:border-[#333d55] prime:border-green-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900 mb-4">Account Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-white dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-red-500/50 prime:focus:ring-green-500/50 transition placeholder-gray-400">
                            @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-white dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-red-500/50 prime:focus:ring-green-500/50 transition placeholder-gray-400">
                            @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Role</label>
                            <select name="role"
                                    class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-white dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-red-500/50 prime:focus:ring-green-500/50 transition">
                                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Change Password (Collapsible) --}}
                <div x-data="{ open: false }" class="bg-white dark:bg-[#1a1f2e] prime:bg-white border border-gray-200 dark:border-[#333d55] prime:border-green-200 rounded-xl overflow-hidden shadow-sm">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 text-left">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900">Change Password</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400 mt-0.5">Leave blank to keep current password</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse style="display:none">
                        <div class="px-6 pb-5 space-y-4 border-t border-gray-100 dark:border-[#2a3042] prime:border-green-100 pt-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">New Password</label>
                                <input type="password" name="password" placeholder="Min. 8 characters"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-white dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-red-500/50 prime:focus:ring-green-500/50 transition">
                                @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 prime:text-gray-400 uppercase tracking-wide mb-1.5">Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="Repeat password"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-white dark:bg-[#222736] prime:bg-white text-gray-900 dark:text-gray-100 prime:text-gray-900 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:focus:ring-red-500/50 prime:focus:ring-green-500/50 transition">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== Actions ========== --}}
        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <a href="{{ route('users.index') }}"
               class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-center rounded-lg border border-gray-200 dark:border-[#333d55] prime:border-green-200 text-gray-600 dark:text-gray-400 prime:text-gray-600 hover:bg-gray-50 dark:hover:bg-[#222736] prime:hover:bg-green-50 transition">
                Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-center rounded-lg bg-gray-900 hover:bg-gray-800 dark:bg-blue-600 dark:hover:bg-blue-700 prime:bg-green-600 prime:hover:bg-green-700 text-white transition shadow-sm">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection