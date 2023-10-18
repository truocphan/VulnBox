<?php
namespace WprAddons\Modules\ThemeBuilder\PostComments\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Post_Comments extends Widget_Base {
	
	public function get_name() {
		return 'wpr-post-comments';
	}

	public function get_title() {
		return esc_html__( 'Post Comments', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-comments';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('single') ? [ 'wpr-theme-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'comments', 'post' ];
	}

	public function add_control_comments_avatar_size() {}

	public function add_control_avatar_gutter() {
		$this->add_responsive_control(
			'avatar_gutter',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-meta, .wpr-comment-content' => 'margin-left: calc(60px + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}}.wpr-comment-reply-separate .wpr-comment-reply' => 'margin-left: calc(60px + {{SIZE}}{{UNIT}});',
				],
				'separator' => 'after',
			]
		);
	}

	public function add_control_comments_form_layout() {
		$this->add_control(
			'comments_form_layout',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-5',
				'options' => [
					'pro-s1' => esc_html__( 'Style 1 (Pro)', 'wpr-addons' ),
					'style-2' => esc_html__( 'Style 2', 'wpr-addons' ),
					'pro-s3' => esc_html__( 'Style 3 (Pro)', 'wpr-addons' ),
					'pro-s4' => esc_html__( 'Style 4 (Pro)', 'wpr-addons' ),
					'style-5' => esc_html__( 'Style 5', 'wpr-addons' ),
					'pro-s6' => esc_html__( 'Style 6 (Pro)', 'wpr-addons' ),
				],
				'separator' => 'before'
			]
		);
	}

	public function add_control_comment_form_placeholders() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_comments_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Show Section Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'comments_text_1',
			[
				'label' => esc_html__( 'One Comment', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comment',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'comments_text_2',
			[
				'label' => esc_html__( 'Multiple Comments', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comments',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'comments_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control_comments_avatar_size();

		$this->add_control(
			'comments_reply_location',
			[
				'label' => esc_html__( 'Reply Location', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'separate',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'separate' => esc_html__( 'Separate', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-comment-reply-',
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'comments_navigation_align',
			[
				'label' => __( 'Navigation Align', 'wpr-addons' ),
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
				'default' => 'center',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comments_navigation_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'selectors_dictionary' => [
					'' => 'display: none;',
					'yes' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation a.prev' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-comments-navigation a.next' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_navigation_numbers',
			[
				'label' => esc_html__( 'Show Numbers', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'selectors_dictionary' => [
					'' => 'display: none;',
					'yes' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation .page-numbers:not(.prev):not(.next)' => '{{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Comment Form -----
		$this->start_controls_section(
			'section_comment_form',
			[
				'label' => esc_html__( 'Comment Form', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'comment_form_title',
			[
				'label' => esc_html__( 'Section Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Leave a Reply',
				'condition' => [
					'section_title' => 'yes'
				]
			]
		);

		$this->add_control_comments_form_layout();

		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'post-comments', 'comments_form_layout', ['pro-s1','pro-s3','pro-s4','pro-s6'] );

		$this->add_control(
			'comment_form_labels',
			[
				'label' => esc_html__( 'Show Labels', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);



		$this->add_control_comment_form_placeholders();

		$this->add_control(
			'comment_form_website',
			[
				'label' => esc_html__( 'Show Website Field', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_form_submit_text',
			[
				'label' => esc_html__( 'Submit Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Submit',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'post-comments', [
			'6 different Comment Form Layouts.',
			'Custom comment author Avatar Size.',
			'Comment Form - Show/Hide Input Placeholder Text (Set Placeholder Text instead of Input Labels).'
		] );

		// Styles ====================
		// Section: Section Title ----
		$this->start_controls_section(
			'section_style_section_title',
			[
				'label' => esc_html__( 'Section Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'section_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
            'section_title_align',
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
					'{{WRAPPER}} .wpr-comments-wrap > h3' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'section_title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-wrap > h3' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'section_title_bd_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-wrap > h3' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'section_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-comments-wrap > h3',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '17',
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
					],
				]
			]
		);

		$this->add_control(
			'section_title_bd_type',
			[
				'label' => esc_html__( 'Border Style', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-comments-wrap > h3' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'section_title_bd_width',
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
					'{{WRAPPER}} .wpr-comments-wrap > h3' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'section_title_bd_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'section_title_space',
			[
				'label' => esc_html__( 'Bottom Space', 'wpr-addons' ),
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
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-wrap > h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Comments ---------
		$this->start_controls_section(
			'section_style_comments',
			[
				'label' => esc_html__( 'Comments', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'comment_odd_color',
			[
				'label' => esc_html__( 'Odd Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fcfcfc',
				'selectors' => [
					'{{WRAPPER}} .even .wpr-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_even_color',
			[
				'label' => esc_html__( 'Even Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fcfcfc',
				'selectors' => [
					'{{WRAPPER}} .odd .wpr-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_author_color',
			[
				'label' => esc_html__( 'By Post Author Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EFEFEF',
				'selectors' => [
					'{{WRAPPER}} .bypostauthor .wpr-post-comment' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-comment' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comment_shadow',
				'selector' => '{{WRAPPER}} .wpr-post-comment',
			]
		);

		$this->add_responsive_control(
			'comment_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-comment' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_border_width',
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
					'{{WRAPPER}} .wpr-post-comment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'comment_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'comment_radius',
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
					'{{WRAPPER}} .wpr-post-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comment_spacing',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
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
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-comment' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comment_indent',
			[
				'label' => esc_html__( 'Nested Indent', 'wpr-addons' ),
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
					'size' => 24,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-list .children' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Avatar -----------
		$this->start_controls_section(
			'section_style_avatar',
			[
				'label' => esc_html__( 'Avatar', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'comments_avatar' => 'yes',
				],
			]
		);

		$this->add_control_avatar_gutter();

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
				'selector' => '{{WRAPPER}} .wpr-comment-avatar',
			]
		);

		$this->add_control(
			'avatar_radius',
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
					'{{WRAPPER}} .wpr-comment-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Nickname ---------
		$this->start_controls_section(
			'section_style_nickname',
			[
				'label' => esc_html__( 'Nickname', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_nickname_style' );

		$this->start_controls_tab(
			'tab_nickname_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nickname_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-author span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comment-author a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'nickname_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-comment-author',
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

		$this->add_control(
			'nickname_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-author a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nickname_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nickname_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-author a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'nickname_space',
			[
				'label' => esc_html__( 'Bottom Space', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-author' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Date and Time ----
		$this->start_controls_section(
			'section_style_metadata',
			[
				'label' => esc_html__( 'Date and Time', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'metadata_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9B9B9B',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-metadata' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comment-metadata a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comment-reply:before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'metadata_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-comment-metadata',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_size'      => [
						'default'    => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'metadata_space',
			[
				'label' => esc_html__( 'Bottom Space', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-metadata' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content (Comment Text)', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-content a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-comment-content',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Open Sans',
					],
					'font_weight'    => [
						'default' => '400',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Reply Link -------
		$this->start_controls_section(
			'section_style_reply_link',
			[
				'label' => esc_html__( 'Reply Link', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_reply_link_style' );

		$this->start_controls_tab(
			'tab_reply_link_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'reply_link_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'reply_link_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FCFCFC',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'reply_link_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'reply_link_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-comment-reply a',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'reply_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_reply_link_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'reply_link_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'reply_link_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'reply_link_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'reply_link_padding',
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
					'{{WRAPPER}} .wpr-comment-reply a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'reply_link_margin',
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
					'{{WRAPPER}} .wpr-comment-reply a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'reply_link_border_type',
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
					'{{WRAPPER}} .wpr-comment-reply a' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reply_link_border_width',
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
					'{{WRAPPER}} .wpr-comment-reply a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'reply_link_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'reply_link_radius',
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
					'{{WRAPPER}} .wpr-comment-reply a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'reply_link_align',
			[
				'label' => __( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'right',
				'prefix_class' => 'wpr-comment-reply-align-',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_navigation_style' );

		$this->start_controls_tab(
			'tab_navigation_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'navigation_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comments-navigation span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'navigation_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comments-navigation span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'navigation_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comments-navigation span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-comments-navigation a, {{WRAPPER}} .wpr-comments-navigation span',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'navigation_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_navigation_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'navigation_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comments-navigation span.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'navigation_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comments-navigation span.current' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'navigation_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comments-navigation a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comments-navigation span.current' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'navigation_padding',
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
					'{{WRAPPER}} .wpr-comments-navigation a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comments-navigation span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'navigation_border_type',
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
					'{{WRAPPER}} .wpr-comments-navigation a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-comments-navigation span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'navigation_border_width',
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
					'{{WRAPPER}} .wpr-comments-navigation a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comments-navigation span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'navigation_radius',
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
					'{{WRAPPER}} .wpr-comments-navigation a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comments-navigation span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();	

		// Styles ====================
		// Section: Comment Form Title
		$this->start_controls_section(
			'section_style_cf_title',
			[
				'label' => esc_html__( 'Comment Form Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'cf_title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cf_title_bd_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply-title' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cf_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-comment-reply-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_family' => [
						'default' => 'Raleway',
					],
					'font_weight'    => [
						'default' => '500',
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
					],
					'font_size'      => [
						'default'    => [
							'size' => '17',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'cf_title_bd_type',
			[
				'label' => esc_html__( 'Border Style', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-comment-reply-title' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'cf_title_bd_width',
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
					'{{WRAPPER}} .wpr-comment-reply-title' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cf_title_bd_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'cf_title_top_space',
			[
				'label' => esc_html__( 'Top Space', 'wpr-addons' ),
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
					'size' => 85,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'cf_title_bottom_space',
			[
				'label' => esc_html__( 'Bottom Space', 'wpr-addons' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-reply-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'cf_title_align',
            [
                'label' => esc_html__( 'Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
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
					'{{WRAPPER}} .wpr-comment-reply-title' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();

		// Styles ====================
		// Section: Comment Form -----
		$this->start_controls_section(
			'section_style_comment_form',
			[
				'label' => esc_html__( 'Comment Form', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_comment_form_style' );

		$this->start_controls_tab(
			'tab_comment_form_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'comment_form_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-comment-form textarea' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-comment-form label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comment-form .logged-in-as a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-comment-form .logged-in-as .required-field-message' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_form_placeholder_color',
			[
				'label'  => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B8B8B8',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .wpr-comment-form textarea::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .wpr-comment-form input[type=text]::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-comment-form textarea::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
				'condition' => [
					'comment_form_placeholders' => 'yes'
				]
			]
		);

		$this->add_control(
			'comment_form_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comment-form textarea' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'comment_form_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DBDBDB',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-comment-form textarea' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-comment-form label, {{WRAPPER}} .wpr-comment-form input[type=text], {{WRAPPER}} .wpr-comment-form textarea, {{WRAPPER}} .wpr-comment-form .logged-in-as',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'comment_form_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-comment-form input[type=text]::placeholder' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-comment-form input[type=text]::-ms-input-placeholder' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-comment-form textarea' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_comment_form_hover',
			[
				'label' => __( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'comment_form_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-comment-form textarea:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comment_form_placeholder_color_hr',
			[
				'label'  => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B8B8B8',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]:focus::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .wpr-comment-form textarea:focus::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .wpr-comment-form input[type=text]:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-comment-form textarea:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
				'condition' => [
					'comment_form_placeholders' => 'yes'
				]
			]
		);

		$this->add_control(
			'comment_form_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]:focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-comment-form textarea:focus' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'comment_form_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-comment-form textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'comment_form_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comment-form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'comment_form_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form input[type=text]' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} .wpr-comment-form textarea' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_form_border_width',
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
					'{{WRAPPER}} .wpr-comment-form input[type=text]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comment-form textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'comment_form_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'comment_form_radius',
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
					'{{WRAPPER}} .wpr-comment-form input[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comment-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comment_form_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-form-author' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comment-form-email' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comment-form-url' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-comment-form-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Submit Button ----
		$this->start_controls_section(
			'section_style_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_submit_button_style' );

		$this->start_controls_tab(
			'tab_submit_button_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'submit_button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'submit_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DBDBDB',
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-submit-comment',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'letter_spacing' => [
						'default' => [
							'size' => '0.5'
						]
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

		$this->add_control(
			'submit_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submit_button_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'submit_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4C48BD',
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'submit_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'submit_button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 45,
					'bottom' => 10,
					'left' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'submit_button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 25,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-submit-comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'submit_button_border_type',
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
					'{{WRAPPER}} .wpr-submit-comment' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submit_button_border_width',
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
					'{{WRAPPER}} .wpr-submit-comment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'submit_button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'submit_button_radius',
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
					'{{WRAPPER}} .wpr-submit-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'submit_button_align',
            [
                'label' => esc_html__( 'Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
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
					'{{WRAPPER}} .form-submit' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section();

	}

	// Outputs a comment in the HTML5 format
	public static function html5_comment( $comment, $args, $depth ) {
		// Get Settings
		$this_widget = $GLOBALS['wpr_post_comments_widget'];
		$settings = $this_widget->get_settings();

		if ( !wpr_fs()->can_use_premium_code() ) {
			$settings['comments_avatar_size'] = 60;
		}

		// Class, URL, Name
		$comment_class = implode( ' ', get_comment_class( $comment->has_children ? 'parent' : '', $comment ) );
		$author_url = get_comment_author_url( $comment );
		$author_name = get_comment_author( $comment );

		// Comment HTML
		echo '<li id="comment-'. esc_attr(get_comment_ID()) .'" class="'. esc_attr( $comment_class ) .'">';
		echo '<article class="wpr-post-comment elementor-clearfix">';

			// Comment Avatar
			if ( 'yes' === $settings['comments_avatar'] ) {
				echo '<div class="wpr-comment-avatar">';
					echo get_avatar( $comment, $settings['comments_avatar_size'] );
				echo '</div>';
			}

			// Comment Meta
			echo '<div class="wpr-comment-meta">';
				// Comment Author
				echo '<div class="wpr-comment-author">';
					if ( '' === $author_url ) {
						echo '<span>'. esc_html( $author_name ) .'</span>';
					} else {
						echo '<a href="'. esc_url( $author_url ) .'">'. esc_html( $author_name ) .'</a>';
					}
				echo '</div>';

				// Comment Metadata
				echo '<div class="wpr-comment-metadata elementor-clearfix">';
					// Date and Time
					echo '<span>'. esc_html(get_comment_date( '', $comment )) . esc_html__( ' at ', 'wpr-addons' ) . esc_html(get_comment_time()) .'</span>';

					// Edit Link
					edit_comment_link( esc_html__( 'Edit', 'wpr-addons' ), ' | ', '' );

					// Reply Button
					if ( 'inline' === $settings['comments_reply_location'] ) {
						comment_reply_link(
							array_merge( $args, [
								'depth' => $depth,
								'max_depth' => $args['max_depth'],
								'before' => '<div class="wpr-comment-reply">',
								'after' => '</div>',
							] )
						);
					}

					// Moderation
					if ( '0' == $comment->comment_approved ) {
						echo '<p>'. esc_html__( 'Your comment is awaiting moderation.', 'wpr-addons' ) .'</p>';
					}
				echo '</div>';
			echo '</div>';

			// Comment Content
			echo '<div class="wpr-comment-content">';
				comment_text( $comment );
			echo '</div>';

			// Reply Button
			if ( 'separate' === $settings['comments_reply_location'] ) {
				comment_reply_link(
					array_merge( $args, [
						'depth' => $depth,
						'max_depth' => $args['max_depth'],
						'before' => '<div class="wpr-comment-reply">',
						'after' => '</div>',
					] )
				);
			}

		echo '</article>';
		echo '</li>';
	}

	protected function render() {
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Temp log out user
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}


		//  Get Settings
		$settings = $this->get_settings();

		$GLOBALS['wpr_post_comments_widget'] = $this;

		if ( ! comments_open( get_the_ID() ) ) {
			return;
		}

		// Comments Count
		$count = get_comments_number( get_the_ID() );

		// Comments Wrapper
		echo '<div class="wpr-comments-wrap" id="comments">';

			// If comments are open or we have at least one comment
			if ( $count ) {

				if ( $count == 1 ) {
					$text = $count .' '. $settings['comments_text_1'];
				} elseif ( $count > 1 ) {
					$text = $count .' '. $settings['comments_text_2'];
				}

				// Comments
				if ( 'yes' === $settings['section_title'] ) {
					echo '<h3> '. esc_html($text) .'</h3>';
				}

				// Get Post Comments
				$get_comments = get_comments( [ 'post_id' => get_the_ID() ] );

				// Comments List HTML
				echo '<ul class="wpr-comments-list">';
					wp_list_comments( [ 'callback' => [$this, 'html5_comment'] ], $get_comments );
				echo '</ul>';

				unset( $GLOBALS['wpr_post_comments_widget'] );

				// Comments Navigation
				if ( get_comment_pages_count($get_comments) > 1 && get_option( 'page_comments' ) ) {
					echo '<div class="wpr-comments-navigation wpr-comments-navigation-'. esc_html($settings['comments_navigation_align']) .'">';
						paginate_comments_links([
							'base' => add_query_arg( 'cpage', '%#%' ),
							'format' => '',
							'total' => get_comment_pages_count($get_comments),
							'echo' => true,
							'add_fragment' => '#comments',
							'prev_text' => '<i class="eicon-arrow-left"></i> '. esc_html__( 'Previous', 'wpr-addons' ),
							'next_text' => esc_html__( 'Next', 'wpr-addons' ) .' <i class="eicon-arrow-right"></i>',
						]);
					echo '</div>';
				}
			}

			// Comment Form: Author, Email and Website Fields
			add_filter( 'comment_form_default_fields', function( $defaults ) {
				$settings = $this->get_settings();
				$author_label = $email_label = $url_label = '';
				$author_ph = $email_ph = $url_ph = '';
				$req = get_option( 'require_name_email' );

				// Labels
				if ( 'yes' === $settings['comment_form_labels'] ) {
					$author_label = '<label>'. esc_html__( 'Name', 'wpr-addons' ) . ($req ? '<span>*</span>' : '') .'</label>';
					$email_label = '<label>'. esc_html__( 'Email', 'wpr-addons' ) . ($req ? '<span>*</span>' : '') .'</label>';
					$url_label = '<label>'. esc_html__( 'Website', 'wpr-addons' ) .'</label>';					
				}

				if ( !wpr_fs()->can_use_premium_code() ) {
					$settings['comment_form_placeholders'] = '';
				}

				// Placeholders
				if ( 'yes' === $settings['comment_form_placeholders'] ) {
					$author_ph = esc_html__( 'Name', 'wpr-addons' ) . ($req ? '*' : '');
					$email_ph = esc_html__( 'Email', 'wpr-addons' ) . ($req ? '*' : '');
					$url_ph = esc_html__( 'Website', 'wpr-addons' );
				}

				$fields = [
					// name
					'author' => '<div class="wpr-comment-form-fields"> <div class="wpr-comment-form-author">'. $author_label .
					'<input type="text" name="author" placeholder="'. esc_attr($author_ph) .'"/></div>',
					// Email
					'email' => '<div class="wpr-comment-form-email">'. $email_label .
					'<input type="text" name="email" placeholder="'. esc_attr($email_ph) .'"/></div>',
					// Website
					'url' => '<div class="wpr-comment-form-url">'. $url_label .
					'<input type="text" name="url" placeholder="'. esc_url($url_ph) .'"/></div></div>',
				];

				// Remove Website Field
				if ( '' === $settings['comment_form_website'] ) {
					$fields['url'] = '</div>';
				}

				return $fields;
			} );

			// Comment Form Defaults
			add_filter( 'comment_form_defaults', function( $defaults ) {
				$settings = $this->get_settings();
				$text_label = $text_ph = '';
				$req = get_option( 'require_name_email' );

				// Text Input Label
				if ( 'yes' === $settings['comment_form_labels'] ) {
					$text_label = '<label>'. esc_html__( 'Message', 'wpr-addons' ) . ($req ? '<span>*</span>' : '') .'</label>';
				}

				if ( !wpr_fs()->can_use_premium_code() ) {
					$settings['comment_form_placeholders'] = '';
				}

				// Text Input Placeholder
				if ( 'yes' === $settings['comment_form_placeholders'] ) {
					$text_ph = esc_html__( 'Message', 'wpr-addons' ) . ($req ? '*' : '');
				}

				// Form
				$defaults['id_form'] = 'wpr-comment-form';
				$defaults['class_form'] = 'wpr-comment-form wpr-cf-'. esc_attr($settings['comments_form_layout']);

				// No Website Filed Class
				if ( '' === $settings['comment_form_website'] ) {
					$defaults['class_form'] .= ' wpr-cf-no-url';
				}

				// Title
				$defaults['title_reply'] = $settings['comment_form_title'];
				$defaults['title_reply_before'] = '<h3 id="wpr-reply-title" class="wpr-comment-reply-title">';
				$defaults['title_reply_after'] = '</h3>';

				// Text Field
				$defaults['comment_field']  = '<div class="wpr-comment-form-text">'. $text_label;
				$defaults['comment_field'] .= '<textarea name="comment" placeholder="'. esc_attr($text_ph) .'" cols="45" rows="8" maxlength="65525"></textarea>';
				$defaults['comment_field'] .= '</div>';

				// Submit Button
				$defaults['id_submit'] = 'wpr-submit-comment';
				$defaults['class_submit'] = 'wpr-submit-comment';
				$defaults['label_submit'] = $settings['comment_form_submit_text'];

				return $defaults;
			} );

			// Form Output
			comment_form();

		echo '</div>'; // End .wpr-comments-wrap


		// Logged-in user back.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
	}
	
}