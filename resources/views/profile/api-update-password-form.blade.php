<div class="profile-panel">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Update Password') }}</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>

    <form id="password-api-form" class="space-y-6">
        <div>
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input type="password" name="current_password" id="current_password" required class="form-input-dark" autocomplete="current-password">
        </div>
        <div>
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input type="password" name="password" id="password" required class="form-input-dark" autocomplete="new-password">
        </div>
        <div>
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-input-dark" autocomplete="new-password">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="btn-primary">{{ __('Save') }}</button>
        </div>
    </form>
</div>
