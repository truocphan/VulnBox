<?php
/**
 * Masteriyo\Jobs\SendTrackingInfoJob file.
 *
 * This file contains the definition for the SendTrackingInfoJob class, which
 * is responsible for sending tracking information to a remote URL by registering
 * the appropriate actions with WordPress.
 *
 * @package Masteriyo\Jobs
 */

namespace Masteriyo\Jobs;

use Masteriyo\Tracking\MasteriyoTrackingInfo;
use Masteriyo\Tracking\WPTrackingInfo;
use Masteriyo\Tracking\ServerTrackingInfo;

/**
 * Job for sending tracking information to a remote URL.
 *
 * This class is used to send tracking information to a remote URL
 * by registering the appropriate actions with WordPress. It has
 * two methods for registering the job and for processing the job.
 *
 * @since 1.6.7
 */
class SendTrackingInfoJob {

	/**
	 * The remote URL to which the tracking information is sent.
	 *
	 * @since 1.6.7
	 * @see https://webhook.site/ for testing.
	 */
	const REMOTE_URL = 'https://stats.wpeverest.com/wp-json/tgreporting/v1/process-free/';

	/**
	 * Name of the job.
	 *
	 * @since 1.6.7
	 */
	const NAME = 'masteriyo/job/tracking';

	/**
	 * Registers the job to run on WordPress option update.
	 *
	 * This method adds the appropriate action for the job to run
	 * when the "update_option_masteriyo_settings" action is triggered.
	 *
	 * @since 1.6.7
	 */
	public function register() {
		add_action( self::NAME, array( $this, 'process' ) );
	}

	/**
	 * Start process.
	 *
	 * @since 1.6.7
	 */
	public function process() {
		$wp_data     = WPTrackingInfo::all();
		$server_data = ServerTrackingInfo::all();

		$data = array_merge( $wp_data, $server_data );
		$data = array_merge( $data, array( 'base_product' => MasteriyoTrackingInfo::get_slug() ) );
		$data['product_data'][ MasteriyoTrackingInfo::get_slug() ] = MasteriyoTrackingInfo::all();

		$this->send( self::REMOTE_URL, $data );
	}

	/**
	 * Return headers.
	 *
	 * @since 1.6.7
	 *
	 * @return array
	 */
	public function get_headers() {
		return array(
			'user-agent' => 'Masteriyo/' . masteriyo_get_version() . '; ' . get_bloginfo( 'url' ),
		);
	}

	/**
	 * Sends a request to the API.
	 *
	 * @since 1.6.7
	 *
	 * @param string $url  The URL to send the request to.
	 * @param array  $data An array of data to send with the request.
	 */
	public function send( $url, $data ) {
		wp_remote_post(
			$url,
			array(
				'method'      => 'POST',
				'timeout'     => 10,
				'redirection' => 5,
				'httpversion' => '1.0',
				'headers'     => $this->get_headers(),
				'body'        => array( 'free_data' => $data ),
			)
		);
	}
}
