<?php
/**
 * Webhook delivery job handler class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Jobs;

use Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Webhook delivery job handler class.
 *
 * @since 1.6.9
 */
class WebhookDeliveryJob {

	/**
	 * Hook to run the job.
	 *
	 * @since 1.6.9
	 */
	const HOOK = 'masteriyo/job/webhook';

	/**
	 * Initialize.
	 *
	 * @since 1.6.9
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.6.9
	 */
	protected function init_hooks() {
		add_action( self::HOOK, array( $this, 'deliver_webhook' ), 10, 3 );
	}

	/**
	 * Deliver webhook data.
	 *
	 * @since 1.6.9
	 *
	 * @param string $event_name
	 * @param array $webhook
	 * @param array $payload
	 */
	public function deliver_webhook( $event_name, $webhook, $payload ) {
		try {
			masteriyo_send_webhook( $event_name, $webhook, $payload );
		} catch ( Exception $e ) {
			error_log( 'Webhook: ' . $e->getMessage() );
		}
	}
}
