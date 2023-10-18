<?php
namespace WprAddons\Modules\ThemeBuilder\PostInfo\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Post_Info extends Widget_Base {
	
	public function get_name() {
		return 'wpr-post-info';
	}

	public function get_title() {
		return esc_html__( 'Post Meta', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-post-info';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('single') ? [ 'wpr-theme-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'post meta', 'post info', 'date', 'time', 'author', 'categories', 'tags', 'comments' ];
	}

	public function add_options_post_info_select() {
		return [
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'time' => esc_html__( 'Time', 'wpr-addons' ),
			'comments' => esc_html__( 'Comments', 'wpr-addons' ),
			'author' => esc_html__( 'Author', 'wpr-addons' ),
			'taxonomy' => esc_html__( 'Taxonomy', 'wpr-addons' ),
			'pro-cf' => esc_html__( 'Custom Field (Expert)', 'wpr-addons' ),
		];
	}

	public function add_section_style_custom_field() {}

	public function get_post_taxonomies() {
		return [
			'category' => esc_html__( 'Categories', 'wpr-addons' ),
			'post_tag' => esc_html__( 'Tags', 'wpr-addons' ),
		];		
	}

	protected function register_controls() {

		// Get Available Meta Keys
		$post_meta_keys = Utilities::get_custom_meta_keys();

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_info',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_info_layout',
			[
				'label' => esc_html__( 'List Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'label_block' => false,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'post_info_select',
			[
				'label' => esc_html__( 'Select Element', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'time',
				'options' => $this->add_options_post_info_select(),
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'post_info_custom_field_video_tutorial',
			[
				'raw' => esc_html__( 'Watch Custom Fields ', 'wpr-addons' ) . sprintf( '<a href="%1$s" target="_blank">%2$s <span class="dashicons dashicons-video-alt3"></span></a>', 'https://www.youtube.com/watch?v=9GvpqyHF_Cs', esc_html__( 'Video Tutorial', 'wpr-addons' ) ),
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'post_info_select' => 'custom-field'
				]
			]
		);

		Utilities::upgrade_expert_notice( $repeater, Controls_Manager::RAW_HTML, 'post-info', 'post_info_select', ['pro-cf'] );

		$repeater->add_control(
			'post_info_modified_time',
			[
				'label' => esc_html__( 'Show Modified Time', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'condition' => [
					'post_info_select' => [ 'time', 'date' ],
				]
			]
		);

		$repeater->add_control(
			'post_info_comments_text_1',
			[
				'label' => esc_html__( 'No Comments', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ' No Comments',
				'condition' => [
					'post_info_select' => 'comments',
				]
			]
		);

		$repeater->add_control(
			'post_info_comments_text_2',
			[
				'label' => esc_html__( 'One Comment', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ' Comment',
				'condition' => [
					'post_info_select' => 'comments',
				]
			]
		);

		$repeater->add_control(
			'post_info_comments_text_3',
			[
				'label' => esc_html__( 'Multiple Comments', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ' Comments',
				'separator' => 'after',
				'condition' => [
					'post_info_select' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'post_info_tax_select',
			[
				'label' => esc_html__( 'Select Taxonomy', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $this->get_post_taxonomies(),
				'condition' => [
					'post_info_select' => 'taxonomy',
				]
			]
		);

		$repeater->add_control(
			'post_info_tax_display',
			[
				'label' => esc_html__( 'Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline-block',
				'options' => [
					'inline-block' => esc_html__( 'Inline', 'wpr-addons' ),
					'block' => esc_html__( 'Separate', 'wpr-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'display: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'display: {{VALUE}}',
				],
				'condition' => [
					'post_info_select' => 'taxonomy',
				]
			]
		);

		$repeater->add_control(
			'post_info_tax_sep',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ', ',
				'separator' => 'after',
				'condition' => [
					'post_info_select' => 'taxonomy',
				]
			]
		);

		$repeater->add_control(
			'post_info_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'condition' => [
					'post_info_select' => 'author'
				]
			]
		);

		$repeater->add_responsive_control(
			'post_info_avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 32,
				'min' => 8,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-author img' => 'width: {{SIZE}}px;',
				],
				'render_type' => 'template',
				'condition' => [
					'post_info_select' => 'author',
					'post_info_show_avatar' => 'yes'
				],
			]
		);

		if ( wpr_fs()->is_plan( 'expert' ) ) {
			$repeater->add_control(
				'post_info_cf',
				[
					'label' => esc_html__( 'Select Custom Field', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'default' => 'default',
					'options' => $post_meta_keys[1],
					'condition' => [
						'post_info_select' => 'custom-field'
					],
				]
			);

			$repeater->add_control(
				'post_info_cf_btn_link',
				[
					'label' => esc_html__( 'Use Value as Button Link', 'wpr-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'condition' => [
						'post_info_select' => 'custom-field'
					],
				]
			);

			$repeater->add_control(
				'post_info_cf_new_tab',
				[
					'label' => esc_html__( 'Open Link in a New Tab', 'wpr-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'condition' => [
						'post_info_select' => 'custom-field',
						'post_info_cf_btn_link' => 'yes'
					],
				]
			);

			$repeater->add_control(
				'post_info_cf_btn_text',
				[
					'label' => esc_html__( 'Button Text', 'wpr-addons' ),
					'type' => Controls_Manager::TEXT,
					'dynamic' => [
						'active' => true,
					],
					'default' => 'Click Me',
					'condition' => [
						'post_info_select' => 'custom-field',
						'post_info_cf_btn_link' => 'yes'
					],
				]
			);

			$repeater->add_control(
				'custom_field_wrapper_html_divider1',
				[
					'type' => Controls_Manager::DIVIDER,
					'style' => 'thick',
					'condition' => [
						'post_info_select' => 'custom-field',
					],
				]
			);

			$repeater->add_control(
				'post_info_cf_wrapper',
				[
					'label' => esc_html__( 'Wrap with HTML', 'wpr-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'condition' => [
						'post_info_select' => 'custom-field'
					],
				]
			);

			$repeater->add_control(
				'post_info_cf_wrapper_html',
				[
					'label' => esc_html__( 'Custom HTML Wrapper', 'wpr-addons' ),
					'description' => 'Insert <strong>*cf_value*</strong> to dislpay your Custom Field.',
					'placeholder'=> 'For Ex: <span>*cf_value*</span>',
					'type' => Controls_Manager::TEXTAREA,
					'dynamic' => [
						'active' => true,
					],
					'condition' => [
						'post_info_select' => 'custom-field',
						'post_info_cf_wrapper' => 'yes',
					],
				]
			);

			$repeater->add_control(
				'post_info_link_wrap',
				[
					'label' => esc_html__( 'Wrap with Link', 'wpr-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'post_info_select!' => [ 'time', 'custom-field' ],
					]
				]
			);
		}

		$repeater->add_control(
			'post_info_extra_icon',
			[
				'label' => esc_html__( 'Extra Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'post_info_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
			]
		);

		$this->add_control(
			'post_info_elements',
			[
				'label' => esc_html__( 'Post Info Elements', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'post_info_select' => 'taxonomy',
					],
					[
						'post_info_select' => 'date',
					],
				],
				'title_field' => '{{{ post_info_select.charAt(0).toUpperCase() + post_info_select.slice(1) }}}',
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'post-info', [
			'Display and Style Custom Fields in and Advanced way (Expert).',
			'Query Custom Post Type Taxonomies (categories).'
		] );

		// Styles ====================
		// Section: List -------------
		$this->start_controls_section(
			'section_style_post_info_list',
			[
				'label' => esc_html__( 'List Style', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'post_info_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Some of the options will only apply if you have multiple Post Meta Elements.', 'wpr-addons' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_responsive_control(
			'post_info_gutter',
			[
				'label' => esc_html__( 'List Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-vertical li' => 'padding-bottom: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-horizontal li' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-horizontal li:after' => 'right: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_responsive_control(
            'post_info_align',
            [
                'label' => esc_html__( 'Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info' => 'text-align: {{VALUE}}',
				],
				'prefix_class' => 'wpr-post-info-align-',
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'post_info_divider',
			[
				'label' => esc_html__( 'Show Dividers', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'post_info_divider_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info li:after' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'post_info_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_info_divider_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-vertical li:after' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-post-info-horizontal li:after' => 'border-right-style: {{VALUE}};',
				],
				'condition' => [
					'post_info_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_info_divider_weight',
			[
				'label' => esc_html__( 'Weight', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-vertical li:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-horizontal li:after' => 'border-right-width: {{SIZE}}{{UNIT}}; margin-right: calc(-{{SIZE}}px / 2);',
				],
				'condition' => [
					'post_info_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_info_divider_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-vertical li:after' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'post_info_divider' => 'yes',
					'post_info_layout!' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'post_info_divider_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-horizontal li:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'post_info_divider' => 'yes',
					'post_info_layout!' => 'vertical',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Elements ---------
		$this->start_controls_section(
			'section_style_post_info_elements',
			[
				'label' => esc_html__( 'Elements (Date, Comments, Author)', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_post_info_elements_style' );

		$this->start_controls_tab(
			'tab_post_info_elements_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_info_elements_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#959595',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info li' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info li:not(.wpr-post-info-taxonomy):not(.wpr-post-info-custom-field) a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_info_elements_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'label' => esc_html__('Typography', 'wpr-addons'),
				'selector' => '{{WRAPPER}} .wpr-post-info li:not(.wpr-post-info-taxonomy):not(.wpr-post-info-custom-field)',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'post_info_elements_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info li a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'post_info_avatar_border_radius',
			[
				'label' => esc_html__( 'Avatar Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_info_elements_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_info_elements_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info li:not(.wpr-post-info-taxonomy):not(.wpr-post-info-custom-field) a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles ====================
		// Section: Taxonomy ---------
		$this->start_controls_section(
			'section_style_post_info_tax',
			[
				'label' => esc_html__( 'Taxonomy (Categories, Tags, etc..)', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_info_tax_style' );

		$this->start_controls_tab(
			'tab_grid_post_info_tax_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_info_tax_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_info_tax_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'post_info_tax_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_info_tax_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-post-info-taxonomy a, {{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)',
				'separator' => 'before',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_info_tax_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_info_tax_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595F',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_info_tax_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'post_info_tax_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'post_info_tax_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'post_info_tax_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_info_tax_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_info_tax_border_width',
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
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'post_info_tax_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'post_info_tax_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-taxonomy a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-taxonomy > span:not(.wpr-post-info-text)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Custom Field -----
		$this->add_section_style_custom_field();

		// Styles ====================
		// Section: Extra Icon -------
		$this->start_controls_section(
			'section_style_post_info_icon',
			[
				'label' => esc_html__( 'Extra Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'post_info_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info li:not(.wpr-post-info-custom-field) i' => 'color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_responsive_control(
			'post_info_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 16
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info li i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_info_icon_space',
			[
				'label' => esc_html__( 'Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 5
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info li i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Extra Text -------
		$this->start_controls_section(
			'section_style_post_info_text',
			[
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'post_info_text_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					// '{{WRAPPER}} .wpr-post-info li:not(.wpr-post-info-custom-field) .wpr-post-info-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info li .wpr-post-info-text' => 'color: {{VALUE}}'
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_info_extra_text_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'label' => esc_html__('Typography', 'wpr-addons'),
				'selector' => '{{WRAPPER}} .wpr-post-info li .wpr-post-info-text',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'post_info_text_width',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info li .wpr-post-info-text span' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	// Post Date
	public function render_post_info_date( $settings ) {
		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );

		// Wrap with Link
		if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
			echo '<a href="'. esc_url( get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ) ) .'">';
		}

		// Modified Time
		if ( 'yes' === $settings['post_info_modified_time']) {
			echo esc_html(get_the_modified_time(get_option( 'date_format')));
		} else {
			// Date
			echo '<span>'. esc_html(apply_filters( 'the_date', get_the_date( '' ), get_option( 'date_format' ), '', '' )) .'</span>';
		}

		// Wrap with Link
		if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
			echo '</a>';
		}
	}

	// Post Time
	public function render_post_info_time( $settings ) {
		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );

		if ( 'yes' === $settings['post_info_modified_time']) {
			echo esc_html(get_the_modified_time());
		} else {
			echo '<span>'. esc_html(get_the_time('')) .'</span>';
		}
	}

	// Post Comments
	public function render_post_info_comments( $settings ) {
		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );

		$count = get_comments_number();

		if ( comments_open() ) {
			if ( $count == 1 ) {
				$text = $count . $settings['post_info_comments_text_2'];
			} elseif ( $count > 1 ) {
				$text = $count . $settings['post_info_comments_text_3'];
			} else {
				$text = $settings['post_info_comments_text_1'];
			}

			// Wrap with Link
			if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
				echo '<a href="'. esc_url( get_comments_link() ) .'">';
			}

			// Comments
			echo '<span> '. esc_html($text) .'</span>';

			if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
				echo '</a>';
			}
		}
	}

	// Post Author
	public function render_post_info_author( $settings ) {
		$author_id = get_post_field( 'post_author' );

		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );
		
		// Wrap with Link
		if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
			echo '<a href="'. esc_url( get_author_posts_url( $author_id ) ) .'">';
		}

			if ( 'yes' === $settings['post_info_show_avatar'] ) {
				echo get_avatar( $author_id, $settings['post_info_avatar_size'] );
			}

			echo '<span>'. esc_html(get_the_author_meta( 'display_name', $author_id )) .'</span>';

		if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
			echo '</a>';
		}
	}

	// Post Taxonomy
	public function render_post_info_taxonomy( $settings ) {
		$terms = wp_get_post_terms( get_the_ID(), $settings['post_info_tax_select'] );
		$count = 0;

		// Extra Icon & Text 
		$this->render_extra_icon_text( $settings );
		
		// Taxonomies
		foreach ( $terms as $term ) {
			if ( isset($settings['post_info_link_wrap']) && 'yes' === $settings['post_info_link_wrap'] ) {
				echo '<a href="'. esc_url(get_term_link( $term->term_id )) .'">';
					// Term Name
					echo esc_html( $term->name );

					// Separator
					if ( ++$count !== count( $terms ) ) {
						echo '<span class="tax-sep">'. esc_html($settings['post_info_tax_sep']) .'</span>';
					}
				echo '</a>';
			} else {
				echo '<span>';
					// Term Name
					echo esc_html( $term->name );

					// Separator
					if ( ++$count !== count( $terms ) ) {
						echo '<span class="tax-sep">'. esc_html($settings['post_info_tax_sep']) .'</span>';
					}
				echo '</span>';
			}
		}
	}

	// Post Custom Field
	public function render_post_info_custom_field( $settings ) {}

	// Extra Icon & Text 
	public function render_extra_icon_text( $settings ) {
		if ( '' !== $settings['post_info_extra_icon'] || '' !== $settings['post_info_extra_text'] ) {
			echo '<span class="wpr-post-info-text">';
				// Extra Icon
				if ( '' !== $settings['post_info_extra_icon'] ) {
					\Elementor\Icons_Manager::render_icon( $settings['post_info_extra_icon'], [ 'aria-hidden' => 'true' ] );
				}

				// Extra Text
				if ( '' !== $settings['post_info_extra_text'] ) {
					echo '<span>'. esc_html( $settings['post_info_extra_text'] ) .'</span>';
				}
			echo '</span>';
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		echo '<ul class="wpr-post-info wpr-post-info-'. esc_attr($settings['post_info_layout']) .'">';

		foreach( $settings['post_info_elements'] as $element_settings ) {
			echo '<li class="wpr-post-info-'. esc_attr($element_settings['post_info_select']) .'">';

			switch ( $element_settings['post_info_select'] ) {
				case 'date':
					$this->render_post_info_date( $element_settings );
					break;

				case 'time':
					$this->render_post_info_time( $element_settings );
					break;

				case 'comments':
					$this->render_post_info_comments( $element_settings );
					break;

				case 'author':
					$this->render_post_info_author( $element_settings );
					break;

				case 'taxonomy':
					$this->render_post_info_taxonomy( $element_settings );
					break;

				case 'custom-field':
					$this->render_post_info_custom_field( $element_settings );
					break;
			}

			echo '</li>';
		}

		echo '</ul>';

	}
	
}