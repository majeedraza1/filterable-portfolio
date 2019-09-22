(function () {
    let items = document.querySelector('.portfolio-items'),
        buttons = document.querySelectorAll('.filterable-portfolio__terms button');

    if (!items || !buttons.length) {
        return;
    }

    if (typeof Isotope === "undefined") {
        return;
    }

    let portfolio = new Isotope(items, {
        itemSelector: '.portfolio-item',
        layoutMode: 'fitRows'
    });

    // layout Isotope after each image loads
    imagesLoaded(items).on('progress', function () {
        portfolio.layout();
    });

    buttons.forEach((button) => {
        button.addEventListener('click', event => {
            let active = document.querySelector('.filterable-portfolio__terms .is-active');
            active.classList.remove('is-active');
            let el = event.target;
            el.classList.add('is-active');
            let data = el.getAttribute('data-filter');
            portfolio.arrange({
                filter: data
            });
        });
    });
})();
