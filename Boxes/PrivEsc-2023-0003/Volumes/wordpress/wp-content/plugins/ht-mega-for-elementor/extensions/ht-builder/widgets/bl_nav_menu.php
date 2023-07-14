<?php

namespace HTMega_Builder\Elementor\Widget;

// Elementor Classes
use Elementor\Plugin as Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Nav_Menu_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-nav-menu';
    }

    public function get_title() {
        return __( 'BL: Nav Menu', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htbuilder-icon eicon-nav-menu';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    public function get_script_depends() {
        return [
            'mean-menu',
        ];
    }

    public function get_style_depends() {
        return [
            'mean-menu',
            'htmega-widgets',
        ];
    }

    private function get_available_menus() {
        $menus = wp_get_nav_menus();
        $menulists = [];
        foreach ( $menus as $menu ) {
            $menulists[ $menu->slug ] = $menu->name;
        }
        return $menulists;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'nav_menu_content',
            [
                'label' => __( 'Navigation', 'htmega-addons' ),
            ]
        );
            
            if ( ! empty( $this->get_available_menus() ) ) {
                $this->add_control(
                    'nav_menu_id',
                    [
                        'label'   => __( 'Menu', 'htmega-addons' ),
                        'type'    => Controls_Manager::SELECT,
                        'options' => $this->get_available_menus(),
                        'default' => array_keys( $this->get_available_menus() )[0],
                        'save_default' => true,
                        'separator' => 'after',
                        'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus Option</a> to manage your menus.', 'htmega-addons' ), admin_url( 'nav-menus.php' ) ),
                    ]
                );
            } else {
                $this->add_control(
                    'nav_menu_id',
                    [
                        'type' => Controls_Manager::RAW_HTML,
                        'raw' => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus Option</a> to create one.', 'htmega-addons' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                        'separator' => 'after',
                    ]
                );
            }


        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'nav_menu_style_section',
            [
                'label' => __( 'Main Menu', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'menu_typography',
                    'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li a',
                ]
            );

            $this->add_responsive_control(
                'menu_alignment',
                [
                    'label'        => __( 'Alignment', 'htmega-addons' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu' => 'text-align: {{VALUE}};',
                    ],
                    'prefix_class' => 'elementor-align-%s',
                    'default'      => 'left',
                ]
            );
            $this->add_responsive_control(
                'menu_area_padding',
                [
                    'label' => __( 'Menu Wrapper Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-menu-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'menu_items_heading',
                [
                    'label' => __( 'Menu Items', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            // Menu Style Normal Tabs Start
            $this->start_controls_tabs( 'menu_style_tabs' );

                // Menu Style Normal Tab Start
                $this->start_controls_tab(
                    'menu_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'menu_normal_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li a' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#636363',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'menu_normal_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_normal_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'menu_normal_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_normal_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'menu_normal_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li a',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Text_Shadow::get_type(),
                        [
                            'name' => 'menu_normal_text_shadow',
                            'label' => __( 'Text Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li a',
                        ]
                    );

                $this->end_controls_tab(); // Menu Style Normal Tab end

                // Menu Style Hover Tab Start
                $this->start_controls_tab(
                    'menu_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu > li:hover > a' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#d94f5c',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'menu_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu > li:hover > a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'menu_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu > li:hover > a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu > li:hover > a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                $this->end_controls_tab(); // Menu Style Hover Tab End

                // Menu Style Active Tab Start
                $this->start_controls_tab(
                    'menu_style_active_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_active_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li.current-menu-item a' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#d94f5c',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'menu_active_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li.current-menu-item a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'menu_active_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li.current-menu-item a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_active_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li.current-menu-item a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                $this->end_controls_tab(); // Menu Style Active Tab End

            $this->end_controls_tabs(); // Menu Style Normal Tabs End

        $this->end_controls_section();

        // Style Submenu tab section
        $this->start_controls_section(
            'submenu_style_section',
            [
                'label' => __( 'Sub Menu', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'submenu_typography',
                    'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li > ul > li > a',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'submenu_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htbuilder-nav ul > li > ul',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'submenu_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htbuilder-nav ul > li > ul',
                ]
            );

            // Submenu Style Normal Tabs Start
            $this->start_controls_tabs( 'submenu_style_tabs' );

                // Submenu Style Normal Tab Start
                $this->start_controls_tab(
                    'submenu_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                   
                    $this->add_control(
                        'submenu_normal_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li > ul > li > a' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#636363',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'submenu_normal_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li > ul > li > a',
                        ]
                    );

                    $this->add_responsive_control(
                        'submenu_normal_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'submenu_normal_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li > ul > li > a',
                        ]
                    );

                $this->end_controls_tab();

                // Submenu Style Hover Tab Start
                $this->start_controls_tab(
                    'submenu_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'submenu_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li > ul > li:hover > a' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#636363',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'submenu_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li > ul > li:hover > a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'submenu_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-nav ul.htbuilder-mainmenu li > ul > li:hover > a',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


        // mobile menu
        $this->start_controls_section(
            'mobilemenu_style_section',
            [
                'label' => __( 'Mobile Menu', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'mobilemenu_icon_color',
            [
                'label'     => __( 'Icon Color', 'htmega-addons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htbuilder-menu-area .htbuilder-mobile-button' => 'color: {{VALUE}};',
                ],
                'default'=>'#111',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'menu_button_background',
                'label' => __( 'Background', 'htmega-addons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .htbuilder-menu-area .htbuilder-mobile-button',
            ]
        );
        $this->add_responsive_control(
            'menu_area_icon_padding',
            [
                'label' => __( 'Padding', 'htmega-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .htbuilder-menu-area .htbuilder-mobile-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_icon_border',
                'label' => __( 'Border', 'htmega-addons' ),
                'selector' => '{{WRAPPER}} .htbuilder-menu-area .htbuilder-mobile-button',
            ]
        );
        $this->add_responsive_control(
            'menu_icon_border_radius',
            [
                'label' => __( 'Border Radius', 'htmega-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .htbuilder-menu-area .htbuilder-mobile-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mobilemenu_icon_align',
            [
                'label'     => __( 'Icon Align', 'htmega-addons' ),
                'type'      => Controls_Manager::SELECT,
                'selectors' => [
                    '{{WRAPPER}} .htbuilder-menu-area' => 'text-align: {{VALUE}};',
                ],
                'options'	=> [
                	'left'	=> __('Left', 'htmega-addons'),
                	'center'	=> __('Center', 'htmega-addons'),
                	'right'	=> __('Right', 'htmega-addons'),
                ],
                'default' =>'center',
            ]
        );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id         = $this->get_id();

        $this->add_render_attribute( 'menu_area_attr', 'class', 'htbuilder-menu-area' );

        $menu_args = [
            'echo'          => false,
            'menu'          => ( isset( $settings['nav_menu_id'] ) ? $settings['nav_menu_id'] : '' ),
            'menu_class'    => 'htbuilder-mainmenu',
            'menu_id'       => 'menu-'. $id,
            'fallback_cb'   => '__return_empty_string',
            'container'     => '',
        ];

        // General Menu.
        $menu_html = wp_nav_menu( $menu_args );

        ?>
            <div <?php echo $this->get_render_attribute_string( 'menu_area_attr' ); ?> >
                <nav class="htbuilder-nav" id="htbuilder-mobilemenu-<?php echo esc_attr($id); ?>">
                    <?php
                        if( !empty( $menu_html ) ){
                            echo $menu_html;
                        }
                    ?>
                </nav>
                <!-- Mobile Menu Content -->
                <a href="#" class="htbuilder-mobile-button"><i class="fa fa-bars"></i></a>
                <div class="htbuilder-mobile-menu-area">
                    <div class="htbuilder-mobile-menu">
                        <a class="htbuilder-mobile-close" href="#"><i class="fa fa-times"></i></a>
                        <?php
                            if( !empty( $menu_html ) ){
                                echo $menu_html;
                            }
                        ?>
                    </div>
                </div>
                <!-- End Mobile Menu Content -->
            </div>
        <?php
    }

}