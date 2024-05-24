jQuery(function()
{
    var body = jQuery('body');

    body.on('contentdone', function( e )
    {
        jQuery(e.target).find(".dialog").addBack('.dialog').addClass('initialized');
    });

});


