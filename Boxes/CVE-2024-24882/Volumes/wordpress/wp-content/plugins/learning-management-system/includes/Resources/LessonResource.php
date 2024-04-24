<?php
/**
 * Resource handler for Lesson data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Lesson data.
 *
 * @since 1.6.9
 */
class LessonResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Lesson $lesson
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $lesson, $context = 'view' ) {
		$section = masteriyo_get_section( $lesson->get_parent_id() );
		$course  = masteriyo_get_course( $lesson->get_course_id( $context ) );

		/**
		 * Filters lesson short description.
		 *
		 * @since 1.0.0
		 *
		 * @param string $short_description Lesson short description.
		 */
		$short_description = 'view' === $context ? apply_filters( 'masteriyo_short_description', $lesson->get_short_description() ) : $lesson->get_short_description();

		$data = array(
			'id'                  => $lesson->get_id(),
			'name'                => wp_specialchars_decode( $lesson->get_name( $context ) ),
			'slug'                => $lesson->get_slug( $context ),
			'permalink'           => $lesson->get_permalink(),
			'preview_link'        => $lesson->get_preview_link(),
			'status'              => $lesson->get_status( $context ),
			'description'         => 'view' === $context ? wpautop( do_shortcode( $lesson->get_description() ) ) : $lesson->get_description( $context ),
			'short_description'   => $short_description,
			'date_created'        => masteriyo_rest_prepare_date_response( $lesson->get_date_created( $context ) ),
			'date_modified'       => masteriyo_rest_prepare_date_response( $lesson->get_date_modified( $context ) ),
			'menu_order'          => $lesson->get_menu_order( $context ),
			'parent_menu_order'   => $section ? $section->get_menu_order( $context ) : 0,
			'reviews_allowed'     => $lesson->get_reviews_allowed( $context ),
			'parent_id'           => $lesson->get_parent_id( $context ),
			'course_id'           => $course ? $course->get_id() : 0,
			'course_name'         => $course ? wp_specialchars_decode( $course->get_name( $context ) ) : '',
			'featured_image'      => $lesson->get_featured_image( $context ),
			'video_source'        => $lesson->get_video_source( $context ),
			'video_source_url'    => $lesson->get_video_source_url( $context ),
			'video_source_id'     => $lesson->get_video_source_id( $context ),
			'video_playback_time' => $lesson->get_video_playback_time( $context ),
			'attachments'         => self::get_attachments( $lesson, $context ),
		);

		/**
		 * Filter lesson data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Lesson data.
		 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_lesson_resource_array', $data, $lesson, $context );
	}

	/**
	 * Get lesson attachments.
	 *
	 * @since 1.6.9
	 *
	 * @param Masteriyo\Models\Lesson $lesson Lesson object.
	 * @param string $context Request context.
	 *
	 * @return array
	 */
	protected static function get_attachments( $lesson, $context ) {
		// Filter invalid attachments.
		$attachments = array_filter(
			array_map(
				function( $attachment ) {
					$post = get_post( $attachment );

					if ( $post && 'attachment' === $post->post_type ) {
						return $post;
					}

					return false;
				},
				$lesson->get_attachments( $context )
			)
		);

		// Convert the attachments to the response format.
		$attachments = array_reduce(
			$attachments,
			function( $result, $attachment ) {
				$result[] = array(
					'id'    => $attachment->ID,
					'url'   => wp_get_attachment_url( $attachment->ID ),
					'title' => $attachment->post_title,
				);

				return $result;
			},
			array()
		);

		return $attachments;
	}
}
