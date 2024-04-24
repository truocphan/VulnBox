<?php
/**
 * Quiz attempt event listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Enums\QuizAttemptStatus;
use Masteriyo\Resources\CourseResource;
use Masteriyo\Resources\QuizAttemptResource;
use Masteriyo\Resources\QuizResource;
use Masteriyo\Resources\UserResource;
use Masteriyo\Resources\WebhookResource;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz attempt event listener class.
 *
 * @since 1.6.9
 */
class QuizAttemptStatusChangeListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'quiz_attempt.status.changed';

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Quiz Attempt Status Change', 'masteriyo' );
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
			'masteriyo_quiz_attempt_status_changed',
			function( $attempt, $old_status, $new_status ) use ( $deliver_callback, $webhook ) {
				if ( ! $this->can_deliver( $webhook, $attempt->get_course_id() ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $attempt, $webhook ),
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
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $quiz_attempt, $webhook ) {
		$data   = QuizAttemptResource::to_array( $quiz_attempt );
		$course = masteriyo_get_course( $quiz_attempt->get_course_id() );
		$quiz   = masteriyo_get_quiz( $quiz_attempt->get_quiz_id() );
		$user   = masteriyo_get_user( $quiz_attempt->get_user_id() );

		if ( $quiz ) {
			$data['quiz'] = QuizResource::to_array( $quiz );
		}

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
		 * @param \Masteriyo\Listeners\Webhook\QuizAttemptStatusChangeListener $listener Listener object.
		 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt Course review model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $quiz_attempt );
	}
}
