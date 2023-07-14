<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Blockquote extends Widget_Base {

    public function get_name() {
        return 'htmega-blockquote-addons';
    }
    
    public function get_title() {
        return __( 'Blockquote', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-blockquote';
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
            'blockquote_content',
            [
                'label' => __( 'Blockquote', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
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

            $this->add_control(
                'template_id',
                [
                    'label'       => __( 'Content', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '0',
                    'options'     => htmega_elementor_template(),
                    'condition'   => [
                        'content_source' => "elementor"
                    ],
                ]
            );

            $this->add_control(
                'custom_content',
                [
                    'label' => __( 'Content', 'htmega-addons' ),
                    'type' => Controls_Manager::WYSIWYG,
                    'title' => __( 'Blockquote Content', 'htmega-addons' ),
                    'condition' => [
                        'content_source' =>'custom',
                    ],
                ]
            );

            $this->add_control(
                'blockquote_by',
                [
                    'label' => __( 'Blockquote By', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Jon Doy', 'htmega-addons' ),
                    'placeholder' => __( 'Jon Doy', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'blockquote_type',
                [
                    'label' => __('Blockquote Type','htmega-addons'),
                    'type' =>Controls_Manager::CHOOSE,
                    'options' =>[
                        'img' =>[
                            'title' =>__('Image','htmega-addons'),
                            'icon' =>'eicon-image',
                        ],
                        'icon' =>[
                            'title' =>__('Icon','htmega-addons'),
                            'icon' =>'eicon-info-circle',
                        ]
                    ],
                    'default' =>'img',
                ]
            );

            $this->add_control(
                'blockquote_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'blockquote_type' => 'img',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'blockquote_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'blockquote_type' => 'img',
                    ]
                ]
            );

            $this->add_control(
                'blockquote_icon',
                [
                    'label' =>__('Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-pencil',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'blockquote_type' => 'icon',
                    ]
                ]
            );

            $this->add_control(
                'blockquote_position',
                [
                    'label' => __( 'Blockquote Position', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'righttop',
                    'options' => [
                        'lefttop'      => __( 'Left Top', 'htmega-addons' ),
                        'leftcenter'   => __( 'Left Center', 'htmega-addons' ),
                        'leftbottom'   => __( 'Left Bottom', 'htmega-addons' ),
                        'centertop'    => __( 'Center Top', 'htmega-addons' ),
                        'center'       => __( 'Center Center', 'htmega-addons' ),
                        'centerbottom' => __( 'Center Bottom', 'htmega-addons' ),
                        'righttop'     => __( 'Right Top', 'htmega-addons' ),
                        'rightcenter'  => __( 'Right Center', 'htmega-addons' ),
                        'rightbottom'  => __( 'Right Bottom', 'htmega-addons' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_blockquote_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'htmega_blockquote_align',
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
                        '{{WRAPPER}} .htmega-blockquote blockquote' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'left',
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'htmega_blockquote_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote',
                ]
            );

            $this->add_responsive_control(
                'htmega_blockquote_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_blockquote_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_blockquote_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote',
                ]
            );

            $this->add_responsive_control(
                'htmega_blockquote_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();


        $this->start_controls_section(
            'htmega_blockquote_content_style_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'htmega_blockquote_content_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#5b5b5b',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_content' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_content p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_blockquote_content_typography',
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_content,{{WRAPPER}} .htmega-blockquote blockquote .blockquote_content p',
                ]
            );

            $this->add_responsive_control(
                'htmega_blockquote_content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_blockquote_content_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();


        $this->start_controls_section(
            'htmega_blockquoteby_style_section',
            [
                'label' => __( 'Quote By', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'htmega_blockquoteby_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#0056ff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote cite' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'htmega_blockquotenby_typography',
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote cite',
                ]
            );

            $this->add_responsive_control(
                'htmega_blockquoteby_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote cite' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_blockquoteby_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote cite' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_blockquoteby_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote cite',
                ]
            );

            $this->add_responsive_control(
                'htmega_blockquoteby_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote cite' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_control(
                'blockquoteby_before_position',
                [
                    'label' => __( 'Separator Position', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'before',
                    'options' => [
                        'before' => __( 'Before', 'htmega-addons' ),
                        'after'  => __( 'After', 'htmega-addons' ),
                        'none'   => __( 'None', 'htmega-addons' ),
                    ],
                    'separator'=>'before',
                ]
            );

            $this->add_control(
                'blockquoteby_before_color',
                [
                    'label' => __( 'Separator Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#0056ff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote cite::before' => 'background-color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'blockquoteby_before_position!'=>'none',
                    ]
                ]
            );

            $this->add_control(
                'blockquoteby_before_width',
                [
                    'label' => __( 'Separator Width', 'htmega-addons' ),
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
                        'size' => 20,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote cite::before' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'blockquoteby_before_position!'=>'none',
                    ]
                ]
            );

            $this->add_control(
                'blockquoteby_before_height',
                [
                    'label' => __( 'Separator Height', 'htmega-addons' ),
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
                        'size' => 2,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote cite::before' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'blockquoteby_before_position!'=>'none',
                    ]
                ]
            );

        $this->end_controls_section();


        // blockquote icon style start
        $this->start_controls_section(
            'htmega_blockquoteicon_style_section',
            [
                'label' => __( 'Quote Icon', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'blockquote_type' =>'icon',
                    'blockquote_icon!' =>'',
                ],
            ]
        );

            $this->add_control(
                'blockquoteicon_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon svg path' => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'blockquoteicon_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon',
                ]
            );

            $this->add_responsive_control(
                'blockquoteicon_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'blockquoteicon_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'blockquoteicon_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon',
                ]
            );

            $this->add_responsive_control(
                'blockquoteicon_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_control(
                'blockquoteicon_fontsize',
                [
                    'label' => __( 'Font Size', 'htmega-addons' ),
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
                        'size' => 18,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'blockquoteicon_line_height',
                [
                    'label' => __( 'Line Height', 'htmega-addons' ),
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
                        'size' => 45,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon' => 'line-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'blockquoteicon_width',
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
                        'size' => 45,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'blockquoteicon_height',
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
                        'size' => 45,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote .blockquote_icon' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();
        

        // blockquote image style start
        $this->start_controls_section(
            'htmega_blockquoteimage_style_section',
            [
                'label' => __( 'Quote Image', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'blockquote_type' => 'img',
                ],
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'blockquoteimage_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote img',
                ]
            );

            $this->add_responsive_control(
                'blockquoteimage_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'blockquoteimage_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'blockquoteimage_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-blockquote blockquote img',
                ]
            );

            $this->add_responsive_control(
                'blockquoteimage_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_control(
                'blockquoteimage_width',
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
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-blockquote blockquote img' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_blockquote_attr', 'class', 'htmega-blockquote' );
        $this->add_render_attribute( 'htmega_blockquote_attr', 'class', 'htmega-blockquote-position-'.$settings['blockquote_position'] );
        $this->add_render_attribute( 'htmega_blockquote_attr', 'class', 'htmega-citeseparator-position-'.$settings['blockquoteby_before_position'] );
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_blockquote_attr' ); ?>>
                <blockquote>
                    <?php 
                        if ( $settings['content_source'] == 'custom' && !empty( $settings['custom_content'] ) ) {
                            echo '<div class="blockquote_content">'.wp_kses_post( $settings['custom_content'] ).'</div>';
                        } elseif ( $settings['content_source'] == "elementor" && !empty( $settings['template_id'] )) {
                            echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'] );
                        }
                        if( !empty( $settings['blockquote_by'] ) ){
                            echo '<cite class="quote-by"> '.esc_html( $settings['blockquote_by']).' </cite>';
                        }
                        if( !empty( $settings['blockquote_image'] ) && $settings['blockquote_type'] == 'img' ){
                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'blockquote_imagesize', 'blockquote_image' );
                        }else{
                            echo sprintf('<span class="blockquote_icon">%1$s</span>', HTMega_Icon_manager::render_icon( $settings['blockquote_icon'], [ 'aria-hidden' => 'true' ] ) );
                        }
                    ?>
                </blockquote>
           </div>

        <?php
    }
}