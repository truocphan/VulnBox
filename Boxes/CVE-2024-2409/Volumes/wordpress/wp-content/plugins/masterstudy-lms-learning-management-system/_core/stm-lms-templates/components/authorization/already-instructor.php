<?php
/**
 * @var string $message
 */
?>

<div class="masterstudy-authorization__instructor-already">
	<div class="masterstudy-authorization__instructor-already-icon-wrapper">
		<span class="masterstudy-authorization__instructor-already-icon"></span>
	</div>
	<span class="masterstudy-authorization__instructor-already-title">
		<?php echo esc_html( $message ); ?>
	</span>
	<span class="masterstudy-authorization__instructor-already-actions">
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/button',
			array(
				'id'    => 'masterstudy-authorization-instructor-already-button',
				'title' => __( 'Go to Profile', 'masterstudy-lms-learning-management-system' ),
				'link'  => STM_LMS_USER::login_page_url(),
				'style' => 'primary',
				'size'  => 'sm',
			)
		);
		?>
	</span>
</div>
