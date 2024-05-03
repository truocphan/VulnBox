<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'delete_term' ) ) :

	class delete_term extends delete_object {



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
			$this->name     = 'delete_term';
			$this->label    = __( 'Delete Term', 'acf-frontend-form-element' );
			$this->category = __( 'Term', 'acf-frontend-form-element' );
			$this->object   = 'term';
			$this->defaults = array(
				'button_text'       => __( 'Delete', 'acf-frontend-form-element' ),
				'confirmation_text' => __( 'Are you sure you want to delete this term?', 'acf-frontend-form-element' ),
				'field_label_hide'  => 1,
				'redirect'          => 'current',
				'show_delete_message' => 1,
				'delete_message'    => __( 'Your term has been deleted' ),
			);

		}

	}




endif; // class_exists check


