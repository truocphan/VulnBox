<?php
namespace Elementor;

// Elementor Classes
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Toggle extends Widget_Base {

    public function get_name() {
        return 'htmega-toggle-addons';
    }
    
    public function get_title() {
        return __( 'Toggle', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-v-align-stretch';
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
            'toggle_content',
            [
                'label' => __( 'Toggle', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'toggle_button_normal_title',
                [
                    'label' => __( 'Normal Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Show All', 'htmega-addons' ),
                    'placeholder' => __( 'Show All', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'toggle_button_normal_icon',
                [
                    'label' => __( 'Normal Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                ]
            );

            $this->add_control(
                'toggle_button_open_title',
                [
                    'label' => __( 'Opened Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Close', 'htmega-addons' ),
                    'placeholder' => __( 'Close', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'toggle_button_open_icon',
                [
                    'label' => __( 'Opened Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
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
                    'separator' =>'before',
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
                    'title' => __( 'Custom Content', 'htmega-addons' ),
                    'condition' => [
                        'content_source' =>'custom',
                    ],
                    'default'=>__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.','htmega-addons' ),
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'toggle_style_section',
            [
                'label' => __( 'Content Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'custom_content_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#444444',
                    'selectors' => [
                        '{{WRAPPER}} .htmega_custom_content' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega_custom_content *' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'content_source' =>'custom',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'custom_content_typography',
                    'selector' => '{{WRAPPER}} .htmega_custom_content',
                    'condition' => [
                        'content_source' =>'custom',
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
                        '{{WRAPPER}} .htmega-toggle-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-toggle-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'toggle_button_style',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'toggle_button_align',
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
                        '{{WRAPPER}} .htmega-toggle-button' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->start_controls_tabs('button_style_tabs');

                $this->start_controls_tab(
                    'button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'toggle_button_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default'=>'#3b3b3b',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-toggle-button a' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-toggle-button a svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'toggle_button_typography',
                            'selector' => '{{WRAPPER}} .htmega-toggle-button a',
                        ]
                    );
                    $this->add_control(
                        'icon_font_size',
                        [
                            'label' => __( 'Icon Font Size', 'htmega-addons' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-toggle-button a i' => 'font-size: {{SIZE}}px;',
                                '{{WRAPPER}} .htmega-toggle-button a svg' => 'width: {{SIZE}}px;',
                            ],
                            'conditions' => [
                                'relation' => 'or',
                                'terms' => [
                                    [
                                    'terms' => [
                                            ['name' => 'toggle_button_open_icon[value]', 'operator' => '!=', 'value' =>'']
                                        ]
                                    ],
                                    [
                                    'terms' => [
                                            ['name' => 'toggle_button_normal_icon[value]', 'operator' => '!=', 'value' => ''],
                                        ]
                                    ],
                                ]
                            ], 
                        ]
                    );
                    $this->add_responsive_control(
                        'toggle_button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-toggle-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'toggle_button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-toggle-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'toggle_button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-toggle-button a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'toggle_button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-toggle-button a',
                        ]
                    );

                    $this->add_responsive_control(
                        'toggle_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-toggle-button a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab end

                // Button Hover Tab start
                $this->start_controls_tab(
                    'button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'toggle_button_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default'=>'#3b3b3b',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-toggle-button a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-toggle-button a:hover svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'toggle_button_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-toggle-button a:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'toggle_button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-toggle-button a:hover',
                        ]
                    );

                $this->end_controls_tab(); // Button Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id = $this->get_id();
        $this->add_render_attribute( 'htmega_toggle_attr', 'class', 'htmega-toggle-area' );

        $button_normal_txt = $button_open_txt = '';
        if( !empty( $settings['toggle_button_normal_title'] ) ){
            $button_normal_txt = $settings['toggle_button_normal_title'];
        }

        if( !empty( $settings['toggle_button_open_title'] ) ){
            $button_open_txt = $settings['toggle_button_open_title'];
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_toggle_attr' ); ?> >
                
                <div class="htmega-toggle-content-<?php echo esc_attr( $id );?>" style="display: none;">
                    <?php
                        if ( $settings['content_source'] == "elementor" && !empty( $settings['template_id'] )) {
                            echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'] );
                        }else{
                            if( !empty( $settings['custom_content'] ) ){
                                echo '<div class="htmega_custom_content">'.wp_kses_post( $settings['custom_content'] ).'</div>';
                            }
                        }
                    ?>
                </div>

                <div class="htmega-toggle-button">
                    <?php
                        echo sprintf( '<a href="#" class="togglebutton-%2$s normal_btn">%1$s</a>', htmega_kses_title( $button_normal_txt ).HTMega_Icon_manager::render_icon( $settings['toggle_button_normal_icon'], [ 'aria-hidden' => 'true' ] ), esc_attr( $id ) );
                        echo sprintf( '<a href="#" class="togglebutton-%2$s opened_btn">%1$s</a>', htmega_kses_title( $button_open_txt ).HTMega_Icon_manager::render_icon( $settings['toggle_button_open_icon'], [ 'aria-hidden' => 'true' ] ), esc_attr( $id ) );
                    ?>
                </div>

            </div>

            <script>
                jQuery(document).ready(function($) {
                    'use strict';
                    $(".togglebutton-<?php echo $id;?>").on('click', function(){
                        $(".htmega-toggle-content-<?php echo esc_attr( $id );?>").slideToggle('slow');
                        $(this).removeAttr("href");
                        $(this).parent().toggleClass("open");
                    });
                });
            </script>
        <?php
    }

}

