jQuery(document).ready( function()
{
    var body = jQuery('body');

    body.on('contentreplace', function(e, content, selector)
    {
        if ((content) && ('status' in content) && ('getResponseHeader' in content))
        {
            // use content only if the response has valid 200 and html content type
            var status = content.status;
            if (status !== 200)
            {
                return;
            }

            var content_type = content.getResponseHeader("content-type");

            if (!content_type || !content_type.match(/html/))
            {
                return;
            }

            content = content.responseText;
        }

        var new_node;

        if (typeof selector !== 'undefined')
        {
            // selector given, find matching node in given content
            content = jQuery('<html />').append( content );
            new_node = content.find( selector );
        }
        else
        {
            // no selector given, whole content is the new node
            new_node = content;
        }


        // old_node defaults to event target if no selector given
        var old_node = jQuery(e.target);

        if (typeof selector !== 'undefined')
        {
            // but matches self or descendants if selector is given
            if (!old_node.is( selector ))
            {
                old_node = old_node.find( selector );
            }
        }

        old_node.replaceWith( new_node );

        new_node.trigger('contentloaded');

    });

    // use setTimeout to trigger this after all scripts have been loaded
    // and attached their initial handlers for this event
    setTimeout( function()
    {
        body.trigger('contentloaded');
        body.trigger('contentdone');
    }, 0);
});
