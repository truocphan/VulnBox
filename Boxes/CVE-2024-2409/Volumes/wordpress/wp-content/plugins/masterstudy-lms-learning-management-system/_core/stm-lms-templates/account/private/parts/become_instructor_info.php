<?php
/**
 * @var $current_user
 */

stm_lms_register_script( 'account/v1/account', true );

$current_user['roles'] = $current_user['roles'] ?? array();

if ( ! in_array( 'stm_lms_instructor', $current_user['roles'] ) ) {
	$user_id = $current_user['id'];
	$history = get_user_meta( $user_id, 'submission_history', true );
	if ( ! empty( $history ) && is_array( $history ) && ! empty( $history[0] && empty( $history[0]['viewed'] ) ) ) {
		$status  = ! empty( $history[0]['status'] ) ? $history[0]['status'] : '';
		$message = ! empty( $history[0]['message'] ) ? $history[0]['message'] : '';
		?>
		<div class="become_instructor_info <?php echo esc_attr( $status ); ?>">
			<i class="lnr lnr-cross-circle info-icon"></i>
			<i class="lnr lnr-cross info-close" data-user-id="<?php echo esc_attr( $user_id ); ?>"></i>
			<h3><?php esc_html_e( 'Your request to become an Instructor has been declined', 'masterstudy-lms-learning-management-system' ); ?></h3>
			<p><?php echo esc_html( $message ); ?></p>
		</div>
		<?php
	}
}
