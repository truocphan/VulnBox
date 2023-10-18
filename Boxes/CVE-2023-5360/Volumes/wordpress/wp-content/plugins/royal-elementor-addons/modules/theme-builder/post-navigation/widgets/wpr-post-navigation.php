<?php
namespace WprAddons\Modules\ThemeBuilder\PostNavigation\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Post_Navigation extends Widget_Base {
	
	public function get_name() {
		return 'wpr-post-navigation';
	}

	public function get_title() {
		return esc_html__( 'Post Navigation', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-post-navigation';
	}

	public function get_categories() {
		if ( Utilities::show_theme_buider_widget_on('single') ) {
			return [ 'wpr-theme-builder-widgets' ];
		} elseif ( Utilities::show_theme_buider_widget_on('product_single') ) {
			return [ 'wpr-woocommerce-builder-widgets' ];
		} else {
			return [];
		}
	}

	public function get_keywords() {
		return [ 'navigation', 'arrows', 'pagination' ];
	}

	public function add_control_display_on_separate_lines() {
		$this->add_responsive_control(
			'display_on_separate_lines',
			[
				'label' => esc_html__( 'Display on Separate Lines', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control',
				'condition' => [
					'post_nav_layout' => 'static'
				],
			]
		);
	}

	public function add_control_post_nav_layout() {
		$this->add_control(
			'post_nav_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static' => esc_html__( 'Static Left/Right', 'wpr-addons' ),
					'pro-fx' => esc_html__( 'Fixed Left/Right (Pro)', 'wpr-addons' ),
					'pro-fd' => esc_html__( 'Fixed Default (Pro)', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_post_nav_fixed_default_align() {}

	public function add_control_post_nav_fixed_vr() {}
	
	public function add_control_post_nav_arrows_loc() {}
	
	public function add_control_post_nav_title() {}

	public function add_controls_group_post_nav_image() {}

	public function add_controls_group_post_nav_back() {}

	public function add_control_post_nav_query() {}

	public function add_controls_group_post_nav_overlay_style() {}

	public function add_control_post_nav_align_vr() {}

	public function add_section_style_post_nav_back_btn() {}

	public function add_section_style_post_nav_title() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_navigation',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_post_nav_layout();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'post-navigation', 'post_nav_layout', ['pro-fx', 'pro-fd'] );

		$this->add_control_post_nav_fixed_default_align();

		$this->add_control_post_nav_fixed_vr();

		$this->add_control(
			'post_nav_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control_post_nav_arrows_loc();
		
		$this->add_control(
			'post_nav_arrow_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => 'wpr-arrow-icons',
				'default' => 'svg-angle-2-left',
				'condition' => [
					'post_nav_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_nav_labels',
			[
				'label' => esc_html__( 'Show Labels', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_prev_text',
			[
				'label' => esc_html__( 'Previous Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Previous Post',
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_next_text',
			[
				'label' => esc_html__( 'Next Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Next Post',
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control_post_nav_title();

		$this->add_controls_group_post_nav_image();

		$this->add_controls_group_post_nav_back();

		$this->add_control(
			'post_nav_dividers',
			[
				'label' => esc_html__( 'Show Dividers', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout' => 'static'
				],
			]
		);

		$this->add_control_display_on_separate_lines();

		$this->add_control_post_nav_query();

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'post-navigation', [
			'Set Navigation Query - Force to navigate posts through specific Taxonomy (category).',
			'Advanced Layout Options - Fixed Left-Right, Fixed Bottom.',
			'Multiple Navigation Arrows Locations.',
			'Show/Hide Post Title.',
			'Show/Hide Post Thumbnail, Show on hover or set as Navigation Label Background.',
			'Show/Hide Back Button - Set custom link to any page to go back to.',
			'Display Navigation on Separate Lines'
		] );

		// Styles ====================
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->add_controls_group_post_nav_overlay_style();

		$this->add_control(
			'post_nav_background',
			[
				'label'  => esc_html__( 'Section Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_divider_color',
			[
				'label'  => esc_html__( 'Divider Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-nav-divider' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before',
				'condition' => [
					'post_nav_layout' => 'static',
					'post_nav_dividers' => 'yes'
				]
			]
		);

		$this->add_control(
			'post_nav_divider_width',
			[
				'label' => esc_html__( 'Divider Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-divider' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation-wrap' => 'border-width: {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}} 0;',
				],
				'condition' => [
					'post_nav_layout' => 'static',
					'post_nav_dividers' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'post_nav_padding',
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
					'{{WRAPPER}} .wpr-post-navigation-wrap.wpr-post-nav-dividers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-bg-images .wpr-post-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_post_nav_align_vr();

		$this->end_controls_section();

		// Styles ====================
		// Section: Arrows -----------
		$this->start_controls_section(
			'section_style_post_nav_arrow',
			[
				'label' => esc_html__( 'Arrows', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_arrows' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_arrow_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_arrow_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_arrow_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-navigation svg path' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_arrow_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_arrow_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_arrow_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'transition: color {{VALUE}}s, background-color {{VALUE}}s, border-color {{VALUE}}s',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper svg' => 'transition: fill {{VALUE}}s',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'transition: background-color {{VALUE}}s, border-color {{VALUE}}s',
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-nav-hover img' => 'transition: all {{VALUE}}s ease',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation-wrap svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-nav-prev img' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-nav-next img' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-navigation img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-post-nav-prev i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-prev .wpr-posts-navigation-svg-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-next i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-next .wpr-posts-navigation-svg-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_border_type',
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
					'{{WRAPPER}} .wpr-post-navigation i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_nav_arrow_border_width',
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
					'{{WRAPPER}} .wpr-post-navigation i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'post_nav_arrow_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_radius',
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
					'{{WRAPPER}} .wpr-post-navigation i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Back Button ------
		$this->add_section_style_post_nav_back_btn();

		// Styles ====================
		// Section: Labels -----------
		$this->start_controls_section(
			'section_style_post_nav_label',
			[
				'label' => esc_html__( 'Labels', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_label_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_label_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_label_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-post-nav-labels span',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'post_nav_label_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels span' => 'transition: color {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_label_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_label_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->add_section_style_post_nav_title();

	}

	// Arrow Icon
	public function render_arrow_by_location( $settings, $location, $dir ) {
		if ( 'fixed' === $settings['post_nav_layout'] || !wpr_fs()->can_use_premium_code() ) {
			$settings['post_nav_arrows_loc'] = 'separate';
		}

		if ( 'yes' === $settings['post_nav_arrows'] && $location === $settings['post_nav_arrows_loc'] ) {
			if (  false !== strpos( $settings['post_nav_arrow_icon'], 'svg-' ) ) {
				echo  '<div class="wpr-posts-navigation-svg-wrapper">' . Utilities::get_wpr_icon( $settings['post_nav_arrow_icon'], $dir ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo  Utilities::get_wpr_icon( $settings['post_nav_arrow_icon'], $dir ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		if ( !wpr_fs()->can_use_premium_code() ) {
			$settings['post_nav_image'] = '';
			$settings['post_nav_image_bg'] = '';
			$settings['post_nav_back'] = '';
			$settings['post_nav_title'] = '';
		}
		wp_reset_postdata();

		// Set Query
		$nav_query = isset($settings['post_nav_query']) ? $settings['post_nav_query'] : 'all';

		// Get Previous and Next Posts
		if ( 'all' === $nav_query || !wpr_fs()->can_use_premium_code() ) {
			$prev_post = get_adjacent_post( false, '', true );
			$next_post = get_adjacent_post( false, '', false );
		} else {
			$prev_post = get_adjacent_post( true, '', true, $nav_query );
			$next_post = get_adjacent_post( true, '', false, $nav_query );
		}

		// Layout Class
		$layout_class = 'wpr-post-navigation wpr-post-nav-'. $settings['post_nav_layout'];

		// Show Image on Hover
		if ( (isset($settings['post_nav_image_hover']) && 'yes' === $settings['post_nav_image_hover']) ) {
			$layout_class .= ' wpr-post-nav-hover';
		}

		$prev_image_url = '';
		$next_image_url = '';
		$prev_post_bg = '';
		$next_post_bg = '';

		// Image URLs
		if ( ! empty($prev_post) && 'yes' === $settings['post_nav_image'] ) {
			$prev_img_id = get_post_thumbnail_id( $prev_post->ID );
			$prev_image_url = Group_Control_Image_Size::get_attachment_image_src( $prev_img_id, 'post_nav_image_width_crop', $settings );
		}
		if ( ! empty($next_post) && 'yes' === $settings['post_nav_image'] ) {
			$next_img_id = get_post_thumbnail_id( $next_post->ID );
			$next_image_url = Group_Control_Image_Size::get_attachment_image_src( $next_img_id, 'post_nav_image_width_crop', $settings );
		}

		// Background Images
		if ( 'yes' === $settings['post_nav_image'] && 'yes' === $settings['post_nav_image_bg'] ) {
			if ( 'fixed' !== $settings['post_nav_layout'] ) {
				if ( ! empty($prev_post) ) {
					$prev_post_bg = ' style="background-image: url('. esc_url($prev_image_url) .')"';
				}

				if ( ! empty($next_post) ) {
					$next_post_bg = ' style="background-image: url('. esc_url($next_image_url) .')"';
				}
			}
		}

		// Navigation Wrapper
		if ( 'fixed' !== $settings['post_nav_layout'] ) {
			// Layout Class
			$wrapper_class = 'wpr-post-nav-'. $settings['post_nav_layout'] .'-wrap';

			// Dividers
			if ( 'static' === $settings['post_nav_layout'] && 'yes' === $settings['post_nav_dividers'] ) {
				$wrapper_class .= ' wpr-post-nav-dividers';
			}

			// Background Images
			if ( 'yes' === $settings['post_nav_image'] && 'yes' === $settings['post_nav_image_bg'] ) {
				$wrapper_class .= ' wpr-post-nav-bg-images';
			}

			echo '<div class="wpr-post-navigation-wrap elementor-clearfix '. esc_attr($wrapper_class) .'">';
		}

		// Previous Post
		echo '<div class="wpr-post-nav-prev '. esc_attr($layout_class) .'"'. $prev_post_bg .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( ! empty($prev_post) ) {
				echo '<a href="'. esc_url( get_permalink($prev_post->ID) ) .'" class="elementor-clearfix">';
					// Left Arrow
					$this->render_arrow_by_location( $settings, 'separate', 'left' );

					// Post Thumbnail
					if ( 'yes' === $settings['post_nav_image'] ) {
						if ( '' === $settings['post_nav_image_bg'] || 'fixed' === $settings['post_nav_layout'] ) {
							echo '<img src="'. esc_url( $prev_image_url ) .'" alt="">';
						}
					}

					// Label & Title
					if ( 'fixed' !== $settings['post_nav_layout'] ) {
						echo '<div class="wpr-post-nav-labels">';
							// Prev Label
							if ( 'yes' === $settings['post_nav_labels'] ) {
								echo '<span>';
									$this->render_arrow_by_location( $settings, 'label', 'left' );
									echo esc_html__( $settings['post_nav_prev_text'] );
								echo '</span>';
							}

							// Post Title
							if ( 'yes' === $settings['post_nav_title'] ) {
								echo '<h5>';
									$this->render_arrow_by_location( $settings, 'title', 'left' );
									echo esc_html( get_the_title($prev_post->ID) );
								echo '</h5>';
							}
						echo '</div>';
					}
				echo '</a>';

				// Image Overlay
				if ( 'yes' === $settings['post_nav_image_bg'] ) {
					echo '<div class="wpr-post-nav-overlay"></div>';
				}
			}
		echo '</div>';

		// Back to Posts
		if ( 'fixed' !== $settings['post_nav_layout'] && 'yes' === $settings['post_nav_back'] ) {
			echo '<div class="wpr-post-nav-back">';
				echo '<a href="'. esc_url($settings['post_nav_back_link'] ) .'">';
					echo '<span></span>';
					echo '<span></span>';
					echo '<span></span>';
					echo '<span></span>';
				echo '</a>';
			echo '</div>';
		}

		// Middle Divider
		if ( 'static' === $settings['post_nav_layout'] && 'yes' === $settings['post_nav_dividers'] && '' === $settings['post_nav_back'] ) {
			echo '<div class="wpr-post-nav-divider"></div>';
		}

		// Next Post
		echo '<div class="wpr-post-nav-next '. esc_attr($layout_class) .'"'. $next_post_bg .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( ! empty($next_post) ) {
				echo '<a href="'. esc_url( get_permalink($next_post->ID) ) .'" class="elementor-clearfix">';
					// Label & Title
					if ( 'fixed' !== $settings['post_nav_layout'] ) {
						echo '<div class="wpr-post-nav-labels">';
							// Next Label
							if ( 'yes' === $settings['post_nav_labels'] ) {
								echo '<span>';
									echo esc_html__( $settings['post_nav_next_text'] );
									$this->render_arrow_by_location( $settings, 'label', 'right' );
								echo '</span>';
							}

							// Post Title
							if ( 'yes' === $settings['post_nav_title'] ) {
								echo '<h5>';
									echo esc_html( get_the_title($next_post->ID) );
									$this->render_arrow_by_location( $settings, 'title', 'right' );
								echo '</h5>';
							}
						echo '</div>';
					}

					// Post Thumbnail
					if ( 'yes' === $settings['post_nav_image'] ) {
						if ( '' === $settings['post_nav_image_bg'] || 'fixed' === $settings['post_nav_layout'] ) {
							echo '<img src="'. esc_url( $next_image_url ) .'" alt="">';
						}
					}

					// Right Arrow
					$this->render_arrow_by_location( $settings, 'separate', 'right' );
				echo '</a>';

				// Image Overlay
				if ( 'yes' === $settings['post_nav_image_bg'] ) {
					echo '<div class="wpr-post-nav-overlay"></div>';
				}
			}
		echo '</div>';

		// End Navigation Wrapper
		if ( 'fixed' !== $settings['post_nav_layout'] ) {
			echo '</div>';
		}

	}
	
}