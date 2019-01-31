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

				if ( ! Filterable_Portfolio_Helper::is_shapla_theme_activate() ) {
					return self::$instance;
				}

				// Provide single portfolio template via filter.
				add_filter( 'single_template', array( self::$instance, 'single_portfolio_template' ) );
				// Provide archive portfolio template via filter.
				add_filter( 'archive_template', array( self::$instance, 'archive_portfolio_template' ) );
			}

			return self::$instance;
		}

		/**
		 * Load single portfolio template from plugin.
		 *
		 * @param string $single_template The post template.
		 *
		 * @return string
		 */
		public function single_portfolio_template( $single_template ) {
			if ( Filterable_Portfolio_Helper::is_single_portfolio() ) {
				// Include template file from the theme if it exists.
				if ( Filterable_Portfolio_Helper::has_single_template() ) {
					return locate_template( 'single-portfolio.php' );
				}

				// Include template file from the plugin.
				$single_portfolio_template = FILTERABLE_PORTFOLIO_TEMPLATES . '/shapla/single-portfolio.php';
				if ( file_exists( $single_portfolio_template ) ) {
					return $single_portfolio_template;
				}
			}

			return $single_template;
		}

		/**
		 * Load portfolio archive template from plugin.
		 *
		 * @param string $archive_template The post template.
		 *
		 * @return string
		 */
		public function archive_portfolio_template( $archive_template ) {
			if ( Filterable_Portfolio_Helper::is_portfolio_archive() ) {
				if ( Filterable_Portfolio_Helper::has_archive_template() ) {
					$templates = array(
						'archive-portfolio.php',
						'taxonomy-portfolio_cat.php',
						'taxonomy-portfolio_skill.php'
					);

					return locate_template( $templates );
				} else {
					$archive_portfolio_template = FILTERABLE_PORTFOLIO_TEMPLATES . '/shapla/archive-portfolio.php';
					if ( file_exists( $archive_portfolio_template ) ) {
						return $archive_portfolio_template;
					}
				}
			}

			return $archive_template;
		}
	}
}
