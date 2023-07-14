<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Double_Button extends Widget_Base {

    public function get_name() {
        return 'htmega-dualbutton-addons';
    }
    
    public function get_title() {
        return __( 'Double Button', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-button';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'dualbutton_content',
            [
                'label' => __( 'Double Button', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'double_button_size',
                [
                    'label'   => __( 'Button Size', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'md',
                    'options' => [
                        'sm' => __( 'Small', 'htmega-addons' ),
                        'md' => __( 'Medium', 'htmega-addons' ),
                        'lg' => __( 'Large', 'htmega-addons' ),
                        'xl' => __( 'Extra Large', 'htmega-addons' ),
                        'xs' => __( 'Extra Small', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'show_button_middle_text',
                [
                    'label' => __( 'Middle Text', 'htmega-addons' ),
                    'type'  => Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'button_middle_text',
                [
                    'label' => __( 'Middle Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Or', 'htmega-addons' ),
                    'condition'   => [
                        'show_button_middle_text' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'double_button_before_bg',
                [
                    'label' => __( 'Skew Background', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

        $this->end_controls_section();

        // Button One
        $this->start_controls_section(
            'button_one_content',
            [
                'label' => __( 'Button One', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'button_one_text',
                [
                    'label' => __( 'Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Button', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'button_one_link',
                [
                    'label' => __( 'Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                ]
            );

            $this->add_control(
                'button_one_icon',
                [
                    'label' => __( 'Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                ]
            );

            $this->add_control(
                'icon_one_specing',
                [
                    'label' => __( 'Icon Spacing', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 8,
                    ],
                    'condition' => [
                        'button_one_icon[value]!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-doublebutton .htmega-doule-btn.btn-one span'  => 'margin-right: {{SIZE}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'double_button_icon_position_1',
                [
                    'label' => __( 'Icon Position', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => 'left',
                    'toggle' => false,
                    'prefix_class' => 'htmega-double-button-icon1-position-',
                    'condition' => [
                        'button_one_icon[value]!' => '',
                    ],
                ]
            );

        $this->end_controls_section(); // Button One End

        // Button Two
        $this->start_controls_section(
            'button_two_content',
            [
                'label' => __( 'Button Two', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'button_two_text',
                [
                    'label' => __( 'Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Button', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'button_two_link',
                [
                    'label' => __( 'Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                ]
            );

            $this->add_control(
                'button_two_icon',
                [
                    'label' => __( 'Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                ]
            );

            $this->add_control(
                'icon_two_specing',
                [
                    'label' => __( 'Icon Spacing', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 8,
                    ],
                    'condition' => [
                        'button_two_icon[value]!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-doublebutton .htmega-doule-btn.btn-two span'  => 'margin-right: {{SIZE}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'double_button_icon_position_2',
                [
                    'label' => __( 'Icon Position', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => 'right',
                    'toggle' => false,
                    'prefix_class' => 'htmega-double-button-icon2-position-',
                    'condition' => [
                        'button_two_icon[value]!' => '',
                    ],
                ]
            );            

        $this->end_controls_section(); // Button Two End


        // Style tab section
        $this->start_controls_section(
            'double_button_area_style_section',
            [
                'label' => __( 'Button Area', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'double_button_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'separator' => 'after',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-doublebutton' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'double_button_area_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-doublebutton',
                ]
            );

            $this->add_responsive_control(
                'double_button_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-doublebutton' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

       
        // Style tab section
        $this->start_controls_section(
            'double_button_style_section',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'double_button_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'end' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-double-button-area' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'double_button_width',
                [
                    'label' => __( 'Button Width', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        '%' => [
                            'max' => 100,
                            'min' => 5,
                        ],
                        'px' => [
                            'max' => 1200,
                            'min' => 200,
                        ],
                    ],
                    'size_units' => ['%', 'px'],
                    'default' => [
                        'size' => 100,
                        'unit' => '%',
                    ],
                    'tablet_default' => [
                        'size' => 100,
                        'unit' => '%',
                    ],
                    'mobile_default' => [
                        'size' => 100,
                        'unit' => '%',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-doublebutton'  => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'double_button_height',
                [
                    'label' => __( 'Button Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 300,
                            'min' => 0,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-doublebutton a'  => 'height: {{SIZE}}px;',
                    ],
                ]
            );

            $this->start_controls_tabs('doule_button_style_tabs');

                // Button Default Normal style start
                $this->start_controls_tab(
                    'doule_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'doule_button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Button Default Normal style start
                $this->start_controls_tab(
                    'doule_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'doule_button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Button One Style tab Start
        $this->start_controls_section(
            'double_button_one_style_section',
            [
                'label' => __( 'Button One', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('doule_button_one_style_tabs');

                // Button Default Normal style start
                $this->start_controls_tab(
                    'doule_button_one_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'doule_button_one_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'doule_button_one_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'doule_button_one_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_one_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'doule_button_one_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one,{{WRAPPER}} .before_bg a.htmega-doule-btn.btn-one::before, {{WRAPPER}} .htmega-double-button-area a.btn-one::before',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'doule_button_one_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_one_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_one_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Button One Normal style End

                // Button One Hover style start
                $this->start_controls_tab(
                    'doule_button_one_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'doule_button_one_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'doule_button_one_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_one_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'doule_button_one_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one:hover, {{WRAPPER}} .before_bg a.htmega-doule-btn.btn-one:hover::before,{{WRAPPER}} .htmega-double-button-area a.btn-one::after',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'doule_button_one_hover_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-one:hover',
                        ]
                    );

                $this->end_controls_tab(); // Button one Hover style End

            $this->end_controls_tabs();

        $this->end_controls_section(); // Button One Style tab end

        // Button One Style tab Start
        $this->start_controls_section(
            'double_button_two_style_section',
            [
                'label' => __( 'Button Two', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('doule_button_two_style_tabs');

                // Button Two Normal style start
                $this->start_controls_tab(
                    'doule_button_two_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'doule_button_two_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'doule_button_two_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'doule_button_two_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_two_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'doule_button_two_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two, {{WRAPPER}} .before_bg a.htmega-doule-btn.btn-two::before, {{WRAPPER}} .htmega-double-button-area a.btn-two::before',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'doule_button_two_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_two_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_two_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Button two Normal style End

                // Button Two Hover style start
                $this->start_controls_tab(
                    'doule_button_two_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'doule_button_two_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'doule_button_two_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'doule_button_two_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'doule_button_two_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two:hover, {{WRAPPER}} .before_bg a.htmega-doule-btn.btn-two:hover::before,{{WRAPPER}} .htmega-double-button-area a.btn-two::after',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'doule_button_two_hover_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-doublebutton a.htmega-doule-btn.btn-two:hover',
                        ]
                    );

                $this->end_controls_tab(); // Button two Hover style End

            $this->end_controls_tabs();

        $this->end_controls_section(); // Button two Style tab end

        // Button Middle Text style start
        $this->start_controls_section(
            'double_button_middletext_style_section',
            [
                'label' => __( 'Middle Text', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_button_middle_text'=>'yes',
                    'button_middle_text!'=>'',
                ]
            ]
        );

            $this->add_responsive_control(
                'double_button_middle_box_size',
                [
                    'label' => __( 'Box Size', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 100,
                            'min' => 15,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} span.htmega-middle-text'  => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'doule_button_middletext_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   =>'#000000',
                    'selectors' => [
                        '{{WRAPPER}} span.htmega-middle-text' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'doule_button_middletext_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} span.htmega-middle-text',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'doule_button_middletext_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} span.htmega-middle-text',
                ]
            );

            $this->add_responsive_control(
                'doule_button_middletext_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} span.htmega-middle-text' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'doule_button_middletext_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} span.htmega-middle-text',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'doule_button_middletext_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} span.htmega-middle-text',
                ]
            );

            $this->add_responsive_control(
                'doule_button_middletext_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} span.htmega-middle-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'doule_button_middletext_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} span.htmega-middle-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); //Button Middle Text Style tab end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $button_one_text = $button_two_text = $button_middle_text = '';
        $this->add_render_attribute( 'htmega_doublebutton', 'class', 'htmega-doublebutton' );

        if( $settings['double_button_before_bg'] == 'yes' ){
            $this->add_render_attribute( 'htmega_doublebutton', 'class', 'before_bg' );
        }

        // Button One
        if ( ! empty( $settings['button_one_link']['url'] ) ) {

            $button_one_text  = !empty( $settings['button_one_text'] ) ? "<span class='button_one_text_progression'>".$settings['button_one_text']."</span>" : '';
            $button_one_icon  = !empty( $settings['button_one_icon']['value'] ) ? "<span class='button_one_icon_progression' >".HTMega_Icon_manager::render_icon( $settings['button_one_icon'], [ 'aria-hidden' => 'true' ] ).'</span>' : '';
            
            $this->add_link_attributes( 'urlone', $settings['button_one_link'] );

            $this->add_render_attribute( 'urlone', 'class', 'htmega-doule-btn btn-one' );
            $this->add_render_attribute( 'urlone', 'class', 'htmega-doule-btn-size-'. $settings['double_button_size'] );

            $button_one_text = sprintf( '<a %1$s>%2$s%3$s</a>', $this->get_render_attribute_string( 'urlone' ), $button_one_text, $button_one_icon );
        }

        // Button Two
        $button_two_text  = !empty( $settings['button_two_text'] ) ? "<span class='button_two_text_progression'>".$settings['button_two_text'].'</span>' : '';
        $button_two_icon  = !empty( $settings['button_two_icon'] ) ? "<span class='button_two_icon_progression'>".HTMega_Icon_manager::render_icon( $settings['button_two_icon'], [ 'aria-hidden' => 'true' ] ).'</span>' : '';

        if ( ! empty( $settings['button_two_link']['url'] ) ) {
            
            $this->add_link_attributes( 'urltwo', $settings['button_two_link'] );

            $this->add_render_attribute( 'urltwo', 'class', 'htmega-doule-btn btn-two' );
            $this->add_render_attribute( 'urltwo', 'class', 'htmega-doule-btn-size-'. $settings['double_button_size'] );

            $button_two_text = sprintf( '<a %1$s>%2$s%3$s</a>', $this->get_render_attribute_string( 'urltwo' ), $button_two_text, $button_two_icon );
        }

        if( $settings['show_button_middle_text'] == 'yes' && !empty( $settings['button_middle_text'] ) ){
            $button_middle_text = '<span class="htmega-middle-text">'.$settings['button_middle_text'].'</span>';
        }

        $button_double_text = $button_one_text.$button_two_text.$button_middle_text;

        if( !empty( $button_one_text ) || !empty( $button_two_text ) ){
            echo sprintf( '<div class="htmega-double-button-area"><div %1$s>%2$s</div></div>', $this->get_render_attribute_string( 'htmega_doublebutton' ), $button_double_text );
        }


    }

}

