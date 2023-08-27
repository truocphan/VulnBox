<?php
namespace JupiterX_Core\Raven\Modules\Post_Comments\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Box_Shadow;
use JupiterX_Core\Raven\Controls\Query;

defined( 'ABSPATH' ) || die();

/**
 * Post commments widget.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 */
class Post_Comments extends Base_Widget {
	protected $_has_template_content = false;

	public function get_name() {
		return 'raven-post-comments';
	}

	public function get_title() {
		return __( 'Post Comments', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-post-comments';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Comments', 'jupiterx-core' ),
			]
		);

		$this->content_styles();

		$this->end_controls_section();

		$this->custom_style_alert();
		$this->heading_style();
		$this->comment_container();
		$this->comment_content();
		$this->author_styles();
		$this->comment_form();
		$this->comment_form_fields();
		$this->consent_checkbox();
		$this->submit_button();
		$this->action_links();
	}

	private function content_styles() {
		$this->add_control(
			'comment_style',
			[
				'label' => esc_html__( 'Style Source', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Theme Default Form Customizer', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'comment_source',
			[
				'label' => esc_html__( 'Source', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => [
					'current' => esc_html__( 'Current Post', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'post_source',
			[
				'label' => esc_html__( 'Search & Select', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'options'     => [
					'1' => esc_html__( 'All', 'jupiterx-core' ),
				],
				'label_block' => true,
				'multiple'    => false,
				'default'     => '1',
				'query'       => [
					'source' => Query::QUERY_SOURCE_POST,
				],
				'condition' => [
					'comment_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'enable_dummy',
			[
				'label' => esc_html__( 'Load Dummy Data in Editor', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_control(
			'enable_guest',
			[
				'label' => esc_html__( 'Enable Guest Mode in Editor', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
	}

	private function custom_style_alert() {
		$this->start_controls_section(
			'custom_alert',
			[
				'label' => esc_html__( 'Custom Style', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'default',
				],
			]
		);

		$this->add_control(
			'important_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Please switch the style source option to the custom, in order to be able to customize style of the widget.', 'jupiterx-core' ),
				'content_classes' => 'elementor-control-field-description',
			]
		);

		$this->end_controls_section();
	}

	private function heading_style() {
		$this->start_controls_section(
			'heading_style',
			[
				'label' => esc_html__( 'Heading', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'heading_style_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comments-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_style_typography',
				'selector' => '{{WRAPPER}} .jupiterx-comments-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'heading_style_text_shadow',
				'selector' => '{{WRAPPER}} .jupiterx-comments-title',
			]
		);

		$this->add_control(
			'heading_style_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#191919',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comments-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} #reply-title' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'heading_style_border',
				'selector' => '{{WRAPPER}} .jupiterx-comments-title',
			]
		);

		$this->add_responsive_control(
			'heading_style_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comments-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'heading_style_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comments-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function comment_container() {
		$this->start_controls_section(
			'container_style',
			[
				'label' => esc_html__( 'Comment Container', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'comment_container_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .jupiterx-comments-list',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'comment_container_border',
				'selector' => '{{WRAPPER}} .jupiterx-comments-list',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comments-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comment_container_box_shadow',
				'selector' => '{{WRAPPER}} .jupiterx-comments-list',
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'comment_container_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comments-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comment_container_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comments-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function comment_content() {
		$this->start_controls_section(
			'comment_content_style',
			[
				'label' => esc_html__( 'Comment Content', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_content_style_typography',
				'selector' => '{{WRAPPER}} li.comment > article.jupiterx-comment,
				{{WRAPPER}} li.comment h1,
				{{WRAPPER}} li.comment h2,
				{{WRAPPER}} li.comment h3,
				{{WRAPPER}} li.comment h4,
				{{WRAPPER}} li.comment h5,
				{{WRAPPER}} li.comment h6',
			]
		);

		$this->add_control(
			'comment_content_style_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#585858',
				'selectors' => [
					'{{WRAPPER}} li.comment > article.jupiterx-comment' => 'color: {{VALUE}}',
					'{{WRAPPER}}  li.comment h1' => 'color: {{VALUE}}',
					'{{WRAPPER}}  li.comment h2' => 'color: {{VALUE}}',
					'{{WRAPPER}}  li.comment h3' => 'color: {{VALUE}}',
					'{{WRAPPER}}  li.comment h4' => 'color: {{VALUE}}',
					'{{WRAPPER}}  li.comment h5' => 'color: {{VALUE}}',
					'{{WRAPPER}}  li.comment h6' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'comment_content_style_links_heading',
			[
				'label' => esc_html__( 'Links', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs(
			'comment_content_style_link_style_tabs'
		);

		$this->start_controls_tab(
			'comment_content_style_link_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'comment_content_style_link_normal_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CCCCCC',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-username > a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-comment-body a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'comment_content_style_link_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'comment_content_style_link_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-username:hover > a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-comment-body a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'comment_content_style_border',
				'selector' => '{{WRAPPER}} li.comment > article.jupiterx-comment',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comment_content_style_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} li.comment > article.jupiterx-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comment_content_style_box_shadow',
				'selector' => '{{WRAPPER}} li.comment > article.jupiterx-comment',
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'comment_content_style_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} li.comment > article.jupiterx-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comment_content_style_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} li.comment > article.jupiterx-comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function author_styles() {
		$this->start_controls_section(
			'author_style_controls',
			[
				'label' => esc_html__( 'Author', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_control(
			'author_style_avatar_heading',
			[
				'label' => esc_html__( 'Avatar', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'author_style_avatar_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
				],
				'default' => 'flex-start',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment' => 'align-items: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'author_style_avatar_size',
			[
				'label' => esc_html__( 'Image Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 200,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-design-1-left-side' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .user-avatar-at-comment-form' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}' => '--avatar-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'author_style_avatar_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment' => 'column-gap: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'author_style_avatar_show_border',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'author_style_avatar_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-design-1-left-side .jupiterx-comment-avatar > img' => 'border-color: {{VALUE}} !important',
				],
				'condition' => [
					'author_style_avatar_show_border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'author_style_avatar_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-design-1-left-side .jupiterx-comment-avatar > img' => 'border-width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'author_style_avatar_show_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_style_avatar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-design-1-left-side .jupiterx-comment-avatar > img' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'author_style_avatar_show_border' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'author_style_avatar_box_shadow',
				'selector' => '{{WRAPPER}} .jupiterx-comment-design-1-left-side .jupiterx-comment-avatar > img',
			]
		);

		$this->add_control(
			'author_style_avatar_name_heading',
			[
				'label' => esc_html__( 'Author Name', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_style_avatar_name_typography',
				'selector' => '{{WRAPPER}} #comments .jupiterx-comment-username',
			]
		);

		$this->add_control(
			'author_style_avatar_name_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#070809',
				'selectors' => [
					'{{WRAPPER}} #comments .jupiterx-comment-username > a' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'author_style_avatar_name_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-title' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'author_style_avatar_meta_heading',
			[
				'label' => esc_html__( 'Author Meta', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_style_avatar_meta_typography',
				'selector' => '{{WRAPPER}} .jupiterx-comment-meta',
			]
		);

		$this->add_control(
			'author_style_avatar_meta_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#818181',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-meta' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'author_style_avatar_moderator_badge_heading',
			[
				'label' => esc_html__( 'Moderator Badge', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_style_avatar_moderator_badge_typography',
				'selector' => '{{WRAPPER}} .jupiterx-comment-title .btn-primary',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'author_style_avatar_moderator_badge_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .jupiterx-comment-title .btn-primary',
			]
		);

		$this->add_control(
			'author_style_avatar_moderator_badge_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-title .btn-primary' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'author_style_avatar_moderator_badge_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-title .btn-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'author_style_avatar_moderator_badge_border',
				'selector' => '{{WRAPPER}} .jupiterx-comment-title .btn-primary',
			]
		);

		$this->add_control(
			'author_style_avatar_moderator_badge_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-title .btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'author_style_awaiting_moderator_badge_heading',
			[
				'label' => esc_html__( 'Awaiting Moderation Badge', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_style_awaiting_moderator_badge_typography',
				'selector' => '{{WRAPPER}} .jupiterx-comment-title .btn-warning',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'author_style_awaiting_moderator_badge_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .jupiterx-comment-title .btn-warning',
			]
		);

		$this->add_control(
			'author_style_awaiting_moderator_badge_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-title .btn-warning' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'author_style_awaiting_moderator_badge_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-title .btn-warning' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'author_style_awaiting_moderator_badge_border',
				'selector' => '{{WRAPPER}} .jupiterx-comment-title .btn-warning',
			]
		);

		$this->add_control(
			'author_style_awaiting_moderator_badge_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-title .btn-warning' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function comment_form() {
		$this->start_controls_section(
			'comment_form_controls',
			[
				'label' => esc_html__( 'Comment Form', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_controls_typography',
				'selector' => '{{WRAPPER}} #commentform p',
			]
		);

		$this->add_control(
			'comment_form_controls_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'comment_form_controls_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} #commentform',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'comment_form_controls_border',
				'selector' => '{{WRAPPER}} #commentform',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comment_form_controls_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} #commentform' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comment_form_controls_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} #commentform' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'comment_form_controls_form_heading',
			[
				'label' => esc_html__( 'Form Heading', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'comment_form_controls_form_heading_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} #reply-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_controls_form_heading_typography',
				'selector' => '{{WRAPPER}} #reply-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'comment_form_controls_form_heading_text_shadow',
				'selector' => '{{WRAPPER}} #reply-title',
			]
		);

		$this->add_control(
			'comment_form_controls_form_heading_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #reply-title' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'comment_form_controls_form_heading_border',
				'selector' => '{{WRAPPER}} #reply-title',
			]
		);

		$this->add_responsive_control(
			'comment_form_controls_form_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} #reply-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comment_form_controls_form_heading_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} #reply-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function comment_form_fields() {
		$this->start_controls_section(
			'comment_form_field_controls',
			[
				'label' => esc_html__( 'Comment Form Fields', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'comment_form_field_controls_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #commentform .row' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'comment_form_field_style_tabs'
		);

		$this->start_controls_tab(
			'comment_form_field_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'comment_form_field_style_normal_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform input[type=text]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} #commentform textarea' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'comment_form_field_style_normal_border',
				'selector' => '{{WRAPPER}} #commentform textarea, {{WRAPPER}} #commentform input[type=text]',
			]
		);

		$this->add_control(
			'comment_form_field_style_normal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #commentform input[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} #commentform textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comment_form_field_style_normal_box_shadow',
				'selector' => '{{WRAPPER}} #commentform textarea, {{WRAPPER}} #commentform input[type=text]',
			]
		);

		$this->add_control(
			'comment_form_field_style_normal_placeholder_heading',
			[
				'label' => esc_html__( 'Placeholder', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'comment_form_field_style_normal_placeholder_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform input::placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} #commentform textarea::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_field_style_normal_placeholder_typography',
				'selector' => '{{WRAPPER}} #commentform input::placeholder, {{WRAPPER}} #commentform textarea::placeholder',
			]
		);

		$this->add_control(
			'comment_form_field_style_normal_value_heading',
			[
				'label' => esc_html__( 'Value', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'comment_form_field_style_normal_value_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform input[type=text], {{WRAPPER}} #commentform textarea' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_field_style_normal_value_typography',
				'selector' => '{{WRAPPER}} #commentform input[type=text], {{WRAPPER}} #commentform textarea',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'comment_form_field_style_focus_tab',
			[
				'label' => esc_html__( 'Focus', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'comment_form_field_style_focus_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform input[type=text]:focus' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} #commentform textarea:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'comment_form_field_style_focus_border',
				'selector' => '{{WRAPPER}} #commentform textarea:focus, {{WRAPPER}} #commentform input[type=text]:focus',
			]
		);

		$this->add_control(
			'comment_form_field_style_focus_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #commentform input[type=text]:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} #commentform textarea:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comment_form_field_style_focus_box_shadow',
				'selector' => '{{WRAPPER}} #commentform textarea:focus, {{WRAPPER}} #commentform input[type=text]:focus',
			]
		);

		$this->add_control(
			'comment_form_field_style_focus_placeholder_heading',
			[
				'label' => esc_html__( 'Placeholder', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'comment_form_field_style_focus_placeholder_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform textarea:focus::placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} #commentform input:focus::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_field_style_focus_placeholder_typography',
				'selector' => '{{WRAPPER}} #commentform textarea:focus::placeholder, {{WRAPPER}} #commentform input:focus::placeholder',
			]
		);

		$this->add_control(
			'comment_form_field_style_focus_value_heading',
			[
				'label' => esc_html__( 'Value', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'comment_form_field_style_focus_value_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform input[type=text]:focus' => 'color: {{VALUE}}',
					'{{WRAPPER}} #commentform textarea:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comment_form_field_style_focus_value_typography',
				'selector' => '{{WRAPPER}} #commentform textarea:focus, {{WRAPPER}} #commentform input[type=text]:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'comment_form_field_style_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} #commentform input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} #commentform textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	private function consent_checkbox() {
		$this->start_controls_section(
			'consent_checkbox_controls',
			[
				'label' => esc_html__( 'Consent Checkbox', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'consent_checkbox_controls_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 17,
						'max' => 150,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-cookies-consent' => '--comment-cookies-consent: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'consent_checkbox_controls_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-form-cookies-consent' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'consent_checkbox_controls_typography',
				'selector' => '{{WRAPPER}} .comment-form-cookies-consent',
			]
		);

		$this->add_responsive_control(
			'consent_checkbox_controls_space_between',
			[
				'label' => esc_html__( 'Spacing Between', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-cookies-consent' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'consent_checkbox_controls_space',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .comment-form-cookies-consent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'consent_checkbox_controls_style_tabs'
		);

		$this->start_controls_tab(
			'consent_checkbox_controls_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'consent_checkbox_controls_style_normal_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .comment-form-cookies-consent input:before' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'consent_checkbox_controls_style_normal_border',
				'selector' => '{{WRAPPER}} #commentform #wp-comment-cookies-consent:before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'consent_checkbox_controls_style_normal_box_shadow',
				'selector' => '{{WRAPPER}} #commentform #wp-comment-cookies-consent:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'consent_checkbox_controls_style_checked_tab',
			[
				'label' => esc_html__( 'Checked', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'consent_checkbox_controls_style_checked_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform #wp-comment-cookies-consent:checked:after' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'consent_checkbox_controls_style_checked_border',
				'selector' => '{{WRAPPER}} #commentform #wp-comment-cookies-consent:checked:before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'consent_checkbox_controls_style_checked_box_shadow',
				'selector' => '{{WRAPPER}} #commentform #wp-comment-cookies-consent:checked:before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'consent_checkbox_controls_style_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #commentform #wp-comment-cookies-consent:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} #commentform #wp-comment-cookies-consent:checked:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	private function submit_button() {
		$this->start_controls_section(
			'submit_button_controls',
			[
				'label' => esc_html__( 'Submit Button', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'submit_button_controls_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .form-submit button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'submit_button_controls_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
						'step' => 1,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .form-submit button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'submit_button_controls_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .form-submit' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_button_controls_typography',
				'selector' => '{{WRAPPER}} #comments #respond .form-submit .btn',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'submit_button_controls_text_shadow',
				'selector' => '{{WRAPPER}} .form-submit button',
			]
		);

		$this->start_controls_tabs(
			'submit_button_controls_style_tabs'
		);

		$this->start_controls_tab(
			'submit_button_controls_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'submit_button_controls_style_normal_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform button[type=submit]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'submit_button_controls_style_normal_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} #commentform button[type=submit]',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submit_button_controls_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'submit_button_controls_style_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #commentform button[type=submit]:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'submit_button_controls_style_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} #commentform button[type=submit]:hover',
			]
		);

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'submit_button_controls_border',
				'selector' => '{{WRAPPER}} #commentform button[type=submit]',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submit_button_controls_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} #commentform button[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'submit_button_controls_box_shadow',
				'selector' => '{{WRAPPER}} #commentform button[type=submit]',
			]
		);

		$this->add_responsive_control(
			'submit_button_controls_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} #commentform button[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'submit_button_controls_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} #commentform button[type=submit]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function action_links() {
		$this->start_controls_section(
			'action_links_controls',
			[
				'label' => esc_html__( 'Action Links', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'comment_style' => 'custom',
				],
			]
		);

		$this->add_control(
			'action_links_controls_description',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'This section will affect links such as "Edit", "Reply", "Link", "Logout", "Edit Profile"', 'jupiterx-core' ),
				'content_classes' => 'elementor-control-field-description',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'action_links_controls_typography',
				'selector' => '{{WRAPPER}} .jupiterx-comment-links a, {{WRAPPER}} .logged-in-as a',
			]
		);

		$this->start_controls_tabs(
			'action_links_controls_style_tabs'
		);

		$this->start_controls_tab(
			'action_links_controls_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'action_links_controls_style_normal_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-links a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .logged-in-as a' => 'color: {{VALUE}}',
					'{{WRAPPER}} #reply-title small a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'action_links_icons_controls_style_normal_color',
			[
				'label' => esc_html__( 'Icons', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CCCCCC',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-links i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'action_links_controls_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'action_links_controls_style_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jupiterx-comment-links a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .logged-in-as a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} #reply-title small a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'action_links_icons_controls_style_hover_color',
			[
				'label' => esc_html__( 'Icons', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--action-links-icon-color-hover: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function get_demo_content() {
		$comment_1_1 = [
			'comment_ID' => 2,
			'comment_post_ID' => 1,
			'comment_author' => esc_html__( 'Brett Newman', 'jupiterx-core' ),
			'comment_author_email' => 'bob+avatar1@artbees.net',
			'comment_author_url' => 'https://jupiterx.com',
			'comment_author_IP' => '',
			'comment_date' => '2023-01-17 08:35:49',
			'comment_date_gmt' => '2023-01-17 08:35:49',
			'comment_content' => esc_html__( 'I just finished reading your post and I must say, it was a real eye-opener. The way you presented the information was very insightful and thought-provoking. I especially liked the way you included personal anecdotes to make the content more relatable. Your writing style is engaging and easy to follow, I am definitely looking forward to reading more of your posts in the future. Thank you for sharing your knowledge with the world!', 'jupiterx-core' ),
			'comment_karma' => '0',
			'comment_approved' => '0',
			'comment_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
			'comment_type' => 'comment',
			'comment_parent' => 1,
			'user_id' => 10001,
		];

		$comment_1_2 = [
			'comment_ID' => 3,
			'comment_post_ID' => 1,
			'comment_author' => esc_html__( 'Kristen Hartley', 'jupiterx-core' ),
			'comment_author_email' => 'bob+avatar8@artbees.net',
			'comment_author_url' => 'https://jupiterx.com',
			'comment_author_IP' => '',
			'comment_date' => '2023-01-17 08:35:49',
			'comment_date_gmt' => '2023-01-17 08:35:49',
			'comment_content' => esc_html__( 'Great article, I love how you explained the concept so clearly.', 'jupiterx-core' ),
			'comment_karma' => 0,
			'comment_approved' => 0,
			'comment_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
			'comment_type' => 'comment',
			'comment_parent' => 1,
			'user_id' => 1,
		];

		$comment_1 = [
			'comment_ID' => 1,
			'comment_post_ID' => 33,
			'comment_author' => esc_html__( 'Darren Hayden', 'jupiterx-core' ),
			'comment_author_email' => 'bob+avatar2@artbees.net',
			'comment_author_url' => 'https://jupiterx.com',
			'comment_author_IP' => '',
			'comment_date' => '2023-01-17 08:35:49',
			'comment_date_gmt' => '2023-01-17 08:35:49',
			'comment_content' => esc_html__( 'I totally agree with your point of view, keep up the good work!', 'jupiterx-core' ),
			'comment_karma' => 0,
			'comment_approved' => 1,
			'comment_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
			'comment_type' => 'comment',
			'comment_parent' => 0,
			'user_id' => 10000,
			'children' => [
				'2' => $comment_1_1,
				'3' => $comment_1_2,
			],
			'populated_children' => 2,
		];

		$comment_2 = [
			'comment_ID' => 4,
			'comment_post_ID' => 1,
			'comment_author' => esc_html__( 'Laila George', 'jupiterx-core' ),
			'comment_author_email' => 'bob+avatar5@artbees.net',
			'comment_author_url' => 'https://jupiterx.com',
			'comment_author_IP' => '',
			'comment_date' => '2023-01-17 08:35:49',
			'comment_date_gmt' => '2023-01-17 08:35:49',
			'comment_content' => esc_html__( 'Thanks for sharing this informative post, it was really helpful.', 'jupiterx-core' ),
			'comment_karma' => 0,
			'comment_approved' => 0,
			'comment_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
			'comment_type' => 'comment',
			'comment_parent' => 0,
			'user_id' => 20000,
		];

		$comment_3 = [
			'comment_ID' => 5,
			'comment_post_ID' => 1,
			'comment_author' => esc_html__( 'Lillian Fowler', 'jupiterx-core' ),
			'comment_author_email' => 'bob+avatar10@artbees.net',
			'comment_author_url' => 'https://jupiterx.com',
			'comment_author_IP' => '',
			'comment_date' => '2023-01-17 08:35:49',
			'comment_date_gmt' => '2023-01-17 08:35:49',
			'comment_content' => esc_html__( 'Your post was a masterpiece, I was completely blown away by the level of expertise and passion you brought to the table. The way you structured the content was very organized and easy to follow. The examples you used were relevant and helped to illustrate your points in a vivid manner. The conclusion was especially well done and left me with a sense of closure. I am honored to have read this post, thank you for sharing your wisdom with us!', 'jupiterx-core' ),
			'comment_karma' => 0,
			'comment_approved' => 0,
			'comment_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
			'comment_type' => 'comment',
			'comment_parent' => 0,
			'user_id' => 30000,
		];

		$comment_4_1 = [
			'comment_ID' => 7,
			'comment_post_ID' => 1,
			'comment_author' => esc_html__( 'Brett Newman', 'jupiterx-core' ),
			'comment_author_email' => 'bob+avatar1@artbees.net',
			'comment_author_url' => 'https://jupiterx.com',
			'comment_author_IP' => '',
			'comment_date' => '2023-01-17 08:35:49',
			'comment_date_gmt' => '2023-01-17 08:35:49',
			'comment_content' => esc_html__( 'Your writing style is amazing, I always look forward to your posts.', 'jupiterx-core' ),
			'comment_karma' => 0,
			'comment_approved' => 0,
			'comment_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
			'comment_type' => 'comment',
			'comment_parent' => 6,
			'user_id' => 41000,
		];

		$comment_4_2 = [
			'comment_ID' => 8,
			'comment_post_ID' => 1,
			'comment_author' => esc_html__( 'Darren Hayden', 'jupiterx-core' ),
			'comment_author_email' => 'bob+avatar2@artbees.net',
			'comment_author_url' => 'https://jupiterx.com',
			'comment_author_IP' => '',
			'comment_date' => '2023-01-17 08:35:49',
			'comment_date_gmt' => '2023-01-17 08:35:49',
			'comment_content' => esc_html__( 'Your post was exactly what I needed to read today, it was filled with valuable insights and practical advice. The way you broke down the information into easy-to-digest segments made it very accessible. I was especially impressed by the amount of time and effort you put into writing this post, it really shows. I have already started implementing some of the tips you shared and I can already see a positive impact. Thank you for taking the time to create this fantastic resource!', 'jupiterx-core' ),
			'comment_karma' => 0,
			'comment_approved' => 0,
			'comment_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
			'comment_type' => 'comment',
			'comment_parent' => 6,
			'user_id' => 42000,
		];

		$comment_4 = [
			'comment_ID' => 6,
			'comment_post_ID' => 1,
			'comment_author' => esc_html__( 'Kristen Hartley', 'jupiterx-core' ),
			'comment_author_email' => 'bob+avatar8@artbees.net',
			'comment_author_url' => 'https://jupiterx.com',
			'comment_author_IP' => '',
			'comment_date' => '2023-01-17 08:35:49',
			'comment_date_gmt' => '2023-01-17 08:35:49',
			'comment_content' => esc_html__( 'I appreciate your honesty and vulnerability in this post, it takes courage.', 'jupiterx-core' ),
			'comment_karma' => 0,
			'comment_approved' => 0,
			'comment_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
			'comment_type' => 'comment',
			'comment_parent' => 0,
			'user_id' => 40000,
			'children' => [
				'7' => $comment_4_1,
				'8' => $comment_4_2,
			],
			'populated_children' => 2,
		];

		$comments   = [];
		$comments[] = json_decode( wp_json_encode( $comment_1_1 ), false );
		$comments[] = json_decode( wp_json_encode( $comment_1_2 ), false );
		$comments[] = json_decode( wp_json_encode( $comment_1 ), false );
		$comments[] = json_decode( wp_json_encode( $comment_2 ), false );
		$comments[] = json_decode( wp_json_encode( $comment_3 ), false );
		$comments[] = json_decode( wp_json_encode( $comment_4_1 ), false );
		$comments[] = json_decode( wp_json_encode( $comment_4_2 ), false );
		$comments[] = json_decode( wp_json_encode( $comment_4 ), false );

		return $comments;
	}

	/**
	 * Generate dummy data. we keep function for later uses.
	 *
	 * @since 3.0.0
	 */
	private function dummy_data() {
		add_filter( 'comments_array', function() {
			$comments = $this->get_demo_content();

			return $comments;
		} );
	}

	/**
	 * Default comments data.
	 *
	 * @since 3.0.0
	 */
	private function default_data() {
		if ( function_exists( 'jupiterx_comments_template' ) ) {
			jupiterx_comments_template();

			return;
		}

		comments_template();
	}

	protected function render_warning() {
		?>
		<div class="elementor-alert elementor-alert-danger" role="alert">
			<span class="elementor-alert-title"> <?php esc_html_e( 'Comments are closed. Switch on comments from', 'jupiterx-core' ); ?></span><br />
			<div class="elementor-alert-description">
				<ul>
					<li> <?php esc_html_e( 'WordPress Customizer or', 'jupiterx-core' ); ?> </li>
					<li> <?php esc_html_e( 'Discussion box on the WordPress post edit screen or', 'jupiterx-core' ); ?> </li>
					<li> <?php esc_html_e( 'WordPress discussion settings or', 'jupiterx-core' ); ?> </li>
					<li> <?php esc_html_e( 'Page/post meta fields.', 'jupiterx-core' ); ?> </li>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Modify comments query arguments.
	 *
	 * @param array $arguments query arguments.
	 * @since 3.0.0
	 */
	public function custom_post( $arguments ) {
		$arguments['post_id'] = $this->get_settings_for_display()['post_source'];

		return $arguments;
	}

	public function add_user_avatar_to_comment_form( $default ) {
		if ( ! is_user_logged_in() ) {
			return $default;
		}

		$user_id = get_current_user_id();
		$avatar  = get_avatar_url( $user_id );

		?>
			<div class="jupiterx-comment-form-structure-custom">
				<div class="user-avatar-at-comment-form">
					<img src="<?php echo esc_url( $avatar ); ?>">
				</div>
				<?php echo jupiterx_comment_form_comment(); ?>
			</div>
		<?php
	}

	public function before_header() {
		echo '<div class="jupiterx-comment-design-1-left-side">';
			echo jupiterx_comment_avatar();
		echo '</div>';

		echo '<div class="jupiterx-comment-design-1-right-side">'; // Gets closed at the jupiterx_comment_after_body hook.
	}

	public function close_inner_wrapper() {
		echo '</div>';
	}

	protected function render() {
		$settings           = $this->get_settings_for_display();
		$is_preview_or_edit = Plugin::instance()->preview->is_preview_mode() || Plugin::instance()->editor->is_edit_mode();
		$dummy_file         = 'extensions/raven/includes/modules/post-comments/dummy/dummy-user';

		//! Only Editor, let admin sees the form as guest.
		if (
			Plugin::instance()->editor->is_edit_mode()
			&& array_key_exists( 'enable_guest', $settings )
			&& 'yes' === $settings['enable_guest']
		) {
			$dummy_file = 'extensions/raven/includes/modules/post-comments/dummy/dummy-guest';
			$user       = get_current_user_id();
			wp_set_current_user( 0 );
			add_filter( 'comments_template_query_args', function( $args ) {
				$args['status'] = 'any';

				return $args;
			} );
		}

		add_filter( 'jupiterx_comment_textarea_placeholder', function() {
			return esc_html__( 'Type here to reply ...' );
		} );

		add_filter( 'get_avatar_data', function( $args ) {
			$url         = $args['url'];
			$url         = str_replace( 's=50', 's=256', $url );
			$args['url'] = $url;

			return $args;
		}, 99, 1 );

		if ( 'custom' === $settings['comment_source'] && ! empty( $settings['post_source'] ) ) {
			add_filter( 'comments_template_query_args', [ $this, 'custom_post' ], 1, 10 );

			$original_post   = $GLOBALS['post'];
			$GLOBALS['post'] = get_post( $settings['post_source'] ); // phpcs:ignore
		}

		$post_type = get_post_type();

		if ( function_exists( 'jupiterx_post_element_enabled' ) && ! jupiterx_post_element_enabled( 'comments', $post_type ) && $is_preview_or_edit ) {
			$this->render_warning();

			return;
		}

		if ( ! comments_open() && $is_preview_or_edit ) {
			$this->render_warning();

			return;
		}

		$classes = [];

		if ( 'default' === $settings['comment_style'] ) :
			$classes[] = 'jupiterx-post-comments-widget-style-default';
		endif;

		if ( 'custom' === $settings['comment_style'] ) :
			jupiterx_add_smart_action( 'comment_form_field_comment', [ $this, 'add_user_avatar_to_comment_form' ] );
			add_action( 'jupiterx_comment_header', function() {
				remove_all_actions( 'jupiterx_comment_header', 5 );
			}, 1 );
			add_filter( 'jupiterx_post_comments_has_custom_style', '__return_true' );
			add_action( 'jupiterx_comment_before_header', [ $this, 'before_header' ], 5 );
			add_action( 'jupiterx_comment_after_body', [ $this, 'close_inner_wrapper' ], 5 );

			$classes[] = 'jupiterx-post-comments-widget-style-customized';
			$classes[] = ( is_user_logged_in() ) ? 'jupiterx-post-comments-widget-user-logged-in' : 'jupiterx-post-comments-widget-user-guest';
			$classes[] = ( 'center' === $settings['author_style_avatar_vertical_align'] ) ? 'jupiterx-post-comment-avatar-centered' : '';
		endif;

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		echo sprintf(
			'<div %s >',
			$this->get_render_attribute_string( 'wrapper' )
		);

		// comment data.
		if (
			array_key_exists( 'enable_dummy', $settings )
			&& 'yes' === $settings['enable_dummy']
			&& Plugin::instance()->editor->is_edit_mode()
		) {
			// Generated by $this->dummy_data.
			jupiterx_core()->load_files(
				[
					$dummy_file,
				]
			);
		} else {
			$this->default_data();
		}

		echo '</div>';

		if ( 'custom' === $settings['comment_source'] && ! empty( $settings['post_source'] ) ) {
			remove_filter( 'comments_template_query_args', [ $this, 'custom_post' ], 10 );

			$GLOBALS['post'] = $original_post; // phpcs:ignore
		}

		if (
			Plugin::instance()->editor->is_edit_mode()
			&& array_key_exists( 'enable_guest', $settings )
			&& 'yes' === $settings['enable_guest']
		) {
			wp_set_current_user( $user );
		}
	}
}
