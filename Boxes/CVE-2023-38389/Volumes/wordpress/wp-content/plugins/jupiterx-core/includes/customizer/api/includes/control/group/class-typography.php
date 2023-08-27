<?php
/**
 * Handles typography control class.
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
 * Typography control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Group_Control_Typography extends JupiterX_Customizer_Base_Group_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-typography';

	/**
	 * Set the fields for this control.
	 *
	 * @since 1.0.0
	 */
	protected function set_fields() {
		$this->add_field( 'font_family', [
			'type'        => 'jupiterx-font',
			'label'       => __( 'Font Family', 'jupiterx-core' ),
			'placeholder' => __( 'Default', 'jupiterx-core' ),
		] );

		$this->add_field( 'font_size', [
			'type'        => 'jupiterx-input',
			'label'       => __( 'Font Size', 'jupiterx-core' ),
			'units'       => [ 'px', 'em', 'rem' ],
			'defaultUnit' => 'rem',
			'responsive'  => true,
		] );

		$this->add_field( 'color', [
			'type'   => 'jupiterx-color',
			'label'  => __( 'Font Color', 'jupiterx-core' ),
		] );

		$this->add_field( 'font_weight', [
			'type'           => 'jupiterx-select',
			'exclude_reload' => true,
			'label'          => __( 'Font Weight', 'jupiterx-core' ),
			'placeholder'    => __( 'Default', 'jupiterx-core' ),
			'choices'        => $this->get_font_weights(),
		] );

		$this->add_field( 'font_style', [
			'type'    => 'jupiterx-select',
			'default' => 'normal',
			'label'   => __( 'Font Style', 'jupiterx-core' ),
			'choices' => [
				'normal' => __( 'Normal', 'jupiterx-core' ),
				'italic' => __( 'Italic', 'jupiterx-core' ),
			],
		] );

		$this->add_field( 'line_height', [
			'type'        => 'jupiterx-input',
			'label'       => __( 'Line Height', 'jupiterx-core' ),
			'units'       => [ '-', 'px', 'em', 'rem' ],
			'defaultUnit' => '-',
			'inputAttrs' => [
				'min' => -100,
				'max' => 100,
			],
		] );

		$this->add_field( 'letter_spacing', [
			'type'        => 'jupiterx-input',
			'label'       => __( 'Letter Spacing', 'jupiterx-core' ),
			'inputAttrs'  => [
				'min' => -100,
				'max' => 100,
			],
			'units'       => [ 'px', 'em', 'rem' ],
			'defaultUnit' => 'px',
		] );

		$this->add_field( 'text_transform', [
			'type'        => 'jupiterx-select',
			'label'       => __( 'Text Transform', 'jupiterx-core' ),
			'placeholder' => __( 'Default', 'jupiterx-core' ),
			'choices'     => [
				'capitalize' => __( 'Capitalize', 'jupiterx-core' ),
				'lowercase'  => __( 'Lowercase', 'jupiterx-core' ),
				'uppercase'  => __( 'Uppercase', 'jupiterx-core' ),
			],
		] );
	}

	/**
	 * Get safe fonts.
	 *
	 * @since 1.0.0
	 *
	 * @return array Safe fonts.
	 */
	protected function get_safe_fonts() {
		$font_family = [];

		$safe_fonts = [
			'HelveticaNeue-Light, Helvetica Neue Light, Helvetica Neue, Helvetica, Arial, "Lucida Grande", sans-serif',
			'Arial, Helvetica, sans-serif',
			'Arial Black, Gadget, sans-serif',
			'Bookman Old Style, serif',
			'Courier, monospace',
			'Courier New, Courier, monospace',
			'Garamond, serif',
			'Georgia, serif',
			'Impact, Charcoal, sans-serif',
			'Lucida Console, Monaco, monospace',
			'Lucida Grande, Lucida Sans Unicode, sans-serif',
			'MS Sans Serif, Geneva, sans-serif',
			'MS Serif, New York, sans-serif',
			'Palatino Linotype, Book Antiqua, Palatino, serif',
			'Tahoma, Geneva, sans-serif',
			'Times New Roman, Times, serif',
			'Trebuchet MS, Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif',
			'Comic Sans MS, cursive',
		];

		foreach ( $safe_fonts as $font ) {
			$font_family[ $font ] = $font;
		}

		return $font_family;
	}

	/**
	 * Get font weights.
	 *
	 * @since 1.0.0
	 */
	protected function get_font_weights() {
		$font_weights = [
			'normal'  => __( 'Normal', 'jupiterx-core' ),
			'bold'    => __( 'Bold', 'jupiterx-core' ),
			'bolder'  => __( 'Bolder', 'jupiterx-core' ),
			'lighter' => __( 'Lighter', 'jupiterx-core' ),
			'100'     => __( '100', 'jupiterx-core' ),
			'200'     => __( '200', 'jupiterx-core' ),
			'300'     => __( '300', 'jupiterx-core' ),
			'400'     => __( '400', 'jupiterx-core' ),
			'500'     => __( '500', 'jupiterx-core' ),
			'600'     => __( '600', 'jupiterx-core' ),
			'700'     => __( '700', 'jupiterx-core' ),
			'800'     => __( '800', 'jupiterx-core' ),
			'900'     => __( '900', 'jupiterx-core' ),
		];

		return $font_weights;
	}

	/**
	 * Format CSS value from theme mod array value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The field's value.
	 * @param array $args The field's arguments.
	 *
	 * @return array The formatted properties.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function format_properties( $value, $args ) {
		$with_unit = [ 'font_size', 'line_height', 'letter_spacing' ];

		foreach ( $with_unit as $property ) {
			if ( isset( $value[ $property ] ) && ! empty( $value[ $property ] ) ) {
				$value[ $property ] = JupiterX_Customizer_Control_Input::format_value( $value[ $property ] );
			}
		}

		return $value;
	}
}
