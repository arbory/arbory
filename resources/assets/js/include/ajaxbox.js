import 'magnific-popup';
import 'jquery-ui/ui/widgets/draggable';

import UrlBuilder from '../modules/UrlBuilder';

jQuery(document).ready( function()
{
    var ajaxbox_link_selector = 'a.ajaxbox';

    var xhr;

    var body = jQuery('body');

    var cached_modals = {};

    var open_ajax_box = function( params )
    {
        var magnific_popup_params =
        {
            showCloseBtn     : false,
            modal            : params.modal,
            callbacks        : {
                open         : function()
                {
                    this.contentContainer.trigger('ajaxboxaftershow', [this, params]);
                },
                beforeClose  : function()
                {

                    this.contentContainer.trigger('ajaxboxbeforeclose');
                }
            }
        };

        if (params.type === 'image')
        {
            magnific_popup_params.items = {src: params.url};
            magnific_popup_params.type = "image";
        }
        else
        {
            magnific_popup_params.items = {src: params.content, type: "inline"};
        }

        jQuery.magnificPopup.open(magnific_popup_params);
        return;
    };

    var close_ajax_box = function()
    {
        jQuery.magnificPopup.close();
    };

    body.on('ajaxboxaftershow', function(e, ajaxbox, params)
    {
        ajaxbox.contentContainer.addClass('ajaxbox-inner');
        // enable drag with header
        if( ajaxbox.wrap.draggable !== undefined )
        {
            ajaxbox.wrap.draggable({ handle: ajaxbox.contentContainer.find('section header').first() });
        }

        // insert close button if header exists and box is not modal
        if (!params.modal)
        {
            var close_container = ajaxbox.contentContainer.first();

            if (params.type !== 'image')
            {
                close_container =  ajaxbox.contentContainer.find('section header').first();
            }

            if (close_container.length > 0)
            {
                var close_icon   = jQuery('<i />').addClass('fa fa-times');
                var close_button = jQuery('<button />').attr('type', 'button').addClass('button secondary close only-icon').append(close_icon);
                close_button.on('click', function()
                {
                    close_ajax_box();
                });
                close_container.append( close_button );
            }
        }

        // focus on cancel button in footer if found
        var cancel_button = ajaxbox.contentContainer.find('section footer .button[data-type="cancel"]').first();
        if (cancel_button.length > 0)
        {
            cancel_button.bind('click', function()
            {
                body.trigger('ajaxboxclose');
                return false;
            });
            cancel_button.focus();
        }

        ajaxbox.contentContainer.trigger('contentloaded');
        ajaxbox.contentContainer.trigger('ajaxboxdone', params);
    });

    body.on('ajaxboxinit', function(e)
    {
        var target = jQuery(e.target);

        // init links
        var links = (target.is(ajaxbox_link_selector)) ? target : target.find(ajaxbox_link_selector);

        links.on('click', function()
        {
            var link = jQuery(this);
            var params =
            {
                url     : new UrlBuilder(link.attr('href')).add( { ajax: 1 } ).getUrl(),
                modal   : link.is('[data-modal]'),
                trigger : link,
                cache   : link.is('[data-cache]'),
            };
            if (link.attr('rel') === 'image')
            {
                params.type = 'image';
            }

            link.trigger('ajaxboxopen', params);

            return false;
        });

    });

    body.on('ajaxboxopen', function(e, params)
    {
        if('cache' in params && params.cache === true) {
            var cached = params.url in cached_modals;

            if(cached) {
                params.content = cached_modals[params.url];
            }
        }

        // params expects either url or content
        if ('content' in params)
        {
            open_ajax_box( params );
        }
        else if ('url' in params)
        {
            if ('trigger' in params)
            {
                params.trigger.trigger('loadingstart');
            }

            if (xhr)
            {
                xhr.abort();
            }

            xhr = jQuery.ajax(
            {
                url:   params.url,
                type: 'get',
                success: function( data )
                {
                    params.content = data;
                    open_ajax_box( params );

                    if(params.cache) {
                        cached_modals[params.url] = data;
                    }
                }
            });
        }
    });

    body.on('ajaxboxdone', function(e, params)
    {
        if (params && ('trigger' in params))
        {
            params.trigger.trigger('loadingend');
        }
        jQuery(e.target).find('.dialog').trigger('contentdone');
    });

    body.on('ajaxboxclose', function()
    {
        close_ajax_box();
    });


    // attach ajaxboxinit to all loaded content
    body.on('contentloaded', function(e)
    {
        // reinit ajaxbox for all content that gets replaced via ajax
        jQuery(e.target).trigger('ajaxboxinit');
    });

});

