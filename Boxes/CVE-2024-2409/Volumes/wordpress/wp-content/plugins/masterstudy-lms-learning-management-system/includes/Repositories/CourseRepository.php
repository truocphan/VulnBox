<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Models\Course;
use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Plugin\Taxonomy;
use MasterStudy\Lms\Utility\Sanitizer;

final class CourseRepository {
	/**
	 * course_property => meta_key
	 */
	public const FIELDS_META_MAPPING = array(
		'certificate_id'    => 'course_certificate',
		'current_students'  => 'current_students',
		'duration_info'     => 'duration_info',
		'end_time'          => 'end_time',
		'expiration'        => 'expiration_course',
		'level'             => 'level',
		'status'            => 'status',
		'status_date_end'   => 'status_dates_end',
		'status_date_start' => 'status_dates_start',
		'video_duration'    => 'video_duration',
		'views'             => 'views',
		'access_duration'   => 'access_duration',
		'access_devices'    => 'access_devices',
		'certificate_info'  => 'certificate_info',
	);

	public function exists( $id ): bool {
		return $this->find_post( $id ) !== null;
	}

	public function find( $id ): ?Course {
		$post = $this->find_post( $id );

		if ( null === $post ) {
			return null;
		}

		return $this->hydrate( $post );
	}

	/**
	 * @param $id
	 *
	 * @return \WP_Post|null
	 */
	public function find_post( int $id ): ?\WP_Post {
		$post = get_post( $id );

		if ( $post && PostType::COURSE === $post->post_type ) {
			return $post;
		}

		return null;
	}

	public function create( array $data ): int {
		$post = array(
			'post_name'  => $data['slug'],
			'post_title' => $data['title'],
			'post_type'  => PostType::COURSE,
		);

		$post_id = wp_insert_post( $post );

		wp_set_post_terms( $post_id, $data['category'], Taxonomy::COURSE_CATEGORY );

		if ( ! empty( $data['level'] ) ) {
			update_post_meta( $post_id, 'level', $data['level'] );
		}

		if ( ! empty( $data['image_id'] ) ) {
			set_post_thumbnail( $post_id, $data['image_id'] );
		}

		do_action( 'masterstudy_lms_course_saved', $post_id, $data );

		return $post_id;
	}

	public function save( Course $course ): void {
		$post = array(
			'ID'           => $course->id,
			'post_content' => apply_filters( 'masterstudy_lms_map_api_data', $course->content, 'post_content' ),
			'post_excerpt' => $course->excerpt,
			'post_name'    => $course->slug,
			'post_title'   => $course->title,
			'post_status'  => $this->moderate_post_status( $course->access_status ),
		);

		wp_update_post( $post );

		wp_set_post_terms( $post['ID'], $course->category, Taxonomy::COURSE_CATEGORY );

		foreach ( self::FIELDS_META_MAPPING as $property => $meta_key ) {
			update_post_meta( $post['ID'], $meta_key, $course->$property );
		}

		update_post_meta( $post['ID'], 'featured', $course->is_featured ? 'on' : '' );

		if ( null === $course->co_instructor ) {
			delete_post_meta( $post['ID'], 'co_instructor' );
		} else {
			update_post_meta( $post['ID'], 'co_instructor', $course->co_instructor->ID );
		}

		if ( null === $course->image ) {
			delete_post_thumbnail( $post['ID'] );
		} else {
			set_post_thumbnail( $post['ID'], $course->image['id'] );
		}

		do_action( 'masterstudy_lms_course_saved', $post['ID'], (array) $course );
	}

	public function update_certificate( int $course_id, $certificate_id ): void {
		update_post_meta( $course_id, self::FIELDS_META_MAPPING['certificate_id'], $certificate_id );
	}

	public function update_status( int $course_id, array $data ): void {
		wp_update_post(
			array(
				'ID'          => $course_id,
				'post_status' => $this->moderate_post_status( $data['status'] ),
			)
		);
	}

	public function update_access( int $course_id, array $data ): void {
		foreach ( $data as $key => $value ) {
			if ( isset( self::FIELDS_META_MAPPING[ $key ] ) ) {
				update_post_meta( $course_id, self::FIELDS_META_MAPPING[ $key ], $value );
			}
		}

		do_action( 'masterstudy_lms_course_update_access', $course_id, $data );
	}

	public function moderate_post_status( string $post_status ): ?string {
		if ( 'publish' === $post_status
			&& ! current_user_can( 'administrator' )
			&& \STM_LMS_Options::get_option( 'course_premoderation', false ) ) {
			return 'pending';
		}

		return $post_status;
	}

	public function get_announcement( int $course_id ): ?string {
		return get_post_meta( $course_id, 'announcement', true );
	}

	public function update_announcement( int $course_id, string $announcement ): void {
		update_post_meta( $course_id, 'announcement', Sanitizer::html( $announcement ) );
	}

	private function get_course_image( \WP_Post $post ): ?array {
		$attachment = get_post( get_post_thumbnail_id( $post ) );

		if ( $attachment ) {
			return array(
				'id'    => $attachment->ID,
				'title' => $attachment->post_title,
				'type'  => get_post_mime_type( $attachment->ID ),
				'url'   => wp_get_attachment_url( $attachment->ID ),
			);
		}

		return null;
	}

	private function find_user( $id ): ?\WP_User {
		// phpcs:ignore WordPress.PHP.DisallowShortTernary.Found
		return get_user_by( 'id', $id ) ?: null;
	}

	private function hydrate( \WP_Post $post ): Course {
		$meta = get_post_meta( $post->ID );

		$course                    = new Course();
		$course->access_status     = $post->post_status;
		$course->category          = wp_get_post_terms( $post->ID, Taxonomy::COURSE_CATEGORY, array( 'fields' => 'ids' ) );
		$course->certificate_id    = intval( $meta['course_certificate'][0] ?? null );
		$course->co_instructor     = isset( $meta['co_instructor'][0] )
			? $this->find_user( $meta['co_instructor'][0] )
			: null;
		$course->current_students  = intval( $meta['current_students'][0] ?? 0 );
		$course->content           = $post->post_content;
		$course->duration_info     = $meta['duration_info'][0] ?? '';
		$course->end_time          = intval( $meta['end_time'][0] ?? 0 );
		$course->excerpt           = $post->post_excerpt;
		$course->expiration        = (bool) ( $meta['expiration_course'][0] ?? false );
		$course->files             = ( new FileMaterialRepository() )->get_files( $meta['course_files'][0] ?? null, true );
		$course->id                = $post->ID;
		$course->image             = $this->get_course_image( $post );
		$course->is_featured       = ( $meta['featured'][0] ?? null ) === 'on';
		$course->level             = $meta['level'][0] ?? null;
		$course->owner             = $this->find_user( $post->post_author );
		$course->slug              = $post->post_name;
		$course->status            = $meta['status'][0] ?? null;
		$course->status_date_end   = isset( $meta['status_dates_end'][0] ) ? (int) $meta['status_dates_end'][0] : null;
		$course->status_date_start = isset( $meta['status_dates_start'][0] ) ? (int) $meta['status_dates_start'][0] : null;
		$course->title             = $post->post_title;
		$course->video_duration    = $meta['video_duration'][0] ?? '';
		$course->views             = $meta['views'][0] ?? 0;
		$course->access_duration   = $meta['access_duration'][0] ?? '';
		$course->access_devices    = $meta['access_devices'][0] ?? '';
		$course->certificate_info  = $meta['certificate_info'][0] ?? '';

		return apply_filters( 'masterstudy_lms_course_hydrate', $course, $meta );
	}
}
