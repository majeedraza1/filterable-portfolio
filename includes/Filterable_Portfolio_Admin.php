<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio_Admin' ) ) {

	class Filterable_Portfolio_Admin {

		protected static $instance;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @since 1.2.3
		 * @return Filterable_Portfolio_Admin
		 */
		public static function init() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'filterable_portfolio_activation', array( $this, 'post_type' ) );
			add_action( 'filterable_portfolio_activation', array( $this, 'taxonomy' ) );
			add_action( 'init', array( $this, 'post_type' ) );
			add_action( 'init', array( $this, 'taxonomy' ) );
		}

		/**
		 * Create portfolio post type
		 *
		 * @return void
		 */
		public function post_type() {
			$labels = apply_filters( 'filterable_portfolio_labels', array(
				'name'               => __( 'Portfolios', 'filterable-portfolio' ),
				'singular_name'      => __( 'Portfolio', 'filterable-portfolio' ),
				'menu_name'          => __( 'Portfolios', 'filterable-portfolio' ),
				'parent_item_colon'  => __( 'Parent Portfolio:', 'filterable-portfolio' ),
				'all_items'          => __( 'All Portfolios', 'filterable-portfolio' ),
				'view_item'          => __( 'View Portfolio', 'filterable-portfolio' ),
				'add_new_item'       => __( 'Add New Portfolio', 'filterable-portfolio' ),
				'add_new'            => __( 'Add New', 'filterable-portfolio' ),
				'edit_item'          => __( 'Edit Portfolio', 'filterable-portfolio' ),
				'update_item'        => __( 'Update Portfolio', 'filterable-portfolio' ),
				'search_items'       => __( 'Search Portfolio', 'filterable-portfolio' ),
				'not_found'          => __( 'Not found', 'filterable-portfolio' ),
				'not_found_in_trash' => __( 'Not found in Trash', 'filterable-portfolio' ),
			) );

			$args = array(
				'label'               => __( 'Portfolios', 'filterable-portfolio' ),
				'description'         => __( 'A WordPress filterable portfolio to display portfolio images or gallery to your site.',
					'filterable-portfolio' ),
				'labels'              => $labels,
				'supports'            => apply_filters( 'filterable_portfolio_supports', array(
					'title',
					'editor',
					'thumbnail',
					'comments',
					'revisions'
				) ),
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
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'rewrite'             => array(
					'slug'       => 'portfolio',
					'with_front' => false,
				),
				'capability_type'     => 'page',
			);
			register_post_type( 'portfolio', apply_filters( 'filterable_portfolio_post_type_args', $args ) );
		}

		/**
		 * Create two taxonomies "portfolio_cat" and "portfolio_skill"
		 * for the post type "portfolio"
		 *
		 * @return void
		 */
		public function taxonomy() {
			register_taxonomy( 'portfolio_cat', 'portfolio', array(
				'label'             => __( 'Categories', 'filterable-portfolio' ),
				'singular_label'    => __( 'Category', 'filterable-portfolio' ),
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'args'              => array( 'orderby' => 'term_order' ),
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'portfolio-category', 'hierarchical' => true ),
			) );

			register_taxonomy( 'portfolio_skill', 'portfolio', array(
				'label'             => __( 'Skills', 'filterable-portfolio' ),
				'singular_label'    => __( 'Skill', 'filterable-portfolio' ),
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'args'              => array( 'orderby' => 'term_order' ),
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'portfolio-skill', 'hierarchical' => true ),
			) );
		}
	}
}

Filterable_Portfolio_Admin::init();
