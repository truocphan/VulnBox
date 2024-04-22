<?php
/**
 * @var $current_user
 */
$user_id = ! empty( $current_user['id'] ) ? $current_user['id'] : '';
$status  = get_user_meta( $user_id, 'submission_status', true );
$class   = ( 'pending' === $status ) ? 'disabled' : '';
?>
<div class="stm_lms_become_instructor">

	<div class="stm_lms_become_instructor__top">
		<img class="stm_lms_become_instructor__image" src="<?php echo esc_url( STM_LMS_URL . '/assets/img/account/become_instructor.svg' ); ?>"/>
		<h3><?php esc_html_e( 'Become an Instructor?', 'masterstudy-lms-learning-management-system' ); ?></h3>
	</div>

	<p><?php esc_html_e( 'Take the chance to run your own courses and show your expertise.', 'masterstudy-lms-learning-management-system' ); ?></p>

	<a href="#" class="btn-default btn lms_become_instructor_btn <?php echo esc_attr( $class ); ?>" data-masterstudy-modal="masterstudy-become-instructor-modal">
		<?php
		if ( 'pending' === $status ) {
			esc_html_e( 'Pending...', 'masterstudy-lms-learning-management-system' );
		} else {
			esc_html_e( 'Submit Request', 'masterstudy-lms-learning-management-system' );
		}
		?>
	</a>
	<?php
	STM_LMS_Templates::show_lms_template(
		'components/modals/become-instructor',
		array(
			'dark_mode' => false,
		)
	);
	?>
</div>
