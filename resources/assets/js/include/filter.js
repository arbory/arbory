jQuery(document).ready(function()
{
    var filterButton = $(".button.filter.trigger"),
        filterCloseButton = $(".button.close"),
        filterWindow = $(".form-filter"),
        contentWindow = $("#main section"),
        filterField = $(".accordion__heading");

    filterButton.on("click", function()
    {
        filterWindow.toggleClass("show");
        contentWindow.toggleClass("show-filter");
    } );

    filterCloseButton.on("click", function()
    {
        filterWindow.removeClass("show");
        contentWindow.removeClass("show-filter");
    } );

    filterField.on( "click", function(event)
    {
        var accordion = $( event.target ).closest(".accordion"),
            accordionContent = $( accordion ).children(".accordion__body"),
            accordionToggle = $( accordion ).find(".button i");

        accordionContent.slideToggle( 150 );
        accordionToggle.toggleClass("fa-minus");
        accordionToggle.toggleClass("fa-plus");
    });
});