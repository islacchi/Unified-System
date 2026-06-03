<!DOCTYPE html>
<html lang="en" id="app-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharma RFQ — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') document.documentElement.classList.add('dark');
        if (theme === 'prime') document.documentElement.classList.add('prime');
    </script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen dark:bg-[var(--surface-2)] dark:text-[var(--text-1)] prime:bg-white prime:text-gray-900"
      x-data="{
          theme: localStorage.getItem('theme') || 'light',
          cycle() {
              if (this.theme === 'light') this.theme = 'dark';
              else if (this.theme === 'dark') this.theme = 'prime';
              else this.theme = 'light';
              localStorage.setItem('theme', this.theme);
              document.documentElement.classList.remove('dark', 'prime');
              if (this.theme === 'dark') document.documentElement.classList.add('dark');
              if (this.theme === 'prime') document.documentElement.classList.add('prime');
          }
      }">

    {{-- Navbar --}}
    <nav class="bg-white dark:bg-[var(--surface)] prime:bg-white border-b border-gray-200 dark:border-[var(--border)] prime:border-green-200 px-6 py-0 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between h-14">

            {{-- Logo --}}
            <a href="{{ route('rfqs.index') }}" class="flex items-center gap-2.5 font-bold text-base">
                <div class="bg-gray-900 dark:bg-[var(--accent)] prime:bg-green-600 text-white rounded-lg w-7 h-7 flex items-center justify-center text-sm">💊</div>
                <span class="text-gray-900 dark:text-[var(--accent)] prime:text-green-700">PRIMEDocs</span>
            </a>

            {{-- Nav links --}}
            <div class="flex items-center gap-1 text-sm" x-data="{ rfqOpen: {{ request()->is('rfqs*') || request()->is('agencies*') ? 'true' : 'false' }} }">

                {{-- RFQ's toggle button --}}
                <button @click="rfqOpen = !rfqOpen"
                        class="px-4 py-2 rounded-lg transition font-medium flex items-center gap-1
                            {{ request()->is('rfqs*') || request()->is('agencies*')
                                ? 'bg-gray-900 text-white dark:bg-[var(--accent)] dark:text-white prime:bg-green-600 prime:text-white'
                                : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-[var(--text-3)] dark:hover:text-[var(--text-1)] dark:hover:bg-[var(--surface-3)] prime:text-gray-600 prime:hover:text-gray-900 prime:hover:bg-green-50' }}">
                    <a href="{{ route('rfqs.index') }}" @click.stop>RFQ's</a>
                    <svg class="w-3 h-3 transition-transform duration-300" :class="rfqOpen ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Agencies — slides out when rfqOpen --}}
                <div class="overflow-hidden transition-all duration-300 ease-in-out"
                     :style="rfqOpen ? 'max-width: 120px; opacity: 1;' : 'max-width: 0px; opacity: 0;'">
                    <a href="{{ route('agencies.index') }}"
                       class="px-4 py-2 rounded-lg transition font-medium
                           {{ request()->is('agencies*')
                               ? 'bg-gray-900 text-white dark:bg-[var(--accent)] dark:text-white prime:bg-green-600 prime:text-white'
                               : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-[var(--text-3)] dark:hover:text-[var(--text-1)] dark:hover:bg-[var(--surface-3)] prime:text-gray-600 prime:hover:text-gray-900 prime:hover:bg-green-50' }}">
                        Agencies
                    </a>
                </div>

                {{-- CPR Tracker --}}
                <a href="{{ route('cpr.index') }}"
                   class="px-4 py-2 rounded-lg transition font-medium
                       {{ request()->is('cpr*')
                           ? 'bg-gray-900 text-white dark:bg-[var(--accent)] dark:text-white prime:bg-green-600 prime:text-white'
                           : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-[var(--text-3)] dark:hover:text-[var(--text-1)] dark:hover:bg-[var(--surface-3)] prime:text-green-700 prime:hover:text-gray-900 prime:hover:bg-green-50' }}">
                    CPR Tracker
                </a>

                {{-- Users — admin only --}}
                @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}"
                       class="px-4 py-2 rounded-lg transition font-medium
                           {{ request()->is('users*')
                               ? 'bg-gray-900 text-white dark:bg-[var(--accent)] dark:text-white prime:bg-green-600 prime:text-white'
                               : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-[var(--text-3)] dark:hover:text-[var(--text-1)] dark:hover:bg-[var(--surface-3)] prime:text-gray-600 prime:hover:text-gray-900 prime:hover:bg-green-50' }}">
                        Users
                    </a>
                @endif

                {{-- Theme toggle --}}
                <button @click="cycle()"
                        class="ml-2 p-2 rounded-lg transition text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-[var(--text-3)] dark:hover:text-[var(--text-1)] dark:hover:bg-[var(--surface-3)] prime:text-green-700 prime:hover:bg-green-50"
                        :title="theme === 'light' ? 'Switch to Dark' : theme === 'dark' ? 'Switch to Prime Link' : 'Switch to Light'">
                    {{-- Light mode icon: moon --}}
                    <svg x-show="theme === 'light'" style="display:none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                    {{-- Dark mode icon: sun --}}
                    <svg x-show="theme === 'dark'" style="display:none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07l-.71.71M6.34 17.66l-.71.71m12.02 0l-.71-.71M6.34 6.34l-.71-.71M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
                    </svg>
                    {{-- Prime mode icon: heart --}}
                    <svg x-show="theme === 'prime'" style="display:none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>

                {{-- User info + logout --}}
                @auth
                <div class="flex items-center gap-3 border-l border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 pl-3 ml-3">
                    <span class="text-xs text-gray-500 dark:text-gray-400 prime:text-gray-500">
                        {{ auth()->user()->name }}
                        <span class="ml-1 px-1.5 py-0.5 rounded text-xs
                            {{ auth()->user()->isAdmin()
                                ? 'bg-red-50 text-red-600 dark:bg-red-950 dark:text-red-400 prime:bg-green-100 prime:text-green-700'
                                : 'bg-gray-100 text-gray-500 dark:bg-[#2a2a2a] dark:text-gray-400 prime:bg-gray-100 prime:text-gray-500' }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-xs text-gray-500 dark:text-gray-400 prime:text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 prime:hover:text-gray-900 border border-gray-200 dark:border-[#2a2a2a] prime:border-green-200 px-3 py-1.5 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>
                @endauth

            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @livewireScripts
</body>
</html>