<?php
/**
 * This class handles AJAX.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX class.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 */
final class JupiterX_Setup_Wizard_Ajax {

	/**
	 * Successful return status.
	 */
	const OK = true;

	/**
	 * Error return status.
	 */
	const ERROR = false;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Get TGMPA.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
		}

		$functions = [
			'next_page',
			'activate_api',
			'install_plugins',
			'activate_plugins',
			'get_templates',
			'import_template',
			'get_template_psd',
			'hide_notice',
		];

		foreach ( $functions as $function ) {
			add_action( 'wp_ajax_jupiterx_setup_wizard_' . $function, [ $this, $function ] );
		}
	}

	/**
	 * Get the next page.
	 *
	 * @since 1.0.0
	 */
	public function next_page() {
		$page_id = jupiterx_setup_wizard()->get_next_page();

		jupiterx_update_option( 'setup_wizard_current_page', $page_id );

		// Remove notice when user reached final page.
		if ( 'completed' === $page_id ) {
			jupiterx_update_option( 'setup_wizard_hide_notice', true );
		}

		ob_start();

		jupiterx_setup_wizard()->render_content( $page_id );

		$html = ob_get_clean();

		wp_send_json_success( [
			'html' => $html,
			'page' => $page_id,
		] );
	}

	/**
	 * Activate the API key.
	 *
	 * @since 1.0.0
	 */
	public function activate_api() {
		$api_key = filter_input( INPUT_POST, 'api_key' );

		if ( empty( $api_key ) ) {
			wp_send_json_success( [
				'message' => __( 'API key is empty.', 'jupiterx' ),
				'status'  => self::ERROR,
			] );
		}

		$data = array(
			'timeout'     => 10,
			'httpversion' => '1.1',
			'body'        => array(
				'apikey' => $api_key,
				'domain' => wp_unslash( $_SERVER['SERVER_NAME'] ), // phpcs:ignore
			),
		);

		$post = wp_remote_post( 'https://artbees.net/api/v1/verify', $data );

		$response = json_decode( wp_remote_retrieve_body( $post ) );

		if ( ! $response->is_verified ) {
			wp_send_json_success( [
				'message' => __( 'Your API key could not be verified.', 'jupiterx' ),
				'status'  => self::ERROR,
			] );
		}

		jupiterx_update_option( 'api_key', $api_key, 'yes' );

		wp_send_json_success( [
			'message' => __( 'Your product registration was successful.', 'jupiterx' ),
			'status'  => self::OK,
		] );
	}

	/**
	 * Prepare a list of installation URLs to let TGMPA install plugins.
	 *
	 * @since 1.0.0
	 */
	public function install_plugins() {

		if ( ! current_user_can( 'install_plugins' ) ) {
			$missed_plugins_html = '<strong>' . implode( '</strong><strong>', $missed_plugins ) . '</strong>';

			$message = sprintf(
				// translators: 1: Strong tag. 2. Strong tag close. 3. Div tag open. 4. Missed plugins Markup. 5. Div tag close.
				esc_html__( 'You are not allowed to install a new plugin.
				Please install required plugins that listed below via your %1$s Network Admin > Plugins %2$s or contact your Network Admin.
				Required plugins for this template: %3$s %4$s %5$s', 'jupiterx' ),
				'<strong>',
				'</strong>',
				'<div class="jupiterx-missed-plugins-list">',
				$missed_plugins_html,
				'</div>'
			);
		}

		$actions            = [];
		$plugins_to_install = [];
		$tgmpa_url          = $this->tgmpa->get_tgmpa_url();
		$plugins_list       = filter_input( INPUT_POST, 'plugins', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		foreach ( $plugins_list as $slug ) {
			if ( ! $this->tgmpa->is_plugin_installed( $slug ) ) {
				$plugins_to_install[] = $slug;
			}
		}

		if ( ! empty( $plugins_to_install ) ) {
			$actions['install'] = [
				'url'           => $tgmpa_url,
				'plugin'        => $plugins_to_install,
				'tgmpa-page'    => $this->tgmpa->menu,
				'plugin_status' => 'all',
				'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
				'action'        => 'tgmpa-bulk-install',
				'action2'       => - 1,
				'message'       => esc_html__( 'Installing', 'jupiterx' ),
			];
		}

		$actions['url']    = $tgmpa_url;
		$actions['status'] = true;

		wp_send_json( $actions );
	}

	/**
	 * Prepare a list of activation URLs to let TGMPA activate plugins.
	 *
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function activate_plugins() {

		$plugins_list = filter_input( INPUT_POST, 'plugins', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$actions      = [];
		$tgmpa_url    = $this->tgmpa->get_tgmpa_url();

		$actions['activate'] = [
			'url'           => $tgmpa_url,
			'plugin'        => $plugins_list,
			'tgmpa-page'    => $this->tgmpa->menu,
			'plugin_status' => 'all',
			'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
			'action'        => 'tgmpa-bulk-activate',
			'action2'       => - 1,
			'message'       => esc_html__( 'Activating', 'jupiterx' ),
		];

		$actions['url']    = $tgmpa_url;
		$actions['status'] = true;

		wp_send_json( $actions );
	}

	/**
	 * Get templates list.
	 *
	 * @since 1.0.0
	 */
	public function get_templates() {
		$template = [
			'id'       => filter_input( INPUT_POST, 'template_id' ),
			'name'     => filter_input( INPUT_POST, 'template_name' ),
			'category' => filter_input( INPUT_POST, 'template_category' ),
		];

		$headers = [
			'pagination-start'  => intval( filter_input( INPUT_POST, 'pagination_start' ) ),
			'pagination-count'  => intval( filter_input( INPUT_POST, 'pagination_count' ) ),
			'template-id'       => $template['id'],
			'template-name'     => empty( $template['name'] ) ? null : $template['name'],
			'template-category' => empty( $template['category'] ) ? null : $template['category'],
		];

		$data = [
			'timeout'     => 10,
			'httpversion' => '1.1',
			'headers'     => array_merge( [
				'theme-name' => JUPITERX_SLUG,
				'domain'     => wp_unslash( $_SERVER['SERVER_NAME'] ), // phpcs:ignore
			], $headers ),
		];

		$post = wp_remote_get( 'https://artbees.net/api/v2/theme/templates', $data );

		$response = json_decode( wp_remote_retrieve_body( $post ) );

		wp_send_json_success( [
			'templates' => $response->data,
			'status'    => self::OK,
		] );
	}

	/**
	 * Process import template.
	 *
	 * @since 1.0.0
	 */
	public function import_template() {
		$template_id = jupiterx_get_option( 'template_installed_id' );

		if ( ! empty( $template_id ) ) {
			wp_send_json_success( [
				'message' => __( 'Cannot process your request because you have already installed a template.', 'jupiterx' ),
				'status'  => self::ERROR,
			] );
		}

		$templates_manager = new JupiterX_Control_Panel_Install_Template();

		// Install template function.
		$templates_manager->install_template_procedure();
	}

	/**
	 * Get template psd link.
	 *
	 * @since 1.0.0
	 */
	public function get_template_psd() {
		$api_key = jupiterx_get_option( 'api_key' );

		if ( empty( $api_key ) ) {
			wp_send_json_success( [
				'message' => __( 'Your API key could not be verified.', 'jupiterx' ),
				'status'  => self::ERROR,
			] );
		}

		$templates_manager = new JupiterX_Control_Panel_Install_Template();

		// Template download function.
		$templates_manager->get_template_psd_link();
	}

	/**
	 * Hide message notice.
	 *
	 * @since 1.0.0
	 */
	public function hide_notice() {
		jupiterx_update_option( 'setup_wizard_hide_notice', true );

		wp_send_json_success( [
			'status' => self::OK,
		] );
	}
}

new JupiterX_Setup_Wizard_Ajax();
