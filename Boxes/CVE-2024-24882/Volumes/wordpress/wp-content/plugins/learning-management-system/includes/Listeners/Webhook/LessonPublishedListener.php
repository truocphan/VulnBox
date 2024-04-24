<?php
/**
 * Lesson published event listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Resources\CourseResource;
use Masteriyo\Resources\LessonResource;
use Masteriyo\Resources\WebhookResource;

defined( 'ABSPATH' ) || exit;

/**
 * Lesson published event listener class.
 *
 * @since 1.6.9
 */
class LessonPublishedListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'lesson.published';

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Lesson Published', 'masteriyo' );
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
			'masteriyo_new_lesson',
			function( $id, $lesson ) use ( $deliver_callback, $webhook ) {
				// Get the fresh data.
				$lesson = masteriyo_get_lesson( $lesson->get_id() );

				if (
					is_null( $lesson ) ||
					PostStatus::PUBLISH !== $lesson->get_status() ||
					! $this->can_deliver( $webhook, $lesson->get_course_id() )
				) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $lesson, $webhook ),
					)
				);
			},
			10,
			2
		);

		add_action(
			'masteriyo_lesson_status_changed',
			function( $lesson, $old_status, $new_status ) use ( $deliver_callback, $webhook ) {
				// Get the fresh data.
				$lesson = masteriyo_get_lesson( $lesson->get_id() );

				if (
					is_null( $lesson ) ||
					$old_status === $new_status ||
					PostStatus::PUBLISH !== $new_status ||
					! $this->can_deliver( $webhook, $lesson->get_course_id() )
				) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $lesson, $webhook ),
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
	 * @param \Masteriyo\Models\Lesson $lesson
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $lesson, $webhook ) {
		$data   = LessonResource::to_array( $lesson );
		$course = masteriyo_get_course( $lesson->get_course_id() );

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
		 * @param \Masteriyo\Listeners\Webhook\LessonCompletedListener $listener Listener object.
		 * @param \Masteriyo\Models\Lesson $lesson lesson review model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $lesson );
	}
}
