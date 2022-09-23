<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package filterable_portfolio
 */

get_header( 'portfolio' );

/**
 * Hook: filterable_portfolio/before_main_content.
 */
do_action( 'filterable_portfolio/before_main_content' );


/**
 * Hook: filterable_portfolio/loop_before.
 */
do_action( 'filterable_portfolio/loop_before' );

/**
 * Hook: filterable_portfolio/loop.
 */
do_action( 'filterable_portfolio/loop' );


/**
 * Hook: filterable_portfolio/loop_after.
 */
do_action( 'filterable_portfolio/loop_after' );

/**
 * Hook: filterable_portfolio/after_main_content.
 */
do_action( 'filterable_portfolio/after_main_content' );

get_sidebar( 'portfolio' );
get_footer( 'portfolio' );
