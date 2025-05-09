/**
 * Get part of URL
 *
 * @param {string} url Url
 * @param {string} part Url object key
 * @return {*|boolean}
 */
export const getPartOfUrl = (url, part) => {
    try {
        const urlObj = new URL(url);
        return part in urlObj ? urlObj[part] : false;
    } catch (e) {
        console.error(e);
        return false;
    }
}

/**
 * Get prepared data to save
 *
 * @param {object} data Data
 * @return {{key: string, value: any}[]}
 */
export const getSettingsDataToSave = (data) => {
    return Object.keys(data).reduce((arr, key) => {
        arr.push({
            key: key,
            value: data[key][0],
        });
        return arr;
    }, []);
}

/**
 * Get setting formated value for form
 *
 * @param {any} value Value
 * @return {number|string|*}
 */
export const getSettingFormatedValueForForm = (value) => {
    if (typeof value === 'boolean') {
        return +value || '';
    }

    return value;
}

/**
 * Add form new field
 *
 * @param {HTMLFormElement} form Form
 * @param {string} name Field name
 * @param {string} value Field value
 */
export const addDataToForm = (form, name, value) => {
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = name;
    hiddenInput.value = getSettingFormatedValueForForm(value);
    // Add input to form
    form.appendChild(hiddenInput);
    // Clean up (hidden inputs elements)
    setTimeout(() => hiddenInput.remove(), 0);
}

/**
 * Add form new field(s)
 *
 * @param {HTMLFormElement} form Form
 * @param {string} keyPrefix Field name
 * @param {object|string} obj Field value
 */
export const addObjectDataToForm = (form, keyPrefix, obj) => {
    if (obj && typeof obj === 'object') {
        Object.entries(obj).forEach(([key, value]) => {
            addObjectDataToForm(form, `${keyPrefix}[${key}]`, value);
        });
    } else {
        addDataToForm(form, keyPrefix, getSettingFormatedValueForForm(obj));
    }
}