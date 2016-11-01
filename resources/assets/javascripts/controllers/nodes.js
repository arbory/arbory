jQuery(function()
{
    var body = jQuery('body.controller-releaf-content-nodes');

    body.on('contentloaded', function(e)
    {
        var block = jQuery(e.target);

        // item collapse / expand
        block.find('.collection li .collapser').click(function()
        {
            var item             = jQuery(this).closest('.collection li');
            var should_expand   = item.is('.collapsed');
            var event_name      = should_expand ? 'nodeitemexpand' : 'nodeitemcollapse';

            item.trigger(event_name);

            var setting_key = 'content.tree.expanded.' + item.data('id');
            body.trigger( 'settingssave', [ setting_key, should_expand ] );
        });

        block.find('.collection li').bind('nodeitemcollapse', function( e )
        {
            e.stopPropagation();

            var item = jQuery(e.target);
            item.addClass('collapsed');
            item.children('.collapser-cell').find('.collapser i').removeClass('fa-chevron-down').addClass('fa-chevron-right');

        });

        block.find('.collection li').bind('nodeitemexpand', function( e )
        {
            e.stopPropagation();

            var item = jQuery(e.target);
            item.removeClass('collapsed');
            item.children('.collapser-cell').find('.collapser i').removeClass('fa-chevron-right').addClass('fa-chevron-down');

        });



        // slug generation
        var name_input  = block.find('.node-fields .field[data-name="name"] input');
        var slug_field  = block.find('.node-fields .field[data-name="slug"]');

        if (name_input.length && slug_field.length)
        {
            var slug_input  = slug_field.find('input');
            var slug_button = slug_field.find('.generate');
            var slug_link   = slug_field.find('a');

            slug_input.on('sluggenerate', function()
            {
                var url = slug_input.attr('data-generator-url');

                slug_button.trigger('loadingstart');
                jQuery.get( url, { name: name_input.val() }, function( slug )
                {
                    slug_input.val( slug );
                    slug_link.find('span').text( encodeURIComponent( slug ) );
                    slug_button.trigger('loadingend');
                }, 'text');
            });

            slug_button.click(function()
            {
                slug_input.trigger('sluggenerate');
            });

            if (name_input.val() === '')
            {
                // bind onchange slug generation only if starting out with an empty name
                name_input.change(function()
                {
                    slug_input.trigger('sluggenerate');
                });
            }
        }

    });

    body.on('click', '.dialog .node-cell label', function() {
        jQuery('.dialog .node-cell label').removeClass('selected');
        jQuery(this).addClass('selected');
    });
});
