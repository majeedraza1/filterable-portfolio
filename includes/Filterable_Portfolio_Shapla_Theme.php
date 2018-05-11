<?php

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
			}

			return self::$instance;
		}

		public function __construct() {
			if ( ! $this->is_shapla_theme_activate() ) {
				return;
			}

			// Provide single portfolio template via filter.
			add_filter( 'single_template', array( $this, 'single_portfolio_template' ) );
			// Provide archive portfolio template via filter.
			add_filter( 'archive_template', array( $this, 'archive_portfolio_template' ) );
		}

		/**
		 * Check if Shapla theme or it's child theme is active
		 * @return boolean
		 */
		private function is_shapla_theme_activate() {
			$current_theme  = wp_get_theme();
			$theme_name     = $current_theme->get( 'Name' );
			$theme_template = $current_theme->get( 'Template' );

			if ( $theme_template == 'shapla' || $theme_name == 'Shapla' ) {
				return true;
			}

			return false;
		}

		/**
		 * Load single portfolio template from plugin.
		 *
		 * @param string $single_template The post template.
		 *
		 * @return string
		 */
		public function single_portfolio_template( $single_template ) {
			if ( is_singular( 'portfolio' ) ) {
				// Include template file from the theme if it exists.
				if ( locate_template( 'single-portfolio.php' ) ) {
					return locate_template( 'single-portfolio.php' );
				}

				if ( $this->is_shapla_theme_activate() ) {
					// Include template file from the plugin.
					$single_portfolio_template = wp_normalize_path( dirname( dirname( __FILE__ ) ) . '/templates/shapla/single-portfolio.php' );

					// Checks if the single post is portfolio.
					if ( file_exists( $single_portfolio_template ) ) {
						return $single_portfolio_template;
					}
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
			if ( is_post_type_archive( 'portfolio' ) ) {
				// Include template file from the theme if it exists.
				if ( locate_template( 'archive-portfolio.php' ) ) {
					return locate_template( 'archive-portfolio.php' );
				}
			}

			if ( is_tax( 'portfolio_cat' ) ) {
				// Include template file from the theme if it exists.
				if ( locate_template( 'taxonomy-portfolio_cat.php' ) ) {
					return locate_template( 'taxonomy-portfolio_cat.php' );
				}
			}

			if ( is_tax( 'portfolio_skill' ) ) {
				// Include template file from the theme if it exists.
				if ( locate_template( 'taxonomy-portfolio_skill.php' ) ) {
					return locate_template( 'taxonomy-portfolio_skill.php' );
				}
			}

			if ( $this->is_shapla_theme_activate() ) {

				$archive_portfolio_template = FILTERABLE_PORTFOLIO_TEMPLATES . '/shapla/archive-portfolio.php';
				// Checks if the archive is portfolio.
				if ( is_post_type_archive( 'portfolio' )
				     || is_tax( 'portfolio_cat' )
				     || is_tax( 'portfolio_skill' ) ) {

					if ( file_exists( $archive_portfolio_template ) ) {
						return $archive_portfolio_template;
					}
				}
			}

			return $archive_template;
		}
	}
}
