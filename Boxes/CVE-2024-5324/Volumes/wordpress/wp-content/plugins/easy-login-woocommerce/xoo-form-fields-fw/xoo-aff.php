<?php

if( !defined( 'XOO_AFF_DIR' ) ){
	define( 'XOO_AFF_DIR', dirname(__FILE__) );
}

if( !defined( 'XOO_AFF_URL' ) ){
	define( 'XOO_AFF_URL', plugins_url( '', __FILE__  ) );
}

if( !defined( 'XOO_AFF_VERSION' ) ){
	define( 'XOO_AFF_VERSION', '1.4' );
}

require_once XOO_AFF_DIR.'/includes/class-xoo-aff.php';

//Begin
if( !function_exists('xoo_aff_fire') ){
	function xoo_aff_fire( $plugin_slug, $admin_page_slug ){
		
		if( !$plugin_slug ) return;
		return new Xoo_Aff( $plugin_slug, $admin_page_slug );
		do_action( 'xoo_aff_'.$plugin_slug.'_loaded' );
		
	}
}
?>