tooltips();

function tooltips(){

    $(document).find('.entities').tooltipster({
        interactive: true,
        multiple: true,
        // content: 'Loading...',
        contentCloning: false,
        contentAsHTML: true,
        // autoClose: false,
        animation: 'none',
        delay: '100',
        speed: '0',
        functionBefore: function(origin, continueTooltip) {
             var element = $(this);

            var pieces = $(element).attr('href').split("/");
            var current_slug = pieces[pieces.length-1];

            var this_content = $('#entity_' + current_slug).html();

            console.log(current_slug);

            continueTooltip();
            origin.tooltipster('content', this_content);
            // if (origin.data('ajax') !== 'cached') {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/entities/' + current_slug,
            //         success: function(data) {
            //             // update our tooltip content with our returned data and cache it
            //             origin.tooltipster('content', data).data('ajax', 'cached');
            //         }
            //     });
            // } 
        }
    });

}

