CKEDITOR.basePath = '/leaf/ckeditor/';

CKEDITOR.on('instanceReady', function(e) {
    jQuery(e.editor.element.$).addClass("ckeditor-initialized");
});