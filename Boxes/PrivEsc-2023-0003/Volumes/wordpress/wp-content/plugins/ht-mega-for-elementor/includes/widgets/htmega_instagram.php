<?php
namespace Elementor;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Instagram extends Widget_Base {

    public function get_name() {
        return 'htmega-instagram-addons';
    }
    
    public function get_title() {
        return __( 'Instagram', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-photo-library';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends() {
        return [
            'elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','slick','htmega-widgets',
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
            'instagram_content',
            [
                'label' => __( 'Instagram', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'instagram_style',
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

            // $this->add_control(
            //     'userid',
            //     [
            //         'label'         => __( 'Instagram user ID', 'htmega-addons' ),
            //         'type'          => Controls_Manager::TEXT,
            //         'placeholder'   => __( '6666969077', 'htmega-addons' ),
            //         'label_block'   =>true,
            //         'description'   => htmega_kses_desc( '(<a href="'.esc_url('https://codeofaninja.com/tools/find-instagram-user-id').'" target="_blank">Lookup your User ID</a>)', 'htmega-addons' ),
            //     ]
            // );
        
            $this->add_control(
                'access_token',
                [
                    'label'         => __( 'Instagram Access Token', 'htmega-addons' ),
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   =>true,
                    'description'   => htmega_kses_desc( '(<a href="'.esc_url('https://developers.facebook.com/docs/instagram-basic-display-api/getting-started').'" target="_blank">Lookup your Access Token</a>)', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'image_link_newtab',
                [
                    'label'         => __( 'Image link in new tab', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'separator'     => 'before',
                    'condition'=>[
                        'instagram_style'=>'3',
                    ],
                ]
            );

            $this->add_control(
                'delete_cache',
                [
                    'label'         => __( 'Delete existing caching data', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'cash_time_duration',
                [
                    'label' => __('Cache Time Duration', 'htmega-addons'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'minute'    => __('Minute', 'htmega-addons'),
                        'hour'      => __('Hour', 'htmega-addons'),
                        'day'       => __('Day', 'htmega-addons'),
                        'week'      => __('Week', 'htmega-addons'),
                        'month'     => __('Month', 'htmega-addons'),
                        'year'      => __('Year', 'htmega-addons'),
                    ],
                    'default' => 'day',
                    'condition'=>[
                        'delete_cache!'=>'yes',
                    ],
                    'label_block'=>true,
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'limit',
                [
                    'label' => __( 'Item Limit', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 200,
                    'step' => 1,
                    'default' => 8,
                    'separator'=>'before',
                ]
            );

            $this->add_responsive_control(
                'instagram_column',
                [
                    'label' => __( 'Column', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'description'   => htmega_kses_desc( 'If the slider is off, Then it will work.', 'htmega-addons' ),
                    'prefix_class' => 'htmegainstagram-column%s-',
                    'default' => '4',
                    'required' => true,
                    'device_args' => [
                        Controls_Stack::RESPONSIVE_TABLET => [
                            'required' => false,
                        ],
                        Controls_Stack::RESPONSIVE_MOBILE => [
                            'required' => false,
                        ],
                    ],
                    'min_affected_device' => [
                        Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
                        Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
                    ],
                    'options' => [
                        '1'   => __( '1', 'htmega-addons' ),
                        '2'   => __( '2', 'htmega-addons' ),
                        '3'   => __( '3', 'htmega-addons' ),
                        '4'   => __( '4', 'htmega-addons' ),
                        '5'   => __( '5', 'htmega-addons' ),
                        '6'   => __( '6', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'show_caption',
                [
                    'label'         => __( 'Show Caption', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'htmega-addons' ),
                    'label_off'     => __( 'Hide', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $this->add_control(
                'show_light_box',
                [
                    'label'         => __( 'Show Light Box', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'htmega-addons' ),
                    'label_off'     => __( 'Hide', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $this->add_control(
                'slider_on',
                [
                    'label'         => __( 'Slider', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'On', 'htmega-addons' ),
                    'label_off'     => __( 'Off', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                ]
            );

            $this->add_control(
                'zoomicon_type',
                [
                    'label' => esc_html__('Zoom Icon Type','htmega-addons'),
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
                    'default' =>'img',
                    'condition' =>[
                        'show_light_box' =>'yes',
                    ],
                ]
            );

            $this->add_control(
                'zoom_image',
                [
                    'label' => __('Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        'show_light_box' =>'yes',
                        'zoomicon_type' => 'img',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'zoom_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                    'condition' => [
                        'show_light_box' =>'yes',
                        'zoomicon_type' => 'img',
                    ]
                ]
            );

            $this->add_control(
                'zoom_icon',
                [
                    'label' =>__('Zoom Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-plus',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'show_light_box' =>'yes',
                        'zoomicon_type' => 'icon',
                    ]
                ]
            );

            $this->add_control(
                'show_flow_button',
                [
                    'label'         => __( 'Show Follow Button', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'htmega-addons' ),
                    'label_off'     => __( 'Hide', 'htmega-addons' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'flow_button_txt',
                [
                    'label' => __( 'Follow button Aditional text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Follow @', 'htmega-addons' ),
                    'condition'=>[
                        'show_flow_button'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'flow_button_icon',
                [
                    'label' =>__('Flow Button Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fab fa-instagram',
                        'library' => 'solid',
                    ],
                    'condition' => [
                        'show_flow_button' =>'yes',
                        
                    ]
                ]
            );

        $this->end_controls_section();

        // Slider setting
        $this->start_controls_section(
            'instagram_slider_option',
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
                    'max' => 20,
                    'step' => 1,
                    'default' => 8,
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

        // Style tab section
        $this->start_controls_section(
            'htmega_instagram_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'instagram_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list',
                ]
            );

            $this->add_responsive_control(
                'instagram_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'instagram_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Section

        // Item Style
        $this->start_controls_section(
            'htmega_instagram_item_style_section',
            [
                'label' => __( 'Item', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'instagram_item_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li',
                ]
            );

            $this->add_responsive_control(
                'instagram_item_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'instagram_item_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'instagram_item_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li',
                ]
            );

            $this->add_responsive_control(
                'instagram_item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_control(
                'more_options',
                [
                    'label' => esc_html__( 'Item Overlay', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'instagram_item_overlay_color',
                [
                    'label' => __( 'Overlay Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => 'rgba(0, 0, 0, 0.7)',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li .instagram-clip::before' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'instagram_item_overlay_padding',
                [
                    'label' => __( 'Overlay Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li .instagram-clip::before' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Item Style end

        // Zoom icon Style
        $this->start_controls_section(
            'htmega_instagram_icon_style_section',
            [
                'label' => __( 'Icon', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'zoomicon_type'=>'icon',
                    'zoom_icon[value]!'=>'',
                ]
            ]
        );

            $this->add_control(
                'icon_size',
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
                        'size' => 43,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list .zoom_icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list .zoom_icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'instagram_icon_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list .zoom_icon i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list .zoom_icon svg' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'instagram_icon_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list .zoom_icon',
                ]
            );

            $this->add_responsive_control(
                'instagram_icon_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list .zoom_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'instagram_icon_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list .zoom_icon',
                ]
            );

            $this->add_responsive_control(
                'instagram_icon_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list .zoom_icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section(); // Zoom icon Style end

        // Zoom icon Style
        $this->start_controls_section(
            'htmega_instagram_caption_style_section',
            [
                'label' => __( 'Caption', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

             $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'commentlike_size',
                    'selector' => '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li .instagram-clip .htmega-content .instagram-like-comment p',
                ]
            );

            $this->add_control(
                'instagram_commentlike_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li .instagram-clip .htmega-content .instagram-like-comment p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'instagram_commentlike_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li .instagram-clip .htmega-content .instagram-like-comment p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'instagram_commentlike_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram ul.htmega-instagram-list li .instagram-clip .htmega-content .instagram-like-comment p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        
        $this->end_controls_section(); // Zoom icon Style end

        // Style instagram arrow style start
        $this->start_controls_section(
            'htmega_instagram_arrow_style',
            [
                'label'     => __( 'Arrow', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'instagram_arrow_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'instagram_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_instagram_arrow_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_instagram_arrow_fontsize',
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
                                '{{WRAPPER}} .htmega-instragram .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'instagram_arrow_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-instragram .slick-arrow',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_instagram_arrow_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-instragram .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_instagram_arrow_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_instagram_arrow_height',
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
                                'size' => 30,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_instagram_arrow_width',
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
                                'size' => 30,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_instagram_arrow_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'instagram_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'htmega_instagram_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'instagram_arrow_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-instragram .slick-arrow:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_instagram_arrow_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-instragram .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_instagram_arrow_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style instagram arrow style end


        // Style instagram Dots style start
        $this->start_controls_section(
            'htmega_instagram_dots_style',
            [
                'label'     => __( 'Pagination', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'slider_on' => 'yes',
                    'sldots'  => 'yes',
                ],
            ]
        );
            
            $this->start_controls_tabs( 'instagram_dots_style_tabs' );

                // Normal tab Start
                $this->start_controls_tab(
                    'instagram_dots_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'instagram_dots_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-instragram .slick-dots li',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_instagram_dots_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-instragram .slick-dots li',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_instagram_dots_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-dots li' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_instagram_dots_height',
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
                                '{{WRAPPER}} .htmega-instragram .slick-dots li' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'htmega_instagram_dots_width',
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
                                '{{WRAPPER}} .htmega-instragram .slick-dots li' => 'width: {{SIZE}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal tab end

                // Hover tab Start
                $this->start_controls_tab(
                    'instagram_dots_style_hover_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'instagram_dots_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-instragram .slick-dots li.slick-active',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'htmega_instagram_dots_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-instragram .slick-dots li.slick-active',
                        ]
                    );

                    $this->add_responsive_control(
                        'htmega_instagram_dots_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-instragram .slick-dots li.slick-active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Style instagram dots style end

        $this->start_controls_section(
            'htmega_instagram_follow_button_style',
            [
                'label' => __( 'Follow Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_flow_button'=>'yes',
                ],
            ]
        );

             $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'instagram_follow_button_typography',
                    'selector' => '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn',
                ]
            );

            $this->add_control(
                'instagram_follow_button_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'instagram_follow_button_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'instagram_bouton_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn',
                ]
            );

            $this->add_responsive_control(
                'instagram_bouton_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'instagram_follow_button_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn' => 'padding: {{TOP}}{{UNIT}} 0{{UNIT}} {{BOTTOM}}{{UNIT}} 0{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'instagram_follow_button_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'instagram_follow_button_icon',
                [
                    'label' => esc_html__( 'Button Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );


            $this->add_responsive_control(
                'instagram_follow_button_icon_font_size',
                [
                    'label'   => __( 'Icon Size', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 250,
                        ],
                    ],
                    'size_units' => [ 'px' ],
                    'selectors'  => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn .instragram-icon-svg svg' => 'width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_control(
                'instagram_follow_button_icon_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn .instragram-icon-svg svg path' => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'instagram_follow_button_icon_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn i, {{WRAPPER}}  .htmega-instragram a.instagram_follow_btn .instragram-icon-svg.instragram-icon-svg',
                ]
            );

           
            $this->add_responsive_control(
                'instagram_follow_button_icon_width',
                [
                    'label'   => __( 'Icon Width', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 250,
                        ],
                    ],
                    'size_units' => [ 'px'],
                    'selectors'  => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn i' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn .instragram-icon-svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'instagram_follow_button_icon_height',
                [
                    'label'   => __( 'Icon Height', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 250,
                        ],
                    ],
                    'size_units' => [ 'px' ],
                    'selectors'  => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn i' => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn .instragram-icon-svg' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'instagram_follow_button_icon_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn i',
                    'selector' => '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn .instragram-icon-svg',
                ]
            );

            $this->add_responsive_control(
                'instagram_follow_button_icon_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn i' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-instragram a.instagram_follow_btn .instragram-icon-svg' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'instagram_follow_button_icon_align',
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
                        '{{WRAPPER}} .htmega-instragram .instagram_follow_btn i' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        
        $this->end_controls_section(); // Zoom icon Style end

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();
        $id         = $this->get_id();

        $this->add_render_attribute( 'htmega_instragram', 'class', 'htmega-instragram' );
        $this->add_render_attribute( 'htmega_instragram', 'class', 'htmega-instragram-style-'.$settings['instagram_style'] );

        $limit        = !empty( $settings['limit'] ) ? $settings['limit'] : 8;
        $access_token = !empty( $settings['access_token'] ) ? $settings['access_token'] : '';

        $cache_duration = $this->get_cacheing_duration( $settings['cash_time_duration'] );
        $transient_var  = $id . '_' . $limit;

        if( $settings['delete_cache'] === 'yes' ){
            delete_transient( $transient_var );
            $cache_duration = MINUTE_IN_SECONDS;
        }

        if( empty( $access_token ) ){
            echo '<p>'.esc_html__('Please enter your access token.','htmega-addons').'</p>';
            return;
        }

        if ( false === ( $items = get_transient( $transient_var ) ) ) {

            $url = 'https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,username&limit=200&access_token='.esc_html($access_token);

            $instagram_data = wp_remote_retrieve_body( wp_remote_get( $url ) );

            $instagram_data = json_decode( $instagram_data, true );
            
            if ( ! is_wp_error( $instagram_data ) ) {
                
                if ( isset( $instagram_data['error']['message'] ) ) {
                    echo '<p>'.esc_html__('Incorrect access token specified.','htmega-addons').'</p>';
                }

                $items = array();
                if( is_array( $instagram_data['data'] ) && $instagram_data['data'] ){

                    foreach ( $instagram_data['data'] as $data_item ) {
                        $image_src = ( $data_item['media_type'] == 'VIDEO' ) ? $data_item['thumbnail_url'] : $data_item['media_url'];
                        $item['id']         = $data_item['id'];
                        $item['media_type'] = $data_item['media_type'];
                        $item['src']        = $image_src;
                        $item['username']   = $data_item['username'];
                        $item['link']       = $data_item['permalink'];
                        $item['timestamp']  = $data_item['timestamp'];
                        $item['caption']    = !empty( $data_item['caption'] ) ? $data_item['caption'] : '';
                        $items[]            = $item;
                    }
                }
                set_transient( $transient_var, $items, 1 * $cache_duration );
            }
        }

        $username      = !empty( $items[0]['username'] ) ? $items[0]['username'] : '';
        $profile_link  = !empty( $items[0]['username'] ) ? 'https://www.instagram.com/'.$items[0]['username'] : '#';

        // Instagram Attribute
        $this->add_render_attribute( 'instagram_attr', 'class', 'htmega-instagram-list' );
        if( $settings['slider_on'] == 'yes' ){
            $direction = is_rtl() ? 'rtl' : 'ltr';
            $this->add_render_attribute( 'instagram_attr', 'dir', $direction );
            
            $this->add_render_attribute( 'instagram_attr', 'class', 'htmega-carousel-activation' );

            $slider_settings = [
                'arrows' => ('yes' === $settings['slarrows']),
                'arrow_prev_txt' => HTMega_Icon_manager::render_icon( $settings['slprevicon'], [ 'aria-hidden' => 'true' ] ),
                'arrow_next_txt' => HTMega_Icon_manager::render_icon( $settings['slnexticon'], [ 'aria-hidden' => 'true' ] ),
                'dots' => ('yes' === $settings['sldots']),
                'autoplay' => ('yes' === $settings['slautolay']),
                'autoplay_speed' => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
                'center_mode' => ( 'yes' === $settings['slcentermode']),
                'center_padding' => absint($settings['slcenterpadding']),
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

            $this->add_render_attribute( 'instagram_attr', 'data-settings', wp_json_encode( $slider_settings ) );
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string('htmega_instragram'); ?> >

                <ul <?php echo $this->get_render_attribute_string('instagram_attr'); ?>>
                    <?php
                        if ( isset( $items ) && !empty($items)):
                            $countitem = 0;
                            foreach ( $items as $item ):
                                $countitem++;
                    ?>
                        <li>
                            <a href="<?php echo esc_url( $item['link'] ); ?>" <?php echo ('yes' == $settings['image_link_newtab']) ? 'target="_blank"' : '' ?>>
                                <img src="<?php echo esc_url( $item['src'] ); ?>" alt="<?php echo esc_attr__( $item['username'],'htmega-addons');?>">
                            </a>
                            <?php if( $settings['show_caption'] == 'yes' || $settings['show_light_box'] == 'yes' ): ?>
                                <div class="instagram-clip">
                                    <div class="htmega-content">
                                        <?php if( $settings['show_caption'] == 'yes' && !empty( $item['caption'] ) ): ?>
                                            <div class="instagram-like-comment">
                                                <p><?php echo esc_html( $item['caption'] ); ?></p>
                                            </div>
                                        <?php endif; if( $settings['show_light_box'] == 'yes' ): ?>
                                            <div class="instagram-btn">
                                                <a class="image-popup-vertical-fit" href="<?php echo esc_url( $item['src'] ); ?>">
                                                    <?php
                                                        if( !empty( $settings['zoom_image'] ) && $settings['zoomicon_type'] == 'img' ){
                                                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'zoom_imagesize', 'zoom_image' );
                                                        }else{
                                                            echo sprintf('<span class="zoom_icon">%1$s</span>', HTMega_Icon_manager::render_icon( $settings['zoom_icon'], [ 'aria-hidden' => 'true' ] ) );
                                                        }
                                                    ?>
                                                </a>
                                            </div>
                                        <?php endif;?>
                                    </div>
                                </div>
                            <?php endif;?>
                        </li>
                    <?php if( $countitem == $limit ){ break; } endforeach; endif; ?>
                </ul>
                <?php 
                    if( $settings['show_flow_button'] == 'yes' ): 
                        $btn_prefix_txt = !empty( $settings['flow_button_txt'] ) ? $settings['flow_button_txt'] : '';
                ?>
                    <a class="instagram_follow_btn" href="<?php echo esc_url( $profile_link ); ?>" target="_blank">
                        <?php 

                        if($settings['flow_button_icon']['library'] == 'svg') {
                        
                             echo "<div class='instragram-icon-svg'>" .HTMega_Icon_manager::render_icon( $settings['flow_button_icon'], [ 'aria-hidden' => 'true' ] ). "</div>"; 
                        }else{
                            echo HTMega_Icon_manager::render_icon( $settings['flow_button_icon'], [ 'aria-hidden' => 'true' ] ); 
                        }
                         ?>
                        
                        <span><?php echo esc_html( $btn_prefix_txt.' '.$username );?></span>
                    </a>
                <?php endif; ?>

            </div>

        <?php
    }

    protected function get_cacheing_duration( $duration ){
        switch ( $duration ) {
            case 'minute':
                $cache_duration = MINUTE_IN_SECONDS;
                break;
            case 'hour':
                $cache_duration = HOUR_IN_SECONDS;
                break;
            case 'day':
                $cache_duration = DAY_IN_SECONDS;
                break;
            case 'week':
                $cache_duration = WEEK_IN_SECONDS;
                break;
            case 'month':
                $cache_duration = MONTH_IN_SECONDS;
                break;
            case 'year':
                $cache_duration = YEAR_IN_SECONDS;
                break;
            default:
                $cache_duration = DAY_IN_SECONDS;
        }
        return $cache_duration;
    }
}

