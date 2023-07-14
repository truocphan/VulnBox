<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_WPforms extends Widget_Base {

    public function get_name() {
        return 'htmega-wpforms-addons';
    }
    
    public function get_title() {
        return __( 'WP Form', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-mail';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_keywords() {
        return [ 'form', 'contact', 'wpforms','ht mega','htmega' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/forms-widgets/wp-forms-widget/';
	}

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function htmega_wpforms_forms(){
        $formlist = array();
        $forms_args = array( 'posts_per_page' => -1, 'post_type'=> 'wpforms' );
        $forms = get_posts( $forms_args );
        if( $forms ){
            foreach ( $forms as $form ){
                $formlist[$form->ID] = $form->post_title;
            }
        }else{
            $formlist[ __( 'Form not found', 'htmega-addons' ) ] = 0;
        }
        return $formlist;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'wpforms_content',
            [
                'label' => __( 'WP Form', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'contact_form_list',
                [
                    'label'             => esc_html__( 'Select Form', 'htmega-addons' ),
                    'type'              => Controls_Manager::SELECT,
                    'label_block'       => true,
                    'options'           => $this->htmega_wpforms_forms(),
                    'default'           => '0',
                ]
            );

            $this->add_control(
                'show_form_title',
                [
                    'label'                 => __( 'Title', 'htmega-addons' ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'no',
                    'label_on'              => __( 'Show', 'htmega-addons' ),
                    'label_off'             => __( 'Hide', 'htmega-addons' ),
                    'return_value'          => 'yes',
                ]
            );

            $this->add_control(
                'show_form_description',
                [
                    'label'                 => __( 'Description', 'htmega-addons' ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'no',
                    'label_on'              => __( 'Show', 'htmega-addons' ),
                    'label_off'             => __( 'Hide', 'htmega-addons' ),
                    'return_value'          => 'yes',
                ]
            );

        $this->end_controls_section();

        // Style Title tab section
        $this->start_controls_section(
            'wpforms_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_form_title'=>'yes',
                ],
            ]
        );
            
            $this->add_control(
                'wpforms_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#212529',
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-container .wpforms-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'wpforms_title_typography',
                    'selector' => '{{WRAPPER}} .wpforms-container .wpforms-title',
                ]
            );

            $this->add_responsive_control(
                'wpforms_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-container .wpforms-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'wpforms_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-container .wpforms-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'wpforms_title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .wpforms-container .wpforms-title',
                ]
            );

            $this->add_responsive_control(
                'wpforms_title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-container .wpforms-title' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
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
                        '{{WRAPPER}} .wpforms-container .wpforms-title' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section(); // Form title style

        // Style Description tab section
        $this->start_controls_section(
            'wpforms_description_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_form_description'=>'yes',
                ],
            ]
        );
            
            $this->add_control(
                'wpforms_description_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#212529',
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-container .wpforms-description' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'wpforms_description_typography',
                    'selector' => '{{WRAPPER}} .wpforms-container .wpforms-description',
                ]
            );

            $this->add_responsive_control(
                'wpforms_description_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-container .wpforms-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'wpforms_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-container .wpforms-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'wpforms_description_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .wpforms-container .wpforms-description',
                ]
            );

            $this->add_responsive_control(
                'wpforms_description_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-container .wpforms-description' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_responsive_control(
                'description_align',
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
                        '{{WRAPPER}} .wpforms-container .wpforms-description' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section(); // Form Description style


        // Label style tab start
        $this->start_controls_section(
            'wpforms_label_style',
            [
                'label'     => __( 'Labels', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'wpforms_label_align',
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
                        '{{WRAPPER}} .wpforms-form .wpforms-field-label,{{WRAPPER}} .wpforms-field-description' => 'text-align: {{VALUE}};',
                    ],
                ]
            );  
            $this->add_control(
                'wpforms_label_background',
                [
                    'label'     => __( 'Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-form .wpforms-field-label'   => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'wpforms_label_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-form .wpforms-field-label,{{WRAPPER}} .wpforms-form .wpforms-field label'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'wpforms_label_typogra',
                    'selector' => '{{WRAPPER}} .wpforms-form .wpforms-field-label,{{WRAPPER}} .wpforms-form .wpforms-field label',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'wpforms_label_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .wpforms-form .wpforms-field-label',
                ]
            );

            $this->add_responsive_control(
                'wpforms_label_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-form .wpforms-field-label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'wpforms_label_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-form .wpforms-field-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'wpforms_label_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-form .wpforms-field-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );
            // Description style
            $this->add_control(
                'wpforms_description_heading',
                [
                    'label' => __( 'Description Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'wpforms_description_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-field-description,{{WRAPPER}} .wpforms-field-number-slider-hint'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'wpforms_description_typogra',
                    'selector' => '{{WRAPPER}} .wpforms-field-description,{{WRAPPER}} .wpforms-form .wpforms-field-number-slider .wpforms-field-number-slider-hint',
                ]
            );
            $this->add_responsive_control(
                'wpforms_input_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-field-description,{{WRAPPER}} .wpforms-field-number-slider-hint' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            ); 
            // Required color style
            $this->add_control(
                'wpforms_required_heading',
                [
                    'label' => __( 'Required Label Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'wpforms_required_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-required-label'   => 'color: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section(); // // Label style tab end

        // Style Input tab section
        $this->start_controls_section(
            'wpforms_input_style_section',
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
                        'wpforms_input_background_color',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .wpforms-field select' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'wpforms_input_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#212529',
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .wpforms-field select' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_control(
                        'wpforms_input_placeholder_color',
                        [
                            'label' => __( 'Placeholder Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field input::-webkit-input-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wpforms-field input::-moz-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wpforms-field input::-ms-input-placeholder' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'wpforms_input_typography',
                            'selector' => '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .wpforms-field select',
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_input_height',
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
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):not([type=range]), {{WRAPPER}} .wpforms-field select' => 'height: {{SIZE}}{{UNIT}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_input_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):not([type=range])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .wpforms-field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_input_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .wpforms-field select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'wpforms_input_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .wpforms-field select',
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_input_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .wpforms-field select' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'wpforms_input_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .wpforms-field select',
                        ]
                    );
                    $this->add_responsive_control(
                        'wpforms_input_align',
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
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .wpforms-field select' => 'text-align: {{VALUE}};',
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
                        'wpforms_input_background_color_focus',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .wpforms-field select:focus' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'wpforms_input_color_focus',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#212529',
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .wpforms-field select:focus' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'wpforms_input_border_focus',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .wpforms-field select:focus',
                        ]
                    );
                    $this->add_responsive_control(
                        'wpforms_input_border_radius_focus',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .wpforms-field select:focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'wpforms_input_box_shadow_focus',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .wpforms-field select:focus',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section(); // Form input style

        // Style Textarea tab section
        $this->start_controls_section(
            'wpforms_textarea_style_section',
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
                    $this->add_control(
                        'wpforms_textarea_background_color',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field textarea' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'wpforms_textarea_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#212529',
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field textarea' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_control(
                        'wpforms_textarea_placeholder_color',
                        [
                            'label' => __( 'Placeholder Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wpforms-field textarea::-moz-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wpforms-field textarea::-ms-input-placeholder' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'wpforms_textarea_typography',
                            'selector' => '{{WRAPPER}} .wpforms-field textarea',
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_textarea_height',
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
                                '{{WRAPPER}} .wpforms-field textarea' => 'height: {{SIZE}}{{UNIT}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_textarea_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-field textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_textarea_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-field textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'wpforms_textarea_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-field textarea',
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_textarea_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-field textarea' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'wpforms_textarea_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-field textarea',
                        ]
                    );
                    $this->add_responsive_control(
                        'wpforms_textarea_align',
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
                                '{{WRAPPER}} .wpforms-field textarea' => 'text-align: {{VALUE}};',
                            ],
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
                        'wpforms_textarea_background_color_focus',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field textarea:focus' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'wpforms_textarea_color_focus',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#212529',
                            'selectors'         => [
                                '{{WRAPPER}} .wpforms-field textarea:focus' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'wpforms_textarea_border_focus',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-field textarea:focus',
                        ]
                    );
                    $this->add_responsive_control(
                        'wpforms_textarea_border_radius_focus',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-field textarea:focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'wpforms_textarea_box_shadow_focus',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-field textarea:focus',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section(); // Form input style


        // Input submit button style tab start
        $this->start_controls_section(
            'wpforms_inputsubmit_style',
            [
                'label'     => __( 'Button', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs('wpforms_submit_style_tabs');

                // Button Normal tab start
                $this->start_controls_tab(
                    'wpforms_submit_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'wpforms_input_submit_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-form button[type="submit"]' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'wpforms_input_submit_width',
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
                                '{{WRAPPER}} .wpforms-form button[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'wpforms_input_submit_typography',
                            'selector' => '{{WRAPPER}} .wpforms-form button[type="submit"]',
                        ]
                    );

                    $this->add_control(
                        'wpforms_input_submit_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-form button[type="submit"]'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'wpforms_input_submit_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-form button[type="submit"]'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_input_submit_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-form button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_input_submit_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-form button[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'wpforms_input_submit_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-form button[type="submit"]',
                        ]
                    );

                    $this->add_responsive_control(
                        'wpforms_input_submit_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-form button[type="submit"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'wpforms_input_submit_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-form button[type="submit"]',
                        ]
                    );
                    $this->add_responsive_control(
                        'wpforms_input_submit_align',
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
                                '{{WRAPPER}} .wpforms-form .wpforms-submit-container' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );   
                $this->end_controls_tab(); // Button Normal tab end

                // Button Hover tab start
                $this->start_controls_tab(
                    'wpforms_submit_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'wpforms_input_submithover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-form button[type="submit"]:hover'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'wpforms_input_submithover_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wpforms-form button[type="submit"]:hover'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'wpforms_input_submithover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-form button[type="submit"]:hover',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'wpforms_input_submit_box_shadow_hover',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .wpforms-form button[type="submit"]:hover',
                        ]
                    );
                $this->end_controls_tab(); // Button Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Input submit button style tab end

        // Input error style tab start
        $this->start_controls_section(
            'wpforms_input_error_style',
            [
                'label'     => __( 'Errors Style', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'wpforms_error_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpforms-error'  => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'wpforms_error_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .wpforms-form .wpforms-field textarea.wpforms-error,{{WRAPPER}} .wpforms-form .wpforms-field input.wpforms-error',
                ]
            );

        $this->end_controls_section(); // Input error style tab end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        if ( !$settings['contact_form_list'] ) {
            echo '<p>'.esc_html__('Please Select form.','htmega-addons').'</p>';
        }else{
            $show_form_title = $settings['show_form_title'];
            $show_form_description = $settings['show_form_description'];
            echo wpforms_display( $settings['contact_form_list'], $show_form_title, $show_form_description );
        }

    }

}

