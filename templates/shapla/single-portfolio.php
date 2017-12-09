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

$id              = get_the_ID();
$project_images  = get_post_meta( $id, '_project_images', true );
$project_images  = array_filter( explode( ',', rtrim( $project_images, ',' ) ) );
$project_url     = esc_url( get_post_meta( $id, '_project_url', true ) );
$client_name     = esc_attr( get_post_meta( $id, '_client_name', true ) );
$project_date    = esc_attr( get_post_meta( $id, '_project_date', true ) );
$project_date    = date_i18n( get_option( 'date_format' ), strtotime( $project_date ) );
$terms           = get_the_terms( $id, 'portfolio_cat' );
$skills          = get_the_terms( $id, 'portfolio_skill' );
$options         = get_option( 'filterable_portfolio' );
$categories_text = esc_attr( $options['project_categories_text'] );
$skills_text     = esc_attr( $options['project_skills_text'] );
$url_text        = esc_attr( $options['project_url_text'] );
$date_text       = esc_attr( $options['project_date_text'] );
$client_text     = esc_attr( $options['project_client_text'] );

$_theme    = $options['portfolio_theme'];
$_fp_class = 'grids portfolio-items related-projects';
$_fp_class .= ' fp-theme-' . $_theme;

$image_size = esc_attr( $options['image_size'] );
$rp_grid    = sprintf(
	'grid %1$s %2$s %3$s %4$s',
	esc_attr( $options['columns_phone'] ),
	esc_attr( $options['columns_tablet'] ),
	esc_attr( $options['columns_desktop'] ),
	esc_attr( $options['columns'] )
);

$args = array(
	'post_type'      => 'portfolio',
	'posts_per_page' => intval( $options['related_projects_number'] ),
	'post__not_in'   => array( get_the_ID() ),
	'tax_query'      => array(
		'relation' => 'OR'
	)
);

if ( is_array( $terms ) ) {
	$term_ids = array_map( function ( $tag ) {
		return $tag->term_id;
	}, $terms );

	$args['tax_query'][] = array(
		'taxonomy' => 'portfolio_cat',
		'field'    => 'id',
		'terms'    => $term_ids
	);
}

if ( is_array( $skills ) ) {
	$skill_ids = array_map( function ( $tag ) {
		return $tag->term_id;
	}, $skills );

	$args['tax_query'][] = array(
		'taxonomy' => 'portfolio_skill',
		'field'    => 'id',
		'terms'    => $skill_ids
	);
}

$related_projects = get_posts( $args );

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
											<?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
                                        </div>
                                    </div>
								<?php endforeach; ?>
                            </div>

                            <div class="fp-tns-slider-controls">
                                <span class="prev" data-controls="prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32">
                                        <path d="M12.3 17.71l6.486 6.486c0.39 0.39 1.024 0.39 1.414 0s0.39-1.024 0-1.414l-5.782-5.782 5.782-5.782c0.39-0.39 0.39-1.024 0-1.414s-1.024-0.39-1.414 0l-6.486 6.486c-0.196 0.196-0.292 0.452-0.292 0.71s0.096 0.514 0.292 0.71z"></path>
                                    </svg>
                                </span>
                                <span class="next" data-controls="next">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32">
                                        <path d="M13.8 24.196c0.39 0.39 1.024 0.39 1.414 0l6.486-6.486c0.196-0.196 0.294-0.454 0.292-0.71 0-0.258-0.096-0.514-0.292-0.71l-6.486-6.486c-0.39-0.39-1.024-0.39-1.414 0s-0.39 1.024 0 1.414l5.782 5.782-5.782 5.782c-0.39 0.39-0.39 1.024 0 1.414z"></path>
                                    </svg>
                                </span>
                            </div>
                            <!-- Arrows Navigation -->
                        </div>
						<?php

					} elseif ( has_post_thumbnail() ) {
						the_post_thumbnail();
					}

					echo '<header class="entry-header">';
					the_title( '<h1 class="entry-title">', '</h1>' );
					echo '</header>';

					echo '<div class="grids">';
					echo '<div class="project-content grid s8">';
					echo sprintf( '<h4>%s</h4>', esc_attr( $options['project_description_text'] ) );
					the_content();
					echo '</div>';
					echo '<div class="project-meta grid s4">';
					echo sprintf( '<h4>%s</h4>', esc_attr( $options['project_details_text'] ) );
					echo "<ul>";
					// Skills
					if ( is_array( $skills ) ) {
						$skills = get_the_term_list( $id, 'portfolio_skill', '', '<br />', '' );
						echo sprintf( '<li><strong>%1$s</strong><p>%2$s</p></li>', $skills_text, $skills );
					}
					// Categories
					if ( is_array( $terms ) ) {
						$terms = get_the_term_list( $id, 'portfolio_cat', '', '<br />', '' );
						echo sprintf( '<li><strong>%1$s</strong><p>%2$s</p></li>', $categories_text, $terms );
					}
					// Client
					if ( ! empty( $client_name ) ) {
						echo sprintf( '<li><strong>%1$s</strong><p>%2$s</p></li>', $client_text, $client_name );
					}
					// Project Date
					if ( ! empty( $project_date ) ) {
						echo sprintf( '<li><strong>%1$s</strong><p>%2$s</p></li>', $date_text, $project_date );
					}
					// Project URL
					if ( ! empty( $project_url ) ) {
						echo sprintf( '<li><strong>%1$s</strong><p><a target="_blank" href="%2$s">%2$s</a></p></li>',
							$url_text, $project_url );
					}
					echo "</ul>";
					echo '</div>';
					echo '</div>';
					?>
                </div><!-- #post-## -->
				<?php

				if ( count( $related_projects ) > 0 ):?>
                    <h4 class="related-projects-title">
						<?php echo esc_attr( $options['related_projects_text'] ); ?>
                    </h4>
                    <div class="<?php echo $_fp_class; ?>">
						<?php foreach ( $related_projects as $portfolio ): ?>
                            <div id="id-<?php echo $portfolio->ID; ?>" class="portfolio-item <?php echo $rp_grid; ?>">
                                <figure>
                                    <a href="<?php echo esc_url( get_permalink( $portfolio->ID ) ); ?>" rel="bookmark">
                                        <img src="<?php echo get_the_post_thumbnail_url( $portfolio->ID,
											$image_size ); ?>">
                                    </a>
                                    <figcaption>
                                        <h4><?php echo esc_attr( $portfolio->post_title ); ?></h4>
                                        <a href="<?php echo esc_url( get_permalink( $portfolio->ID ) ); ?>"
                                           rel="bookmark" class="button"><?php _e( 'Details',
												'filterable-portfolio' ); ?></a>
                                    </figcaption>
                                </figure>
                            </div>
						<?php endforeach; ?>
                    </div>
				<?php endif;

				do_action( 'shapla_single_post_after' );

			endwhile; // End of the loop.
			?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
