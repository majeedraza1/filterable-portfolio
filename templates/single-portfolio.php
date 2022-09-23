<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

$options        = Filterable_Portfolio_Helper::get_options();
$id             = get_the_ID();
$project_images = get_post_meta( $id, '_project_images', true );
$project_images = array_filter( explode( ',', rtrim( $project_images, ',' ) ) );
?>
<div class="single-portfolio-content">
	<?php
	if ( count( $project_images ) > 0 ) {
		load_template( FILTERABLE_PORTFOLIO_TEMPLATES . '/portfolio-slider.php' );
	} elseif ( has_post_thumbnail() ) {
		the_post_thumbnail();
	}
	?>
	<div class="grids">
		<div class="project-content grid s8">
			<h4><?php echo esc_attr( $options['project_description_text'] ); ?></h4>
			<?php do_action( 'filterable_portfolio_before_single_portfolio_content' ); ?>
			<?php echo do_shortcode( get_the_content() ); ?>
			<?php do_action( 'filterable_portfolio_after_single_portfolio_content' ); ?>
		</div>
		<div class="project-meta grid s4">
			<?php do_action( 'filterable_portfolio_before_single_portfolio_meta' ); ?>
			<?php
			load_template( FILTERABLE_PORTFOLIO_TEMPLATES . '/portfolio-meta.php' );
			?>
			<?php do_action( 'filterable_portfolio_after_single_portfolio_meta' ); ?>
		</div>
	</div>
	<?php
	if ( isset( $options['show_related_projects'] ) && $options['show_related_projects'] ) {
		load_template( FILTERABLE_PORTFOLIO_TEMPLATES . '/related-portfolio.php' );
	}
	?>
</div>
