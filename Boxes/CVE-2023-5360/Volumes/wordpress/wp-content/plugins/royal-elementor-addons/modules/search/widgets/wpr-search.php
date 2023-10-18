<?php
namespace WprAddons\Modules\Search\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Search extends Widget_Base {
		
	public function get_name() {
		return 'wpr-search';
	}

	public function get_title() {
		return esc_html__( 'Search (AJAX)', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-site-search';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('archive') ? [ 'wpr-theme-builder-widgets' ] : [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'search', 'search widget', 'ajax search' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-search-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_section_style_ajax() {
		$this->start_controls_section(
			'section_style_ajax',
			[
				'label' => esc_html__( 'Ajax', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ajax_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_list',
			[
				'label' => esc_html__( 'Search List', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color_hover',
			[
				'label' => esc_html__( 'Background Color (Hover)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F6F6F6',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch ul li:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ajax_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-data-fetch'
			]
		);

		$this->add_control(
			'search_list_item_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch ul li' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_responsive_control(
            'slider_content_hr',
            [
                'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
				'selectors_dictionary' => [
					'left' => 'left: 0; right: auto;',
					'center' => 'left: 50%; transform: translateX(-50%)',
					'right' => 'right: 0; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch' => '{{VALUE}};',
				],
				'separator' => 'before'
            ]
        );

		$this->add_responsive_control(
			'search_list_width',
			[
				'label' => esc_html__( 'Container Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%' => [
						'min' => 50,
						'max' => 200,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'search_list_max_height',
			[
				'label' => esc_html__( 'Max Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch ul' => 'max-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'search_list_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'vh' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'list_item_title',
			[
				'label' => esc_html__( 'List Item', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'search_list_item_bottom_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'vh' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch ul li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'search_list_item_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch a.wpr-ajax-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-data-fetch a.wpr-ajax-title',
			]
		);

		$this->add_responsive_control(
			'title_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-content a.wpr-ajax-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#757575',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch p a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-admin-notice' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-data-fetch p a, {{WRAPPER}} .wpr-search-admin-notice',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'       => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				],
			]
		);

		$this->add_responsive_control(
			'description_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-content p.wpr-ajax-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'heading_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_ajax_thumbnails' => 'yes'
				]
			]
		);

		// $this->add_control_ajax_search_img_size();

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch a.wpr-ajax-img-wrap' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-data-fetch .wpr-ajax-search-content' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'show_ajax_thumbnails' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'image_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch a.wpr-ajax-img-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_ajax_thumbnails' => 'yes'
				]
			]
		);

		$this->add_control(
			'view_result_text_heading',
			[
				'label' => esc_html__( 'View Result', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'view_result_text_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} a.wpr-view-result' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_result_text_color_hr',
			[
				'label' => esc_html__( 'Color (Hover)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} a.wpr-view-result:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_result_text_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} a.wpr-view-result' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_result_text_bg_color_hr',
			[
				'label' => esc_html__( 'Background Color (Hover)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} a.wpr-view-result:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'view_result_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} a.wpr-view-result',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'view_result_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} a.wpr-view-result' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'view_result_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.wpr-view-result' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'view_result_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} a.wpr-view-result' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'heading_close_btn',
			[
				'label' => esc_html__( 'Close Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'close_btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch .wpr-close-search' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'close_btn_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch .wpr-close-search::before' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-data-fetch .wpr-close-search' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'close_btn_position',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch .wpr-close-search' => 'top: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'scrollbar_heading',
			[
				'label' => esc_html__( 'Scrollbar', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'scrollbar_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch ul::-webkit-scrollbar-thumb' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'scrollbar_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch ul::-webkit-scrollbar-thumb' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-data-fetch ul::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + 3px);',
				]
			]
		);

		$this->add_control(
			'no_results_heading',
			[
				'label' => esc_html__( 'No Results', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'no_results_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch .wpr-no-results' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'no_results_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-data-fetch .wpr-no-results',
			]
		);

		$this->add_responsive_control(
			'no_results_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch .wpr-no-results' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'search_results_box_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_results_box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'search_results_box_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-fetch ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
	}

	public function add_section_ajax_pagination() {}

	public function add_section_style_ajax_pagination() {}

	public function add_control_ajax_search_img_size() {

		$intermediate_image_sizes = [];

		foreach ( get_intermediate_image_sizes() as $key=>$value ) {
			$intermediate_image_sizes[$value] = $value;
		}

		$this->add_control(
			'ajax_search_img_size',
			[
				'label' => esc_html__( 'Image Crop', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => $intermediate_image_sizes,
				'default' => 'thumbnail',
			]
		);
	}

	public function add_control_search_query() {
		$search_post_type = Utilities::get_custom_types_of( 'post', false );
		$search_post_type = array_merge( [ 'all' => esc_html__( 'All', 'wpr-addons' ) ], $search_post_type );

		foreach ( $search_post_type as $key => $value ) {
			if ( 'all' != $key ) {
				$search_post_type['pro-'. $key] = $value .' (Pro)';

				if ( 'all' != $key && 'post' != $key && 'page' != $key && 'product' != $key && 'e-landing-page' != $key && !wpr_fs()->is_plan( 'expert' ) ) {
					$search_post_type['pro-'. $key] = $value .' (Expert)';
				}

				unset($search_post_type[$key]);
			}
		}

		$this->add_control(
			'search_query',
			[
				'label' => esc_html__( 'Select Query', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => $search_post_type,
				'default' => 'all',
			]
		);
	}

	public function add_control_select_category() {
		$this->add_control(
			'select_category',
			[
				'label' => esc_html__( 'Category Filter (Pro)', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_control_all_cat_text() {
	}

	public function add_control_ajax_search() {
		$this->add_control(
			'ajax_search',
			[
				'label' => esc_html__( 'Enable Ajax Search', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);
	}

	public function add_control_number_of_results() {}

	public function add_control_open_in_new_page() {
		$this->add_control(
			'ajax_search_link_target',
			[
				'label' => esc_html__( 'Open Link in New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_show_ajax_thumbnails() {
		$this->add_control(
			'show_ajax_thumbnails',
			[
				'label' => esc_html__( 'Show Ajax Thumbnails', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_exclude_posts_without_thumbnail() {
		$this->add_control(
			'exclude_posts_without_thumbnail',
			[
				'label' => esc_html__( 'Exclude Results without Thumbnails', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes',
					'show_ajax_thumbnails' => 'yes'
                ]
			]
		);
	}

	public function add_control_show_description() {
		$this->add_control(
			'show_description',
			[
				'label' => esc_html__( 'Show Description', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_number_of_words_in_excerpt() {
        $this->add_control(
            'number_of_words_in_excerpt',
            [
                'label' => __( 'Description Number of Words', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'step' => 1,
                'default' => 30,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes',
					'show_description' => 'yes'
                ]
            ]
        );
	}

	public function add_control_show_view_result_btn() {
		$this->add_control(
			'show_view_result_btn',
			[
				'label' => esc_html__( 'Show View Results Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_view_result_text() {
		$this->add_control(
			'view_result_text',
			[
				'label' => esc_html__( 'View Results', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'View Results', 'wpr-addons' ),
                'condition' => [
					'show_view_result_btn' => 'yes',
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	public function add_control_no_results_text() {
		$this->add_control(
			'no_results_text',
			[
				'label' => esc_html__( 'No Resulsts Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'No Results Found', 'wpr-addons' ),
                'condition' => [
                    'ajax_search' => 'yes'
                ]
			]
		);
	}

	protected function register_controls() {
		
		// Section: Search -----------
		$this->start_controls_section(
			'section_search',
			[
				'label' => esc_html__( 'Search', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_search_query();

		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'search', 'search_query', ['pro-post', 'pro-page', 'pro-product', 'pro-product', 'pro-e-landing-page'] );

		if ( !wpr_fs()->is_plan( 'expert' ) ) {
			$this->add_control(
				'search_query_exp_pro_notice',
				[
					'raw' => 'This option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-grid-upgrade-expert#purchasepro" target="_blank">Expert version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'search_query!' => ['all','post','page','product','e-landing-page','pro-post', 'pro-page', 'pro-product', 'pro-product', 'pro-e-landing-page'],
					]
				]
			);
		}

		$this->add_control_select_category();

		$this->add_control_all_cat_text();

		$this->add_control_ajax_search();

		$this->add_control_number_of_results();

		$this->add_control_open_in_new_page();

		$this->add_control_show_ajax_thumbnails();

		$this->add_control_exclude_posts_without_thumbnail();

		$this->add_control_show_view_result_btn();

		$this->add_control_view_result_text();
		
		$this->add_control_show_description();

		$this->add_control_number_of_words_in_excerpt();

		$this->add_control_no_results_text();

		$this->add_control(
			'search_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Search...', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_aria_label',
			[
				'label' => esc_html__( 'Aria Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Search', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_btn_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inner',
				'options' => [
					'inner' => esc_html__( 'Inner', 'wpr-addons' ),
					'outer' => esc_html__( 'Outer', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-search-form-style-',
				'render_type' => 'template',
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		// $this->add_control(
		// 	'open_search_input_on_btn_click',
		// 	[
		// 		'label' => esc_html__( 'Open Search on Click', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'condition' => [
		// 			'search_btn' => 'yes',
		// 		]
		// 	]
		// );

		$this->add_control(
			'search_btn_disable_click',
			[
				'label' => esc_html__( 'Disable Button Click', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'search_btn_style' => 'inner',
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'text' => esc_html__( 'Text', 'wpr-addons' ),
					'icon' => esc_html__( 'Icon', 'wpr-addons' ),
				],
				'render_type' => 'template',
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'search_btn_type' => 'text',
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'search_btn_type' => 'icon',
					'search_btn' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->add_section_ajax_pagination();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'search', [
			'Custom Search Query - Only Posts or Pages',
			'Custom Search Query - Only Custom Post Types (Expert)',
			'More than 2 Results in Ajax Search',
			'Enable Taxonomy Filter (Pro)',
			'Ajax Search Results Pagination (Load More)'
		] );

		// Styles
		// Section: Input ------------
		$this->start_controls_section(
			'section_style_input',
			[
				'label' => esc_html__( 'Input', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-data-fetch' => 'border-color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus_colors',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_focus_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'input_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-search-form-input, {{WRAPPER}} .wpr-category-select-wrap, {{WRAPPER}} .wpr-category-select',
			]
		);

		$this->add_responsive_control(
			'input_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
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
					'{{WRAPPER}} .wpr-search-form-input' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-data-fetch' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-data-fetch' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-category-select-wrap::before' => 'right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-category-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Select ------------
		$this->start_controls_section(
			'section_style_select',
			[
				'label' => esc_html__( 'Taxonomy Filter', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'select_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'select_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-category-select-wrap' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-category-select' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'select_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-category-select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'select_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-category-select' => 'border-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'select_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-category-select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-category-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-category-select-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'select_width',
			[
				'label' => esc_html__( 'Select Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 230,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-category-select-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'options_heading',
			[
				'label' => esc_html__( 'Options', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'option_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-category-select option' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'optgroup_heading',
			[
				'label' => esc_html__( 'Options Group', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'optgroup_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-category-select optgroup' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Button ------------
		$this->start_controls_section(
			'section_style_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_colors' );

		$this->start_controls_tab(
			'tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-submit',
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);


		$this->add_control(
			'btn_hv_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hv_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hv_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hv_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-submit:hover',
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'btn_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 125,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-style-outer .wpr-search-form-submit' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'btn_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-style-outer.wpr-search-form-position-right .wpr-search-form-submit' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-search-form-style-outer.wpr-search-form-position-left .wpr-search-form-submit' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'btn_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-search-form-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
            'btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-search-form-submit',
			]
		);

		$this->add_control(
			'btn_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: AJAX ------------
		$this->add_section_style_ajax();

		$this->add_section_style_ajax_pagination();
		
	}

	public function render_search_pagination($settings) {}

	protected function render_search_submit_btn() {
		$settings = $this->get_settings();

		$this->add_render_attribute(
		'button', [
			'class' => 'wpr-search-form-submit',
			'aria-label' => $settings['search_aria_label'],
			'type' => 'submit',
		]
		);

		if ( $settings['search_btn_disable_click'] ) {
			$this->add_render_attribute( 'button', 'disabled' );
		}

		if ( 'yes' === $settings['search_btn'] ) : ?>

		<button <?php echo $this->get_render_attribute_string( 'button' ); ?>>
			<?php if ( 'icon' === $settings['search_btn_type'] && '' !== $settings['search_btn_icon']['value'] ) : ?>
				<i class="<?php echo esc_attr( $settings['search_btn_icon']['value'] ); ?>"></i>
			<?php elseif( 'text' === $settings['search_btn_type'] && '' !== $settings['search_btn_text'] ) : ?>
				<?php echo esc_html( $settings['search_btn_text'] ); ?>
			<?php endif; ?>
		</button>

		<?php
		endif;
	}
	
	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// $hidden_input = 'yes' === $settings['open_search_input_on_btn_click'] ? 'wpr-search-input-hidden' : '';
		$hidden_input = '';

		$this->add_render_attribute(
			'input', [
				'class' => 'wpr-search-form-input',
				'placeholder' => $settings['search_placeholder'],
				'aria-label' => $settings['search_aria_label'],
				'type' => 'search',
				'name' => 's',
				'title' => esc_html__( 'Search', 'wpr-addons' ),
				'value' => get_search_query(),
				'wpr-query-type' => $settings['search_query'],
				'wpr-taxonomy-type' => isset($settings['query_taxonomy_'. $settings['search_query']]) ? $settings['query_taxonomy_'. $settings['search_query']] : '',
				'number-of-results' => isset($settings['number_of_results']) && wpr_fs()->can_use_premium_code() ? $settings['number_of_results'] : 2,
				'ajax-search' => isset($settings['ajax_search']) ? $settings['ajax_search'] : '',
				'show-description' => isset($settings['show_description']) ? $settings['show_description'] : '',
				'number-of-words' => isset($settings['number_of_words_in_excerpt']) ? $settings['number_of_words_in_excerpt'] : '',
				'show-ajax-thumbnails' => isset($settings['show_ajax_thumbnails']) ? $settings['show_ajax_thumbnails'] : '',
				'show-view-result-btn' => isset($settings['show_view_result_btn']) ? $settings['show_view_result_btn'] : '',
				'view-result-text' => isset($settings['view_result_text']) ? $settings['view_result_text'] : '',
				'no-results' => isset($settings['no_results_text']) ? esc_html__($settings['no_results_text']) : '',
				'exclude-without-thumb' => isset($settings['exclude_posts_without_thumbnail']) ? $settings['exclude_posts_without_thumbnail'] : '',
				'link-target' => isset($settings['ajax_search_link_target']) && ( 'yes' === $settings['ajax_search_link_target'] ) ? '_blank'  : '_self',
				// 'ajax-search-img-size' => isset($settings['ajax_search_img_size']) ? $settings['ajax_search_img_size'] : ''
			]
		);

		?>

		<form role="search" method="get" class="wpr-search-form" action="<?php echo esc_url(home_url()); ?>">

			<div class="wpr-search-form-input-wrap elementor-clearfix">
				<input <?php echo $this->get_render_attribute_string( 'input' ); ?>>
				<?php
				if ( $settings['search_btn_style'] === 'inner' ) {
					$this->render_search_submit_btn();
				}
				?>
			</div>

			<?php

			if ( $settings['search_btn_style'] === 'outer' ) {
				$this->render_search_submit_btn();
			}

			?>
		</form>
		<div class="wpr-data-fetch">
			<span class="wpr-close-search"></span>
			<ul></ul>
			<?php if ( !wpr_fs()->can_use_premium_code() && current_user_can( 'administrator' ) ) : ?>
				<p class="wpr-search-admin-notice">More than 2 results are available in the PRO version (This notice is only visible to admin users)</p>
			<?php endif; ?>
		</div>
		
		<?php

	}

}