(function () {
	"use strict";
	var items, buttons, portfolio, active, data;

	items 		= document.querySelector('.portfolio-items');
	buttons 	= document.querySelectorAll('.filter-options button');

	if ( ! items || ! buttons ) {
		return;
	}

	portfolio = new Isotope( items, {
		itemSelector: '.portfolio-item',
		layoutMode: 'fitRows'
	});

	Array.prototype.forEach.call(buttons, function(el, i){
		el.addEventListener('click', function() {
			active = document.querySelector('.filter-options .active');
			active.classList.remove('active');
			el.classList.add('active');
			data = el.getAttribute('data-filter');
			portfolio.arrange({
				filter: data
			});
		});
	});
})();
