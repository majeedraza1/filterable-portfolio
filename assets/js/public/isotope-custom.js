(function () {
    "use strict";
    var items, buttons, portfolio, active, data, i;

    items = document.querySelector('.portfolio-items');
    buttons = document.querySelectorAll('.filter-options button');

    if (!items || !buttons) {
        return;
    }

    document.addEventListener('DOMContentLoaded', function () {
        portfolio = new Isotope(items, {
            itemSelector: '.portfolio-item',
            layoutMode: 'fitRows'
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
    });
})();
