<?php
/**
 * Handles box shadow control class.
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
 * Box_Shadow control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Group_Control_Box_Shadow extends JupiterX_Customizer_Base_Group_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-box-shadow';

	/**
	 * Set the fields for this control.
	 *
	 * @since 1.0.0
	 */
	protected function set_fields() {
		$this->add_field( 'horizontal', [
			'type'       => 'jupiterx-text',
			'inputType'  => 'number',
			'label'      => __( 'Horizontal', 'jupiterx-core' ),
			'inputAttrs' => [
				'placeholder' => 0,
			],
		] );

		$this->add_field( 'vertical', [
			'type'       => 'jupiterx-text',
			'inputType'  => 'number',
			'label'      => __( 'Vertical', 'jupiterx-core' ),
			'inputAttrs' => [ 'placeholder' => 0 ],
		] );

		$this->add_field( 'blur', [
			'type'       => 'jupiterx-text',
			'inputType'  => 'number',
			'label'      => __( 'Blur', 'jupiterx-core' ),
			'inputAttrs' => [ 'placeholder' => 0 ],
		] );

		$this->add_field( 'spread', [
			'type'       => 'jupiterx-text',
			'inputType'  => 'number',
			'label'      => __( 'Spread', 'jupiterx-core' ),
			'inputAttrs' => [ 'placeholder' => 0 ],
		] );

		$this->add_field( 'position', [
			'type'    => 'jupiterx-choose',
			'label'   => __( 'Position', 'jupiterx-core' ),
			'default' => '',
			'choices' => [
				'' => [
					'label' => __( 'Outline', 'jupiterx-core' ),
				],
				'inset' => [
					'label' => __( 'Inset', 'jupiterx-core' ),
				],
			],
		] );

		$this->add_field( 'color', [
			'type'   => 'jupiterx-color',
			'label'  => __( 'Color', 'jupiterx-core' ),
		] );
	}

	/**
	 * Format theme mod array value into a valid box shadow value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The field's value.
	 *
	 * @return string The formatted box shadow value.
	 */
	public static function format_value( $value ) {
		$value = array_merge(
			[
				'horizontal' => 0,
				'vertical'   => 0,
				'blur'       => 0,
				'spread'     => 0,
				'color'      => '#0000',
				'position'   => '',
				'unit'       => 'px',
			],
			$value
		);

		$value = sprintf(
			'%1$s%7$s %2$s%7$s %3$s%7$s %4$s%7$s %5$s %6$s',
			$value['horizontal'],
			$value['vertical'],
			$value['blur'],
			$value['spread'],
			$value['color'],
			$value['position'],
			$value['unit']
		);

		return $value;
	}
}
