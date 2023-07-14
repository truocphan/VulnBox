<?php
namespace Elementor;

// Elementor Classes
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists('TwitterOAuth') ) {
    include HTMEGA_ADDONS_PL_PATH . 'includes/twitteroauth.php';
}

class HTMega_Elementor_Widget_Twitter_Feed extends Widget_Base {

    public function get_name() {
        return 'htmega-twitterfeed-addons';
    }
    
    public function get_title() {
        return __( 'Twitter Feed', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-twitter-feed';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends() {
        return [
            'slick',
            'htmega-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'slick',
            'htmega-widgets-scripts',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'twitterfeed_content',
            [
                'label' => __( 'Twitter Feed', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'twitter_style',
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
                'username',
                [
                    'label' => __( 'UserName', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'hastechit', 'htmega-addons' ),
                    'label_block' => true,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'consumer_key',
                [
                    'label' => __( 'Consumer Key', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Twitter consumer key.', 'htmega-addons' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'consumer_secret',
                [
                    'label' => __( 'Consumer Secret', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Twitter consumer secret.', 'htmega-addons' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'access_token',
                [
                    'label' => __( 'Access Token', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Twitter access token.', 'htmega-addons' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'access_token_secret',
                [
                    'label' => __( 'Access Token Secret', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Twitter access token secret.', 'htmega-addons' ),
                    'label_block' => true,
                ]
            );
            
            $this->add_control(
                'limit',
                [
                    'label' => __( 'Limit', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'default' => 5,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'content_length',
                [
                    'label' => __( 'Content Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'default' => 20,
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'twitter_meta',
                [
                    'label' => __( 'Show Twitter Meta', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'author_image',
                [
                    'label' => __( 'Show Author Image', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'author_name',
                [
                    'label' => __( 'Show Author Name', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_time',
                [
                    'label' => __( 'Show Time', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'relative_time',
                [
                    'label' => __( 'Show Relative Time', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition'=>[
                        'show_time'=>'yes',
                    ]
                ]
            );
            $this->add_control(
                'show_time_icon',
                [
                    'label' => __( 'Show Time Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $this->add_control(
                'slider_on',
                [
                    'label'         => __( 'Slider', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'On', 'htmega-addons' ),
                    'label_off'     => __( 'Off', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

        $this->end_controls_section();

        // Slider setting
        $this->start_controls_section(
            'instagram_slider_option',
            [
                'label' => esc_html__( 'Slider Option', 'htmega-addons' ),
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 20,
                    'step' => 1,
                    'default' => 3,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );
            
            $this->add_responsive_control(
                'sli_gutter',
                [
                    'label' => esc_html__( 'Column Space', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 200,
                    'step' => 1,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-carousel-activation .slick-list' => 'margin: 0 -{{VALUE}}px;',
                        '{{WRAPPER}} .htmega-carousel-activation .slick-slide' => 'padding: 0 {{VALUE}}px;',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            ); 
            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slprevicon',
                [
                    'label' => __( 'Previous icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fa fa-angle-left',
                        'library'=>'solid',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slnexticon',
                [
                    'label' => __( 'Next icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fa fa-angle-right',
                        'library'=>'solid',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'htmega-addons'),
                    'label_on' => __('Yes', 'htmega-addons'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'htmega-addons'),
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slcentermode',
                [
                    'label' => esc_html__( 'Center Mode', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slcenterpadding',
                [
                    'label' => esc_html__( 'Center padding', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                    'default' => 50,
                    'condition' => [
                        'slider_on' => 'yes',
                        'slcentermode' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => esc_html__( 'Slider auto play', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );


            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'htmega-addons'),
                    'description' => __('The resolution to tablet.', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'htmega-addons'),
                    'description' => __('The resolution to mobile.', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section(); // Slider Option end

        // Item Style tab section
        $this->start_controls_section(
            'twitterfeed_item_style_section',
            [
                'label' => __( 'Single Item', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'twitterfeed_item_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-twitter-area .htmega-twitter-single',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'twitterfeed_item_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-twitter-area .htmega-twitter-single',
                ]
            );

            $this->add_responsive_control(
                'twitterfeed_item_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-twitter-area .htmega-twitter-single' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'twitterfeed_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-twitter-area .htmega-twitter-single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'twitterfeed_item_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-twitter-area .htmega-twitter-single',
                ]
            );

            $this->add_responsive_control(
                'twitterfeed_item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-twitter-area .htmega-twitter-single' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Content Style tab section
        $this->start_controls_section(
            'twitterfeed_style_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'twitterfeed_content_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#434343',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-content p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'twitterfeed_content_link_color',
                [
                    'label' => __( 'Link Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#00c8fa',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-content p a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'twitterfeed_content_typography',
                    'selector' => '{{WRAPPER}} .htmega-content p',
                ]
            );

            $this->add_responsive_control(
                'twitterfeed_content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'twitterfeed_content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Author Style tab section
        $this->start_controls_section(
            'twitterfeed_author_style_section',
            [
                'label' => __( 'Author', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'author_name'=>'yes',
                ]
            ]
        );
            
            $this->start_controls_tabs( 'author_style_tabs' );

                $this->start_controls_tab(
                    'author_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'twitterfeed_author_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#5c5c5c',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-single .htmega-content .htmega-author h6 a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'twitterfeed_author_typography',
                            'selector' => '{{WRAPPER}} .htmega-twitter-single .htmega-content .htmega-author h6 a',
                        ]
                    );

                    $this->add_responsive_control(
                        'twitterfeed_author_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-single .htmega-content .htmega-author h6 a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'twitterfeed_author_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-single .htmega-content .htmega-author h6 a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );
                // Author Image style
                $this->add_control(
                    'author_image_heading',
                    [
                        'label' => __( 'Author Image Style', 'htmega-addons' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' =>'before',
                        'condition' =>[
                            'author_image' => 'yes',
                        ]
                    ]
                );
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'author_image_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-twitter-single .htmega-thumb img',
                        'separator' => 'before',
                        'condition'=>[
                            'author_image' => 'yes',
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'author_image_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-twitter-single .htmega-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' => 'before',
                        'condition' =>[
                            'author_image' => 'yes',
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'author_image_margin',
                    [
                        'label' => __( 'Margin', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-twitter-single .htmega-thumb' => 'padding : {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' =>'before',
                        'condition' =>[
                            'author_image' => 'yes',
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'image_box_width',
                    [
                        'label' => __( 'Image Width', 'htmega-addons' ),
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
                        'selectors' => [
                            '{{WRAPPER}} .htmega-twitter-single .htmega-thumb img' => 'width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' =>[
                            'author_image' => 'yes',
                        ]
                    ]
                );                
                // Author User name style
                $this->add_control(
                    'author_user_name',
                    [
                        'label' => __( 'User Name Style', 'htmega-addons' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' =>'before',
                        'condition' =>[
                            'twitter_style' => '2',
                        ]
                    ]
                );
                $this->add_control(
                    'twitterfeed_user_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-twitter-style-2 .ht-twitter-container .htmega-content .htmega-author h6 a span' => 'color: {{VALUE}};',
                        ],
                        'condition' =>[
                            'twitter_style' => '2',
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'twitterfeed_user_typography',
                        'selector' => '{{WRAPPER}} .htmega-twitter-style-2 .ht-twitter-container .htmega-content .htmega-author h6 a span',
                        'condition' =>[
                            'twitter_style' => '2',
                        ]
                    ]
                );                           
                $this->end_controls_tab(); // Author Normal Style tab end

                // Author Hover Style tab Start
                $this->start_controls_tab(
                    'author_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'twitterfeed_author_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#5c5c5c',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-single .htmega-content .htmega-author h6 a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Time Style tab section
        $this->start_controls_section(
            'twitterfeed_time_style_section',
            [
                'label' => __( 'Time', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_time'=>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'twitterfeed_time_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-twitter-single span.twitter-time' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-twitter-single .htmega-content .htmega-author span::before' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'twitterfeed_time_typography',
                    'selector' => '{{WRAPPER}} .htmega-twitter-single span.twitter-time',
                ]
            );

            $this->add_responsive_control(
                'twitterfeed_time_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-twitter-single span.twitter-time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'twitterfeed_time_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-twitter-single span.twitter-time' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Meta Button Style tab section
        $this->start_controls_section(
            'twitterfeed_meta_button_style_section',
            [
                'label' => __( 'Meta Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'twitter_meta'=>'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'meta_button_style_tabs' );

                $this->start_controls_tab(
                    'meta_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'twitterfeed_button_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#aaaaaa',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-single ul.twitter-meta li a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'twitterfeed_button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-single ul.twitter-meta li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'twitterfeed_button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-single ul.twitter-meta li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'twitter_meta_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-twitter-style-4 .htmega-content ul.twitter-meta',
                            'condition' =>[
                                'twitter_style' => '4',
                            ]
                        ]
                    );
                $this->end_controls_tab(); // Normal Button style end 

                // Hover button Style Start
                $this->start_controls_tab(
                    'meta_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'twitterfeed_button_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#18012c',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-single ul.twitter-meta li a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style instagram arrow style start
        $this->start_controls_section(
            'twitter_arrow_style',
            [
                'label'     => __( 'Arrow', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'twitter_arrow_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'twitter_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'twitter_arrow_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#2d3e50',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'twitter_arrow_fontsize',
                        [
                            'label' => __( 'Font Size', 'htmega-addons' ),
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
                                'size' => 60,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'twitter_arrow_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-twitter-area .slick-arrow',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'twitter_arrow_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-twitter-area .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'twitter_arrow_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'twitter_arrow_height',
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
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'twitter_arrow_width',
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
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'twitter_arrow_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'slider_arrow_horizontal_postion',
                        [
                            'label' => __( 'Horizontal Position', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => -1200,
                                    'max' => 1200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => -100,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area button.slick-arrow' => 'left: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-twitter-area button.htmega-carosul-next' => 'right: {{SIZE}}{{UNIT}}; left:auto;',
                            ],
                        ]
                    );
                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'twitter_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'twitter_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#2d3e50',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow:hover svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'twitter_arrow_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-twitter-area .slick-arrow:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'twitter_arrow_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-twitter-area .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'twitter_arrow_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style instagram arrow style end


        // Style instagram Dots style start
        $this->start_controls_section(
            'twitter_dots_style',
            [
                'label'     => __( 'Pagination', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'sldots'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'twitter_dots_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'twitter_dots_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'twitter_dots_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-twitter-area .slick-dots li button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'twitter_instagram_dots_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-twitter-area .slick-dots li button',
                        ]
                    );

                    $this->add_responsive_control(
                        'twitter_dots_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'twitter_dots_height',
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
                                'size' => 15,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-dots li' => 'height: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-twitter-area .slick-dots li button' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'twitter_dots_width',
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
                                'size' => 15,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-dots li' => 'width: {{SIZE}}{{UNIT}} !important;',
                                '{{WRAPPER}} .htmega-twitter-area .slick-dots li button' => 'width: {{SIZE}}{{UNIT}} !important;',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'twitter_dots_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-dots li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'twitter_vertical_width',
                        [
                            'label' => __( 'Vertical Space', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => -300,
                                    'max' => 300,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );                    
                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'twitter_dots_style_hover_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'twitter_dots_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-twitter-area .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'twitter_dots_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-twitter-area .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_responsive_control(
                        'twitter_dots_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-twitter-area .slick-dots li.slick-active button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style instagram dots style end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'htmega_twitter_attr', 'class', 'htmega-twitter-area htmega-twitter-style-'.$settings['twitter_style'] );


        if( $settings['slider_on'] == 'yes' ){
            $direction = is_rtl() ? 'rtl' : 'ltr';
            $this->add_render_attribute( 'htmega_twitter_attr', 'dir', $direction );
            
            $this->add_render_attribute( 'htmega_twitter_attr', 'class', 'htmega-carousel-activation' );
            $slider_settings = [
                'arrows' => ('yes' === $settings['slarrows']),
                'arrow_prev_txt' => HTMega_Icon_manager::render_icon( $settings['slprevicon'], [ 'aria-hidden' => 'true' ] ),
                'arrow_next_txt' => HTMega_Icon_manager::render_icon( $settings['slnexticon'], [ 'aria-hidden' => 'true' ] ),
                'dots' => ('yes' === $settings['sldots']),
                'autoplay' => ('yes' === $settings['slautolay']),
                'autoplay_speed' => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
                'center_mode' => ( 'yes' === $settings['slcentermode']),
                'center_padding' => absint($settings['slcenterpadding']),
            ];

            $slider_responsive_settings = [
                'display_columns' => $settings['slitems'],
                'scroll_columns' => $settings['slscroll_columns'],
                'tablet_width' => $settings['sltablet_width'],
                'tablet_display_columns' => $settings['sltablet_display_columns'],
                'tablet_scroll_columns' => $settings['sltablet_scroll_columns'],
                'mobile_width' => $settings['slmobile_width'],
                'mobile_display_columns' => $settings['slmobile_display_columns'],
                'mobile_scroll_columns' => $settings['slmobile_scroll_columns'],

            ];

            $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );

            $this->add_render_attribute( 'htmega_twitter_attr', 'data-settings', wp_json_encode( $slider_settings ) );
        }

        $consumer_key = ( !empty( $settings['consumer_key'] ) ) ? $settings['consumer_key'] : 'f8rgdbh1TRxnyOmKZRzVooMEQ';
        $consumer_secret = ( !empty( $settings['consumer_secret'] ) ) ? $settings['consumer_secret'] : 'KQTDmHzIMig6PGElowd4KXEjeU0MDAV189vKmyTT6kvumO0giK';
        $access_token = ( !empty( $settings['access_token'] ) ) ? $settings['access_token'] : '1062990223171145729-fcehRzuBPGjD2dkQi44hqgS7ApMSX2';
        $access_token_secret = ( !empty( $settings['access_token_secret'] ) ) ? $settings['access_token_secret'] : '7Ip9Z5uiWP8iYZOCd8EYtOY8Wti4MaWwbUkMFuZndevEo';
        $username = ( !empty( $settings['username'] ) ) ? $settings['username'] : 'hastechit';


        $relative_time      = $settings['relative_time'];
        $show_time_icon      = $settings['show_time_icon'];
        $limit              = ( !empty( $settings['limit'] ) ) ? $settings['limit'] : 5;
        $exclude_replies    = 'false';

        $connection = new \TwitterOAuth( $consumer_key, $consumer_secret, $access_token, $access_token_secret );
        $tweets = $connection->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$username.'&count='.$limit.'&exclude_replies='.$exclude_replies);
        $s_display_none = ( 'yes' == $settings['slider_on'] ) ? ' style="display:none;"':'';

        if( !isset( $tweets->errors ) && is_array( $tweets ) ):
            ?> <div <?php echo $this->get_render_attribute_string('htmega_twitter_attr').$s_display_none; ?>> <?php
                foreach( $tweets as $tweet ):
                    $tweet_link = 'http://twitter.com/'.$tweet->user->screen_name.'/statuses/'.$tweet->id_str;
                    $user_link = 'http://twitter.com/'.$tweet->user->screen_name;
                    $tweet_short_link = $this->get_short_link( $tweet_link );
           
                ?>
                    <div class="htmega-twitter">

                    <?php if( $settings['twitter_style'] == 2 ):?>
                        <div class="htmega-twitter-single">
                            <div class="ht-twitter-container">
                                <?php if( $settings['author_image'] == 'yes' ):?>
                                   <div class="htmega-thumb">
                                        <a href="<?php echo esc_url($user_link); ?>" target="_blank">
                                            <img src="<?php echo esc_url( $tweet->user->profile_image_url ); ?>" title="<?php echo esc_attr( $tweet->user->name ); ?>" alt="<?php echo esc_attr( $tweet->user->name ); ?>">
                                        </a>
                                    </div>
                                <?php endif;?>
                                <div class="htmega-content">
                                    <?php if($settings['author_name'] == 'yes' ): ?>
                                        <div class="htmega-author">
                                            <h6><a href="<?php echo esc_url($user_link); ?>"><?php echo esc_html( $tweet->user->name ); ?> <span><?php echo esc_html( '@'.$username,'htmega-addons' ); ?></span></a></h6>
                                        </div>
                                    <?php endif;?>
                                    <p>
                                        <?php echo wp_trim_words( $tweet->text, $settings['content_length'], ' ' );
                                            if( !empty( $tweet_short_link ) ){
                                                echo '<a href="'.esc_url($tweet_short_link ).'" target="_blank">'.esc_attr( $tweet_short_link ).'</a>';
                                            }
                                        ?>
                                    </p>

                                </div>
                            </div>
                            <div class="twitter-meta-inner">
                                <?php if($settings['show_time'] == 'yes' ): ?>
                                    <span class="twitter-time">
                                        <?php 
                                        if( 'yes'== $show_time_icon ){
                                            echo '<i class="fa fa-clock-o"></i> ';
                                        }
                                            if( $relative_time == 'yes' ){
                                                echo $this->relative_time($tweet->created_at);
                                            }
                                            else{
                                                echo $this->date_format( $tweet->created_at );
                                            }
                                        ?>
                                    </span>
                                <?php endif;?>
                                <?php if( $settings['twitter_meta'] == 'yes' ): ?>
                                    <ul class="twitter-meta">
                                        <li><a href="https://twitter.com/intent/favorite?tweet_id=<?php echo esc_attr__( $tweet->id_str ); ?>" target="_blank"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="https://twitter.com/intent/retweet?tweet_id=<?php echo esc_attr__( $tweet->id_str ); ?>"><i class="fa fa-repeat"></i></a></li>
                                        <li><a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo esc_attr__( $tweet->id_str ); ?>" target="_blank"><i class="fa fa-reply"></i></a></li>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>

                    <?php else:?>
                        <div class="htmega-twitter-single">
                            <?php if($settings['author_image'] == 'yes' ): ?>
                                <div class="htmega-thumb">
                                    <a href="<?php echo esc_url($user_link); ?>" target="_blank">
                                        <img src="<?php echo esc_url( $tweet->user->profile_image_url ); ?>" title="<?php echo esc_attr( $tweet->user->name ); ?>" alt="<?php echo esc_attr( $tweet->user->name ); ?>">
                                    </a>
                                </div>
                            <?php endif;?>
                            <div class="htmega-content">
                                <div class="htmega-author">
                                    <?php if($settings['author_name'] == 'yes' ): ?>
                                        <h6><a href="<?php echo esc_url($user_link); ?>" target="_blank"><?php echo esc_html($tweet->user->name); ?></a></h6>
                                    <?php endif;?>
                                    <?php if($settings['show_time'] == 'yes' ): ?>
                                        <span class="twitter-time">
                                            <?php 
                                                if( 'yes'== $show_time_icon ){
                                                    echo '<i class="fa fa-clock-o"></i> ';
                                                }
                                                if( $relative_time == 'yes' ){
                                                    echo $this->relative_time($tweet->created_at);
                                                }
                                                else{
                                                    echo $this->date_format( $tweet->created_at );
                                                }
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p>
                                    <?php echo wp_trim_words( $tweet->text, $settings['content_length'], ' ' );
                                        if( !empty( $tweet_short_link ) ){
                                            echo '<a href="'.esc_url($tweet_short_link ).'" target="_blank">'.esc_attr( $tweet_short_link ).'</a>';
                                        }
                                    ?>
                                </p>

                                <?php if( $settings['twitter_meta'] == 'yes' ): ?>
                                    <ul class="twitter-meta">
                                        <li><a href="https://twitter.com/intent/favorite?tweet_id=<?php echo esc_attr__( $tweet->id_str ); ?>" target="_blank"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="https://twitter.com/intent/retweet?tweet_id=<?php echo esc_attr__( $tweet->id_str ); ?>"><i class="fa fa-repeat"></i></a></li>
                                        <li><a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo esc_attr__( $tweet->id_str ); ?>" target="_blank"><i class="fa fa-reply"></i></a></li>
                                    </ul>
                                <?php endif;?>

                            </div>
                        </div>
                    <?php endif;?>

                    </div>

                <?php

                endforeach;
            ?> </div> <?php
        endif;

    }

    public function relative_time( $time ){
        $second = 1;
        $minute = 60 * $second;
        $hour = 60 * $minute;
        $day = 24 * $hour;
        $month = 30 * $day;

        $delta = strtotime('+0 hours') - strtotime($time);
        if ($delta < 2 * $minute) {
            return esc_html__('1 min ago', 'htmega-addons');
        }
        if ($delta < 45 * $minute) {
            return floor($delta / $minute) . esc_html__(' min ago', 'htmega-addons');
        }
        if ($delta < 90 * $minute) {
            return esc_html__('1 hour ago', 'htmega-addons');
        }
        if ($delta < 24 * $hour) {
            return floor($delta / $hour) . esc_html__(' hours ago', 'htmega-addons');
        }
        if ($delta < 48 * $hour) {
            return esc_html__('yesterday', 'htmega-addons');
        }
        if ($delta < 30 * $day) {
            return floor($delta / $day) . esc_html__(' days ago', 'htmega-addons');
        }
        if ($delta < 12 * $month) {
            $months = floor($delta / $day / 30);
            return $months <= 1 ? esc_html__('1 month ago', 'htmega-addons') : $months . esc_html__(' months ago', 'htmega-addons');
        } else {
            $years = floor($delta / $day / 365);
            return $years <= 1 ? esc_html__('1 year ago', 'htmega-addons') : $years . esc_html__(' years ago', 'htmega-addons');
        }
    }
        
    public function date_format($time){
        return mysql2date(get_option('time_format'), $time) . ' - ' . mysql2date(get_option('date_format'), $time);
    }
    
    public function get_short_link($url){
        $result = wp_remote_post( add_query_arg( 'key', apply_filters( 'google_api_key', 'AIzaSyBEPh-As7b5US77SgxbZUfMXAwWYjfpWYg' ), 'https://www.googleapis.com/urlshortener/v1/url' ), array(
            'body' => json_encode( array( 'longUrl' => esc_url_raw( $url ) ) ),
            'headers' => array( 'Content-Type' => 'application/json' ),
        ) );

        /* Return the URL if the request got an error. */
        if( is_wp_error( $result ) ){
            return '';
        }
        $result = json_decode( $result['body'] );
        if(isset($result->id)){
            $shortlink = $result->id;
            if( $shortlink ){
                return $shortlink;
            }
        }
        return '';
    }

}

