<?php

if ( ! defined( 'WPINC' ) ) {
	die; // If this file is called directly, abort.
}

$option          = get_option( 'filterable_portfolio' );
$all_button_text = esc_html__( 'All', 'filterable-portfolio' );
if ( ! empty( $option['all_categories_text'] ) ) {
	$all_button_text = esc_html( $option['all_categories_text'] );
}

$theme       = ! empty( $option['portfolio_theme'] ) ? $option['portfolio_theme'] : '';
$theme       = in_array( $theme, array( 'one', 'two' ) ) ? $theme : 'one';
$items_class = 'grids portfolio-items';
$items_class .= ' fp-theme-' . $theme;
?>
<div id="filterable-portfolio" class="filterable-portfolio">
	<?php if ( $categories ) { ?>
        <div id="filter" class="portfolio-terms">
            <div class="filter-options">
                <button class="button active" data-group="all" data-filter="*"><?php echo $all_button_text; ?></button>
				<?php foreach ( $categories as $category ) {
					$slug = esc_attr( $category->slug );
					?>
                    <button class="button" data-group="<?php echo $slug; ?>"
                            data-filter=".<?php echo $slug; ?>"><?php echo esc_html( $category->name ); ?></button>
				<?php } ?>
            </div>
        </div>
	<?php } ?>
    <div id="portfolio-items" class="<?php echo $items_class; ?>">
		<?php
		$temp_post = $GLOBALS['post'];
		foreach ( $portfolios as $portfolio ) {
			setup_postdata( $portfolio );
			$GLOBALS['post'] = $portfolio;
			do_action( 'filterable_portfolio_loop_post' );
		}
		wp_reset_postdata();
		$GLOBALS['post'] = $temp_post;
		?>
    </div>
</div>
