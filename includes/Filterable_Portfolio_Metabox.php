<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio_Metabox' ) ) {

	class Filterable_Portfolio_Metabox {

		/**
		 * Instance of current class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * @return Filterable_Portfolio_Metabox
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Hook into the appropriate actions when the class is constructed.
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
		}

		/**
		 * Save custom meta box
		 *
		 * @param int $post_id The post ID
		 */
		public function save_meta_boxes( $post_id ) {
			if ( ! isset( $_POST['_fp_nonce'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST['_fp_nonce'], 'filterable_portfolio_nonce' ) ) {
				return;
			}

			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			// Check if not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			if ( ! isset( $_POST['filterable_portfolio_meta'] ) ) {
				return;
			}

			foreach ( $_POST['filterable_portfolio_meta'] as $key => $val ) {
				update_post_meta( $post_id, $key, stripslashes( htmlspecialchars( $val ) ) );
			}
		}

		/**
		 * Adds the meta box container.
		 */
		public function add_meta_box() {
			$args     = apply_filters( 'filterable_portfolio_meta_box', array(
				'id'          => 'filterable-portfolio-metabox',
				'title'       => __( 'Portfolio Settings', 'filterable-portfolio' ),
				'description' => __( 'Here you can customize your project details.', 'filterable-portfolio' ),
				'screen'      => 'portfolio',
				'context'     => 'normal',
				'priority'    => 'high',
				'fields'      => apply_filters( 'filterable_portfolio_meta_box_fields', array(
					'_project_images' => array(
						'name' => __( 'Project Images', 'filterable-portfolio' ),
						'desc' => __( 'Choose project images.', 'filterable-portfolio' ),
						'id'   => '_project_images',
						'type' => 'images',
						'std'  => '',
					),
					'_client_name'    => array(
						'name' => __( 'Client Name', 'filterable-portfolio' ),
						'desc' => __( 'Enter the client name of the project', 'filterable-portfolio' ),
						'id'   => '_client_name',
						'type' => 'text',
						'std'  => ''
					),
					'_project_date'   => array(
						'name' => __( 'Project Date', 'filterable-portfolio' ),
						'desc' => __( 'Choose the project date.', 'filterable-portfolio' ),
						'id'   => '_project_date',
						'type' => 'date',
						'std'  => '',
					),
					'_project_url'    => array(
						'name' => __( 'Project URL', 'filterable-portfolio' ),
						'desc' => __( 'Enter the project URL', 'filterable-portfolio' ),
						'id'   => '_project_url',
						'type' => 'text',
						'std'  => ''
					),
				) ),
			) );
			$meta_box = new Filterable_Portfolio_MetaBox_API();
			$meta_box->add( $args );
		}
	}
}
