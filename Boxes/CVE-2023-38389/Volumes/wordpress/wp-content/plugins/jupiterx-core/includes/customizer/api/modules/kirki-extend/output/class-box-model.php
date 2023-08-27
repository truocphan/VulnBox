<?php
/**
 * This class handles box model control css output.
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
 * Overrides Kirki CSS output.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Kirki_Extend_Output_Box_Model extends JupiterX_Customizer_Kirki_Extend_Base_Output {

	/**
	 * Processes a single item from the `output` array.
	 *
	 * @since 1.0.0
	 *
	 * @param array $output Defined single output.
	 * @param array $value  Settings value.
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function apply_output( $output, $value ) {
		$output = wp_parse_args(
			$output, array(
				'element'     => '',
				'property'    => '',
				'media_query' => 'global',
				'prefix'      => '',
				'suffix'      => '',
			)
		);

		if ( ! is_array( $value ) ) {
			return;
		}

		if ( ! isset( $this->field['exclude'] ) ) {
			$this->field['exclude'] = [];
		}

		$positions    = [ 'top', 'right', 'bottom', 'left' ];
		$default_unit = JupiterX_Customizer_Control_Box_Model::$default_unit;

		if ( ! in_array( 'margin', $this->field['exclude'], true ) ) {
			$margin_unit = isset( $value['margin_unit'] ) ? $value['margin_unit'] : $default_unit;

			foreach ( $positions as $position ) {
				// Accepts non-numeric value such as 'auto'.
				if ( array_key_exists( 'margin_' . $position, $value ) ) {
					$property = 'margin-' . $position;
					$unit     = is_numeric( $value[ 'margin_' . $position ] ) ? $margin_unit : '';

					$this->styles[ $output['media_query'] ][ $output['element'] ][ $property ] = $output['prefix'] . $this->process_property_value( $property, $value[ 'margin_' . $position ] ) . $unit . $output['suffix'];
				}
			}
		}

		if ( ! in_array( 'padding', $this->field['exclude'], true ) ) {
			$padding_unit = isset( $value['padding_unit'] ) ? $value['padding_unit'] : $default_unit;

			foreach ( $positions as $position ) {
				// Does not accept any value that is not numeric.
				if ( array_key_exists( 'padding_' . $position, $value ) && is_numeric( $value[ 'padding_' . $position ] ) ) {
					$property = 'padding-' . $position;
					$unit     = $padding_unit;

					$this->styles[ $output['media_query'] ][ $output['element'] ][ $property ] = $output['prefix'] . $this->process_property_value( $property, $value[ 'padding_' . $position ] ) . $unit . $output['suffix'];
				}
			}
		}
	}
}
