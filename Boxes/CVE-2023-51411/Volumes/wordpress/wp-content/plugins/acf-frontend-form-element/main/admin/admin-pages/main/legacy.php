<?php
namespace Frontend_Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Legacy_Settings {

	/**
	 * Redirect non-admin users to home page
	 *
	 * This function is attached to the ‘admin_init’ action hook.
	 */


	public function get_settings_fields( $field_keys ) {
		$local_fields = array(
			'fea_legacy_elementor' => array(
				'label'        => __( 'Show Elementor widgets with legacy settings', 'acf-frontend-form-element' ),
				'type'         => 'true_false',
				'instructions' => '',
				'required'     => 0,
				'wrapper'      => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'message'      => '',
				'ui'           => 1,
				'ui_on_text'   => '',
				'ui_off_text'  => '',
			),
		);

		return $local_fields;
	}

	public function legacy_elementor() {
		$value = get_option( 'frontend_admin_version', 0 );
		if ( $value ) {
			update_option( 'fea_legacy_elementor', 1 );
		}

	}

	public function __construct() {
		 add_filter( 'frontend_admin/legacy_fields', array( $this, 'get_settings_fields' ) );
		add_action( 'init', array( $this, 'legacy_elementor' ) );

	}

}

new Legacy_Settings( $this );
