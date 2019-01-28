<?php
/**
 * Template part for displaying portfolio content
 *
 * @package Filterable_Portfolio
 * @since 1.3.3
 */

if ( ! defined( 'WPINC' ) ) {
	die; // If this file is called directly, abort.
}

$option      = get_option( 'filterable_portfolio' );
$button_text = esc_html__( 'Details', 'filterable-portfolio' );
if ( ! empty( $options['details_button_text'] ) ) {
	$button_text = esc_html( $options['details_button_text'] );
}
$item_class   = array( 'filterable-portfolio-item', 'portfolio-item', 'grid' );
$item_class[] = ! empty( $options['columns_phone'] ) ? esc_attr( $options['columns_phone'] ) : 'xs12';
$item_class[] = ! empty( $options['columns_tablet'] ) ? esc_attr( $options['columns_tablet'] ) : 's6';
$item_class[] = ! empty( $options['columns_desktop'] ) ? esc_attr( $options['columns_desktop'] ) : 'm4';
$item_class[] = ! empty( $options['columns'] ) ? esc_attr( $options['columns'] ) : 'l4';

$categories_slug = array();
$categories      = get_the_terms( get_the_ID(), 'portfolio_cat' );
if ( $categories && ! is_wp_error( $categories ) ) {
	$categories_slug = wp_list_pluck( $categories, 'slug' );
	$item_class      = array_merge( $item_class, $categories_slug );
}

$image_size        = ! empty( $option['image_size'] ) ? esc_attr( $option['image_size'] ) : 'medium_large';
$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
?>
<div id="id-<?php echo get_the_ID(); ?>" class="<?php echo implode( ' ', $item_class ) ?>"
     data-groups='<?php echo wp_json_encode( $categories_slug ); ?>'>
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
