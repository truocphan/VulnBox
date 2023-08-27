<?php
/**
 * This class handles border control css output.
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
class JupiterX_Customizer_Kirki_Extend_Output_Border extends JupiterX_Customizer_Kirki_Extend_Base_Output {

	/**
	 * CSS output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $output Defined single output.
	 * @param array $value Settings value.
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function apply_output( $output, $value ) {
		$output = array_merge(
			[
				'element'     => '',
				'property'    => 'border',
				'media_query' => 'global',
				'prefix'      => '',
				'suffix'      => '',
			],
			$output
		);

		$value = array_merge(
			[
				'size'   => [],
				'radius' => [],
				'width'  => [],
				'style'  => 'solid',
				'color'  => '',
			],
			$this->value
		);

		// Get a value from array and use it to the property.
		if ( isset( $output['choice'] ) && isset( $output['property'] ) ) {
			switch ( $output['choice'] ) {
				case 'size':
				case 'radius':
				case 'width':
					if ( isset( $value[ $output['choice'] ] ) && ! empty( $value[ $output['choice'] ] ) ) {
						$input_value = JupiterX_Customizer_Control_Input::format_value( $value[ $output['choice'] ] );
						$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = $this->apply_value_pattern( $output, $input_value );
					}
					break;

				case 'style':
				case 'color':
					if ( isset( $value[ $output['choice'] ] ) && '' !== $value[ $output['choice'] ] ) {
						$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = $this->apply_value_pattern( $output, $value[ $output['choice'] ] );
					}
					break;
			}

			return;
		}

		// Size.
		if ( ! empty( $value['size'] ) ) {
			$size = JupiterX_Customizer_Control_Input::format_value( $value['size'] );
			$this->styles[ $output['media_query'] ][ $output['element'] ]['width'] = $output['prefix'] . $size . $output['suffix'];
		}

		// Border Radius.
		if ( ! empty( $value['radius'] ) ) {
			$radius = JupiterX_Customizer_Control_Input::format_value( $value['radius'] );
			$this->styles[ $output['media_query'] ][ $output['element'] ]['border-radius'] = $output['prefix'] . $radius . $output['suffix'];
		}

		// Border Width.
		if ( ! empty( $value['width'] ) ) {
			$width = JupiterX_Customizer_Control_Input::format_value( $value['width'] );
			$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] . '-width' ] = $output['prefix'] . $width . $output['suffix'];
		}

		// Border Style.
		if ( ! empty( $value['style'] ) ) {
			$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] . '-style' ] = $output['prefix'] . $value['style'] . $output['suffix'];
		}

		// Border Color.
		if ( ! empty( $value['color'] ) ) {
			$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] . '-color' ] = $output['prefix'] . $value['color'] . $output['suffix'];
		}
	}
}
