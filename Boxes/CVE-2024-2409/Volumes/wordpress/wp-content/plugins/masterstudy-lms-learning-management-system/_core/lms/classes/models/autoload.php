<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$files = [];

foreach (scandir(STM_LMS_PATH . '/lms/classes/models/') as $key => $value) {
	if ( strpos($value, '.php') AND $value != "autoload.php")
		$files[] = '/lms/classes/models/'.$value;
}

if(is_admin()) {
    if(!class_exists('WP_List_Table')){
        require_once( ABSPATH . 'wp-admin/includes/screen.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    }
	foreach (scandir(STM_LMS_PATH . '/lms/classes/models/admin') as $key => $value) {
		if ( strpos($value, '.php') AND $value != "autoload.php")
			$files[] = '/lms/classes/models/admin/'.$value;
	}
}

foreach ( $files as $file ) {
	if(file_exists(STM_LMS_PATH."/{$file}")) require_once STM_LMS_PATH."/{$file}";
}

