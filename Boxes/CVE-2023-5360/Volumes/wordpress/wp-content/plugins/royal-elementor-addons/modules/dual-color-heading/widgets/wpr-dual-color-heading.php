<?php

namespace WprAddons\Modules\DualColorHeading\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes\Color;
use Elementor\Icons_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Dual_Color_Heading extends Widget_Base {

	public function get_name() {
		return 'wpr-dual-color-heading';
	}

	public function get_title() {
		return esc_html__('Dual Color Heading', 'wpr-addons');
	}
	public function get_icon() {
		return 'wpr-icon eicon-heading';
	}

	public function get_categories() {
		return ['wpr-widgets'];
	}

	public function get_keywords() {
		return ['royal', 'Dual Color Heading'];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Settings', 'wpr-addons'),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'dual_heading_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2'
			]
		);

		$this->add_control(
			'content_style',
			[
				'label' => esc_html__('Select Layout', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon-top',
				'options' => [
					'default'  => esc_html__('Default', 'wpr-addons'),
					'icon-top'  => esc_html__('Icon Top', 'wpr-addons'),
					'desc-top'  => esc_html__('Desccription Top', 'wpr-addons'),
					'icon-and-desc-top'  => esc_html__('Heading Bottom', 'wpr-addons'),
				],
				'prefix_class' => 'wpr-dual-heading-',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __('Alignment', 'wpr-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'wpr-addons'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'wpr-addons'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'wpr-addons'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-wrap' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'primary_heading',
			[
				'label'   => __('Primary Heading', 'wpr-addons'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Dual Color', 'wpr-addons'),
				'separator' => 'before',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'secondary_heading',
			[
				'label'   => __('Secondary Heading', 'wpr-addons'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Heading', 'wpr-addons'),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => __('Show Description', 'wpr-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'wpr-addons'),
				'label_off' => __('Hide', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => __('', 'wpr-addons'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __('Description text or Sub Heading', 'wpr-addons'),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_description' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label' => __('Show Icon', 'wpr-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'wpr-addons'),
				'label_off' => __('Hide', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'feature_list_icon',
			[
				'label' => esc_html__('Select Icon', 'wpr-addons'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-rocket',
					'library' => 'solid',
				],
				'condition' => [
					'show_icon' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		$this->start_controls_section(
			'primary_heading_styles',
			[
				'label' => esc_html__('Primary Heading', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'primary_heading_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-dual-title .first'
			]
		);

		$this->add_control(
			'primary_heading_color',
			[
				'label' => __('Text Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#7B7B7B',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'primary_heading_border_color',
			[
				'label' => __('Border Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'primary_heading_typography',
				'label' => __('Typography', 'wpr-addons'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-dual-title .first',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '300',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '32',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'primary_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'primary_heading_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'primary_heading_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'primary_heading_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'primary_heading_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'feature_list_title_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title-wrap'  => 'margin-bottom: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'feature_list_title_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first'  => 'margin-right: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'secondary_heading_styles',
			[
				'label' => esc_html__('Secondary Heading', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'secondary_heading_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-dual-title .second'
			]
		);

		$this->add_control(
			'secondary_heading_color',
			[
				'label' => __('Text Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#9E5BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'secondary_heading_border_color',
			[
				'label' => __('Border Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'secondary_heading_typography',
				'label' => __('Typography', 'wpr-addons'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-dual-title .second',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '32',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'secondary_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'secondary_heading_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'secondary_heading_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'secondary_heading_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'secondary_heading_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'general_styles_description',
			[
				'label' => esc_html__('Description', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_description' => 'yes'
				]
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#989898',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __('Typography', 'wpr-addons'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-dual-heading-description',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'feature_list_description_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-description'  => 'margin-bottom: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_styles_icon',
			[
				'label' => esc_html__('Icon', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Size', 'wpr-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 35,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap'  => 'margin-bottom: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes('title', 'none');
		$this->add_inline_editing_attributes('description', 'basic');
		$this->add_inline_editing_attributes('content', 'advanced');

        ?>
			<div class="wpr-dual-heading-wrap">
				<div class="wpr-dual-title-wrap">
					<<?php echo esc_attr($settings['dual_heading_tag']); ?> class="wpr-dual-title">
					<?php if (!empty($settings['primary_heading'])) : ?>
						<span class="first"><?php echo esc_html($settings['primary_heading']); ?></span>
					<?php endif; ?>
					
					<?php if (!empty($settings['secondary_heading'])) : ?>
						<span class="second"><?php echo esc_html($settings['secondary_heading']); ?></span>
					<?php endif; ?>
					</<?php echo esc_attr($settings['dual_heading_tag']); ?>>
				</div>
				
				<?php if ('yes' == $settings['show_description']) { ?>
					<div class="wpr-dual-heading-description" <?php echo $this->get_render_attribute_string('description'); ?>><?php echo esc_html($settings['description']); ?></div>
				<?php } ?>

				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="wpr-dual-heading-icon-wrap">
						<?php \Elementor\Icons_Manager::render_icon($settings['feature_list_icon'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>

			</div>
		<?php
	}
}
