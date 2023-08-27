<?php
/**
 * This class handles typography control css output.
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
class JupiterX_Customizer_Kirki_Extend_Output_Typography extends JupiterX_Customizer_Kirki_Extend_Base_Output {

	/**
	 * CSS output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $output Defined single output.
	 * @param array $value Settings value.
	 */
	protected function apply_output( $output, $value ) {
		$output = array_merge(
			[
				'element'     => '',
				'property'    => '',
				'media_query' => 'global',
				'prefix'      => '',
				'suffix'      => '',
			],
			$output
		);

		if ( ! is_array( $value ) ) {
			return;
		}

		$with_unit = [ 'font_size', 'line_height', 'letter_spacing' ];

		if ( isset( $value['font_size'] ) && empty( $value['font_size'] ) ) {
			$value['font_size'] = 'inherit';
		}

		foreach ( $value as $property => $raw_value ) {
			$css = [
				'property' => str_replace( '_', '-', $property ),
				'value'    => $raw_value,
			];

			if ( isset( $output['choice'] ) && $output['choice'] !== $property ) {
				continue;
			}

			if ( in_array( $property, $with_unit, true ) ) {
				$css['value'] = $this->apply_value_pattern( $output, JupiterX_Customizer_Control_Input::format_value( $raw_value ) );
			}

			$this->styles[ $output['media_query'] ][ $output['element'] ][ $css['property'] ] = $output['prefix'] . $this->process_property_value( $css['property'], $css['value'] ) . $output['suffix'];
		}
	}
}
