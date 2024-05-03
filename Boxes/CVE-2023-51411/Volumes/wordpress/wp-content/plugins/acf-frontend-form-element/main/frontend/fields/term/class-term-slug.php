<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'term_slug' ) ) :

	class term_slug extends text {



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
			$this->name       = 'term_slug';
			$this->label      = __( 'Term Slug', 'acf-frontend-form-element' );
			  $this->category = __( 'Term', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'default_value' => '',
				'maxlength'     => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'change_slug'   => 0,
			);
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}

		function prepare_field( $field ) {
			$field['type'] = 'text';
			return $field;
		}
		function render_field( $field ) {
			parent::render_field( $field );

			echo '<input type="hidden" name="term_slug_field" value="' . esc_attr( $field['key'] ) . '"/>';

		}

		public function load_value( $value, $post_id = false, $field = false ) {
			if ( strpos( $post_id, 'term_' ) !== false ) {
				$term_id   = explode( '_', $post_id )[1];
				$edit_term = get_term( $term_id );
				if ( isset( $edit_term->slug ) ) {
					$value = $edit_term->slug;
				}
			}
			return $value;
		}

		function validate_value( $is_valid, $value, $field, $input ) {
			if ( ! isset( $_POST['_acf_taxonomy_type'] ) ) {
				return $is_valid;
			}

			if ( term_exists( $value, sanitize_title( $_POST['_acf_taxonomy_type'] ) ) ) {
				$term_id = absint( $_POST['_acf_term'] );
				if ( $term_id != 'add_term' && ! empty( $term_id ) ) {
					$term_to_edit = get_term( $term_id );
					if ( ! empty( $term_to_edit->slug ) && $term_to_edit->slug == sanitize_title( $value ) ) {
						return $is_valid;
					}
				}
				return __( 'The term ' . $value . ' exists.', 'acf-frontend-form-element' );
			}
			return $is_valid;
		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}
		function pre_update_value( $value, $post_id = false, $field = false ) {
			 $term_id  = explode( '_', $post_id )[1];
			$edit_term = get_term( $term_id );
			if ( ! is_wp_error( $edit_term ) ) {
				$update_args = array( 'slug' => $value );
				remove_action( 'acf/save_post', '_acf_do_save_post' );
				wp_update_term( $term_id, $edit_term->taxonomy, $update_args );
				add_action( 'acf/save_post', '_acf_do_save_post' );
			}

			return $value;
		}

		public function update_value( $value, $post_id = false, $field = false ) {
			return null;
		}

	}



endif;


