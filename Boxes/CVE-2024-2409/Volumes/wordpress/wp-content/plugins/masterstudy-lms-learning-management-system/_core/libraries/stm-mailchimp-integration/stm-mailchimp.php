<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() ) {
	return;
}
if ( ! function_exists( 'stm_mailchimp_get_page_now' ) ) {

	function stm_mailchimp_get_page_now() {

		if ( ! isset( $_pagenow ) ) {
			global $pagenow;

			if ( empty( $pagenow ) && is_admin() && is_multisite() ) {
				if ( is_network_admin() ) {
					preg_match( '#/wp-admin/network/?(.*?)$#i', $_SERVER['PHP_SELF'], $self_matches );
				} elseif ( is_user_admin() ) {
					preg_match( '#/wp-admin/user/?(.*?)$#i', $_SERVER['PHP_SELF'], $self_matches );
				} else {
					preg_match( '#/wp-admin/?(.*?)$#i', $_SERVER['PHP_SELF'], $self_matches );
				}

				$pagenow = $self_matches[1];
				$pagenow = trim( $pagenow, '/' );
				$pagenow = preg_replace( '#\?.*?$#', '', $pagenow );
				if ( '' === $pagenow || 'index' === $pagenow || 'index.php' === $pagenow ) {
					$pagenow = 'index.php';
				} else {
					preg_match( '#(.*?)(/|$)#', $pagenow, $self_matches );
					$pagenow = strtolower( $self_matches[1] );
					if ( '.php' !== substr( $pagenow, - 4, 4 ) ) {
						$pagenow .= '.php';
					} // for Options +Multiviews: /wp-admin/themes/index.php (themes.php is queried)
				}

				return $pagenow;
			} else {
				return $pagenow;
			}

			if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) &&
			     'admin-ajax.php' === $pagenow
			) {
				$referer = fs_get_raw_referer();

				if ( is_string( $referer ) ) {
					$parts = explode( '?', $referer );

					return basename( $parts[0] );
				}
			}
		}
	}
}

if ( ! function_exists( 'stm_mailchimp_is_show_page' ) ) {
	function stm_mailchimp_is_show_page( $pluginActions = [], $pluginPages = [], $postTypes = [] ) {
		$page     = isset( $_GET['page'] ) ? $_GET['page'] : '';
		$postType = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
		$action   = isset( $_GET['action'] ) ? explode( '/', $_GET['action'] )[0] : '';

		if ( in_array( $action, $pluginActions ) || in_array( $page, $pluginPages ) || in_array( $postType, $postTypes ) ) {
			return true;
		}

		if ( 'plugins.php' === stm_mailchimp_get_page_now() ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'stm_mailchimp_admin_init' ) ) {

	define( 'STM_ADMIN_MAILCHIMP_INTEGRATION_PATH', dirname( __FILE__ ) );
	define( 'STM_ADMIN_MAILCHIMP_INTEGRATION_URL', plugin_dir_url( __FILE__ ) );

	function stm_mailchimp_admin_init( $plugin_data ) {
		if ( ! class_exists( 'STMMailChimpBase' ) ) {
			require_once __DIR__ . '/classes/STMMailChimpBase.php';
		}
		STMMailChimpBase::init( $plugin_data );
	}
}
