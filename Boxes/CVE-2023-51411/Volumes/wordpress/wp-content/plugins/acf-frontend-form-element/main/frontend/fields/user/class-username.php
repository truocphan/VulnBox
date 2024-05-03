<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'username' ) ) :

	class username extends text {



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
			$this->name       = 'username';
			$this->label      = __( 'Username', 'acf-frontend-form-element' );
			  $this->category = __( 'User', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'default_value' => '',
				'maxlength'     => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'allow_edit'	=> 0,
			);
			add_filter( 'acf/load_field/type=text', array( $this, 'load_username_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );
		}

		function load_username_field( $field ) {
			if ( ! empty( $field['custom_username'] ) ) {
				$field['type'] = 'username';
			}
			return $field;
		}

		public function prepare_field( $field ) {
			// make sure field is not disabled when no value exists
			if ( ! $field['value'] || isset( $GLOBALS['admin_form']['submission'] ) ) {
				$field['disabled'] = 0;
			}else{
				if( empty( $field['allow_edit'] ) && $field['value'] ){
					$field['disabled'] = 1;
				}
			}
			$field['type'] = 'text';

			

			// return
			return $field;
		}

		public function load_value( $value, $post_id = false, $field = false ) {

			$user = explode( 'user_', $post_id );
			if ( empty( $user[1] ) ) {
				return $value;
			} else {
				$user_id = absint( $user[1] );
				$edit_user = get_user_by( 'ID', $user_id );
				if ( $edit_user instanceof \WP_User ) {
					$value = $edit_user->user_login;
				}
			}
			return $value;
		}

		public function validate_value( $is_valid, $value, $field, $input ) {
			if ( $field['required'] == 0 && $value == '' ) {
				return $is_valid;
			}

			if ( ! validate_username( $value ) ) {
				return __( 'The username contains illegal characters. Please enter only latin letters, numbers, @, -, . and _', 'acf-frontend-form-element' );
			}

			if ( empty( $_POST['_acf_user'] ) ) {
				return $is_valid;
			}

			$user_id   = absint( $_POST['_acf_user'] );
			if( $user_id ){
				$edit_user = get_user_by( 'ID', $user_id );
			}
			
			$username_taken = sprintf( __( 'The username %s is taken. Please try a different username', 'acf-frontend-form-element' ), $value );

			if ( username_exists( $value ) ) {
				if ( ! empty( $edit_user->user_login ) && $edit_user->user_login == $value ) {
					return $is_valid;
				}
				return $username_taken;
			}
			if ( email_exists( $value ) ) {
				if ( ! empty( $edit_user->user_email ) && $edit_user->user_email == $value ) {
					return $is_valid;
				}
				return $username_taken;
			}

			return $is_valid;
		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}
		function pre_update_value( $value, $post_id = false, $field = false ) {
			 $user = explode( 'user_', $post_id );
			if ( ! empty( $user[1] ) ) {
				$user_id     = $user[1];
				$user_object = get_user_by( 'ID', $user_id );
				if ( $user_object->user_login == $value ) {
					return null;
				}

				if ( get_current_user_id() == $user_id ) {
					$_POST['log_back_in'] = array( $user_id, $value );
				}
				global $wpdb;
				$wpdb->update( $wpdb->users, array( 'user_login' => $value ), array( 'ID' => $user_id ) );
			}
			return null;
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
			parent::render_field_settings( $field );
			// default_value
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Allow Edit' ),
					'instructions' => __( 'Allow users to change their username. WARNING: allowing your users to change their username might affect existing urls and their SEO rating.', 'acf-frontend-form-element' ),
					'name'         => 'allow_edit',
					'type'         => 'true_false',
					'ui'           => 1,
				) 
			);
		}



	}



endif;


