<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Filterable_Portfolio_Single_Post' ) ) {

	class Filterable_Portfolio_Single_Post {

		/**
		 * Instance of current class
		 *
		 * @var self
		 */
		protected static $instance;

		/**
		 * @return self
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_filter( 'post_thumbnail_html', array( self::$instance, 'post_thumbnail_html' ) );
				add_filter( 'the_content', array( self::$instance, 'portfolio_content' ), 20 );
			}

			return self::$instance;
		}

		/**
		 * Filters the post thumbnail HTML for portfolio.
		 *
		 * @param string $html The post thumbnail HTML.
		 *
		 * @return string
		 */
		public function post_thumbnail_html( $html ) {
			if ( Filterable_Portfolio_Helper::is_single_portfolio() ) {

				if ( Filterable_Portfolio_Helper::has_single_template() || Filterable_Portfolio_Helper::is_shapla_theme_activate() ) {
					return $html;
				}

				if ( Filterable_Portfolio_Helper::has_portfolio_images() ) {
					return '';
				}
			}

			return $html;
		}

		/**
		 * Filterable portfolio single page content
		 *
		 * @param  string $content
		 *
		 * @return string
		 */
		public function portfolio_content( $content ) {
			if ( Filterable_Portfolio_Helper::is_single_portfolio() ) {

				if ( Filterable_Portfolio_Helper::has_single_template() || Filterable_Portfolio_Helper::is_shapla_theme_activate() ) {
					return $content;
				}

				ob_start();
				require FILTERABLE_PORTFOLIO_TEMPLATES . '/single-portfolio.php';
				$project = ob_get_contents();
				ob_end_clean();

				return $project;
			}

			return $content;
		}
	}
}