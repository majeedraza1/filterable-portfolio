import {tns} from 'tiny-slider';

let sliders = document.querySelectorAll('.fp-tns-slider');
sliders.forEach(slider => {
	let controls = slider.parentNode.querySelector('.fp-tns-slider-controls');

	tns({
		container: slider,
		loop: true,
		lazyload: true,
		autoplay: true,
		autoplayHoverPause: true,
		controlsContainer: controls ? controls : false,
	});
})
