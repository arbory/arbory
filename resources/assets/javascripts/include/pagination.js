/* global UrlBuilder */

jQuery(function()
{
    var body = jQuery('body');
    body.on('contentloaded', function(e)
    {
        jQuery(e.target).find('.pagination select[name="page"]').on('change', function()
        {
            var val = jQuery(this).val();
            if (val)
            {
                var url = new UrlBuilder().add({page: val}).getUrl();
                window.location.href = url;
            }
        });
    });

});
