tooltips();

function tooltips(){
// console.log('ran tooltips')
    $(document).find('.entities').tooltipster({
        interactive: true,
        multiple: true,
        content: '',
        // contentCloning: true,
        contentAsHTML: true,
        // autoClose: false,
        animation: 'none',
        delay: '100',
        speed: '0',
        functionBefore: function(origin, continueTooltip) {
            
            var element = $(this);

            var pieces = $(element).attr('href').split("/");
            var current_slug = pieces[pieces.length-1];

            var this_content = $(document).find('#entity_' + current_slug).html();

            continueTooltip();
            origin.tooltipster('content', this_content);

        }
    });

}

