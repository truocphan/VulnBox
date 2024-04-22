<?php
/**
 * @var string $token
 */

wp_enqueue_style( 'masterstudy-authorization' );
wp_enqueue_script( 'masterstudy-authorization-new-pass' );
?>

<script>
let new_pass_data = {
	'nonce': '<?php echo esc_html( wp_create_nonce( 'stm_lms_restore_password' ) ); ?>',
	'ajax_url': '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
	'token': '<?php echo esc_html( $token ); ?>',
};
</script>

<div class="masterstudy-authorization masterstudy-authorization_new-pass">
	<div class="masterstudy-authorization__wrapper">
		<div class="masterstudy-authorization__header">
			<span class="masterstudy-authorization__header-title">
				<?php echo esc_html__( 'Restore password', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</div>
		<div id="masterstudy-authorization-form-new-pass" class="masterstudy-authorization__form">
			<div class="masterstudy-authorization__form-wrapper">
				<div class="masterstudy-authorization__form-field">
					<input type="password" name="user_new_password" class="masterstudy-authorization__form-input masterstudy-authorization__form-input_pass" placeholder="<?php echo esc_html__( 'Enter new password', 'masterstudy-lms-learning-management-system' ); ?>">
					<span class="masterstudy-authorization__form-show-pass"></span>
				</div>
				<div class="masterstudy-authorization__form-field">
					<input type="password" name="user_repeat_new_password" class="masterstudy-authorization__form-input masterstudy-authorization__form-input_pass" placeholder="<?php echo esc_html__( 'Repeat password', 'masterstudy-lms-learning-management-system' ); ?>">
					<span class="masterstudy-authorization__form-show-pass"></span>
				</div>
			</div>
		</div>
		<div class="masterstudy-authorization__actions">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'id'    => 'masterstudy-authorization-new-pass-button',
					'title' => __( 'Save & Sign In', 'masterstudy-lms-learning-management-system' ),
					'link'  => '#',
					'style' => 'primary',
					'size'  => 'sm',
				)
			);
			?>
		</div>
	</div>
</div>
