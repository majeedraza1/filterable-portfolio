<?php
/**
 * Template part for displaying portfolio content
 *
 * @package Filterable_Portfolio
 * @since 1.3.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

$options     = Filterable_Portfolio_Helper::get_options();
$button_text = esc_html( $options['details_button_text'] );
$attributes  = $GLOBALS['filterable_portfolio_attributes'] ?? [];
$classes     = $attributes['responsive_classes'] ?? [];

$item_class   = array( 'filterable-portfolio-item', 'portfolio-item', 'grid' );
$item_class[] = esc_attr( $classes['columns_phone'] ?? $options['columns_phone'] );
$item_class[] = esc_attr( $classes['columns_tablet'] ?? $options['columns_tablet'] );
$item_class[] = esc_attr( $classes['columns_desktop'] ?? $options['columns_desktop'] );
$item_class[] = esc_attr( $classes['columns'] ?? $options['columns'] );

$categories_slug = array();
if ( isset( $attributes['filter_by'] ) && $attributes['filter_by'] === 'skills' ) {
	$categories = get_the_terms( get_the_ID(), 'portfolio_skill' );
} else {
	$categories = get_the_terms( get_the_ID(), 'portfolio_cat' );
}
if ( $categories && ! is_wp_error( $categories ) ) {
	$categories_slug = wp_list_pluck( $categories, 'slug' );
	$item_class      = array_merge( $item_class, $categories_slug );
}

$image_size        = esc_attr( $options['image_size'] );
$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
?>
<div id="id-<?php echo get_the_ID(); ?>" class="<?php echo implode( ' ', $item_class ) ?>">
	<figure class="filterable-portfolio-item__content">
		<a href="<?php echo esc_url( get_the_permalink() ); ?>" rel="bookmark" class="filterable-portfolio-item__media">
			<?php echo wp_get_attachment_image( $post_thumbnail_id, $image_size ) ?>
		</a>
		<figcaption class="filterable-portfolio-item__supporting-text">
			<h4 class="filterable-portfolio-item__title"><?php echo get_the_title(); ?></h4>
			<a href="<?php echo esc_url( get_the_permalink() ); ?>" rel="bookmark"
			   class="button filterable-portfolio-item__action"><?php echo $button_text; ?></a>
		</figcaption>
	</figure>
</div>
