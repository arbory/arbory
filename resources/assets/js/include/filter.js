jQuery(document).ready(function() {
    var filterOpenButton = $('.js-filter-trigger');
    var filterWindow = $('.form-filter');
    var contentWindow = $('#main > .content');
    var searchInputName = 'search';

    filterWindow.submit( function(eventObj) {
        addSearchToFilter();
        return true;
    });

    function openCloseFilter() {
        filterWindow.toggleClass('show');
        contentWindow.toggleClass('show-filter');
    }

    filterOpenButton.on('click', function() {
        openCloseFilter();
    });

    function addSearchToFilter() {
        $('<input />').attr('type', 'hidden')
            .attr('name', searchInputName)
            .attr('value', $(`#${searchInputName}`).val())
            .appendTo(filterWindow);
    }

    $('body').on('contentdone', '.js-save-filter-dialog', initSaveFilterDialog);

    function initSaveFilterDialog(event) {
        let dialog = $(event.target);
        let form = dialog.find('form');

        form.on('submit', function() {
            addSearchToFilter();
            dialog.find('[name="filter"]').val(filterWindow.serialize());
            return true;
        });
    }
});