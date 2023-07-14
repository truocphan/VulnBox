<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Scroll_Navigation extends Widget_Base {

    public function get_name() {
        return 'htmega-scrollnavigation-addons';
    }
    
    public function get_title() {
        return __( 'Scroll Navigation', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-slider-full-screen';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_keywords() {
        return ['navigation image','htmega', 'ht mega','navigation slider', 'scroll navigation'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/creative-widgets/scroll-navigation-widget/';
    }

    public function get_style_depends(){
        return [
            'swiper',
            'htmega-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'swiper',
            'htmega-widgets-scripts',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'scroll_navigation_content',
            [
                'label' => __( 'Scroll Navigation', 'htmega-addons' ),
            ]
        );
            
            $repeater = new Repeater();

            $repeater->add_control(
                'content_source',
                [
                    'label'   => esc_html__( 'Content Source', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'    => esc_html__( 'Custom', 'htmega-addons' ),
                        "elementor" => esc_html__( 'Elementor Template', 'htmega-addons' ),
                    ],
                ]
            );
            
            $repeater->add_control(
                'navigation_content',
                [
                    'label'      => __( 'Content', 'htmega-addons' ),
                    'type'       => Controls_Manager::WYSIWYG,
                    'default'    => __( 'Content', 'htmega-addons' ),
                    'condition' => [
                        'content_source' =>'custom',
                    ],
                ]
            );

            $repeater->add_control(
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
                'navigator_content_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls() ,
                    'prevent_empty'=>false,
                    'title_field' => '<# print( (content_source == "custom" ) ? content_source : ("Elementor Template") ) #>',
                    'default' => [

                        [
                            'navigation_content'    => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                            'content_source' => 'custom'
                        ],

                    ],
                ]
            );

        $this->end_controls_section(); // Content Section End

        // Slider Options Section Start
        $this->start_controls_section(
            'scroll_navigation_slider_options',
            [
                'label' => __( 'Slider Options', 'htmega-addons' ),
            ]
        );
        $this->add_control(
            'slider_direction_toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'Slider Direction', 'htmega-addons' ),
                'label_off' => esc_html__( 'Default', 'htmega-addons' ),
                'label_on' => esc_html__( 'Custom', 'htmega-addons' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->start_popover();
        
            $this->add_control(
                'slider_direction',
                [
                    'label' => __( 'Desktop Direction', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'vertical',
                    'options' => [
                        'horizontal' => __( 'Horizontal', 'htmega-addons' ),
                        'vertical'  => __( 'Vertical', 'htmega-addons' ),
                    ],
                    'separator' => 'after',
                ]
            );
            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet Device', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'tablet_direction',
                [
                    'label' => __( 'Direction', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'vertical',
                    'options' => [
                        'horizontal' => __( 'Horizontal', 'htmega-addons' ),
                        'vertical'  => __( 'Vertical', 'htmega-addons' ),
                    ],
                ]
            );
            $this->add_control(
                'tablet_width',
                [
                    'label' => __('Resolution', 'htmega-addons'),
                    'description' => __('The resolution to tablet device.', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 991,
                    'separator' => 'after',
                ]
            );
            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Device', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'mobile_direction',
                [
                    'label' => __( 'Direction', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'vertical',
                    'options' => [
                        'horizontal' => __( 'Horizontal', 'htmega-addons' ),
                        'vertical'  => __( 'Vertical', 'htmega-addons' ),
                    ],
                ]
            );
            $this->add_control(
                'mobile_width',
                [
                    'label' => __('Resolution', 'htmega-addons'),
                    'description' => __('The resolution to mobile device.', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 768,
                ]
            );
            $this->end_popover();

            $this->add_control(
                'slider_height',
                [
                    'label' => __( 'Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'full_screen',
                    'options' => [
                        'full_screen'    => __( 'Full Screen', 'htmega-addons' ),
                        'custom_height'  => __( 'Custom', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'slider_container_height',
                [
                    'label' => __( 'Custom Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 300,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-container' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'slider_height' =>'custom_height',
                    ],
                ]
            );

            $this->add_control(
                'slider_speed',
                [
                    'label' => __('Speed', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                ]
            );

            $this->add_control(
                'slider_item',
                [
                    'label' => __('Slider Visible Item', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'initial_slider',
                [
                    'label' => __('Initial Slide', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slider_mousewheel',
                [
                    'label' => esc_html__( 'Mouse Wheel', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            $this->add_control(
                'slider_simulate_touch',
                [
                    'label' => esc_html__( 'Simulate Touch', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            $this->add_control(
                'slider_arrow',
                [
                    'label' => esc_html__( 'Slider Navigation', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'slider_dots',
                [
                    'label' => esc_html__( 'Slider Pagination', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

        $this->end_controls_section(); // Slider Options Section End

        // Style tab section
        $this->start_controls_section(
            'scroll_navigation_style_section',
            [
                'label' => __( 'Custom Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'scroll_navigation_content_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .scroll-navigation-content' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'scroll_navigation_content_typography',
                    'selector' => '{{WRAPPER}} .scroll-navigation-content',
                ]
            );

            $this->add_responsive_control(
                'scroll_navigation_content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .scroll-navigation-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'scroll_navigation_content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .scroll-navigation-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();


        // Style Testimonial Dots style start
        $this->start_controls_section(
            'scroll_navigation_pagination_style',
            [
                'label'     => __( 'Pagination', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->start_controls_tabs( 'scroll_navigation_pagination_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'scroll_navigation_pagination_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'scroll_navigation_pagination_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'scroll_navigation_pagination_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
                        ]
                    );

                    $this->add_responsive_control(
                        'scroll_navigation_pagination_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .swiper-pagination-bullet' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'scroll_navigation_pagination_height',
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
                                'size' => 20,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'scroll_navigation_pagination_width',
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
                                'size' => 20,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'scroll_navigation_pagination_style_hover_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'scroll_navigation_pagination_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .swiper-pagination-bullet-active',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'scroll_navigation_pagination_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .swiper-pagination-bullet-active',
                        ]
                    );

                    $this->add_responsive_control(
                        'scroll_navigation_pagination_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .swiper-pagination-bullet-active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style Testimonial dots style end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'swiperslider_area_attr', 'class', 'swiper-container' );

        $slider_settings = [
            'slideitem'         => $settings['slider_item'],
            'direction'         => $settings['slider_direction'],
            'tablet_direction'         => $settings['tablet_direction'],
            'mobile_direction'         => $settings['mobile_direction'],
            'tablet_width'             => absint( $settings['tablet_width'] ),
            'mobile_width'             => absint( $settings['mobile_width'] ),
            'mousewheel'        => ('yes' === $settings['slider_mousewheel']),
            'simulateTouch'     => ('yes' === $settings['slider_simulate_touch']) ? true:false,
            'arrow'             => ('yes' === $settings['slider_arrow']),
            'pagination'        => ('yes' === $settings['slider_dots']),
            'speed'             => absint( $settings['slider_speed'] ),
            'initialslide'      => absint( $settings['initial_slider'] ) - 1,
        ];
        $this->add_render_attribute( 'swiperslider_area_attr', 'data-settings', wp_json_encode( $slider_settings ) );
      

        ?>
            <!-- Swiper -->
            <div <?php echo $this->get_render_attribute_string( 'swiperslider_area_attr' ); ?>>
                <?php if( $settings['slider_arrow'] == 'yes' ){ echo '<div class="swiper-button-next"></div>'; } ?>
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['navigator_content_list'] as  $navigatorcontent ): ?>
                        <div class="swiper-slide">
                            <div class="scroll-navigation-inner">
                                <?php 
                                    if ( $navigatorcontent['content_source'] == 'custom' && !empty( $navigatorcontent['navigation_content'] ) ) {
                                        echo '<div class="scroll-navigation-content">'.wp_kses_post( $navigatorcontent['navigation_content'] ).'</div>';
                                    } elseif ( $navigatorcontent['content_source'] == "elementor" && !empty( $navigatorcontent['template_id'] )) {
                                        echo Plugin::instance()->frontend->get_builder_content_for_display( $navigatorcontent['template_id'] );
                                    }
                                ?>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
                <?php
                    if( $settings['slider_arrow'] == 'yes' ){
                        echo '<div class="swiper-button-prev"></div>';
                    }
                    // Pagination
                    if( $settings['slider_dots'] == 'yes' ){
                        echo '<div class="htmega-swiper-pagination swiper-pagination"></div>';
                    }
                ?>
                
            </div>

        <?php

    }

}