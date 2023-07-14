<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Counter extends Widget_Base {

    public function get_name() {
        return 'htmega-counter-addons';
    }
    
    public function get_title() {
        return __( 'Counter', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-counter';
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
            'counterup',
            'htmega-widgets-scripts',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'counter_content',
            [
                'label' => __( 'Counter', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'counter_layout_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default'=>'1',
                    'options' => [
                        '1' => __( 'Style One', 'htmega-addons' ),
                        '2' => __( 'Style Two', 'htmega-addons' ),
                        '3' => __( 'Style Three', 'htmega-addons' ),
                        '4' => __( 'Style Four', 'htmega-addons' ),
                        '5' => __( 'Style Five', 'htmega-addons' ),
                        '6' => __( 'Style Six', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'counter_layout_align',
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
                    'default'     => is_rtl() ? 'right' : 'left',
                    'toggle'      => false,
                    'label_block' => false,
                    'condition' => [
                        'counter_layout_style' => '2',
                    ]
                ]
            );

            $this->add_control(
                'counter_icon_type',
                [
                    'label'   => __( 'Icon Type', 'htmega-addons' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'label_block'=>false,
                    'options' => [
                        'none' => [
                            'title' => __( 'None', 'htmega-addons' ),
                            'icon'  => 'eicon-ban',
                        ],
                        'image' => [
                            'title' => __( 'Image', 'htmega-addons' ),
                            'icon'  => 'eicon-image-bold',
                        ],
                        'icon' => [
                            'title' => __( 'Icon', 'htmega-addons' ),
                            'icon'  => 'eicon-info-circle',
                        ],
                    ],
                    'default' => 'image',
                ]
            );

            $this->add_control(
                'counter_icon',
                [
                    'label' => __( 'Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'condition'=>[
                        'counter_icon_type'=>'icon',
                    ],
                ]
            );

            $this->add_control(
                'counter_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'counter_icon_type' => 'image',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'counter_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'counter_icon_type' => 'image',
                    ]
                ]
            );

            $this->add_control(
                'counter_title',
                [
                    'label' => __( 'Counter Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Happy Clients', 'htmega-addons' ),
                    'placeholder' => __( 'Type your title here', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'terget_number',
                [
                    'label' => __( 'Target Number', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 100,
                ]
            );

            $this->add_control(
                'counter_number_prefix',
                [
                    'label' => __( 'Number Prefix', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( '$', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'counter_number_suffix',
                [
                    'label' => __( 'Number Suffix', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( '+', 'htmega-addons' ),
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'counter_style_section',
            [
                'label' => __( 'Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area',
                ]
            );

            $this->add_control(
                'counter_area_background_overlay',
                [
                    'label'     => __( 'Background Overlay', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area::before' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                    'default'=>'#52b6bc',
                    'condition' => [
                        'counter_area_background_image[id]!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'counter_area_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_area_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area',
                ]
            );

            $this->add_responsive_control(
                'counter_area_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-counter-area::before' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'title_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area',
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'counter_area_align',
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
                        '{{WRAPPER}} .htmega-counter-area' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'condition'=>[
                        'counter_layout_style!'=>'2',
                    ]
                ]
            );

            $this->add_responsive_control(
                'counter_area_align_justify',
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
                        '{{WRAPPER}} .htmega-counter-area.htmega-counter-style-2' => 'justify-content: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'condition'=>[
                        'counter_layout_style'=>'2',
                    ]
                ]
            );

            $this->add_control(
                'counter_area_width',
                [
                    'label' => __( 'Box  Width', 'htmega-addons' ),
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
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area.htmega-counter-style-3' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'counter_layout_style'=>'3',
                    ]
                ]
            );

            $this->add_control(
                'counter_area_height',
                [
                    'label' => __( 'Box Height', 'htmega-addons' ),
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
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area.htmega-counter-style-3' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'counter_layout_style'=>'3',
                    ]
                ]
            );

        $this->end_controls_section();

        // Style Number tab section
        $this->start_controls_section(
            'counter_number_style_section',
            [
                'label' => __( 'Number', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'terget_number!'=>'',
                ]
            ]
        );
            $this->add_responsive_control(
                'counter_number_align',
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
                        '{{WRAPPER}} .htmega-counter-style-2 .htmega-counter-content' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                    'condition'=>[
                        'counter_layout_style'=>'2',
                    ]
                ]
            );

            $this->add_control(
                'counter_number_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#696969',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_number_typography',
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number,{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_number_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number,{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number',
                ]
            );

            $this->add_responsive_control(
                'counter_number_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_number_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_number_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number,{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number',
                ]
            );

            $this->add_responsive_control(
                'counter_number_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-number' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon .htmega-counter-number' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Title tab section
        $this->start_controls_section(
            'counter_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'counter_title!'=>'',
                ]
            ]
        );

            $this->add_responsive_control(
                'counter_title_align',
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
                        '{{WRAPPER}} .htmega-counter-style-2 .htmega-counter-content h2' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                    'condition'=>[
                        'counter_layout_style'=>'2',
                    ]
                ]
            );

            $this->add_control(
                'counter_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#898989',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_title_typography',
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_title_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title',
                ]
            );

            $this->add_responsive_control(
                'counter_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title',
                ]
            );

            $this->add_responsive_control(
                'counter_title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-counter-title' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_control(
                'counter_title_after_color',
                [
                    'label' => __( 'Title After Border Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-content h2::before' => 'background-color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'counter_layout_style!'=>array('2','3','6'),
                    ]
                ]
            );

            $this->add_control(
                'counter_title_brdr_width',
                [
                    'label' => __( 'After Border Width', 'htmega-addons' ),
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
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-content h2::before' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'counter_layout_style!'=>array('2','3','6'),
                    ]
                ]
            );

            $this->add_control(
                'counter_title_brdr_height',
                [
                    'label' => __( 'After Border Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-content h2::before' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'counter_layout_style!'=>array('2','3','6'),
                    ]
                ]
            );

            $this->add_control(
                'counter_title_brdr_position',
                [
                    'label' => __( 'After Border Position', 'htmega-addons' ),
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
                            'max' => 200,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-content h2::before' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'counter_layout_style!'=>array('2','3','6'),
                    ]
                ]
            );

        $this->end_controls_section();

        // Style Title After Border Control
        $this->start_controls_section(
            'counter_title_border_section',
            [
                'label' => __( 'Border After Color', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'counter_layout_style'=>'6',
                ]
            ]
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_title_brdr_clr',
                    'label' => __( 'After Border Color', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-style-6 .htmega-counter-content::after',
                ]
            );

            $this->add_control(
                'counter_title_after_brdr_width',
                [
                    'label' => __( 'Border Width', 'htmega-addons' ),
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
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-style-6 .htmega-counter-content::after' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Icon tab section
        $this->start_controls_section(
            'counter_icon_style_section',
            [
                'label' => __( 'Icon/Image', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'counter_icon_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ed552d',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon svg path' => 'fill: {{VALUE}};',
                    ],
                    'condition'=>[
                        'counter_icon_type'=>'icon',
                    ],
                ]
            );

            $this->add_control(
                'counter_icon_size',
                [
                    'label' => __( 'Size', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
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
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'counter_icon_type'=>'icon',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_icon_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span,{{WRAPPER}} .htmega-counter-area .htmega-counter-img',
                ]
            );

            $this->add_responsive_control(
                'counter_icon_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_icon_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_icon_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span,{{WRAPPER}} .htmega-counter-area .htmega-counter-img',
                ]
            );

            $this->add_responsive_control(
                'counter_icon_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span, {{WRAPPER}} .htmega-counter-area .htmega-counter-img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'counter_icon_boxshadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-icon span, {{WRAPPER}} .htmega-counter-area .htmega-counter-img',
                ]
            );
        $this->end_controls_section();

        // Style Prefix tab section
        $this->start_controls_section(
            'counter_prefix_style_section',
            [
                'label' => __( 'Prefix', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'counter_number_prefix!'=>'',
                ]
            ]
        );
            $this->add_control(
                'counter_prefix_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#696969',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_prefix_typography',
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_prefix_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix',
                ]
            );

            $this->add_responsive_control(
                'counter_prefix_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_prefix_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_prefix_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix',
                ]
            );

            $this->add_responsive_control(
                'counter_prefix_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-prefix' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Suffix tab section
        $this->start_controls_section(
            'counter_suffix_style_section',
            [
                'label' => __( 'Suffix', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'counter_number_suffix!'=>'',
                ]
            ]
        );
            $this->add_control(
                'counter_suffix_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#696969',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-counter-style-6 .htmega-counter-icon span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_suffix_typography',
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix,.htmega-counter-style-6 .htmega-counter-icon .htmega-suffix',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_suffix_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix',
                ]
            );

            $this->add_responsive_control(
                'counter_suffix_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-counter-style-6 .htmega-counter-icon .htmega-suffix' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'counter_suffix_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix,.htmega-counter-style-6 .htmega-counter-icon .htmega-suffix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'counter_suffix_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix,.htmega-counter-style-6 .htmega-counter-icon .htmega-suffix',
                ]
            );

            $this->add_responsive_control(
                'counter_suffix_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-counter-area .htmega-counter-content .htmega-suffix,.htmega-counter-style-6 .htmega-counter-icon .htmega-suffix' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'htmega_counter_attr', 'class', 'htmega-counter-area htmega-counter-style-'.$settings['counter_layout_style'] );

        $this->add_render_attribute( 'htmega_counter_attr', 'class', 'htmega-countericon-align-'.$settings['counter_layout_align'] );
        
        $prefix = $suffix = '';
        if( !empty($settings['counter_number_prefix']) ){
            $prefix = '<span class="htmega-prefix">'.$settings['counter_number_prefix'].'</span>';
        }
        if( !empty($settings['counter_number_suffix']) ){ 
            $suffix = '<span class="htmega-suffix">'.$settings['counter_number_suffix'].'</span>';
        }
    
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_counter_attr' ); ?>>
                <?php
                    if( $settings['counter_layout_style'] == 6 ){
                        echo '<div class="htmega-counter-icon">';
                            if( isset( $settings['counter_icon']['value'] ) ){
                                echo '<span>'.HTMega_Icon_manager::render_icon( $settings['counter_icon'], [ 'aria-hidden' => 'true' ] ).'</span>';
                            }
                            if( isset( $settings['counter_image']['url'] ) ){
                                echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'counter_image_size', 'counter_image' );
                            }
                            if( !empty( $settings['terget_number'] ) ){
                                echo htmega_kses_title( $prefix ).'<span class="htmega-counter-number">'.esc_html( $settings['terget_number'] ).'</span>'.htmega_kses_title( $suffix );
                            }
                        echo '</div>';
                    }else{
                        if( isset( $settings['counter_icon']['value'] ) ){
                            echo '<div class="htmega-counter-icon"><span>'.HTMega_Icon_manager::render_icon( $settings['counter_icon'], [ 'aria-hidden' => 'true' ] ).'</span></div>';
                        }
                        if( isset( $settings['counter_image']['url'] ) ){
                            echo '<div class="htmega-counter-img">'.Group_Control_Image_Size::get_attachment_image_html( $settings, 'counter_image_size', 'counter_image' ).'</div>';
                        }
                    }                    
                ?>
                <div class="htmega-counter-content">
                    <?php
                        if($settings['counter_layout_style'] == 4 ){
                            if( !empty( $settings['counter_title'] ) ){
                                echo '<h2 class="htmega-counter-title">'.esc_html( $settings['counter_title'] ).'</h2>';
                            }
                            if( !empty( $settings['terget_number'] ) ){
                                echo htmega_kses_title( $prefix ).'<span class="htmega-counter-number">'.esc_html( $settings['terget_number'] ).'</span>'.htmega_kses_title( $suffix );
                            }
                        }elseif($settings['counter_layout_style'] == 6 ){
                            if( !empty( $settings['counter_title'] ) ){
                                echo '<h2 class="htmega-counter-title">'.esc_html( $settings['counter_title'] ).'</h2>';
                            }
                        }else{
                            if( !empty( $settings['terget_number'] ) ){
                                echo htmega_kses_title( $prefix ).'<span class="htmega-counter-number">'.esc_html( $settings['terget_number'] ).'</span>'.htmega_kses_title( $suffix );
                            }
                            if( !empty( $settings['counter_title'] ) ){
                                echo '<h2 class="htmega-counter-title">'.esc_html( $settings['counter_title'] ).'</h2>';
                            }
                        }
                    ?>
                </div>
            </div>
        <?php
    }
}