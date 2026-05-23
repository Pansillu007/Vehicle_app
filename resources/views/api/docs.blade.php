<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation | VehiclePro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        code { font-family: 'JetBrains Mono', monospace; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .dark .glass { background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.05); }
        .gradient-text { background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 h-full">
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-12">
        <header class="mb-16">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full uppercase tracking-wider">v1.0.0</span>
                <span class="text-slate-400 dark:text-slate-600">/</span>
                <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">REST API</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold mb-6 tracking-tight">
                <span class="gradient-text">VehiclePro</span> API Documentation
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl">
                Comprehensive guide to integrating with the VehiclePro backend. Our API is built on REST principles and returns JSON-encoded responses.
            </p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <aside class="lg:col-span-1">
                <nav class="sticky top-12 space-y-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest pl-4 mb-4">Overview</p>
                    <a href="#authentication" class="block px-4 py-2 rounded-xl text-sm font-medium hover:bg-white dark:hover:bg-slate-900 transition-colors">Authentication</a>
                    <a href="#responses" class="block px-4 py-2 rounded-xl text-sm font-medium hover:bg-white dark:hover:bg-slate-900 transition-colors">Response Format</a>
                    
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest pl-4 mt-8 mb-4">Endpoints</p>
                    @foreach($docs['endpoints'] as $group => $routes)
                        <a href="#{{ $group }}" class="block px-4 py-2 rounded-xl text-sm font-medium hover:bg-white dark:hover:bg-slate-900 capitalize transition-colors">
                            {{ str_replace('_', ' ', $group) }}
                        </a>
                    @endforeach
                </nav>
            </aside>

            <main class="lg:col-span-3 space-y-24 pb-24">
                <section id="authentication">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center text-white text-base">#</span>
                        Authentication
                    </h2>
                    <div class="glass rounded-3xl p-6 sm:p-8">
                        <p class="mb-6 text-slate-600 dark:text-slate-400">
                            VehiclePro uses Laravel Sanctum for secure API authentication. All protected routes require a Bearer token in the Authorization header.
                        </p>
                        <div class="bg-slate-900 rounded-2xl p-4 overflow-hidden">
                            <pre class="text-blue-400 text-sm"><code>Authorization: Bearer {your_token_here}</code></pre>
                        </div>
                    </div>
                </section>

                <section id="responses">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white text-base">#</span>
                        Response Format
                    </h2>
                    <div class="glass rounded-3xl p-6 sm:p-8">
                        <p class="mb-6 text-slate-600 dark:text-slate-400">
                            All responses follow a consistent envelope structure to facilitate easy parsing across different clients.
                        </p>
                        <div class="bg-slate-900 rounded-2xl p-4 overflow-hidden">
                            <pre class="text-indigo-400 text-sm"><code>{
  "success": true,
  "message": "Human readable summary",
  "data": { ... payload ... }
}</code></pre>
                        </div>
                    </div>
                </section>

                @foreach($docs['endpoints'] as $group => $routes)
                <section id="{{ $group }}">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-3 uppercase tracking-tighter">
                        <span class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-white text-base">></span>
                        {{ str_replace('_', ' ', $group) }}
                    </h2>
                    <div class="space-y-4">
                        @foreach($routes as $pattern => $description)
                            <div class="glass rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        @php
                                            $method = explode(' ', $pattern)[0];
                                            $path = explode(' ', $pattern)[1];
                                            $methodColor = match($method) {
                                                'GET' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                                'POST' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
                                                'PUT' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                                'DELETE' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400',
                                                default => 'bg-slate-500/10 text-slate-600'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $methodColor }}">{{ $method }}</span>
                                        <code class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $path }}</code>
                                    </div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
                                </div>
                                <div class="flex shrink-0">
                                    <span class="text-[10px] items-center flex gap-1 font-bold text-slate-400 uppercase tracking-widest">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Private
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endforeach
            </main>
        </div>

        <footer class="mt-24 pt-12 border-t border-slate-200 dark:border-slate-800 text-center">
            <p class="text-slate-500 text-sm">© {{ date('Y') }} VehiclePro. Build with Laravel Sanctum & Premium Aesthetics.</p>
        </footer>
    </div>
</body>
</html>
