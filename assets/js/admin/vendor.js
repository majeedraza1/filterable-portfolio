(function ($) {
    'use strict';

    $(".colorpicker").wpColorPicker();
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        showAnim: "slideDown"
    });
})(jQuery);