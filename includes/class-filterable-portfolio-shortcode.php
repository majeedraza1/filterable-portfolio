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
				add_action( 'filterable_portfolio_loop_post', array( self::$instance, 'portfolio_loop_item' ) );
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
				'featured'           => 'no',
				'show_filter'        => 'yes',
				'filter_by'          => 'categories',
				'responsive_classes' => [],
				'theme'              => Filterable_Portfolio_Helper::get_option( 'portfolio_theme', 'two' ),
				'buttons_alignment'  => Filterable_Portfolio_Helper::get_option( 'filter_buttons_alignment', 'end' ),
				'per_page'           => Filterable_Portfolio_Helper::get_option( 'posts_per_page', 100 ),
				'orderby'            => Filterable_Portfolio_Helper::get_option( 'orderby', 'ID' ),
				'order'              => Filterable_Portfolio_Helper::get_option( 'order', 'DESC' ),
			], $attributes, 'filterable_portfolio' );

			wp_enqueue_script( 'filterable-portfolio' );

			$args = [
				'orderby' => $attributes['orderby'],
				'order'   => $attributes['order'],
			];

			if ( isset( $attributes['per_page'] ) && is_numeric( $attributes['per_page'] ) ) {
				$args['per_page'] = $attributes['per_page'];
			}

			$featured = in_array( $attributes['featured'], [ 'yes', 'on', 'true', true, 1 ], true );
			if ( $featured ) {
				$args['featured'] = true;
			}
			$filter_by = in_array( $attributes['filter_by'], [ 'categories', 'skills' ], true ) ?
				$attributes['filter_by'] : 'categories';

			$portfolios = Filterable_Portfolio_Helper::get_portfolios( $args );
			if ( 'skills' === $filter_by ) {
				$categories = Filterable_Portfolio_Helper::get_skills_from_portfolios( $portfolios );
			} else {
				$categories = Filterable_Portfolio_Helper::get_categories_from_portfolios( $portfolios );
			}

			ob_start();
			$locate_template = locate_template( "filterable_portfolio.php" );
			if ( $locate_template != '' ) {
				load_template( $locate_template, false );
			} else {
				$this->portfolio_items( $attributes, $portfolios, $categories );
			}
			$html = ob_get_clean();

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
			$buttons_alignment = in_array( $attributes['buttons_alignment'], [ 'start', 'center', 'end' ], true ) ?
				$attributes['buttons_alignment'] : 'end';
			$all_button_text   = Filterable_Portfolio_Helper::get_option( 'all_categories_text' );

			$html = '<div class="filterable-portfolio__terms is-justify-' . esc_attr( $buttons_alignment ) . '">';
			$html .= '<button class="button is-active" data-filter="*">' . esc_html( $all_button_text ) . '</button>';
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
		 * @param array $attributes Setting attributes.
		 * @param array $portfolios List of WP_Post object.
		 * @param array $categories List of WP_Term object.
		 */
		public function portfolio_items( array $attributes, $portfolios = [], $categories = [] ) {
			$theme       = in_array( $attributes['theme'], [ 'one', 'two' ] ) ? $attributes['theme'] : 'one';
			$items_class = 'grids portfolio-items';
			$items_class .= ' fp-theme-' . $theme;
			?>
			<div id="filterable-portfolio" class="filterable-portfolio">
				<?php echo $this->filter_buttons( $attributes, $categories ); ?>
				<div id="portfolio-items" class="<?php echo $items_class; ?>">
					<?php
					$GLOBALS['filterable_portfolio_attributes'] = $attributes;
					$temp_post                                  = $GLOBALS['post'];
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
		public function portfolio_loop_item() {
			load_template( FILTERABLE_PORTFOLIO_TEMPLATES . '/content-portfolio.php', false );
		}
	}
}
