<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

class Filterable_Portfolio_Helper {

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
	 * Plugin options
	 *
	 * @var array
	 */
	protected static $options = [];

	/**
	 * Get plugin options
	 *
	 * @return array
	 */
	public static function get_options() {
		if ( empty( static::$options ) ) {
			$defaults        = [
				'columns'                       => 'l4',
				'columns_desktop'               => 'm4',
				'columns_tablet'                => 's6',
				'columns_phone'                 => 'xs12',
				'portfolio_theme'               => 'two',
				'image_size'                    => 'filterable-portfolio',
				'button_color'                  => '#4cc1be',
				'filter_buttons_alignment'      => 'end',
				'orderby'                       => 'ID',
				'order'                         => 'DESC',
				'posts_per_page'                => 100,
				'project_date_as_post_date'     => 0,
				'all_categories_text'           => __( 'All', 'filterable-portfolio' ),
				'details_button_text'           => __( 'Details', 'filterable-portfolio' ),
				'project_image_size'            => 'full',
				'category_disable_archive_link' => 0,
				'skill_disable_archive_link'    => 0,
				'show_related_projects'         => 1,
				'related_projects_number'       => 4,
				'project_description_text'      => __( 'Project Description', 'filterable-portfolio' ),
				'project_details_text'          => __( 'Project Details', 'filterable-portfolio' ),
				'project_skills_text'           => __( 'Skills Needed:', 'filterable-portfolio' ),
				'project_categories_text'       => __( 'Categories:', 'filterable-portfolio' ),
				'project_url_text'              => __( 'Project URL:', 'filterable-portfolio' ),
				'project_date_text'             => __( 'Project Date:', 'filterable-portfolio' ),
				'project_client_text'           => __( 'Client:', 'filterable-portfolio' ),
				'related_projects_text'         => __( 'Related Projects', 'filterable-portfolio' ),
			];
			$options         = get_option( 'filterable_portfolio' );
			$options         = is_array( $options ) ? $options : [];
			static::$options = wp_parse_args( $options, $defaults );
		}

		return static::$options;
	}

	/**
	 * Get option
	 *
	 * @param string $key The option key.
	 * @param string $default Default value.
	 *
	 * @return false|mixed
	 */
	public static function get_option( $key, $default = false ) {
		$options = self::get_options();

		return $options[ $key ] ?? $default;
	}

	/**
	 * Get all portfolios
	 *
	 * @param array $args
	 *
	 * @return WP_Post[]
	 */
	public static function get_portfolios( $args = [] ) {
		$options         = static::get_options();
		$portfolios_args = array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => intval( $options['posts_per_page'] ),
			'orderby'        => $args['orderby'] ? $args['orderby'] : $options['orderby'],
			'order'          => $args['order'] ? $args['order'] : $options['order'],
		);

		if ( isset( $args['per_page'] ) && is_numeric( $args['per_page'] ) ) {
			$portfolios_args['posts_per_page'] = intval( $args['per_page'] );
		}

		if ( isset( $args['page'] ) && is_numeric( $args['page'] ) ) {
			$portfolios_args['paged'] = intval( $args['page'] );
		}

		if ( isset( $args['featured'] ) && $args['featured'] == true ) {
			$portfolios_args['meta_query'] = array(
				array(
					'key'   => '_is_featured_project',
					'value' => 'yes',
				)
			);
		}

		return get_posts( $portfolios_args );
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
		$options    = static::get_options();

		$args = array(
			'post_type'      => self::POST_TYPE,
			'posts_per_page' => intval( $options['related_projects_number'] ),
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
	 * Get portfolio images ids
	 *
	 * @param int|\WP_Post|null $post Post ID or post object. Defaults to global $post.
	 *
	 * @return array
	 */
	public static function get_portfolio_images_ids( $post = null ) {
		$post           = get_post( $post );
		$project_images = get_post_meta( $post->ID, '_project_images', true );
		if ( is_string( $project_images ) ) {
			// Remove last comma if any
			$project_images = rtrim( $project_images, ',' );
			// Split by comma
			$project_images = explode( ',', $project_images );
			// Remove empty value from array
			$project_images = array_filter( $project_images );
		}

		return is_array( $project_images ) ? $project_images : array();
	}

	/**
	 * Check if has portfolio images
	 *
	 * @param int|\WP_Post|null $post Post ID or post object. Defaults to global $post.
	 *
	 * @return bool
	 */
	public static function has_portfolio_images( $post = null ) {
		$ids = self::get_portfolio_images_ids( $post );

		return count( $ids ) > 0;
	}

	/**
	 * Get categories from portfolios
	 *
	 * @param WP_Post[] $portfolios
	 *
	 * @return array|WP_Term[]
	 */
	public static function get_categories_from_portfolios( array $portfolios ) {
		$ids = wp_list_pluck( $portfolios, "ID" );

		return wp_get_object_terms( $ids, self::CATEGORY );
	}

	/**
	 * Get skills from portfolios
	 *
	 * @param WP_Post[] $portfolios
	 *
	 * @return array|WP_Term[]
	 */
	public static function get_skills_from_portfolios( array $portfolios ) {
		$ids = wp_list_pluck( $portfolios, "ID" );

		return wp_get_object_terms( $ids, self::SKILL );
	}

	/**
	 * Get portfolio categories
	 *
	 * @return array|WP_Term[]
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

	/**
	 * Get portfolio skills
	 *
	 * @return array|\WP_Term[]
	 */
	public static function get_portfolio_skills() {
		$terms = get_terms( array(
			'taxonomy'   => self::SKILL,
			'hide_empty' => true,
		) );

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return count( $terms ) ? $terms : array();
	}

	/**
	 * Check if single portfolio page
	 *
	 * @return bool
	 */
	public static function is_single_portfolio() {
		return is_singular( self::POST_TYPE );
	}

	/**
	 * Check if portfolio archive page
	 *
	 * @return bool
	 */
	public static function is_portfolio_archive() {
		if ( is_post_type_archive( self::POST_TYPE ) ) {
			return true;
		}
		if ( is_tax( self::CATEGORY ) || is_tax( self::SKILL ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check current theme has single-portfolio.php file
	 *
	 * @return bool
	 */
	public static function has_single_template() {
		$template = locate_template( 'single-portfolio.php' );
		if ( '' != $template ) {
			return true;
		}

		return false;
	}

	/**
	 * Check current theme has custom archive template
	 *
	 * @return bool
	 */
	public static function has_archive_template() {
		$templates = array(
			'archive-portfolio.php',
			'taxonomy-portfolio_cat.php',
			'taxonomy-portfolio_skill.php'
		);

		if ( '' != locate_template( $templates ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if Shapla theme or it's child theme is active
	 *
	 * @return boolean
	 */
	public static function is_shapla_theme_activate() {
		$current_theme  = wp_get_theme();
		$theme_name     = $current_theme->get( 'Name' );
		$theme_template = $current_theme->get( 'Template' );

		if ( $theme_template == 'shapla' || $theme_name == 'Shapla' ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if skills and categories should show with link
	 * If current theme support archive template then link should include
	 *
	 * @return bool
	 */
	public static function support_archive_template() {
		return ( self::has_archive_template() || self::is_shapla_theme_activate() );
	}

	/**
	 * Create dummy categories
	 */
	public static function create_dummy_categories() {
		$terms = get_terms( [ 'taxonomy' => self::CATEGORY ] );
		if ( is_array( $terms ) && count( $terms ) ) {
			return;
		}

		foreach ( range( 1, 5 ) as $cat ) {
			wp_insert_term( sprintf( "Cat %s", $cat ), self::CATEGORY,
				[ 'slug' => sprintf( "cat-%s", $cat ) ]
			);
		}
	}

	/**
	 * Create dummy categories
	 */
	public static function create_dummy_skills() {
		$terms = get_terms( [ 'taxonomy' => self::SKILL ] );
		if ( is_array( $terms ) && count( $terms ) ) {
			return;
		}

		foreach ( range( 1, 5 ) as $cat ) {
			wp_insert_term( sprintf( "Skill %s", $cat ), self::SKILL,
				[ 'slug' => sprintf( "skill-%s", $cat ) ]
			);
		}
	}

	/**
	 * Create dummy portfolios
	 *
	 * @param int $total
	 */
	public static function create_dummy_portfolio( $total = 1 ) {
		$categories = get_terms( [ 'taxonomy' => self::CATEGORY, 'hide_empty' => false ] );
		$skills     = get_terms( [ 'taxonomy' => self::SKILL, 'hide_empty' => false ] );
		$names      = [ "Sayful", "Alkima", "Araf", "Jara", "Akhi", "Saif", "Sabiha", "Islam", "Sajib" ];

		foreach ( range( 1, $total ) as $item ) {
			$id = wp_insert_post( [
				'post_title'   => self::lorem( 1, 10, false ),
				'post_excerpt' => self::lorem( 1, 30, false ),
				'post_content' => self::lorem( 5 ),
				'post_status'  => 'publish',
				'post_type'    => self::POST_TYPE,
			] );
			if ( ! is_wp_error( $id ) ) {
				$name = $names[ rand( 0, 8 ) ];
				add_post_meta( $id, '_client_name', $name );

				add_post_meta( $id, '_project_url', 'https://example.com' );

				$random_time = rand( strtotime( 'first day of last year' ), time() );
				add_post_meta( $id, '_project_date', date( "Y-m-d", $random_time ) );

				$images     = self::get_images( 'full', 10 );
				$images_ids = wp_list_pluck( $images, 'id' );
				add_post_meta( $id, '_project_images', implode( ',', $images_ids ) );
				set_post_thumbnail( $id, $images_ids[0] );

				$category = $categories[ rand( 0, count( $categories ) - 1 ) ];
				wp_set_object_terms( $id, [ $category->term_id ], self::CATEGORY );

				$skill = $skills[ rand( 0, count( $skills ) - 1 ) ];
				wp_set_object_terms( $id, [ $skill->term_id ], self::SKILL );
			}
		}
	}

	/**
	 * Creates Filterable Portfolio test page
	 *
	 * @return int|WP_Error
	 */
	public static function create_test_page() {
		$page_path    = 'filterable-portfolio-test';
		$page_title   = __( 'Filterable Portfolio Test', 'carousel-slider' );
		$page_content = '[filterable_portfolio]';

		// Check that the page doesn't exist already
		$_page     = get_page_by_path( $page_path );
		$page_data = [
			'post_content'   => $page_content,
			'post_name'      => $page_path,
			'post_title'     => $page_title,
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'ping_status'    => 'closed',
			'comment_status' => 'closed',
		];

		if ( $_page instanceof WP_Post ) {
			$page_data['ID'] = $_page->ID;

			return wp_update_post( $page_data );
		}

		return wp_insert_post( $page_data );
	}

	/**
	 * Generate lorem text
	 *
	 * @param int $sentence
	 * @param int $max_words
	 * @param bool $prepend_lorem_text
	 *
	 * @return string
	 */
	public static function lorem( $sentence = 1, $max_words = 20, $prepend_lorem_text = true ) {
		$out = '';
		if ( $prepend_lorem_text ) {
			$out = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
			       'sed do eiusmod tempor incididunt ut labore et dolore magna ' .
			       'aliqua.';
		}
		$rnd       = explode( ' ',
			'a ab ad accusamus adipisci alias aliquam amet animi aperiam ' .
			'architecto asperiores aspernatur assumenda at atque aut beatae ' .
			'blanditiis cillum commodi consequatur corporis corrupti culpa ' .
			'cum cupiditate debitis delectus deleniti deserunt dicta ' .
			'dignissimos distinctio dolor ducimus duis ea eaque earum eius ' .
			'eligendi enim eos error esse est eum eveniet ex excepteur ' .
			'exercitationem expedita explicabo facere facilis fugiat harum ' .
			'hic id illum impedit in incidunt ipsa iste itaque iure iusto ' .
			'laborum laudantium libero magnam maiores maxime minim minus ' .
			'modi molestiae mollitia nam natus necessitatibus nemo neque ' .
			'nesciunt nihil nisi nobis non nostrum nulla numquam occaecati ' .
			'odio officia omnis optio pariatur perferendis perspiciatis ' .
			'placeat porro possimus praesentium proident quae quia quibus ' .
			'quo ratione recusandae reiciendis rem repellat reprehenderit ' .
			'repudiandae rerum saepe sapiente sequi similique sint soluta ' .
			'suscipit tempora tenetur totam ut ullam unde vel veniam vero ' .
			'vitae voluptas' );
		$max_words = $max_words <= 3 ? 4 : $max_words;
		for ( $i = 0, $add = $sentence - (int) $prepend_lorem_text; $i < $add; $i ++ ) {
			shuffle( $rnd );
			$words = array_slice( $rnd, 0, mt_rand( 3, $max_words ) );
			$out   .= ( ! $prepend_lorem_text && $i == 0 ? '' : ' ' ) . ucfirst( implode( ' ', $words ) ) . '.';
		}

		return $out;
	}

	/**
	 * Get list of images sorted by its width and height
	 *
	 * @param string $image_size
	 * @param int $per_page
	 *
	 * @return array
	 */
	public static function get_images( $image_size = 'full', $per_page = 100 ) {
		$args        = [
			'order'          => 'DESC',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'any',
			'posts_per_page' => $per_page,
			'orderby'        => 'rand',
		];
		$attachments = get_posts( $args );

		$images = [];

		foreach ( $attachments as $attachment ) {
			if ( ! $attachment instanceof WP_Post ) {
				continue;
			}

			if ( ! in_array( $attachment->post_mime_type, array( 'image/jpeg', 'image/png' ) ) ) {
				continue;
			}

			$src = wp_get_attachment_image_src( $attachment->ID, $image_size );

			$images[] = [
				'id'           => $attachment->ID,
				'title'        => $attachment->post_title,
				'description'  => $attachment->post_content,
				'caption'      => $attachment->post_excerpt,
				'image_src'    => $src[0],
				'image_width'  => $src[1],
				'image_height' => $src[2],
			];
		}

		$widths  = wp_list_pluck( $images, 'image_width' );
		$heights = wp_list_pluck( $images, 'image_height' );

		// Sort the $images with $widths and $heights descending
		array_multisort( $widths, SORT_DESC, $heights, SORT_DESC, $images );

		return $images;
	}

	/**
	 * Load a template
	 *
	 * @param string $template The template name.
	 * @param bool $require_once Should require once?
	 * @param array $args Optional arguments to be passed to template.
	 *
	 * @return void
	 */
	public static function load_template( $template, $require_once = true, $args = [] ) {
		$located = FILTERABLE_PORTFOLIO_TEMPLATES . '/' . $template;

		// First check if file exist in stylesheet or template directory
		$locate_template = locate_template( $template );
		if ( '' != $locate_template ) {
			$located = $locate_template;
		}

		load_template( $located, $require_once, $args );
	}
}
