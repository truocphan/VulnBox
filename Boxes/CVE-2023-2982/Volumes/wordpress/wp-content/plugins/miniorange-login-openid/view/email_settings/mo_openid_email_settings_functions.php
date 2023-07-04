<?php

function mo_openid_send_email( $user_id = '', $user_url = '' ) {
	if ( get_option( 'mo_openid_email_enable' ) == 1 ) {
		global $wpdb;
		$admin_mail = get_option( 'mo_openid_admin_email' );
		$user_name  = ( $user_id == '' ) ? '##UserName##' : ( $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM {$wpdb->users} WHERE ID = %d", $user_id ) ) );
		$content    = get_option( 'mo_openid_register_email_message' );
		$subject    = '[' . get_bloginfo( 'name' ) . '] New User Registration - Social Login';
		$content    = str_replace( '##User Name##', $user_name, $content );
		$headers    = 'Content-Type: text/html';
		$a          = wp_mail( $admin_mail, $subject, $content, $headers );
	}
}
