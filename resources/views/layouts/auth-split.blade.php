<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        darkMode: localStorage.getItem('vehiclepro-theme') === 'dark' || (!localStorage.getItem('vehiclepro-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
    }"
    x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('vehiclepro-theme', val ? 'dark' : 'light') })"
    :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'VehiclePro')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        if (localStorage.getItem('vehiclepro-theme') === 'dark' || (!localStorage.getItem('vehiclepro-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="antialiased overflow-x-hidden theme-transition text-gray-200 dark:text-gray-200">
    <div class="min-h-screen flex auth-split-bg relative">
        <!-- Floating blobs -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="auth-blob auth-blob-1"></div>
            <div class="auth-blob auth-blob-2"></div>
            <div class="auth-blob auth-blob-3"></div>
        </div>

        <!-- Theme toggle -->
        <button type="button"
            onclick="window.toggleDarkMode()"
            class="fixed top-6 right-6 z-50 p-2.5 rounded-xl bg-white/10 dark:bg-slate-800/60 backdrop-blur-md border border-white/10 text-gray-400 hover:text-white transition-all duration-300"
            title="Toggle theme">
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>

        <!-- Left: Marketing -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-center px-16 relative z-10">
            <a href="/" class="absolute top-10 left-16 text-2xl font-bold tracking-tight z-10 flex items-center group">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-cyan-300 group-hover:from-blue-300 group-hover:to-cyan-200 transition-all duration-300">VehiclePro</span><span class="text-blue-500">.</span>
            </a>

            <div class="mt-10 animate-fade-in">
                @yield('marketing')
            </div>
        </div>

        <!-- Right: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative z-10 min-h-screen">
            <div class="glass-auth-card w-full max-w-md rounded-3xl p-8 sm:p-10 animate-slide-up">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">@yield('form-title')</h2>
                    @hasSection('form-subtitle')
                        <p class="text-gray-500 dark:text-gray-400">@yield('form-subtitle')</p>
                    @endif
                </div>

                @yield('content')
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>
