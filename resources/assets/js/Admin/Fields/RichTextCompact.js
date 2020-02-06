import RichText from "./RichText";

export const CONFIG_EDITOR_COMPACT = {
    language: 'en',
    entities_latin: false,
    forcePasteAsPlainText: true,
    height: '400px',
    allowedContent: true,
    format_tags: 'p;h2;h3',
    toolbar: [['Bold', 'Italic'], ['Subscript', 'Superscript'], ['Link', 'Unlink'], ['Maximize', 'ShowBlocks']],
    forceEnterMode: true,
    enterMode : CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_BR,
    autoParagraph: false
};

export default class RichTextCompact extends RichText {
    getDefaultConfig() {
        return CONFIG_EDITOR_COMPACT;
    }
}