jQuery(document).ready(function () {
    jQuery('body').on('click', '.constructor-dialog .js-select-block', function (e) {
        e.preventDefault();

        const target = jQuery(e.target);

        const name = target.data('name');
        const field = target.data('field');

        const constructor = jQuery('body').find(`.type-constructor[data-namespaced-name="${field}"]`);

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