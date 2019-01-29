<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
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
			if ( Filterable_Portfolio_Utils::is_single_portfolio() ) {

				if ( Filterable_Portfolio_Utils::has_single_template() || Filterable_Portfolio_Utils::is_shapla_theme_activate() ) {
					return $html;
				}

				if ( Filterable_Portfolio_Utils::has_portfolio_images() ) {
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
			if ( Filterable_Portfolio_Utils::is_single_portfolio() ) {

				if ( Filterable_Portfolio_Utils::has_single_template() || Filterable_Portfolio_Utils::is_shapla_theme_activate() ) {
					return $content;
				}

				ob_start();
				require FILTERABLE_PORTFOLIO_TEMPLATES . '/single-portfolio.php';

				if ( $this->options['show_related_projects'] ) {
					require FILTERABLE_PORTFOLIO_TEMPLATES . '/related-portfolio.php';
				}
				$project = ob_get_contents();
				ob_end_clean();

				return $project;
			}

			return $content;
		}
	}
}