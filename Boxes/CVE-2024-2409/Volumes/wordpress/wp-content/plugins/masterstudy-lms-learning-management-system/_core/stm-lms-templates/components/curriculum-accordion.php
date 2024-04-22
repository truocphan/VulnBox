<?php
/**
 * @var int $course_id
 * @var int $current_lesson_id
 * @var array $user
 * @var array $curriculum
 * @var int $trial_lessons
 * @var boolean $trial_access
 * @var boolean $is_enrolled
 * @var boolean $dark_mode
 *
 * masterstudy-curriculum-accordion_dark-mode - for dark mode
 * masterstudy-curriculum-accordion__wrapper_opened - for open curriculum list
 * masterstudy-curriculum-accordion__link_current - for current lesson
 * masterstudy-curriculum-accordion__check_completed - for completed lesson
 */

use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Repositories\CoursePlayerRepository;

wp_enqueue_style( 'masterstudy-curriculum-accordion' );
wp_enqueue_script( 'masterstudy-curriculum-accordion' );
?>

<div class="masterstudy-curriculum-accordion <?php echo esc_attr( $dark_mode ? 'masterstudy-curriculum-accordion_dark-mode' : '' ); ?>">
	<?php
	$material_index = 0;
	foreach ( $curriculum as $section ) {
		$opened               = in_array( $current_lesson_id, array_column( $section['materials'], 'post_id' ), true ) ? 'masterstudy-curriculum-accordion__wrapper_opened' : '';
		$section['materials'] = ( new CoursePlayerRepository() )->hydrate_materials( $section['materials'] );
		$completed_count      = 0;

		if ( is_user_logged_in() ) {
			foreach ( $section['materials'] as $index => &$section_material ) {
				if ( ! isset( $section_material['lesson_completed'] ) ) {
					if ( PostType::QUIZ === $section_material['post_type'] ) {
						$section_material['lesson_completed'] = STM_LMS_Quiz::quiz_passed( $section_material['post_id'], $user['id'] ) ? 'completed' : '';
					} else {
						$section_material['lesson_completed'] = STM_LMS_Lesson::is_lesson_completed( $user['id'], $course_id, $section_material['post_id'] ) ? 'completed' : '';
					}
				}

				if ( 'completed' === $section_material['lesson_completed'] ) {
					$completed_count++;
				}
			}
		}
		?>
		<div class="masterstudy-curriculum-accordion__wrapper <?php echo esc_attr( $opened ); ?>">
			<div class="masterstudy-curriculum-accordion__section">
				<h4 class="masterstudy-curriculum-accordion__section-title"><?php echo esc_html( $section['title'] ); ?></h4>
				<span class="masterstudy-curriculum-accordion__section-count"><?php echo esc_html( $completed_count . '/' . count( $section['materials'] ) ); ?></span>
				<span class="masterstudy-curriculum-accordion__toggler">
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/files/new/chevron_up.svg' ); ?>" class="masterstudy-curriculum-accordion__toggler-icon">
				</span>
			</div>
			<ul class="masterstudy-curriculum-accordion__list" style="<?php echo esc_attr( $opened ? 'display:flex' : 'display:none' ); ?>">
				<?php
				foreach ( $section['materials'] as $material ) {
					$material_index++;
					$material = apply_filters( 'masterstudy_lms_lesson_curriculum_data', $material, $curriculum, $course_id );
					?>
					<li class="masterstudy-curriculum-accordion__item">
						<a href="<?php echo esc_url( STM_LMS_Lesson::get_lesson_url( $course_id, $material['post_id'] ) ); ?>"
							class="masterstudy-curriculum-accordion__link <?php echo esc_attr( $material['post_id'] === $current_lesson_id ? 'masterstudy-curriculum-accordion__link_current' : '' ); ?><?php echo esc_attr( $material['lesson_locked_by_drip'] ? 'masterstudy-curriculum-accordion__link_disabled' : '' ); ?>">
							<div class="masterstudy-curriculum-accordion__title-wrapper">
								<div class="masterstudy-curriculum-accordion__title">
									<?php echo esc_html( $material['title'] ); ?>
								</div>
								<?php
								if ( $material['lesson_lock_before_start'] || $material['lesson_locked_by_drip'] ) {
									?>
									<span class="masterstudy-curriculum-accordion__locked">
									<?php
									STM_LMS_Templates::show_lms_template(
										'components/hint',
										array(
											'content'   => $material['lesson_lock_message'],
											'side'      => 'right',
											'dark_mode' => $dark_mode,
										)
									);
									?>
									</span>
								<?php } else { ?>
									<span class="masterstudy-curriculum-accordion__check <?php echo esc_attr( ! empty( $material['lesson_completed'] ) ? 'masterstudy-curriculum-accordion__check_completed' : '' ); ?>"></span>
								<?php } ?>
							</div>
							<div class="masterstudy-curriculum-accordion__meta-wrapper">
								<img src="<?php echo esc_url( STM_LMS_URL . "/assets/icons/lessons/{$material['icon']}.svg" ); ?>" class="masterstudy-curriculum-accordion__image">
								<div class="masterstudy-curriculum-accordion__meta">
									<?php
									if ( 'stm-quizzes' === $material['post_type'] ) {
										/* translators: %s: number */
										echo esc_html( ! empty( $material['questions_array'] ) ? sprintf( __( '%d questions', 'masterstudy-lms-learning-management-system' ), count( $material['questions_array'] ) ) : '' );
										echo esc_html( empty( $material['questions_array'] ) ? $material['label'] : '' );
									} else {
										echo esc_html( $material['duration'] ?? '' );
										echo esc_html( $material['meta'] ?? '' );
										echo esc_html( empty( $material['meta'] ) && empty( $material['duration'] ) ? $material['label'] : '' );
									}
									?>
								</div>
								<?php if ( ! $is_enrolled && $trial_access && $material_index <= $trial_lessons ) { ?>
									<div class="masterstudy-curriculum-accordion__trial">
										<span class="masterstudy-curriculum-accordion__trial-hint">
											<?php echo esc_html__( 'Trial', 'masterstudy-lms-learning-management-system' ); ?>
										</span>
									</div>
								<?php } elseif ( ! $is_enrolled && STM_LMS_Lesson::lesson_has_preview( $material['post_id'] ) ) { ?>
									<div class="masterstudy-curriculum-accordion__preview">
										<span class="masterstudy-curriculum-accordion__preview-hint">
											<?php echo esc_html__( 'Preview', 'masterstudy-lms-learning-management-system' ); ?>
										</span>
									</div>
								<?php } ?>
							</div>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
</div>
