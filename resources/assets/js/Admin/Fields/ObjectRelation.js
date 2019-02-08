/**
 * @typedef {Object} Options
 * @property {number} limit
 * @property {string} grouped
 * @property {number} indent
 */

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

        if (this.isGrouped()) {
            relational.on('click', '.group > .title', (event) => {
                let group = jQuery(event.target).closest('.group');

                group.find('.item').each((_, element) => {
                    this.selectRelation(jQuery(element));
                });
            })
        }

        if (this.isSingular()) {
            jQuery(window).click(function() {
                relational.removeClass('active');
            });

            related.on('click', () => {
                if(!this.isInteractive()) {
                    return false;
                }

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
        if(!this.isInteractive()) {
            return false;
        }

        let selectedItem = item.clone();
        let key = item.data('key');

        if (this.selected.has(key) || (!this.isSingular() && this.hasLimit() && this.selected.size >= this.getLimit())) {
            return;
        }

        if (this.isSingular()) {
            this.selected.clear();
            this.getRelationalElement().find('.item').attr('data-inactive', 'false');
        }

        this.selected.add(key);

        item.attr('data-inactive', 'true');

        if (this.isSingular()) {
            this.getRelatedElement().html(selectedItem);
        } else {
            this.getRelatedElement().append(selectedItem);
        }

        this.updateSelectedInputElement();
    }

    /**
     * @param item
     */
    removeRelation(item) {
        if(!this.isInteractive()) {
            return false;
        }

        let key = item.data('key');

        this.selected.delete(key);

        item.remove();
        this.getRelationalElement().find('.item[data-key=' + key + ']').attr('data-inactive', 'false');

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
     * @return {boolean}
     */
    hasLimit() {
        return this.getLimit() > 0;
    }

    /**
     * @return {Number}
     */
    getLimit() {
        return parseInt(this.getOptions().limit);
    }

    /**
     * @return {boolean}
     */
    hasIndentation() {
        return this.getOptions().indent !== undefined;
    }

    /**
     * @return {boolean}
     */
    isSingular() {
        return this.getField().hasClass('single');
    }

    /**
     * @return {boolean}
     */
    isGrouped() {
        return this.getOptions().grouped !== undefined;
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

    /**
     * @return {Options}
     */
    getOptions() {
        let element = this.getField().find('.object-relation');

        return {
            limit: element.data('limit'),
            grouped: element.data('grouped'),
            indent: element.data('indent')
        };
    }


    isInteractive() {
        let disabled = $(this.element).data('disabled');
        let interactive = $(this.element).data('interactive');

        return interactive && !disabled;
    }
}