
export const CONFIG_EDITOR = {
    language: 'en',
    entities_latin: false,
    forcePasteAsPlainText: true,
    height: '400px',
    allowedContent: true,
    format_tags: 'p;h2;h3',
    toolbar: [['Bold', 'Italic'], ['Format'], ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Subscript', 'Superscript'], ['NumberedList', 'BulletedList'], ['Link', 'Unlink', 'Anchor', 'Image', 'MediaEmbed'], ['Source', 'Maximize', 'ShowBlocks']],
    extraPlugins: 'mediaembed',
    coreStyles_bold: { element: 'b', overrides: 'strong' },
    coreStyles_italic: { element: 'i', overrides: 'em' }
};

export default class RichText {
    constructor(element, config = {}) {
        if (element.id in CKEDITOR.instances) {
            CKEDITOR.instances[element.id].destroy();
        }

        this.element = element;
        this.config = config;

        this.initialize();
    }

    initialize() {
        let token = this.getToken();
        let textarea = this.getTextarea();
        let config = Object.assign(this.getDefaultConfig(), {
            width: '100%',
            height: textarea.outerHeight(),
            filebrowserImageBrowseUrl: '/admin/filemanager?type=Images',
            filebrowserImageUploadUrl: '/admin/filemanager/upload?type=Images&responseType=json&_token=' + token,
            filebrowserBrowseUrl: '/admin/filemanager?type=Files',
            filebrowserUploadUrl: '/admin/filemanager/upload?type=Files&responseType=json&_token=' + token
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

        form.on('beforevalidation', () => {
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
        return CONFIG_EDITOR;
    }

    getToken() {
        let token = document.head.querySelector('meta[name="csrf-token"]');

        return token ? token.content : null;
    }
}