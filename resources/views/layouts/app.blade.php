<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('vehiclepro-theme') === 'dark' || (!localStorage.getItem('vehiclepro-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    x-init="
        $watch('darkMode', val => {
            document.documentElement.classList.toggle('dark', val);
            localStorage.setItem('vehiclepro-theme', val ? 'dark' : 'light');
        });
        window.addEventListener('theme-toggled', (e) => { darkMode = e.detail.dark; });
    "
    :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @auth
            <meta name="api-token" content="{{ $apiToken ?? '' }}">
            <script>
                (function () {
                    var t = document.querySelector('meta[name="api-token"]');
                    if (t && t.content) {
                        localStorage.setItem('vehiclepro_api_token', t.content);
                    }
                })();
            </script>
        @endauth
        <meta name="description" content="VehiclePro — Premium vehicle management platform. Track vehicles, manage maintenance, monitor service history.">

        <title>{{ config('app.name', 'VehiclePro') }} | Premium Vehicle Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
<!-- Dark mode flash prevention -->
        <script>
            if (localStorage.getItem('vehiclepro-theme') === 'dark' || (!localStorage.getItem('vehiclepro-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased theme-transition">
        <x-banner />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100/80 to-blue-50/40 dark:from-[#020617] dark:via-slate-950 dark:to-[#020617] theme-transition">
            @include('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white/70 dark:bg-slate-800/40 backdrop-blur-lg border-b border-gray-200/50 dark:border-white/[0.06] theme-transition">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
@if (session('success'))
                    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8 animate-slide-up">
                        <div class="glass-card rounded-xl p-4 border-l-4 border-blue-500" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="font-medium text-gray-800 dark:text-white">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8 animate-slide-up">
                        <div class="alert-error" role="alert">{{ session('error') }}</div>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>

        @stack('modals')
@stack('scripts')

        @livewireScripts
    </body>
</html>