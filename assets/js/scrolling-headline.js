(function($) {
    'use strict';

    var ScrollingHeadline = function($scope) {
        var $wrapper = $scope.find('.scrolling-headline-wrapper');
        var $content = $wrapper.find('span').first();
        var speed = $wrapper.data('speed');
        var offset = $wrapper.data('offset');
        var continuous = $wrapper.data('continuous');
        
        // Store the text content for the :after pseudo-element
        $content.attr('data-text', $content.text());
        
        // Calculate animation duration based on content width and speed
        var duration = 20 - speed/2.5; // Convert speed (1-50) to duration (19-0)

        function startAnimation() {
            // Remove any existing animation
            $content.css('animation', 'none');
            $wrapper.removeClass('animating');
            
            // Force reflow
            void $content[0].offsetWidth;
            
            // Set initial position based on offset
            if (window.elementorFrontend && window.elementorFrontend.isEditMode()) {
                $wrapper.css('--progress', offset + '%');
                return; // Don't start animation in editor
            }

            // Add animation
            $wrapper.addClass('animating');
            if (continuous === 'yes') {
                $wrapper.addClass('continuous');
                $content.css({
                    'animation': `scroll-left-continuous ${duration}s linear infinite`
                });
            } else {
                $wrapper.removeClass('continuous');
                $content.css({
                    'animation': `scroll-left-single ${duration}s linear infinite`
                });
            }
        }

        startAnimation();

        // Handle Elementor editor preview
        if (window.elementorFrontend && window.elementorFrontend.isEditMode()) {
            elementor.channels.editor.on('change', function(view) {
                if (view.container.model.get('id') === $scope.data('id')) {
                    startAnimation();
                }
            });
        }
    };

    // Initialize on frontend
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/scrolling_headline.default', ScrollingHeadline);
    });
})(jQuery); 