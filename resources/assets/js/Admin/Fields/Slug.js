import SlugApiHandler from "../Services/SlugApiHandler";

export default class Slug {
    /**
     * @param element
     * @param config
     */
    constructor(element, config) {
        this.element = element;
        this.config = config;
        this.translatable = this.getField().hasClass('localization');
        this.initial = this.getFieldInput().val().length === 0;

        this.registerEventHandlers();
    }

    /**
     * @return {void}
     */
    registerEventHandlers() {
        this.getFieldGenerateButton().on('click', () => this.generate());
        if (this.initial) {
            this.getFieldInput().on('keyup', () => this.updateFieldLinkValue());
            this.getFromFieldInput().on('blur', () => this.generate());
        }
    }

    /**
     * @return {void}
     */
    generate() {
        this.getApi().createFrom(this.getFromFieldInput().val()).done(value => {
            this.getFieldInput().val(value);
            this.updateFieldLinkValue();
        });
    }

    /**
     * @return {SlugApiHandler}
     */
    getApi() {
        return new SlugApiHandler(this.getGeneratorUrl(), {
            parent_id: this.getNodeParentId(),
            object_id: this.getObjectId(),
            model_table: this.getModelTable(),
            column_name: this.getFieldName(),
            locale: this.getFieldLocale()
        });
    }

    /**
     * @return {void}
     */
    updateFieldLinkValue() {
        this.getFieldLink().find('span:last').text(this.getFieldInput().val());
    }

    /**
     * @return {string}
     */
    getGeneratorUrl() {
        return this.getFieldInput().data('generatorUrl');
    }

    /**
     * @return {string}
     */
    getFromFieldName() {
        return this.getFieldInput().data('fromFieldName');
    }

    /**
     * @return {string}
     */
    getNodeParentId() {
        return this.getFieldInput().data('nodeParentId');
    }

    /**
     * @return {int}
     */
    getObjectId() {
        return this.getFieldInput().data('objectId');
    }

    /**
     * @return {int}
     */
    getModelTable() {
        return this.getFieldInput().data('modelTable');
    }

    /**
     * @return {jQuery}
     */
    getField() {
        return jQuery(this.element);
    }

    /**
     * @return {string}
     */
    getFieldName() {
        return this.getField().data('name');
    }

    /**
     * @return {jQuery}
     */
    getFieldInput() {
        return this.getField().find('.value > input');
    }

    /**
     * @return string
     */
    getFieldLocale() {
        return this.getField().closest('.localization').data('locale');
    }

    /**
     * @return {jQuery}
     */
    getFieldGenerateButton() {
        return this.getField().find('.button.generate');
    }

    /**
     * @return {jQuery}
     */
    getFieldLink() {
        return this.getField().find('.link:first');
    }

    /**
     * @return {jQuery}
     */
    getFromField() {
        let selector = '.field';
        let attributes = '[data-name=' + this.getFromFieldName() + ']';
        if (this.translatable) {
            selector = '.localization';
            attributes += '[data-locale=' + this.getFieldLocale() + ']';
        }
        return this.getGroup().find(selector + attributes);
    }

    /**
     * @return {jQuery}
     */
    getFromFieldInput() {
        return this.getFromField().find('.value > input');
    }

    /**
     * @return {jQuery}
     */
    getGroup() {
        return this.getField().closest('fieldset.item,.body');
    }
}