<?php

namespace JupiterX_Core\Raven\Modules\Forms\Classes\Social_Login_Handler;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use Elementor\Settings;

/**
 * Facebook
 * Handle Social Login Process with Facebook.
 *
 * @since 2.0.0
*/
class Facebook {
	const APP_ID = 'elementor_raven_facebook_app_id';

	/**
	 * Required actions.
	 *
	 * @since 2.0.0
	*/
	public function __construct() {
		add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 20 );
		add_action( 'init', [ $this, 'facebook_log_user_in' ] );
	}

	/**
	 * Create Setting fields to save user facebook app id
	 *
	 * @param object $settings
	 * @since 2.0.0
	 */
	public function register_admin_fields( $settings ) {
		$settings->add_section( 'raven', 'raven_facebook_app_id', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'Facebook App ID', 'jupiterx-core' ) . '</h2>';
			},
			'fields' => [
				'raven_facebook_app_id' => [
					'label' => __( 'APP ID', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
						/* translators: %s: Facebook Developer URL  */
						'desc' => sprintf( __( 'This App ID will be used for facebook login. <a href="%s" target="_blank">Get your App ID</a>.', 'jupiterx-core' ), 'https://developers.facebook.com/' ),
					],
				],
			],
		] );
	}

	/**
	 * Social media render HTML.
	 *
	 * @param array $settings
	 * @param object $widget
	 * @return void
	 * @since 2.0.0
	 */
	public static function html() {
		$app_id = get_option( self::APP_ID );
		?>
			<script>
				var jxRavenFacebookAppId = '<?php echo $app_id; ?>';
			</script>
		<?php
	}

	/**
	 * Handle Login Process.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function ajax_handler( $ajax_handler ) {
		$email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
		$name  = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING );
		$fbid  = filter_input( INPUT_POST, 'fbid', FILTER_SANITIZE_STRING );

		if ( empty( $fbid ) || empty( $name ) ) {
			wp_send_json_error( __( 'Wrong Details.', 'jupiterx-core' ) );
		}

		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			wp_send_json_error( __( 'Not a Valid Email.', 'jupiterx-core' ) );
		}

		$user_id = email_exists( $email );

		// Email is not registered.
		if ( false === $user_id ) {
			$user_id = $this->create_user( $email );
		}

		$set_meta         = $this->set_user_facebook_id( $user_id, $fbid );
		$unique_login_url = $this->create_unique_link_to_login_facebook_user( $fbid );
		$login            = [
			'login_url' => $unique_login_url,
		];

		if ( ! empty( $ajax_handler->form['settings']['redirect_url']['url'] ) ) {
			$login['redirect_url'] = $ajax_handler->form['settings']['redirect_url']['url'];
		}

		wp_send_json_success( $login );
	}

	/**
	 * Create User By Given Email
	 *
	 * @param string $email
	 * @return int user_id
	 * @since 2.0.0
	 */
	private function create_user( $email ) {
		$user_data = [
			'user_login' => $email,
			'user_pass'  => wp_generate_password(),
			'user_email' => $email,
			'role'       => 'subscriber',
		];

		$user_id = wp_insert_user( $user_data );

		return $user_id;
	}

	/**
	 * Add User Facebook ID For The User As Meta.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	private function set_user_facebook_id( $user_id, $facebook_id ) {
		update_user_meta( $user_id, 'social-media-user-facebook-id', $facebook_id );
	}

	/**
	 * Create User unique Login URL based on user facebook id.
	 *
	 * @param string login url for facebook.
	 * @since 2.0.0
	*/
	private function create_unique_link_to_login_facebook_user( $facebook_id ) {
		$site      = site_url();
		$login_url = $site . '?jupiterx-facebook-social-login=' . $facebook_id;

		return $login_url;
	}

	/**
	 * Log user in and redirect based on unique URL.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function facebook_log_user_in() {
		if ( ! isset( $_GET['jupiterx-facebook-social-login'] ) ) { // phpcs:ignore
			return;
		}

		$value = filter_input( INPUT_GET, 'jupiterx-facebook-social-login', FILTER_SANITIZE_STRING );
		$user  = get_users(
			[
				'meta_key'    => 'social-media-user-facebook-id', // phpcs:ignore
				'meta_value'  => $value, // phpcs:ignore
				'number'      => 1,
				'count_total' => false,
			]
		);
		$id    = $user[0]->ID;

		wp_clear_auth_cookie();
		wp_set_current_user( $id ); // Set the current user detail
		wp_set_auth_cookie( $id ); // Set auth details in cookie

		if ( isset( $_GET['redirect'] ) ) { // phpcs:ignore
			$redirect = filter_input( INPUT_GET, 'redirect' );
			wp_redirect( $redirect ); // phpcs:ignore
			exit();
		}

		wp_redirect( site_url() ); // phpcs:ignore
		exit();
	}
}
