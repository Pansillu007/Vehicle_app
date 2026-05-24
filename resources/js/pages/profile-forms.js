import { profileApi } from '../api/profile.js';
import { bindApiForm } from '../api/forms.js';
import { handleApiError } from '../api/errors.js';
import { unwrapData } from '../api/response.js';

const profileForm = document.getElementById('profile-api-form');
if (profileForm) {
    async function loadProfile() {
        profileForm.classList.add('opacity-60');
        try {
            const response = await profileApi.get();
            const user = unwrapData(response);
            const nameInput = profileForm.querySelector('[name="name"]');
            const emailInput = profileForm.querySelector('[name="email"]');
            if (nameInput) nameInput.value = user.name ?? '';
            if (emailInput) emailInput.value = user.email ?? '';
        } catch (error) {
            handleApiError(error);
        } finally {
            profileForm.classList.remove('opacity-60');
        }
    }

    bindApiForm(profileForm, {
        submit: (data) => profileApi.update(data),
        onSuccess: () => loadProfile(),
    });

    loadProfile();
}

const passwordForm = document.getElementById('password-api-form');
if (passwordForm) {
    bindApiForm(passwordForm, {
        submit: (data) => profileApi.updatePassword(data),
        onSuccess: () => {
            passwordForm.reset();
        },
    });
}
