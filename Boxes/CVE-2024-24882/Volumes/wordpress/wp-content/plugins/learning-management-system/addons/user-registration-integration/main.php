<?php
/**
 * Addon Name: User Registration Integration
 * Addon URI: https://masteriyo.com/wordpress-lms/
 * Description: User Registration Integration addon integrates with User Registration Plugin to maximize registration process.
 * Author: Masteriyo
 * Author URI: https://masteriyo.com
 * Addon Type: feature
 * Requires: User Registration
 * Plan: Free
 */

use Masteriyo\Pro\Addons;
use Masteriyo\Addons\UserRegistrationIntegration\Helper;

define( 'MASTERIYO_USER_REGISTRATION_INTEGRATION_FILE', __FILE__ );
define( 'MASTERIYO_USER_REGISTRATION_INTEGRATION_BASENAME', plugin_basename( __FILE__ ) );
define( 'MASTERIYO_USER_REGISTRATION_INTEGRATION_DIR', dirname( __FILE__ ) );
define( 'MASTERIYO_USER_REGISTRATION_INTEGRATION_SLUG', 'user-registration-integration' );

if ( ( new Addons() )->is_active( MASTERIYO_USER_REGISTRATION_INTEGRATION_SLUG ) && ! Helper::is_user_registration_active() ) {
	add_action(
		'admin_notices',
		function() {
			printf(
				'<div class="notice notice-warning is-dismissible"><p><strong>%s </strong>%s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">%s</span></button></div>',
				esc_html( 'Masteriyo:' ),
				wp_kses_post( 'User Registration Integration addon requires User Registration plugin to be installed and activated.', 'masteriyo' ),
				esc_html__( 'Dismiss this notice.', 'masteriyo' )
			);
		}
	);
}

// Bail early if User Registration plugin is not activated.
if ( ! Helper::is_user_registration_active() ) {
	add_filter(
		'masteriyo_pro_addon_' . MASTERIYO_USER_REGISTRATION_INTEGRATION_SLUG . '_activation_requirements',
		function ( $result, $request, $controller ) {
			$result = __( 'User Registration plugin needs to be installed and activated for this addon to work properly', 'masteriyo' );
			return $result;
		},
		10,
		3
	);

	add_filter(
		'masteriyo_pro_addon_data',
		function( $data, $slug ) {
			if ( MASTERIYO_USER_REGISTRATION_INTEGRATION_SLUG === $slug ) {
				$data['requirement_fulfilled'] = masteriyo_bool_to_string( Helper::is_user_registration_active() );
			}

			return $data;
		},
		10,
		2
	);

	return;
}

// Bail early if the addon is not active.
if ( ! ( new Addons() )->is_active( MASTERIYO_USER_REGISTRATION_INTEGRATION_SLUG ) ) {
	return;
}

add_filter(
	'masteriyo_service_providers',
	function( $providers ) {
		return array_merge( $providers, require_once dirname( __FILE__ ) . '/config/providers.php' );
	}
);

add_action(
	'masteriyo_before_init',
	function() {
		masteriyo( 'addons.user-registration-integration' )->init();
	}
);
