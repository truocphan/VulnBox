<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'post_to_edit' ) ) :

	class post_to_edit extends Field_Base {



		/*
		*  __construct
		*
		*  This function will setup the field type data
		*
		*  @type    function
		*  @date    5/03/2014
		*  @since   5.0.0
		*
		*  @param   n/a
		*  @return  n/a
		*/

		function initialize() {
			// vars
			$this->name     = 'post_to_edit';
			$this->label    = __( 'Post To Edit', 'acf' );
			$this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->defaults = array(
				'post_type'       => array( 'post' ),
				'taxonomy'        => array(),
				'allow_null'      => 0,
				'add_new'         => 1,
				'add_new_text'    => __( 'New Post', 'acf-frontend-form-element' ),
				'multiple'        => 0,
				'ui'              => 1,
				'no_data_collect' => 1,
			);

			// extra
			add_action( 'wp_ajax_acf/fields/post_to_edit/query', array( $this, 'ajax_query' ) );
			add_action( 'wp_ajax_nopriv_acf/fields/post_to_edit/query', array( $this, 'ajax_query' ) );

		}


		/*
		*  ajax_query
		*
		*  description
		*
		*  @type    function
		*  @date    24/10/13
		*  @since   5.0.0
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function ajax_query() {
			// validate
			if ( ! acf_verify_ajax() ) {
				die();
			}

			// get choices
			$response = $this->get_ajax_query( $_POST );

			// return
			acf_send_ajax_results( $response );

		}


		/*
		*  get_ajax_query
		*
		*  This function will return an array of data formatted for use in a select2 AJAX response
		*
		*  @type    function
		*  @date    15/10/2014
		*  @since   5.0.9
		*
		*  @param   $options (array)
		*  @return  (array)
		*/

		function get_ajax_query( $options = array() ) {
			// defaults
			$options = acf_parse_args(
				$options,
				array(
					'post_id'   => 0,
					's'         => '',
					'field_key' => '',
					'paged'     => 1,
				)
			);

			// load field
			$field = acf_get_field( $options['field_key'] );
			if ( ! $field ) {
				return false;
			}

			// vars
			$results = array();

			if ( ! empty( $field['add_new'] ) && $options['paged'] == 1 && ! $options['s'] ) {
				$new_item = true;
				if ( $field['type'] == 'product_to_edit' ) {
					$default_text = 'New Product';
					$type         = 'product';
				} else {
					$type         = 'post';
					$default_text = 'New Post';
				}
				$add_new_text = ! empty( $field['add_new_text'] ) ? $field['add_new_text'] : __( $default_text, 'acf-frontend-form-element' );
				$results      = array(
					array(
						'id'   => 'add_' . $type,
						'text' => $add_new_text,
					),
				);
			}

			$args      = array();
			$s         = false;
			$is_search = false;

			// paged
			$args['posts_per_page'] = 20;
			$args['paged']          = $options['paged'];

			// search
			if ( $options['s'] !== '' ) {

				// strip slashes (search may be integer)
				$s = wp_unslash( strval( $options['s'] ) );

				// update vars
				$args['s'] = $s;
				$is_search = true;

			}

			// post_type
			if ( ! empty( $field['post_type'] ) ) {

				$args['post_type'] = acf_get_array( $field['post_type'] );

			} else {

				$args['post_type'] = acf_get_post_types();

			}

			// taxonomy
			if ( ! empty( $field['taxonomy'] ) ) {

				// vars
				$terms = acf_decode_taxonomy_terms( $field['taxonomy'] );

				// append to $args
				$args['tax_query'] = array();

				// now create the tax queries
				foreach ( $terms as $k => $v ) {

					$args['tax_query'][] = array(
						'taxonomy' => $k,
						'field'    => 'slug',
						'terms'    => $v,
					);

				}
			}

			if ( ! empty( $field['post_author'] ) ) {
				$args['author'] = get_current_user_id();
			}

			// filters
			$args = apply_filters( 'frontend_admin/fields/post_to_edit/query', $args, $field, $options['post_id'] );
			$args = apply_filters( 'frontend_admin/fields/post_to_edit/query/name=' . $field['name'], $args, $field, $options['post_id'] );
			$args = apply_filters( 'frontend_admin/fields/post_to_edit/query/key=' . $field['key'], $args, $field, $options['post_id'] );

			// get posts grouped by post type
			$groups = acf_get_grouped_posts( $args );

			// bail early if no posts
			if ( empty( $groups ) && ! isset( $new_item ) ) {
				return false;
			}

			// loop
			foreach ( array_keys( $groups ) as $group_title ) {

				// vars
				$posts = acf_extract_var( $groups, $group_title );

				// data
				$data = array(
					'text'     => $group_title,
					'children' => array(),
				);

				// convert post objects to post titles
				foreach ( array_keys( $posts ) as $post_id ) {

					$posts[ $post_id ] = $this->get_post_title( $posts[ $post_id ], $field, $options['post_id'], $is_search );

				}

				// order posts by search
				if ( $is_search && empty( $args['orderby'] ) && isset( $args['s'] ) ) {

					$posts = acf_order_by_search( $posts, $args['s'] );

				}

				// append to $data
				foreach ( array_keys( $posts ) as $post_id ) {

					$data['children'][] = $this->get_post_result( $post_id, $posts[ $post_id ] );

				}

				// append to $results
				$results[] = $data;

			}

			// optgroup or single
			$post_type = acf_get_array( $args['post_type'] );
			if ( count( $post_type ) == 1 ) {
				if ( isset( $new_item ) ) {
					if ( count( $results ) > 1 ) {
						$results = array_merge( array( $results[0] ), $results[1]['children'] );
					}
				} else {
					$results = $results[0]['children'];
				}
			}

			// vars
			$response = array(
				'results' => $results,
				'limit'   => $args['posts_per_page'],
			);

			// return
			return $response;

		}


		/*
		*  get_post_result
		*
		*  This function will return an array containing id, text and maybe description data
		*
		*  @type    function
		*  @date    7/07/2016
		*  @since   5.4.0
		*
		*  @param   $id (mixed)
		*  @param   $text (string)
		*  @return  (array)
		*/

		function get_post_result( $id, $text ) {
			// vars
			$result = array(
				'id'   => $id,
				'text' => $text,
			);

			// look for parent
			$search = '| ' . __( 'Parent', 'acf' ) . ':';
			$pos    = strpos( $text, $search );

			if ( $pos !== false ) {

				$result['description'] = substr( $text, $pos + 2 );
				$result['text']        = substr( $text, 0, $pos );

			}

			// return
			return $result;

		}


		/*
		*  get_post_title
		*
		*  This function returns the HTML for a result
		*
		*  @type    function
		*  @date    1/11/2013
		*  @since   5.0.0
		*
		*  @param   $post (object)
		*  @param   $field (array)
		*  @param   $post_id (int) the post_id to which this value is saved to
		*  @return  (string)
		*/

		function get_post_title( $post, $field, $post_id = 0, $is_search = 0 ) {
			// get post_id
			if ( ! $post_id ) {
				$post_id = acf_get_form_data( 'post_id' );
			}

			// vars
			$title = acf_get_post_title( $post, $is_search );

			// filters
			$title = apply_filters( 'frontend_admin/fields/post_to_edit/result', $title, $post, $field, $post_id );
			$title = apply_filters( 'frontend_admin/fields/post_to_edit/result/name=' . $field['_name'], $title, $post, $field, $post_id );
			$title = apply_filters( 'frontend_admin/fields/post_to_edit/result/key=' . $field['key'], $title, $post, $field, $post_id );

			// return
			return $title;
		}


		/*
		*  render_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param   $field - an array holding all the field's data
		*
		*  @type    action
		*  @since   3.6
		*  @date    23/01/13
		*/

		function render_field( $field ) {
			if ( empty( $field['placeholder'] ) ) {
				$field['placeholder'] = __( 'Select Post', 'acf-frontend-form-element' );
			}

			// Change Field into a select
			$field['allow_null'] = 1;
			$field['type']       = 'select';
			$field['ui']         = 1;
			$field['ajax']       = 1;
			if ( $field['add_new'] ) {
				$add_new_text     = $field['add_new_text'] ? $field['add_new_text'] : __( 'New Post', 'acf-frontend-form-element' );
				$field['choices'] = array( 'add_post' => $add_new_text );
			} else {
				$field['choices'] = array();
			}
			// load posts
			$posts = $this->get_posts( $field['value'], $field );

			if ( $posts ) {

				foreach ( array_keys( $posts ) as $i ) {

					// vars
					$post = acf_extract_var( $posts, $i );

					// append to choices
					$field['choices'][ $post->ID ] = $this->get_post_title( $post, $field );

				}
			}

			// render
			acf_render_field( $field );
		}


		/*
		*  render_field_settings()
		*
		*  Create extra options for your field. This is rendered when editing a field.
		*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
		*
		*  @type    action
		*  @since   3.6
		*  @date    23/01/13
		*
		*  @param   $field  - an array holding all the field's data
		*/

		function render_field_settings( $field ) {
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filter by User', 'acf-frontend-form-element' ),
					'instructions' => __( 'Only show posts by the following users', 'acf-frontend-form-element' ),
					'type'         => 'select',
					'name'         => 'post_author',
					'choices'      => array( 'current_user' => __( 'Current User' ) ),
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => '',
				)
			);
			// default_value
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filter by Post Type', 'acf' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'post_type',
					'choices'      => acf_get_pretty_post_types(),
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => __( 'All post types', 'acf' ),
				)
			);

			// default_value
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filter by Taxonomy', 'acf' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'taxonomy',
					'choices'      => acf_get_taxonomy_terms(),
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => __( 'All taxonomies', 'acf' ),
				)
			);
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Placeholder', 'acf-frontend-form-element' ),
					'instructions' => '',
					'name'         => 'placeholder',
					'type'         => 'text',
					'placeholder'  => __( 'Select Post', 'acf-frontend-form-element' ),
				)
			);
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Add New Post?', 'acf-frontend-form-element' ),
					'instructions' => '',
					'name'         => 'add_new',
					'type'         => 'true_false',
					'ui'           => 1,
				)
			);
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'New Post Text', 'acf-frontend-form-element' ),
					'instructions' => '',
					'name'         => 'add_new_text',
					'type'         => 'text',
					'placeholder'  => __( 'New Post', 'acf-frontend-form-element' ),
					'conditions'   => array(
						array(
							array(
								'field'    => 'add_new',
								'operator' => '==',
								'value'    => 1,
							),
						),
					),
				)
			);

			/*
			 // multiple
			acf_render_field_setting(
			$field,
			array(
			'label'        => __( 'Select multiple values?', 'acf' ),
			'instructions' => '',
			'name'         => 'multiple',
			'type'         => 'true_false',
			'ui'           => 1,
			)
			); */

		}


		/*
		*  load_value()
		*
		*  This filter is applied to the $value after it is loaded from the db
		*
		*  @type    filter
		*  @since   3.6
		*  @date    23/01/13
		*
		*  @param   $value (mixed) the value found in the database
		*  @param   $post_id (mixed) the $post_id from which the value was loaded
		*  @param   $field (array) the field array holding all the field options
		*  @return  $value
		*/

		function load_value( $value, $post_id, $field ) {
			if ( $post_id == 'none' ) {
				return null;
			}

			// return
			return $post_id;

		}

		/*
		*  update_value()
		*
		*  This filter is appied to the $value before it is updated in the db
		*
		*  @type    filter
		*  @since   3.6
		*  @date    23/01/13
		*
		*  @param   $value - the value which will be saved in the database
		*  @param   $post_id - the $post_id of which the value will be saved
		*  @param   $field - the field array holding all the field options
		*
		*  @return  $value - the modified value
		*/

		function update_value( $value, $post_id, $field ) {
			 return null;
		}


		/*
		*  get_posts
		*
		*  This function will return an array of posts for a given field value
		*
		*  @type    function
		*  @date    13/06/2014
		*  @since   5.0.0
		*
		*  @param   $value (array)
		*  @return  $value
		*/

		function get_posts( $value, $field ) {
			// numeric
			$value = acf_get_numeric( $value );

			// bail early if no value
			if ( empty( $value ) ) {
				return false;
			}

			$args = array(
				'post__in'  => $value,
				'post_type' => $field['post_type'],
			);
			if ( ! empty( $field['post_author'] ) ) {
				$args['author'] = get_current_user_id();
			}
			// get posts
			$posts = acf_get_posts( $args );

			// return
			return $posts;

		}

	}




endif; // class_exists check


