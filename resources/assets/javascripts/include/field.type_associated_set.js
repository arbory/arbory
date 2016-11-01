jQuery( document ).ready(function()
{
    var body = jQuery('body');

    jQuery(document).bind('associatedsetsinit', function( e )
    {
        var target_selector = '.field.type-associated-set';
        var target = jQuery(e.target);
        if (!target.is(target_selector))
        {
            target = target.find(target_selector);
        }

        target.each(function()
        {
            var block = jQuery(this);
            var checkboxes = block.find('input.keep');
            checkboxes.bind('click', function()
            {
                var checkbox = jQuery(this);
                var destroy = checkbox.siblings('input.destroy');
                destroy.val(checkbox.prop('checked') ? 'false' : 'true');
            });
        });

    });

    body.on('contentloaded', function(e, event_params)
    {
        jQuery(e.target).trigger('associatedsetsinit', event_params);
    });
});
