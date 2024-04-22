<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder;

use WP_User;

class CertificateFieldsDataResolver {
	/**
	 * @param array{course_id: ?int, id: int, fields: string} $certificate
	 */
	public static function resolve( array $certificate ): array {

		if ( empty( $certificate['fields'] ) ) {
			return array();
		}

		try {
			$fields = json_decode( $certificate['fields'], true, 512, JSON_THROW_ON_ERROR );
		} catch ( \JsonException $e ) {
			return array();
		}

		if ( ! is_array( $fields ) ) {
			return array();
		}

		$resolved     = array();
		$current_user = wp_get_current_user();

		foreach ( $fields as $field ) {
			$resolver = $field['type'] . '_resolver';

			if ( method_exists( __CLASS__, $resolver ) ) {
				$field = static::$resolver( $field, $certificate, $current_user );
			}

			$resolved[] = $field;
		}

		return $resolved;
	}

	/**
	 * @param array{imageId: int, type: string, content: string} $field
	 */
	protected static function image_resolver( array $field, array $certificate, WP_User $current_user ): array {
		$file = get_attached_file( $field['imageId'] );

		if ( $file ) {
			$field['image_data'] = ImageEncoder::to_base64( $file );
		}

		return $field;
	}

	protected static function course_name_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$field['content'] = html_entity_decode( get_the_title( $certificate['course_id'] ) );
		}

		return $field;
	}

	protected static function author_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$author           = get_post_field( 'post_author', $certificate['course_id'] );
			$author_name      = get_the_author_meta( 'display_name', $author );
			$field['content'] = $author_name;
		}

		return $field;
	}

	protected static function student_name_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) && ! empty( $current_user ) ) {

			$user_name = get_user_meta( $current_user->ID, 'user_fullname', true );

			if ( empty( $user_name ) ) {
				$last_name  = get_user_meta( $current_user->ID, 'last_name', true );
				$first_name = get_user_meta( $current_user->ID, 'first_name', true );
				$user_name  = trim( "$first_name $last_name" );

				update_user_meta( $current_user->ID, 'user_fullname', $user_name );
			}

			$field['content'] = ! empty( $user_name ) ? $user_name : $current_user->display_name;
		}

		return $field;
	}

	protected static function start_date_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$start_date = stm_lms_get_user_course( $current_user->ID, $certificate['course_id'], array( 'start_time' ) );
			if ( ! empty( $start_date ) ) {
				$start_date = \STM_LMS_Helpers::simplify_db_array( $start_date );
				if ( ! empty( $start_date['start_time'] ) ) {
					$date_format      = get_option( 'date_format', 'j F Y' );
					$field['content'] = date_i18n( $date_format, $start_date['start_time'] );
				}
			}
		}

		return $field;
	}

	protected static function end_date_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$end_date = get_user_meta( $current_user->ID, 'last_progress_time', true );
			if ( ! empty( $end_date[ $certificate['course_id'] ] ) ) {
				$date_format      = get_option( 'date_format', 'j F Y' );
				$field['content'] = date_i18n( $date_format, $end_date[ $certificate['course_id'] ] );
			}
		}

		return $field;
	}

	protected static function current_date_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$date_format      = get_option( 'date_format', 'j F Y' );
			$field['content'] = date_i18n( $date_format, time() );
		}

		return $field;
	}

	protected static function progress_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$progress = stm_lms_get_user_course( $current_user->ID, $certificate['course_id'], array( 'progress_percent' ) );
			if ( ! empty( $progress ) ) {
				$progress = \STM_LMS_Helpers::simplify_db_array( $progress );
				if ( ! empty( $progress['progress_percent'] ) ) {
					$field['content'] = $progress['progress_percent'] . '%';
				}
			}
		}

		return $field;
	}

	protected static function co_instructor_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$co_instructor    = get_post_meta( $certificate['course_id'], 'co_instructor', true );
			$field['content'] = '';
			if ( ! empty( $co_instructor ) ) {
				$co_instructor_data = get_userdata( $co_instructor );
				if ( $co_instructor_data ) {
					$co_instructor_name = $co_instructor_data->data->display_name;
					$field['content']   = $co_instructor_name;
				}
			}
		}
		return $field;
	}

	protected static function details_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$curriculum_info  = \STM_LMS_Course::curriculum_info( $certificate['course_id'] );
			$field['content'] = sprintf(
				/* translators: %s: number */
				esc_html__( '%1$s Lessons, %2$s Quizzes', 'masterstudy-lms-learning-management-system-pro' ),
				$curriculum_info['lessons'],
				$curriculum_info['quizzes']
			);
		}

		return $field;
	}

	protected static function student_code_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$user_code = get_user_meta( $current_user->ID, 'certificate_user_code', true );

			if ( empty( $user_code ) ) {
				$user_code = CodeGenerator::generate();
				update_user_meta( $current_user->ID, 'certificate_user_code', $user_code );
			}

			$field['content'] = $user_code;
		}

		return $field;
	}

	protected static function code_resolver( array $field, array $certificate, WP_User $current_user ): array {
		if ( ! empty( $certificate['course_id'] ) ) {
			$field['content'] = \STM_LMS_Certificates::generate_certificate_code( $current_user->ID, $certificate['course_id'] );
		}
		return $field;
	}
}
