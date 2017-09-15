<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio_Scripts' ) ):

	class Filterable_Portfolio_Scripts {
		private $plugin_name = 'filterable-portfolio';
		private $options;

		public function __construct( $options ) {
			$this->options = $options;

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

			add_action( 'wp_head', array( $this, 'inline_style' ), 10 );
			add_action( 'wp_footer', array( $this, 'inline_script' ), 30 );
		}

		public function admin_scripts() {
			global $post;
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();

			wp_enqueue_style(
				$this->plugin_name,
				FILTERABLE_PORTFOLIO_ASSETS . '/css/admin-style.css',
				array(),
				FILTERABLE_PORTFOLIO_VERSION,
				'all'
			);
			wp_enqueue_script(
				$this->plugin_name,
				FILTERABLE_PORTFOLIO_ASSETS . '/js/admin-script.js',
				array(
					'jquery',
					'wp-color-picker',
					'jquery-ui-datepicker'
				),
				FILTERABLE_PORTFOLIO_VERSION,
				true );

			wp_localize_script( $this->plugin_name, 'FilterablePortfolio', array(
				'ajaxurl'           => admin_url( 'admin-ajax.php' ),
				'nonce'             => wp_create_nonce( 'fp_ajax_nonce' ),
				'post_id'           => $post ? $post->ID : '',
				'image_ids'         => $post ? get_post_meta( $post->ID, '_project_images', true ) : '',
				'create_btn_text'   => __( 'Create Gallery', 'filterable-portfolio' ),
				'edit_btn_text'     => __( 'Edit Gallery', 'filterable-portfolio' ),
				'progress_btn_text' => __( 'Saving...', 'filterable-portfolio' ),
				'save_btn_text'     => __( 'Save Gallery', 'filterable-portfolio' ),
			) );
		}

		public function frontend_scripts() {
			wp_register_script(
				'isotope',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/isotope.pkgd.min.js',
				array(),
				'3.0.3',
				true
			);
			wp_register_script(
				'isotope-fp-custom',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/isotope-custom.js',
				array( 'isotope' ),
				FILTERABLE_PORTFOLIO_VERSION,
				true
			);
			wp_register_script(
				'shuffle',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/shuffle.min.js',
				array(),
				'4.0.2',
				true
			);
			wp_register_script(
				'shuffle-fp-custom',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/shuffle-custom.js',
				array( 'shuffle' ),
				FILTERABLE_PORTFOLIO_VERSION,
				true
			);

			if ( is_singular( 'portfolio' ) ) {
				wp_enqueue_script( 'jquery' );
			}
		}

		public function inline_style() {
			global $post;
			$theme      = $this->options['portfolio_theme'];
			$btn_bg     = ! empty( $this->options['button_color'] ) ? $this->options['button_color'] : '#4cc1be';
			$slideArrow = FILTERABLE_PORTFOLIO_ASSETS . '/img/themes.gif';

			$grids    = file_get_contents( FILTERABLE_PORTFOLIO_ASSETS . '/css/grids.css' );
			$terms    = file_get_contents( FILTERABLE_PORTFOLIO_ASSETS . '/css/terms.css' );
			$themeOne = file_get_contents( FILTERABLE_PORTFOLIO_ASSETS . '/css/theme-one.css' );
			$themeTwo = file_get_contents( FILTERABLE_PORTFOLIO_ASSETS . '/css/theme-two.css' );
			$slides   = file_get_contents( FILTERABLE_PORTFOLIO_ASSETS . '/css/slides.css' );
			$meta     = file_get_contents( FILTERABLE_PORTFOLIO_ASSETS . '/css/project-meta.css' );

			?>
            <style type="text/css" id="filterable-portfolio-css">
                <?php
					if ($this->should_load_script( $post ) || is_singular( 'portfolio' ) || is_tax( 'portfolio_cat' ) || is_tax( 'portfolio_skill' ) ) {
						echo $grids;
						echo str_replace('#4cc1be', $btn_bg, $terms);

						if ( $theme == 'one' ) {
							echo str_replace('#4cc1be', $btn_bg, $themeOne);
						} else {
							echo str_replace('#4cc1be', $btn_bg, $themeTwo);
						}

						echo wp_strip_all_tags($this->options['custom_css']);
					}

					if ( is_singular( 'portfolio' ) ) {
						echo $meta;
						echo str_replace('../img/themes.gif', $slideArrow, $slides);
					}
				?>
            </style>
			<?php
		}

		public function inline_script() {
			if ( is_singular( 'portfolio' ) ) {
				$responsiveslides = file_get_contents( FILTERABLE_PORTFOLIO_ASSETS . '/js/responsiveslides.min.js' );

				?>
                <script type="text/javascript">
					<?php echo $responsiveslides; ?>

                    jQuery(document).ready(function ($) {
                        $("#fp_slides").responsiveSlides({
                            auto: true,
                            pager: true,
                            nav: true,
                            speed: 500,
                            namespace: "fp_slides"
                        });
                    });
                </script>
				<?php
			}
		}

		public function should_load_script( $post ) {
			global $post;
			$load_scripts = is_active_widget( false, false, 'widget_filterable_portfolio', true ) ||
			                ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'filterable_portfolio' ) );

			return apply_filters( 'filterable_portfolio_load_scripts', $load_scripts );
		}
	}

endif;
