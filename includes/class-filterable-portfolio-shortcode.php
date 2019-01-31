<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Filterable_Portfolio_Shortcode' ) ) {

	class Filterable_Portfolio_Shortcode {

		/**
		 * Instance of current class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * @return self
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_shortcode( 'filterable_portfolio', array( self::$instance, 'shortcode' ) );
				add_action( 'filterable_portfolio_loop_post', array( self::$instance, 'portfolio_item' ) );
			}

			return self::$instance;
		}

		/**
		 * Filterable Portfolio shortcode.
		 *
		 * @return mixed
		 */
		public function shortcode() {
			$options = get_option( 'filterable_portfolio' );
			$isotope = isset( $options['portfolio_filter_script'] ) && $options['portfolio_filter_script'] == 'isotope';
			if ( $isotope ) {
				wp_enqueue_script( 'isotope' );
			} else {
				wp_enqueue_script( 'shuffle' );
			}

			wp_enqueue_script( 'filterable-portfolio' );

			$portfolios = Filterable_Portfolio_Helper::get_portfolios();
			$categories = Filterable_Portfolio_Helper::get_portfolio_categories();

			ob_start();
			$locate_template = locate_template( "filterable_portfolio.php" );
			if ( $locate_template != '' ) {
				load_template( $locate_template, false );
			} else {
				require FILTERABLE_PORTFOLIO_TEMPLATES . '/filterable_portfolio.php';
			}
			$html = ob_get_contents();
			ob_end_clean();

			return apply_filters( 'filterable_portfolio', $html, $portfolios, $categories );
		}

		/**
		 * Portfolio loop post content
		 */
		public function portfolio_item() {
			$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/content-portfolio.php';
			load_template( $template, false );
		}
	}
}
