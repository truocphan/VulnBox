<?php
/**
 * @var $current_user
 */

stm_lms_register_style( 'user-quizzes' );
stm_lms_register_style( 'user-certificates' );
$completed = stm_lms_get_user_completed_courses( $current_user['id'], array( 'user_course_id', 'course_id' ), -1 );
stm_lms_register_script( 'affiliate_points' );

stm_lms_register_style( 'affiliate_points' );

if ( ! empty( $completed ) ) { ?>
	<?php
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
	<div class="stm-lms-user-quizzes stm-lms-user-certificates">

		<h2 class="stm-lms-account-title">
			<?php esc_html_e( 'My Certificates', 'masterstudy-lms-learning-management-system' ); ?>
		</h2>

		<div class="multiseparator"></div>

		<div class="stm-lms-user-quiz__head heading_font">
			<div class="stm-lms-user-quiz__head_title">
				<?php esc_html_e( 'Course', 'masterstudy-lms-learning-management-system' ); ?>
			</div>
			<div class="stm-lms-user-quiz__head_status">
				<?php esc_html_e( 'Certificate', 'masterstudy-lms-learning-management-system' ); ?>
			</div>
		</div>

		<?php
		foreach ( $completed as $course ) :
			if ( masterstudy_lms_course_has_certificate( $course['course_id'] ) ) {
				$code = STM_LMS_Certificates::stm_lms_certificate_code( $course['user_course_id'], $course['course_id'] );
				?>
			<div class="stm-lms-user-quiz">
				<div class="stm-lms-user-quiz__title">
					<a href="<?php echo esc_url( get_the_permalink( $course['course_id'] ) ); ?>">
						<?php echo wp_kses_post( get_the_title( $course['course_id'] ) ); ?>
					</a>
				</div>
				<?php if ( is_ms_lms_addon_enabled( 'certificate_builder' ) ) : ?>
					<a href="#"
					data-id="<?php echo esc_attr( $course['course_id'] ); ?>"
					class="stm-lms-user-quiz__name masterstudy_preview_certificate">
						<?php esc_html_e( 'Download', 'masterstudy-lms-learning-management-system' ); ?>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( STM_LMS_Course::certificates_page_url( $course['course_id'] ) ); ?>"
					target="_blank"
					class="stm-lms-user-quiz__name">
						<?php esc_html_e( 'Download', 'masterstudy-lms-learning-management-system' ); ?>
					</a>
				<?php endif; ?>


				<div class="affiliate_points heading_font" data-copy="<?php echo esc_attr( $code ); ?>">
					<span class="hidden" id="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $code ); ?></span>
					<span class="affiliate_points__btn">
						<i class="fa fa-link"></i>
						<span class="text"><?php esc_html_e( 'Copy code', 'masterstudy-lms-learning-management-system' ); ?></span>
					</span>
				</div>

			</div>
				<?php
			}
			endforeach;
		?>
	</div>

<?php } else { ?>

	<h2 class="stm-lms-account-title">
		<?php esc_html_e( 'My Certificates', 'masterstudy-lms-learning-management-system' ); ?>
	</h2>

	<div class="multiseparator"></div>

	<h4 class="no-certificates-notice"><?php esc_html_e( 'You do not have a certificate yet.', 'masterstudy-lms-learning-management-system' ); ?></h4>
	<h4 class="no-certificates-notice"><?php esc_html_e( 'Get started easy, select a course here, pass it and get your first certificate', 'masterstudy-lms-learning-management-system' ); ?></h4>

<?php } ?>
