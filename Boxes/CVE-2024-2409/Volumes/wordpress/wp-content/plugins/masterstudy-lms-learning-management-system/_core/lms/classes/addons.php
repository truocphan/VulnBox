<?php

use MasterStudy\Lms\Plugin\Addons;

new STM_LMS_Addons();

class STM_LMS_Addons {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 1001 );
	}

	public function admin_menu() {
		add_submenu_page(
			'stm-lms-settings',
			__( 'Pro Addons', 'masterstudy-lms-learning-management-system' ),
			'<span class="stm-lms-addons-menu"><span class="stm-lms-addons-pro">PRO</span> <span class="stm-lms-addons-text">'
			. __( 'Addons', 'masterstudy-lms-learning-management-system' ) . '</span></span>',
			'manage_options',
			'stm-addons',
			array( $this, 'addons_page' ),
			( stm_lms_addons_menu_position() + 1 )
		);
	}

	public function addons_page() {
		$addons         = Addons::list();
		$enabled_addons = get_option( 'stm_lms_addons', array() );

		wp_enqueue_style( 'stm-addons', STM_LMS_URL . 'assets/css/parts/admin/addons.css', array(), STM_LMS_VERSION );
		wp_enqueue_script( 'stm-lms-addons', STM_LMS_URL . 'assets/js/admin/addons.js', array( 'jquery' ), STM_LMS_VERSION, true );
		wp_localize_script(
			'stm-lms-addons',
			'stm_lms_addons',
			array(
				'enabled_addons' => wp_json_encode( $enabled_addons, JSON_FORCE_OBJECT ),
			)
		);

		stm_lms_render( STM_LMS_PATH . '/lms/views/addons/main', compact( 'addons', 'enabled_addons' ), true );
	}
}
