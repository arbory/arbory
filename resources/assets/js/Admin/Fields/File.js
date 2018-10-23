export default class File {
    /**
     * @param element
     */
    constructor(element) {
        this.element = element;

        this.registerEventHandlers();
    }

    /**
     * @return {void}
     */
    registerEventHandlers() {
        let field = this.getField();

        field.find('input').on('change', function () {
            field.find('input.remove').val(0);
        });

        field.find('button.remove').on('click', function () {
            field.find('input.remove').val(1);
            field.find('.thumbnail').hide();
            field.find('.file-details').hide();
        });
    }

    getField() {
        return jQuery(this.element);
    }
}
