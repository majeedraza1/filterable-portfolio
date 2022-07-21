<?php

defined( 'ABSPATH' ) || exit;

class Filterable_Portfolio_Gutenberg_Block {
	/**
	 * Instance of current class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Only one instance of the class can be loaded
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'init', [ self::$instance, 'register_block_type' ] );
		}

		return self::$instance;
	}

	public function register_block_type() {
		wp_register_script(
			'filterable-portfolio-block',
			FILTERABLE_PORTFOLIO_ASSETS . '/js/block.js',
			[ 'wp-blocks', 'wp-components', 'wp-block-editor', 'wp-i18n', 'wp-element', 'wp-server-side-render' ],
			FILTERABLE_PORTFOLIO_VERSION
		);

		register_block_type( 'filterable-portfolio/projects', array(
			'api_version'     => 2,
			'editor_script'   => 'filterable-portfolio-block',
			'script'          => 'filterable-portfolio',
			'style'           => 'filterable-portfolio',
			'render_callback' => [ $this, 'portfolio_dynamic_render_callback' ],
			'attributes'      => [
				'isFeatured'       => [ 'type' => 'boolean', 'default' => false ],
				'showFilter'       => [ 'type' => 'boolean', 'default' => true ],
				'limit'            => [
					'type'    => 'number',
					'default' => Filterable_Portfolio_Helper::get_option( 'posts_per_page', 100 )
				],
				'theme'            => [
					'type'    => 'string',
					'default' => Filterable_Portfolio_Helper::get_option( 'portfolio_theme' )
				],
				'buttonsAlignment' => [
					'type'    => 'string',
					'default' => Filterable_Portfolio_Helper::get_option( 'filter_buttons_alignment' )
				],
			],
		) );
	}

	/**
	 * @param array $attributes The block attributes.
	 * @param string $content The block content.
	 *
	 * @return string Returns the block content.
	 */
	public function portfolio_dynamic_render_callback( $attributes, $content ) {
		$featured          = in_array( $attributes['isFeatured'], [ 'true', true, 1, '1', 'yes', 'on' ], true );
		$show_filter       = in_array( $attributes['showFilter'], [ 'true', true, 1, '1', 'yes', 'on' ], true );
		$theme             = $attributes['theme'] ?? '';
		$buttons_alignment = $attributes['buttonsAlignment'] ?? '';

		$args = [
			'featured'    => $featured ? 'yes' : 'no',
			'show_filter' => $show_filter ? 'yes' : 'no',
		];
		if ( in_array( $theme, [ 'one', 'two' ], true ) ) {
			$args['theme'] = $theme;
		}
		if ( in_array( $buttons_alignment, [ 'start', 'center', 'end' ], true ) ) {
			$args['buttons_alignment'] = $buttons_alignment;
		}

		if ( $attributes['limit'] && is_numeric( $attributes['limit'] ) ) {
			$args['per_page'] = intval( $attributes['limit'] );
		}

		return Filterable_Portfolio_Shortcode::init()->shortcode( $args );
	}
}
