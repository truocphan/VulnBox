<?php
/**
 * New course review webhook event listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Resources\CourseResource;
use Masteriyo\Resources\CourseReviewResource;
use Masteriyo\Resources\WebhookResource;

defined( 'ABSPATH' ) || exit;

/**
 * New course review webhook event listener class.
 *
 * @since 1.6.9
 */
class NewCourseReviewListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'course.review.created';

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'New Course Review', 'masteriyo' );
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
			'masteriyo_new_course_review',
			function( $id, $course_review ) use ( $deliver_callback, $webhook ) {
				$course = masteriyo_get_course( $course_review->get_course_id() );

				if ( empty( $course ) || ! $this->can_deliver( $webhook, $course->get_id() ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $course_review, $webhook ),
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
	 * @param \Masteriyo\Models\CourseReview $course_review
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $course_review, $webhook ) {
		$data   = CourseReviewResource::to_array( $course_review );
		$course = masteriyo_get_course( $course_review->get_course_id() );

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
		 * @param \Masteriyo\Listeners\Webhook\NewCourseReviewListener $listener Listener object.
		 * @param \Masteriyo\Models\CourseReview $course_review Course review model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $course_review );
	}
}
