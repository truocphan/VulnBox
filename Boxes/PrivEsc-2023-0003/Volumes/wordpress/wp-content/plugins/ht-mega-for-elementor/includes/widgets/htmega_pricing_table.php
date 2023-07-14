<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Pricing_Table extends Widget_Base
{

    public function get_name()
    {
        return 'htmega-pricing-table-addons';
    }

    public function get_title()
    {
        return __('Pricing Table', 'htmega-addons');
    }

    public function get_icon()
    {
        return 'htmega-icon eicon-price-table';
    }

    public function get_categories()
    {
        return ['htmega-addons'];
    }

    public function get_keywords() {
        return ['price table','table', 'pricing table', 'htmega', 'ht mega'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/general-widgets/pricing-list-view-widget/';
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    protected function register_controls()
    {

        // Layout Fields tab start
        $this->start_controls_section(
            'htmega_pricing_layout',
            [
                'label' => __('Layout', 'htmega-addons'),
            ]
        );
        $this->add_control(
            'htmega_pricing_style',
            [
                'label' => __('Style', 'htmega-addons'),
                'type' => 'htmega-preset-select',
                'default' => '1',
                'options' => [
                    '1'   => __('Style One', 'htmega-addons'),
                    '2'   => __('Style Two', 'htmega-addons'),
                    '3'   => __('Style Three', 'htmega-addons'),
                    '4'   => __('Style Four', 'htmega-addons'),
                    '5'   => __('Style Five', 'htmega-addons'),
                    '6'   => __('Style Six', 'htmega-addons'),
                    '7'   => __('Style Seven', 'htmega-addons'),
                    '8'   => __('Style Eight', 'htmega-addons'),
                    '9'   => __('Style Nine', 'htmega-addons'),
                ],
            ]
        );

        $this->add_control(
            'htmega_show_badge',
            [
                'label' => __('Show Badge', 'htmega-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'htmega-addons'),
                'label_off' => __('Hide', 'htmega-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'htmega_badge_position',
            [
                'label' => __('Position', 'htmega-addons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'htmega-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'htmega-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'default' => 'left',
                'condition' => [
                    'htmega_show_badge' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'htmega_badge_position_left',
            [
                'label' => __('Left', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-htmega-pricing-table-addons span.htmega-price-badge-position-left' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_badge_position' => 'left',
                    'htmega_show_badge' => 'yes'
                ],

            ]
        );

        $this->add_responsive_control(
            'htmega_badge_position_right',
            [
                'label' => __('Right', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-htmega-pricing-table-addons span.htmega-price-badge-position-right' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_badge_position' => 'right',
                    'htmega_show_badge' => 'yes'
                ],

            ]
        );

        $this->add_responsive_control(
            'htmega_badge_position_top',
            [
                'label' => __('Top', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-htmega-pricing-table-addons span.htmega-price-badge' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_show_badge' => 'yes'
                ],

            ]
        );

        $this->add_control(
            'pricing_badge_title',
            [
                'label' => __('Badge Text', 'htmega-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('New', 'htmega-addons'),
                'default' => __('New', 'htmega-addons'),
                'title' => __('Enter your service title', 'htmega-addons'),
                'dynamic' => [
                    'active' => true
                ],
                'condition' => [
                    'htmega_show_badge' => 'yes'
                ]
            ]
        );        

        $this->end_controls_section(); // Layout Fields tab end

        // Header Fields tab start
        $this->start_controls_section(
            'htmega_pricing_header',
            [
                'label' => __('Header', 'htmega-addons'),
            ]
        );

        $this->add_control(
            'htmega_pricing_header_top',
            [
                'label' => esc_html__('Icon Type','htmega-addons'),
                'type' =>Controls_Manager::CHOOSE,
                'options' =>[
                    'img' =>[
                        'title' =>__('Image','htmega-addons'),
                        'icon' =>'eicon-image-bold',
                    ],
                    'icon' =>[
                        'title' =>__('Icon','htmega-addons'),
                        'icon' =>'eicon-info-circle',
                    ]
                ],
                'condition' => [
                    'htmega_pricing_style' => '9',
                ]
            ]
        );

        $this->add_control(
            'titleimage',
            [
                'label' => __('Image','htmega-addons'),
                'type'=>Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'htmega_pricing_header_top' => 'img',
                    'htmega_pricing_style' => '9',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'iconimagesize',
                'default' => 'large',
                'separator' => 'none',
                'condition' => [
                    'htmega_pricing_header_top' => 'img',
                    'htmega_pricing_style' => '9',
                ]
            ]
        );

        $this->add_control(
            'titleicon',
            [
                'label' =>__('Icon','htmega-addons'),
                'type'=>Controls_Manager::ICONS,
                'default' => [
                    'value'=>'fas fa-pencil-alt',
                    'library'=>'solid',
                ],
                'condition' => [
                    'htmega_pricing_header_top' => 'icon',
                    'htmega_pricing_style' => '9',
                ]
            ]
        );

        $this->add_control(
            'pricing_title',
            [
                'label' => __('Title', 'htmega-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __('Standard', 'htmega-addons'),
                'default' => __('Standard', 'htmega-addons'),
                'title' => __('Enter your pricing title', 'htmega-addons'),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'pricing_plan_title',
            [
                'label' => __('Price Plan Name', 'htmega-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __('SIMPLE PLAN', 'htmega-addons'),
                'title' => __('Enter your pricing plan name here', 'htmega-addons'),
                'condition' => [
                    'htmega_pricing_style!' => array('1','7','9'),
                ],
            ]
        );

        $this->add_control(
            'htmega_ribon_pricing_table',
            [
                'label'        => esc_html__('Ribon', 'htmega-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pricing_table_ribon_background',
                'label' => __('Ribon Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .htmega-pricing-panel',
                'condition' => [
                    'htmega_ribon_pricing_table' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'pricing_table_ribon_image',
            [
                'label' => __('Ribon image', 'htmega-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => HTMEGA_ADDONS_PL_URL . '/assets/images/pricing/pricing-ribon.png',
                ],
                'condition' => [
                    'htmega_ribon_pricing_table' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-ribon::before' => 'content: url( {{URL}} )',
                ]
            ]
        );


        $this->add_control(
            'htmega_header_icon_type',
            [
                'label' => __('Image or Icon', 'htmega-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'img' => [
                        'title' => __('Image', 'htmega-addons'),
                        'icon' => 'eicon-image-bold',
                    ],
                    'icon' => [
                        'title' => __('Icon', 'htmega-addons'),
                        'icon' => 'eicon-info-circle',
                    ]
                ],
                'default' => 'img',
                'condition' => [
                    'htmega_pricing_style' => '2'
                ]
            ]
        );

        $this->add_control(
            'headerimage',
            [
                'label' => __('Image', 'htmega-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],

                'condition' => [
                    'htmega_pricing_style' => '2',
                    'htmega_header_icon_type' => 'img',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'headerimagesize',
                'default' => 'large',
                'separator' => 'none',
                'condition' => [
                    'htmega_pricing_style' => '2',
                    'htmega_header_icon_type' => 'img',
                ]
            ]
        );

        $this->add_control(
            'headericon',
            [
                'label' => esc_html__('Icon', 'htmega-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-pencil',
                    'library' => 'solid',
                ],
                'condition' => [
                    'htmega_pricing_style' => '2',
                    'htmega_header_icon_type' => 'icon',
                ]
            ]
        );

        $this->add_responsive_control(
            'htmega_header_alignment_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .htmega-pricing-header-align' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'htmega_pricing_style!' => '1',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'htmega_header_alignment',
            [
                'label' => __('Alignment', 'htmega-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'htmega-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'htmega-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'htmega-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .htmega-pricing-header-align' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'htmega_pricing_style!' => '1',
                ],
            ]
        );

        $this->end_controls_section(); // Header Fields tab end

        // Pricing Fields tab start
        $this->start_controls_section(
            'htmega_pricing_price',
            [
                'label' => __('Pricing', 'htmega-addons'),
            ]
        );
        $this->add_control(
            'htmega_currency_symbol',
            [
                'label'   => __('Currency Symbol', 'htmega-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    ''             => esc_html__('None', 'htmega-addons'),
                    'dollar'       => '&#36; ' . __('Dollar', 'htmega-addons'),
                    'euro'         => '&#128; ' . __('Euro', 'htmega-addons'),
                    'baht'         => '&#3647; ' . __('Baht', 'htmega-addons'),
                    'franc'        => '&#8355; ' . __('Franc', 'htmega-addons'),
                    'guilder'      => '&fnof; ' . __('Guilder', 'htmega-addons'),
                    'krona'        => 'kr ' . __('Krona', 'htmega-addons'),
                    'lira'         => '&#8356; ' . __('Lira', 'htmega-addons'),
                    'peseta'       => '&#8359 ' . __('Peseta', 'htmega-addons'),
                    'peso'         => '&#8369; ' . __('Peso', 'htmega-addons'),
                    'pound'        => '&#163; ' . __('Pound Sterling', 'htmega-addons'),
                    'real'         => 'R$ ' . __('Real', 'htmega-addons'),
                    'ruble'        => '&#8381; ' . __('Ruble', 'htmega-addons'),
                    'rupee'        => '&#8360; ' . __('Rupee', 'htmega-addons'),
                    'indian_rupee' => '&#8377; ' . __('Rupee (Indian)', 'htmega-addons'),
                    'shekel'       => '&#8362; ' . __('Shekel', 'htmega-addons'),
                    'yen'          => '&#165; ' . __('Yen/Yuan', 'htmega-addons'),
                    'won'          => '&#8361; ' . __('Won', 'htmega-addons'),
                    'custom'       => __('Custom', 'htmega-addons'),
                ],
                'default' => 'dollar',
            ]
        );

        $this->add_control(
            'htmega_currency_symbol_custom',
            [
                'label'     => __('Custom Symbol', 'htmega-addons'),
                'type'      => Controls_Manager::TEXT,
                'condition' => [
                    'htmega_currency_symbol' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'htmega_price',
            [
                'label'   => esc_html__('Price', 'htmega-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => '35.50',
            ]
        );

        $this->add_control(
            'htmega_offer_price',
            [
                'label'        => esc_html__('Offer', 'htmega-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'htmega_original_price',
            [
                'label'     => esc_html__('Original Price', 'htmega-addons'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '49',
                'condition' => [
                    'htmega_offer_price' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'htmega_period',
            [
                'label'   => esc_html__('Period', 'htmega-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Monthly', 'htmega-addons'),
            ]
        );

        $this->add_responsive_control(
            'htmega_price_alignment_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'htmega_pricing_alignment',
            [
                'label' => __('Alignment', 'htmega-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'htmega-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'htmega-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'htmega-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price , {{WRAPPER}} .htmega-pricing-style-1 .htmega-pricing-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section(); // Pricing Fields tab end

        // Features tab start
        $this->start_controls_section(
            'htmega_pricing_features',
            [
                'label' => __('Features', 'htmega-addons'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'htmega_features_title',
            [
                'label'   => esc_html__('Title', 'htmega-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Features Tilte', 'htmega-addons'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'htmega_old_features',
            [
                'label'        => esc_html__('Old Features', 'htmega-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'htmega_features_icon',
            [
                'label'   => esc_html__('Icon', 'htmega-addons'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-angle-double-right',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'htmega_features_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .htmega-pricing-panel {{CURRENT_ITEM}} svg path' => 'fill: {{VALUE}}',
                ],
                'condition' => [
                    'htmega_features_icon[value]!' => '',
                ]
            ]
        );

        $repeater->add_responsive_control(
            'htmega_badge_position_right',
            [
                'label' => __('Icon Size', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 17,
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel {{CURRENT_ITEM}} i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .htmega-pricing-panel {{CURRENT_ITEM}} svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_features_icon[value]!' => '',
                ]

            ]
        );

        $repeater->add_responsive_control(
            'htmega_badge_position_margin',
            [
                'label' => __('Icon Position', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel {{CURRENT_ITEM}} i , {{WRAPPER}} .htmega-pricing-panel {{CURRENT_ITEM}} svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_features_icon[value]!' => '',
                ]

            ]
        );

        $this->add_control(
            'htmega_features_list',
            [
                'type'    => Controls_Manager::REPEATER,
                'fields'  =>  $repeater->get_controls(),
                'prevent_empty' => false,
                'default' => [
                    [
                        'htmega_features_title' => esc_html__('Features Title One', 'htmega-addons'),
                        'htmega_features_icon' => 'fas fa-angle-double-right',
                    ],

                    [
                        'htmega_features_title' => esc_html__('Features Title Two', 'htmega-addons'),
                        'htmega_features_icon' => 'fas fa-angle-double-right',
                    ],

                    [
                        'htmega_features_title' => esc_html__('Features Title Three', 'htmega-addons'),
                        'htmega_features_icon' => 'fas fa-angle-double-right',
                    ],
                ],
                'title_field' => '{{{ htmega_features_title }}}',
            ]
        );

        $this->add_responsive_control(
            'pricing_features_list_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-body ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'htmega_pricing_feature_alignment',
            [
                'label' => __('Alignment', 'htmega-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'htmega-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'htmega-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'htmega-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'htmega-addons'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-body ul' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section(); // Features Fields tab end

        // Footer tab start
        $this->start_controls_section(
            'htmega_pricing_footer',
            [
                'label' => __('Footer', 'htmega-addons'),
            ]
        );

        $this->add_control(
            'htmega_button_text',
            [
                'label'   => esc_html__('Button Text', 'htmega-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Sign Up', 'htmega-addons'),
            ]
        );

        $this->add_control(
            'htmega_button_link',
            [
                'label'       => __('Link', 'htmega-addons'),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section(); // Footer Fields tab end

        // Style tab section start
        $this->start_controls_section(
            'htmega_pricing_style_section',
            [
                'label' => __('Style', 'htmega-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'htmega_heighlight_pricing_table',
            [
                'label'        => esc_html__('High Light Pricing Table', 'htmega-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pricing_table_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .htmega-pricing-panel',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pricing_table_box_shadow',
                'label' => __('Box Shadow', 'htmega-addons'),
                'selector' => '{{WRAPPER}} .htmega-pricing-panel',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pricing_table_border',
                'label' => __('Border', 'htmega-addons'),
                'selector' => '{{WRAPPER}} .htmega-pricing-panel',
            ]
        );

        $this->add_responsive_control(
            'pricing_table_margin',
            [
                'label' => __('Margin', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'pricing_table_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'pricing_table_radius',
            [
                'label' => esc_html__('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_section(); // Style tab section end 

        // Header Top style tab start
        $this->start_controls_section(
            'htmega_header_top_style',
            [
                'label'     => __('Header Top', 'htmega-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_pricing_style' => '9'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'htmega_header_top_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .header-top-image',
                'exclude' =>['image']
            ]
        );
        
        $this->add_control(
            'htmega_header_top_icon_color',
            [
                'label'     => __('Icon Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .header-top-image i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .header-top-image svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'htmega_pricing_header_top' =>'icon'
                ]
            ]
        );
        $this->add_responsive_control(
            'htmega_header_top_icon_fontsize',
            [
                'label' => __( 'Icon Size', 'htmega-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .header-top-image i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .header-top-image svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_pricing_header_top' =>'icon'
                ]
            ]
        );
        $this->add_responsive_control(
            'htmega_header_top_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .header-top-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'htmega_header_topborder',
                'label' => __( 'Border', 'htmega-addons' ),
                'selector' => '{{WRAPPER}} .header-top-image',
            ]
        );
        $this->add_responsive_control(
            'htmega_header_top_radius',
            [
                'label' => esc_html__('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .header-top-image' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );
        $this->end_controls_section(); // Header Top Style section end 

        // Header style tab start
        $this->start_controls_section(
            'htmega_header_style',
            [
                'label'     => __('Header', 'htmega-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pricing_header_border_style_toggle',
            [
                'label' => __('Price Border', 'htmega-addons'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'htmega-addons'),
                'label_on' => __('Custom', 'htmega-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'htmega_pricing_style' => '4',
                ],
            ]
        );

        $this->start_popover();

            $this->add_control(
                'pricing_header_border_background',
                [
                    'label'     => __('Border Color', 'htmega-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .title h2::before' => 'background: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'pricing_header_border_width',
                [
                    'label' => __('Border Width', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .title h2::before' => 'width: {{SIZE}}{{UNIT}};',
                    ],

                ]
            );

            $this->add_responsive_control(
                'pricing_header_border_height',
                [
                    'label' => __('Border Height', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .title h2::before' => 'height: {{SIZE}}{{UNIT}};',
                    ],

                ]
            );

            $this->add_responsive_control(
                'pricing_header_border_position',
                [
                    'label' => __('Border Position Y', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .title h2::before' => 'top: {{SIZE}}{{UNIT}};',
                    ],

                ]
            );

        $this->end_popover();        

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pricing_header_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .htmega-pricing-heading',
            ]
        );

        $this->add_responsive_control(
            'pricing_header_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'pricing_header_margin',
            [
                'label' => __('Margin', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'pricing_price_radius',
            [
                'label' => esc_html__('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_section(); // Header style tab end

        // Header Title style tab start
        $this->start_controls_section(
            'htmega_header_title_style',
            [
                'label'     => __('Header Title', 'htmega-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pricing_title!' => ''
                ]
            ]
        );

        $this->add_control(
            'pricing_header_heading_title',
            [
                'label'     => __('Title', 'htmega-addons'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pricing_header_title_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .title h2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pricing_header_title_typography',
                'selector' => '{{WRAPPER}} .htmega-pricing-heading .title h2',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pricing_header_title_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .htmega-pricing-heading .title h2',
                'condition' => [
                    'htmega_pricing_style' => '1',
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_header_title_margin',
            [
                'label' => esc_html__('Margin', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .title h2 , {{WRAPPER}} .htmega-pricing-style-3 .htmega-pricing-heading .title' => 'margin: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_header_title_padding',
            [
                'label' => esc_html__('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .title h2' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_header_title_radius',
            [
                'label' => esc_html__('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .title h2' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_section(); // Header Price style tab end 

        // Header Price Style tab start
        $this->start_controls_section(
            'htmega_header_price_plan_style',
            [
                'label'     => __('Price Plan', 'htmega-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pricing_plan_title!' => '',
                    'htmega_pricing_style!' => array('1','7','9'),
                ]
            ]
        );

        $this->add_control(
            'pricing_plan_title_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .pricing-plan h1' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'pricing_plan_title!' => ''
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pricing_plan_typography',
                'selector' => '{{WRAPPER}} .htmega-pricing-heading .pricing-plan h1',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pricing_header_plan_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .htmega-pricing-heading .pricing-plan h1',
                'condition' => [
                    'htmega_pricing_style' => array('1'),
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_header_plan_padding',
            [
                'label' => esc_html__('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .pricing-plan h1' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_header_plan_margin',
            [
                'label' => esc_html__('Margin', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .pricing-plan h1' => 'margin: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_header_plan_radius',
            [
                'label' => esc_html__('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .pricing-plan h1' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_section(); // Header Price style tab end

        // Header Price Style tab start
        $this->start_controls_section(
            'htmega_header_price_style',
            [
                'label'     => __('Header Price', 'htmega-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_price!' => ''
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pricing_header_price_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .htmega-pricing-heading .price , {{WRAPPER}} .htmega-pricing-panel .price-label',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pricing_header_price_border',
                'label' => __('Border', 'htmega-addons'),
                'selector' => '{{WRAPPER}} .htmega-pricing-heading .price , {{WRAPPER}} .htmega-pricing-panel .price-label',
            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_width',
            [
                'label' => __('Price Label Width', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 70,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .price , {{WRAPPER}} .htmega-pricing-panel .price-label' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_pricing_style' => ['3','8'],
                ],

            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_position',
            [
                'label' => __('Price Label Position ( Left-Right )', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => -30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .price , {{WRAPPER}} .htmega-pricing-panel .price-label' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_pricing_style' => ['8'],
                ],

            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_padding',
            [
                'label' => esc_html__('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .price , {{WRAPPER}} .htmega-pricing-panel .price-label' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_margin',
            [
                'label' => esc_html__('Margin', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .price , {{WRAPPER}} .htmega-pricing-panel .price-label' => 'margin: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_radius',
            [
                'label' => esc_html__('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-heading .price , {{WRAPPER}} .htmega-pricing-panel .price-label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_control(
            'pricing_header_heading_price_symbol',
            [
                'label'     => __('Price Symbol', 'htmega-addons'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'htmega_currency_symbol!' => ''
                ]
            ]
        );

        $this->add_control(
            'pricing_header_price_symbol_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 sub , {{WRAPPER}} .htmega-pricing-panel .price-label h4 sub' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pricing_header_price_symbol_typography',
                'selector' => '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 sub , {{WRAPPER}} .htmega-pricing-panel .price-label h4 sub',
            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_symbol_position_x',
            [
                'label' => __('Position ( Left-Right )', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 sub , {{WRAPPER}} .htmega-pricing-panel .price-label h4 sub' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_pricing_style!' => ['5'],
                ],

            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_symbol_position_y',
            [
                'label' => __('Position ( Top-Bottom )', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 sub , {{WRAPPER}} .htmega-pricing-panel .price-label h4 sub' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_pricing_style!' => ['5'],
                ],

            ]
        );

        $this->add_control(
            'pricing_header_heading_price',
            [
                'label'     => __('Price', 'htmega-addons'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pricing_header_price_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.pricing_new , {{WRAPPER}} .htmega-pricing-panel .price-label h4 span.pricing_new , {{WRAPPER}} .htmega-pricing-panel .price-label h4' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pricing_header_price_typography',
                'selector' => '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.pricing_new , {{WRAPPER}} .htmega-pricing-panel .price-label h4 span.pricing_new',
            ]
        );

        $this->add_control(
            'pricing_header_heading_price_offer',
            [
                'label'     => __('Offer Price', 'htmega-addons'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'htmega_offer_price' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pricing_header_price_color_offer',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.pricing_old' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'htmega_offer_price' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pricing_header_price_typography_offer',
                'selector' => '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.pricing_old',
                'condition' => [
                    'htmega_offer_price' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'pricing_header_price_offer_space',
            [
                'label' => __( 'Inner Space', 'htmega-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px',],
                'range' => [
                    'px' => [
                        'min' => -300,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .pricing_old' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_offer_price' => 'yes',
                ],
            ]
        );  
        $this->add_control(
            'pricing_header_heading_prce_period',
            [
                'label'     => __('Price Period', 'htmega-addons'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'htmega_period!' => '',
                ],
            ]
        );

        $this->add_control(
            'pricing_header_price_separator_toggle',
            [
                'label' => __('Price Separator Settings', 'htmega-addons'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'htmega-addons'),
                'label_on' => __('Custom', 'htmega-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'htmega_period!' => '',
                ],
            ]
        );

        $this->start_popover();

        $this->add_control(
            'pricing_header_price_separator_period_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.separator , {{WRAPPER}} .htmega-pricing-panel .price-label h4 span.separator' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_period_separator_font_size',
            [
                'label' => __('Separator Size', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.separator , {{WRAPPER}} .htmega-pricing-panel .price-label h4 span.separator' => 'font-size: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'pricing_header_price_period_separator_position',
            [
                'label' => __('Price Separator Position', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.separator , {{WRAPPER}} .htmega-pricing-panel .price-label h4 span.separator' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'pricing_header_price_period_position',
            [
                'label' => __('Separator Position Right', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.period-txt , {{WRAPPER}} .htmega-pricing-panel .price-label h4 span.period-txt' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'htmega_period!' => '',
                ]
            ]
        );
        $this->end_popover();

        $this->add_control(
            'pricing_header_price_period_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.period-txt , {{WRAPPER}} .htmega-pricing-panel .price-label h4 span.period-txt' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'htmega_period!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pricing_header_price_period_typography',
                'selector' => '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-heading .price h4 span.period-txt , {{WRAPPER}} .htmega-pricing-panel .price-label h4 span.period-txt',
                'condition' => [
                    'htmega_period!' => '',
                ]
            ]
        );

        $this->end_controls_section(); // Header Price style tab end


        // Features style tab start
        $this->start_controls_section(
            'htmega_features_style',
            [
                'label'     => __('Features', 'htmega-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pricing_bottom_border_style_toggle',
            [
                'label' => __('Price Border', 'htmega-addons'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'htmega-addons'),
                'label_on' => __('Custom', 'htmega-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'htmega_pricing_style' => '4',
                ],
            ]
        );

        $this->start_popover();

            $this->add_control(
                'pricing_bottom_border_background',
                [
                    'label'     => __('Border Color', 'htmega-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-body::before' => 'background: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'pricing_bottom_border_width',
                [
                    'label' => __('Border Width', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-body::before' => 'width: {{SIZE}}{{UNIT}};',
                    ],

                ]
            );

            $this->add_responsive_control(
                'pricing_bottom_border_height',
                [
                    'label' => __('Border Height', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-body::before' => 'height: {{SIZE}}{{UNIT}};',
                    ],

                ]
            );

            $this->add_responsive_control(
                'pricing_bottom_border_position',
                [
                    'label' => __('Border Position Y', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-body::before' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],

                ]
            );

        $this->end_popover();        

        $this->add_control(
            'pricing_features_area_toggle',
            [
                'label' => __('Price Features Box Area', 'htmega-addons'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __('None', 'htmega-addons'),
                'label_on' => __('Custom', 'htmega-addons'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pricing_features_area_background',
                    'label' => __('Background', 'htmega-addons'),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .htmega-pricing-body',
                ]
            );

            $this->add_responsive_control(
                'pricing_features_area_padding',
                [
                    'label' => __('Padding', 'htmega-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_responsive_control(
                'pricing_features_area_margin',
                [
                    'label' => __('Margin', 'htmega-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );
    
            $this->add_responsive_control(
                'pricing_features_area_border_radius',
                [
                    'label' => __('Border Radius', 'htmega-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-pricing-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

        $this->end_popover();

        $this->add_control(
            'pricing_features_item_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-body ul li' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pricing_features_item_typography',
                'selector' => '{{WRAPPER}} .htmega-pricing-body ul li',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pricing_features_item_border',
                'label' => __('Border', 'htmega-addons'),
                'selector' => '{{WRAPPER}} .htmega-pricing-body ul li',
            ]
        );

        $this->add_responsive_control(
            'pricing_features_item_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-body ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pricing_features_item_margin',
            [
                'label' => __('Margin', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-body ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section(); // Features style tab end


        // Badge style tab start
        $this->start_controls_section(
            'htmega_badge_style',
            [
                'label'     => __('Badge', 'htmega-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'htmega_show_badge' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'htmega_badge_style_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-htmega-pricing-table-addons span.htmega-price-badge' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'htmega_badge_style_typography',
                'selector' => '{{WRAPPER}}.elementor-widget-htmega-pricing-table-addons span.htmega-price-badge',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'htmega_badge_style_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}}.elementor-widget-htmega-pricing-table-addons span.htmega-price-badge',
            ]
        );

        $this->add_responsive_control(
            'htmega_badge_style_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-htmega-pricing-table-addons span.htmega-price-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'htmega_badge_style_border_radius',
            [
                'label' => __('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-htmega-pricing-table-addons span.htmega-price-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section(); // Badge style tab end

        // Footer style tab start
        $this->start_controls_section(
            'htmega_pricing_footer_style',
            [
                'label'     => __('Footer', 'htmega-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('pricing_footer_style_tabs');

        // Pricing Normal tab start
        $this->start_controls_tab(
            'style_pricing_normal_tab',
            [
                'label' => __('Normal', 'htmega-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_pricing_normal_box_shadow',
                'label' => __( 'Box Shadow', 'htmega-addons' ),
                'selector' => '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer a.price_btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pricing_footer_typography',
                'selector' => '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer a.price_btn',
            ]
        );

        $this->add_control(
            'pricing_footer_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-body a.price_btn , {{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-style-4 .htmega-pricing-footer a.price_btn' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pricing_footer_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .htmega-pricing-body a.price_btn,{{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn span,{{WRAPPER}} .htmega-pricing-style-4 .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer a.price_btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pricing_footer_border',
                'label' => __('Border', 'htmega-addons'),
                'selector' => '{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn',
            ]
        );

        $this->add_responsive_control(
            'pricing_footer_padding',
            [
                'label' => __('Padding', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn span , {{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'pricing_footer_margin',
            [
                'label' => __('Margin', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn span ,{{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer , {{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'pricing_footer_radius',
            [
                'label' => esc_html__('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-panel .htmega-pricing-footer a.price_btn , {{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn span , {{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_tab(); // Pricing Normal tab end

        // Pricing Hover tab start
        $this->start_controls_tab(
            'style_pricing_hover_tab',
            [
                'label' => __('Hover', 'htmega-addons'),
            ]
        );

        $this->add_control(
            'pricing_footer_hover_color',
            [
                'label'     => __('Color', 'htmega-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-footer a.price_btn:hover' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pricing_footer_hover_background',
                'label' => __('Background', 'htmega-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .htmega-pricing-footer a.price_btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pricing_footer_hover_border',
                'label' => __('Border', 'htmega-addons'),
                'selector' => '{{WRAPPER}} .htmega-pricing-footer a.price_btn:hover , {{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn:hover',
            ]
        );

        $this->add_responsive_control(
            'pricing_footer_hover_radius',
            [
                'label' => esc_html__('Border Radius', 'htmega-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .htmega-pricing-footer a.price_btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    '{{WRAPPER}} .htmega-pricing-style-5 .htmega-pricing-body a.price_btn span:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_tab(); // Pricing Hover tab end

        $this->end_controls_tabs();

        $this->end_controls_section(); // Footer style tab end

    }

    private function get_currency_symbol($symbol_name)
    {
        $symbols = [
            'dollar'       => '&#36;',
            'baht'         => '&#3647;',
            'euro'         => '&#128;',
            'franc'        => '&#8355;',
            'guilder'      => '&fnof;',
            'indian_rupee' => '&#8377;',
            'krona'        => 'kr',
            'lira'         => '&#8356;',
            'peseta'       => '&#8359',
            'peso'         => '&#8369;',
            'pound'        => '&#163;',
            'real'         => 'R$',
            'ruble'        => '&#8381;',
            'rupee'        => '&#8360;',
            'shekel'       => '&#8362;',
            'won'          => '&#8361;',
            'yen'          => '&#165;',
        ];
        return isset($symbols[$symbol_name]) ? $symbols[$symbol_name] : '';
    }

    protected function render($instance = [])
    {

        $settings   = $this->get_settings_for_display();

        if (!empty($settings['htmega_button_link']['url'])) {

            $this->add_render_attribute('url', 'class', 'price_btn');
            $this->add_link_attributes('url', $settings['htmega_button_link']);
        }

        // Currency symbol
        $currencysymbol = '';
        if (!empty($settings['htmega_currency_symbol'])) {
            if ($settings['htmega_currency_symbol'] != 'custom') {
                $currencysymbol = '<sub>' . $this->get_currency_symbol($settings['htmega_currency_symbol']) . '</sub>';
            } else {
                $currencysymbol = '<sub>' . $settings['htmega_currency_symbol_custom'] . '</sub>';
            }
        }


        $this->add_render_attribute('pricing_area_attr', 'class', 'htmega-pricing-panel');
        $this->add_render_attribute('pricing_badge_attr', 'class', ['htmega-price-badge', 'htmega-price-badge-position-' . $settings['htmega_badge_position']]);
        $this->add_render_attribute('pricing_area_attr', 'class', 'htmega-pricing-style-' . $settings['htmega_pricing_style']);

        if ($settings['htmega_heighlight_pricing_table'] == 'yes') {
            $this->add_render_attribute('pricing_area_attr', 'class', 'htmega-pricing-heighlight');
        }

        if ($settings['htmega_ribon_pricing_table'] == 'yes') {
            $this->add_render_attribute('pricing_area_attr', 'class', 'htmega-pricing-ribon');
        }

?>
        <div <?php echo $this->get_render_attribute_string('pricing_area_attr'); ?>>

            <?php if ('yes' == $settings['htmega_show_badge']) : ?>
                <span <?php $this->print_render_attribute_string('pricing_badge_attr'); ?>><?php echo esc_html($settings['pricing_badge_title']); ?></span>
            <?php endif; ?>

            <?php if ($settings['htmega_pricing_style'] == 2) : ?>
                <div class="htmega-pricing-heading">
                    <div class="icon">
                        <?php
                        if ($settings['htmega_header_icon_type'] == 'img') {
                            echo Group_Control_Image_Size::get_attachment_image_html($settings, 'headerimagesize', 'headerimage');
                        } else {
                            echo HTMega_Icon_manager::render_icon($settings['headericon'], ['aria-hidden' => 'true']);
                        }
                        ?>
                    </div>
                    <?php
                        if (!empty($settings['pricing_title'])) {
                            echo '<div class="title"><h2>' . htmega_kses_title( $settings['pricing_title'] ) . '</h2></div>';
                        }
                        if (!empty($settings['pricing_plan_title'])) {
                            echo '<div class="pricing-plan"><h1>' . htmega_kses_title( $settings['pricing_plan_title'] ) . '</h1></div>';
                        }
                    ?>
                    <div class="price">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title($settings['htmega_original_price']) . '</del></span><span class="pricing_new">' . $currencysymbol . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . $currencysymbol . htmega_kses_title( $settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php if ($settings['htmega_features_list']) : ?>
                    <div class="htmega-pricing-body">
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off'; } ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo esc_html__($features['htmega_features_title'], 'htmega-addons');
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php
                if (!empty($settings['htmega_button_text'])) {
                    echo '<div class="htmega-pricing-footer">' . sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']) . '</div>';
                }
                ?>

            <?php elseif ($settings['htmega_pricing_style'] == 3) : ?>
                <div class="htmega-pricing-heading">
                    <div class="price">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title( $settings['htmega_original_price'] ) . '</del></span><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title( $settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title( $settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                    <div class="htmega-pricing-header-align">
                        <?php
                            if (!empty($settings['pricing_title'])) {
                                echo '<div class="title"><h2>' . htmega_kses_title( $settings['pricing_title'] ) . '</h2></div>';
                            }
                            if (!empty($settings['pricing_plan_title'])) {
                                echo '<div class="pricing-plan"><h1>' . htmega_kses_title( $settings['pricing_plan_title'] ) . '</h1></div>';
                            }
                        ?>
                    </div>

                </div>

                <?php if ($settings['htmega_features_list']) : ?>
                    <div class="htmega-pricing-body">
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off'; } ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo htmega_kses_title( $features['htmega_features_title'] );
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php
                if (!empty($settings['htmega_button_text'])) {
                    echo '<div class="htmega-pricing-footer">' . sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']) . '</div>';
                }
                ?>

            <?php elseif ($settings['htmega_pricing_style'] == 4) : ?>
                <div class="htmega-pricing-heading">
                    <?php
                        if (!empty($settings['pricing_title'])) {
                            echo '<div class="title"><h2>' . htmega_kses_title( $settings['pricing_title'] ) . '</h2></div>';
                        }
                        if (!empty($settings['pricing_plan_title'])) {
                            echo '<div class="pricing-plan"><h1>' . htmega_kses_title( $settings['pricing_plan_title'] ) . '</h1></div>';
                        }
                    ?>
                    <div class="price">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title( $settings['htmega_original_price'] ) . '</del></span><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title( $settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title( $settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                </div>

                <?php if ($settings['htmega_features_list']) : ?>
                    <div class="htmega-pricing-body">
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off'; } ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo esc_html__($features['htmega_features_title'], 'htmega-addons');
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php
                if (!empty($settings['htmega_button_text'])) {
                    echo '<div class="htmega-pricing-footer">' . sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']) . '</div>';
                }
                ?>

            <?php elseif ($settings['htmega_pricing_style'] == 5) : ?>
                <div class="htmega-pricing-heading">
                    <?php
                        if (!empty($settings['pricing_title'])) {
                            echo '<div class="title"><h2>' . htmega_kses_title( $settings['pricing_title'] ) . '</h2></div>';
                        }
                        if (!empty($settings['pricing_plan_title'])) {
                            echo '<div class="pricing-plan"><h1>' . htmega_kses_title( $settings['pricing_plan_title'] ) . '</h1></div>';
                        }
                    ?>
                    <div class="price">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title( $settings['htmega_original_price'] ) . '</del></span><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title( $settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title($settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="htmega-pricing-body">
                    <?php if ($settings['htmega_features_list']) : ?>
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off'; } ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo esc_html__($features['htmega_features_title'], 'htmega-addons');
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;
                    if (!empty($settings['htmega_button_text'])) {
                        echo sprintf('<a %1$s><span>%2$s</span></a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']);
                    }
                    ?>
                </div>

            <?php elseif ($settings['htmega_pricing_style'] == 6) : ?>
                <div class="htmega-pricing-heading">
                    <div class="htmega-pricing-header-align">
                        <?php
                            if (!empty($settings['pricing_title'])) {
                                echo '<div class="title"><h2>' . htmega_kses_title( $settings['pricing_title'] ) . '</h2></div>';
                            }
                            if (!empty($settings['pricing_plan_title'])) {
                                echo '<div class="pricing-plan"><h1>' . htmega_kses_title( $settings['pricing_plan_title'] ) . '</h1></div>';
                            }
                        ?>
                    </div>
                    <div class="price">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title( $settings['htmega_original_price'] ) . '</del></span><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title( $settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title( $settings['htmega_period'] ) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title( $settings['htmega_price'] ) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php if ($settings['htmega_features_list']) : ?>
                    <div class="htmega-pricing-body">
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off'; } ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo esc_html__($features['htmega_features_title'], 'htmega-addons');
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php
                if (!empty($settings['htmega_button_text'])) {
                    echo '<div class="htmega-pricing-footer">' . sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']) . '</div>';
                }
                ?>

            <?php elseif ($settings['htmega_pricing_style'] == 7) : ?>
                <div class="htmega-pricing-heading">
                    <div class="price">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title($settings['htmega_original_price']) . '</del></span><span class="pricing_new">' . htmega_kses_title($currencysymbol) . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . htmega_kses_title($currencysymbol) . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                    <?php
                    if (!empty($settings['pricing_title'])) {
                        echo '<div class="title"><h2>' . htmega_kses_title($settings['pricing_title']) . '</h2></div>';
                    }
                    ?>
                </div>
                <?php if ($settings['htmega_features_list']) : ?>
                    <div class="htmega-pricing-body">
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off'; } ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo esc_html__($features['htmega_features_title'], 'htmega-addons');
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php
                if (!empty($settings['htmega_button_text'])) {
                    echo '<div class="htmega-pricing-footer">' . sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']) . '</div>';
                }
                ?>
            <?php elseif ($settings['htmega_pricing_style'] == 8) : ?>

                <div class="htmega-pricing-heading">
                    <div class="htmega-pricing-header-align">
                        <?php
                            if (!empty($settings['pricing_title'])) {
                                echo '<div class="title"><h2>' . htmega_kses_title($settings['pricing_title']) . '</h2></div>';
                            }
                            if (!empty($settings['pricing_plan_title'])) {
                                echo '<div class="pricing-plan"><h1>' . htmega_kses_title($settings['pricing_plan_title']) . '</h1></div>';
                            }
                        ?>
                    </div>
                    <div class="price-label">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if ( !empty($settings['htmega_price']) ) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title($settings['htmega_original_price']) . '</del></span><span class="pricing_new">' . htmega_kses_title($currencysymbol) . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price']) ) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . htmega_kses_title($currencysymbol) . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                </div>

                <?php if ($settings['htmega_features_list']) : ?>
                    <div class="htmega-pricing-body">
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off';} ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo esc_html__($features['htmega_features_title'], 'htmega-addons');
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php
                if (!empty($settings['htmega_button_text'])) {
                    echo '<div class="htmega-pricing-footer">' . sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']) . '</div>';
                }
                ?>          
            <?php elseif ($settings['htmega_pricing_style'] == 9) : ?>    

                <div class="htmega-pricing-heading">
                    <div class="header-top-image">
                        <?php 
                        if( !empty( $settings['titleimage'] ) ){
                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'iconimagesize', 'titleimage' );
                        }

                        if( !empty( $settings['titleicon']['value'] ) ){
                            echo HTMega_Icon_manager::render_icon( $settings['titleicon'], [ 'aria-hidden' => 'true' ] );
                        }
                        ?>
                    </div>
                    <div class="htmega-pricing-header-align">
                        <?php
                            if (!empty($settings['pricing_title'])) {
                                echo '<div class="title"><h2>' . htmega_kses_title($settings['pricing_title']) . '</h2></div>';
                            }
                            if (!empty($settings['pricing_plan_title'])) {
                                echo '<div class="pricing-plan"><h1>' . htmega_kses_title($settings['pricing_plan_title']) . '</h1></div>';
                            }
                        ?>
                    </div>
                    <div class="price">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if ( !empty($settings['htmega_price']) ) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title($settings['htmega_original_price']) . '</del></span><span class="pricing_new">' . htmega_kses_title($currencysymbol) . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price']) ) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . htmega_kses_title( $currencysymbol ) . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                </div>

                <?php if ($settings['htmega_features_list']) : ?>
                    <div class="htmega-pricing-body">
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off'; } ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo esc_html__($features['htmega_features_title'], 'htmega-addons');
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php
                if (!empty($settings['htmega_button_text'])) {
                    echo '<div class="htmega-pricing-footer">' . sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']) . '</div>';
                }
                ?>
            <?php else : ?>
                <div class="htmega-pricing-heading">
                    <?php
                    if (!empty($settings['pricing_title'])) {
                        echo '<div class="title"><h2>' . htmega_kses_title($settings['pricing_title']) . '</h2></div>';
                    }
                    ?>
                    <div class="price">
                        <?php
                        if ($settings['htmega_offer_price'] == 'yes' && !empty($settings['htmega_original_price'])) {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_old">' . htmega_kses_title( $currencysymbol ) . '<del>' . htmega_kses_title($settings['htmega_original_price']) . '</del></span><span class="pricing_new">' . htmega_kses_title($currencysymbol) . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        } else {
                            if (!empty($settings['htmega_price'])) {
                                echo '<h4><span class="pricing_new">' . htmega_kses_title($currencysymbol) . htmega_kses_title($settings['htmega_price']) . '</span>';
                            }
                            if(!empty($settings['htmega_period'])){
                                echo '<span class="separator">/</span> <span class="period-txt">' . htmega_kses_title($settings['htmega_period']) . '</span>';
                            }
                            if ( !empty($settings['htmega_price'])) {
                                echo '</h4>';
                            }
                        }
                        ?>
                    </div>
                </div>

                <?php if ($settings['htmega_features_list']) : ?>
                    <div class="htmega-pricing-body">
                        <ul class="htmega-features">
                            <?php foreach ($settings['htmega_features_list'] as $features) : ?>
                                <li class="<?php if ($features['htmega_old_features'] == 'yes') { echo 'off'; } ?> elementor-repeater-item-<?php echo esc_attr($features['_id']); ?>">
                                    <?php
                                    if (!empty($features['htmega_features_icon']['value'])) {
                                        echo HTMega_Icon_manager::render_icon($features['htmega_features_icon'], ['aria-hidden' => 'true']);
                                    }
                                    echo esc_html__($features['htmega_features_title'], 'htmega-addons');
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php
                if (!empty($settings['htmega_button_text'])) {
                    echo '<div class="htmega-pricing-footer">' . sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $settings['htmega_button_text']) . '</div>';
                }
                ?>

            <?php endif; ?>
        </div>
<?php
    }
}
