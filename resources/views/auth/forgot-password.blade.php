@extends('layouts.auth-split')

@section('title', 'Forgot Password | VehiclePro')

@section('marketing')
    <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">Reset your<br/>password securely.</h1>
    <p class="text-xl text-gray-600 dark:text-gray-400 max-w-lg">We'll send you a secure link to reset your password and get you back into your dashboard.</p>
@endsection

@section('form-title', 'Forgot password')
@section('form-subtitle', 'Enter your email and we will send you a reset link.')

@section('content')
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 -mt-4">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    @session('status')
        <div class="alert-success">{{ $value }}</div>
    @endsession

    @if ($errors->any())
        <div class="alert-error">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div>
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="auth-input" placeholder="you@example.com">
        </div>

        <button type="submit" class="auth-btn-primary" :disabled="loading">
            <span x-show="!loading">Email Password Reset Link</span>
            <span x-show="loading" x-cloak class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sending...
            </span>
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500 transition-colors">&larr; Back to sign in</a>
    </p>
@endsection