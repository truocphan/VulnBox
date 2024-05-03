<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'fields_select' ) ) :

	class fields_select extends Field_Base {

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
			$this->name     = 'fields_select';
			$this->label    = __( 'ACF Fields', 'acf-frontend-form-element' );
			$this->category = __( 'Form', 'acf-frontend-form-element' );
			$this->defaults = array(
				'fields_select'  => '',
				'fields_exclude' => '',
			);

			acf_enable_filter( 'fields_select' );
			// filters
			add_action( 'wp_ajax_acf/fields/fields_select/query', array( $this, 'fields_select_query' ) );
			add_action( 'wp_ajax_acf/fields/fields_exclude/query', array( $this, 'fields_exclude_query' ) );			

			add_action( 'rest_api_init', array( $this, 'register_fields_list' ) );

		}

		public function register_fields_list() {
			register_rest_route(
				'frontend-admin/v1',
				'/grouped-acf-fields',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_fields_list' ),
					'permission_callback' => function ( $request ) {
						return current_user_can('manage_options');
					},
				)
			);
		}

		function get_fields_list() {
			$field_groups = acf_get_field_groups();
			$fields_by_group = array();
			foreach ($field_groups as $field_group) {
			  $fields = acf_get_fields($field_group);
			  $group_fields = array();
			  foreach ($fields as $field) {
				$group_fields[$field['key']] = $field['label'];
			  }
			  $fields_by_group[$field_group['key']] = array(
				'label' => $field_group['title'],
				'fields' => $group_fields
			  );
			}
			return $fields_by_group;
		  }

		function load_field( $field ) {	
			global $post;	
			if( ! empty( $field['key'] ) ){
				$field['name'] = $field['key'];
			}		
			if ( isset( $post->post_type ) && 
				$post->post_type == 'admin_form' && 
				isset($_GET['action']) && 
				$_GET['action'] === 'edit' ) return $field;
			
			if( ! empty( $field['fields_select'] ) ){
				$field['fields_select'] = $this->get_selected_fields( $field );
			}
			return $field;		
		}
		function prepare_field( $field ) {		
			
			$field['no_wrap'] = 1;
			// load sub fields
			// - sub field name's will be modified to include prefix_name settings
			$field['name']          = $field['key'];
			// return
			return $field;

		}

		function render_field_settings( $field ){
			// temp enable 'local' to allow .json fields to be displayed
			acf_enable_filter( 'local' );

			// default_value
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Fields or Field Groups', 'acf' ),
					'instructions' => __( 'Select one or more fields or field groups', 'acf-frontend-form-element' ),
					'type'         => 'select',
					'name'         => 'fields_select',
					'multiple'     => 1,
					'allow_null'   => 1,
					'choices'      => feadmin_get_selected_fields( $field['fields_select'] ),
					'ui'           => 1,
					'class'		   => 'fields-and-groups',
                    'ajax'         => 1,
					'ajax_action'  => 'acf/fields/fields_select/query',
					'placeholder'  => '',
				)
			);
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Exlude Fields from Field Groups', 'acf' ),
					'instructions' => __( 'Select one or more fields from the field groups to exclude', 'acf-frontend-form-element' ),
					'type'         => 'select',
					'name'         => 'fields_exclude',
					'multiple'     => 1,
					'allow_null'   => 1,
					'choices'      => feadmin_get_selected_fields( $field['fields_exclude'] ),
					'ui'           => 1,
                    'ajax'         => 1,
					'class'		   => 'field-group-fields',
					'ajax_action'  => 'acf/fields/fields_exclude/query',
					'placeholder'  => '',
					'conditions'   => array(
						'field'    => 'fields_select',
						'operator' => '!=empty',
					),
				)
			);

			acf_disable_filter( 'local' );
		}
		function get_selected_fields( $field ) {
			// vars
			$fields = array();

			// bail early if no clone setting
			if ( empty( $field['fields_select'] ) ) {
				return $fields;
			}

			// Loop over selectors and load fields.
			foreach ( $field['fields_select'] as $selector ) {
				// Field Group selector.
				if ( acf_is_field_group_key( $selector ) ) {
					$field_group_fields = acf_get_fields( $selector );

					if ( ! $field_group_fields ) {
						continue;
					}

					if ( ! empty( $field['fields_exclude'] ) ) {
						$fields_to_show = array();
						foreach ( $field_group_fields as $index => $group_field ) {
							if ( ! in_array( $group_field['key'], $field['fields_exclude'] ) ) {
								$fields_to_show[] = $group_field;
							}
						}
						if ( $fields_to_show ) {
							$field_group_fields = $fields_to_show;
						}
					}

					$fields = array_merge( $fields, $field_group_fields );

					// Field selector.
				} elseif ( acf_is_field_key( $selector ) ) {
					$fields[] = acf_get_field( $selector );
				}
			}

			// return
			return $fields;

		}

		function render_field( $field ) {
			// bail early if no sub fields
			if ( empty( $field['fields_select'] ) ) {
				return;
			}

			$sub_fields = $field['fields_select'];

			if( is_string( $sub_fields[0] ) ){
				$sub_fields =  $this->get_selected_fields( $field );
			}

			//acf_hidden_input( array( 'name' => $field['name'] ) );

			$sub_fields = apply_filters( 'frontend_admin/pre_render_fields', $sub_fields );

			// load values
			foreach ( $sub_fields as $index => $sub_field ) {
				if( is_string( $sub_field ) ){
					$sub_field = acf_maybe_get_field( $sub_field );
					if( ! $sub_field ) continue;
				}
				// add value
				if ( ! empty( $field['value'] ) && isset( $field['value'][ $sub_field['key'] ] ) ) {
					// this is a normal value
					$sub_field['value'] = $field['value'][ $sub_field['key'] ];

				} elseif ( isset( $sub_field['default_value'] ) ) {

					// no value, but this sub field has a default value
					$sub_field['value'] = $sub_field['default_value'];

				}
				if( isset( $field['prefix'] ) )	$sub_field['prefix'] = $field['prefix'];
				if ( isset( $field['wrapper']['class'] ) ) {
					$sub_field['wrapper']['class'] .= ' ' . $field['wrapper']['class'];
				}
				if ( isset( $field['wrapper']['data-step'] ) ) {
					$sub_field['wrapper']['data-step'] = $field['wrapper']['data-step'];
				}

				fea_instance()->form_display->render_field_wrap( $sub_field );
			}

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
			global $post, $form_preview;

			// bail early if no sub fields
			if ( empty( $field['fields_select'] ) ) {
				return $value;
			}

			if ( $value ) {
				return $value;
			}

			// load sub fields
			$value = array();


			// loop
			foreach ( $field['fields_select'] as $index => $sub_field ) {
				if( is_string( $sub_field ) ){
					$sub_field = acf_maybe_get_field( $sub_field );
					if( ! $sub_field ) continue;
				}
				// add value
				$value[ $sub_field['key'] ] = acf_get_value( $post_id, $sub_field );

			}

			// return
			return $value;

		}


		/*
		*  validate_value
		*
		*  description
		*
		*  @type    function
		*  @date    11/02/2014
		*  @since   5.0.0
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function validate_value( $valid, $value, $field, $input ) {
			// bail early if no sub fields
			if ( empty( $field['fields_select'] ) ) {
				return $valid;
			}

			$group = explode( 'acff[', $input );

			if ( isset( $group[1] ) ) {
				$group = explode( ']', $group[1] )[0];
			} else {
				return $valid;
			}
			// loop
			foreach ( $field['fields_select'] as $sub_field ) {
				$k         = $sub_field['key'];
				$sub_input = str_replace( $field['key'], $k, $input );
				if ( ! isset( $_POST['acff'][ $group ][ $k ] ) ) {
					continue;
				}

				// validate
				acf_validate_value( $_POST['acff'][ $group ][ $k ], $sub_field, $sub_input );

			}

			// return
			return $valid;

		}

		/*
		*  fields_select_query
		*
		*  description
		*
		*  @type    function
		*  @date    17/06/2016
		*  @since   5.3.8
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function fields_select_query() {

			// validate
			if ( ! acf_verify_ajax() ) {
				die();
			}

			// disable field to allow clone fields to appear selectable
			acf_disable_filter( 'fields_select' );

			// options
			$options = acf_parse_args(
				$_POST,
				array(
					'post_id' => 0,
					'paged'   => 0,
					's'       => '',
					'title'   => '',
					'fields'  => array(),
				)
			);

			// vars
			$results     = array();
			$s           = false;
			$i           = -1;
			$limit       = 20;
			$range_start = $limit * ( $options['paged'] - 1 );  // 0,  20, 40
			$range_end   = $range_start + ( $limit - 1 );         // 19, 39, 59

			// search
			if ( $options['s'] !== '' ) {

				// strip slashes (search may be integer)
				$s = wp_unslash( strval( $options['s'] ) );

			}

			// load groups
			$GLOBALS['only_acf_field_groups'] = 1;
			$field_groups = acf_get_field_groups();
			$GLOBALS['only_acf_field_groups'] = 0;
			$field_group  = false;

			// bail early if no field groups
			if ( empty( $field_groups ) ) {
				die();
			}

			// move current field group to start
			foreach ( array_keys( $field_groups ) as $j ) {

				// check ID
				if ( $field_groups[ $j ]['ID'] !== $options['post_id'] ) {
					continue;
				}

				// extract field group and move to start
				$field_group = acf_extract_var( $field_groups, $j );

				// field group found, stop looking
				break;

			}

			// if field group was not found, this is a new field group (not yet saved)
			if ( ! $field_group ) {

				$field_group = array(
					'ID'    => $options['post_id'],
					'title' => $options['title'],
					'key'   => '',
				);

			}

			// move current field group to start of list
			array_unshift( $field_groups, $field_group );

			// loop
			foreach ( $field_groups as $field_group ) {

				// vars
				$fields   = false;
				$ignore_s = false;
				$data     = array(
					'text'     => $field_group['title'],
					'children' => array(),
				);

				// get fields
				if ( $field_group['ID'] == $options['post_id'] ) {

					$fields = $options['fields'];

				} else {

					$fields = acf_get_fields( $field_group );
					$fields = acf_prepare_fields_for_import( $fields );

				}

				// bail early if no fields
				if ( ! $fields ) {
					continue;
				}

				// show all children for field group search match
				if ( $s !== false && stripos( $data['text'], $s ) !== false ) {

					$ignore_s = true;

				}

				// populate children
				$children   = array();
				$children[] = $field_group['key'];
				foreach ( $fields as $field ) {
					$children[] = $field['key']; }

				// loop
				foreach ( $children as $child ) {

					// bail ealry if no key (fake field group or corrupt field)
					if ( ! $child ) {
						continue;
					}

					// vars
					$text = false;

					// bail early if is search, and $text does not contain $s
					if ( $s !== false && ! $ignore_s ) {

						// get early
						$text = feadmin_get_selected_field( $child );

						// search
						if ( stripos( $text, $s ) === false ) {
							continue;
						}
					}

					// $i
					$i++;

					// bail early if $i is out of bounds
					if ( $i < $range_start || $i > $range_end ) {
						continue;
					}

					// load text
					if ( $text === false ) {
						$text = feadmin_get_selected_field( $child );
					}

					// append
					$data['children'][] = array(
						'id'   => $child,
						'text' => $text,
					);

				}

				// bail early if no children
				// - this group contained fields, but none shown on this page
				if ( empty( $data['children'] ) ) {
					continue;
				}

				// append
				$results[] = $data;

				// end loop if $i is out of bounds
				// - no need to look further
				if ( $i > $range_end ) {
					break;
				}
			}

			// return
			acf_send_ajax_results(
				array(
					'results' => $results,
					'limit'   => $limit,
				)
			);

		}


			/*
		*  fields_exclude_query
		*
		*  description
		*
		*  @type    function
		*  @date    17/06/2016
		*  @since   5.3.8
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function fields_exclude_query() {

			// validate
			if ( ! acf_verify_ajax() ) {
				die();
			}

			// disable field to allow clone fields to appear selectable
			acf_disable_filter( 'fields_select' );

			// options
			$options = acf_parse_args(
				$_POST,
				array(
					'post_id' => 0,
					'paged'   => 0,
					's'       => '',
					'title'   => '',
					'fields'  => array(),
					'groups'  => array(),
				)
			);

			// vars
			$results     = array();
			$s           = false;
			$i           = -1;
			$limit       = 20;
			$range_start = $limit * ( absint( $options['paged'] ) - 1 );  // 0,  20, 40
			$range_end   = $range_start + ( $limit - 1 );         // 19, 39, 59

			// search
			if ( $options['s'] !== '' ) {

				// strip slashes (search may be integer)
				$s = wp_unslash( strval( $options['s'] ) );

			}

			// load groups
			$field_groups = array();
			$GLOBALS['only_acf_field_groups'] = 1;
			
			if( $options['groups'] ){	
				foreach( $options['groups'] as $group ){
					$field_groups[] = acf_get_field_group( $group );
				}
			}
			$GLOBALS['only_acf_field_groups'] = 0;
			$field_group  = false;

			// bail early if no field groups
			if ( empty( $field_groups ) ) {
				die();
			}

			// loop
			foreach ( $field_groups as $field_group ) {

				// vars
				$fields   = false;
				$ignore_s = false;
				$data     = array(
					'text'     => $field_group['title'],
					'children' => array(),
				);

				// get fields
				if ( $field_group['ID'] == $options['post_id'] ) {

					$fields = $options['fields'];

				} else {

					$fields = acf_get_fields( $field_group );
					$fields = acf_prepare_fields_for_import( $fields );

				}

				// bail early if no fields
				if ( ! $fields ) {
					continue;
				}

				// show all children for field group search match
				if ( $s !== false && stripos( $data['text'], $s ) !== false ) {

					$ignore_s = true;

				}

				// populate children
				$children   = array();
				foreach ( $fields as $field ) {
					$children[] = $field['key']; }

				// loop
				foreach ( $children as $child ) {

					// bail ealry if no key (fake field group or corrupt field)
					if ( ! $child ) {
						continue;
					}

					// vars
					$text = false;

					// bail early if is search, and $text does not contain $s
					if ( $s !== false && ! $ignore_s ) {

						// get early
						$text = feadmin_get_selected_field( $child );

						// search
						if ( stripos( $text, $s ) === false ) {
							continue;
						}
					}

					// $i
					$i++;

					// bail early if $i is out of bounds
					if ( $i < $range_start || $i > $range_end ) {
						continue;
					}

					// load text
					if ( $text === false ) {
						$text = feadmin_get_selected_field( $child );
					}

					// append
					$data['children'][] = array(
						'id'   => $child,
						'text' => $text,
					);

				}

				// bail early if no children
				// - this group contained fields, but none shown on this page
				if ( empty( $data['children'] ) ) {
					continue;
				}

				// append
				$results[] = $data;

				// end loop if $i is out of bounds
				// - no need to look further
				if ( $i > $range_end ) {
					break;
				}
			}

			// return
			acf_send_ajax_results(
				array(
					'results' => $results,
					'limit'   => $limit,
				)
			);

		}

	}




endif; // class_exists check


