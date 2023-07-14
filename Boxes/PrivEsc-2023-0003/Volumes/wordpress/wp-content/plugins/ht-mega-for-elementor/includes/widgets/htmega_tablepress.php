<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Tablepress extends Widget_Base {

    public function get_name() {
        return 'htmega-tablepress-addons';
    }
    
    public function get_title() {
        return __( 'TablePress', 'htmega-addons' );
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

    public function htmega_tablepress_table_list() {
        $table_ids = \TablePress::$model_table->load_all( false );
        $table_data['0'] = esc_html__( 'Select Table', 'htmega-addons' );
        foreach ( $table_ids as $table_id ) {
            $table = \TablePress::$model_table->load( $table_id, false, false );
            if ( '' === trim( $table['name'] ) ) {
                $table['name'] = __( '(no name)', 'htmega-addons' );
            }
            $table_data[$table['id']] = $table['name'];
        }
        return $table_data;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'tablepress_content',
            [
                'label' => __( 'TablePress', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'table_id',
                [
                    'label'   => esc_html__( 'Select Table', 'htmega-addons' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => $this->htmega_tablepress_table_list(),
                ]
            );
            
        $this->end_controls_section();

        //Options
        $this->start_controls_section(
            'tablepress_options',
            [
                'label' => __( 'Options', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'entries_hide',
                [
                    'label'   => esc_html__( 'Entries Hide', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .dataTables_length' => 'display: none;',
                    ],
                ]
            );

            $this->add_control(
                'pagination_hide',
                [
                    'label'   => esc_html__( 'Pagination Hide', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .dataTables_paginate' => 'display: none;',
                    ],
                ]
            );

            $this->add_control(
                'search_hide',
                [
                    'label'   => esc_html__( 'Search Hide', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .dataTables_filter' => 'display: none;',
                    ],
                ]
            );

            $this->add_control(
                'footer_info_hide',
                [
                    'label'   => esc_html__( 'Footer text Hide', 'htmega-addons' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .dataTables_info' => 'display: none;',
                    ],
                ]
            );

        $this->end_controls_section();


        // Style table tab section
        $this->start_controls_section(
            'tablepress_table_style_section',
            [
                'label' => __( 'Table', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'tablepress_table_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-tablepress table.tablepress',
                ]
            );

        $this->end_controls_section();

        // Style header tab section
        $this->start_controls_section(
            'tablepress_header_style_section',
            [
                'label' => __( 'Header', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'tablepress_header_background',
                [
                    'label'     => __( 'Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#d9edf7',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .tablepress th' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'tablepress_header_active_background',
                [
                    'label'     => __( 'Hover Background', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#049cdb',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .tablepress .sorting:hover, {{WRAPPER}} .htmega-tablepress .tablepress .sorting_asc, {{WRAPPER}} .htmega-tablepress .tablepress .sorting_desc' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'tablepress_header_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#212529',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .tablepress th' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'tablepress_header_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-tablepress .tablepress th',
                ]
            );

            $this->add_responsive_control(
                'tablepress_header_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .tablepress th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'tablepress_header_align',
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
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default' => 'center',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .tablepress th' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Style table body section
        $this->start_controls_section(
            'tablepress_body_style_section',
            [
                'label' => __( 'Body', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'tablepress_body_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-tablepress .tablepress td',
                ]
            );

            // TablePress Body
            $this->start_controls_tabs('tablepress_body_style_tabs');

                // Odd cell style start
                $this->start_controls_tab(
                    'tablepress_body_odd_style_normal_tab',
                    [
                        'label' => __( 'Event Cell', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'tablepress_body_odd_background',
                        [
                            'label'     => __( 'Background', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#f9f9f9',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tablepress .tablepress .odd td' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tablepress_body_odd_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tablepress .tablepress .odd td' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tablepress_body_odd_border_color',
                        [
                            'label'     => __( 'Border Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#ccc',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tablepress .tablepress .odd td' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Odd cell style end

                $this->start_controls_tab(
                    'tablepress_body_event_style_normal_tab',
                    [
                        'label' => __( 'Odd Cell', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'tablepress_body_event_background',
                        [
                            'label'     => __( 'Background', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tablepress .tablepress .even td' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tablepress_body_event_color',
                        [
                            'label'     => __( 'Text Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tablepress .tablepress .even td' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tablepress_body_event_border_color',
                        [
                            'label'     => __( 'Border Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#ccc',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-tablepress .tablepress .even td' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Event cell style end

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'tablepress_body_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .tablepress td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'tablepress_body_align',
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
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default' => 'center',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .tablepress td' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

         // Style table footer section
        $this->start_controls_section(
            'tablepress_footer_style_section',
            [
                'label' => __( 'Footer text', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'footer_info_hide!'=>'yes',
                ],
            ]
        );

            $this->add_control(
                'tablepress_footer_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'=>'#212529',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .dataTables_info' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'tablepress_footer_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-tablepress .dataTables_info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        if ( !$settings['table_id'] ) {
            echo '<p>'.esc_html_e( "Please Select table","htmega-addons").'</p>';
        }else{
            if ( Plugin::instance()->editor->is_edit_mode() ) {
                \TablePress::load_controller( 'frontend' );
                $controller = new \TablePress_Frontend_Controller();
                $controller->init_shortcodes();
            }
            $attributes = [
                'id'  => $settings['table_id'],
            ];
            $this->add_render_attribute( 'shortcode', $attributes );

            echo '<div class="htmega-tablepress htb-table-responsive">'.do_shortcode( sprintf( '[table %s]', $this->get_render_attribute_string( 'shortcode' ) ) ).'</div>';
        }


    }

}

