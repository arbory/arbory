
import 'admin/ckeditor';
import RichText from 'admin/fields/RichText';
import CompactRichText, {CKEDITOR_CONFIG_COMPACT} from "./admin/fields/CompactRichtText";
import IconPicker from "./admin/fields/IconPicker";
import {CKEDITOR_CONFIG} from "./admin/fields/RichText";
import Sortable, {CONFIG_JQUERY_SORTABLE} from "./admin/fields/Sortable";

export let FIELD_TYPE_DEFINITIONS = {
    RichText: {
        handler: RichText,
        config: CKEDITOR_CONFIG,
        selector: '.type-richText.full'
    },
    CompactRichText: {
        handler: CompactRichText,
        config: CKEDITOR_CONFIG_COMPACT,
        selector: '.type-richText.compact'
    },
    IconPicker: {
        handler: IconPicker,
        selector: '.type-icon-picker'
    },
    Sortable: {
        handler: Sortable,
        config: {
            vendor: CONFIG_JQUERY_SORTABLE
        },
        selector: '.type-sortable'
    }
};

export function initializeFields(scope) {
    for (let [_, definition] of Object.entries(FIELD_TYPE_DEFINITIONS)) {
        jQuery(scope).find(definition.selector).each((key, element) => {
            new definition.handler(element, definition.config);
        });
    }
}

let body = jQuery('body');

body.on('nestedfieldsitemadd', 'section.nested', event => {
    initializeFields(event.target);
});

body.ready(() => {
    initializeFields(body[0]);
});