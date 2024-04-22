<?php
/**
 * @var array $user
 * @var object $comment
 * @var array $attachments
 * @var boolean $dark_mode
 */
?>
<div class="masterstudy-course-player-assignments__instructor-comment">
	<div class="masterstudy-course-player-assignments__instructor-comment-title">
		<?php echo esc_html__( 'Instructor comment:', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</div>
	<div class="masterstudy-course-player-assignments__instructor-comment-wrapper">
		<div class="masterstudy-course-player-assignments__instructor-comment-image">
			<img src="<?php echo esc_url( $user['avatar_url'] ); ?>" class="masterstudy-course-player-assignments__instructor-comment-avatar">
		</div>
		<div class="masterstudy-course-player-assignments__instructor-comment-content">
			<span class="masterstudy-course-player-assignments__instructor-comment-name">
				<?php echo esc_html( $user['login'] ); ?>
			</span>
			<div class="masterstudy-course-player-assignments__instructor-comment-text">
				<?php echo wp_kses_post( $comment ); ?>
			</div>
			<?php if ( ! empty( $attachments ) ) { ?>
				<div class="masterstudy-course-player-assignments__instructor-comment-files">
					<?php
					STM_LMS_Templates::show_lms_template(
						'components/file-attachment',
						array(
							'attachments' => $attachments,
							'download'    => true,
							'deletable'   => false,
							'dark_mode'   => $dark_mode,
						)
					);
					?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
