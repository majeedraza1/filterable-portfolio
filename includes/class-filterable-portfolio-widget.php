<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

class Filterable_Portfolio_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_filterable_portfolio',
			'description' => __( 'Display portfolio images with filtering.', 'filterable-portfolio' ),
		);
		parent::__construct( 'widget_filterable_portfolio', __( 'Filterable Portfolio', 'filterable-portfolio' ),
			$widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param  array  $args
	 * @param  array  $instance
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

		echo $args['before_widget'];// phpcs:ignore WordPress.Security.EscapeOutput

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];// phpcs:ignore WordPress.Security.EscapeOutput
		}

		echo do_shortcode( '[filterable_portfolio]' );
		echo $args['after_widget'];// phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param  array  $instance  The widget options
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

		printf( '<p><label for="%1$s">%2$s</label>', esc_attr( $this->get_field_id( 'title' ) ),
			esc_html__( 'Title (optional):', 'filterable-portfolio' ) );
		printf( '<input class="widefat" id="%1$s" name="%2$s" value="%3$s" /></p>',
			esc_attr( $this->get_field_id( 'title' ) ),
			esc_attr( $this->get_field_name( 'title' ) ), esc_attr( $title ) );
		printf( '<p><a target="_blank" href="%3$s">%1$s</a> %2$s</p>',
			esc_html__( 'Click here', 'filterable-portfolio' ),
			esc_html__( 'to change portfolio settings', 'filterable-portfolio' ),
			esc_url( admin_url( 'edit.php?post_type=portfolio&page=fp-settings' ) )
		);
	}

	/**
	 * Processing widget options on save
	 *
	 * @param  array  $new_instance  The new options
	 * @param  array  $old_instance  The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Register current class as widget
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}
}
