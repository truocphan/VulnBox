<?php
function init_mixpanel() {
	require_once STM_LMS_DIR . '/lms/helpers.php';
	require_once 'class-mixpanel.php';
	require_once 'class-mixpanel-general.php';
	require_once 'class-mixpanel-posts.php';
	require_once 'class-mixpanel-addons.php';

	$data_classes = array(
		'MasterStudy\Lms\Libraries\Mixpanel_General',
		'MasterStudy\Lms\Libraries\Mixpanel_Posts',
		'MasterStudy\Lms\Libraries\Mixpanel_Addons',
	);

	$mixpanel = new MasterStudy\Lms\Libraries\Mixpanel( $data_classes );
	$mixpanel->execute();
}

add_filter( 'cron_schedules', 'mixpanel_cron_schedule' );

function mixpanel_cron_schedule( $schedules ) {
	if ( ! isset( $schedules['weekly'] ) ) {
		$schedules['weekly'] = array(
			'interval' => WEEK_IN_SECONDS,
			'display'  => __( 'Once every 1 week' ),
		);
	}

	return $schedules;
}

if ( ! wp_next_scheduled( 'ms_init_mixpanel_cron' ) ) {
	wp_schedule_event( time(), 'weekly', 'ms_init_mixpanel_cron' );
}

add_action( 'ms_init_mixpanel_cron', 'init_mixpanel' );
