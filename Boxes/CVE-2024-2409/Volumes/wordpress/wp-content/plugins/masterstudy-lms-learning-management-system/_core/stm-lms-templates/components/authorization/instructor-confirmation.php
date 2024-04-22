<?php
/**
 * @var boolean $show
 */

$show = $show ?? false;
?>

<div class="masterstudy-authorization__instructor-confirm <?php echo esc_attr( $show ? 'masterstudy-authorization__instructor-confirm_show' : '' ); ?>">
	<div class="masterstudy-authorization__instructor-confirm-icon-wrapper">
		<span class="masterstudy-authorization__instructor-confirm-icon"></span>
	</div>
	<span class="masterstudy-authorization__instructor-confirm-title">
		<?php echo esc_html__( 'Your application is sent', 'masterstudy-lms-learning-management-system' ); ?>
	</span>
	<span class="masterstudy-authorization__instructor-confirm-instructions">
		<?php echo esc_html__( "We'll send you an email as soon as your application is approved.", 'masterstudy-lms-learning-management-system' ); ?>
	</span>
	<span class="masterstudy-authorization__instructor-confirm-actions">
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/button',
			array(
				'id'    => 'masterstudy-authorization-instructor-confirm-button',
				'title' => __( 'Go to Profile', 'masterstudy-lms-learning-management-system' ),
				'link'  => STM_LMS_USER::login_page_url(),
				'style' => 'primary',
				'size'  => 'sm',
			)
		);
		?>
	</span>
</div>
