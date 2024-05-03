<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'user_password_confirm' ) ) :

	class user_password_confirm extends password {



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
			$this->name       = 'user_password_confirm';
			$this->label      = __( 'Password Confirm', 'acf-frontend-form-element' );
			  $this->category = __( 'User', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'placeholder' => '',
				'prepend'     => '',
				'append'      => '',
			);
			add_filter( 'acf/load_field/type=password', array( $this, 'load_user_password_confirm_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}

		function load_user_password_confirm_field( $field ) {
			if ( ! empty( $field['custom_password_confirm'] ) ) {
				$field['type'] = 'user_password_confirm';
			}
			return $field;
		}

		function prepare_field( $field ) {
			$form = $GLOBALS['admin_form'];
			if ( isset( $form['approval'] ) ) {
				return false;
			}

			if ( ! $field['value'] ) {
				return $field;
			}
			if ( isset( $field['wrapper']['class'] ) ) {
				$field['wrapper']['class'] .= ' acf-hidden';
			}

			$field['required'] = false;
			$field['value']    = '';

			return $field;
		}

		public function load_value( $value, $post_id = false, $field = false ) {
			$user = explode( 'user_', $post_id );

			if ( empty( $user[1] ) ) {
				return $value;
			} else {
				$user_id   = $user[1];
				$edit_user = get_user_by( 'ID', $user_id );
				if ( $edit_user instanceof \WP_User ) {
					$value = 'i';
				}
			}
			return $value;
		}

		function validate_value( $is_valid, $value, $field, $input ) {
			if ( is_numeric( $_POST['_acf_user'] ) && ! isset( $_POST['edit_user_password'] ) ) {
				return $is_valid;
			}

			if ( empty( $_POST['custom_password'] ) ) {
				return false;
			}
			$password_field = sanitize_key( $_POST['custom_password'] );
			if ( $_POST['acff']['user'][ $password_field ] != $value ) {
				return __( 'The passwords do not match', 'acf-frontend-form-element' );
			}

			return $is_valid;
		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}
		function pre_update_value( $value, $post_id = false, $field = false ) {
			 return null;
		}

		function render_field( $field ) {
			$field['type'] = 'password';
			parent::render_field( $field );

			echo '<div class="pass-strength-result weak"></div>';
			echo '<input type="hidden" name="custom_password_confirm" value="' . esc_attr( $field['key'] ) . '"/>';

		}


	}



endif;


