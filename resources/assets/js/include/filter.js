jQuery(document).ready(function()
{
    let filterButton = $('.button.filter.trigger'),
        filterWindow = $('.form-filter'),
        contentWindow = $('#main section');

    filterButton.on( 'click', function ()
    {
        contentWindow.toggleClass( 'show-filter' );
    } );
});