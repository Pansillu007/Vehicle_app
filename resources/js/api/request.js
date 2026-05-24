/**
 * Prevents stale API responses from overwriting newer data.
 */
export function createRequestGuard() {
    let requestId = 0;

    return {
        next() {
            requestId += 1;
            return requestId;
        },
        isCurrent(id) {
            return id === requestId;
        },
    };
}

/** Disable a button during async work to prevent duplicate mutations. */
export async function withButtonLoading(button, work) {
    if (!button || button.disabled) {
        return undefined;
    }

    const original = button.innerHTML;
    button.disabled = true;
    button.classList.add('opacity-70', 'cursor-not-allowed');

    try {
        return await work();
    } finally {
        button.disabled = false;
        button.classList.remove('opacity-70', 'cursor-not-allowed');
        button.innerHTML = original;
    }
}
