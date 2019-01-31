<?php
/**
 * The template for displaying all single portfolios.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Shapla
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$id             = get_the_ID();
$project_images = get_post_meta( $id, '_project_images', true );
$project_images = array_filter( explode( ',', rtrim( $project_images, ',' ) ) );
$options        = get_option( 'filterable_portfolio' );

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

			<?php
			while ( have_posts() ) : the_post();

				do_action( 'shapla_single_post_before' );

				?>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php
					do_action( 'shapla_single_post_top' );

					if ( count( $project_images ) > 0 ) {
						$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/portfolio-slider.php';
						load_template( $template, false );
					} elseif ( has_post_thumbnail() ) {
						the_post_thumbnail();
					}

					echo '<div class="grids">';
					echo '<div class="project-content grid s8">';
					echo sprintf( '<h4>%s</h4>', esc_attr( $options['project_description_text'] ) );
					the_content();
					echo '</div>';
					echo '<div class="project-meta grid s4">';
					$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/portfolio-meta.php';
					load_template( $template, false );
					echo '</div>';
					echo '</div>';
					?>
                </div><!-- #post-## -->
				<?php

				if ( isset( $options['show_related_projects'] ) && $options['show_related_projects'] ) {
					$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/related-portfolio.php';
					load_template( $template, false );
				}

				do_action( 'shapla_single_post_after' );

			endwhile; // End of the loop.
			?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
