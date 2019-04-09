jQuery(document).ready(function() {
    var filterOpenButton = $('.js-filter-trigger');
    var filterWindow = $('.form-filter');
    var contentWindow = $('#main > .content');

    function openCloseFilter() {
        filterWindow.toggleClass('show');
        contentWindow.toggleClass('show-filter');
    }

    filterOpenButton.on('click', function() {
        openCloseFilter();
    });
});