<?php
/**
 * @var boolean $modal
 */
?>

<div id="masterstudy-authorization-restore-pass" class="masterstudy-authorization__send-mail">
	<div class="masterstudy-authorization__send-mail-icon-wrapper">
		<span class="masterstudy-authorization__send-mail-icon"></span>
	</div>
	<span class="masterstudy-authorization__send-mail-content">
		<span class="masterstudy-authorization__send-mail-content-title">
			<?php echo esc_html__( 'Password reset link sent', 'masterstudy-lms-learning-management-system' ); ?>
		</span>
		<span class="masterstudy-authorization__send-mail-content-subtitle">
			<?php echo esc_html__( 'to your email', 'masterstudy-lms-learning-management-system' ); ?>
		</span>
	</span>
	<?php
	if ( $modal ) {
		STM_LMS_Templates::show_lms_template(
			'components/button',
			array(
				'id'    => 'masterstudy-authorization-close-button',
				'title' => __( 'Close', 'masterstudy-lms-learning-management-system' ),
				'link'  => '#',
				'style' => 'primary',
				'size'  => 'sm',
			)
		);
	}
	?>
</div>
