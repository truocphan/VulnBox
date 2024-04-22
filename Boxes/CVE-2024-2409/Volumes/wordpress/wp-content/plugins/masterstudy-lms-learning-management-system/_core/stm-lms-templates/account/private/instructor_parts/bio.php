<?php
/**
 * @var $current_user
 */

$bio = get_user_meta( $current_user['id'], 'description', true );
?>

	<div class="stm_lms_user_bio">
		<?php if ( ! empty( $bio ) ) : ?>
			<h3><?php esc_html_e( 'Instructor Bio', 'masterstudy-lms-learning-management-system' ); ?></h3>
			<div class="stm_lms_update_field__description"><?php echo nl2br( wp_kses_post( $bio ) ); ?></div>
		<?php endif; ?>
		<?php
		if ( defined( 'STM_LMS_PRO_FILE' ) ) {
			STM_LMS_Templates::show_lms_template(
				'account/public/additional_information',
				array(
					'current_user_id' => get_current_user_id(),
					'instructor_id'   => $current_user['id'],
				)
			);
		}
		?>
	</div>
