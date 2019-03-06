jQuery(document).ready(function () {
    jQuery('body').on('click', '.constructor-dialog .js-select-block', function (e) {
        e.preventDefault();

        var name = jQuery(e.target).data('name');
        var field = jQuery(e.target).data('field');

        var constructor = jQuery('.type-constructor[data-name="' + field + '"]');
        var templates = constructor.data('templates');

        if(name in templates) {
            constructor.trigger('nestedfieldscreate', {
                target_block: constructor,
                template: jQuery(templates[name])
            });

            jQuery('body').trigger('ajaxboxclose');
        }
    })
});