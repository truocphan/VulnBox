<?php
namespace WprAddons\Modules\ThemeBuilder\AuthorBox\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Author_Box extends Widget_Base {
	
	public function get_name() {
		return 'wpr-author-box';
	}

	public function get_title() {
		return esc_html__( 'Author Box', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-person';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('single') || Utilities::show_theme_buider_widget_on('archive') ? [ 'wpr-theme-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'author', 'box', 'post', ];
	}

	public function add_controls_group_author_name_links_to() {
		$this->add_control(
			'author_name_links_to',
			[
				'label' => esc_html__( 'Links To', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'wpr-addons' ),
					'posts' => esc_html__( 'Author Posts', 'wpr-addons' ),
					'pro-ws' => esc_html__( 'Website (Pro)', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);
	}

	public function add_controls_group_author_title_links_to() {
		$this->add_control(
			'author_title_links_to',
			[
				'label' => esc_html__( 'Links To', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'wpr-addons' ),
					'posts' => esc_html__( 'Author Posts', 'wpr-addons' ),
					'pro-ws' => esc_html__( 'Website (Pro)', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);
	}

	public function add_control_author_bio() {}

	public function add_section_style_bio() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_author_box',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'author_arrange',
			[
				'label' => esc_html__( 'Arrange', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'vertical',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-author-box-arrange-'
			]
		);

		$this->add_control(
			'author_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} .wpr-author-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name',
			[
				'label' => esc_html__( 'Show Name', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name_tag',
			[
				'label' => esc_html__( 'Name HTML Tag', 'wpr-addons' ),
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
				'default' => 'h3',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);

		$this->add_controls_group_author_name_links_to();

		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'author-box', 'author_name_links_to', ['pro-ws'] );

		$this->add_control(
			'author_title',
			[
				'label' => esc_html__( 'Show Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_title_text',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Writer & Blogger',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_title_tag',
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
				'default' => 'h3',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_controls_group_author_title_links_to();

		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'author-box', 'author_title_links_to', ['pro-ws'] );

		$this->add_control_author_bio();

		$this->add_control(
			'author_posts_link',
			[
				'label' => esc_html__( 'Show Author Posts Link', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'author_posts_link_text',
			[
				'label' => esc_html__( 'Posts Link Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'All Posts',
				'condition' => [
					'author_posts_link' => [ 'yes' ],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'author-box', [
			'Link to Author Website.',
			'Show/Hide Author Biography (description).'
		] );

		// Styles ====================
		// Section: Avatar -----------
		$this->start_controls_section(
			'section_style_avatar',
			[
				'label' => esc_html__( 'Avatar', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'avatar_align',
			[
				'label' => esc_html__( 'Center Image Vertically', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'align-self: center;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-image' => '{{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Image Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 65,
				],
				'range' => [
					'px' => [
						'min' => 16,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-image img' => 'width: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'avatar_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-author-box-arrange-vertical .wpr-author-box-image' => 'margin-bottom: {{SIZE}}px',
					'{{WRAPPER}}.wpr-author-box-arrange-left .wpr-author-box-image' => 'margin-right: {{SIZE}}px',
					'{{WRAPPER}}.wpr-author-box-arrange-right .wpr-author-box-image' => 'margin-left: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'avatar_border',
				'fields_options' => [
					'border' => [
						'default' => '',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-author-box-image img',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'avatar_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'avatar_shadow',
				'selector' => '{{WRAPPER}} .wpr-author-box-image',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Name -------------
		$this->start_controls_section(
			'section_style_name',
			[
				'label' => esc_html__( 'Name', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-author-box-name a' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-author-box-name',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '500',
					],
					'font_size' => [
						'default' => [
							'size' => '18',
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.2'
						]
					],
				]
			]
		);

		$this->add_responsive_control(
			'name_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-name' => 'margin-top: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'name_bot_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-name' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title -------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-author-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-author-box-title a' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-author-box-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'    => [
						'default' => '500',
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_responsive_control(
			'title_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-title' => 'margin-top: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_bot_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-title' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Biography --------
		$this->add_section_style_bio();

		// Styles ====================
		// Section: Author Posts Link
		$this->start_controls_section(
			'section_style_archive_link',
			[
				'label' => esc_html__( 'Author Posts Link', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_archive_link_style' );

		$this->start_controls_tab(
			'tab_grid_archive_link_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'archive_link_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'archive_link_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'archive_link_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'archive_link_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-author-box-btn'
			]
		);

		$this->add_control(
			'archive_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_archive_link_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'archive_link_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-author-box-btn:hover a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'archive_link_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'archive_link_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'archive_link_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'archive_link_border_type',
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
					'{{WRAPPER}} .wpr-author-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'archive_link_border_width',
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
					'{{WRAPPER}} .wpr-author-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'archive_link_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'archive_link_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		if ( !wpr_fs()->can_use_premium_code() ) {
			$settings['author_bio'] = '';
			$settings['author_name_link_tab'] = '';
			$settings['author_title_link_tab'] = '';
		}

		// Get Author Info
		$id = get_the_author_meta( 'ID' );
		$avatar = get_avatar( $id, 264 );
		$name = get_the_author_meta( 'display_name' );
		$title = $settings['author_title_text'];
		$biography = get_the_author_meta( 'description' );
		$website = get_the_author_meta( 'user_url' );
		$archive_url = get_author_posts_url( $id );
		$author_name_link = 'website' === $settings['author_name_links_to'] ? $website : $archive_url;
		$author_name_target = 'yes' === $settings['author_name_link_tab'] ? '_blank' : '_self';
		$author_name_has_website = 'website' === $settings['author_name_links_to'] && '' !== $website ? true : false;
		$author_title_link = 'website' === $settings['author_title_links_to'] ? $website : $archive_url;
		$author_title_target = 'yes' === $settings['author_title_link_tab'] ? '_blank' : '_self';
		$author_title_has_website = 'website' === $settings['author_title_links_to'] && '' !== $website ? true : false;

		// HTML
		echo '<div class="wpr-author-box">';

			// Avatar
			if ( '' !== $settings['author_avatar'] && false !== $avatar ) {
				echo '<div class="wpr-author-box-image">';
					if ( 'posts' === $settings['author_name_links_to'] || $author_name_has_website ) {
						echo '<a href="'. esc_url( $author_name_link ) .'" target="'. esc_attr($author_name_target) .'">'. wp_kses_post($avatar) .'</a>';
					} else {
						echo wp_kses_post($avatar);
					}
				echo '</div>';
			}

			// Wrap All Text Blocks
			echo '<div class="wpr-author-box-text">';

			// Author Name
			if ( '' !== $settings['author_name'] && '' !== $name ) {
				echo '<'. esc_attr($settings['author_name_tag']) .' class="wpr-author-box-name">';
					if ( 'posts' === $settings['author_name_links_to'] || $author_name_has_website ) {
						echo '<a href="'. esc_url( $author_name_link ) .'" target="'. esc_attr($author_name_target) .'">'. esc_html($name) .'</a>';
					} else {
						echo esc_html($name);
					}
				echo '</'. esc_attr($settings['author_name_tag']) .'>';
			}

			// Author Title
			if ( '' !== $title && 'yes' === $settings['author_title'] ) {
				echo '<'. esc_attr($settings['author_title_tag']) .' class="wpr-author-box-title">';
					if ( 'posts' === $settings['author_title_links_to'] || $author_title_has_website ) {
						echo '<a href="'. esc_url( $author_title_link ) .'" target="'. esc_attr($author_title_target) .'">'. wp_kses_post($title) .'</a>';
					} else {
						echo wp_kses_post($title);
					}
				echo '</'. esc_attr($settings['author_title_tag']) .'>';
			}

			// Author Biography
			if ( '' !== $settings['author_bio'] && '' !== $biography ) {
				echo '<p class="wpr-author-box-bio">'. wp_kses_post($biography) .'</p>';
			}

			// Author Posts Link
			if ( '' !== $settings['author_posts_link'] ) {
				echo '<a href="'. esc_url( $archive_url ) .'" class="wpr-author-box-btn">';
					echo esc_html( $settings['author_posts_link_text'] );
				echo '</a>';
			}

			echo '</div>'; // End .wpr-author-box-text

		echo '</div>';
	}
	
}