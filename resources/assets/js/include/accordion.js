jQuery(document).ready(function()
{
    $(".accordion .heading").on( "click", function(event)
    {
        event.preventDefault();
        
        var accordion = $( event.target ).closest(".accordion"),
            accordionContent = $( accordion ).children(".body"),
            accordionToggle = $( accordion ).find(".button i");

        accordionContent.slideToggle( 150 );
        accordionToggle.toggleClass("fa-minus");
        accordionToggle.toggleClass("fa-plus");
    });
});