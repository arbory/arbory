
const COMPACT_CKEDITOR_CONFIG = {
    language: 'en',
    entities_latin: false,
    forcePasteAsPlainText: true,
    height: '400px',
    allowedContent: true,
    format_tags: 'p;h2;h3',
    toolbar: [['Bold', 'Italic'], ['Subscript', 'Superscript'], ['Link', 'Unlink', 'Anchor'], ['Maximize', 'ShowBlocks']],
    extraPlugins: 'embed',
};

class CompactRichText extends RichText {
    getDefaultConfig() {
        return COMPACT_CKEDITOR_CONFIG;
    }
}

jQuery('body').on('contentloaded', function (e) {
    let block = jQuery(e.target);
    let textareas = block.is('textarea.richtext') ? block : block.find('textarea.richtext');
    // remove textareas that need not be initialized automatically
    textareas = textareas.not('.template textarea, textarea.manual-init');

    textareas.trigger('richtextinit');

    jQuery('body').on('richtextinit', 'textarea.richtext', function (event, config) {
        new RichText(this, config);
    });
});