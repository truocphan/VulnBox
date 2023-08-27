<?php

namespace JupiterX_Core\Raven\Modules\My_Account;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'my-account' ];
	}

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function __construct() {
		parent::__construct();

		// Define an endpoint for all custom My Account tabs.
		define( 'JX_MY_ACCOUNT_CUSTOM_ENDPOINT', 'tab' );

		// On Editor: Register WooCommerce frontend hooks before the Editor init.
		add_action( 'init', [ $this, 'include_wc_frontend' ], 0 );

		// Register the defined endpoint("JX_MY_ACCOUNT_CUSTOM_ENDPOINT") to WC My Account endpoints.
		add_action( 'init', [ $this, 'add_custom_templates_endpoint' ], 5 );

		// Ajax action that sends WC My Account tabs to frontend (used for syncing tabs with 3rd party plugins activation/deactivation).
		add_action( 'wp_ajax_raven_my_account_nav_items', [ $this, 'register_ajax_action_nav_items' ] );

	}

	public function include_wc_frontend() {
		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			wc()->frontend_includes();
		}
	}

	public function add_custom_templates_endpoint() {
		add_filter( 'woocommerce_get_query_vars', function( $queries ) {
			$queries[ JX_MY_ACCOUNT_CUSTOM_ENDPOINT ] = JX_MY_ACCOUNT_CUSTOM_ENDPOINT;
			return $queries;
		} );

		// Force update rewrite rules only once.
		$current_rewrite_rule = get_option( 'jx_my_account_custom_tab' );

		if ( ! $current_rewrite_rule || JX_MY_ACCOUNT_CUSTOM_ENDPOINT !== $current_rewrite_rule ) {
			flush_rewrite_rules( true );
			update_option( 'jx_my_account_custom_tab', JX_MY_ACCOUNT_CUSTOM_ENDPOINT );
		}
	}

	public function register_ajax_action_nav_items() {
		$result = wc_get_account_menu_items();

		wp_send_json_success( $result );
	}
}
