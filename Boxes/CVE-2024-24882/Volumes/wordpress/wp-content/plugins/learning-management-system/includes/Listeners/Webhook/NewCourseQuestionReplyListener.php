<?php
/**
 * New reply to a course question event listener class.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Listeners\Webhook;

use Masteriyo\Abstracts\Listener;
use Masteriyo\Resources\CourseQuestionAnswerResource;
use Masteriyo\Resources\CourseResource;
use Masteriyo\Resources\WebhookResource;

defined( 'ABSPATH' ) || exit;

/**
 * New reply to a course question event listener class.
 *
 * @since 1.6.9
 */
class NewCourseQuestionReplyListener extends Listener {

	/**
	 * Event name the listener is listening to.
	 *
	 * @since 1.6.9
	 */
	protected $event_name = 'course.question.reply.created';

	/**
	 * Get event label.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'New Reply to a question in a Course', 'masteriyo' );
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
			'masteriyo_new_course_qa',
			function( $id, $course_qa ) use ( $deliver_callback, $webhook ) {
				if ( ! $course_qa->is_answer() || ! $this->can_deliver( $webhook, $course_qa->get_course_id() ) ) {
					return;
				}

				call_user_func_array(
					$deliver_callback,
					array(
						WebhookResource::to_array( $webhook ),
						$this->get_payload( $course_qa, $webhook ),
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
	 * @param \Masteriyo\Models\CourseQuestionAnswer $course_qa
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array
	 */
	protected function get_payload( $course_qa, $webhook ) {
		$data     = CourseQuestionAnswerResource::to_array( $course_qa );
		$course   = masteriyo_get_course( $course_qa->get_course_id() );
		$question = masteriyo_get_course_qa( $course_qa->get_parent() );

		if ( $question ) {
			$data['question'] = CourseQuestionAnswerResource::to_array( $question );
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
		 * @param \Masteriyo\Listeners\Webhook\NewCourseQuestionListener $listener Listener object.
		 * @param \Masteriyo\Models\CourseQuestionAnswer $course_qa Course review model object.
		 */
		return apply_filters( "masteriyo_webhook_payload_for_{$this->event_name}", $data, $webhook, $this, $course_qa );
	}
}
