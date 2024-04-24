<?php
/**
 * Addon Name: Course Announcement
 * Addon URI: https://masteriyo.com/wordpress-lms/
 * Description: Provide a streamlined way to share updates and information with students, fostering effective communication in online courses.
 * Author: Masteriyo
 * Author URI: https://masteriyo.com
 * Addon Type: feature
 * Plan: Free
 */

use Masteriyo\Pro\Addons;

define( 'MASTERIYO_COURSE_ANNOUNCEMENT_ADDON_FILE', __FILE__ );
define( 'MASTERIYO_COURSE_ANNOUNCEMENT_ADDON_BASENAME', plugin_basename( __FILE__ ) );
define( 'MASTERIYO_COURSE_ANNOUNCEMENT_ADDON_DIR', __DIR__ );
define( 'MASTERIYO_COURSE_ANNOUNCEMENT_ADDON_SLUG', 'course-announcement' );

if ( ! ( new Addons() )->is_active( MASTERIYO_COURSE_ANNOUNCEMENT_ADDON_SLUG ) ) {
	return;
}

require_once __DIR__ . '/helper/course-announcement.php';

/**
 * Include service providers for Course Announcement.
 */
add_filter(
	'masteriyo_service_providers',
	function( $providers ) {
		return array_merge( $providers, require_once __DIR__ . '/config/providers.php' );
	}
);

/**
 * Initialize Masteriyo Course Announcement.
 */
add_action(
	'masteriyo_before_init',
	function() {
		masteriyo( 'addons.course-announcement' )->init();
	}
);
