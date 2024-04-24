<?php
/**
 * Abstract Rest CRUD Controller Class
 *
 * @class    CrudController
 * @package Masteriyo/RestApi
 * @version  1.0.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

use Masteriyo\ModelException;
use Masteriyo\Exceptions\RestException;


defined( 'ABSPATH' ) || exit;

/**
 * CrudController class.
 *
 * @extends PostsC
 */
abstract class CrudController extends RestController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * If object is hierarchical.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $hierarchical = false;

	/**
	 * Controls visibility on frontend.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $public = false;

	/**
	 * Get object.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $id Object ID.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $id ) {
		return new \WP_Error(
			'invalid-method',
			// translators: %s: Class method name.
			sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'masteriyo' ), __METHOD__ ),
			array( 'status' => 405 )
		);
	}

	/**
	 * Get object permalink.
	 *
	 * @since 1.0.0
	 *
	 * @param  object $object Object.
	 *
	 * @return string
	 */
	protected function get_permalink( $object ) {
		return '';
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  1.0.0
	 *
	 * @param  Model           $object  Object data.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		// translators: %s: Class method name.
		return new \WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'masteriyo' ), __METHOD__ ), array( 'status' => 405 ) );
	}

	/**
	 * Prepares one object for create or update operation.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_REST_Request $request Request object.
	 * @param  bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model The prepared item, or WP_Error object on failure.
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		// translators: %s: Class method name.
		return new \WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'masteriyo' ), __METHOD__ ), array( 'status' => 405 ) );
	}

	/**
	 * Get a single item.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$object = $this->get_object( (int) $request['id'] );

		if ( ! $object || 0 === $object->get_id() ) {
			return new \WP_Error( "masteriyo_rest_{$this->object_type}_invalid_id", __( 'Invalid ID', 'masteriyo' ), array( 'status' => 404 ) );
		}

		$data     = $this->prepare_object_for_response( $object, $request );
		$response = rest_ensure_response( $data );

		if ( $this->public ) {
			$response->link_header( 'alternate', $this->get_permalink( $object ), array( 'type' => 'text/html' ) );
		}

		return $response;
	}

	/**
	 * Save an object data.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @param  bool            $creating If is creating a new object.
	 *
	 * @return Model|WP_Error
	 */
	protected function save_object( $request, $creating = false ) {
		try {
			$object = $this->prepare_object_for_database( $request, $creating );

			if ( is_wp_error( $object ) ) {
				return $object;
			}

			$res = $object->save();

			if ( is_wp_error( $res ) ) {
				return $res;
			}

			$new_object = $this->get_object( $object );

			if ( ! $new_object ) {
				throw new ModelException( 'masteriyo_rest_object_not_read', __( 'Failed to read an object!', 'masteriyo' ) );
			}

			return $new_object;
		} catch ( ModelException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
		} catch ( RestException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	}

	/**
	 * Create a single item.
	 *
	 * @since 1.0.00
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		if ( ! empty( $request['id'] ) ) {
			/* translators: %s: post type */
			return new \WP_Error( "masteriyo_rest_{$this->object_type}_exists", sprintf( __( 'Cannot create existing %s.', 'masteriyo' ), $this->object_type ), array( 'status' => 400 ) );
		}

		$object = $this->save_object( $request, true );

		if ( is_wp_error( $object ) ) {
			return $object;
		}

		try {
			$this->update_additional_fields_for_object( $object, $request );

			/**
			 * Fires after a single object is created or updated via the REST API.
			 *
			 * @since 1.0.0
			 *
			 * @param \Masteriyo\Database\Model $object Inserted object.
			 * @param WP_REST_Request $request   Request object.
			 * @param boolean         $creating  True when creating object, false when updating.
			 */
			do_action( "masteriyo_rest_insert_{$this->object_type}_object", $object, $request, true );
		} catch ( ModelException $e ) {
			$object->delete();
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
		} catch ( RestException $e ) {
			$object->delete();
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_object_for_response( $object, $request );
		$response = rest_ensure_response( $response );
		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $object->get_id() ) ) );

		return $response;
	}

	/**
	 * Update a single post.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$object = $this->get_object( (int) $request['id'] );

		if ( ! $object || 0 === $object->get_id() ) {
			return new \WP_Error( "masteriyo_rest_{$this->post_type}_invalid_id", __( 'Invalid ID', 'masteriyo' ), array( 'status' => 400 ) );
		}

		$object = $this->save_object( $request, false );

		if ( is_wp_error( $object ) ) {
			return $object;
		}

		try {
			$this->update_additional_fields_for_object( $object, $request );

			/**
			 * Fires after a single object is created or updated via the REST API.
			 *
			 * @param \Masteriyo\Database\Model $object Inserted object.
			 * @param WP_REST_Request $request   Request object.
			 * @param boolean         $creating  True when creating object, false when updating.
			 */
			do_action( "masteriyo_rest_insert_{$this->object_type}_object", $object, $request, false );
		} catch ( ModelException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
		} catch ( RestException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_object_for_response( $object, $request );
		return rest_ensure_response( $response );
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array(
			'offset'              => $request['offset'],
			'order'               => $request['order'],
			'orderby'             => $request['orderby'],
			'paged'               => $request['page'],
			'post__in'            => $request['include'],
			'post__not_in'        => $request['exclude'],
			'posts_per_page'      => $request['per_page'],
			'name'                => $request['slug'],
			'post_parent__in'     => $request['parent'],
			'post_parent__not_in' => $request['parent_exclude'],
			's'                   => $request['search'],
		);

		if ( 'date' === $args['orderby'] ) {
			$args['orderby'] = 'date ID';
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

		// Force the post_type argument, since it's not a user input variable.
		$args['post_type'] = $this->post_type;

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
		$args = apply_filters( "masteriyo_rest_{$this->post_type}_object_query", $args, $request );

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
	 *
	 * @return array          $query_args
	 */
	protected function prepare_items_query( $prepared_args = array(), $request = null ) {

		$valid_vars = array_flip( $this->get_allowed_query_vars() );
		$query_args = array();
		foreach ( $valid_vars as $var => $index ) {
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

		$query_args['ignore_sticky_posts'] = true;

		if ( 'include' === $query_args['orderby'] ) {
			$query_args['orderby'] = 'post__in';
		} elseif ( 'id' === $query_args['orderby'] ) {
			$query_args['orderby'] = 'ID'; // ID must be capitalized.
		} elseif ( 'slug' === $query_args['orderby'] ) {
			$query_args['orderby'] = 'name';
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
			'date_query',
			'ignore_sticky_posts',
			'offset',
			'post__in',
			'post__not_in',
			'post_parent',
			'post_parent__in',
			'post_parent__not_in',
			'posts_per_page',
			'meta_query',
			'tax_query',
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
	 * Get objects.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $query_args Query args.
	 *
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$query  = new \WP_Query();
		$result = $query->query( $query_args );

		$total_posts = $query->found_posts;
		if ( $total_posts < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $query_args['paged'] );
			$count_query = new \WP_Query();
			$count_query->query( $query_args );
			$total_posts = $count_query->found_posts;
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $result ) ),
			'total'   => (int) $total_posts,
			'pages'   => (int) ceil( $total_posts / (int) $query_args['posts_per_page'] ),
		);
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type Post type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $post_type, $context = 'read', $object_id = 0 ) {
		return $this->permission->rest_check_post_permissions( $post_type, $context, $object_id );
	}

	/**
	 * Get a collection of posts.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$query_args    = $this->prepare_objects_query( $request );
		$query_results = $this->get_objects( $query_args );

		$objects = array();
		foreach ( $query_results['objects'] as $object ) {
			if ( ! $this->check_item_permission( $this->post_type, 'read', $object->get_id() ) ) {
				continue;
			}

			$data      = $this->prepare_object_for_response( $object, $request );
			$objects[] = $this->prepare_response_for_collection( $data );
		}

		/**
		 * Filters objects collection before processing.
		 *
		 * @since 1.0.0
		 *
		 * @param array $objects Objects collection.
		 * @param array $query_vars Query vars.
		 * @param array $query_results Query results.
		 */
		$objects = apply_filters( 'masteriyo_before_process_objects_collection', $objects, $query_args, $query_results );

		if ( is_callable( array( $this, 'process_objects_collection' ) ) ) {
			$objects = $this->process_objects_collection( $objects, $query_args, $query_results );
		}

		/**
		 * Filters objects collection after processing.
		 *
		 * @since 1.0.0
		 *
		 * @param array $objects Objects collection.
		 * @param array $query_vars Query vars.
		 * @param array $query_results Query results.
		 */
		$objects = apply_filters( 'masteriyo_after_process_objects_collection', $objects, $query_args, $query_results );

		$page      = (int) $query_args['paged'];
		$max_pages = $query_results['pages'];

		$response = rest_ensure_response( $objects );
		$response->header( 'X-WP-Total', $query_results['total'] );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		$base = $this->rest_base;
		$base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $base ) ) );

		if ( $page > 1 ) {
			$prev_page = $page - 1;
			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}
			$prev_link = add_query_arg( 'page', $prev_page, $base );
			$response->link_header( 'prev', $prev_link );
		}
		if ( $max_pages > $page ) {
			$next_page = $page + 1;
			$next_link = add_query_arg( 'page', $next_page, $base );
			$response->link_header( 'next', $next_link );
		}

		return $response;
	}

	/**
	 * Delete a single item.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function delete_item( $request ) {
		$force  = isset( $request['force'] ) ? (bool) $request['force'] : true;
		$object = $this->get_object( (int) $request['id'] );
		$result = false;

		if ( ! $object || 0 === $object->get_id() ) {
			return new \WP_Error( "masteriyo_rest_{$this->object_type}_invalid_id", __( 'Invalid ID', 'masteriyo' ), array( 'status' => 404 ) );
		}

		$supports_trash = EMPTY_TRASH_DAYS > 0 && is_callable( array( $object, 'get_status' ) );

		/**
		 * Filter whether an object is trashable.
		 *
		 * Return false to disable trash support for the object.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $supports_trash Whether the object type support trashing.
		 * @param Masteriyo\Database\Model $object The object being considered for trashing support.
		 */
		$supports_trash = apply_filters( "masteriyo_rest_{$this->object_type}_object_trashable", $supports_trash, $object );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_object_for_response( $object, $request );

		// If we're forcing, then delete permanently.
		if ( $force ) {
			$object->delete( $force, $request->get_params() );
			$result = 0 === $object->get_id();
		} else {
			// If we don't support trashing for this type, error out.
			if ( ! $supports_trash ) {
				/* translators: %s: post type */
				return new \WP_Error( 'masteriyo_rest_trash_not_supported', sprintf( __( 'The %s does not support trashing.', 'masteriyo' ), $this->object_type ), array( 'status' => 501 ) );
			}

			// Otherwise, only trash if we haven't already.
			if ( is_callable( array( $object, 'get_status' ) ) ) {
				if ( 'trash' === $object->get_status() ) {
					/* translators: %s: post type */
					return new \WP_Error( 'masteriyo_rest_already_trashed', sprintf( __( 'The %s has already been deleted.', 'masteriyo' ), $this->object_type ), array( 'status' => 410 ) );
				}

				$object->delete( $force, $request->get_params() );
				$result = 'trash' === $object->get_status();
			}
		}

		if ( ! $result ) {
			/* translators: %s: post type */
			return new \WP_Error( 'masteriyo_rest_cannot_delete', sprintf( __( 'The %s cannot be deleted.', 'masteriyo' ), $this->object_type ), array( 'status' => 500 ) );
		}

		/**
		 * Fires after a single object is deleted or trashed via the REST API.
		 *
		 * @since 1.0.0
		 *
		 * @param \Masteriyo\Database\Model $object The deleted or trashed object.
		 * @param WP_REST_Response $response The response data.
		 * @param WP_REST_Request  $request  The request sent to the API.
		 */
		do_action( "masteriyo_rest_delete_{$this->object_type}_object", $object, $response, $request );

		return $response;
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
		$links = array(
			'self'       => array(
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $object->get_id() ) ),
			),
			'collection' => array(
				'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ),
			),
		);

		return $links;
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params                       = array();
		$params['context']            = $this->get_context_param();
		$params['context']['default'] = 'view';

		$params['page']     = array(
			'description'       => __( 'Current page of the collection.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'minimum'           => 1,
		);
		$params['per_page'] = array(
			'description'       => __( 'Maximum number of items to be returned in result set.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => 10,
			'minimum'           => 1,
			'maximum'           => 100,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['search']   = array(
			'description'       => __( 'Limit results to those matching a string.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['after']    = array(
			'description'       => __( 'Limit response to resources published after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['before']   = array(
			'description'       => __( 'Limit response to resources published before a given ISO8601 compliant date.', 'masteriyo' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['exclude']  = array(
			'description'       => __( 'Ensure result set excludes specific IDs.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);
		$params['include']  = array(
			'description'       => __( 'Limit result set to specific ids.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);
		$params['offset']   = array(
			'description'       => __( 'Offset the result set by a specific number of items.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['order']    = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'masteriyo' ),
			'type'              => 'string',
			'default'           => 'desc',
			'enum'              => array( 'asc', 'desc' ),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['orderby']  = array(
			'description'       => __( 'Sort collection by object attribute.', 'masteriyo' ),
			'type'              => 'string',
			'default'           => 'date',
			'enum'              => array(
				'date',
				'id',
				'include',
				'title',
				'slug',
				'modified',
				'menu_order',
				'rand',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);

		if ( $this->hierarchical ) {
			$params['parent']         = array(
				'description'       => __( 'Limit result set to those of particular parent IDs.', 'masteriyo' ),
				'type'              => 'array',
				'items'             => array(
					'type' => 'integer',
				),
				'sanitize_callback' => 'wp_parse_id_list',
				'default'           => array(),
			);
			$params['parent_exclude'] = array(
				'description'       => __( 'Limit result set to all items except those of a particular parent ID.', 'masteriyo' ),
				'type'              => 'array',
				'items'             => array(
					'type' => 'integer',
				),
				'sanitize_callback' => 'wp_parse_id_list',
				'default'           => array(),
			);
		}

		/**
		 * Filter collection parameters for the posts controller.
		 *
		 * The dynamic part of the filter `$this->object_type` refers to the post
		 * type slug for the controller.
		 *
		 * This filter registers the collection parameter, but does not map the
		 * collection parameter to an internal WP_Query parameter. Use the
		 * `rest_{$this->object_type}_query` filter to set WP_Query parameters.
		 *
		 * @since 1.0.0
		 *
		 * @param array        $query_params JSON Schema-formatted collection parameters.
		 * @param WP_object_type $object_type    Post type object.
		 */
		return apply_filters( "rest_{$this->object_type}_collection_params", $params, $this->object_type );
	}

	/**
	 * Clone item.
	 *
	 * @since  1.5.5
	 *
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|Model The prepared item, or WP_Error object on failure.
	 */
	protected function clone_item( $request ) {
		// translators: %s: Class method name.
		return new \WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'masteriyo' ), __METHOD__ ), array( 'status' => 405 ) );
	}
}
