<?php
namespace WprAddons\Modules\ThemeBuilder\PostContent\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Post_Content extends Widget_Base {
	
	public function get_name() {
		return 'wpr-post-content';
	}

	public function get_title() {
		return esc_html__( 'Post Content', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-post-content';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('single') ? [ 'wpr-theme-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'post', 'content' ];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_content',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_content_display',
			[
				'label' => esc_html__( 'Display As', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => [
					'content' => esc_html__( 'Post Content', 'wpr-addons' ),
					'excerpt' => esc_html__( 'Post Excerpt', 'wpr-addons' ),
				],
			]
		);

		// $this->add_control(
		// 	'post_content_dropcap',
		// 	[
		// 		'label' => esc_html__( 'Enable Drop Cap', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'separator' => 'before'
		// 	]
		// );

		$this->add_responsive_control(
            'post_content_align',
            [
                'label' => esc_html__( 'Alignment', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => __( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-content' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-content a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_link_hover_color',
			[
				'label'  => esc_html__( 'Link Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-content a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-post-content',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'title_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-content a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	[
		// 		'name'     => 'content_dropcap_typography',
		// 		'label' => esc_html__( 'Drop Cap Typography', 'wpr-addons' ),
		// 		'scheme' => Typography::TYPOGRAPHY_3,
		// 		'selector' => '{{WRAPPER}} .wpr-post-content.wpr-enable-dropcap p:first-child:first-letter'
		// 	]
		// );

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// $dropcap_class = 'yes' === $settings['post_content_dropcap'] ? ' wpr-enable-dropcap' : '';

		echo '<div class="wpr-post-content">';
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			     \Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();
			} else {
				if ( 'content' === $settings['post_content_display'] ) {
					the_content();
				} else {
					the_excerpt();
				}
			}
		echo '</div>';

	}
	
}