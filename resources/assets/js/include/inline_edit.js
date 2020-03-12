const config = {
    selectors: {
        value: '.js-value',
        field: '.js-field',

    },
    element: {
        loading: 'loading',
    },
    field: {
        loaded: 'loaded',
        error: 'has-error'
    },
    error: 'error-box',
    loader: 'loader',
    hidden: '-hidden',
};

class InlineEdit
{
    constructor(element) {
        this.element = jQuery(element);
        this.value = this.element.find(config.selectors.value);
        this.field = this.element.find(config.selectors.field);

        this.initialize();
    }

    initialize() {
        this.value.on('click', this.showInlineEdit.bind(this));
    }

    showInlineEdit() {
        if (this.element.hasClass(config.element.loading)) {
            return;
        }

        if (!this.field.data(config.field.loaded)) {
            this.loadField();
            return;
        }

        this.showField();
    }

    showField() {
        this.value.addClass(config.hidden);
        this.field
            .removeClass(config.hidden)
            .find(':input').focus();
    }

    hideField() {
        this.field.addClass(config.hidden);
        this.value.removeClass(config.hidden);
    }

    loadField() {
        let self = this;

        jQuery.ajax({
            type: 'get',
            url: this.element.data('edit-url'),
            data: {column: this.element.data('column')},
            beforeSend: () => self.showLoader(),
            success: (response) => self.fieldLoaded(response),
            complete: () => self.hideLoader()
        });
    }

    fieldLoaded(response) {
        this.field
            .data(config.field.loaded, true)
            .html(response.field);

        this.field.find(':input')
            .on('blur', this.hideField.bind(this))
            .on('change paste', this.saveValue.bind(this));

        this.showField();
    }

    saveValue() {
        let self = this;
        let changedField = jQuery(event.target);
        let data = {
            _method: 'put',
            _token: InlineEdit.getToken(),
            column: this.element.data('column'),
        };
        data[changedField.attr('name')] = changedField.val();

        jQuery.ajax({
            type: 'post',
            url: this.element.data('update-url') + location.search,
            data: data,
            beforeSend: () => {
                self.showLoader();
                self.hideValidationErrors();
            },
            success: (response) => self.updateValue(response.columnValue),
            error: (response) => {
                if (response.status === 422) {
                    self.showField();
                    self.showValidationErrors(response.responseJSON.errors, changedField);
                }
            },
            complete: () => self.hideLoader()

        });
    }

    updateValue(value) {
        this.value.html(value);
    }

    showValidationErrors(errors, field) {
        let errorBox;
        let message = [];
        jQuery.each(errors, (fieldName, fieldErrors) => {
            message.push(fieldErrors.join('<br>'));
        });
        errorBox = InlineEdit.errorBox(message.join('<br>'));

        field
            .after(errorBox)
            .closest('.field').addClass(config.field.error);
    }

    hideValidationErrors() {
        this.element.find('.' + config.error).remove();
        this.element.find('.' + config.field.error).removeClass(config.field.error);
    }

    static errorBox(errorMessage) {
        return jQuery('<div></div>')
            .addClass(config.error)
            .append('<div class="error"><div>')
            .html(errorMessage);
    }

    showLoader() {
        this.element
            .addClass(config.element.loading)
            .prepend(InlineEdit.loader());
    }

    hideLoader() {
        this.element
            .removeClass(config.element.loading)
            .find('.' + config.loader).remove();
    }

    static loader() {
        return jQuery('<i class="fa fa-spin fa-spinner" />').addClass(config.loader);
    }

    static getToken() {
        return jQuery('meta[name="csrf-token"]').attr('content');
    }
}

jQuery(function() {
    let body = jQuery('body');

    body.on('contentloaded', function(event) {
        jQuery(event.target).find('.js-inline-edit').each(function (index, element) {
            new InlineEdit(element);
        });
    });
});