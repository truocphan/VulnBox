<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Section_Title extends Widget_Base {

    public function get_name() {
        return 'section-title-addons';
    }
    
    public function get_title() {
        return __( 'Section Title', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-t-letter';
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
            'section_title_txt',
            [
                'label' => __( 'Section Title', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'titlestyle',
                [
                    'label' => __( 'Title Style', 'htmega-addons' ),
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

            $this->add_control(
                'section_title_text',
                [
                    'label' => __( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter your title', 'htmega-addons' ),
                    'default' => __( 'Add Your Heading Text Here', 'htmega-addons' ),
                    'title' => __( 'Enter your title', 'htmega-addons' ),
                    'description' => __( 'Put the highlighted word in between the span tags!', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'section_subtitle_text',
                [
                    'label' => __( 'Sub Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter your sub title', 'htmega-addons' ),
                    'default' => '',
                    'title' => __( 'Enter your sub title', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'section_advancetitle_text',
                [
                    'label' => __( 'Advance Title', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'Enter your advance title', 'htmega-addons' ),
                    'default' => '',
                    'title' => __( 'Enter your advance title', 'htmega-addons' ),
                    'condition' =>[
                        'titlestyle' => 'five',
                    ],
                ]
            );

            $this->add_control(
                'section_icon_type',
                [
                    'label' => esc_html__('Icon Type','htmega-addons'),
                    'type' =>Controls_Manager::CHOOSE,
                    'options' =>[
                        'img' =>[
                            'title' =>__('Image','htmega-addons'),
                            'icon' =>'eicon-image-bold',
                        ],
                        'icon' =>[
                            'title' =>__('Icon','htmega-addons'),
                            'icon' =>'eicon-info-circle',
                        ]
                    ],
                ]
            );

            $this->add_control(
                'titleimage',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'section_icon_type' => 'img',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'iconimagesize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'section_icon_type' => 'img',
                    ]
                ]
            );

            $this->add_control(
                'titleicon',
                [
                    'label' =>__('Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-pencil-alt',
                        'library'=>'solid',
                    ],
                    'condition' => [
                        'section_icon_type' => 'icon',
                    ]
                ]
            );

            $this->add_control(
                'image_position',
                [
                    'label' => __( 'Image Position', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => __( 'Top', 'htmega-addons' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'eicon-align-center-h',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'htmega-addons' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'bottom',
                    'condition' => [
                        //'section_icon_type' => 'img',
                        'titlestyle!' => 'one',
                    ]
                ]
            );

        $this->end_controls_section();

        // Title Option start
        $this->start_controls_section(
            'section_title_setting',
            [
                'label' => esc_html__( 'Title Setting', 'htmega-addons' ),
                'condition' => [
                    'section_title_text!' => '',
                ]
            ]
        );

            $this->add_control(
                'section_link',
                [
                    'label' => __( 'Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'default' => [
                        'url' => '',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'section_title_tag',
                [
                    'label' => __( 'HTML Tag', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'h2',
                ]
            );

        $this->end_controls_section(); // Subtitle Option end

        // Subtitle Option start
        $this->start_controls_section(
            'section_subtitle_setting',
            [
                'label' => esc_html__( 'Sub Title Setting', 'htmega-addons' ),
                'condition' => [
                    'section_subtitle_text!' => '',
                ]
            ]
        );

            $this->add_control(
                'subtitle_position',
                [
                    'label' => __( 'Position', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => __( 'Top', 'htmega-addons' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'htmega-addons' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'bottom',
                ]
            );

            $this->add_control(
                'sectionsubtitle_link',
                [
                    'label' => __( 'Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'default' => [
                        'url' => '',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'section_subtitle_tag',
                [
                    'label' => __( 'HTML Tag', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => htmega_html_tag_lists(),
                    'default' => 'p',
                ]
            );

        $this->end_controls_section(); // Subtitle Option end


        // Advance Title Option start
        $this->start_controls_section(
            'section_advancetitle_setting',
            [
                'label' => esc_html__( 'Advance Title Setting', 'htmega-addons' ),
                'condition' => [
                    'section_advancetitle_text!' => '',
                ]
            ]
        );

            $this->add_responsive_control(
                'section_advancetitle_x_position',
                [
                    'label'   => esc_html__( 'X Offset', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'tablet_default' => [
                        'size' => 0,
                    ],
                    'mobile_default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -800,
                            'max' => 800,
                        ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'section_advancetitle_y_position',
                [
                    'label'   => esc_html__( 'Y Offset', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'tablet_default' => [
                        'size' => 0,
                    ],
                    'mobile_default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -800,
                            'max' => 800,
                        ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'section_advancetitle_rotate',
                [
                    'label'   => esc_html__( 'Rotate', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'tablet_default' => [
                        'size' => 0,
                    ],
                    'mobile_default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min'  => -150,
                            'max'  => 150,
                            'step' => 2,
                        ],
                    ],
                    'selectors' => [
                        // '(desktop){{WRAPPER}} .section-advancetitle-txt' => 'transform: translate({{section_advancetitle_x_position.SIZE}}px, {{section_advancetitle_y_position.SIZE}}px) rotate({{SIZE}}deg);',
                        // '(tablet){{WRAPPER}} .section-advancetitle-txt' => 'transform: translate({{section_advancetitle_x_position_tablet.SIZE}}px, {{section_advancetitle_y_position_tablet.SIZE}}px) rotate({{SIZE}}deg);',
                        // '(mobile){{WRAPPER}} .section-advancetitle-txt' => 'transform: translate({{section_advancetitle_x_position_mobile.SIZE}}px, {{section_advancetitle_y_position_mobile.SIZE}}px) rotate({{SIZE}}deg);',
                    ],
                ]
            );

        $this->end_controls_section(); //  Advance Title Option end

        // Style tab section
        $this->start_controls_section(
            'section_area_style',
            [
                'label' => __( 'Section style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-section-title',
                ]
            );

            $this->add_responsive_control(
                'sectionmargin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'sectionpadding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'aligntitle',
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
                        '{{WRAPPER}} .htmega-section-title' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'prefix_class' => 'htmega-title-align%s-',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'section_title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-section-title',
                ]
            );

            $this->add_control(
                'before_after_title_color',
                [
                    'label' => __( 'Before And After Border Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#412e51',
                    'selectors' => [
                        '{{WRAPPER}} .title-style-two .section-title-txt::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .title-style-two .section-title-txt::after' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'titlestyle' => 'two',
                    ]
                ]
            );

            $this->add_control(
                'title_separaotr_color',
                [
                    'label' => __( 'Title Separator Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#412e51',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title span.htmega-title-sperator' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-section-title span.htmega-title-sperator::before' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'titlestyle' => array('three', 'four'),
                    ],
                    'separator'=>'before',
                ]
            );

            $this->add_responsive_control(
                'title_separaotr_margin',
                [
                    'label' => __( 'Separator Specing', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title span.htmega-title-sperator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'titlestyle' => 'four',
                    ]
                ]
            );

        $this->end_controls_section();

        // Style tab tite section
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'section_title_text!' => '',
                ]
            ]
        );

            $this->add_control(
                'sectitle-heading',
                [
                    'label' => __( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#23252a',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-title-txt' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'titletypography',
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-title-txt',
                ]
            );

            $this->add_control(
                'sectitle-heading_span',
                [
                    'label' => __( 'Highlight Title Color', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'title_color_span',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#23252a',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-title-txt span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography_span',
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-title-txt span',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'title_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-title-txt',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'title_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-title-txt' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'titlenmargin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-title-txt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'titlepadding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-title-txt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );


            $this->add_responsive_control(
                'titledisplay',
                [
                    'label' => __( 'Display', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'block' => [
                            'title' => __( 'Block', 'htmega-addons' ),
                            'icon' => 'eicon-device-desktop',
                        ],
                        'inline-block' => [
                            'title' => __( 'Inline block', 'htmega-addons' ),
                            'icon' => 'eicon-slider-push',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-title-txt' => 'display: {{VALUE}};',
                    ],
                    'default' => 'inline-block',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'titlebackground',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-title-txt',
                ]
            );

            $this->add_control(
                'htmega_section_title_font_backround',
                [
                    'label' => esc_html__( 'Use Backround for Text', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' =>'before',
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-title-txt: ',
                ]
            );

            $this->add_control(
                'htmega_section_title_font_stroke',
                [
                    'label' => esc_html__( 'Stroke', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' =>'before',
                ]
            );
            $this->add_control(
                'htmega_section_title_font_stroke_color',
                [
                    'label' => __( 'Stroke Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#838383',
                    'selectors' => [
                        '{{WRAPPER}} .section-title-txt' => '-webkit-text-stroke-color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_section_title_font_stroke' => 'yes',
                    ]
                ]
            );
            $this->add_control(
                'htmega_section_title_font_stroke_widht',
                [
                    'label' => __( 'Stroke Fill Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .section-title-txt' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' =>[
                        'htmega_section_title_font_stroke' => 'yes',
                    ]
                ]
            ); 

            $this->add_control(
                'htmega_section_litle_border_both',
                [
                    'label' => esc_html__( 'Befor, After Border', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' =>'before',
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-title-txt: ',
                    'condition' => [
                        'titlestyle' => 'two',
                    ]
                ]
            );

            $this->add_responsive_control(
                'htmega_section_litle_border_both_width',
                [
                    'label'   => esc_html__( 'Border Width', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],

                    'selectors' => [
                        '{{WRAPPER}} .section-title-txt::before' => 'width: {{SIZE}}px;',
                        '{{WRAPPER}} .section-title-txt::after' => 'width: {{SIZE}}px;',
                    ],
                    'condition' => [
                        'htmega_section_litle_border_both' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'htmega_section_litle_border_both_hight',
                [
                    'label' => esc_html__( 'Border Height', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 15,
                    'step' => 1,
                    'default' => 2,
                    'selectors' => [
                        '{{WRAPPER}} .section-title-txt::before, {{WRAPPER}} .section-title-txt::after' => 'height: {{SIZE}}px;',
                    ],
                    'condition' => [
                        'htmega_section_litle_border_both' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section(); // Title style end

        // Style tab sub tite section
        $this->start_controls_section(
            'section_subtitle_style',
            [
                'label' => __( 'Sub Title style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'section_subtitle_text!' => '',
                ]
            ]
        );

            $this->add_control(
                'subtitle_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#23252a',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-subtitle-txt' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'subtitletypography',
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-subtitle-txt',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'subtitle_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-subtitle-txt',
                ]
            );

            $this->add_responsive_control(
                'subtitle_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-subtitle-txt' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'subtitlenmargin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-subtitle-txt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'subtitlepadding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-subtitle-txt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'subtitledisplay',
                [
                    'label' => __( 'Display', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'block' => [
                            'title' => __( 'Block', 'htmega-addons' ),
                            'icon' => 'eicon-device-desktop',
                        ],
                        'inline-block' => [
                            'title' => __( 'Inline block', 'htmega-addons' ),
                            'icon' => 'eicon-slider-push',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-subtitle-txt' => 'display: {{VALUE}};',
                    ],
                    'default' => 'block',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'subtitlebackground',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-subtitle-txt',
                ]
            );

            $this->add_control(
                'htmega_section_sublitle_border_both',
                [
                    'label' => esc_html__( 'Befor, After Border', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' =>'before',
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-title-txt: ',
                ]
            );

            $this->add_responsive_control(
                'htmega_section_sublitle_border_both_width',
                [
                    'label'   => esc_html__( 'Border Width', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        '{{WRAPPER}} .htmega_sub_title_border_both::before' => 'width: {{SIZE}}px;',
                        '{{WRAPPER}} .htmega_sub_title_border_both::after' => 'width: {{SIZE}}px;',
                    ],
                    'condition' => [
                        'htmega_section_sublitle_border_both' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'htmega_section_sublitle_border_both_hight',
                [
                    'label' => esc_html__( 'Border Height', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 15,
                    'step' => 1,
                    'default' => 2,
                    'selectors' => [
                        '{{WRAPPER}} .htmega_sub_title_border_both::before, {{WRAPPER}} .htmega_sub_title_border_both::after' => 'height: {{SIZE}}px;',
                    ],
                    'condition' => [
                        'htmega_section_sublitle_border_both' => 'yes',
                    ]
                ]
            );

            
        $this->end_controls_section();

        // Style tab advance tite section
        $this->start_controls_section(
            'section_advancetitle_style',
            [
                'label' => __( 'Advance Title style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'section_advancetitle_text!' => '',
                ]
            ]
        );

            $this->add_control(
                'advancetitle_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#f1f1f1',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'advancetitletypography',
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt',
                ]
            );

            $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'advance_text_shadow',
                    'label' => __( 'Text Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'advance_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'advancetitle_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'advancetitle_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'advancetitlepadding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'advancetitlebackground',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'advanced_title_opacity',
                [
                    'label' => __( 'Opacity', 'htmega-addons' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min'  => 0.05,
                            'max'  => 1,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-section-title .section-advancetitle-txt' => 'opacity: {{SIZE}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'htmega_section_advance_title_stroke',
                [
                    'label' => esc_html__( 'Stroke', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' =>'before',
                ]
            );
            $this->add_control(
                'htmega_section_advance_title_stroke_color',
                [
                    'label' => __( 'Stroke Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#838383',
                    'selectors' => [
                        '{{WRAPPER}} .section-advancetitle-txt' => '-webkit-text-stroke-color: {{VALUE}};',
                    ],
                    'condition' =>[
                        'htmega_section_advance_title_stroke' => 'yes',
                    ]
                ]
            );
            $this->add_control(
                'htmega_section_advance_title_stroke_widht',
                [
                    'label' => __( 'Stroke Fill Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .section-advancetitle-txt' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' =>[
                        'htmega_section_advance_title_stroke' => 'yes',
                    ]
                ]
            ); 

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();
        $titlestyle = $settings['titlestyle'];
        $sectionid = "htmega-". $this-> get_id();

        $this->add_render_attribute( 'section_area_attr', 'class', "$sectionid htmega-section-title" );
        $this->add_render_attribute( 'section_area_attr', 'class', 'htmega-subtitle-position-'. $settings['subtitle_position'] );

        $image_pasition_parent = ($settings['subtitle_position'] == 'bottom') ? 'htmega-section-title-order-parent' : ''; 
        
        $this->add_render_attribute( 'section_area_attr', 'class', 'title-style-'. $settings['titlestyle'].' '. $image_pasition_parent .' image-' .$settings['image_position']);

        $this->add_render_attribute( 'section_title_text', 'class', 'section-title-txt' );

        $subTitleBoderBoth = '';
        if($settings['htmega_section_sublitle_border_both'] == 'yes'){
            $subTitleBoderBoth = ' htmega_sub_title_border_both';
        }
        $this->add_render_attribute( 'section_subtitle_attr', 'class', 'section-subtitle-txt'.$subTitleBoderBoth );

        $this->add_render_attribute( 'section_advancetitle_attr', 'class', 'section-advancetitle-txt' );

        $title      = ! empty( $settings['section_title_text'] ) ? $settings['section_title_text'] : '';
        $subtitle   = ! empty( $settings['section_subtitle_text'] ) ? $settings['section_subtitle_text'] : '';

        $this->add_render_attribute( 'title_image_pasition', 'class', 'htmaga-section-title-image-position-'. $settings['image_position'] );
        $title_image_pasition = $this->get_render_attribute_string( 'title_image_pasition' );

        // URL Generate Title
        if ( ! empty( $settings['section_link']['url'] ) ) {
            $this->add_render_attribute( 'url', 'href', $settings['section_link']['url'] );

            if ( $settings['section_link']['is_external'] ) {
                $this->add_render_attribute( 'url', 'target', '_blank' );
            }

            if ( ! empty( $settings['section_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'url', 'rel', 'nofollow' );
            }

            $title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
        }

        // URL Generate Sub Title
        if ( ! empty( $settings['sectionsubtitle_link']['url'] ) ) {
            $this->add_render_attribute( 'suburl', 'href', $settings['sectionsubtitle_link']['url'] );

            if ( $settings['sectionsubtitle_link']['is_external'] ) {
                $this->add_render_attribute( 'suburl', 'target', '_blank' );
            }

            if ( ! empty( $settings['sectionsubtitle_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'suburl', 'rel', 'nofollow' );
            }

            $subtitle = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'suburl' ), $subtitle );
        }

        $title_tag = htmega_validate_html_tag( $settings['section_title_tag'] );
        $sub_title_tag = htmega_validate_html_tag( $settings['section_subtitle_tag'] );

        ?>
            <div <?php echo $this->get_render_attribute_string( 'section_area_attr' ); ?>>
                <?php
                    if( $titlestyle == 'one' ){

                        if( !empty($title) ){
                            echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $title_tag, $this->get_render_attribute_string( 'section_title_text' ), $title );
                        }
                        if( !empty( $subtitle ) ){
                            echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $sub_title_tag, $this->get_render_attribute_string( 'section_subtitle_attr' ), $subtitle );
                        }

                        if( !empty( $settings['titleimage'] ) ){
                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'iconimagesize', 'titleimage' );
                        }

                        if( !empty( $settings['titleicon']['value'] ) ){
                            echo HTMega_Icon_manager::render_icon( $settings['titleicon'], [ 'aria-hidden' => 'true' ] );
                        }

                    }else{

                        if( !empty( $title ) ){
                            echo sprintf( '<%1$s %2$s>%3$s</%1$s>%4$s', $title_tag, $this->get_render_attribute_string( 'section_title_text' ), $title, '<div class=htmega-title-sperator-sec><span class="htmega-title-sperator">&nbsp;</span></div>' );
                        }
                        if( !empty($settings['section_advancetitle_text']) && $titlestyle == 'five' ){
                            echo sprintf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( 'section_advancetitle_attr' ), $settings['section_advancetitle_text'] );
                        }
                        if( !empty( $subtitle ) ){
                            echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $sub_title_tag, $this->get_render_attribute_string( 'section_subtitle_attr' ), $subtitle );
                        }

                        if( !empty( $settings['titleimage'] ) ){
                            echo "<div {$title_image_pasition}>". Group_Control_Image_Size::get_attachment_image_html( $settings, 'iconimagesize', 'titleimage' ).'</div>';
                        }
                        if( !empty( $settings['titleicon']['value'] ) ){
                            echo "<div {$title_image_pasition}>" .HTMega_Icon_manager::render_icon( $settings['titleicon'], [ 'aria-hidden' => 'true' ] ). '</div>';
                        }
                        

                    }
                ?>
            </div>

            
        <?php
        if($settings['htmega_section_title_font_backround'] == 'yes' || !empty($settings['section_advancetitle_rotate']['size']) ||  !empty($settings['section_advancetitle_x_position']['size']) || !empty($settings['section_advancetitle_y_position']['size'])){ 
            $css_print = "";
            if($settings['htmega_section_title_font_backround'] == 'yes'){

                $css_print.= ".{$sectionid}.htmega-section-title .section-title-txt{ -webkit-background-clip: text; -webkit-text-fill-color: transparent;}";
    
            }

            $x_position = $settings['section_advancetitle_x_position']['size'] ? $settings['section_advancetitle_x_position']['size'] : 0;
            $y_position = $settings['section_advancetitle_y_position']['size'] ? $settings['section_advancetitle_y_position']['size'] : 0;
            $rotate = $settings['section_advancetitle_rotate']['size'] ? $settings['section_advancetitle_rotate']['size'] : 0;
            
            if(!empty($rotate) ||  !empty($x_position) || !empty($y_position)){
                $css_print.= ".{$sectionid} .section-advancetitle-txt { transform: translate({$x_position}px, {$y_position}px) rotate({$rotate}deg);}";

            }

            echo "<style>";
                esc_html_e($css_print);
            echo "</style>";
        }
    }
}

