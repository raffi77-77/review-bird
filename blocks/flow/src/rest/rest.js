/**
 * URL search params
 *
 * @param {object} params Query params
 * @param {string} pre '?'
 * @return {string}
 */
export const urlSearchParams = (params, pre = '?') => {
    try {
        const urlSearchParams = new URLSearchParams();
        for (const key in params) {
            if (Array.isArray(params[key])) {
                params[key].forEach(value => urlSearchParams.append(`${key}[]`, value));
            } else {
                urlSearchParams.append(key, params[key]);
            }
        }
        // To string
        let queryString = urlSearchParams.toString();
        // Add pre part
        if (queryString) {
            queryString = pre + queryString
        }
        return queryString;
    } catch (e) {
        console.error(e);
        return '';
    }
}

/**
 * Handle request response
 *
 * @param {object} res Response
 * @return {Promise<*>}
 */
export const handleResponse = async (res) => {
    let data;
    // Parse response
    try {
        data = await res.json();
    } catch (e) {
        // Invalid response
        throw new Error(__("Something went wrong", 'review-bird'));
    }
    if (!res.ok) {
        let message = data.message;
        if (data.data?.details) {
            const messages = Object.values(data.data.details);
            if (messages.length) {
                message = messages.map(item => __(item.message, 'review-bird')).join("<br/>");
            }
        } else if (data.data?.message) {
            message = data.data?.message;
        }
        throw new Error(message ? message : __("Something went wrong", 'review-bird'));
    }
    return data;
}

/**
 * Get flow
 *
 * @param {string} restUrl Rest url
 * @param {string} restNonce Rest nonce
 * @param {string} id Flow ID
 * @param {object} params Query params
 * @return {Promise<*>}
 * @constructor
 */
export const GetFlow = (restUrl, restNonce, id, params = {}) => {
    const query = urlSearchParams(params);

    return fetch(`${restUrl}flows/${id}${query}`, {
        headers: {
            'X-WP-Nonce': restNonce
        }
    }).then(handleResponse);
}

/**
 * Create review
 *
 * @param {string} restUrl Rest url
 * @param {string} restNonce Rest nonce
 * @param {object} requestData Request data
 * @return {Promise<*>}
 * @constructor
 */
export const CreateReview = (restUrl, restNonce, requestData) => {
    return fetch(`${restUrl}reviews`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': restNonce
        },
        body: JSON.stringify(requestData)
    }).then(handleResponse);
}

/**
 * Get settings
 *
 * @param {string} restUrl Rest url
 * @param {string} restNonce Rest nonce
 * @param {object} params Query params
 * @return {Promise<any>}
 */
export const GetSettings = (restUrl, restNonce, params = {}) => {
    const query = urlSearchParams(params);

    return fetch(`${restUrl}settings${query}`, {
        headers: {
            'X-WP-Nonce': restNonce
        }
    }).then(handleResponse);
}