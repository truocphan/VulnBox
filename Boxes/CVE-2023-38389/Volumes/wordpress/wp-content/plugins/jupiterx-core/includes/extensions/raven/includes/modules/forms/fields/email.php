<?php
/**
 * Add form email field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

/**
 * Email Field.
 *
 * Initializing the email field by extending text field.
 *
 * @since 1.0.0
 */
class Email extends Text {

	/**
	 * Validate.
	 *
	 * Check the field based on specific validation rules.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param object $field The field data.
	 */
	public static function validate( $ajax_handler, $field ) {
		$record_field = $ajax_handler->record['fields'][ $field['_id'] ];

		if ( ! empty( $ajax_handler->response['errors'][ $field['_id'] ] ) ) {
			return;
		}

		if ( empty( $record_field ) ) {
			return;
		}

		if ( ! filter_var( $record_field, FILTER_VALIDATE_EMAIL ) ) {
			$error = __( 'The value is not a valid email address', 'jupiterx-core' );
		}

		if ( empty( $error ) ) {
			return;
		}

		$ajax_handler
			->add_response( 'errors', $error, $field['_id'] )
			->set_success( false );
	}

}
