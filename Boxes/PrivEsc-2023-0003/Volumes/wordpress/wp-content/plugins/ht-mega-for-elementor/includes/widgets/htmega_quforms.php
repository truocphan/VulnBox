<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_QUforms extends Widget_Base {

    public function get_name() {
        return 'htmega-quforms-addons';
    }
    
    public function get_title() {
        return __( 'QU Form', 'htmega-addons' );
    }

    public function get_keywords() {
        return [ 'form', 'contact', 'qu', 'contact form','qu form','htmega' ];
    }

    public function get_icon() {
        return 'htmega-icon eicon-mail';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/forms-widgets/quform-widget/';
	}

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function htmega_quform_list() {
        if ( class_exists( 'Quform' ) ) {
            $quform = \Quform::getService('repository');
            $quform = $quform->formsToSelectArray();
            $form_options = ['0' => esc_html__( 'Select Form', 'htmega-addons' )];
            if ( ! empty( $quform ) && ! is_wp_error( $quform ) ) {
                foreach ( $quform as $id => $name ) {
                    $form_options[esc_attr( $id )] = esc_html( $name );
                }
            }
        } else {
            $form_options = ['0' => esc_html__( 'Form Not Found!', 'htmega-addons' ) ];
        }
        return $form_options;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'quform_content',
            [
                'label' => __( 'QU Form', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'contact_form_list',
                [
                    'label'   => esc_html__( 'Select Form', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => $this->htmega_quform_list(),
                ]
            );
            
        $this->end_controls_section();

        // Label style tab start
        $this->start_controls_section(
            'quform_label_style',
            [
                'label'     => __( 'Label', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'label_align',
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
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .quform-label,{{WRAPPER}} .quform-option,{{WRAPPER}} .quform-option .quform-option-label' => 'text-align: {{VALUE}};',
                ],
            ]
        );
            $this->add_control(
                'quform_label_background',
                [
                    'label'     => __( 'Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-label-text,{{WRAPPER}} .quform-option .quform-option-label'   => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'quform_label_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-label-text,{{WRAPPER}} .quform-option .quform-option-label'   => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'label_required_color',
                [
                    'label'     => __( 'Required Symbol Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} label>.quform-required'   => 'color: {{VALUE}}!important;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'quform_label_typography',
                    'selector' => '{{WRAPPER}} .quform-form-inner .quform-label-text,{{WRAPPER}} .quform-option .quform-option-label',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'quform_label_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .quform-form-inner .quform-label-text,{{WRAPPER}} .quform-option .quform-option-label',
                ]
            );

            $this->add_responsive_control(
                'quform_label_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-label-text,{{WRAPPER}} .quform-option .quform-option-label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'quform_label_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-label-text,{{WRAPPER}} .quform-option .quform-option-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'quform_label_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-label-text,{{WRAPPER}} .quform-option .quform-option-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // // Label style tab end

        // Sub Label style tab start
        $this->start_controls_section(
            'quform_sublabel_style',
            [
                'label'     => __( 'Sub Label', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'sublabel_align',
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
                        ]
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-description' => 'text-align: {{VALUE}};',
                    ],
                ]
            ); 
            $this->add_control(
                'quform_sublabel_background',
                [
                    'label'     => __( 'Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-description'   => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'quform_sublabel_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-description'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'quform_sublabel_typography',
                    'selector' => '{{WRAPPER}} .quform-form-inner .quform-description',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'quform_sublabel_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .quform-form-inner .quform-description',
                ]
            );

            $this->add_responsive_control(
                'quform_sublabel_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-description' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'quform_sublabel_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'quform_sublabel_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // // Label style tab end

        // Style Input tab section
        $this->start_controls_section(
            'quform_input_style_section',
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
                'style_input_normal_tab',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );
            $this->add_responsive_control(
                'input_align',
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
                        ]
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .quform-form-inner .quform-input select' => 'text-align: {{VALUE}};',
                    ],
                ]
            ); 
                $this->add_control(
                    'quform_input_background_color',
                    [
                        'label' => __( 'Background Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .quform-form-inner .quform-input select' => 'background-color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'quform_input_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#212529',
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .quform-form-inner .quform-input select' => 'color: {{VALUE}}',
                        ],
                    ]
                );
                $this->add_control(
                    'htmega_input_placeholder_color',
                    [
                        'label' => __( 'Placeholder Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input::-webkit-input-placeholder' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .quform-form-inner .quform-input input::-moz-placeholder' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .quform-form-inner .quform-input input::-ms-input-placeholder' => 'color: {{VALUE}}',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'quform_input_typography',
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .quform-form-inner .quform-input select',
                    ]
                );

                $this->add_responsive_control(
                    'quform_input_height',
                    [
                        'label'             => __( 'Height', 'htmega-addons' ),
                        'type'              => Controls_Manager::SLIDER,
                        'range'             => [
                            'px' => [
                                'min'   => 0,
                                'max'   => 100,
                                'step'  => 1,
                            ],
                        ],
                        'size_units'        => [ 'px', 'em', '%' ],
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .quform-form-inner .quform-input select' => 'height: {{SIZE}}{{UNIT}}',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'quform_input_padding',
                    [
                        'label' => __( 'Padding', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            '{{WRAPPER}} .quform-form-inner .quform-input select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' =>'before',
                    ]
                );

                $this->add_responsive_control(
                    'quform_input_margin',
                    [
                        'label' => __( 'Margin', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            '{{WRAPPER}} .quform-form-inner .quform-input select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'quform_input_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .quform-form-inner .quform-input select',
                    ]
                );

                $this->add_responsive_control(
                    'quform_input_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            '{{WRAPPER}} .quform-form-inner .quform-input select' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'htmega_input_box_shadow',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .quform-form-inner .quform-input select',
                    ]
                );
            $this->end_controls_tab();
            // Hover Style Tab
            $this->start_controls_tab(
                'style_input_foucs_tab',
                [
                    'label' => __( 'Focus', 'htmega-addons' ),
                ]
            );
                $this->add_control(
                    'quform_input_background_color_focus',
                    [
                        'label' => __( 'Background Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .quform-form-inner .quform-input select:focus' => 'background-color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'quform_input_color_focus',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus,{{WRAPPER}} .quform-form-inner .quform-input select:focus' => 'color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'quform_input_border_focus',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .quform-form-inner .quform-input select:focus',
                    ]
                );

                $this->add_responsive_control(
                    'quform_input_border_radius_focus',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus,{{WRAPPER}} .quform-form-inner .quform-input select:focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'htmega_input_box_shadow_focus',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .quform-form-inner .quform-input select:focus',
                    ]
                );
                $this->end_controls_tab();
            $this->end_controls_tabs();   
        $this->end_controls_section(); // Form input style

        // Style Textarea tab section
        $this->start_controls_section(
            'quform_textarea_style_section',
            [
                'label' => __( 'Textarea', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'style_textarea_tabs'
        );
            // Normal Style Tab
            $this->start_controls_tab(
                'style_textarea_normal_tab',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );
                $this->add_responsive_control(
                    'textarea_align',
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
                            ]
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea' => 'text-align: {{VALUE}};',
                        ],
                    ]
                ); 
                $this->add_control(
                    'quform_textarea_background_color',
                    [
                        'label' => __( 'Background Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea' => 'background-color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'quform_textarea_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#212529',
                        'selectors'  => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea' => 'color: {{VALUE}}',
                        ],
                    ]
                );
                $this->add_control(
                    'quform_textarea_placeholder_color',
                    [
                        'label' => __( 'Placeholder Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea::-moz-placeholder' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea::-ms-input-placeholder' => 'color: {{VALUE}}',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'quform_textarea_typography',
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea',
                    ]
                );

                $this->add_responsive_control(
                    'quform_textarea_height',
                    [
                        'label'             => __( 'Height', 'htmega-addons' ),
                        'type'              => Controls_Manager::SLIDER,
                        'range'             => [
                            'px' => [
                                'min'   => 0,
                                'max'   => 500,
                                'step'  => 1,
                            ],
                        ],
                        'size_units'        => [ 'px', 'em', '%' ],
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea' => 'height: {{SIZE}}{{UNIT}}',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'quform_textarea_padding',
                    [
                        'label' => __( 'Padding', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' =>'before',
                    ]
                );

                $this->add_responsive_control(
                    'quform_textarea_margin',
                    [
                        'label' => __( 'Margin', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'quform_textarea_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea',
                    ]
                );

                $this->add_responsive_control(
                    'quform_textarea_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'htmega_textarea_box_shadow',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea',
                    ]
                );
            $this->end_controls_tab();
            // Hover Style Tab
            $this->start_controls_tab(
                'style_textarea_focus_tab',
                [
                    'label' => __( 'Focus', 'htmega-addons' ),
                ]
            );
                $this->add_control(
                    'quform_textarea_background_color_focus',
                    [
                        'label' => __( 'Background Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors'         => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea:focus' => 'background-color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'quform_textarea_color_focus',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '#212529',
                        'selectors'  => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea:focus' => 'color: {{VALUE}}',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'quform_textarea_border_focus',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea:focus',
                    ]
                );

                $this->add_responsive_control(
                    'quform_textarea_border_radius_focus',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea:focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'htmega_textarea_box_shadow_focus',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .quform-form-inner .quform-input-textarea textarea:focus',
                    ]
                );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section(); // Form input style


        // Input submit button style tab start
        $this->start_controls_section(
            'quform_inputsubmit_style',
            [
                'label'     => __( 'Button', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs('quform_submit_style_tabs');

                // Button Normal tab start
                $this->start_controls_tab(
                    'quform_submit_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_responsive_control(
                        'submit_align',
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
                                ]
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .quform-button-back-default, .quform-button-next-default,{{WRAPPER}} .quform-button-submit-default' => 'display:inline-block;float:none; margin:0;',
                                '{{WRAPPER}} .quform-element-submit' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'quform_input_submit_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'htmega_input_submit_width',
                        [
                            'label' => __( 'Width', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
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
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit,{{WRAPPER}} .quform-element-submit>div' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'quform_input_submit_typography',
                            'selector' => '{{WRAPPER}} .quform-form-inner button.quform-submit',
                        ]
                    );

                    $this->add_control(
                        'quform_input_submit_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'quform_input_submit_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'quform_input_submit_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'quform_input_submit_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'quform_input_submit_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .quform-form-inner button.quform-submit',
                        ]
                    );

                    $this->add_responsive_control(
                        'quform_input_submit_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'quform_input_submit_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .quform-form-inner button.quform-submit',
                        ]
                    );

                $this->end_controls_tab(); // Button Normal tab end

                // Button Hover tab start
                $this->start_controls_tab(
                    'quform_submit_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'quform_input_submithover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit:hover'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'quform_input_submithover_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .quform-form-inner button.quform-submit:hover'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'quform_input_submithover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .quform-form-inner button.quform-submit:hover',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'quform_input_submit_box_shadow_hover',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .quform-form-inner button.quform-submit:hover',
                        ]
                    );
                $this->end_controls_tab(); // Button Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Input submit button style tab end
        // Input error style tab start
        $this->start_controls_section(
            'htmega_input_error_style',
            [
                'label'     => __( 'Errors and Success Style', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'htmega_error_text_color',
                [
                    'label'     => __( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-error>.quform-error-inner,{{WRAPPER}} .quform-error-text'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_error_text_typography',
                    'selector' => '{{WRAPPER}} .quform-error>.quform-error-inner,{{WRAPPER}} .quform-error-text',
                ]
            );
            $this->add_control(
                'htmega_error_border_color',
                [
                    'label'     => __( 'Border Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-error>.quform-error-inner'  => 'border-color: {{VALUE}}!important;',
                    ],
                ]
            );
            $this->add_control(
                'error_background_hover_color',
                [
                    'label'     => __( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-error>.quform-error-inner' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            // Feedback style
            $this->add_control(
                'htmega_confirmation_style',
                [
                    'label' => __( 'Confirmation Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'htmega__confirmation_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-success-message'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'htmega_confirmation_border_color',
                [
                    'label'     => __( 'Border Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-success-message'  => 'border-color: {{VALUE}}!important;',
                    ],
                ]
            );
            $this->add_control(
                'confirmation_background_color',
                [
                    'label'     => __( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .quform-success-message' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega__confirmation_text_typography',
                    'selector' => '{{WRAPPER}} .quform-success-message',
                ]
            );            
        $this->end_controls_section(); // Input error style tab end
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        if (!$settings['contact_form_list']) {
            echo '<p>'.__('Please select Contact Form', 'htmega-addons').'</p>';
        }else{
            $form_attributes = [
                'id' => $settings['contact_form_list'],
            ];
            $this->add_render_attribute( 'shortcode', $form_attributes );
            echo do_shortcode( sprintf( '[quform %s]', $this->get_render_attribute_string( 'shortcode' ) ) );
        }

    }

}

