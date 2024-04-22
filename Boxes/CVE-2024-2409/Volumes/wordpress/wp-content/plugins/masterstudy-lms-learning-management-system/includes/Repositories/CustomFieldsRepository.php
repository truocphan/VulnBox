<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Plugin\PostType;

final class CustomFieldsRepository {
	public function update( $post_id, $data ): void {
		$fields = array_column(
			apply_filters( $this->get_filter_name( $post_id ), array() ),
			'name',
		);

		foreach ( $fields as $field ) {
			if ( array_key_exists( $field, $data ) ) {
				update_post_meta( $post_id, $field, $data[ $field ] );
			}
		}

		do_action( 'masterstudy_lms_custom_fields_updated', $post_id, $data );
	}

	public function get_filter_name( $post_id ): string {
		switch ( get_post_type( $post_id ) ) {
			case PostType::COURSE:
				return 'masterstudy_lms_course_custom_fields';
			case PostType::LESSON:
				return 'masterstudy_lms_lesson_custom_fields';
			case PostType::QUIZ:
				return 'masterstudy_lms_quiz_custom_fields';
			default:
				return 'masterstudy_lms_custom_fields';
		}
	}
}
