<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'post_status' ) ) :

	class post_status extends Field_Base {


		function initialize() {
			$this->name     = 'post_status';
			$this->label    = __( 'Post Status', 'acf-frontend-form-element' );
			$this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->defaults = array(
				'post_status'        => array(),
				'field_type'         => 'radio',
				'choices'            => array(),
				'default_value'      => '',
				'ui'                 => 0,
				'ajax'               => 0,
				'placeholder'        => '',
				'search_placeholder' => '',
				'layout'             => '',
				'toggle'             => 0,
				'allow_custom'       => 0,
				'return_format'      => 'object',
				'post_status'        => array( 'publish', 'draft', 'pending', 'private' ),
			);

			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );
		}

		function pre_update_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$post_to_edit                = array(
					'ID' => $post_id,
				);
				$post_to_edit['post_status'] = $value;
				remove_action( 'acf/save_post', '_acf_do_save_post' );
				wp_update_post( $post_to_edit );
				add_action( 'acf/save_post', '_acf_do_save_post' );

			}
			return null;
		}
		function update_value( $value, $post_id = false, $field = false ) {
			 return null;
		}
		function load_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$edit_post = get_post( $post_id );
				$value     = $edit_post->post_status;
			}
			return $value;
		}

		function get_post_status_choices( $posts_statuses = array(), $description = true ) {
			if ( empty( $posts_statuses ) ) {
				$posts_statuses = get_post_stati( array(), 'names' );
			}

			$return = array();

			// Choices
			if ( ! empty( $posts_statuses ) ) {

				foreach ( $posts_statuses as $post_status ) {

					$post_status_object = get_post_status_object( $post_status );

					$ps_name = $post_status_object->name;

					$return[ $ps_name ] = $post_status_object->label;

					if ( $description ) {
						$return[ $ps_name ] .= ' (' . $ps_name . ')';
					}
				}
			}

			return $return;

		}

		function render_field_settings( $field ) {
			if ( isset( $field['default_value'] ) ) {
				$field['default_value'] = acf_encode_choices( $field['default_value'], false );
			}

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Statuses to choose from', 'acf-frontend-form-element' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'post_status',
					'choices'      => $this->get_post_status_choices(),
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => __( 'All statuses will show', 'acf-frontend-form-element' ),
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Default', 'acf-frontend-form-element' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'default_value',
					'choices'      => $this->get_post_status_choices(),
					'ui'           => 0,
					'allow_null'   => 0,
					'placeholder'  => __( 'Any post status', 'acf-frontend-form-element' ),
				)
			);

			// field_type
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Appearance', 'acf-frontend-form-element' ),
					'instructions' => __( 'Select the appearance of this field', 'acf-frontend-form-element' ),
					'type'         => 'select',
					'name'         => 'field_type',
					'optgroup'     => true,
					'choices'      => array(
						'radio'  => __( 'Radio Buttons', 'acf-frontend-form-element' ),
						'select' => _x( 'Select', 'noun', 'acf-frontend-form-element' ),
					),
				)
			);

			// Select: ui
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Stylised UI', 'acf-frontend-form-element' ),
					'instructions' => '',
					'name'         => 'ui',
					'type'         => 'true_false',
					'ui'           => 1,
					'conditions'   => array(
						array(
							array(
								'field'    => 'field_type',
								'operator' => '==',
								'value'    => 'select',
							),
						),
					),
				)
			);

			// Checkbox: layout
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Layout', 'acf-frontend-form-element' ),
					'instructions' => '',
					'type'         => 'radio',
					'name'         => 'layout',
					'layout'       => 'horizontal',
					'choices'      => array(
						'vertical'   => __( 'Vertical', 'acf-frontend-form-element' ),
						'horizontal' => __( 'Horizontal', 'acf-frontend-form-element' ),
					),
					'conditions'   => array(
						array(
							array(
								'field'    => 'field_type',
								'operator' => '==',
								'value'    => 'radio',
							),
						),
					),
				)
			);

		}


		function prepare_field( $field ) {
			// Set Field Type
			if ( $field['field_type'] == 'checkbox' ) {
				$field['field_type'] = 'radio';
			}
			$field['type']         = $field['field_type'];
			$field['allow_null']   = 0;
			$field['multiple']     = 0;
			$field['other_choice'] = 0;

			// Choices
			$field['choices'] = $this->get_post_status_choices( $field['post_status'], false );

			return $field;

		}

		function format_value( $value, $post_id, $field ) {
			// Bail early
			if ( empty( $value ) ) {
				return $value;
			}

			// Vars
			$is_array = is_array( $value );
			$value    = acf_get_array( $value );

			// Loop
			foreach ( $value as &$v ) {

				// Retrieve Object
				$object = get_post_status_object( $v );

				if ( ! $object || is_wp_error( $object ) ) {
					continue;
				}

				// Return: Object
				if ( $field['return_format'] === 'object' ) {

					$v = $object;

				}
			}

			// Return
			return $value;

		}

	}



endif;
