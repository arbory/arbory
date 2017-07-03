
import RichText from "./RichText";

export const CKEDITOR_CONFIG_COMPACT = {
    language: 'en',
    entities_latin: false,
    forcePasteAsPlainText: true,
    height: '400px',
    allowedContent: true,
    format_tags: 'p;h2;h3',
    toolbar: [['Bold', 'Italic'], ['Subscript', 'Superscript'], ['Link', 'Unlink'], ['Maximize', 'ShowBlocks']],
    extraPlugins: 'embed',
    forceEnterMode: true,
    enterMode : CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_BR,
    autoParagraph: false
};

export default class CompactRichText extends RichText {
    getDefaultConfig() {
        return CKEDITOR_CONFIG_COMPACT;
    }
}