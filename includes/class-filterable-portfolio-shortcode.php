<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Filterable_Portfolio_Shortcode' ) ) {

	class Filterable_Portfolio_Shortcode {

		/**
		 * Instance of current class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * @return self
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_shortcode( 'filterable_portfolio', array( self::$instance, 'shortcode' ) );
				add_action( 'filterable_portfolio_loop_post', array( self::$instance, 'portfolio_item' ) );
			}

			return self::$instance;
		}

		/**
		 * Filterable Portfolio shortcode.
		 *
		 * @param array $attributes
		 *
		 * @return mixed
		 */
		public function shortcode( $attributes ) {
			$attributes = shortcode_atts( [
				'featured'    => 'no',
				'show_filter' => 'yes',
			], $attributes, 'filterable_portfolio' );

			wp_enqueue_script( 'isotope' );
			wp_enqueue_script( 'filterable-portfolio' );

			$args = [];

			$featured = in_array( $attributes['featured'], [ 'yes', 'on', 'true', true, 1 ], true );
			if ( $featured ) {
				$args['featured'] = $featured;
			}

			$portfolios = Filterable_Portfolio_Helper::get_portfolios( $args );
			$categories = Filterable_Portfolio_Helper::get_categories_from_portfolios( $portfolios );

			ob_start();
			$locate_template = locate_template( "filterable_portfolio.php" );
			if ( $locate_template != '' ) {
				load_template( $locate_template, false );
			} else {
				//require FILTERABLE_PORTFOLIO_TEMPLATES . '/filterable_portfolio.php';
				$this->portfolio_items( $attributes );
			}
			$html = ob_get_contents();
			ob_end_clean();

			return apply_filters( 'filterable_portfolio', $html, $portfolios, $categories );
		}

		/**
		 * Get shortcode filter buttons
		 *
		 * @param array $attributes
		 * @param WP_Term[] $terms
		 *
		 * @return string|void
		 */
		public function filter_buttons( $attributes, $terms ) {
			$show_filter = in_array( $attributes['show_filter'], [ 'yes', 'on', 'true', true, 1 ], true );
			if ( count( $terms ) < 2 || ! $show_filter ) {
				return;
			}
			$option          = Filterable_Portfolio_Helper::get_options();
			$all_button_text = esc_html( $option['all_categories_text'] );

			$html = '<div class="filterable-portfolio__terms is-justify-end">';
			$html .= '<button class="button is-active" data-filter="*">' . $all_button_text . '</button>';
			foreach ( $terms as $term ) {
				$html .= sprintf( "<button class='button' data-filter='.%s'>%s</button>",
					esc_attr( $term->slug ), esc_html( $term->name ) );
			}
			$html .= '</div>';

			return $html;
		}

		/**
		 * Get portfolio items
		 *
		 * @param array $attributes
		 */
		public function portfolio_items( $attributes ) {
			$args = [];

			$featured = in_array( $attributes['featured'], [ 'yes', 'on', 'true', true, 1 ], true );
			if ( $featured ) {
				$args['featured'] = $featured;
			}
			$portfolios = Filterable_Portfolio_Helper::get_portfolios( $args );
			$categories = Filterable_Portfolio_Helper::get_categories_from_portfolios( $portfolios );

			$option      = Filterable_Portfolio_Helper::get_options();
			$theme       = in_array( $option['portfolio_theme'], array(
				'one',
				'two'
			) ) ? $option['portfolio_theme'] : 'one';
			$items_class = 'grids portfolio-items';
			$items_class .= ' fp-theme-' . $theme;
			?>
            <div id="filterable-portfolio" class="filterable-portfolio">
				<?php echo $this->filter_buttons( $attributes, $categories ); ?>
                <div id="portfolio-items" class="<?php echo $items_class; ?>">
					<?php
					$temp_post = $GLOBALS['post'];
					foreach ( $portfolios as $portfolio ) {
						setup_postdata( $portfolio );
						$GLOBALS['post'] = $portfolio;
						do_action( 'filterable_portfolio_loop_post', $portfolio );
					}
					wp_reset_postdata();
					$GLOBALS['post'] = $temp_post;
					?>
                </div>
            </div>
			<?php
		}

		/**
		 * Portfolio loop post content
		 */
		public function portfolio_item() {
			$template = FILTERABLE_PORTFOLIO_TEMPLATES . '/content-portfolio.php';
			load_template( $template, false );
		}
	}
}
