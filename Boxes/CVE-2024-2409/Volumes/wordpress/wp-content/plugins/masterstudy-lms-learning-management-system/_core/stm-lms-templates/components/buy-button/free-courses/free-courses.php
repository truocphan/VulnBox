<?php
/**
 * @var int $post_id
 * @var array $button_classes
 */

$user = STM_LMS_User::get_current_user(); ?>
	<div class="<?php echo esc_attr( implode( ' ', $button_classes ) ); ?>">
	<?php
	if ( empty( $user['id'] ) ) {
		?>
		<a href="#" data-authorization-modal="login">
			<span class="masterstudy-buy-button__title"><?php echo esc_html__( 'Enroll course', 'masterstudy-lms-learning-management-system' ); ?></span>
		</a>
		<?php
	} else {
		$course         = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_course( $user['id'], $post_id, array( 'current_lesson_id', 'progress_percent' ) ) );
		$current_lesson = $course['current_lesson_id'] ?? '0';
		$progress       = intval( $course['progress_percent'] ?? 0 );
		$lesson_url     = STM_LMS_Course::item_url( $post_id, $current_lesson );
		$btn_label      = esc_html__( 'Start course', 'masterstudy-lms-learning-management-system' );

		if ( $progress > 0 ) {
			$btn_label = esc_html__( 'Continue', 'masterstudy-lms-learning-management-system' );
		}
		?>
		<a href="<?php echo esc_url( $lesson_url ); ?>">
			<span class="masterstudy-buy-button__title"><?php echo esc_html( sanitize_text_field( $btn_label ) ); ?></span>
		</a>
		<?php
	}
	?>
</div>
