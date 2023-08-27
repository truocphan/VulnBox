<?php
/**
 * Handles background control class.
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
 * Background control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Group_Control_Background extends JupiterX_Customizer_Base_Group_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-background';

	/**
	 * Set the fields for this control.
	 *
	 * @since 1.0.0
	 */
	protected function set_fields() {
		$this->add_field( 'label', [
			'type'  => 'jupiterx-label',
			'label' => __( 'Background Color Type', 'jupiterx-core' ),
		] );

		$this->add_field( 'type', [
			'type'     => 'jupiterx-choose',
			'default'  => 'classic',
			'choices'  => [
				'classic'  => [
					'label' => __( 'Color Type', 'jupiterx-core' ),
				],
				'gradient' => [
					'label' => __( 'Gradiant Type', 'jupiterx-core' ),
				],
			],
		] );

		$this->add_field( 'color', [
			'type'     => 'jupiterx-color',
			'label'      => __( 'Background Color', 'jupiterx-core' ),
			'cssClass' => 'for-classic',
			'responsive' => true,
		] );

		$this->add_field( 'image', [
			'type'     => 'jupiterx-image',
			'label'    => __( 'Background Image', 'jupiterx-core' ),
			'cssClass' => 'for-classic',
		] );

		$this->add_field( 'position', [
			'type'    => 'jupiterx-select',
			'label'   => __( 'Position', 'jupiterx-core' ),
			'choices' => [
				'center'       => __( 'Center Center', 'jupiterx-core' ),
				'center left'  => __( 'Center Left', 'jupiterx-core' ),
				'center right' => __( 'Center Right', 'jupiterx-core' ),
				'top'          => __( 'Top Center', 'jupiterx-core' ),
				'top left'     => __( 'Top Left', 'jupiterx-core' ),
				'top right'    => __( 'Top Right', 'jupiterx-core' ),
				'bottom'       => __( 'Bottom Center', 'jupiterx-core' ),
				'bottom left'  => __( 'Bottom Left', 'jupiterx-core' ),
				'bottom right' => __( 'Bottom Right', 'jupiterx-core' ),
			],
			'cssClass' => 'for-classic',
		] );

		$this->add_field( 'repeat', [
			'type'    => 'jupiterx-select',
			'label'   => __( 'Repeat', 'jupiterx-core' ),
			'default' => 'no-repeat',
			'choices' => [
				'repeat'    => __( 'Repeat', 'jupiterx-core' ),
				'repeat-x'  => __( 'Repeat-x', 'jupiterx-core' ),
				'repeat-y'  => __( 'Repeat-y', 'jupiterx-core' ),
				'no-repeat' => __( 'No-repeat', 'jupiterx-core' ),
			],
			'cssClass'   => 'for-classic',
		] );

		$this->add_field( 'attachment', [
			'type'     => 'jupiterx-choose',
			'text'     => __( 'Fixed', 'jupiterx-core' ),
			'default'  => 'scroll',
			'inline'   => true,
			'choices'  => [
				'fixed' => [
					'icon' => 'check',
				],
				'scroll' => [
					'icon' => 'x',
				],
			],
			'cssClass' => 'for-classic',
		] );

		$this->add_field( 'size', [
			'type'     => 'jupiterx-choose',
			'text'     => __( 'Cover', 'jupiterx-core' ),
			'default'  => 'auto',
			'inline'   => true,
			'choices'  => [
				'cover' => [
					'icon' => 'check',
				],
				'auto' => [
					'icon' => 'x',
				],
			],
			'cssClass' => 'for-classic',
		] );

		$this->add_field( 'divider', [
			'type'        => 'jupiterx-divider',
			'dividerType' => 'empty',
			'cssClass'    => 'for-gradient jupiterx-divider-control-empty',
		] );

		$gradient_colors = [
			'color_from' => [
				'type'     => 'jupiterx-color',
				'label'     => is_rtl() ? __( 'Direction Arrow', 'jupiterx-core' ) : __( 'Background Color', 'jupiterx-core' ),
				'cssClass' => 'for-gradient',
			],
			'color_to' => [
				'type'     => 'jupiterx-color',
				'label'     => is_rtl() ? __( 'Background Color', 'jupiterx-core' ) : __( 'Direction Arrow', 'jupiterx-core' ),
				'cssClass' => 'for-gradient',
			],
		];

		if ( is_rtl() ) {
			$gradient_colors = array_reverse( $gradient_colors );
		}

		foreach ( $gradient_colors as $key => $value ) {
			$this->add_field( $key, $value );
		}

		$this->add_field( 'gradient_type', [
			'type'    => 'jupiterx-choose',
			'default' => 'linear',
			'choices' => [
				'linear' => [
					'label' => __( 'Linear', 'jupiterx-core' ),
				],
				'radial' => [
					'label' => __( 'Radial', 'jupiterx-core' ),
				],
			],
			'cssClass' => 'for-gradient',
		] );

		$this->add_field( 'angle', [
			'type'       => 'jupiterx-text',
			'inputType'  => 'number',
			'label'      => __( 'Angle', 'jupiterx-core' ),
			'inputAttrs' => [
				'placeholder' => 90,
			],
			'default'    => 90,
			'cssClass'   => 'for-gradient',
		] );
	}

	/**
	 * Include fields.
	 *
	 * @since 1.0.0
	 */
	protected function include_fields() {
		if ( empty( $this->include ) ) {
			return;
		}

		$include = $this->include;

		if ( in_array( 'video', $include, true ) ) {
			$this->update_field( 'type', [
				'choices'  => [
					'classic'  => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
					'gradient' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
					'video' => [
						'label' => __( 'Video Type', 'jupiterx-core' ),
					],
				],
			] );

			$this->add_field( 'video_divider', [
				'type'        => 'jupiterx-divider',
				'dividerType' => 'empty',
				'cssClass'    => 'for-video',
			] );

			$this->add_field( 'video_link', [
				'type'       => 'jupiterx-text',
				'inputType'  => 'url',
				'inputAttrs' => [
					'placeholder' => __( 'Social or Self hosted video link', 'jupiterx-core' ),
				],
				'label'      => __( 'Video Link', 'jupiterx-core' ),
				'cssClass'   => 'for-video',
			] );

			$this->add_field( 'video_fallback', [
				'type'     => 'jupiterx-image',
				'label'    => __( 'Video Fallback', 'jupiterx-core' ),
				'cssClass' => 'for-video',
			] );
		}
	}

	/**
	 * Exclude fields.
	 *
	 * @since 1.0.0
	 */
	protected function exclude_fields() {
		if ( ! empty( $this->exclude ) && in_array( 'image', $this->exclude, true ) ) {
			$this->exclude = [
				'image',
				'position',
				'repeat',
				'attachment',
				'size',
			];
		}

		parent::exclude_fields();
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
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function format_properties( $value, $args ) {
		$value = wp_parse_args(
			$value,
			[
				'type'       => 'classic',
				'position'   => 'top left',
				'repeat'     => 'no-repeat',
				'attachment' => 'scroll',
				'size'       => 'auto',
			]
		);

		if ( isset( $value['image'] ) && ! empty( $value['image'] ) ) {
			$value['image'] = "url({$value['image']})";
		}

		return $value;
	}

	/**
	 * Format theme mod array value into a valid background value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $value The field's value.
	 *
	 * @return string The formatted background value.
	 */
	public static function format_value( $value ) {
		$value = array_merge(
			[
				'type'          => 'classic',
				'color'         => '',
				'image'         => '',
				'repeat'        => 'no-repeat',
				'attachment'    => '',
				'position'      => 'top left',
				'gradient_type' => 'linear',
				'angle'         => '90',
				'color_from'    => 'transparent',
				'color_to'      => 'transparent',
			],
			$value
		);

		if ( 'classic' === $value['type'] ) {
			if ( ! empty( $value['image'] ) ) {
				return sprintf(
					'%1$s %2$s %3$s %4$s %5$s',
					'url(' . $value['image'] . ')',
					$value['color'],
					$value['attachment'],
					$value['repeat'],
					jupiterx_get_direction( $value['position'] )
				);
			}

			if ( ! empty( $value['color'] ) ) {
				return $value['color'];
			}
		}

		if ( 'gradient' === $value['type'] ) {
			if ( ! is_numeric( $value['angle'] ) ) {
				$value['angle'] = '90';
			}

			$gradient = 'radial' === $value['gradient_type'] ? sprintf( 'radial-gradient(%1$s, %2$s)', $value['color_from'], $value['color_to'] ) : sprintf( 'linear-gradient(%1$sdeg, %2$s, %3$s)', $value['angle'], $value['color_from'], $value['color_to'] );

			return $gradient;
		}
	}


}
