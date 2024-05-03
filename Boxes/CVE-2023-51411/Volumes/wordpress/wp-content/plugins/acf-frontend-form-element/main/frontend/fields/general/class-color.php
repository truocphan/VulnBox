<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'color' ) ) :

	class color extends text {

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
			$this->name     = 'color';
			$this->label    = __( 'Color', 'acf-frontend-form-element' );
			$this->defaults = array(
				'default_value' => '',
			);

		}



		/*
		*  render_field_settings()
		*
		*  Create extra options for your field. This is rendered when editing a field.
		*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
		*
		*  @param    $field    - an array holding all the field's data
		*
		*  @type    action
		*  @since    3.6
		*  @date    23/01/13
		*/

		function render_field_settings( $field ) {
			// default_value
			acf_render_field_setting(
				$field,
				array(
					'label'                 => __( 'Default Value', 'acf-frontend-form-element' ),
					'instructions'          => __( 'Appears when creating a new post', 'acf-frontend-form-element' ),
					'type'                  => 'color',
					'name'                  => 'default_value',
					'dynamic_value_choices' => 1,
				)
			);

		}

	}




endif; // class_exists check


