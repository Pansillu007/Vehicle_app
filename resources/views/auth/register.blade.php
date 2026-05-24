@extends('layouts.auth-split')

@section('title', 'Register | VehiclePro')

@section('marketing')
    <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">Start managing<br/>your fleet today.</h1>
    <p class="text-xl text-gray-600 dark:text-gray-400 mb-10 max-w-lg">Join teams using VehiclePro to track maintenance, costs, and service history in one place.</p>

    <ul class="space-y-4">
        <li class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Unlimited vehicle records
        </li>
        <li class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Service history & reminders
        </li>
        <li class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Cost analytics dashboard
        </li>
        <li class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Free to get started
        </li>
    </ul>
@endsection

@section('form-title', 'Create account')
@section('form-subtitle', 'Set up your account in under a minute.')

@section('content')
    @if ($errors->any())
        <div class="alert-error">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-google-auth-button />

    <form id="register-api-form" class="space-y-4" method="post" action="#" novalidate
        x-data="{
            loading: false,
            showPassword: false,
            showConfirm: false,
            password: '',
            strength: 0,
            strengthLabel: '',
            strengthColor: 'bg-gray-600',
            checkStrength() {
                const p = this.password;
                let score = 0;
                if (p.length >= 8) score++;
                if (p.length >= 12) score++;
                if (/[a-z]/.test(p) && /[A-Z]/.test(p)) score++;
                if (/\d/.test(p)) score++;
                if (/[^a-zA-Z0-9]/.test(p)) score++;
                this.strength = Math.min(score, 4);
                const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
                const colors = ['bg-gray-600', 'bg-red-500', 'bg-amber-500', 'bg-blue-500', 'bg-emerald-500'];
                this.strengthLabel = p.length ? labels[this.strength] : '';
                this.strengthColor = colors[this.strength] || colors[0];
            }
        }"
        @submit.prevent>
        <div>
            <label for="name" class="form-label">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="auth-input" placeholder="John Doe">
        </div>

        <div>
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="auth-input" placeholder="you@example.com">
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <div class="relative">
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="new-password"
                    class="auth-input pr-12" placeholder="••••••••"
                    x-model="password" @input="checkStrength()">
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>
            <div x-show="password.length > 0" x-cloak class="mt-2">
                <div class="flex gap-1 mb-1">
                    <template x-for="i in 4" :key="i">
                        <div class="h-1 flex-1 rounded-full transition-all duration-300"
                            :class="i <= strength ? strengthColor : 'bg-gray-200 dark:bg-slate-700'"></div>
                    </template>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="strengthLabel ? 'Password strength: ' + strengthLabel : ''"></p>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="relative">
                <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                    class="auth-input pr-12" placeholder="••••••••">
                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                    <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showConfirm" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>
        </div>

        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="flex items-start">
                <input id="terms" name="terms" type="checkbox" required
                    class="h-4 w-4 mt-0.5 rounded border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <label for="terms" class="ml-2.5 text-sm text-gray-600 dark:text-gray-400">
                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-blue-600 dark:text-blue-400 hover:underline">'.__('Terms of Service').'</a>',
                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-blue-600 dark:text-blue-400 hover:underline">'.__('Privacy Policy').'</a>',
                    ]) !!}
                </label>
            </div>
        @endif

        <button type="submit" class="auth-btn-primary mt-2" :disabled="loading">
            <span x-show="!loading">Create account</span>
            <span x-show="loading" x-cloak class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating account...
            </span>
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500 transition-colors">Sign in</a>
    </p>
@endsection