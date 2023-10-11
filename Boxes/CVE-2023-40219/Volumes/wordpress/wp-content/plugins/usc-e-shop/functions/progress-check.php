<?php
/**
 * Check progress.
 *
 * @since 2.7.7
 * @return void
 */
function usces_item_progress_check(){
	check_ajax_referer( 'wel_progress_check_ajax', 'nonce' );

	if ( 4 > usces_get_admin_user_level() ) {
		die( 'user_level' );
	}

	$progressfile = wp_unslash( filter_input( INPUT_POST, 'progressfile', FILTER_DEFAULT ) );
	$progressfile = WP_CONTENT_DIR . USCES_UPLOAD_TEMP . DIRECTORY_SEPARATOR . $progressfile;
	if ( usces_is_reserved_file( $progressfile ) ) {
		$text = file_get_contents( $progressfile );
		echo( $text );
	}
	exit;
}
