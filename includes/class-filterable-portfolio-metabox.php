<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
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

				add_action( 'add_meta_boxes', array( self::$instance, 'add_meta_box' ) );
				add_action( 'save_post', array( self::$instance, 'save_meta_boxes' ) );
			}

			return self::$instance;
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

			$data = $_POST['filterable_portfolio_meta'] ?? [];

			foreach ( $data as $key => $val ) {
				update_post_meta( $post_id, $key, stripslashes( htmlspecialchars( $val ) ) );
			}

			$settings = Filterable_Portfolio_Helper::get_options();
			if ( in_array( $settings['project_date_as_post_date'], [ 1, '1', 'on', 'yes', 'true', true ], true ) ) {
				$date     = $data['_project_date'] ?? '';
				$datetime = date( 'Y-m-d H:i:s', strtotime( $date ) );
				if ( $datetime ) {
					wp_update_post( [
						'ID'            => $post_id,
						'post_date'     => $datetime,
						'post_date_gmt' => $datetime,
					] );
				}
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
					'_project_images'      => array(
						'name' => __( 'Project Images', 'filterable-portfolio' ),
						'desc' => __( 'Choose project images.', 'filterable-portfolio' ),
						'id'   => '_project_images',
						'type' => 'images',
						'std'  => '',
					),
					'_client_name'         => array(
						'name' => __( 'Client Name', 'filterable-portfolio' ),
						'desc' => __( 'Enter the client name of the project', 'filterable-portfolio' ),
						'id'   => '_client_name',
						'type' => 'text',
						'std'  => ''
					),
					'_project_date'        => array(
						'name' => __( 'Project Date', 'filterable-portfolio' ),
						'desc' => __( 'Choose the project date.', 'filterable-portfolio' ),
						'id'   => '_project_date',
						'type' => 'date',
						'std'  => '',
					),
					'_project_url'         => array(
						'name' => __( 'Project URL', 'filterable-portfolio' ),
						'desc' => __( 'Enter the project URL', 'filterable-portfolio' ),
						'id'   => '_project_url',
						'type' => 'text',
						'std'  => ''
					),
					'_is_featured_project' => array(
						'name' => __( 'Featured project', 'filterable-portfolio' ),
						'desc' => __( 'Check this if you want to mark this project as featured.', 'filterable-portfolio' ),
						'id'   => '_is_featured_project',
						'type' => 'checkbox',
						'std'  => 'no'
					),
				) ),
			) );
			$meta_box = new Filterable_Portfolio_MetaBox_API();
			$meta_box->add( $args );
		}
	}
}
