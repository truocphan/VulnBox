<?php
/**
 * Handles Input control css output.
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
class JupiterX_Customizer_Kirki_Extend_Output_Input extends JupiterX_Customizer_Kirki_Extend_Base_Output {

	/**
	 * CSS output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $output         Defined single output.
	 * @param array $filtered_value Filtered settings value.
	 */
	protected function apply_output( $output, $filtered_value ) {
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

		if ( ! is_array( $this->value ) ) {
			return;
		}

		$value = array_merge(
			[
				'size' => '',
				'unit' => '',
			],
			$this->value
		);

		$css_value = JupiterX_Customizer_Control_Input::format_value( $value );

		$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = $output['prefix'] . $this->apply_value_pattern( $output, $css_value ) . $output['suffix'];
	}
}
