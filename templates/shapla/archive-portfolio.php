<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Shapla
 */
$options    = get_option( 'filterable_portfolio' );
$image_size = esc_attr( $options['image_size'] );
$grid       = sprintf(
	'grid %1$s %2$s %3$s %4$s',
	$options['columns_phone'],
	$options['columns_tablet'],
	$options['columns_desktop'],
	$options['columns']
);

$_theme    = $options['portfolio_theme'];
$_fp_class = 'grids portfolio-items';
$_fp_class .= ' fp-theme-' . $_theme;

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <header class="page-header">
                <h1 class="page-title"><?php echo single_cat_title( '', false ); ?></h1>
            </header><!-- .page-header -->

			<?php if ( have_posts() ) : ?>

                <div class="<?php echo $_fp_class; ?>">

					<?php while ( have_posts() ) : the_post();
						if ( ! has_post_thumbnail() ) {
							continue;
						}
						?>
                        <div id="id-<?php echo get_the_ID(); ?>" class="portfolio-item <?php echo $grid; ?>">
                            <figure>
                                <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
									<?php echo get_the_post_thumbnail( get_the_ID(), $image_size ); ?>
                                </a>
                                <figcaption>
                                    <h4><?php echo get_the_title(); ?></h4>
                                    <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"
                                       class="button"><?php echo esc_attr( $options['details_button_text'] ); ?></a>
                                </figcaption>
                            </figure>
                        </div>
					<?php endwhile; ?>
                </div>
			<?php endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
