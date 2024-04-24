<?php
/**
 * Quiz published event listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Resources\CourseResource;
use Masteriyo\Resources\QuizResource;
use Masteriyo\Resources\WebhookResource;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz published event listener class.
 *
 * @since 1.6.9
 */
class QuizPublishedListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'quiz.published';

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Quiz Published', 'masteriyo' );
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
			'masteriyo_new_quiz',
			function( $id, $quiz ) use ( $deliver_callback, $webhook ) {
				if ( PostStatus::PUBLISH !== $quiz->get_status() || ! $this->can_deliver( $webhook, $quiz->get_course_id() ) ) {
					return;
				}

				// Get the fresh data.
				$quiz = masteriyo_get_quiz( $quiz->get_id() );

				if ( is_null( $quiz ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $quiz, $webhook ),
					)
				);
			},
			10,
			2
		);

		add_action(
			'masteriyo_quiz_status_changed',
			function( $quiz, $old_status, $new_status ) use ( $deliver_callback, $webhook ) {
				if (
					$old_status === $new_status ||
					PostStatus::PUBLISH !== $new_status ||
					! $this->can_deliver( $webhook, $quiz->get_course_id() )
				) {
					return;
				}

				// Get the fresh data.
				$quiz = masteriyo_get_quiz( $quiz->get_id() );

				if ( is_null( $quiz ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $quiz, $webhook ),
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
	 * @param \Masteriyo\Models\Quiz $quiz
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $quiz, $webhook ) {
		$data   = QuizResource::to_array( $quiz );
		$course = masteriyo_get_course( $quiz->get_course_id() );

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
		 * @param \Masteriyo\Listeners\Webhook\QuizCompletedListener $listener Listener object.
		 * @param \Masteriyo\Models\Quiz $quiz quiz review model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $quiz );
	}
}
