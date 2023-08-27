<?php
/**
 * This class handles AJAX.
 *
 * @since 1.3.0
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
 * @since 1.3.0
 *
 * @package JupiterX\Framework\Admin\Setup_Wizard
 */
final class JupiterX_API_Ajax {

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
	 * @since 1.3.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_api', [ $this, 'ajax' ] );
	}

	/**
	 * Main AJAX function.
	 *
	 * @since 1.3.0
	 *
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	public function ajax() {
		check_ajax_referer( 'jupiterx-nonce', 'nonce' );

		if ( ! current_user_can( 'edit_others_posts' ) || ! current_user_can( 'edit_others_pages' ) ) {
			wp_send_json_error( 'You do not have access to this section', 'jupiterx' );
		}

		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
			$method = filter_input( INPUT_GET, 'method' );
		} else {
			$method = filter_input( INPUT_POST, 'method' );
		}

		if ( ! isset( $method ) || ( ! method_exists( $this, $method ) && ! has_action( "jupiterx_api_ajax_{$method}" ) ) ) {
			wp_send_json_success( [
				'status' => self::ERROR,
			] );
		}

		$action = "jupiterx_api_ajax_{$method}";

		if ( has_action( $action ) ) {
			do_action( $action );
		} else {
			// Run AJAX type.
			call_user_func( [ $this, $method ] );
		}
	}
	/**
	 * Activate the API key.
	 *
	 * @since 1.3.0
	 */
	public function activate() {
		$api_key = filter_input( INPUT_POST, 'api_key' );

		if ( empty( $api_key ) ) {
			wp_send_json_success( [
				'message' => esc_html__( 'API key is empty.', 'jupiterx' ),
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
				'message' => esc_html__( 'Your API key could not be verified.', 'jupiterx' ),
				'status'  => self::ERROR,
			] );
		}

		jupiterx_update_option( 'api_key', $api_key, 'yes' );

		wp_send_json_success( [
			'message' => esc_html__( 'Your product registration was successful.', 'jupiterx' ),
			'status'  => self::OK,
		] );
	}

	/**
	 * Install plugin.
	 *
	 * @since 1.3.0
	 */
	public function install_plugin() {
		$api_key = filter_input( INPUT_POST, 'api_key' );

		if ( empty( $api_key ) ) {
			wp_send_json_success( [
				'message' => esc_html__( 'API key is empty.', 'jupiterx' ),
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
				'message' => esc_html__( 'Your API key could not be verified.', 'jupiterx' ),
				'status'  => self::ERROR,
			] );
		}

		jupiterx_update_option( 'api_key', $api_key, 'yes' );

		wp_send_json_success( [
			'message' => esc_html__( 'Your product registration was successful.', 'jupiterx' ),
			'status'  => self::OK,
		] );
	}

	/**
	 * Deactivate plugins.
	 *
	 * @since 1.6.0
	 */
	public function deactivate_plugins() {
		$plugins = filter_input( INPUT_POST, 'plugins', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( empty( $plugins ) ) {
			wp_send_json_success( [
				'message' => esc_html__( 'Plugins list is empty.', 'jupiterx' ),
				'status'  => self::ERROR,
			] );
		}

		deactivate_plugins( $plugins );

		wp_send_json_success( [
			'message' => esc_html__( 'Deactivating plugins successful.', 'jupiterx' ),
			'status'  => self::OK,
		] );
	}
}

new JupiterX_API_Ajax();
