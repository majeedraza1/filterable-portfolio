<?php

defined( 'ABSPATH' ) || exit;

class Filterable_Portfolio_REST_Controller extends WP_REST_Controller {

	/**
	 * HTTP status code.
	 *
	 * @var int
	 */
	protected $statusCode = 200;

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'filterable-portfolio/v1';

	/**
	 * MYSQL data format
	 *
	 * @var string
	 */
	protected static $mysql_date_format = 'Y-m-d';

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * Only one instance of the class can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;

			add_action( 'rest_api_init', array( self::$instance, 'register_routes' ) );
		}

		return self::$instance;
	}

	/**
	 * Registers the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/portfolios', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_items' ],
				'args'                => $this->get_collection_params(),
				'permission_callback' => '__return_true',
			],
		] );
		register_rest_route( $this->namespace, '/categories', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_categories' ],
				'permission_callback' => '__return_true',
			],
		] );
		register_rest_route( $this->namespace, '/skills', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_skills' ],
				'permission_callback' => '__return_true',
			],
		] );
	}

	/**
	 * Retrieves a collection of portfolios.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {
		$page     = $request->get_param( 'page' );
		$per_page = $request->get_param( 'per_page' );
		$order    = $request->get_param( 'order' );
		$orderby  = $request->get_param( 'orderby' );

		$args = [
			'page'     => $page,
			'per_page' => $per_page,
		];

		if ( $request->get_param( 'featured' ) ) {
			$args['featured'] = true;
		}

		if ( ! empty( $order ) ) {
			$args['order'] = $order;
		}

		if ( ! empty( $orderby ) ) {
			$args['orderby'] = $orderby;
		}

		$portfolios = Filterable_Portfolio_Helper::get_portfolios( $args );
		$response   = [ 'items' => $this->prepare_portfolios_for_response( $portfolios, $request ) ];

		return $this->respondOK( $response );
	}

	/**
	 * Retrieves a collection of portfolios categories.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	public function get_categories( $request ) {
		$items    = Filterable_Portfolio_Helper::get_portfolio_categories();
		$response = [ 'items' => $this->prepare_terms_for_response( $items ) ];

		return $this->respondOK( $response );
	}

	/**
	 * Retrieves a collection of portfolios skills.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	public function get_skills( $request ) {
		$items    = Filterable_Portfolio_Helper::get_portfolio_skills();
		$response = [ 'items' => $this->prepare_terms_for_response( $items ) ];

		return $this->respondOK( $response );
	}

	/**
	 * Prepares a single post output for response.
	 *
	 * @param WP_Post $post Post object.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response Response object.
	 */
	public function prepare_item_for_response( $post, $request ) {
		$fields = $request->get_param( 'fields' );
		$option = get_option( 'filterable_portfolio' );

		// Base fields for every post.
		$data = array( 'id' => $post->ID );

		if ( in_array( 'title', $fields ) ) {
			$data['title'] = get_the_title( $post->ID );
		}

		if ( in_array( 'content', $fields ) ) {
			$data['content'] = apply_filters( 'the_content', $post->post_content );
		}

		if ( in_array( 'excerpt', $fields ) ) {
			$data['excerpt'] = apply_filters( 'the_excerpt', apply_filters( 'get_the_excerpt', $post->post_excerpt, $post ) );
		}

		if ( in_array( 'date', $fields ) ) {
			$data['date'] = mysql_to_rfc3339( $post->post_date );
		}

		if ( in_array( 'date_gmt', $fields ) ) {
			$data['date_gmt'] = mysql_to_rfc3339( $post->post_date_gmt );
		}

		if ( in_array( 'modified', $fields ) ) {
			$data['modified'] = mysql_to_rfc3339( $post->post_modified );
		}

		if ( in_array( 'modified_gmt', $fields ) ) {
			$data['modified_gmt'] = mysql_to_rfc3339( $post->post_modified_gmt );
		}

		if ( in_array( 'link', $fields ) ) {
			$data['link'] = get_permalink( $post->ID );
		}

		if ( in_array( 'categories', $fields ) ) {
			$categories = wp_get_object_terms( $post->ID, Filterable_Portfolio_Helper::CATEGORY );
			if ( ! empty( $categories ) ) {
				$data['categories'] = $this->prepare_terms_for_response( $categories );
			}
		}

		if ( in_array( 'skills', $fields ) ) {
			$skills = wp_get_object_terms( $post->ID, Filterable_Portfolio_Helper::SKILL );
			if ( ! empty( $skills ) ) {
				$data['skills'] = $this->prepare_terms_for_response( $skills );
			}
		}

		if ( in_array( 'project_images', $fields ) ) {
			$project_images_ids = Filterable_Portfolio_Helper::get_portfolio_images_ids( $post );
			if ( $project_images_ids ) {
				$image_size = ! empty( $option['project_image_size'] ) ? $option['project_image_size'] : 'full';
				foreach ( $project_images_ids as $id ) {
					$image_src = wp_get_attachment_image_src( $id, $image_size );
					if ( isset( $image_src[0] ) && filter_var( $image_src[0], FILTER_VALIDATE_URL ) ) {
						$data['project_images'][] = [
							'id'       => intval( $id ),
							'title'    => get_the_title( $id ),
							'alt_text' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
							'src'      => $image_src[0],
						];
					}
				}
			}
		}

		if ( in_array( 'client_name', $fields ) ) {
			$client_name         = get_post_meta( $post->ID, '_client_name', true );
			$data['client_name'] = ! empty( $client_name ) ? $client_name : '';
		}

		if ( in_array( 'project_url', $fields ) ) {
			$project_url         = get_post_meta( $post->ID, '_project_url', true );
			$data['project_url'] = '';
			if ( ! empty( $project_url ) && filter_var( $project_url, FILTER_VALIDATE_URL ) ) {
				$data['project_url'] = $project_url;
			}
		}

		if ( in_array( 'project_date', $fields ) ) {
			$project_date         = get_post_meta( $post->ID, '_project_date', true );
			$data['project_date'] = ! empty( $project_date ) ? date( "Y-m-d", strtotime( $project_date ) ) : '';
		}

		if ( in_array( 'featured_media', $fields ) ) {
			$image_size             = ! empty( $option['image_size'] ) ? $option['image_size'] : 'filterable-portfolio';
			$data['featured_media'] = [];
			$thumbnail_id           = get_post_thumbnail_id( $post->ID );
			$image_src              = wp_get_attachment_image_src( $thumbnail_id, $image_size );
			if ( isset( $image_src[0] ) && filter_var( $image_src[0], FILTER_VALIDATE_URL ) ) {
				$data['featured_media'] = [
					'id'       => intval( $thumbnail_id ),
					'title'    => get_the_title( $thumbnail_id ),
					'alt_text' => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
					'src'      => $image_src[0],
				];
			}
		}

		// Wrap the data in a response object.
		$response  = rest_ensure_response( $data );
		$post_type = Filterable_Portfolio_Helper::POST_TYPE;

		return apply_filters( "rest_prepare_{$post_type}", $response, $post, $request );
	}

	/**
	 * @param WP_Post[] $portfolios
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array
	 */
	public function prepare_portfolios_for_response( array $portfolios, $request ) {
		$items = [];
		foreach ( $portfolios as $portfolio ) {
			$items[] = $this->prepare_item_for_response( $portfolio, $request )->get_data();
		}

		return $items;
	}

	/**
	 * @param WP_Term[] $categories
	 *
	 * @return array
	 */
	public function prepare_terms_for_response( array $categories ) {
		$items = [];
		foreach ( $categories as $category ) {
			$items[] = [
				'id'    => $category->term_id,
				'slug'  => $category->slug,
				'name'  => $category->name,
				'count' => $category->count,
			];
		}

		return $items;
	}

	/**
	 * Retrieves the query params for the collections.
	 *
	 * @return array Query parameters for the collection.
	 */
	public function get_collection_params() {
		$valid_fields = [
			'id',
			'title',
			'content',
			'excerpt',
			'date',
			'date_gmt',
			'modified',
			'modified_gmt',
			'link',
			'categories',
			'skills',
			'project_images',
			'client_name',
			'project_url',
			'project_date',
			'featured_media'
		];

		$params = parent::get_collection_params();

		return array_merge( $params, [
			'order'    => [
				'description' => __( 'Order sort attribute ascending or descending.' ),
				'type'        => 'string',
				'default'     => 'desc',
				'enum'        => [ 'asc', 'desc' ],
			],
			'orderby'  => [
				'description' => __( 'Sort collection by object attribute.' ),
				'type'        => 'string',
				'default'     => 'date',
				'enum'        => [ 'id', 'title', 'date', ],
			],
			'fields'   => [
				'description'       => __( 'List of fields to include in response. Available fields are ' ) . implode( ', ', $valid_fields ),
				'type'              => 'array',
				'default'           => [ 'id', 'title', 'featured_media' ],
				'validate_callback' => 'rest_validate_request_arg',
			],
			'featured' => [
				'description'       => __( 'Limit results to featured projects only.' ),
				'type'              => 'boolean',
				'default'           => false,
				'validate_callback' => 'rest_validate_request_arg',
			],
		] );
	}

	/**
	 * Get HTTP status code.
	 *
	 * @return integer
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * Set HTTP status code.
	 *
	 * @param int $statusCode
	 *
	 * @return self
	 */
	public function setStatusCode( $statusCode ) {
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * Respond.
	 *
	 * @param mixed $data Response data. Default null.
	 * @param int $status Optional. HTTP status code. Default 200.
	 * @param array $headers Optional. HTTP header map. Default empty array.
	 *
	 * @return WP_REST_Response
	 */
	public function respond( $data = null, $status = 200, $headers = array() ) {
		return new WP_REST_Response( $data, $status, $headers );
	}

	/**
	 * Response error message
	 *
	 * @param string $code
	 * @param string $message
	 * @param mixed $data
	 *
	 * @return WP_REST_Response
	 */
	public function respondWithError( $code = null, $message = null, $data = null ) {
		if ( 1 === func_num_args() && is_array( $code ) ) {
			list( $code, $message, $data ) = array( null, null, $code );
		}

		$status_code = $this->getStatusCode();
		$response    = [ 'success' => false ];

		if ( ! empty( $code ) && is_string( $code ) ) {
			$response['code'] = $code;
		}

		if ( ! empty( $message ) && is_string( $message ) ) {
			$response['message'] = $message;
		}

		if ( ! empty( $data ) ) {
			$response['errors'] = $data;
		}

		return $this->respond( $response, $status_code );
	}

	/**
	 * Response success message
	 *
	 * @param mixed $data
	 * @param string $message
	 * @param array $headers
	 *
	 * @return WP_REST_Response
	 */
	public function respondWithSuccess( $data = null, $message = null, $headers = array() ) {
		if ( 1 === func_num_args() && is_string( $data ) ) {
			list( $data, $message ) = array( null, $data );
		}

		$code     = $this->getStatusCode();
		$response = [ 'success' => true ];

		if ( ! empty( $message ) ) {
			$response['message'] = $message;
		}

		if ( ! empty( $data ) ) {
			$response['data'] = $data;
		}

		return $this->respond( $response, $code, $headers );
	}

	/**
	 * 200 (OK)
	 * The request has succeeded.
	 *
	 * Use cases:
	 * --> update/retrieve data
	 * --> bulk creation
	 * --> bulk update
	 *
	 * @param mixed $data
	 * @param string $message
	 *
	 * @return WP_REST_Response
	 */
	public function respondOK( $data = null, $message = null ) {
		return $this->setStatusCode( 200 )->respondWithSuccess( $data, $message );
	}
}
