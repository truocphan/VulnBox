<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'time' ) ) :

	class time extends text {

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
			$this->name     = 'time';
			$this->label    = __( 'Time', 'acf-frontend-form-element' );
			$this->defaults = array(
				'default_value' => '',
			);

		}


		/*
		*  render_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param    $field - an array holding all the field's data
		*
		*  @type    action
		*  @since    3.6
		*  @date    23/01/13
		*/

		function render_field( $field ) {
			$html = '';

			// Input.
			$input_attrs = array( 'type' => 'time' );

			$attr_keys = array( 'id', 'class', 'value', 'placeholder', 'maxlength', 'pattern', 'readonly', 'disabled', 'required' );

			if ( empty( $field['sensitive'] ) ) {
				$attr_keys[] = 'name';
			}
			if ( ! empty( $field['no_autocomplete'] ) ) {
				$input_attrs['autocomplete'] = 'no';
			}

			if ( ! empty( $field['input_data'] ) ) {
				foreach ( $field['input_data'] as $k => $data ) {
					$input_attrs[ 'data-' . $k ] = $data;
				}
			}

			foreach ( $attr_keys as $k ) {
				if ( isset( $field[ $k ] ) ) {
					$input_attrs[ $k ] = $field[ $k ];
				}
			}
			if ( empty( $input_attrs['value'] ) ) {
				$input_attrs['value'] = date( 'H:i' );
			}

			if( ! empty( $field['prepend'] ) ){
			?>
			<div class="acf-input-prepend"><?php echo acf_esc_html( $field['prepend'] ); ?></div>
			<?php } ?>
			<div class="acf-input-wrap"><?php acf_text_input( acf_filter_attrs( $input_attrs ) ); ?></div>
			<?php if( ! empty( $field['append'] ) ){ ?>
			<div class="acf-input-append"><?php echo acf_esc_html( $field['append'] ); ?></div>
			<?php } 
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
					'type'                  => 'time',
					'name'                  => 'default_value',
					'dynamic_value_choices' => 1,
				)
			);

		}

	
	}




endif; // class_exists check


