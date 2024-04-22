<?php
add_action( 'wp_ajax_stm_lms_send_test_email_ajax', 'stm_lms_send_test_email_ajax' );
function stm_lms_send_test_email_ajax() {
	if ( ! empty( $_POST['emailId'] ) ) {
		$email_manager = STM_LMS_Email_Manager::stm_lms_get_settings();
		if ( ! empty( $email_manager ) && is_array( $email_manager ) ) {
			$subject      = $email_manager[ $_POST['emailId'] . '_subject' ];
			$message      = $email_manager[ $_POST['emailId'] ];
			$current_user = wp_get_current_user();
			$result       = str_replace( '_', ' ', $message );
			$result       = str_replace( '{{', 'Sample ', $result );
			$result       = str_replace( '}}', ' ', $result );

			$data = apply_filters(
				'stm_lms_filter_email_data',
				array(
					'subject' => $subject,
					'message' => $result,
				)
			);

			add_filter( 'wp_mail_content_type', array( STM_LMS_Helpers::class, 'set_html_content_type' ) );

			$response = wp_mail( $current_user->user_email, $data['subject'], $data['message'] );

			remove_filter( 'wp_mail_content_type', array( STM_LMS_Helpers::class, 'set_html_content_type' ) );

			if ( $response ) {
				wp_send_json_success();
			}
		}
	}
	wp_send_json_error();
}

add_filter(
	'wpcfto_field_send_email',
	function () {
		return STM_LMS_PATH . '/settings/custom_fields/send_email/fields/send_email.php';
	}
);
