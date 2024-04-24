<?php
/**
 * Lessons class controller.
 *
 * @since 1.0.0
 * @package Masteriyo\RestApi
 * @subpackage Controllers
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\PostStatus;
use Masteriyo\Enums\SectionChildrenPostType;
use Masteriyo\Helper\Permission;

class LessonsController extends PostsController {
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'lessons';

	/** Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'lesson';

	/** Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'mto-lesson';

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

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

		// The sections should be order by menu which is the sort order.
		$params['order']['default']   = 'asc';
		$params['orderby']['default'] = 'menu_order';

		$params['course_id']  = array(
			'description'       => __( 'Limit lessons by course id.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
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
	 * @since 1.0.0
	 *
	 * @param  int|WP_Post|Model $object Object ID or WP_Post or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Post' ) ? $object->ID : $object->get_id();
			}
			$lesson = masteriyo( 'lesson' );
			$lesson->set_id( $id );
			$lesson_repo = masteriyo( 'lesson.store' );
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
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Lesson $lesson Lesson instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_lesson_data( $lesson, $context = 'view' ) {
		$section = masteriyo_get_section( $lesson->get_parent_id() );
		$course  = masteriyo_get_course( $lesson->get_course_id( $context ) );

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
			'name'                => wp_specialchars_decode( $lesson->get_name( $context ) ),
			'slug'                => $lesson->get_slug( $context ),
			'permalink'           => $lesson->get_permalink(),
			'preview_link'        => $lesson->get_preview_link(),
			'status'              => $lesson->get_status( $context ),
			'description'         => 'view' === $context ? wpautop( do_shortcode( $lesson->get_description() ) ) : $lesson->get_description( $context ),
			'short_description'   => $short_description,
			'date_created'        => masteriyo_rest_prepare_date_response( $lesson->get_date_created( $context ) ),
			'date_modified'       => masteriyo_rest_prepare_date_response( $lesson->get_date_modified( $context ) ),
			'menu_order'          => $lesson->get_menu_order( $context ),
			'parent_menu_order'   => $section ? $section->get_menu_order( $context ) : 0,
			'reviews_allowed'     => $lesson->get_reviews_allowed( $context ),
			'parent_id'           => $lesson->get_parent_id( $context ),
			'course_id'           => $course ? $course->get_id() : 0,
			'course_name'         => $course ? wp_specialchars_decode( $course->get_name( $context ) ) : '',
			'featured_image'      => $lesson->get_featured_image( $context ),
			'video_source'        => $lesson->get_video_source( $context ),
			'video_source_url'    => $lesson->get_video_source_url( $context ),
			'video_source_id'     => $lesson->get_video_source_id( $context ),
			'video_playback_time' => $lesson->get_video_playback_time( $context ),
			'attachments'         => $this->get_attachments( $lesson, $context ),
			'navigation'          => $this->get_navigation_items( $lesson, $context ),
		);

		/**
		 * Filter lesson rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Lesson data.
		 * @param Masteriyo\Models\lesson $lesson Lesson object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\lessonsController $controller REST lessons controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $lesson, $context, $this );
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

		if ( ! empty( $request['course_id'] ) ) {
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => '_course_id',
					'value'   => absint( $request['course_id'] ),
					'compare' => '=',
				),
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
					'description' => __( 'Lesson name', 'masteriyo' ),
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
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'course_id'           => array(
					'description' => __( 'Course ID', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'course_name'         => array(
					'description' => __( 'Course name', 'masteriyo' ),
					'type'        => 'string',
					'readonly'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'menu_order'          => array(
					'description' => __( 'Menu order, used to custom sort lessons.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'featured_image'      => array(
					'description' => __( 'Course featured image.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'video_source'        => array(
					'description' => __( 'Video source', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'default'     => 'self-hosted',
					'enum'        => array_keys( masteriyo_get_lesson_video_sources() ),
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
				'attachments'         => array(
					'description' => __( 'Attachments', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'  => array(
								'description' => __( 'Attachment ID', 'masteriyo' ),
								'type'        => 'integer',
								'default'     => 0,
								'context'     => array( 'view', 'edit' ),
							),
							'url' => array(
								'description' => __( 'Attachment title', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'url' => array(
								'description' => __( 'Attachment URL', 'masteriyo' ),
								'type'        => 'string',
								'format'      => 'uri',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
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

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Prepare a single lesson for create or update.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Masteriyo\Database\Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id     = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$lesson = masteriyo( 'lesson' );

		if ( 0 !== $id ) {
			$lesson->set_id( $id );
			$lesson_repo = masteriyo( 'lesson.store' );
			$lesson_repo->read( $lesson );
		}

		// Post title.
		if ( isset( $request['name'] ) ) {
			$lesson->set_name( sanitize_text_field( $request['name'] ) );
		}

		// Post content.
		if ( isset( $request['description'] ) ) {
			$lesson->set_description( wp_slash( $request['description'] ) );
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

		// Automatically set the menu order if it's not set and the operation is POST.
		if ( ! isset( $request['menu_order'] ) && $creating ) {
			$query = new \WP_Query(
				array(
					'post_type'      => SectionChildrenPostType::all(),
					'post_status'    => PostStatus::all(),
					'posts_per_page' => 1,
					'post_parent'    => $request['parent_id'],
				)
			);

			$lesson->set_menu_order( $query->found_posts );
		}

		// Comment status.
		if ( isset( $request['reviews_allowed'] ) ) {
			$lesson->set_reviews_allowed( $request['reviews_allowed'] );
		}

		// Lesson parent ID.
		if ( isset( $request['parent_id'] ) ) {
			$lesson->set_parent_id( $request['parent_id'] );
		}

		// Course ID.
		if ( isset( $request['course_id'] ) ) {
			$lesson->set_course_id( $request['course_id'] );
		}

		// Featured image.
		if ( isset( $request['featured_image'] ) ) {
			$lesson->set_featured_image( $request['featured_image'] );
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

		// Lesson attachments.
		if ( isset( $request['attachments'] ) ) {
			$lesson->set_attachments( wp_list_pluck( $request['attachments'], 'id' ) );
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
		 * @param Masteriyo\Database\Model $lesson Lesson object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $lesson, $request, $creating );
	}

	/**
	 * Prepare links for the request.
	 *
	 * @since 1.0.0
	 *
	 * @param Model           $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 * @return array                   Links for the given post.
	 */
	protected function prepare_links( $object, $request ) {
		$links = parent::prepare_links( $object, $request );

		$next_prev_links = $this->get_navigation_links( $object, $request );

		return $links + $next_prev_links;
	}

	/**
	 * Check if a given request has access to create an item.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		if ( ! $this->permission->rest_check_post_permissions( $this->post_type, 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$course_id = absint( $request['course_id'] );
		$course    = masteriyo_get_course( $course_id );

		if ( is_null( $course ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid course ID', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to delete an item.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$id     = absint( $request['id'] );
		$lesson = masteriyo_get_lesson( $id );

		if ( is_null( $lesson ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( $this->post_type, 'delete', $id ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to update an item.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$id     = absint( $request['id'] );
		$lesson = masteriyo_get_lesson( $id );

		if ( is_null( $lesson ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( $this->post_type, 'update', $id ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Get lesson attachments.
	 *
	 * @since 1.3.2
	 *
	 * @param Masteriyo\Models\Lesson $lesson Lesson object.
	 * @param string $context Request context.
	 *
	 * @return array
	 */
	protected function get_attachments( $lesson, $context ) {
		// Filter invalid attachments.
		$attachments = array_filter(
			array_map(
				function( $attachment ) {
					$post = get_post( $attachment );

					if ( $post && 'attachment' === $post->post_type ) {
						return $post;
					}

					return false;
				},
				$lesson->get_attachments( $context )
			)
		);

		// Convert the attachments to the response format.
		$attachments = array_reduce(
			$attachments,
			function( $result, $attachment ) {
				$result[] = array(
					'id'    => $attachment->ID,
					'url'   => wp_get_attachment_url( $attachment->ID ),
					'title' => $attachment->post_title,
				);

				return $result;
			},
			array()
		);

		/**
		 * Lesson attachment filter.
		 *
		 * @since 1.3.2
		 *
		 * @param string[] Attachments array.
		 * @param Masteriyo\Models\Lesson $lesson Lesson object.
		 * @param string $context Context.
		 */
		return apply_filters( "masteriyo_rest_{$this->object_type}_attachments", $attachments, $lesson, $context );
	}
}
