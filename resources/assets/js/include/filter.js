jQuery(document).ready(function()
{
    let filterButton = $('.button.filter.trigger'),
        filterWindow = $('.form-filter'),
        contentWindow = $('#main section');

    filterButton.on( 'click', function ()
    {
        filterWindow.toggleClass( 'show' );
        contentWindow.toggleClass( 'show-filter' );
    } );
});