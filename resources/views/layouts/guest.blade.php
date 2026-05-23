<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('vehiclepro-theme') === 'dark' || (!localStorage.getItem('vehiclepro-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    x-init="$watch('darkMode', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('vehiclepro-theme', val ? 'dark' : 'light') })"
    :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'VehiclePro') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
<script>
            if (localStorage.getItem('vehiclepro-theme') === 'dark' || (!localStorage.getItem('vehiclepro-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased theme-transition">
        <div class="text-gray-900 dark:text-gray-100">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>