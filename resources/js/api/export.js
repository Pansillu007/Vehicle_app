import api from './client.js';
import { showToast } from './toast.js';

export async function downloadCsv(url, filename, params = {}) {
    try {
        const response = await api.get(url, {
            params,
            responseType: 'blob',
        });

        const blob = new Blob([response.data], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
        showToast('CSV export downloaded.', 'success');
    } catch {
        showToast('Could not download CSV export.', 'error');
    }
}
