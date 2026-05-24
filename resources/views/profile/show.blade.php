<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <img src="{{ Auth::user()->profile_photo_url }}" alt="" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-blue-500/30 shadow-lg">
            <div>
                <h2 class="page-header-title">{{ __('Profile') }}</h2>
                <p class="page-header-subtitle">{{ Auth::user()->email }} · Account & security settings</p>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @include('profile.api-profile-information-form')

            @include('profile.api-update-password-form')

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="profile-panel">
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif

            <div class="profile-panel">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="profile-panel border-red-500/20">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
