<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'delete_post' ) ) :

	class delete_post extends delete_object {



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
			$this->name     = 'delete_post';
			$this->label    = __( 'Delete Post', 'acf-frontend-form-element' );
			$this->category = __( 'Post', 'acf-frontend-form-element' );
			$this->object   = 'post';
			$this->defaults = array(
				'button_text'       => __( 'Delete', 'acf-frontend-form-element' ),
				'confirmation_text' => __( 'Are you sure you want to delete this post?', 'acf-frontend-form-element' ),
				'field_label_hide'  => 1,
				'force_delete'      => 0,
				'redirect'          => 'current',
				'show_delete_message' => 1,
				'delete_message'    => __( 'Your post has been deleted' ),
			);

		}

	}




endif; // class_exists check


