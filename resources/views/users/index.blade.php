@extends('layouts.app')

@section('content')
<div>
    @if(session('message'))
        <div class="mb-4 bg-green-50 dark:bg-green-950 prime:bg-green-50 border border-green-200 dark:border-green-800 prime:border-green-200 text-green-800 dark:text-green-400 prime:text-green-800 text-sm px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900">User Management</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500 mt-0.5">Manage system users and roles</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="text-sm bg-gray-900 hover:bg-gray-800 dark:bg-red-600 dark:hover:bg-red-700 prime:bg-green-600 prime:hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg transition">
            + Add User
        </a>
    </div>

    <div class="bg-white dark:bg-[#111111] prime:bg-white rounded-xl border border-gray-200 dark:border-red-900 prime:border-green-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-[#1a1a1a] prime:bg-gray-50 border-b border-gray-200 dark:border-red-900 prime:border-green-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 prime:text-gray-500">Name</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 prime:text-gray-500">Email</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 prime:text-gray-500">Role</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 prime:text-gray-500">Joined</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-[#2a2a2a] prime:divide-green-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a] prime:hover:bg-green-50 transition">
                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-gray-100 prime:text-gray-900">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="ml-2 text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400">(you)</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400 prime:text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $user->role === 'admin'
                                ? 'bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-400 prime:bg-green-100 prime:text-green-800'
                                : 'bg-gray-100 text-gray-600 dark:bg-[#2a2a2a] dark:text-gray-400 prime:bg-gray-100 prime:text-gray-600' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400 prime:text-gray-500 text-xs">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('users.edit', $user) }}"
                               class="text-xs border border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 text-gray-500 dark:text-gray-400 prime:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 prime:hover:text-gray-900 hover:border-gray-400 dark:hover:border-red-700 prime:hover:border-green-400 px-3 py-1.5 rounded-lg transition">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-500 dark:text-red-400 prime:text-red-500 border border-red-200 dark:border-red-900 prime:border-red-200 hover:bg-red-50 dark:hover:bg-red-950 prime:hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection