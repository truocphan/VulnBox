<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'email' ) ) :

	class email extends Field_Base {


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
			$this->name     = 'email';
			$this->label    = __( 'Email', 'acf' );
			$this->defaults = array(
				'default_value' => '',
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
			$keys  = array( 'type', 'id', 'class', 'name', 'value', 'placeholder', 'pattern' );
			$keys2 = array( 'readonly', 'disabled', 'required', 'multiple' );

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

		/**
		 * Validate the email value. If this method returns TRUE, the input value is valid. If
		 * FALSE or a string is returned, the input value is invalid and the user is shown a
		 * notice. If a string is returned, the string is show as the message text.
		 *
		 * @param bool   $valid Whether the value is valid.
		 * @param mixed  $value The field value.
		 * @param array  $field The field array.
		 * @param string $input The request variable name for the inbound field.
		 *
		 * @return bool|string
		 */
		public function validate_value( $valid, $value, $field, $input ) {
			$flags = defined( 'FILTER_FLAG_EMAIL_UNICODE' ) ? FILTER_FLAG_EMAIL_UNICODE : 0;

			if ( $value && filter_var( wp_unslash( $value ), FILTER_VALIDATE_EMAIL, $flags ) === false ) {
				return sprintf( __( "'%s' is not a valid email address", 'acf' ), esc_html( $value ) );
			}

			return $valid;
		}

		/**
		 * Return the schema array for the REST API.
		 *
		 * @param array $field
		 * @return array
		 */
		public function get_rest_schema( array $field ) {
			$schema           = parent::get_rest_schema( $field );
			$schema['format'] = 'email';

			return $schema;
		}

	}




endif; // class_exists check


