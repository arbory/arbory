import UrlBuilder from '../modules/UrlBuilder';

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
                window.location.href = new UrlBuilder(window.location.href).add({page: val}).getUrl();
            }
        });
    });
});
