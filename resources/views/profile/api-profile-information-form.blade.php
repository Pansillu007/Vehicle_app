<div class="profile-panel">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Profile Information') }}</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Update your account\'s profile information and email address.') }}</p>

    <form id="profile-api-form" class="space-y-6">
        <div>
            <label for="profile_name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" id="profile_name" value="{{ auth()->user()->name }}" required class="form-input-dark" autocomplete="name">
        </div>
        <div>
            <label for="profile_email" class="form-label">{{ __('Email') }}</label>
            <input type="email" name="email" id="profile_email" value="{{ auth()->user()->email }}" required class="form-input-dark" autocomplete="username">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="btn-primary">{{ __('Save') }}</button>
        </div>
    </form>
</div>
