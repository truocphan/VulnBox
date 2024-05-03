<?php
namespace Frontend_Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class API_KEYS {

	/**
	 * Redirect non-admin users to home page
	 *
	 * This function is attached to the ‘admin_init’ action hook.
	 */


	public function get_settings_fields( $field_keys ) {
		$local_fields = array(
			'frontend_admin_google_maps_api' => array(
				'label'        => __( 'Google Maps API Key', 'acf-frontend-form-element' ),
				'type'         => 'text',
				'instructions' => '',
				'required'     => 0,
				'wrapper'      => array(
					'width' => '50.1',
					'class' => '',
					'id'    => '',
				),
			),
		);

		$site_key   = get_option( 'frontend_admin_google_recaptcha_site' );
		$secret_key = get_option( 'frontend_admin_google_recaptcha_secret' );

		$local_fields = apply_filters( 'frontend_admin/api_settings', $local_fields );

		$local_fields = array_merge(
			$local_fields,
			array(
				'google_recaptcha_message'           => array(
					'label'        => '',
					'type'         => 'message',
					'message'      => sprintf( __( '<a href="%s" target="_blank">reCAPTCHA</a> is a free service by Google that protects your website from spam and abuse. It does this while letting your valid users pass through with ease.', 'acf-frontend-form-element' ), 'https://www.google.com/recaptcha/intro/v3.html' ),
					'instructions' => '',
					'required'     => 0,
					'wrapper'      => array(
						'width' => '50.1',
						'class' => '',
						'id'    => '',
					),
				),

				'fea_recapthca_V2'                   => array(
					'label'        => __( 'V2', 'acf-frontend-form-element' ),
					'type'         => 'tab',
					'instructions' => '',
					'required'     => 0,
					'wrapper'      => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'placement'    => 'top',
					'endpoint'     => 0,
				),
				'frontend_admin_recaptcha_site_v2'   => array(
					'label'         => __( 'Google reCaptcha Site Key', 'acf-frontend-form-element' ),
					'type'          => 'text',
					'instructions'  => '',
					'required'      => 0,
					'default_value' => $site_key,
					'wrapper'       => array(
						'width' => '50.1',
						'class' => '',
						'id'    => '',
					),
				),
				'frontend_admin_recaptcha_secret_v2' => array(
					'label'         => __( 'Google reCaptcha Secret Key', 'acf-frontend-form-element' ),
					'type'          => 'text',
					'instructions'  => '',
					'required'      => 0,
					'default_value' => $secret_key,
					'wrapper'       => array(
						'width' => '50.1',
						'class' => '',
						'id'    => '',
					),
				),
				'fea_recapthca_V3'                   => array(
					'label'        => __( 'V3', 'acf-frontend-form-element' ),
					'type'         => 'tab',
					'instructions' => '',
					'required'     => 0,
					'wrapper'      => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'placement'    => 'top',
					'endpoint'     => 0,
				),
				'frontend_admin_recaptcha_site_v3'   => array(
					'label'         => __( 'Google reCaptcha Site Key', 'acf-frontend-form-element' ),
					'type'          => 'text',
					'instructions'  => '',
					'required'      => 0,
					'default_value' => $site_key,
					'wrapper'       => array(
						'width' => '50.1',
						'class' => '',
						'id'    => '',
					),
				),
				'frontend_admin_recaptcha_secret_v3' => array(
					'label'         => __( 'Google reCaptcha Secret Key', 'acf-frontend-form-element' ),
					'type'          => 'text',
					'instructions'  => '',
					'required'      => 0,
					'default_value' => $secret_key,
					'wrapper'       => array(
						'width' => '50.1',
						'class' => '',
						'id'    => '',
					),
				),
			)
		);

		return $local_fields;
	}
	public function frontend_admin_update_maps_api() {
		acf_update_setting( 'google_api_key', get_option( 'frontend_admin_google_maps_api' ) );
	}

	public function __construct() {
		 add_filter( 'frontend_admin/apis_fields', array( $this, 'get_settings_fields' ) );

		add_action( 'acf/init', array( $this, 'frontend_admin_update_maps_api' ) );
	}

}

new API_KEYS( $this );
