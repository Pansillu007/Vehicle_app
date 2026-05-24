import api from './client.js';
import { handleApiError } from './errors.js';
import { showToast } from './toast.js';

async function parseBlobError(blob) {
    try {
        const text = await blob.text();
        const payload = JSON.parse(text);
        return payload.message || 'Could not download CSV export.';
    } catch {
        return 'Could not download CSV export.';
    }
}

export async function downloadCsv(url, filename, params = {}) {
    try {
        const response = await api.get(url, {
            params,
            responseType: 'blob',
        });

        const contentType = response.headers['content-type'] || '';
        if (contentType.includes('application/json')) {
            showToast(await parseBlobError(response.data), 'error');
            return;
        }

        const blob = new Blob([response.data], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
        showToast('CSV export downloaded.', 'success');
    } catch (error) {
        if (error.response?.data instanceof Blob) {
            showToast(await parseBlobError(error.response.data), 'error');
            return;
        }
        handleApiError(error);
    }
}
