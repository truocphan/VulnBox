<?php
namespace Elementor;

// Elementor Classes

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Testimonial extends Widget_Base {

    public function get_name() {
        return 'htmega-testimonial-addons';
    }
    
    public function get_title() {
        return __( 'Testimonial', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-testimonial';
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
            'htmega_testimonial_content_section',
            [
                'label' => __( 'Testimonial', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'htmega_testimonial_style',
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
                        '6'   => __( 'Style Six', 'htmega-addons' ),
                        '7'   => __( 'Style Seven', 'htmega-addons' ),
                        '8'   => __( 'Style Eight', 'htmega-addons' ),
                        '9'   => __( 'Style Nine', 'htmega-addons' ),
                        '10'   => __( 'Style Ten', 'htmega-addons' ),
                        '11'   => __( 'Style Eleven', 'htmega-addons' ),
                        '12'   => __( 'Style Twelve', 'htmega-addons' ),
                        '13'   => __( 'Style Thirteen', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'slider_on',
                [
                    'label' => esc_html__( 'Slider', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'separator'=>'before',
                ]
            );
            $this->add_responsive_control(
                'column_gap',
                [
                    'label' => esc_html__( 'Column Gap', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'description' => esc_html__( 'Add Column gap Ex. 15px', 'htmega-addons' ),
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area' => 'margin: 0 -{{SIZE}}px',
                        '{{WRAPPER}} .htmega-testimonial-area .slick-slide' => 'padding-left:{{SIZE}}px;padding-right: {{SIZE}}px',
                        '{{WRAPPER}} .htmega-testimonial-area [class*="htb-col-"]' => 'padding-left:{{SIZE}}px;padding-right: {{SIZE}}px',
                        
                    ],
                ]
            );
            $repeater = new Repeater();

            $repeater->add_control(
                'client_name',
                [
                    'label'   => __( 'Name', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Carolina Monntoya','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'client_designation',
                [
                    'label'   => __( 'Designation', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Managing Director','htmega-addons'),
                ]
            );
            
            $repeater->add_control(
                'client_image',
                [
                    'label' => __( 'Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'client_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $repeater->add_control(
                'client_say',
                [
                    'label'   => __( 'Client Say', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __('Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','htmega-addons'),
                ]
            );
            $repeater->add_control(
                'client_rating',
                [
                    'label' => __( 'Client Rating', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                ]
            );

            $this->add_control(
                'htmega_testimonial_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [

                        [
                            'client_name'           => __('Carolina Monntoya','htmega-addons'),
                            'client_designation'    => __( 'Managing Director','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],

                        [
                            'client_name'           => __('Peter Rose','htmega-addons'),
                            'client_designation'    => __( 'Manager','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],

                        [
                            'client_name'           => __('Gerald Gilbert','htmega-addons'),
                            'client_designation'    => __( 'Developer','htmega-addons' ),
                            'client_say'            => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'htmega-addons' ),
                        ],
                    ],
                    'title_field' => '{{{ client_name }}}',
                ]
            );

            $this->add_control(
                'client_image_divider',
                [
                    'label' => __( 'Divider image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'separator' => 'before',
                    'condition'=>[
                        'htmega_testimonial_style!' => array('13','4','3','6','8','9','2','10','11'),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'client_image_divider_size',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition'=>[
                        'htmega_testimonial_style!' => array('13','4','3','6','8','9','2','10','11'),
                    ],
                ]
            );
        

        $this->end_controls_section();

        // Slider setting
        $this->start_controls_section(
            'testimonial-slider-option',
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
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
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
                'slarrows_style',
                [
                    'label' => __( 'Arrow Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'htmega-addons' ),
                        '2'   => __( 'Style Two', 'htmega-addons' ),
                        '3'   => __( 'Style Three', 'htmega-addons' ),
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows' => 'yes',
                        'htmega_testimonial_style' => array( '4','12' ),
                    ]
                ]
            );
            $this->add_control(
                'slprevicon',
                [
                    'label' => __( 'Previous icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-angle-left',
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
                        'value'=>'fas fa-angle-right',
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
                'slcenterpadding2',
                [
                    'label' => esc_html__( 'Center padding', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                    'default' =>0,
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

        // Style Testimonial area tab section
        $this->start_controls_section(
            'htmega_testimonial_style_area',
            [
                'label' => __( 'Section Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();
        // Style tab section
        $this->start_controls_section(
            'testimonial_style_section',
            [
                'label' => __( 'Item Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'style_tabs'
        );
            // Normal Style Tab
            $this->start_controls_tab(
                'box_tab_normal_style',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );

            $this->add_responsive_control(
                'box_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .testimonal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'box_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .testimonal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'box_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .testimonal',
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'box_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .testimonal',
                ]
            );

            $this->add_responsive_control(
                'box_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .testimonal,.testimonal:before' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .testimonal',
                ]
            );
            $this->add_responsive_control(
                'image_nave_max_width',
                [
                    'label' => __( 'Image slider Max Width(%)', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonal-nav.slick-slider' => 'max-width: {{SIZE}}%;',
                    ],
                    'condition' =>[
                        'htmega_testimonial_style' => array( '5','11' ),
                    ]
                ]
            );            
            $this->end_controls_tab();

            // Hover Style Tab
            $this->start_controls_tab(
                'box_tabs_hover_style',
                [
                    'label' => __( 'Hover', 'htmega-addons' ),
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'box_background_hover',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .testimonal:before',
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'box_border_hover',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .testimonal:hover',
                ]
            );
            $this->add_control(
                'box_hover_content_color',
                [
                    'label' => __( 'All Content Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal:hover .content p,
                        {{WRAPPER}} .htmega-testimonial-area .testimonal:hover .content h4,
                        {{WRAPPER}} .htmega-testimonial-area .testimonal:hover .content span,
                        {{WRAPPER}} .htmega-testimonial-area .testimonal:hover p,
                        {{WRAPPER}} .htmega-testimonial-area .testimonal:hover .htmega-testimonial-rating,
                        {{WRAPPER}} .htmega-testimonial-area .testimonal:hover .clint-info span' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-style-2 .testimonal:hover .content .clint-info::before' => 'background: {{VALUE}};',
                    ],

                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'box_shadow_hover',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .testimonal:hover',
                ]
            );

            $this->end_controls_tab();
        $this->end_controls_tabs();            
        $this->end_controls_section();
        // Style Testimonial image style start
        $this->start_controls_section(
            'htmega_testimonial_image_style',
            [
                'label'     => __( 'Image', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_testimonial_image_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .testimonal-image img',
                ]
            );
            $this->add_control(
                'htmega_testimonial_border_color',
                [
                    'label' => __( 'Border Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-style-4 .testimonal .testimonal-image::after' => 'border-left-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-style-4 .testimonal .testimonal-image::before,{{WRAPPER}} .htmega-testimonial-style-4 .testimonal .testimonal-image::after' => 'background: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_testimonial_style' => '4',
                    ]
                ]
            );
            $this->add_control(
                'image_arrow_shape',
                [
                    'label' => esc_html__( 'Arrow Shape', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' =>[
                        'htmega_testimonial_style' => '4',
                    ]
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .testimonal-image img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'image_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .slick-slide.slick-active.slick-center .testimonal-img img',
                    'condition' =>[
                        'htmega_testimonial_style' => array( '5','11' ),
                    ]
                ]
            );
            $this->add_responsive_control(
                'htmega_testimonial_image_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .testimonal-image' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonal-nav.slick-slider' => 'margin-top: {{TOP}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-style-5 .testimonal-image' => 'margin-bottom:{{BOTTOM}}{{UNIT}};margin-left: {{LEFT}}{{UNIT}};margin-right: {{RIGHT}}{{UNIT}};margin-top:0px',

                        '{{WRAPPER}} .htmega-testimonial-style-5 .htmega-testimonal-nav .slick-track,.htmega-testimonal-nav.slick-slider' => 'margin-top: 0px',
                    ],
                    'separator' =>'before',
                ]
            );
            $this->add_responsive_control(
                'htmega_testimonial_image_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .testimonal-image' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );
            $this->add_responsive_control(
                'image_box_width',
                [
                    'label' => __( 'Image Box Width', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-style-12 .testimonal-image' => 'flex: 0 0 {{SIZE}}%;',
                    ],
                    'condition' =>[
                        'htmega_testimonial_style' => '12',
                    ]
                ]
            );
        $this->end_controls_section(); // Style Testimonial image style end

        // Style Testimonial name style start
        $this->start_controls_section(
            'htmega_testimonial_name_style',
            [
                'label'     => __( 'Name', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_name_align',
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
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4' => 'text-align: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'htmega_testimonial_name_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#3e3e3e',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_name_typography',
                    'selector' => '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4, {{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_name_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} {{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_name_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} {{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial name style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'htmega_testimonial_designation_style',
            [
                'label'     => __( 'Designation', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_designation_align',
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
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info' => 'text-align: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'htmega_testimonial_designation_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#3e3e3e',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_designation_typography',
                    'selector' => '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span, {{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info span',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_designation_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_designation_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .clint-info span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );
            $this->add_control(
                'designation_border',
                [
                    'label' => esc_html__( 'Border On Left ', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' =>[
                        'htmega_testimonial_style' => '4',
                    ]
                ]
            );

            $this->add_responsive_control(
                'designation_border_width',
                [
                    'label' => __( 'Width', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span:before' => 'width: {{SIZE}}px;',
                         
                    ],
                    'condition' =>[
                        'designation_border' => 'yes',
                    ]
                ]
            );
    
            $this->add_responsive_control(
                'designation_border_height',
                [
                    'label' => __( 'Height', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
    
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span:before' => 'height: {{SIZE}}px;',
                    ],
                    'condition' =>[
                        'designation_border' => 'yes',
                    ]
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'designation_shape_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-testimonial-area .testimonal .content span:before',
                    'condition' =>[
                        'designation_border' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end


        // Style Testimonial designation style start
        $this->start_controls_section(
            'htmega_testimonial_clientsay_style',
            [
                'label'     => __( 'Client say', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_testimonial_clientsay_align',
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
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p' => 'text-align: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'htmega_testimonial_clientsay_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#3e3e3e',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_testimonial_clientsay_typography',
                    'selector' => '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p, {{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p',
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'clientsay_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .testimonial-desc,{{WRAPPER}} .htmega-testimonial-style-6 .testimonal .content',
                    'condition'=>[
                        'htmega_testimonial_style' => array( '5','11','6'),
                    ],
                ]
            );
            $this->add_control(
                'htmega_testimonialclient_say_arrrow_color',
                [
                    'label' => __( 'Arrow Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-style-6 .testimonal .content .triangle' => 'border-top-color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'htmega_testimonial_style' => array('6'),
                    ],
                ]
            );
            $this->add_control(
                'htmega_testimonialclient_say_quote_color',
                [
                    'label' => __( 'Quote Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-style-7 .testimonal p::before' => 'color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'htmega_testimonial_style' =>'7',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'content_boxshadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-testimonial-style-6 .testimonal .content',
                    'condition'=>[
                        'htmega_testimonial_style' => array('6'),
                    ],
                ]
            );
            $this->add_responsive_control(
                'htmega_testimonial_clientsay_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_testimonial_clientsay_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .testimonal .content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-testimonial-for .testimonial-desc p' => 'padding: 0px',
                    ],
                    'separator' =>'before',
                ]
            );
            $this->add_responsive_control(
                'htmega_testimonial_clientsa_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-area .htmega-testimonial-for .testimonial-desc' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition'=>[
                        'htmega_testimonial_style' => array( '5','11','6'),
                    ],
                ]
            );
            $this->add_control(
                'client_say_quote_style',
                [
                    'label' => __( 'Quote/ Divider Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'=>[
                        'htmega_testimonial_style' => array('5'),
                    ],
                ]
            );
            $this->add_responsive_control(
                'client_say_quote_position',
                [
                    'label' => __( 'Position', 'htmega-addons' ),
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
                        '{{WRAPPER}} .htmega-testimonial-style-5 .testimonial-shape' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'htmega_testimonial_style' => array('5'),
                    ],
                ]
            );
    
        $this->end_controls_section(); // Style Testimonial designation style end

        // Style Border Shap style start
        $this->start_controls_section(
            'htmega_testimonial_borderstyle',
            [
                'label'     => __( 'Border Shape Style', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'htmega_testimonial_style' => array('3','10'),
                ],
            ]
        );
        $this->add_control(
            'shape_position',
            [
                'label' => esc_html__( 'Shape On Top', 'htmega-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'condition' =>[
                    'htmega_testimonial_style' => array('3','10'),
                ]
            ]
        );
        $this->add_responsive_control(
            'line_border_width',
            [
                'label' => __( 'Width', 'htmega-addons' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-testimonial-style-3 .testimonal .content .clint-info::before' => 'width: {{SIZE}}px;',
                    '{{WRAPPER}} .htmega-testimonial-style-3 .testimonal .content .clint-info' => 'padding-left: {{SIZE}}px;',
                    '{{WRAPPER}} .htmega-testimonial-style-3 .testimonal .content .clint-info *' => 'padding-left: 15px;',
                     
                ],
                'condition'=>[
                    'htmega_testimonial_style' => array('3','10'),
                ],
            ]
        );

        $this->add_responsive_control(
            'line_border_height',
            [
                'label' => __( 'Height', 'htmega-addons' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .htmega-testimonial-style-3 .testimonal .content .clint-info::before' => 'height: {{SIZE}}px;',
                ],
                'condition'=>[
                    'htmega_testimonial_style' => array('3','10'),
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'testimonial_shape_background',
                'label' => __( 'Background', 'htmega-addons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .htmega-testimonial-style-3 .testimonal .content .clint-info::before',
                'condition'=>[
                    'htmega_testimonial_style' => array('3','10'),
                ],
            ]
        );
        $this->end_controls_section(); // Border Shap style end
        // Style Testimonial rating style start
        $this->start_controls_section(
            'htmega_testimonial_clientrating_style',
            [
                'label'     => __( 'Rating', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'rating_position',
            [
                'label' => esc_html__( 'Rating On Right', 'htmega-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
                'condition' =>[
                    'testimonial_style' => array( '5','11'),
                ]
            ]
        );
            $this->add_control(
                'htmega_testimonial_clientrating_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-rating' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'htmega_testimonial_clientrating_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-testimonial-rating li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );
        $this->end_controls_section(); // Style Testimonial rating style end    
        // Style Testimonial arrow style start
        $this->start_controls_section(
            'htmega_testimonial_arrow_style',
            [
                'label'     => __( 'Arrow', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'testimonial_arrow_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'testimonial_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_testimonial_arrow_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#7d7d7d',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_arrow_fontsize',
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
                                'size' => 20,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'testimonial_arrow_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-arrow',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_testimonial_arrow_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_arrow_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_arrow_height',
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
                                'size' => 36,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_arrow_width',
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
                                'size' => 36,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_arrow_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .htmega-testimonial-area button.slick-arrow' => 'left: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-testimonial-area button.slick-next' => 'right: {{SIZE}}{{UNIT}}; left:auto;',
                            ],
                            'condition' =>[
                                'slarrows_style!'=>'2',
                            ]
                        ]
                    );
                    $this->add_responsive_control(
                        'slider_arrow_vertical_postion',
                        [
                            'label' => __( 'Vertical Position', 'htmega-addons' ),
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
                                '{{WRAPPER}} .htmega-testimonial-area .slick-prev,{{WRAPPER}} .htmega-testimonial-area .slick-next' => 'margin-top: {{SIZE}}{{UNIT}};margin-bottom:0px;',
                                '{{WRAPPER}} .htmega-sl-arraow-style-3.htmega-testimonial-area .slick-arrow' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top:0px;',
                            ],
                            
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'slider_arrow_boxshadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '.htmega-testimonial-area .slick-arrow',
                            
                        ]
                    );
                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'testimonial_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_testimonial_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'testimonial_arrow_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_testimonial_arrow_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_arrow_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'slider_arrow_boxshadow_hover',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '.htmega-testimonial-area .slick-arrow:hover',
                            
                        ]
                    );
                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style Testimonial arrow style end


        // Style Testimonial Dots style start
        $this->start_controls_section(
            'htmega_testimonial_dots_style',
            [
                'label'     => __( 'Pagination', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'sldots'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'testimonial_dots_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'testimonial_dots_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                $this->add_control(
                    'testimonial_dots_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button,{{WRAPPER}} .htmega-testimonial-style-4 ul.slick-dots li button::before' => 'color: {{VALUE}};',
                        ],
                        'condition' =>[
                            'htmega_testimonial_style' =>'4'
                        ],
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'testimonial_dots_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_testimonial_dots_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_dots_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_dots_height',
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
                                'size' => 12,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_testimonial_dots_width',
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
                                'size' => 12,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'htmega-testimonial_dots_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'htmega_testimonial_vertical_width',
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
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );   
                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'testimonial_dots_style_hover_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                $this->add_control(
                    'testimonial_dots_active_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-testimonial-area .slick-dots li.slick-active button,{{WRAPPER}} .htmega-testimonial-style-4 ul.slick-dots li.slick-active button::before' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .htmega-testimonial-area .slick-dots li.slick-active button,{{WRAPPER}} .htmega-testimonial-style-4 ul.slick-dots li::after' => 'background: {{VALUE}};',
                        ],
                        'condition' =>[
                            'htmega_testimonial_style' =>'4'
                        ],
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'testimonial_dots_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_testimonial_dots_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-testimonial-area .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_testimonial_dots_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-testimonial-area .slick-dots li.slick-active button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style Testimonial dots style end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $sectionid = "sid". $this-> get_id();

        $slarrows_style = 1;
        $slarrows_style = $settings['slarrows_style'];

        $slider_settings = [
            'sectionid'=> $sectionid,
            'arrows' => ('yes' === $settings['slarrows']),
            'arrow_prev_txt' => HTMega_Icon_manager::render_icon( $settings['slprevicon'], [ 'aria-hidden' => 'true' ] ),
            'arrow_next_txt' => HTMega_Icon_manager::render_icon( $settings['slnexticon'], [ 'aria-hidden' => 'true' ] ),
            'dots' => ('yes' === $settings['sldots']),
            'autoplay' => ('yes' === $settings['slautolay']),
            'autoplay_speed' => absint($settings['slautoplay_speed']),
            'animation_speed' => absint($settings['slanimation_speed']),
            'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
            'center_mode' => ( 'yes' === $settings['slcentermode']),
            'center_padding' => absint($settings['slcenterpadding2']),
            'testimonial_style_ck' => absint( $settings['htmega_testimonial_style'] ),
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


        $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-area htmega-sl-arraow-style-'.$slarrows_style );
        if( 10 == $settings['htmega_testimonial_style'] ){

            $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-style-3  htmega-testimonial-style-'.$settings['htmega_testimonial_style'].' '.$sectionid );
        }elseif( 11 == $settings['htmega_testimonial_style'] ){

            $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-style-5  htmega-testimonial-style-'.$settings['htmega_testimonial_style'].' '.$sectionid );
        }elseif( 12 == $settings['htmega_testimonial_style'] ){

            $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-style-3  htmega-testimonial-style-'.$settings['htmega_testimonial_style'].' '.$sectionid );
        }else{

            $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-style-'.$settings['htmega_testimonial_style'].' '.$sectionid );
        }
        if( $settings['slider_on'] == 'yes'){
            $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htmega-testimonial-activation' );   
            $this->add_render_attribute( 'testimonial_area_attr', 'data-settings', wp_json_encode( $slider_settings ) );   
        }

        if( ( $settings['htmega_testimonial_style'] == 3 || $settings['htmega_testimonial_style'] == 9 || $settings['htmega_testimonial_style'] == 10 ) && $settings['slider_on'] != 'yes' ){
            $this->add_render_attribute( 'testimonial_area_attr', 'class', 'htb-row' );
        }

        $s_display_none = ( 'yes' == $settings['slider_on'] ) ? ' style="display:none;"':'';
       

        ?>
            <div <?php echo $this->get_render_attribute_string( 'testimonial_area_attr' ).$s_display_none; ?>>

                <?php if( $settings['htmega_testimonial_style'] == 5 ): ?>

                    
                    <div class="htmega-testimonial-for" <?php echo $s_display_none; ?>>
                        <?php 
                            foreach ( $settings['htmega_testimonial_list'] as $testimonial ){
                                
                                if( !empty($testimonial['client_say']) ){
                                    
                                    echo '<div class="testimonial-desc"><p>'.wp_kses_post( $testimonial['client_say'] ).'</p>';
                                      // Rating
                                      if( !empty( $testimonial['client_rating'] ) ){
                                        $rating = $testimonial['client_rating'];
                                        $rating_whole = floor( $testimonial['client_rating'] );
                                        $rating_fraction = $rating - $rating_whole;
                                        echo '<ul class="htmega-testimonial-rating">';
                                            for($i = 1; $i <= 5; $i++){
                                                if( $i <= $rating_whole ){
                                                    echo '<li><i class="fa fa-star"></i></li>';
                                                } else {
                                                    if( $rating_fraction != 0 ){
                                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                        $rating_fraction = 0;
                                                    } else {
                                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                                    }
                                                }
                                            }
                                        echo '</ul>';
                                    }
                                    
                                    
                                    echo '</div>';
                                }
                                    
                            }
                        ?>
                    </div>

                    <!-- Start Testimonial Nav -->
                    <div class="htmega-testimonal-nav" <?php echo $s_display_none; ?>>
                        <?php foreach ( $settings['htmega_testimonial_list'] as $testimonial ) :?>
                            <div class="testimonal-img testimonal">
                                <?php
                                    if( !empty($testimonial['client_image']['url']) ){
                                        echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                    } 
                                ?>
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- End Testimonial Nav -->
                    <div class="testimonial-shape">
                        <?php
                            if( !empty($settings['client_image_divider']['url']) ){
                                echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' );
                            }
                        ?>
                    </div>

                <?php

                elseif( $settings['htmega_testimonial_style'] == 11 ): ?>
                    <!-- Start Testimonial Nav -->
                    <div class="htmega-testimonal-nav" <?php echo $s_display_none; ?>>
                        <?php foreach ( $settings['htmega_testimonial_list'] as $testimonial ) :?>
                            <div class="testimonal-img testimonal">
                                <?php
                                    if( !empty($testimonial['client_image']['url']) ){
                                        echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                    } 
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- End Testimonial Nav -->

                    <div class="htmega-testimonial-for" <?php echo $s_display_none; ?>>
                        <?php 
                            foreach ( $settings['htmega_testimonial_list'] as $testimonial ){
                                
                                if( !empty($testimonial['client_say']) ){
                                    
                                    echo '<div class="testimonial-desc"><p>'.wp_kses_post( $testimonial['client_say'] ).'</p>';
                                      // Rating
                                      if( !empty( $testimonial['client_rating'] ) ){
                                        $rating = $testimonial['client_rating'];
                                        $rating_whole = floor( $testimonial['client_rating'] );
                                        $rating_fraction = $rating - $rating_whole;
                                        echo '<ul class="htmega-testimonial-rating">';
                                            for($i = 1; $i <= 5; $i++){
                                                if( $i <= $rating_whole ){
                                                    echo '<li><i class="fa fa-star"></i></li>';
                                                } else {
                                                    if( $rating_fraction != 0 ){
                                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                        $rating_fraction = 0;
                                                    } else {
                                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                                    }
                                                }
                                            }
                                        echo '</ul>';
                                    } ?>
                                    
                                    <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                        }
                                    ?>
                                </div>
                                <?php
                                    echo '</div>';
                                }
                                    
                            }
                        ?>
                    </div>

                    <?php
                    else: 
                        foreach ( $settings['htmega_testimonial_list'] as $testimonial ) :
                            if( ($settings['htmega_testimonial_style'] == 3) && $settings['slider_on'] != 'yes'){ echo '<div class="htb-col-lg-6 htb-col-xl-6 htb-col-sm-12 htb-col-12">';}
                ?>
                    <?php if( $settings['htmega_testimonial_style'] == 6 ): ?>
                        <div class="testimonal">
                            <div class="content">
                                <?php
                                    if( !empty($testimonial['client_say']) ){
                                        echo '<p>'.wp_kses_post( $testimonial['client_say'] ).'</p>';
                                    }
                                ?>
                                <div class="triangle"></div>
                            </div>
                            <div class="clint-info">
                                <?php
                                    if( !empty($testimonial['client_image']['url']) ){
                                        echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                    } 

                                    if( !empty($settings['client_image_divider']['url']) ){
                                        echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                    }
                                        // Rating
                                        if( !empty( $testimonial['client_rating'] ) ){
                                            $rating = $testimonial['client_rating'];
                                            $rating_whole = floor( $testimonial['client_rating'] );
                                            $rating_fraction = $rating - $rating_whole;
                                            echo '<ul class="htmega-testimonial-rating">';
                                                for($i = 1; $i <= 5; $i++){
                                                    if( $i <= $rating_whole ){
                                                        echo '<li><i class="fa fa-star"></i></li>';
                                                    } else {
                                                        if( $rating_fraction != 0 ){
                                                            echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                            $rating_fraction = 0;
                                                        } else {
                                                            echo '<li><i class="fa fa-star-o"></i></li>';
                                                        }
                                                    }
                                                }
                                            echo '</ul>';
                                        }
                                    if( !empty($testimonial['client_name']) ){
                                        echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                    }
                                    if( !empty($testimonial['client_designation']) ){
                                        echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                    }
                                ?>
                            </div>
                        </div>

                    <?php elseif( $settings['htmega_testimonial_style'] == 7 ): ?>
                        <div class="testimonal">
                            <?php
                                if( !empty($testimonial['client_image']['url']) ){
                                    echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                } 

                                if( !empty($settings['client_image_divider']['url']) ){
                                    echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                }
                                if( !empty($testimonial['client_say']) ){
                                    echo ' <div class="content"><p>'.wp_kses_post( $testimonial['client_say'] ).'</p></div>';
                                }
                            ?>
                            <div class="clint-info">
                                <?php

                                 // Rating
                                 if( !empty( $testimonial['client_rating'] ) ){
                                    $rating = $testimonial['client_rating'];
                                    $rating_whole = floor( $testimonial['client_rating'] );
                                    $rating_fraction = $rating - $rating_whole;
                                    echo '<ul class="htmega-testimonial-rating">';
                                        for($i = 1; $i <= 5; $i++){
                                            if( $i <= $rating_whole ){
                                                echo '<li><i class="fa fa-star"></i></li>';
                                            } else {
                                                if( $rating_fraction != 0 ){
                                                    echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                    $rating_fraction = 0;
                                                } else {
                                                    echo '<li><i class="fa fa-star-o"></i></li>';
                                                }
                                            }
                                        }
                                    echo '</ul>';
                                }
                                    if( !empty($testimonial['client_name']) ){
                                        echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                    }
                                    if( !empty($testimonial['client_designation']) ){
                                        echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                    }
                                ?>
                            </div>
                        </div>

                    <?php elseif( $settings['htmega_testimonial_style'] == 8 ): ?>
                        <div class="testimonal">
                            <div class="content">
                                <?php
                                    if( !empty($testimonial['client_image']['url']) ){
                                        echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                    } 

                                    if( !empty($settings['client_image_divider']['url']) ){
                                        echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                    }
                                ?>
                                <div class="clint-info">
                                    <?php
                                     // Rating
                                     if( !empty( $testimonial['client_rating'] ) ){
                                        $rating = $testimonial['client_rating'];
                                        $rating_whole = floor( $testimonial['client_rating'] );
                                        $rating_fraction = $rating - $rating_whole;
                                        echo '<ul class="htmega-testimonial-rating">';
                                            for($i = 1; $i <= 5; $i++){
                                                if( $i <= $rating_whole ){
                                                    echo '<li><i class="fa fa-star"></i></li>';
                                                } else {
                                                    if( $rating_fraction != 0 ){
                                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                        $rating_fraction = 0;
                                                    } else {
                                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                                    }
                                                }
                                            }
                                        echo '</ul>';
                                    }
                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                                if( !empty($testimonial['client_say']) ){
                                    echo '<div class="content"><p>'.wp_kses_post( $testimonial['client_say'] ).'</p></div>';
                                }
                            ?>
                        </div>

                    <?php elseif( ( $settings['htmega_testimonial_style'] == 9 ) && $settings['slider_on'] != 'yes' ): ?>
                        <div class="htb-col-xl-4 htb-col-lg-4 htb-col-sm-6 htb-col-12">
                            <div class="testimonal">
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_image']['url']) ){
                                            echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                        } 

                                        if( !empty($settings['client_image_divider']['url']) ){
                                            echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                        }
                                    ?>
                                    <div class="clint-info">
                                        <?php
                                         // Rating
                                       if( !empty( $testimonial['client_rating'] ) ){
                                        $rating = $testimonial['client_rating'];
                                        $rating_whole = floor( $testimonial['client_rating'] );
                                        $rating_fraction = $rating - $rating_whole;
                                        echo '<ul class="htmega-testimonial-rating">';
                                            for($i = 1; $i <= 5; $i++){
                                                if( $i <= $rating_whole ){
                                                    echo '<li><i class="fa fa-star"></i></li>';
                                                } else {
                                                    if( $rating_fraction != 0 ){
                                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                        $rating_fraction = 0;
                                                    } else {
                                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                                    }
                                                }
                                            }
                                        echo '</ul>';
                                    }
                                            if( !empty($testimonial['client_name']) ){
                                                echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                            }
                                            if( !empty($testimonial['client_designation']) ){
                                                echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                    if( !empty($testimonial['client_say']) ){
                                        echo '<div class="content"><p>'.wp_kses_post( $testimonial['client_say'] ).'</p></div>';
                                    }
                                ?>
                            </div>
                        </div>
                        <?php elseif( $settings['htmega_testimonial_style'] == 13 ): ?>
                        <div class="testimonal">
                            <div class="content">
                            <?php
                                if( !empty($testimonial['client_say']) ){
                                    echo '<p>'.wp_kses_post( $testimonial['client_say'] ).'</p>';
                                }
                        
                                if( !empty($testimonial['client_image']['url']) ){
                                    echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
                                } 

                                
                                ?>
                                <div class="clint-info">
                                    <?php
                                        // Rating
                                        if( !empty( $testimonial['client_rating'] ) ){
                                            $rating = $testimonial['client_rating'];
                                            $rating_whole = floor( $testimonial['client_rating'] );
                                            $rating_fraction = $rating - $rating_whole;
                                            echo '<ul class="htmega-testimonial-rating">';
                                                for($i = 1; $i <= 5; $i++){
                                                    if( $i <= $rating_whole ){
                                                        echo '<li><i class="fa fa-star"></i></li>';
                                                    } else {
                                                        if( $rating_fraction != 0 ){
                                                            echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                            $rating_fraction = 0;
                                                        } else {
                                                            echo '<li><i class="fa fa-star-o"></i></li>';
                                                        }
                                                    }
                                                }
                                            echo '</ul>';
                                        }

                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                            
                        </div>
                    <?php else:?>
                        <div class="testimonal">
                            <?php
                                if( !empty($testimonial['client_image']['url']) ){
                                    echo '<div class="testimonal-image">'.Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' );

                                    
                                       // Rating
                                       if( !empty( $testimonial['client_rating'] ) && 10 == $settings['htmega_testimonial_style'] ){
                                        $rating = $testimonial['client_rating'];
                                        $rating_whole = floor( $testimonial['client_rating'] );
                                        $rating_fraction = $rating - $rating_whole;
                                        echo '<ul class="htmega-testimonial-rating">';
                                            for($i = 1; $i <= 5; $i++){
                                                if( $i <= $rating_whole ){
                                                    echo '<li><i class="fa fa-star"></i></li>';
                                                } else {
                                                    if( $rating_fraction != 0 ){
                                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                        $rating_fraction = 0;
                                                    } else {
                                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                                    }
                                                }
                                            }
                                        echo '</ul>';
                                    }
                                    
                                    echo '</div>';
                                } 

                                if( !empty($settings['client_image_divider']['url']) && 12 != $settings['htmega_testimonial_style'] ){
                                    echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                }
                            ?>

                            <?php if( $settings['htmega_testimonial_style'] == 3 || 10 == $settings['htmega_testimonial_style'] || 12 == $settings['htmega_testimonial_style'] ):?>
                                <div class="content">

                                    <?php
                                     if( !empty($settings['client_image_divider']['url']) && 12 == $settings['htmega_testimonial_style'] ){
                                        echo '<div class="shape">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'client_image_divider_size', 'client_image_divider' ).'</div>';
                                    }
                                        if( !empty($testimonial['client_say']) ){
                                            echo '<p>'.wp_kses_post( $testimonial['client_say'] ).'</p>';
                                        }

                                        // Rating
                                        if( !empty( $testimonial['client_rating'] ) && 12 == $settings['htmega_testimonial_style'] ){
                                            $rating = $testimonial['client_rating'];
                                            $rating_whole = floor( $testimonial['client_rating'] );
                                            $rating_fraction = $rating - $rating_whole;
                                            echo '<ul class="htmega-testimonial-rating">';
                                                for($i = 1; $i <= 5; $i++){
                                                    if( $i <= $rating_whole ){
                                                        echo '<li><i class="fa fa-star"></i></li>';
                                                    } else {
                                                        if( $rating_fraction != 0 ){
                                                            echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                            $rating_fraction = 0;
                                                        } else {
                                                            echo '<li><i class="fa fa-star-o"></i></li>';
                                                        }
                                                    }
                                                }
                                            echo '</ul>';
                                        }

                                    ?>
                                    
                                    <div class="clint-info">
                                        <?php
                                         // Rating
                                       if( !empty( $testimonial['client_rating'] ) && 3 == $settings['htmega_testimonial_style'] ){
                                        $rating = $testimonial['client_rating'];
                                        $rating_whole = floor( $testimonial['client_rating'] );
                                        $rating_fraction = $rating - $rating_whole;
                                        echo '<ul class="htmega-testimonial-rating">';
                                            for($i = 1; $i <= 5; $i++){
                                                if( $i <= $rating_whole ){
                                                    echo '<li><i class="fa fa-star"></i></li>';
                                                } else {
                                                    if( $rating_fraction != 0 ){
                                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                        $rating_fraction = 0;
                                                    } else {
                                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                                    }
                                                }
                                            }
                                        echo '</ul>';
                                    }
                                            if( !empty($testimonial['client_name']) ){
                                                echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                            }
                                            if( !empty($testimonial['client_designation']) ){
                                                echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php else:?>
                                <div class="content">
                                    <?php
                                        if( !empty($testimonial['client_say']) ){
                                            echo '<p>'.wp_kses_post( $testimonial['client_say'] ).'</p>';
                                        }
                                         // Rating
                                       if( !empty( $testimonial['client_rating'] ) ){
                                        $rating = $testimonial['client_rating'];
                                        $rating_whole = floor( $testimonial['client_rating'] );
                                        $rating_fraction = $rating - $rating_whole;
                                        echo '<ul class="htmega-testimonial-rating">';
                                            for($i = 1; $i <= 5; $i++){
                                                if( $i <= $rating_whole ){
                                                    echo '<li><i class="fa fa-star"></i></li>';
                                                } else {
                                                    if( $rating_fraction != 0 ){
                                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                                        $rating_fraction = 0;
                                                    } else {
                                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                                    }
                                                }
                                            }
                                        echo '</ul>';
                                    }
                                        if( !empty($testimonial['client_name']) ){
                                            echo '<h4>'.htmega_kses_title( $testimonial['client_name'] ).'</h4>';
                                        }
                                        if( !empty($testimonial['client_designation']) ){
                                            echo '<span>'.esc_html( $testimonial['client_designation'] ).'</span>';
                                        }
                                    ?>
                                </div>
                            <?php endif;?>
                        </div>
                    <?php endif;?>

                    <?php
                        if( ( $settings['htmega_testimonial_style'] == 3 ) && $settings['slider_on'] != 'yes' ){ echo '</div>'; } 
                        endforeach;
                endif;
                ?>
            </div>

            <?php 
                 $htmega_print_css = '';
            ?>
             <!-- Border style  -->
            <?php if( 'yes'== $settings['shape_position']  && ( '3' == $settings['htmega_testimonial_style'] || '10' == $settings['htmega_testimonial_style'] ) ){
                    $htmega_print_css .= "
                    .{$sectionid}.htmega-testimonial-style-3 .testimonal .content .clint-info::before {
                        top: auto;
                        bottom: 100%;
                    }
                    .{$sectionid}.htmega-testimonial-style-3 .testimonal .content .clint-info,.{$sectionid}.htmega-testimonial-style-3 .testimonal .content .clint-info *{
                        padding-left:0!important;
                    }
                     ";
                    ?>
                <?php } ?>
              
            <?php if( 'yes'== $settings['image_arrow_shape'] && '4' == $settings['htmega_testimonial_style']  ){
                  //Image shape for style 4 
                  $htmega_print_css .= "
                    .{$sectionid}.htmega-testimonial-style-4 .testimonal .testimonal-image::after {
                        background: transparent!important;
                        right: 18px;
                        border: 12px solid blue;
                        border-top-color: transparent;
                        border-bottom-color: transparent;
                        border-right-color: transparent;
                        border-left-width: 20px;
                    }
                    .{$sectionid}.htmega-testimonial-style-4 .testimonal .testimonal-image::before{
                        display:none;
                    }
                    .{$sectionid}.htmega-testimonial-style-4 .testimonal .content{
                        padding-left:0;
                    }
                    ";
                    ?>
                <?php } ?>
                
            <?php if( 'yes' == $settings['designation_border'] && '4' == $settings['htmega_testimonial_style']  ){
                  //Designation Shape for style 4 
                  $htmega_print_css .= "
                    .{$sectionid}.htmega-testimonial-style-4 .testimonal .content span:before {
                        left: 0;
                        width: 20px;
                        background: #fff;
                        height: 2px;
                        position: absolute;
                        top: 50%;
                        transform:translateY(-50%);
                    }
                    .{$sectionid}.htmega-testimonial-style-4 .testimonal .content span{
                        padding-left:30px;
                        position:relative;
                    }
                     ";
                     
                    ?>
                <?php }
                
                
                if( '' != $htmega_print_css ){ ?>
                    <style>
                        <?php echo esc_html( $htmega_print_css ); ?>
                    </style>

                <?php } ?>
        <?php
    }
}

