import Isotope from 'isotope-layout';
import imagesLoaded from 'imagesloaded';

let items = document.querySelector('.portfolio-items'),
	buttons = document.querySelectorAll('.filterable-portfolio__terms button');

if (items || buttons.length) {
	let portfolio = new Isotope(items, {
		itemSelector: '.portfolio-item',
		layoutMode: 'fitRows'
	});

	// layout Isotope after each image loads
	imagesLoaded(items).on('progress', () => {
		portfolio.layout();
	});

	buttons.forEach((button) => {
		button.addEventListener('click', event => {
			document.querySelector('.filterable-portfolio__terms .is-active').classList.remove('is-active');
			event.target.classList.add('is-active');
			portfolio.arrange({
				filter: event.target.getAttribute('data-filter')
			});
		});
	});
}

