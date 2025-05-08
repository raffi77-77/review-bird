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