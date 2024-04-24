<?php
/**
 * Abstract class controller.
 */

namespace Masteriyo\RestApi\Controllers\Store;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\PostStatus;
use Masteriyo\RestApi\Controllers\Version1\CrudController;
use Masteriyo\Helper\Utils;
use Masteriyo\Helper\Permission;

class CheckoutController extends CrudController {
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/store';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'checkout';

	/** Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'checkout';

	/**
	 * Permission class.
	 *
	 * @since 1.0.0
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param(
							array(
								'default' => 'view',
							)
						),
					),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
					'args'                => array(
						'force' => array(
							'default'     => false,
							'description' => __( 'Whether to bypass trash and force deletion.', 'masteriyo' ),
							'type'        => 'boolean',
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['slug']       = array(
			'description'       => __( 'Limit result set to lessons with a specific slug.', 'masteriyo' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['status']     = array(
			'default'           => 'any',
			'description'       => __( 'Limit result set to lessons assigned a specific status.', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any', 'future' ), array_keys( get_post_statuses() ) ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['category']   = array(
			'description'       => __( 'Limit result set to lessons assigned a specific category ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['tag']        = array(
			'description'       => __( 'Limit result set to lessons assigned a specific tag ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['difficulty'] = array(
			'description'       => __( 'Limit result set to lessons assigned a specific difficulty ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @param  int|WP_Post $id Object ID.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $id ) {
		global $masteriyo_container;
		try {
			$id     = $id instanceof \WP_Post ? $id->ID : $id;
			$lesson = $masteriyo_container->get( 'lesson' );
			$lesson->set_id( $id );
			$lesson_repo = $masteriyo_container->get( \Masteriyo\Repository\LessonRepository::class );
			$lesson_repo->read( $lesson );
		} catch ( \Exception $e ) {
			return false;
		}

		return $lesson;
	}


	/**
	 * Prepares the object for the REST response.
	 *
	 * @since   1.0.0
	 *
	 * @param  Masteriyo\Database\Model $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_lesson_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Get lesson data.
	 *
	 * @param Masteriyo\Models\Lesson $lesson Lesson instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_lesson_data( $lesson, $context = 'view' ) {
		/**
		 * Filters lesson short description.
		 *
		 * @since 1.0.0
		 *
		 * @param string $short_description Lesson short description.
		 */
		$short_description = 'view' === $context ? apply_filters( 'masteriyo_short_description', $lesson->get_short_description() ) : $lesson->get_short_description();

		$data = array(
			'id'                  => $lesson->get_id(),
			'name'                => $lesson->get_name( $context ),
			'slug'                => $lesson->get_slug( $context ),
			'permalink'           => $lesson->get_permalink(),
			'status'              => $lesson->get_status( $context ),
			'featured'            => $lesson->get_featured( $context ),
			'description'         => 'view' === $context ? wpautop( do_shortcode( $lesson->get_description() ) ) : $lesson->get_description( $context ),
			'short_description'   => $short_description,
			'menu_order'          => $lesson->get_menu_order( $context ),
			'reviews_allowed'     => $lesson->get_reviews_allowed( $context ),
			'parent_id'           => $lesson->get_parent_id( $context ),
			'categories'          => $this->get_taxonomy_terms( $lesson ),
			'tags'                => $this->get_taxonomy_terms( $lesson, 'tag' ),
			'video_source'        => $lesson->get_video_source( $context ),
			'video_source_url'    => $lesson->get_video_source_url( $context ),
			'video_playback_time' => $lesson->get_video_playback_time( $context ),
		);

		return $data;
	}

	/**
	 * Get taxonomy terms.
	 *
	 * @since 1.0.0
	 *
	 * @param Lesson $lesson Lesson object.
	 * @param string $taxonomy Taxonomy slug.
	 *
	 * @return array
	 */
	protected function get_taxonomy_terms( $lesson, $taxonomy = 'cat' ) {
		$terms = Utils::get_object_terms( $lesson->get_id(), 'lesson_' . $taxonomy );

		$terms = array_map(
			function( $term ) {
				return array(
					'id'   => $term->term_id,
					'name' => $term->name,
					'slug' => $term->slug,
				);
			},
			$terms
		);

		return $terms;
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since   1.0.0
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		// Set post_status.
		$args['post_status'] = $request['status'];

		// Taxonomy query to filter lessons by category, tag and difficult.
		$tax_query = array();

		// Map between taxonomy name and arg's key.
		$taxonomies = array(
			'lesson_cat' => 'category',
			'lesson_tag' => 'tag',
		);

		// Set tax_query for each passed arg.
		foreach ( $taxonomies as $taxonomy => $key ) {
			if ( ! empty( $request[ $key ] ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $request[ $key ],
				);
			}
		}

		// Build tax_query if taxonomies are set.
		if ( ! empty( $tax_query ) ) {
			if ( ! empty( $args['tax_query'] ) ) {
				$args['tax_query'] = array_merge( $tax_query, $args['tax_query'] ); // WPCS: slow query ok.
			} else {
				$args['tax_query'] = $tax_query; // WPCS: slow query ok.
			}
		}

		// Filter featured.
		if ( is_bool( $request['featured'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'lesson_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => true === $request['featured'] ? 'IN' : 'NOT IN',
			);
		}

		return $args;
	}

	/**
	 * Get the lessons'schema, conforming to JSON Schema.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'id'                  => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'                => array(
					'description' => __( 'Lesson name.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'                => array(
					'description' => __( 'Lesson slug', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'permalink'           => array(
					'description' => __( 'Lesson URL', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'        => array(
					'description' => __( "The date the lesson was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created_gmt'    => array(
					'description' => __( 'The date the lesson was created, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_modified'       => array(
					'description' => __( "The date the lesson was last modified, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt'   => array(
					'description' => __( 'The date the lesson was last modified, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'status'              => array(
					'description' => __( 'Lesson status (post status).', 'masteriyo' ),
					'type'        => 'string',
					'default'     => PostStatus::PUBLISH,
					'enum'        => array_merge( array_keys( get_post_statuses() ), array( 'future' ) ),
					'context'     => array( 'view', 'edit' ),
				),
				'featured'            => array(
					'description' => __( 'Featured lesson', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'catalog_visibility'  => array(
					'description' => __( 'Catalog visibility', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'visible',
					'enum'        => array( 'visible', 'catalog', 'search', 'hidden' ),
					'context'     => array( 'view', 'edit' ),
				),
				'description'         => array(
					'description' => __( 'Lesson description', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'short_description'   => array(
					'description' => __( 'Lesson short description', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'reviews_allowed'     => array(
					'description' => __( 'Allow reviews.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'average_rating'      => array(
					'description' => __( 'Reviews average rating.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'rating_count'        => array(
					'description' => __( 'Amount of reviews that the lesson has.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'parent_id'           => array(
					'description' => __( 'Lesson parent ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'categories'          => array(
					'description' => __( 'List of categories.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Category ID', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Category name', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Category slug', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'tags'                => array(
					'description' => __( 'List of tags', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Tag ID', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Tag name', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Tag slug', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'menu_order'          => array(
					'description' => __( 'Menu order, used to custom sort lessons.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'video_source'        => array(
					'description' => __( 'Video source', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'video_source_url'    => array(
					'description' => __( 'Video source URL', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'video_playback_time' => array(
					'description' => __( 'Video playback time', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data'           => array(
					'description' => __( 'Meta data', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'description' => __( 'Meta ID', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'key'   => array(
								'description' => __( 'Meta key', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'value' => array(
								'description' => __( 'Meta value', 'masteriyo' ),
								'type'        => 'mixed',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
			),
		);

		return $schema;
	}

	/**
	 * Prepare a single lesson for create or update.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return \WP_Error|\Masteriyo\Database\Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		global $masteriyo_container;

		$id     = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$lesson = $masteriyo_container->get( 'lesson' );

		if ( 0 !== $id ) {
			$lesson->set_id( $id );
			$lesson_repo = $masteriyo_container->get( \Masteriyo\Repository\LessonRepository::class );
			$lesson_repo->read( $lesson );
		}

		// Post title.
		if ( isset( $request['name'] ) ) {
			$lesson->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Post content.
		if ( isset( $request['description'] ) ) {
			$lesson->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Post excerpt.
		if ( isset( $request['short_description'] ) ) {
			$lesson->set_short_description( wp_filter_post_kses( $request['short_description'] ) );
		}

		// Post status.
		if ( isset( $request['status'] ) ) {
			$lesson->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : 'draft' );
		}

		// Post slug.
		if ( isset( $request['slug'] ) ) {
			$lesson->set_slug( $request['slug'] );
		}

		// Menu order.
		if ( isset( $request['menu_order'] ) ) {
			$lesson->set_menu_order( $request['menu_order'] );
		}

		// Comment status.
		if ( isset( $request['reviews_allowed'] ) ) {
			$lesson->set_reviews_allowed( $request['reviews_allowed'] );
		}

		// Featured Lesson.
		if ( isset( $request['featured'] ) ) {
			$lesson->set_featured( $request['featured'] );
		}

		// Lesson parent ID.
		if ( isset( $request['parent_id'] ) ) {
			$lesson->set_parent_id( $request['parent_id'] );
		}

		// Lesson video source.
		if ( isset( $request['video_source'] ) ) {
			$lesson->set_video_source( $request['video_source'] );
		}

		// Lesson video source url.
		if ( isset( $request['video_source_url'] ) ) {
			$lesson->set_video_source_url( $request['video_source_url'] );
		}

		// Lesson video playback time.
		if ( isset( $request['video_playback_time'] ) ) {
			$lesson->set_video_playback_time( $request['video_playback_time'] );
		}

		// Lesson categories.
		if ( isset( $request['categories'] ) && is_array( $request['categories'] ) ) {
			$lesson = $this->save_taxonomy_terms( $lesson, $request['categories'] );
		}

		// Lesson tags.
		if ( isset( $request['tags'] ) && is_array( $request['tags'] ) ) {
			$lesson = $this->save_taxonomy_terms( $lesson, $request['tags'], 'tag' );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$lesson->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.0.0
		 *
		 * @param \Masteriyo\Database\Model $lesson Lesson object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $lesson, $request, $creating );
	}

	/**
	 * Save taxonomy terms.
	 *
	 * @since 1.0.0
	 *
	 * @param Lesson $lesson  Lesson instance.
	 * @param array  $terms    Terms data.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return Lesson
	 */
	protected function save_taxonomy_terms( $lesson, $terms, $taxonomy = 'cat' ) {
		$term_ids = wp_list_pluck( $terms, 'id' );

		if ( 'cat' === $taxonomy ) {
			$lesson->set_category_ids( $term_ids );
		} elseif ( 'tag' === $taxonomy ) {
			$lesson->set_tag_ids( $term_ids );
		} elseif ( 'difficulty' === $taxonomy ) {
			$lesson->set_difficulty_id( array_shift( $term_ids ) );
		}

		return $lesson;
	}
}
