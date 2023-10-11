<?php
/**
* Plugins Update checker
*/
add_filter( 'http_request_args', 'welcart_http_request_args', 10, 2 );
function welcart_http_request_args( $a, $b ) {
	if ( false === strpos( $b, ( USCES_UPDATE_INFO_URL . '/update_info/plugins/info.php' ) ) && false === strpos( $b, ( USCES_UPDATE_INFO_URL . '/update_info/themes/info.php' ) ) ) {
		return $a;
	}
	$a['body'] = array(
		'wpver'  => get_bloginfo( 'version' ),
		'wcver'  => USCES_VERSION,
		'prhost' => ( isset( $_SERVER['SERVER_NAME'] ) ? wp_unslash( $_SERVER['SERVER_NAME'] ) : '' ),
	);

	return $a;
}

add_action( 'init', 'welcart_update_check' );
function welcart_update_check() {

	if ( ! is_admin() ) {
		return;
	}

	$current = get_site_transient( 'update_wcex_plugins' );
	if ( ! is_object( $current ) ) {
		$current = new stdClass;
	}
	require USCES_PLUGIN_DIR . '/update_check/plugin-update-checker.php';

	$timeout = 2 * HOUR_IN_SECONDS;

	$time_not_changed = isset( $current->last_checked ) && $timeout > ( time() - $current->last_checked );
	if ( $time_not_changed ) {

		$wcproducts = $current->products;

	} else {

		$options  = array(
			'body' => array(
				'wpver'     => get_bloginfo( 'version' ),
				'wcver'     => USCES_VERSION,
				'prhost'    => ( isset( $_SERVER['SERVER_NAME'] ) ? wp_unslash( $_SERVER['SERVER_NAME'] ) : '' ),
				'checktime' => time(),
			),
		);
		$response = wp_remote_post( USCES_UPDATE_INFO_URL . '/update_info/info_api.php', $options );
		if ( is_wp_error( $response ) ) {
			return;
		}
		$wcproducts = (array) json_decode( $response['body'], true );

		if ( empty( $wcproducts ) ) {
			return;
		}
		$current->last_checked = time();
		$current->products     = $wcproducts;
		set_site_transient( 'update_wcex_plugins', $current );
	}

	$wcproducts = (array) $wcproducts;

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugins   = get_plugins();
	$installed = array();
	foreach ( $plugins as $path => $pv ) {

		if ( array_key_exists( $pv['Name'], $wcproducts ) ) {
			$slug               = $wcproducts[ $pv['Name'] ];
			$fullpath           = USCES_WP_PLUGIN_DIR . '/' . $path;
			$installed[ $slug ] = $fullpath;
		}
	}

	$themes = wp_get_themes();
	foreach ( $themes as $path => $theme ) {
		$Name = $theme->get( 'Name' );
		if ( array_key_exists( $Name, $wcproducts ) ) {
			$slug               = $wcproducts[ $Name ];
			$fullpath           = get_theme_root() . '/' . $path . '/functions.php';
			$installed[ $slug ] = $fullpath;
		}
	}
	foreach ( $installed as $slug => $fullpath ) {

		$json_path = USCES_UPDATE_INFO_URL . '/update_info/plugins/' . $slug . '.json';
		$$slug     = Puc_v4_Factory::buildUpdateChecker( $json_path, $fullpath, $slug );
	
		add_filter(	'puc_manual_check_link-' . $slug, 'usces_manual_check_label' );
	}
}

function usces_manual_check_label( $label ) {
	return __( 'Check for updates', 'usces' );
}

add_action( 'init', 'usces_check_for_updates' );
function usces_check_for_updates() {

	if ( isset( $_GET['puc_update_check_result'], $_GET['puc_slug'] ) && ! empty( $_GET['puc_slug'] ) ) {
		$filter_handle = 'puc_manual_check_message-' . wp_unslash( $_GET['puc_slug'] );

		add_filter( $filter_handle, 'usces_manual_check_message', 10, 2 );
	}
}

function usces_manual_check_message( $message, $status ) {

	if ( ! is_admin() ) {
		return;
	}

	$query = array(
		'wpver'        => get_bloginfo( 'version' ),
		'wcver'        => USCES_VERSION,
		'prhost'       => ( isset( $_SERVER['SERVER_NAME'] ) ? wp_unslash( $_SERVER['SERVER_NAME'] ) : '' ),
		'manual_check' => 1,
		'checktime'    => time(),
	);

	$json_path   = USCES_UPDATE_INFO_URL . '/update_info/plugins/' . $_GET['puc_slug'] . '.json';
	$update_json = @file_get_contents( $json_path );
	$update_info = (array) json_decode( $update_json, true );
	$check_url   = add_query_arg( $query, $update_info['download_url'] );
	$check_info  = @file_get_contents( $check_url );

	if ( empty( $check_info ) ) {

		$message = __( 'An unexpected error has occurred.', 'usces' ) . '（ERROR0）';

	} else {

		switch ( $check_info ) {
			case '422 Unprocessable Entity':
				$message = __( 'Update Right is invalid. The corresponding plug-in does not exist.', 'usces' ) . '（ERROR422）';
				break;
			case '401 Unauthorized':
				$message = __( 'Update Right is invalid. The domain of your site does not match.', 'usces' ) . '（DomainMismatch）';
				break;
			case '402 Payment Required':
				$message = __( 'Update Right is invalid. There is no valid license purchase information.', 'usces' ) . '（OrderCanceled）';
				break;
			case '400 Bad Request':
				$message = __( 'Update Right is invalid. The domain of your site does not match.', 'usces' ) . '（ClientDomainMissing）';
				break;
			case '403 Forbidden':
				$message = __( 'An unexpected error has occurred.', 'usces' ) . '（ERROR403）';
				break;
			default:
				$message = __( 'Update Right is valid. You can update it.', 'usces' );
		}
	}

	return $message;
}

