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
@vite(['resources/css/app.css'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }
       input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0px 1000px white inset !important;
    -webkit-text-fill-color: #111827 !important;
    transition: background-color 9999s ease-in-out 0s;
}
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 dark:bg-[#0f1117] prime:bg-gray-50">

    {{-- Background --}}
    <div class="fixed inset-0 bg-gray-100 dark:bg-[#0f1117] prime:bg-gray-50 pointer-events-none">
        {{-- Subtle grid pattern --}}
        <div class="absolute inset-0 opacity-40 dark:opacity-20"
             style="background-image: radial-gradient(circle, #d1d5db 1px, transparent 1px); background-size: 24px 24px;"></div>
    </div>

    {{-- Top bar --}}
    <div class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 bg-gray-900 dark:bg-red-600 prime:bg-green-600 rounded-lg flex items-center justify-center text-sm shadow-sm">💊</div>
            <span class="text-sm font-bold text-gray-900 dark:text-gray-100 prime:text-green-700">PRIMEDocs</span>
        </div>
        {{-- Theme toggle --}}
<button id="theme-toggle" onclick="cycleTheme()"
        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-300 dark:border-red-800 prime:border-green-300 bg-white dark:bg-[#1a1f2e] prime:bg-white text-gray-500 dark:text-red-400 prime:text-green-600 hover:bg-gray-50 dark:hover:bg-[#222736] prime:hover:bg-green-50 shadow-sm transition">
    <svg id="icon-light" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
    </svg>
    <svg id="icon-dark" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07l-.71.71M6.34 17.66l-.71.71m12.02 0l-.71-.71M6.34 6.34l-.71-.71M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
    </svg>
    <svg id="icon-prime" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    </svg>
</button>
    </div>

    {{-- Centered modal card --}}
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-sm mx-auto">

            {{-- Modal --}}
            <div class="bg-white dark:bg-[#1a1f2e] prime:bg-white rounded-2xl border border-gray-200 dark:border-[#333d55] prime:border-green-200 shadow-xl overflow-hidden">

                {{-- Modal Header --}}
                <div class="px-8 pt-8 pb-6 text-center border-b border-gray-100 dark:border-[#2a3042] prime:border-green-100">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-900 dark:bg-red-600 prime:bg-green-600 rounded-xl text-xl mb-4 shadow-md">
                        💊
                    </div>
                    <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 prime:text-gray-900 tracking-tight">Welcome back</h1>
                    <p class="text-xs text-gray-400 dark:text-gray-500 prime:text-gray-400 mt-1">Sign in to PRIMEDocs to continue</p>
                </div>

                {{-- Modal Body --}}
                <div class="px-8 py-6">

                    @if($errors->any())
                        <div class="mb-5 flex items-center gap-2.5 bg-red-50 dark:bg-red-950 prime:bg-red-50 border border-red-200 dark:border-red-800 prime:border-red-200 rounded-lg px-4 py-3">
                            <span class="text-red-500 text-sm">⚠</span>
                            <p class="text-sm text-red-700 dark:text-red-400 prime:text-red-700">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 prime:text-gray-500 uppercase tracking-wider mb-1.5">
                                Email Address
                            </label>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   autofocus
                                   autocomplete="email"
                                   placeholder="you@example.com"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       border-gray-200 dark:border-[#2a3042] prime:border-green-200
                                       bg-gray-50 dark:bg-[#222736] prime:bg-white
                                       text-gray-900 dark:text-gray-100 prime:text-gray-900
                                       placeholder-gray-400 dark:placeholder-gray-600 prime:placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-red-500 prime:focus:ring-green-400
                                       transition">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 prime:text-gray-500 uppercase tracking-wider mb-1.5">
                                Password
                            </label>
                            <input type="password" id="password" name="password"
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-lg border
                                       border-gray-200 dark:border-[#2a3042] prime:border-green-200
                                       bg-gray-50 dark:bg-[#222736] prime:bg-white
                                       text-gray-900 dark:text-gray-100 prime:text-gray-900
                                       placeholder-gray-400 dark:placeholder-gray-600 prime:placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-red-500 prime:focus:ring-green-400
                                       transition">
                        </div>

                        {{-- Remember me --}}
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="remember" name="remember"
                                   class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 prime:border-green-300 cursor-pointer">
                            <label for="remember" class="text-sm text-gray-500 dark:text-gray-400 prime:text-gray-500 cursor-pointer select-none">
                                Keep me signed in
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                class="w-full py-2.5 text-sm font-semibold rounded-lg transition mt-2
                                    bg-gray-900 hover:bg-gray-800
                                    dark:bg-red-600 dark:hover:bg-red-700
                                    prime:bg-green-600 prime:hover:bg-green-700
                                    text-white shadow-sm">
                            Sign In →
                        </button>

                    </form>
                </div>

                {{-- Modal Footer --}}
                <div class="px-8 py-4 bg-gray-50 dark:bg-[#161b27] prime:bg-gray-50 border-t border-gray-100 dark:border-[#2a3042] prime:border-green-100 text-center">
                    <p class="text-xs text-gray-400 dark:text-gray-600 prime:text-gray-400">
                        Contact your administrator if you can't sign in.
                    </p>
                </div>

            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-gray-400 dark:text-gray-600 prime:text-gray-400 mt-5">
                PrimeLink Pharma &copy; {{ date('Y') }} · All rights reserved
            </p>

        </div>
    </div>

<script>
    function cycleTheme() {
        let theme = localStorage.getItem('theme') || 'light';
        if (theme === 'light') theme = 'dark';
        else if (theme === 'dark') theme = 'prime';
        else theme = 'light';
        localStorage.setItem('theme', theme);
        document.documentElement.classList.remove('dark', 'prime');
        if (theme === 'dark') document.documentElement.classList.add('dark');
        if (theme === 'prime') document.documentElement.classList.add('prime');
        updateIcon(theme);
    }

    function updateIcon(theme) {
        document.getElementById('icon-light').classList.toggle('hidden', theme !== 'light');
        document.getElementById('icon-dark').classList.toggle('hidden', theme !== 'dark');
        document.getElementById('icon-prime').classList.toggle('hidden', theme !== 'prime');
    }

    // Set correct icon on load
    updateIcon(localStorage.getItem('theme') || 'light');
</script>
</body>
</html>