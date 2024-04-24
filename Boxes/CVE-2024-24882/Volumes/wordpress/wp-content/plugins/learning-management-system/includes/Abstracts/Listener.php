<?php
/**
 * Hook/Event Listener class.
 *
 * @since 1.6.9
 *
 * @package \Masteriyo\Abstracts
 */

namespace Masteriyo\Abstracts;

use Masteriyo\Roles;

defined( 'ABSPATH' ) || exit;

/**
 * Hook/Event Listener class.
 *
 * @since 1.6.9
 */
abstract class Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = '';

	/**
	 * Allowed roles.
	 *
	 * @since 1.6.9
	 *
	 * @var array
	 */
	protected $allowed_roles = array( Roles::ADMIN, Roles::MANAGER, Roles::INSTRUCTOR );

	/**
	 * Get event name the listener is listening to.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->event_name;
	}

	/**
	 * Get the roles which are allowed this listener.
	 *
	 * @since 1.6.9
	 *
	 * @return string[]
	 */
	public function get_allowed_roles() {
		return $this->allowed_roles;
	}

	/**
	 * Get listener label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	abstract public function get_label();

	/**
	 * Setup the webhook event.
	 *
	 * @since 1.6.9
	 *
	 * @param callable $deliver_callback
	 * @param \Masteriyo\Models\Webhook $webhook
	 */
	abstract public function setup( $deliver_callback, $webhook );

	/**
	 *  Return list of roles current listener can use.
	 *
	 * @since 1.6.9\
	 *
	 * @param int|\WP_User|\Masteriyo\Models\User $user
	 *
	 * @return string
	 */
	public function is_allowed( $user ) {
		$user    = masteriyo_get_user( $user );
		$allowed = false;

		if ( $user ) {
			foreach ( $this->get_allowed_roles() as $allowed_role ) {
				if ( $user->has_roles( $allowed_role ) ) {
					$allowed = true;
				}
			}
		}

		return $allowed;
	}

	/**
	 * Check permission if the triggered webhook should be delivered.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook Triggered webhook ID.
	 * @param integer $post_id Related post ID (i.e. course, lesson, section etc) that's being operated on. Default 0, which indicates no post.
	 *
	 * @return boolean
	 */
	public function can_deliver( $webhook, $post_id = 0 ) {
		// Give permission if the webhook author is an admin.
		if ( masteriyo_is_user_admin( $webhook->get_author_id() ) ) {
			return true;
		}

		return empty( $post_id ) || user_can( $webhook->get_author_id(), 'manage_masteriyo_settings' ) || user_can( $webhook->get_author_id(), 'edit_course', $post_id );
	}
}
