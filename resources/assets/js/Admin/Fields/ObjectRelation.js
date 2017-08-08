
export default class ObjectRelation {

    /**
     * @return {string}
     */
    static getSelector() {
        return '.type-object-relation';
    }

    /**
     * @param element
     * @param {Object} config
     */
    constructor(element, config) {
        this.element = element;
        this.config = config;

        this.registerEventHandlers();
    }

    /**
     * @return {void}
     */
    registerEventHandlers() {
        let field = this.getField();
        let related = this.getRelatedElement();
        let relational = this.getRelationalElement();
        let relationalItems = relational.find('.item');

        relationalItems.on('click', event => {
            let item = jQuery(event.target);

            if (! item.hasClass('item'))
            {
                item = item.closest('.item');
            }

            this.selectRelation(item);
        });

        if (this.isSingular()) {
            jQuery(window).click(function() {
                relational.removeClass('active');
            });

            related.on('click', () => {
                jQuery(ObjectRelation.getSelector() + ' .relations.active').removeClass('active');

                relational.toggleClass('active');
            });

            field.children('.value').on('click', event => event.stopPropagation());
            relationalItems.on('click', () => relational.toggleClass('active'));
        }
    }

    /**
     * @param item
     */
    selectRelation(item) {
        let title = item.find('.title').text();

        this.getRelatedIdInputElement().val(item.data('key'));
        this.getRelatedElement().find('.title').html(title);
    }

    /**
     * @return {jQuery}
     */
    getField() {
        return jQuery(this.element);
    }

    /**
     * @return {bool}
     */
    isSingular() {
        return this.getField().hasClass('single');
    }

    /**
     * @return {jQuery}
     */
    getRelatedIdInputElement() {
        return this.getField().find('input[data-name=related_id]');
    }

    /**
     * @return {jQuery}
     */
    getRelatedElement() {
        return this.getField().find('.related');
    }

    /**
     * @return {jQuery}
     */
    getRelationalElement() {
        return this.getField().find('.relations');
    }
}