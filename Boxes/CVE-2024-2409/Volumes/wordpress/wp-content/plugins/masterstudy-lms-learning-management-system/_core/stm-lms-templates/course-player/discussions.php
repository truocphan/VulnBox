<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var string $lesson_type
 * @var array $quiz_data
 * @var boolean $dark_mode
 * @var array $current_user
 * @var boolean $discussions_sidebar
 */
wp_enqueue_style( 'masterstudy-course-player-discussions' );
wp_enqueue_script( 'masterstudy-course-player-discussions' );
?>

<div class="masterstudy-course-player-discussions">
	<?php if ( 'quiz' === $lesson_type && 'default' === $quiz_data['quiz_style'] && ! empty( $quiz_data['questions_for_nav'] ) && $quiz_data['questions_for_nav'] > 1 ) { ?>
		<div class="masterstudy-course-player-quiz__navigation-tabs">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/tabs-pagination',
				array(
					'max_visible_tabs' => 10,
					'tabs_quantity'    => $quiz_data['questions_for_nav'],
					'vertical'         => true,
					'dark_mode'        => $dark_mode,
				)
			);
			?>
		</div>
		<?php
	}
	if ( $discussions_sidebar && ! empty( $current_user['id'] ) ) {
		?>
		<div class="masterstudy-course-player-discussions__wrapper">
			<div class="masterstudy-course-player-discussions__mobile-header">
				<h3 class="masterstudy-course-player-discussions__mobile-title">
					<?php echo esc_html__( 'Discussions', 'masterstudy-lms-learning-management-system' ); ?>
				</h3>
				<span class="masterstudy-course-player-discussions__mobile-close"></span>
			</div>
			<div class="masterstudy-course-player-discussions__content">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/discussions',
					array(
						'course_id' => $post_id,
						'lesson_id' => $item_id,
						'dark_mode' => $dark_mode,
					)
				);
				?>
			</div>
		</div>
	<?php } ?>
</div>
