<?php

namespace WprAddons\Modules\DataTable\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;



// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

class Wpr_Data_Table extends Widget_Base {
    public function get_name() {
		return 'wpr-data-table';
	}

	public function get_title() {
		return esc_html__('Data Table', 'wpr-addons');
	}
	public function get_icon() {
		return 'wpr-icon eicon-table';
	}

	public function get_categories() {
		return ['wpr-widgets'];
	}

	public function get_keywords() {
		return ['royal', 'data table', 'advanced', 'table', 'data', 'comparison table', 'table comparison'];
	}

	public function get_script_depends() {
		return ['wpr-table-to-excel-js'];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-pricing-table-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_choose_table_type() {
		$this->add_control(
			'choose_table_type',
			[
				'label' => esc_html__( 'Data Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'render_type' => 'template',
				'options' => [
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
					'pro-cv' => esc_html__( 'CSV (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-data-table-type-'
			]
		);
	}

	public function add_control_enable_table_export() {
		$this->add_control(
			'enable_table_export',
			[
				'label' => sprintf( __( 'Show Export Buttons %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_control_export_excel_text() {}

	public function add_control_export_buttons_distance() {}

	public function add_control_table_search_input_padding() {}

	public function add_control_export_csv_text() {}

	public function add_section_export_buttons_styles() {}

	public function add_control_enable_table_search() {
		$this->add_control(
			'enable_table_search',
			[
				'label' => sprintf( __( 'Show Search %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_section_search_styles() {}

	public function add_control_enable_table_sorting() {
		$this->add_control(
			'enable_table_sorting',
			[
				'label' => sprintf( __( 'Show Sorting %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_control_active_td_bg_color() {}

	public function add_control_enable_custom_pagination() {
		$this->add_control(
			'enable_custom_pagination',
			[
				'label' => sprintf( __( 'Show Pagination %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_section_pagination_styles() {}

	public function add_control_stack_content_tooltip_section() {}

	public function add_repeater_args_content_tooltip() {
			return [
				'label' => sprintf( __( 'Show Tooltip %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			];
	}

	public function add_repeater_args_content_tooltip_text() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_content_tooltip_show_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

    public function register_controls() {

		$this->start_controls_section(
			'section_preview',
			[
				'label' => esc_html__('General', 'wpr-addons'),
			]
		);
		
		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		// Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_choose_table_type();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'data-table', 'choose_table_type', ['pro-cv'] );

		// $this->add_control(
		// 	'enable_custom_links',
		// 	[
		// 		'label' => esc_html__('Custom Links', 'wpr-addons'),
		// 		'type' => \Elementor\Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'default' => 'no',
		// 		'separator' => 'before',
		// 		'condition' => [
		// 			'choose_table_type' => 'csv',
		// 		]
		// 	]
		// );

		$this->add_control_enable_table_export();

		$this->add_control_export_excel_text();

		$this->add_control_export_csv_text();

		$this->add_control_enable_table_search();

		$this->add_control_enable_table_sorting();

		$this->add_control_enable_custom_pagination();

		$this->add_control(
			'equal_column_width',
			[
				'label' => esc_html__('Equal Column Width', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'prefix_class' => 'wpr-equal-column-width-'
			]
		);

		$this->add_control(
			'enable_row_pagination', 
			[
				'label' => esc_html__('Table Row Index', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'white_space_text',
			[
				'label' => esc_html__('Prevent Word Wrap', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'wpr-table-text-nowrap-',
				'separator' => 'before'
			]
		);

		// $this->add_control(
		// 	'enable_columns_control',
		// 	[
		// 		'label' => esc_html__('Columns', 'wpr-addons'),
		// 		'type' => \Elementor\Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'default' => 'no',
		// 		'separator' => 'before'
		// 	]
		// );

		// $this->add_control(
		// 	'columns_number',
		// 	[
		// 		'label' => esc_html__( 'Quantity', 'wpr-addons' ),
		// 		'type' => Controls_Manager::NUMBER,
		// 		'min' => 1,
		// 		'max' => 100,
		// 		'render_type' => 'template',
		// 		'frontend_available' => true,
		// 		'default' => 10,
		// 		'condition' => [
		// 			'enable_columns_control' => 'yes'
		// 		]
		// 	]
		// );

        $this->add_control(
            'table_export_csv_button',
            [
                'label' => esc_html__('Export table as CSV file', 'wpr-addons'),
                'type'  => Controls_Manager::BUTTON,
                'text'  => esc_html__('Export', 'wpr-addons'),
                'event' => 'my-table-export',
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();

		// $this->start_controls_section(
		// 	'custom_links_section',
		// 	[
		// 		'label' => esc_html__( 'Custom Links', 'wpr-addons' ),
		// 		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		// 		'condition' => [
		// 			'choose_table_type' => 'csv',
		// 			'enable_custom_links' => 'yes',
		// 		]
		// 	]
		// );

		// $repeater = new \Elementor\Repeater();

		// $repeater->add_control(
		// 	'custom_link_title', [
		// 		'label' => esc_html__( 'Title', 'plugin-name' ),
		// 		'type' => \Elementor\Controls_Manager::TEXT,
		// 		'default' => esc_html__( 'Custom Link Title' , 'plugin-name' ),
		// 		'label_block' => true,
		// 	]
		// );

        // $repeater->add_control(
        //     'table_custom_link',
        //     [
        //         'label'         => esc_html__( 'Custom Link', 'wpr-addons' ),
        //         'type'          => Controls_Manager::URL,
        //         'show_external' => false,
        //         'label_block'   => true,
        //     ]
        // );

		// $repeater->add_control(
		// 	'custom_link_tr_index',
		// 	[
		// 		'label'			=> esc_html__( 'Table Row Index', 'wpr-addons'),
		// 		'type'			=> Controls_Manager::NUMBER,
		// 		'default' 		=> 0,
		// 		'min'     		=> 0,
		// 	]
		// );

		// $this->add_control(
		// 	'custom_links',
		// 	[
		// 		'label' => esc_html__( 'Custom Links', 'wpr-addons' ),
		// 		'type' => \Elementor\Controls_Manager::REPEATER,
		// 		'fields' => $repeater->get_controls(),
		// 		'default' => [
		// 			[
		// 				'table_custom_link' => esc_html__( 'X', 'plugin-name' ),
		// 				'custom_link_tr_index' => esc_html__( 'Change This', 'plugin-name' ),
		// 			],
		// 		],
		// 		'title_field' => '{{{ custom_link_title }}}',
		// 	]
		// );

		// $this->end_controls_section();

		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__('Header', 'wpr-addons'),
				'condition' => [
					'choose_table_type' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'table_th', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Table Title' , 'wpr-addons' ),
				'label_block' => true
			]
		);
		
		$repeater->add_responsive_control(
			'header_icon',
			[
				'label' => esc_html__('Media', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'header_icon_type',
			[
				'label' => esc_html__('Media Type', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__('Icon', 'wpr-addons'),
					'image' => esc_html__('Image', 'wpr-addons'),
				],
				'condition' => [
					'header_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'header_icon_position',
			[
				'label' => esc_html__('Media Position', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Left', 'wpr-addons'),
					'right' => esc_html__('Right', 'wpr-addons'),
					'top' => esc_html__('Top', 'wpr-addons'),
					'bottom' => esc_html__('Bottom', 'wpr-addons'),
				],
				'condition' => [
					'header_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'choose_header_col_icon',
			[
				'label' => esc_html__('Select Icon', 'wpr-addons'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'header_icon' => 'yes',
					'header_icon_type' => 'icon',
				]

			]
		);

		$repeater->add_control(
			'header_col_img',
			[
				'label' => esc_html__( 'Image', 'wpr-addons'),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'header_icon_type'	=> 'image'
				]
			]
		);

		$repeater->add_responsive_control(
			'header_col_img_size',
			[
				'label' => esc_html__( 'Image Size', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 100,
					'unit' => 'px'
				],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-data-table-th-img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
				],
				'condition' => [
					'header_icon_type'	=> 'image'
				]
			]
		);
		
		$repeater->add_control(
			'header_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}'
				],
				'condition' => [
					'header_icon' => 'yes',
					'header_icon_type'	=> 'icon'
				]
			]
		);

		$repeater->add_control(
			'header_th_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
				],
			]
		);

		$repeater->add_control(
			'header_th_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'header_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'wpr-addons'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'separator' => 'before'
			]
		);

		$repeater->add_responsive_control(
			'th_individual_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'table_header',
			[
				'label' => esc_html__( 'Repeater Table Header', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'table_th' => esc_html__( 'TABLE HEADER 1', 'wpr-addons' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 2', 'wpr-addons' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 3', 'wpr-addons' ),
					],
					[
						'table_th' => esc_html__( 'TABLE HEADER 4', 'wpr-addons' ),
					],
				],
				'title_field' => '{{{ table_th }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__('Content', 'wpr-addons'),
				'condition' => [
					'choose_table_type' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'table_content_row_type',
			[
				'label' => esc_html__( 'Row Type', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'row',
				'label_block' => false,
				'options' => [
					'row' => esc_html__( 'Row', 'wpr-addons'),
					'col' => esc_html__( 'Column', 'wpr-addons'),
				]
			]
		);

		$repeater->add_control(
			'table_td', 
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Content' , 'wpr-addons' ),
				'show_label' => true,
				'separator' => 'before',
				'condition' => [
					'table_content_row_type' => 'col',
				]
			]
		);

		$repeater->add_control(
			'cell_link',
			[
				'label' => esc_html__( 'Content URL', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'show_external' => true,
				'default' => [
					'url' => 'https://royal-elementor-addons.com/',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'table_content_row_type' => 'col',
				]
			]
		);

		$repeater->add_responsive_control(
			'td_icon',
			[
				'label' => esc_html__('Media', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);
		$repeater->add_control(
			'td_icon_type',
			[
				'label' => esc_html__('Media Type', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__('Icon', 'wpr-addons'),
					'image' => esc_html__('Image', 'wpr-addons')
				],
				'condition' => [
					'td_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'td_icon_position',
			[
				'label' => esc_html__('Media Position', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Left', 'wpr-addons'),
					'right' => esc_html__('Right', 'wpr-addons'),
					'top' => esc_html__('Top', 'wpr-addons'),
					'bottom' => esc_html__('Bottom', 'wpr-addons'),
				],
				'condition' => [
					'td_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'choose_td_icon',
			[
				'label' => esc_html__('Select Icon', 'wpr-addons'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type' => 'icon'
				]

			]
		);

		$repeater->add_control(
			'td_col_img',
			[
				'label' => esc_html__( 'Image', 'wpr-addons'),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'icon']
				]
			]
		);

		$repeater->add_responsive_control(
			'td_col_img_size',
			[
				'label' => esc_html__( 'Image Size', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 100,
					'unit' => 'px'
				],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'icon']
				]
			]
		);

        $repeater->add_responsive_control(
            'td_col_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'wpr-addons' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', 'em', 'rem',
				],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
					],
				],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wpr-td-content-wrapper i:not(.fa-question-circle)' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type!'	=> ['none', 'image']
				]
			]
        );

		$repeater->add_control(
			'td_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}'
				],
				'condition' 	=> [
					'table_content_row_type' => 'col',
					'td_icon' => 'yes',
					'td_icon_type' => 'icon'
				]
			]
		);

		$repeater->add_control( 'content_tooltip', $this->add_repeater_args_content_tooltip() );

		$repeater->add_control( 'content_tooltip_text', $this->add_repeater_args_content_tooltip_text() );

		$repeater->add_control( 'content_tooltip_show_icon', $this->add_repeater_args_content_tooltip_show_icon() );

		$repeater->add_control(
			'td_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-table-text' => 'color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'td_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
				],
			]
		);

		$repeater->add_control(
			'td_background_color_hover',
			[
				'label' => esc_html__( 'Background Color (Hover)', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}} !important'
				],
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'table_content_row_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'wpr-addons'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> false,
				'separator' => 'before',
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'table_content_row_rowspan',
			[
				'label'			=> esc_html__( 'Row Span', 'wpr-addons'),
				'type'			=> Controls_Manager::NUMBER,
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> false,
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_responsive_control(
			'td_individual_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important;',
				],
			]
		);
	
		$this->add_control(
			'table_content_rows',
			[
				'label' => esc_html__( 'Repeater Table Rows', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'table_content_row_type' => 'row' ],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 1'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 2'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 3'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 4'
					],
					[ 'table_content_row_type' => 'row' ],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 1'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 2'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 3'
					],
					[ 
						'table_content_row_type' => 'col',
						'table_td' => 'Content 4'
					],
				],
				'title_field' => '{{table_content_row_type}}::{{table_td}}',
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'data-table', [
			'Import Table data from CSV file upload or URL',
			'Show/Hide Export Table data buttons',
			'Enable Live Search for Tables',
			'Enable Table Sorting option',
			'Enable Table Pagination. Divide Table items by pages',
			'Enable Tooltips on each cell'
		] );

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__('General', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_responsive_width',
			[
				'label' => esc_html__( 'Table Min Width', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500
					]
				],
				// 'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 600,
					'unit' => 'px'
				],
				// 'desktop_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				// 'tablet_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				// 'mobile_default' => [
				// 	'size' => 100,
				// 	'unit' => '%',
				// ],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-container .wpr-data-table' => 'min-width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-export-search-inner-cont' => 'min-width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-table-custom-pagination' => 'width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-table-pagination-cont' => 'min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-table-inner-container' => 'width: 100%;',
					'{{WRAPPER}} .wpr-data-table' => 'width: 100%;',
				],
				// 'separator' => 'before'
			]
		);

		$this->add_control(
			'all_border_type',
			[
				'label' => esc_html__('Border', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-inner-container' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} th.wpr-table-th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} th.wpr-table-th-pag' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} td.wpr-table-td' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} td.wpr-table-td-pag' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'all_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E4E4E4',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-inner-container' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} th.wpr-table-th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} th.wpr-table-th-pag' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} td.wpr-table-td' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} td.wpr-table-td-pag' => 'border-color: {{VALUE}}'
				],
				'condition' => [
					'all_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'all_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-inner-container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} th.wpr-table-th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} th.wpr-table-th-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} td.wpr-table-td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} td.wpr-table-td-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'all_border_type!' => 'none',
				]
			]
		);

		$this->add_responsive_control(
			'header_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-inner-container' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} table' => 'border-radius: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} th:first-child' => 'border-top-left-radius: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} th:last-child' => 'border-top-right-radius: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} tr:last-child td:first-child' => 'border-bottom-left-radius: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} tr:last-child td:last-child' => 'border-bottom-right-radius: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control_export_buttons_distance();

		$this->add_control_table_search_input_padding();

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-th' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .wpr-table-th-pag' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .wpr-table-th i' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .wpr-table-td' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .wpr-table-td-pag' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .wpr-table-td i' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size',
					'{{WRAPPER}} .wpr-table-text' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size'
				],
				'separator' => 'before'
			]
		);

		// $this->add_control(
        //     'table_alignment',
        //     [
        //         'label'        => esc_html__('Alignment', 'wpr-addons'),
        //         'type'         => Controls_Manager::CHOOSE,
        //         'label_block'  => false,
        //         'default'      => 'center',
        //         'options'      => [
        //             'flex-start'   => [
        //                 'title' => esc_html__('Left', 'wpr-addons'),
        //                 'icon'  => 'eicon-h-align-left',
        //             ],
        //             'center' => [
        //                 'title' => esc_html__('Center', 'wpr-addons'),
        //                 'icon'  => 'eicon-h-align-center',
        //             ],
        //             'flex-end'  => [
        //                 'title' => esc_html__('Right', 'wpr-addons'),
        //                 'icon'  => 'eicon-h-align-right',
        //             ],
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-table-container .wpr-table-inner-container' => 'justify-content: {{VALUE}}',
		// 			'{{WRAPPER}} .wpr-table-container' => 'display: flex; justify-content: {{VALUE}}',
		// 			'{{WRAPPER}} .wpr-export-search-cont' => 'display: flex; justify-content: {{VALUE}}',
		// 			'{{WRAPPER}} .wpr-table-pagination-outer-cont' => 'display: flex; justify-content: {{VALUE}}'
		// 		],
		// 		'separator' => 'before'
        //     ]
        // );

		$this->end_controls_section();

		$this->start_controls_section(
			'header_style',
			[
				'label' => esc_html__('Header', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'th_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'th_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} tr th' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'th_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} th:hover' => 'color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'th_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} tr th:hover' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'th_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} th',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->add_responsive_control(
            'header_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'wpr-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpr-data-table thead i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpr-data-table thead svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
				'separator' => 'before'
            ]
        );

		$this->add_responsive_control(
            'header_sorting_icon_size',
            [
                'label'      => esc_html__('Sorting Icon Size', 'wpr-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpr-data-table thead .wpr-sorting-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'enable_table_sorting' => 'yes'
				]
            ]
        );

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

        $this->add_responsive_control(
            'header_image_space',
            [
                'label'      => esc_html__('Image Margin', 'wpr-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .wpr-data-table th img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

        $this->add_responsive_control(
            'header_icon_space',
            [
                'label'      => esc_html__('Icon Margin', 'wpr-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .wpr-data-table th i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

		$this->add_responsive_control(
			'th_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'wpr-table-align-items-',
				'selectors' => [
					'{{WRAPPER}} th:not(".wpr-table-th-pag")' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-th-inner-cont' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-flex-column span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-flex-column-reverse span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-table-th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_styles',
			[
				'label' => esc_html__('Content', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs(
			'cells_style_tabs'
		);

		$this->start_controls_tab(
			'cells_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'odd_cell_styles',
			[
				'label' => esc_html__('Odd Rows', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'odd_row_td_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					// '{{WRAPPER}} tr:nth-child(odd) td a' => 'color: {{VALUE}} !important',
					// '{{WRAPPER}} tr.wpr-odd td.wpr-table-text' => 'color: {{VALUE}}',
					// '{{WRAPPER}} tr.wpr-odd td a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td.wpr-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(odd) td a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td span' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'odd_row_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					// '{{WRAPPER}} tr.wpr-odd td' => 'background-color: {{VALUE}}', // TODO: decide tr or td
					'{{WRAPPER}} tbody tr:nth-child(odd) td' => 'background-color: {{VALUE}}', // TODO: decide tr or td
				],
			]
		);

		$this->add_control(
			'even_cell_styles',
			[
				'label' => esc_html__('Even Rows', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'even_row_td_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					// '{{WRAPPER}} tr.wpr-even td a .wpr-table-text' => 'color: {{VALUE}} !important',
					// '{{WRAPPER}} tr.wpr-even td.wpr-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td a .wpr-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td.wpr-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td.wpr-table-td-pag' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'even_row_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F3F3F3',
				'selectors' => [
					// '{{WRAPPER}} tr.wpr-even td' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} tbody tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cells_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'odd_cell_hover_styles',
			[
				'label' => esc_html__('Odd Rows', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'odd_row_td_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					// '{{WRAPPER}} tr.wpr-odd td:hover a' => 'color: {{VALUE}} !important',
					// '{{WRAPPER}} tr.wpr-odd td:hover.wpr-table-text' => 'color: {{VALUE}} !important',
					// '{{WRAPPER}} tr.wpr-odd td:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover span' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover.wpr-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(odd) td:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'odd_row_td_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					// '{{WRAPPER}} tr.wpr-odd:hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
					'{{WRAPPER}} tbody tr:nth-child(odd):hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'even_cell_hover_styles',
			[
				'label' => esc_html__('Even Rows', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'even_row_td_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				// 'selectors' => [
				// 	'{{WRAPPER}} tr.wpr-even td:hover.wpr-table-text' => 'color: {{VALUE}}',
				// 	'{{WRAPPER}} tr.wpr-even td:hover a .wpr-table-text' => 'color: {{VALUE}} !important',
				// 	'{{WRAPPER}} tr.wpr-even td:hover i' => 'color: {{VALUE}}',
				// ],
				'selectors' => [
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover.wpr-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover a .wpr-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tbody tr:nth-child(even) td:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'even_row_td_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					// '{{WRAPPER}} tr.wpr-even:hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
					'{{WRAPPER}} tbody tr:nth-child(even):hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_active_td_bg_color();

		$this->add_control(
			'typograpphy_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'td_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} td, {{WRAPPER}} i.fa-question-circle',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->add_responsive_control(
            'tbody_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'wpr-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
				'separator' => 'before',
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpr-data-table tbody i:not(.fa-question-circle)' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'tbody_image_size',
            [
                'label'      => esc_html__('Image Size', 'wpr-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpr-data-table-th-img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

		$this->add_responsive_control(
			'td_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'tbody_image_border_radius',
			[
				'label' => esc_html__( 'Image Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-table-th-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'td_img_space',
            [
                'label'      => esc_html__('Image Margin', 'wpr-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .wpr-data-table td img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

        $this->add_responsive_control(
            'td_icon_space',
            [
                'label'      => esc_html__('Icon Margin', 'wpr-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .wpr-data-table td i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

		$this->add_responsive_control(
			'td_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => ' eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => ' eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => ' eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} td:not(".wpr-table-td-pag")' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-td-content-wrapper span' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-table-td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->add_section_export_buttons_styles();

		$this->add_section_pagination_styles();

		$this->add_control_stack_content_tooltip_section();
	
    }

	public function render_content_tooltip($item) {}

	public function render_tooltip_icon($item) {}

	public function render_custom_pagination($settings, $countRows) {}

	protected function render_csv_data($url, $custom_pagination, $sorting_icon, $settings) {
		
		$url_ext = pathinfo($url, PATHINFO_EXTENSION);
		$url_ext2 = pathinfo($url);

		ob_start();
		if( $url_ext === 'csv' || str_contains($url_ext2['dirname'], 'docs.google.com/spreadsheets') ) {
			if (str_contains($url_ext2['dirname'], 'docs.google.com/spreadsheets')) {
				$url = $settings['table_insert_url']['url'];
			}
			echo $this->wpr_parse_csv_to_table($url, $settings, $custom_pagination, $sorting_icon );
		} else {
			echo '<p class="wpr-no-csv-file-found">'. esc_html__('Please provide a CSV file.', 'wpr-addons') .'</p>';
		}
		return \ob_get_clean();

	}

	protected function wpr_parse_csv_to_table($filename, $settings, $custom_pagination, $sorting_icon ) {

		$handle = fopen($filename, "r");
		
		// Determine the delimiter
		$delimiter = $this->detect_csv_delimiter($filename);
		//display header row if true
		echo '<table class="wpr-append-to-scope wpr-data-table">';
		if ( 'yes' === $settings['display_header'] ) {
			$csvcontents = fgetcsv($handle, 0, $delimiter);
			echo '<thead><tr class="wpr-table-head-row wpr-table-row">';
			foreach ($csvcontents as $headercolumn) {
				echo "<th class='wpr-table-th wpr-table-text'>$headercolumn  $sorting_icon</th>";
			}
			echo '</tr></thead>';
		}
		echo '<tbody>';

		// displaying contents
		$countRows = 0;
		$oddEven = '';
		while ($csvcontents = fgetcsv($handle, 0, $delimiter)) {
				$countRows++;
				$oddEven = $countRows % 2 == 0 ? 'wpr-even' : 'wpr-odd';
				echo '<tr class="wpr-table-row  '. esc_attr($oddEven) .'">';
				foreach ($csvcontents as $column) {
					echo '<td class="wpr-table-td wpr-table-text">'. $column .'</td>';
				}
				echo '</tr>';
		}
		echo '</tbody></table>';
		echo '</div>';
		echo '</div>';

		if ( 'yes' == $settings['enable_custom_pagination'] ) {
			$this->render_custom_pagination($settings, $countRows);
		} 

		fclose($handle);
	}

	protected function detect_csv_delimiter($filename) {
		$delimiters = [',', ';'];
		$counts = [];
		$maxCount = 0;
		$bestDelimiter = ',';
	
		$handle = fopen($filename, "r");
		$firstLine = fgets($handle);
		fclose($handle);
	
		foreach ($delimiters as $delimiter) {
			$counts[$delimiter] = count(str_getcsv($firstLine, $delimiter));
		}
	
		foreach ($counts as $delimiter => $count) {
			if ($count > $maxCount) {
				$maxCount = $count;
				$bestDelimiter = $delimiter;
			}
		}
	
		return $bestDelimiter;
	}

	public function render_th_icon($item) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($item['choose_header_col_icon'], ['aria-hidden' => 'true']);
		return ob_get_clean();
	}

	public function render_th_icon_or_image($item, $i) {
		if ( $item['header_icon'] === 'yes' && $item['header_icon_type'] === 'icon' ) {
			$header_icon = '<span style="display: inline-block; vertical-align: middle;">'. $this->render_th_icon($item) . '</span>';
		}

		if( $item['header_icon'] == 'yes' && $item['header_icon_type'] == 'image' ) {
			$this->add_render_attribute('wpr_table_th_img'. $i, [
				'src'	=> esc_url( $item['header_col_img']['url'] ),
				'class'	=> 'wpr-data-table-th-img',
				'alt'	=> esc_attr(get_post_meta($item['header_col_img']['id'], '_wp_attachment_image_alt', true))
			]);

			$header_icon = '<img'.' '. $this->get_render_attribute_string('wpr_table_th_img'. $i) . '>';
		}

		echo $header_icon;
	}

	public function render_td_icon($table_td, $j) {
		ob_start();
		\Elementor\Icons_Manager::render_icon($table_td[$j]['icon_item'], ['aria-hidden' => 'true']);
		return ob_get_clean();
	}

	public function render_td_icon_or_image($table_td, $j) {
		if ( $table_td[$j]['icon'] === 'yes' && $table_td[$j]['icon_type'] == 'icon' ) {
			$tbody_icon = '<span style="display: inline-block; vertical-align: middle;">'. $this->render_td_icon($table_td, $j) . '</span>';
		}

		if ( $table_td[$j]['icon'] == 'yes' && $table_td[$j]['icon_type'] == 'image' ) { 
            $this->add_render_attribute('wpr_table_td_img'. esc_attr($j), [
                'src'	=> esc_url( $table_td[$j]['col_img']['url'] ),
                'class'	=> 'wpr-data-table-th-img',
                'alt'	=> esc_attr(get_post_meta($table_td[$j]['col_img']['id'], '_wp_attachment_image_alt', true))
            ]);

			$tbody_icon = '<img' . ' ' . $this->get_render_attribute_string('wpr_table_td_img'. esc_attr($j)) . '>';
		}

		echo $tbody_icon;
	}

	public function render_search_export() {}

    protected function render() {
		$settings = $this->get_settings_for_display(); 

		$table_tr = [];
		$table_td = [];
		?>

		<?php

		// Render Search and/or Export Buttons
		$this->render_search_export();
		
		$x = 0;
		
		$sorting_icon = ('yes' === $settings['enable_table_sorting'] && wpr_fs()->can_use_premium_code() ) ? '<span class="wpr-sorting-icon"><i class="fas fa-sort"></i></span>' : ''; 
		
		$this->add_render_attribute(
			'wpr_table_inner_container_attributes',
			[
				'class' => ['wpr-table-inner-container', 'yes' === $settings['enable_custom_pagination'] ? 'wpr-hide-table-before-arrange' : ''],
				// 'data-table-columns' => !empty($settings['columns_number']) ? $settings['columns_number'] : '',
				'data-table-sorting' => $settings['enable_table_sorting'],
				'data-custom-pagination' => $settings['enable_custom_pagination'],
				'data-row-pagination' => $settings['enable_row_pagination'],
				'data-entry-info' => wpr_fs()->can_use_premium_code() ? $settings['enable_entry_info'] : 'no',
				'data-rows-per-page' => isset($settings['table_items_per_page']) ? $settings['table_items_per_page'] : ''
			]
		);

		?>
		
		<div class="wpr-table-container">
		<div <?php echo $this->get_render_attribute_string( 'wpr_table_inner_container_attributes' ); ?>>

		<?php if ( isset($settings['choose_csv_type']) && 'file' === $settings['choose_csv_type'] ) {

			echo $this->render_csv_data($settings['table_upload_csv']['url'], $settings['enable_custom_pagination'], $sorting_icon, $settings);

		} elseif ( isset($settings['choose_csv_type']) && 'url' === $settings['choose_csv_type']) {

			echo $this->render_csv_data(esc_url($settings['table_insert_url']['url']), esc_attr($settings['enable_custom_pagination']), $sorting_icon, $settings);

		} else {

			// Storing Data table content values
			$countRows = 0;
			foreach( $settings['table_content_rows'] as $content_row ) {
				$countRows++;
				$oddEven = $countRows % 2 == 0 ? 'wpr-even' : 'wpr-odd';
				$row_id = uniqid();

				if( $content_row['table_content_row_type'] == 'row' ) {
					$table_tr[] = [
						'id' => $row_id,
						'type' => $content_row['table_content_row_type'],
						'class' => ['wpr-table-body-row', 'wpr-table-row', 'elementor-repeater-item-'. esc_attr($content_row['_id']), esc_attr($oddEven)]
					];
				}

				if( $content_row['table_content_row_type'] == 'col' ) {

					$table_tr_keys = array_keys( $table_tr );
					$last_key = end( $table_tr_keys );

					$table_td[] = [
						'row_id' => $table_tr[$last_key]['id'],
						'type' => $content_row['table_content_row_type'],
						'content' => $content_row['table_td'],
						'colspan' => $content_row['table_content_row_colspan'],
						'rowspan' => $content_row['table_content_row_rowspan'],
						'link' => $content_row['cell_link'],
						'external' => $content_row['cell_link']['is_external'] == true ? '_blank' : '_self',
						'icon_type' => $content_row['td_icon_type'],
						'icon' => $content_row['td_icon'],
						'icon_position' => $content_row['td_icon_position'],
						'icon_item' => $content_row['choose_td_icon'],
						'col_img' => $content_row['td_col_img'],
						'class' => ['elementor-repeater-item-'. esc_attr($content_row['_id']), 'wpr-table-td'],
						'content_tooltip' => $content_row['content_tooltip'],
						'content_tooltip_text' => $content_row['content_tooltip_text'],
						'content_tooltip_show_icon' => $content_row['content_tooltip_show_icon']
					];
				}
			} ?>

			<table class="wpr-data-table" id="wpr-data-table">
			<?php if ( $settings['table_header'] ) { ?>
					
				<thead>
					<tr class="wpr-table-head-row wpr-table-row">
					<?php $i = 0; foreach ($settings['table_header'] as $item) { 

						$this->add_render_attribute('th_class'. esc_attr($i), [
							'class' => ['wpr-table-th', 'elementor-repeater-item-'. esc_attr($item['_id'])],
							'colspan' => $item['header_colspan'],
						]); 
						
						$this->add_render_attribute('th_inner_class'. esc_attr($i), [
							'class' => [($item['header_icon_position'] === 'top') ? 'wpr-flex-column-reverse' : (($item['header_icon_position'] === 'bottom') ? 'wpr-flex-column' : '')],
						]); ?>

						<th <?php echo $this->get_render_attribute_string('th_class'. esc_attr($i)); ?>>
							<div <?php echo $this->get_render_attribute_string('th_inner_class'. esc_attr($i)); ?>>
								<?php $item['header_icon'] === 'yes'  && $item['header_icon_position'] == 'left' ? $this->render_th_icon_or_image($item, $i) : '' ?>
								
								<?php if ( '' !== $item['table_th'] ) :  ?>
									<span class="wpr-table-text"><?php echo esc_html($item['table_th']); ?></span>
								<?php endif; ?>
								<?php $item['header_icon'] === 'yes' && $item['header_icon_position'] == 'right' ? $this->render_th_icon_or_image($item, $i) : '' ?>
								<?php echo $sorting_icon; ?>
								<?php $item['header_icon'] === 'yes' && ($item['header_icon_position'] == 'top' || $item['header_icon_position'] == 'bottom')? $this->render_th_icon_or_image($item, $i) : '' ?>
								<?php echo $sorting_icon; ?>
							</div>
						</th>
						<?php $i++; } ?>
					</tr>
				</thead>

				<tbody>
				<?php for( $i = 0 + $x; $i < count( $table_tr ) + $x; $i++ ) :

						$this->add_render_attribute('table_row_attributes'. esc_attr($i), [
							'class' => $table_tr[$i]['class'],
						]);

						?>
					<tr <?php echo $this->get_render_attribute_string('table_row_attributes'. esc_attr($i)) ?>>
					<?php for( $j = 0; $j < count( $table_td ); $j++ ) {
							if( $table_tr[$i]['id'] == $table_td[$j]['row_id'] ) {
								$this->add_render_attribute('tbody_td_attributes'. esc_attr($i . $j), [
								'colspan' => $table_td[$j]['colspan'] > 1 ? $table_td[$j]['colspan'] : '',
								'rowspan' => $table_td[$j]['rowspan'] > 1 ? $table_td[$j]['rowspan'] : '',
								'class' => $table_td[$j]['class']
								]); ?>
								
							<td <?php echo $this->get_render_attribute_string('tbody_td_attributes'. esc_attr($i . $j)); ?>>

								<div class="wpr-td-content-wrapper <?php echo esc_attr(('top' === $table_td[$j]['icon_position']) ? 'wpr-flex-column' : (('bottom' === $table_td[$j]['icon_position']) ? 'wpr-flex-column-reverse' : '')) ?>">

									<?php $table_td[$j]['icon'] === 'yes' && ($table_td[$j]['icon_position'] === 'left' || $table_td[$j]['icon_position'] === 'top' || $table_td[$j]['icon_position'] === 'bottom') ? $this->render_td_icon_or_image($table_td, $j) : '' ?>
									<?php if ( '' !== $table_td[$j]['content'] ) : 
										  if ( '' !== $table_td[$j]['link']['url'] ) : ?>
											<a href="<?php echo esc_url($table_td[$j]['link']['url']) ?>" target="<?php echo esc_attr($table_td[$j]['external']) ?>">
									<?php else : ?>
											<span>
									<?php endif; ?> 
											<span class="wpr-table-text">
												<?php 
													echo wp_kses_post( $table_td[$j]['content'] );

													$this->render_tooltip_icon( $table_td[$j] );
													
													$this->render_content_tooltip( $table_td[$j] ); 
												?>
											</span>
										<?php if ( '' !== $table_td[$j]['link']['url'] ) : ?>
										</a>
										<?php else : ?>
										</span>
										<?php endif; ?>
									<?php endif;  ?>
									<?php $table_td[$j]['icon'] === 'yes' && $table_td[$j]['icon_position'] === 'right' ? $this->render_td_icon_or_image($table_td, $j) : '' ?>

								</div>

							</td>
							<?php }
							} ?>
					</tr>
			        <?php endfor; ?>
				</tbody>
			</table>
		</div>
		</div>
    	<?php }
			if ( 'yes' == $settings['enable_custom_pagination'] ) {
				$this->render_custom_pagination($settings, null);
			}
		}
  	}
}