<?php
/**
 * Base output class.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles default output.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Kirki_Extend_Base_Output extends Kirki_Output {

	/**
	 * Process output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $output Defined single output.
	 * @param array $value  Settings value.
	 */
	protected function process_output( $output, $value ) {
		if ( ! isset( $this->field['responsive'] ) ) {
			$this->apply_output( $output, $value );
		}

		if ( isset( $this->field['responsive'] ) && $this->field['responsive'] ) {
			$this->apply_responsive_output( $output, $value );
		}
	}

	/**
	 * CSS output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $output Defined single output.
	 * @param array $value  Settings value.
	 */
	protected function apply_output( $output, $value ) {
		parent::process_output( $output, $value );
	}

	/**
	 * Responsive CSS output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $output Defined single output.
	 * @param array $value Settings value.
	 */
	protected function apply_responsive_output( $output, $value ) {
		foreach ( JupiterX_Customizer::$responsive_devices as $device => $media_query ) {
			if ( ! isset( $value[ $device ] ) ) {
				continue;
			}

			$device_output = array_merge( [ 'media_query' => $media_query ], $output );

			$device_value = $value[ $device ];

			$this->apply_output( $device_output, $device_value );
		}
	}
}
