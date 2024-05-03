<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'allow_comments' ) ) :

	class allow_comments extends true_false {



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
			$this->name     = 'allow_comments';
			$this->label    = __( 'Allow Comments' );
			$this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->defaults = array(
				'default_value' => 0,
				'message'       => '',
				'ui'            => 1,
				'ui_on_text'    => '',
				'ui_off_text'   => '',
			);
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}

		function prepare_field( $field ) {
			$field['ui'] = 1;
			return $field;
		}

		public function load_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$edit_post = get_post( $post_id );
				if ( $edit_post->comment_status == 'open' ) {
					$value = 1;
				} else {
					$value = 0;
				}
			}
			return $value;
		}

		function pre_update_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$post_to_edit = array(
					'ID' => $post_id,
				);
				if ( $value ) {
					$post_to_edit['comment_status'] = 'open';
				} else {
					$post_to_edit['comment_status'] = 'closed';
				}
				remove_action( 'acf/save_post', '_acf_do_save_post' );
				wp_update_post( $post_to_edit );
				add_action( 'acf/save_post', '_acf_do_save_post' );

			}
			return $value;
		}

		public function update_value( $value, $post_id = false, $field = false ) {
			return null;
		}

	}



endif; // class_exists check


