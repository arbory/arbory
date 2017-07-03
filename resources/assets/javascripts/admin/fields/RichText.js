
export const CKEDITOR_CONFIG = {
    language: 'en',
    entities_latin: false,
    forcePasteAsPlainText: true,
    height: '400px',
    allowedContent: true,
    format_tags: 'p;h2;h3',
    toolbar: [['Bold', 'Italic'], ['Format'], ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Subscript', 'Superscript'], ['NumberedList', 'BulletedList'], ['Link', 'Unlink', 'Anchor', 'Image', 'Embed'], ['Source', 'Maximize', 'ShowBlocks']],
    extraPlugins: 'embed',
    embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}'
};

export default class RichText {
    constructor(element, config = {}) {
        this.element = element;
        this.config = config;

        this.initialize();
    }

    initialize() {
        let textarea = this.getTextarea();
        let config = Object.assign(this.getDefaultConfig(), {
            width: '100%',
            height: textarea.outerHeight()
        });

        if (!textarea.attr('id')) {
            textarea.attr('id', 'richtext_' + String((new Date()).getTime()).replace(/\D/gi, ''));
        }

        if (textarea.data('attachment-upload-url')) {
            config.filebrowserUploadUrl = textarea.data('attachment-upload-url');
        }

        if (textarea.data('external-stylesheet')) {
            config.contentsCss = textarea.data('external-stylesheet');
        }

        for (let [key, value] of Object.entries(this.getCustomConfig())) {
            config[key] = value;
        }

        CKEDITOR.replace(this.element, config);

        this.registerEventHandlers(textarea);
    }

    registerEventHandlers() {
        let textarea = this.getTextarea();
        let form = textarea.closest("form");

        form.on('beforevalidation', function () {
            for (let instance in CKEDITOR.instances) {
                if (CKEDITOR.instances.hasOwnProperty(instance)) {
                    CKEDITOR.instances[instance].updateElement();
                }
            }
        });

        textarea.on('richtextresume', () => {
            if (! textarea.data('richtext-suspended')) {
                return;
            }

            textarea.show();
            textarea.data('richtext-suspended', false);

            this.initialize();
        });

        textarea.on('richtextsuspend', () => {
            if (textarea.data('richtext-suspended')) {
                return;
            }

            CKEDITOR.instances[textarea.attr('id')].destroy();

            textarea.hide();
            textarea.data('richtext-suspended', true);
        });

        textarea.on('focusprepare', () => {
            if (textarea.data('richtext-suspended')) {
                return;
            }

            CKEDITOR.instances[textarea.attr('id')].focus();
        });
    }

    getCustomConfig() {
        return this.config;
    }

    getTextarea() {
        return jQuery(this.element);
    }

    getDefaultConfig() {
        return CKEDITOR_CONFIG;
    }
}