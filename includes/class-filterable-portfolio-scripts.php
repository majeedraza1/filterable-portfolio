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
		 * Register plugin admin & public styles
		 */
		public function register_styles() {
			$styles = array(
				'filterable-portfolio'       => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/css/frontend.css',
					'dependency' => array(),
					'version'    => FILTERABLE_PORTFOLIO_VERSION,
					'media'      => 'all',
				),
				'filterable-portfolio-admin' => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/css/admin.css',
					'dependency' => array( 'wp-color-picker' ),
					'version'    => FILTERABLE_PORTFOLIO_VERSION,
					'media'      => 'all',
				),
			);

			foreach ( $styles as $handle => $style ) {
				wp_register_style( $handle, $style['src'], $style['dependency'], $style['version'], $style['media'] );
			}
		}

		/**
		 * Register plugin admin & public scripts
		 */
		public function register_scripts() {
			$suffix = ( defined( "SCRIPT_DEBUG" ) && SCRIPT_DEBUG ) ? '' : '.min';

			$scripts = array(
				'isotope'                    => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/lib/isotope/isotope' . $suffix . '.js',
					'dependency' => array( 'imagesloaded' ),
					'version'    => '3.0.5',
					'in_footer'  => true,
				),
				'tiny-slider'                => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/lib/tiny-slider/tiny-slider' . $suffix . '.js',
					'dependency' => array(),
					'version'    => '2.9.1',
					'in_footer'  => true,
				),
				'filterable-portfolio'       => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/js/frontend.js',
					'dependency' => array(),
					'version'    => FILTERABLE_PORTFOLIO_VERSION,
					'in_footer'  => true,
				),
				'filterable-portfolio-admin' => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/js/admin.js',
					'dependency' => array( 'jquery', 'wp-color-picker', 'jquery-ui-datepicker' ),
					'version'    => FILTERABLE_PORTFOLIO_VERSION,
					'in_footer'  => true,
				),
			);


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

			if ( is_singular( 'portfolio' ) ) {
				wp_enqueue_script( 'tiny-slider' );
				wp_enqueue_script( 'filterable-portfolio' );
			}
		}

		/**
		 * Dynamic style
		 */
		public function inline_style() {
			$options = get_option( 'filterable_portfolio' );
			$btn_bg  = ! empty( $options['button_color'] ) ? $options['button_color'] : '#4cc1be';
			?>
            <style type="text/css" id="filterable-portfolio-inline-style">
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
		function find_color_invert( $color ) {
			if ( '' === $color ) {
				return '';
			}

			// Trim unneeded whitespace
			$color = str_replace( ' ', '', $color );

			// If this is hex color
			if ( 1 === preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
				$r = hexdec( substr( $color, 0, 2 ) );
				$g = hexdec( substr( $color, 2, 2 ) );
				$b = hexdec( substr( $color, 4, 2 ) );
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
