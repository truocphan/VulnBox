<?php
namespace WprAddons\Modules\Charts\Widgets;

use Elementor\Fonts;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Charts extends Widget_Base {
	
	public function get_name() {
		return 'wpr-charts';
	}

	public function get_title() {
		return esc_html__( 'Charts', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-align-end-v';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'line charts', 'radar charts', 'doughnut charts', 'pie charts', 'polararea charts', 'polar charts', 'bar charts', 'horizontal charts', 'vertical charts' ];
	}

	public function get_script_depends() {
		return [ 'wpr-charts' ];
	}

	public function get_style_depends() {
		return ['wpr-loading-animations-css'];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_choose_chart_data_source() {
		$this->add_control(
			'data_source',
			[
				'label'              => __( 'Data Source', 'wpr-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'custom',
				'options'            => [
					'custom' => __( 'Custom', 'wpr-addons' ),
					'pro-csv'    => 'CSV (Pro)' . __( ' File', 'wpr-addons' ),
				],
				'frontend_available' => true,
			]
		);
	}

    protected function register_controls() {

        $this->start_controls_section(
            'section_chart_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_choose_chart_data_source();

		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'charts', 'data_source', ['pro-csv'] );

		$this->add_control(
			'csv_source',
			[
				'label'              => __( 'CSV Source', 'wpr-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'url',
				'options'            => [
					'url' => __( 'Remote URL', 'wpr-addons' ),
					'file' => __( ' File', 'wpr-addons' ),
				],
				'condition'   => [
					'data_source' => 'csv',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'data_csv_separator',
			[
				'label'       => __( 'Separator', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default' => ',',
				'label_block' => true,
				'condition'   => [
					'data_source' => 'csv',
				],
			]
		);

		$this->add_control(
			'data_source_csv_url',
			[
				'label'       => __( 'Remote URL', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'condition'   => [
					'data_source' => 'csv',
					'csv_source' => 'url',
				],
			]
		);

		$this->add_control(
			'data_source_csv_file',
			[
				'label'       => __( 'Upload CSV File', 'wpr-addons' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'dynamic'     => ['
					active' => true,
				],
				'media_type'  => [],
				'label_block' => true,
				'condition'   => [
					'data_source' => 'csv',
					'csv_source' => 'file',
				],
			]
		);

		// chart style
		$this->add_control(
			'chart_type',
			[
				'label'   => esc_html__('Chart Styles', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'description' => esc_html__('doughnut, pie and polarArea charts only work with custom data source', 'wpr-addons'),
				'default' => 'bar',
				'options' => [
					'bar'           => esc_html__('Bar Vertical', 'wpr-addons'),
					'bar_horizontal' => esc_html__('Bar Horizontal', 'wpr-addons'),
					'line'          => esc_html__('Line', 'wpr-addons'),
					'radar'         => esc_html__('Radar', 'wpr-addons'),
					'doughnut'      => esc_html__('Doughnut', 'wpr-addons'),
					'pie'           => esc_html__('Pie', 'wpr-addons'),
					'polarArea'     => esc_html__('Polar Area', 'wpr-addons'),
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'data_type',
			[
				'label'              => __( 'Data Grid Type', 'wpr-addons' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'linear'      => __( 'Linear', 'wpr-addons' ),
					'logarithmic' => __( 'Logarithmic', 'wpr-addons' ),
				],
				'default'            => 'linear',
				'condition' => [
					'chart_type' => ['bar', 'bar_horizontal', 'line']
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'column_width_x',
			[
				'label' => esc_html__( 'Column Width', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'step' => 0.1,
				'min' => 0,
				'max' => 1,
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'chart_type' => ['bar', 'bar_horizontal'],
				]
			]
		);

		$this->add_control(
			'exclude_dataset_on_click',
			[
				'label'   => esc_html__('Exclude Data on Legend Click', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'stacked_bar_chart',
			[
				'label'   => esc_html__('Enable Stacked Chart', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'chart_type' => ['bar', 'bar_horizontal', 'radar', 'doughnut', 'pie']
				]
			]
		);

		$this->add_control(
			'inner_datalabels',
			[
				'label'   => esc_html__('Show Data Values', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'false',
				'return_value' => 'true',
				'condition' => [
					'chart_type' => ['bar', 'bar_horizontal', 'line', '']
				]
			]
		);

		$this->add_control(
			'enable_min_max',
			[
				'label' => esc_html__( 'Min-Max Values', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'chart_type!' => ['doughnut', 'polarArea', 'pie'],
				]
			]
		);

		$this->add_control(
			'min_value', 
			[
				'label'       => esc_html__('Min. Value', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => -100,
				'condition' => [
					'enable_min_max' => 'yes',
				]
			]
		);

		$this->add_control(
			'max_value', 
			[
				'label'       => esc_html__('Max. Value', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 100,
				'condition' => [
					'enable_min_max' => 'yes',
				]
			]
		);

		$this->add_control(
			'animations_heading',
			[
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_animation',
			[
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'chart_type' => ['line', 'radar']
			]
		);

		$this->add_control(
			'chart_animation_loop',
			[
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'chart_animation' => 'yes',
					'chart_type' => ['line', 'radar']
				]
			]
		);

		$this->add_control(
			'chart_animation_duration', 
			[
				'label'       => esc_html__('Animation Duration', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1000,
				'condition' => [
					'chart_animation' => 'yes',
					'chart_type' => ['radar', 'line']
				]
			]
		);

		$this->add_control(
			'animation_transition_type',
			[
				'label'   => esc_html__('Animation Timing', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'linear',
				'options' => [
					'linear' => 'linear',
					'easeInQuad' => 'easeInQuad',
					'easeOutQuad' => 'easeOutQuad',
					'easeInOutQuad' => 'easeInOutQuad',
					'easeInCubic' => 'easeInCubic',
					'easeOutCubic' => 'easeOutCubic',
					'easeInOutCubic' => 'easeInOutCubic',
					'easeInQuart' => 'easeInQuart',
					'easeOutQuart' => 'easeOutQuart',
					'easeInOutQuart' => 'easeInOutQuart',
					'easeInQuint' => 'easeInQuint',
					'easeOutQuint' => 'easeOutQuint',
					'easeInOutQuint' => 'easeInOutQuint',
					'easeInSine' => 'easeInSine',
					'easeOutSine' => 'easeOutSine',
					'easeInOutSine' => 'easeInOutSine',
					'easeInExpo' => 'easeInExpo',
					'easeOutExpo' => 'easeOutExpo',
					'easeInOutExpo' => 'easeInOutExpo',
					'easeInCirc' => 'easeInCirc',
					'easeOutCirc' => 'easeOutCirc',
					'easeInOutCirc' => 'easeInOutCirc',
					'easeInElastic' => 'easeInElastic',
					'easeOutElastic' => 'easeOutElastic',
					'easeInOutElastic' => 'easeInOutElastic',
					'easeInBack' => 'easeInBack',
					'easeOutBack' => 'easeOutBack',
					'easeInOutBack' => 'easeInOutBack',
					'easeInBounce' => 'easeInBounce',
					'easeOutBounce' => 'easeOutBounce',
					'easeInOutBounce' => 'easeInOutBounce',
				],
				'condition' => [
					'chart_animation' => 'yes',
					'chart_type' => ['radar', 'line']
				]
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_data',
			[
				'label' => esc_html__( 'Data', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

		$this->add_control( //TODO: use this instead of repeater
			'charts_labels_data', [
				'label'       => esc_html__('Data Labels', 'wpr-addons'),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default'     => esc_html__('January, February, March', 'wpr-addons'),
				'description' => esc_html__('Enter the comma-separated list of values (Used only with custom data source)', 'wpr-addons'),
				'label_block' => true,
				'condition'   => [
					'data_source' => 'custom',
					// 'chart_type' => ['bar', 'bar_horizontal', 'line', 'radar']
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'charts_repeater_labels_data_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 3 Data Labels are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-charts-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		// repeter for data fields
		$chart_repeater_labels = new Repeater();

		$chart_repeater_labels->add_control(
			'chart_data_label', [
				'label'       => esc_html__('Label', 'wpr-addons'),
				'type'        => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default'     => esc_html__('Label #1', 'wpr-addons'),
				'label_block' => true,
			]
		);

		$chart_repeater_labels->add_control(
			'chart_data_set', [
				'label'       => esc_html__('Data', 'wpr-addons'),
				'type'        => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default'     => '10,23,15',
				'label_block' => true,
				'description' => esc_html__("Only works with custom charts. Enter comma separated data values (Shouldn't Exceed number of values provided in Data Labels option).", 'wpr-addons'),
			]
		);

		// start tabs section
		$chart_repeater_labels->start_controls_tabs(
			'chart_data_bar_background_tab'
		);
		// start normal sections
		$chart_repeater_labels->start_controls_tab(
			'chart_data_bar_background_normal',
			[
				'label' => esc_html__('Normal', 'wpr-addons'),
			]
		);

		$chart_repeater_labels->add_control(
			'chart_data_background_color', [
				'label'       => esc_html__('Background Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#605BE5',
			]
		);

		$chart_repeater_labels->add_control(
			'chart_data_border_color', [
				'label'       => esc_html__('Border Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#605BE5',
			]
		);

		$chart_repeater_labels->end_controls_tab();

		// end normal sections
		// start hover sections
		$chart_repeater_labels->start_controls_tab(
			'chart_data_bar_background_hover',
			[
				'label' => esc_html__('Hover', 'wpr-addons'),
			]
		);
		$chart_repeater_labels->add_control(
			'chart_data_background_color_hover', [
				'label'       => esc_html__('Background Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => '#7A7A7ACC'
			]
		);

		$chart_repeater_labels->add_control(
			'chart_data_border_color_hover', [
				'label'       => esc_html__('Border Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#7A7A7ACC',
			]
		);
		$chart_repeater_labels->end_controls_tab();
		// end hover sections
		$chart_repeater_labels->end_controls_tabs();
		// end tabs section

		$chart_repeater_labels->add_control(
			'chart_data_border_width', [
				'label'       => esc_html__('Border Width', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
			]
		);

		$this->add_control(
			'charts_data_set',
			[
				'label'   => esc_html__('Set Data', 'wpr-addons'),
				'type'    => Controls_Manager::REPEATER,
				'default' => [
					[
						'chart_data_label'            => esc_html__('Laptops', 'wpr-addons'),
						'chart_data_set'              => '13,20,15',
						'chart_data_background_color'   => '#605BE5CC',
						'chart_data_border_color' => '#605BE5CC',
						'chart_data_border_width' => 1,
					],
					[
						'chart_data_label'            => esc_html__('Phones', 'wpr-addons'),
						'chart_data_set'              => '20,10,33',
						'chart_data_background_color'   => '#E5605BCC',
						'chart_data_border_color' => '#E5605BCC',
						'chart_data_border_width' => 1,
					],
					[
						'chart_data_label'            => esc_html__('Other', 'wpr-addons'),
						'chart_data_set'              => '10,3,23',
						'chart_data_background_color'   => '#5BE560CC',
						'chart_data_border_color' => '#5BE560CC',
						'chart_data_border_width' => 1,
					],

				],

				'fields'      => $chart_repeater_labels->get_controls(),
				'title_field' => '{{{ chart_data_label }}}',
				// 'condition'   => ['chart_style' => ['bar', 'bar_horizontal', 'horizontalBar', 'line', 'radar']],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'charts_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 3 Items are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-charts-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_axis_r',
			[
				'label' => esc_html__( 'Axis', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'   => [
					'chart_type' => ['radar', 'polarArea']
				],
			]
        );

		$this->add_control(
			'r_axis_conditions',
			[
				'label' => esc_html__( 'Axis', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'display_r_axis',
			[
				'label'   => esc_html__('Grid Lines', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'true',
				'return_value' => 'true'
			]
		);

		$this->add_control(
			'grid_line_width_r', [
				'label'       => esc_html__('Grid Line Width', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
				'step' => 0.1,
				'condition' => [
					'display_r_axis' => 'true'
				]
			]
		);

		$this->add_control(
			'display_r_ticks',
			[
				'label'   => esc_html__('Ticks (Labels)', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'true',
				'return_value' => 'true'
			]
		);

		$this->add_control(
			'r_step_size',
			[
				'label'              => __( 'Step Size', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 360,
				'default'            => 0,
				'frontend_available' => true,
				'condition' => [
					'display_r_ticks' => 'true'
				]
			]
		);

		$this->add_control(
			'border_dash_length_r', [
				'label'       => esc_html__('Border Dash length', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_dash_spacing_r', [
				'label'       => esc_html__('Border Dash spacing', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1'
			]
		);

		$this->add_control(
			'border_dash_offset_r', [
				'label'       => esc_html__('Border Dash offset', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1'
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_axis',
			[
				'label' => esc_html__( 'Axis', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'   => [
					'chart_type' => ['bar', 'bar_horizontal', 'line']
				],
			]
        );

		$this->add_control(
			'x_axis_conditions',
			[
				'label' => esc_html__( 'X-Axis', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'display_x_axis',
			[
				'label'   => esc_html__('Grid Lines', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'grid_line_width_x', [
				'label'       => esc_html__('Grid Line Width', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
				'step' => 0.1,
				'condition' => [
					'display_x_axis' => 'true'
				]
			]
		);

		$this->add_control(
			'display_x_ticks',
			[
				'label'   => esc_html__('Ticks (Labels)', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'true',
				'return_value' => 'true'
			]
		);

		$this->add_control(
			'x_step_size',
			[
				'label'              => __( 'Step Size', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 360,
				'default'            => 0,
				'frontend_available' => true,
				'condition' => [
					'display_x_ticks' => 'true'
				]
			]
		);

		$this->add_control(
			'display_x_axis_title',
			[
				'label'   => esc_html__('Show Title', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'true',
				'return_value' => 'true'
			]
		);

		$this->add_control(
			'x_axis_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'X-Axis', 'wpr-addons' ),
				'default' => esc_html__('X-Axis', 'wpr-addons'),
				'condition' => [
					'display_x_axis_title' => 'true'
				]
			]
		);

		$this->add_control(
			'labels_rotation_x_axis',
			[
				'label'              => __( 'Ticks (Labels) Rotation', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 360,
				'default'            => 0,
				'frontend_available' => true
			]
		);

		$this->add_control(
			'y_axis_conditions',
			[
				'label' => esc_html__( 'Y-Axis', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'display_y_axis',
			[
				'label'   => esc_html__('Grid Lines', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'true',
				'return_value' => 'true'
			]
		);

		$this->add_control(
			'grid_line_width_y', [
				'label'       => esc_html__('Grid Line Width', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
				'step' => 0.1,
				'condition' => [
					'display_y_axis' => 'true'
				]
			]
		);

		$this->add_control(
			'display_y_ticks',
			[
				'label'   => esc_html__('Ticks (Labels)', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'true',
				'return_value' => 'true'
			]
		);

		$this->add_control(
			'y_step_size',
			[
				'label'              => __( 'Step Size', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 360,
				'default'            => 0,
				'frontend_available' => true,
				'condition' => [
					'display_y_ticks' => 'true'
				]
			]
		);

		$this->add_control(
			'display_y_axis_title',
			[
				'label'   => esc_html__('Show Title', 'wpr-addons'),
				'type'    => Controls_Manager::SWITCHER ,
				'default' => 'true',
				'return_value' => 'true'
			]
		);

		$this->add_control(
			'y_axis_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Y-Axis', 'wpr-addons' ),
				'default' => esc_html__('Y-Axis', 'wpr-addons'),
				'condition' => [
					'display_y_axis_title' => 'true'
				]
			]
		);

		$this->add_control(
			'labels_rotation_y_axis',
			[
				'label'              => __( 'Ticks (Labels) Rotation', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 360,
				'default'            => 0,
				'frontend_available' => true
			]
		);

		$this->add_control(
			'reverse_x',
			[
				'label' => esc_html__( 'Reverse Charts', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'show_chart_legend' => 'yes',
					'chart_type' => 'bar_horizontal'
				]
			]
		);

		$this->add_control(
			'reverse_y',
			[
				'label' => esc_html__( 'Reverse Charts', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'show_chart_legend' => 'yes',
					'chart_type' => ['bar', 'line']
				]
			]
		);

		$this->add_control(
			'border_dash_length', [
				'label'       => esc_html__('Border Dash length', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
				'separator' => 'before',
				// 'step' => 0.1,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'display_x_axis',
							'operator' => '==',
							'value' => 'true'
						],
						[
							'name' => 'display_y_axis',
							'operator' => '==',
							'value' => 'true'
						]
					]
				]
			]
		);

		$this->add_control(
			'border_dash_spacing', [
				'label'       => esc_html__('Border Dash spacing', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
				// 'step' => 0.1,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'display_x_axis',
							'operator' => '==',
							'value' => 'true'
						],
						[
							'name' => 'display_y_axis',
							'operator' => '==',
							'value' => 'true'
						]
					]
				]
			]
		);

		$this->add_control(
			'border_dash_offset', [
				'label'       => esc_html__('Border Dash offset', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
				// 'step' => 0.1,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'display_x_axis',
							'operator' => '==',
							'value' => 'true'
						],
						[
							'name' => 'display_y_axis',
							'operator' => '==',
							'value' => 'true'
						]
					]
				]
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_chart_title',
			[
				'label' => esc_html__( 'Show Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'chart_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'To Be Applied', 'wpr-addons' ),
				'default' => esc_html__('To Be Applied', 'wpr-addons'),
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'chart_title_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'label_block' => false,
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					// 'chartArea' => esc_html__( 'chartArea', 'wpr-addons' ),
				],
				'condition' => [
					'show_chart_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'chart_title_align',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'start' => esc_html__( 'Start', 'wpr-addons' ),
					'center' => esc_html__( 'Center', 'wpr-addons' ),
					'end' => esc_html__( 'End', 'wpr-addons' ),
				],
				'condition' => [
					'show_chart_title' => 'yes',
				]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_legend',
			[
				'label' => esc_html__( 'Legends', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_chart_legend',
			[
				'label' => esc_html__( 'Show Legends', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'reverse_legend',
			[
				'label' => esc_html__( 'Reverse', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'show_chart_legend' => 'yes',
				]
			]
		);

		$this->add_control(
			'charts_legend_shape',
			[
				'label' => esc_html__( 'Shape', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'rectangle',
				'label_block' => false,
				'render_type' => 'template',
				'options' => [
					'rectangle' => esc_html__( 'Rectangle', 'wpr-addons' ),
					'point' => esc_html__( 'Point', 'wpr-addons' ),
				],
				'condition' => [
					'show_chart_legend' => 'yes',
				]
			]
		);

		$this->add_control(
			'charts_legend_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'label_block' => false,
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'chartArea' => esc_html__( 'chartArea', 'wpr-addons' ),
				],
				'condition' => [
					'show_chart_legend' => 'yes',
				]
			]
		);

		$this->add_control(
			'charts_legend_align',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'start' => esc_html__( 'Start', 'wpr-addons' ),
					'center' => esc_html__( 'Center', 'wpr-addons' ),
					'end' => esc_html__( 'End', 'wpr-addons' ),
				],
				'condition' => [
					'show_chart_legend' => 'yes',
				]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_chart_tooltip',
			[
				'label' => esc_html__( 'Show Tooltip', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'tooltips_percent',
			[
				'label'              => __( 'Convert Values to Percents', 'wpr-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'true',
				'default' => 'true',
				'frontend_available' => true,
				'condition' => [
					'show_chart_tooltip' => 'yes'
				]
			]
		);

		$this->add_control(
			'trigger_tooltip_on',
			[
				'label'   => esc_html__('Show Tooltip On', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'mousemove',
				'options' => [
					'mousemove' => esc_html__('Hover', 'wpr-addons'),
					'click' => esc_html__('Click', 'wpr-addons'),
				],
				'condition' => [
					'show_chart_tooltip' => 'yes'
				]
			]
		);

		$this->add_control(
			'chart_interaction_mode',
			[
				'label'   => esc_html__('Interaction Mode', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'point',
				'options' => [
					// 'nearest' => esc_html__('Nearest', 'wpr-addons'),
					'point' => esc_html__('Point', 'wpr-addons'),
					'index' => esc_html__('Index', 'wpr-addons'),
					'dataset' => esc_html__('Dataset', 'wpr-addons'),
				],
				'condition' => [
					'show_chart_tooltip' => 'yes'
				]
			]
		);

		$this->add_control(
			'chart_tooltip_position',
			[
				'label'   => esc_html__('Position', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'nearest',
				'options' => [
					'nearest' => esc_html__('Nearest', 'wpr-addons'),
					'average' => esc_html__('Average', 'wpr-addons'),
				],
				'condition' => [
					'show_chart_tooltip' => 'yes'
				]
			]
		);

		$this->end_controls_section(); 
		
		$this->start_controls_section(
            'section_chart_lines',
			[
				'label' => esc_html__( 'Lines', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'chart_type' => ['line', 'radar'],
				]
			]
        );

		$this->add_control(
			'show_lines',
			[
				'label' => esc_html__( 'Show Lines', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_type_line_fill',
			[
				'label' => esc_html__( 'Show Background Fill', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'chart_type!' => ['bar', 'bar_horizontal'],
				]
			]
		);

		$this->add_control(
			'line_dots',
			[
				'label' => esc_html__( 'Show Dots', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'chart_type!' => ['bar', 'bar_horizontal'],
				]
			]
		);

		$this->add_responsive_control(
			'line_dots_radius',
			[
				'label' => esc_html__( 'Dots Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => ['desktop', 'mobile'],
				'exclude' => ['tablet'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 5
				],
				'condition' => [
					'chart_type!' => ['bar', 'bar_horizontal'],
					'line_dots' => 'yes',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'chart_section_style_res',
			[
				'label' => esc_html__( 'Responsive', 'wpr-addons' ),
				'tab'	=> Controls_Manager::TAB_CONTENT,
			]
		);
			/**
			 * Control: Enable/Disable
			*/
		$this->add_control(
			'responsive_chart',
			[
				'label'		=> esc_html__( 'Responsive Layout', 'wpr-addons' ),
				'description' => esc_html__( 'Enables scrollbar on tablet and mobile screens', 'wpr-addons' ),
				'default' => 'yes',
				'type'		=> Controls_Manager::SWITCHER,
				'selectors'	=> [
					'{{WRAPPER}} .wpr-charts-container'	=> 'overflow: auto;',
					'{{WRAPPER}} .wpr-charts-wrapper' => 'position: relative; margin: 0 auto;',
				],
			]
		);

			/**
			 * Control: Width
			 */
		$this->add_responsive_control(
			'chart_res_width',
			[
				'label'		=> esc_html__( 'Min Width (px)', 'wpr-addons' ),
				'type'		=> Controls_Manager::SLIDER,
				'range'		=> [
					'px'	=> [
						'min' => 0,
						'max' => 1600
					]
				],
				'default'	=> [
					'size'	=> 800,
				],
				'selectors'	=> [
					'{{WRAPPER}} .wpr-charts-wrapper' => 'min-width: {{SIZE}}px;',
				],
				'separator'	=> 'before',
				'condition'	=> [
					'responsive_chart' => 'yes',
				],
			]
		);

			/**
			 * Control: Height
			 */
		$this->add_responsive_control(
			'chart_res_height',
			[
				'label'		=> esc_html__( 'Min Height (px)', 'wpr-addons' ),
				'type'		=> Controls_Manager::SLIDER,
				'range'		=> [
					'px'	=> [
						'min' => 0,
						'max' => 1600
					]
				],
				'default'	=> [
					'size'	=> 400,
				],
				'selectors'	=> [
					'{{WRAPPER}} .wpr-charts-wrapper' => 'min-height: {{SIZE}}px;',
				],
				'condition'	=> [
					'responsive_chart' => 'yes',
				],
			]
		);
		
		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'charts', [
			'Upload CSV File',
			'Import CSV File from URL',
			'Import published Google Sheets',
			'Add Unlimited Data Labels',
			'Add Unlimited Chart Items'
		] );
		
		$this->start_controls_section(
            'section_datalabels_styles',
			[
				'label' => esc_html__( 'Data Values', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'inner_datalabels' => 'true'
				]
			]
        );

		$this->add_control(
			'inner_datalabels_color', [
				'label'       => esc_html__('Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => '#FFF',
			]
		);

		// $this->add_control(
		// 	'inner_datalabels_bg_color', [
		// 		'label'       => esc_html__('Background Color', 'wpr-addons'),
		// 		'type'        => Controls_Manager::COLOR,
		// 		'default' => '',
		// 	]
		// );

		$this->add_control(
			'inner_datalabels_font_family',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'inner_datalabels_font_style',
			[
				'label'   => esc_html__('Font Style', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],
		
			]
		);
		
		$this->add_control(
			'inner_datalabels_font_weight',
			[
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true
			]
		);
		
		$this->add_control(
			'inner_datalabels_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				]
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_styles_chart_axis_r',
			[
				'label' => esc_html__( 'Axis', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'chart_type' => ['radar', 'polarArea']
				],
			]
        );

		$this->add_control(
			'r_axis_angle_lines_heading',
			[
				'label' => esc_html__( 'Angle Lines', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'angle_lines_color', [
				'label' => esc_html__('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#E7E7E7'
			]
		);

		$this->add_control(
			'r_axis_grid_lines_heading',
			[
				'label' => esc_html__( 'Grid Lines', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'axis_grid_line_color_r', [
				'label' => esc_html__('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#E7E7E7'
			]
		);

		$this->add_control(
			'axis_labels_heading',
			[
				'label' => esc_html__( 'Axis Labels', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'axis_labels_color', [
				'label' => esc_html__('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222'
			]
		);

		$this->add_control(
			'axis_labels_bg_color', [
				'label' => esc_html__('Background Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#B2B2B200'
			]
		);
		
		$this->add_control(
			'axis_labels_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
			]
		);

		$this->add_control(
			'chart_point_labels_heading',
			[
				'label' => esc_html__( 'Ticks', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'chart_point_labels_color_r', [
				'label' => esc_html__('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#B2B2B2'
			]
		);

		$this->add_control(
			'point_labels_font_family_r',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'point_labels_font_style_r',
			[
				'label'   => esc_html__('Font Style', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],
		
			]
		);
		
		$this->add_control(
			'point_labels_font_weight_r',
			[
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'point_labels_font_size_r',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_axis_styles',
			[
				'label' => esc_html__( 'Axis', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'chart_type' => ['bar', 'bar_horizontal', 'line']
				],
			]
        );

		$this->add_control(
			'x_axis_grid_lines_heading',
			[
				'label' => esc_html__( 'Grid Lines (X)', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'axis_grid_line_color_x', [
				'label'       => esc_html__('Color (X)', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => '#888888'
			]
		);

		$this->add_control(
			'y_axis_grid_lines_heading',
			[
				'label' => esc_html__( 'Grid Lines (Y)', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'axis_grid_line_color_y', [
				'label'       => esc_html__('Color (Y)', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => '#888888'
			]
		);

		$this->add_control(
			'x_axis_title_styles_heading',
			[
				'label' => esc_html__( 'X-Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'axis_title_color_x', [
				'label'       => esc_html__('Color (X)', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => '#C0C0C0'
			]
		);

		$this->add_control(
			'axis_title_font_family_x',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'axis_title_font_style_x',
			[
				'label'   => esc_html__('Font Style', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],
		
			]
		);
		
		$this->add_control(
			'axis_title_font_weight_x',
			[
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'axis_title_font_size_x',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
			]
		);

		$this->add_control(
			'y_axis_title_styles_heading',
			[
				'label' => esc_html__( 'Y-Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'axis_title_color_y', [
				'label'       => esc_html__('Color (Y)', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => '#C0C0C0'
			]
		);

		$this->add_control(
			'axis_title_font_family_y',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'axis_title_font_style_y',
			[
				'label'   => esc_html__('Font Style', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],
		
			]
		);
		
		$this->add_control(
			'axis_title_font_weight_y',
			[
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'axis_title_font_size_y',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
			]
		);

		$this->add_control(
			'x_ticks_styles_heading',
			[
				'label' => esc_html__( 'X-Ticks', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_ticks_color_x', [
				'label'       => esc_html__('Ticks Color (X)', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'ticks_font_family_x',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'ticks_font_style_x',
			[
				'label'   => esc_html__('Font Style', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],
		
			]
		);
		
		$this->add_control(
			'ticks_font_weight_x',
			[
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'ticks_font_size_x',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 17,
				],
			]
		);

		$this->add_control(
			'ticks_padding_x',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				]
			]
		);

		$this->add_control(
			'y_ticks_styles_heading',
			[
				'label' => esc_html__( 'Y-Ticks', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_ticks_color_y', [
				'label'       => esc_html__('Ticks Color (Y)', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'ticks_font_family_y',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'ticks_font_style_y',
			[
				'label'   => esc_html__('Font Style', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],
		
			]
		);
		
		$this->add_control(
			'ticks_font_weight_y',
			[
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'ticks_font_size_y',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
			]
		);

		$this->add_control(
			'ticks_padding_y',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				]
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_title_styles',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_chart_title' => 'yes'
				]
			]
        );

		$this->add_control(
			'chart_title_color', [
				'label'       => esc_html__('Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'title_font_family',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'title_font_style',
			[
				'label'   => esc_html__('Font Style', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],
		
			]
		);
		
		$this->add_control(
			'title_font_weight',
			[
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'title_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
			]
		);

		$this->add_control(
			'chart_title_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_legend_styles',
			[
				'label' => esc_html__( 'Legend', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

		$this->add_control(
			'chart_legend_text_color', [
				'label' => esc_html__('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222'
			]
		);

		$this->add_control(
			'legend_font_family',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);

		$this->add_control(
			'legend_font_style',
			[
				'label'   => esc_html__('Font Style', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],

			]
		);

		$this->add_control(
			'legend_font_weight',
			[
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'legend_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
			]
		);

		$this->add_control(
			'legend_box_width',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				]
			]
		);

		$this->add_control(
			'chart_legend_padding',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_tooltip_styles',
			[
				'label' => esc_html__( 'Tooltip', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_chart_tooltip' => 'yes'
				]
			]
        ); 

		$this->add_control(
			'chart_tooltip_bg_color', [
				'label'       => esc_html__('Background Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
			]
		);
		
		$this->add_control(
			'tooltip_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
			]
		);
		
		$this->add_responsive_control(
			'tooltip_caret_size',
			[
				'label' => esc_html__( 'Triangle Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => ['desktop', 'mobile'],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 6,
				],
			]
		);

		$this->add_control(
			'tooltip_title_heading',
			[
				'label' => esc_html__( 'Tooltip Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_tooltip_title_color', [
				'label'       => esc_html__('Title Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => '#FFF'
			]
		);

		$this->add_control(
			'chart_tooltip_title_font',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'chart_tooltip_title_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'chart_tooltip_title_margin_bottom',
			[
				'label' => esc_html__( 'Title Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
			]
		);

		$this->add_control(
			'chart_tooltip_title_align',
			[
				'label' => esc_html__( 'Title Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
				'separator' => 'before'
			]
		);

		$this->add_control(
			'tooltip_item_heading',
			[
				'label' => esc_html__( 'Tooltip Item', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_tooltip_item_color', [
				'label'       => esc_html__('Item Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => '#FFF'
			]
		);

		$this->add_control(
			'chart_tooltip_item_font',
			[
				'label' => esc_html__( 'Font Family', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'description' => esc_html__('Use only the fonts located under System options group', 'wpr-addons'),
				'default' => 'Arial',
			]
		);
		
		$this->add_control(
			'chart_tooltip_item_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'chart_tooltip_item_spacing',
			[
				'label' => esc_html__( 'Item Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
			]
		);

		$this->add_control(
			'chart_tooltip_item_align',
			[
				'label' => esc_html__( 'Item Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
				'separator' => 'before'
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        extract($settings);

		$data_charts_array = [];

		if ( in_array($chart_type, array('bar', 'bar_horizontal', 'line', 'radar')) ) {
			
			if ( !empty($charts_labels_data) ) :
				$data_charts_array['labels'] = explode(',', trim($charts_labels_data));
				if ( !wpr_fs()->can_use_premium_code() && 3 < sizeof($data_charts_array['labels']) ) {
					$data_charts_array['labels'] = array_slice($data_charts_array['labels'], 0, 3);
				}
			endif;

			if ( is_array($charts_data_set) && sizeof($charts_data_set) ) {
				$charts_counter = 0;
				foreach ( $charts_data_set as $chart_data ) {
					if ( !wpr_fs()->can_use_premium_code() && $charts_counter === 3 ) {
						break;
					}
					$data_charts_array['datasets'][] = [
						'label' => $chart_data['chart_data_label'],
						'data' => array_map('floatval', explode(',', trim($chart_data['chart_data_set'], ','))),
						'backgroundColor' => $chart_data['chart_data_background_color'],
						'hoverBackgroundColor' => $chart_data['chart_data_background_color_hover'],
						'borderColor' => $chart_data['chart_data_border_color'],
						'hoverBorderColor' => $chart_data['chart_data_border_color_hover'],
						'borderWidth' => $chart_data['chart_data_border_width'],
						'barPercentage' => !empty($settings['column_width_x']) ? $settings['column_width_x'] : '',
						'fill' => !empty($settings['chart_type_line_fill']) && 'yes' === $settings['chart_type_line_fill'] ? true : false,
					];

					$charts_counter++;
				}
			}
		} else {
			if ( is_array($charts_data_set) && sizeof($charts_data_set) && $settings['data_source'] !== 'csv' ) {
				$chart_data_number_values = [];
				$chart_background_colors = [];
				$chart_background_hover_colors = [];
				$chart_data_border_colors = [];
				$chart_data_border_hover_colors = [];
				$chart_data_border_width = [];
				$chart_data_bar_percentage = [];
				
				$charts_counter = 0;
				foreach ( $charts_data_set AS $labels_data ) :
					if ( ! wpr_fs()->can_use_premium_code() && $charts_counter === 3 ) {
						break;
					}
					$data_charts_array['labels'][] = $labels_data['chart_data_label'];
					$charts_counter++;
				endforeach;
			
				if ( !empty($charts_labels_data) ) :
					$data_charts_array_test = explode(',', trim($charts_labels_data));
				endif;

				foreach ( $data_charts_array_test as $key=>$test_data ) {
					if ( ! wpr_fs()->can_use_premium_code() && $key === 3 ) {
						break;
					}
					$chart_data_number_values[$key] = [];
					$outer_key = $key;
					foreach ( $charts_data_set as $key=>$chart_data ) {
						if ( ! wpr_fs()->can_use_premium_code() && $key === 3 ) {
							break;
						}
						$number_value = sizeof(explode(',', trim($chart_data['chart_data_set'], ','))) >= $outer_key + 1 ? array_map('floatval', explode(',', trim($chart_data['chart_data_set'], ',')))[$outer_key] : '0';
						array_push($chart_data_number_values[$outer_key],  $number_value);
					}
				}
				
				foreach ( $charts_data_set as $key=>$chart_data ) {
					if ( ! wpr_fs()->can_use_premium_code() && $key === 3 ) {
						break;
					}
					array_push($chart_background_colors, trim($chart_data['chart_data_background_color']));
					array_push($chart_background_hover_colors, trim($chart_data['chart_data_background_color_hover']));
					array_push($chart_data_border_colors, trim($chart_data['chart_data_border_color']));
					array_push($chart_data_border_hover_colors, trim($chart_data['chart_data_border_color_hover']));
					array_push($chart_data_border_width, trim($chart_data['chart_data_border_width']));
					!empty($settings['column_width_x']) ? array_push($chart_data_bar_percentage, trim($chart_data['column_width_x'])) : '';
				}

				foreach ( $data_charts_array_test as $key=>$data_test ) {
					if ( ! wpr_fs()->can_use_premium_code() && $key === 3 ) {
						break;
					}
					$data_charts_array['datasets'][] = [
						'label' => $data_test, // test with fixed value 
						'data' => $chart_data_number_values[$key],
						'backgroundColor' => $chart_background_colors,
						'hoverBackgroundColor' => $chart_background_hover_colors,
						'borderColor' => $chart_data_border_colors,
						'hoverBorderColor' => $chart_data_border_hover_colors,
						'borderWidth' => $chart_data_border_width,
						'barPercentage' => $chart_data_bar_percentage,
					];
				}
			}
		}

		if ( !empty($data_source_csv_url) ) {
			$data_url = $data_source_csv_url;
		} else if ( !empty($data_source_csv_file['url']) ) {
			$data_url = $data_source_csv_file['url'];
		} else {
			$data_url = '';
		}

        $layout_settings = [
			'data_source' => $data_source,
            'chart_type' => $settings['chart_type'],
			'stacked_bar_chart' => !empty($settings['stacked_bar_chart']) ? $settings['stacked_bar_chart'] : '',
            'data_type' => isset($settings['data_type']) ? $settings['data_type'] : 'linear',
			'inner_datalabels' => $inner_datalabels,
			'inner_datalabels_color' => $inner_datalabels_color,
			// 'inner_datalabels_bg_color' => $inner_datalabels_bg_color,
			'inner_datalabels_font_size' => !empty($inner_datalabels_font_size['size']) ? $inner_datalabels_font_size['size'] : '',
			'inner_datalabels_font_family' => !empty($inner_datalabels_font_family) ? $inner_datalabels_font_family : '',
			'inner_datalabels_font_weight' => !empty($inner_datalabels_font_weight) ? $inner_datalabels_font_weight : '',
			'inner_datalabels_font_style' => !empty($inner_datalabels_font_style) ? $inner_datalabels_font_style : '',
			'ticks_padding_x' => !empty($ticks_padding_x['size']) ? $ticks_padding_x['size'] : '',
			'ticks_color_x' => $chart_ticks_color_x,
			'ticks_font_family_x' => $ticks_font_family_x,
			'ticks_font_style_x' => $ticks_font_style_x,
			'ticks_font_weight_x' => $ticks_font_weight_x,
			'ticks_font_size_x' => !empty($ticks_font_size_x['size']) ? $ticks_font_size_x['size'] : '',
			'ticks_padding_y' => !empty($ticks_padding_y['size']) ? $ticks_padding_y['size'] : '',
			'ticks_color_y' => isset($chart_ticks_color_y) ? $chart_ticks_color_y : '',
			'point_labels_color_r' => isset($chart_point_labels_color_r) ? $chart_point_labels_color_r : '',
			'ticks_font_family_y' => $ticks_font_family_y,
			'ticks_font_style_y' => $ticks_font_style_y,
			'ticks_font_weight_y' => $ticks_font_weight_y,
			'ticks_font_size_y' => !empty($ticks_font_size_y['size']) ? $ticks_font_size_y['size'] : '',
			'point_labels_font_family_r' => $point_labels_font_family_r,
			'point_labels_font_style_r' => $point_labels_font_style_r,
			'point_labels_font_weight_r' => $point_labels_font_weight_r,
			'point_labels_font_size_r' => !empty($point_labels_font_size_r['size']) ? $point_labels_font_size_r['size'] : '',
			'exclude_dataset_on_click' => $exclude_dataset_on_click,
			'trigger_tooltip_on' => $trigger_tooltip_on,
            'chart_labels' => !empty($data_charts_array['labels']) ? $data_charts_array['labels'] : '',
			'chart_datasets' => !empty($data_charts_array['datasets']) ? wp_json_encode($data_charts_array['datasets']) : '',
			'show_chart_legend' => $show_chart_legend,
			'reverse_legend' => $reverse_legend,
			'reverse_x' => $reverse_x,
			'reverse_y' => $reverse_y,
			'legend_shape' => $charts_legend_shape,
			'legend_box_width' => $legend_box_width['size'],
			'legend_position' => $settings['charts_legend_position'],
			'legend_align' => $settings['charts_legend_align'],
			'legend_text_color' => $chart_legend_text_color,
			'legend_font_family' => $legend_font_family,
			'legend_font_size' => $legend_font_size['size'],
			'legend_font_style' => $legend_font_style,
			'legend_font_weight' => $legend_font_weight,
			'legend_padding' => $chart_legend_padding['size'],
			'chart_animation' => isset($chart_animation) ? $chart_animation : '',
			'chart_animation_loop' => isset($chart_animation_loop) ? $chart_animation_loop : '',
			'chart_animation_duration' => isset($chart_animation_duration) ? $chart_animation_duration : '',
			'animation_transition_type' => $animation_transition_type,
			'show_chart_title' => $settings['show_chart_title'],
			'chart_title' => !empty($settings['chart_title']) ? $settings['chart_title'] : '',
			'chart_title_align' => !empty($chart_title_align) ? $chart_title_align : '',
			'chart_title_position' => !empty($chart_title_position) ? $chart_title_position : '',
			'chart_title_color' => !empty($chart_title_color) ? $chart_title_color : '',
			'title_font_family' => $title_font_family,
			'title_font_size' => isset($title_font_size) ? $title_font_size['size'] : '',
			'title_font_style' => isset($title_font_style) ? $title_font_style : '',
			'title_font_weight' => $title_font_weight,
			'title_padding' => isset($chart_title_padding) ? $chart_title_padding['size'] : '',
			'show_chart_tooltip' => $show_chart_tooltip,
			'tooltips_percent' => $tooltips_percent,
			'chart_interaction_mode' => $chart_interaction_mode,
			'tooltip_position' => $chart_tooltip_position,
			'tooltip_padding' => isset($tooltip_padding['size']) ? $tooltip_padding['size'] : 1,
			'tooltip_caret_size' => isset($tooltip_caret_size['size']) ? $tooltip_caret_size['size'] : 1,
			'tooltip_caret_size_mobile' => isset($tooltip_caret_size_mobile) ? $tooltip_caret_size_mobile['size'] : 0,
			'chart_tooltip_bg_color' => $chart_tooltip_bg_color,
			'chart_tooltip_title_color' => $chart_tooltip_title_color,
			'chart_tooltip_title_font' => $chart_tooltip_title_font,
			'chart_tooltip_title_font_size' => isset($chart_tooltip_title_font_size['size']) ? $chart_tooltip_title_font_size['size'] : 1,
			'chart_tooltip_title_align' => $chart_tooltip_title_align,
			'chart_tooltip_title_margin_bottom' => isset($chart_tooltip_title_margin_bottom['size']) ? $chart_tooltip_title_margin_bottom['size'] : 1,
			'chart_tooltip_item_color' => $chart_tooltip_item_color,
			'chart_tooltip_item_font' => $chart_tooltip_item_font,
			'chart_tooltip_item_font_size' => isset($chart_tooltip_item_font_size['size']) ? $chart_tooltip_item_font_size['size'] : 1,
			'chart_tooltip_item_align' => $chart_tooltip_item_align,
			'chart_tooltip_item_spacing' => isset($chart_tooltip_item_spacing['size']) ? $chart_tooltip_item_spacing['size'] : 1,
			'show_lines' => isset($show_lines) ? $show_lines : '',
			'line_dots' => isset($line_dots) ? $line_dots : '',
			'line_dots_radius' => isset($line_dots_radius) ? $line_dots_radius['size'] : '',
			'line_dots_radius_mobile' => isset($line_dots_radius_mobile) ? $line_dots_radius_mobile['size'] : 0,
			'border_dash_offset' => $border_dash_offset,
			'border_dash_length' => $border_dash_length,
			'border_dash_spacing' => $border_dash_spacing,
			'border_dash_offset_r' => $border_dash_offset_r,
			'border_dash_length_r' => $border_dash_length_r,
			'border_dash_spacing_r' => $border_dash_spacing_r,
			'display_x_axis' => $display_x_axis,
			'display_x_ticks' => $display_x_ticks,
			'display_x_axis_title' => $display_x_axis_title,
			'x_axis_title' => $x_axis_title,
			'axis_title_color_x' => $axis_title_color_x,
			'axis_title_font_family_x' => $axis_title_font_family_x,
			'axis_title_font_style_x' => $axis_title_font_style_x,
			'axis_title_font_weight_x' => $axis_title_font_weight_x,
			'axis_title_font_size_x' => !empty($axis_title_font_size_x['size']) ? $axis_title_font_size_x['size'] : '',
			'rotation_x' => $labels_rotation_x_axis,
			'axis_grid_line_color_x' => $axis_grid_line_color_x,
			'grid_line_width_x' => $grid_line_width_x,
			'display_y_axis' => $display_y_axis,
			'display_y_ticks' => $display_y_ticks,
			'y_step_size' => $y_step_size,
			'x_step_size' => $x_step_size,
			'display_r_axis' => $display_r_axis,
			'display_r_ticks' => $display_r_ticks,
			'r_step_size' => $r_step_size,
			'display_y_axis_title' => $display_y_axis_title,
			'y_axis_title' => $y_axis_title,
			'axis_title_color_y' => $axis_title_color_y,
			'axis_title_font_family_y' => $axis_title_font_family_y,
			'axis_title_font_style_y' => $axis_title_font_style_y,
			'axis_title_font_weight_y' => $axis_title_font_weight_y,
			'axis_title_font_size_y' => !empty($axis_title_font_size_y['size']) ? $axis_title_font_size_y['size'] : '',
			'rotation_y' => $labels_rotation_y_axis,
			'axis_grid_line_color_y' => $axis_grid_line_color_y,
			'grid_line_width_y' => $grid_line_width_y,
			'grid_line_width_r' => $grid_line_width_r,
			'axis_grid_line_color_r' => $axis_grid_line_color_r,
			'angle_lines_color' => isset($angle_lines_color) ? $angle_lines_color : '',
			'axis_labels_color' => isset($axis_labels_color) ? $axis_labels_color : '',
			'axis_labels_bg_color' => isset($axis_labels_bg_color) ? $axis_labels_bg_color : '',
			'axis_labels_padding' => isset($axis_labels_padding) ? $axis_labels_padding['size'] : '',
			'min_value' => $min_value,
			'max_value' => $max_value,
			'url' => $data_url,
			'separator' => $data_csv_separator,
        ];

		$this->add_render_attribute( 'chart-settings', [
            'class' => 'wpr-charts-container',
			'data-settings' => wp_json_encode( $layout_settings ),
		] );
        
        echo '<div ' . $this->get_render_attribute_string( 'chart-settings') . '>';

			if ( $data_source === 'csv' ) {
				echo '<span class="wpr-rotating-plane"></span>';
			}

            echo '<div class="wpr-charts-wrapper">';
                echo '<canvas class="wpr-chart"></canvas>';
            echo '</div>';
        echo '</div>';
    }

}