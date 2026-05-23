<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        darkMode: localStorage.getItem('vehiclepro-theme') === 'dark' || (!localStorage.getItem('vehiclepro-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
    }"
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
    <title>VehiclePro | Modern Vehicle Management Platform</title>
    <meta name="description" content="Track vehicles, manage maintenance, monitor service history and analytics in one powerful dashboard.">
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
    <style>
        .hero-grid {
            background-image: linear-gradient(rgba(59, 130, 246, 0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(59, 130, 246, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        .blob-1 { animation: blobFloat 12s ease-in-out infinite; }
        .blob-2 { animation: blobFloat 15s ease-in-out 3s infinite; }
        .blob-3 { animation: blobFloat 18s ease-in-out 6s infinite; }
        .blob-4 { animation: blobFloat 14s ease-in-out 4s infinite; }
        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1) rotate(0deg); }
            33% { transform: translate(30px, -50px) scale(1.05) rotate(3deg); }
            66% { transform: translate(-20px, 20px) scale(0.95) rotate(-3deg); }
        }
        @keyframes heroFadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .hero-animate { animation: heroFadeIn 0.8s ease-out forwards; }
        .hero-animate-delay-1 { animation-delay: 0.15s; opacity: 0; }
        .hero-animate-delay-2 { animation-delay: 0.3s; opacity: 0; }
        .hero-animate-delay-3 { animation-delay: 0.45s; opacity: 0; }
        .feature-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.06);
        }
        .dark .feature-card {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .feature-card:hover {
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.12);
            transform: translateY(-4px);
        }
        .dark .feature-card:hover {
            background: rgba(15, 23, 42, 0.7);
        }
        .stat-glow {
            text-shadow: 0 0 40px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="antialiased bg-slate-50 dark:bg-[#060910] text-gray-900 dark:text-gray-200 overflow-x-hidden selection:bg-blue-500 selection:text-white font-sans theme-transition">

    <!-- Background Effects -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="hero-grid absolute inset-0"></div>
        <div class="blob-1 absolute -top-32 -left-32 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[128px]"></div>
        <div class="blob-2 absolute top-1/4 -right-32 w-[400px] h-[400px] bg-cyan-600/15 rounded-full blur-[128px]"></div>
        <div class="blob-3 absolute bottom-0 left-1/4 w-[600px] h-[600px] bg-indigo-600/15 rounded-full blur-[128px]"></div>
        <div class="blob-4 absolute top-1/2 left-1/2 w-[300px] h-[300px] bg-violet-600/10 rounded-full blur-[128px]"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/70 dark:bg-[#060910]/60 backdrop-blur-xl border-b border-gray-200/50 dark:border-white/[0.04] theme-transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <span class="text-2xl font-bold tracking-tight">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-cyan-300">VehiclePro</span><span class="text-blue-500">.</span>
                    </span>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <button type="button" onclick="window.toggleDarkMode()"
                        class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition-all duration-200"
                        title="Toggle theme">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>
                    <a href="#features" class="nav-link-premium text-gray-600 dark:text-gray-400 dark:hover:text-white">Features</a>
                    <a href="#stats" class="nav-link-premium text-gray-600 dark:text-gray-400 dark:hover:text-white">
                        Stats
                    </a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium text-sm hover:shadow-[0_0_25px_rgba(59,130,246,0.4)] transition-all duration-300 transform hover:-translate-y-0.5">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium text-sm hover:shadow-[0_0_25px_rgba(59,130,246,0.4)] transition-all duration-300 transform hover:-translate-y-0.5">
                                Get Started Free
                            </a>
                        @endif
                    @endauth
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium">Sign In</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-36 pb-20 lg:pt-52 lg:pb-36 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <!-- Badge -->
            <div class="hero-animate inline-flex items-center px-4 py-1.5 rounded-full border border-blue-500/20 bg-blue-500/5 text-blue-400 text-sm font-medium mb-8 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 animate-pulse"></span>
                Now in v2.0 — Smarter than ever
            </div>

            <h1 class="hero-animate hero-animate-delay-1 text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-extrabold tracking-tight mb-8 leading-[0.9]">
                <span class="block text-gray-900 dark:text-white mb-3">Modern Vehicle</span>
                <span class="block bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-cyan-400 to-indigo-400">
                    Management Platform
                </span>
            </h1>

            <p class="hero-animate hero-animate-delay-2 mt-6 max-w-2xl mx-auto text-lg sm:text-xl text-gray-600 dark:text-gray-400 leading-relaxed mb-12">
                Track vehicles, manage maintenance, monitor service history and analytics in one powerful dashboard.
            </p>

            <div class="hero-animate hero-animate-delay-3 flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="group px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-lg hover:shadow-[0_0_40px_rgba(59,130,246,0.5)] transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center">
                        Open Dashboard
                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="group px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-lg hover:shadow-[0_0_40px_rgba(59,130,246,0.5)] transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center">
                        Get Started
                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl text-white font-semibold text-lg border border-white/10 bg-white/[0.03] hover:bg-white/[0.07] backdrop-blur-sm transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Live Demo
                    </a>
                @endauth
            </div>

            <!-- Social Proof -->
            <div class="hero-animate hero-animate-delay-3 mt-16 flex flex-col sm:flex-row items-center justify-center gap-6 text-sm text-gray-500">
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold ring-2 ring-[#060910]">JD</div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-xs font-bold ring-2 ring-[#060910]">MK</div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white text-xs font-bold ring-2 ring-[#060910]">AR</div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold ring-2 ring-[#060910]">+5k</div>
                </div>
                <div class="flex items-center gap-1">
                    @for ($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                    <span class="ml-2 text-gray-400">Loved by <span class="text-white font-semibold">5,000+</span> teams</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold tracking-wider uppercase bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">What we offer</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">Everything you need to manage your fleet</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Built for modern teams who need powerful vehicle management without the complexity.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="feature-card rounded-2xl p-8 transition-all duration-500 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center mb-6 border border-blue-500/20 group-hover:border-blue-500/40 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.2)] transition-all duration-300">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Lightning Fast</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Experience a seamless, instantaneous interface built on modern infrastructure to save your valuable time.</p>
                </div>
                <!-- Feature 2 -->
                <div class="feature-card rounded-2xl p-8 transition-all duration-500 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500/20 to-cyan-600/20 flex items-center justify-center mb-6 border border-cyan-500/20 group-hover:border-cyan-500/40 group-hover:shadow-[0_0_20px_rgba(6,182,212,0.2)] transition-all duration-300">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Cost Analytics</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Visualize your maintenance expenditures over time with beautiful, interactive financial charts and insights.</p>
                </div>
                <!-- Feature 3 -->
                <div class="feature-card rounded-2xl p-8 transition-all duration-500 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 flex items-center justify-center mb-6 border border-indigo-500/20 group-hover:border-indigo-500/40 group-hover:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition-all duration-300">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Highly Secure</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Industry-standard encryption and robust authentication keep your sensitive vehicle data private and protected.</p>
                </div>
                <!-- Feature 4 -->
                <div class="feature-card rounded-2xl p-8 transition-all duration-500 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center mb-6 border border-emerald-500/20 group-hover:border-emerald-500/40 group-hover:shadow-[0_0_20px_rgba(16,185,129,0.2)] transition-all duration-300">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Service Tracking</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Record every service, oil change, and repair with detailed logs and automatic maintenance reminders.</p>
                </div>
                <!-- Feature 5 -->
                <div class="feature-card rounded-2xl p-8 transition-all duration-500 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/20 to-amber-600/20 flex items-center justify-center mb-6 border border-amber-500/20 group-hover:border-amber-500/40 group-hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-all duration-300">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Real-time Updates</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Instantly track vehicle status, mileage, and upcoming maintenance schedules from any device.</p>
                </div>
                <!-- Feature 6 -->
                <div class="feature-card rounded-2xl p-8 transition-all duration-500 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500/20 to-rose-600/20 flex items-center justify-center mb-6 border border-rose-500/20 group-hover:border-rose-500/40 group-hover:shadow-[0_0_20px_rgba(244,63,94,0.2)] transition-all duration-300">
                        <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Beautiful UI</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">A thoughtfully crafted premium interface that makes vehicle management a delightful experience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-24 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="feature-card rounded-3xl p-12 md:p-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white stat-glow mb-2">5K+</div>
                        <div class="text-sm text-gray-400 font-medium">Active Teams</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white stat-glow mb-2">50K+</div>
                        <div class="text-sm text-gray-400 font-medium">Vehicles Tracked</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white stat-glow mb-2">99.9%</div>
                        <div class="text-sm text-gray-400 font-medium">Uptime SLA</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white stat-glow mb-2">4.9</div>
                        <div class="text-sm text-gray-400 font-medium">User Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">Testimonials</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Trusted by fleet managers</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['quote' => 'VehiclePro cut our maintenance tracking time in half. The dashboard analytics are exactly what we needed.', 'name' => 'Sarah Chen', 'role' => 'Fleet Manager, Apex Logistics'],
                    ['quote' => 'Clean UI, fast search, and PDF exports for audits. This feels like a product we paid enterprise prices for.', 'name' => 'James Okonkwo', 'role' => 'Operations Lead, Metro Transit'],
                    ['quote' => 'Service reminders alone saved us from two overdue oil changes last quarter. Highly recommend.', 'name' => 'Maria Lopez', 'role' => 'Small Business Owner'],
                ] as $t)
                <div class="feature-card rounded-2xl p-8 transition-all duration-500">
                    <div class="flex gap-1 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-6">"{{ $t['quote'] }}"</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $t['name'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $t['role'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-6">Ready to streamline your fleet?</h2>
            <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">Join thousands of teams already using VehiclePro to manage their vehicles more efficiently.</p>
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-10 py-5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-lg hover:shadow-[0_0_40px_rgba(59,130,246,0.5)] transition-all duration-300 transform hover:-translate-y-1">
                    Open Dashboard
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center px-10 py-5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-lg hover:shadow-[0_0_40px_rgba(59,130,246,0.5)] transition-all duration-300 transform hover:-translate-y-1">
                    Start Free Today
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-200/80 dark:border-white/[0.04] bg-white/50 dark:bg-[#040608] py-12 relative z-10 theme-transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <span class="text-xl font-bold tracking-tight">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-cyan-300">VehiclePro</span><span class="text-blue-500">.</span>
                    </span>
                    <p class="text-gray-500 text-sm mt-1">&copy; {{ date('Y') }} VehiclePro. All rights reserved.</p>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="#" class="text-sm text-gray-500 hover:text-white transition-colors duration-200">Privacy</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-white transition-colors duration-200">Terms</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-white transition-colors duration-200">Contact</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-white transition-colors duration-200">Docs</a>
                </div>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>