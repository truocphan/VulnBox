<?php
/**
 * @var $comment
 * @var $editor_id
 */

$user = STM_LMS_User::get_current_user( $editor_id );
?>

<div class="clearfix assignment-comment">
	<i class="lnricons-chevron-down"></i>
	<div class="assignment-comment-image">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo stm_lms_filtered_output( $user['avatar'] );
		?>
	</div>
	<div class="assignment-comment-content">
		<span><?php esc_html_e( 'Teacher', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
		<div class="teacher_name heading_font"><?php echo esc_html( $user['login'] ); ?></div>
		<div class="teacher_review"><?php echo wp_kses_post( $comment ); ?></div>
	</div>
</div>
