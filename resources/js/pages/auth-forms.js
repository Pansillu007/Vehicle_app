import {
    bootstrapWebSession,
    clearApiToken,
    loginApi,
    registerApi,
    revokeApiToken,
    setApiToken,
    verifyWebSession,
} from '../api/auth.js';
import { resetUnauthorizedGuard } from '../api/client.js';
import { clearFieldErrors, handleApiError } from '../api/errors.js';
import { unwrapData } from '../api/response.js';
import { showToast } from '../api/toast.js';

function setAlpineLoading(form, loading) {
    const alpine = form?._x_dataStack?.[0];
    if (alpine && Object.prototype.hasOwnProperty.call(alpine, 'loading')) {
        alpine.loading = loading;
    }
}

function setAuthLoading(form, loading) {
    setAlpineLoading(form, loading);
    const btn = form.querySelector('[type="submit"]');
    if (btn) {
        btn.disabled = loading;
    }
}

async function completeAuthFlow(token) {
    if (!token) {
        throw new Error('Login succeeded but no API token was returned.');
    }

    setApiToken(token);
    resetUnauthorizedGuard();

    const session = await bootstrapWebSession(token);

    const sessionReady = await verifyWebSession();
    if (!sessionReady) {
        throw new Error('Web session could not be established. Please try again.');
    }

    showToast('Welcome! Redirecting to dashboard...', 'success');
    window.location.replace(session.data?.redirect || '/dashboard');
}

function bindAuthForm(form, submitHandler) {
    if (!form || form.dataset.authBound === 'true') {
        return;
    }

    form.dataset.authBound = 'true';
    form.setAttribute('action', '#');
    form.setAttribute('method', 'post');

    form.addEventListener(
        'submit',
        async (event) => {
            event.preventDefault();
            event.stopImmediatePropagation();

            clearFieldErrors(form);
            setAuthLoading(form, true);

            try {
                await submitHandler(form);
            } catch (error) {
                handleApiError(error, form);
            } finally {
                setAuthLoading(form, false);
            }
        },
        true
    );
}

function initAuthForms() {
    bindAuthForm(document.getElementById('login-api-form'), async (form) => {
        clearApiToken();

        const formData = new FormData(form);
        const response = await loginApi({
            email: formData.get('email'),
            password: formData.get('password'),
        });
        const payload = unwrapData(response);

        if (payload.requires_two_factor) {
            showToast('Enter your authenticator code to continue.', 'info');
            window.location.href = payload.redirect || '/two-factor-challenge';
            return;
        }

        try {
            await completeAuthFlow(payload.token);
        } catch (error) {
            await revokeApiToken().catch(() => {});
            clearApiToken();
            throw error;
        }
    });

    bindAuthForm(document.getElementById('register-api-form'), async (form) => {
        clearApiToken();

        const formData = new FormData(form);
        const response = await registerApi({
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation'),
        });
        const payload = unwrapData(response);

        try {
            await completeAuthFlow(payload.token);
        } catch (error) {
            await revokeApiToken().catch(() => {});
            clearApiToken();
            throw error;
        }
    });

    const expiredBanner = document.querySelector('[data-session-expired]');
    if (expiredBanner && window.location.search.includes('session=expired')) {
        expiredBanner.classList.remove('hidden');
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAuthForms);
} else {
    initAuthForms();
}
