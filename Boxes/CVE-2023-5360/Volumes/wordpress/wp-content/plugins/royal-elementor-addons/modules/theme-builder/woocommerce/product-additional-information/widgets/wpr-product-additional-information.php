<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductAdditionalInformation\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_AdditionalInformation extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-additional-information';
	}

	public function get_title() {
		return esc_html__( 'Product Additional Information', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-info';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-additional-information', 'product', 'additional information', 'information' ];
	}	
	
	public function get_script_depends() {
		return [ 'wc-single-product' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'additional_info_syles',
			[
				'label' => esc_html__('Additional Information', 'wpr-addons'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'additional_info_label',
			[
				'label'     => esc_html__('Attribute Name', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'additional_info_label_color',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table th' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_info_label_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_info_label_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table tr:nth-child(even) th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'additional_info_th_typography',
				'label'          => esc_html__('Typography', 'wpr-addons'),
				'selector'       => '{{WRAPPER}} .wpr-product-additional-information tr :is(th)',
				'exclude'        => ['font_family', 'text_transform', 'text_decoration'],
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'wpr-addons'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					],
					'font_weight'    => [
						'default' => '400',
					],
					'text_transform' => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'wpr-addons'),
						'default' => [
							'size' => '19',
							'unit' => 'px',
						],
						'size_units' => ['px'],
						'tablet_default' => [
							'unit' => 'px',
						],
						'mobile_default' => [
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'label'      => esc_html__('Letter Spacing (px)', 'wpr-addons'),
						'size_units' => ['px'],
					],
				],
			]
		);

		$this->add_control(
			'additional_info_th_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table th' => 'text-align: {{VALUE}}',
				],
				'default' => 'left',
			]
		);

		$this->add_responsive_control(
			'additional_info_label_width',
			[
				'label'      => esc_html__('Width', 'wpr-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 25,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpr-product-additional-information table th' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'additional_info_value_heading',
			[
				'label'     => esc_html__('Attribute Value', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
                'separator'  => 'before',
			]
		);

		$this->add_control(
			'additional_information_value_color',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#101010',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table td p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_information_value_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fdfdfd',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_information_value_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'additional_info_td_typography',
				'label'          => esc_html__('Typography', 'wpr-addons'),
				'selector'       => '{{WRAPPER}} .wpr-product-additional-information tr :is(td, p)',
				'exclude'        => ['font_family', 'text_transform', 'text_decoration'],
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'wpr-addons'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					],
					'font_weight'    => [
						'default' => '400',
					],
					'text_transform' => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'wpr-addons'),
						'default' => [
							'size' => '19',
							'unit' => 'px',
						],
						'size_units' => ['px'],
						'tablet_default' => [
							'unit' => 'px',
						],
						'mobile_default' => [
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'label'      => esc_html__('Letter Spacing (px)', 'wpr-addons'),
						'size_units' => ['px'],
					],
				],
			]
		);

		$this->add_responsive_control(
			'additional_info_padding',
			[
				'label'      => esc_html__('Padding', 'wpr-addons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '15',
					'right'    => '35',
					'bottom'   => '15',
					'left'     => '35',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'separator' => 'before',
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .wpr-product-additional-information table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-additional-information table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'additional_info_divider_color',
			[
				'label'     => esc_html__('Divider (Border) Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2f2f2',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table td' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-product-additional-information table th' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'additional_info_border_width',
			[
				'label' => esc_html__( 'Divider (Border) Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-additional-information table tr:not(:last-child) td' => 'border-bottom-width: {{SIZE}}px; border-bottom-style: solid;',
					'{{WRAPPER}} .wpr-product-additional-information table tr:not(:last-child) th' => 'border-bottom-width: {{SIZE}}px; border-bottom-style: solid;',
					'{{WRAPPER}}.wpr-add-info-borders-yes .wpr-product-additional-information table td' => 'border-width: {{SIZE}}px; border-style: solid;',
					'{{WRAPPER}}.wpr-add-info-borders-yes .wpr-product-additional-information table th' => 'border-width: {{SIZE}}px; border-style: solid;',
				],
			]
		);

		$this->add_control(
			'additional_info_show_borders',
			[
				'label' => esc_html__( 'Show Table Borders', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'wpr-add-info-borders-'
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, Controls_Manager::TAB_STYLE );

    }

    protected function render() {
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        $heading = apply_filters( 'woocommerce_product_additional_information_heading', esc_html__( 'Additional information', 'wpr-addons' ) );

        echo '<div class="wpr-product-additional-information">';
		    do_action('woocommerce_product_additional_information', $product);
        echo '</div>';   
    }
}