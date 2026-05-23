import { apiMessage } from './response.js';
import { clearFieldErrors, handleApiError, setFormLoading } from './errors.js';
import { showToast } from './toast.js';

export function formToJson(form) {
    const data = {};
    new FormData(form).forEach((value, key) => {
        if (key === '_method' || value instanceof File) return;
        if (value === '' && ['vin_number', 'next_service_due_date', 'next_service_due_mileage'].includes(key)) {
            data[key] = null;
            return;
        }
        if (value === '') return;
        data[key] = value;
    });
    return data;
}

export function formToFormData(form) {
    const fd = new FormData(form);
    if (!fd.get('vin_number')) fd.delete('vin_number');
    return fd;
}

export function bindApiForm(form, { submit, onSuccess }) {
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearFieldErrors(form);
        setFormLoading(form, true);

        try {
            const hasFile = form.querySelector('input[type="file"]')?.files?.length > 0;
            const payload = hasFile ? formToFormData(form) : formToJson(form);
            const response = await submit(payload);
            showToast(apiMessage(response, 'Saved successfully.'), 'success');
            if (onSuccess) await onSuccess(response);
        } catch (error) {
            handleApiError(error, form);
        } finally {
            setFormLoading(form, false);
        }
    });
}
