<?php
/**
 * Sections Controller class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\RestApi\Controllers\Version1;
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\PostStatus;
use Masteriyo\Helper\Permission;
use WP_CLI\Fetchers\Post;

/**
 * SectionsController class.
 */
class SectionsController extends PostsController {
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
	protected $rest_base = 'sections';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'mto-section';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'section';

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
						'force'    => array(
							'default'     => true,
							'description' => __( 'Whether to bypass trash and force deletion.', 'masteriyo' ),
							'type'        => 'boolean',
						),
						'children' => array(
							'default'     => true,
							'description' => __( 'Whether to delete the children(lessons, quizzes and questions) under the section.', 'masteriyo' ),
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

		$params['course_id'] = array(
			'description'       => __( 'Limit result by course id.', 'masteriyo' ),
			'type'              => 'integer',
			'validate_callback' => 'rest_validate_request_arg',
			'sanitize_callback' => 'absint',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 1.0.0
	 *
	 * @param  int|Model|WP_Post $object Object ID or Model or WP_Post object.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Post' ) ? $object->ID : $object->get_id();
			}
			$section = masteriyo( 'section' );
			$section->set_id( $id );
			$section_repo = masteriyo( 'section.store' );
			$section_repo->read( $section );
		} catch ( \Exception $e ) {
			return false;
		}

		return $section;
	}


	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  1.0.0
	 *
	 * @param  Masteriyo\Database\Model $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_section_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->post_type,
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
	 * Get section data.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\Section $section section instance.
	 * @param string  $context Request context.
	 *                         Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_section_data( $section, $context = 'view' ) {
		$course      = get_post( $section->get_course_id( $context ) );
		$course_name = is_null( $course ) ? '' : $course->post_title;

		$data = array(
			'id'          => $section->get_id(),
			'name'        => wp_specialchars_decode( $section->get_name( $context ) ),
			'permalink'   => $section->get_permalink( $context ),
			'menu_order'  => $section->get_menu_order( $context ),
			'parent_id'   => $section->get_parent_id( $context ),
			'course_id'   => $section->get_course_id( $context ),
			'course_name' => $course_name,
			'description' => 'view' === $context ? wpautop( do_shortcode( $section->get_description() ) ) : $section->get_description( $context ),
		);

		/**
		 * Filter section rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Section data.
		 * @param Masteriyo\Models\Section $section Section object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\SectionsController $controller REST Sections controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $section, $context, $this );
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		// Set post_status.
		$args['post_status'] = PostStatus::PUBLISH;

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
	 * Get the sections'schema, conforming to JSON Schema.
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
				'id'                => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'              => array(
					'description' => __( 'Section name', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created'      => array(
					'description' => __( "The date the section was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created_gmt'  => array(
					'description' => __( 'The date the section was created, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_modified'     => array(
					'description' => __( "The date the section was last modified, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt' => array(
					'description' => __( 'The date the section was last modified, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'description'       => array(
					'description' => __( 'Section description', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'parent_id'         => array(
					'description' => __( 'Section parent ID', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'course_id'         => array(
					'description' => __( 'Course ID', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'course_name'       => array(
					'description' => __( 'Course name', 'masteriyo' ),
					'type'        => 'string',
					'readonly'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'menu_order'        => array(
					'description' => __( 'Menu order, used to custom sort sections.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data'         => array(
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
	 * Prepare a single section for create or update.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Masteriyo\Database\Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id      = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$section = masteriyo( 'section' );

		if ( 0 !== $id ) {
			$section->set_id( $id );
			$section_repo = masteriyo( 'section.store' );
			$section_repo->read( $section );
		}

		// Post title.
		if ( isset( $request['name'] ) ) {
			$section->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Post content.
		if ( isset( $request['description'] ) ) {
			$section->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Post status.
		if ( isset( $request['status'] ) ) {
			$section->set_status( PostStatus::PUBLISH );
		}

		// Menu order.
		if ( isset( $request['menu_order'] ) ) {
			$section->set_menu_order( $request['menu_order'] );
		}

		// Automatically set the menu order if it's not set and the operation is POST.
		if ( ! isset( $request['menu_order'] ) && $creating ) {
			$query = new \WP_Query(
				array(
					'post_type'      => 'mto-section',
					'post_status'    => PostStatus::all(),
					'posts_per_page' => 1,
					'post_parent'    => $request['parent_id'],
				)
			);

			$section->set_menu_order( $query->found_posts );
		}

		// Section parent ID.
		if ( isset( $request['parent_id'] ) ) {
			$section->set_parent_id( $request['parent_id'] );
		}

		// Course ID.
		if ( isset( $request['course_id'] ) ) {
			$section->set_course_id( $request['course_id'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$section->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->post_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Database\Model $section Section object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->post_type}_object", $section, $request, $creating );
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

		$id      = absint( $request['id'] );
		$section = masteriyo_get_section( $id );

		if ( is_null( $section ) ) {
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

		$id      = absint( $request['id'] );
		$section = masteriyo_get_section( $id );

		if ( is_null( $section ) ) {
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
}
