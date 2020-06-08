export default class SlugApiHandler {
    /**
     * @param {string} apiUrl
     * @param {Object} parameters
     */
    constructor(apiUrl, parameters = {}) {
        this.apiUrl = apiUrl;
        this.parameters = parameters;
    }

    /**
     * @param {string} value
     */
    createFrom(value) {
        return jQuery.get(this.apiUrl, Object.assign({from: value}, this.parameters));
    }
}