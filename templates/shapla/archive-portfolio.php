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
$options   = get_option( 'filterable_portfolio' );
$_theme    = $options['portfolio_theme'];
$_fp_class = 'grids portfolio-items';
$_fp_class .= ' fp-theme-' . $_theme;

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php if ( have_posts() ) : ?>
				<div class="<?php echo $_fp_class; ?>">
					<?php
					while ( have_posts() ) {
						the_post();
						if ( ! has_post_thumbnail() ) {
							continue;
						}
						do_action( 'filterable_portfolio_loop_post' );
					}
					?>
				</div>
			<?php endif; ?>
			<?php
			/**
			 * Functions hooked in to shapla_paging_nav action
			 *
			 * @hooked shapla_paging_nav - 10
			 */
			do_action( 'shapla_loop_after' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
