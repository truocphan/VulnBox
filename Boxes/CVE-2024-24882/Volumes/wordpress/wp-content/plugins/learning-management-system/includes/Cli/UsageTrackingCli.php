<?php
/**
 * Usage tracking.
 *
 * @since 1.6.0
 * @package Masteriyo\Cli
 */

namespace Masteriyo\Cli;

use Masteriyo\Jobs\SendTrackingInfoJob;

class UsageTrackingCli {

	/**
	 * Reset usage tracking options.
	 *
	 * @since 1.6.0
	 */
	public function reset() {
		masteriyo_set_setting( 'advance.tracking.allow_usage', false );
		masteriyo_clear_usage_tracking_preference_by_user();
		$this->clear_notice();
	}

	/**
	 * Clear usage tracking notice.
	 *
	 * @since 1.6.0
	 */
	public function clear_notice() {
		masteriyo_clear_usage_tracking_notice_cancelled();

		\WP_CLI::success( sprintf( 'Usage tracking options reset successfully.' ) );
	}

	/**
	 * Return true if the usage tracking job exists.
	 *
	 * @since 1.6.0
	 */
	public function exist() {
		$actions = as_get_scheduled_actions(
			array(
				'hook'   => SendTrackingInfoJob::NAME,
				'status' => 'pending',
			)
		);

		if ( $actions ) {
			\WP_CLI::success( sprintf( 'Usage tracking job exists.' ) );
		} else {
			\WP_CLI::error( sprintf( 'Usage tracking job doesn\'t.' ) );
		}
	}
}
