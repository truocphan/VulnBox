<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Data_Table extends Widget_Base {

    public function get_name() {
        return 'htmega-datatable-addons';
    }
    
    public function get_title() {
        return __( 'Data Table', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-table';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_keywords() {
        return ['data table','data', 'data sheet', 'advanced table','advanced data table', 'ht mega', 'htmega'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/general-widgets/data-table-widget/';
    }
    public function get_script_depends() {
        return [ 'datatables' ];
    }

    public function get_style_depends() {
        return [ 'datatables', 'htmega-widgets', ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'datatable_layout',
            [
                'label' => __( 'Table Data', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'source_type',
                [
                    'label' => __( 'Source', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'      => __( 'Custom', 'htmega-addons' ),
                        'csv'      => __( 'CSV File(Pro)', 'htmega-addons' ),
                        'google'      => __( 'Google Sheet (Pro)', 'htmega-addons' ),
                        'table_press' => __( 'TablePress (Pro)', 'htmega-addons' ),
                        'database'    => __( 'Database (Pro)', 'htmega-addons' ),
                    ],
                ]
            );
            $this->pro_notice( $this,'source_type', ['csv', 'google', 'table_press', 'database'] );

        $this->end_controls_section();

        // Table Header
        $this->start_controls_section(
            'datatable_header',
            [
                'label' => __( 'Table Header', 'htmega-addons' ),
            ]
        );

            $repeater = new Repeater();

            $repeater->add_control(
                'column_name',
                [
                    'label'   => __( 'Column Name', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'No', 'htmega-addons' ),
                ]
            );

           $repeater->add_control(
                'column_heading',
                [
                    'label' => esc_html__( 'Column styles', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

           $repeater->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'column_heading_background_color',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}}',
                ]
            );

           $repeater->add_group_control(
                Group_Control_Border::get_type(),
                    [
                    'name' => 'column_background_border',
                    'label' => esc_html__( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}}',
                ]
            );

           $repeater->add_responsive_control(
                'column_background_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selector' => '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}}',
                ]
            );

           $repeater->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'column_background_border_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}}',
                ]
            );

            $this->add_control(
                'header_column_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'prevent_empty' => false,
                    'default' => [
                        [
                            'column_name' => __( 'No', 'htmega-addons' ),
                        ],

                        [
                            'column_name' => __( 'Name', 'htmega-addons' ),
                        ],

                        [
                            'column_name' => __( 'Designation', 'htmega-addons' ),
                        ],

                        [
                            'column_name' => __( 'Email', 'htmega-addons' ),
                        ]

                    ],
                    'title_field' => '{{{ column_name }}}',
                ]
            );
            
        $this->end_controls_section();

        // Table Content
        $this->start_controls_section(
            'datatable_content',
            [
                'label' => __( 'Table Content', 'htmega-addons' ),
            ]
        );

            $repeater_one = new Repeater();

            $repeater_one->add_control(
                'field_type',
                [
                    'label' => __( 'Field Type', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'row',
                    'options' => [
                        'row'   => __( 'Row', 'htmega-addons' ),
                        'col'   => __( 'Column', 'htmega-addons' ),
                    ],
                ]
            );

            $repeater_one->add_control(
                'cell_data_type',
                [
                    'label' => __( 'Data Type', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'text',
                    'options' => [
                        'text'   => __( 'Text', 'htmega-addons' ),
                        'icon'   => __( 'Icon', 'htmega-addons' ),
                        'image'   => __( 'Image', 'htmega-addons' ),
                        'template'   => __( 'Elementor Template (Pro)', 'htmega-addons' ),
                    ],
                    'condition'=>[
                        'field_type'=>'col',
                    ]
                ]
            );
            $this->pro_notice( $repeater_one,'cell_data_type', 'template' );
            $repeater_one->add_control(
                'cell_text',
                [
                    'label'   => __( 'Cell Content', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Louis Hudson', 'htmega-addons' ),
                    'condition'=>[
                        'cell_data_type'=>'text',
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_control(
                'cell_icon',
                [
                    'label'   => esc_html__( 'Cell Icon', 'htmega-addons' ),
                    'type'    => Controls_Manager::ICONS,
                    'condition'=>[
                        'cell_data_type'=>'icon',
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_responsive_control(
                'cell_icon_size',
                [
                    'label' => esc_html__( 'Icon SIze', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 20,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}} svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}}' => 'font-size: {{SIZE}}{{UNIT}};',

                    ],
                    'condition'=>[
                        'cell_data_type'=>'icon',
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_control(
                'cell_icon_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}} i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}} svg path' => 'fill: {{VALUE}};',
                    ],
                    'condition'=>[
                        'cell_data_type'=>'icon',
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_control(
                'cell_image',
                [
                    'label' => esc_html__( 'Cell Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'condition'=>[
                        'cell_data_type'=>'image',
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_responsive_control(
                'cell_image_width',
                [
                    'label' => esc_html__( 'Image Width', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'cell_data_type'=>'image',
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_responsive_control(
                'cell_image_height',
                [
                    'label' => esc_html__( 'Image Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}} img' => 'height: {{SIZE}}{{UNIT}}',
                    ],
                    'condition'=>[
                        'cell_data_type'=>'image',
                        'field_type'=>'col',
                    ]
                ]
            );


            $repeater_one->add_control(
                'row_colspan',
                [
                    'label' => __( 'Colspan', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'condition'=>[
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_control(
                'cell_heading',
                [
                    'label' => esc_html__( 'Cell Styles', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'=>[
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'cell_background_color',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}}',
                    'condition'=>[
                        'field_type'=>'col',
                    ]
                ]
            );

            $repeater_one->add_group_control(
                Group_Control_Border::get_type(),
                    [
                    'name' => 'cell_background_border',
                    'label' => esc_html__( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style {{CURRENT_ITEM}}',
                    'condition'=>[
                        'field_type'=>'col',
                    ]
                ]
            );

            $this->add_control(
                'content_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater_one->get_controls(),
                    'default' => [
                        [
                            'field_type' => __( 'row', 'htmega-addons' ),
                        ],

                        [
                            'field_type' => __( 'col', 'htmega-addons' ),
                            'cell_text' => __( '1', 'htmega-addons' ),
                            'row_colspan' => __( '1', 'htmega-addons' ),
                        ],

                        [
                            'field_type' => __( 'col', 'htmega-addons' ),
                            'cell_text' => __( 'Louis Hudson', 'htmega-addons' ),
                            'row_colspan' => __( '1', 'htmega-addons' ),
                        ],

                        [
                            'field_type' => __( 'col', 'htmega-addons' ),
                            'cell_text' => __( 'Developer', 'htmega-addons' ),
                            'row_colspan' => __( '1', 'htmega-addons' ),
                        ],


                        [
                            'field_type' => __( 'col', 'htmega-addons' ),
                            'cell_text' => __( 'jondoy@gmail.com', 'htmega-addons' ),
                            'row_colspan' => __( '1', 'htmega-addons' ),
                        ]

                    ],
                    'title_field' => '{{{field_type}}}',
                ]
            );
            
        $this->end_controls_section();
        // Sorting Options
        $this->start_controls_section(
            'datatable_sorting_options',
            [
                'label' => __( 'Display Options', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'datatable_style',
                [
                    'label' => __( 'Layout', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Layout One', 'htmega-addons' ),
                        '2'   => __( 'Layout Two', 'htmega-addons' ),
                        '3'   => __( 'Layout Three', 'htmega-addons' ),
                    ],
                ]
            );
            $this->add_control(
                'show_datatable_sorting',
                [
                    'label'        => __( 'Show Sorting Options', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __( 'Show', 'htmega-addons' ),
                    'label_off'    => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default'      => 'no',
                ]
            );
            $this->add_control(
                'show_datatable_paging',
                [
                    'label' => __( 'Pagination', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'show_datatable_sorting' => 'yes'
                    ]
                ]
            );
            $this->add_control(
                'default_row_number',
                [
                    'label' => __( 'Default option for number of Rows', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 10,
                    'condition' =>[
                        'show_datatable_paging'=>'yes',
                        'show_datatable_sorting' => 'yes'
                    ]
                ]
            );
            $this->add_control(
                'custom_display_row',
                [
                    'label' => __( 'Custom options for number of Rows?', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' =>[
                        'show_datatable_paging'=>'yes',
                        'show_datatable_sorting' => 'yes'
                    ]
                ]
            );
            $this->add_control(
                'display_options',
                [
                    'label'   => __( 'Options for number of Rows', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __( '10,25,50,100', 'htmega-addons' ),
                    'description'=> esc_html__( 'Enter the number separate by comma(10,25,50,100)','htmega-addons'),
                    'condition'=> [
                        'custom_display_row'=>'yes',
                        'show_datatable_paging'=>'yes',
                        'show_datatable_sorting' => 'yes'
                    ]
                ]
            );

            $this->add_control(
                'show_all_button',
                [
                    'label' => __( 'Add all option', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' =>[
                        'show_datatable_paging'=>'yes',
                        'custom_display_row'=>'yes',
                        'show_datatable_sorting' => 'yes'
                    ]
                ]
            );
            $this->add_control(
                'show_all_text',
                [
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'All', 'htmega-addons' ),
                    'label_block'=>true,
                    'condition'=>[
                        'show_all_button'=>'yes',
                        'show_datatable_paging'=>'yes',
                        'custom_display_row'=>'yes',
                        'show_datatable_sorting' => 'yes'
                    ],
                   
                ]
            );
            $this->add_control(
                'show_datatable_searching',
                [
                    'label' => __( 'Searching', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' =>'before',
                    'condition'=>[
                        'show_datatable_sorting' => 'yes'
                    ],
                ]
            );

            $this->add_control(
                'show_datatable_ordering',
                [
                    'label' => __( 'Ordering', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition'=>[
                        'show_datatable_sorting' => 'yes'
                    ],
                ]
            );

            $this->add_control(
                'show_datatable_info',
                [
                    'label' => __( 'Footer Info', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'htmega-addons' ),
                    'label_off' => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition'=>[
                        'show_datatable_sorting' => 'yes'
                    ],
                ]
            );
            $this->add_control(
                'export_data',
                [
                    'label'        => __( 'Data Export Button (Pro)', 'htmega-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __( 'Show', 'htmega-addons' ),
                    'label_off'    => __( 'Hide', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'default'      => 'no',
                ]
            );

            $this->pro_notice( $this,'export_data', 'yes' );
        $this->end_controls_section();


        // Style tab section
        $this->start_controls_section(
            'htmega_table_style_section',
            [
                'label' => __( 'Table', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'datatable_bg_color',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper',
                ]
            );

            
            $this->add_responsive_control(
                'datatable_padding',
                [
                    'label' => esc_html__( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                            '{{WRAPPER}} .htmega-table-style' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'datatable_margin',
                [
                    'label' => esc_html__( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                            '{{WRAPPER}} .htmega-table-style' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                        'name' => 'datatable_border',
                        'label' => esc_html__( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-table-style',
                    ]
            );

            $this->add_responsive_control(
                'datatable_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'datatable_border_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style',
                ]
            );
            
        $this->end_controls_section();

        // Table Header Style tab section
        $this->start_controls_section(
            'htmega_table_header_style_section',
            [
                'label' => __( 'Table Header', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'datatable_header_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style thead tr th' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'datatable_header_bg_color',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-table-style thead tr th',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'datatable_header_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style thead tr th',
                ]
            );

            $this->add_responsive_control(
                'datatable_header_padding',
                [
                    'label' => esc_html__( 'Table Header Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                            '{{WRAPPER}} .htmega-table-style thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                        'name' => 'datatable_header_border',
                        'label' => esc_html__( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-table-style thead tr th',
                    ]
            );

            $this->add_responsive_control(
                'datatable_header_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style thead tr th' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'datatable_header_align',
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
                        '{{WRAPPER}} .htmega-table-style thead tr th' => 'text-align: {{VALUE}};',
                    ],
                    'default' => '',
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Table Body Style tab section
        $this->start_controls_section(
            'htmega_table_body_style_section',
            [
                'label' => __( 'Table Body', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'datatable_body_bg_color',
                [
                    'label' => esc_html__( 'Background Color ( Event )', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htb-table tbody tr:nth-child(even)' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'datatable_body_odd_bg_color',
                [
                    'label' => esc_html__( 'Background Color ( Odd )', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htb-table tbody tr:nth-child(odd)' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                        'name' => 'datatable_body_all_border',
                        'label' => esc_html__( 'Table Body Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} table.dataTable.no-footer',
                    ]
            );
            $this->add_control(
                'datatable_body_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style tbody tr td' => 'color: {{VALUE}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'datatable_body_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style tbody tr td',
                ]
            );

            $this->add_responsive_control(
                'datatable_body_padding',
                [
                    'label' => esc_html__( 'Table Cell Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                            '{{WRAPPER}} .htmega-table-style tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                        'name' => 'datatable_body_border',
                        'label' => esc_html__( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-table-style tbody tr td',
                    ]
            );

            $this->add_responsive_control(
                'datatable_body_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style tbody tr td' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'datatable_body_align',
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
                        '{{WRAPPER}} .htmega-table-style tbody tr td' => 'text-align: {{VALUE}};',
                    ],
                    'default' => '',
                ]
            );

        $this->end_controls_section();

         // Entires section
         $this->start_controls_section(
            'entries_style_section',
                [
                    'label' => __( 'Entries Style', 'htmega-addons' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition'=>[
                        'show_datatable_paging'=>'yes',
                    ]
                ]
            );
            $this->add_control(
                'datatable_sorting_text_color_header',
                [
                    'label' => esc_html__( 'Label Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style .dataTables_length label' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .htmega-table-style .dataTables_filter label' => 'color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'show_datatable_paging'=>'yes',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'datatable_sorting_typography_header',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_length label',
                    'condition'=>[
                        'show_datatable_paging'=>'yes',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                    'name' => 'datatable_sorting_border_header',
                    'label' => esc_html__( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_length, {{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input, {{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_paginate',
                    'condition'=>[
                        'show_datatable_paging'=>'yes',
                    ]
                ]
            );
            $this->add_responsive_control(
                'entries_border_box_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_length' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_responsive_control(
                'header_bottom_space',
                [
                    'label' => esc_html__( 'Bottom Space', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 300,
                    'step' => 1,
                    'default' => 0,
                    'selectors' => [
                        '{{WRAPPER}} .dataTables_length,{{WRAPPER}} .dataTables_filter' => 'margin-bottom: {{VALUE}}px',
                    ],
                ]
            );
            $this->add_control(
                'htmega_table_sorting_length_pagination_header',
                [
                    'label' => esc_html__( 'Input Style', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'=>[
                        'show_datatable_paging'=>'yes',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'typography_header_slect_entries',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_length label select',
                    'condition'=>[
                        'show_datatable_paging'=>'yes',
                    ]
                ]
            );
            $this->start_controls_tabs('entries_style_tabs');

            $this->start_controls_tab(
                'entries_style_normal_tab',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );

                $this->add_control(
                    'datatable_sorting_length_text_color_header',
                    [
                        'label' => esc_html__( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .htmega-table-style .dataTables_length label select' => 'color: {{VALUE}};',
                        ],
                        'condition'=>[
                            'show_datatable_paging'=>'yes',
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'datatable_sorting_length_typography_background_color_header',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_length label select',
                        'condition'=>[
                            'show_datatable_paging'=>'yes',
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                        [
                        'name' => 'datatable_sorting_length_border_header',
                        'label' => esc_html__( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_length label select',
                        'condition'=>[
                            'show_datatable_paging'=>'yes',
                        ]
                    ]
                );

            $this->end_controls_tab(); // Normal Tab end

            $this->start_controls_tab(
                'entries_style_hover_tab',
                [
                    'label' => __( 'Focus', 'htmega-addons' ),
                ]
            );
                $this->add_control(
                    'entries_hover_color',
                    [
                        'label' => __( 'Color', 'htmega-addons' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-table-style .dataTables_length label select:hover,{{WRAPPER}} .htmega-table-style .dataTables_length label select:focus' => 'color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'entries_hover_background',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_length label select:hover,{{WRAPPER}} .htmega-table-style .dataTables_length label select:focus',
                        'exclude' =>['image'],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'entries_hover_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_length label select:hover,{{WRAPPER}} .htmega-table-style .dataTables_length label select:focus',
                    ]
                );

            $this->end_controls_tab(); // Hover Tab end

        $this->end_controls_tabs();
        $this->end_controls_section();
        // Info section
        $this->start_controls_section(
            'info_style_section',
                [
                    'label' => __( 'Info Style', 'htmega-addons' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition'=>[
                        'show_datatable_info'=>'yes',
                    ]
                ]
            );
            $this->add_control(
                'datatable_sorting_text_color_footer',
                [
                    'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style .dataTables_info' => 'color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'show_datatable_info'=>'yes',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'datatable_sorting_typography_footer',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_info',
                    'condition'=>[
                        'show_datatable_info'=>'yes',
                    ]
                ]
            );
            $this->add_responsive_control(
                'info_top_space',
                [
                    'label' => esc_html__( 'Top Space', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 300,
                    'step' => 1,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style .dataTables_info' => 'padding-top: {{VALUE}}px',
                    ],
                ]
            );
        $this->end_controls_section();

        // Search Box Style section
        $this->start_controls_section(
            'search_box_style_section',
                [
                    'label' => __( 'Search Box', 'htmega-addons' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition'=>[
                        'show_datatable_searching'=>'yes',
                    ]
                ]
            );
            $this->add_responsive_control(
                'search_box_space_bottom',
                [
                    'label' => esc_html__( 'Bottom Space', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 300,
                    'step' => 1,
                    'default' => 0,
                    'selectors' => [
                        '{{WRAPPER}} .dataTables_filter' => 'margin-bottom: {{VALUE}}px',
                    ],
                ]
            );
            $this->add_control(
                'search_box_label',
                [
                    'label' => __( 'Label Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style .dataTables_filter label' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'search_box_typography_label',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_filter label',
                ]
            );
            $this->add_control(
                'search_box_input_heading',
                [
                    'label' => esc_html__( 'Input Box', 'htmega-addons' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',

                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'search_box_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input',
                ]
            );

            $this->add_responsive_control(
                'search_box_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('search_box_style_tabs');

                $this->start_controls_tab(
                    'search_box_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'search_box_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input' => 'color: {{VALUE}};',
                            ],
                        ]
                    );


                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_box_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input',
                            'exclude' =>['image'],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'search_box_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_box_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );


                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'search_box_style_hover_tab',
                    [
                        'label' => __( 'Focus', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'search_box_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input:hover,{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input:focus' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_box_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input:hover,{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input:focus',
                            'exclude' =>['image'],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'search_box_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input:hover,{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input:focus',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_box_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input:hover,{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_filter input:focus' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();

        $this->end_controls_section();
         // Table Pagination Style tab section
         $this->start_controls_section(
            'htmega_table_sorting_style_section',
            [
                'label' => __( 'Pagination Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_datatable_paging'=>'yes',
                ]
            ]
        );

        
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'datatable_pagination_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_paginate a.paginate_button',
                ]
            );
            $this->add_responsive_control(
                'paginat_top_space',
                [
                    'label' => esc_html__( 'Top Space', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 300,
                    'step' => 1,
                    'default' => 0,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style .dataTables_paginate' => 'margin-top: {{VALUE}}px',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                    [
                    'name' => 'paginat_active_border_box',
                    'label' => esc_html__( 'Pagination Box Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_paginate',
                ]
            );
            $this->add_responsive_control(
                'paginat_active_border_box_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_paginate' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pagination_box_bg',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic','gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_wrapper .dataTables_paginate',
                ]
            );
            $this->start_controls_tabs('paginat_style_tabs');

                $this->start_controls_tab(
                    'paginat_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_control(
                        'datatable_pagination_text_color',
                        [
                            'label' => esc_html__( 'Text Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-table-style .dataTables_paginate a.paginate_button' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );
                
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'datatable_pagination_background_color',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_paginate span a.paginate_button,
                            {{WRAPPER}} .htmega-table-style .dataTables_paginate a.paginate_button',
                        ]
                    );
        
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                            [
                            'name' => 'datatable_pagination_border_header',
                            'label' => esc_html__( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_paginate span a.paginate_button,{{WRAPPER}} .htmega-table-style .dataTables_paginate a.paginate_button',
                        ]
                    );
                $this->end_controls_tab(); // Normal Tab end

                $this->start_controls_tab(
                    'paginat_style_hover_tab',
                    [
                        'label' => __( 'Active', 'htmega-addons' ),
                    ]
                );
                    $this->add_control(
                        'datatable_pagination_text_active_color',
                        [
                            'label' => esc_html__( 'Text Active Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-table-style .dataTables_paginate span a.paginate_button.current,
                                {{WRAPPER}} .htmega-table-style .dataTables_paginate a.paginate_button:not(.disabled):hover' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'paginat_active_bg',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_paginate span a.paginate_button.current,
                            {{WRAPPER}} .htmega-table-style .dataTables_paginate a.paginate_button:not(.disabled):hover',
                        ]
                    );
        
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                            [
                            'name' => 'paginat_active_border',
                            'label' => esc_html__( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-table-style .dataTables_paginate span a.paginate_button.current,
                            {{WRAPPER}} .htmega-table-style .dataTables_paginate a.paginate_button:not(.disabled):hover',
                        ]
                    );

                $this->end_controls_tab(); // Hover Tab end

            $this->end_controls_tabs();


        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id = $this->get_id();

        $this->add_render_attribute( 'datatable_attr', 'class', 'htmega-table-style htmega-table-style-'.$settings['datatable_style'] );

        if( $settings['show_datatable_sorting'] != 'yes' ){
            $this->add_render_attribute( 'datatable_attr', 'class', 'htb-table-responsive' );
        }

        $table_tr = array();
        $table_td = array();

        foreach( $settings['content_list'] as $content_row ) {

            $row_id = uniqid();
            if( $content_row['field_type'] == 'row' ) {
                $table_tr[] = [
                    'id' => $row_id,
                    'type' => $content_row['field_type'],
                ];
            }
            if( $content_row['field_type'] == 'col' ) {

                $table_tr_keys = array_keys( $table_tr );
                $last_key = end( $table_tr_keys );

                //Image Control
                if( isset($content_row['cell_image']) ){
                    $table_cell_data = Group_Control_Image_Size::get_attachment_image_html( $content_row, 'large', 'cell_image' );
                
                //Icon Control
                }elseif( isset($content_row['cell_icon']) ){
                    $table_cell_data = '<div class="elementor-repeater-item-'.$content_row['_id'].'">' . HTMega_Icon_manager::render_icon( $content_row['cell_icon'], [ 'aria-hidden' => 'false' ] ) . '</div>';
                
                //Text Control
                }else{
                    $table_cell_data = $content_row['cell_text'];
                }
                
                $table_td[] = [
                    'row_id' => $table_tr[$last_key]['id'],
                    'title' => $table_cell_data,
                    'colspan' => $content_row['row_colspan'],
                    'content_sal_id' => $content_row['_id'],
                ];
            }

        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'datatable_attr' ); ?>>
            <table class="htb-table <?php if( $settings['show_datatable_sorting'] == 'yes' ){ echo 'htmega-datatable-'.esc_attr( $id ); } ?>">
                <?php if( $settings['header_column_list'] ): ?>
                    <thead>
                        <tr>
                            <?php
                                foreach ( $settings['header_column_list'] as $headeritem ) {
                                    echo "<th class='elementor-repeater-item-". esc_attr( $headeritem['_id'] )."'>".esc_html__( $headeritem['column_name'],'htmega-addons' ).'</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                <?php endif;?>
                <tbody>
                    <?php for( $i = 0; $i < count( $table_tr ); $i++ ) : ?>
                        <tr>
                            <?php
                                for( $j = 0; $j < count( $table_td ); $j++ ):
                                    if( $table_tr[$i]['id'] == $table_td[$j]['row_id'] ):
                                        printf('<td class="elementor-repeater-item-%1$s" %2$s>%3$s</td>',
                                            esc_attr( $table_td[$j]['content_sal_id'] ),
                                            ( $table_td[$j]['colspan'] > 1 ) ? ' colspan="'.esc_attr( $table_td[$j]['colspan']).'"' : '',
                                            $table_td[$j]['title']
                                        );
                                    endif; 
                                endfor; 
                            ?>
                        </tr>
                    <?php endfor;?>
                </tbody>
            </table>
        </div>
        <?php if( $settings['show_datatable_sorting'] == 'yes' ): 

        $display_options = '10, 25, 50, 100';
        $show_all_num ='';
        $show_all_text ='';
        $default_row_number = 10;
            if( 'yes' == $settings['custom_display_row'] && !empty( $settings['display_options'] ) ){
                $display_options = $settings['display_options'];
            }
            if( 'yes'== $settings['show_all_button'] && !empty( $settings['show_all_text'] ) ){
                $show_all_text = " ,'".esc_html($settings['show_all_text'])."'";
                $show_all_num =', -1';
            }
            if(!empty($settings['default_row_number'])){
                $default_row_number = $settings['default_row_number'];
            }
            ?>

            <script>
                jQuery(document).ready(function($) {
                    'use strict';
                    $('.htmega-datatable-<?php echo esc_attr( $id ); ?>').DataTable({
                        paging: <?php echo esc_js(( $settings['show_datatable_paging'] == 'yes' ) ? 'true' : 'false'); ?>,
                        searching: <?php echo esc_js(( $settings['show_datatable_searching'] == 'yes' ) ? 'true' : 'false'); ?>,
                        ordering:  <?php echo esc_js(( $settings['show_datatable_ordering'] == 'yes' ) ? 'true' : 'false'); ?>,
                        info: <?php echo esc_js(( $settings['show_datatable_info'] == 'yes' ) ? 'true' : 'false'); ?>,
                        pageLength: <?php echo esc_js($default_row_number ); ?>,
                        lengthMenu: [ [<?php echo esc_js( $display_options ).$show_all_num;?>], [<?php echo esc_js( $display_options ).$show_all_text;?>] ]
                    });
                 });
            </script>
        <?php endif;
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
                'content_classes' => 'htmega-addons-notice',
                'condition' => [
                    $condition_key => $array_value,
                ]
            ]
        );
    }
}

