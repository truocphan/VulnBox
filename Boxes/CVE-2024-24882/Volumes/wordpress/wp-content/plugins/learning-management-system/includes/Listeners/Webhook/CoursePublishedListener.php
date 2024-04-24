<?php
/**
 * Course published event/hook listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Resources\CourseResource;
use Masteriyo\Resources\WebhookResource;

defined( 'ABSPATH' ) || exit;

/**
 * Course published event/hook listener class.
 *
 * @since 1.6.9
 */
class CoursePublishedListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'course.published';

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Course Published', 'masteriyo' );
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
			'masteriyo_new_course',
			function( $id, $course ) use ( $deliver_callback, $webhook ) {
				if ( PostStatus::PUBLISH !== $course->get_status() || ! $this->can_deliver( $webhook, $course->get_id() ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $course, $webhook ),
					)
				);
			},
			10,
			2
		);

		add_action(
			'masteriyo_course_status_changed',
			function( $course, $old_status, $new_status ) use ( $deliver_callback, $webhook ) {
				if (
					$old_status === $new_status ||
					PostStatus::PUBLISH !== $new_status ||
					! $this->can_deliver( $webhook, $course->get_id() )
				) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $course, $webhook ),
					)
				);
			},
			10,
			3
		);
	}

	/**
	 * Get payload data for the currently triggered webhook.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Course $course
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $course, $webhook ) {
		$data = CourseResource::to_array( $course );

		/**
		 * Filters the payload data for the currently triggered webhook.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data The payload data.
		 * @param \Masteriyo\Models\Webhook $webhook
		 * @param \Masteriyo\Listeners\Webhook\CoursePublishedListener $listener Listener object.
		 * @param \Masteriyo\Models\Course $course Course review model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $course );
	}
}
