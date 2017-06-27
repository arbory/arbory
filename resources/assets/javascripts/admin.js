
import 'admin/ckeditor';
import RichText from 'admin/fields/richtext';
import CompactRichText from "./admin/fields/compact_richtext";

let body = jQuery('body');

// TODO: remove events

jQuery('textarea.richtext').on('richtextinit', function(event, config) {
    let textArea = jQuery(this);

    if (textArea.hasClass('compact')) {
        new CompactRichText(this, config);
    } else {
        new RichText(this, config);
    }
});

body.on('contentloaded', function (e) {
    let block = jQuery(e.target);
    let textareas = block.is('textarea.richtext') ? block : block.find('textarea.richtext');
    // remove textareas that need not be initialized automatically
    textareas = textareas.not('.template textarea, textarea.manual-init');

    textareas.trigger('richtextinit');
});