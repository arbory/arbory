jQuery(document).ready(function() {
    var filterOpenButton = $('.js-filter-trigger');
    var filterWindow = $('.form-filter');
    var contentWindow = $('#main > .content');
    var searchInputName = 'search';

    filterWindow.submit( function(eventObj) {
        $('<input />').attr('type', 'hidden')
            .attr('name', searchInputName)
            .attr('value', $(`#${searchInputName}`).val())
            .appendTo(filterWindow);
        return true;
    });

    function openCloseFilter() {
        filterWindow.toggleClass('show');
        contentWindow.toggleClass('show-filter');
    }

    filterOpenButton.on('click', function() {
        openCloseFilter();
    });
});