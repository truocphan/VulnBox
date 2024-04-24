<?php
/**
 * Tracking helper functions.
 *
 * @since 1.6.0
 * @package Masteriyo\Helper
 */

use Masteriyo\Constants;

/**
 * Determines whether to show the "Allow usage tracking" notice.
 *
 * @since 1.6.0
 *
 * @return bool
 */
function masteriyo_show_usage_tracking_notice() {
	if ( masteriyo_get_setting( 'advance.tracking.allow_usage' ) ) {
		return false;
	}

	if ( masteriyo_is_usage_tracking_notice_cancel_timeout() ) {
		return false;
	}

	if ( masteriyo_is_set_usage_tracking_preference_by_user_set() ) {
		return false;
	}

	return true;
}

/**
 * Return true if the tracking notice is cancelled.
 *
 * @since 1.6.0
 *
 * @return boolean
 */
function masteriyo_is_usage_tracking_notice_cancel_timeout() {
	return 'yes' === get_transient( 'masteriyo_is_usage_tracking_notice_cancel_timeout' );
}

/**
 * Set flat to set tracking notice is cancelled.
 *
 * @since 1.6.0
 */
function masteriyo_set_usage_tracking_notice_is_cancelled() {
	$timeout = masteriyo_get_usage_tracking_notice_cancel_timeout();
	set_transient( 'masteriyo_is_usage_tracking_notice_cancel_timeout', 'yes', $timeout );
}

/**
 * Clear tracking notice is cancelled.
 *
 * @since 1.6.0
 */
function masteriyo_clear_usage_tracking_notice_cancelled() {
	delete_transient( 'masteriyo_is_usage_tracking_notice_cancel_timeout' );
}

/**
 * Return usage tracking notice cancel timeout.
 *
 * @since 1.6.0
 *
 * @return integer
 */
function masteriyo_get_usage_tracking_notice_cancel_timeout() {
	$timeout = 5 * DAY_IN_SECONDS;

	if ( Constants::is_defined( 'MASTERIYO_USAGE_TRACKING_NOTICE_CANCEL_TIMEOUT' ) ) {
		$timeout = Constants::get( 'MASTERIYO_USAGE_TRACKING_NOTICE_CANCEL_TIMEOUT' );
	}

	return $timeout;
}

/**
 * Return usage tracking job interval.
 *
 * @since 1.6.0
 *
 * @return integer
 */
function masteriyo_get_usage_tracking_job_interval() {
	$timeout = 14 * DAY_IN_SECONDS;

	if ( Constants::is_defined( 'MASTERIYO_USAGE_TRACKING_JOB_INTERVAL' ) ) {
		$timeout = Constants::get( 'MASTERIYO_USAGE_TRACKING_JOB_INTERVAL' );
	}

	return $timeout;
}

/**
 * Set the user has checked allow/no-thanks from the admin notice.
 *
 * @since 1.6.0
 */
function masteriyo_set_usage_tracking_preference_by_user() {
	update_option( 'masteriyo_usage_tracking_user_preference', 'yes' );
}

/**
 * Clear usage tracking preference by the user.
 *
 * @since 1.6.0
 */
function masteriyo_clear_usage_tracking_preference_by_user() {
	delete_option( 'masteriyo_usage_tracking_user_preference' );
}

/**
 * Return true if the user has checked allow/no-thanks from the admin notice.
 *
 * @since 1.6.0
 *
 * @return bool
 */
function masteriyo_is_set_usage_tracking_preference_by_user_set() {
	return 'yes' === get_option( 'masteriyo_usage_tracking_user_preference' );
}
