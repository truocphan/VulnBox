<?php

$init_data = array(
	'lms-pages',
	'database',
	'old-course-builder',
);

$themes       = array( 'ms-lms-starter-theme', 'masterstudy', 'globalstudy', 'smarty' );
$theme_exists = array_reduce(
	$themes,
	function ( $carry, $theme ) {
		return $carry || wp_get_theme( $theme )->exists();
	},
	false
);

$start_theme_notice = get_transient( 'stm_starter_theme_notice_setting' );

if ( ! $theme_exists && empty( $start_theme_notice ) ) {

	$install_url = add_query_arg(
		array( 'page' => 'starter_lms_demo_installer' ),
		admin_url( 'themes.php' ),
	);

	$init_data['starter-theme'] = array(
		'notice_type'            => 'starter-theme-notice',
		'notice_logo'            => 'ms_starter.svg',
		'notice_title'           => esc_html__( 'What\'s next? Install the', 'masterstudy-lms-learning-management-system' ) . ' <span style="font-weight: 700">free</span> <a href="https://stylemixthemes.com/wordpress-lms-plugin/starter/" style="text-decoration: none" target="_blank">MasterStudy Starter Theme!</a>',
		'notice_desc'            => '',
		'notice_btn_one_title'   => esc_html__( 'Install', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one_class'   => 'ms_start_theme_install',
		'notice_btn_one'         => esc_url( $install_url ),
		'notice_btn_two_title'   => esc_html__( 'Live Demo', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_two_class'   => 'ms_start_theme_live_demo light-bg',
		'notice_btn_two'         => esc_url( 'https://masterstudy.stylemixthemes.com/lms-plugin/' ),
		'notice_btn_two_attrs'   => 'target=_blank',
		'notice_btn_three_title' => esc_html__( 'No Thanks', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_three_class' => 'no-bg',
		'notice_btn_three'       => '#',
		'notice_btn_three_attrs' => 'data-type=discard data-key=starter_theme',
	);
}

if ( ! stm_lms_has_generated_pages( stm_lms_generate_pages_list() ) ) {
	$init_data['lms-pages'] = array(
		'notice_type'          => 'cog-notice',
		'notice_logo'          => 'cog.svg',
		'notice_title'         => esc_html__( 'There are not any LMS pages!', 'masterstudy-lms-learning-management-system' ),
		'notice_desc'          => esc_html__( 'Please create pages and indicate them on LMS Settings>>>LMS Pages or generate them with the page generator', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one_title' => esc_html__( 'Open settings', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one_class' => 'ms_settings_open',
		'notice_btn_one'       => admin_url( 'admin.php?page=stm-lms-settings#section_routes' ),
	);
}
$buy_pro_notice = get_transient( 'stm_buy_pro_notice_setting' );
if ( empty( $buy_pro_notice ) && ! defined( 'STM_LMS_PRO_PATH' ) ) {
	$init_data['lms-unlock-pro-features'] = array(
		'notice_type'          => 'cb-info go-to-pro-notice',
		'notice_logo'          => 'ms.svg',
		'notice_title'         => esc_html__( 'Unlock all features of MasterStudy LMS!', 'masterstudy-lms-learning-management-system' ),
		'notice_desc'          => esc_html__( 'Upgrade now and access a world of pro features, advanced addons, and limitless possibilities for your eLearning journey. Boost your teaching today!', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one_title' => esc_html__( 'Get Pro', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one_class' => 'ms_settings_open',
		'notice_btn_one'       => admin_url( 'admin.php?page=stm-lms-go-pro&source=get-pro-button-notice' ),
		'notice_btn_two_title' => esc_html__( 'No Thanks', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_two_class' => 'buy-pro-btn no-bg',
		'notice_btn_two'       => '#',
		'notice_btn_two_attrs' => 'data-type=discard data-key=buy_pro target=_blank',
	);
}

$current_database = get_option( 'stm_lms_db_version', 1 );
$has_new_database = version_compare( STM_LMS_DB_VERSION, $current_database );
if ( $has_new_database ) {
	$init_data['database'] = array(
		'notice_type'          => 'animate-triangle-notice',
		'notice_logo'          => 'attent_triangle.svg',
		'notice_title'         => esc_html__( 'MasterStudy LMS database update required', 'masterstudy-lms-learning-management-system' ),
		'notice_desc'          => esc_html__( 'We added new features, and need to update your database to latest version.', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one'       => '#',
		'notice_btn_one_title' => esc_html__( 'Update', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one_class' => 'ms-lms-table-update',
	);
}

$notice_status = get_option( 'course_builder_notice_status' );
$current_date  = gmdate( 'Y-m-d H:i' );
$deadline      = '2023-09-01 00:00';
$difference    = strtotime( $deadline ) - strtotime( $current_date );
$days          = floor( $difference / ( 60 * 60 * 24 ) );
$hours         = floor( ( $difference % ( 60 * 60 * 24 ) ) / ( 60 * 60 ) );
$minutes       = floor( ( $difference % ( 60 * 60 ) ) / 60 );
$seconds       = $difference % 60;

/**
 * Hook:Action to add admin notice popup scripts.
 */
function stm_lms_add_notice_popup_script() {
	wp_enqueue_script( 'ms_lms_notice-popup', STM_LMS_URL . 'assets/js/notices/notices.js', array(), STM_LMS_VERSION, true );
	wp_localize_script(
		'ms_lms_notice-popup',
		'ms_lms_notice_data',
		array(
			'nonce'    => wp_create_nonce( 'skip_cb_popup' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'stm_lms_add_notice_popup_script' );

global $pagenow;

if ( empty( $notice_status ) && $difference > 0 && 'widgets.php' !== $pagenow ) {
	$init_data['old-course-builder'] = array(
		'notice_type'          => 'cb-info',
		'notice_logo'          => 'ms.svg',
		'notice_title'         => esc_html__( 'Legacy course creation process will be entirely replaced by the new Course Builder starting from November 1st, 2023.', 'masterstudy-lms-learning-management-system' ),
		'notice_desc'          => esc_html__( 'The legacy course creation process will be switched to the new Course Builder interface starting from November 1st, 2023. All your existing content (courses, lessons, quizzes, assignments, etc.) will be safely transferred to the new course builder interface.', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_two'       => 'https://stylemixthemes.com/wp/masterstudy-lms-ending-support-for-legacy-course-creation/',
		'notice_btn_two_title' => esc_html__( 'Learn more', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one'       => 'https://forms.gle/fKs9vssCKSzJ9DQV6',
		'notice_btn_one_title' => esc_html__( 'Report a problem', 'masterstudy-lms-learning-management-system' ),
		'notice_btn_one_class' => 'course-builder-notice',
	);

	/**
	 * Hook:Action to close admin notice.
	 */
	function stm_lms_ajax_close_cb_notice() {
		check_ajax_referer( 'skip_cb_popup', 'security' );
		update_option( 'course_builder_notice_status', sanitize_text_field( $_GET['add_pear_hb_status'] ) ); // phpcs:ignore
	}
	add_action( 'wp_ajax_stm_close_cb_notice', 'stm_lms_ajax_close_cb_notice' );
}

foreach ( $init_data as $item ) {
	stm_admin_notices_init( $item );
}

if ( ! class_exists( 'RateNotification' ) ) {
	require_once STM_LMS_LIBRARY . '/admin-notification-popup/classes/RateNotification.php';
}

$rate_data = array(
	'plugin_title' => 'Masterstudy LMS Plugin',
	'plugin_name'  => 'masterstudy-lms-learning-management-system',
	'plugin_file'  => MS_LMS_FILE,
	'logo'         => STM_LMS_URL . 'assets/img/ms-logo.png',
);

RateNotification::init( $rate_data );
