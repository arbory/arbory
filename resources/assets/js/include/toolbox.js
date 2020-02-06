import UrlBuilder from '../modules/UrlBuilder';

jQuery(function()
{
    var body = jQuery('body');
    var xhr;

    var overlay = jQuery('<div />').addClass('toolbox-overlay').appendTo(body);
    overlay.bind('click', function()
    {
        body.trigger('toolboxcloseall');
    });

    body.bind('toolboxinit', function(e)
    {
        var target = jQuery(e.target);

        e.stopPropagation();

        var toolboxes;
        if (target.is('.toolbox'))
        {
            toolboxes = target;
        }
        else
        {
            toolboxes = target.find('.toolbox');
        }

        if (toolboxes.length < 1)
        {
            return;
        }

        toolboxes.bind('toolboxopen', function()
        {
            var toolbox   = jQuery(this);

            // close all other open toolboxes
            body.trigger('toolboxcloseall');

            if (xhr)
            {
                xhr.abort();
            }

            if (toolbox.data("url")) {
                var url = new UrlBuilder( toolbox.data("url") ).add( { ajax: 1 } ).getUrl();
                xhr = jQuery.ajax(
                {
                    url: url,
                    type: 'get',
                    success: function( data )
                    {
                        toolbox.trigger('toolboxbuild', data);
                    }
                });
            }
            else
            {
                toolbox.trigger('toolboxbuild');
            }

            return;
        });

        toolboxes.bind('toolboxbuild', function(e, data)
        {
            var toolbox = jQuery( this );
            var menu = toolbox.data('toolbox-menu');
            var items_container = toolbox.find('.toolbox-items ul');

            toolbox.attr('data-toolbox-open', true);
            menu.appendTo( body );
            toolbox.trigger('toolboxposition');

            overlay.show();

            menu.show();

            if( data !== undefined)
            {
                items_container.html(data);
            }
            items_container.trigger('contentloaded');
        });

        toolboxes.bind('toolboxclose', function()
        {
            var toolbox   = jQuery(this);

            var menu = toolbox.data('toolbox-menu');

            menu.hide().appendTo( toolbox );

            overlay.hide();

            toolbox.removeAttr('data-toolbox-open');

            return;
        });

        toolboxes.bind('toolboxtoggle', function()
        {
            var toolbox   = jQuery(this);
            var event = (toolbox.attr('data-toolbox-open')) ? 'toolboxclose' : 'toolboxopen';
            toolbox.trigger( event );
        });

        toolboxes.bind('toolboxposition', function()
        {
            var toolbox   = jQuery(this);
            if (!toolbox.attr('data-toolbox-open'))
            {
                return;
            }

            var menu = toolbox.data('toolbox-menu');

            var trigger        = toolbox.find('.trigger');
            var triggerOffset  = trigger.offset();

            var triggerCenterX = triggerOffset.left + (trigger.outerWidth() / 2);

            var menuWidth   = menu.outerWidth();
            var openToRight = ((jQuery(document).width() - triggerCenterX - menuWidth - 50) > 0);

            var beak = menu.children('i').first();

            if (openToRight)
            {
                menu.css({
                    left:  triggerCenterX - 23,
                    top :  triggerOffset.top  + trigger.outerHeight()
                });
                beak.css({
                    left : 18
                });
            }
            else
            {
                menu.css({
                    left:  triggerCenterX - menuWidth + 20,
                    top :  triggerOffset.top  + trigger.outerHeight()
                });
                beak.css({
                    left : menuWidth - 24
                });
            }

        });

        toolboxes.find('.trigger').click(function()
        {
            jQuery(this).closest('.toolbox').trigger('toolboxtoggle');
        });


        toolboxes.find('.toolbox-items ul').on('contentloaded', function(e){
            var container = jQuery(e.target);
            var toolbox = container.data("toolbox");
            var trigger = toolbox.find('.trigger');

            var items = container.find('li');
            var buttons = items.find('.button');

            buttons.click(function()
            {
                toolbox.trigger('toolboxclose');
            });

            // forward loader events from item buttons to main toolbox trigger
            buttons.on('loadingstart', function()
            {
                trigger.trigger('loadingstart');
            });

            buttons.on('loadingend', function()
            {
                trigger.trigger('loadingend');
            });
        });


        toolboxes.each(function()
        {
            var toolbox = jQuery(this);

            var menu = toolbox.find('menu').first();
            toolbox.data('toolbox-menu', menu);

            var items_container = toolbox.find('.toolbox-items ul');
            items_container.data('toolbox', toolbox);

            toolbox.addClass('initialized');
        });

    });

    jQuery(window).bind('resize', function()
    {
        jQuery('.toolbox[data-toolbox-open]').trigger('toolboxposition');
    });

    body.bind('toolboxcloseall', function()
    {
        body.find('.toolbox[data-toolbox-open]').trigger('toolboxclose');
    });


    // attach toolboxinit to all loaded content
    body.on('contentloaded', function(e)
    {
        jQuery(e.target).trigger('toolboxinit');
    });

});
