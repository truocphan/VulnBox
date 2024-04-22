<?php
$currentDate = strtotime( date( 'd-m-Y' ) );
$expiredDate = strtotime( '06-12-2022' );
if ( $currentDate < $expiredDate ) {
	add_action( 'admin_notices', 'stm_lms_survey_plugin_notice' );
	add_action( 'wp_ajax_stm_lms_survey_hide_notice_ajax', 'stm_lms_survey_notice_hide' );
}
register_activation_hook( MS_LMS_FILE, 'plugin_activation_hook_survey' );
function plugin_activation_hook_survey() {
	set_transient( 'stm_lms_survey_plugin_notification', true, 72 * HOUR_IN_SECONDS );
}

function stm_lms_survey_plugin_notice() {

	if ( empty( get_option( 'stm_lms_show_plugin_notification' ) ) && empty( get_transient( 'stm_lms_survey_plugin_notification' ) ) ) {
		echo '<div class="notice notice-info is-dismissible survey-plugin-notice">
				<p class="install-text"><b>' . esc_html( __( 'MasterStudy Experience Survey 2022', 'masterstudy-lms-learning-management-system' ) ) . '</b></p>
				<p class="install-text">' . esc_html( __( 'Do you have two minutes to spare? Your feedback will help us focus our efforts on the features that matter the most.', 'masterstudy-lms-learning-management-system' ) ) . '</p>
				<p> 
					<a href="https://docs.google.com/forms/d/1aIvUgnZVpSJg3fTVeQmWwFLHxq-2vW5MO-sVgSxsYUM/viewform?edit_requested=true" class="button" target="_blank">
					' . esc_html( __( 'Complete the Survey', 'masterstudy-lms-learning-management-system' ) ) . '
					</a>
				</p>
				<a class="stm_lms_survey_plugin_notice" data-nonce="' . esc_attr( wp_create_nonce( 'stm_lms_survey_plugin_notice_nonce' ) ) . '" admin-ajax-url="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '" href="#">
				' . esc_html( __( 'Dismiss', 'masterstudy-lms-learning-management-system' ) ) . '
				</a>
			</div>';
	}
}

function stm_lms_survey_notice_hide() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	add_option( 'stm_lms_show_plugin_notification', 'no' );
	wp_send_json_success();
}
