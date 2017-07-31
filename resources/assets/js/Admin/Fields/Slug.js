import SlugApiHandler from "../Services/SlugApiHandler";

export default class Slug {
    /**
     * @param element
     * @param config
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
        this.getFieldInput().on('keyup', () => this.updateFieldLinkValue());
        this.getFieldGenerateButton().on('click', () => this.generate());
        this.getFromFieldInput().on('blur', () => this.generate());
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
            model_table: this.getModelTable(),
            column_name: this.getFieldName()
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
     * @return {int}
     */
    getNodeParentId() {
        return this.getFieldInput().data('nodeParentId');
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
        return this.getForm().find('.field[data-name=' + this.getFromFieldName() + ']');
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
    getForm() {
        return this.getField().closest('form');
    }
}