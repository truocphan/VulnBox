<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_ErrorContent extends Widget_Base {

    public function get_name() {
        return 'htmega-errorcontent-addons';
    }
    
    public function get_title() {
        return __( '404 Content', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-icon-box';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_keywords() {
        return ['not found page','404page','elementor page not found content','page not found', 'ht mega', 'htmega', '404 content'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/general-widgets/page-not-found-content-widget/';
    }

    public function get_style_depends() {
        return [
            'htmega-widgets',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'error_content_section',
            [
                'label' => __( '404 Content', 'htmega-addons' ),
            ]
        );
          
            $this->add_control(
                'error_layout_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'htmega-addons' ),
                        '2'   => __( 'Style Two', 'htmega-addons' ),
                        '3'   => __( 'Style Three', 'htmega-addons' ),
                        '4'   => __( 'Style Four', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'error_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'error_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'error_title',
                [
                    'label' => __( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Type your title here.', 'htmega-addons' ),
                    'condition'=>[
                        'error_layout_style!' => array('4'),
                    ]
                ]
            );

            $this->add_control(
                'error_sub_title',
                [
                    'label' => __( 'Sub Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Type your Sub title here.', 'htmega-addons' ),
                    'condition'=>[
                        'error_layout_style' => array('2'),
                    ]
                ]
            );

            $this->add_control(
                'error_description',
                [
                    'label' => __( 'Description', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Type your Description here.', 'htmega-addons' ),
                    'condition'=>[
                        'error_layout_style' => array('2','3','4'),
                    ]
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'error_page_button_section',
            [
                'label' => __( 'Button', 'htmega-addons' ),
            ]
        );
            
            $this->start_controls_tabs('error_page_button_tabs');
                
                // Back Button Tab Start
                $this->start_controls_tab(
                    'back_button_tab',
                    [
                        'label' => __( 'Back Button', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'error_back_button_text',
                        [
                            'label' => __( 'Button Text', 'htmega-addons' ),
                            'type' => Controls_Manager::TEXT,
                            'default' => __( 'Back To Home', 'htmega-addons' ),
                            'placeholder' => __( 'Back To Home', 'htmega-addons' ),
                        ]
                    );

                    $this->add_control(
                        'error_back_button_icon',
                        [
                            'label' => __( 'Button Icons', 'htmega-addons' ),
                            'type' => Controls_Manager::ICONS,
                            'default' => [
                                'value' => 'fas fa-star',
                                'library' => 'solid',
                            ],
                        ]
                    );

                    $this->add_control(
                        'back_button_link',
                        [
                            'label' => __( 'Button Link', 'htmega-addons' ),
                            'type' => Controls_Manager::URL,
                            'dynamic' => [
                                'active' => true,
                            ],
                            'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                            'show_external' => true,
                            'default' => [
                                'url' => '#',
                                'is_external' => false,
                                'nofollow' => false,
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Back Button Tab End
                
                // Contact Button Tab Start
                $this->start_controls_tab(
                    'contact_button_tab',
                    [
                        'label' => __( 'Contact Button', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'error_contact_button_text',
                        [
                            'label' => __( 'Button Text', 'htmega-addons' ),
                            'type' => Controls_Manager::TEXT,
                            'placeholder' => __( 'Back To Home', 'htmega-addons' ),
                        ]
                    );

                    $this->add_control(
                        'error_contact_button_icon',
                        [
                            'label' => __( 'Button Icons', 'htmega-addons' ),
                            'type' => Controls_Manager::ICONS,
                            'default' => [
                                'value' => 'fas fa-star',
                                'library' => 'solid',
                            ],
                        ]
                    );

                    $this->add_control(
                        'contact_button_link',
                        [
                            'label' => __( 'Button Link', 'htmega-addons' ),
                            'type' => Controls_Manager::URL,
                            'dynamic' => [
                                'active' => true,
                            ],
                            'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                            'show_external' => true,
                            'default' => [
                                'url' => '#',
                                'is_external' => false,
                                'nofollow' => false,
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Contact Button Tab End

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Style tab section
        $this->start_controls_section(
            'error_page_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'error_page_content_align',
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
                        '{{WRAPPER}} .htmega-not-found' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'error_page_content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'error_page_content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style title tab section
        $this->start_controls_section(
            'error_page_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'error_title!'=>'',
                ]
            ]
        );
            $this->add_control(
                'error_page_title_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content h1' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'error_page_title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-not-found .content h1',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'error_page_title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-not-found .content h1',
                ]
            );

            $this->add_responsive_control(
                'error_page_title_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content h1' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'error_page_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content h1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'error_page_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content h1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Style Sub title tab section
        $this->start_controls_section(
            'error_page_subtitle_style_section',
            [
                'label' => __( 'Sub Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'error_sub_title!'=>'',
                ]
            ]
        );
            $this->add_control(
                'error_page_subtitle_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content h2' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'error_page_subtitle_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-not-found .content h2',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'error_page_subtitle_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-not-found .content h2',
                ]
            );

            $this->add_responsive_control(
                'error_page_subtitle_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content h2' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'error_page_subtitle_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'error_page_subtitle_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Style Description tab section
        $this->start_controls_section(
            'error_page_description_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'error_description!'=>'',
                ]
            ]
        );
            $this->add_control(
                'error_page_description_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'error_page_description_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-not-found .content p',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'error_page_description_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-not-found .content p',
                ]
            );

            $this->add_responsive_control(
                'error_page_description_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content p' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'error_page_description_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'error_page_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-not-found .content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Style Back Button tab section
        $this->start_controls_section(
            'error_page_backbutton_style_section',
            [
                'label' => __( 'Back Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->start_controls_tabs('back_button_style_tabs');
            
                $this->start_controls_tab(
                    'back_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'back_button_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'back_button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'back_button_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'back_button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'back_button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'back_button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'back_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Back Button Normal style End

                $this->start_controls_tab(
                    'back_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'back_button_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'back_button_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn:hover',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'back_button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'back_button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Back Button Hover style End

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Contact Button tab section
        $this->start_controls_section(
            'error_page_contactbutton_style_section',
            [
                'label' => __( 'Contact Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->start_controls_tabs('contact_button_style_tabs');
            
                $this->start_controls_tab(
                    'contact_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'contact_button_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'contact_button_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'contact_button_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact',
                        ]
                    );

                    $this->add_responsive_control(
                        'contact_button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'contact_button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'contact_button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact',
                        ]
                    );

                    $this->add_responsive_control(
                        'contact_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Contact Button Normal style End

                $this->start_controls_tab(
                    'contact_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'contact_button_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'contact_button_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact:hover',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'contact_button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'contact_button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-not-found .content a.page-back-btn.error_contact:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Back Button Hover style End

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'htmega_error_attr', 'class', 'htmega-not-found' );
        $this->add_render_attribute( 'htmega_error_attr', 'class', 'htmega-error-style-'.$settings['error_layout_style'] );

        // Back Button
        if ( ! empty( $settings['back_button_link']['url'] ) ) {

            $this->add_render_attribute( 'backurl', 'class', 'page-back-btn' );
            $this->add_link_attributes( 'backurl', $settings['back_button_link'] );

        }
        $back_button = $settings['error_back_button_text'];

        // Contact Button
        if ( ! empty( $settings['contact_button_link']['url'] ) ) {

            $this->add_render_attribute( 'contacturl', 'class', 'page-back-btn error_contact' );
            $this->add_link_attributes( 'contacturl', $settings['contact_button_link'] );
        }
        $contact_button = $settings['error_contact_button_text'];
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_error_attr' ); ?>>
                <?php if($settings['error_layout_style'] == 2 ): ?>
                    <div class="htb-row align-items-center">
                        <div class="htb-col-lg-6">
                            <div class="thumd text-center">
                                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'error_image_size', 'error_image' ); ?>
                            </div>
                        </div>
                        <div class="htb-col-lg-6">
                            <div class="content">
                                <?php
                                    if( !empty( $settings['error_sub_title'] ) ){
                                        echo '<h2>'.htmega_kses_title( $settings['error_sub_title'] ).'</h2>';
                                    }
                                    if( !empty( $settings['error_title'] ) ){
                                        echo '<h1>'.htmega_kses_title( $settings['error_title'] ).'</h1>';
                                    }
                                    if( !empty( $settings['error_description'] ) ){
                                        echo '<p>'.htmega_kses_desc( $settings['error_description'] ).'</p>';
                                    }
                                    if( !empty( $back_button ) || !empty( $settings['error_back_button_icon']['value'] ) ){
                                        ?>
                                        <a <?php echo $this->get_render_attribute_string( 'backurl' ); ?>>
                                            <?php
                                                Icons_Manager::render_icon( $settings['error_back_button_icon'], [ 'aria-hidden' => 'true' ] );
                                                echo wp_kses_post( $back_button );
                                            ?>
                                        </a>
                                        <?php
                                    }
                                    if( !empty( $contact_button ) || !empty( $settings['error_contact_button_icon']['value'] ) ){
                                        ?>
                                            <a <?php echo $this->get_render_attribute_string( 'contacturl' ); ?> >
                                                <?php
                                                    Icons_Manager::render_icon( $settings['error_contact_button_icon'], [ 'aria-hidden' => 'true' ] );
                                                    echo wp_kses_post( $contact_button );
                                                ?>
                                            </a>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                <?php elseif($settings['error_layout_style'] == 3 ): ?>
                    <div class="content">
                        <?php
                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'error_image_size', 'error_image' );
                            if( !empty( $settings['error_title'] ) ){
                                echo '<h2>'.htmega_kses_title( $settings['error_title'] ).'</h2>';
                            }
                            if( !empty( $settings['error_description'] ) ){
                                echo '<p>'.htmega_kses_desc( $settings['error_description'] ).'</p>';
                            }
                            if( !empty( $back_button ) || !empty( $settings['error_back_button_icon']['value'] ) ){
                                ?>
                                <a <?php echo $this->get_render_attribute_string( 'backurl' ); ?>>
                                    <?php
                                        Icons_Manager::render_icon( $settings['error_back_button_icon'], [ 'aria-hidden' => 'true' ] );
                                        echo wp_kses_post( $back_button );
                                    ?>
                                </a>
                                <?php
                            }
                            if( !empty( $contact_button ) || !empty( $settings['error_contact_button_icon']['value'] ) ){
                                ?>
                                    <a <?php echo $this->get_render_attribute_string( 'contacturl' ); ?> >
                                        <?php
                                            Icons_Manager::render_icon( $settings['error_contact_button_icon'], [ 'aria-hidden' => 'true' ] );
                                            echo wp_kses_post( $contact_button );
                                        ?>
                                    </a>
                                <?php
                            }
                        ?>
                    </div>

                <?php elseif($settings['error_layout_style'] == 4): ?>

                    <div class="htb-row align-items-center">
                        <div class="htb-col-lg-6">
                            <div class="ht-not-found not-found-4">
                                <div class="content">
                                    <?php
                                        if( !empty( $settings['error_description'] ) ){
                                            echo '<h2>'.htmega_kses_title( $settings['error_description'] ).'</h2>';
                                        }
                                    ?>
                                    <div class="not-found-btn-group">
                                        <?php
                                            if( !empty( $back_button ) || !empty( $settings['error_back_button_icon']['value'] ) ){
                                                ?>
                                                <a <?php echo $this->get_render_attribute_string( 'backurl' ); ?>>
                                                    <?php
                                                        Icons_Manager::render_icon( $settings['error_back_button_icon'], [ 'aria-hidden' => 'true' ] );
                                                        echo wp_kses_post( $back_button );
                                                    ?>
                                                </a>
                                                <?php
                                            }
                                            if( !empty( $contact_button ) || !empty( $settings['error_contact_button_icon']['value'] ) ){
                                                ?>
                                                    <a <?php echo $this->get_render_attribute_string( 'contacturl' ); ?> >
                                                        <?php
                                                            Icons_Manager::render_icon( $settings['error_contact_button_icon'], [ 'aria-hidden' => 'true' ] );
                                                            echo wp_kses_post( $contact_button );
                                                        ?>
                                                    </a>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="htb-col-lg-6">
                            <div class="thumd text-center">
                                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'error_image_size', 'error_image' ); ?>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="content">
                        <?php
                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'error_image_size', 'error_image' );
                            if( !empty( $settings['error_title'] ) ){
                                echo '<h1>'.htmega_kses_title( $settings['error_title'] ).'</h1>';
                            }
                            if( !empty( $back_button ) || !empty( $settings['error_back_button_icon']['value'] ) ){
                                ?>
                                <a <?php echo $this->get_render_attribute_string( 'backurl' ); ?>>
                                    <?php
                                        Icons_Manager::render_icon( $settings['error_back_button_icon'], [ 'aria-hidden' => 'true' ] );
                                        echo wp_kses_post($back_button);
                                    ?>
                                </a>
                                <?php
                            }
                            if( !empty( $contact_button ) || !empty( $settings['error_contact_button_icon']['value'] ) ){
                                ?>
                                    <a <?php echo $this->get_render_attribute_string( 'contacturl' ); ?> >
                                        <?php
                                            Icons_Manager::render_icon( $settings['error_contact_button_icon'], [ 'aria-hidden' => 'true' ] );
                                            echo wp_kses_post($contact_button);
                                        ?>
                                    </a>
                                <?php
                            }
                        ?>
                    </div>
                    <div id="clouds">
                        <div class="cloud cloud-1"></div>
                        <div class="cloud cloud-2"></div>
                        <div class="cloud cloud-3"></div>
                        <div class="cloud cloud-4"></div>
                        <div class="cloud cloud-5"></div>
                        <div class="cloud cloud-6"></div>
                    </div>
                <?php endif;?>

            </div>

        <?php

    }

}