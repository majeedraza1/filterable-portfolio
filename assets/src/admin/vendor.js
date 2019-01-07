(function ($) {
    'use strict';

    // Initializing WP Color Picker
    $('.color-picker').each(function () {
        $(this).wpColorPicker({
            palettes: [
                '#2196F3', // Blue
                '#009688', // Teal
                '#4CAF50', // Green
                '#F44336', // Red
                '#FFEB3B', // Yellow
                '#00D1B2', // Turquoise
                '#000000', // Blank
                '#ffffff' // White
            ]
        });
    });

    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        showAnim: "slideDown"
    });
})(jQuery);