<?php

namespace JupiterX_Core\Raven\Modules\Pricing_Table\Classes;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use JupiterX_Core\Raven\Modules\Pricing_Table\Widgets\Pricing_Table;

defined( 'ABSPATH' ) || die();

class Controls {
	/**
	 * @var Pricing_Table
	 */
	protected $widget;

	/**
	 * Constructor of control class get Price_table widget instance.
	 *
	 * @param $widget
	 */
	public function __construct( $widget ) {
		$this->widget = $widget;
	}

	/**
	 * Controls for header section in content tab.
	 *
	 * @return void
	 */
	public function header_controls_section() {
		$this->widget->start_controls_section(
			'section_header',
			[
				'label' => esc_html__( 'Header', 'jupiterx-core' ),
			]
		);

		$this->widget->add_control(
			'heading',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Enter your title', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->add_control(
			'sub_heading',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Enter your description', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->add_control(
			'heading_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'h3',
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Controls for pricing section in content tab.
	 *
	 * @return void
	 */
	public function pricing_controls_section() {
		$this->widget->start_controls_section(
			'section_pricing',
			[
				'label' => esc_html__( 'Pricing', 'jupiterx-core' ),
			]
		);

		$this->widget->add_control(
			'currency_symbol',
			[
				'label' => esc_html__( 'Currency Symbol', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'dollar' => '&#36; ' . _x( 'Dollar', 'Currency', 'jupiterx-core' ),
					'euro' => '&#128; ' . _x( 'Euro', 'Currency', 'jupiterx-core' ),
					'baht' => '&#3647; ' . _x( 'Baht', 'Currency', 'jupiterx-core' ),
					'franc' => '&#8355; ' . _x( 'Franc', 'Currency', 'jupiterx-core' ),
					'guilder' => '&fnof; ' . _x( 'Guilder', 'Currency', 'jupiterx-core' ),
					'krona' => 'kr ' . _x( 'Krona', 'Currency', 'jupiterx-core' ),
					'lira' => '&#8356; ' . _x( 'Lira', 'Currency', 'jupiterx-core' ),
					'peseta' => '&#8359 ' . _x( 'Peseta', 'Currency', 'jupiterx-core' ),
					'peso' => '&#8369; ' . _x( 'Peso', 'Currency', 'jupiterx-core' ),
					'pound' => '&#163; ' . _x( 'Pound Sterling', 'Currency', 'jupiterx-core' ),
					'real' => 'R$ ' . _x( 'Real', 'Currency', 'jupiterx-core' ),
					'ruble' => '&#8381; ' . _x( 'Ruble', 'Currency', 'jupiterx-core' ),
					'rupee' => '&#8360; ' . _x( 'Rupee', 'Currency', 'jupiterx-core' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency', 'jupiterx-core' ),
					'shekel' => '&#8362; ' . _x( 'Shekel', 'Currency', 'jupiterx-core' ),
					'yen' => '&#165; ' . _x( 'Yen/Yuan', 'Currency', 'jupiterx-core' ),
					'won' => '&#8361; ' . _x( 'Won', 'Currency', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
				'default' => 'dollar',
			]
		);

		$this->widget->add_control(
			'currency_symbol_custom',
			[
				'label' => esc_html__( 'Custom Symbol', 'jupiterx-core' ),
				'type' => 'text',
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->widget->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'type' => 'text',
				'default' => '39.99',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->add_control(
			'currency_format',
			[
				'label' => esc_html__( 'Currency Format', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => '1,234.56 (Default)',
					',' => '1.234,56',
				],
			]
		);

		$this->widget->add_control(
			'sale',
			[
				'label' => esc_html__( 'Sale', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'default' => '',
			]
		);

		$this->widget->add_control(
			'original_price',
			[
				'label' => esc_html__( 'Original Price', 'jupiterx-core' ),
				'type' => 'number',
				'default' => '59',
				'condition' => [
					'sale' => 'yes',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->add_control(
			'period',
			[
				'label' => esc_html__( 'Period', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Monthly', 'jupiterx-core' ),
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Controls for features section in content tab.
	 *
	 * @return void
	 */
	public function features_controls_section() {
		$this->widget->start_controls_section(
			'section_features',
			[
				'label' => esc_html__( 'Features', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			[
				'label' => esc_html__( 'Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'List Item', 'jupiterx-core' ),
			]
		);

		$default_icon = [
			'value' => 'far fa-check-circle',
			'library' => 'fa-regular',
		];

		$repeater->add_control(
			'selected_item_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'item_icon',
				'default' => $default_icon,
			]
		);

		$repeater->add_control(
			'item_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}} !important',
				],
			]
		);

		$this->widget->add_control(
			'features_list',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_text' => esc_html__( 'List Item #1', 'jupiterx-core' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text' => esc_html__( 'List Item #2', 'jupiterx-core' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text' => esc_html__( 'List Item #3', 'jupiterx-core' ),
						'selected_item_icon' => $default_icon,
					],
				],
				'title_field' => '{{{ item_text }}}',
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Controls for footer section in content tab.
	 *
	 * @return void
	 */
	public function footer_controls_section() {
		$this->widget->start_controls_section(
			'section_footer',
			[
				'label' => esc_html__( 'Footer', 'jupiterx-core' ),
			]
		);

		$this->widget->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Click Here', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => esc_html__( 'https://your-link.com', 'jupiterx-core' ),
				'default' => [
					'url' => '#',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->add_control(
			'footer_additional_info',
			[
				'label' => esc_html__( 'Additional Info', 'jupiterx-core' ),
				'type' => 'textarea',
				'default' => esc_html__( 'This is text element', 'jupiterx-core' ),
				'rows' => 3,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Controls for ribbon section in content tab.
	 *
	 * @return void
	 */
	public function ribbon_controls_section() {
		$this->widget->start_controls_section(
			'section_ribbon',
			[
				'label' => esc_html__( 'Ribbon', 'jupiterx-core' ),
			]
		);

		$this->widget->add_control(
			'show_ribbon',
			[
				'label' => esc_html__( 'Show', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->widget->add_control(
			'ribbon_title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Popular', 'jupiterx-core' ),
				'condition' => [
					'show_ribbon' => 'yes',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->add_control(
			'ribbon_horizontal_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'choose',
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
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Controls for header section in style tab.
	 *
	 * @return void
	 */
	public function header_style_controls_section() {
		$this->widget->start_controls_section(
			'section_header_style',
			[
				'label' => esc_html__( 'Header', 'jupiterx-core' ),
				'tab' => 'style',
				'show_label' => false,
			]
		);

		$this->widget->add_control(
			'header_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table .raven-pricing-table__header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->widget->add_responsive_control(
			'header_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_control(
			'heading_heading_style',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->widget->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .raven-pricing-table__heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->widget->add_control(
			'heading_sub_heading_style',
			[
				'label' => esc_html__( 'Sub Title', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->widget->add_control(
			'sub_heading_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__subheading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'sub_heading_typography',
				'selector' => '{{WRAPPER}} .raven-pricing-table__subheading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Controls for pricing section in style tab.
	 *
	 * @return void
	 */
	public function pricing_style_controls_section() {
		$this->widget->start_controls_section(
			'section_pricing_element_style',
			[
				'label' => esc_html__( 'Pricing', 'jupiterx-core' ),
				'tab' => 'style',
				'show_label' => false,
			]
		);

		$this->widget->add_control(
			'pricing_element_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__price' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->widget->add_responsive_control(
			'pricing_element_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__currency' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-pricing-table__integer-part' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-pricing-table__fractional-part' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .raven-pricing-table .raven-pricing-table__price',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->widget->add_control(
			'heading_currency_style',
			[
				'label' => esc_html__( 'Currency Symbol', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->widget->add_control(
			'currency_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__price > .raven-pricing-table__currency' => 'font-size: calc({{SIZE}}em/100)',
				],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->widget->add_control(
			'currency_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => esc_html__( 'Before', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => esc_html__( 'After', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
			]
		);

		$this->widget->add_control(
			'currency_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__currency' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->fractional_and_period_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Fractional and period controls.
	 *
	 * @return void
	 */
	public function fractional_and_period_controls() {
		$this->widget->add_control(
			'fractional_part_style',
			[
				'label' => esc_html__( 'Fractional Part', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->widget->add_control(
			'fractional-part_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__fractional-part' => 'font-size: calc({{SIZE}}em/100)',
				],
			]
		);

		$this->widget->add_control(
			'fractional_part_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__after-price' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->widget->add_control(
			'heading_original_price_style',
			[
				'label' => esc_html__( 'Original Price', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->widget->add_control(
			'original_price_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__original-price' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-pricing-table__original-price .raven-pricing-table__currency' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'original_price_typography',
				'selector' => '{{WRAPPER}} .raven-pricing-table__original-price',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->widget->add_control(
			'original_price_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'default' => 'bottom',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__original-price' => 'align-self: {{VALUE}}',
				],
				'condition' => [
					'sale' => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->widget->add_control(
			'heading_period_style',
			[
				'label' => esc_html__( 'Period', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->widget->add_control(
			'period_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__period' => 'color: {{VALUE}}',
				],
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'period_typography',
				'selector' => '{{WRAPPER}} .raven-pricing-table__period',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->widget->add_control(
			'period_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => false,
				'options' => [
					'below' => esc_html__( 'Below', 'jupiterx-core' ),
					'beside' => esc_html__( 'Beside', 'jupiterx-core' ),
				],
				'default' => 'below',
				'condition' => [
					'period!' => '',
				],
			]
		);
	}

	/**
	 * Controls for features section in style tab.
	 *
	 * @return void
	 */
	public function features_style_controls_section() {
		$this->widget->start_controls_section(
			'section_features_list_style',
			[
				'label' => esc_html__( 'Features', 'jupiterx-core' ),
				'tab' => 'style',
				'show_label' => false,
			]
		);

		$this->widget->add_control(
			'features_list_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->widget->add_responsive_control(
			'features_list_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_control(
			'features_list_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-pricing-table__features-list svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'features_list_typography',
				'selector' => '{{WRAPPER}} .raven-pricing-table__features-list li',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->widget->add_control(
			'features_list_alignment',
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
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->widget->add_responsive_control(
			'item_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'%' => [
						'min' => 25,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__feature-inner' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				],
			]
		);

		$this->widget->add_control(
			'list_divider',
			[
				'label' => esc_html__( 'Divider', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->widget->add_control(
			'divider_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'solid' => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
				],
				'default' => 'solid',
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list li:before' => 'border-top-style: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ddd',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list li:before' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'divider_weight',
			[
				'label' => esc_html__( 'Weight', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list li:before' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_control(
			'divider_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list li:before' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				],
			]
		);

		$this->widget->add_control(
			'divider_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__features-list li:before' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Controls for footer section in style tab.
	 *
	 * @return void
	 */
	public function footer_style_controls_section() {
		$this->widget->start_controls_section(
			'section_footer_style',
			[
				'label' => esc_html__( 'Footer', 'jupiterx-core' ),
				'tab' => 'style',
				'show_label' => false,
			]
		);

		$this->widget->add_control(
			'footer_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__footer' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->widget->add_responsive_control(
			'footer_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_control(
			'heading_footer_button',
			[
				'label' => esc_html__( 'Button', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->widget->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'md',
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'jupiterx-core' ),
					'sm' => esc_html__( 'Small', 'jupiterx-core' ),
					'md' => esc_html__( 'Medium', 'jupiterx-core' ),
					'lg' => esc_html__( 'Large', 'jupiterx-core' ),
					'xl' => esc_html__( 'Extra Large', 'jupiterx-core' ),
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->widget->start_controls_tabs( 'tabs_button_style' );

		$this->widget->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->widget->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .raven-pricing-table__button',
			]
		);

		$this->widget->add_group_control(
			'background',
			[
				'name' => 'button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-pricing-table__button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);

		$this->widget->add_group_control(
			'border',
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .raven-pricing-table__button',
				'separator' => 'before',
			]
		);

		$this->widget->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->end_controls_tab();

		$this->widget->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->widget->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_group_control(
			'background',
			[
				'name' => 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-pricing-table__button:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->widget->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'jupiterx-core' ),
				'type' => 'hover_animation',
			]
		);

		$this->widget->end_controls_tab();

		$this->widget->end_controls_tabs();

		$this->widget->add_control(
			'heading_additional_info',
			[
				'label' => esc_html__( 'Additional Info', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->widget->add_control(
			'additional_info_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__additional_info' => 'color: {{VALUE}}',
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'additional_info_typography',
				'selector' => '{{WRAPPER}} .raven-pricing-table__additional_info',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->widget->add_control(
			'additional_info_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 30,
					'bottom' => 0,
					'left' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__additional_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'footer_additional_info!' => '',
				],
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Controls for ribbon section in style tab.
	 *
	 * @return void
	 */
	public function ribbon_style_controls_section() {
		$this->widget->start_controls_section(
			'section_ribbon_style',
			[
				'label' => esc_html__( 'Ribbon', 'jupiterx-core' ),
				'tab' => 'style',
				'show_label' => false,
				'condition' => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->widget->add_control(
			'ribbon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__ribbon-inner' => 'background-color: {{VALUE}}',
				],
			]
		);

		$ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

		$this->widget->add_responsive_control(
			'ribbon_distance',
			[
				'label' => esc_html__( 'Distance', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
				],
			]
		);

		$this->widget->add_control(
			'ribbon_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-pricing-table__ribbon-inner' => 'color: {{VALUE}}',
				],
			]
		);

		$this->widget->add_group_control(
			'typography',
			[
				'name' => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .raven-pricing-table__ribbon-inner',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->widget->add_group_control(
			'box-shadow',
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .raven-pricing-table__ribbon-inner',
			]
		);

		$this->widget->end_controls_section();
	}
}
