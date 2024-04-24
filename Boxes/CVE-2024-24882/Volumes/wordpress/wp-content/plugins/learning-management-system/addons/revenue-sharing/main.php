<?php
/**
 * Addon Name: Revenue Sharing
 * Addon URI: https://masteriyo.com/wordpress-lms/
 * Description: Effortlessly set up and customize revenue sharing agreements with instructors, providing a fair and transparent way to reward their hard work and dedications.
 * Author: Masteriyo
 * Author URI: https://masteriyo.com
 * Addon Type: feature
 * Plan: Free
 */

use Masteriyo\Pro\Addons;

define( 'MASTERIYO_REVENUE_SHARING_ADDON_FILE', __FILE__ );
define( 'MASTERIYO_REVENUE_SHARING_ADDON_BASENAME', plugin_basename( __FILE__ ) );
define( 'MASTERIYO_REVENUE_SHARING_ADDON_DIR', __DIR__ );
define( 'MASTERIYO_REVENUE_SHARING_ADDON_SLUG', 'revenue-sharing' );

if ( ! ( new Addons() )->is_active( MASTERIYO_REVENUE_SHARING_ADDON_SLUG ) ) {
	return;
}

require_once __DIR__ . '/helper/revenue-sharing.php';

/**
 * Include service providers for revenue sharing.
 */
add_filter(
	'masteriyo_service_providers',
	function( $providers ) {
		return array_merge( $providers, require_once __DIR__ . '/config/providers.php' );
	}
);

/**
 * Initialize Masteriyo Revenue Sharing.
 */
add_action(
	'masteriyo_before_init',
	function() {
		masteriyo( 'addons.revenue-sharing' )->init();
	}
);
