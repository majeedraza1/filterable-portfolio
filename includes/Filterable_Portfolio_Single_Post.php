<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio_Single_Post' ) ) {

	class Filterable_Portfolio_Single_Post {

		/**
		 * Plugin options
		 *
		 * @var array
		 */
		private $options = array();

		/**
		 * class construct of filterable portfolio single post
		 *
		 * @param array $options
		 */
		public function __construct( $options ) {
			$this->options = $options;

			add_filter( 'post_thumbnail_html', array( $this, 'post_thumbnail_html' ) );
			add_filter( 'the_content', array( $this, 'portfolio_content' ), 20 );
		}

		/**
		 * Filters the post thumbnail HTML for portfolio.
		 *
		 * @param string $html The post thumbnail HTML.
		 *
		 * @return string
		 */
		public function post_thumbnail_html( $html ) {
			if ( is_singular( 'portfolio' ) ) {

				if ( $this->single_portfolio_loaded_in_theme() ) {
					return $html;
				}

				if ( $this->is_shapla_theme_activate() ) {
					return $html;
				}

				$ids = get_post_meta( get_the_ID(), '_project_images', true );
				$ids = array_filter( explode( ',', rtrim( $ids, ',' ) ) );
				if ( count( $ids ) > 1 ) {
					return '';
				}

				return $html;
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
			if ( is_singular( 'portfolio' ) ) {

				if ( $this->single_portfolio_loaded_in_theme() ) {
					return $content;
				}

				if ( $this->is_shapla_theme_activate() ) {
					return $content;
				}

				ob_start();
				require FILTERABLE_PORTFOLIO_TEMPLATES . '/single-portfolio.php';

				if ( $this->options['show_related_projects'] ) {
					require FILTERABLE_PORTFOLIO_TEMPLATES . '/related-project.php';
				}
				$project = ob_get_contents();
				ob_end_clean();

				return $project;
			}

			return $content;
		}

		/**
		 * Check if single-portfolio.php file loaded in theme directory
		 *
		 * @return boolean
		 */
		public function single_portfolio_loaded_in_theme() {
			if ( locate_template( "single-portfolio.php" ) != '' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if Shapla theme or it's child theme is active
		 * @return boolean
		 */
		public function is_shapla_theme_activate() {
			$current_theme  = wp_get_theme();
			$theme_name     = $current_theme->get( 'Name' );
			$theme_template = $current_theme->get( 'Template' );

			if ( $theme_template == 'shapla' || $theme_name == 'Shapla' ) {
				return true;
			}

			return false;
		}
	}
}