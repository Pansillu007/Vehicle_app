@if (filled(config('services.google.client_id')))
    <div x-data="{ loading: false }" class="w-full">
        {{-- Divider --}}
        <div class="flex items-center gap-3 sm:gap-4 my-6" role="separator" aria-label="Or continue with Google">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-600/60 to-slate-600/40 dark:via-slate-600 dark:to-slate-700/80"></div>
            <span class="shrink-0 text-[11px] sm:text-xs font-medium uppercase tracking-[0.12em] text-slate-500 dark:text-slate-400 select-none">
                Or continue with
            </span>
            <div class="flex-1 h-px bg-gradient-to-l from-transparent via-slate-600/60 to-slate-600/40 dark:via-slate-600 dark:to-slate-700/80"></div>
        </div>

        <a href="{{ route('auth.google.redirect') }}"
            @click="loading = true"
            :aria-busy="loading"
            :class="loading ? 'pointer-events-none cursor-wait' : ''"
            class="group relative flex w-full min-h-[3rem] items-center justify-center gap-3 rounded-xl border border-slate-300 bg-slate-100/90 px-4 py-3 text-sm font-semibold text-slate-800 shadow-md shadow-slate-900/5 backdrop-blur-md transition-all duration-300 hover:border-slate-400 hover:bg-slate-200 hover:shadow-lg hover:ring-1 hover:ring-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 focus:ring-offset-white dark:border-slate-700 dark:bg-slate-800/90 dark:text-white dark:shadow-lg dark:shadow-black/20 dark:hover:border-slate-600 dark:hover:bg-slate-700 dark:hover:shadow-xl dark:hover:shadow-blue-500/10 dark:hover:ring-blue-500/25 dark:focus:ring-offset-slate-900 sm:min-h-[3.25rem]">

            <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500/0 via-blue-500/5 to-indigo-500/0 opacity-0 transition-opacity duration-300 group-hover:opacity-100 pointer-events-none" aria-hidden="true"></span>

            <span class="relative flex h-5 w-5 shrink-0 items-center justify-center" x-show="!loading">
                <svg class="h-[1.25rem] w-[1.25rem]" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
            </span>

            <span class="relative flex h-5 w-5 shrink-0 items-center justify-center" x-show="loading" x-cloak>
                <svg class="h-5 w-5 animate-spin text-white/90" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>

            <span class="relative text-slate-800 dark:text-white" x-show="!loading">Continue with Google</span>
            <span class="relative text-slate-500 dark:text-slate-300" x-show="loading" x-cloak>Connecting to Google…</span>
        </a>
    </div>
@endif
