<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_ImageMarker extends Widget_Base {

    public function get_name() {
        return 'htmega-imagemarker-addons';
    }
    
    public function get_title() {
        return __( 'Image Marker / Hotspots', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-post';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_keywords() {
        return ['image pointer','marker', 'image marker', 'hotspot','hot spots','image hostspot', 'ht mega', 'htmega'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/creative-widgets/image-marker-widget/';
    }
    
    public function get_style_depends() {
        return [
            'elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','htmega-widgets',
        ];
    }

    protected function register_controls() {

        // Marker Content section
        $this->start_controls_section(
            'image_marker_content_section',
            [
                'label' => __( 'Marker', 'htmega-addons' ),
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'marker_bg_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper',
                ]
            );

            $this->add_control(
                'hotspot_bg_image',
                [
                    'label' => __('Choose Image','htmega-addons'),
                    'type'=>Controls_Manager::MEDIA,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'separator'=>'before'
                ]
            );
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'hotspot_bg_image_size',
                    'default' => 'full',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'marker_bg_opacity_color',
                [
                    'label' => __( 'Opacity Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper:before' => 'content:"";position:absolute;width:100%;height:100%;left:0;top:0;background-color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'marker_bg_background_image[id]!'=>'',
                    ]
                ]
            );

            $this->add_control(
            'marker_bg_opacity_slider',
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
                    '{{WRAPPER}} .htmega-marker-wrapper:before' => 'opacity: {{SIZE}};',
                ],
                'condition'=>[
                    'marker_bg_background_image[id]!'=>'',
                    'marker_bg_opacity_color!'=>'',
                ]
            ]
        );  

        $this->add_responsive_control(
            'image_marker_area_padding',
            [
                'label' => __( 'Area padding', 'htmega-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-marker-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );        

        $this->add_control(
            'marker_section',
            [
                'label' => __( 'Marker Items', 'htmega-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' =>'before',
            ]
        );      

            $this->add_control(
                'marker_style',
                [
                    'label'   => __( 'Style', 'htmega-addons' ),
                    'type'    => 'htmega-preset-select',
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'htmega-addons' ),
                        '2'   => __( 'Style Two', 'htmega-addons' ),
                        '3'   => __( 'Style Three', 'htmega-addons' ),
                        '4'   => __( 'Style Four', 'htmega-addons' ),
                        '5'   => __( 'Style Five', 'htmega-addons' ),
                        '6'   => __( 'Style Six', 'htmega-addons' ),
                    ],
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'indicator_type',
                [
                    'label' => esc_html__( 'Indicator Type', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'icon',
                    'options' => [
                        'icon'   => esc_html__( 'Icon', 'htmega-addons' ),
                        'image'   => esc_html__( 'Image (Pro)', 'htmega-addons' ),
                        'text'   => esc_html__( 'Text (Pro)', 'htmega-addons' ),
                    ],
                ]
            );

            $this->pro_notice($repeater,'indicator_type', array('image','text') );
            $repeater->add_control(
                'marker_indicator_icon',
                [
                    'label' =>esc_html__('Marker Indicator Icon','htmega-addons'),
                    'type'=>Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-info',
                        'library'=>'solid',
                    ],
                ]
            );
            $repeater->add_control(
                'content_type',
                [
                    'label' => esc_html__( 'Content Type', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'   => esc_html__( 'Custom', 'htmega-addons' ),
                        'template'   => esc_html__( 'Elementor Template (Pro)', 'htmega-addons' ),
                    ],
                    'separator' =>'before'
                ]
            );
            $this->pro_notice($repeater,'content_type','template' );


            $repeater->add_control(
                'marker_title',
                [
                    'label'   => __( 'Marker Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Marker #1', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'marker_placeholder_text',
                [
                    'label' => __( 'Marker Placeholder Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'marker_content',
                [
                    'label'   => __( 'Marker Content', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __( 'Lorem ipsum pisaci volupt atem accusa saes ntisdumtiu loperm asaerks.', 'htmega-addons' ),
                ]
            );

            $repeater->add_control(
                'marker_content_position_offset',
                [
                    'label' => __( 'Marker Content Position', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                        'top' => [
                            'title' => __( 'Top', 'htmega-addons' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'htmega-addons' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'top',
                    'separator' => 'before',
                ]
            );

            // Marker Icon Postition Start 
            $repeater->add_control(
                'marker_icon_position_offset_toggle',
                [
                    'label' => __( 'Marker Icon Position', 'htmega-addons' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => __( 'None', 'htmega-addons' ),
                    'label_on' => __( 'Custom', 'htmega-addons' ),
                    'return_value' => 'yes',
                ]
            );
    
            $repeater->start_popover(); 

                $repeater->add_responsive_control(
                    'marker_x_position',
                    [
                        'label' => __( 'X Position', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 66,
                            'unit' => '%',
                        ],
                        'range' => [
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-marker-wrapper .htmega-shadow-wrapper{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-marker-wrapper .htmega-shadow-wrapper{{CURRENT_ITEM}} .htmega_image_pointer{{CURRENT_ITEM}} ' => 'left: 50%; transform: translateY(-50%) translateX(-50%)',
                        ],
                    ]
                );

                $repeater->add_responsive_control(
                    'marker_y_position',
                    [
                        'label' => __( 'Y Position', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 15,
                            'unit' => '%',
                        ],
                        'range' => [
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-marker-wrapper .htmega-shadow-wrapper{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-marker-wrapper .htmega-shadow-wrapper{{CURRENT_ITEM}} .htmega_image_pointer{{CURRENT_ITEM}}' => 'top: 50%; transform: translateY(-50%) translateX(-50%)',
                        ],
                    ]
                );

            $repeater->end_popover();
            // Marker icon position end 

            // Marker Content Position Start 
            $repeater->add_control(
                'marker_content_position_offset_toggle',
                [
                    'label' => __( 'Marker Content Position', 'htmega-addons' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => __( 'None', 'htmega-addons' ),
                    'label_on' => __( 'Custom', 'htmega-addons' ),
                    'return_value' => 'yes',
                ]
            );
    
            $repeater->start_popover();

                $repeater->add_responsive_control(
                    'marker_content_position_x',
                    [
                        'label' => __( 'Marker Content Position(X)', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => -300,
                                'step' => 1,
                                'max'=> 300,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer{{CURRENT_ITEM}} .htmega_pointer_box' => 'right: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $repeater->add_responsive_control(
                    'marker_content_position_y',
                    [
                        'label' => __( 'Marker Content Position(Y)', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => -300,
                                'step' => 1,
                                'max'=> 300,
                            ],
                            '%' => [
                                'min' => -100,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer{{CURRENT_ITEM}} .htmega_pointer_box' => 'top: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );            

            $repeater->end_popover();
            // Marker Content Position End
            $repeater->add_responsive_control(
                'content_width',
                [
                    'label' => esc_html__( 'Content Box Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 1170,
                        ],
                    ],
                    'size_units' => ['px' ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .htmega_pointer_box' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );  
            $repeater->add_control(
                'content_indicator_section', 
                [
                    'label'         => __( 'Content Indicator', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'separator' => 'before',
                ]
            );
            $repeater->add_control(
                'indicator_styles',
                [
                    'label' => esc_html__( 'Indicator Style', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'caret',
                    'options' => [
                        'caret'   => esc_html__( 'Caret', 'htmega-addons' ),
                        'arrow'   => esc_html__( 'Arrow (Pro)', 'htmega-addons' ),
                        'line'   => esc_html__( 'Line (Pro)', 'htmega-addons' ),
                    ],
                    'condition' => [
                        'content_indicator_section' =>'yes',
                    ],
                ]
            );
            $this->pro_notice($repeater,'indicator_styles', ['arrow','line'] );

            $repeater->add_responsive_control(
                'marker_indicator_position',
                [
                    'label' => __( 'Indicator Position(X)', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ '%' ],
                    'range' => [
                        '%' => [
                            'min' => -300,
                            'step' => 1,
                            'max'=> 300,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 50,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega_image_pointer.htmega-marker-content-position-yes .htmega_pointer_box:before' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'content_indicator_section' =>'yes',
                    ],
                ]
            );

            $repeater->add_responsive_control(
                'marker_indicator_position_y',
                [
                    'label' => __( 'Indicator Position(Y)', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ '%' ],
                    'range' => [
                        '%' => [
                            'min' => -300,
                            'step' => 1,
                            'max'=>300,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 100,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega_image_pointer.htmega-marker-content-position-yes .htmega_pointer_box:before' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'content_indicator_section' =>'yes',
                    ],
                ]
            );

            $repeater->add_responsive_control(
                'marker_indicator_rotated_deg',
                [
                    'label' => __( 'Indicator Rotated', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => -360,
                            'step' => 45,
                            'max'=>360,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega_image_pointer.htmega-marker-content-position-yes .htmega_pointer_box:before' => 'transform: rotate({{SIZE}}deg);',
                    ],
                    'condition' => [
                        'content_indicator_section' =>'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'progressbar_value_before_after_color', 
                [
                    'label'     => __( 'Indicator color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default' => '#ff0000',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega_image_pointer.htmega-marker-content-position-yes .htmega_pointer_box:before' => 'border-top: 12px solid {{VALUE}};',
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega_image_pointer::after' => 'background: {{VALUE}};',
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega-marker-content-position-top.htmega_image_pointer::before' => 'border-bottom-color: {{VALUE}};',
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega-marker-content-position-bottom.htmega_image_pointer::before' => 'border-top-color: {{VALUE}};',
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega-marker-content-position-left.htmega_image_pointer::before' => 'border-right-color: {{VALUE}};',
                        '{{WRAPPER}} {{CURRENT_ITEM}}.htmega-marker-content-position-right.htmega_image_pointer::before' => 'border-left-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'content_indicator_section' =>'yes',
                    ],
                   
                ]
            ); 

            $repeater->add_control(
                'tooltip_active', 
                [
                    'label'         => __( 'Active Tooltip (Pro)', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'separator' => 'before',
                ]
            );
            $this->pro_notice($repeater,'tooltip_active', 'yes' );
            $repeater->add_control(
                'tooltip_hide_on_mobile', 
                [
                    'label'         => __( 'Hide On Mobile(Pro)', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'default'       => 'no',
                ]
            );
            $this->pro_notice($repeater,'tooltip_hide_on_mobile', 'yes' );
            $this->add_control(
                'image_marker_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'prevent_empty'=>false,
                    'default' => [
                        [
                            'marker_title' => __( 'Marker #1', 'htmega-addons' ),
                            'marker_content' => __( 'Lorem ipsum pisaci volupt atem accusa saes ntisdumtiu loperm asaerks.','htmega-addons' ),
                            'marker_x_position' => [
                                'size' => 66,
                                'unit' => '%',
                            ],
                            'marker_y_position' => [
                                'size' => 15,
                                'unit' => '%',
                            ]
                        ]
                    ],
                    'title_field' => '{{{ marker_title }}}',
                ]
            );
            $this->add_control(
                'pulse_shadow', 
                [
                    'label'         => __( 'Pulse Shadow', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'marker_animation',
                [
                    'label' => esc_html__( 'Marker Icon Animation', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'rotated',
                    'options' => [
                        'none'   => esc_html__( 'None', 'htmega-addons' ),
                        'rotated'   => esc_html__( 'Rotated', 'htmega-addons' ),
                    ],
                ]
            );
        $this->end_controls_section();     

        // Style Marker tab section
        $this->start_controls_section(
            'image_marker_style_section',
            [
                'label' => __( 'Marker', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

                $this->add_responsive_control(
                    'marker_icon_width',
                    [
                        'label' => __( 'Marker Width', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 1,
                                'step' => 1,
                                'max'=>200,
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
                            '{{WRAPPER}} .htmega-marker-style-6 .htmega_image_pointer,{{WRAPPER}} .htmega_image_pointer,{{WRAPPER}} .htmega-marker-style-6 .htmega-shadow-wrapper,{{WRAPPER}} .htmega-shadow-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega_image_pointer.htmega-indicator-type-text,{{WRAPPER}} .htmega-shadow-wrapper.htmega-indicator-type-text{{WRAPPER}} .htmega_image_pointer.htmega-indicator-type-image,{{WRAPPER}} .htmega-shadow-wrapper.htmega-indicator-type-image' => 'width: max-content;'
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'marker_icon_height',
                    [
                        'label' => __( 'Marker Height', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 1,
                                'step' => 1,
                                'max'=>200,
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
                            '{{WRAPPER}} .htmega-marker-style-6 .htmega_image_pointer,{{WRAPPER}} .htmega_image_pointer,{{WRAPPER}} .htmega-shadow-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega_image_pointer.htmega-indicator-type-text,{{WRAPPER}} .htmega-shadow-wrapper.htmega-indicator-type-text,{{WRAPPER}} .htmega_image_pointer.htmega-indicator-type-image,{{WRAPPER}} .htmega-shadow-wrapper.htmega-indicator-type-image' => 'height: auto; width: max-content;'
                        ],
                    ]
                );
                $this->add_responsive_control(
                    'marker_icon_font_size',
                    [
                        'label' => __( 'Icon Size', 'htmega-addons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 1,
                                'step' => 1,
                                'max'=>100,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'default' => [
                            'unit' => 'px',
                            'size' => 14,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .htmega-marker-style-6 .htmega-image-marker-icon i,{{WRAPPER}} .htmega-image-marker-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                            '{{WRAPPER}} .htmega-marker-style-6 .htmega-image-marker-icon svg,{{WRAPPER}} .htmega-image-marker-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'marker_text_typography',
                        'selector' => '{{WRAPPER}} .htmega-indicator-type-text .htmega-image-marker-icon',
                    ]
                );

                $this->add_control(
                    'image_marker_icon_color',
                    [
                        'label'     => __( 'Color', 'htmega-addons' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-marker-style-6 .htmega-image-marker-icon i' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .htmega-marker-style-6 .htmega-image-marker-icon path' => 'fill: {{VALUE}};',
                        ],
                        'default'=>'#ed552d',
                        'condition' =>[
                            'marker_style' => '6',
                        ],
                    ]
                );
            $this->add_control(
                'image_marker_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-image-marker-icon i,{{WRAPPER}} .htmega-image-marker-icon' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-image-marker-icon path' => 'fill: {{VALUE}};',
                    ],
                    'default'=>'#ed552d',
                    'condition' =>[
                        'marker_style!' => '6',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_marker_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer , {{WRAPPER}} .htmega-marker-style-3 .htmega_image_pointer::after',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_marker_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer',
                ]
            );

            $this->add_responsive_control(
                'image_marker_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer,{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega-image-marker-icon img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'image_marker_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer',
                ]
            );
            $this->add_responsive_control(
                'image_marker_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'pulse_shadow_color',
                [
                    'label'     => __( 'Pulse Shadow Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-shadow-wrapper::before, {{WRAPPER}} .htmega-shadow-wrapper::after' => 'border-color: {{VALUE}}; background-color:{{VALUE}};',
                    ],
                    'default'=>'#ddd',
                    'condition' =>[
                        'pulse_shadow' => 'yes',
                    ],
                ]
            );
        $this->end_controls_section(); // End Marker style tab

        // Style Marker tab section
        $this->start_controls_section(
            'image_marker_content_style_section',
            [
                'label' => __( 'Content', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'image_marker_content_alignment_box',
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
                    'prefix_class' => 'htmega-marker-content-',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box' => 'text-align: {{VALUE}};',
                    ]
                ]
            );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_marker_content_area_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_marker_content_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box',
                ]
            );

            $this->add_responsive_control(
                'image_marker_content_area_border_radius',
                [
                    'label' => esc_html__( 'Content area border radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_marker_content_area_padding',
                [
                    'label' => __( 'Content area padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'content_box_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega_image_pointer .htmega_pointer_box',
                    'separator' => 'after',
                ]
            );
         $this->start_controls_tabs('image_marker_content_style_tabs');
                
                // Style Title Tab start
                $this->start_controls_tab(
                    'style_title_tab',
                    [
                        'label' => __( 'Title', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'image_marker_title_style_toggle',
                        [
                            'label' => __( 'Title Bottom Border Style', 'htmega-addons' ),
                            'type' => Controls_Manager::POPOVER_TOGGLE,
                            'label_off' => __( 'None', 'htmega-addons' ),
                            'label_on' => __( 'Custom', 'htmega-addons' ),
                            'return_value' => 'yes',
                        ]
                    );
        
                    $this->start_popover();

                        $this->add_control(
                            'marker_title_bottom_background',
                            [
                                'label'     => __( 'Border Background', 'htmega-addons' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4:before' => 'background: {{VALUE}}',
                                ],
                            ]
                        );

                        $this->add_responsive_control(
                            'marker_border_bottom_width',
                            [
                                'label' => __( 'Width', 'htmega-addons' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ '%'],
                                'range' => [
                                    'px' => [
                                        'min' => 5,
                                        'max' => 100,
                                        'step' => 1,
                                    ],
                                ],
                                'default' => [
                                    'unit' => '%',
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4:before' => 'width: {{SIZE}}{{UNIT}};',
                                ],
                            ]
                        );

                        $this->add_responsive_control(
                            'marker_border_bottom_height',
                            [
                                'label' => __( 'Height', 'htmega-addons' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px'],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 1,
                                    ],
                                ],
                                'default' => [
                                    'unit' => 'px',
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4:before' => 'height: {{SIZE}}{{UNIT}};',
                                ],
                            ]
                        );

                    $this->end_popover();


                    $this->add_control(
                        'image_marker_title_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#18012c',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'image_marker_title_typography',
                            'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'image_marker_title_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4',
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_title_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_title_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'marker_title_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Style Title Tab end
                
                // Style Description Tab start
                $this->start_controls_tab(
                    'style_description_tab',
                    [
                        'label' => __( 'Description', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'image_marker_description_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box p' => 'color: {{VALUE}};',
                            ],
                            'default'=>'#18012c',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'image_marker_description_typography',
                            'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box p',
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_description_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Style Description Tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // End Content style tab

        // Style Marker tab section
        $this->start_controls_section(
            'image_marker_placeholder_section',
            [
                'label' => __( 'Placeholder Text Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
                    
        $this->add_control(
            'image_marker_placeholder_color',
            [
                'label'     => __( 'Color', 'htmega-addons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4:after' => 'color: {{VALUE}};',
                ],
                'default'=>'#F0F4F4',
                // 'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'image_marker_placeholder_typography',
                'selector' => '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4:after',
            ]
        );

        $this->add_responsive_control(
            'marker_placeholder_position_ashim',
            [
                'label' => __( 'Position Top-Bottom (Y)', 'htmega-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'step' => 1,
                        'max'=> 200,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => -31,
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4:after' => 'top: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'marker_placeholder_position_sweet',
            [
                'label' => __( 'Position Left-Right (X)', 'htmega-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'step' => 1,
                        'max'=> 200,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-marker-wrapper .htmega_image_pointer .htmega_pointer_box h4:after' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section(); // End Content style tab 

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'htmega_image_marker_attr', 'class', 'htmega-marker-wrapper' );
        $this->add_render_attribute( 'htmega_image_marker_attr', 'class', 'htmega-marker-style-'.$settings['marker_style'] );
       
        ?>
        
            <div <?php echo $this->get_render_attribute_string('htmega_image_marker_attr'); ?> >
            <?php
                    if( !empty( $settings['hotspot_bg_image']['url'] ) ){
                        echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'hotspot_bg_image_size', 'hotspot_bg_image' );
                    }
                    foreach ( $settings['image_marker_list'] as $item ):

                        if( 'yes' == $settings['pulse_shadow'] ){
                            echo '<div class="htmega-shadow-wrapper elementor-repeater-item-'.esc_attr( $item['_id'] ).'">';
                        }
                        $indicator_display = 'no';
                    if ( 'yes' == $item['content_indicator_section'] ) {
                        $indicator_display = ('caret' == $item['indicator_styles'] ) ? 'yes' : 'yes';
                    }

                    ?>
                        <div class="htmega_image_pointer elementor-repeater-item-<?php echo esc_attr( $item['_id'] );?> htmega-marker-content-position-<?php echo esc_attr( $item['marker_content_position_offset'] ); ?> htmega-marker-content-position-<?php echo esc_attr( $indicator_display ).' htmega-marker-animation-'.esc_attr( $settings[ 'marker_animation' ] ); ?>">
                            
                                <div class="htmega-image-marker-icon">
                                    <?php
                                    if( !empty( $item['marker_indicator_icon']['value'] ) ){
                                        echo HTMega_Icon_manager::render_icon( $item['marker_indicator_icon'], [ 'aria-hidden' => 'true' ] ); 
                                    }?>
                                </div>
                            
                            <div class="htmega_pointer_box">
                                <?php 
                                    if (!empty($item['marker_title'])) { ?>
                                        <h4 <?php if ($item['marker_placeholder_text'] !== ''): ?>
                                            data-pltext="<?php echo esc_attr($item['marker_placeholder_text']) ?>" 
                                        <?php endif ?> >
                                            <?php echo htmega_kses_title($item['marker_title']); ?>
                                        </h4>
                                    <?php }
                                    if (!empty($item['marker_content'])) {
                                        echo '<p>' . htmega_kses_desc($item['marker_content']) . '</p>';
                                    }
                                    ?>
                            </div>
                        </div>
                        
                    <?php
                        if( 'yes' == $settings['pulse_shadow'] ){
                            echo '</div>';
                        }
                    endforeach;
                ?> 
          
            </div>
        <?php
    }

    public function pro_notice( $repeater,$condition_key, $array_value){
        $repeater->add_control(
            'update_pro'.$condition_key,
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(
                    __('Upgrade to pro version to use this feature %s Pro Version %s', 'htmega-addons'),
                    '<strong><a href="https://wphtmega.com/pricing/" target="_blank">',
                    '</a></strong>'),
                'content_classes' => 'htmega-pro-notice',
                'condition' => [
                    $condition_key => $array_value,
                ]
            ]
        );
    }
}