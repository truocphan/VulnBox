<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Gravity_Forms extends Widget_Base {

    public function get_name() {
        return 'htmega-gravityforms-addons';
    }
    
    public function get_title() {
        return __( 'Gravity Forms', 'htmega-addons' );
    }

    public function get_keywords() {
        return [ 'form', 'contact', 'gravity', 'contact form','gravity form','htmega' ];
    }

    public function get_icon() {
        return 'htmega-icon eicon-mail';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/forms-widgets/gravity-forms-widget/';
	}

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function htmega_gravity_forms_options() {
        if ( class_exists( 'GFCommon' ) ) {
            $contact_forms = \RGFormsModel::get_forms( null, 'title' );
            $form_options = ['0' => esc_html__( 'Select Form', 'htmega-addons' )];
            if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {
                foreach ( $contact_forms as $form ) {   
                    $form_options[ $form->id ] = $form->title;
                }
            }
        } else {
            $form_options = ['0' => esc_html__( 'Form Not Found!', 'htmega-addons' ) ];
        }

        return $form_options;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'gravityforms_content',
            [
                'label' => __( 'Gravity Forms', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'gravity_form',
                [
                    'label'   => esc_html__( 'Select Form', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => $this->htmega_gravity_forms_options(),
                ]
            );

            $this->add_control(
                'show_title',
                [
                    'label'        => __( 'Show Title', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'no',
                    'label_on'     => __( 'Show', 'htmega-addons' ),
                    'label_off'    => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                ]
            );
            
            $this->add_control(
                'show_description',
                [
                    'label'        => __( 'Show Description', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'no',
                    'label_on'     => __( 'Show', 'htmega-addons' ),
                    'label_off'    => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                ]
            );
            
            $this->add_control(
                'form_ajax',
                [
                    'label'        => __( 'From Ajax', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'no',
                    'label_on'     => __( 'Yes', 'htmega-addons' ),
                    'label_off'    => __( 'No', 'htmega-addons' ),
                    'return_value' => 'yes',
                ]
            );
            
        $this->end_controls_section();


        // Title Style tab section
        $this->start_controls_section(
            'gravityforms_title_style',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=> [
                    'show_title'=>'yes',
                ],
            ]
        );

            $this->add_control(
                'gravityforms_title_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gform_heading .gform_title'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'gravityforms_title_typography',
                    'selector' => '{{WRAPPER}} .gform_wrapper .gform_heading .gform_title',
                ]
            );
            $this->add_responsive_control(
                'title_align',
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
                        '{{WRAPPER}} .gform_wrapper .gform_heading .gform_title' => 'text-align: {{VALUE}};',
                    ],
                ]
            );    
        $this->end_controls_section();

        // Description Style tab section
        $this->start_controls_section(
            'gravityforms_description_style',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=> [
                    'show_description'=>'yes',
                ],
            ]
        );

            $this->add_control(
                'gravityforms_description_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gform_heading .gform_description'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'gravityforms_description_typography',
                    'selector' => '{{WRAPPER}} .gform_wrapper .gform_heading .gform_description',
                ]
            );
            $this->add_responsive_control(
                'title_description_align',
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
                        '{{WRAPPER}} .gform_heading' => 'text-align: {{VALUE}};',
                    ],
                ]
            );    
        $this->end_controls_section();

        // Lavel Style tab section
        $this->start_controls_section(
            'gravityforms_label_style',
            [
                'label' => __( 'Labels', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                        '{{WRAPPER}} .gfield' => 'text-align: {{VALUE}};',
                    ],
                ]
            ); 
            $this->add_control(
                'gravityforms_label_background',
                [
                    'label'     => __( 'Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gfield label, {{WRAPPER}} .gform_wrapper .gfield_label'   => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'gravityforms_label_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gfield label,{{WRAPPER}} .gform_wrapper .gfield_label'   => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'gravityforms_required_text_color',
                [
                    'label'     => __( 'Required Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gfield_required'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'gravityforms_label_typography',
                    'selector' => '{{WRAPPER}} .gform_wrapper .gfield label,{{WRAPPER}} .gform_wrapper .gfield_label',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'gravityforms_label_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .gform_wrapper .gfield label,{{WRAPPER}} .gform_wrapper .gfield_label',
                ]
            );

            $this->add_responsive_control(
                'gravityforms_label_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gfield label,{{WRAPPER}} .gform_wrapper .gfield_label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'gravityforms_label_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gfield label,{{WRAPPER}} .gform_wrapper .gfield_label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'gravityforms_label_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gfield label,{{WRAPPER}} .gform_wrapper .gfield_label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'description_headline',
                [
                    'label' => __( 'Description Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'htmega_description_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#212529',
                    'selectors' => [
                        '{{WRAPPER}} .gfield_description' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_description_typography',
                    'selector' => '{{WRAPPER}} .gfield_description',
                ]
            );

            $this->add_responsive_control(
                'htmega_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .gfield_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

   
        $this->end_controls_section();

        // Style Input tab section
        $this->start_controls_section(
            'gravityforms_input_style_section',
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
                        'gravityforms_input_background_color',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gfield input[type="text"], {{WRAPPER}} .gform_wrapper .gfield textarea, {{WRAPPER}} .gform_wrapper .gfield select,{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'gravityforms_input_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .gform_wrapper .gfield input[type="text"], {{WRAPPER}} .gform_wrapper .gfield textarea, {{WRAPPER}} .gform_wrapper .gfield select,{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_control(
                        'htmega_input_placeholder_color',
                        [
                            'label' => __( 'Placeholder Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .gform_wrapper .gfield input::-webkit-input-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .gform_wrapper .gfield input::-moz-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .gform_wrapper .gfield input::-ms-input-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .gform_wrapper .gfield textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .gform_wrapper .gfield textarea::-moz-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .gform_wrapper .gfield textarea::-ms-input-placeholder' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'gravityforms_input_typography',
                            'selector' => '{{WRAPPER}} .gform_wrapper .gfield input[type="text"], {{WRAPPER}} .gform_wrapper .gfield textarea, {{WRAPPER}} .gform_wrapper .gfield select,{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])',
                        ]
                    );

                    $this->add_responsive_control(
                        'gravityforms_input_height',
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
                                '{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'height: {{SIZE}}{{UNIT}}',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'gravityforms_textarea_height',
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
                                '{{WRAPPER}} .gform_wrapper .gfield textarea' => 'height: {{SIZE}}{{UNIT}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'gravityforms_input_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gfield input[type="text"], {{WRAPPER}} .gform_wrapper .gfield textarea, {{WRAPPER}} .gform_wrapper .gfield select,{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'gravityforms_input_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gfield' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'gravityforms_input_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), 
                            {{WRAPPER}} .gform_wrapper .gfield textarea,{{WRAPPER}} .gform_wrapper .gfield select',
                        ]
                    );

                    $this->add_responsive_control(
                        'gravityforms_input_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), 
                            {{WRAPPER}} .gform_wrapper .gfield textarea,{{WRAPPER}} .gform_wrapper .gfield select' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'htmega_input_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), 
                            {{WRAPPER}} .gform_wrapper .gfield textarea,{{WRAPPER}} .gform_wrapper .gfield select',
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
                                '{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), 
                                {{WRAPPER}} .gform_wrapper .gfield textarea,{{WRAPPER}} .gform_wrapper .gfield select' => 'text-align: {{VALUE}};',
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
                        'gravityforms_input_background_color_focus',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gfield input[type="text"]:focus, {{WRAPPER}} .gform_wrapper .gfield textarea, {{WRAPPER}} .gform_wrapper .gfield select:focus,{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'gravityforms_input_color_focus',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .gform_wrapper .gfield input[type="text"]:focus, {{WRAPPER}} .gform_wrapper .gfield textarea, {{WRAPPER}} .gform_wrapper .gfield select:focus,{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'gravityforms_input_border_focus',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, 
                            {{WRAPPER}} .gform_wrapper .gfield textarea:focus,{{WRAPPER}} .gform_wrapper .gfield select:focus',
                        ]
                    );

                    $this->add_responsive_control(
                        'gravityforms_input_border_radius_focus',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, 
                            {{WRAPPER}} .gform_wrapper .gfield textarea:focus,{{WRAPPER}} .gform_wrapper .gfield select:focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'htmega_input_box_shadow_focus',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, 
                            {{WRAPPER}} .gform_wrapper .gfield textarea:focus,{{WRAPPER}} .gform_wrapper .gfield select:focus',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section(); // Form input style

        // Input submit button style tab start
        $this->start_controls_section(
            'gravityforms_inputsubmit_style',
            [
                'label'     => __( 'Button', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs('gravityforms_submit_style_tabs');

                // Button Normal tab start
                $this->start_controls_tab(
                    'gravityforms_submit_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'gravityforms_input_submit_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'max' => 200,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]' => 'height: {{SIZE}}{{UNIT}};',
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
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'gravityforms_input_submit_typography',
                            'selector' => '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]',
                        ]
                    );

                    $this->add_control(
                        'gravityforms_input_submit_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'gravityforms_input_submit_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'gravityforms_input_submit_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'gravityforms_input_submit_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'gravityforms_input_submit_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]',
                        ]
                    );

                    $this->add_responsive_control(
                        'gravityforms_input_submit_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'gravityforms_input_submit_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]',
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
                                ]
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer' => 'justify-content: {{VALUE}};',
                            ],
                        ]
                    ); 
                $this->end_controls_tab(); // Button Normal tab end

                // Button Hover tab start
                $this->start_controls_tab(
                    'gravityforms_submit_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'gravityforms_input_submithover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]:hover'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'gravityforms_input_submithover_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]:hover'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'gravityforms_input_submithover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .gform_wrapper .gform_footer input[type="submit"]:hover',
                        ]
                    );

                $this->end_controls_tab(); // Button Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Input submit button style tab end
        // Input error style tab start
        $this->start_controls_section(
            'htmega_input_error_style',
            [
                'label'     => __( 'Errors and Confirmation Style', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'htmega_error_text_color',
                [
                    'label'     => __( 'Error Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gfield_error .gfield_repeater_cell label, {{WRAPPER}} .gform_wrapper .gfield_error label, {{WRAPPER}} .gform_wrapper .gfield_error legend, {{WRAPPER}} .gform_wrapper .gfield_validation_message, {{WRAPPER}} .gform_wrapper .validation_message, {{WRAPPER}} .gform_wrapper [aria-invalid=true]+label, {{WRAPPER}} .gform_wrapper label+[aria-invalid=true],{{WRAPPER}} .validation_message,{{WRAPPER}} .gform_wrapper .gform_validation_errors>h2'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_error_text_typography',
                    'selector' => '{{WRAPPER}} .validation_message,{{WRAPPER}} .gform_wrapper .gform_validation_errors>h2',
                ]
            );
            $this->add_control(
                'htmega_error_border_color',
                [
                    'label'     => __( 'Border Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gform_wrapper .gfield_validation_message, {{WRAPPER}} .gform_wrapper .validation_message,{{WRAPPER}} .gform_wrapper .gfield_error [aria-invalid=true],{{WRAPPER}} .gform_wrapper .gform_validation_errors'  => 'border-color: {{VALUE}}!important;',
                    ],
                ]
            );
            // Feedback style
            $this->add_control(
                'htmega_error_submit_feedback_style',
                [
                    'label' => __( 'Confirmation Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'htmega_feedback_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .gform_confirmation_message'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_feedback_text_typography',
                    'selector' => '{{WRAPPER}} .gform_confirmation_message',
                ]
            );            
        $this->end_controls_section(); // Input error style tab end
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

       $form_attributes = [
            'id' => $settings['gravity_form'],
            'ajax' => ( $settings['form_ajax'] == 'yes' ) ? 'true' : 'false',
            'title' => ( $settings['show_title'] == 'yes' ) ? 'true' : 'false',
            'description' => ( $settings['show_description'] == 'yes' ) ? 'true' : 'false',
        ];

        $this->add_render_attribute( 'shortcode', $form_attributes );
        
        echo do_shortcode( sprintf( '[gravityform %s]', $this->get_render_attribute_string( 'shortcode' ) ) );

    }

}