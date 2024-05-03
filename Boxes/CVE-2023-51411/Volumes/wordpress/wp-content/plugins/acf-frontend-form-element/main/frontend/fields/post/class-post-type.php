<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'post_type' ) ) :

	class post_type extends Field_Base {



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
			$this->name       = 'post_type';
			$this->label      = __( 'Post Type', 'acf-frontend-form-element' );
			  $this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => '',
				'ui'            => 0,
				'ajax'          => 0,
				'placeholder'   => '',
				'return_format' => 'value',
				'field_type'    => 'radio',
				'layout'        => 'vertical',
				'other_choice'  => 0,
			);
			add_filter( 'acf/load_field/type=select', array( $this, 'load_post_type_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}



		function prepare_field( $field ) {
			$all_post_types = acf_get_pretty_post_types();
			if ( empty( $field['choices'] ) ) {
				if ( ! empty( $field['post_type_options'] ) ) {
					foreach ( $field['post_type_options'] as $slug ) {
						$field['choices'][ $slug ] = $all_post_types[ $slug ];
					}
				} else {
					$field['choices'] = $all_post_types;
				}
			}

			return $field;
		}

		function load_post_type_field( $field ) {
			if ( ! empty( $field['custom_post_type'] ) ) {
				$field['type'] = 'post_type';
			}
			return $field;
		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}

		public function load_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$edit_post = get_post( $post_id );
				$value     = $edit_post->post_type;
			}
			return $value;
		}

		function pre_update_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				remove_action( 'acf/save_post', '_acf_do_save_post' );
				wp_update_post(
					array(
						'ID'        => $post_id,
						'post_type' => $value,
					)
				);
				add_action( 'acf/save_post', '_acf_do_save_post' );
			}
			return null;
		}

		public function update_value( $value, $post_id = false, $field = false ) {
			return null;
		}

		function render_field( $field ) {
			$field['type'] = $field['field_type'];
			acf_render_field( $field );

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

		function render_field_settings( $field ) {
			// default_value

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Default Post Type', 'acf-frontend-form-element' ),
					'instructions' => __( 'Appears when creating a new post', 'acf-frontend-form-element' ),
					'type'         => 'select',
					'name'         => 'default_value',
					'ui'           => 0,
					'choices'      => acf_get_pretty_post_types(),
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Post Types', 'acf-frontend-form-element' ),
					'instructions' => __( 'Select the types to choose from', 'acf-frontend-form-element' ),
					'type'         => 'select',
					'name'         => 'post_type_options',
					'multiple'     => 1,
					'ui'           => 1,
					'choices'      => acf_get_pretty_post_types(),
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
		}

	}



endif;


