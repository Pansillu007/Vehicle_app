import { clearApiToken } from './auth.js';
import { showToast } from './toast.js';

export function clearFieldErrors(form) {
    form.querySelectorAll('[data-field-error]').forEach((el) => el.remove());
    form.querySelectorAll('.border-red-500').forEach((el) => {
        el.classList.remove('border-red-500', 'dark:border-red-500');
    });
}

export function showFieldErrors(form, errors) {
    clearFieldErrors(form);
    if (!errors || typeof errors !== 'object') {
        return;
    }

    Object.entries(errors).forEach(([field, messages]) => {
        const input = form.querySelector(`[name="${field}"], #${field}`);
        if (input) {
            input.classList.add('border-red-500', 'dark:border-red-500');
            const p = document.createElement('p');
            p.className = 'text-sm text-red-600 dark:text-red-400 mt-1';
            p.setAttribute('data-field-error', field);
            p.textContent = Array.isArray(messages) ? messages[0] : messages;
            (input.closest('div') || input.parentElement)?.appendChild(p);
        }
    });
}

function redirectToLogin() {
    if (!window.location.pathname.includes('login')) {
        window.location.href = '/login?session=expired';
    }
}

window.addEventListener('api:unauthorized', () => {
    showToast('Your session expired. Please sign in again.', 'error');
    setTimeout(redirectToLogin, 800);
});

export function handleApiError(error, form = null) {
    if (error?.code === 'ERR_CANCELED') {
        return;
    }

    if (error.response?.status === 401) {
        clearApiToken();
        if (!form && !window.location.pathname.includes('login')) {
            showToast('Your session expired. Please sign in again.', 'error');
        }
        return;
    }

    if (error.response?.status === 422) {
        const errors = error.response.data?.errors;
        if (form && errors) {
            showFieldErrors(form, errors);
        }
        showToast(error.response.data?.message || 'Please fix the validation errors.', 'error');
        return;
    }

    if (error.response?.status === 403) {
        showToast('You are not authorized to perform this action.', 'error');
        return;
    }

    if (error.status === 401 || error.message?.includes('session')) {
        showToast(error.message || 'Could not establish session. Please sign in again.', 'error');
        return;
    }

    if (error.response?.data?.message) {
        showToast(error.response.data.message, 'error');
        return;
    }

    if (error.message) {
        showToast(error.message, 'error');
        return;
    }

    showToast('Something went wrong. Please try again.', 'error');
}

export function setFormLoading(form, loading) {
    const btn = form.querySelector('[type="submit"]');
    if (!btn) return;

    btn.disabled = loading;
    btn.dataset.originalText = btn.dataset.originalText || btn.innerHTML;

    if (loading) {
        btn.innerHTML = `<span class="inline-flex items-center gap-2">
            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            Saving...
        </span>`;
    } else {
        btn.innerHTML = btn.dataset.originalText;
    }
}
