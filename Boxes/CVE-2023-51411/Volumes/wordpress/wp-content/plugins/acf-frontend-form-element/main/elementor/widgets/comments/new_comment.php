<?php
namespace Frontend_Admin\Elementor\Widgets;





/**

 *
 * @since 1.0.0
 */
class New_Comment_Widget extends ACF_Form {


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
		return 'new_comment';
	}

	/**
	 * Get widget action.
	 *
	 * Retrieve acf ele form widget action.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget action.
	 */
	public function get_form_defaults() {
		return array(
			'custom_fields_save' => 'comment',
			'form_title'         => __( 'Add Comment', 'acf-frontend-form-element' ),
			'submit'             => __( 'Submit', 'acf-frontend-form-element' ),
			'success_message'    => __( 'Your comment has been added successfully.', 'acf-frontend-form-element' ),
			'field_type'         => 'comment',
			'fields'             => array(
				array(
					'field_type'     => 'comment',
					'field_label_on' => 'true',
					'field_required' => 'true',
				),
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
		return __( 'New Comment Form', 'acf-frontend-form-element' );
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
		return 'fas fa-comment frontend-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the acf ele form widget belongs to.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'frontend-forms' );
	}

}
