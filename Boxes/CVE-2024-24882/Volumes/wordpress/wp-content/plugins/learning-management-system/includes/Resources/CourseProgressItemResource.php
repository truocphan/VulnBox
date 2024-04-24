<?php
/**
 * Resource handler for Course progress item data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Course progress item data.
 *
 * @since 1.6.9
 */
class CourseProgressItemResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\CourseProgressItem $progress_item
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $progress_item, $context = 'view' ) {
		$progress = masteriyo_get_course_progress( $progress_item->get_progress_id( 'edit' ) );

		$data = array(
			'id'           => $progress_item->get_id( $context ),
			'progress_id'  => $progress_item->get_progress_id( $context ),
			'course_id'    => is_null( $progress ) ? $progress_item->get_item_id( $context ) : $progress->get_course_id( $context ),
			'user_id'      => $progress_item->get_user_id( $context ),
			'item_id'      => $progress_item->get_item_id( $context ),
			'item_type'    => $progress_item->get_item_type( $context ),
			'completed'    => $progress_item->get_completed( $context ),
			'started_at'   => masteriyo_rest_prepare_date_response( $progress_item->get_started_at( $context ) ),
			'modified_at'  => masteriyo_rest_prepare_date_response( $progress_item->get_modified_at( $context ) ),
			'completed_at' => masteriyo_rest_prepare_date_response( $progress_item->get_completed_at( $context ) ),
		);

		/**
		 * Filter course progress item data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Progress item data.
		 * @param \Masteriyo\Models\CourseProgressItem $progress_item Course progress item object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_course_progress_item_resource_array', $data, $progress_item, $context );
	}
}
