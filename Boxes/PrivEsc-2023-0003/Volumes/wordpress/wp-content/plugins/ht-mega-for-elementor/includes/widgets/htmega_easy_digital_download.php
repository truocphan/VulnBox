<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Easy_Digital_Download extends Widget_Base {

    public function get_name() {
        return 'htmega-easydigitaldownload-addons';
    }
    
    public function get_title() {
        return __( 'Easy Digital Downloads', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-file-download';
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
        return [ 'easy downloads', 'download', 'easy digital downloads', 'widget','ht mega','htmega addons' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/3rd-party-plugin-widgets/easy-digital-downloads-widget/';
	}

    protected function register_controls() {

        $this->start_controls_section(
            'easydigitaldownload_content',
            [
                'label' => __( 'Easy Digital Downloads', 'htmega-addons' ),
            ]
        );

            $this->add_responsive_control(
                'columns',
                [
                    'label'   => __( 'Columns', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ],
                    'default' => '4',
                    'selectors' => [
                        '{{WRAPPER}} .edd_downloads_list'   => 'grid-template-columns: repeat({{VALUE}},1fr);',
                    ],
                ]
            );

            $this->add_control(
                'number',
                [
                    'label'   => __( 'Number of Item', 'htmega-addons' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => '4',
                ]
            );

            $this->add_control(
                'easydigitaldownload_thumbnail_show',
                [
                    'label'        => __( 'Show Thumbnail', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );

            $this->add_control(
                'easydigitaldownload_excerpt_show',
                [
                    'label'        => __( 'Show Content', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );

            $this->add_control(
                'easydigitaldownload_price_show',
                [
                    'label'        => __( 'Show Price', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );

            $this->add_control(
                'easydigitaldownload_buy_button',
                [
                    'label'        => __( 'Show Buy Button', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );

            $this->add_control(
                'easydigitaldownload_pagination_show',
                [
                    'label'        => __( 'Show Pagination', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );
            
        $this->end_controls_section();

        // Content Options
        $this->start_controls_section(
            'section_options',
            [
                'label' => __( 'Options', 'htmega-addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'source',
                [
                    'label'   => _x( 'Source', 'Posts Query Control', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        ''          => __( 'Show All', 'htmega-addons' ),
                        'by_id'     => __( 'Manual Selection', 'htmega-addons' ),
                        'by_parent' => __( 'By Parent', 'htmega-addons' ),
                    ],
                ]
            );

            $categories = get_terms( 'download_category' );
            $options = array();
            foreach ( $categories as $category ) {
                $options[ $category->term_id ] = $category->name;
            }

            $this->add_control(
                'categories',
                [
                    'label'       => __( 'Categories', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => $options,
                    'default'     => [],
                    'label_block' => true,
                    'multiple'    => true,
                    'condition'   => [
                        'source' => 'by_id',
                    ],
                ]
            );

            $parent_options = array( '0' => __( 'Only Top Level', 'htmega-addons' ) ) + $options;
            $this->add_control(
                'parent',
                [
                    'label'     => __( 'Parent', 'htmega-addons' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => '0',
                    'options'   => $parent_options,
                    'condition' => [
                        'source' => 'by_parent',
                    ],
                ]
            );

            $this->add_control(
                'hide_empty',
                [
                    'label'        => __( 'Hide Empty', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label'   => __( 'Order by', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'name',
                    'options' => [
                        'name'        => __( 'Name', 'htmega-addons' ),
                        'slug'        => __( 'Slug', 'htmega-addons' ),
                        'description' => __( 'Description', 'htmega-addons' ),
                        'count'       => __( 'Count', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'order',
                [
                    'label'   => __( 'Order', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'desc',
                    'options' => [
                        'asc'  => __( 'ASC', 'htmega-addons' ),
                        'desc' => __( 'DESC', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Single Item section
        $this->start_controls_section(
            'single_item_style_section',
            [
                'label' => __( 'Single Item', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'single_item_gap',
                [
                    'label'   => esc_html__( 'Item Gap', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 15,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    // 'selectors' => [
                    //     '{{WRAPPER}} .edd_downloads_list' => 'margin: -{{SIZE}}px -{{SIZE}}px 0',
                    //     '(desktop){{WRAPPER}} .edd_downloads_list .edd_download' => 'width: calc( 100% / {{columns.SIZE}} ); border: {{SIZE}}px solid transparent',
                    //     '(tablet){{WRAPPER}} .edd_downloads_list .edd_download'  => 'width: calc( 100% / 2 ); border: {{SIZE}}px solid transparent',
                    //     '(mobile){{WRAPPER}} .edd_downloads_list .edd_download'  => 'width: calc( 100% / 1 ); border: {{SIZE}}px solid transparent',
                    //     '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner'        => 'margin: 0;',
                    // ],
                    'selectors' => [
                        '{{WRAPPER}} .edd_downloads_list' => 'margin: -{{SIZE}}px -{{SIZE}}px 0',
                        '{{WRAPPER}} .edd_downloads_list .edd_download' => 'border: {{SIZE}}px solid transparent',
                        '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner'        => 'margin: 0;',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'single_item_background',
                    'label' => __( 'Item Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner',
                ]
            );

            $this->add_responsive_control(
                'single_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'      => 'single_item_border',
                    'label'     => esc_html__( 'Border', 'htmega-addons' ),
                    'selector'  => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'single_item_border_radius',
                [
                    'label'      => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'    => 'single_item_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner',
                ]
            );

            $this->add_control(
                'single_item_alignment',
                [
                    'label'   => esc_html__( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner' => 'text-align: {{VALUE}}',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Item Title section
        $this->start_controls_section(
            'single_item_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('title_style_tabs');

                $this->start_controls_tab(
                    'title_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'title_color',
                        [
                            'label'     => esc_html__( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_download_title a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'     => 'title_typography',
                            'label'    => esc_html__( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_download_title a',
                        ]
                    );

                    $this->add_responsive_control(
                        'title_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_download_title a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Title Hover Start
                $this->start_controls_tab(
                    'title_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'title_hover_color',
                        [
                            'label'     => esc_html__( 'Hover Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_download_title a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Description Style section
        $this->start_controls_section(
            'single_item_description_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'easydigitaldownload_excerpt_show'=>'yes',
                ]
            ]
        );

            $this->add_control(
                'single_item_description_color',
                [
                    'label'     => esc_html__( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_download_excerpt' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'single_item_description_margin',
                [
                    'label'      => esc_html__( 'Margin', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_download_excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'single_item_description_typography',
                    'label'    => esc_html__( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_download_excerpt',
                ]
            );

        $this->end_controls_section();

        // Price Style section
        $this->start_controls_section(
            'single_item_price_style_section',
            [
                'label' => __( 'Price', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'easydigitaldownload_price_show'=>'yes',
                ]
            ]
        );

            $this->add_control(
                'single_item_price_color',
                [
                    'label'     => esc_html__( 'Price Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner span.edd_price, 
                         {{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_price_options span' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'single_item_price_typography',
                    'label'    => esc_html__( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner span.edd_price, 
                     {{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_price_options span',
                ]
            );

            $this->add_responsive_control(
                'single_item_price_margin',
                [
                    'label'      => esc_html__( 'Price Margin', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner span.edd_price, 
                         {{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_price_options span,{{WRAPPER}} .edd_download_purchase_form .edd_price_options li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};display:inline-block;',
                        '{{WRAPPER}} .edd_download_purchase_form .edd_price_options li span' => 'margin: 0!important;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Button section
        $this->start_controls_section(
            'single_item_button_style_section',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'easydigitaldownload_buy_button'=>'yes',
                ]
            ]
        );
            
            $this->start_controls_tabs('single_item_button_style_tabs');

                $this->start_controls_tab(
                    'single_item_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'button_text_color',
                        [
                            'label'     => esc_html__( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button',
                        ]
                    );


                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'button_border',
                            'label'       => esc_html__( 'Border', 'htmega-addons' ),
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button',
                            'separator'   => 'before',
                        ]
                    );

                    $this->add_control(
                        'button_border_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'button_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                $this->end_controls_tab();

                // Button Hover
                $this->start_controls_tab(
                    'single_item_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'button_text_hover_color',
                        [
                            'label'     => esc_html__( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button:hover',
                        ]
                    );


                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'button_hover_border',
                            'label'       => esc_html__( 'Border', 'htmega-addons' ),
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button:hover',
                        ]
                    );

                    $this->add_control(
                        'button_hover_border_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .edd_downloads_list .edd_download .edd_download_inner .edd_purchase_submit_wrapper > .button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
        // Pagination section
        $this->start_controls_section(
            'pagination_style_section',
            [
                'label' => __( 'Pagination', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'easydigitaldownload_pagination_show' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'pagination_alignment',
                [
                    'label'   => esc_html__( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .edd_pagination ' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'pagination_color',
                [
                    'label'     => esc_html__( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .page-numbers ' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'pagination_color_active_color',
                [
                    'label'     => esc_html__( 'Active Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .page-numbers.current,{{WRAPPER}} .page-numbers:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'pagination_typography',
                    'label'    => esc_html__( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .page-numbers',
                ]
            );
            $this->add_responsive_control(
                'pagination__margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .page-numbers' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};display:inline-block',
                    ],
                ]
            );
        $this->end_controls_section();
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $edd_attributes = [
            'number'     => $settings['number'],
            'columns'    => $settings['columns'],
            'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? 1 : 0,
            'orderby'    => $settings['orderby'],
            'order'      => $settings['order'],
            'thumbnails' => ('yes' === $settings['easydigitaldownload_thumbnail_show']) ? 'true' : 'false',
            'excerpt'    => ('yes' === $settings['easydigitaldownload_excerpt_show']) ? 'yes' : 'no',
            'price'      => ('yes' === $settings['easydigitaldownload_price_show']) ? 'yes' : 'no',
            'buy_button' => ('yes' === $settings['easydigitaldownload_buy_button']) ? 'yes' : 'no',
            'pagination' => ('yes' === $settings['easydigitaldownload_pagination_show']) ? 'true' : 'false',
        ];

        if ( 'by_id' === $settings['source'] ) {
            $edd_attributes['category'] = implode( ',', $settings['categories'] );
        } elseif ( 'by_parent' === $settings['source'] ) {
            $edd_attributes['parent'] = $settings['parent'];
        }

        $this->add_render_attribute( 'shortcode', $edd_attributes );

        echo do_shortcode( sprintf( '[edd_downloads %s]', $this->get_render_attribute_string( 'shortcode' ) ) );

    }

}