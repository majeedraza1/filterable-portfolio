<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

/**
 * @var \WP_Term[] $categories List of WP_Term object.
 * @var \WP_Post[] $portfolios List of WP_Post object.
 */

$option          = Filterable_Portfolio_Helper::get_options();
$all_button_text = esc_html( $option['all_categories_text'] );

$theme       = in_array( $option['portfolio_theme'], array( 'one', 'two' ) ) ? $option['portfolio_theme'] : 'one';
$items_class = 'grids portfolio-items';
$items_class .= ' fp-theme-' . $theme;
?>
<div id="filterable-portfolio" class="filterable-portfolio">
	<?php if ( $categories && count( $categories ) > 1 ) { ?>
        <div class="filterable-portfolio__terms is-justify-end">
            <button class="button is-active" data-filter="*"><?php echo $all_button_text; ?></button>
			<?php foreach ( $categories as $category ) { ?>
                <button class="button" data-filter=".<?php echo esc_attr( $category->slug ); ?>">
					<?php echo esc_html( $category->name ); ?>
                </button>
			<?php } ?>
        </div>
	<?php } ?>
    <div id="portfolio-items" class="<?php echo $items_class; ?>">
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
</div>
