<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var string $lesson_type
 * @var array $material_ids
 * @var boolean $lesson_completed
 * @var boolean $has_access
 * @var boolean $lesson_lock_before_start
 * @var boolean $lesson_locked_by_drip
 * @var array $current_user
 * @var boolean $dark_mode
 */

wp_enqueue_style( 'masterstudy-course-player-navigation' );
wp_enqueue_script( 'masterstudy-course-player-navigation' );

$current_lesson_id   = array_search( $item_id, $material_ids, true );
$prev_lesson         = $material_ids[ $current_lesson_id - 1 ] ?? null;
$prev_lesson_url     = '';
$prev_lesson_preview = false;
$next_lesson         = $material_ids[ $current_lesson_id + 1 ] ?? null;
$next_lesson_url     = '';
$next_lesson_preview = false;
$is_assignment       = method_exists( 'STM_LMS_Assignments', 'is_draft_assignment' ) && STM_LMS_Assignments::is_draft_assignment( $item_id );

if ( ! empty( $prev_lesson ) ) {
	$prev_lesson_url     = esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $prev_lesson ) );
	$prev_lesson_preview = STM_LMS_Lesson::lesson_has_preview( $prev_lesson );
}

if ( ! empty( $next_lesson ) ) {
	$next_lesson_url     = esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $next_lesson ) );
	$next_lesson_preview = STM_LMS_Lesson::lesson_has_preview( $next_lesson );
}
?>

<div class="masterstudy-course-player-navigation <?php echo esc_attr( $dark_mode ? 'masterstudy-course-player-navigation_dark-mode' : '' ); ?>">
	<div class="masterstudy-course-player-navigation__wrapper">
		<div class="masterstudy-course-player-navigation__prev">
			<?php
			if ( ! empty( $prev_lesson ) && $has_access ) {
				STM_LMS_Templates::show_lms_template(
					'components/nav-button',
					array(
						'title'     => __( 'Previous', 'masterstudy-lms-learning-management-system' ),
						'type'      => 'prev',
						'link'      => $prev_lesson_url,
						'style'     => 'secondary',
						'dark_mode' => $dark_mode,
						'data'      => array(),
					)
				);
			}
			?>
		</div>
		<?php if ( $lesson_completed ) { ?>
			<div class="masterstudy-course-player-navigation__status">
				<?php echo esc_html__( 'Completed', 'masterstudy-lms-learning-management-system' ); ?>
			</div>
			<?php if ( ( 'quiz' === $lesson_type || $is_assignment ) && empty( $next_lesson ) ) { ?>
				<div class="masterstudy-course-player-navigation__next"></div>
				<?php
			}
		} elseif ( $has_access && 'quiz' === $lesson_type && ! empty( $current_user['id'] ) ) {
			?>
			<div class="masterstudy-course-player-navigation__submit-quiz masterstudy-course-player-navigation__submit-quiz_hide <?php echo esc_attr( empty( $next_lesson ) ? 'masterstudy-course-player-navigation__submit-quiz_last' : '' ); ?>">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'title'         => __( 'Submit', 'masterstudy-lms-learning-management-system' ),
						'type'          => '',
						'link'          => '#',
						'style'         => 'primary',
						'size'          => 'sm',
						'id'            => 'submit-quiz',
						'icon_position' => '',
						'icon_name'     => '',
					)
				);
				?>
			</div>
			<?php
		} elseif ( $has_access && $is_assignment && ! empty( $current_user['id'] ) ) {
			?>
			<div class="masterstudy-course-player-navigation__send-assignment <?php echo esc_attr( empty( $next_lesson ) ? 'masterstudy-course-player-navigation__send-assignment_last' : '' ); ?>">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'id'            => 'masterstudy-course-player-assignments-save-draft-button',
						'title'         => __( 'Save as draft', 'masterstudy-lms-learning-management-system' ),
						'link'          => '#',
						'style'         => 'tertiary',
						'size'          => 'sm',
						'icon_position' => '',
						'icon_name'     => '',
					)
				);
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'id'            => 'masterstudy-course-player-assignments-send-button',
						'title'         => __( 'Submit', 'masterstudy-lms-learning-management-system' ),
						'link'          => '#',
						'style'         => 'primary',
						'size'          => 'sm',
						'icon_position' => '',
						'icon_name'     => '',
					)
				);
				?>
			</div>
			<?php
		}

		if ( $has_access ) {
			if ( ! $lesson_completed && ! empty( $next_lesson ) ) {
				if ( ! empty( $current_user['id'] ) ) {
					$buttont_title    = __( 'Complete & Next', 'masterstudy-lms-learning-management-system' );
					$button_style     = 'primary';
					$button_id        = 'masterstudy-course-player-lesson-submit';
					$next_lesson_data = array(
						'course' => $post_id,
						'lesson' => $item_id,
					);
				}

				if ( 'assignments' === $lesson_type || 'quiz' === $lesson_type || $lesson_lock_before_start || empty( $current_user['id'] ) ) {
					$buttont_title    = __( 'Next', 'masterstudy-lms-learning-management-system' );
					$button_style     = 'secondary';
					$button_id        = 'masterstudy-course-player-lesson-next';
					$next_lesson_data = array();
				}

				if ( ! $lesson_locked_by_drip ) {
					?>
					<div class="masterstudy-course-player-navigation__next">
						<?php
						STM_LMS_Templates::show_lms_template(
							'components/nav-button',
							array(
								'title'     => $buttont_title,
								'id'        => $button_id,
								'type'      => 'next',
								'link'      => $next_lesson_url,
								'style'     => "$button_style " . apply_filters( 'masterstudy_lms_course_player_complete_button_class', '' ),
								'dark_mode' => $dark_mode,
								'data'      => $next_lesson_data,
							)
						);
						?>
					</div>
					<?php
				}
			} elseif ( ! $lesson_completed && empty( $next_lesson ) && ! $lesson_lock_before_start
					&& ! $lesson_locked_by_drip && 'assignments' !== $lesson_type && 'quiz' !== $lesson_type && ! empty( $current_user['id'] ) ) {
				?>
				<div class="masterstudy-course-player-navigation__next">
					<?php
					STM_LMS_Templates::show_lms_template(
						'components/nav-button',
						array(
							'title'     => __( 'Complete', 'masterstudy-lms-learning-management-system' ),
							'id'        => 'masterstudy-course-player-lesson-submit',
							'type'      => 'next',
							'link'      => '',
							'style'     => 'primary',
							'dark_mode' => $dark_mode,
							'data'      => array(
								'course' => $post_id,
								'lesson' => $item_id,
							),
						)
					);
					?>
				</div>
				<?php
			}
		}

		if ( ! empty( $next_lesson ) && $lesson_completed && $has_access ) {
			?>
			<div class="masterstudy-course-player-navigation__next">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/nav-button',
					array(
						'title'     => __( 'Next', 'masterstudy-lms-learning-management-system' ),
						'type'      => 'next',
						'link'      => $next_lesson_url,
						'style'     => 'secondary',
						'dark_mode' => $dark_mode,
						'data'      => array(),
					)
				);
				?>
			</div>
			<?php
		}
		?>
	</div>
</div>
