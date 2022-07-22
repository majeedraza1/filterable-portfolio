<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Filterable_Portfolio_Scripts' ) ) {

	class Filterable_Portfolio_Scripts {

		/**
		 * Instance of current class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return self
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				self::$instance->init_hooks();
			}

			return self::$instance;
		}

		/**
		 * Filterable_Portfolio_Scripts constructor.
		 */
		public function init_hooks() {
			add_action( 'wp_loaded', array( $this, 'register_styles' ) );
			add_action( 'wp_loaded', array( $this, 'register_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 10 );
			add_action( 'wp_head', array( $this, 'inline_style' ), 5 );
		}

		/**
		 * Plugin version
		 *
		 * @return string
		 */
		public function plugin_version() {
			$version = FILTERABLE_PORTFOLIO_VERSION;

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				return $version . '-' . time();
			}

			return $version;
		}

		/**
		 * Register plugin admin & public styles
		 */
		public function register_styles() {
			$styles = [
				'filterable-portfolio'       => [
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/css/frontend.css',
					'dependency' => [],
					'version'    => $this->plugin_version(),
					'media'      => 'all',
				],
				'filterable-portfolio-admin' => [
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/css/admin.css',
					'dependency' => [ 'wp-color-picker' ],
					'version'    => $this->plugin_version(),
					'media'      => 'all',
				],
			];

			foreach ( $styles as $handle => $style ) {
				wp_register_style( $handle, $style['src'], $style['dependency'], $style['version'], $style['media'] );
			}
		}

		/**
		 * Register plugin admin & public scripts
		 */
		public function register_scripts() {
			$scripts = [
				'filterable-portfolio'        => [
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/js/frontend.js',
					'dependency' => [ 'imagesloaded' ],
					'version'    => $this->plugin_version(),
					'in_footer'  => true,
				],
				'filterable-portfolio-single' => [
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/js/single-portfolio.js',
					'dependency' => [],
					'version'    => $this->plugin_version(),
					'in_footer'  => true,
				],
				'filterable-portfolio-admin'  => [
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/js/admin.js',
					'dependency' => [ 'jquery', 'wp-color-picker' ],
					'version'    => $this->plugin_version(),
					'in_footer'  => true,
				],
			];


			foreach ( $scripts as $handle => $script ) {
				wp_register_script( $handle, $script['src'], $script['dependency'], $script['version'], $script['in_footer'] );
			}
		}

		/**
		 * Load admin scripts
		 */
		public function admin_scripts() {
			wp_enqueue_media();
			wp_enqueue_style( 'filterable-portfolio-admin' );
			wp_enqueue_script( 'filterable-portfolio-admin' );
		}

		/**
		 * Load front facing script
		 */
		public function frontend_scripts() {
			wp_enqueue_style( 'filterable-portfolio' );
		}

		/**
		 * Check if it should load scripts
		 *
		 * @return bool
		 */
		public function should_load_scripts() {
			if ( Filterable_Portfolio_Helper::is_single_portfolio() ) {
				return true;
			}

			if ( Filterable_Portfolio_Helper::is_portfolio_archive() ) {
				return true;
			}

			return false;
		}

		/**
		 * Dynamic style
		 */
		public function inline_style() {
			$options = Filterable_Portfolio_Helper::get_options();
			$btn_bg  = esc_attr( $options['button_color'] );
			?>
			<style id="filterable-portfolio-inline-style">
				:root {
					--portfolio-primary: <?php echo $btn_bg; ?>;
					--portfolio-on-primary: <?php echo $this->find_color_invert($btn_bg); ?>;
				}
			</style>
			<?php
		}

		/**
		 * Find light or dark color for given color
		 *
		 * @param string $color
		 *
		 * @return string
		 */
		public function find_color_invert( $color ) {
			if ( '' === $color ) {
				return '';
			}

			// Trim unneeded whitespace
			$color = str_replace( ' ', '', $color );

			// 3 or 6 hex digits, or the empty string.
			if ( preg_match( '/^(\#[\da-f]{3}|\#[\da-f]{6}|\#[\da-f]{8})$/', $color ) ) {
				// Format the hex color string.
				$hex = str_replace( '#', '', $color );

				if ( 3 == strlen( $hex ) ) {
					$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) .
						   str_repeat( substr( $hex, 1, 1 ), 2 ) .
						   str_repeat( substr( $hex, 2, 1 ), 2 );
				}

				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}

			// If this is rgb color
			if ( 'rgb(' === substr( $color, 0, 4 ) ) {
				list( $r, $g, $b ) = sscanf( $color, 'rgb(%d,%d,%d)' );
			}

			// If this is rgba color
			if ( 'rgba(' === substr( $color, 0, 5 ) ) {
				list( $r, $g, $b, $alpha ) = sscanf( $color, 'rgba(%d,%d,%d,%f)' );
			}

			if ( ! isset( $r, $g, $b ) ) {
				return '';
			}

			$contrast = (
				$r * $r * .299 +
				$g * $g * .587 +
				$b * $b * .114
			);

			if ( $contrast > pow( 130, 2 ) ) {
				//bright color, use dark font
				return '#000';
			} else {
				//dark color, use bright font
				return '#fff';
			}
		}
	}
}
