<?php

namespace MasterStudy\Lms\Http\Serializers;

use MasterStudy\Lms\Models\Course;

final class CourseSerializer extends AbstractSerializer {
	/**
	 * @param Course $course
	 */
	public function toArray( $course ): array {
		return array(
			'access_status'     => $course->access_status,
			'category'          => $course->category,
			'certificate_id'    => $course->certificate_id,
			'co_instructor'     => $this->when(
				$course->co_instructor,
				function ( \WP_User $co_instructor ) {
					return array(
						'id'   => $co_instructor->ID,
						'name' => $co_instructor->display_name,
					);
				}
			),
			'content'           => $course->content,
			'current_students'  => $course->current_students,
			'duration_info'     => $course->duration_info,
			'end_time'          => $course->end_time,
			'excerpt'           => $course->excerpt,
			'expiration'        => $course->expiration,
			'files'             => $course->files,
			'id'                => $course->id,
			'image'             => $course->image,
			'is_featured'       => $course->is_featured,
			'owner'             => array(
				'id'     => $course->owner->ID,
				'name'   => $course->owner->display_name,
				'avatar' => $this->get_avatar_url( $course->owner->ID ),
			),
			'level'             => $course->level,
			'prerequisites'     => $course->prerequisites,
			'shareware'         => $course->shareware,
			'slug'              => $course->slug,
			'status'            => $course->status,
			'status_date_end'   => $course->status_date_end,
			'status_date_start' => $course->status_date_start,
			'title'             => html_entity_decode( $course->title ),
			'video_duration'    => $course->video_duration,
			'views'             => $course->views,
			'access_duration'   => $course->access_duration,
			'access_devices'    => $course->access_devices,
			'certificate_info'  => $course->certificate_info,
		);
	}

	private function get_avatar_url( int $user_id ) {
		$url = get_user_meta( $user_id, 'stm_lms_user_avatar', true );

		if ( empty( $url ) ) {
			$url = get_avatar_url( $user_id );
		}

		return $url;
	}
}
