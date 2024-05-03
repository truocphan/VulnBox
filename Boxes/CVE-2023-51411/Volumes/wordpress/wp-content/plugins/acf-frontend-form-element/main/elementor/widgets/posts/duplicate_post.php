<?php
namespace Frontend_Admin\Elementor\Widgets;





/**

 *
 * @since 1.0.0
 */
class Duplicate_Post_Widget extends ACF_Form {


	/**
	 * Get widget name.
	 *
	 * Retrieve acf ele form widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'duplicate_post';
	}
	public function get_form_defaults() {
		return array(
			'custom_fields_save' => 'post',
			'form_title'         => '',
			'submit'             => __( 'Submit', 'acf-frontend-form-element' ),
			'success_message'    => __( 'Your post has been duplicated successfully.', 'acf-frontend-form-element' ),
			'field_type'         => 'post_title',
			'fields'             => array(
				array( 'post_title' ),
			),
		);
	}
		/**
		 * Get widget title.
		 *
		 * Retrieve acf ele form widget title.
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @return string Widget title.
		 */
	public function get_title() {
		return __( 'Duplicate Post Form', 'acf-frontend-form-element' );
	}

	 /**
	  * Get widget icon.
	  *
	  * Retrieve acf ele form widget icon.
	  *
	  * @since  1.0.0
	  * @access public
	  *
	  * @return string Widget icon.
	  */
	public function get_icon() {
		return 'eicon-copy frontend-icon';
	}


}
