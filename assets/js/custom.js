'use strict';

var Shuffle = window.shuffle;

var FilterablePortfolio = function (element) {
	this.element = element;


	this.shuffle = new Shuffle(element, {
		itemSelector: '.portfolio-item',
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

document.addEventListener('DOMContentLoaded', function () {
	window.demo = new FilterablePortfolio(document.getElementById('portfolio-items'));
});