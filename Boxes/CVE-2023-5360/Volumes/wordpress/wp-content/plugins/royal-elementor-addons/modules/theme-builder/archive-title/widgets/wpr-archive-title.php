<?php
namespace WprAddons\Modules\ThemeBuilder\ArchiveTitle\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Archive_Title extends Widget_Base {
	
	public function get_name() {
		return 'wpr-archive-title';
	}

	public function get_title() {
		return esc_html__( 'Archive Title/Desc', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-site-title';
	}

	public function get_categories() {
		if ( Utilities::show_theme_buider_widget_on('archive') ) {
			return [ 'wpr-theme-builder-widgets' ];
		} elseif ( Utilities::show_theme_buider_widget_on('product_archive') ) {
			return [ 'wpr-woocommerce-builder-widgets' ];
		} else {
			return [];
		}
	}

	public function get_keywords() {
		return [ 'archive', 'title', 'description', 'category', 'tag' ];
	}

	public function add_control_archive_description() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_title',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
            'post_title_align',
            [
                'label' => esc_html__( 'Alignment', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'label_block' => false,
                'options' => [
					'left'    => [
						'title' => __( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors_dictionary' => [
					'left' => 'text-align: left;',
					'center' => 'text-align: center; margin: 0 auto;',
					'right' => 'text-align: right; margin-left: auto;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-archive-title:after' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-archive-description' => '{{VALUE}}',
				],
            ]
        );

		$this->add_control(
			'post_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h1',
			]
		);

		$this->add_control(
			'post_title_before_text',
			[
				'label' => esc_html__( 'Text Before Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
			]
		);

		$this->add_control_archive_description();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'archive-title', [
			'Show/Hide Taxonomy (Category) Description, also change Color and Typography.',
		] );

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title & Description', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_before_text_color',
			[
				'label'  => esc_html__( 'Before Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '24',
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-archive-title'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .wpr-archive-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .wpr-archive-title',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'wpr-addons' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title' => 'mix-blend-mode: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_desc_heading',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'archive_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-description' => 'color: {{VALUE}}',
				],
				'condition' => [
					'archive_description' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-archive-description',
				'condition' => [
					'archive_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_divider_heading',
			[
				'label' => esc_html__( 'Divider', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_divider_show',
			[
				'label' => esc_html__( 'Show Divider', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'title_divider_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title:after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_divider_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title:after' => 'height: {{SIZE}}px;',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_divider_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title:after' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_divider_distance_top',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 7,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title:after' => 'margin-top: {{SIZE}}px;',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_divider_distance_bot',
			[
				'label' => esc_html__( 'Bottom Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-archive-title:after' => 'margin-bottom: {{SIZE}}px;',
				],
				'condition' => [
					'title_divider_show' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$tax = get_queried_object();

		if ( !is_null($tax) ) {
			$title = isset($tax->post_title) ? $tax->post_title : $tax->name;
			$description = isset($tax->description) ? $tax->description : '';
			
			if ( function_exists('is_shop') && is_shop() ) {
				$title = $tax->label;
			}

			if ( '' !== $title ) {
				echo '<'. esc_attr($settings['post_title_tag']) .' class="wpr-archive-title">';
					echo '<span>'. wp_kses_post($settings['post_title_before_text']) .'</span>'. esc_html($title);
				echo '</'. esc_attr($settings['post_title_tag']) .'>';
			}

			if ( wpr_fs()->can_use_premium_code() ) {
				if ( '' !== $description && '' !== $settings['archive_description'] ) {
					echo '<p class="wpr-archive-description">'. wp_kses_post($description) .'</p>';
				}
			}
		} elseif ( is_search() ) {
			echo '<'. esc_attr($settings['post_title_tag']) .' class="wpr-archive-title">';
				echo '<span>'. esc_html($settings['post_title_before_text']) .'</span>'. esc_html(get_search_query());
			echo '</'. esc_attr($settings['post_title_tag']) .'>';	
		}

	}
	
}