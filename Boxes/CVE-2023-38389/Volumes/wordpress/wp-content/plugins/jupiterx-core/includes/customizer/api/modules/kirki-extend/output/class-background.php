<?php
/**
 * This class handles background control css output.
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
class JupiterX_Customizer_Kirki_Extend_Output_Background extends JupiterX_Customizer_Kirki_Extend_Base_Output {

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
				'property'    => '',
				'media_query' => 'global',
				'unit'        => '',
				'prefix'      => '',
				'suffix'      => '',
			]
		);

		if ( ! is_array( $value ) ) {
			return;
		}

		// Add fallback for value.
		$value = wp_parse_args(
			$value, [
				'type'          => 'classic',
				'color'         => '',
				'image'         => '',
				'repeat'        => 'no-repeat',
				'attachment'    => 'scroll',
				'size'          => 'auto',
				'position'      => 'initial',
				'gradient_type' => 'linear',
				'angle'         => '90',
				'color_from'    => 'transparent',
				'color_to'      => 'transparent',
			]
		);

		if ( 'classic' === $value['type'] ) {
			$this->styles[ $output['media_query'] ][ $output['element'] ]['background'] = 'none';

			// CSS for background color.
			if ( ! empty( $value['color'] ) ) {
				$this->styles[ $output['media_query'] ][ $output['element'] ]['background-color'] = $output['prefix'] . $value['color'] . $output['suffix'];
			}

			// Exit proceedings if background image is empty.
			if ( empty( $value['image'] ) ) {
				return;
			}

			// Style for background image.
			$this->styles[ $output['media_query'] ][ $output['element'] ]['background-image'] = $output['prefix'] . $this->process_property_value( 'background-image', $value['image'] ) . $output['suffix'];

			// CSS for these properties.
			foreach ( [ 'position', 'repeat', 'attachment', 'size' ] as $property ) {
				if ( isset( $value[ $property ] ) && ! empty( $value[ $property ] ) ) {
					$this->styles[ $output['media_query'] ][ $output['element'] ][ 'background-' . $property ] = $output['prefix'] . $value[ $property ] . $output['suffix'];
				}
			}
		}

		if ( 'gradient' === $value['type'] ) {
			if ( empty( $value['angle'] ) ) {
				$value['angle'] = '90';
			}

			// Create gradient value.
			$gradient = 'radial' === $value['gradient_type'] ? sprintf( 'radial-gradient(%1$s, %2$s)', $value['color_from'], $value['color_to'] ) : sprintf( 'linear-gradient(%1$sdeg, %2$s, %3$s)', $value['angle'], $value['color_from'], $value['color_to'] );

			// CSS for gradient.
			$this->styles[ $output['media_query'] ][ $output['element'] ]['background'] = $output['prefix'] . $gradient . $output['suffix'];
		}
	}
}
