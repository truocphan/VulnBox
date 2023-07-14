<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Mailchimp_Wp extends Widget_Base {

    public function get_name() {
        return 'htmega-mailchimp-wp-addons';
    }
    
    public function get_title() {
        return __( 'Mailchimp for wp', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-mailchimp';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_keywords() {
        return ['email subscription', 'mailchimp for wp', 'htmega', 'ht mega'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/?dr=14055';
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'htmega_mailchimp',
            [
                'label' => __( 'Mailchimp', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'htmega_mailchimp_form_style',
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
                'htmega_mailchimp_id',
                [
                    'label'       => __( 'Mailchimp ID', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( '294', 'htmega-addons' ),
                    'description' => __( 'For show ID <a href="admin.php?page=mailchimp-for-wp-forms" target="_blank"> Click here </a>', 'htmega-addons' ),
                    'label_block' => true,
                    'separator'   => 'before',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_mailchimp_section_style',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_mailchimp_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-input-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_mailchimp_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-input-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'htmega_mailchimp_section_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-input-box',
                ]
            );

            $this->add_responsive_control(
                'htmega_mailchimp_section_align',
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
                        '{{WRAPPER}} .htmega-input-box' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Input Box style tab start
        $this->start_controls_section(
            'htmega_mailchimp_input_style',
            [
                'label'     => __( 'Input Box', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'htmega_input_box_height',
                [
                    'label' => __( 'Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 50,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]'  => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_input_box_typography',
                    'selector' => '{{WRAPPER}} .mc4wp-form input[type*="email"]',
                ]
            );

            $this->add_control(
                'htmega_input_box_background',
                [
                    'label'     => __( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]'         => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]'        => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form select[name*="_mc4wp_lists"]' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'htmega_input_box_text_color',
                [
                    'label'     => __( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'htmega_input_box_placeholder_color',
                [
                    'label'     => __( 'Placeholder Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]::-webkit-input-placeholder' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]::-moz-placeholder' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]:-ms-input-placeholder' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .mc4wp-form select[name*="_mc4wp_lists"]'      => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_input_box_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .mc4wp-form input[type*="email"]',
                ]
            );

            $this->add_responsive_control(
                'htmega_input_box_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_input_box_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_input_box_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .mc4wp-form input[type*="text"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .mc4wp-form input[type*="email"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );
           
        $this->end_controls_section(); // Input box style tab end

        // Input submit button style tab start
        $this->start_controls_section(
            'htmega_mailchimp_inputsubmit_style',
            [
                'label'     => __( 'Button', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->start_controls_tabs('htmega_submit_style_tabs');

                // Button Normal tab start
                $this->start_controls_tab(
                    'htmega_submit_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_responsive_control(
                        'htmega_input_submit_height',
                        [
                            'label' => __( 'Height', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                                'size' => 40,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'htmega_input_submit_width',
                        [
                            'label' => __( 'Width', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'htmega_input_submit_position',
                        [
                            'label' => __( 'Position', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                                'size' => 20,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-mailchimp-style-4 .htmega-input-box::before' => 'right: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'htmega_mailchimp_form_style' =>'4',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'htmega_input_submit_typography',
                            'selector' => '{{WRAPPER}} .mc4wp-form input[type*="submit"]',
                        ]
                    );

                    $this->add_control(
                        'htmega_input_submit_text_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_input_submit_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_input_submit_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_input_submit_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_input_submit_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .mc4wp-form input[type*="submit"]',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_input_submit_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'htmega_input_submit_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .mc4wp-form input[type*="submit"]',
                        ]
                    );

                $this->end_controls_tab(); // Button Normal tab end

                // Button Hover tab start
                $this->start_controls_tab(
                    'htmega_submit_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_input_submithover_text_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]:hover'  => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_input_submithover_background_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .mc4wp-form input[type*="submit"]:hover'  => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_input_submithover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .mc4wp-form input[type*="submit"]:hover',
                        ]
                    );

                $this->end_controls_tab(); // Button Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Input submit button style tab end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'mailchimp_area_attr', 'class', 'htmega-mailchimp' );
        $this->add_render_attribute( 'mailchimp_area_attr', 'class', 'htmega-mailchimp-style-'.$settings['htmega_mailchimp_form_style'] );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'mailchimp_area_attr' ); ?> >
                <div class="htmega-input-box">
                    <?php echo do_shortcode( '[mc4wp_form  id="'.$settings['htmega_mailchimp_id'].'"]' ); ?>
                </div>
            </div>
        <?php
    }

}

