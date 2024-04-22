<?php

/**
 * @var $course_id
 */

$total_progress = STM_LMS_Lesson::get_total_progress( get_current_user_id(), $course_id );

if ( ! empty( $total_progress ) && $total_progress['course_completed'] ) :
	stm_lms_register_style( 'lesson/total_progress' );
	if ( is_ms_lms_addon_enabled( 'certificate_builder' ) ) {
		wp_register_script( 'jspdf', STM_LMS_PRO_URL . '/assets/js/certificate-builder/jspdf.umd.js', array(), stm_lms_custom_styles_v(), false );
		wp_enqueue_script( 'masterstudy_generate_certificate', STM_LMS_URL . '/assets/js/course-player/generate-certificate.js', array( 'jspdf', 'masterstudy_certificate_fonts' ), stm_lms_custom_styles_v(), true );
		wp_localize_script(
			'masterstudy_generate_certificate',
			'course_certificate',
			array(
				'nonce'    => wp_create_nonce( 'stm_get_certificate' ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}
	?>

	<div class="stm_lms_course_completed_summary">

		<div class="stm_lms_course_completed_summary__title">
			<span><?php esc_html_e( 'You have completed the course:', 'masterstudy-lms-learning-management-system' ); ?></span>
			<strong><?php printf( /* translators: %s will be replaced with a string. */ esc_html__( 'Score %s', 'masterstudy-lms-learning-management-system' ), esc_html( "{$total_progress['course']['progress_percent']}%" ) ); ?></strong>
		</div>

		<div class="stm_lms_finish_score">

			<div class="stm_lms_finish_score__stats">

				<?php foreach ( $total_progress['curriculum'] as $item_type => $item_data ) : ?>

					<?php if ( 'lesson' === $item_type ) : ?>
						<div class="stm_lms_finish_score__stat">
							<div class="stm_lms_finish_score__stat_<?php echo esc_attr( $item_type ); ?>">
								<i class="far fa-file-alt"></i>
								<span><?php esc_html_e( 'Pages:', 'masterstudy-lms-learning-management-system' ); ?>
									<strong><?php echo esc_html( "{$item_data['completed']}/{$item_data['total']}" ); ?></strong></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( 'multimedia' === $item_type ) : ?>
						<div class="stm_lms_finish_score__stat">
							<div class="stm_lms_finish_score__stat_<?php echo esc_attr( $item_type ); ?>">
								<i class="far fa-play-circle"></i>
								<span><?php esc_html_e( 'Multimedia:', 'masterstudy-lms-learning-management-system' ); ?>
									<strong><?php echo esc_html( "{$item_data['completed']}/{$item_data['total']}" ); ?></strong></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( 'quiz' === $item_type ) : ?>
						<div class="stm_lms_finish_score__stat">
							<div class="stm_lms_finish_score__stat_<?php echo esc_attr( $item_type ); ?>">
								<i class="far fa-question-circle"></i>
								<span><?php esc_html_e( 'Quizzes:', 'masterstudy-lms-learning-management-system' ); ?>
									<strong><?php echo esc_html( "{$item_data['completed']}/{$item_data['total']}" ); ?></strong></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( 'assignment' === $item_type ) : ?>
						<div class="stm_lms_finish_score__stat">
							<div class="stm_lms_finish_score__stat_<?php echo esc_attr( $item_type ); ?>">
								<i class="fa fa-spell-check"></i>
								<span><?php esc_html_e( 'Assignments:', 'masterstudy-lms-learning-management-system' ); ?>
									<strong><?php echo esc_html( "{$item_data['completed']}/{$item_data['total']}" ); ?></strong></span>
							</div>
						</div>
					<?php endif; ?>

				<?php endforeach; ?>

			</div>

		</div>

	</div>
	<?php if ( is_ms_lms_addon_enabled( 'certificate_builder' ) && masterstudy_lms_course_has_certificate( $course_id ) ) : ?>
		<a
			href="#"
			class="stm_lms_course_completed_summary__certificate masterstudy_preview_certificate"
			data-id="<?php echo esc_attr( $course_id ); ?>"
		>
			<i class="fa fa-cloud-download-alt"></i>
			<?php esc_html_e( 'Download your Certificate', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
	<?php endif; ?>
	<?php
endif;
