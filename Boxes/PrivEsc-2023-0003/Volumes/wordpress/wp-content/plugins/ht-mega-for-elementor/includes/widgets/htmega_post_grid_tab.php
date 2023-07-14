<?php
namespace Elementor;

// Elementor Classes

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Post_Grid_Tab extends Widget_Base {

    public function get_name() {
        return 'htmega-postgridtab-addons';
    }
    
    public function get_title() {
        return __( 'Post Grid Tab', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'htmega-widgets-scripts',
        ];
    }
    public function get_keywords() {
        return [ 'post grid tab', 'post tab','custom post grid','post grid','post','htmega addons' ];
    }
    
    public function get_help_url() {
		return 'https://wphtmega.com/docs/post-widgets/post-grid-tab-widget/';
	}
    protected function register_controls() {

        $this->start_controls_section(
            'post_gridtab_content',
            [
                'label' => __( 'Post Grid Tab', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'post_grid_style',
                [
                    'label' => __( 'Layout', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Layout One', 'htmega-addons' ),
                        '2'   => __( 'Layout Two', 'htmega-addons' ),
                        '3'   => __( 'Layout Three', 'htmega-addons' ),
                        '4'   => __( 'Layout Four', 'htmega-addons' ),
                        '5'   => __( 'Layout Five', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Content Option Start
        $this->start_controls_section(
            'post_content_option',
            [
                'label' => __( 'Post Option', 'htmega-addons' ),
            ]
        );
            $this->show_post_source();

            $this->add_control(
                'show_title',
                [
                    'label' => esc_html__( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            $this->add_control(
                'title_length',
                [
                    'label' => __('Title Length', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 8,
                    'condition' =>[
                        'show_title' => 'yes',
                    ]
                    
                ]
            );            
            $this->add_control(
                'show_content',
                [
                    'label' => esc_html__( 'Content', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            $this->add_control(
                'content_limit',
                [
                    'label' => __('Content Length', 'htmega-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 50,
                    'condition' =>[
                        'show_content' => 'yes',
                    ]
                    
                ]
            );
            $this->add_control(
                'show_category',
                [
                    'label' => esc_html__( 'Category', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_date',
                [
                    'label' => esc_html__( 'Meta', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_read_more_btn',
                [
                    'label' => esc_html__( 'Read More', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'read_more_txt',
                [
                    'label' => __( 'Read More button text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Read More', 'htmega-addons' ),
                    'placeholder' => __( 'Read More', 'htmega-addons' ),
                    'condition'=>[
                        'show_read_more_btn'=>'yes',
                    ]
                ]
            );

        $this->end_controls_section(); // Content Option End

        // Style tab section
        $this->start_controls_section(
            'post_gridtab_style_section',
            [
                'label' => __( 'Expand Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'post_gridtab_item_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .single-post-grid-tab',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'post_gridtab_item_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .single-post-grid-tab',
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_item_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab,
                        {{WRAPPER}} .htmega-post-gridtab-layout-2 .post-content,
                        {{WRAPPER}} .htmega-post-gridtab-layout-4 .post-content,
                        {{WRAPPER}} .htmega-post-gridtab-layout-5 .post-content
                        
                        ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'post_gridtab_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'post_gridtab_item_border_radius_image',
                [
                    'label' => esc_html__( 'Image Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .thumb' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_control(
                'Content_box_heading',
                [
                    'label' => __( 'Content Box Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' =>'before',
                ]
            ); 
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'post_gridtab_item_background_box',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-mega-post-grid-right .content',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'post_gridtab_item_border_box',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-mega-post-grid-right .content',
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_item_border_radius_box',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .ht-mega-post-grid-right .content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_item_margin_box',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-mega-post-grid-right .content
                        
                        ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'post_gridtab_item_padding_box',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-mega-post-grid-right .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'post_gridtab_item_alignment_box',
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
                        '{{WRAPPER}} .single-post-grid-tab .post-inner' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section();


        // Style Title tab section
        $this->start_controls_section(
            'post_slider_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_title'=>'yes',
                ]
            ]
        );
            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#494849',
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2 a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'title_color_hover',
                [
                    'label' => __( 'Hover Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2 a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner h2',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_align',
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
                        '{{WRAPPER}} .single-post-grid-tab .post-inner h2' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Content tab section
        $this->start_controls_section(
            'post_slider_content_style_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_content'=>'yes',
                ]
            ]
        );
            $this->add_control(
                'content_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#494849',
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner p',
                ]
            );

            $this->add_responsive_control(
                'content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .single-post-grid-tab .post-inner p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_align',
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
                        '{{WRAPPER}} .single-post-grid-tab .post-inner p' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Category tab section
        $this->start_controls_section(
            'post_slider_category_style_section',
            [
                'label' => __( 'Category', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_category'=>'yes',
                ]
            ]
        );
            
            $this->start_controls_tabs('category_style_tabs');

                $this->start_controls_tab(
                    'category_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'category_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'category_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
    
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'category_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'category_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'category_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a',
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'category_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'category_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'category_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-category li a:hover',
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Meta tab section
        $this->start_controls_section(
            'post_meta_style_section',
            [
                'label' => __( 'Meta', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_date'=>'yes',
                ]
                
            ]
        );
            $this->add_control(
                'meta_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#464545',
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'meta_color_hover',
                [
                    'label' => __( 'Meta Hover Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );            
            $this->add_control(
                'meta_icon_color',
                [
                    'label' => __( 'Icon Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta i' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li a i' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'meta_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li',
                ]
            );

            $this->add_responsive_control(
                'meta_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'meta_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'meta_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'end' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single-post-grid-tab .post-inner .meta' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Read More button tab section
        $this->start_controls_section(
            'post_slider_readmore_style_section',
            [
                'label' => __( 'Read More', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_read_more_btn'=>'yes',
                    'read_more_txt!'=>'',
                ]
            ]
        );
            
            $this->start_controls_tabs('readmore_style_tabs');

                $this->start_controls_tab(
                    'readmore_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'readmore_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default'=>'#494849',
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'readmore_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
    
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'readmore_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'readmore_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'readmore_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'readmore_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default'=>'#494849',
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'readmore_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'readmore_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single-post-grid-tab .post-inner .post-btn a.readmore-btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Read More button tab section
        $this->start_controls_section(
            'post_slider_close_style_section',
            [
                'label' => __( 'Close', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('post_slider_close_style_tabs');

                $this->start_controls_tab(
                    'post_slider_close_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_slider_close_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .post-content .close__wrap button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'post_slider_close_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .post-content .close__wrap button',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'closer_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .post-content .close__wrap button',
                        ]
                    );                   
                    $this->add_responsive_control(
                        'closer_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .post-content .close__wrap button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'closer_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .post-content .close__wrap button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                $this->end_controls_tab(); // Normal Tab

                $this->start_controls_tab(
                    'post_slider_close_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_slider_close_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .post-content .close__wrap button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'post_slider_close_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .post-content .close__wrap button:hover',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'closer_border_hover',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .post-content .close__wrap button:hover',
                        ]
                    ); 
                $this->end_controls_tab(); // Hover Tab

            $this->end_controls_tabs();

        $this->end_controls_section();
        // Group Item Style
        $this->start_controls_section(
            'post_gridtab_style_items',
            [
                'label' => __( 'Group Item Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'post_gridtab_items_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .single-post-grid-tab',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'post_gridtab_items_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .post-grid',
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_items_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .post-grid' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'post_gridtab_items_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .post-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .ht-post-grid-tab .post-grid.post-grid-half' => 'min-width: calc(50% - 2*{{RIGHT}}{{UNIT}});width: calc(50% - 2*{{RIGHT}}{{UNIT}});',
                        '{{WRAPPER}} .ht-post-grid-tab' => 'margin: 0 -{{RIGHT}}{{UNIT}});',
                        '{{WRAPPER}} .ht-post-grid-tab .post-grid.post-grid-one-third' => 'min-width: calc(33.33% - 2*{{RIGHT}}{{UNIT}}); width: calc(33.33% - 2*{{RIGHT}}{{UNIT}});',
                        '{{WRAPPER}} .ht-post-grid-tab .post-grid.post-grid-four' => 'min-width: calc(25% - 2*{{RIGHT}}{{UNIT}}); width: calc(25% - 2*{{RIGHT}}{{UNIT}});',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'post_gridtab_items_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .post-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();
        // Group Item gradient Style
        $this->start_controls_section(
            'post_gridtab_gradients_tyle_items',
            [
                'label' => __( 'Custom Gradient Color', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=> [
                    'post_grid_style'=> array('1','5'),
                    
                ],
            ]
        );
            $this->add_control(
                'gradient1_heading',
                [
                    'label' => __( 'Item One Gradient', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );  
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'gradient1',
                    'label' => __( 'Gradient One', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gradient-overlay.gradient-overlay-1 .thumb a::before',
                    'separator'=>'after'
                ]
            );
            $this->add_control(
                'gradient2_heading',
                [
                    'label' => __( 'Item Two Gradient', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator'=>'before'
                ]
            );  
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'gradient2',
                    'label' => __( 'Gradient One', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gradient-overlay.gradient-overlay-2 .thumb a::before',
                    'separator'=>'before'
                ]
            );
            $this->add_control(
                'gradient3_heading',
                [
                    'label' => __( 'Item Three Gradient', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator'=>'before'
                ]
            );  
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'gradient3',
                    'label' => __( 'Gradient Three', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gradient-overlay.gradient-overlay-3 .thumb a::before',
                    'separator'=>'before'
                ]
            );
            $this->add_control(
                'gradient4_heading',
                [
                    'label' => __( 'Item Four Gradient', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator'=>'before'
                ]
            );  
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'gradient4',
                    'label' => __( 'Gradient Four', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gradient-overlay.gradient-overlay-4 .thumb a::before',
                    'separator'=>'before'
                ]
            );
            $this->add_control(
                'gradient5_heading',
                [
                    'label' => __( 'Item Five Gradient', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator'=>'before'
                ]
            );  
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'gradient5',
                    'label' => __( 'Gradient Five', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gradient-overlay.gradient-overlay-5 .thumb a::before',
                    'separator'=>'before'
                ]
            );
            $this->add_control(
                'gradient6_heading',
                [
                    'label' => __( 'Item Six Gradient', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator'=>'before'
                ]
            );  
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'gradient6',
                    'label' => __( 'Gradient ', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gradient-overlay.gradient-overlay-6 .thumb a::before',
                    'separator'=>'before'
                ]
            );
            $this->add_control(
                'gradient7_heading',
                [
                    'label' => __( 'Item Seven Gradient', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator'=>'before'
                ]
            );  
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'gradient7',
                    'label' => __( 'Gradient ', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gradient-overlay.gradient-overlay-7 .thumb a::before',
                    'separator'=>'before'
                ]
            );
        $this->end_controls_section();
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $post_type = $settings['grid_post_type'];
        if( 'post'== $post_type ){
            $post_categorys = $settings['grid_categories'];
        } else if( 'product'== $post_type ){
            $post_categorys = $settings['grid_prod_categories'];
        }else {
            $post_categorys = $settings[ $post_type.'_post_category'];
        }
        $post_author = $settings['post_author'];
        $exclude_posts = $settings['exclude_posts'];
        $orderby            = $this->get_settings_for_display('orderby');
        $postorder          = $this->get_settings_for_display('postorder');
        $category_name =  get_object_taxonomies($post_type);
        $id = $this->get_id();

        $this->add_render_attribute( 'htmega_post_gridtab', 'class', 'ht-post-grid-tab htmega-post-gridtab-layout-'.$settings['post_grid_style'] );

        // Post query
        $args = array(
            'post_type'             => $post_type,
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => !empty( $settings['post_limit'] ) ? (int)$settings['post_limit'] : 3,
        );

        if (  !empty( $post_categorys ) ) {

            if( $category_name['0'] == "product_type" ){
                    $category_name['0'] = 'product_cat';
            }

            if( is_array($post_categorys) && count($post_categorys) > 0 ){

                $field_name = is_numeric( $post_categorys[0] ) ? 'term_id' : 'slug';
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $category_name[0],
                        'terms' => $post_categorys,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }
        }
        // author check
        if (  !empty( $post_author ) ) {
            $args['author__in'] = $post_author;
        }
        // order by  check
        if ( !empty( $orderby ) ) {
            if ( 'date' == $orderby && 'yes'== $settings['custom_order_by_date'] && (!empty( $settings['order_by_date_after'] || $settings['order_by_date_before'] ) ) ) {
            $order_by_date_after = strtotime($settings['order_by_date_after']);
            $order_by_date_before = strtotime($settings['order_by_date_before']);
                $args['date_query'] = array(
                    array(
                        'before'    => array(
                            'year'  => date('Y', $order_by_date_before),
                            'month' =>date('m', $order_by_date_before),
                            'day'   => date('d', $order_by_date_before),
                        ),
                        'after'    => array(
                            'year'  => date('Y', $order_by_date_after),
                            'month' =>date('m', $order_by_date_after),
                            'day'   => date('d', $order_by_date_after),
                        ),
                        'inclusive' => true,
                    ),
                );

            } else {
                $args['orderby'] = $orderby;
            }
        }

        // Exclude posts check
        if (  !empty( $exclude_posts ) ) {
            $exclude_posts = explode(',',$exclude_posts);
            $args['post__not_in'] =  $exclude_posts;
        }

        // Order check
        if (  !empty( $postorder ) ) {
            $args['order'] =  $postorder;
        }

        $grid_post = new \WP_Query( $args );

        $tabs_options = [];
        $tabs_options['wrapid'] = $id;
        $this->add_render_attribute( 'htmega_post_gridtab', 'data-postgridtab', wp_json_encode( $tabs_options ) );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_post_gridtab' ); ?>>
                <?php
                if( $grid_post->have_posts() ):
                    $countrow = $gdc = $rowcount = $item_class = 0;

                    while( $grid_post->have_posts() ) : $grid_post->the_post();
                        $countrow++;
                        $gdc++;
                        if( $gdc > 6){ $gdc = 1; }
                        ?>
                    
                        <?php 
                        if( $settings['post_grid_style'] == 2 ):

                            if( $countrow <= 4 ){
                                $order_content = 5;
                            }else{
                                $order_content = 10;
                            }
                        ?>
                        <div class="post-gridthumb-<?php echo esc_attr( $id ); ?> post-grid post-grid-four htb-order-<?php echo esc_attr( $countrow );?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                        <!-- Grid Content -->
                        <div class="post-gridcontent-<?php echo esc_attr( $id ); ?> post-content htb-order-<?php echo esc_attr( $order_content ); if( $countrow == 1 ){ echo ' is-visible'; } ?>">
                            <?php $this->render_post_content(0, $id, $grid_post->ID); ?>
                        </div>

                        <?php 
                        elseif( $settings['post_grid_style'] == 3 ):
                            $image_size = 'full';
                            if( $countrow <= 3 ){
                                $order_content = 4;
                            }else{ $order_content = 7; }

                            // Item Class
                            if( $countrow % 3 == 0 ){
                                $item_class = 'post-grid-half';
                                $image_size = 'htmega_size_585x295';
                            }else{
                                $item_class = 'post-grid-four';
                            }
                        ?>

                        <div class="post-gridthumb-<?php echo esc_attr( $id ); ?> post-grid htb-order-<?php echo esc_attr( $countrow ); echo ' '.esc_attr( $item_class ); ?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail( $image_size ); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                         <div class="post-gridcontent-<?php echo esc_attr( $id ); ?> post-content htb-order-<?php echo esc_attr( $order_content );  ?>">
                            <?php $this->render_post_content(0, $id, $grid_post->ID); ?>
                        </div>

                        <?php 
                        elseif( $settings['post_grid_style'] == 4 ):

                            if( $countrow <= 3 ){
                                $order_content = 4;
                            }else{ $order_content = 7; }

                            // Item Class
                            $item_class = 'post-grid-one-third';
                        ?>

                        <div class="post-gridthumb-<?php echo esc_attr( $id ); ?> post-grid htb-order-<?php echo esc_attr( $countrow ); echo ' '.esc_attr( $item_class ); ?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                         <div class="post-gridcontent-<?php echo esc_attr( $id ); ?> post-content htb-order-<?php echo esc_attr( $order_content );  ?>">
                            <?php $this->render_post_content(0, $id, $grid_post->ID); ?>
                        </div>

                        <?php 
                        elseif( $settings['post_grid_style'] == 5 ):

                            if( $countrow <= 2 ){
                                $order_content = 3;
                            }else{ $order_content = 6; }

                            // Item Class
                            if( $countrow <= 2 ){
                                $item_class = 'post-grid-half';
                            }else{
                                $item_class = 'post-grid-one-third';
                            }
                        ?>
                        <div class="post-gridthumb-<?php echo esc_attr( $id ); ?> post-grid htb-order-<?php echo esc_attr( $countrow ); echo ' '.esc_attr( $item_class ); ?> gradient-overlay gradient-overlay-<?php echo esc_attr( $gdc );?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                         <div class="post-gridcontent-<?php echo esc_attr( $id ); ?> post-content htb-order-<?php echo esc_attr( $order_content );  ?>">
                            <?php $this->render_post_content($gdc, $id, $grid_post->ID); ?>
                        </div>

                        <?php else:

                        if( $countrow <= 3 ){
                            $item_class = 'post-grid-one-third';
                        }
                        if( $countrow >= 4 ){
                            $item_class = 'post-grid-half';
                        }
                        // Content Class
                        $order_content = 4;
                        if( $countrow > 3 ){
                            $order_content = 6;
                        }
                        ?>
                        <div class="post-gridthumb-<?php echo esc_attr( $id ); ?> post-grid htb-order-<?php echo esc_attr( $countrow ); echo ' '.esc_attr( $item_class );?> gradient-overlay gradient-overlay-<?php echo esc_attr( $gdc );?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                        <!-- Grid Content -->
                        <div class="post-gridcontent-<?php echo esc_attr( $id ); ?> post-content htb-order-<?php echo esc_attr( $order_content ); if( $countrow == 1 ){ echo esc_attr( ' is-visible' ); } ?>">
                            <?php $this->render_post_content( $gdc, $id, $grid_post->ID); ?>
                        </div>
                        <?php endif;?>

                        <?php 
                    endwhile; wp_reset_postdata(); wp_reset_query(); 
                else:
                    echo "<div class='htmega-error-notice'>".esc_html__('There are no posts in this query','htmega-addons')."</div>";
                endif;
                ?>

            </div>
        <?php
    }

    public function render_post_content( $gdc = NULL, $id = NULL, $post_id = null ){

        $settings   = $this->get_settings_for_display();
        $category_name =  get_object_taxonomies($settings['grid_post_type']);
        ?>
            <!-- Start Post Slider -->
            <div class="single-post-grid-tab">
                <div class="htb-row"><!-- htb-align-items-center -->
                    <div class="htb-col-lg-6 ht-mega-post-grid-left">
                        <div class="gradient-overlay gradient-overlay-<?php echo esc_attr( $gdc );?>">
                            <div class="thumb">
                                <a href="<?php the_permalink();?>">
                                    <?php 
                                        if ( has_post_thumbnail() ){
                                            the_post_thumbnail(); 
                                        }else{
                                            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="htb-col-lg-6 ht-mega-post-grid-right">
                        <div class="content">
                            <div class="post-inner">
                                <?php
                                    if( $settings['show_category'] == 'yes' ){
                                        if( $category_name ){
                                            $get_terms = get_the_terms( $post_id, $category_name[0] );
                                            if( $settings['grid_post_type'] == 'product' ){
                                                $get_terms = get_the_terms($post_id, 'product_cat');
                                            }
                                            if( $get_terms ){
                                                echo '<ul class="post-category">';
                                                foreach ( $get_terms as $category ) {
                                                    $term_link = get_term_link( $category );
                                                    ?>
                                                        <li><a href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></a></li>
                                                    <?php
                                                }
                                        
                                                echo '</ul>';
                                            }
                                        }
                                    }
                                ?>
                                <?php if( $settings['show_title'] == 'yes' ):
                                    
                                    $title_length = $settings['title_length'];
                                    
                                    if ( 0 > $title_length ) { ?>
                                        <h2><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
                                    <?php
                                    } else { ?>
                                        <h2><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), (int)$title_length, '' ); ?></a></h2>
                                    <?php
                                     }
                                    ?>

                                    <?php endif; if( $settings['show_date'] == 'yes' ): ?>
                                    <ul class="meta">
                                        <li><i class="fa fa-user-circle"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                        <li><i class="fa fa-clock-o"></i> <?php the_time( 'd F Y' ); ?></li>
                                    </ul>
                                    <?php endif; if( $settings['show_content'] == 'yes' ):
                                       $content_limit =  !empty( $settings['content_limit'] ) ? $settings['content_limit'] : 50;
                                        
                                        ?>
                                    
                                    <p><?php echo wp_trim_words( get_the_content(), (int)$content_limit, '' ); ?></p>

                                <?php endif; if( $settings['show_read_more_btn'] == 'yes' ): ?>
                                    <div class="post-btn">
                                        <a class="readmore-btn" href="<?php the_permalink();?>"><?php echo htmega_kses_desc( $settings['read_more_txt'] );?></a>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Post Slider -->
            <!-- Close Btn -->
            <div class="post-gridclose-<?php echo esc_attr( $id ); ?> close__wrap">
                <button><i class="fa fa-times"></i></button>
            </div>

        <?php
    }
    // post query fields
    public function show_post_source(){

        $this->add_control(
            'grid_post_type',
            [
                'label' => esc_html__( 'Post Type', 'htmega-addons' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => htmega_get_post_types(),
                'default' =>'post',
                'frontend_available' => true,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'include_by',
            [
                'label' => __( 'Include By', 'htmega-addons' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'default' =>'in_category',
                'options' => [
                    'in_author'      => __( 'Author', 'htmega-addons' ),
                    'in_category'      => __( 'Category', 'htmega-addons' ),
                ],
            ]
        );
        $this->add_control(
            'post_author',
            [
                'label' => esc_html__( 'Authors', 'htmega-addons' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => htmega_get_authors_list(),
                'condition' =>[
                    'include_by' => 'in_author',
                ]
            ]
        );
        $all_post_type = htmega_get_post_types();
        foreach( $all_post_type as $post_key => $post_item ){
            
            if( 'post' == $post_key ){
                $this->add_control(
                    'grid_categories',
                    [
                        'label' => esc_html__( 'Categories', 'htmega-addons' ),
                        'type' => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'multiple' => true,
                        'options' => htmega_get_taxonomies(),
                        'condition' =>[
                            'grid_post_type' => 'post',
                            'include_by' => 'in_category',
                        ]
                    ]
                );
            } else if( 'product' == $post_key){
                $this->add_control(
                    'grid_prod_categories',
                    [
                        'label' => esc_html__( 'Categories', 'htmega-addons' ),
                        'type' => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'multiple' => true,
                        'options' => htmega_get_taxonomies('product_cat'),
                        'condition' =>[
                            'grid_post_type' => 'product',
                            'include_by' => 'in_category',
                        ]
                    ]
                );

            } else {
                $this->add_control(
                    "{$post_key}_post_category",
                    [
                        'label' => esc_html__( 'Select Categories', 'htmega-addons' ),
                        'type' => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'multiple' => true,
                        'options' => all_object_taxonomie_show_catagory($post_key),
                        'condition' => [
                            'grid_post_type' => $post_key,
                            'include_by' => 'in_category',
                        ],
                    ]
                );
            }

        }
        $this->add_control(
            "exclude_posts",
            [
                'label' => esc_html__( 'Exclude Posts', 'htmega-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__( 'Example: 10,11,105', 'htmega-addons' ),
                'description' => esc_html__( "To Exclude Post, Enter  the post id separated by ','", 'htmega-addons' ),
            ]
        );
        $this->add_control(
            'post_limit',
            [
                'label' => __('Limit', 'htmega-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'separator'=>'before',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__( 'Order By', 'htmega-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'ID'            => esc_html__('ID','htmega-addons'),
                    'date'          => esc_html__('Date','htmega-addons'),
                    'name'          => esc_html__('Name','htmega-addons'),
                    'title'         => esc_html__('Title','htmega-addons'),
                    'comment_count' => esc_html__('Comment count','htmega-addons'),
                    'rand'          => esc_html__('Random','htmega-addons'),
                ],
            ]
        );
        $this->add_control(
            'custom_order_by_date',
            [
                'label' => esc_html__( 'Custom Date', 'htmega-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'condition' =>[
                    'orderby'=>'date'
                ]
            ]
        );
        $this->add_control(
            'order_by_date_before',
            [
                'label' => __( 'Before Date', 'htmega-addons' ),
                'type' => Controls_Manager::DATE_TIME,
                'condition' =>[
                    'orderby'=>'date',
                    'custom_order_by_date'=>'yes',
                ]
            ]
        );
        $this->add_control(
            'order_by_date_after',
            [
                'label' => __( 'After Date', 'htmega-addons' ),
                'type' => Controls_Manager::DATE_TIME,
                'condition' =>[
                    'orderby'=>'date',
                    'custom_order_by_date'=>'yes',
                ]
            ]
        );
        $this->add_control(
            'postorder',
            [
                'label' => esc_html__( 'Order', 'htmega-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC'  => esc_html__('Descending','htmega-addons'),
                    'ASC'   => esc_html__('Ascending','htmega-addons'),
                ],
                'separator' => 'after'

            ]
        );
    }
}

