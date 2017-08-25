
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
        this.selected = new Set(this.getInitialValues());

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
        } else {
            this.getRelatedElement().on('click', '.item', event => {
                let item = jQuery(event.target);

                if (!item.hasClass('item')) {
                    item = item.closest('.item');
                }

                this.removeRelation(item);
            });
        }
    }

    /**
     * @param item
     */
    selectRelation(item) {
        let key = item.data('key');

        if (this.selected.has(key) || this.selected.size >= this.getLimit()) {
            return;
        }

        if (this.isSingular()) {
            this.selected.clear();
        }

        this.selected.add(key);

        this.updateSelectedInputElement();

        if (this.isSingular()) {
            this.getRelatedElement().html(item);
        } else {
            this.getRelatedElement().append(item);
        }
    }

    /**
     * @param item
     */
    removeRelation(item) {
        this.selected.delete(item.data('key'));

        this.getRelationalElement().append(item);

        this.updateSelectedInputElement();
    }

    /**
     * @return {Array}
     */
    getInitialValues() {
        let selected = [];
        let items = this.getRelatedElement().find('.item');

        items.each((key, element) => {
            let item = jQuery(element);

            selected.push(item.data('key'));
        });

        return selected;
    }

    /**
     * @return {void}
     */
    updateSelectedInputElement()
    {
        this.getRelatedIdInputElement().val(Array.from(this.selected).join(','));
    }

    /**
     * @return {jQuery}
     */
    getField() {
        return jQuery(this.element);
    }

    /**
     * @return {Number}
     */
    getLimit() {
        return parseInt(this.getField().data('limit'));
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
        return this.getField().find('[data-name=related_id]');
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