<?php
namespace Elementor;

// Elementor Classes
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Search extends Widget_Base {

    public function get_name() {
        return 'htmega-search-addons';
    }
    
    public function get_title() {
        return __( 'Search', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-search';
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
            'search_content',
            [
                'label' => __( 'Search', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'search_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'htmega-addons' ),
                        '2'   => __( 'Style Two', 'htmega-addons' ),
                        '3'   => __( 'Style Three', 'htmega-addons' ),
                        '4'   => __( 'Style Four', 'htmega-addons' ),
                        '5'   => __( 'Style Five', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'inpur_placeholder',
                [
                    'label' => __( 'Placeholder Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Search', 'htmega-addons' ),
                    'placeholder' => __( 'Search', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'search_btn_icon_type',
                [
                    'label' => esc_html__('Button Icon Type','htmega-addons'),
                    'type' =>Controls_Manager::CHOOSE,
                    'options' =>[
                        'buttontext' =>[
                            'title' =>__('Text','htmega-addons'),
                            'icon' =>'eicon-font',
                        ],
                        'icon' =>[
                            'title' =>__('Icon','htmega-addons'),
                            'icon' =>'eicon-info-circle',
                        ]
                    ],
                    'default' =>'icon',
                    'condition' => [
                        'search_style!' => '4',
                    ]
                ]
            );

            $this->add_control(
                'search_button_text',
                [
                    'label' => __( 'Search Button Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Search', 'htmega-addons' ),
                    'placeholder' => __( 'Search', 'htmega-addons' ),
                    'condition' => [
                        'search_btn_icon_type' => 'buttontext',
                        'search_style!' => '4',
                    ]
                ]
            );

            $this->add_control(
                'search_button_icon',
                [
                    'label' =>__('Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-search',
                        'library'=>'solid',
                    ],
                    'condition' => [
                        'search_btn_icon_type' => 'icon',
                        'search_style!' => '4',
                    ]
                ]
            );
            $this->add_control(
                'close_button_text',
                [
                    'label' => __( 'Close Button Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'close', 'htmega-addons' ),
                    'condition' => [
                        'search_style' => '5',
                    ]
                ]
            );
            $this->add_control(
                'hide_close_icon',
                [
                    'label' => esc_html__( 'Hide Close Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'search_style' => '5',
                    ]
                ]
            );
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_search_style_section',
            [
                'label' => __( 'Search Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'search_style_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'search_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'search_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_search_style_input',
            [
                'label' => __( 'Input', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'style_input_tabs'
        );
            // Normal Style Tab
            $this->start_controls_tab(
                'style_normal_tab',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );
            $this->add_control(
                'search_input_text_color',
                [
                    'label'     => __( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box input'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'search_input_placeholder_color',
                [
                    'label'     => __( 'Placeholder Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box input[type*="text"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-search-box input[type*="text"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-search-box input[type*="text"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'search_input_typography',
                    'selector' => '{{WRAPPER}} .htmega-search-box input',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-search-box input,{{WRAPPER}} .htmega-search-style-4',
                ]
            );
            $this->add_control(
                'search_submicon__color',
                [
                    'label'     => __( 'Icon Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-style-4 .search-icon'   => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-search-style-4 .search-icon:before,{{WRAPPER}} .htmega-search-style-4 .search-icon:after'   => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'search_style' => '4',
                    ]
                ]
            );
            $this->add_responsive_control(
                'search_input_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'search_input_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'search_input_height',
                [
                    'label' => __( 'Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 45,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box input' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );
            $this->add_responsive_control(
                'search_input_width',
                [
                    'label' => __( 'Min Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 50,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-style-3 input[type="text"]' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-search-style-3 input[type="text"]:focus' => 'width:100%;',
                    ],
                    'separator' =>'before',
                    'condition' => [
                        'search_style' => '3',
                    ]
                ]
            );
           
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'search_input_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-search-box input',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'search_input_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-search-box input,{{WRAPPER}} .htmega-search-style-1 input,{{WRAPPER}} .htmega-search-style-4' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->end_controls_tab();

            // Hover Style Tab
            $this->start_controls_tab(
                'filter_menu_hover_tab',
                [
                    'label' => __( 'Focus', 'htmega-addons' ),
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'search_input_border_focus',
                    'label' => __( 'Border Focus Color', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-search-box input:focus',
                    'separator' =>'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background_hover',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'exclude' => [ 'image' ],
                    'selector' => '{{WRAPPER}} .htmega-search-box input:focus',
                ]
            );
            $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'htmega_search_style_submit_button',
            [
                'label' => __( 'Submit Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_style!' => '4',
                ]
            ]
        );

            // Button Tabs Start
            $this->start_controls_tabs('search_style_submit_tabs');

                // Start Normal Submit button tab
                $this->start_controls_tab(
                    'search_style_submit_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'search_submitbutton_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-search-box button.btn-search svg path'  => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'search_submitbutton_typography',
                            'selector' => '{{WRAPPER}} .htmega-search-box input',
                            'condition' => [
                                'search_btn_icon_type' => 'buttontext',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'search_submitbutton_fontsize',
                        [
                            'label' => __( 'Font Size', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-search-box button.btn-search svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'search_btn_icon_type' => 'icon',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_submitbutton_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-search-box button.btn-search',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_submitbutton_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_submitbutton_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'search_submitbutton_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'search_submitbutton_width',
                        [
                            'label' => __( 'Width', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'search_submitbutton_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-search-box button.btn-search',
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_submitbutton_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                $this->end_controls_tab(); // Normal submit Button tab end

                // Start Hover Submit button tab
                $this->start_controls_tab(
                    'search_style_submit_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'search_submitbutton_hover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search:hover'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-search-box button.btn-search:hover svg path'   => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_submitbutton_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-search-box button.btn-search:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'search_submitbutton_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-search-box button.btn-search:hover,{{WRAPPER}} .htmega-search-box button.btn-search:focus',
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_submitbutton_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box button.btn-search:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                $this->end_controls_tab(); // Hover Submit Button tab End

            $this->end_controls_tabs(); // Button Tabs End

        $this->end_controls_section();
        $this->start_controls_section(
            'htmega_search_style_popup_button',
            [
                'label' => __( 'Popup style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_style' => '5',
                ]
            ]
        );

            // Button Tabs Start
            $this->start_controls_tabs('search_style_popupt_tabs');

                // Start Normal Submit button tab
                $this->start_controls_tab(
                    'search_style_popup_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'popup_search_button_heading',
                        [
                            'label' => __( 'Popup Button Style', 'htmega-addons' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );
                    $this->add_control(
                        'search_submitbutton_text_color_popup',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search svg path'   => 'fill: {{VALUE}};',
                            ],
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'search_submitbutton_typography_popup',
                            'selector' => '{{WRAPPER}} .htmega-search-box .action input',
                            'condition' => [
                                'search_btn_icon_type' => 'buttontext',
                                'search_style' => '5',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'search_submitbutton_fontsize_popup',
                        [
                            'label' => __( 'Font Size', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'search_btn_icon_type' => 'icon',
                                'search_style' => '5',
                            ],
                            
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_submitbutton_background_popup',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-search-box .action button.btn-search',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );
                    $this->add_responsive_control(
                        'search_submitbutton_height_popup',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box .action' => 'height: {{SIZE}}{{UNIT}};top:auto;bottom:0',
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );
                    $this->add_responsive_control(
                        'search_submitbutton_width_popup',
                        [
                            'label' => __( 'Width', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'search_submitbutton_border_popup',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-search-box .action button.btn-search',
                            'separator' =>'before',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );

                    $this->add_responsive_control(
                        'search_submitbutton_border_radius_popup',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );
                    $this->add_control(
                        'popup_search_button_close_heading',
                        [
                            'label' => __( 'Close Button Style', 'htmega-addons' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );
                    $this->add_control(
                        'search_submit_close_color_popup',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .block-bg .close__wrap,{{WRAPPER}} .block-bg .close__wrap::after'   => 'color: {{VALUE}};',
                                
                            ],
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'search_submit_close_typography_popup',
                            'selector' => '{{WRAPPER}} .block-bg .close__wrap',
                            'condition' => [
                                'search_style' => '5',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'close_icon_font_size',
                        [
                            'label' => __( 'Icon Font Size', 'htmega-addons' ),
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
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .block-bg .close__wrap::after' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'search_style' => '5',
                                'hide_close_icon!' => 'yes',
                            ],
                        ]
                    );
                    $this->add_control(
                        'popupbackground_bg',
                        [
                            'label' => __( 'Popup Background', 'htmega-addons' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                            'condition' => [
                                'search_style' => '5',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_background_popup',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .block-bg',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );
                $this->end_controls_tab(); // Normal submit Button tab end

                // Start Hover Submit button tab
                $this->start_controls_tab(
                    'search_style__popup_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'popup_search_button_heading_hover',
                        [
                            'label' => __( 'Popup Button Style', 'htmega-addons' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );
                    $this->add_control(
                        'search_submitbutton_text_color_popup_hover',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search:hover'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search:hover svg path'   => 'fill: {{VALUE}};',
                            ],
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_submitbutton_background_popup_hover',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-search-box .action button.btn-search:hover',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'search_submitbutton_border_popup_hover',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-search-box .action button.btn-search:hover',
                            'separator' =>'before',
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );

                    $this->add_responsive_control(
                        'search_submitbutton_border_radius_popup_hover',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-search-box .action button.btn-search:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'condition' => [
                                'search_style' => '5',
                            ]
                        ]
                    );
                $this->end_controls_tab(); // Hover Submit Button tab End

            $this->end_controls_tabs(); // Button Tabs End

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $sectionid = "sid". $this-> get_id();
        $this->add_render_attribute( 'htmega_search_attr', 'class', 'htmega-search-box' );
        $this->add_render_attribute( 'htmega_search_attr', 'class', 'htmega-search-style-'.$settings['search_style'].' '.$sectionid );

        $this->add_render_attribute(
            'input_attr', [
                'placeholder' => $settings['inpur_placeholder'],
                'type' => 'text',
                'name' => 's',
                'title' => esc_html__( 'Search', 'htmega-addons' ),
                'value' => get_search_query(),
            ]
        );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_search_attr' ); ?> >
                <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">

                    <?php
                        if( $settings['search_style'] == '4' ){
                            $this->add_render_attribute( 'input_attr', 'class', 'search-box' );
                            echo '<input '.$this->get_render_attribute_string( 'input_attr' ).' >';
                            echo '<span class="search-button"><span class="search-icon"></span></span>';
                        } elseif ( $settings['search_style'] == '5' ) {
                            ?>
                                
                                <?php 
                                    if( $settings['search_btn_icon_type'] == 'icon' ) {
                                        echo sprintf( '<button type="submit" class="btn-search search-trigger">%1$s %2$s</button>',HTMega_Icon_manager::render_icon( $settings['search_button_icon'], [ 'aria-hidden' => 'true' ] ),htmega_kses_desc( $settings['search_button_text'] ) );
                                    }else{
                                        echo sprintf( '<button type="submit" class="btn-search search-trigger">%1$s</button>', htmega_kses_desc( $settings['search_button_text'] ) );
                                    }
                                ?>

                                <!-- Start Search Popup -->
                                <div class="box-search-content search_active block-bg close__top minisearch" style="opacity:0">
                                    <div class="field__search">
                                        <input <?php echo $this->get_render_attribute_string( 'input_attr' ); ?> >
                                        <div class="action">
                                            <?php 
                                                if( $settings['search_btn_icon_type'] == 'icon' ) {
                                                    echo sprintf( '<button type="submit" class="htb-btn btn-search">%1$s %2$s</button>',HTMega_Icon_manager::render_icon( $settings['search_button_icon'], [ 'aria-hidden' => 'true' ] ), htmega_kses_desc( $settings['search_button_text'] ) );
                                                }else{
                                                    echo sprintf( '<button type="submit" class="htb-btn btn-search">%1$s</button>', htmega_kses_desc( $settings['search_button_text'] ));
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="close__wrap">
                                        <span><?php echo htmega_kses_desc( $settings['close_button_text'] );?></span>
                                    </div>
                                </div>
                                <!-- End Search Popup -->

                            <?php
                        }

                        else{
                            echo '<input '.$this->get_render_attribute_string( 'input_attr' ).' >';
                            if( $settings['search_btn_icon_type'] == 'icon' ) {
                                echo sprintf( '<button type="submit" class="htb-btn btn-search">%1$s %2$s</button>',HTMega_Icon_manager::render_icon( $settings['search_button_icon'], [ 'aria-hidden' => 'true' ] ), htmega_kses_desc( $settings['search_button_text'] ));
                            }else{
                                echo sprintf( '<button type="submit" class="htb-btn btn-search">%1$s</button>', htmega_kses_desc( $settings['search_button_text'] ) );
                            }
                        }
                    ?>

                </form>
            </div>
            <?php if( $settings['search_style'] == '4' || $settings['search_style'] == '5' ){ ?>
                <script type="text/javascript">
                    (function($){
                    "use strict";
                        <?php if( $settings['search_style'] == '4' ): ?>
                            $('.search-button').click(function(){
                                $(this).parent().toggleClass('open');
                            });
                        <?php else:?>
                            function searchToggler() {
                                var trigger = $('.search-trigger'),
                                container = $('.search_active');

                                trigger.on('click', function (e) {
                                e.preventDefault();
                                container.toggleClass('is-visible');
                                container.css( 'opacity',1 );
                                });

                                $('.close__wrap').on('click', function () {
                                container.removeClass('is-visible');
                                container.css( 'opacity',0 );
                                });

                            }
                            searchToggler();
                        <?php endif;?>

                    })(jQuery);
                </script>
            <?php } ?>
            <?php if( 'yes'== $settings['hide_close_icon'] ){

                    $htmega_print_css = ".{$sectionid} .block-bg .close__wrap::after {
                        display:none;
                    }
                    ";
                    ?>
                 <style>
                <?php echo esc_html( $htmega_print_css ); ?>
                </style>

            <?php } ?>
        <?php
    }

}

