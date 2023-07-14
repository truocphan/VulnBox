<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Booked_Calender extends Widget_Base {

    public function get_name() {
        return 'htmega-bookedcalender-addons';
    }
    
    public function get_title() {
        return __( 'Booked Calendar', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-table';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function get_keywords() {
        return [ 'booked ', 'booked calendar', 'calendar','htmega','htmega' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/general-widgets/booked-calendar-widget/';
	}
    
    protected function register_controls() {

        $this->start_controls_section(
            'booked_calender_content',
            [
                'label' => __( 'Booked Calender', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'calendar_style',
                [
                    'label'   => __( 'Style', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''     => __('Default', 'htmega-addons') ,
                        'list' => __('List', 'htmega-addons') ,
                    ],
                ]
            );

            $this->add_control(
                'calendar_day',
                [
                    'label'   => __( 'Day', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => date('d'),
                    'options' => [
                        '01'     => __( '01', 'htmega-addons' ),
                        '02'     => __( '02', 'htmega-addons' ),
                        '03'     => __( '03', 'htmega-addons' ),
                        '04'     => __( '04', 'htmega-addons' ),
                        '05'     => __( '05', 'htmega-addons' ),
                        '06'     => __( '06', 'htmega-addons' ),
                        '07'     => __( '07', 'htmega-addons' ),
                        '08'     => __( '08', 'htmega-addons' ),
                        '09'     => __( '09', 'htmega-addons' ),
                        '10'     => __( '10', 'htmega-addons' ),
                        '11'     => __( '11', 'htmega-addons' ),
                        '12'     => __( '12', 'htmega-addons' ),
                        '13'     => __( '13', 'htmega-addons' ),
                        '14'     => __( '14', 'htmega-addons' ),
                        '15'     => __( '15', 'htmega-addons' ),
                        '16'     => __( '16', 'htmega-addons' ),
                        '17'     => __( '17', 'htmega-addons' ),
                        '18'     => __( '18', 'htmega-addons' ),
                        '19'     => __( '19', 'htmega-addons' ),
                        '20'     => __( '20', 'htmega-addons' ),
                        '21'     => __( '21', 'htmega-addons' ),
                        '22'     => __( '22', 'htmega-addons' ),
                        '23'     => __( '23', 'htmega-addons' ),
                        '24'     => __( '24', 'htmega-addons' ),
                        '25'     => __( '25', 'htmega-addons' ),
                        '26'     => __( '26', 'htmega-addons' ),
                        '27'     => __( '27', 'htmega-addons' ),
                        '28'     => __( '28', 'htmega-addons' ),
                        '29'     => __( '29', 'htmega-addons' ),
                        '30'     => __( '30', 'htmega-addons' ),
                        '31'     => __( '31', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'calendar_month',
                [
                    'label'   => __( 'Month', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => date('m'),
                    'options' => [
                        '01' => __('January', 'htmega-addons'),
                        '02' => __('February', 'htmega-addons'),
                        '03' => __('March', 'htmega-addons'),
                        '04' => __('April', 'htmega-addons'),
                        '05' => __('May', 'htmega-addons'),
                        '06' => __('June', 'htmega-addons'),
                        '07' => __('July', 'htmega-addons'),
                        '08' => __('August', 'htmega-addons'),
                        '09' => __('September', 'htmega-addons'),
                        '10' => __('October', 'htmega-addons'),
                        '11' => __('November', 'htmega-addons'),
                        '12' => __('December', 'htmega-addons'),
                    ],
                ]
            );

            $this->add_control(
                'calendar_year',
                [
                    'label'   => __( 'Year', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => date('Y'),
                    'options' => [
                        '2018'     => __( '2018', 'htmega-addons' ),
                        '2019'     => __( '2019', 'htmega-addons' ),
                        '2020'     => __( '2020', 'htmega-addons' ),
                        '2021'     => __( '2021', 'htmega-addons' ),
                        '2022'     => __( '2022', 'htmega-addons' ),
                        '2023'     => __( '2023', 'htmega-addons' ),
                        '2024'     => __( '2024', 'htmega-addons' ),
                        '2025'     => __( '2025', 'htmega-addons' ),
                        '2026'     => __( '2026', 'htmega-addons' ),
                        '2027'     => __( '2027', 'htmega-addons' ),
                        '2028'     => __( '2028', 'htmega-addons' ),
                        '2029'     => __( '2029', 'htmega-addons' ),
                        '2030'     => __( '2030', 'htmega-addons' ),
                        '2031'     => __( '2031', 'htmega-addons' ),
                        '2032'     => __( '2032', 'htmega-addons' ),
                        '2033'     => __( '2033', 'htmega-addons' ),
                        '2034'     => __( '2034', 'htmega-addons' ),
                        '2035'     => __( '2035', 'htmega-addons' ),
                        '2036'     => __( '2036', 'htmega-addons' ),
                        '2037'     => __( '2037', 'htmega-addons' ),
                        '2038'     => __( '2038', 'htmega-addons' ),
                        '2039'     => __( '2039', 'htmega-addons' ),
                        '2040'     => __( '2040', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'calendar_size',
                [
                    'label'   => __( 'Calendar Size', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''      => __('Default', 'htmega-addons') ,
                        'small' => __('Small', 'htmega-addons') ,
                    ],
                ]
            );

            $this->add_control(
                'calendar_members_only',
                [
                    'label' => __( 'Members Only', 'htmega-addons' ),
                    'type'  => Controls_Manager::SWITCHER,
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'booked_calender_header_style_section',
            [
                'label' => __( 'Header', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'calendar_style!' => 'list',
                ],
            ]
        );
            
            $this->add_control(
                'header_background',
                [
                    'label'     => __( 'Header Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.booked-calendar thead th' => 'background-color: {{VALUE}} !important;',
                        '{{WRAPPER}} table.booked-calendar thead'    => 'background-color: transparent !important',
                    ],
                ]
            );

            $this->add_control(
                'header_color',
                [
                    'label'     => __( 'Header Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.booked-calendar thead th' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'header_day_background',
                [
                    'label'     => __( 'Day Name Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.booked-calendar tr.days th' => 'background-color: {{VALUE}} !important;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'header_day_color',
                [
                    'label'     => __( 'Day Name Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.booked-calendar tr.days th' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Calender Body tab section
        $this->start_controls_section(
            'booked_calender_body_style_section',
            [
                'label' => __( 'Body', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'calendar_style!' => 'list',
                ],
            ]
        );
            $this->add_control(
                'calender_body_background',
                [
                    'label'     => __( 'Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.booked-calendar td.prev-month .date'           => 'background-color: {{VALUE}} !important;',
                        '{{WRAPPER}} table.booked-calendar td.next-month .date'           => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} table.booked-calendar td.prev-date:hover .date'      => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} table.booked-calendar td.prev-date .date'            => 'background-color: {{VALUE}} !important;',
                        '{{WRAPPER}} table.booked-calendar td.prev-date:hover .date span' => 'background-color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'calender_body_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.booked-calendar td.prev-date .date'            => 'color: {{VALUE}} !important;',
                        '{{WRAPPER}} table.booked-calendar td.prev-month .date span'      => 'color: {{VALUE}} !important;',
                        '{{WRAPPER}} table.booked-calendar td.next-month .date span'      => 'color: {{VALUE}} !important;',
                        '{{WRAPPER}} table.booked-calendar td.prev-date:hover .date span' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Calender Date tab section
        $this->start_controls_section(
            'booked_calender_date_style_section',
            [
                'label' => __( 'Date', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'calendar_style!' => 'list',
                ],
            ]
        );

            $this->start_controls_tabs( 'booked_calender_date_style_tabs' );
                
                // Available date style
                $this->start_controls_tab(
                    'booked_calender_date',
                    [
                        'label' => __( 'Available Date', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'booked_calender_date_background',
                        [
                            'label'     => __( 'Background', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} table.booked-calendar td .date' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_date_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} table.booked-calendar td' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_date_hover_background',
                        [
                            'label'     => __( 'Hover Background', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} table.booked-calendar td:hover .date span' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_date_hover_color',
                        [
                            'label'     => __( 'Hover Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} table.booked-calendar td:hover .date span' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();
                
                // Current Date style
                $this->start_controls_tab(
                    'booked_calender_current_date',
                    [
                        'label' => __( 'Current Date', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'booked_calender_current_date_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} table.booked-calendar td.today .date span' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_current_date_border_color',
                        [
                            'label'     => __( 'Border Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} table.booked-calendar td.today .date span' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_current_date_hover_background',
                        [
                            'label'     => __( 'Hover Background', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} table.booked-calendar td.today:hover .date span' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_current_date_hover_color',
                        [
                            'label'     => __( 'Hover Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} table.booked-calendar td.today:hover .date span' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Appointments Style Section
        $this->start_controls_section(
            'booked_calender_style_apointments',
            [
                'label'     => __( 'Appointments', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'calendar_style!' => 'list',
                ],
            ]
        );

            $this->add_control(
                'apointments_background',
                [
                    'label'     => __( 'Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.booked-calendar .booked-appt-list' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .booked-calendar-wrap .booked-appt-list .timeslot:hover' => 'background-color: rgba(255, 255, 255, 0.3);',
                    ],
                ]
            );

            $this->add_control(
                'apointments_text_color',
                [
                    'label'     => __( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .booked-calendar-wrap .booked-appt-list h2' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .booked-calendar-wrap .booked-appt-list .timeslot .timeslot-time' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .booked-calendar-wrap .booked-appt-list .timeslot .timeslot-time i.booked-icon' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'active_date_background_color',
                [
                    'label'     => __( 'Active Date Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} table.booked-calendar tr.week td.active .date' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} table.booked-calendar tr.week td.active:hover .date' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} table.booked-calendar tr.entryBlock' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .booked-calendar-wrap .booked-appt-list .timeslot .spots-available' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'timeslot_time_text_color',
                [
                    'label'     => __( 'Time Slot Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .booked-calendar-wrap .booked-appt-list .timeslot .spots-available' => 'color: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section();

        // List style Heading Section
        $this->start_controls_section(
            'booked_calender_section_style_heading',
            [
                'label'     => __( 'Heading', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'calendar_style' => 'list',
                ],
            ]
        );

            $this->add_control(
                'booked_calender_list_heading_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .booked-appt-list > h2' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'booked_calender_list_heading_typography',
                    'selector' => '{{WRAPPER}} .booked-appt-list > h2',
                ]
            );

        $this->end_controls_section();

        // List Time 
        $this->start_controls_section(
            'booked_calender_section_style_time',
            [
                'label'     => __( 'Time', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'calendar_style' => 'list',
                ],
            ]
        );

            $this->add_control(
                'booked_calender_list_time_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .timeslot-range' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'booked_calender_list_time_icon_color',
                [
                    'label'     => __( 'Icon Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .timeslot-range .booked-icon.booked-icon-clock' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'booked_calender_list_text_color',
                [
                    'label'     => __( 'Text Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .spots-available,{{WRAPPER}} .booked-calendar-wrap .booked-appt-list .timeslot .spots-available' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'booked_calender_list_time_typography',
                    'selector' => '{{WRAPPER}} .timeslot-range',
                ]
            );

        $this->end_controls_section();

        // Appointment Button
        $this->start_controls_section(
            'booked_calender_section_style_appointment_button',
            [
                'label'     => __( 'Appointment Button', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,

            ]
        );

            $this->start_controls_tabs( 'booked_calender_tabs_appointment_button_style' );

                $this->start_controls_tab(
                    'booked_calender_tab_appointment_button_normal',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'booked_calender_appointment_button_text_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .new-appt.button' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'      => 'booked_calender_appointment_button_background',
                            'types'     => [ 'classic', 'gradient' ],
                            'selector'  => '{{WRAPPER}} .new-appt.button',
                            'separator' => 'after',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'booked_calender_appointment_button_border',
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} .new-appt.button',
                            'separator'   => 'before',
                        ]
                    );

                    $this->add_control(
                        'booked_calender_appointment_button_radius',
                        [
                            'label'      => __( 'Border Radius', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .new-appt.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'booked_calender_appointment_button_shadow',
                            'selector' => '{{WRAPPER}} .new-appt.button',
                        ]
                    );

                    $this->add_control(
                        'booked_calender_appointment_button_padding',
                        [
                            'label'      => __( 'Padding', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} .new-appt.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'booked_calender_appointment_button_typography',
                            'selector'  => '{{WRAPPER}} .new-appt.button',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // Appointment Button Normal

                $this->start_controls_tab(
                    'booked_calender_tab_appointment_button_hover',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'booked_calender_appointment_button_hover_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .new-appt.button:hover' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_appointment_button_hover_background',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .new-appt.button:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'booked_calender_appointment_button_border_hover',
                            'selector'    => '{{WRAPPER}} .new-appt.button:hover',
                            'separator'   => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'booked_calender_appointment_button_shadow_hover',
                            'selector' => '{{WRAPPER}} .new-appt.button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Navigation Button
        $this->start_controls_section(
            'booked_calender_section_style_navigation_button',
            [
                'label'     => __( 'Navigation Button', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,

            ]
        );

            $this->start_controls_tabs( 'booked_calender_tabs_navigation_button_style' );

                $this->start_controls_tab(
                    'booked_calender_tab_navigation_button_normal',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'booked_calender_navigation_button_text_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} [class*="booked-list-view-date-"],{{WRAPPER}} table.booked-calendar th .monthName a,{{WRAPPER}} table.booked-calendar thead th .page-left,{{WRAPPER}} table.booked-calendar thead th .page-right' => 'color: {{VALUE}}!important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'      => 'booked_calender_navigation_button_background',
                            'types'     => [ 'classic', 'gradient' ],
                            'selector'  => '{{WRAPPER}} [class*="booked-list-view-date-"]',
                            'separator' => 'after',
                                'condition' => [
                                    'calendar_style' => 'list',
                                ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'booked_calender_navigation_button_border',
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} [class*="booked-list-view-date-"]',
                            'separator'   => 'before',
                            'condition' => [
                                'calendar_style' => 'list',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_navigation_button_radius',
                        [
                            'label'      => __( 'Border Radius', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} [class*="booked-list-view-date-"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                'calendar_style' => 'list',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'booked_calender_navigation_button_shadow',
                            'selector' => '{{WRAPPER}} [class*="booked-list-view-date-"]',
                            'condition' => [
                                'calendar_style' => 'list',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_navigation_button_padding',
                        [
                            'label'      => __( 'Padding', 'htmega-addons' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors'  => [
                                '{{WRAPPER}} [class*="booked-list-view-date-"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                            'condition' => [
                                'calendar_style' => 'list',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'booked_calender_navigation_button_typography',
                            'selector'  => '{{WRAPPER}} [class*="booked-list-view-date-"],{{WRAPPER}} table.booked-calendar th .monthName a',
                            'separator' => 'before',
                        ]
                    );

                    $this->end_controls_tab();

                    $this->start_controls_tab(
                        'booked_calender_tab_navigation_button_hover',
                        [
                            'label' => __( 'Hover', 'htmega-addons' ),
                        ]
                    );

                    $this->add_control(
                        'booked_calender_navigation_button_hover_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} [class*="booked-list-view-date-"]:hover,{{WRAPPER}} table.booked-calendar th .monthName a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'      => 'booked_calender_navigation_button_hover_background',
                            'types'     => [ 'classic', 'gradient' ],
                            'selector'  => '{{WRAPPER}} [class*="booked-list-view-date-"]:hover',
                            'separator' => 'after',
                            'condition' => [
                                'calendar_style' => 'list',
                            ],
                        ]
                    );

                    $this->add_control(
                        'booked_calender_navigation_button_hover_border_color',
                        [
                            'label'     => __( 'Border Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'condition' => [
                                'navigation_button_border_border!' => '',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} [class*="booked-list-view-date-"]:hover' => 'border-color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                            'condition' => [
                                'calendar_style' => 'list',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Calender Style 
        $this->start_controls_section(
            'booked_calender_section_style_additional',
            [
                'label'     => __( 'Calendar', 'htmega-addons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'calendar_style' => 'list',
                ],
            ]
        );

            $this->add_control(
                'booked_calender_calendar_icon_color',
                [
                    'label'     => __( 'Calendar Icon Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .booked-list-view a.booked_list_date_picker_trigger' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'booked_calendar_icon_background',
                [
                    'label'     => __( 'Calendar Icon Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .booked-list-view a.booked_list_date_picker_trigger' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'        => 'booked_calendar_icon_border',
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '{{WRAPPER}} .booked-list-view a.booked_list_date_picker_trigger',
                    'separator'   => 'before',
                ]
            );

            $this->add_control(
                'booked_calendar_icon_radius',
                [
                    'label'      => __( 'Calendar Icon Radius', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .booked-list-view a.booked_list_date_picker_trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'booked_calendar_icon_shadow',
                    'selector' => '{{WRAPPER}} .booked-list-view a.booked_list_date_picker_trigger'
                ]
            );

            $this->add_control(
                'booked_calendar_icon_padding',
                [
                    'label'      => __( 'Calendar Icon Padding', 'htmega-addons' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .booked-list-view a.booked_list_date_picker_trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'booked_calendar_row_border_color',
                [
                    'label'     => __( 'Row Border Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .booked-calendar-wrap .booked-appt-list .timeslot' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'booked_calendar_row_border_width',
                [
                    'label' => __( 'Row Border Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .booked-calendar-wrap .booked-appt-list .timeslot' => 'border-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $calender_attributes = [
            'style'        => $settings['calendar_style'],
            'year'         => $settings['calendar_year'],
            'month'        => $settings['calendar_month'],
            'day'          => $settings['calendar_day'],
            'size'         => $settings['calendar_size'],
            'members-only' => ( 'yes' === $settings['calendar_members_only'] ) ? 'true' : '',
        ];
        $this->add_render_attribute( 'shortcode', $calender_attributes );
        
        echo do_shortcode( sprintf( '[booked-calendar %s]', $this->get_render_attribute_string( 'shortcode' ) ) );

    }

}

