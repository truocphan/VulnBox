<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var string $course_title
 * @var array $user
 * @var array $curriculum
 * @var int $trial_lessons
 * @var boolean $trial_access
 * @var boolean $is_enrolled
 * @var boolean $dark_mode
 */
wp_enqueue_style( 'masterstudy-course-player-curriculum' );
wp_enqueue_script( 'masterstudy-course-player-curriculum' );
?>

<div class="masterstudy-course-player-curriculum">
	<div class="masterstudy-course-player-curriculum__wrapper">
		<div class="masterstudy-course-player-curriculum__mobile-header">
			<h3 class="masterstudy-course-player-curriculum__mobile-title">
				<?php echo esc_html__( 'Curriculum', 'masterstudy-learning-management-system' ); ?>
			</h3>
			<span class="masterstudy-course-player-curriculum__mobile-close"></span>
		</div>
		<div class="masterstudy-course-player-curriculum__content">
			<?php
			$progress    = 0;
			$my_progress = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_course( $user['id'], $post_id, array( 'progress_percent' ) ) );
			if ( ! empty( $my_progress['progress_percent'] ) ) {
				$progress = $my_progress['progress_percent'];
			}
			?>
			<div class="masterstudy-course-player-curriculum__title-wrapper">
				<h3 class="masterstudy-course-player-curriculum__title">
					<?php echo esc_html( $course_title ); ?>
				</h3>
				<?php
				if ( $progress > 0 ) {
					?>
					<div class="masterstudy-course-player-curriculum__progress">
						<?php
						STM_LMS_Templates::show_lms_template(
							'components/progress',
							array(
								'title'     => __( 'Course progress', 'masterstudy-lms-learning-management-system' ),
								'progress'  => $progress,
								'dark_mode' => $dark_mode,
							)
						);
						?>
					</div>
				<?php } ?>
			</div>
			<?php
			if ( ! empty( $curriculum ) ) {
				STM_LMS_Templates::show_lms_template(
					'components/curriculum-accordion',
					array(
						'course_id'         => $post_id,
						'current_lesson_id' => $item_id,
						'user'              => $user,
						'curriculum'        => $curriculum,
						'trial_lessons'     => intval( $trial_lessons ),
						'trial_access'      => $trial_access,
						'is_enrolled'       => $is_enrolled,
						'dark_mode'         => $dark_mode,
					)
				);
			}
			?>
		</div>
	</div>
</div>
