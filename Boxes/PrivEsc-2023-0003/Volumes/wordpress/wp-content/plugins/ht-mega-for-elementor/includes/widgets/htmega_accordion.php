<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Accordion extends Widget_Base {

    public function get_name() {
        return 'htmega-accordion-addons';
    }
    
    public function get_title() {
        return __( 'Accordion / FAQ', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-accordion';
    }

    public function get_keywords() {
        return ['accordion', 'faq', 'htmega', 'ht mega', 'addons'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/general-widgets/faq-widget/';
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
            'jquery-easing',
            'jquery-mousewheel',
            'vaccordion',
            'htmega-widgets-scripts',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'accordion_content',
            [
                'label' => __( 'Accordion', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'accordiantstyle',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => 'one',
                    'options' => [
                        'one'   => __( 'Style One', 'htmega-addons' ),
                        'two'   => __( 'Style Two', 'htmega-addons' ),
                        'three' => __( 'Style Three', 'htmega-addons' ),
                        'four'  => __( 'Style Four', 'htmega-addons' ),
                        'five'  => __( 'Style Five', 'htmega-addons' ),
                    ],
                ]
            );

            // Accordion One Repeater
            $repeater = new Repeater();

            $repeater->add_control(
                'accordion_title', 
                [
                    'label'       => __( 'Title', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                ]
            );
            $repeater->add_control(
                'icon_type',
                [
                    'label' => __('Title Icon', 'htmega-addons') . ' <i class="eicon-pro-icon"></i>',
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'none' => [
                            'title' => __( 'None', 'htmega-addons' ),
                            'icon'  => 'eicon-ban',
                        ],
                        'icon' => [
                            'title' => __( 'Icon', 'htmega-addons' ),
                            'icon'  => 'eicon-info-circle',
                        ],
                        'image' => [
                            'title' => __( 'Image', 'htmega-addons' ),
                            'icon'  => 'eicon-image-bold',
                        ],
                    ],
                    'default' => 'none',
                    'classes' => 'htmega-disable-control',
                ]
            );
            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'tab_icon_imagesize',
                    'default' => 'thumbnail',
                    'separator' => 'none',
                    'condition' => [
                        'tab_icon_image[url]!' => '',
                        'icon_type' => 'image',
                    ]
                ]
            );
            $repeater->add_control(
                'content_source', 
                [
                    'label'   => __( 'Select Content Source', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'    => __( 'Custom', 'htmega-addons' ),
                        "elementor" => __( 'Elementor Template', 'htmega-addons' ),
                    ],
                ]
            );
            $repeater->add_control(
                'accordion_content', 
                [
                    'label'       => __( 'Accordion Content', 'htmega-addons' ),
                    'type'        => Controls_Manager::WYSIWYG,
                    'condition'   => [
                    'content_source' =>'custom',
                     ],
                ]
            );
            $repeater->add_control(
                'template_id', 
                [
                    
                    'label'       => __( 'Accordion Content', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '0',
                    'options'     => htmega_elementor_template(),
                    'condition'   => [
                        'content_source' => "elementor"
                    ],
                ]
            );

            $this->add_control(
            'htmega_accordion_list',
            [
                'label'     => __( 'Accordion Items', 'htmega-addons' ),
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $repeater->get_controls(),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                        'terms' => [
                                ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                            ]
                        ],
                        [
                        'terms' => [
                                ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                            ]
                        ],
                    ]
                ],
                'default' => [
                    [
                        'accordion_title'   => __( 'Accordion Title One', 'htmega-addons' ),
                        'accordion_content' => __( 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably have not heard of them accusamus labore sustainable VHS.', 'htmega-addons' ),
                        
                    ],
                    [
                        'accordion_title'   => __( 'Accordion Title Two', 'htmega-addons' ),
                        'accordion_content' => __( 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably have not heard of them accusamus labore sustainable VHS.', 'htmega-addons' ),
                    ],
                    [
                        'accordion_title'   => __( 'Accordion Title Two', 'htmega-addons' ),
                        'accordion_content' => __( 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably have not heard of them accusamus labore sustainable VHS.', 'htmega-addons' ),
                    ],
                ],
                'title_field' => '{{{ accordion_title }}}',
            ]
        ); //end style one

            // Accordion Two Repeater

            $repeater2 = new Repeater();

            $repeater2->add_control(
                'accordion_title', 
                [
                    'label'       => __( 'Title', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                ]
            );
            $repeater2->add_control(
                'accordion_image', 
                [
                     'label'      => __( 'Image', 'htmega-addons' ),
                    'type'        => Controls_Manager::MEDIA,
                    'default'     => [
                        'url'     => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_control(
            'htmega_accordion_list_two',
            [
                'label'     => __( 'Accordion Items', 'htmega-addons' ),
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $repeater2->get_controls(),
                'condition' => [
                    'accordiantstyle' =>'two',
                ],
                'default' => [
                    [
                        'accordion_title'   => __( 'Accordion Title', 'htmega-addons' ),
                        
                    ],
                ],
                'title_field' => '{{{ accordion_title }}}',
            ]
        ); //end style two

            // Accordion Three Repeater



            $repeater3 = new Repeater();

            $repeater3->add_control(
                'accordion_title', 
                [
                    'label'       => __( 'Title', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                ]
            );
            $repeater3->add_control(
                'accordion_image', 
                [
                     'label'      => __( 'Image', 'htmega-addons' ),
                    'type'        => Controls_Manager::MEDIA,
                    'default'     => [
                        'url'     => Utils::get_placeholder_image_src(),
                    ],
                ]
            );
            $repeater3->add_control(
                'content_source', 
                [
                    'label'   => __( 'Select Content Source', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'    => __( 'Custom', 'htmega-addons' ),
                        "elementor" => __( 'Elementor Template', 'htmega-addons' ),
                    ],
                ]
            );
            $repeater3->add_control(
                'accordion_content', 
                [
                    
                    'label'      => __( 'Accordion Content', 'htmega-addons' ),
                    'type'       => Controls_Manager::WYSIWYG,
                    'default'    => __( 'Accordion Content', 'htmega-addons' ),
                    'condition' => [
                        'content_source' =>'custom',
                    ],
                ]
            );
            $repeater3->add_control(
                'template_id', 
                [
                    'label'       => __( 'Accordion Content', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '0',
                    'options'     => htmega_elementor_template(),
                    'condition'   => [
                        'content_source' => "elementor"
                    ],
                ]
            );

            $this->add_control(
            'htmega_accordion_list_three',
            [
                'label'     => __( 'Accordion Items', 'htmega-addons' ),
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $repeater3->get_controls(),
                'condition' => [
                    'accordiantstyle' =>array( 'three','four' ),
                ],
                'default' => [
                        [
                        'accordion_title'   => __( 'Accordion Title One', 'htmega-addons' ),
                        'accordion_content' => __('Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably have not heard of them accusamus labore sustainable VHS.','htmega-addons'),
                        ],
                        
                    ],
                
                'title_field' => '{{{ accordion_title }}}',
            ]
        ); //end style three

            $this->add_control(
                'accourdion_title_html_tag',
                [
                    'label'   => __( 'Title HTML Tag', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'h2',
                    'condition' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                                ]
                            ],
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                                ]
                            ],
                        ]
                    ],
                ]
            );

            $this->add_control(
                'accordion_open_icon',
                [
                    'label'       => __( 'Item Collapse Icon', 'htmega-addons' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-plus',
                        'library' => 'solid',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                                ]
                            ],
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                                ]
                            ],
                        ]
                    ],
                ]
            );

            $this->add_control(
                'accordion_close_icon',
                [
                    'label'       => __( 'Open Item Icon', 'htmega-addons' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-minus',
                        'library' => 'solid',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                                ]
                            ],
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                                ]
                            ],
                        ]
                    ],
                ]
            );
            $this->add_control(
                'show_short_description',
                [
                    'label'   => __( 'Show Short Description ', 'htmega-addons' ) . ' <i class="eicon-pro-icon"></i>',
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'return_value' => 'yes',
                    'condition' => [
                        'accordiantstyle' => ['five','one'],
                    ],
                    'classes' => 'htmega-disable-control',
                ]
            );

            $this->add_control(
                'accordion_close_all',
                [
                    'label'   => __( 'Close All Item', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'return_value' => 'yes',
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                                ]
                            ],
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                                ]
                            ],
                        ]
                    ],
                ]
            );

            $this->add_control(
                'accordion_multiple',
                [
                    'label' => __( 'Multiple Item Open', 'htmega-addons' ),
                    'type'  => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'condition' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                                ]
                            ],
                            [
                            'terms' => [
                                    ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                                ]
                            ],
                        ]
                    ],
                ]
            );

            $this->add_control(
                'current_item',
                [
                    'label' => __( 'Current Item No', 'htmega-addons' ),
                    'type'  => Controls_Manager::NUMBER,
                    'min'   => 1,
                    'max'   => 50,
                    'condition' => [
                        'accordion_close_all!' =>'yes',
                    ],
                ]
            );
            $this->add_control(
                'show_title_icon',
                [
                    'label'   => __( 'Show Title Icon ', 'htmega-addons' ) . ' <i class="eicon-pro-icon"></i>',
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'return_value' => 'yes',
                    'condition' => [
                        'accordiantstyle' => ['five','one'],
                    ],
                    'classes' => 'htmega-disable-control',
                ]
            );
        $this->end_controls_section();

        // Additional Options
        $this->start_controls_section(
            'accordion_additional_option',
            [
                'label' => __( 'Additional Options', 'htmega-addons' ),
                'condition' => [
                    'accordiantstyle' =>'four',
                ],
            ]
        );
           
            $this->add_control(
                'accordion_visible_items',
                [
                    'label' => __( 'Visible Item', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'default' => [
                        'size' => 3,
                    ],
                ]
            );

             $this->add_control(
                'accordion_display_height',
                [
                    'label' => __( 'Accordion Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'default' => [
                        'size' => 450,
                    ],
                ]
            );

            $this->add_control(
                'accordion_expand_items_height',
                [
                    'label' => __( 'Expand Item Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'default' => [
                        'size' => 450,
                    ],
                ]
            );

        $this->end_controls_section(); // Additional Options End


        // Style tab section
        $this->start_controls_section(
            'htmega_button_style_section',
            [
                'label' => __( 'Accordion Item', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                        'terms' => [
                                ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                            ]
                        ],
                        [
                        'terms' => [
                                ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                            ]
                        ],
                    ]
                ],
            ]
        );
            $this->add_control(
                'accordion_item_spacing',
                [
                    'label' => __( 'Accordion Item Spacing', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 15,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single_accourdion' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Title style tab start
        $this->start_controls_section(
            'htmega_accordion_title_style',
            [
                'label'     => __( 'Accordion Title', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                        'terms' => [
                                ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                            ]
                        ],
                        [
                        'terms' => [
                                ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                            ]
                        ],
                    ]
                ],
            ]
        );
            $this->add_responsive_control(
                'titlealign',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-accourdion-title'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'accordion_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-items-hedding' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'title_color_border_heading',
                [
                    'label' => __( 'Colors and Border', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->start_controls_tabs('htmega_accordion_title_style_tabs');
                // Accordion Title Normal tab Start
                $this->start_controls_tab(
                    'accordion_title_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'accordion_title_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htb-collapsed.htmega-items-hedding' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htb-collapsed.htmega-items-hedding',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_title_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htb-collapsed.htmega-items-hedding',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htb-collapsed.htmega-items-hedding' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'title_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htb-collapsed.htmega-items-hedding',
                        ]
                    );

                $this->end_controls_tab(); // Accordion Title Normal tab End

                // Accordion Title Active tab Start
                $this->start_controls_tab(
                    'accordion_title_style_active_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                
                    $this->add_control(
                        'accordion_title_active_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-items-hedding' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'activebackground',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-items-hedding',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_title_active_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-items-hedding',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_active_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-items-hedding' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'title_active_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-items-hedding',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // Accordion Title Active tab End

            $this->end_controls_tabs();
           
        $this->end_controls_section(); // Title style tab end

        // Title Three style collapesd tab start
        $this->start_controls_section(
            'htmega_accordion_title_three_collapsed_style',
            [
                'label'     => __( 'Accordion Title Collapsed', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' => array( 'three'),
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'titlethree_collapsed_align_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} ul.accordion--4 li .heading',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'title_three_collapsed_align',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion--4 .heading'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'accordion_title_three_collapsed_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .accordion--4 .heading'   => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'title_three_collapsed_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .accordion--4 .heading',
                ]
            );

            $this->add_responsive_control(
                'title_three_collapsed_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion--4 .heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'title_three_collapsed_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion--4 .heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        // Title Three style collapesd tab end
        $this->end_controls_section();

        // Item Style two tab start
        $this->start_controls_section(
            'htmega_accordion_item_two_style',
            [
                'label'     => __( 'Accordion Item Box', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' => array( 'two' ),
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'itemboxbackground',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .gallery-wrap .item',
                ]
            );

            $this->add_responsive_control(
                'accordion_content_margin_gallery_wrap',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .gallery-wrap .item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'accordion_itembox_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .gallery-wrap .item',
                ]
            );

            $this->add_responsive_control(
                'accordion_itembox_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .gallery-wrap .item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'titlethree_box_shadow_2',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} h2.heading-three',
                    'selector' => '{{WRAPPER}} .accordion--5 .single_accordion .va-title',
                    'separator' => 'before',
                ]
            );


        $this->end_controls_section(); // Item Style two tab end

        // Title Three style tab start
        $this->start_controls_section(
            'htmega_accordion_title_three_style',
            [
                'label'     => __( 'Accordion Title', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' => array( 'three','four' ),
                ],
            ]
        );

            $this->add_responsive_control(
                'titlethreealign',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} h2.heading-three'   => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'tithethreebackground',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} h2.heading-three',
                    'selector' => '{{WRAPPER}} .accordion--5 .single_accordion .va-title',
                ]
            );

            $this->add_responsive_control(
                'accordion_titlethree_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} h2.heading-three' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'accordion_titlethree_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} h2.heading-three',
                    'selector' => '{{WRAPPER}} .accordion--5 .single_accordion .va-title',
                ]
            );

            $this->add_responsive_control(
                'accordion_titlethree_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} h2.heading-three' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'titlethree_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} h2.heading-three',
                    'selector' => '{{WRAPPER}} .accordion--5 .single_accordion .va-title',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'accordion_titlethree_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} h2.heading-three' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'titlethree_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} h2.heading-three, {{WRAPPER}} .accordion--5 .single_accordion .va-title',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'titlethree_margin',
                [
                    'label' => __( 'Active Title Space', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} ul.accordion--4 li .description h2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'accordiantstyle' =>'three',
                    ],
                ]
            );

            $this->add_responsive_control(
                'titlefour_lineheight',
                [
                    'label' => __( 'Active Title Line Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-title.htmegava-active' => 'line-height: {{SIZE}}{{UNIT}} !important;',
                    ],
                    'condition' => [
                        'accordiantstyle' =>'four',
                    ],
                ]
            );

        $this->end_controls_section(); // Title three tab end


        // Icon style tab start
        $this->start_controls_section(
            'htmega_accordion_icon_style',
            [
                'label'     => __( 'Accordion Icon', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                        'terms' => [
                                ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'one']
                            ]
                        ],
                        [
                        'terms' => [
                                ['name' => 'accordiantstyle', 'operator' => '===', 'value' => 'five'],
                            ]
                        ],
                    ]
                ],
            ]
        );
            $this->add_responsive_control(
                'accordion_icon_size',
                [
                    'label' => __( 'Icon Size', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'accordion_icon_align',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Start', 'htmega-addons' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'End', 'htmega-addons' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                    ],
                    'default'     => is_rtl() ? 'left' : 'right',
                    'toggle'      => false,
                    'label_block' => false,
                ]
            );
            $this->add_responsive_control(
                'accordion_icon_width',
                [
                    'label' => __( 'Icon Box Width', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'accordion_icon_height',
                [
                    'label' => __( 'Icon Box Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'accordion_icon_box_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'accordion_icon_color_border_heading',
                [
                    'label' => __( 'Colors and Border', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );            
            // Accordion Icon tabs Start
            $this->start_controls_tabs('htmega_accordion_icon_style_tabs');

                // Accordion Icon normal tab Start
                $this->start_controls_tab(
                    'accordion_icon_style_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'accordion_icon_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon svg' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'iconbackground',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_icon_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_icon_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'icon_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding.htb-collapsed .accourdion-icon',
                        ]
                    );

                $this->end_controls_tab(); // Accordion Icon normal tab End

                // Accordion Icon Active tab Start
                $this->start_controls_tab(
                    'accordion_active_icon_style_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'accordion_active_icon_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon svg' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'iconactivebackground',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_active_icon_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_active_icon_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'icon_active_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-accourdion-title .htmega-items-hedding .accourdion-icon',
                        ]
                    );

                $this->end_controls_tab(); // Accordion Icon Active tab End

            $this->end_controls_tabs();

        $this->end_controls_section(); // Icon style tabs end


        // Content style tab start
        $this->start_controls_section(
            'htmega_accordion_content_style',
            [
                'label'     => __( 'Accordion Content', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordiantstyle' => array( 'one','three','four', 'five'),
                ],
            ]
        );

            $this->add_responsive_control(
                'content_align',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content p'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .accordion-content',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'accordion_content_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'accordion_content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .accordion--5 .single_accordion .va-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'accordion_content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'contentbackground',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .accordion-content',
                ]
            );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'accordion_content_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .accordion-content',
                ]
            );

            $this->add_responsive_control(
                'accordion_content_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .accordion-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'content_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .accordion-content',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'contentthreealign',
                [
                    'label'   => __( 'Alignment', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .accordion--4 li .description .accordion-content'   => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .accordion--5 .single_accordion .accordion-content'   => 'text-align: {{VALUE}};',
                    ],
                    'condition' => [
                        'accordiantstyle' => array('three'),
                    ],
                ]
            );

        $this->end_controls_section(); // Content style tabs end

    }

    protected function render( $instance = [] ) {

        $settings           = $this->get_settings_for_display();
        $accordion_list     = $settings['htmega_accordion_list'];
        $accordion_list_two = $settings['htmega_accordion_list_two'];
        $accordion_list_three = $settings['htmega_accordion_list_three'];
        $accordion_id       = $this->get_id();
        $this->add_render_attribute( 'accordion_heading', 'data-toggle', 'htbcollapse' );

        $title_tag = htmega_validate_html_tag( $settings['accourdion_title_html_tag'] );

        // Accordiant Style Two
        if( $settings['accordiantstyle'] == 'two' ){
            if( $accordion_list_two ){
                echo '<div class="gallery-wrap">';
                    foreach ( $accordion_list_two as $itemtwo ) {
                        ?>
                            <div class="item" <?php if( !empty($itemtwo['accordion_image']['url']) ){ echo 'style="background-image:url('.$itemtwo['accordion_image']['url'].')"'; } ?>></div>
                        <?php
                    }
                echo '</div>';
            }

        } elseif( $settings['accordiantstyle'] == 'three' ){
            if( $accordion_list_three ){
                echo '<ul class="accordion--4" id="accordion-4">';
                    foreach ( $accordion_list_three as $itemthree ) {
                        ?>
                            <li <?php if( !empty($itemthree['accordion_image']['url']) ){ echo 'style="background-image:url('.$itemthree['accordion_image']['url'].')"'; } ?>>
                                <div class="heading"><?php echo esc_attr__( $itemthree['accordion_title'] ); ?></div>
                                <div class="bgDescription" style="background: transparent url(<?php echo HTMEGA_ADDONS_PL_URL.'/assets/images/bg/bgDescription.png';?>) repeat-x top left;"></div>
                                <div class="description">
                                    <h2 class="heading-three"><?php echo esc_html__( $itemthree['accordion_title'], 'htmega-addons' ); ?></h2>
                                    <div class="accordion-content">
                                       <?php 
                                            if ( $itemthree['content_source'] == 'custom' && !empty( $itemthree['accordion_content'] ) ) {
                                                echo wp_kses_post( $itemthree['accordion_content'] );
                                            } elseif ( $itemthree['content_source'] == "elementor" && !empty( $itemthree['template_id'] )) {
                                                echo Plugin::instance()->frontend->get_builder_content_for_display( $itemthree['template_id'] );
                                            }
                                        ?>
                                    </div>
                                </div>
                            </li>
                        <?php
                    }
                echo '</ul>';
            }
        }elseif( $settings['accordiantstyle'] == 'four' ){

            $accordian_options = [];
            $accordian_options['visibleitem'] = ( $settings['accordion_visible_items']['size'] ) ? $settings['accordion_visible_items']['size'] : 3;
            $accordian_options['expandedheight'] = ( $settings['accordion_expand_items_height']['size'] ) ? $settings['accordion_expand_items_height']['size'] : 450;
            $accordian_options['accordionheight'] = ( $settings['accordion_display_height']['size'] ) ? $settings['accordion_display_height']['size'] : 450;
            
            if( $accordion_list_three ){
                echo '<div id="va-accordion" class="accordion--5" data-accordionoptions=\'' . wp_json_encode( $accordian_options ) . '\' ><div class="accor_wrapper" >';
                    foreach ( $accordion_list_three as $itemthree ) {
                        ?>
                            <div class="single_accordion" <?php if( !empty($itemthree['accordion_image']['url']) ){ echo 'style="background-image:url('. esc_url( $itemthree['accordion_image']['url'] ).')"'; } ?>>
                                <h3 class="va-title"><?php echo htmega_kses_title( $itemthree['accordion_title'] ); ?></h3>
                                <div class="va-content">
                                    <div class="accordion-content">
                                       <?php 
                                            if ( $itemthree['content_source'] == 'custom' && !empty( $itemthree['accordion_content'] ) ) {
                                                echo wp_kses_post( $itemthree['accordion_content'] );
                                            } elseif ( $itemthree['content_source'] == "elementor" && !empty( $itemthree['template_id'] )) {
                                                echo Plugin::instance()->frontend->get_builder_content_for_display( $itemthree['template_id'] );
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        <?php
                    }
                echo '</div></div>';
            }

        }else{
            $buttonicon = '<span class="accourdion-icon close-accourdion">'.HTMega_Icon_manager::render_icon( $settings['accordion_close_icon'], [ 'aria-hidden' => 'true' ] ).'</span><span class="accourdion-icon open-accourdion">'.HTMega_Icon_manager::render_icon( $settings['accordion_open_icon'], [ 'aria-hidden' => 'true' ] ).'</span>';

            $count_items = count($accordion_list);
            if ( $accordion_list ) {
                echo '<div class="accordion" id="accordionExample'. esc_attr( $accordion_id ).'">';
                    if( !empty( $settings['current_item'] ) && $count_items >= $settings['current_item'] ){
                        $current_item = $settings['current_item'];
                    }else{
                        $current_item = 1;
                    }
                    $i = 0;
                    $j = 0;
                    foreach ( $accordion_list as $item ) {
                        $i++;
                        $j = $i.$accordion_id;
                        ?>
                            <div class="single_accourdion htmega-icon-align-<?php echo esc_attr( $settings['accordion_icon_align'] ); ?>">

                                <div class="htmega-accourdion-title">
                                    <?php
                                        if( ( $current_item == $i ) && ( $settings['accordion_close_all'] != 'yes' ) ){
                                            printf('<%1$s %2$s data-target="#htmega-collapse%3$s" class="htmega-items-hedding">%4$s %5$s</%1$s>', 
                                                esc_attr( $title_tag ), 
                                                $this->get_render_attribute_string( 'accordion_heading' ), 
                                                esc_attr( $j ), 
                                                htmega_kses_title( $item['accordion_title'] ), 
                                                $buttonicon
                                            );
                                        }else{
                                            printf('<%1$s %2$s data-target="#htmega-collapse%3$s" class="htb-collapsed htmega-items-hedding">%4$s %5$s</%1$s>', 
                                                esc_attr( $title_tag ), 
                                                $this->get_render_attribute_string( 'accordion_heading' ), 
                                                esc_attr( $j ), 
                                                htmega_kses_title( $item['accordion_title'] ), 
                                                $buttonicon 
                                            );
                                        }
                                    ?>
                                </div>

                                <div id="htmega-collapse<?php echo esc_attr( $j );?>" class="htb-collapse <?php if( ( $current_item == $i ) && ( $settings['accordion_close_all'] != 'yes' ) ){ echo 'htb-show'; }?>" <?php if( $settings['accordion_multiple'] != 'yes' ){ echo 'data-parent="#accordionExample'.$accordion_id.'"'; } ?> >
                                    <div class="accordion-content">
                                        <?php 
                                            if ( $item['content_source'] == 'custom' && !empty( $item['accordion_content'] ) ) {
                                                echo wp_kses_post( $item['accordion_content'] );
                                            } elseif ( $item['content_source'] == "elementor" && !empty( $item['template_id'] )) {
                                                echo Plugin::instance()->frontend->get_builder_content_for_display( $item['template_id'] );
                                            }
                                        ?>
                                    </div>
                                </div>

                            </div>

                        <?php
                    }
                echo '</div>';
            }
        }
    }
}

