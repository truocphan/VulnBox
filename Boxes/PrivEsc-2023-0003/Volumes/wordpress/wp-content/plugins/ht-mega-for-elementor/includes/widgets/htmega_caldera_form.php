<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Caldera_Form extends Widget_Base {

    public function get_name() {
        return 'htmega-calderaform-addons';
    }
    
    public function get_title() {
        return __( 'Caldera Form', 'htmega-addons' );
    }

    public function get_keywords() {
        return [ 'form', 'contact', 'caldera', 'contact form','caldera form','htmega' ];
    }

    public function get_icon() {
        return 'htmega-icon eicon-mail';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }
    public function get_help_url() {
		return 'https://wphtmega.com/docs/forms-widgets/caldera-forms-widget/';
	}
    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'calderaform_content',
            [
                'label' => __( 'Caldera Form', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'caldera_form_list',
                [
                    'label'   => __( 'Select Form', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => htmega_caldera_forms_options(),
                ]
            );
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'calderaform_style_section',
            [
                'label' => __( 'Labels', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'labels_align',
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
                    '{{WRAPPER}} .form-group' => 'text-align: {{VALUE}};',
                ],
            ]
        ); 
            $this->add_control(
                'label_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .caldera_forms_form label.control-label,{{WRAPPER}} .caldera-grid label' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'label_required_color',
                [
                    'label'     => __( 'Required Symbol Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .field_required'   => 'color: {{VALUE}}!important;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'label_typography',
                    'label'    => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .caldera_forms_form label.control-label,{{WRAPPER}} .caldera-grid label',
                ]
            );
            $this->add_responsive_control(
                'labels_space',
                [
                    'label'   => __( 'Space(px)', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .caldera_forms_form label.control-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            // Description style
            $this->add_control(
                'htmega_form_description_heading',
                [
                    'label' => __( 'Description Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'description_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .help-block' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'description_typography',
                    'label'    => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .help-block',
                ]
            );
            $this->add_responsive_control(
                'descriptions_space',
                [
                    'label'   => __( 'Space(px)', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .help-block,{{WRAPPER}} .caldera-grid .help-block' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Input Field Style
        $this->start_controls_section(
            'calderaform_input_style_section',
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
                    $this->add_control(
                        'input_text_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .caldera_forms_form input.form-control'    => 'color: {{VALUE}};',
                                '{{WRAPPER}} .caldera_forms_form select.form-control'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .caldera_forms_form textarea.form-control' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'input_placeholder_color',
                        [
                            'label'     => __( 'Placeholder Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .caldera_forms_form input.form-control::placeholder'    => 'color: {{VALUE}};',
                                '{{WRAPPER}} .caldera_forms_form select.form-control::placeholder'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .caldera_forms_form textarea.form-control::placeholder' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'input_text_background',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .caldera_forms_form input.form-control'    => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .caldera_forms_form select.form-control'   => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .caldera_forms_form textarea.form-control' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'forms_input_height',
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
                                '{{WRAPPER}} .caldera_forms_form input.form-control, 
                                {{WRAPPER}} .caldera_forms_form select.form-control' => 'height: {{SIZE}}{{UNIT}}',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'forms_textarea_height',
                        [
                            'label'             => __( 'Textarea Height', 'htmega-addons' ),
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
                                '{{WRAPPER}} .caldera_forms_form textarea.form-control' => 'height: {{SIZE}}{{UNIT}}',
                            ],
                        ]
                    );
                    $this->add_control(
                        'input_padding',
                        [
                            'label'      => __( 'Padding', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .caldera_forms_form input.form-control, 
                                {{WRAPPER}} .caldera_forms_form textarea.form-control, 
                                {{WRAPPER}} .caldera_forms_form select.form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'input_space',
                        [
                            'label'   => __( 'Input Space', 'htmega-addons' ),
                            'type'    => Controls_Manager::SLIDER,
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
                                '{{WRAPPER}} .caldera_forms_form .row:not(.last_row) .form-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(), [
                            'name'        => 'input_border',
                            'label'       => __( 'Border', 'htmega-addons' ),
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} .caldera_forms_form input.form-control, {{WRAPPER}} .caldera_forms_form textarea.form-control, {{WRAPPER}} .caldera_forms_form select.form-control',
                            
                        ]
                    );

                    $this->add_control(
                        'input_border_radius',
                        [
                            'label'      => __( 'Border Radius', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .caldera_forms_form input.form-control'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .caldera_forms_form textarea.form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .caldera_forms_form select.form-control'   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'htmega_input_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .caldera_forms_form input.form-control, {{WRAPPER}} .caldera_forms_form textarea.form-control, {{WRAPPER}} .caldera_forms_form select.form-control',
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
                                '{{WRAPPER}} .caldera_forms_form input.form-control, {{WRAPPER}} .caldera_forms_form textarea.form-control, {{WRAPPER}} .caldera_forms_form select.form-control' => 'text-align: {{VALUE}};',
                            ],
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
                            'input_text_color_focus',
                            [
                                'label'     => __( 'Text Color', 'htmega-addons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .caldera_forms_form input.form-control:focus'    => 'color: {{VALUE}};',
                                    '{{WRAPPER}} .caldera_forms_form select.form-control:focus'   => 'color: {{VALUE}};',
                                    '{{WRAPPER}} .caldera_forms_form textarea.form-control:focus' => 'color: {{VALUE}};',
                                ],
                            ]
                        );
                        $this->add_control(
                            'input_text_background_focus',
                            [
                                'label'     => __( 'Background Color', 'htmega-addons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .caldera_forms_form input.form-control:focus'    => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}} .caldera_forms_form select.form-control:focus'   => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}} .caldera_forms_form textarea.form-control:focus' => 'background-color: {{VALUE}};',
                                ],
                            ]
                        );
                        $this->add_group_control(
                            Group_Control_Border::get_type(), [
                                'name'        => 'input_border_focus',
                                'label'       => __( 'Border', 'htmega-addons' ),
                                'placeholder' => '1px',
                                'default'     => '1px',
                                'selector'    => '{{WRAPPER}} .caldera_forms_form input.form-control:focus, {{WRAPPER}} .caldera_forms_form textarea.form-control:focus, {{WRAPPER}} .caldera_forms_form select.form-control:focus',
                                
                            ]
                        );
    
                        $this->add_control(
                            'input_border_radius_focus',
                            [
                                'label'      => __( 'Border Radius', 'htmega-addons' ),
                                'type'       => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors'  => [
                                    '{{WRAPPER}} .caldera_forms_form input.form-control:focus,{{WRAPPER}} .caldera_forms_form select.form-control:focus,{{WRAPPER}} .caldera_forms_form textarea.form-control:focus'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'separator' => 'before',
                            ]
                        );
                        $this->add_group_control(
                            Group_Control_Box_Shadow::get_type(),
                            [
                                'name' => 'htmega_input_box_shadow_focus',
                                'label' => __( 'Box Shadow', 'htmega-addons' ),
                                'selector' => '{{WRAPPER}} .caldera_forms_form input.form-control:focus, {{WRAPPER}} .caldera_forms_form textarea.form-control:focus, {{WRAPPER}} .caldera_forms_form select.form-control:focus',
                            ]
                        );

                    $this->end_controls_tab();
                 $this->end_controls_tabs();
        $this->end_controls_section();

        // Submit Button
        $this->start_controls_section(
            'form_style_submit_button',
            [
                'label' => __( 'Submit Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'tabs_button_style' );

                // Button Normal
                $this->start_controls_tab(
                    'form_tab_button_normal',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'button_text_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"],{{WRAPPER}} .caldera-grid .btn-block' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"],{{WRAPPER}} .caldera-grid .btn-block' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'button_typography',
                            'label'     => __( 'Typography', 'htmega-addons' ),
                            'selector'  => '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"],{{WRAPPER}} .caldera-grid .btn-block',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'border',
                            'label'       => __( 'Border', 'htmega-addons' ),
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"],{{WRAPPER}} .caldera-grid .btn-block',
                            'separator'   => 'before',
                        ]
                    );

                    $this->add_control(
                        'border_radius',
                        [
                            'label'      => __( 'Border Radius', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"],{{WRAPPER}} .caldera-grid .btn-block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'button_padding',
                        [
                            'label'      => __( 'Padding', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'separator'  => 'before',
                            'selectors'  => [
                                '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn, {{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"],{{WRAPPER}} .caldera-grid .btn-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"],{{WRAPPER}} .caldera-grid .btn-block' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'button_box_shadow',
                            'selector' => '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"],{{WRAPPER}} .caldera-grid .btn-block',
                        ]
                    );
                $this->end_controls_tab();

                // Button Hover
                $this->start_controls_tab(
                    'tab_button_hover',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'hover_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn:hover,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"]:hover,{{WRAPPER}} .caldera-grid .btn-block:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_background_hover_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn:hover,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"]:hover,{{WRAPPER}} .caldera-grid .btn-block:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_border_color:hover',
                        [
                            'label'     => __( 'Border Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'condition' => [
                                'border_border!' => '',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn:hover,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"]:hover,{{WRAPPER}} .caldera-grid .btn-block:hover' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'button_box_shadow_hover',
                            'selector' => '{{WRAPPER}} .caldera_forms_form input[type="submit"].btn:hover,{{WRAPPER}} .caldera_forms_form .cf-page-btn-next[type*="button"]:hover,{{WRAPPER}} .caldera-grid .btn-block:hover',
                        ]
                    );
                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
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
                    'label'     => __( 'Error Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .caldera-grid .has-error .checkbox, {{WRAPPER}} .caldera-grid .has-error .checkbox-inline, {{WRAPPER}} .caldera-grid .has-error .control-label, {{WRAPPER}} .caldera-grid .has-error .help-block, {{WRAPPER}} .caldera-grid .has-error .radio, {{WRAPPER}} .caldera-grid .has-error .radio-inline, {{WRAPPER}} .caldera-grid .has-error.checkbox label, {{WRAPPER}} .caldera-grid .has-error.checkbox-inline label, {{WRAPPER}} .caldera-grid .has-error.radio label, {{WRAPPER}} .caldera-grid .has-error.radio-inline label,{{WRAPPER}} .caldera-grid .alert-danger, {{WRAPPER}} .caldera-grid .alert-error'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_error_text_typography',
                    'selector' => '{{WRAPPER}} .caldera-grid .has-error .checkbox, {{WRAPPER}} .caldera-grid .has-error .checkbox-inline, {{WRAPPER}} .caldera-grid .has-error .control-label, {{WRAPPER}} .caldera-grid .has-error .help-block, {{WRAPPER}} .caldera-grid .has-error .radio, {{WRAPPER}} .caldera-grid .has-error .radio-inline, {{WRAPPER}} .caldera-grid .has-error.checkbox label, {{WRAPPER}} .caldera-grid .has-error.checkbox-inline label, {{WRAPPER}} .caldera-grid .has-error.radio label, {{WRAPPER}} .caldera-grid .has-error.radio-inline label,{{WRAPPER}} .caldera-grid .alert-danger, {{WRAPPER}} .caldera-grid .alert-error',
                ]
            );
            $this->add_control(
                'htmega_error_border_color',
                [
                    'label'     => __( 'Border Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} input.form-control.parsley-error,{{WRAPPER}} select.form-control.parsley-error, {{WRAPPER}} textarea.form-control.parsley-error,{{WRAPPER}} .caldera-grid .has-error .form-control,{{WRAPPER}} .caldera-grid .alert-danger, {{WRAPPER}} .caldera-grid .alert-error'  => 'border-color: {{VALUE}}!important;',
                    ],
                ]
            );
            $this->add_control(
                'error_background_hover_color',
                [
                    'label'     => __( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} input.form-control.parsley-error,{{WRAPPER}} select.form-control.parsley-error, {{WRAPPER}} textarea.form-control.parsley-error,{{WRAPPER}} .caldera-grid .alert-danger, {{WRAPPER}} .caldera-grid .alert-error' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            // Validation style
            $this->add_control(
                'htmega_success_style',
                [
                    'label' => __( 'Validation Pass Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'htmega_validation_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} input.form-control.parsley-success, {{WRAPPER}} select.form-control.parsley-success,{{WRAPPER}} textarea.form-control.parsley-success'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'htmega_validation_border_color',
                [
                    'label'     => __( 'Border Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} input.form-control.parsley-success, {{WRAPPER}} select.form-control.parsley-success,{{WRAPPER}} textarea.form-control.parsley-success'  => 'border-color: {{VALUE}}!important;',
                    ],
                ]
            );
            $this->add_control(
                'validationr_background_color',
                [
                    'label'     => __( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} input.form-control.parsley-success, {{WRAPPER}} select.form-control.parsley-success,{{WRAPPER}} textarea.form-control.parsley-success' => 'background-color: {{VALUE}};',
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
                        '{{WRAPPER}} .caldera-grid .alert-success'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'htmega_confirmation_border_color',
                [
                    'label'     => __( 'Border Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .caldera-grid .alert-success'  => 'border-color: {{VALUE}}!important;',
                    ],
                ]
            );
            $this->add_control(
                'confirmation_background_color',
                [
                    'label'     => __( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .caldera-grid .alert-success' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega__confirmation_text_typography',
                    'selector' => '{{WRAPPER}} .caldera-grid .alert-success',
                ]
            );            
        $this->end_controls_section(); // Input error style tab end
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $calderaform_attributes = [
            'id' => $settings['caldera_form_list'],
        ];
        $this->add_render_attribute( 'shortcode', $calderaform_attributes );

        if ( !$settings['caldera_form_list'] ) {
            echo '<div class="htmega-notices"><p>'.__('Please select a Contact Form From Setting!', 'htmega-addons').'</p></div>';
        }else{
            echo do_shortcode( sprintf( '[caldera_form %s]', $this->get_render_attribute_string( 'shortcode' ) ) );
        }
        

    }

}

