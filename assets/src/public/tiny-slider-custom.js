(function () {
    'use strict';

    if (typeof tns === "undefined") {
        return;
    }

    var sliders,
        i,
        slider,
        sliderOuter,
        controls,
        showDots,
        showArrows,
        autoplay,
        autoplayHoverPause,
        autoplayTimeout,
        speed,
        gutter,
        loop,
        lazyload,
        slideBy,
        mobile,
        tablet,
        desktop,
        wideScreen,
        fullHD,
        highScreen;

    sliders = document.querySelectorAll('.fp-tns-slider');
    for (i = 0; i < sliders.length; i++) {
        slider = sliders[i];

        sliderOuter = slider.parentNode;
        controls = sliderOuter.querySelector('.fp-tns-slider-controls');

        mobile = parseInt(slider.getAttribute('data-mobile'));
        tablet = parseInt(slider.getAttribute('data-tablet'));
        desktop = parseInt(slider.getAttribute('data-desktop'));
        wideScreen = parseInt(slider.getAttribute('data-wide-screen'));
        fullHD = parseInt(slider.getAttribute('data-full-hd'));
        highScreen = parseInt(slider.getAttribute('data-high-screen'));

        showDots = slider.getAttribute('data-dots') === 'true';
        showArrows = slider.getAttribute('data-arrows') === 'true';

        autoplay = slider.getAttribute('data-autoplay') === 'true';
        autoplayHoverPause = slider.getAttribute('data-autoplay-hover-pause') === 'true';
        autoplayTimeout = parseInt(slider.getAttribute('data-autoplay-timeout'));
        speed = parseInt(slider.getAttribute('data-speed'));

        gutter = parseInt(slider.getAttribute('data-gutter'));
        loop = slider.getAttribute('data-loop') === 'true';
        lazyload = slider.getAttribute('data-lazyload') === 'true';

        slideBy = slider.getAttribute('data-slide-by');
        slideBy = (slideBy === 'page') ? 'page' : parseInt(slideBy);

        tns({
            container: slider,
            slideBy: slideBy,
            loop: loop,
            lazyload: lazyload,
            autoplay: autoplay,
            autoplayTimeout: autoplayTimeout,
            autoplayHoverPause: autoplayHoverPause,
            speed: speed,
            gutter: gutter,
            nav: showDots,
            controls: showArrows,
            controlsContainer: controls ? controls : false,
            edgePadding: 0,
            items: mobile,
            responsive: {
                600: {items: tablet},
                1000: {items: desktop},
                1200: {items: wideScreen},
                1500: {items: fullHD},
                1921: {items: highScreen}
            }
        });
    }
})();