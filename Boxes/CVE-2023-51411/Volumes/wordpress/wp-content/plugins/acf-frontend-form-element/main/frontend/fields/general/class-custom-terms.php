<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'custom_terms' ) ) :


	class custom_terms extends select {


		/*
		*  __construct
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
			$this->name     = 'custom_terms';
			$this->label    = __( 'Custom Choices', 'acf-frontend-form-element' );
			$this->category = 'choice';
			$this->defaults = array(
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => '',
				'allow_custom'  => 1,
				'ui'            => 1,
				'multiple'      => 0,
				'ajax'          => 0,
				'placeholder'   => __( 'Type your values and click enter', 'acf-frontend-form-element' ),
				'return_format' => 'value',
			);

			/*
			 // ajax
			add_action('wp_ajax_acf/fields/select/query',                array($this, 'ajax_query'));
			add_action('wp_ajax_nopriv_acf/fields/select/query',        array($this, 'ajax_query')); */

		}



		function prepare_field( $field ) {
			// Allow Custom
			if ( acf_maybe_get( $field, 'allow_custom' ) ) {

				if ( $value = acf_maybe_get( $field, 'value' ) ) {

					 $value = acf_get_array( $value );

					foreach ( $value as $v ) {

						if ( isset( $field['choices'][ $v ] ) ) {
							  continue;
						}

						$field['choices'][ $v ] = $v;

					}
				}

				if ( empty( $field['wrapper'] ) ) {
					$field['wrapper'] = array();
				}

				$field['wrapper']['data-allow-custom'] = 1;

			}

			if ( ! acf_maybe_get( $field, 'ajax' ) ) {

				if ( is_array( $field['choices'] ) ) {

					 $found       = false;
					 $found_array = array();

					foreach ( $field['choices'] as $k => $choice ) {

						if ( is_string( $choice ) ) {

							 $choice = trim( $choice );

							if ( strpos( $choice, '##' ) === 0 ) {

								$choice = substr( $choice, 2 );
								$choice = trim( $choice );

								$found                  = $choice;
								$found_array[ $choice ] = array();

							} elseif ( ! empty( $found ) ) {

								$found_array[ $found ][ $k ] = $choice;

							}
						}
					}

					if ( ! empty( $found_array ) ) {

						$field['choices'] = $found_array;

					}
				}
			}

			return $field;

		}




	}




endif; // class_exists check


