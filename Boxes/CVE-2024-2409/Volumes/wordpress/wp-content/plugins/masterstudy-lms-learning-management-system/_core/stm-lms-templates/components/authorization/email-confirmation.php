<?php
/**
 * @var boolean $modal
 */
?>

<div id="masterstudy-authorization-confirm-email" class="masterstudy-authorization__send-mail">
	<div class="masterstudy-authorization__send-mail-icon-wrapper">
		<span class="masterstudy-authorization__send-mail-icon"></span>
	</div>
	<span class="masterstudy-authorization__send-mail-title">
		<?php echo esc_html__( 'Confirmation link sent', 'masterstudy-lms-learning-management-system' ); ?>
	</span>
	<span class="masterstudy-authorization__send-mail-instructions">
		<?php echo esc_html__( 'Please follow the instructions sent to your email address', 'masterstudy-lms-learning-management-system' ); ?>
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
