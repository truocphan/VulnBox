<?php

if ( ! is_admin() ) return;

if ( ! defined( 'STM_ANP_PATH' ) ) {
	define( 'STM_ANP_PATH', dirname( __FILE__ ) );
}

if ( ! defined( 'STM_ANP_URL' ) ) {
	define( 'STM_ANP_URL', ( false !== strpos( STM_ANP_PATH, 'plugins' ) ) ? plugin_dir_url( __FILE__ ) : get_template_directory_uri() . '/admin/admin-notifications-popup/' );
}

spl_autoload_register(
	function ( $class_name ) {
		$class_path = str_replace( array( '\\', 'ANP' ), array( '/', 'classes' ), $class_name );

		if ( file_exists( STM_ANP_PATH . '/' . $class_path . '.php' ) ) {
			include STM_ANP_PATH . '/' . $class_path . '.php';
		}
	}
);

ANP\EnqueueSS::init();
ANP\AdminbarItem::init();
\ANP\Popup\DefaultHooks::init();
