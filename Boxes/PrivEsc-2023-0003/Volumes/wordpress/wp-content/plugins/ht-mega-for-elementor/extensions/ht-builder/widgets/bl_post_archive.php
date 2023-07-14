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
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Post_Archive_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-archive';
    }

    public function get_title() {
        return __( 'BL: Archive Posts', 'htmega-addons' );
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'post-layout-setting',
            [
                'label' => __( 'Settings', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'post_layout',
                [
                    'label' => __( 'Layout', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'one',
                    'options' => [
                        'one' => __( 'Layout One', 'htmega-addons' ),
                        'two' => __( 'Layout Two', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'post_grid_column',
                [
                    'label' => __( 'Columns', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '4',
                    'options' => [
                        '1' => __( '1', 'htmega-addons' ),
                        '2' => __( '2', 'htmega-addons' ),
                        '3' => __( '3', 'htmega-addons' ),
                        '4' => __( '4', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'read_more_btn_text',
                [
                    'label' => __( 'Read More Button Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Read more', 'htmega-addons' ),
                    'placeholder' => __( 'Enter Your Text', 'htmega-addons' ),
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'imagesize',
                    'default' => 'full',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'post_more_options',
                [
                    'label' => __( 'Additional Options', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'show_title',
                [
                    'label' => __( 'Show Title', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_category',
                [
                    'label' => __( 'Show Category', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_meta_info',
                [
                    'label' => __( 'Show Post Meta', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_read_more',
                [
                    'label' => __( 'Show Read More Button', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

        $this->end_controls_section();

        // Post Query
        $this->start_controls_section(
            'posts-query',
            [
                'label' => __( 'Query Settings', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'content_length',
                [
                    'label' => __( 'Content Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                    'default' => 50,
                ]
            );

            $this->add_control(
                'title_length',
                [
                    'label' => __( 'Title Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                    'default' => 6,
                    'condition'=> [
                        'show_title' => 'yes'
                    ]
                ]
            );

            $this->add_control(
                'post_custom_order',
                [
                    'label' => __( 'Custom order', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => __( 'Orderby', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'          => __('None','htmega-addons'),
                        'ID'            => __('ID','htmega-addons'),
                        'date'          => __('Date','htmega-addons'),
                        'name'          => __('Name','htmega-addons'),
                        'title'         => __('Title','htmega-addons'),
                        'comment_count' => __('Comment count','htmega-addons'),
                        'rand'          => __('Random','htmega-addons'),
                    ],
                    'condition' => [
                        'post_custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => __( 'order', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => __('Descending','htmega-addons'),
                        'ASC'   => __('Ascending','htmega-addons'),
                    ],
                    'condition' => [
                        'post_custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'hide_pagination_button',
                [
                    'label' => __( 'Hide Pagination', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-pagination' => 'display: none;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Item Box Style
        $this->start_controls_section(
            'post_item_style_section',
            array(
                'label' => __( 'Item', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_responsive_control(
                'post_item_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-single-post' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'post_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-single-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'post_item_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htbuilder-single-post',
                ]
            );

            $this->add_responsive_control(
                'post_item_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-single-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'post_item_align',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
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
                        '{{WRAPPER}} .htbuilder-single-post'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section(
            'post_title_style_section',
            array(
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=> [
                    'show_title' => 'yes'
                ]
            )
        );

            $this->start_controls_tabs( 'title_style_tabs' );
                
                // Title Normal Style
                $this->start_controls_tab(
                    'style_title_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'post_title_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-title a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'post_title_typography',
                            'label'     => __( 'Typography', 'htmega-addons' ),
                            'selector'  => '{{WRAPPER}} .htbuilder-post-title',
                        )
                    );

                    $this->add_responsive_control(
                        'post_title_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'post_title_align',
                        [
                            'label'   => __( 'Alignment', 'htmega-addons' ),
                            'type'    => Controls_Manager::CHOOSE,
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
                                '{{WRAPPER}} .htbuilder-post-title'   => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();
                
                // Title Hover
                $this->start_controls_tab(
                    'style_title_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_title_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-title a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Meta Info Style
        $this->start_controls_section(
            'post_meta_info_style_section',
            array(
                'label' => __( 'Meta Info', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=> [
                    'show_meta_info' => 'yes'
                ]
            )
        );

            $this->start_controls_tabs('post_meta_info_style_tabs');

                // Meta info Normal
                $this->start_controls_tab(
                    'post_meta_info_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_meta_info_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-meta-info' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htbuilder-post-meta-info a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'post_meta_info_typography',
                            'label'     => __( 'Typography', 'htmega-addons' ),
                            'selector'  => '{{WRAPPER}} .htbuilder-post-meta-info',
                        )
                    );

                    $this->add_responsive_control(
                        'post_meta_info_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-meta-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'post_meta_info_align',
                        [
                            'label'   => __( 'Alignment', 'htmega-addons' ),
                            'type'    => Controls_Manager::CHOOSE,
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
                                '{{WRAPPER}} .htbuilder-post-meta-info'   => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Meta Info Hover
                $this->start_controls_tab(
                    'post_meta_info_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_meta_info_hover_color',
                        [
                            'label'     => __( 'Link Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-meta-info a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Category Style
        $this->start_controls_section(
            'post_meta_category_style_section',
            array(
                'label' => __( 'Category', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=> [
                    'show_category' => 'yes'
                ]
            )
        );

            $this->start_controls_tabs('post_meta_category_style_tabs');

                // Category Normal
                $this->start_controls_tab(
                    'post_meta_category_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_meta_category_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-cat a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'post_meta_category_typography',
                            'label'     => __( 'Typography', 'htmega-addons' ),
                            'selector'  => '{{WRAPPER}} .htbuilder-post-cat a',
                        )
                    );

                    $this->add_responsive_control(
                        'post_meta_category_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-cat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'post_meta_category_align',
                        [
                            'label'   => __( 'Alignment', 'htmega-addons' ),
                            'type'    => Controls_Manager::CHOOSE,
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
                                '{{WRAPPER}} .htbuilder-post-cat'   => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Meta Info Hover
                $this->start_controls_tab(
                    'post_meta_category_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_meta_category_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-post-cat a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section(
            'post_content_style_section',
            array(
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'post_content_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-post-content p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'post_content_typography',
                    'label'     => __( 'Typography', 'htmega-addons' ),
                    'selector'  => '{{WRAPPER}} .htbuilder-post-content p',
                )
            );

            $this->add_responsive_control(
                'post_content_align',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
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
                        '{{WRAPPER}} .htbuilder-post-content p'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Read More Style
        $this->start_controls_section(
            'post_readmore_style_section',
            array(
                'label' => __( 'Read More', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=> [
                    'show_read_more' => 'yes'
                ]
            )
        );

            $this->start_controls_tabs('post_readmore_style_tabs');

                // Read More Normal
                $this->start_controls_tab(
                    'post_readmore_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_readmore_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-read-more-btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'post_readmore_typography',
                            'label'     => __( 'Typography', 'htmega-addons' ),
                            'selector'  => '{{WRAPPER}} .htbuilder-read-more-btn',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_readmore_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-read-more-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'post_readmore_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-read-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'post_readmore_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-read-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'post_readmore_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-read-more-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Read More Hover
                $this->start_controls_tab(
                    'post_readmore_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'post_readmore_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-read-more-btn:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_readmore_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-read-more-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'post_readmore_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-read-more-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Pagination Style
        $this->start_controls_section(
            'post_pagination_style_section',
            array(
                'label' => __( 'Pagination', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'hide_pagination_button!'=>'yes'
                ]
            )
        );

            $this->start_controls_tabs('post_pagination_style_tabs');

                // Read More Normal
                $this->start_controls_tab(
                    'post_pagination_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_pagination_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-pagination ul li a' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htbuilder-pagination ul li span.current' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'post_pagination_typography',
                            'label'     => __( 'Typography', 'htmega-addons' ),
                            'selector'  => '{{WRAPPER}} .htbuilder-pagination ul li a',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_pagination_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-pagination ul li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'post_pagination_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-pagination ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'post_pagination_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-pagination ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Pagination Hover
                $this->start_controls_tab(
                    'post_pagination_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'post_pagination_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-pagination ul li span.current' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htbuilder-pagination ul li:hover a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_pagination_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-pagination ul li:hover a, {{WRAPPER}} .htbuilder-pagination ul li span.current',
                        ]
                    );

                    $this->add_responsive_control(
                        'post_pagination_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-pagination ul li:hover a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .htbuilder-pagination ul li span.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings           = $this->get_settings_for_display();
        $custom_order_ck    = $this->get_settings_for_display('post_custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $order              = $this->get_settings_for_display('order');

        // Search Page Arg
        if( is_search() ){
            global $query_string;
            wp_parse_str( $query_string, $search_query );
            $blog_post = new \WP_Query( $search_query );
        }
        // Other Page Arg
        else{
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            $args = array(
                'post_type'             => 'post',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'paged'                 => $paged, 
            );

            if( $custom_order_ck == 'yes' ){
                $args['orderby'] = $orderby;
                $args['order'] = $order;
            }

            if( is_category() ){
                $termobj = get_queried_object();
                $field_name = 'term_id';
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'category',
                        'terms' => $termobj->term_id,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }elseif( is_tag() ){
                $termobj = get_queried_object();
                $field_name = 'term_id';
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_tag',
                        'terms' => $termobj->term_id,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }elseif( is_author() ){
                $author = get_user_by( 'slug', get_query_var('author_name') );
                $current_user_name = $author->user_nicename;
                $args['author_name'] = $current_user_name;
            }

            $blog_post = new \WP_Query( $args );
        }

        if( $blog_post->have_posts() ):
            ?>
            <div class="htbuilder-post-area htbuilder-col-<?php echo esc_attr($settings['post_grid_column']); ?>">
                <?php
                    while( $blog_post->have_posts() ): $blog_post->the_post();
                        ?>
                            <div class="htbuilder-post-col htbuilder-layout-<?php echo esc_attr($settings['post_layout']);?>">

                                <div class="htbuilder-single-post">
                                    <?php if( has_post_thumbnail() ): ?>
                                        <div class="htbuilder-post-media">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php
                                                    if( $settings['imagesize_size'] == 'custom' ){
                                                        the_post_thumbnail( array( $settings['imagesize_custom_dimension']['width'], $settings['imagesize_custom_dimension']['height'] ) );
                                                    }else{
                                                        the_post_thumbnail( $settings['imagesize_size'] ); 
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="htbuilder-post-info <?php if( !has_post_thumbnail() ){ echo 'htbuilder-noimg'; } ?>">

                                        <?php if( $settings['show_category'] == 'yes' ): ?>
                                            <div class="htbuilder-post-cat">
                                                <?php
                                                    $i=0;
                                                    foreach ( get_the_category() as $category ) {
                                                        $i++;
                                                        $term_link = get_term_link( $category );
                                                        ?>
                                                            <a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $category->name );?></a>
                                                        <?php
                                                        if( $i == 2 ){ break; }
                                                    }
                                                ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if( $settings['show_title'] == 'yes' ): ?>
                                            <h3 class="htbuilder-post-title">
                                                <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a>
                                            </h3>
                                        <?php endif; ?>

                                        <?php if( $settings['show_meta_info'] == 'yes' ): ?>
                                            <div class="htbuilder-post-meta-info">
                                                <?php the_time( esc_html__('M d, Y','htmega-addons') );?>
                                                <span class="htbuilder-meta-separator"> -</span>
                                                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" class="htauthor">
                                                    <?php the_author();?>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <div class="htbuilder-post-content">
                                            <p><?php echo wp_trim_words( get_the_content(), $settings['content_length'], '' ); ?></p>
                                        </div>

                                        <?php if( $settings['show_read_more'] == 'yes' ): ?>
                                            <a class="htbuilder-read-more-btn" href="<?php the_permalink(); ?>">
                                                <?php
                                                    if( !empty( $settings['read_more_btn_text'] ) ){
                                                        echo esc_html__( $settings['read_more_btn_text'], 'htmega-addons' );
                                                    }else{
                                                        echo esc_html__( 'Read more', 'htmega-addons' );
                                                    }
                                                ?>
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                </div>

                            </div>

                        <?php
                    endwhile;
                ?>
            </div>
            <?php
        endif;
        if( $blog_post->max_num_pages > 1 ){ htmega_custom_pagination( $blog_post->max_num_pages ); }
        wp_reset_postdata();

    }

    

}
