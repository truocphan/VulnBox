<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Modal extends Widget_Base {

    public function get_name() {
        return 'htmega-modal-addons';
    }
    
    public function get_title() {
        return __( 'Modal', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-eye';
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
            'modal_content',
            [
                'label' => __( 'Modal', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'modal_header_text',
                [
                    'label'   => __( 'Header Content', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __('Modal Header Content','htmega-addons'),
                ]
            );

            $this->add_control(
                'content_source',
                [
                    'label'   => esc_html__( 'Select Content Source', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'    => esc_html__( 'Custom', 'htmega-addons' ),
                        "elementor" => esc_html__( 'Elementor Template', 'htmega-addons' ),
                    ],
                ]
            );

             $this->add_control(
                'template_id',
                [
                    'label'       => __( 'Content', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '0',
                    'options'     => htmega_elementor_template(),
                    'condition'   => [
                        'content_source' => "elementor"
                    ],
                ]
            );

             $this->add_control(
                'custom_content',
                [
                    'label' => __( 'Content', 'htmega-addons' ),
                    'type' => Controls_Manager::WYSIWYG,
                    'title' => __( 'Content', 'htmega-addons' ),
                    'show_label' => false,
                    'condition' => [
                        'content_source' =>'custom',
                    ],
                    'default' => __('Lorem ipsum dolor sit amet, consectetur adipis elit, sed do eiusmod tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitati ulla laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in repre in voluptate velit esse cillum dolore eu.','htmega-addons'),
                ]
            );

            $this->add_control(
                'modal_footer_text',
                [
                    'label'   => __( 'Footer Text', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __('Modal Footer Content','htmega-addons'),
                ]
            );

        $this->end_controls_section(); // Modal Content Area end

        // Modal Button Section area Start
        $this->start_controls_section(
            'modal_button',
            [
                'label' => __( 'Button', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'button_text',
                [
                    'label'   => __( 'Text', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Modal Design','htmega-addons'),
                ]
            );

            $this->add_control(
                'button_icon_type',
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
                ]
            );

            $this->add_control(
                'button_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'button_icon_type' => 'img',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'button_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'button_icon_type' => 'img',
                    ]
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label' =>__('Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'condition' => [
                        'button_icon_type' => 'icon',
                    ]
                ]
            );

            $this->add_responsive_control(
                'modal_icon_position',
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
                    'condition' =>[
                        'button_icon_type' =>'icon',
                        'button_icon[value]!' =>'',
                    ],
                    'prefix_class' => 'modal_icon_positioning-'
                ]
            );

        $this->end_controls_section(); // Modal Button end

        // Style tab section
        $this->start_controls_section(
            'modal_button_style',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'button_style_tabs' );

                $this->start_controls_tab(
                    'button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'button_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-modal-btn button' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-modal-btn button svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'button_typography',
                            'selector' => '{{WRAPPER}} .htmega-modal-btn button',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'button_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-modal-btn button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-modal-btn button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-modal-btn button',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-modal-btn button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-modal-btn button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_control(
                        'button_image_distance',
                        [
                            'label' => esc_html__( 'Image space', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-modal-btn button img' => 'margin-right: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' =>[
                                'button_icon_type' =>'img',
                            ],
                            'separator' =>'after',
                        ]
                    );

                    $this->add_control(
                        'button_width',
                        [
                            'label' => esc_html__( 'Width', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1200,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-modal-btn button' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_height',
                        [
                            'label' => esc_html__( 'Height', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-modal-btn button' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_align',
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
                                '{{WRAPPER}} .htmega-modal-btn' => 'text-align: {{VALUE}};',
                            ],
                            'default' => 'left',
                            'separator' =>'after',
                        ]
                    );

                    $this->add_control(
                        'modal_icon_size_opt',
                        [
                            'label' => __( 'Icon Size', 'htmega-addons' ),
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
                                'size' => '',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-modal-btn button i' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-modal-btn svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' =>[
                                'button_icon_type' =>'icon',
                                'button_icon[value]!' =>'',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_icon_distance',
                        [
                            'label' => esc_html__( 'Icon Space', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .modal_icon_positioning-left .htmega-modal-btn button i, .modal_icon_positioning-left .htmega-modal-btn button svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .modal_icon_positioning-right .htmega-modal-btn button i, .modal_icon_positioning-right .htmega-modal-btn button svg' => 'margin-left: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' =>[
                                'button_icon_type' =>'icon',
                                'button_icon[value]!' =>'',
                            ],
                            'separator' =>'after',
                        ]
                    );

                $this->end_controls_tab(); // Button Normal End

                $this->start_controls_tab(
                    'button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'button_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-modal-btn button:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-modal-btn button:hover svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-modal-btn button:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-modal-btn button:hover',
                        ]
                    );

                $this->end_controls_tab(); // Button Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'modal_content_box_style',
            [
                'label' => __( 'Modal Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'modal_content_box_top_spacing',
                [
                    'label' => __( 'Modal Top Space', 'htmega-addons' ),
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
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-modal-area .htb-modal-dialog' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'modal_content_box_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htb-modal-content',
                ]
            );

            $this->add_responsive_control(
                'modal_content_box_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'modal_content_box_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'modal_content_box_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htb-modal-content',
                ]
            );

            $this->add_responsive_control(
                'modal_content_box_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Header tab section
        $this->start_controls_section(
            'modal_header_style',
            [
                'label' => __( 'Header', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'header_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#444444',
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-header h5' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'header_typography',
                    'selector' => '{{WRAPPER}} .htb-modal-header h5',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'header_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htb-modal-header',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'header_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htb-modal-header',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'header_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htb-modal-header',
                ]
            );

            $this->add_responsive_control(
                'header_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-header' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'header_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'header_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'header_close_btn_note',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => sprintf(
                        esc_html__( 'Note: Below, This CSS style to use on your header close button ', 'htmega-addons' )
                    ),
                    'content_classes' => 'elementor-panel-alert',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'header_close_btn_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-header .htb-close' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'header_close_btn_typography',
                    'selector' => '{{WRAPPER}} .htb-modal-header .htb-close',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'header_close_btn_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htb-modal-header .htb-close',
                ]
            );

            $this->add_responsive_control(
                'header_close_btn_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-header .htb-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'header_close_btn_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htb-modal-header .htb-close',
                ]
            );

            $this->add_responsive_control(
                'header_close_btn_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-header .htb-close' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Footer tab section
        $this->start_controls_section(
            'modal_footer_style',
            [
                'label' => __( 'Footer', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'footer_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#444444',
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-footer p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'footer_typography',
                    'selector' => '{{WRAPPER}} .htb-modal-footer p',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'footer_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htb-modal-footer',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'footer_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htb-modal-footer',
                ]
            );

            $this->add_responsive_control(
                'footer_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-footer' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'footer_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'footer_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'footer_close_txt',
                [
                    'label' => __( 'Footer Close Button', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Close', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'footer_close_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-modal-area .htb-btn-secondary' => 'color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'footer_close_txt!'=>'',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'footer_close_typography',
                    'selector' => '{{WRAPPER}} .htmega-modal-area .htb-btn-secondary',
                    'separator' => 'before',
                    'condition' =>[
                        'footer_close_txt!'=>'',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'footer_close_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-modal-area .htb-btn-secondary',
                    'condition' =>[
                        'footer_close_txt!'=>'',
                    ]
                ]
            );

            $this->add_responsive_control(
                'footer_close_btn_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-modal-area .htb-btn-secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'footer_close_btn_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-modal-area .htb-btn-secondary',
                ]
            );

            $this->add_responsive_control(
                'footer_close_btn_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-modal-area .htb-btn-secondary' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Footer tab section
        $this->start_controls_section(
            'modal_content_style',
            [
                'label' => __( 'Modal Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'content_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#444444',
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-body' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'selector' => '{{WRAPPER}} .htb-modal-body',
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'content_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htb-modal-body',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'content_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htb-modal-body',
                ]
            );

            $this->add_responsive_control(
                'content_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-body' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htb-modal-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'content_align',
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
                        '{{WRAPPER}} .htb-modal-body' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'content_width',
                [
                    'label' => esc_html__( 'Modal Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 300,
                            'max' => 1200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-modal-area .htb-modal-dialog' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $id = $this->get_id();
        $this->add_render_attribute( 'htmega_modal_attr', 'class', 'htmega-modal-area htb-modal htb-fade' );
        $this->add_render_attribute( 'htmega_modal_attr', 'id', 'htmegamodal'.$id );
       
        ?>
            
            <div class="htmega-modal-btn">
                <?php
                    $buttontxt = isset( $settings['button_text'] ) ? $settings['button_text'] : '';
                    if( $settings['button_icon_type'] == 'img' && !empty( $settings['button_image']['url'] ) ){
                        $buttontxt = Group_Control_Image_Size::get_attachment_image_html( $settings, 'button_imagesize', 'button_image' ).$buttontxt;
                    }elseif( $settings['button_icon_type'] == 'icon' && !empty( $settings['button_icon']['value'] )){
                        $buttontxt = HTMega_Icon_manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ).$buttontxt;
                    }else{
                        $buttontxt = $buttontxt;
                    }

                    echo sprintf('<button type="button" data-toggle="htbmodal" data-target="#htmegamodal%1$s">%2$s</button>', esc_attr( $id ), $buttontxt );
                ?>
            </div>

            <!-- Modal -->
            <div <?php echo $this->get_render_attribute_string( 'htmega_modal_attr' ); ?>>

                <div class="htb-modal-dialog htb-modal-dialog-centered">
                    <div class="htb-modal-content">

                        <div class="htb-modal-header">
                            <?php
                                if( !empty( $settings['modal_header_text'] ) ){
                                    echo '<h5>'.esc_html( $settings['modal_header_text'] ).'</h5>';
                                }
                            ?>
                            <button type="button" class="htb-close" data-dismiss="modal"><span>&times;</span></button>
                        </div>

                        <?php

                            if ( $settings['content_source'] == 'custom' && !empty( $settings['custom_content'] ) ) {
                                echo '<div class="htb-modal-body">'.wp_kses_post( $settings['custom_content'] ).'</div>';
                            } elseif ( $settings['content_source'] == "elementor" && !empty( $settings['template_id'] )) {
                                echo '<div class="htb-modal-body">'.Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'] ).'</div>';
                            }
                        ?>

                        <div class="htb-modal-footer">
                            <?php
                                if( !empty( $settings['modal_header_text'] ) ){
                                    echo '<p>'.esc_html( $settings['modal_footer_text'] ).'</p>';
                                }
                                if( !empty( $settings['footer_close_txt'] ) ){
                                    echo '<button type="button" class="htb-btn htb-btn-secondary" data-dismiss="modal">'.esc_html($settings['footer_close_txt'] ).'</button>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
}

