<?php

namespace JupiterX_Core\Raven\Modules\Forms\Classes\Social_Login_Handler;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;

/**
 * Google
 * Handle Social Login Process with Google.
 *
 * @since 2.0.0
*/
class Google {
	public function __construct() {
		add_action( 'init', [ $this, 'google_log_user_in' ] );
	}

	/**
	 * Ajax handler.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function ajax_handler( $ajax_handler ) {
		$token    = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_STRING );
		$url      = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $token;
		$response = wp_remote_get( $url );

		if ( ! is_array( $response ) || is_wp_error( $response ) ) {
			wp_send_json_error( __( 'Google API Error', 'jupiterx-core' ) );
		}

		$body        = $response['body'];
		$information = json_decode( $body, true );

		if ( 'true' !== $information['email_verified'] ) {
			wp_send_json_error( __( 'We could not get user email from google api', 'jupiterx-core' ) );
		}

		$email            = $information['email'];
		$user_google_id   = $information['sub'];
		$return_client_id = $information['aud'];
		$user_client_id   = get_option( 'elementor_raven_google_client_id' );

		if ( $user_client_id !== $return_client_id ) {
			wp_send_json_error( __( 'Verify process has failed.', 'jupiterx-core' ) );
		}

		$user_id = email_exists( $email );

		// Email is not registered.
		if ( false === $user_id ) {
			$user_id = $this->create_user( $email );
		}

		$set_meta         = $this->set_user_google_id( $user_id, $user_google_id );
		$unique_login_url = $this->create_unique_link_to_login_google_user( $user_google_id );
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
	 * @param [String] $email
	 * @return int
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
	 * Add User Google ID For The User As Meta.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	private function set_user_google_id( $user_id, $user_google_id ) {
		update_user_meta( $user_id, 'social-media-user-google-id', $user_google_id );
	}

	/**
	 * Create User unique Login URL based on user google id.
	 *
	 * @param string $user_google_id
	 * @return string
	 * @since 2.0.0
	*/
	private function create_unique_link_to_login_google_user( $user_google_id ) {
		$site      = site_url();
		$login_url = $site . '?jupiterx-google-social-login=' . $user_google_id;

		return $login_url;
	}

	/**
	 * Log user in and redirect based on unique URL
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function google_log_user_in() {
		if ( ! isset( $_GET['jupiterx-google-social-login'] ) ) { // phpcs:ignore
			return;
		}

		$value = filter_input( INPUT_GET, 'jupiterx-google-social-login', FILTER_SANITIZE_STRING );
		$user  = get_users(
			[
				'meta_key'    => 'social-media-user-google-id', // phpcs:ignore
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
			$redirect = filter_input( INPUT_GET, 'redirect', FILTER_SANITIZE_URL );
			wp_redirect( $redirect ); // phpcs:ignore
			exit();
		}

		wp_redirect( site_url() ); // phpcs:ignore
		exit();
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
		$user_client_id = get_option( 'elementor_raven_google_client_id' );
		?>
			<script>
				var jxRavenSocialWidgetGoogleClient = '<?php echo $user_client_id; ?>';
			</script>
		<?php
	}
}
