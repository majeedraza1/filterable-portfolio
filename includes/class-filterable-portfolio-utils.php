<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

class Filterable_Portfolio_Utils {

	/**
	 * Post type
	 */
	const POST_TYPE = 'portfolio';

	/**
	 * Portfolio category
	 */
	const CATEGORY = 'portfolio_cat';

	/**
	 * Portfolio skill
	 */
	const SKILL = 'portfolio_skill';

	/**
	 * Get all portfolios
	 *
	 * @return \WP_Post[]
	 */
	public static function get_portfolios() {
		$options        = get_option( 'filterable_portfolio' );
		$posts_per_page = isset( $options['posts_per_page'] ) ? intval( $options['posts_per_page'] ) : - 1;
		$orderby        = isset( $options['orderby'] ) ? esc_attr( $options['orderby'] ) : 'ID';
		$order          = isset( $options['order'] ) ? esc_attr( $options['order'] ) : 'DESC';


		$args = array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'orderby'        => $orderby,
			'order'          => $order,
		);

		return get_posts( $args );
	}

	/**
	 * Get related portfolios
	 *
	 * @param int|\WP_Post|null $post Post ID or post object. Defaults to global $post.
	 *
	 * @return \WP_Post[] List of posts.
	 */
	public static function get_related_portfolios( $post = null ) {
		$post       = get_post( $post );
		$categories = get_the_terms( $post->ID, self::CATEGORY );
		$skills     = get_the_terms( $post->ID, self::SKILL );

		$options  = get_option( 'filterable_portfolio' );
		$per_page = isset( $options['related_projects_number'] ) ? intval( $options['related_projects_number'] ) : 3;

		$args = array(
			'post_type'      => self::POST_TYPE,
			'posts_per_page' => $per_page,
			'post__not_in'   => array( $post->ID ),
			'tax_query'      => array( 'relation' => 'OR' )
		);

		if ( is_array( $categories ) ) {
			$category_ids = wp_list_pluck( $categories, 'term_id' );

			$args['tax_query'][] = array( 'taxonomy' => self::CATEGORY, 'field' => 'id', 'terms' => $category_ids );
		}

		if ( is_array( $skills ) ) {
			$skill_ids = wp_list_pluck( $skills, 'term_id' );

			$args['tax_query'][] = array( 'taxonomy' => self::SKILL, 'field' => 'id', 'terms' => $skill_ids );
		}

		return get_posts( $args );
	}

	/**
	 * Get portfolio categories
	 *
	 * @return array|\WP_Term[]
	 */
	public static function get_portfolio_categories() {
		$terms = get_terms( array(
			'taxonomy'   => self::CATEGORY,
			'hide_empty' => true,
		) );

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return count( $terms ) ? $terms : array();
	}
}
