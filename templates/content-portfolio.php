<?php
/**
 * Template part for displaying portfolio content
 *
 * @package Filterable_Portfolio
 * @since 1.3.3
 */
$option = get_option( 'filterable_portfolio' );
?>
<div id="id-<?php echo get_the_ID(); ?>" class="portfolio-item <?php echo $rp_grid; ?>">
    <figure>
        <a href="<?php echo esc_url( get_the_permalink() ); ?>" rel="bookmark">
			<?php echo get_the_post_thumbnail( null, $image_size ) ?>
        </a>
        <figcaption>
            <h4><?php echo get_the_title(); ?></h4>
            <a href="<?php echo esc_url( get_the_permalink() ); ?>" rel="bookmark"
               class="button"><?php esc_html_e( 'Details', 'filterable-portfolio' ); ?></a>
        </figcaption>
    </figure>
</div>
