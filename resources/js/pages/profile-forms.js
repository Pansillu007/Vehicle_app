import { profileApi } from '../api/profile.js';
import { bindApiForm } from '../api/forms.js';

const profileForm = document.getElementById('profile-api-form');
if (profileForm) {
    bindApiForm(profileForm, {
        submit: (data) => profileApi.update(data),
        onSuccess: () => {},
    });
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
