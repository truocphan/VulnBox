<?php
namespace JupiterX_Core\Raven\Modules\Hotspot\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use JupiterX_Core\Raven\Base\Base_Widget;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class Hotspot extends Base_Widget {

	public function get_name() {
		return 'raven-hotspot';
	}

	public function get_title() {
		return esc_html__( 'Hotspot', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-hotspot';
	}


	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'jupiterx-core' ),
				'type' => 'media',
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'image', // Usage: {name}_size and {name}_custom_dimension, in this case image_size and image_custom_dimension.
				'default' => 'large',
				'separator' => 'none',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--background-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'jupiterx-core' ),
				'type' => 'hidden',
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--container-width: {{SIZE}}{{UNIT}}; --image-width: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => esc_html__( 'Max Width', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--container-max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--container-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'object-fit',
			[
				'label' => esc_html__( 'Object Fit', 'jupiterx-core' ),
				'type' => 'select',
				'condition' => [
					'height[size]!' => '',
				],
				'options' => [
					'' => esc_html__( 'Default', 'jupiterx-core' ),
					'fill' => esc_html__( 'Fill', 'jupiterx-core' ),
					'cover' => esc_html__( 'Cover', 'jupiterx-core' ),
					'contain' => esc_html__( 'Contain', 'jupiterx-core' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'separator_panel_style',
			[
				'type' => 'divider',
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			'css-filter',
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container>img:hover' => '--opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			'css-filter',
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}}:hover img',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			'border',
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->end_controls_section();

		/**
		 * Section Hotspot
		 */
		$this->start_controls_section(
			'hotspot_section',
			[
				'label' => esc_html__( 'Hotspot', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'hotspot_repeater' );

		$repeater->start_controls_tab(
			'hotspot_content_tab',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'hotspot_label',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'type' => 'text',
				'default' => '',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'hotspot_link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'hotspot_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'hotspot_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Icon Start', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'end' => [
						'title' => esc_html__( 'Icon End', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'start' => 'grid-column: 1;',
					'end' => 'grid-column: 2;',
				],
				'condition' => [
					'hotspot_icon[value]!' => '',
					'hotspot_label[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .raven-hotspot__icon' => '{{VALUE}}',
				],
				'default' => 'start',
			]
		);

		$repeater->add_control(
			'hotspot_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '5',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .raven-hotspot__button' =>
							'grid-gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'hotspot_icon[value]!' => '',
					'hotspot_label[value]!' => '',
				],
			]
		);

		$repeater->add_control(
			'hotspot_custom_size',
			[
				'label' => esc_html__( 'Custom Hotspot Size', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'default' => 'no',
				'description' => esc_html__( 'Set custom Hotspot size that will only affect this specific hotspot.', 'jupiterx-core' ),
			]
		);

		$repeater->add_control('hotspot_width',
			[
				'label' => esc_html__( 'Min Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--raven-hotspot-min-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'hotspot_custom_size' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_height',
			[
				'label' => esc_html__( 'Min Height', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--raven-hotspot-min-height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'hotspot_custom_size' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_tooltip_content',
			[
				'render_type' => 'template',
				'label' => esc_html__( 'Tooltip Content', 'jupiterx-core' ),
				'type' => 'wysiwyg',
				'default' => esc_html__( 'Add Your Tooltip Text Here', 'jupiterx-core' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'hotspot_position_tab',
			[
				'label' => esc_html__( 'POSITION', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'hotspot_horizontal',
			[
				'label' => esc_html__( 'Horizontal Orientation', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => is_rtl() ? 'right' : 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => false,
			]
		);

		$repeater->add_responsive_control(
			'hotspot_offset_x',
			[
				'label' => esc_html__( 'Offset', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
					'size' => '50',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' =>
							'{{hotspot_horizontal.VALUE}}: {{SIZE}}%; --raven-hotspot-translate-x: {{SIZE}}%;',
				],
			]
		);

		$repeater->add_control(
			'hotspot_vertical',
			[
				'label' => esc_html__( 'Vertical Orientation', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'toggle' => false,
			]
		);

		$repeater->add_responsive_control(
			'hotspot_offset_y',
			[
				'label' => esc_html__( 'Offset', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
					'size' => '50',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' =>
							'{{hotspot_vertical.VALUE}}: {{SIZE}}%; --raven-hotspot-translate-y: {{SIZE}}%;',
				],
			]
		);

		$repeater->add_control(
			'hotspot_tooltip_position',
			[
				'label' => esc_html__( 'Custom Tooltip Properties', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'default' => 'no',
				'description' => sprintf( esc_html__( 'Set custom Tooltip opening that will only affect this specific hotspot.', 'jupiterx-core' ), '<code>|</code>' ),
			]
		);

		$repeater->add_control(
			'hotspot_heading',
			[
				'label' => esc_html__( 'Box', 'jupiterx-core' ),
				'type' => 'heading',
				'condition' => [
					'hotspot_tooltip_position' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'hotspot_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'right' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'bottom' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'left' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
					'top' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .raven-hotspot--tooltip-position' => 'right: initial;bottom: initial;left: initial;top: initial;{{VALUE}}: calc(100% + 5px );',
				],
				'condition' => [
					'hotspot_tooltip_position' => 'yes',
				],
				'render_type' => 'template',
			]
		);

		$repeater->add_responsive_control(
			'hotspot_tooltip_width',
			[
				'label' => esc_html__( 'Min Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .raven-hotspot__tooltip' => 'min-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'hotspot_tooltip_position' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_tooltip_text_wrap',
			[
				'label' => esc_html__( 'Text Wrap', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--white-space: normal',
				],
				'condition' => [
					'hotspot_tooltip_position' => 'yes',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'hotspot',
			[
				'label' => esc_html__( 'Hotspot', 'jupiterx-core' ),
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ hotspot_label }}}',
				'default' => [
					[
						// Default #1 circle.
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'hotspot_animation',
			[
				'label' => esc_html__( 'Animation', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'raven-hotspot--soft-beat' => esc_html__( 'Soft Beat', 'jupiterx-core' ),
					'raven-hotspot--expand' => esc_html__( 'Expand', 'jupiterx-core' ),
					'raven-hotspot--overlay' => esc_html__( 'Overlay', 'jupiterx-core' ),
					'' => esc_html__( 'None', 'jupiterx-core' ),
				],
				'default' => 'raven-hotspot--expand',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hotspot_sequenced_animation',
			[
				'label' => esc_html__( 'Sequenced Animation', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'default' => 'no',
				'frontend_available' => true,
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'hotspot_sequenced_animation_duration',
			[
				'label' => esc_html__( 'Sequence Duration (ms)', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 20000,
					],
				],
				'condition' => [
					'hotspot_sequenced_animation' => 'yes',
				],
				'frontend_available' => true,
				'render_type' => 'ui',
			]
		);

		$this->end_controls_section();

		/**
		 * Tooltip Section
		 */
		$this->start_controls_section(
			'tooltip_section',
			[
				'label' => esc_html__( 'Tooltip', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'tooltip_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'top',
				'toggle' => false,
				'options' => [
					'right' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'bottom' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'left' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
					'top' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-hotspot--tooltip-position' => 'right: initial;bottom: initial;left: initial;top: initial;{{VALUE}}: calc(100% + 5px );',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'tooltip_trigger',
			[
				'label' => esc_html__( 'Trigger', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'mouseenter' => esc_html__( 'Hover', 'jupiterx-core' ),
					'click' => esc_html__( 'Click', 'jupiterx-core' ),
					'none' => esc_html__( 'None', 'jupiterx-core' ),
				],
				'default' => 'click',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'tooltip_animation',
			[
				'label' => esc_html__( 'Animation', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'raven-hotspot--fade-in-out' => esc_html__( 'Fade In/Out', 'jupiterx-core' ),
					'raven-hotspot--fade-grow' => esc_html__( 'Fade Grow', 'jupiterx-core' ),
					'raven-hotspot--fade-direction' => esc_html__( 'Fade By Direction', 'jupiterx-core' ),
					'raven-hotspot--slide-direction' => esc_html__( 'Slide By Direction', 'jupiterx-core' ),
				],
				'default' => 'raven-hotspot--fade-in-out',
				'placeholder' => esc_html__( 'Enter your image caption', 'jupiterx-core' ),
				'condition' => [
					'tooltip_trigger!' => 'none',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'tooltip_animation_duration',
			[
				'label' => esc_html__( 'Duration (ms)', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--tooltip-transition-duration: {{SIZE}}ms;',
				],
				'condition' => [
					'tooltip_trigger!' => 'none',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Section Style Hotspot
		 */
		$this->start_controls_section(
			'section_style_hotspot',
			[
				'label' => esc_html__( 'Hotspot', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'style_hotspot_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#6EC1E4',
				'selectors' => [
					'{{WRAPPER}}' => '--raven-hotspot-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'style_hotspot_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-hotspot-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'style_typography',
				'selector' => '{{WRAPPER}} .raven-hotspot__label',
				'default' => '#6EC1E4',
			]
		);

		$this->add_responsive_control(
			'style_hotspot_width',
			[
				'label' => esc_html__( 'Min Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-hotspot-min-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'style_hotspot_height',
			[
				'label' => esc_html__( 'Min Height', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-hotspot-min-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'style_hotspot_box_color',
			[
				'label' => esc_html__( 'Box Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#54595F',
				'selectors' => [
					'{{WRAPPER}}' => '--raven-hotspot-box-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'style_hotspot_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-hotspot-padding: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);

		$this->add_control(
			'style_hotspot_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => '--raven-hotspot-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'style_hotspot_box_shadow',
				'selector' => '
					{{WRAPPER}} .raven-hotspot:not(.raven-hotspot--circle) .raven-hotspot__button,
					{{WRAPPER}} .raven-hotspot.raven-hotspot--circle .raven-hotspot__button .raven-hotspot__outer-circle
				',
			]
		);

		$this->end_controls_section();

		/**
		 * Section Style Tooltip
		 */
		$this->start_controls_section(
			'section_style_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'style_tooltip_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--tooltip-text-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'style_tooltip_typography',
				'selector' => '{{WRAPPER}} .raven-hotspot__tooltip',
				'default' => '#54595F',
			]
		);

		$this->add_responsive_control(
			'style_tooltip_align',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--tooltip-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'style_tooltip_heading',
			[
				'label' => esc_html__( 'Box', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'style_tooltip_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}}' => '--tooltip-min-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'style_tooltip_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--tooltip-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_tooltip_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--tooltip-color: {{VALUE}}',
				],
				'default' => '#54595F',
			]
		);

		$this->add_control(
			'style_tooltip_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => '--tooltip-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'style_tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .raven-hotspot__tooltip',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$is_tooltip_direction_animation = 'raven-hotspot--slide-direction' === $settings['tooltip_animation'] || 'raven-hotspot--fade-direction' === $settings['tooltip_animation'];
		$show_tooltip                   = 'none' === $settings['tooltip_trigger'];
		$sequenced_animation_class      = 'yes' === $settings['hotspot_sequenced_animation'] ? 'raven-hotspot--sequenced' : '';

		// Main Image.
		Group_Control_Image_Size::print_attachment_image_html( $settings, 'image', 'image' );

		// Hotspot.
		foreach ( $settings['hotspot'] as $key => $hotspot ) :
			$is_circle           = ! $hotspot['hotspot_label'] && ! $hotspot['hotspot_icon']['value'];
			$is_only_icon        = ! $hotspot['hotspot_label'] && $hotspot['hotspot_icon']['value'];
			$hotspot_position_x  = '%' === $hotspot['hotspot_offset_x']['unit'] ? 'raven-hotspot--position-' . $hotspot['hotspot_horizontal'] : '';
			$hotspot_position_y  = '%' === $hotspot['hotspot_offset_y']['unit'] ? 'raven-hotspot--position-' . $hotspot['hotspot_vertical'] : '';
			$is_hotspot_link     = ! empty( $hotspot['hotspot_link']['url'] );
			$hotspot_element_tag = $is_hotspot_link ? 'a' : 'div';

			// hotspot attributes.
			$hotspot_repeater_setting_key = $this->get_repeater_setting_key( 'hotspot', 'hotspots', $key );
			$this->add_render_attribute(
				$hotspot_repeater_setting_key, [
					'class' => [
						'raven-hotspot',
						'elementor-repeater-item-' . $hotspot['_id'],
						$sequenced_animation_class,
						$hotspot_position_x,
						$hotspot_position_y,
						$is_hotspot_link ? 'raven-hotspot--link' : '',
						( 'click' === $settings['tooltip_trigger'] && $is_hotspot_link ) ? 'raven-hotspot--no-tooltip' : '',
					],
				]
			);

			if ( $is_circle ) {
				$this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'raven-hotspot--circle' );
			}

			if ( $is_only_icon ) {
				$this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'raven-hotspot--icon' );
			}

			if ( $is_hotspot_link ) {
				$this->add_link_attributes( $hotspot_repeater_setting_key, $hotspot['hotspot_link'] );
			}

			// hotspot trigger attributes.
			$trigger_repeater_setting_key = $this->get_repeater_setting_key( 'trigger', 'hotspots', $key );
			$this->add_render_attribute(
				$trigger_repeater_setting_key, [
					'class' => [
						'raven-hotspot__button',
						$settings['hotspot_animation'],
					],
				]
			);

			//direction mask attributes.
			$direction_mask_repeater_setting_key = $this->get_repeater_setting_key( 'raven-hotspot__direction-mask', 'hotspots', $key );
			$this->add_render_attribute(
				$direction_mask_repeater_setting_key, [
					'class' => [
						'raven-hotspot__direction-mask',
						( $is_tooltip_direction_animation ) ? 'raven-hotspot--tooltip-position' : '',
					],
				]
			);

			//tooltip attributes.
			$tooltip_custom_position      = ( $is_tooltip_direction_animation && $hotspot['hotspot_tooltip_position'] && $hotspot['hotspot_position'] ) ? 'raven-hotspot--override-tooltip-animation-from-' . $hotspot['hotspot_position'] : '';
			$tooltip_repeater_setting_key = $this->get_repeater_setting_key( 'tooltip', 'hotspots', $key );
			$this->add_render_attribute(
				$tooltip_repeater_setting_key, [
					'class' => [
						'raven-hotspot__tooltip',
						( $show_tooltip ) ? 'raven-hotspot--show-tooltip' : '',
						( ! $is_tooltip_direction_animation ) ? 'raven-hotspot--tooltip-position' : '',
						( ! $show_tooltip ) ? $settings['tooltip_animation'] : '',
						$tooltip_custom_position,
					],
				]
			);

			echo sprintf( '<%1$s %2$s>', Utils::validate_html_tag( $hotspot_element_tag ), $this->get_render_attribute_string( $hotspot_repeater_setting_key ) );
			?>
			<div <?php $this->print_render_attribute_string( $trigger_repeater_setting_key ); ?>>
				<?php if ( $is_circle ) : ?>
					<div class="raven-hotspot__outer-circle"></div>
					<div class="raven-hotspot__inner-circle"></div>
				<?php else : ?>
					<?php if ( $hotspot['hotspot_icon']['value'] ) : ?>
						<div class="raven-hotspot__icon"><?php Icons_Manager::render_icon( $hotspot['hotspot_icon'] ); ?></div>
					<?php endif; ?>
					<?php if ( $hotspot['hotspot_label'] ) : ?>
						<div class="raven-hotspot__label">
							<?php echo esc_html( $hotspot['hotspot_label'] ); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php // Hotspot Tooltip ?>
			<?php if ( $hotspot['hotspot_tooltip_content'] && ! ( 'click' === $settings['tooltip_trigger'] && $is_hotspot_link ) ) : ?>
				<?php if ( $is_tooltip_direction_animation ) : ?>
					<div <?php $this->print_render_attribute_string( $direction_mask_repeater_setting_key ); ?>>
				<?php endif;
			?>
			<div <?php $this->print_render_attribute_string( $tooltip_repeater_setting_key ); ?>>
				<?php $this->print_unescaped_setting( 'hotspot_tooltip_content', 'hotspot', $key ); ?>
			</div>
				<?php if ( $is_tooltip_direction_animation ) : ?>
					</div>
				<?php endif;
			endif;
			echo sprintf( '</%s>', Utils::validate_html_tag( $hotspot_element_tag ) );
		endforeach;
	}

	/**
	 * Render Hotspot widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  2.9.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		const image = {
			id: settings.image.id,
			url: settings.image.url,
			size: settings.image_size,
			dimension: settings.image_custom_dimension,
			model: view.getEditModel()
		};

		const imageUrl = elementor.imagesManager.getImageUrl( image );

		#>
		<img src="{{ imageUrl }}" title="" alt="">
		<#
		const isTooltipDirectionAnimation = (settings.tooltip_animation==='raven-hotspot--slide-direction' || settings.tooltip_animation==='raven-hotspot--fade-direction' ) ? true : false;
		const showTooltip = ( settings.tooltip_trigger === 'none' );

		_.each( settings.hotspot, ( hotspot, index ) => {
			const iconHTML = elementor.helpers.renderIcon( view, hotspot.hotspot_icon, {}, 'i' , 'object' );

			const isCircle = !hotspot.hotspot_label && !hotspot.hotspot_icon.value;
			const isOnlyIcon = !hotspot.hotspot_label && hotspot.hotspot_icon.value;
			const hotspotPositionX = '%' === hotspot.hotspot_offset_x.unit ? 'raven-hotspot--position-' + hotspot.hotspot_horizontal : '';
			const hotspotPositionY = '%' === hotspot.hotspot_offset_y.unit ? 'raven-hotspot--position-' + hotspot.hotspot_vertical : '';
			const hotspotLink = hotspot.hotspot_link.url;
			const hotspotElementTag = hotspotLink ? 'a': 'div';

			// hotspot attributes
			const hotspotRepeaterSettingKey = view.getRepeaterSettingKey( 'hotspot', 'hotspots', index );

			view.addRenderAttribute( hotspotRepeaterSettingKey, {
				'class' : [
					'raven-hotspot',
					'elementor-repeater-item-' + hotspot._id,
					hotspotPositionX,
					hotspotPositionY,
					hotspotLink ? 'raven-hotspot--link' : '',,
				]
			});

			if ( isCircle ) {
				view.addRenderAttribute( hotspotRepeaterSettingKey, 'class', 'raven-hotspot--circle' );
			}

			if ( isOnlyIcon ) {
				view.addRenderAttribute( hotspotRepeaterSettingKey, 'class', 'raven-hotspot--icon' );
			}

			// hotspot trigger attributes
			const triggerRepeaterSettingKey = view.getRepeaterSettingKey( 'trigger', 'hotspots', index );

			view.addRenderAttribute(triggerRepeaterSettingKey, {
				'class' : [
					'raven-hotspot__button',
					settings.hotspot_animation,
					//'hotspot-trigger-' + hotspot.hotspot_icon_position
				]
			});

			//direction mask attributes
			const directionMaskRepeaterSettingKey = view.getRepeaterSettingKey( 'raven-hotspot__direction-mask', 'hotspots', index );

			view.addRenderAttribute(directionMaskRepeaterSettingKey, {
				'class' : [
					'raven-hotspot__direction-mask',
					( isTooltipDirectionAnimation ) ? 'raven-hotspot--tooltip-position' : ''
				]
			});

			//tooltip attributes
			const tooltipCustomPosition = ( isTooltipDirectionAnimation && hotspot.hotspot_tooltip_position && hotspot.hotspot_position ) ? 'raven-hotspot--override-tooltip-animation-from-' + hotspot.hotspot_position : '';
			const tooltipRepeaterSettingKey = view.getRepeaterSettingKey('tooltip', 'hotspots', index);

			view.addRenderAttribute( tooltipRepeaterSettingKey, {
				'class': [
					'raven-hotspot__tooltip',
					( showTooltip ) ? 'raven-hotspot--show-tooltip' : '',
					( !isTooltipDirectionAnimation ) ? 'raven-hotspot--tooltip-position' : '',
					( !showTooltip ) ? settings.tooltip_animation : '',
					tooltipCustomPosition
				],
			});

			#>
			<{{{ hotspotElementTag }}} {{{ view.getRenderAttributeString( hotspotRepeaterSettingKey ) }}}>

					<?php // Hotspot Trigger ?>
					<div {{{ view.getRenderAttributeString( triggerRepeaterSettingKey ) }}}>
						<# if ( isCircle ) { #>
							<div class="raven-hotspot__outer-circle"></div>
							<div class="raven-hotspot__inner-circle"></div>
						<# } else { #>
							<# if (hotspot.hotspot_icon.value){ #>
								<div class="raven-hotspot__icon">{{{ iconHTML.value }}}</div>
							<# } #>

							<# if ( hotspot.hotspot_label ){ #>
								<div class="raven-hotspot__label">{{{ hotspot.hotspot_label }}}</div>
							<# } #>
						<# } #>
					</div>

					<?php // Hotspot Tooltip ?>
					<# if( hotspot.hotspot_tooltip_content && ! ( 'click' === settings.tooltip_trigger && hotspotLink ) ){ #>
						<# if( isTooltipDirectionAnimation ){ #>
						<div {{{ view.getRenderAttributeString( directionMaskRepeaterSettingKey ) }}}>
							<# } #>
							<div {{{ view.getRenderAttributeString( tooltipRepeaterSettingKey ) }}}>
								{{{ hotspot.hotspot_tooltip_content }}}
							</div>
							<# if( isTooltipDirectionAnimation ){ #>
						</div>
						<# } #>
					<# } #>

			</{{{ hotspotElementTag }}}>
		<# }); #>
		<?php
	}

}
