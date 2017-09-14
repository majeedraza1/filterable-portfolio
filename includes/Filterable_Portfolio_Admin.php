<?php
if( ! class_exists('Filterable_Portfolio_Admin') ):

class Filterable_Portfolio_Admin
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'post_type' ), 0 );
		add_action( 'init', array( $this, 'taxonomy' ), 0 );
	}

	public static function post_type()
	{
	    $labels = array(
	        'name'                => __( 'Portfolios', 'filterable-portfolio' ),
	        'singular_name'       => __( 'Portfolio', 'filterable-portfolio' ),
	        'menu_name'           => __( 'Portfolios', 'filterable-portfolio' ),
	        'parent_item_colon'   => __( 'Parent Portfolio:', 'filterable-portfolio' ),
	        'all_items'           => __( 'All Portfolios', 'filterable-portfolio' ),
	        'view_item'           => __( 'View Portfolio', 'filterable-portfolio' ),
	        'add_new_item'        => __( 'Add New Portfolio', 'filterable-portfolio' ),
	        'add_new'             => __( 'Add New', 'filterable-portfolio' ),
	        'edit_item'           => __( 'Edit Portfolio', 'filterable-portfolio' ),
	        'update_item'         => __( 'Update Portfolio', 'filterable-portfolio' ),
	        'search_items'        => __( 'Search Portfolio', 'filterable-portfolio' ),
	        'not_found'           => __( 'Not found', 'filterable-portfolio' ),
	        'not_found_in_trash'  => __( 'Not found in Trash', 'filterable-portfolio' ),
	    );
	    $args = array(
	        'label'               => __( 'Portfolios', 'filterable-portfolio' ),
	        'description'         => __( 'A WordPress filterable portfolio to display portfolio images or gallery to your site.', 'filterable-portfolio' ),
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
	        'rewrite'             => array(
	        	'slug' => 'portfolio',
	        	'with_front' => false,
	        ),
	        'capability_type'     => 'post',
	    );
	    register_post_type( 'portfolio', $args );
	}

	public static function taxonomy()
	{
		$labels = array(
	        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'filterable-portfolio' ),
	        'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'filterable-portfolio' ),
	        'menu_name'                  => __( 'Categories', 'filterable-portfolio' ),
	        'all_items'                  => __( 'All Categories', 'filterable-portfolio' ),
	        'parent_item'                => __( 'Parent Category', 'filterable-portfolio' ),
	        'parent_item_colon'          => __( 'Parent Category:', 'filterable-portfolio' ),
	        'new_item_name'              => __( 'New Category Name', 'filterable-portfolio' ),
	        'add_new_item'               => __( 'Add New Category', 'filterable-portfolio' ),
	        'edit_item'                  => __( 'Edit Category', 'filterable-portfolio' ),
	        'update_item'                => __( 'Update Category', 'filterable-portfolio' ),
	        'separate_items_with_commas' => __( 'Separate Categories with commas', 'filterable-portfolio' ),
	        'search_items'               => __( 'Search Categories', 'filterable-portfolio' ),
	        'add_or_remove_items'        => __( 'Add or remove Categories', 'filterable-portfolio' ),
	        'choose_from_most_used'      => __( 'Choose from the most used Categories', 'filterable-portfolio' ),
	        'not_found'                  => __( 'Not Found', 'filterable-portfolio' ),
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
}

endif;