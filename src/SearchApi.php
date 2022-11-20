<?php
/**
 * Assets Class.
 *
 * @package aquila-features
 */

namespace AquilaFeatures;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use WP_Query;
use WP_Post;
use stdClass;

/**
 * Class SearchApi.
 */
class SearchApi {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize.
	 */
	private function init() {
		/**
		 * Register Rest Api Endpoints Routes.
		 */
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}


	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes(): void {

		/**
		 * Register search api.
		 * e.g. https://example.com/wp-json/af/v1/search?q='Hello'&category=23,43&post_tag=23,32&page_no=1&posts_per_page=9
		 */
		register_rest_route(
			'af/v1',
			'/search',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_items' ],
				'permission_callback' => '__return_true',
				'args'                => [
					'q' => [
						'required'          => false,
						'type'              => 'string',
						'description'       => esc_html__( 'Search Query', 'ex' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'categories' => [
						'required'          => false,
						'type'              => 'string',
						'description'       => esc_html__( 'Categories', 'ex' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'tags' => [
						'required'          => false,
						'type'              => 'string',
						'description'       => esc_html__( 'Tags', 'ex' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'page_no' => [
						'required'          => false,
						'type'              => 'string',
						'description'       => esc_html__( 'Page no', 'ex' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'posts_per_page' => [
						'required'          => false,
						'type'              => 'string',
						'description'       => esc_html__( 'Posts per page', 'ex' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Get the items for trip choices.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function get_items( WP_REST_Request $request ): WP_REST_Response {
		$search_term    = $request->get_param( 'q' );
		$category_ids   = $request->get_param( 'category' );
		$tag_ids        = $request->get_param( 'post_tag' );
		$page_no        = $request->get_param( 'page_no' );
		$posts_per_page = $request->get_param( 'posts_per_page' );
		$search_query   = [
			'posts_per_page'         => $posts_per_page ? intval( $posts_per_page ) : 9,
			'post_status'            => 'publish',
			'paged'                  => $page_no ? intval( $page_no ) : 1,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];

		// Add search query args.
		if ( ! empty( $search_term ) ) {
			$search_query['s'] = $search_term;
		}

		// Add tax_query_array args.
		if ( ! empty( $category_ids ) || ! empty( $tag_ids ) ) {
			$search_query['tax_query'] = [];
		}

		// Add category query args.
		if ( ! empty( $category_ids ) ) {
			$search_query['tax_query'][] = [
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => array_map( 'intval', explode( ',', $category_ids ) ),
				'operator' => 'IN',
			];
		}

		// Add tags query args.
		if ( ! empty( $tag_ids ) ) {
			$search_query['tax_query'][] = [
				'taxonomy' => 'post_tag',
				'field'    => 'id',
				'terms'    => array_map( 'intval', explode( ',', $tag_ids ) ),
				'operator' => 'IN',
			];
		}

		$results = new WP_Query( $search_query );

		$response = $this->build_response( $results );

		return rest_ensure_response( $response );
	}

	/**
	 * Build the response data for choices list.
	 *
	 * @param object $results List of choices.
	 *
	 * @return stdClass
	 */
	private function build_response( object $results ): stdClass {
		$the_posts = [];
		if ( ! empty( $results->posts ) && is_array( $results->posts ) ) {
			foreach ( $results->posts as $the_post ) {
				if ( ! $the_post instanceof WP_Post || empty( $the_post ) ) {
					continue;
				}

				$the_posts[] = [
					'id'        => $the_post->ID,
					'title'     => $the_post->post_title,
					'content'   => $the_post->post_title,
					'date'      => wp_date( get_option( 'date_format' ), get_post_timestamp( $the_post ) ),
					'permalink' => get_the_permalink( $the_post ),
					'thumbnail' => get_the_post_thumbnail( $the_post ),
				];
			}
		}

		// Get total number of pages.
		$total_pages = $this->calculate_page_count(
			$results->found_posts ?? 0,
			$results->query['posts_per_page'] ?? 0
		);

		// Return the formatted result.
		return (object) [
			'posts'          => $the_posts,
			'posts_per_page' => $results->query['posts_per_page'],
			'total_posts'    => $results->found_posts,
			'no_of_pages'    => $total_pages,
		];
	}

	/**
	 * Calculate page count.
	 *
	 * @param int $total_found_posts Total posts found.
	 * @param int $post_per_page     Post per page count.
	 *
	 * @return int
	 */
	public function calculate_page_count( int $total_found_posts, int $post_per_page ): int {
		if ( empty( $total_found_posts ) || empty( $post_per_page ) ) {
			return 0;
		}
		return ( (int) ( $total_found_posts / $post_per_page ) + ( ( $total_found_posts % $post_per_page ) ? 1 : 0 ) );
	}

}

