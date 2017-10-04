<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio_Scripts' ) ) {

	class Filterable_Portfolio_Scripts {

		private $plugin_name = 'filterable-portfolio';
		private $options;

		public function __construct( $options ) {
			$this->options = $options;

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 10 );
			add_action( 'wp_head', array( $this, 'inline_style' ), 30 );
		}

		/**
		 * Load admin scripts
		 */
		public function admin_scripts() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();

			wp_enqueue_style(
				$this->plugin_name,
				FILTERABLE_PORTFOLIO_ASSETS . '/css/admin.css',
				array(),
				FILTERABLE_PORTFOLIO_VERSION,
				'all'
			);
			wp_enqueue_script(
				$this->plugin_name,
				FILTERABLE_PORTFOLIO_ASSETS . '/js/admin.min.js',
				array(
					'jquery',
					'wp-color-picker',
					'jquery-ui-datepicker'
				),
				FILTERABLE_PORTFOLIO_VERSION,
				true
			);
		}

		/**
		 * Load front facing script
		 */
		public function frontend_scripts() {
			wp_register_script(
				'isotope',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/vendors/isotope.pkgd.min.js',
				array(),
				'3.0.3',
				true
			);
			wp_register_script(
				'shuffle',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/vendors/shuffle.min.js',
				array(),
				'4.0.2',
				true
			);
			wp_register_script(
				'tiny-slider',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/vendors/tiny-slider.min.js',
				array(),
				'2.2.0',
				true
			);
			wp_register_script(
				'filterable-portfolio',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/script.js',
				array(),
				FILTERABLE_PORTFOLIO_VERSION,
				true
			);

			if ( is_singular( 'portfolio' ) ) {
				wp_enqueue_script( 'tiny-slider' );
				wp_enqueue_script( 'filterable-portfolio' );
			}

			wp_enqueue_style(
				$this->plugin_name,
				FILTERABLE_PORTFOLIO_ASSETS . '/css/style.css',
				array(),
				FILTERABLE_PORTFOLIO_VERSION,
				'all'
			);
		}

		public function inline_style() {
			$btn_bg = ! empty( $this->options['button_color'] ) ? $this->options['button_color'] : '#4cc1be';
			?>
            <style type="text/css" id="filterable-portfolio-inline-style">
                .portfolio-terms {
                    border-bottom: 1px solid <?php echo $btn_bg; ?>;
                }

                .portfolio-terms button {
                    border: 1px solid <?php echo $btn_bg; ?>;
                    color: <?php echo $btn_bg; ?>;
                }

                .portfolio-terms button.active,
                .portfolio-terms button:focus,
                .portfolio-terms button:hover {
                    border: 1px solid <?php echo $btn_bg; ?>;
                    background-color: <?php echo $btn_bg; ?>;
                }

                .portfolio-items .button {
                    background-color: <?php echo $btn_bg; ?>;
                }
            </style>
			<?php
		}
	}
}
