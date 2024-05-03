<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'post_author' ) ) :

	class post_author extends Field_Base {
		/*
		*  initialize
		*
		*  This function will setup the field type data
		*
		*  @type    function
		*  @date    5/03/2014
		*  @since    5.0.0
		*
		*  @param    n/a
		*  @return    n/a
		*/
		function initialize() {
			// vars
			$this->name       = 'post_author';
			$this->label      = __( 'Author', 'acf-frontend-form-element' );
			  $this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'data_name'     => 'author',
				'role'          => '',
				'multiple'      => 0,
				'allow_null'    => 0,
				'return_format' => 'array',
			);

			$this->field_types = array( 'post_author', 'product_author' );

			add_filter( 'acf/load_field/type=user', array( $this, 'load_post_author_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

					// Add AJAX query.
			add_action( 'wp_ajax_fea/fields/post_author/query', array( $this, 'ajax_query' ) );
			add_action( 'wp_ajax_nopriv_fea/fields/post_author/query', array( $this, 'ajax_query' ) );
		}

		/**
		 * Callback for the AJAX query request.
		 *
		 * @date  24/10/13
		 * @since 5.0.0
		 *
		 * @param  void
		 * @return void
		 */
		function ajax_query() {
			if ( ! acf_verify_ajax() ) {
				wp_send_json_error( __( 'Invalid nonce.', 'acf-frontend-form-element' ) );
			}
			 // Modify Request args.
			if ( isset( $_REQUEST['s'] ) ) {
				$_REQUEST['search'] = sanitize_text_field( $_REQUEST['s'] );
			}
			if ( isset( $_REQUEST['paged'] ) ) {
				$_REQUEST['page'] = sanitize_text_field( $_REQUEST['paged'] );
			}

			// Add query hooks.
			add_action( 'acf/ajax/query_users/init', array( $this, 'ajax_query_init' ), 10, 2 );
			add_filter( 'acf/ajax/query_users/args', array( $this, 'ajax_query_args' ), 10, 3 );
			add_filter( 'acf/ajax/query_users/result', array( $this, 'ajax_query_result' ), 10, 3 );
			add_filter( 'acf/ajax/query_users/search_columns', array( $this, 'ajax_query_search_columns' ), 10, 4 );

			// Simulate AJAX request.
			acf_get_instance( 'ACF_Ajax_Query_Users' )->request();
		}

		/**
		 * Runs during the AJAX query initialization.
		 *
		 * @date  9/3/20
		 * @since 5.8.8
		 *
		 * @param  array          $request The query request.
		 * @param  ACF_Ajax_Query $query   The query object.
		 * @return void
		 */
		function ajax_query_init( $request, $query ) {
			// Require field and make sure it's a user field.
			if ( ! $query->field || ! in_array( $query->field['type'], $this->field_types ) ) {
				$query->send( new WP_Error( 'acf_missing_field', __( 'Error loading field.', 'acf' ), array( 'status' => 404 ) ) );
			}

			// Verify that this is a legitimate request using a separate nonce from the main AJAX nonce.
			if ( ! isset( $_REQUEST['author_query_nonce'] ) || ! wp_verify_nonce( $_REQUEST['author_query_nonce'], 'fea/fields/post_author/query' . $query->field['key'] ) ) {
				$query->send( new WP_Error( 'acf_invalid_request', __( 'Invalid request.', 'acf' ), array( 'status' => 404 ) ) );
			}
		}

		/**
		 * Filters the AJAX query args.
		 *
		 * @date  9/3/20
		 * @since 5.8.8
		 *
		 * @param  array          $args    The query args.
		 * @param  array          $request The query request.
		 * @param  ACF_Ajax_Query $query   The query object.
		 * @return array
		 */
		function ajax_query_args( $args, $request, $query ) {
			// Add specific roles.
			if ( $query->field['role'] ) {
				$args['role__in'] = acf_array( $query->field['role'] );
			}

			/**
			 * Filters the query args.
			 *
			 * @date  21/5/19
			 * @since 5.8.1
			 *
			 * @param array $args The query args.
			 * @param array $field The ACF field related to this query.
			 * @param (int|string) $post_id The post_id being edited.
			 */
			return apply_filters( 'acf/fields/user/query', $args, $query->field, $query->post_id );
		}

		/**
		 * Filters the WP_User_Query search columns.
		 *
		 * @date  9/3/20
		 * @since 5.8.8
		 *
		 * @param  array         $columns       An array of column names to be searched.
		 * @param  string        $search        The search term.
		 * @param  WP_User_Query $WP_User_Query The WP_User_Query instance.
		 * @return array
		 */
		function ajax_query_search_columns( $columns, $search, $WP_User_Query, $query ) {
			/**
			 * Filters the column names to be searched.
			 *
			 * @date  21/5/19
			 * @since 5.8.1
			 *
			 * @param array $columns An array of column names to be searched.
			 * @param string $search The search term.
			 * @param WP_User_Query $WP_User_Query The WP_User_Query instance.
			 * @param array $field The ACF field related to this query.
			 */
			return apply_filters( 'acf/fields/user/search_columns', $columns, $search, $WP_User_Query, $query->field );
		}

		/**
		 * Filters the AJAX Query result.
		 *
		 * @date  9/3/20
		 * @since 5.8.8
		 *
		 * @param  array          $item  The choice id and text.
		 * @param  WP_User        $user  The user object.
		 * @param  ACF_Ajax_Query $query The query object.
		 * @return array
		 */
		function ajax_query_result( $item, $user, $query ) {
			/**
			 * Filters the result text.
			 *
			 * @date  21/5/19
			 * @since 5.8.1
			 *
			 * @param string The result text.
			 * @param WP_User $user The user object.
			 * @param array $field The ACF field related to this query.
			 * @param (int|string) $post_id The post_id being edited.
			 */
			$item['text'] = apply_filters( 'acf/fields/user/result', $item['text'], $user, $query->field, $query->post_id );
			return $item;
		}


		/**
		 * load_post_author_field
		 *
		 * @param  mixed $field
		 * @return array
		 */
		function load_post_author_field( $field ) {
			if ( ! empty( $field['custom_post_author'] ) ) {
				$field['type'] = 'post_author';
			}
			return $field;
		}

		/**
		 * load_field
		 *
		 * @param  mixed $field
		 * @return void
		 */
		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}

		/**
		 * load_value
		 *
		 * @param  mixed $value
		 * @param  mixed $post_id
		 * @param  mixed $field
		 * @return mixed
		 */
		public function load_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$edit_post = get_post( $post_id );
				$value     = $edit_post->post_author;
			} else {
				$value = get_current_user_id();
			}
			return $value;
		}

		/**
		 * pre_update_value
		 *
		 * @param  mixed $value
		 * @param  mixed $post_id
		 * @param  mixed $field
		 * @return mixed
		 */
		function pre_update_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$post_to_edit                = array(
					'ID' => $post_id,
				);
				$post_to_edit['post_author'] = $value;
				remove_action( 'acf/save_post', '_acf_do_save_post' );
				wp_update_post( $post_to_edit );
				add_action( 'acf/save_post', '_acf_do_save_post' );

			}
			return $value;
		}

		/**
		 * update_value
		 *
		 * @param  mixed $value
		 * @param  mixed $post_id
		 * @param  mixed $field
		 * @return mixed
		 */
		public function update_value( $value, $post_id = false, $field = false ) {
			return null;
		}

		/**
		 * Renders the field input HTML.
		 *
		 * @date  23/01/13
		 * @since 3.6.0
		 *
		 * @param  array $field The ACF field.
		 * @return void
		 */
		function render_field( $field ) {
			// Change Field into a select.
			$field['type']        = 'select';
			$field['ui']          = 1;
			$field['ajax']        = 1;
			$field['ajax_action'] = 'fea/fields/post_author/query';
			$field['multiple']    = 0;
			$field['choices']     = array();
			$field['query_nonce'] = wp_create_nonce( 'fea/fields/post_author/query' . $field['key'] );

			// Populate choices.
			if ( $field['value'] ) {

				// Clean value into an array of IDs.
				$user_ids = array_map( 'intval', acf_array( $field['value'] ) );

				// Find users in database (ensures all results are real).
				$users = acf_get_users(
					array(
						'include' => $user_ids,
					)
				);

				// Append.
				if ( $users ) {
					foreach ( $users as $user ) {
						$field['choices'][ $user->ID ] = $this->get_result( $user, $field );
					}
				}
			}

			// Render.
			acf_render_field( $field );
		}

		/**
		 * Returns the result text for a fiven WP_User object.
		 *
		 * @date  1/11/2013
		 * @since 5.0.0
		 *
		 * @param  WP_User      $user    The WP_User object.
		 * @param  array        $field   The ACF field related to this query.
		 * @param  (int|string) $post_id The post_id being edited.
		 * @return string
		 */
		function get_result( $user, $field, $post_id = 0 ) {
			// Get user result item.
			$item = acf_get_user_result( $user );

			// Default $post_id to current post being edited.
			$post_id = $post_id ? $post_id : acf_get_form_data( 'post_id' );

			/**
			 * Filters the result text.
			 *
			 * @date  21/5/19
			 * @since 5.8.1
			 *
			 * @param array $args The query args.
			 * @param array $field The ACF field related to this query.
			 * @param (int|string) $post_id The post_id being edited.
			 */
			return apply_filters( 'acf/fields/user/result', $item['text'], $user, $field, $post_id );
		}


		/*
		*  render_field_settings()
		*
		*  Create extra options for your field. This is rendered when editing a field.
		*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
		*
		*  @type    action
		*  @since    3.6
		*  @date    23/01/13
		*
		*  @param    $field    - an array holding all the field's data
		*/

		public function render_field_settings( $field ) {
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filter by role', 'acf' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'role',
					'choices'      => acf_get_user_role_labels(),
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => __( 'All user roles', 'acf' ),
				)
			);

		}


	}



endif;


