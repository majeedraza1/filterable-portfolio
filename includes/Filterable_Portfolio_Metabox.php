<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( !class_exists('Filterable_Portfolio_Metabox') ):

class Filterable_Portfolio_Metabox {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
		add_action( 'wp_ajax_fp_save_images', array( $this, 'save_images' ) );
	}

	/**
	 * Save custom meta box
	 *
	 * @param int $post_id The post ID
	 */
	public function save_meta_boxes( $post_id )
	{
		if( ! isset($_POST['_fp_nonce']) ){
			return;
		}

		if ( !wp_verify_nonce( $_POST['_fp_nonce'], 'filterable_portfolio_nonce' )) {
			return;
		}

		// Check if user has permissions to save data.
		if ( !current_user_can( 'edit_post', $post_id ) ){
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

        if ( ! isset($_POST['filterable_portfolio_meta']) ) {
        	return;
        }

		foreach( $_POST['filterable_portfolio_meta'] as $key => $val ){
			update_post_meta( $post_id, $key, stripslashes(htmlspecialchars($val)) );
		}
	}

	public function save_images()
	{
	    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'fp_ajax_nonce' ) ) {
	        return;
	    }

		if ( ! isset( $_POST['post_id'], $_POST['ids'] ) ) {
			return;
		}

		$post_id = $_POST['post_id'];
		// Check if user has permissions to save data.
		if ( !current_user_can( 'edit_post', $post_id ) ){
			return;
		}
		// Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

	    $ids = strip_tags(rtrim($_POST['ids'], ','));

	    $thumbs_output = '';
	    foreach( explode(',', $ids) as $thumb ) {
	        $thumbs_output .= sprintf(
	        	'<li>%s</li>',
	        	wp_get_attachment_image( $thumb, array( 75, 75 ) )
	        );
	    }
	    echo $thumbs_output;
	    wp_die();
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		$meta_box = array(
		    'id' => 'filterable-portfolio-metabox',
		    'title' => __('Portfolio Settings', 'filterable-portfolio'),
		    'description' => __('Here you can customize your project details.', 'filterable-portfolio'),
		    'screen' => 'portfolio',
		    'context' => 'normal',
		    'priority' => 'high',
		    'fields' => array(
		        array(
		            'name' => __('Project Images', 'filterable-portfolio'),
		            'desc' => __('Choose project images.', 'filterable-portfolio'),
		            'id' => '_project_images',
		            'type' => 'images',
		            'std' => __('Upload Images', 'filterable-portfolio')
		        ),
		        array(
		            'name' => __('Client Name', 'filterable-portfolio'),
		            'desc' => __('Enter the client name of the project', 'filterable-portfolio'),
		            'id' => '_client_name',
		            'type' => 'text',
		            'std' => ''
		        ),
		        array(
		            'name' => __('Project Date', 'filterable-portfolio'),
		            'desc' => __('Choose the project date.', 'filterable-portfolio'),
		            'id' => '_project_date',
		            'type' => 'date',
		            'std' => '',
		        ),
		        array(
		            'name' => __('Project URL', 'filterable-portfolio'),
		            'desc' => __('Enter the project URL', 'filterable-portfolio'),
		            'id' => '_project_url',
		            'type' => 'text',
		            'std' => ''
		        ),
		    )
		);
		$ShaplaTools_Metaboxs = new ShaplaTools_Meta_Box;
		$ShaplaTools_Metaboxs->add($meta_box);
	}
}
endif;

new Filterable_Portfolio_Metabox;