@extends('layouts.auth-split')

@section('title', 'Two-Factor Verification | VehiclePro')

@section('marketing')
    <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">Extra layer of<br/>account protection.</h1>
    <p class="text-xl text-gray-600 dark:text-gray-400 mb-10 max-w-lg">Two-factor authentication keeps your fleet data secure even if your password is compromised.</p>

    <ul class="space-y-4">
        <li class="flex items-start gap-3">
            <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </span>
            <div>
                <p class="font-semibold text-gray-900 dark:text-white">Authenticator app</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Enter the 6-digit code from Google Authenticator, Authy, or similar.</p>
            </div>
        </li>
        <li class="flex items-start gap-3">
            <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </span>
            <div>
                <p class="font-semibold text-gray-900 dark:text-white">Recovery codes</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Lost your device? Use a one-time emergency recovery code instead.</p>
            </div>
        </li>
        <li class="flex items-start gap-3">
            <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </span>
            <div>
                <p class="font-semibold text-gray-900 dark:text-white">Session protected</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Your login is paused until verification completes successfully.</p>
            </div>
        </li>
    </ul>
@endsection

@section('form-title', 'Two-Factor Verification')
@section('form-subtitle', 'Confirm your identity to continue to VehiclePro.')

@section('content')
    <div class="flex justify-center mb-6">
        <div class="relative">
            <div class="absolute inset-0 rounded-2xl bg-blue-500/30 blur-xl animate-pulse" aria-hidden="true"></div>
            <div class="relative flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500/20 to-indigo-600/20 border border-blue-500/30 shadow-lg shadow-blue-500/20">
                <svg class="h-8 w-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-error" role="alert">
            <div class="flex gap-3">
                <svg class="h-5 w-5 flex-shrink-0 text-red-500 dark:text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div
        x-data="{
            recovery: false,
            loading: false,
            toggleRecovery() {
                this.recovery = !this.recovery;
                this.$nextTick(() => {
                    (this.recovery ? this.$refs.recovery_code : this.$refs.code)?.focus();
                });
            }
        }"
    >
        <div
            class="mb-6 rounded-2xl border border-blue-500/20 bg-blue-500/5 dark:bg-blue-500/10 px-4 py-3 text-sm text-gray-600 dark:text-slate-300 transition-all duration-300"
            x-show="!recovery"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            Open your authenticator app and enter the current 6-digit verification code.
        </div>

        <div
            class="mb-6 rounded-2xl border border-amber-500/25 bg-amber-500/5 dark:bg-amber-500/10 px-4 py-3 text-sm text-gray-600 dark:text-slate-300"
            x-show="recovery"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            Enter one of your emergency recovery codes. Each code can only be used once.
        </div>

        <form
            method="POST"
            action="{{ route('two-factor.login') }}"
            class="space-y-5"
            @submit="loading = true"
        >
            @csrf

            <div
                x-show="!recovery"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
                <label for="code" class="form-label">Verification code</label>
                <input
                    id="code"
                    name="code"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    autocomplete="one-time-code"
                    autofocus
                    x-ref="code"
                    :disabled="recovery"
                    placeholder="000000"
                    class="auth-input auth-input-otp text-center tracking-[0.35em] text-lg font-mono"
                    aria-describedby="code-hint"
                >
                <p id="code-hint" class="mt-2 text-xs text-gray-500 dark:text-slate-500">6-digit code from your authenticator app</p>
            </div>

            <div
                x-show="recovery"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
                <label for="recovery_code" class="form-label">Recovery code</label>
                <input
                    id="recovery_code"
                    name="recovery_code"
                    type="text"
                    autocomplete="one-time-code"
                    x-ref="recovery_code"
                    :disabled="!recovery"
                    placeholder="xxxx-xxxx"
                    class="auth-input auth-input-otp font-mono lowercase"
                    aria-describedby="recovery-hint"
                >
                <p id="recovery-hint" class="mt-2 text-xs text-gray-500 dark:text-slate-500">Paste a recovery code from your secure backup</p>
            </div>

            <button type="submit" class="auth-btn-primary" :disabled="loading">
                <span x-show="!loading">Verify &amp; continue</span>
                <span x-show="loading" x-cloak class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Verifying...
                </span>
            </button>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                <button
                    type="button"
                    class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors duration-200 text-left"
                    x-show="!recovery"
                    @click="toggleRecovery()"
                >
                    Use a recovery code instead
                </button>

                <button
                    type="button"
                    class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors duration-200 text-left"
                    x-show="recovery"
                    x-cloak
                    @click="toggleRecovery()"
                >
                    Use authenticator code instead
                </button>

                <a href="{{ route('login') }}" class="text-sm text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200 transition-colors text-left sm:text-right">
                    &larr; Back to sign in
                </a>
            </div>
        </form>
    </div>
@endsection
