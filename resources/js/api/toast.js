const containerId = 'vp-toast-container';

function ensureContainer() {
    let el = document.getElementById(containerId);
    if (!el) {
        el = document.createElement('div');
        el.id = containerId;
        el.className = 'fixed top-20 right-4 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none';
        document.body.appendChild(el);
    }
    return el;
}

export function showToast(message, type = 'success') {
    const container = ensureContainer();
    const toast = document.createElement('div');
    const styles = {
        success: 'border-emerald-500/50 bg-emerald-500/10 text-emerald-800 dark:text-emerald-200',
        error: 'border-red-500/50 bg-red-500/10 text-red-800 dark:text-red-200',
        info: 'border-blue-500/50 bg-blue-500/10 text-blue-800 dark:text-blue-200',
    };

    toast.className = `pointer-events-auto glass-card rounded-xl px-4 py-3 text-sm font-medium border-l-4 shadow-xl animate-slide-up ${styles[type] || styles.info}`;
    toast.setAttribute('role', 'alert');
    toast.textContent = message;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}
