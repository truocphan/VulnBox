<?php
/**
 * Course completed event listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Resources\CourseProgressResource;
use Masteriyo\Resources\CourseResource;
use Masteriyo\Resources\UserResource;
use Masteriyo\Resources\WebhookResource;

defined( 'ABSPATH' ) || exit;

/**
 * Course completed event listener class.
 *
 * @since 1.6.9
 */
class CourseCompletedListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'course.completed';

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Course Completed', 'masteriyo' );
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
			'masteriyo_course_progress_status_completed',
			function( $id, $course_progress ) use ( $deliver_callback, $webhook ) {
				$course = masteriyo_get_course( $course_progress->get_course_id() );

				if ( empty( $course ) || ! $this->can_deliver( $webhook, $course->get_id() ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $course_progress, $webhook ),
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
	 * @param \Masteriyo\Models\CourseProgress $course_progress
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $course_progress, $webhook ) {
		$data   = CourseProgressResource::to_array( $course_progress );
		$course = masteriyo_get_course( $course_progress->get_course_id() );
		$user   = masteriyo_get_user( $course_progress->get_user_id() );

		if ( $course ) {
			$data['course'] = CourseResource::to_array( $course );
		}

		if ( $user ) {
			$data['user'] = UserResource::to_array( $user );
		}

		/**
		 * Filters the payload data for the currently triggered webhook.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data The payload data.
		 * @param \Masteriyo\Models\Webhook $webhook
		 * @param \Masteriyo\Listeners\Webhook\CourseCompletedListener $listener Listener object.
		 * @param \Masteriyo\Models\CourseProgress $course_progress Course model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $course_progress );
	}
}
