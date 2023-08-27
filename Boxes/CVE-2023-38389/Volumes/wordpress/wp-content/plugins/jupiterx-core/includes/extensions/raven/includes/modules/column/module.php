<?php
namespace JupiterX_Core\Raven\Modules\Column;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use Elementor\Plugin;

class Module extends Module_Base {

	public function __construct() {
		add_action( 'elementor/element/column/layout/before_section_end', [ $this, 'extend_settings' ], 10 );
		add_action( 'elementor/frontend/column/before_render', [ $this, 'before_render' ] );
	}

	public function extend_settings( $element ) {
		$element->add_control(
			'raven_link',
			[
				'label' => __( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
					'categories' => [
						'url',
					],
				],
				'placeholder' => 'https://your-link.com',
				'render_type' => 'ui',
			]
		);

		if ( ! empty( Plugin::$instance->experiments ) ) {
			$is_dom_optimization_active = Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		}

		if ( empty( $is_dom_optimization_active ) ) {
			$element->update_control(
				'content_position',
				[
					'selectors' => [
						'{{WRAPPER}}.elementor-column .elementor-column-wrap' => 'align-items: {{VALUE}}', // Elementor 2.
						'{{WRAPPER}}.elementor-column .elementor-column-wrap .elementor-widget-wrap' => 'align-items: {{VALUE}}', // Elementor 2.
						'{{WRAPPER}}.elementor-column .elementor-widget-wrap' => 'align-items: {{VALUE}}', // Elementor 3.
					],
				]
			);
		}

		$element->add_control(
			'raven_display',
			[
				'label' => __( 'Display', 'jupiterx-core' ),
				'description' => __( 'This Raven option is deprecated and will be removed in v2.0. Set this to <strong>Block</strong> and use Elementor <strong>Custom Positioning</strong> feature.', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Block', 'jupiterx-core' ),
					'flex' => __( 'Flex', 'jupiterx-core' ),
				],
			]
		);

		$element->add_control(
			'raven_flex_orientation',
			[
				'label' => __( 'Content Orientation', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'horizontal',
				'options' => [
					'horizontal' => [
						'title' => __( 'Horizontal', 'jupiterx-core' ),
						'icon' => 'eicon-ellipsis-h',
					],
					'vertical' => [
						'title' => __( 'Vertical', 'jupiterx-core' ),
						'icon' => 'eicon-editor-list-ul',
					],
				],
				'label_block' => false,
				'prefix_class' => 'raven-column-flex-',
				'condition' => [ 'raven_display' => 'flex' ],
			]
		);

		$element->add_control(
			'raven_flex_align',
			[
				'label' => __( 'Content Align', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'jupiterx-core' ),
					'start' => __( 'Left', 'jupiterx-core' ),
					'center' => __( 'Middle', 'jupiterx-core' ),
					'end' => __( 'Right', 'jupiterx-core' ),
					'space-between' => __( 'Space Between', 'jupiterx-core' ),
					'space-evenly' => __( 'Space Evenly', 'jupiterx-core' ),
					'space-around' => __( 'Space Around', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-column-flex-',
				'condition' => [
					'raven_display' => 'flex',
					'raven_flex_orientation' => 'horizontal',
				],
			]
		);

		$element->add_control(
			'raven_flex_vertical_align',
			[
				'label' => __( 'Content Align', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'jupiterx-core' ),
					'start' => __( 'Top', 'jupiterx-core' ),
					'center' => __( 'Middle', 'jupiterx-core' ),
					'end' => __( 'Bottom', 'jupiterx-core' ),
					'space-between' => __( 'Space Between', 'jupiterx-core' ),
					'space-evenly' => __( 'Space Evenly', 'jupiterx-core' ),
					'space-around' => __( 'Space Around', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-column-flex-',
				'condition' => [
					'raven_display' => 'flex',
					'raven_flex_orientation' => 'vertical',
				],
			]
		);
	}

	public function before_render( \Elementor\Element_Base $element ) {
		$link = $element->get_settings_for_display( 'raven_link' );

		if ( empty( $link['url'] ) ) {
			return;
		}

		$element->add_render_attribute( '_wrapper', [
			'class' => 'raven-column-link',
			'data-raven-link' => $link['url'],
			'data-raven-link-target' => empty( $link['is_external'] ) ? '_self' : '_blank',
		] );
	}

}
