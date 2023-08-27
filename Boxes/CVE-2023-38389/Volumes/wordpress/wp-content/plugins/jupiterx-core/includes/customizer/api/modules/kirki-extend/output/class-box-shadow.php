<?php
/**
 * Handles Box Shadow control css output.
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
class JupiterX_Customizer_Kirki_Extend_Output_Box_Shadow extends JupiterX_Customizer_Kirki_Extend_Base_Output {

	/**
	 * CSS output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $output Defined single output.
	 * @param array $value Settings value.
	 */
	protected function apply_output( $output, $value ) {
		$output = wp_parse_args(
			$output, [
				'element'     => '',
				'property'    => 'box-shadow',
				'media_query' => 'global',
				'unit'        => '',
				'prefix'      => '',
				'suffix'      => '',
			]
		);

		$value = JupiterX_Customizer_Group_Control_Box_Shadow::format_value( $value, $output['units'] );

		$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = $output['prefix'] . $this->process_property_value( $output['property'], $value ) . $output['suffix'];
	}
}
