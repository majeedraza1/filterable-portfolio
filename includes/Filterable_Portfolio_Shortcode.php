<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio_Shortcode' ) ) {

	class Filterable_Portfolio_Shortcode {

		/**
		 * Plugin options
		 *
		 * @var array
		 */
		private $options = array();

		/**
		 * Filterable_Portfolio_Shortcode constructor.
		 *
		 * @param $options
		 */
		public function __construct( $options ) {
			$this->options = $options;

			add_shortcode( 'filterable_portfolio', array( $this, 'shortcode' ) );
		}

		/**
		 * Filterable Portfolio shortcode.
		 *
		 * @return mixed
		 */
		public function shortcode() {

			$portfolios = $this->get_portfolios();
			$terms      = get_terms( "portfolio_cat" );
			$_theme     = $this->options['portfolio_theme'];
			$image_size = $this->options['image_size'];
			$grid       = sprintf( 'grid %1$s %2$s %3$s %4$s', $this->options['columns_phone'],
				$this->options['columns_tablet'], $this->options['columns_desktop'], $this->options['columns'] );

			ob_start();
			if ( locate_template( "filterable_portfolio.php" ) != '' ) {
				require get_stylesheet_directory() . '/filterable_portfolio.php';
			} else {
				if ( $this->options['portfolio_filter_script'] == 'isotope' ) {
					wp_enqueue_script( 'isotope' );
					wp_enqueue_script( 'filterable-portfolio' );
					require FILTERABLE_PORTFOLIO_TEMPLATES . '/filterable_portfolio-isotope.php';
				} else {
					wp_enqueue_script( 'shuffle' );
					wp_enqueue_script( 'filterable-portfolio' );
					require FILTERABLE_PORTFOLIO_TEMPLATES . '/filterable_portfolio-shuffle.php';
				}
			}
			$html = ob_get_contents();
			ob_end_clean();

			return apply_filters( 'filterable_portfolio', $html, $portfolios, $terms );
		}

		/**
		 * Get all portfolios
		 *
		 * @return object
		 */
		public function get_portfolios() {
			$posts_per_page = isset( $this->options['posts_per_page'] ) ? intval( $this->options['posts_per_page'] ) : - 1;
			$orderby        = isset( $this->options['orderby'] ) ? esc_attr( $this->options['orderby'] ) : 'ID';
			$order          = isset( $this->options['order'] ) ? esc_attr( $this->options['order'] ) : 'DESC';

			$args = array(
				'post_type'      => 'portfolio',
				'post_status'    => 'publish',
				'posts_per_page' => $posts_per_page,
				'orderby'        => $orderby,
				'order'          => $order,
			);

			$portfolios = get_posts( $args );

			$portfolios = array_map( function ( $portfolio ) {

				$thumb_id = intval( get_post_thumbnail_id( $portfolio->ID ) );

				if ( ! $thumb_id ) {
					return array();
				}

				$terms = get_the_terms( $portfolio->ID, 'portfolio_cat' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$terms = array_map( function ( $term ) {
						return $term->slug;
					}, $terms );
				}

				$terms = $terms ? json_encode( $terms ) : json_encode( array() );

				return array(
					'id'        => $portfolio->ID,
					'title'     => esc_attr( $portfolio->post_title ),
					'permalink' => esc_url( get_permalink( $portfolio->ID ) ),
					'thumb_id'  => intval( get_post_thumbnail_id( $portfolio->ID ) ),
					'modified'  => $portfolio->post_modified,
					'created'   => $portfolio->post_date,
					'excerpt'   => wp_trim_words( strip_tags( $portfolio->post_content ), '19', ' ...' ),
					'terms'     => $terms,
				);
			}, $portfolios );

			$portfolios = array_filter( $portfolios );

			return json_decode( json_encode( $portfolios ), false );
		}
	}
}
