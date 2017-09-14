<?php
/*
Plugin Name: Filterable Portfolio
Plugin URI:  https://wordpress.org/plugins/filterable-portfolio/
Description: A WordPress Filterable Portfolio to display portfolio images or gallery to your site.
Author: Sayful Islam
Version: 1.0.2
Author URI: http://sayful.net/
License: GPLv2 or later
*/

/**
 * Load plugin textdomain.
 */
function filterableportfolio_load_textdomain() {
  load_plugin_textdomain( 'filterableportfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'filterableportfolio_load_textdomain' );

/* Adding Latest jQuery for Wordpress plugin */
function filterableportfolio_plugin_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('filterableportfolio-prettyPhoto',plugins_url( '/js/prettyPhoto.js' , __FILE__ ),array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script('filterableportfolio-filterable',plugins_url( '/js/filterable.min.js' , __FILE__ ),array( 'jquery' ), '1.0.0', true );
    
    wp_enqueue_style('filterableportfolio-style',plugins_url( '/css/style.css' , __FILE__ ), array(), '1.0.0', 'all');
    wp_enqueue_style('filterableportfolio-prettyPhoto',plugins_url( '/css/prettyPhoto.css' , __FILE__ ), array(), '1.0.0', 'all');
    wp_enqueue_style('filterableportfolio-fontawesome',plugins_url( '/css/fontawesome.css' , __FILE__ ), array(), '1.0.0', 'all');
}
add_action('init', 'filterableportfolio_plugin_scripts');

// Register Custom Post Type
function filterableportfolio_custom_post_type() {

    $labels = array(
        'name'                => _x( 'Portfolios', 'Post Type General Name', 'filterableportfolio' ),
        'singular_name'       => _x( 'Portfolio', 'Post Type Singular Name', 'filterableportfolio' ),
        'menu_name'           => __( 'Portfolios', 'filterableportfolio' ),
        'parent_item_colon'   => __( 'Parent Portfolio:', 'filterableportfolio' ),
        'all_items'           => __( 'All Portfolios', 'filterableportfolio' ),
        'view_item'           => __( 'View Portfolio', 'filterableportfolio' ),
        'add_new_item'        => __( 'Add New Portfolio', 'filterableportfolio' ),
        'add_new'             => __( 'Add New', 'filterableportfolio' ),
        'edit_item'           => __( 'Edit Portfolio', 'filterableportfolio' ),
        'update_item'         => __( 'Update Portfolio', 'filterableportfolio' ),
        'search_items'        => __( 'Search Portfolio', 'filterableportfolio' ),
        'not_found'           => __( 'Not found', 'filterableportfolio' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'filterableportfolio' ),
    );
    $args = array(
        'label'               => __( 'portfolios', 'filterableportfolio' ),
        'description'         => __( 'Post Type Description goes here.', 'filterableportfolio' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'comments' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-portfolio',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'rewrite'             => false,
        'capability_type'     => 'post',
    );
    register_post_type( 'portfolio', $args );

}

// Hook into the 'init' action
add_action( 'init', 'filterableportfolio_custom_post_type', 0 );

// Register Custom Taxonomy
function filterableportfolio_custom_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Portfolio Categories', 'Taxonomy General Name', 'filterableportfolio' ),
        'singular_name'              => _x( 'Portfolio Category', 'Taxonomy Singular Name', 'filterableportfolio' ),
        'menu_name'                  => __( 'Portfolio Categories', 'filterableportfolio' ),
        'all_items'                  => __( 'All Portfolio Categories', 'filterableportfolio' ),
        'parent_item'                => __( 'Parent Portfolio Category', 'filterableportfolio' ),
        'parent_item_colon'          => __( 'Parent Portfolio Category:', 'filterableportfolio' ),
        'new_item_name'              => __( 'New Portfolio Category Name', 'filterableportfolio' ),
        'add_new_item'               => __( 'Add New Portfolio Category', 'filterableportfolio' ),
        'edit_item'                  => __( 'Edit Portfolio Category', 'filterableportfolio' ),
        'update_item'                => __( 'Update Portfolio Category', 'filterableportfolio' ),
        'separate_items_with_commas' => __( 'Separate Portfolio Categories with commas', 'filterableportfolio' ),
        'search_items'               => __( 'Search Portfolio Categories', 'filterableportfolio' ),
        'add_or_remove_items'        => __( 'Add or remove Portfolio Categories', 'filterableportfolio' ),
        'choose_from_most_used'      => __( 'Choose from the most used Portfolio Categories', 'filterableportfolio' ),
        'not_found'                  => __( 'Not Found', 'filterableportfolio' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => array( 'slug' => 'portfolio-category', ),
    );
    register_taxonomy( 'portfolio_cat', array( 'portfolio' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'filterableportfolio_custom_taxonomy', 0 );

/**
 * Adds custom image size for portfolio Thumbnail
 */
add_image_size( 'portfolio-thumb', 640, 360, true );
/**
 * Adds a box to the custom post Portfolio edit screens.
 */


/* Move featured image box under title */
function filterableportfolio_image_box()
{
    remove_meta_box( 'postimagediv', 'portfolio', 'side' );
    add_meta_box('postimagediv', __('Set Portfolio Image', 'filterableportfolio'), 'post_thumbnail_meta_box', 'portfolio', 'normal', 'high');
}
add_action('do_meta_boxes', 'filterableportfolio_image_box');


function filterableportfolio_add_meta_box() {
    add_meta_box( 'filterableportfolio_sectionid', __( 'Portfolio Meta Box','filterableportfolio' ), 'filterableportfolio_meta_box_callback', 'portfolio' );
}
add_action( 'add_meta_boxes', 'filterableportfolio_add_meta_box' );

function filterableportfolio_meta_box_callback( $post ) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'filterableportfolio_meta_box', 'filterableportfolio_meta_box_nonce' );

    //Use get_post_meta() to retrieve an existing value from the database and use the value for the form.
    $value = get_post_meta( $post->ID, 'filterableportfolio_post_live_link', true );

    // Creating form for 'filterableportfolio Meta Box'
    ?>
        <p>
            <label for="filterableportfolio_post_live_link"><?php _e('Portfolio External Link','filterableportfolio') ?></label>
            <input type="text" id="filterableportfolio_post_live_link" name="filterableportfolio_post_live_link" value="<?php echo $value; ?>" />
        </p>
    <?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
add_action( 'save_post', 'filterableportfolio_meta_box_save' );
function filterableportfolio_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['filterableportfolio_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['filterableportfolio_meta_box_nonce'], 'filterableportfolio_meta_box' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    // now we can actually save the data
    $allowed = array(
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );

    // Make sure your data is set before trying to save it
    if( isset( $_POST['filterableportfolio_post_live_link'] ) )
        update_post_meta( $post_id, 'filterableportfolio_post_live_link', wp_kses( $_POST['filterableportfolio_post_live_link'], $allowed ) );
}


include_once 'display-portfolio.php';