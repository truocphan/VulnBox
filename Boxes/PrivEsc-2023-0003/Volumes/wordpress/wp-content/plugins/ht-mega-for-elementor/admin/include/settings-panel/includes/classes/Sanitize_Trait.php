<?php
namespace HTMegaOpt\SanitizeTrail;

/**
 * Settings Fields Sanitize handler trait
 */
trait Sanitize_Trait {

    /**
	 * Sanitize the text field.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_text_field( $setting_value, $errors, $setting ) {
		return trim( wp_strip_all_tags( $setting_value, true ) );
	}

	/**
	 * Sanitize textarea field.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return string
	 */
	public function sanitize_textarea_field( $setting_value, $errors, $setting ) {
		return stripslashes( wp_kses_post( $setting_value ) );
	}

	/**
	 * Sanitize multiselect and multicheck field.
	 *
	 * @param mixed $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return array
	 */
	public function sanitize_multiple_field( $setting_value, $errors, $setting ) {

		$new_values = [];

		if ( is_array( $setting_value ) && ! empty( $setting_value ) ) {
			foreach ( $setting_value as $key => $value ) {
				$new_values[ sanitize_key( $key ) ] = sanitize_text_field( $value );
			}
		}

		if ( ! empty( $setting_value ) && ! is_array( $setting_value ) ) {
			$setting_value = explode( ',', $setting_value );
			foreach ( $setting_value as $key => $value ) {
				$new_values[ sanitize_key( $key ) ] = sanitize_text_field( $value );
			}
		}

		return $new_values;

	}

	/**
	 * Sanitize urls for the file field.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return void
	 */
	public function sanitize_file_field( $setting_value, $errors, $setting ) {
		return esc_url( $setting_value );
	}

	/**
	 * Sanitize the checkbox field.
	 *
	 * @param string $setting_value
	 * @param object $errors
	 * @param array $setting
	 * @return void
	 */
	public function sanitize_checkbox_field( $setting_value, $errors, $setting ) {

		return ( isset( $setting_value ) && 'on' == $setting_value ) ? 'on' : 'off';

	}

}