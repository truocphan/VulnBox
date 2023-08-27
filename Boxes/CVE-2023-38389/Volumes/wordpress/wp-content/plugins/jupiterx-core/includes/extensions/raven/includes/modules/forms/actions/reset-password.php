<?php
/**
 * Add form Reset Password action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

/**
 * Reset Password Action.
 *
 * Initializing the reset password action by extending action base.
 *
 * @since 2.0.0
 */
class Reset_Password extends Action_Base {

	/**
	 * Get name.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_name() {
		return 'reset-password';
	}

	/**
	 * Get title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Reset Password', 'jupiterx-core' );
	}

	/**
	 * Is private.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function is_private() {
		return true;
	}

	/**
	 * Update controls.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {}

	/**
	 * Run action.
	 *
	 * Retieve password.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 */
	public static function run( $ajax_handler ) {
		foreach ( $ajax_handler->form['settings']['fields'] as $field ) {
			if ( 'email' === $field['type'] ) {
				$email = $ajax_handler->record['fields'][ $field['_id'] ];
			}
		}

		add_filter( 'lostpassword_errors', function( $errors ) {
			if ( ! $errors->errors['invalid_email'] ) {
				return $errors;
			}

			$errors->errors['invalid_email'][0] = __( 'We could not find an account with that username or email address.', 'jupiterx-core' );

			return $errors;
		}, 10 );

		// Handle reset password.
		$result = retrieve_password( $email );

		if ( is_wp_error( $result ) ) {
			$ajax_handler
				->set_success( false )
				->add_response( 'message', $result->get_error_message() )
				->send_response();
		}

		$ajax_handler->add_response( 'message', $ajax_handler->form['settings']['messages_success'] );
	}

}
