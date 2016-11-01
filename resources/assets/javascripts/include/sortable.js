jQuery(document).ready(function()
{
    var body = jQuery('body');

    body.on('sortableinit', function(e)
    {
        var target = jQuery(e.target);
        if (!target.is('[data-sortable]'))
        {
            target = target.find('[data-sortable]');
        }

        target.each(function()
        {
            var list =  jQuery(this);
            if (list.is('.ui-sortable'))
            {
                return; // already initialized
            }

            list.sortable({
                cursor: "move",
                delay: 150,
                distance: 5,
                forcePlaceholderSize : true,
                handle: '> .handle',
                items: "> .item",
                scroll: true,
                start: function(e, ui){
                    ui.item.trigger('sortablestart');
                },
                stop: function(e,ui) {
                    ui.item.trigger('sortablestop');
                },
                update: function( event, ui )
                {
                    ui.item.trigger('sortableupdate');
                }
            });

            list.on('sortablereindex', function()
            {
                list.find('> .item:visible > input[type="hidden"].item-position').each(function(i)
                {
                    jQuery(this).attr('value', i);
                });
            });

            list.on('sortableupdate contentloaded contentremoved', function()
            {
                // item dragged to a new position
                // or
                // new content loaded or removed somewhere inside the list (possibly item added/removed)

                list.trigger('sortablereindex');
            });

            list.trigger('sortablereindex');
        });

    });

    body.on('contentloaded', function(e, event_params)
    {
        jQuery(e.target).trigger('sortableinit', event_params);
    });


});
