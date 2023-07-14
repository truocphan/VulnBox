<?php
namespace Elementor;

// Elementor Classes
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Special_day_Banner extends Widget_Base {

    public function get_name() {
        return 'htmega-specialdaybanner-addons';
    }
    
    public function get_title() {
        return __( 'Special Day Offer', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-image';
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
            'add_banner_content',
            [
                'label' => __( 'Banner', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'banner_content_pos',
                [
                    'label' => __( 'Content Position', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'center',
                    'options' => [
                        'top'   => __( 'Top', 'htmega-addons' ),
                        'center' => __( 'Center', 'htmega-addons' ),
                        'bottom' => __( 'Bottom', 'htmega-addons' ),
                        'left'   => __( 'Left', 'htmega-addons' ),
                        'right'  => __( 'Right', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'banner_image',
                [
                    'label' => __( 'Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'banner_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'banner_title',
                [
                    'label' => __( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Banner Title', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'banner_sub_title',
                [
                    'label' => __( 'Sub Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Banner Sub Title', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'banner_description',
                [
                    'label' => __( 'Description', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Banner Description', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'banner_offer',
                [
                    'label' => __( 'Offer Amount', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( '50%', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'banner_offer_tag_line',
                [
                    'label' => __( 'Offer Tag Line', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Off', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'banner_link',
                [
                    'label' => __( 'Banner Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                ]
            );

            $this->add_control(
                'banner_button_txt',
                [
                    'label' => __( 'Button Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Button Text', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'banner_badge_toggle',
                [
                    'label' => __( 'Banner Badge', 'htmega-addons' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                ]
            );

            $this->start_popover();

                $this->add_control(
                    'banner_badge_image',
                    [
                        'label' => __( 'Badge Image', 'htmega-addons' ),
                        'type' => Controls_Manager::MEDIA,
                    ]
                );

                $this->add_responsive_control(
                    'badge_width',
                    [
                        'label' => __( 'Width', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 1000,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'condition'=>[
                            'banner_badge_image[url]!'=>'',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-banner .htbanner-badgeimage' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'badge_x_position',
                    [
                        'label' => __( 'Horizontal Postion', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'default' => [
                            'size' => 25,
                            'unit' => '%',
                        ],
                        'range' => [
                            'px' => [
                                'min' => -1000,
                                'max' => 1000,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'condition'=>[
                            'banner_badge_image[url]!'=>'',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-banner .htbanner-badgeimage' => 'left: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'badge_y_position',
                    [
                        'label' => __( 'Vertical Postion', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'default' => [
                            'size' => 0,
                            'unit' => '%',
                        ],
                        'range' => [
                             'px' => [
                                'min' => -1000,
                                'max' => 1000,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'condition'=>[
                            'banner_badge_image[url]!'=>'',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-banner .htbanner-badgeimage' => 'top: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

            $this->end_popover();
            
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'add_banner_style_section',
            [
                'label' => __( 'Content Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'add_banner_section_align',
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
                        '{{WRAPPER}} .htmega-banner .banner-content' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'add_banner_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'add_banner_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'banner_hover_bg_heading',
                [
                    'label' => __( 'Banner Hover Color', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'banner_hover_color_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-banner .banner-thumb a::before,{{WRAPPER}} .htmega-banner:hover .banner-thumb a::after',
                    'separator' =>'before',
                ]
            );
            
        $this->end_controls_section();

        // Style Title tab section
        $this->start_controls_section(
            'banner_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'banner_title!'=>'',
                ]
            ]
        );

            $this->add_control(
                'banner_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#1f1e26',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h2' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'banner_title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-banner .banner-content h2',
                ]
            );

            $this->add_responsive_control(
                'banner_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'banner_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style Sub Title tab section
        $this->start_controls_section(
            'banner_sub_title_style_section',
            [
                'label' => __( 'Sub Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'banner_sub_title!'=>'',
                ]
            ]
        );

            $this->add_control(
                'banner_sub_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#1f1e26',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h6' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'banner_sub_title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-banner .banner-content h6',
                ]
            );

            $this->add_responsive_control(
                'banner_sub_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'banner_sub_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style Description tab section
        $this->start_controls_section(
            'banner_description_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'banner_description!'=>'',
                ]
            ]
        );

            $this->add_control(
                'banner_description_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#1f1e26',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content p' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'banner_description_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-banner .banner-content p',
                ]
            );

            $this->add_responsive_control(
                'banner_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'banner_description_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style Offer tab section
        $this->start_controls_section(
            'banner_offer_style_section',
            [
                'label' => __( 'Offer Amount', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'banner_offer!'=>'',
                ]
            ]
        );

            $this->add_control(
                'banner_offer_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#1f1e26',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h5' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'banner_offer_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-banner .banner-content h5',
                ]
            );

            $this->add_responsive_control(
                'banner_offer_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'banner_offer_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style Offer Tag section
        $this->start_controls_section(
            'banner_offer_tag_style_section',
            [
                'label' => __( 'Offer Tag Line', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'banner_offer!'=>'',
                ]
            ]
        );

            $this->add_control(
                'banner_offer_tag_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#1f1e26',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h5 span' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'banner_offer_tag_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-banner .banner-content h5 span',
                ]
            );

            $this->add_responsive_control(
                'banner_offer_tag_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h5 span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'banner_offer_tag_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-banner .banner-content h5 span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style Button tab section
        $this->start_controls_section(
            'banner_button_style_section',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'banner_button_txt!'=>'',
                ]
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
                        'button_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   =>'#383838',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-banner .banner-content a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'button_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-banner .banner-content a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-banner .banner-content a',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-banner .banner-content a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-banner .banner-content a',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-banner .banner-content a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-banner .banner-content a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // Button Normal tab end

                // Button Hover tab start
                $this->start_controls_tab(
                    'button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'button_hover_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   =>'#383838',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-banner .banner-content a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-banner .banner-content a:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-banner .banner-content a:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-banner .banner-content a:hover',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // Button Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_banner', 'class', 'htmegaspcial-banner htmega-banner htmega-banner-content-pos-'.$settings['banner_content_pos'] );

        // URL Generate
        if ( ! empty( $settings['banner_link']['url'] ) ) {
            
            $this->add_render_attribute( 'url', 'href', $settings['banner_link']['url'] );
            if ( $settings['banner_link']['is_external'] ) {
                $this->add_render_attribute( 'url', 'target', '_blank' );
            }

            if ( ! empty( $settings['banner_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'url', 'rel', 'nofollow' );
            }
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_banner' ); ?>>
                <div class="banner-thumb">
                    <a <?php echo $this->get_render_attribute_string( 'url' ); ?>>
                        <?php
                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'banner_image_size', 'banner_image' );
                        ?>
                    </a>
                </div>
                <?php
                    if( !empty($settings['banner_badge_image']['url']) ){
                        echo '<div class="htbanner-badgeimage"><img src="' . esc_url( $settings['banner_badge_image']['url'] ). '"></div>';
                    }
                ?>
                <div class="banner-content">
                    <?php
                        if( !empty( $settings['banner_title'] ) ){
                            echo '<h2>'.htmega_kses_title( $settings['banner_title'] ).'</h2>';
                        }
                        if( !empty( $settings['banner_sub_title'] ) ){
                            echo '<h6>'.htmega_kses_title( $settings['banner_sub_title'] ).'</h6>';
                        }
                        if( !empty( $settings['banner_offer'] ) ){
                            echo '<h5>'.$settings['banner_offer'].'<span>'.esc_html( $settings['banner_offer_tag_line'] ).'</span></h5>';
                        }
                        if( !empty( $settings['banner_description'] ) ){
                            echo '<p>'.htmega_kses_desc( $settings['banner_description'] ).'</p>';
                        }

                        if( !empty( $settings['banner_button_txt'] ) ){
                            echo '<a '.$this->get_render_attribute_string( 'url' ).'>'.htmega_kses_desc( $settings['banner_button_txt'] ).'</a>';
                        }
                    ?>
                </div>
            </div>

        <?php

    }

}