<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Filterable_Portfolio_Shapla_Theme' ) ) {

	class Filterable_Portfolio_Shapla_Theme {

		/**
		 * Instance of current class
		 *
		 * @var self
		 */
		protected static $instance;

		/**
		 * @return Filterable_Portfolio_Shapla_Theme
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				// Provide single portfolio template via filter.
				add_filter( 'single_template', array( self::$instance, 'single_portfolio_template' ) );
				// Provide archive portfolio template via filter.
				add_filter( 'archive_template', array( self::$instance, 'archive_portfolio_template' ) );

				add_action( 'init', [ self::$instance, 'init_theme_content' ] );
			}

			return self::$instance;
		}

		/**
		 * Initialize theme content
		 *
		 * @return void
		 */
		public function init_theme_content() {
			$current_theme  = wp_get_theme();
			$theme_name     = $current_theme->get( 'Name' );
			$theme_template = $current_theme->get( 'Template' );
			$themes         = [
				'storefront' => [
					'before_content' => '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">',
					'after_content'  => '</main></div>',
				],
				'astra'      => [
					'before_content' => '<div id="primary" class="content-area primary"><main id="main" class="site-main" role="main">',
					'after_content'  => '</main></div>',
				],
			];

			$slug       = ! empty( $theme_template ) ? $theme_template : strtolower( $theme_name );
			$theme_info = $themes[ $slug ] ?? [
				'before_content' => '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">',
				'after_content'  => '</main></div>',
			];

			add_action( 'filterable_portfolio/before_main_content', function () use ( $theme_info ) {
				echo $theme_info['before_content'];
			} );
			add_action( 'filterable_portfolio/after_main_content', function () use ( $theme_info ) {
				echo $theme_info['after_content'];
			} );

			add_action( 'filterable_portfolio/loop_before', [ self::$instance, 'do_page_title' ] );
			add_action( 'filterable_portfolio/loop', [ self::$instance, 'do_loop_content' ] );
			add_action( 'filterable_portfolio/loop_after', [ self::$instance, 'do_pagination' ] );
		}

		/**
		 * Load single portfolio template from plugin.
		 *
		 * @param string $single_template The post template.
		 *
		 * @return string
		 */
		public function single_portfolio_template( $single_template ) {
			if ( ! Filterable_Portfolio_Helper::is_single_portfolio() ) {
				return $single_template;
			}

			// Include template file from the theme if it exists.
			if ( Filterable_Portfolio_Helper::has_single_template() ) {
				return locate_template( 'single-portfolio.php' );
			}

			if ( Filterable_Portfolio_Helper::is_shapla_theme_activate() ) {
				return FILTERABLE_PORTFOLIO_TEMPLATES . '/shapla/single-portfolio.php';
			}

			return apply_filters( 'filterable_portfolio/single_template', $single_template );
		}

		/**
		 * Load portfolio archive template from plugin.
		 *
		 * @param string $archive_template The post template.
		 *
		 * @return string
		 */
		public function archive_portfolio_template( $archive_template ) {
			// Exit if not portfolio archive
			if ( ! Filterable_Portfolio_Helper::is_portfolio_archive() ) {
				return $archive_template;
			}

			// Load template from theme if exists
			if ( Filterable_Portfolio_Helper::has_archive_template() ) {
				$templates = array(
					'archive-portfolio.php',
					'taxonomy-portfolio_cat.php',
					'taxonomy-portfolio_skill.php'
				);

				return locate_template( $templates );
			}

			if ( Filterable_Portfolio_Helper::is_shapla_theme_activate() ) {
				return FILTERABLE_PORTFOLIO_TEMPLATES . '/shapla/archive-portfolio.php';
			}

			// Load default archive template.
			return apply_filters(
				'filterable_portfolio/archive_template',
				FILTERABLE_PORTFOLIO_TEMPLATES . '/archive-portfolio.php'
			);
		}

		/**
		 * Show archive page title
		 *
		 * @return void
		 */
		public function do_page_title() {
			if ( ! apply_filters( 'filterable_portfolio/show_page_title', true ) ) {
				return;
			}
			?>
			<header class="page-header">
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			</header><?php
		}

		/**
		 * Load loop content
		 *
		 * @return void
		 */
		public function do_loop_content() {
			$_fp_class   = [ 'grids', 'portfolio-items' ];
			$_fp_class[] = sprintf( 'fp-theme-%s', Filterable_Portfolio_Helper::get_option( 'portfolio_theme' ) );
			if ( have_posts() ) : ?>
				<div class="<?php echo join( ' ', $_fp_class ); ?>">
					<?php
					while ( have_posts() ) {
						the_post();
						if ( ! has_post_thumbnail() ) {
							continue;
						}
						Filterable_Portfolio_Helper::load_template( 'content-portfolio.php', false );
					}
					?>
				</div>
			<?php endif;
		}

		/**
		 * Show pagination
		 *
		 * @return void
		 */
		public function do_pagination() {
			the_posts_pagination( array(
				'type'      => 'list',
				'next_text' => _x( 'Next', 'Next post', 'storefront' ),
				'prev_text' => _x( 'Previous', 'Previous post', 'storefront' ),
			) );
		}
	}
}
