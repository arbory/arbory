jQuery(function()
{

    jQuery('body').on('loadingstart', '.button', function(e)
    {
        var button = jQuery(e.target);

        if (button.hasClass('loading'))
        {
            return;
        }

        button.addClass('loading');

        button.data('disabled-before-loading', button.prop('disabled') );

        button.prop('disabled', true);

        var loader = jQuery('<i />').addClass('loader fa fa-spin fa-spinner');
        button.append( loader );
    });


    jQuery('body').on('loadingend', '.button', function(e)
    {
        var button = jQuery(e.target);
        button.find('.loader').remove();

        var disabled_before_loading = button.data('disabled-before-loading') ;

        if (typeof disabled_before_loading !== 'undefined')
        {
            if (!disabled_before_loading)
            {
                button.prop('disabled', false);
            }
        }

        button.removeClass('loading');

    });



});
