<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'display_name' ) ) :


	class display_name extends select {


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
			$this->name     = 'display_name';
			$this->label    = __( 'Display Name', 'acf-frontend-form-element' );
			$this->category = __( 'User', 'acf-frontend-form-element' );
			$this->defaults = array(
				'multiple'      => 0,
				'allow_null'    => 0,
				'choices'       => array(),
				'default_value' => '',
				'allow_custom'  => 1,
				'ui'            => 1,
				'ajax'          => 0,
				'placeholder'   => __( 'Start typing or choose one of the options', 'acf-frontend-form-element' ),
				'return_format' => 'value',
			);

			add_filter( 'acf/load_field/type=text', array( $this, 'load_display_name_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}

		function load_display_name_field( $field ) {
			if ( ! empty( $field['custom_display_name'] ) ) {
				$field['type'] = 'display_name';
			}
			return $field;
		}

		function load_value( $value, $post_id = false, $field = false ) {
			$user = explode( 'user_', $post_id );
			if ( empty( $user[1] ) ) {
				return $value;
			} else {
				$user_id   = $user[1];
				$edit_user = get_user_by( 'ID', $user_id );
				if ( $edit_user instanceof \WP_User ) {
					$value = $edit_user->display_name;
				}
			}
			return $value;
		}

		function prepare_field( $field ) {
			if ( isset( $GLOBALS['admin_form']['user_id'] ) ) {
				$user = explode( '_', $GLOBALS['admin_form']['user_id'] );

				if ( $user[0] == 'user' && ! empty( $user[1] ) ) {
					 $user = get_userdata( $user[1] );
					if ( isset( $user->user_login ) ) {
						$choices          = array(
							$user->user_login,
							$user->user_email,
							$user->first_name,
							$user->last_name,
							$user->first_name . ' ' . $user->last_name,
							$user->nickname,
						);
						$field['choices'] = array();
						foreach ( $choices as $choice ) {
							if ( $choice && $choice != ' ' ) {
								$field['choices'][ $choice ] = $choice;
							}
						}
					}
				}
			}

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

		function load_field( $field ) {
			  $field['name'] = $field['type'];
			 return $field;
		}
		function pre_update_value( $value, $post_id = false, $field = false ) {
				 $user = explode( 'user_', $post_id );
			if ( ! empty( $user[1] ) ) {
				$user_id = $user[1];
				remove_action( 'acf/save_post', '_acf_do_save_post' );
				wp_update_user(
					array(
						'ID'           => $user_id,
						'display_name' => $value,
					)
				);
				add_action( 'acf/save_post', '_acf_do_save_post' );
			}
				return null;
		}


	}




endif; // class_exists check


