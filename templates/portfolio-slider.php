<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! Filterable_Portfolio_Helper::has_portfolio_images() ) {
	return;
}

$project_images = Filterable_Portfolio_Helper::get_portfolio_images_ids();
$option         = Filterable_Portfolio_Helper::get_options();
$image_size     = $option['project_image_size'];
?>
<div class="fp-tns-slider-outer dots-circle dots-right">
	<div
		class="fp-tns-slider"
		data-slide-by="1"
		data-gutter="10"
		data-loop="true"
		data-autoplay="true"
		data-lazyload="true"
		data-autoplay-timeout="5000"
		data-autoplay-hover-pause="true"
		data-speed="500"
		data-dots="true"
		data-arrows="true"
		data-mobile="1"
		data-tablet="1"
		data-desktop="1"
		data-wide-screen="1"
		data-full-hd="1"
		data-high-screen="1"
	>
		<?php foreach ( $project_images as $image_id ): ?>
			<div class="item">
				<div class="img img-1">
					<?php echo wp_get_attachment_image( $image_id, $image_size ); ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="fp-tns-slider-controls">
            <span class="prev" data-controls="prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32">
                    <path
						d="M12.3 17.71l6.486 6.486c0.39 0.39 1.024 0.39 1.414 0s0.39-1.024 0-1.414l-5.782-5.782 5.782-5.782c0.39-0.39 0.39-1.024 0-1.414s-1.024-0.39-1.414 0l-6.486 6.486c-0.196 0.196-0.292 0.452-0.292 0.71s0.096 0.514 0.292 0.71z"></path>
                </svg>
            </span>
		<span class="next" data-controls="next">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32">
                    <path
						d="M13.8 24.196c0.39 0.39 1.024 0.39 1.414 0l6.486-6.486c0.196-0.196 0.294-0.454 0.292-0.71 0-0.258-0.096-0.514-0.292-0.71l-6.486-6.486c-0.39-0.39-1.024-0.39-1.414 0s-0.39 1.024 0 1.414l5.782 5.782-5.782 5.782c-0.39 0.39-0.39 1.024 0 1.414z"></path>
                </svg>
            </span>
	</div>
</div>
