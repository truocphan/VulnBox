<?php
/**
 * Abstract Rest Terms Controller
 *
 * @package RestApi
 * @version  1.0.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

use Masteriyo\RestApi\Controllers\Version1\CrudController;
use Masteriyo\Helper\Permission;

defined( 'ABSPATH' ) || exit;

/**
 * Terms controller class.
 */
abstract class RestTermsController extends CrudController {

	/**
	 * Taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy = '';

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
	 * Register the routes for terms.
	 *
	 * @since 1.0.0
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
					'args'                => array_merge(
						$this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
						array(
							'name' => array(
								'type'        => 'string',
								'description' => __( 'Name for the resource.', 'masteriyo' ),
								'required'    => true,
							),
						)
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
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
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
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
							'type'        => 'boolean',
							'description' => __( 'Required to be true, as resource does not support trashing.', 'masteriyo' ),
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/batch',
			array(
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'batch_items' ),
					'permission_callback' => array( $this, 'batch_items_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::EDITABLE ),
				),
				'schema' => array( $this, 'get_public_batch_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/delete',
			array(
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_items' ),
					'permission_callback' => array( $this, 'delete_items_permissions_check' ),
					'args'                => array(
						'ids'   => array(
							'required'    => true,
							'description' => __( 'Term IDs.', 'masteriyo' ),
							'type'        => 'array',
						),
						'force' => array(
							'default'     => false,
							'type'        => 'boolean',
							'description' => __( 'Required to be true, as resource does not support trashing.', 'masteriyo' ),
						),
					),
				),
			)
		);
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 1.0.0
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return true;
	}

	/**
	 * Check if a given request has access to read the terms.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'read' );

		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new \WP_Error( 'masteriyo_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'masteriyo' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check if a given request has access to create a term.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'create' );

		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new \WP_Error( 'masteriyo_rest_cannot_create', __( 'Sorry, you are not allowed to create resources.', 'masteriyo' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check if a given request has access to read a term.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'read' );

		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new \WP_Error( 'masteriyo_rest_cannot_view', __( 'Sorry, you cannot view this resource.', 'masteriyo' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check if a given request has access to update a term.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'edit' );

		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new \WP_Error( 'masteriyo_rest_cannot_edit', __( 'Sorry, you are not allowed to edit this resource.', 'masteriyo' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check if a given request has access to delete a term.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'delete' );

		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new \WP_Error( 'masteriyo_rest_cannot_delete', __( 'Sorry, you are not allowed to delete this resource.', 'masteriyo' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check if a given request has access to delete terms.
	 *
	 * @since 1.6.0
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @return \WP_Error|boolean
	 */
	public function delete_items_permissions_check( $request ) {
		$taxonomy = $this->get_taxonomy( $request );

		if ( ! $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
			return new \WP_Error( 'masteriyo_rest_taxonomy_invalid', __( 'Taxonomy does not exist.', 'masteriyo' ), array( 'status' => 404 ) );
		}

		return $this->permission->rest_check_term_permissions( $taxonomy, 'delete' );
	}

	/**
	 * Delete multiple items.
	 *
	 * @since 1.6.0
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function delete_items( $request ) {
		$force           = isset( $request['force'] ) ? (bool) $request['force'] : true;
		$deleted_objects = array();

		$objects = $this->get_objects(
			array(
				'taxonomy'   => $this->taxonomy,
				'include'    => $request['ids'],
				'hide_empty' => false,
				'number'     => 100,
			)
		);

		$objects = isset( $objects['objects'] ) ? $objects['objects'] : array();

		$request->set_param( 'context', 'edit' );

		foreach ( $objects as $object ) {
			if ( ! $this->permission->rest_check_term_permissions( $this->taxonomy, 'delete', $object->get_id() ) ) {
				continue;

			}
			$data = $this->prepare_object_for_response( $object, $request );

			$object->delete( $force, $request->get_params() );

			if ( 0 === $object->get_id() ) {
				$deleted_objects[] = $this->prepare_response_for_collection( $data );
			}
		}

		if ( empty( $deleted_objects ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_bulk_delete',
				/* translators: %s: post type */
				sprintf( __( 'The %s cannot be bulk deleted.', 'masteriyo' ), $this->object_type ),
				array( 'status' => 500 )
			);
		}

		/**
		 * Fires after a multiple objects is deleted via the REST API.
		 *
		 * @since 1.6.0
		 *
		 * @param array $deleted_objects Objects collection which are deleted.
		 * @param array $objects Objects which are supposed to be deleted.
		 * @param \WP_REST_Request  $request  The request sent to the API.
		 */
		do_action( "masteriyo_rest_bulk_delete_{$this->object_type}_objects", $deleted_objects, $objects, $request );

		return rest_ensure_response( $deleted_objects );
	}

	/**
	 * Check if a given request has access batch create, update and delete items.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error
	 */
	public function batch_items_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'batch' );

		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new \WP_Error( 'masteriyo_rest_cannot_batch', __( 'Sorry, you are not allowed to batch manipulate this resource.', 'masteriyo' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check permissions.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @param string          $context Request context.
	 * @return bool|WP_Error
	 */
	protected function check_permissions( $request, $context = 'read' ) {
		// Get taxonomy.
		$taxonomy = $this->get_taxonomy( $request );
		if ( ! $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
			return new \WP_Error( 'masteriyo_rest_taxonomy_invalid', __( 'Taxonomy does not exist.', 'masteriyo' ), array( 'status' => 404 ) );
		}

		// Check permissions for a single term.
		$id = (int) $request['id'];

		if ( $id ) {
			$term = get_term( $id, $taxonomy );

			if ( is_wp_error( $term ) || ! $term || $term->taxonomy !== $taxonomy ) {
				return new \WP_Error( 'masteriyo_rest_term_invalid', __( 'Resource does not exist.', 'masteriyo' ), array( 'status' => 404 ) );
			}

			return $this->permission->rest_check_term_permissions( $taxonomy, $context, $term->term_id );
		}

		return $this->permission->rest_check_term_permissions( $taxonomy, $context );
	}

	/**
	 * Prepare links for the request.
	 *
	 * @since 1.0.0
	 *
	 * @param Model           $term   Model object.
	 * @param WP_REST_Request $request Full details about the request.
	 * @return array Links for the given term.
	 */
	protected function prepare_links( $term, $request ) {
		$base = '/' . $this->namespace . '/' . $this->rest_base;

		$links = array(
			'self'       => array(
				'href' => rest_url( trailingslashit( $base ) . $term->get_id() ),
			),
			'collection' => array(
				'href' => rest_url( $base ),
			),
		);

		if ( $term->get_parent_id() ) {
			$parent_term = get_term( (int) $term->get_parent_id(), $term->get_taxonomy() );
			if ( $parent_term ) {
				$links['up'] = array(
					'href' => rest_url( trailingslashit( $base ) . $parent_term->term_id ),
				);
			}
		}

		return $links;
	}

	/**
	 * Get the terms attached to a course.
	 *
	 * @since 1.0.0
	 *
	 * This is an alternative to `get_terms()` that uses `get_the_terms()`
	 * instead, which hits the object cache. There are a few things not
	 * supported, notably `include`, `exclude`. In `self::get_items()` these
	 * are instead treated as a full query.
	 *
	 * @param WP_REST_Request $request       Full details about the request.
	 *
	 * @return array List of term objects. (Total count in `$this->total_terms`).
	 */
	protected function prepare_objects_query( $request ) {
		$taxonomy = $this->get_taxonomy( $request );

		$args = array(
			'taxonomy'   => $taxonomy,
			'order'      => $request['order'],
			'orderby'    => $request['orderby'],
			'offset'     => ( $request['page'] - 1 ) * $request['per_page'],
			'paged'      => $request['page'],
			'number'     => $request['per_page'],
			'slug'       => $request['slug'],
			'hide_empty' => $request['hide_empty'],
			'search'     => $request['search'],
		);

		if ( isset( $request['course'] ) ) {
			$args['course'] = absint( $request['course'] );
		}

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( "masteriyo_rest_{$this->taxonomy}_object_query", $args, $request );

		return $this->prepare_items_query( $args, $request );
	}

	/**
	 * Determine the allowed query_vars for a get_items() response and
	 * prepare for WP_Query.
	 *
	 * @since 1.0.0
	 *
	 * @param array           $prepared_args Prepared arguments.
	 * @param WP_REST_Request $request Request object.
	 * @return array          $query_args
	 */
	protected function prepare_items_query( $prepared_args = array(), $request = null ) {
		return $prepared_args;
	}

	/**
	 * Get objects.
	 *
	 * @since  1.0.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$query       = new \WP_Term_Query();
		$result      = $query->query( $query_args );
		$count_args  = array_filter(
			$query_args,
			function ( $value, $key ) {
				return ! empty( $value ) && ! in_array( $key, array( 'paged', 'offset', 'number' ), true );
			},
			ARRAY_FILTER_USE_BOTH
		);
		$total_posts = wp_count_terms( $count_args );

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $result ) ),
			'total'   => (int) $total_posts,
			'pages'   => (int) ceil( $total_posts / (int) $query->query_vars['number'] ),
		);
	}

	/**
	 * Comparison function for sorting terms by a column.
	 *
	 * Uses `$this->sort_column` to determine field to sort by.
	 *
	 * @since 1.0.0
	 *
	 * @param stdClass $left Term object.
	 * @param stdClass $right Term object.
	 * @return int <0 if left is higher "priority" than right, 0 if equal, >0 if right is higher "priority" than left.
	 */
	protected function compare_terms( $left, $right ) {
		$col       = $this->sort_column;
		$left_val  = $left->$col;
		$right_val = $right->$col;

		if ( is_int( $left_val ) && is_int( $right_val ) ) {
			return $left_val - $right_val;
		}

		return strcmp( $left_val, $right_val );
	}

	/**
	 * Get the query params for collections
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		if ( '' !== $this->taxonomy && taxonomy_exists( $this->taxonomy ) ) {
			$taxonomy = get_taxonomy( $this->taxonomy );
		} else {
			$taxonomy               = new \stdClass();
			$taxonomy->hierarchical = true;
		}

		$params['context']['default'] = 'view';

		$params['exclude'] = array(
			'description'       => __( 'Ensure result set excludes specific IDs.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);
		$params['include'] = array(
			'description'       => __( 'Limit result set to specific ids.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);
		if ( ! $taxonomy->hierarchical ) {
			$params['page'] = array(
				'description'       => __( 'Page of the items.', 'masteriyo' ),
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			);
		}
		$params['order']      = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_key',
			'default'           => 'desc',
			'enum'              => array(
				'asc',
				'desc',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['orderby']    = array(
			'description'       => __( 'Sort collection by resource attribute.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_key',
			'default'           => 'id',
			'enum'              => array(
				'id',
				'include',
				'name',
				'slug',
				'term_group',
				'description',
				'count',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['hide_empty'] = array(
			'description'       => __( 'Whether to hide resources not assigned to any courses.', 'masteriyo' ),
			'type'              => 'boolean',
			'default'           => false,
			'validate_callback' => 'rest_validate_request_arg',
		);
		if ( $taxonomy->hierarchical ) {
			$params['parent'] = array(
				'description'       => __( 'Limit result set to resources assigned to a specific parent.', 'masteriyo' ),
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			);
		}
		$params['course'] = array(
			'description'       => __( 'Limit result set to resources assigned to a specific course.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => null,
			'validate_callback' => 'masteriyo_is_course',
		);
		$params['slug']   = array(
			'description'       => __( 'Limit result set to resources with a specific slug.', 'masteriyo' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 * @return int|\WP_Error
	 */
	protected function get_taxonomy( $request ) {
		return $this->taxonomy;
	}
}
