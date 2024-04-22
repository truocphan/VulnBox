<?php
$client = new Google_Client();
$client->setAuthConfigFile( 'client_secrets.json' );
$client->setRedirectUri( 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php' );
$client->addScope( Google_Service_Drive::DRIVE_METADATA_READONLY );

if ( ! isset( $_GET['code'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$auth_url = $client->createAuthUrl();
	echo esc_url( $auth_url );
	header( 'Location: ' . filter_var( $auth_url, FILTER_SANITIZE_URL ) );
} else {
	$client->authenticate( $_GET['code'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$_SESSION['access_token'] = $client->getAccessToken();
	$redirect_uri             = 'http://' . $_SERVER['HTTP_HOST'] . '/';
	header( 'Location: ' . filter_var( $redirect_uri, FILTER_SANITIZE_URL ) );
}
