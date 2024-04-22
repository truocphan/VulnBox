<?php
if ( ! function_exists( 'mslms_appsumo' ) && file_exists( STM_LMS_PRO_PATH . '/appsumo/main.php' ) ) {
	function mslms_appsumo() {
		require_once STM_LMS_PRO_PATH . '/appsumo/main.php';

		return appsumo_init(
			array(
				'item'      => 'masterstudy',
				'name'      => 'MasterStudy LMS PRO',
				'main_file' => STM_LMS_PRO_FILE,
			)
		);
	}
}
