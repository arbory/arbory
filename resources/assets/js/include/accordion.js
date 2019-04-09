jQuery(document).ready(function() {
    $('.js-accordion-trigger').on('click', function(event) {
        event.preventDefault();

        const animationSpeed = 150;
        
        var accordion = $(event.target).closest('.accordion');
        var accordionContent = $(accordion).children('.body');
        var accordionToggle = $(accordion).find('.fa');

        accordionContent.slideToggle(animationSpeed);
        accordionToggle.toggleClass('fa-minus');
        accordionToggle.toggleClass('fa-plus');
    });
});