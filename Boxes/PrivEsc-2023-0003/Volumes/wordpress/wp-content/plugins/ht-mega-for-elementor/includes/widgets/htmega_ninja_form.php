<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Ninja_Form extends Widget_Base {

    public function get_name() {
        return 'htmega-ninjaform-addons';
    }
    
    public function get_title() {
        return __( 'Ninja Form', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-mail';
    }

    public function get_keywords() {
        return [ 'form', 'contact', 'ninja', 'contact form','ninja form','htmega' ];
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/forms-widgets/ninja-form-widget/';
	}

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function htmega_ninja_forms_list() {
        $form_options = array();
        if ( class_exists( 'Ninja_Forms' ) ) {
            $ninja_forms  = Ninja_Forms()->form()->get_forms();
            if ( ! empty( $ninja_forms ) && ! is_wp_error( $ninja_forms ) ) {
                $form_options = ['0' => esc_html__( 'Select Form', 'htmega-addons' )];
                foreach ( $ninja_forms as $form ) {   
                    $form_options[ $form->get_id() ] = $form->get_setting( 'title' );
                }
            }
        } else {
            $form_options = ['0' => esc_html__( 'Form Not Found.', 'htmega-addons' ) ];
        }
        return $form_options;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'ninjaform_content',
            [
                'label' => __( 'Ninja Form', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'ninja_form',
                [
                    'label'   => esc_html__( 'Select Form', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => $this->htmega_ninja_forms_list(),
                ]
            );
            
        $this->end_controls_section();

        $this->start_controls_section(
            'ninjaform_opation_content',
            [
                'label' => __( 'Options', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'show_form_title',
                [
                    'label'                 => __( 'Title Hide', 'htmega-addons' ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} span.nf-form-title' => 'display: none;',
                    ],
                ]
            );

            $this->add_control(
                'show_form_description',
                [
                    'label'                 => __( 'Description Hide', 'htmega-addons' ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .nf-before-form-content' => 'display: none;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Title tab section
        $this->start_controls_section(
            'ninjaform_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_form_title!'=>'yes',
                ],
            ]
        );
            
            $this->add_control(
                'ninjaform_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#212529',
                    'selectors' => [
                        '{{WRAPPER}} span.nf-form-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'ninjaform_title_typography',
                    'selector' => '{{WRAPPER}} span.nf-form-title',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} span.nf-form-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} span.nf-form-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'ninjaform_title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} span.nf-form-title',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} span.nf-form-title' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_responsive_control(
                'ninjaformninjaform_title_align',
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
                        '{{WRAPPER}} span.nf-form-title' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section(); // Form title style

        // Style Description tab section
        $this->start_controls_section(
            'ninjaform_description_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_form_description!'=>'yes',
                ],
            ]
        );
            
            $this->add_control(
                'ninjaform_description_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#212529',
                    'selectors' => [
                        '{{WRAPPER}} .nf-before-form-content' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'ninjaform_description_typography',
                    'selector' => '{{WRAPPER}} .nf-before-form-content',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_description_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .nf-before-form-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .nf-before-form-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'ninjaform_description_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .nf-before-form-content',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_description_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .nf-before-form-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_responsive_control(
                'ninjaform_description_align',
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
                        '{{WRAPPER}} .nf-before-form-content' => 'text-align: {{VALUE}};',
                    ],
                ]
            );  
        $this->end_controls_section(); // Form Description style

        // Label style tab start
        $this->start_controls_section(
            'ninjaform_label_style',
            [
                'label'     => __( 'Labels', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'htmega_label_align',
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
                        '{{WRAPPER}} .nf-field-label label,{{WRAPPER}} .nf-field-element label,{{WRAPPER}} .nf-field-description,{{WRAPPER}} .nf-field,{{WRAPPER}} .nf-field-label,{{WRAPPER}} .field-wrap, {{WRAPPER}} .nf-error' => 'text-align: {{VALUE}}; justify-content:{{VALUE}};',
                    ],
                ]
            );  
            $this->add_control(
                'ninjaform_label_background',
                [
                    'label'     => __( 'Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nf-field-label label,{{WRAPPER}} .nf-field-element label'   => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .nf-field-element label'   => 'display: inline-block;',
                    ],
                ]
            );

            $this->add_control(
                'ninjaform_label_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nf-field-label label,{{WRAPPER}} .nf-field-element label'   => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'ninjaform_label_required_color',
                [
                    'label'     => __( 'Required Symbol Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ninja-forms-req-symbol'   => 'color: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'ninjaform_label_typography',
                    'selector' => '{{WRAPPER}} .nf-field-label label,{{WRAPPER}} .nf-field-element label',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'ninjaform_label_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .nf-field-label label,{{WRAPPER}} .nf-field-element label',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_label_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .nf-field-label label,{{WRAPPER}} .nf-field-element label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_label_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .nf-field-label label,{{WRAPPER}} .nf-field-element label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_label_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .nf-field-label label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
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
                'htmega_form_description_text_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nf-field-description'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_form_description_typogra',
                    'selector' => '{{WRAPPER}} .nf-field-description',
                ]
            );
            $this->add_responsive_control(
                'htmega_form_input_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .nf-field-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            ); 
        $this->end_controls_section(); // // Label style tab end

        // Style Input tab section
        $this->start_controls_section(
            'ninjaform_input_style_section',
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
                'ninjaform_input_background_color',
                [
                    'label' => __( 'Background Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors'         => [
                        '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .nf-form-layout .nf-field select' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'ninjaform_input_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#212529',
                    'selectors'         => [
                        '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .nf-form-layout .nf-field select' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'htmega_input_placeholder_color',
                [
                    'label' => __( 'Placeholder Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors'         => [
                        '{{WRAPPER}} .nf-form-layout .nf-field input::-webkit-input-placeholder' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .nf-form-layout .nf-field input::-moz-placeholder' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .nf-form-layout .nf-field input::-ms-input-placeholder' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'ninjaform_input_typography',
                    'selector' => '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .nf-form-layout .nf-field select',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_input_height',
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
                        '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .nf-form-layout .nf-field select' => 'height: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'ninjaform_input_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .nf-form-layout .nf-field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_input_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .nf-form-layout .nf-field select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'ninjaform_input_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .nf-form-layout .nf-field select',
                ]
            );

            $this->add_responsive_control(
                'ninjaform_input_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .nf-form-layout .nf-field select' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'htmega_input_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]),{{WRAPPER}} .nf-form-layout .nf-field select',
                ]
            );
            $this->add_responsive_control(
                'htmega_input_align',
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
                        '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]),{{WRAPPER}} .nf-form-layout .nf-field select' => 'text-align: {{VALUE}};',
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
                        'ninjaform_input_background_color_focus',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .nf-form-layout .nf-field select:focus' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'ninjaform_input_color_focus',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .nf-form-layout .nf-field select:focus' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ninjaform_input_border_focus',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .nf-form-layout .nf-field select:focus',
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_input_border_radius_focus',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .nf-form-layout .nf-field select:focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'htmega_input_box_shadow_focus',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .nf-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]),{{WRAPPER}} .nf-form-layout .nf-field select:focus',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section(); // Form input style

        // Style Textarea tab section
        $this->start_controls_section(
            'ninjaform_textarea_style_section',
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
                        'ninjaform_textarea_background_color',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors'         => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'ninjaform_textarea_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#212529',
                            'selectors'         => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_control(
                        'htmega_textarea_placeholder_color',
                        [
                            'label' => __( 'Placeholder Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea::-moz-placeholder' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea::-ms-input-placeholder' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'ninjaform_textarea_typography',
                            'selector' => '{{WRAPPER}} .nf-form-layout .nf-field textarea',
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_textarea_height',
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
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea' => 'height: {{SIZE}}{{UNIT}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_textarea_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_textarea_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ninjaform_textarea_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .nf-field textarea',
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_textarea_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'htmega_textarea_align',
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
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );  
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'htmega_textarea_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .nf-field textarea',
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
                        'ninjaform_textarea_background_color_focus',
                        [
                            'label' => __( 'Background Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea:focus' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'ninjaform_textarea_color_focus',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors'         => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea:focus' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ninjaform_textarea_border_focus',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .nf-field textarea:focus',
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_textarea_border_radius_focus',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .nf-field textarea:focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'htmega_textarea_box_shadow_focus',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .nf-field textarea:focus',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section(); // Form input style


        // Input submit button style tab start
        $this->start_controls_section(
            'ninjaform_inputsubmit_style',
            [
                'label'     => __( 'Button', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs('ninjaform_submit_style_tabs');

                // Button Normal tab start
                $this->start_controls_tab(
                    'ninjaform_submit_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'ninjaform_input_submit_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'max' => 200,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'ninjaform_input_submit_typography',
                            'selector' => '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]',
                        ]
                    );

                    $this->add_control(
                        'ninjaform_input_submit_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'ninjaform_input_submit_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_input_submit_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_input_submit_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ninjaform_input_submit_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]',
                        ]
                    );

                    $this->add_responsive_control(
                        'ninjaform_input_submit_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'ninjaform_input_submit_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]',
                        ]
                    );
                    $this->add_responsive_control(
                        'htmega_button_align',
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
                                '{{WRAPPER}} .submit-wrap' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );  
                $this->end_controls_tab(); // Button Normal tab end

                // Button Hover tab start
                $this->start_controls_tab(
                    'ninjaform_submit_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'ninjaform_input_submithover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]:hover'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'ninjaform_input_submithover_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]:hover'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'ninjaform_input_submithover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .nf-form-layout .submit-container input[type="button"]:hover',
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
                    'label'     => __( 'Error Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nf-error .nf-error-msg, {{WRAPPER}} .nf-error,{{WRAPPER}} .nf-error-msg'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_error_text_typography',
                    'selector' => '{{WRAPPER}} .nf-error .nf-error-msg, {{WRAPPER}} .nf-error,{{WRAPPER}} .nf-error-msg',
                ]
            );
            $this->add_control(
                'htmega_error_border_color',
                [
                    'label'     => __( 'Border Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nf-error .ninja-forms-field'  => 'border-color: {{VALUE}}!important;',
                    ],
                ]
            );
            // Validation Pass style
            $this->add_control(
                'htmega_value_pass_style',
                [
                    'label' => __( 'Validation Pass Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'htmega_validation_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .nf-pass .ninja-forms-field'  => 'border-color: {{VALUE}}!important;',
                        '{{WRAPPER}} .nf-pass.field-wrap .nf-field-element:after'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            // Feedback style
            $this->add_control(
                'htmega_error_submit_feedback_style',
                [
                    'label' => __( 'Success Style', 'htmega-addons' ),
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
                        '{{WRAPPER}} .nf-response-msg'  => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_feedback_text_typography',
                    'selector' => '{{WRAPPER}} .nf-response-msg',
                ]
            );            
        $this->end_controls_section(); // Input error style tab end
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $form_attributes = [
            'id' => $settings['ninja_form'],
        ];

        $this->add_render_attribute( 'shortcode', $form_attributes );
        
        echo do_shortcode( sprintf( '[ninja_form %s]', $this->get_render_attribute_string( 'shortcode' ) ) );

    }

}

