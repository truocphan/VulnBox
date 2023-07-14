<?php
namespace Elementor;

// Elementor Classes

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMegaMenu_Verticle_Menu extends Widget_Base {

    public function get_name() {
        return 'htmega-menu-verticle-menu';
    }

    public function get_title() {
        return __( 'Vertical Mega Menu', 'htmega-addons' );
    }

    public function get_icon() {
        return 'eicon-menu-bar';
    }

    public function get_categories() {
        return array( 'htmegamenu-addons' );
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_menu_options',
            array(
                'label' => __( 'Menu', 'htmega-addons' ),
            )
        );

            $this->add_control(
                'menu',
                array(
                    'label'   => __( 'Select Menu', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => htmega_get_all_create_menus(),
                )
            );

            $this->add_control(
                'dropdown_icon',
                array(
                    'label'       => __( 'Dropdown Icon', 'htmega-addons' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'default'     => [
                        'value' => 'fa fa-angle-down',
                        'library' => 'solid',
                    ],
                )
            );
            if ( is_plugin_active('htmega-pro/htmega_pro.php') ) {

                $this->add_responsive_control(
                    'menu_badge',
                    [
                        'label' => esc_html__( 'Hide Menu Badge', 'htmega-addons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'return_value' => 'none',
                        'default' => 'block',
                        'selectors'  => array(
                            '{{WRAPPER}} .htmenu-menu-tag' => 'display: {{VALUE}}',
                        ),
                    ]
                );
            } else {
                $this->add_control(
                    'menu_badge_free',
                    [
                        'label' => esc_html__( 'Show Menu Badge ', 'htmega-addons' ) . ' <i class="eicon-pro-icon"></i>',
                        'type' => Controls_Manager::SWITCHER,
                        'return_value' => 'ture',
                        'default' => 'false',
                        'classes' => 'htmega-disable-control',
                    ]
                );
            }
        $this->end_controls_section();

        // Menu Style
        $this->start_controls_section(
            'section_main_menu_style',
            array(
                'label'      => __( 'Menu Area', 'htmega-addons' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_responsive_control(
                'menu_wrap_width',
                array(
                    'label' => __( 'Main Menu Width', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => array(
                        '%', 'px',
                    ),
                    'range' => array(
                        '%' => array(
                            'min' => 10,
                            'max' => 100,
                        ),
                        'px' => array(
                            'min' => 200,
                            'max' => 1500,
                        ),
                    ),
                    'default' => array(
                        'unit' => '%',
                        'size' => 100,
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu' => 'width: {{SIZE}}{{UNIT}}',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name'     => 'main_menu_background',
                    'selector' => '{{WRAPPER}} .htmega-verticle-menu',
                )
            );

            $this->add_responsive_control(
                'main_menu_margin',
                array(
                    'label'      => __( 'Margin', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu'=> 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'main_menu_padding',
                array(
                    'label'      => __( 'Padding', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'main_menu_border_radius',
                array(
                    'label'      => __( 'Border Radius', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'main_menu_border',
                    'label'       => __( 'Border', 'htmega-addons' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '{{WRAPPER}} .htmega-verticle-menu',
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'main_menu_box_shadow',
                    'selector' => '{{WRAPPER}} .htmega-verticle-menu',
                )
            );

            $this->add_responsive_control(
                'main_menu_alignment',
                array(
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => array(
                        'left'    => array(
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-h-align-left',
                        ),
                        'center' => array(
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-h-align-center',
                        ),
                        'right' => array(
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-h-align-right',
                        ),
                    ),
                    'selectors_dictionary' => array(
                        'left'   => 'margin-right: auto;',
                        'center' => 'margin-left: auto; margin-right: auto;',
                        'right'  => 'margin-left: auto;',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu' => '{{VALUE}}',
                    ),
                )
            );

        $this->end_controls_section();

        // Sub Menu Style
        $this->start_controls_section(
            'section_sub_menu_style',
            array(
                'label'      => __( 'Sub Menu', 'htmega-addons' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_responsive_control(
                'sub_menu_width',
                array(
                    'label' => __( 'Sub Menu Width', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => array(
                        '%', 'px',
                    ),
                    'range' => array(
                        '%' => array(
                            'min' => 10,
                            'max' => 100,
                        ),
                        'px' => array(
                            'min' => 100,
                            'max' => 1500,
                        ),
                    ),
                    'default' => array(
                        'unit' => 'px',
                        'size' => 250,
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu .sub-menu' => 'min-width: {{SIZE}}{{UNIT}}',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name'     => 'sub_menu_background',
                    'selector' => '{{WRAPPER}} .htmega-verticle-menu .sub-menu',
                )
            );

            $this->add_responsive_control(
                'sub_menu_padding',
                array(
                    'label'      => __( 'Padding', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'sub_menu_border_radius',
                array(
                    'label'      => __( 'Border Radius', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'sub_menu_border',
                    'label'       => __( 'Border', 'htmega-addons' ),
                    'selector'    => '{{WRAPPER}} .htmega-verticle-menu .sub-menu',
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'sub_menu_box_shadow',
                    'selector' => '{{WRAPPER}} .htmega-verticle-menu .sub-menu',
                )
            );

        $this->end_controls_section();

        // Mega Menu Style
        $this->start_controls_section(
            'section_mega_menu_style',
            array(
                'label'      => __( 'Mega Menu', 'htmega-addons' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_responsive_control(
                'mega_menu_width',
                array(
                    'label' => __( 'Mega Menu Width', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'size_units' => array(
                        '%', 'px',
                    ),
                    'range' => array(
                        '%' => array(
                            'min' => 10,
                            'max' => 100,
                        ),
                        'px' => array(
                            'min' => 100,
                            'max' => 1500,
                        ),
                    ),
                    'default' => array(
                        'unit' => 'px',
                        'size' => 750,
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu .htmegamenu-content-wrapper' => 'min-width: {{SIZE}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name'     => 'mega_menu_background',
                    'selector' => '{{WRAPPER}} .htmega-verticle-menu .htmegamenu-content-wrapper',
                )
            );

            $this->add_responsive_control(
                'mega_menu_padding',
                array(
                    'label'      => __( 'Padding', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu .htmegamenu-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'mega_menu_border_radius',
                array(
                    'label'      => __( 'Border Radius', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} .htmega-verticle-menu .htmegamenu-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'mega_menu_border',
                    'label'       => __( 'Border', 'htmega-addons' ),
                    'selector'    => '{{WRAPPER}} .htmega-verticle-menu .htmegamenu-content-wrapper',
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'mega_menu_box_shadow',
                    'selector' => '{{WRAPPER}} .htmega-verticle-menu .htmegamenu-content-wrapper',
                )
            );

        $this->end_controls_section();

        // Main Menu Items Style
        $this->start_controls_section(
            'section_main_menu_items_style',
            array(
                'label'      => __( 'Main Menu Items', 'htmega-addons' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs( 'main_menu_item_style_tabs' );
                
                // Items Normal Tabs
                $this->start_controls_tab(
                    'main_menu_item_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'main_menu_items_color',
                        array(
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => array(
                                '{{WRAPPER}} .htmega-verticle-menu ul > li > a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .htmega-verticle-menu ul > li > a > span.htmenu-icon' => 'color: {{VALUE}}',
                            ),
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'     => 'main_menu_items_typography',
                            'selector' => '{{WRAPPER}} .htmega-verticle-menu > ul > li > a,{{WRAPPER}} .htmega-verticle-menu ul.sub-menu > li > a',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'main_menu_items_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-verticle-menu > ul > li,{{WRAPPER}} .htmega-verticle-menu ul.sub-menu > li',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        array(
                            'name'     => 'main_menu_items_bg',
                            'selector' => '{{WRAPPER}} .htmega-verticle-menu > ul > li > a,{{WRAPPER}} .htmega-verticle-menu ul.sub-menu > li > a',
                            'fields_options' => array(
                                'background' => array(
                                    'default' => 'classic',
                                )
                            ),
                            'exclude' => array(
                                'image',
                                'position',
                                'attachment',
                                'attachment_alert',
                                'repeat',
                                'size',
                            ),
                        )
                    );

                $this->end_controls_tab();
                
                // Items Hover Tabs
                $this->start_controls_tab(
                    'main_menu_item_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'main_menu_items_hover_color',
                        array(
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => array(
                                '{{WRAPPER}} .htmega-verticle-menu ul > li > a:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .htmega-verticle-menu ul > li > a:hover > span.htmenu-icon' => 'color: {{VALUE}}',
                            ),
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'main_menu_items_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-verticle-menu > ul > li:hover,
                            {{WRAPPER}} .htmega-verticle-menu ul.sub-menu > li:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        array(
                            'name'     => 'main_menu_items_hover_bg',
                            'selector' => '{{WRAPPER}} .htmega-verticle-menu ul > li > a:hover,
                            {{WRAPPER}} .htmega-verticle-menu ul.sub-menu > li:hover',
                            'fields_options' => array(
                                'background' => array(
                                    'default' => 'classic',
                                )
                            ),
                            'exclude' => array(
                                'image',
                                'position',
                                'attachment',
                                'attachment_alert',
                                'repeat',
                                'size',
                            ),
                        )
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
        if ( is_plugin_active('htmega-pro/htmega_pro.php') ) {
            //Badge Style
                $this->start_controls_section(
                    'menu_badge_style',
                    array(
                        'label'      => __( 'Menu Badge', 'htmega-addons' ),
                        'tab'        => Controls_Manager::TAB_STYLE,
                        'condition'  => [
                            'menu_badge!' => 'none',
                        ]
                    )
                ); 
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    array(
                        'name'     => 'menu_badge_typography',
                        'selector' => '{{WRAPPER}} .htmenu-menu-tag',
                        'condition' => [
                            'menu_badge!' => 'none',
                        ]
                    )
                );
                $this->add_responsive_control(
                    'menu_badge_position_v',
                    [
                        'label' => __( 'Vertical Position', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px'],
                        'range' => [
                            'px' => [
                                'min' => -100,
                                'max' => 100,
                                'step' => 1,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmenu-menu-tag' => 'top: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
                $this->add_responsive_control(
                    'menu_badge_position_h',
                    [
                        'label' => __( 'Horizontal Position', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                            '%' => [
                                'min' => -100,
                                'max' => 100,
                                'step' => 1,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmenu-menu-tag' => 'left: {{SIZE}}%;',
                        ],
                    ]
                );
                $this->add_responsive_control(
                    'menu_badge_padding',
                    array(
                        'label'      => __( 'Padding', 'htmega-addons' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'selectors'  => array(
                            '{{WRAPPER}} .htmenu-menu-tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ),
                    )
                );
                $this->add_responsive_control(
                    'menu_badge_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .htmenu-menu-tag' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                    ]
                );
                $this->end_controls_section();
            }

    }
    
    protected function render( $instance = [] ) {

        $settings  = $this->get_settings_for_display();
        if ( ! $settings['menu'] ) {
            return;
        }

        $this->add_render_attribute( 'menu-wrapper', 'class', 'htmega-verticle-menu' );

        $items_wrap = '<div '. $this->get_render_attribute_string( 'menu-wrapper' ) .'><ul id="%1$s" class="%2$s">%3$s</ul></div>';

        $args = array(
            'menu'            => $settings['menu'],
            'fallback_cb'     => '',
            'items_wrap'      => $items_wrap,
            'walker'          => new \HTMega_Menu_Nav_Walker(),
            'extra_menu_settings' => array(
                'dropdown_icon' => HTMega_Icon_manager::render_icon( $settings['dropdown_icon'] ),
           ),
        );
        wp_nav_menu( $args );

    }

}

htmega_widget_register_manager( new HTMegaMenu_Verticle_Menu() );
