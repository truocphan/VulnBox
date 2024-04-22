<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'masterstudy-login-page' );
do_action( 'stm_lms_template_main' );

STM_LMS_Templates::show_lms_template( 'modals/preloader' );

$token = sanitize_text_field( wp_unslash( $_GET['restore_password'] ?? '' ) );

if ( ! empty( $token ) ) {
	$user_id = STM_LMS_User::check_restore_token( $token );
}
?>
<div class="masterstudy__login-page">
	<div class="masterstudy__login-page-form">
		<?php
		if ( ! empty( $user_id ) ) {
			STM_LMS_Templates::show_lms_template(
				'components/authorization/new-pass',
				array(
					'token' => $token,
				)
			);
		} else {
			STM_LMS_Templates::show_lms_template(
				'components/authorization/main',
				array(
					'modal'     => false,
					'type'      => isset( $_GET['mode'] ) && 'register' === $_GET['mode'] ? 'register' : 'login',
					'dark_mode' => false,
				)
			);
		}
		?>
	</div>
</div>
