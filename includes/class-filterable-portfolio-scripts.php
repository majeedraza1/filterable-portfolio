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
			add_action( 'wp_head', array( $this, 'inline_style' ), 30 );
		}

		/**
		 * Register plugin admin & public styles
		 */
		public function register_styles() {
			$styles = array(
				'filterable-portfolio'       => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/css/style.css',
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
				'shuffle'                    => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/lib/shuffle/shuffle' . $suffix . '.js',
					'dependency' => array( 'imagesloaded' ),
					'version'    => '5.2.1',
					'in_footer'  => true,
				),
				'tiny-slider'                => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/lib/tiny-slider/tiny-slider' . $suffix . '.js',
					'dependency' => array(),
					'version'    => '2.9.1',
					'in_footer'  => true,
				),
				'wp-color-picker-alpha'      => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/lib/wp-color-picker-alpha/wp-color-picker-alpha' . $suffix . '.js',
					'dependency' => array( 'jquery', 'wp-color-picker' ),
					'version'    => '2.1.3',
					'in_footer'  => true,
				),
				'filterable-portfolio'       => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/js/script' . $suffix . '.js',
					'dependency' => array(),
					'version'    => FILTERABLE_PORTFOLIO_VERSION,
					'in_footer'  => true,
				),
				'filterable-portfolio-admin' => array(
					'src'        => FILTERABLE_PORTFOLIO_ASSETS . '/js/admin' . $suffix . '.js',
					'dependency' => array( 'jquery', 'wp-color-picker-alpha', 'jquery-ui-datepicker' ),
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
                .portfolio-terms {
                    border-bottom-color: <?php echo $btn_bg; ?>;
                }

                .portfolio-terms button {
                    border-color: <?php echo $btn_bg; ?>;
                    color: <?php echo $btn_bg; ?>;
                }

                .portfolio-terms button.active,
                .portfolio-terms button:focus,
                .portfolio-terms button:hover {
                    border-color: <?php echo $btn_bg; ?>;
                    background-color: <?php echo $btn_bg; ?>;
                }

                .portfolio-items .button,
                .portfolio-items button:focus,
                .portfolio-items button:hover {
                    background-color: <?php echo $btn_bg; ?> !important;
                }
            </style>
			<?php
		}
	}
}
