(function () {
    'use strict';

    var items, buttons, portfolio, active, data, i;

    items = document.querySelector('.portfolio-items');
    buttons = document.querySelectorAll('.filter-options button');

    if (!items || !buttons) {
        return;
    }

    if (typeof window.Shuffle === "undefined") {
        return;
    }

    var shuffleInstance = new Shuffle(items, {
        itemSelector: '.portfolio-item'
    });

    imagesLoaded(items).on('progress', function () {
        shuffleInstance.layout();
    });

    for (i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', function () {
            active = document.querySelector('.filter-options .active');
            active.classList.remove('active');
            this.classList.add('active');
            data = this.getAttribute('data-group');
            shuffleInstance.filter(data);
        });
    }
})();
