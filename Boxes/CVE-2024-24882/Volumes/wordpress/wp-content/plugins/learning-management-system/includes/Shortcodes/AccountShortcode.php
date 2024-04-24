<?php
/**
 * Account shortcode.
 *
 * @since 1.0.0
 * @class AccountShortcode
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Account shortcode.
 */
class AccountShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_account';

	/**
	 * Get shortcode content.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_content() {
		$template_path = $this->get_template_path();

		/**
		 * Render the template.
		 */
		return $this->get_rendered_html(
			array_merge(
				$this->get_attributes(),
				$this->get_template_args()
			),
			$template_path
		);
	}

	/**
	 * Get template path to render.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function get_template_path() {
		// Render signup page if registration is enable.
		$is_registration_enable = masteriyo_get_setting( 'general.registration.enable_student_registration' );

		if ( masteriyo_is_signup_page() && $is_registration_enable ) {
			return masteriyo( 'template' )->locate( 'account/form-signup.php' );
		}
		if ( masteriyo_is_lost_password_page() ) {
			return $this->get_lost_password_page_template();
		}
		if ( is_user_logged_in() ) {
			return masteriyo( 'template' )->locate( 'account.php' );
		}

		return masteriyo( 'template' )->locate( 'account/form-login.php' );
	}

	/**
	 * Get lost password page template.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function get_lost_password_page_template() {
		if ( ! empty( $_GET['reset-link-sent'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			masteriyo_add_notice( esc_html__( 'Password reset email has been sent.', 'masteriyo' ) );

			return masteriyo( 'template' )->locate( 'account/reset-password-confirmation.php' );
		}

		if ( ! empty( $_GET['show-reset-form'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {  // @codingStandardsIgnoreLine
				list( $rp_id, $rp_key ) = array_map( 'masteriyo_clean', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) ); // @codingStandardsIgnoreLine
				$user                   = masteriyo_get_user( absint( $rp_id ) );
				$rp_login               = $user ? $user->get_username() : '';

				if ( is_wp_error( check_password_reset_key( $rp_key, $rp_login ) ) ) {
					masteriyo_add_notice( __( 'This key is invalid or has already been used. Please request to reset your password again if needed.', 'masteriyo' ), 'error' );
				} else {
					$this->set_template_args(
						array(
							'key'   => $rp_key,
							'login' => $rp_login,
						)
					);
					return masteriyo( 'template' )->locate( 'account/form-reset-password.php' );
				}
			}
		}

		return masteriyo( 'template' )->locate( 'account/form-reset-password-request.php' );
	}
}
