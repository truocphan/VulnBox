<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Switcher extends Widget_Base {

    public function get_name() {
        return 'htmega-switcher-addons';
    }
    
    public function get_title() {
        return __( 'Switcher', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-exchange';
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
            'htmega_switch_layout',
            [
                'label' => __( 'Switcher Layout', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'htmega_switcher_layout_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => 'layout-1',
                    'options' => [
                        'layout-1' => __( 'Layout One', 'htmega-addons' ),
                        'layout-2'   => __( 'Layout Two', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section(); // Switcher One tab end

        $this->start_controls_section(
            'switch_one_content',
            [
                'label' => __( 'Switcher One', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'switch_one_title',
                [
                    'label'     => __( 'Title', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'Switch One' , 'htmega-addons' ),
                    'title' => __( 'Switcher Title', 'htmega-addons' ),
                    'dynamic'   => [ 'active' => true ],
                ]
            );

            $this->add_control(
                'switcher_one_icon',
                [
                    'label'     => __( 'Icon', 'htmega-addons' ),
                    'type'      => Controls_Manager::ICONS,
                    'title' => __( 'Switcher Title Icon', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'switcher_one_icon_size',
                [
                    'label' => esc_html__( 'Icon Size', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-btn .htmega-switcher-nav .switcher_one_icon svg' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-switcher-btn .htmega-switcher-nav .switcher_one_icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'switcher_one_content_source',
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
                'switcher_one_template_id',
                [
                    'label'       => __( 'Content', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '0',
                    'options'     => htmega_elementor_template(),
                    'condition'   => [
                        'switcher_one_content_source' => "elementor"
                    ],
                ]
            );

            $this->add_control(
                'switcher_one_custom_content',
                [
                    'label' => __( 'Content', 'htmega-addons' ),
                    'show_label' =>false,
                    'type' => Controls_Manager::WYSIWYG,
                    'title' => __( 'Content', 'htmega-addons' ),
                    'dynamic'    => [ 'active' => true ],
                    'condition' => [
                        'switcher_one_content_source' =>'custom',
                    ],
                    'default' =>__('Switcher Content One', 'htmega-addons'),
                ]
            );

        $this->end_controls_section(); // Switcher One tab end


        // Switcher Two tab start
        $this->start_controls_section(
            'switch_two_content',
            [
                'label' => __( 'Switcher Two', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'switch_two_title',
                [
                    'label'     => __( 'Title', 'htmega-addons' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'Switch Two' , 'htmega-addons' ),
                    'title' => __( 'Switcher Title', 'htmega-addons' ),
                    'dynamic'   => [ 'active' => true ],
                ]
            );

            $this->add_control(
                'switcher_two_icon',
                [
                    'label'     => __( 'Icon', 'htmega-addons' ),
                    'type'      => Controls_Manager::ICONS,
                    'title' => __( 'Switcher Title Icon', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'switcher_two_icon_size',
                [
                    'label' => esc_html__( 'Icon Size', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-btn .htmega-switcher-nav .switcher_two_icon svg' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-switcher-btn .htmega-switcher-nav .switcher_two_icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            
            $this->add_control(
                'switcher_two_content_source',
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
                'switcher_two_template_id',
                [
                    'label'       => __( 'Content', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '0',
                    'options'     => htmega_elementor_template(),
                    'condition'   => [
                        'switcher_two_content_source' => "elementor"
                    ],
                ]
            );

            $this->add_control(
                'switcher_two_custom_content',
                [
                    'label' => __( 'Content', 'htmega-addons' ),
                    'show_label' =>false,
                    'type' => Controls_Manager::WYSIWYG,
                    'title' => __( 'Content', 'htmega-addons' ),
                    'dynamic'    => [ 'active' => true ],
                    'condition' => [
                        'switcher_two_content_source' =>'custom',
                    ],
                    'default' =>__('Switcher Content Two', 'htmega-addons'),
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_switcher_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'switcher_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-switcher-toggle-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],                ]
            );

            $this->add_responsive_control(
                'switcher_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-switcher-toggle-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'after',
                ]
            );

        $this->end_controls_section();

        // Style switcher button tab section
        $this->start_controls_section(
            'switcher_button_style_section',
            [
                'label' => __( 'Switcher Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'switcher_button_area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-switcher-nav',
                    'condition'   => [
                        'htmega_switcher_layout_style' => "layout-1"
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'switcher_button_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-switcher-nav',
                    'condition'   => [
                        'htmega_switcher_layout_style' => "layout-1"
                    ],
                ]
            );

            $this->add_responsive_control(
                'switcher_button_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-nav' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition'   => [
                        'htmega_switcher_layout_style' => "layout-1"
                    ],
                ]
            );

            $this->add_control(
                'switcher_toggle_button_title_color',
                [
                    'label' => __( 'Title Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#444444',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-toggle .htmega-switcher-toggle-title' => 'color: {{VALUE}};',
                    ],
                    'condition'   => [
                        'htmega_switcher_layout_style' => "layout-2"
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'switcher_button_toggle_title_typography',
                    'selector' => '{{WRAPPER}} .htmega-switcher-toggle .htmega-switcher-toggle-title',
                    'condition'   => [
                        'htmega_switcher_layout_style' => "layout-2"
                    ],
                ]
            );

            $this->start_controls_tabs('style_tabs');

                // Button Normal Tab Start
                $this->start_controls_tab(
                    'switcher_button_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'switcher_button_color',
                        [
                            'label' => __( 'Title Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#444444',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-switcher-nav a' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-switcher-nav a svg path' => 'fill: {{VALUE}};',
                            ],
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-1"
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'switcher_button_typography',
                            'selector' => '{{WRAPPER}} .htmega-switcher-nav a',
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-1"
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'switcher_button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-switcher-nav a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ], 
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-1"
                            ],               ]
                    );

                    $this->add_responsive_control(
                        'switcher_button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-switcher-nav a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'after',
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-1"
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'switcher_button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-switcher-nav a,{{WRAPPER}} input+.htmega-switche-toggle-slider',
                        ]
                    );

                    $this->add_control(
                        'switcher_button_toggle_color',
                        [
                            'label' => __( 'Toggle Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-switcher-toggle input+.htmega-switche-toggle-slider:before' => 'background-color: {{VALUE}};',
                            ],
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-2"
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'switcher_button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-switcher-nav a',
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-1"
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'switcher_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-switcher-nav a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-1"
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Button Normal Tab End

                // Button Active Tab Start
                $this->start_controls_tab(
                    'switcher_button_active_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'switcher_button_active_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-switcher-nav a.htb-active' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-switcher-nav a.htb-active svg path' => 'fill: {{VALUE}};',
                            ],
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-1"
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'switcher_button_active_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-switcher-nav a.htb-active, {{WRAPPER}} .htmega-switcher-nav a.htb-active::before,{{WRAPPER}} input:checked+.htmega-switche-toggle-slider',
                        ]
                    );

                    $this->add_control(
                        'switcher_button_toggle_active_color',
                        [
                            'label' => __( 'Toggle Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-switcher-toggle input:checked+.htmega-switche-toggle-slider:before' => 'background-color: {{VALUE}};',
                            ],
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-2"
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'switcher_button_active_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-switcher-nav a.htb-active',
                            'condition'   => [
                                'htmega_switcher_layout_style' => "layout-1"
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'switcher_button_active_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-switcher-nav a.htb-active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .htmega-switcher-nav a.htb-active::before' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ]
                        ]
                    );

                $this->end_controls_tab(); // Button Active Tab End

            $this->end_controls_tabs();

        $this->end_controls_section();
        
        // Style Content tab section
        $this->start_controls_section(
            'htmega_switcher_content_style_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
           
            $this->add_control(
                'switcher_content_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-area .htmega_switcher_content' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-switcher-toggle-area .htmega_switcher_content' => 'color: {{VALUE}};',
                    ],
                    'decsription' =>__( 'Only for custom content.', 'htmega-addons' ),
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'switcher_content_typography',
                    'selector' => '{{WRAPPER}} .htmega-switcher-area .htmega_switcher_content,{{WRAPPER}} .htmega-switcher-toggle-area .htmega_switcher_content',
                    'decsription' =>__( 'Only for custom content.', 'htmega-addons' ),
                ]
            );

            $this->add_responsive_control(
                'switcher_content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-area .htmega-single-switch' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-switcher-toggle-area .htmega_switcher_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],                ]
            );

            $this->add_responsive_control(
                'switcher_content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-area .htmega-single-switch' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-switcher-toggle-area .htmega_switcher_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'switcher_content_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-switcher-area .htmega-single-switch,{{WRAPPER}} .htmega-switcher-toggle-area .htmega_switcher_content',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'switcher_content_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-switcher-area .htmega-single-switch,{{WRAPPER}} .htmega-switcher-toggle-area .htmega_switcher_content',
                ]
            );

            $this->add_responsive_control(
                'switcher_content_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-switcher-area .htmega-single-switch' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-switcher-toggle-area .htmega_switcher_content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        if( 'layout-1' == $settings['htmega_switcher_layout_style']):
            $this->add_render_attribute( 'htmega_switcher_attr', 'class', 'htmega-switcher-area' );
            ?>
                <div <?php echo $this->get_render_attribute_string( 'htmega_switcher_attr' ); ?>>
                    <!-- Switcher Menu area start  -->
                    <div class="htmega-switcher-btn">
                        <div class="htmega-switcher-nav htb-nav" role="tablist">
                            <a class="htb-nav-link htb-active htb-show switcher_one_icon" data-toggle="htbtab" href="#switcherone<?php echo $this->get_id(); ?>">
                                <?php
                                    if( $settings['switcher_one_icon']['value'] != ''){
                                        echo HTMega_Icon_manager::render_icon( $settings['switcher_one_icon'], [ 'aria-hidden' => 'true' ] ).esc_html( $settings['switch_one_title'] );
                                    }else{
                                        echo esc_html__( $settings['switch_one_title'],'htmega-addons' );
                                    }
                                ?>
                            </a>
                            <a class="htb-nav-link switcher_two_icon" data-toggle="htbtab" href="#switchertwo<?php echo $this->get_id(); ?>">
                                <?php
                                    if( $settings['switcher_two_icon']['value'] != ''){
                                        echo HTMega_Icon_manager::render_icon( $settings['switcher_two_icon'], [ 'aria-hidden' => 'true' ] ).esc_html( $settings['switch_two_title'] );
                                    }else{
                                        echo esc_html( $settings['switch_two_title'] );
                                    }
                                ?>
                            </a>
                        </div>
                    </div>
                    <!-- Switcher Menu area End  -->

                    <!-- Switcher Content area Start  -->
                    <div class="ht-tab-content htb-tab-content">

                        <!-- Start Single Tab -->
                        <div class="htmega-single-switch htb-tab-pane htb-active htb-show" id="switcherone<?php echo $this->get_id(); ?>" role="tabpanel">
                            <?php
                                if ( $settings['switcher_one_content_source'] == "elementor" && !empty( $settings['switcher_one_template_id'] ) ) {
                                    echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['switcher_one_template_id'] );
                                }
                                else {
                                    echo '<div class="htmega_switcher_content">'.wp_kses_post( $settings['switcher_one_custom_content'] ).'</div>';
                                }
                            ?>
                        </div>
                        <!-- End Tab A Tab -->

                        <!-- Start tab B Single Tab -->
                        <div class="htmega-single-switch htb-tab-pane" id="switchertwo<?php echo $this->get_id(); ?>" role="tabpanel">
                            <?php
                                if ( $settings['switcher_two_content_source'] == "elementor" && !empty( $settings['switcher_two_template_id'] ) ) {
                                    echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['switcher_two_template_id'] );
                                }
                                else {
                                    echo '<div class="htmega_switcher_content">'.wp_kses_post( $settings['switcher_two_custom_content'] ).'</div>';
                                }
                            ?>
                        </div>
                        <!-- End Tab B Tab -->
                    </div>
                    <!-- Switcher Content area End  -->
                </div>
            <?php
        else:
            $this->add_render_attribute( 'htmega_switcher_toggle_attr', 'class', 'htmega-switcher-toggle-area' );
            ?>
                <div <?php echo $this->get_render_attribute_string( 'htmega_switcher_toggle_attr' ); ?>>
                    <div class="htmega-switcher-toggle">
                        <span class="htmega-switcher-toggle-title" id="switcher-toggle-title-2">
                            <?php
                                if( $settings['switcher_one_icon']['value'] != ''){
                                    echo HTMega_Icon_manager::render_icon( $settings['switcher_one_icon'], [ 'aria-hidden' => 'true' ] ).esc_html( $settings['switch_one_title'] );
                                }else{
                                    echo esc_html( $settings['switch_one_title'] );
                                }
                            ?>
                        </span>
                        <label class="htmega-switch-toggle">
                            <input type="checkbox" checked="checked">
                            <span class="htmega-switche-toggle-slider"></span>
                        </label>
                        <span class="htmega-switcher-toggle-title" id="switcher-toggle-title-1">
                            <?php
                                if( $settings['switcher_two_icon']['value'] != ''){
                                    echo HTMega_Icon_manager::render_icon( $settings['switcher_two_icon'], [ 'aria-hidden' => 'true' ] ).esc_html( $settings['switch_two_title'] );
                                }else{
                                    echo esc_html( $settings['switch_two_title'] );
                                }
                            ?> 
                        </span>             
                    </div>
                    <div class="htmega-switcher-toggle-content">
                        <!-- Start Single Tab -->
                        <div class="htmega-single-toggle-switch toggle-tab-pane toggl-active" id="switchertglone-<?php echo $this->get_id(); ?>" role="tabpanel">
                            <?php
                                if ( $settings['switcher_one_content_source'] == "elementor" && !empty( $settings['switcher_one_template_id'] ) ) {
                                    echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['switcher_one_template_id'] );
                                }
                                else {
                                    echo '<div class="htmega_switcher_content">'.wp_kses_post( $settings['switcher_one_custom_content'] ).'</div>';
                                }
                            ?>
                        </div>
                        <!-- End Tab A Tab -->

                        <!-- Start tab B Single Tab -->
                        <div class="htmega-single-toggle-switch toggle-tab-pane" id="switchertgltwo-<?php echo $this->get_id(); ?>" role="tabpanel">
                            <?php
                                if ( $settings['switcher_two_content_source'] == "elementor" && !empty( $settings['switcher_two_template_id'] ) ) {
                                    echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['switcher_two_template_id'] );
                                }
                                else {
                                    echo '<div class="htmega_switcher_content">'.wp_kses_post( $settings['switcher_two_custom_content'] ).'</div>';
                                }
                            ?>
                        </div>
                        <!-- End Tab B Tab -->
                    </div>
                </div>
                <script type="text/javascript">
                    jQuery(document).ready(function($){
                        var switcherTglOne ='#switchertglone-'+'<?php echo $this->get_id(); ?>';
                        var switcherTglTwo ='#switchertgltwo-'+'<?php echo $this->get_id(); ?>';
                        activeSwitcherContent(true);
                        $('.htmega-switch-toggle input').on( 'click', function() {
                            activeSwitcherContent(this.checked);
                        });

                        function activeSwitcherContent(status){
                            if(status){
                                $(switcherTglOne).removeClass('toggl-active');
                                $(switcherTglTwo).addClass('toggl-active');
                            }else{
                                $(switcherTglTwo).removeClass('toggl-active');
                                $(switcherTglOne).addClass('toggl-active');
                            } 
                        }
                    });
                </script>
            <?php 
        endif;
    }
}