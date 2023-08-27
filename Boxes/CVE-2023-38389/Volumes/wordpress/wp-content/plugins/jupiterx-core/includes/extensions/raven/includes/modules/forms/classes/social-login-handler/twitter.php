<?php

namespace JupiterX_Core\Raven\Modules\Forms\Classes\Social_Login_Handler;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use Elementor\Settings;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitter
 * Handle Social Login Process with Twitter.
 *
 * @since 2.0.0
*/
class Twitter {
	const APP_KEY       = 'elementor_raven_twitter_api_key';
	const APP_SECRET    = 'elementor_raven_twitter_api_secret';
	const ACCESS_TOKEN  = 'elementor_raven_twitter_access_token';
	const ACCESS_SECRET = 'elementor_raven_twitter_access_token_secret';

	public function __construct() {
		add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 20 );
		add_action( 'init', [ $this, 'check_user_details_on_twitter_callback' ] );
	}

	/**
	 * Create Connection to Twitter API
	 *
	 * @return void
	*/
	private function connection() {
		jupiterx_core()->load_files( [ 'extensions/raven/includes/modules/forms/classes/social-login-handler/vendors/autoload' ] );

		$key           = get_option( self::APP_KEY );
		$api_key       = get_option( self::APP_SECRET );
		$access_token  = get_option( self::ACCESS_TOKEN );
		$access_secret = get_option( self::ACCESS_SECRET );
		$connection    = new TwitterOAuth( $key, $api_key, $access_token, $access_secret );

		return $connection;
	}

	public function register_admin_fields( $settings ) {
		$settings->add_section( 'raven', 'raven_twitter_api_key', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'Twitter API Details', 'jupiterx-core' ) . '</h2>';
			},
			'fields' => [
				'raven_twitter_api_key' => [
					'label' => __( 'API KEY', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
					],
				],
				'raven_twitter_api_secret' => [
					'label' => __( 'API Secret Key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
					],
				],
				'raven_twitter_access_token' => [
					'label' => __( 'Access Token', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
					],
				],
				'raven_twitter_access_token_secret' => [
					'label' => __( 'Access Token Secret', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
					],
				],
			],
		] );
	}

	/**
	 * Get Twitter Login url
	 * Also set a cookie to save Redirect URL
	 *
	 * @return void
	 */
	public function ajax_handler( $ajax_handler ) {
		$redirect = site_url();

		if ( ! empty( $ajax_handler->form['settings']['redirect_url']['url'] ) ) {
			$redirect = $ajax_handler->form['settings']['redirect_url']['url'];
		}

		setcookie( 'jupiterx-social-media-redirect-url', $redirect, time() + 86400, '/' );

		$connection = $this->connection();
		$content    = $connection->get( 'account/verify_credentials' );

		if ( ! ( $content->id ) ) {
			wp_send_json_error( __( 'Not valid api credentials.', 'jupiterx-core' ) );
		}

		$response = $connection->oauth( 'oauth/request_token', [ 'oauth_callback' => site_url() ] );

		if ( ! is_array( $response ) ) {
			wp_send_json_error( __( 'Twitter Api Error.', 'jupiterx-core' ) );
		}

		if ( ! array_key_exists( 'oauth_token', $response ) ) {
			wp_send_json_error( __( 'Twitter Api Error.', 'jupiterx-core' ) );
		}

		$sign_in_url = $connection->url( 'oauth/authorize', [ 'oauth_token' => $response['oauth_token'] ] );
		wp_send_json_success( $sign_in_url, 200 );
	}

	/**
	 * Twitter callback screen
	 */
	public function check_user_details_on_twitter_callback() {
		if ( ! isset( $_GET['oauth_verifier'] ) ) { // phpcs:ignore
			return;
		}

		$oauth_verifier = filter_input( INPUT_GET, 'oauth_verifier', FILTER_SANITIZE_STRING );
		$oauth_token    = filter_input( INPUT_GET, 'oauth_token', FILTER_SANITIZE_STRING );
		$connection     = $this->connection();
		$access_token   = $connection->oauth(
			'oauth/access_token',
			[
				'oauth_verifier' => $oauth_verifier,
				'oauth_token'    => $oauth_token,
			]
		);

		$this->verify_twitter_callback( $connection, $access_token );
	}

	/**
	 * Verify twitter callback
	 *
	 * @param [object] $connection
	 * @param [string] $access_token
	 */
	private function verify_twitter_callback( $connection, $access_token ) {
		if ( 200 === $connection->getLastHttpCode() && is_array( $access_token ) && array_key_exists( 'screen_name', $access_token ) ) {
			$name              = $access_token['screen_name'];
			$user_twitter_info = $connection->get(
				'account/verify_credentials',
				[
					'name'          => $name,
					'include_email' => true,
				]
			);

			if ( 200 !== $connection->getLastHttpCode() ) {
				echo '<script>alert( ' . __( 'Twitter Api Error', 'jupiterx-core' ) . ' );</script>';
				return;
			}

			$email                    = $user_twitter_info->email;
			$user_twitter_id          = $user_twitter_info->id;
			$user_twitter_name        = $user_twitter_info->name;
			$user_twitter_screen_name = $user_twitter_info->screen_name;

			$user_id = email_exists( $email );

			// Email is not registered.
			if ( false === $user_id ) {
				$user_id = $this->create_user( $email );
			}

			$meta = $this->set_user_twitter_meta( $user_id, $user_twitter_name, $user_twitter_id, $user_twitter_screen_name );

			$this->sign_in_user( $user_id );
		} else {
			echo '<script>alert( ' . __( 'Twitter Api Error', 'jupiterx-core' ) . ' );</script>';
		}
	}

	/**
	 * Create User By Given Email
	 *
	 * @param [String] $email
	 * @return user_id
	 */
	private function create_user( $email ) {
		$user_data = [
			'user_login' => $email,
			'user_pass'  => wp_generate_password(),
			'user_email' => $email,
			'role'       => 'subscriber',
		];
		$user_id   = wp_insert_user( $user_data );

		return $user_id;
	}

	/**
	 * Add User Twitter ID For The User As Meta.
	 */
	private function set_user_twitter_meta( $user_id, $name, $twitter_id, $screen_name ) {
		update_user_meta( $user_id, 'social-media-user-twitter-id', $twitter_id );
		update_user_meta( $user_id, 'social-media-user-twitter-name', $name );
		update_user_meta( $user_id, 'social-media-user-twitter-screen-name', $screen_name );
	}

	/**
	 * Sign-in user by using user id
	 *
	 * @param [int] $id
	 */
	private function sign_in_user( $user_id ) {
		wp_clear_auth_cookie();
		wp_set_current_user( $user_id ); // Set the current user detail
		wp_set_auth_cookie( $user_id ); // Set auth details in cookie

		if ( isset( $_COOKIE['jupiterx-social-media-redirect-url'] ) ) {      // get saved cookie
			$redirect = filter_input( INPUT_COOKIE, 'jupiterx-social-media-redirect-url', FILTER_SANITIZE_URL );
			wp_redirect( $redirect ); // phpcs:ignore
			exit();
		}

		wp_redirect( site_url() ); // phpcs:ignore
		exit();
	}
}
