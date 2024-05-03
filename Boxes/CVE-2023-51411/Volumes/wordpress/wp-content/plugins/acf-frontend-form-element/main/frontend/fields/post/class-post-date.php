<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'post_date' ) ) :

	class post_date extends datetime_input {



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
			$this->name       = 'post_date';
			$this->label      = __( 'Published On', 'acf-frontend-form-element' );
			  $this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->defaults   = array(
				'data_name'      => 'published_on',
				'display_format' => get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
				'return_format'  => 'd/m/Y g:i a',
				'first_day'      => get_option( 'start_of_week' ),
			);
			add_filter( 'acf/load_field/type=date_time_picker', array( $this, 'load_post_date_field' ) );
			add_filter( 'acf/update_value/type=' . $this->name, array( $this, 'pre_update_value' ), 9, 3 );

		}

		function load_post_date_field( $field ) {
			if ( ! empty( $field['custom_post_date'] ) ) {
				$field['type'] = 'post_date';
			}
			return $field;
		}

		function load_field( $field ) {
			 $field['name'] = $field['type'];
			return $field;
		}

		function prepare_field( $field ) {
			$field['type'] = 'date_time_picker';
			if ( ! $field['value'] ) {
				$field['value'] = date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), current_time( 'timestamp' ) );
			}
			return $field;
		}

		public function load_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$edit_post = get_post( $post_id );
				$value     = $edit_post->post_date;
			}
			return $value;
		}

		function pre_update_value( $value, $post_id = false, $field = false ) {
			if ( $post_id && is_numeric( $post_id ) ) {
				$post_to_edit              = array(
					'ID' => $post_id,
				);
				$post_to_edit['post_date'] = $value;
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



endif;


