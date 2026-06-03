<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — PRIMEDocs</title>
    <script>
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') document.documentElement.classList.add('dark');
        if (theme === 'prime') document.documentElement.classList.add('prime');
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 dark:bg-[#0f1117] prime:bg-gray-50 flex items-center justify-center px-4">

<div class="w-full max-w-md">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-900 dark:bg-red-600 prime:bg-green-600 rounded-2xl text-2xl mb-4 shadow-lg">
            💊
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 prime:text-gray-900 tracking-tight">PRIMEDocs</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500 mt-1">Pharma RFQ & CPR Management System</p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-[#1a1f2e] prime:bg-white rounded-2xl border border-gray-200 dark:border-[#333d55] prime:border-green-200 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="px-8 py-5 border-b border-gray-100 dark:border-[#2a3042] prime:border-green-100">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100 prime:text-gray-900">Sign in to your account</h2>
            <p class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400 mt-0.5">Enter your credentials to continue</p>
        </div>

        {{-- Form --}}
        <div class="px-8 py-6">

            @if($errors->any())
                <div class="mb-5 flex items-start gap-3 bg-red-50 dark:bg-red-950 prime:bg-red-50 border border-red-200 dark:border-red-800 prime:border-red-200 rounded-lg px-4 py-3">
                    <span class="text-red-500 dark:text-red-400 prime:text-red-500 mt-0.5">⚠</span>
                    <p class="text-sm text-red-700 dark:text-red-400 prime:text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 prime:text-gray-500 uppercase tracking-wider mb-2">
                        Email Address
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           autofocus
                           autocomplete="email"
                           placeholder="you@example.com"
                           class="w-full px-4 py-2.5 text-sm rounded-lg border
                               border-gray-200 dark:border-[#2a3042] prime:border-green-200
                               bg-gray-50 dark:bg-[#222736] prime:bg-white
                               text-gray-900 dark:text-gray-100 prime:text-gray-900
                               placeholder-gray-400 dark:placeholder-gray-600 prime:placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500
                               transition">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 prime:text-gray-500 uppercase tracking-wider mb-2">
                        Password
                    </label>
                    <input type="password" id="password" name="password"
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 text-sm rounded-lg border
                               border-gray-200 dark:border-[#2a3042] prime:border-green-200
                               bg-gray-50 dark:bg-[#222736] prime:bg-white
                               text-gray-900 dark:text-gray-100 prime:text-gray-900
                               placeholder-gray-400 dark:placeholder-gray-600 prime:placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500
                               transition">
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 prime:border-green-300
                                   text-gray-900 dark:text-red-600 prime:text-green-600
                                   focus:ring-gray-400 dark:focus:ring-red-500 prime:focus:ring-green-500
                                   cursor-pointer">
                        <span class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500">Remember me</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-2.5 text-sm font-semibold rounded-lg transition
                            bg-gray-900 hover:bg-gray-800
                            dark:bg-red-600 dark:hover:bg-red-700
                            prime:bg-green-600 prime:hover:bg-green-700
                            text-white shadow-sm">
                    Sign In
                </button>

            </form>
        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-400 dark:text-gray-600 prime:text-gray-400 mt-6">
        PrimeLink Pharma &copy; {{ date('Y') }} · All rights reserved
    </p>

</div>

{{-- Theme toggle (bottom right) --}}
<div class="fixed bottom-5 right-5"
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
    <button @click="cycle()"
            class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 dark:border-[#333d55] prime:border-green-200 bg-white dark:bg-[#1a1f2e] prime:bg-white text-gray-500 dark:text-gray-400 prime:text-gray-500 hover:bg-gray-50 dark:hover:bg-[#222736] prime:hover:bg-green-50 shadow-sm transition"
            :title="theme === 'light' ? 'Switch to Dark' : theme === 'dark' ? 'Switch to Prime' : 'Switch to Light'">
        <svg x-show="theme === 'light'" style="display:none" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
        </svg>
        <svg x-show="theme === 'dark'" style="display:none" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07l-.71.71M6.34 17.66l-.71.71m12.02 0l-.71-.71M6.34 6.34l-.71-.71M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
        </svg>
        <svg x-show="theme === 'prime'" style="display:none" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
    </button>
</div>

</body>
</html>