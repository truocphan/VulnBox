<?php
/**
 * Abstract Rest Posts Controller Class
 *
 * @class CommentsController
 * @package Masteriyo/RestApi
 */

namespace Masteriyo\RestApi\Controllers\Version1;

use Masteriyo\Enums\CommentStatus;

defined( 'ABSPATH' ) || exit;

/**
 * CommentsController
 *
 * @package Masteriyo/RestApi
 * @version  1.0.0
 */
abstract class CommentsController extends CrudController {

	/**
	 * Comment Type.
	 *
	 * @since 1.4.10
	 *
	 * @var string
	 */
	protected $comment_type = 'all';

	/**
	 * Retrieves the query params for collections.
	 *
	 * @since 1.0.0
	 *
	 * @return array Comments collection parameters.
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['context']['default'] = 'view';

		$params['search'] = array(
			'description' => __( 'Limit results to those matching a string.', 'masteriyo' ),
			'type'        => 'string',
		);

		$params['after'] = array(
			'description' => __( 'Limit response to comments published after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'        => 'string',
			'format'      => 'date-time',
		);

		$params['user'] = array(
			'description'       => __( 'Limit result set to comments assigned to specific user IDs. Requires authorization.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'sanitize_callback' => 'wp_parse_id_list',
		);

		$params['user_exclude'] = array(
			'description'       => __( 'Ensure result set excludes comments assigned to specific user IDs. Requires authorization.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'sanitize_callback' => 'wp_parse_id_list',
		);

		$params['user_email'] = array(
			'default'     => null,
			'description' => __( 'Limit result set to that from a specific author email. Requires authorization.', 'masteriyo' ),
			'format'      => 'email',
			'type'        => 'string',
		);

		$params['before'] = array(
			'description' => __( 'Limit response to comments published before a given ISO8601 compliant date.', 'masteriyo' ),
			'type'        => 'string',
			'format'      => 'date-time',
		);

		$params['after'] = array(
			'description' => __( 'Limit response to comments published before a given ISO8601 compliant date.', 'masteriyo' ),
			'type'        => 'string',
			'format'      => 'date-time',
		);

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
			'description'       => __( 'Limit result set to specific IDs.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);

		$params['offset'] = array(
			'description' => __( 'Offset the result set by a specific number of items.', 'masteriyo' ),
			'type'        => 'integer',
		);

		$params['order'] = array(
			'description' => __( 'Order sort attribute ascending or descending.', 'masteriyo' ),
			'type'        => 'string',
			'default'     => 'desc',
			'enum'        => array(
				'asc',
				'desc',
			),
		);

		$params['orderby'] = array(
			'description' => __( 'Sort collection by object attribute.', 'masteriyo' ),
			'type'        => 'string',
			'default'     => 'date_gmt',
			'enum'        => array(
				'date',
				'date_gmt',
				'id',
				'include',
				'parent',
			),
		);

		$params['parent'] = array(
			'default'           => array(),
			'description'       => __( 'Limit result set to comments of specific parent IDs.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'sanitize_callback' => 'wp_parse_id_list',
		);

		$params['parent_exclude'] = array(
			'default'           => array(),
			'description'       => __( 'Ensure result set excludes specific parent IDs.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'sanitize_callback' => 'wp_parse_id_list',
		);

		$params['post'] = array(
			'default'           => array(),
			'description'       => __( 'Limit result set to comments assigned to specific post IDs.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'sanitize_callback' => 'wp_parse_id_list',
		);

		$params['status'] = array(
			'default'           => CommentStatus::ALL,
			'description'       => __( 'Limit result set to comments assigned a specific status. Requires authorization.', 'masteriyo' ),
			'sanitize_callback' => 'sanitize_key',
			'type'              => 'string',
			'enum'              => array_merge( array( CommentStatus::ALL ), CommentStatus::readable() ),
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['password'] = array(
			'description' => __( 'The password for the post if it is password protected.', 'masteriyo' ),
			'type'        => 'string',
		);

		/**
		 * Filters REST API collection parameters for the comments controller.
		 *
		 * This filter registers the collection parameter, but does not map the
		 * collection parameter to an internal WP_Comment_Query parameter. Use the
		 * `rest_comment_query` filter to set WP_Comment_Query parameters.
		 *
		 * @since 1.0.0
		 *
		 * @param array $params JSON Schema-formatted collection parameters.
		 */
		return apply_filters( 'masteriyo_rest_comment_collection_params', $params );
	}

	/**
	 * Checks if a given request has access to get a specific item.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$comment = get_comment( (int) $request['id'] );

		if ( $comment && ! $this->permission->rest_check_comment_permissions( 'read', $request['id'] ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you are not allowed to read resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to read items.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_comment_permissions( 'read' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you cannot list resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
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

		if ( ! $this->permission->rest_check_comment_permissions( 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
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

		$comment = get_comment( (int) $request['id'] );

		if ( $comment && ! $this->permission->rest_check_comment_permissions( 'delete', $comment->ID ) ) {
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

		$post = get_comment( (int) $request['id'] );

		if ( $post && ! $this->permission->rest_check_comment_permissions( 'edit', $post->ID ) ) {
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
	 * Check permissions for an item.
	 *
	 * @since 1.0.0
	 *
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Object ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return $this->permission->rest_check_comment_permissions( 'read', $object_id );
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array(
			'search'          => $request['search'],
			'offset'          => $request['offset'],
			'order'           => $request['order'],
			'orderby'         => $request['orderby'],
			'paged'           => $request['page'],
			'comment__in'     => $request['include'],
			'comment__not_in' => $request['exclude'],
			'number'          => $request['per_page'],
			'parent__in'      => $request['parent'],
			'parent__not_in'  => $request['parent_exclude'],
			'author__in'      => $request['user'],
			'author__not_in'  => $request['user_exclude'],
			'author_email'    => $request['user_email'],
			'post__in'        => $request['post'],
			'status'          => $request['status'],
		);

		if ( isset( $this->comment_type ) ) {
			$args['type'] = $this->comment_type;
		}

		if ( 'date' === $args['orderby'] ) {
			$args['orderby'] = 'comment_date comment_ID';
		} elseif ( 'date_gmt' === $args['orderby'] ) {
			$args['orderby'] = 'comment_date_gmt comment_ID';
		}

		$args['date_query'] = array();
		// Set before into date query. Date query must be specified as an array of an array.
		if ( isset( $request['before'] ) ) {
			$args['date_query'][0]['before'] = $request['before'];
		}

		// Set after into date query. Date query must be specified as an array of an array.
		if ( isset( $request['after'] ) ) {
			$args['date_query'][0]['after'] = $request['after'];
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
		$args = apply_filters( "masteriyo_rest_{$this->object_type}_object_query", $args, $request );

		return $this->prepare_items_query( $args, $request );
	}

	/**
	 * Determine the allowed query_vars for a get_items() response and
	 * prepare for WP_Comment_Query.
	 *
	 * @since 1.0.0
	 *
	 * @param array           $prepared_args Prepared arguments.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array          $query_args
	 */
	protected function prepare_items_query( $prepared_args = array(), $request = null ) {
		$query_args = array();
		$valid_vars = $this->get_allowed_query_vars();

		foreach ( $valid_vars as $index => $var ) {
			if ( isset( $prepared_args[ $var ] ) ) {
				/**
				 * Filter the query_vars used in `get_items` for the constructed query.
				 *
				 * The dynamic portion of the hook name, $var, refers to the query_var key.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed $prepared_args[ $var ] The query_var value.
				 */
				$query_args[ $var ] = apply_filters( "masteriyo_rest_query_var-{$var}", $prepared_args[ $var ] ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			}
		}

		if ( 'include' === $query_args['orderby'] ) {
			$query_args['orderby'] = 'comment__in';
		} elseif ( 'id' === $query_args['orderby'] ) {
			$query_args['orderby'] = 'comment_ID'; // ID must be capitalized.
		} elseif ( 'parent' === $query_args['orderby'] ) {
			$query_args['orderby'] = 'comment_parent';
		}

		return $query_args;
	}

	/**
	 * Get all the WP Query vars that are allowed for the API request.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_allowed_query_vars() {
		global $wp;

		/**
		 * Filter the publicly allowed query vars.
		 *
		 * Allows adjusting of the default query vars that are made public.
		 *
		 * @since 1.0.0
		 *
		 * @param array  Array of allowed WP_Query query vars.
		 */
		$valid_vars = apply_filters( 'query_vars', $wp->public_query_vars );

		$post_type_obj = get_post_type_object( $this->post_type );
		if ( null !== $post_type_obj && current_user_can( $post_type_obj->cap->edit_posts ) ) {
			/**
			 * Filter the allowed 'private' query vars for authorized users.
			 *
			 * If the user has the `edit_posts` capability, we also allow use of
			 * private query parameters, which are only undesirable on the
			 * frontend, but are safe for use in query strings.
			 *
			 * To disable anyway, use
			 * `add_filter( 'masteriyo_rest_private_query_vars', '__return_empty_array' );`
			 *
			 * @since 1.0.0
			 *
			 * @param array $private_query_vars Array of allowed query vars for authorized users.
			 */
			$private    = apply_filters( 'masteriyo_rest_private_query_vars', $wp->private_query_vars );
			$valid_vars = array_merge( $valid_vars, $private );
		}

		// Define our own in addition to WP's normal vars.
		$rest_valid = array(
			'search',
			'date_query',
			'offset',
			'post__in',
			'parent__in',
			'parent__not_in',
			'author__in',
			'author__not_in',
			'comment__in',
			'comment__not_in',
			'number',
			'type',
			'status',
			'author_email',
			'author__in',
			'meta_query',
			'meta_key',
			'meta_value',
			'meta_compare',
			'meta_value_num',
		);

		$valid_vars = array_merge( $valid_vars, $rest_valid );

		/**
		 * Filter allowed query vars for the REST API.
		 *
		 * This filter allows you to add or remove query vars from the final allowed
		 * list for all requests, including unauthenticated ones. To alter the
		 * vars for editors only.
		 *
		 * @since 1.0.0
		 *
		 * @param array {
		 *    Array of allowed WP_Query query vars.
		 *
		 *    @param string $allowed_query_var The query var to allow.
		 * }
		 */
		$valid_vars = apply_filters( 'masteriyo_rest_query_vars', $valid_vars );
		$valid_vars = array_values( array_unique( $valid_vars ) );

		return $valid_vars;
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.4.10
	 *
	 * @param array $objects Courses data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Courses query result data.
	 *
	 * @return array
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		return array(
			'data' => $objects,
			'meta' => array(
				'total'          => $query_results['total'],
				'pages'          => $query_results['pages'],
				'current_page'   => $query_args['paged'],
				'per_page'       => $query_args['number'],
				'comments_count' => $this->get_comments_count(),
			),
		);
	}

	/**
	 * Get comments count by status.
	 *
	 * @since 1.4.10
	 *
	 * @return Array
	 */
	protected function get_comments_count() {
		$post_count = (array) masteriyo_count_comments( $this->comment_type );

		$post_count         = array_map( 'absint', $post_count );
		$approve_hold_count = masteriyo_array_only( $post_count, array( CommentStatus::HOLD_STR, CommentStatus::APPROVE_STR ) );
		$post_count['all']  = array_sum( $approve_hold_count );

		return $post_count;
	}

	/**
	 * Clone comment.
	 *
	 * @since 1.5.5
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function clone_item( $request ) {
		$old_comment = get_comment( (int) $request['id'] );

		if ( ! $old_comment ) {
			return new \WP_Error( "masteriyo_rest_{$this->object_type}_invalid_id", __( 'Invalid ID', 'masteriyo' ), array( 'status' => 404 ) );
		}

		$new_comment = array(
			'comment_agent'        => $old_comment->comment_agent,
			'comment_approved'     => $old_comment->comment_approved,
			'comment_author'       => $old_comment->comment_author,
			'comment_author_email' => $old_comment->comment_author_email,
			'comment_author_IP'    => $old_comment->comment_author_IP,
			'comment_author_url'   => $old_comment->comment_author_url,
			'comment_content'      => $old_comment->comment_content,
			'comment_date'         => $old_comment->comment_date,
			'comment_date_gmt'     => $old_comment->comment_date_gmt,
			'comment_karma'        => $old_comment->comment_karma,
			'comment_parent'       => $old_comment->comment_parent,
			'comment_post_ID'      => $old_comment->comment_post_ID,
			'comment_type'         => $old_comment->comment_type,
			'user_id'              => $old_comment->user_id,
		);

		$new_comment_id = wp_insert_comment( $new_comment );

		if ( ! $new_comment_id ) {
			return new \WP_Error( "masteriyo_rest_{$this->object_type}_cannot_clone", __( 'Unable to clone', 'masteriyo' ), array( 'status' => 400 ) );
		}

		// Clone all the meta data.
		$meta_data = get_comment_meta( absint( $request['id'] ) );
		foreach ( $meta_data as $meta_key => $meta_value ) {
			update_comment_meta( $new_comment_id, $meta_key, maybe_unserialize( $meta_value[0] ) );
		}

		// Read the new comment.
		$object = $this->get_object( $new_comment_id );

		if ( ! $object || 0 === $object->get_id() ) {
			return new \WP_Error( "masteriyo_rest_{$this->object_type}_invalid_id", __( 'Invalid ID', 'masteriyo' ), array( 'status' => 404 ) );
		}

		$data     = $this->prepare_object_for_response( $object, $request );
		$response = rest_ensure_response( $data );

		if ( $this->public ) {
			$response->link_header( 'alternate', $this->get_permalink( $object ), array( 'type' => 'text/html' ) );
		}

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.5.5
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WP_Comment          $old_comment Old comment.
		 * @param WP_Comment          $new_comment New comment.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_clone_prepare_{$this->object_type}_object", $response, $old_comment, $new_comment, $request );
	}

	/**
	 * Restore review.
	 *
	 * @since 1.6.5
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function restore_items( $request ) {
		$restored_objects = array();
		$query            = new \WP_Comment_Query(
			array(
				'comment__in' => wp_parse_id_list( $request['ids'] ),
				'number'      => 9999999,
				'status'      => CommentStatus::TRASH,
			)
		);
		$objects          = array_map( array( $this, 'get_object' ), $query->comments );

		foreach ( $objects as $object ) {
			if ( ! $object || 0 === $object->get_id() ) {
				continue;
			}

			wp_untrash_comment( $object->get_id() );

			// Read course review again.
			$object = $this->get_object( $object->get_id() );

			$data               = $this->prepare_object_for_response( $object, $request );
			$restored_objects[] = $this->prepare_response_for_collection( $data );
		}

		return rest_ensure_response( $restored_objects );
	}

	/**
	 * Delete multiple items.
	 *
	 * @since 1.6.5
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function delete_items( $request ) {
		$force           = isset( $request['force'] ) ? (bool) $request['force'] : true;
		$deleted_objects = array();

		$request->set_param( 'context', 'edit' );

		$query   = new \WP_Comment_Query(
			array(
				'comment__in' => wp_parse_id_list( $request['ids'] ),
				'number'      => 9999999,
				'status'      => CommentStatus::all(),
			)
		);
		$objects = array_map( array( $this, 'get_object' ), $query->comments );

		foreach ( $objects as $object ) {
			if ( ! $object || 0 === $object->get_id() ) {
				continue;
			}

			$data           = $this->prepare_object_for_response( $object, $request );
			$supports_trash = EMPTY_TRASH_DAYS > 0 && is_callable( array( $object, 'get_status' ) );

			/**
			 * Filter whether an object is trashable.
			 *
			 * Return false to disable trash support for the object.
			 *
			 * @since 1.6.5
			 *
			 * @param boolean $supports_trash Whether the object type support trashing.
			 * @param Masteriyo\Database\Model $object The object being considered for trashing support.
			 */
			$supports_trash = apply_filters( "masteriyo_rest_{$this->object_type}_object_trashable", $supports_trash, $object );

			if ( $force ) {
				$object->delete( $force, $request->get_params() );

				if ( 0 === $object->get_id() ) {
					$deleted_objects[] = $this->prepare_response_for_collection( $data );
				}
			} else {
				if ( ! $supports_trash ) {
					continue;
				}

				if ( is_callable( array( $object, 'get_status' ) ) ) {
					if ( CommentStatus::TRASH === $object->get_status() ) {
						continue;
					}

					$object->delete( $force, $request->get_params() );

					if ( CommentStatus::TRASH === $object->get_status() ) {
						$deleted_objects[] = $this->prepare_response_for_collection( $data );
					}
				}
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
		 * Fires after a multiple objects is deleted or trashed via the REST API.
		 *
		 * @since 1.6.5
		 *
		 * @param array $deleted_objects Objects collection which are deleted.
		 * @param array $objects Objects which are supposed to be deleted.
		 * @param WP_REST_Request  $request  The request sent to the API.
		 */
		do_action( "masteriyo_rest_bulk_delete_{$this->object_type}_objects", $deleted_objects, $objects, $request );

		return rest_ensure_response( $deleted_objects );
	}

	/**
	 * Check if a given request has access to delete items.
	 *
	 * @since 1.6.5
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @return \WP_Error|boolean
	 */
	public function delete_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_comment_permissions( 'delete' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you are not allowed to delete resources', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}
}
