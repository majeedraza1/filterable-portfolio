(function () {
    "use strict";
    var items, buttons, portfolio, active, data, i;

    items = document.querySelector('.portfolio-items');
    buttons = document.querySelectorAll('.filter-options button');

    if (!items || !buttons) {
        return;
    }

    if (typeof Isotope === "undefined") {
        return;
    }

    portfolio = new Isotope(items, {
        itemSelector: '.portfolio-item',
        layoutMode: 'fitRows'
    });

    // layout Isotope after each image loads
    imagesLoaded(items).on('progress', function () {
        portfolio.layout();
    });

    for (i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', function () {
            active = document.querySelector('.filter-options .active');
            active.classList.remove('active');
            this.classList.add('active');
            data = this.getAttribute('data-filter');
            portfolio.arrange({
                filter: data
            });
        });
    }
})();

(function () {
    'use strict';

    if (typeof window.shuffle === "undefined") {
        return;
    }

    var Shuffle = window.shuffle;

    var FilterablePortfolio = function (element) {
        this.element = element;


        this.shuffle = new Shuffle(element, {
            itemSelector: '.portfolio-item'
        });

        this._activeFilters = [];

        this.addFilterButtons();

        this.mode = 'exclusive';
    };

    FilterablePortfolio.prototype.toArray = function (arrayLike) {
        return Array.prototype.slice.call(arrayLike);
    };

    FilterablePortfolio.prototype.toggleMode = function () {
        if (this.mode === 'additive') {
            this.mode = 'exclusive';
        } else {
            this.mode = 'additive';
        }
    };

    FilterablePortfolio.prototype.addFilterButtons = function () {
        var options = document.querySelector('.filter-options');

        if (!options) {
            return;
        }

        var filterButtons = this.toArray(
            options.children
        );

        filterButtons.forEach(function (button) {
            button.addEventListener('click', this._handleFilterClick.bind(this), false);
        }, this);
    };

    FilterablePortfolio.prototype._handleFilterClick = function (evt) {
        var btn = evt.currentTarget;
        var isActive = btn.classList.contains('active');
        var btnGroup = btn.getAttribute('data-group');

        // You don't need _both_ of these modes. This is only for the demo.

        // For this custom 'additive' mode in the demo, clicking on filter buttons
        // doesn't remove any other filters.
        if (this.mode === 'additive') {
            // If this button is already active, remove it from the list of filters.
            if (isActive) {
                this._activeFilters.splice(this._activeFilters.indexOf(btnGroup));
            } else {
                this._activeFilters.push(btnGroup);
            }

            btn.classList.toggle('active');

            // Filter elements
            this.shuffle.filter(this._activeFilters);

            // 'exclusive' mode lets only one filter button be active at a time.
        } else {
            this._removeActiveClassFromChildren(btn.parentNode);

            var filterGroup;
            if (isActive) {
                btn.classList.remove('active');
                filterGroup = Shuffle.ALL_ITEMS;
            } else {
                btn.classList.add('active');
                filterGroup = btnGroup;
            }

            this.shuffle.filter(filterGroup);
        }
    };

    FilterablePortfolio.prototype._removeActiveClassFromChildren = function (parent) {
        var children = parent.children;
        for (var i = children.length - 1; i >= 0; i--) {
            children[i].classList.remove('active');
        }
    };

    var item = document.getElementById('portfolio-items');
    imagesLoaded(item).on('progress', function () {
        new FilterablePortfolio(item);
    });

})();
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