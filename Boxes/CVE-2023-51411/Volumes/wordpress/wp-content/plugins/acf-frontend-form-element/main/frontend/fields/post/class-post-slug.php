<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'post_slug' ) ) :

	class post_slug extends text {



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
			$this->name       = 'post_slug';
			$this->label      = __( 'Slug', 'acf-frontend-form-element' );
			  $this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'data_name'     => 'slug',
				'default_value' => '',
				'maxlength'     => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
			);
			add_filter( 'acf/load_field/type=text', array( $this, 'load_post_slug_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}

		function load_post_slug_field( $field ) {
			if ( ! empty( $field['custom_slug'] ) ) {
				$field['type'] = 'post_slug';
			}
			return $field;
		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}

		function prepare_field( $field ) {
			if ( isset( $field['wrapper']['class'] ) ) {
				$field['wrapper']['class'] .= ' post-slug-field';
			} else {
				$field['wrapper']['class'] = 'post-slug-field';
			}
			$field['type'] = 'text';
			return $field;
		}

		public function load_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$edit_post = get_post( $post_id );
				$value     = $edit_post->post_name == 'auto-draft' ? '' : $edit_post->post_name;
			}
			return $value;
		}

		function pre_update_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$post_to_edit              = array(
					'ID' => $post_id,
				);
				$post_to_edit['post_name'] = sanitize_text_field( $value );
				remove_action( 'acf/save_post', '_acf_do_save_post' );
				wp_update_post( $post_to_edit );
				add_action( 'acf/save_post', '_acf_do_save_post' );
			}
			return null;
		}

		public function update_value( $value, $post_id = false, $field = false ) {
			return null;
		}

	}



endif;


