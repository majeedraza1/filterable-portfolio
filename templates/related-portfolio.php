<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

$portfolios = Filterable_Portfolio_Helper::get_related_portfolios();

if ( count( $portfolios ) < 1 ) {
	return;
}

$option      = Filterable_Portfolio_Helper::get_options();
$theme       = in_array( $option['portfolio_theme'], array( 'one', 'two' ) ) ? $option['portfolio_theme'] : 'one';
$items_class = 'grids portfolio-items related-projects';
$items_class .= ' fp-theme-' . $theme;
$title       = esc_html( $option['related_projects_text'] );
?>
<h4 class="related-projects-title"><?php echo $title; ?></h4>
<div class="<?php echo $items_class; ?>">
	<?php
	$temp_post = $GLOBALS['post'];
	foreach ( $portfolios as $portfolio ) {
		setup_postdata( $portfolio );
		$GLOBALS['post'] = $portfolio;
		do_action( 'filterable_portfolio_loop_post', $portfolio );
	}
	wp_reset_postdata();
	$GLOBALS['post'] = $temp_post;
	?>
</div>
