<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'number' ) ) :

	class number extends Field_Base {


		/*
		*  initialize
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
			$this->name     = 'number';
			$this->label    = __( 'Number', 'acf' );
			$this->defaults = array(
				'default_value' => '',
				'min'           => '',
				'max'           => '',
				'step'          => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
			);

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

			// vars
			$atts  = array();
			$keys  = array( 'type', 'id', 'class', 'name', 'value', 'min', 'max', 'step', 'placeholder', 'pattern' );
			$keys2 = array( 'readonly', 'disabled', 'required' );
			$html  = '';

			// step
			if ( ! $field['step'] ) {
				$field['step'] = 'any';
			}

			// atts (value="123")
			foreach ( $keys as $k ) {
				if ( isset( $field[ $k ] ) ) {
					$atts[ $k ] = $field[ $k ];
				}
			}

			// atts2 (disabled="disabled")
			foreach ( $keys2 as $k ) {
				if ( ! empty( $field[ $k ] ) ) {
					$atts[ $k ] = $k;
				}
			}

			// remove empty atts
			$atts = acf_clean_atts( $atts );

				if( $field['prepend'] ){
			?>
			<div class="acf-input-prepend"><?php echo acf_esc_html( $field['prepend'] ); ?></div>
			<?php } ?>
			<div class="acf-input-wrap"><?php acf_text_input( acf_filter_attrs( $atts ) ); ?></div>
			<?php if( $field['append'] ){ ?>
			<div class="acf-input-append"><?php echo acf_esc_html( $field['append'] ); ?></div>
			<?php } 

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

			// default_value
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Default Value', 'acf' ),
					'instructions' => __( 'Appears when creating a new post', 'acf' ),
					'type'         => 'text',
					'name'         => 'default_value',
				)
			);
		}

		/**
		 * Renders the field settings used in the "Validation" tab.
		 *
		 * @since 6.0
		 *
		 * @param array $field The field settings array.
		 * @return void
		 */
		function render_field_validation_settings( $field ) {
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Minimum Value', 'acf' ),
					'instructions' => '',
					'type'         => 'number',
					'name'         => 'min',
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Maximum Value', 'acf' ),
					'instructions' => '',
					'type'         => 'number',
					'name'         => 'max',
				)
			);
		}

		/**
		 * Renders the field settings used in the "Presentation" tab.
		 *
		 * @since 6.0
		 *
		 * @param array $field The field settings array.
		 * @return void
		 */
		function render_field_presentation_settings( $field ) {
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Placeholder Text', 'acf' ),
					'instructions' => __( 'Appears within the input', 'acf' ),
					'type'         => 'text',
					'name'         => 'placeholder',
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Step Size', 'acf' ),
					'instructions' => '',
					'type'         => 'number',
					'name'         => 'step',
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Prepend', 'acf' ),
					'instructions' => __( 'Appears before the input', 'acf' ),
					'type'         => 'text',
					'name'         => 'prepend',
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Append', 'acf' ),
					'instructions' => __( 'Appears after the input', 'acf' ),
					'type'         => 'text',
					'name'         => 'append',
				)
			);
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

			// remove ','
			if ( acf_str_exists( ',', $value ) ) {

				$value = str_replace( ',', '', $value );

			}

			// if value is not numeric...
			if ( ! is_numeric( $value ) ) {

				// allow blank to be saved
				if ( ! empty( $value ) ) {

					$valid = __( 'Value must be a number', 'acf' );

				}

				// return early
				return $valid;

			}

			// convert
			$value = floatval( $value );

			// min
			if ( is_numeric( $field['min'] ) && $value < floatval( $field['min'] ) ) {

				$valid = sprintf( __( 'Value must be equal to or higher than %d', 'acf' ), $field['min'] );

			}

			// max
			if ( is_numeric( $field['max'] ) && $value > floatval( $field['max'] ) ) {

				$valid = sprintf( __( 'Value must be equal to or lower than %d', 'acf' ), $field['max'] );

			}

			// return
			return $valid;

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
		*  @param   $field - the field array holding all the field options
		*  @param   $post_id - the $post_id of which the value will be saved
		*
		*  @return  $value - the modified value
		*/

		function update_value( $value, $post_id, $field ) {

			// no formatting needed for empty value
			if ( empty( $value ) ) {

				return $value;

			}

			// remove ','
			if ( acf_str_exists( ',', $value ) ) {

				$value = str_replace( ',', '', $value );

			}

			// return
			return $value;

		}

		/**
		 * Return the schema array for the REST API.
		 *
		 * @param array $field
		 * @return array
		 */
		public function get_rest_schema( array $field ) {
			$schema = array(
				'type'     => array( 'number', 'null' ),
				'required' => ! empty( $field['required'] ),
			);

			if ( ! empty( $field['min'] ) ) {
				$schema['minimum'] = (float) $field['min'];
			}

			if ( ! empty( $field['max'] ) ) {
				$schema['maximum'] = (float) $field['max'];
			}

			if ( isset( $field['default_value'] ) && is_numeric( $field['default_value'] ) ) {
				$schema['default'] = (float) $field['default_value'];
			}

			return $schema;
		}

		/**
		 * Apply basic formatting to prepare the value for default REST output.
		 *
		 * @param mixed      $value
		 * @param string|int $post_id
		 * @param array      $field
		 * @return mixed
		 */
		public function format_value_for_rest( $value, $post_id, array $field ) {
			return acf_format_numerics( $value );
		}

	}




endif; // class_exists check


