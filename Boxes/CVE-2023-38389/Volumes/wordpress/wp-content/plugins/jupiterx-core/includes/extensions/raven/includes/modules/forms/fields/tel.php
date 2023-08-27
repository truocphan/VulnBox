<?php
/**
 * Add form tel field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

/**
 * Tel Field.
 *
 * Initializing the tel field by extending text field.
 *
 * @since 1.0.0
 */
class Tel extends Text {

	/**
	 * Get field pattern.
	 *
	 * Retrieve the field pattern.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field pattern.
	 */
	public function get_pattern() {
		return '^[0-9\-\+\s\(\)]*$';
	}

	/**
	 * Get field title.
	 *
	 * Retrieve the field title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field title.
	 */
	public function get_title() {
		return __( 'The value should only consist numbers and phone characters (-, +, (), etc)', 'jupiterx-core' );
	}

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

		if ( ! preg_match( '/^[0-9\-\+\s\(\)]*$/', $record_field ) ) {
			$error = __( 'The value should only consist numbers and phone characters (-, +, (), etc)', 'jupiterx-core' );
		}

		if ( empty( $error ) ) {
			return;
		}

		$ajax_handler
			->add_response( 'errors', $error, $field['_id'] )
			->set_success( false );
	}

	/**
	 * Render content (override).
	 *
	 * Render the field content.
	 *
	 * @since 2.5.0
	 * @access public
	 */
	public function render_content() {
		$settings = $this->widget->get_settings_for_display();

		$field_key = array_search( $this->get_id(), array_column( $settings['fields'], '_id' ), true );
		$field     = $settings['fields'][ $field_key ];

		if ( isset( $field['required'] ) && 'true' !== $field['required'] ) {
			parent::render_content();
			return;
		}

		$iti_switcher_options = [
			'iti_tel'                  => 'data-iti-tel',
			'iti_tel_ip_detect'        => 'data-iti-ip-detect',
			'iti_tel_require_area'     => 'data-iti-area-required',
			'iti_tel_internationalize' => 'data-iti-internationalize',
			'iti_tel_allow_dropdown'   => 'data-iti-allow-dropdown',
		];

		foreach ( $iti_switcher_options as $id => $attr ) {
			if ( isset( $field[ $id ] ) && 'yes' === $field[ $id ] ) {
				$this->widget->add_render_attribute( 'field-' . $this->get_id(), $attr );
			}
		}

		$iti_select_options = [
			'iti_tel_country_include' => 'data-iti-country-include',
			'iti_tel_tel_type' => 'data-iti-tel-type',
		];

		foreach ( $iti_select_options as $id => $attr ) {
			if ( isset( $field[ $id ] ) && ( ! empty( $field[ $id ] ) || '0' === $field[ $id ] ) ) {
				$this->widget->add_render_attribute( 'field-' . $this->get_id(), $attr, $field[ $id ] );
			}
		}

		parent::render_content();
	}
}
