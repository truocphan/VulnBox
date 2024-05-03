<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'first_name' ) ) :

	class first_name extends text {



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
			$this->name       = 'first_name';
			$this->label      = __( 'First Name', 'acf-frontend-form-element' );
			  $this->category = __( 'User', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'default_value' => '',
				'maxlength'     => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
			);
			add_filter( 'acf/load_field/type=text', array( $this, 'load_first_name_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

			add_filter( 'frontend_admin/add_to_record/' . $this->name, array( $this, 'add_to_record' ), 10, 3 );

		}

		function add_to_record( $record, $group, $field ) {
			if ( empty( $record['mailchimp']['first_name'] ) ) {
				$record['mailchimp']['first_name'] = $group . ':' . $field['name'];
			}
			return $record;
		}

		function load_first_name_field( $field ) {
			if ( ! empty( $field['custom_first_name'] ) ) {
				$field['type'] = 'first_name';
			}
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
					$value = $edit_user->first_name;
				}
			}
			return $value;
		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}

		public function prepare_field( $field ) {
			$field['type'] = 'text';
			// return
			return $field;
		}

		function pre_update_value( $value, $post_id = false, $field = false ) {
			if ( $field['name'] == 'first_name' ) {
				return $value;
			}

			$user = explode( 'user_', $post_id );
			if ( ! empty( $user[1] ) ) {
				$user_id = $user[1];
				remove_action( 'acf/save_post', '_acf_do_save_post' );
				wp_update_user(
					array(
						'ID'         => $user_id,
						'first_name' => $value,
					)
				);
				add_action( 'acf/save_post', '_acf_do_save_post' );
			}
			return null;
		}


	}



endif;


