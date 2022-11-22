<?php

defined( 'ABSPATH' ) || exit;

/**
 * Filterable_Portfolio_Gutenberg_Block class.
 */
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

	/**
	 * Register block type
	 *
	 * @return void
	 */
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
				'isFeatured'        => [ 'type' => 'boolean', 'default' => false ],
				'showFilter'        => [ 'type' => 'boolean', 'default' => true ],
				'filterBy'          => [ 'type' => 'string', 'default' => 'categories' ],
				'orderBy'           => [
					'type'    => 'string',
					'default' => Filterable_Portfolio_Helper::get_option( 'orderby', 'ID' )
				],
				'order'             => [
					'type'    => 'string',
					'default' => Filterable_Portfolio_Helper::get_option( 'order', 'DESC' )
				],
				'limit'             => [
					'type'    => 'number',
					'default' => Filterable_Portfolio_Helper::get_option( 'posts_per_page', 100 )
				],
				'theme'             => [
					'type'    => 'string',
					'default' => Filterable_Portfolio_Helper::get_option( 'portfolio_theme' )
				],
				'buttonsAlignment'  => [
					'type'    => 'string',
					'default' => Filterable_Portfolio_Helper::get_option( 'filter_buttons_alignment' )
				],
				'columnsPhone'      => [ 'type' => 'number' ],
				'columnsTablet'     => [ 'type' => 'number' ],
				'columnsDesktop'    => [ 'type' => 'number' ],
				'columnsWidescreen' => [ 'type' => 'number' ],
			],
		) );
	}

	/**
	 * Render portfolio content
	 *
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
		$columnsPhone      = $this->get_responsive_class( $attributes, 'columnsPhone' );
		$columnsTablet     = $this->get_responsive_class( $attributes, 'columnsTablet' );
		$columnsDesktop    = $this->get_responsive_class( $attributes, 'columnsDesktop' );
		$columnsWidescreen = $this->get_responsive_class( $attributes, 'columnsWidescreen' );

		$args = [
			'featured'           => $featured ? 'yes' : 'no',
			'show_filter'        => $show_filter ? 'yes' : 'no',
			'filter_by'          => $attributes['filterBy'] ?? '',
			'responsive_classes' => [
				'columns_phone'   => $columnsPhone,
				'columns_tablet'  => $columnsTablet,
				'columns_desktop' => $columnsDesktop,
				'columns'         => $columnsWidescreen,
			],
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

		if ( isset( $attributes['order'], $attributes['orderBy'] ) ) {
			$args['order']   = sanitize_text_field( $attributes['order'] );
			$args['orderby'] = sanitize_text_field( $attributes['orderBy'] );
		}

		return Filterable_Portfolio_Shortcode::init()->shortcode( $args );
	}

	/**
	 * Get responsive class
	 *
	 * @param array $attributes The block attributes.
	 * @param string $name The attribute key.
	 *
	 * @return string
	 */
	public function get_responsive_class( $attributes, $name ) {
		$prefixes = [
			'columnsPhone'      => [ 'prefix' => 'xs', 'option' => 'columns_phone' ],
			'columnsTablet'     => [ 'prefix' => 's', 'option' => 'columns_tablet' ],
			'columnsDesktop'    => [ 'prefix' => 'm', 'option' => 'columns_desktop' ],
			'columnsWidescreen' => [ 'prefix' => 'l', 'option' => 'columns' ],
		];
		if ( ! empty( $attributes[ $name ] ) ) {
			return sprintf( '%s%s', $prefixes[ $name ]['prefix'], $attributes[ $name ] );
		}

		return Filterable_Portfolio_Helper::get_option( $prefixes[ $name ]['option'] );
	}
}
