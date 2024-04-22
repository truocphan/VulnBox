<?php

add_action(
	'admin_enqueue_scripts',
	function () {
		wp_enqueue_style( 'stm-item-announcements-styles', 'https://stylemixthemes.com/item-announcements/css/app.css', array(), STM_LMS_PRO_VERSION );
		wp_enqueue_script( 'stm-item-announcements-app', 'https://stylemixthemes.com/item-announcements/js/app.js', array(), STM_LMS_PRO_VERSION, true );
		wp_localize_script(
			'stm-item-announcements-app',
			'stmItemAnnouncements',
			array(
				'installedPlugins' => array_values(
					array_filter(
						scandir( WP_PLUGIN_DIR ),
						function ( $name ) {
							return 0 !== strpos( $name, '.' ) && 'index.php' !== $name;
						}
					)
				),
				'installedThemes'  => array_values(
					array_filter(
						scandir( WP_CONTENT_DIR . '/themes' ),
						function ( $name ) {
							return 0 !== strpos( $name, '.' ) && 'index.php' !== $name;
						}
					)
				),
			)
		);
	}
);

add_action(
	'all_admin_notices',
	function () {
		echo '<div data-mount="stm-item-announcements-notice" data-slug="masterstudy-lms-learning-management-system-pro"></div>';
	}
);
