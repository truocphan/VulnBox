<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'user_url' ) ) :

	class user_url extends url {



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
			$this->name       = 'user_url';
			$this->label      = __( 'Website', 'acf-frontend-form-element' );
			  $this->category = __( 'User', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
			);
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}

		public function load_value( $value, $post_id = false, $field = false ) {
			$user = explode( 'user_', $post_id );

			if ( empty( $user[1] ) ) {
				return $value;
			} else {
				$user_id   = $user[1];
				$edit_user = get_user_by( 'ID', $user_id );
				if ( $edit_user instanceof \WP_User ) {
					$value = esc_html( $edit_user->user_url );
				}
			}
			return $value;
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
						'ID'       => $user_id,
						'user_url' => esc_attr( $value ),
					)
				);
				add_action( 'acf/save_post', '_acf_do_save_post' );

			}
			return null;
		}

		function render_field( $field ) {
			$field['type'] = 'url';
			parent::render_field( $field );
		}

	}



endif;


