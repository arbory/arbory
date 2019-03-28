jQuery(document).ready(function()
{
    var filterOpenButton = $(".button.filter.trigger"),
        filterCloseButton = $(".button.close"),
        filterWindow = $(".form-filter"),
        contentWindow = $("#main section");

    function openCloseFilter() {
        filterWindow.toggleClass("show");
        contentWindow.toggleClass("show-filter");
    }

    filterOpenButton.add(filterCloseButton).on("click", function () {
        openCloseFilter();
    } );
});