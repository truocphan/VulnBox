<?php

use MasterStudy\Lms\Database\CurriculumMaterial;
use MasterStudy\Lms\Database\CurriculumSection;
use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use MasterStudy\Lms\Repositories\CurriculumSectionRepository;

/**
 * Load a template file for demo import.
 *
 * @param string $tpl The name of the template file to be loaded (without the file extension).
 */
function stm_lms_demo_import_load_template( $tpl ) {
	require STM_LMS_PATH . "/settings/demo_import/tpls/{$tpl}.php";
}

add_filter(
	'wpcfto_field_demo_import',
	function () {
		return STM_LMS_PATH . '/settings/demo_import/demo_import.php';
	}
);

add_action( 'wp_ajax_stm_lms_import_sample_data', 'stm_lms_import_sample_data' );
/**
 * Import sample data for the LMS.
 *
 * @param string $post_type The type of post to import sample data for (optional).
 * @param bool   $die       Whether to terminate the process with JSON response (optional).
 */
function stm_lms_import_sample_data( $post_type = '', $die = true ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$step = ! empty( $_GET['stm_lms_step'] ) ? sanitize_text_field( wp_unslash( $_GET['stm_lms_step'] ) ) : $post_type;
	if ( ! empty( $step ) ) {

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		require_once STM_LMS_PATH . '/settings/demo_import/wordpress-importer/class-stm-wp-import.php';
		require_once STM_LMS_PATH . '/settings/demo_import/wordpress-importer/wordpress-importer.php';

		$file = STM_LMS_PATH . '/settings/demo_import/sample_data/' . $step . '.xml';
		if ( file_exists( $file ) ) {

			$wp_import = new STM_WP_Import();

			ob_start();
			$wp_import->import( $file );
			ob_end_clean();
			if ( 'courses' === $step ) {
				$placeholder_id = stm_lms_upload_placeholder();

				$q     = array(
					'post_type'      => 'stm-courses',
					'posts_per_page' => -1,
				);
				$query = new WP_Query( $q );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						$course_id = get_the_ID();

						if ( ! has_post_thumbnail( get_the_ID() ) || ! wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ) ) {
							set_post_thumbnail( get_the_ID(), $placeholder_id );
						}

						stm_lms_set_course_curriculum( $course_id );
					}
				}

				wp_reset_postdata();
			}
			if ( $die ) {
				wp_send_json( 'ok' );
			}
		}
	}
}

/**
 * Get the ID of the placeholder image used for LMS.
 *
 * @return int The ID of the placeholder image attachment.
 */
function stm_lms_get_placeholder() {
	$placeholder_id    = 0;
	$placeholder_array = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_attachment_image_alt',
			'meta_value'     => 'stm_lms_placeholder',
		)
	);
	if ( $placeholder_array ) {
		foreach ( $placeholder_array as $val ) {
			$placeholder_id = $val->ID;
		}
	}

	return $placeholder_id;
}

/**
 * Upload and set a placeholder image for LMS if not already set.
 *
 * @return int The ID of the uploaded or existing placeholder image attachment.
 */
function stm_lms_upload_placeholder() {
	$placeholder = stm_lms_get_placeholder();
	if ( empty( $placeholder ) ) {

		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$upload_dir = wp_upload_dir();

		$placeholder_path = STM_LMS_PATH . '/assets/img/placeholder.gif';
		$image_data       = $wp_filesystem->get_contents( $placeholder_path );

		$filename = basename( $placeholder_path );

		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
		$wp_filesystem->put_contents( $file, $image_data, FS_CHMOD_FILE );

		$wp_filetype = wp_check_filetype( $filename, null );

		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $file );
		update_post_meta( $attach_id, '_wp_attachment_image_alt', 'stm_lms_placeholder' );
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		$placeholder = $attach_id;
	}

	return $placeholder;
}

/**
 * Get a demo curriculum structure.
 *
 * @return array An array representing the demo curriculum.
 */
function stm_lms_get_demo_curriculum() {
	$curriculum = array(
		array(
			'title'     => 'Starting Course',
			'order'     => 1,
			'materials' => array(
				array(
					'title' => 'Nvidia New Technologies Slides',
					'order' => 1,
				),
				array(
					'title' => 'Engine Target Audience',
					'order' => 2,
				),
				array(
					'title' => 'Quiz: Mobile / Native Apps',
					'order' => 3,
				),
			),
		),
		array(
			'title'     => 'After Intro',
			'order'     => 2,
			'materials' => array(
				array(
					'title' => 'Realistic Graphic on UE4',
					'order' => 1,
				),
				array(
					'title' => 'Volta GPU for optimization.',
					'order' => 2,
				),
				array(
					'title' => 'Deep Learning',
					'order' => 3,
				),
			),
		),
	);

	foreach ( $curriculum as &$section ) {
		foreach ( $section['materials'] as &$material ) {
			global $wpdb;
			$post = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $wpdb->posts WHERE post_title LIKE %s",
					$wpdb->esc_like( $material['title'] )
				)
			);

			if ( ! empty( $post->ID ) ) {
				$material['post_id']   = $post->ID;
				$material['post_type'] = $post->post_type;
			}
		}
	}

	return $curriculum;
}

add_action( 'stm_masterstudy_importer_done', 'stm_lms_update_curriculum' );
/**
 * Update curriculum for all courses.
 */
function stm_lms_update_curriculum() {
	$args  = array(
		'post_type'      => 'stm-courses',
		'posts_per_page' => - 1,
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			stm_lms_set_course_curriculum( get_the_ID() );
		}
	}

	wp_reset_postdata();
}

/**
 * Set the curriculum for a course.
 *
 * @param int $course_id The ID of the course.
 */
function stm_lms_set_course_curriculum( $course_id ) {
	$section_repository  = new CurriculumSectionRepository();
	$material_repository = new CurriculumMaterialRepository();
	$curriculum          = stm_lms_get_demo_curriculum();

	foreach ( $curriculum as $section ) {
		$current_section = ( new CurriculumSection() )->query()
			->where( 'course_id', $course_id )
			->where( 'title', $section['title'] )
			->findOne();

		if ( ! $current_section ) {
			$current_section = $section_repository->create(
				array(
					'title'     => $section['title'],
					'course_id' => $course_id,
					'order'     => $section['order'],
				)
			);
		}

		if ( ! empty( $current_section->id ) ) {
			foreach ( $section['materials'] as $material ) {
				if ( ! empty( $material['post_id'] ) ) {
					$current_material = ( new CurriculumMaterial() )->query()
						->where( 'post_id', $material['post_id'] )
						->where( 'section_id', $current_section->id )
						->findOne();

					if ( ! $current_material ) {
						$material_repository->create(
							array(
								'post_id'    => $material['post_id'],
								'post_type'  => $material['post_type'],
								'section_id' => $current_section->id,
								'order'      => $material['order'],
							)
						);
					}
				}
			}
		}
	}
}
