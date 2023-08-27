<?php
/**
 * Handles border control class.
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
 * Border control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Group_Control_Border extends JupiterX_Customizer_Base_Group_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-border';

	/**
	 * Set the fields for this control.
	 *
	 * @since 1.0.0
	 */
	protected function set_fields() {
		$this->add_field( 'style', [
			'type'    => 'jupiterx-select',
			'label'   => __( 'Border Style', 'jupiterx-core' ),
			'unit'    => 'px',
			'default' => 'solid',
			'choices' => [
				'dashed' => __( 'Dashed', 'jupiterx-core' ),
				'dotted' => __( 'Dotted', 'jupiterx-core' ),
				'solid'  => __( 'Solid', 'jupiterx-core' ),
			],
		] );

		$this->add_field( 'size', [
			'type'   => 'jupiterx-input',
			'label'  => __( 'Width', 'jupiterx-core' ),
			'units'  => [ 'px', '%', 'em', 'rem' ],
		] );

		$this->add_field( 'width', [
			'type'       => 'jupiterx-input',
			'label'      => __( 'Border Width', 'jupiterx-core' ),
			'units'      => [ 'px' ],
			'responsive' => true,
		] );

		$this->add_field( 'radius', [
			'type'   => 'jupiterx-input',
			'label'  => __( 'Border Radius', 'jupiterx-core' ),
			'units'  => [ 'px', '%', 'rem' ],
		] );

		$this->add_field( 'color', [
			'type'   => 'jupiterx-color',
			'label'  => __( 'Border Color', 'jupiterx-core' ),
		] );
	}

	/**
	 * Format CSS value from theme mod array value.
	 *
	 * Add unit to border width.
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The field's value.
	 * @param array $args The field's arguments.
	 *
	 * @return array The formatted properties.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function format_properties( $value, $args ) {
		$with_unit = [ 'width', 'radius', 'size' ];

		foreach ( $with_unit as $property ) {
			if ( isset( $value[ $property ] ) && ! empty( $value[ $property ] ) ) {
				$value[ $property ] = JupiterX_Customizer_Control_Input::format_value( $value[ $property ] );
			}
		}

		return $value;
	}
}
