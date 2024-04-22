<?php

register_activation_hook( STM_LMS_PRO_FILE, 'set_stm_admin_notification_ms_lms' );

if ( ! function_exists( 'set_stm_admin_notification_ms_lms' ) ) {
	function set_stm_admin_notification_ms_lms() {
		//set rate us notice
		set_transient(
			'stm_masterstudy-lms-learning-management-system_notice_setting',
			array(
				'show_time'   => time(),
				'step'        => 0,
				'prev_action' => '',
			)
		);
	}
}

add_filter(
	'masterstudy_lms_timezones',
	function ( array $timezones ) {
		return $timezones + stm_lms_get_timezone_options();
	}
);
