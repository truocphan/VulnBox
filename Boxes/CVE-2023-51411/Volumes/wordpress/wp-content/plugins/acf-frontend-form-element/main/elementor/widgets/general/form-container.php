<?php
namespace Frontend_Admin\Elementor\Widgets;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class FormContainer extends \Elementor\Modules\Library\Documents\Library_Document {
	
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['support_kit'] = true;

		return $properties;
	}


	/**
	 * Get document name.
	 *
	 * Retrieve the document name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Document name.
	 */
	public function get_name() {
		return 'form_container';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return esc_html__( 'Form', 'elementor' );
	}
}
