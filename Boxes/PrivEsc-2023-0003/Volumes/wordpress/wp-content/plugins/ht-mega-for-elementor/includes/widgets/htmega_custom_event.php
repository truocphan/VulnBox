<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Custom_Event extends Widget_Base {

    public function get_name() {
        return 'htmega-customevent-addons';
    }
    
    public function get_title() {
        return __( 'Custom Event', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-archive-posts';
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
            'customevent_content',
            [
                'label' => __( 'Event', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'event_title',
                [
                    'label'   => __( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'placeholder' => __('Event Title.','htmega-addons'),
                ]
            );

            $this->add_control(
                'event_image',
                [
                    'label' => __( 'Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'event_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'event_time',
                [
                    'label' => __( 'Event Time', 'htmega-addons' ),
                    'type' => Controls_Manager::DATE_TIME,
                ]
            );

            $this->add_control(
                'event_location',
                [
                    'label'   => __( 'Event Location', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'placeholder' => __('Location.','htmega-addons'),
                ]
            );

            $this->add_control(
                'event_description',
                [
                    'label'   => __( 'Event description', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                ]
            );

            $this->add_control(
                'event_button',
                [
                    'label'   => __( 'Event Button text', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'placeholder' => __('Read More.','htmega-addons'),
                ]
            );

            $this->add_control(
                'event_link',
                [
                    'label' => __( 'Event Button Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                    'condition'=>[
                        'event_button!'=>'',
                    ]
                ]
            );
            
        $this->end_controls_section();

        // Event Title Style tab section
        $this->start_controls_section(
            'event_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'event_title!'=>'',
                ],
            ]
        );

            $this->start_controls_tabs('event_title_style_tabs');

                $this->start_controls_tab(
                    'event_title_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                ); 

                    $this->add_control(
                        'event_title_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#000000',
                            'selectors' => [
                                '{{WRAPPER}} .ht-event .content h4 a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'event_title_typography',
                            'selector' => '{{WRAPPER}} .ht-event .content h4',
                        ]
                    );

                    $this->add_responsive_control(
                        'event_title_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-event .content h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'event_title_margin',
                        [
                            'label' => __( 'Margin', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-event .content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'event_title_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'event_title_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#000000',
                            'selectors' => [
                                '{{WRAPPER}} .ht-event .content h4 a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();
            
        $this->end_controls_section();

        // Event Description Style tab section
        $this->start_controls_section(
            'event_description_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'event_description!'=>'',
                ],
            ]
        );

            $this->add_control(
                'event_description_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#727272',
                    'selectors' => [
                        '{{WRAPPER}} .ht-event .content p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'event_description_typography',
                    'selector' => '{{WRAPPER}} .ht-event .content p',
                ]
            );

            $this->add_responsive_control(
                'event_description_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-event .content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'event_description_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-event .content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );
            
        $this->end_controls_section();

        // Event Time Location Style tab section
        $this->start_controls_section(
            'event_timelocation_style_section',
            [
                'label' => __( 'Time / Location', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'event_timelocation_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#909090',
                    'selectors' => [
                        '{{WRAPPER}} .ht-event .content ul.event-time li' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'event_timelocation_typography',
                    'selector' => '{{WRAPPER}} .ht-event .content ul.event-time li',
                ]
            );

            $this->add_responsive_control(
                'event_timelocation_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-event .content ul.event-time li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );
            
        $this->end_controls_section();


        // Event Date Style tab section
        $this->start_controls_section(
            'event_eventdate_style_section',
            [
                'label' => __( 'Date', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'event_eventdate_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#fff',
                    'selectors' => [
                        '{{WRAPPER}} .ht-event .thumb .event-date' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'event_eventdate_bg_color',
                [
                    'label' => __( 'Background Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#141414',
                    'selectors' => [
                        '{{WRAPPER}} .ht-event .thumb .event-date' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'event_eventdate_typography',
                    'selector' => '{{WRAPPER}} .ht-event .thumb .event-date',
                ]
            );

            $this->add_responsive_control(
                'event_eventdate_padding',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-event .ht-event .thumb .event-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Event Button Style tab section
        $this->start_controls_section(
            'event_button_style_section',
            [
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'event_button!'=>'',
                ],
            ]
        );

            $this->start_controls_tabs('event_button_style_tabs');

                $this->start_controls_tab(
                    'event_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'event_button_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#727272',
                            'selectors' => [
                                '{{WRAPPER}} .ht-event .content .event-btn a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'event_button_typography',
                            'selector' => '{{WRAPPER}} .ht-event .content .event-btn a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'event_button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .ht-event .content .event-btn a',
                        ]
                    );

                    $this->add_responsive_control(
                        'event_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-event .content .event-btn a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'event_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'event_button_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#727272',
                            'selectors' => [
                                '{{WRAPPER}} .ht-event .content .event-btn a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'event_button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .ht-event .content .event-btn a:hover',
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'custom_event_attr', 'class', 'htmega-event-area' );

        // Event Link
        if ( isset( $settings['event_link'] ) ) {

            $this->add_link_attributes( 'url', $settings['event_link'] );

        }

        $eventdate = date_create( $settings['event_time'] );
        $timeformate = get_option('time_format');
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'custom_event_attr' ); ?>>
                <div class="ht-event">
                    <div class="thumb">
                        <a <?php echo $this->get_render_attribute_string( 'url' ); ?> >
                            <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'event_imagesize', 'event_image' ); ?>
                        </a>
                        <div class="event-date">
                            <?php $formated_time = strtotime( $settings['event_time'] );?>

                            <span><?php echo esc_html( date_i18n( "d", $formated_time ) ); ?></span>

                            <span class="month"><?php echo esc_html( date_i18n( "M", $formated_time ) ); ?></span>
                        </div>
                    </div>
                    <div class="content">
                        <?php
                            if( !empty( $settings['event_title'] ) ){
                                echo '<h4><a href="#">'.htmega_kses_title( $settings['event_title'] ).'</a></h4>';
                            }
                            if( !empty( $settings['event_time'] ) || !empty( $settings['event_location'] ) ):
                        ?>
                        <ul class="event-time">
                            <?php
                                if( !empty( $settings['event_time'] ) ){
                                    echo '<li><i class="fa fa-clock-o"></i>'.esc_html( date_i18n( $timeformate, $formated_time ) ).'</li>';
                                }
                                if( !empty( $settings['event_location'] ) ){
                                    echo '<li><i class="fa fa-map-marker""></i>'.htmega_kses_desc( $settings['event_location'] ).'</li>';
                                }
                            ?>
                        </ul>
                        <?php 
                            endif;
                            if( !empty( $settings['event_description'] ) ){
                                echo '<p>'.htmega_kses_desc( $settings['event_description'] ).'</p>';
                            }
                        ?>
                        <?php if( !empty( $settings['event_button'] ) ):?>
                            <div class="event-btn">
                                <a <?php echo $this->get_render_attribute_string( 'url' ); ?> > <?php echo htmega_kses_desc( $settings['event_button'] ); ?></a>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        <?php

    }

}

