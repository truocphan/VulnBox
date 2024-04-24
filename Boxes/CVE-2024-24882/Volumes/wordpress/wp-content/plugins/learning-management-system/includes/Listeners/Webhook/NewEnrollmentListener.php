<?php
/**
 * New student enrollment webhook event listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Resources\CourseResource;
use Masteriyo\Resources\UserCourseResource;
use Masteriyo\Resources\UserResource;
use Masteriyo\Resources\WebhookResource;

defined( 'ABSPATH' ) || exit;

/**
 * New student enrollment webhook event listener class.
 *
 * @since 1.6.9
 */
class NewEnrollmentListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'student.enrolled';

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'New Enrollment', 'masteriyo' );
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
			'masteriyo_new_user_course',
			function( $id, $user_course ) use ( $deliver_callback, $webhook ) {
				if ( ! $this->can_deliver( $webhook, $user_course->get_course_id() ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $user_course, $webhook ),
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
	 * @param \Masteriyo\Models\UserCourse $user_course
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $user_course, $webhook ) {
		$data   = UserCourseResource::to_array( $user_course );
		$user   = masteriyo_get_user( $user_course->get_user_id() );
		$course = masteriyo_get_course( $user_course->get_course_id() );

		if ( $user ) {
			$data['user'] = UserResource::to_array( $user );
		}

		if ( $course ) {
			$data['course'] = CourseResource::to_array( $course );
		}

		/**
		 * Filters the payload data for the currently triggered webhook.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data The payload data.
		 * @param \Masteriyo\Models\Webhook $webhook
		 * @param \Masteriyo\Listeners\Webhook\NewEnrollmentListener $listener Listener object.
		 * @param \Masteriyo\Models\UserCourse $user_course Course review model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $user_course );
	}
}
