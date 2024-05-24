import Navigator from "./Services/Navigator";
export default class AdminPanel {
    /**
     * @param {FieldRegistry} registry
     */
    constructor(registry) {
        this.registry = registry;
    }

    /**
     * @param {FieldRegistry} registry
     */
    set registry(registry) {
        this._registry = registry;
    }

    /**
     * @return {FieldRegistry}
     */
    get registry() {
        return this._registry;
    }

    /**
     * @return {void}
     */
    initialize() {
        this.navigator = new Navigator();
        this.registerEventHandlers();
    }

    /**
     * @return {void}
     */
    registerEventHandlers() {
        let body = jQuery('body');

        body.on('nestedfieldsitemadd', 'section.nested .new', event => {
            this.initializeFields(event.target);
        });

        body.ready(() => {
            this.initializeFields(body[0]);
        });
    }

    /**
     * @return {void}
     */
    initializeFields(scope) {
        jQuery.each(this.registry.definitions, function(_, definition) {
            jQuery(scope).find(definition.selector).each((key, element) => {
                new definition.handler(element, definition.config);
            });
        });
    }
}
