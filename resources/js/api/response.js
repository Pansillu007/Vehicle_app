export function apiMessage(response, fallback = 'Success') {
    return response?.data?.message || fallback;
}

export function unwrapList(response) {
    const body = response?.data;

    if (body?.success === true && body.data) {
        return {
            items: body.data.items ?? [],
            meta: body.data.meta ?? null,
        };
    }

    return {
        items: body?.data ?? [],
        meta: body?.meta ?? null,
    };
}

export function unwrapData(response) {
    const body = response?.data;
    if (body?.success === true) {
        return body.data;
    }

    return body?.data ?? body;
}
