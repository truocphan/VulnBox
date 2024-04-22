<?php

namespace MasterStudy\Lms\Repositories;

final class ComingSoonRepository {
	const META_FIELDS = array(
		'coming_soon_show_course_price',
		'coming_soon_show_course_details',
		'coming_soon_email_notification',
		'coming_soon_preordering',
		'coming_soon_status',
		'coming_soon_time',
		'coming_soon_date',
		'coming_soon_message',
	);

	public function find_by_course( int $course_id ): array {
		$coming_soon_metas = array(
			'coming_soon_settings' => get_option( 'masterstudy_lms_coming_soon_settings' ),
		);

		foreach ( self::META_FIELDS as $meta_key ) {
			$meta_value                     = get_post_meta( $course_id, $meta_key, true );
			$coming_soon_metas[ $meta_key ] = $meta_value;
		}

		return $coming_soon_metas;
	}

	public function save( int $course_id, array $data ): void {
		do_action( 'masterstudy_lms_course_coming_soon_before_save', $course_id, $data );

		foreach ( $data as $key => $value ) {
			if ( in_array( $key, self::META_FIELDS ) ) {
				update_post_meta( $course_id, $key, $value );
			}
		}
	}
}
