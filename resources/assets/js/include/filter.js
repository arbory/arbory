jQuery(document).ready(function()
{
    var filterOpenButton = $(".button.filter.trigger"),
        filterCloseButton = $(".button.filter-container.close"),
        filterWindow = $(".form-filter"),
        contentWindow = $("#main > .content");

    function openCloseFilter() {
        filterWindow.toggleClass("show");
        contentWindow.toggleClass("show-filter");
    }

    filterOpenButton.add(filterCloseButton).on("click", function () {
        openCloseFilter();
    } );
});