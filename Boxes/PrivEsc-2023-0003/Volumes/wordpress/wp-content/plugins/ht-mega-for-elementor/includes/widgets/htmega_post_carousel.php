<?php
namespace Elementor;

// Elementor Classes
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Post_Carousel extends Widget_Base {

    public function get_name() {
        return 'htmega-postcarousel-addons';
    }
    
    public function get_title() {
        return __( 'Post carousel', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-posts-carousel';
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

    public function get_keywords() {
        return [ 'post slider', 'slider widget','carousel','post carousel','htmega','ht mega' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/post-widgets/post-carousel-widget/';
	}

    protected function register_controls() {

        $this->start_controls_section(
            'post_carousel_content',
            [
                'label' => __( 'Post carousel', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'post_carousel_style',
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

            $this->add_control(
                'slider_on',
                [
                    'label'         => __( 'Carousel', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'On', 'htmega-addons' ),
                    'label_off'     => __( 'Off', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
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
                    'label' => esc_html__( 'Date', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

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
                    'label' => __( 'Title Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'default' => 5,
                    'condition'=>[
                        'show_title'=>'yes',
                    ]
                ]
            );
            $this->add_control(
                'show_content',
                [
                    'label' => esc_html__( 'Show Content', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            $this->add_control(
                'content_type',
                [
                    'label' => esc_html__( 'Content Source', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'content',
                    'options' => [
                        'content'          => esc_html__('Content','htmega-addons'),
                        'excerpt'            => esc_html__('Excerpt','htmega-addons'),
                    ],
                    'condition'=>[
                        'show_content'=>'yes',
                    ]
                ]
            );
            $this->add_control(
                'content_length',
                [
                    'label' => __( 'Content Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'default' => 20,
                    'condition'=>[
                        'show_content'=>'yes',
                        'content_type'=>'content',
                    ]
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
                    'label_block'=>true,
                    'condition'=>[
                        'show_read_more_btn'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'show_author',
                [
                    'label' => esc_html__( 'Author', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

        $this->end_controls_section(); // Content Option End

        // Carousel setting
        $this->start_controls_section(
            'slider_option',
            [
                'label' => esc_html__( 'Carousel Option', 'htmega-addons' ),
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
                    'default' => 15,
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper' => 'margin: 0 -{{VALUE}}px',
                        '{{WRAPPER}} .post-carousel-wrapper .slick-slide' => 'margin: 0 {{VALUE}}px',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );
            $this->add_control(
                'equal_height_column',
                [
                    'label' => esc_html__( 'Equal Height Column', 'htmega-addons' ),
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
                        'value'=>'fas fa-angle-left',
                        'library' => 'solid',
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
                    'default' => 'fas fa-angle-right',
                    'default' => [
                        'value'=>'fas fa-angle-right',
                        'library' => 'solid',
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
                'slloop',
                [
                    'label' => esc_html__( 'Slide Loop', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
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
        // Style Slide Image
        $this->start_controls_section(
            'post_carousel_image_style',
            [
                'label' => __( 'Carousel Item Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [  'name' => 'htmega_post_image',
                    'default' => 'htmega_size_396x360',
                    'separator' => 'before',
                ]
            ); 
            $this->add_responsive_control(
                'slider_height',
                [
                    'label' => __( 'Item Min Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'description' =>'Custom  Item height(px)',
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 1024,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .thumb' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );            
            $this->add_responsive_control(
                'items_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .thumb a img,{{WRAPPER}} .htmega-single-post-slide .thumb a:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'post_carousel_image_overlay_heading',
                [
                    'label' => __( 'Image Overlay', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'post_slider_image_overlay',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-single-post-slide .thumb a:after',
                    'separator' => 'after',
                ]
            );
            $this->add_control(
                'image_overlay_heading_hover',
                [
                    'label' => __( 'Image Hover Overlay', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'=>[
                        'post_carousel_style' =>'3', 
                    ]
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'post_slider_image_overlay_hover',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-single-post-slide .thumb a:before',
                    'separator' => 'after',
                    'condition'=>[
                        'post_carousel_style' =>'3', 
                    ]
                ]
            );
 
            // Content box style start
            $this->add_control(
                'content_box_heading',
                [
                    'label' => __( 'Content Box Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );   
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'content_box_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-1 .content .post-inner, {{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content .post-inner,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-4 .content,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-5 .content .post-inner',
                    'separator' => 'before',
                    'condition'=>[
                        'post_carousel_style!' =>'3', 
                    ]
                ]
            );
            // Content box hover bg 
            $this->add_control(
                'content_box_hover_heading',
                [
                    'label' => __( 'Content Box Hover BG', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'=>[
                        'post_carousel_style' =>'1',
                    ]
                ]
            );   
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'content_box_background_hover',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-1:hover .content .post-inner',
                    'condition'=>[
                        'post_carousel_style' =>'1',
                    ]
                ]
            );
            $this->add_responsive_control(
                'content_box_margin', 
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-postslider-layout-5 .content,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-4 .content,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-5 .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                    'condition'=>[
                        'post_carousel_style!' =>['1','3'], 
                    ]
                ]
            );
            $this->add_responsive_control(
                'content_box_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-1 .content .post-inner,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content .post-inner,{{WRAPPER}} .htmega-postslider-layout-3 .content .post-inner, {{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-4 .content, {{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-5 .content .post-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'content_box_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-1 .content .post-inner,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-5 .content .post-inner',
                    'separator' => 'before',
                    'condition'=>[
                        'post_carousel_style!' =>['3','4','2'], 
                    ]
                ]
            );
            $this->add_responsive_control(
                'content_box_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-1 .content .post-inner,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content .post-inner,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-4 .content,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-5 .content .post-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                    'condition'=>[
                        'post_carousel_style!' =>'3', 
                    ]
                ]
            );

            $this->add_responsive_control(
                'post_slider_content_box_align',
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
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-1 .content .post-inner,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content .post-inner,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-4 .content .post-inner, {{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-5 .content .post-inner, {{WRAPPER}} .htmega-postslider-layout-3 .content' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'content_boxshadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content .post-inner,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-4 .content,{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-5 .content .post-inner',
                    'condition'=>[
                        'post_carousel_style!' =>['1','3'], 
                    ]
                ]
            );

        $this->end_controls_section();
         //Slider Image Style end
        
        $this->start_controls_section(
            'post_slider_content_border',
            [
                'label' => __( 'Content Box Bottom Border', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'post_carousel_style' => '2',
                ]
            ]
        );
        $this->add_control(
            'border_type',
            [
                'label' => esc_html__( 'Border Type', 'htmega-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'htmega-solid',
                'options' => [
                    'htmega-solid'          => esc_html__('Solid Color','htmega-addons'),
                    'htmega-gradient'       => esc_html__('Gradient Color','htmega-addons'),
                ],
                
            ]
        );

            $this->add_control(
                'post_slider_content_border_color',
                [
                    'label' => __( 'Border Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#b5b5b5',
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content .post-inner' => 'border-color: {{VALUE}}',
                    ],
                    'condition' => [
                        'border_type' => 'htmega-solid',
                    ]
                ]
            );

            $this->add_control(
                'post_slider_content_hover_border_color',
                [
                    'label' => __( 'Hover Border Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#0056ff',
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2:hover .content .post-inner' => 'border-color: {{VALUE}}',
                    ],
                    'condition' => [
                        'border_type' => 'htmega-solid',
                    ]
                ]
            );
            $this->add_control(
                'border_gradient_bg_title',
                [
                    'label' => __( 'Border Gradient Color', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'border_type' => 'htmega-gradient',
                    ]
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'border_gradient_bg',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2 .content .post-inner:after',
                    'condition' => [
                        'border_type' => 'htmega-gradient',
                    ]
                ]
            );
            $this->add_control(
                'border_gradient_bg_title_hover',
                [
                    'label' => __( 'Border Hover Gradient Color', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'border_type' => 'htmega-gradient',
                    ]
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'border_gradient_bg_hover',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-2:hover .content .post-inner:after',
                    'condition' => [
                        'border_type' => 'htmega-gradient',
                    ]
                ]
            );
            
        $this->end_controls_section(); // Slider Option end

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
                    'default'=>'#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2 a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'title_color_hover',
                [
                    'label' => __( 'Hover Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2 a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner h2' => 'text-align: {{VALUE}};',
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
                    'default'=>'#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p',
                ]
            );

            $this->add_responsive_control(
                'content_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide .content .post-inner p' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        //style thumbnail opacity section
        $this->start_controls_section(
            'post_thumbnail_opacity_section',
            [
                'label' => __( 'Thumbnail', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'post_carousel_style'=>'3',
                ]
            ]
        );

        $this->add_control(
            'post_thumbnail_opacity_color',
            [
                'label' => esc_html__( 'Opacity Color', 'htmega-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-postslider-layout-3 .thumb:before' => 'content: ""; background: {{VALUE}}; width: 100%; height: 100%; position: absolute; z-index:1;',
                ]
            ]
        );

        $this->add_control(
            'post_thumbnail_opacity_opacity',
            [
                'label'   => __( 'Opacity (%)', 'htmega-addons' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.8,
                ],
                'range' => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-postslider-layout-3 .thumb:before' => 'opacity: {{SIZE}};',
                ]
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
                                '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'category_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'category_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'category_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a',
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
                                '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'category_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-single-post-slide .content ul.post-category li a:hover',
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
            ]
        );
            $this->add_control(
                'meta_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide ul.meta li' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .content .post-inner ul.meta li a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'meta_color_icon',
                [
                    'label' => __( 'Icon Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide ul.meta li i' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .content .post-inner ul.meta li a i' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'meta_color_hover',
                [
                    'label' => __( 'Meta Hover Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .content .post-inner ul.meta li a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'meta_separator_color',
                [
                    'label' => __( 'Separator Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-postslider-layout-5 .content .post-inner ul.meta li::before' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'post_carousel_style' => '5',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'meta_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide ul.meta li',
                ]
            );

            $this->add_responsive_control(
                'meta_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide ul.meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide ul.meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .htmega-single-post-slide ul.meta' => 'text-align: {{VALUE}};',
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
                            'default'=>'#464545',
                            'selectors' => [
                                '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn' => 'color: {{VALUE}}; border-bottom: 1px solid {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'readmore_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'readmore_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'readmore_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'read_more_alignment',
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
                                '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn' => 'text-align: {{VALUE}};',
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
                            'default'=>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'readmore_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'readmore_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'readmore_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .post-carousel-wrapper .htmega-single-post-slide .post-btn a.readmore-btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Slider arrow style start
        $this->start_controls_section(
            'slider_arrow_style',
            [
                'label'     => __( 'Arrow', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
        
            $this->start_controls_tabs( 'slider_arrow_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'slider_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'slider_arrow_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#00282a',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_fontsize',
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
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'slider_arrow_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'slider_arrow_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_height',
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
                                'size' => 40,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_width',
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
                                'size' => 46,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );
                    $this->add_control(
                        'slider_arrow_postion_option',
                        [
                            'label' => __( 'Arrow Position', 'htmega-addons' ),
                            'type' => Controls_Manager::SELECT,
                            'options' => [
                                'htmega-top-right-arrow'   => __( 'Top Right', 'htmega-addons' ),
                                'htmega-bottom-right-arrow'   => __( 'Bottom Right', 'htmega-addons' ),
                                'htmega-verticle-center-arrow'   => __( 'Vertical Center', 'htmega-addons' ),
                                'htmega-bottom-center-arrow'   => __( 'Bottom Center', 'htmega-addons' ),
                            ],
                            'default' =>'htmega-verticle-center-arrow'

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
                                    'min' => -1000,
                                    'max' => 1000,
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
                                '{{WRAPPER}} .post-carousel-wrapper button.slick-arrow' => 'left: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .post-carousel-wrapper button.htmega-carosul-next.slick-arrow' => 'right: {{SIZE}}{{UNIT}}; left:auto;',
                            ],
                            'condition'=>[
                                'slider_arrow_postion_option' => 'htmega-verticle-center-arrow',
                            ]
                        ]
                    );
                    $this->add_responsive_control(
                        'slider_arrow_horizontal_postion_prev',
                        [
                            'label' => __( 'Horizontal Position Prev', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => -1000,
                                    'max' => 1000,
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
                                '{{WRAPPER}} .post-carousel-wrapper.htmega-top-right-arrow button.slick-arrow,{{WRAPPER}} .post-carousel-wrapper.htmega-bottom-right-arrow button.slick-arrow' => 'right: {{SIZE}}{{UNIT}}; left:auto;',
                                '{{WRAPPER}} .post-carousel-wrapper.htmega-bottom-center-arrow button.slick-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',

                            ],
                            'condition'=>[
                                'slider_arrow_postion_option!' => 'htmega-verticle-center-arrow',
                            ]
                        ]
                    );
                    $this->add_responsive_control(
                        'slider_arrow_horizontal_postion_right',
                        [
                            'label' => __( 'Horizontal Position Next', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => -1000,
                                    'max' => 1000,
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
                                '{{WRAPPER}} .post-carousel-wrapper.htmega-top-right-arrow button.htmega-carosul-next.slick-arrow,{{WRAPPER}} .post-carousel-wrapper.htmega-bottom-right-arrow button.htmega-carosul-next.slick-arrow' => 'right: {{SIZE}}{{UNIT}}; left:auto;',
                                '{{WRAPPER}} .post-carousel-wrapper.htmega-bottom-center-arrow button.htmega-carosul-next.slick-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; left:auto;',
                            ],
                            'condition'=>[
                                'slider_arrow_postion_option!' => 'htmega-verticle-center-arrow',
                            ]
                        ]
                    );


                    $this->add_responsive_control(
                        'slider_arrow_verticle_postion',
                        [
                            'label' => __( 'Vertical Position', 'htmega-addons' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => -1000,
                                    'max' => 1000,
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
                                '{{WRAPPER}} .post-carousel-wrapper button.slick-arrow' => 'margin-top: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );                    
                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'slider_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'slider_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#00282a',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover svg path' => 'fill: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'slider_arrow_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'slider_arrow_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_arrow_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation button.slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style Slider arrow style end

        // Style Pagination button tab section
        $this->start_controls_section(
            'post_slider_pagination_style_section',
            [
                'label' => __( 'Pagination', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'slider_on' => 'yes',
                    'sldots'=>'yes',
                   
                ]
            ]
        );
            
            $this->start_controls_tabs('pagination_style_tabs');

                $this->start_controls_tab(
                    'pagination_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_responsive_control(
                        'slider_pagination_height',
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
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'slider_pagination_width',
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
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'pagination_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button',
                        ]
                    );

                    $this->add_responsive_control(
                        'pagination_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'pagination_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button',
                        ]
                    );

                    $this->add_responsive_control(
                        'pagination_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'pagination_style_active_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'pagination_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li:hover button, {{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'pagination_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-carousel-activation .slick-dots li:hover button, {{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active button',
                        ]
                    );

                    $this->add_responsive_control(
                        'pagination_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li.slick-active button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .htmega-carousel-activation .slick-dots li:hover button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings  = $this->get_settings_for_display();
        $sectionid = "sid". $this-> get_id();
        $post_slider_image_overlay_hover = $settings['post_slider_image_overlay_hover_background'];
        $htmega_post_image  =  $this->get_settings_for_display('htmega_post_image_size');
        $post_type = $settings['carousel_post_type'];
        
        if( 'post'== $post_type ){
            $post_categorys = $settings['carousel_categories'];
        } else if( 'product'== $post_type ){
            $post_categorys = $settings['carousel_prod_categories'];
        }else {
            $post_categorys = $settings[ $post_type.'_post_category'];
        }

        $post_author = $settings['post_author'];
        $exclude_posts = $settings['exclude_posts'];
        $orderby            = $this->get_settings_for_display('orderby');
        $postorder          = $this->get_settings_for_display('postorder');

        $this->add_render_attribute( 'htmega_post_carousel', 'class', 'post-carousel-wrapper htmega-postcarousel-layout-'.$settings['post_carousel_style'].' '. $settings['slider_arrow_postion_option'].' '.$sectionid);

        $this->add_render_attribute( 'htmega_post_slider_item_attr', 'class', 'htmega-single-post-slide htmega-postslider-layout-'.$settings['post_carousel_style'] );
        // Slider options
        if( $settings['slider_on'] == 'yes' ){

            $direction = is_rtl() ? 'rtl' : 'ltr';
            $this->add_render_attribute( 'htmega_post_slider_attr', 'dir', $direction );
            
            $this->add_render_attribute( 'htmega_post_slider_attr', 'class', 'htmega-carousel-activation' );

            $slider_settings = [
                'sectionid' => $sectionid,
                'arrows' => ('yes' === $settings['slarrows']),
                'arrow_prev_txt' => HTMega_Icon_manager::render_icon( $settings['slprevicon'], [ 'aria-hidden' => 'true' ] ),
                'arrow_next_txt' => HTMega_Icon_manager::render_icon( $settings['slnexticon'], [ 'aria-hidden' => 'true' ] ),
                'dots' => ('yes' === $settings['sldots']),
                'autoplay' => ('yes' === $settings['slautolay']),
                'autoplay_speed' => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'slloop' => ('yes' === $settings['slloop']) ? true:false,
                'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
                'center_mode' => ( 'yes' === $settings['slcentermode']),
                'center_padding' => absint($settings['slcenterpadding']),
                'equal_height_column' => ('yes' === $settings['equal_height_column']),
                'equal_height_column_class' => '.post-inner',
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

            $this->add_render_attribute( 'htmega_post_slider_attr', 'data-settings', wp_json_encode( $slider_settings ) );
        }

         // Post query
         $args = array(
            'post_type'             => $post_type,
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => !empty( $settings['post_limit'] ) ? (int)$settings['post_limit'] : 3,
        );

        if (  !empty( $post_categorys ) ) {

            $category_name =  get_object_taxonomies($post_type);
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

        $carousel_post = new \WP_Query( $args );

        $s_display_none = ( 'yes' == $settings['slider_on'] ) ? ' style="display:none;"':'';
        ?>
            <div <?php echo $this->get_render_attribute_string( 'htmega_post_carousel' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_attr' ).$s_display_none; ?>>

                    <?php
                if( $carousel_post->have_posts() ):
                    while( $carousel_post->have_posts() ): $carousel_post->the_post();
                            ?>

                        <?php if( $settings['post_carousel_style'] == 2 ): ?>
                            <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_item_attr' ); ?> >
                                <div class="thumb">
                                    <a href="<?php the_permalink();?>"><?php $this->htmega_render_loop_thumbnail( $htmega_post_image );?></a>
                                </div>
                                <?php $this->htmega_render_loop_content( 2,$carousel_post->ID ); ?>
                            </div>

                        <?php elseif( $settings['post_carousel_style'] == 4 ): ?>
                            <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_item_attr' ); ?> >
                                <div class="post-carousel-flex">
                                    <div class="thumb">
                                        <a href="<?php the_permalink();?>"><?php $this->htmega_render_loop_thumbnail( $htmega_post_image );?></a>
                                    </div>
                                    <?php $this->htmega_render_loop_content(null,$carousel_post->ID); ?>
                                </div>
                            </div>

                        <?php elseif( $settings['post_carousel_style'] == 5 ): ?>
                            <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_item_attr' ); ?> >
                                <div class="thumb">
                                    <a href="<?php the_permalink();?>"><?php $this->htmega_render_loop_thumbnail( $htmega_post_image );?></a>
                                </div>
                                <?php $this->htmega_render_loop_content( 3,$carousel_post->ID ); ?>
                            </div>

                        <?php else:?>
                            <div <?php echo $this->get_render_attribute_string( 'htmega_post_slider_item_attr' ); ?> >
                                <div class="thumb">
                                    <a href="<?php the_permalink();?>"><?php $this->htmega_render_loop_thumbnail( $htmega_post_image  ); ?></a>
                                </div>
                                <?php $this->htmega_render_loop_content(null,$carousel_post->ID); ?>
                            </div>
                        <?php endif;?>

                        <?php 
                    endwhile; wp_reset_postdata(); wp_reset_query(); 

                else:
                    echo "<div class='htmega-error-notice'>".esc_html__('There are no posts in this query','htmega-addons')."</div>";
                endif; ?>

                </div>
            </div>
            
            <?php
     if( 'null'!= $post_slider_image_overlay_hover && ''!= $post_slider_image_overlay_hover  ){
        $htmega_print_css = "
            .{$sectionid} .htmega-single-post-slide:hover .thumb a:after{
                opacity: 0;
              }";
             ?> 
              <style><?php echo esc_html( $htmega_print_css ); ?></style>
    <?php } ?>


        <?php

    }

    // Loop Content
    public function htmega_render_loop_content( $contetntstyle = NULL, $post_id = null  ){
        $settings   = $this->get_settings_for_display();
        $category_name =  get_object_taxonomies($settings['carousel_post_type']);
        ?>
            <div class="content <?php echo $settings['border_type']; ?>">
                <div class="post-inner">
                    <?php 
                    if( $settings['show_category'] == 'yes' ): 
                        if( $category_name ){
                            $get_terms = get_the_terms($post_id, $category_name[0] );
                            if($settings['carousel_post_type'] == 'product'){
                                $get_terms = get_the_terms($post_id, 'product_cat');
                            }
                            if($get_terms){
                                ?>
                                <ul class="post-category">
                                    <?php
                                    foreach ( $get_terms as $category ) {
                                        $term_link = get_term_link( $category );
                                        ?>
                                            <li><a href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name );?></a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            <?php 
                            }
                        }
                    endif; ?>

                    <?php
                        if( $contetntstyle == 3 ):
                            if( $settings['show_author'] == 'yes' || $settings['show_date'] == 'yes'): 
                    ?>
                            <ul class="meta">
                                <?php if( $settings['show_author'] == 'yes' ): ?>
                                    <li><i class="fa fa-user-circle"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                <?php endif; if( $settings['show_date'] == 'yes' ):?>
                                    <li><i class="fa fa-clock-o"></i><?php the_time('d F Y');?></li>
                                <?php endif; ?>
                            </ul>
                                
                    <?php endif; endif;?>

                    <?php if( $settings['show_title'] == 'yes' ):
                        
                        if ( 0 > $settings['title_length'] ) { ?>
                            <h2><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
                        <?php
                        } else { ?>
                            <h2><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $settings['title_length'], '' ); ?></a></h2>
                        <?php
                         }
                        
                        endif;?>

                    <?php
                        if( $contetntstyle != 2 ):
                            if( $contetntstyle != 3 ):
                            if( $settings['show_author'] == 'yes' || $settings['show_date'] == 'yes'): 
                    ?>
                                <ul class="meta">
                                    <?php if( $settings['show_author'] == 'yes' ): ?>
                                        <li><i class="fa fa-user-circle"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                    <?php endif; if( $settings['show_date'] == 'yes' ):?>
                                        <li><i class="fa fa-clock-o"></i><?php the_time('d F Y');?></li>
                                    <?php endif; ?>
                                </ul>

                    <?php endif; endif;endif;?>

                    <?php
                        if( $settings['show_content'] == 'yes' ){
                            if( $settings['content_type'] == 'excerpt' ){
                                echo '<p>'.get_the_excerpt().'</p>';
                            } else {
                                echo '<p>'.wp_trim_words( strip_shortcodes( get_the_content() ), $settings['content_length'], '' ).'</p>'; 
                            }
                        }
                    ?>

                    <?php
                        if( $contetntstyle == 2 ):
                            if( $settings['show_author'] == 'yes' || $settings['show_date'] == 'yes'): 
                    ?>
                            <ul class="meta">
                                <?php if( $settings['show_author'] == 'yes' ): ?>
                                    <li><i class="fa fa-user-circle"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                <?php endif; if( $settings['show_date'] == 'yes' ):?>
                                    <li><i class="fa fa-clock-o"></i><?php the_time('d F Y');?></li>
                                <?php endif; ?>
                            </ul>
                                
                    <?php endif; endif;?>

                    <?php if( $settings['show_read_more_btn'] == 'yes' ): ?>
                        <div class="post-btn">
                            <a class="readmore-btn" href="<?php the_permalink();?>"><?php echo htmega_kses_desc( $settings['read_more_txt'] );?></a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php
    }

    // Loop Thumbnails
    public function htmega_render_loop_thumbnail( $thumbnails_size = 'full' ){
        if ( has_post_thumbnail() ){
            the_post_thumbnail( $thumbnails_size ); 
        }else{
            echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
        }
    }
    // post query fields
    public function show_post_source(){

        $this->add_control(
            'carousel_post_type',
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
                    'carousel_categories',
                    [
                        'label' => esc_html__( 'Categories', 'htmega-addons' ),
                        'type' => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'multiple' => true,
                        'options' => htmega_get_taxonomies(),
                        'condition' =>[
                            'carousel_post_type' => 'post',
                            'include_by' => 'in_category',
                        ]
                    ]
                );
            } else if( 'product' == $post_key){
                $this->add_control(
                    'carousel_prod_categories',
                    [
                        'label' => esc_html__( 'Categories', 'htmega-addons' ),
                        'type' => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'multiple' => true,
                        'options' => htmega_get_taxonomies('product_cat'),
                        'condition' =>[
                            'carousel_post_type' => 'product',
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
                            'carousel_post_type' => $post_key,
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

            ]
        );
    }

}

