<?php
/**
 * New student registration event listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Resources\UserResource;
use Masteriyo\Resources\WebhookResource;
use Masteriyo\Roles;

defined( 'ABSPATH' ) || exit;

/**
 * New student registration event listener class.
 *
 * @since 1.6.9
 */
class NewStudentRegistrationListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'student.created';

	/**
	 * Allowed roles.
	 *
	 * @since 1.6.9
	 */
	protected $allowed_roles = array( 'administrator', 'masteriyo_manager' );

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'New Student Registration', 'masteriyo' );
	}

	/**
	 * Setup the webhook event.
	 *
	 * @since 1.6.9
	 *
	 * @param callable $deliver_callback
	 * @param \Masteriyo\Models\Webhook $webhook
	 */
	public function setup( $deliver_callback, $webhook ) {
		add_action(
			'user_register',
			function( $user_id, $userdata ) use ( $deliver_callback, $webhook ) {
				if ( ! masteriyo_is_user_admin( $webhook->get_author_id() ) ) {
					return;
				}

				$user = masteriyo_get_user( $user_id );

				if ( is_wp_error( $user ) || empty( $user ) || ! in_array( Roles::STUDENT, (array) $user->get_roles(), true ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $user, $webhook ),
					)
				);
			},
			10,
			2
		);
	}

	/**
	 * Get payload data for the currently triggered webhook.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\User $user
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $user, $webhook ) {
		$data = UserResource::to_array( $user );

		/**
		 * Filters the payload data for the currently triggered webhook.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data The payload data.
		 * @param \Masteriyo\Models\Webhook $webhook
		 * @param \Masteriyo\Listeners\Webhook\NewStudentRegistrationListener $listener Listener object.
		 * @param \Masteriyo\Models\User $user User model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $user );
	}
}
