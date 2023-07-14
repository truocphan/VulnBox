<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_SocialShere extends Widget_Base {

    public function get_name() {
        return 'htmega-social-shere-addons';
    }
    
    public function get_title() {
        return __( 'Social Share', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-share';
    }
    
    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function get_keywords() {
        return ['social share', 'elementor social share','share button', 'social', 'share', 'facebook', 'twitter', 'instagram', 'linkedin'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/social-widgets/social-share-widget/';
    }

    public function get_script_depends() {
        return [
            'htmega-goodshare',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'social_media_sheres',
            [
                'label' => __( 'Social Share', 'htmega-addons' ),
            ]
        );
        
            $repeater = new Repeater();

            $repeater->add_control(
                'htmega_social_media',
                [
                    'label' => __( 'Social Media', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'facebook',
                    'options' => [
                        'facebook'      => __( 'Facebook', 'htmega-addons' ),
                        'twitter'       => __( 'Twitter', 'htmega-addons' ),
                        'googleplus'    => __( 'Google+', 'htmega-addons' ),
                        'pinterest'     => __( 'Pinterest', 'htmega-addons' ),
                        'linkedin'      => __( 'Linkedin', 'htmega-addons' ),
                        'tumblr'        => __( 'tumblr', 'htmega-addons' ),
                        'vkontakte'     => __( 'Vkontakte', 'htmega-addons' ),
                        'odnoklassniki' => __( 'Odnoklassniki', 'htmega-addons' ),
                        'moimir'        => __( 'Moimir', 'htmega-addons' ),
                        'livejournal'   => __( 'Live journal', 'htmega-addons' ),
                        'blogger'       => __( 'Blogger', 'htmega-addons' ),
                        'digg'          => __( 'Digg', 'htmega-addons' ),
                        'evernote'      => __( 'Evernote', 'htmega-addons' ),
                        'reddit'        => __( 'Reddit', 'htmega-addons' ),
                        'delicious'     => __( 'Delicious', 'htmega-addons' ),
                        'stumbleupon'   => __( 'Stumbleupon', 'htmega-addons' ),
                        'pocket'        => __( 'Pocket', 'htmega-addons' ),
                        'surfingbird'   => __( 'Surfingbird', 'htmega-addons' ),
                        'liveinternet'  => __( 'Liveinternet', 'htmega-addons' ),
                        'buffer'        => __( 'Buffer', 'htmega-addons' ),
                        'instapaper'    => __( 'Instapaper', 'htmega-addons' ),
                        'xing'          => __( 'Xing', 'htmega-addons' ),
                        'wordpress'     => __( 'WordPress', 'htmega-addons' ),
                        'baidu'         => __( 'Baidu', 'htmega-addons' ),
                        'renren'        => __( 'Renren', 'htmega-addons' ),
                        'weibo'         => __( 'Weibo', 'htmega-addons' ),
                        'skype'         => __( 'Skype', 'htmega-addons' ),
                        'telegram'      => __( 'Telegram', 'htmega-addons' ),
                        'viber'         => __( 'Viber', 'htmega-addons' ),
                        'whatsapp'      => __( 'Whatsapp', 'htmega-addons' ),
                        'line'          => __( 'Line', 'htmega-addons' ),
                    ],
                ]
            );

            $repeater->add_control(
                'htmega_social_title',
                [
                    'label'   => esc_html__( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Facebook', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'htmega_social_icon',
                [
                    'label'   => esc_html__( 'Icon', 'htmega-addons' ),
                    'type'    => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fab fa-facebook-square',
                        'library'=>'brands',
                    ],
                ]
            );
            
            $repeater->add_control(
                'normal_style_area_heading',
                [
                    'label' => __( 'Normal Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $repeater->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'social_rep_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}',
                ]
            );

            $repeater->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'social_rep_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}',
                ]
            );

            $repeater->add_control(
                'hover_style_area_heading',
                [
                    'label' => __( 'Hover Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $repeater->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'social_rep_hover_background',
                    'label' => __( 'Hover Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}:hover',
                ]
            );

            $repeater->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'social_rep_hover_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}:hover',
                ]
            );

            $repeater->start_controls_tabs('social_content_area_tabs');

                $repeater->start_controls_tab(
                    'social_rep_style',
                    [
                        'label' => __( 'Title', 'htmega-addons' ),
                    ]
                );

                    $repeater->add_control(
                        'social_text_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#000000',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'social_text_hover_color',
                        [
                            'label'     => __( 'Hover color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $repeater->end_controls_tab();// End Style tab

                // Start Icon tab
                $repeater->start_controls_tab(
                    'social_rep_icon_style',
                    [
                        'label' => __( 'Icon', 'htmega-addons' ),
                    ]
                );
                    
                    $repeater->add_control(
                        'normal_style_icon_heading',
                        [
                            'label' => __( 'Normal Style', 'htmega-addons' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );

                    $repeater->add_control(
                        'social_icon_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}} i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}} svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'social_rep_icon_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}} i',
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'social_rep_icon_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}} i',
                        ]
                    );

                    $repeater->add_responsive_control(
                        'social_rep_icon_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}} i' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator'=>'after',
                        ]
                    );

                    $repeater->add_control(
                        'hover_style_icon_heading',
                        [
                            'label' => __( 'Hover Style', 'htmega-addons' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );


                    $repeater->add_control(
                        'social_icon_hover_color',
                        [
                            'label'     => __( 'Hover color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}:hover i' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'social_rep_icon_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}:hover i',
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'social_rep_icon_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-social-share {{CURRENT_ITEM}}:hover i',
                        ]
                    );

                $repeater->end_controls_tab();// End icon Style tab

            $repeater->end_controls_tabs();// Repeater Tabs end

            $this->add_control(
                'htmega_socialmedia_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'prevent_empty' => false,
                    'default' => [
                        [
                            'htmega_social_media' => 'facebook',
                            'htmega_social_title' => __( 'Facebook', 'htmega-addons' ),
                            'htmega_social_icon' => 'fab fa-facebook-square',
                        ],
                        [
                            'htmega_social_media' => 'twitter',
                            'htmega_social_title' => __( 'Twitter', 'htmega-addons' ),
                            'htmega_social_icon' => 'fab fa-twitter',
                        ],
                        [
                            'htmega_social_media' => 'googleplus',
                            'htmega_social_title' => __( 'Google Plus', 'htmega-addons' ),
                            'htmega_social_icon' => 'fab fa-google-plus-g',
                        ],
                    ],
                    'title_field' => '{{{ htmega_social_title }}}',
                ]
            );
            
            $this->add_control(
                'social_view',
                [
                    'label' => esc_html__( 'View', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'label_block' => false,
                    'options' => [
                        'icon'       => 'Icon',
                        'title'      => 'Title',
                        'icon-title' => 'Icon & Title',
                    ],
                    'default'      => 'icon',
                ]
            );

            $this->add_control(
                'show_counter',
                [
                    'label'        => esc_html__( 'Count', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'htmega-addons' ),
                    'label_off'    => esc_html__( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'condition'    => [
                        'social_view!' => 'icon',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'social_icon_alignment',
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
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share ul' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_socialshere_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'social_shere_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'social_shere_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'social_shere_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%',],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'social_shere_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-social-share li',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'social_shere_margin_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-social-share ul li',
                ]
            );

            $this->add_control(
                'icon_control_offset_toggle',
                [
                    'label' => __( 'Icon Settings', 'htmega-addons' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => __( 'None', 'htmega-addons' ),
                    'label_on' => __( 'Custom', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'condition'    => [
                        'social_view!' => 'title',
                    ],
                ]
            );

            $this->start_popover();

            $this->add_control(
                'icon_fontsize',
                [
                    'label' => __( 'Icon Font Size', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
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
                        '{{WRAPPER}} .htmega-social-share ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-social-share ul li > svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'icon_height',
                [
                    'label' => __( 'Icon Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 42,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share ul li i' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-social-share ul li svg' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'icon_line_height',
                [
                    'label' => __( 'Line Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 42,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share ul li i' => 'line-height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-social-share ul li svg' => 'line-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'icon_width',
                [
                    'label' => __( 'Icon Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 42,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share ul li i' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-social-share ul li svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'social_icon_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-social-share li i,{{WRAPPER}} .htmega-social-share li svg',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'social_icon_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-social-share li i,{{WRAPPER}} .htmega-social-share li svg',
                ]
            );

            $this->add_responsive_control(
                'social_icon_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share li i' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-social-share li svg' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->end_popover();

            $this->add_control(
                'share_button_line_height',
                [
                    'label' => __( 'Button Line Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 42,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share ul li' => 'line-height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'    => [
                        'social_view!' => 'icon',
                    ],
                ]
            );
            
            $this->add_control(
                'normal_style_title_heading',
                [
                    'label' => __( 'Title Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'social_view!' =>'icon',
                    ],
                ]
            );

            $this->add_responsive_control(
                'social_shere_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-social-share ul li span.htmega-share-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'social_view!' =>'icon',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .htmega-social-share ul li span',
                    'condition' => [
                        'social_view!' =>'icon',
                    ],
                ]
            );

            $this->start_controls_tabs('social_share_style_tabs');

            // Start Icon tab
            $this->start_controls_tab(
                'social_share_normal_style',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );


                $this->add_control(
                    'social_shere_color',
                    [
                        'label'     => __( 'color', 'htmega-addons' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-social-share ul li' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .htmega-social-style-1 ul li svg path' => 'fill: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'social_shere_background',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .htmega-social-share li',
                    ]
                );

            $this->end_controls_tab();// End Style tab

            // Start Icon tab
            $this->start_controls_tab(
                'social_share_hover_style',
                [
                    'label' => __( 'Hover', 'htmega-addons' ),
                ]
            );

                $this->add_control(
                    'social_shere_hover_color',
                    [
                        'label'     => __( 'color', 'htmega-addons' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-social-share ul li:hover' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'social_shere_hover_background',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .htmega-social-share li:hover',
                    ]
                );

            $this->end_controls_tab();// End Style tab

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_socialshere', 'class', 'htmega-social-share htmega-social-style-1' );
        if( $settings['social_view'] == 'icon-title' || $settings['social_view'] == 'title' ){
            $this->add_render_attribute( 'htmega_socialshere', 'class', 'htmega-social-view-'.$settings['social_view'] );
        }
             
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_socialshere' ); ?> >
                <ul>
                    <?php foreach ( $settings['htmega_socialmedia_list'] as $socialmedia ) :?>
                        <li class="elementor-repeater-item-<?php echo esc_attr( $socialmedia['_id']); ?>" data-social="<?php echo esc_attr( $socialmedia['htmega_social_media'] ); ?>" > 
                            <?php
                                if( $settings['social_view'] == 'icon' ){
                                    echo HTMega_Icon_manager::render_icon( $socialmedia['htmega_social_icon'], [ 'aria-hidden' => 'true' ] );
                                }elseif( $settings['social_view'] == 'title' ){
                                    echo sprintf('<span class="htmega-share-title">%1$s</span>', htmega_kses_title( $socialmedia['htmega_social_title'] ));
                                }else{
                                    echo sprintf('%1$s<span class="htmega-share-title">%2$s</span>', HTMega_Icon_manager::render_icon( $socialmedia['htmega_social_icon'], [ 'aria-hidden' => 'true' ] ), htmega_kses_title(  $socialmedia['htmega_social_title'] ));
                                }
                                if( $settings['show_counter'] == 'yes' ){
                                    echo '<span class="htmega-share-counter" data-counter="'.esc_attr( $socialmedia['htmega_social_media'] ).'"></span>';
                                }
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php

    }

}

