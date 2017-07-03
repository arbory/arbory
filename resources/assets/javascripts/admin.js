
import 'admin/ckeditor';
import RichText from 'admin/fields/RichText';
import CompactRichText from "./admin/fields/CompactRichtText";
import IconPicker from "./admin/fields/IconPicker";

export function initializeRichText(using, config ={}, className = '') {
    for (let textArea of document.querySelectorAll('textarea.richtext')) {
        if (textArea.classList.contains(className) && !(textArea.id in CKEDITOR.instances)) {
            new using(textArea, config);
        }
    }
}

export function initializeFullRichText(config = {}, using = RichText) {
    initializeRichText(using, config, 'full');
}

export function initializeCompactRichText(config = {}, using = CompactRichText) {
    initializeRichText(using, config, 'compact');
}

let body = jQuery('body');

body.on('richtextinit nestedfieldsitemadd', () => {
    initializeFullRichText();
    initializeCompactRichText();
});

body.ready(() => {
    body.trigger('richtextinit');

    body.find('.type-icon-picker').each((key, element) => {
        new IconPicker(element);
    });
});