<?php
namespace Frontend_Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Submissions_Settings {

	public function get_settings_fields( $field_keys ) {
		$local_fields = array(
			'frontend_admin_save_submissions' => array(
				'label'             => __( 'Save Form Submissions', 'acf-frontend-form-element' ),
				'type'              => 'true_false',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '15',
					'class' => '',
					'id'    => '',
				),
				'message'           => '',
				'ui'                => 1,
				'ui_on_text'        => '',
				'ui_off_text'       => '',
			),
		/*
		 'frontend_admin_submissions_deletetion' => array(
		'label' => __( 'Delete Submissions After...', 'acf-frontend-form-element' ),
		'type' => 'number',
		'min' => 1,
		'instructions' => '',
		'append' => __( 'Days', 'acf-frontend-form-element' ),
		'placeholder' => __( 'Never', 'acf-frontend-form-element' ),
		'required' => 0,
		'conditional_logic' => array(
					array(
						array(
							'field' => 'frontend_admin_save_submissions',
							'operator' => '==',
							'value' => '1',
						),
					),
		),
		'wrapper' => array(
					'width' => '20',
					'class' => '',
					'id' => '',
		),
		), */
		);

		return $local_fields;
	}

	public function __construct() {
		 // add_action( 'init', [ $this, 'hide_admin_bar'] );

		add_filter( 'frontend_admin/submissions_fields', array( $this, 'get_settings_fields' ) );

	}

}
new Submissions_Settings( $this );
