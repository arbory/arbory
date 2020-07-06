jQuery(function()
{
    var body = jQuery('body');

    var container = body.children('.notifications').first();

    var icon_base_class = 'icon fa';

    var icons_by_type =
    {
        info    : 'fa-info',
        success : 'fa-check',
        error   : 'fa-times-circle'
    };


    var notifications = {};

    var close_icon   = jQuery('<i />').addClass('fa fa-times');
    var close_button = jQuery('<button type="button" />').addClass('close button only-icon').append(close_icon).attr('title', container.attr('data-close-text'));
    close_button.click(function()
    {
        var notification_id = jQuery(this).closest('.notification').attr('data-id');
        body.trigger('notificationremove', notification_id );

    });

    var get_params = function( custom_params )
    {
        var random_id;
        do
        {
            random_id = Math.random().toString(16).slice(2);
        } while (typeof notifications[random_id] !== 'undefined');

        // set defaults and then override with custom_params
        var params =
        {
            id       : random_id,
            type     : 'info',
            closable : true,
            // default closable notifications to automatic closing after a timeout;
            // default non-closable notifications to never close automatically
            duration : (('closable' in custom_params) && !custom_params.closable) ? null : 5,
            message  : '',
            html     : null,
            icon     : (('type' in custom_params) && (custom_params.type in icons_by_type)) ? icons_by_type[ custom_params.type ] : icons_by_type.info
        };

        jQuery.extend( params, custom_params );

        return params;
    };

    var get_notification_ids = function( params )
    {
        var notification_ids = [];

        if (typeof params === 'string')
        {
            // locate notification by id
            notification_ids.push( params );
        }
        else if (typeof params === 'object')
        {
            // match multiple notifications by params
            jQuery.each(notifications, function(notification_id, notification)
            {
                var notification_params = notification.data('params');

                var all_params_match = true;

                jQuery.each(params, function(param, value)
                {
                    if ((typeof notification_params[param] === 'undefined') || (notification_params[param] !== value))
                    {
                        all_params_match = false;
                        return false;
                    }
                });
                if (all_params_match)
                {
                    notification_ids.push(notification_id);
                }
            });
        }

        return notification_ids;
    };

    body.on('notificationsinit', function()
    {

        body.on('notificationadd', function(e, custom_params)
        {
            // adds or updates a notification

            var notification;
            var params = get_params( custom_params );
            var is_new = false;

            if (typeof notifications[params.id] === 'undefined')
            {
                is_new = true;

                notification = jQuery('<div />').addClass('notification').attr('data-id', params.id);

                notification.append(jQuery('<i />'));

                notification.append(jQuery('<div />').addClass('content'));

                notifications[params.id] = notification;
                notification.hide();
                notification.appendTo( container );

            }

            notification = notifications[params.id];

            notification.data('params', params);

            notification.attr('data-type', params.type);

            notification.children('i').removeClass().addClass(icon_base_class + ' ' + params.icon);

            // check whether notification already have close button added
            if (params.closable && notification.find('.close').length === 0)
            {
                notification.append( close_button.clone(true) );
            }
            else if(!params.closable)
            {
                notification.find('.close').remove();
            }

            if (typeof params.html !== 'string')
            {
                params.html = jQuery('<div />').addClass('message').text( params.message );
            }

            notification.find('.content').html( params.html );

            if (is_new)
            {
                notification.fadeIn('slow', function()
                {
                    body.trigger('notificationadded', { notification : notification });
                });
            }
            else
            {
                body.trigger('notificationupdated', { notification : notification });
            }

        });

        body.on('notificationremove', function(e, params)
       {
            // removes single or multiple notifications

            var removable_notification_ids = get_notification_ids( params );

            jQuery.each( removable_notification_ids, function( index, notification_id)
            {
                if (typeof notifications[notification_id] === 'undefined')
                {
                    return;
                }

                var notification = notifications[notification_id];

                var timer = notification.data('removal-timer');
                clearTimeout( timer );

                notification.fadeOut('fast', function()
                {
                    notification.css({ opacity: 0 }).show().slideUp( 'fast', function()
                    {
                        notification.remove();
                    });
                });

                delete( notifications[notification_id] );
            });

        });

        body.on('notificationremovedelayed', function(e, removal_params)
        {
            // sets up removal timer for a single notification
            // accepts id and duration in removal_params

            var notification_id = removal_params.id;

            if (typeof notifications[notification_id] === 'undefined')
            {
                return;
            }

            var notification = notifications[notification_id];

            notification.data('removal-timer', setTimeout( function()
            {
                body.trigger('notificationremove', notification_id);
            }, removal_params.duration * 1000));

        });

        body.on('notificationadded notificationupdated', function(e, event_params)
        {
            if (!('notification' in event_params))
            {
                return;
            }
            var notification = event_params.notification;

            var params = notification.data('params');

            var timer = notification.data('removal-timer');
            clearTimeout( timer );

            if (params.duration)
            {
                var removal_params =
                {
                    id       : params.id,
                    duration : params.duration
                };
                body.trigger('notificationremovedelayed', removal_params);

            }

        });

        body.on('notificationaddflash', '.flash', function()
        {
            // convert .flash notice to notification
            var params =
            {
                type    : jQuery(this).attr('data-type'),
                message : jQuery(this).text().trim()
            };

            var id = jQuery(this).attr('data-id');
            if (typeof id !== 'undefined')
            {
                params.id = id;
            }

            body.trigger('notificationadd', params);
            jQuery(this).remove();

        });

    });

    body.trigger('notificationsinit');

    // attach notificationaddflash to all loaded content
    body.on('contentloaded', function(e)
	{
        jQuery(e.target).find('.flash').trigger('notificationaddflash');
    });



});
