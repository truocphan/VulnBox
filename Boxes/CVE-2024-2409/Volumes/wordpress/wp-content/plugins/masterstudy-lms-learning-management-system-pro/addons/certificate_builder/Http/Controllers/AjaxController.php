<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder\Http\Controllers;

use MasterStudy\Lms\Pro\addons\certificate_builder\CertificateFieldsDataResolver;
use MasterStudy\Lms\Pro\addons\certificate_builder\CertificateRepository;
use MasterStudy\Lms\Pro\addons\certificate_builder\ImageEncoder;

class AjaxController {
	public static function get_certificates(): void {
		check_ajax_referer( 'stm_get_certificates', 'nonce' );

		$repo     = self::get_repository();
		$response = array();

		foreach ( $repo->get_all() as $certificate ) {
			$resource = array(
				'id'           => $certificate['id'],
				'title'        => $certificate['title'],
				'thumbnail_id' => get_post_thumbnail_id( $certificate['id'] ),
				'thumbnail'    => get_the_post_thumbnail_url( $certificate['id'], 'thumbnail' ),
				'image'        => get_the_post_thumbnail_url( $certificate['id'], 'full' ),
				'classes'      => '',
				'filename'     => '',
				'data'         => array(
					'orientation' => $certificate['orientation'],
					'fields'      => array(),
					'category'    => $certificate['category'],
				),
			);

			if ( ! empty( $resource['thumbnail_id'] ) ) {
				$resource['filename'] = basename( get_attached_file( $resource['thumbnail_id'] ) );
			}

			if ( ! empty( $certificate['fields'] ) ) {
				$resource['data']['fields'] = json_decode( $certificate['fields'], true );
			}

			if ( empty( $resource['data']['orientation'] ) ) {
				$resource['data']['orientation'] = 'landscape';
			}

			$response[] = $resource;
		}

		wp_send_json( $response );
	}

	public static function get_fields(): void {
		check_ajax_referer( 'stm_get_certificate_fields', 'nonce' );

		$fields = array(
			'text'          => array(
				'name'  => esc_html__( 'Text', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( 'Any text', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'course_name'   => array(
				'name'  => esc_html__( 'Course name', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Course name-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'student_name'  => array(
				'name'  => esc_html__( 'Student name', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Student name-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'image'         => array(
				'name'  => esc_html__( 'Image', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => '',
			),
			'author'        => array(
				'name'  => esc_html__( 'Instructor', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Instructor-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'start_date'    => array(
				'name'  => esc_html__( 'Start Date', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Start Date-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'end_date'      => array(
				'name'  => esc_html__( 'End Date', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-End Date-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'current_date'  => array(
				'name'  => esc_html__( 'Current Date', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Current Date-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'co_instructor' => array(
				'name'  => esc_html__( 'Co Instructor', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Co Instructor-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'progress'      => array(
				'name'  => esc_html__( 'Progress', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Progress-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'details'       => array(
				'name'  => esc_html__( 'Details', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Details-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'code'          => array(
				'name'  => esc_html__( 'Certificate code', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Certificate code-', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'student_code'  => array(
				'name'  => esc_html__( 'Student code', 'masterstudy-lms-learning-management-system-pro' ),
				'value' => esc_html__( '-Student code-', 'masterstudy-lms-learning-management-system-pro' ),
			),
		);

		wp_send_json( apply_filters( 'stm_certificates_fields', $fields ) );
	}

	public static function save_certificate(): void {
		check_ajax_referer( 'stm_save_certificate', 'nonce' );

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( null, 403 );
		}

		if ( empty( $_POST['certificate'] ) ) {
			return;
		}

		$certificate = $_POST['certificate'];
		$args        = array(
			'title'        => esc_html__( 'New template', 'masterstudy-lms-learning-management-system-pro' ),
			'orientation'  => 'landscape',
			'fields'       => '',
			'category'     => '',
			'thumbnail_id' => $certificate['thumbnail_id'] ?? 0,
		);

		if ( ! empty( $certificate['title'] ) ) {
			$args['title'] = wp_strip_all_tags( $certificate['title'] );
		}
		if ( ! empty( $certificate['data']['orientation'] ) ) {
			$args['orientation'] = sanitize_text_field( $certificate['data']['orientation'] );
		}
		if ( ! empty( $certificate['data']['fields'] ) ) {
			$args['fields'] = wp_json_encode( $certificate['data']['fields'], JSON_HEX_APOS + JSON_UNESCAPED_UNICODE );
		}
		if ( ! empty( $certificate['data']['category'] ) ) {
			$args['category'] = sanitize_text_field( $certificate['data']['category'] );
		}

		$repo = self::get_repository();
		if ( empty( $certificate['id'] ) ) {
			$post_id = $repo->create( wp_slash( $args ) );
		} else {
			$post_id = intval( $certificate['id'] );
			$repo->update( $post_id, $args );
		}

		do_action( 'wp_ajax_stm_lms_pro_certificate_update' );

		wp_send_json(
			array(
				'id' => $post_id,
			)
		);
	}

	public static function delete_certificate() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( null, 403 );
		}
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['certificate_id'] ) ) {
			return;
		}

		self::get_repository()->delete( intval( $_GET['certificate_id'] ) );
		wp_send_json( 'deleted' );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
	}

	public static function get_certificate() {
		check_ajax_referer( 'stm_get_certificate', 'nonce' );

		$id        = '';
		$course_id = filter_input( INPUT_GET, 'course_id', FILTER_SANITIZE_NUMBER_INT );

		$repo = self::get_repository();
		if ( $course_id ) {
			$id = get_post_meta( $course_id, 'course_certificate', true );

			if ( ! $id ) {
				$terms = wp_get_post_terms( $course_id, 'stm_lms_course_taxonomy', array( 'fields' => 'ids' ) );
				$id    = $repo->get_first_for_categories( $terms );
			}
		}

		if ( empty( $id ) ) {
			$id = filter_input( INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT );
		}

		if ( empty( $id ) ) {
			return;
		}

		$certificate              = $repo->get( $id );
		$certificate['course_id'] = $course_id;

		if ( empty( $certificate['orientation'] ) ) {
			$certificate['orientation'] = 'landscape';
		}

		$base64     = false;
		$image_size = false;
		$image      = get_post_thumbnail_id( $id );
		if ( $image ) {
			$image_file = get_attached_file( $image );

			if ( $image_file ) {
				$image_size = getimagesize( $image_file );
				$base64     = ImageEncoder::to_base64( $image_file );
			}
		}

		$fields = CertificateFieldsDataResolver::resolve( $certificate );
		$fields = apply_filters( 'masterstudy_lms_certificate_fields_data', $fields, $certificate );

		$response = array(
			'data' => array(
				'orientation' => $certificate['orientation'],
				'fields'      => $fields,
				'image'       => $base64,
				'image_size'  => $image_size,
			),
		);

		wp_send_json( $response );
	}

	public static function get_categories() {
		check_ajax_referer( 'stm_get_certificate_categories', 'nonce' );

		$result = array();
		$terms  = get_terms(
			array(
				'taxonomy'   => 'stm_lms_course_taxonomy',
				'hide_empty' => false,
			)
		);

		foreach ( $terms as $term ) {
			$result[] = array(
				'id'   => $term->term_id,
				'name' => $term->name,
			);
		}

		wp_send_json( $result );
	}

	private static function get_repository(): CertificateRepository {
		return new CertificateRepository();
	}
}
