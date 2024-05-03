<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'post_title' ) ) :

	class post_title extends text {



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
			$this->name       = 'post_title';
			$this->label      = __( 'Title', 'acf-frontend-form-element' );
			  $this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'data_name'     => 'title',
				'default_value' => '',
				'maxlength'     => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'custom_slug'   => 0,
			);
			add_filter( 'acf/load_field/type=text', array( $this, 'load_post_title_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}

		function load_post_title_field( $field ) {
			if ( ! empty( $field['custom_title'] ) ) {
				$field['type'] = 'post_title';
			}
			return $field;
		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}

		function prepare_field( $field ) {
			$field['type'] = 'text';
			return $field;
		}

		public function load_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$edit_post = get_post( $post_id );
				$value     = ( empty( $edit_post->post_title ) || $edit_post->post_title == 'Auto Draft' ) ? '' : $edit_post->post_title;
			}
			return $value;
		}

		function pre_update_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$post_to_edit               = array(
					'ID' => $post_id,
				);
				$post_to_edit['post_title'] = $value;
				if ( isset( $_POST['_acf_post'] ) && $_POST['_acf_post'] == 'add_post' ) {
					$post_to_edit['post_name'] = sanitize_title( $value );
				}
				if ( ! empty( $field['custom_slug'] ) ) {
					$post_to_edit['post_name'] = sanitize_title( $value );
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
					'label'        => __( 'Post Slug' ),
					'instructions' => 'Save value as post slug.',
					'name'         => 'custom_slug',
					'type'         => 'true_false',
					'ui'           => 1,
				)
			);
		}

	}



endif;


