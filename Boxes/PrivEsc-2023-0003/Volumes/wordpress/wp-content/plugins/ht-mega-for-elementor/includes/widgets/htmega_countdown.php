<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Countdown extends Widget_Base {

    public function get_name() {
        return 'htmega-countdown-addons';
    }
    
    public function get_title() {
        return __( 'Countdown', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-countdown';
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
            'htmega-countdown',
            'htmega-widgets-scripts',
        ];
    }

    public function get_event_list() {

        if( is_plugin_active('the-events-calendar/the-events-calendar.php') ) {
            $event_item = get_posts(array(
                'fields'         => 'ids',
                'posts_per_page' => -1,
                'post_type'      => \Tribe__Events__Main::POSTTYPE,
            ));

            $event_items = ['0' => __( 'Select Event', 'htmega-addons' ) ];

            foreach ($event_item as $key => $value) {
                $event_items[$value] = get_the_title($value);
            }

            wp_reset_postdata();
        } else {
            $event_items = ['0' => __( 'Event Calendar Not Installed', 'htmega-addons' ) ];
        }
        return $event_items;
    }

    protected function register_controls() {

        // Start Date option tab 
        $this->start_controls_section(
            'countdown_content',
            [
                'label' => __( 'Countdown', 'htmega-addons' ),
            ]
        );
        
            $this->add_control(
                'show_event_list',
                [
                    'label'   => __( 'Event Countdown', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'event_id',
                [
                    'label'       => __( 'Event List', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'options'     => $this->get_event_list(),
                    'default'     => '0',
                    'condition'=>[
                        'show_event_list'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'htmega_count_style',
                [
                    'label'          => __( 'Style', 'htmega-addons' ),
                    'type'           => 'htmega-preset-select',
                    'default'        => '1',
                    'options'        => [
                        '1' => __( 'Style one', 'htmega-addons' ),
                        '2' => __( 'Style Two', 'htmega-addons' ),
                        '3' => __( 'Style Three', 'htmega-addons' ),
                        'flip' => __( 'Style Four (Flip)', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'target_date',
                [
                    'label'       => __( 'Due Date', 'htmega-addons' ),
                    'type'        => Controls_Manager::DATE_TIME,
                    'picker_options'=>array(
                        'dateFormat' =>"Y/m/d",
                    ),
                    'default'     => date( 'Y/m/d', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
                    'condition'=>[
                        'show_event_list!'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'counter_timing_heading',
                [
                    'label' => __( 'Time Setting', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'count_down_days',
                [
                    'label'        => __( 'Day', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' =>'yes',
                ]
            );

            $this->add_control(
                'count_down_hours',
                [
                    'label'        => __( 'Hours', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' =>'yes',
                ]
            );

            $this->add_control(
                'count_down_miniute',
                [
                    'label'        => __( 'Minutes', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' =>'yes',
                ]
            );

            $this->add_control(
                'count_down_second',
                [
                    'label'        => __( 'Seconds', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' =>'yes',
                ]
            );

            $this->add_control(
                'counter_lavel_heading',
                [
                    'label' => __( 'Label Setting', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'count_down_labels',
                [
                    'label'        => __( 'Hide Label', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' =>'no',
                ]
            );

            $this->add_control(
                'custom_labels',
                [
                    'label'        => __( 'Custom Label', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'condition'   => [
                        'count_down_labels!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'customlabel_days',
                [
                    'label'       => __( 'Days', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Days', 'htmega-addons' ),
                    'condition'   => [
                        'custom_labels!'     => '',
                        'count_down_labels!' => 'yes',
                        'count_down_days'    => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'customlabel_hours',
                [
                    'label'       => __( 'Hours', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Hours', 'htmega-addons' ),
                    'condition'   => [
                        'custom_labels!'     => '',
                        'count_down_labels!' => 'yes',
                        'count_down_hours'   => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'customlabel_minutes',
                [
                    'label'       => __( 'Minutes', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Minutes', 'htmega-addons' ),
                    'condition'   => [
                        'custom_labels!'     => '',
                        'count_down_labels!' => 'yes',
                        'count_down_miniute' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'customlabel_seconds',
                [
                    'label'       => __( 'Seconds', 'htmega-addons' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Seconds', 'htmega-addons' ),
                    'condition'   => [
                        'custom_labels!'     => '',
                        'count_down_labels!' => 'yes',
                        'count_down_second'  => 'yes',
                    ],
                ]
            );

        $this->end_controls_section(); // Date Optiin end

        // Event Button
        $this->start_controls_section(
            'countdown_event_button',
            [
                'label' => __( 'Event Button', 'htmega-addons' ),
                'condition'=>[
                    'show_event_list'=>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'button_text',
                [
                    'label' => __( 'Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default'=>__('Details','htmega-addons'),
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label' => __( 'Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                ]
            );

        $this->end_controls_section(); // Date Optiin end

        // Content Layout
        $this->start_controls_section(
            'countdown_layout',
            [
                'label' => __( 'Count Layout', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            
            $this->add_responsive_control(
                'column_width',
                [
                    'label'   => __( 'Column Width', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'unit' => 'px',
                        'size' => 139,
                    ],
                    'tablet_default' => [
                        'unit' => '%',
                    ],
                    'mobile_default' => [
                        'unit' => '%',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 2000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'size_units' => [ '%', 'px' ],
                    'selectors'  => [
                        '{{WRAPPER}} span.ht-count' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-time-inner' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'   => [
                        'htmega_count_style' => ['1', 'flip'],
                    ],
                ]
            );

            $this->add_responsive_control(
                'column_height',
                [
                    'label'   => __( 'Column Height', 'htmega-addons' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'unit' => 'px',
                        'size' => 185,
                    ],
                    'tablet_default' => [
                        'unit' => '%',
                    ],
                    'mobile_default' => [
                        'unit' => '%',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 2000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'size_units' => [ '%', 'px' ],
                    'selectors'  => [
                        '{{WRAPPER}} span.ht-count' => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-time-inner' => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-time-inner .htmega-top' => 'line-height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'   => [
                        'htmega_count_style' => ['1', 'flip'],
                    ],
                ]
            );

            $this->add_responsive_control(
                'count_down_specing',
                [
                    'label' => __( 'Column Spacing', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 22,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox .ht-count' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-flash-flip-countdown-timer .ht-countdown-flip .htmega-time' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_countdown_style',
            [
                'label' => __( 'Count Area', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} span.ht-count',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_control(
                'counter_background_daly_heading',
                [
                    'label' => esc_html__( '1. Background Days', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_background_daly_flip',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-flash-flip-countdown-timer .ht-countdown .htmega-days .ht-count',
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ],
                ]
            );

            $this->add_control(
                'counter_background_hours_heading',
                [
                    'label' => esc_html__( '2. Background Hours', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_background_hours_flip',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-flash-flip-countdown-timer .ht-countdown .htmega-hours .ht-count',
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ],
                ]
            );

            $this->add_control(
                'counter_background_minutes_heading',
                [
                    'label' => esc_html__( '3. Background Minutes', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_background_minutes_flip',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-flash-flip-countdown-timer .ht-countdown .htmega-mins .ht-count',
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ],
                ]
            );

            $this->add_control(
                'counter_background_seconds_heading',
                [
                    'label' => esc_html__( '4. Background Seconds', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_background_seconds_flip',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-flash-flip-countdown-timer .ht-countdown .htmega-secs .ht-count',
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'counter_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} span.ht-count, {{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-time-inner',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'countborder',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} span.ht-count',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'countborder_flip',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-time-inner',
                    'condition'=>[
                        'htmega_count_style'=> 'flip',
                    ]
                ]
            );
            

            $this->add_responsive_control(
                'count_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} span.ht-count' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-time-inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_responsive_control(
                'countpadding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} span.ht-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_responsive_control(
                'countmargin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} span.ht-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_responsive_control(
                'itemaligntitle',
                [
                    'label' => __( 'Item Alignment', 'htmega-addons' ),
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
                        '{{WRAPPER}} .htmega-countbox' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .ht-countdown-flip' => 'justify-content: {{VALUE}};',
                    ],
                    'prefix_class' => 'htmega-item-align%s-',
                    'default' => '',
                ]
            );

            $this->add_responsive_control(
                'aligntitle',
                [
                    'label' => __( 'Content Alignment', 'htmega-addons' ),
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
                        '{{WRAPPER}} span.ht-count' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'prefix_class' => 'htmega-count-align%s-',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_control(
                'counter_separator',
                [
                    'label'        => __( 'Counter separator', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' =>'yes',
                    'separator' =>'before',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_control(
                'count_seperator_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#5e5b60',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox .ht-count::before' => 'color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'counter_separator'=>'yes',
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_control(
                'count_seperator_image',
                [
                    'label' => esc_html__( 'Choose Area Seperator Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'condition'=>[
                        'counter_separator'=>'yes',
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            
            $this->add_responsive_control(
                'count_timer_separator_position',
                [
                    'label' => esc_html__( 'Separator Position', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox .ht-count::before' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'counter_separator' => 'yes',
                        'htmega_count_style'=> ['1', '2', '3'],
                    ],
                ]
            );

        $this->end_controls_section(); // Section style tab end

        // Timer style tab start
        $this->start_controls_section(
            'htmega_countdown_time_style',
            [
                'label'     => __( 'Timer', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'count_timer_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#242424',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span.time-count' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-time-inner .ht-count' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'count_timer_typography',
                    'selector' => '{{WRAPPER}} .htmega-countbox span.time-count, {{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-time-inner .ht-count',
                ]
            );

            $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'count_timer_shadow',
                    'label' => __( 'Text Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-countbox span.time-count',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'count_timer_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-countbox span.time-count',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'count_timer_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-countbox span.time-count',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_responsive_control(
                'count_timer_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span.time-count' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_responsive_control(
                'count_timer_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span.time-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_responsive_control(
                'count_timer_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span.time-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_control(
                'counter_timer_separator',
                [
                    'label'        => __( 'Timer separator', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' =>'yes',
                    'condition'=>[
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
                
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'count_timer_separator_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-countbox span.time-count::before',
                    'condition'=>[
                        'counter_timer_separator' => 'yes',
                        'htmega_count_style'=> ['1', '2', '3'],
                    ]
                ]
            );

            $this->add_control(
                'count_timer_separator_background_width',
                [
                    'label' => esc_html__( 'Separator Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ '%' ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 80,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span.time-count::before' => 'background-size: {{SIZE}}{{UNIT}} auto;',
                    ],
                    'condition'=>[
                        'counter_timer_separator' => 'yes',
                        'htmega_count_style'=> ['1', '2', '3'],
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Timer style tab end

        // Style tab section
        $this->start_controls_section(
            'htmega_countdown_label_style',
            [
                'label' => __( 'Label', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'count_down_labels!' => 'yes',
                ],
            ]
        );
            $this->add_control(
                'count_lavel_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#242424',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span span.count-inner p' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-label p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'count_lavel_typography',
                    'selector' => '{{WRAPPER}} .htmega-countbox span span.count-inner p, {{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-label p',
                ]
            );

            $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'count_lavel_shadow',
                    'label' => __( 'Text Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-countbox span span.count-inner p, {{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-label p',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'count_lavel_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-countbox span span.count-inner p, {{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-label p',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'count_lavel_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-countbox span span.count-inner p, {{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-label p',
                ]
            );

            $this->add_responsive_control(
                'count_lavel_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span span.count-inner p' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-label p' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'count_lavel_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span span.count-inner p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-label p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'count_lavel_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-countbox span span.count-inner p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .ht-countdown-flip .htmega-time .htmega-label p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Label style tab end

        // Style tab section
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => __( 'Button Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_event_list'=>'yes',
                ]
            ]
        );

            $this->start_controls_tabs( 'button_style_tabs' );
            
                $this->start_controls_tab(
                    'button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'button_typography',
                            'label' => __( 'Typography', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega_event_button a',
                        ]
                    );

                    $this->add_control(
                        'button_text_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .htmega_event_button a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'      => 'button_background_color',
                            'types'     => [ 'classic', 'gradient' ],
                            'selector'  => '{{WRAPPER}} .htmega_event_button a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'button_box_shadow',
                            'selector' => '{{WRAPPER}} .htmega_event_button a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(), [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'placeholder' => '1px',
                            'default' => '1px',
                            'selector' => '{{WRAPPER}} .htmega_event_button a',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega_event_button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega_event_button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega_event_button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal Button style End

                // Hover Button style End
                $this->start_controls_tab(
                    'button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'button_hover_text_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .htmega_event_button a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'      => 'button_hover_background_color',
                            'types'     => [ 'classic', 'gradient' ],
                            'selector'  => '{{WRAPPER}} .htmega_event_button a:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'button_hover_box_shadow',
                            'selector' => '{{WRAPPER}} .htmega_event_button a:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(), [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'placeholder' => '1px',
                            'default' => '1px',
                            'selector' => '{{WRAPPER}} .htmega_event_button a:hover',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega_event_button a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $sectionid =  "htmaga-". $this-> get_id();
        $settings   = $this->get_settings_for_display();
        $data_options = [];

        if( $settings['show_event_list'] == 'yes' && function_exists('tribe_get_start_date') ){
            $data_options['htmegadate']  =  tribe_get_start_date ( $settings['event_id'], false,  'Y/m/d' );
        }else{ 
            $data_options['htmegadate'] = isset( $settings['target_date'] ) ? $settings['target_date'] : date( 'Y/m/d', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
        }

        // Hide Countdownload item
        $data_options['style']      = $settings['htmega_count_style'];
        $data_options['lavelhide']      = $settings['count_down_labels'];
        $data_options['htmegaday']      = $settings['count_down_days'];
        $data_options['htmegahours']    = $settings['count_down_hours'];
        $data_options['htmegaminiute']  = $settings['count_down_miniute'];
        $data_options['htmegasecond']   = $settings['count_down_second'];

        // Custom Label
        $data_options['htmegadaytxt'] = ! empty( $settings['customlabel_days'] ) ? $settings['customlabel_days'] : 'Days';
        $data_options['htmegahourtxt'] = ! empty( $settings['customlabel_hours'] ) ? $settings['customlabel_hours'] : 'Hours';
        $data_options['htmegaminutestxt'] = ! empty( $settings['customlabel_minutes'] ) ? $settings['customlabel_minutes'] : 'Minutes';
        $data_options['htmegasecondstxt'] = ! empty( $settings['customlabel_seconds'] ) ? $settings['customlabel_seconds'] : 'Seconds';
        
        $this->add_render_attribute( 'countdown_wrapper_attr', 'class', 'htmega-countdown-wrapper ' .$sectionid );
        $this->add_render_attribute( 'countdown_wrapper_attr', 'class', 'htmega-countdown-style-'. $settings['htmega_count_style'] );

        if( $settings['counter_timer_separator'] != 'yes' ){
            $this->add_render_attribute( 'countdown_wrapper_attr', 'class', 'htmega-timer-separate-no' );
        }
        if( $settings['counter_separator'] != 'yes' ){
            $this->add_render_attribute( 'countdown_wrapper_attr', 'class', 'htmega-separate-no' );
        }
        if( $settings['count_down_labels'] == 'yes' ){
            $this->add_render_attribute( 'countdown_wrapper_attr', 'class', 'htmega-hide-lavel' );
        }

        if(isset($settings['count_seperator_image']['url']) &&  $settings['count_seperator_image']['url'] != ''){
            $count_area_seperator = "url('" . $settings['count_seperator_image']['url']. "')";
        }else{
            $count_area_seperator =":";
        }

        $countdownClassOne = '';
        $countdownClassTwo = '';
        if($settings['htmega_count_style'] == 'flip'){
            $countdownClassOne = 'htmega-flash-flip-countdown-timer';
            $countdownClassTwo = 'ht-countdown ht-countdown-flip';
        }else{
            $countdownClassOne = 'htmega-countbox';
            $countdownClassTwo = '';
        }

        ?>
            <div <?php echo $this->get_render_attribute_string( 'countdown_wrapper_attr' ); ?> >
                <div class="htmega-box-timer">
                    <div class="<?php echo esc_attr( $countdownClassOne ) ?>">
                        <?php
                            echo '<div class="'.esc_attr( $countdownClassTwo ).'"data-countdown=\'' . wp_json_encode( $data_options ) . '\' ></div>';
                        ?>
                        
                        <?php if( $settings['show_event_list'] == 'yes' && $settings['event_id'] != 0 ):?>
                            <div class="htmega_event_button">
                                <a class="elementor-button" href="<?php echo esc_url( get_permalink( $settings['event_id'] ) );?>">
                                    <?php
                                        if( !empty( $settings['button_icon']['value'] ) ){
                                            echo HTMega_Icon_manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] );
                                        } 
                                        if( !empty( $settings['button_text'] ) ){
                                            echo esc_html( $settings['button_text'] );
                                        }
                                    ?>
                                </a>
                            </div>
                        <?php endif;?>
                    </div>
                </div>

            </div>

            <?php if($settings['counter_separator'] == 'yes'): ?>
                <style><?php echo esc_html( '.'.$sectionid ) ?> .htmega-countbox .ht-count::before{ content: <?php echo htmega_kses_desc($count_area_seperator) ?>;}</style>
           <?php endif;
    }

}
