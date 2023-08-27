<?php
namespace JupiterX_Core\Raven\Modules\Sticky_Media_Scroller\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Utils;
use Elementor\Embed;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use JupiterX_Core\Raven\Controls\Query;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * @suppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Sticky_Media_Scroller extends Base_Widget {

	public function get_name() {
		return 'raven-sticky-media-scroller';
	}

	public function get_title() {
		return esc_html__( 'Sticky Media Scroller', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-sticky-media-scroller';
	}

	public function register_controls() {
		$this->register_controls_content_section();
		$this->register_controls_content_settings();
		$this->register_controls_style_media();
		$this->register_controls_style_content();
	}

	protected function register_controls_content_section() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Section', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs(
			'content_section_tabs'
		);

		$repeater->start_controls_tab(
			'content_section_tab_media',
			[
				'label' => esc_html__( 'Media', 'jupiterx-core' ),
			]
		);

		$this->register_controls_content_section_tab_media( $repeater );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'content_section_tab_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		$this->register_controls_content_section_tab_content( $repeater );

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'sections',
			[
				'label' => esc_html__( 'Sections', 'jupiterx-core' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'frontend_available' => true,
				'default' => [
					[],
					[],
					[],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls_content_section_tab_media( $repeater ) {
		$repeater->add_control(
			'media_type',
			[
				'label' => esc_html__( 'Media Type', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'image' => [
						'title' => esc_html__( 'Image', 'jupiterx-core' ),
						'icon' => 'eicon-paint-brush',
					],
					'video' => [
						'title' => esc_html__( 'Video', 'jupiterx-core' ),
						'icon' => 'eicon-video-camera',
					],
				],
				'default' => 'image',
				'toggle' => true,
			]
		);

		$repeater->add_responsive_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'condition' => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_responsive_control(
			'image_aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1 / 1' => '1:1',
					'1 / 2' => '2:1',
					'2 / 3' => '3:2',
					'3 / 4' => '4:3',
					'4 / 5' => '5:4',
					'3 / 5' => '5:3',
					'5 / 8' => '8:5',
					'5 / 9' => '9:5',
					'16 / 9' => '9:16',
					'7 / 10' => '10:7',
					'9 / 16' => '16:9',
					'9 / 20' => '20:9',
					'9 / 21' => '21:9',
					'9 / 25' => '25:9',
				],
				'condition' => [
					'media_type' => 'image',
				],
				'default' => '9 / 16',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .media-type-image picture' => 'padding-bottom: calc( {{VALUE}} * 100% );',
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_responsive_control(
			'item_media_position',
			[
				'label' => esc_html__( 'Media Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'center center',
				'options' => [
					'center center' => esc_html__( 'Center Center', 'jupiterx-core' ),
					'center left' => esc_html__( 'Center Left', 'jupiterx-core' ),
					'center right' => esc_html__( 'Center Right', 'jupiterx-core' ),
					'top center' => esc_html__( 'Top Center', 'jupiterx-core' ),
					'top left' => esc_html__( 'Top Left', 'jupiterx-core' ),
					'top right' => esc_html__( 'Top Right', 'jupiterx-core' ),
					'bottom center' => esc_html__( 'Bottom Center', 'jupiterx-core' ),
					'bottom left' => esc_html__( 'Bottom Left', 'jupiterx-core' ),
					'bottom right' => esc_html__( 'Bottom Right', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .media-type-image picture img' => '-o-object-position: {{VALUE}}; object-position: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'video_url',
			[
				'label' => esc_html__( 'Video Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'autocomplete' => false,
				'options' => false,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'description' => esc_html__( 'YouTube/Vimeo link, or link to a video file (mp4 is recommended).', 'jupiterx-core' ),
				'placeholder' => esc_html__( 'https://www.youtube.com/watch?v=GuAL8OhcbNk', 'jupiterx-core' ),
				'condition' => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'start',
			[
				'label' => esc_html__( 'Start Time', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'description' => esc_html__( 'Specify a start time (in seconds)', 'jupiterx-core' ),
				'condition' => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'end',
			[
				'label' => esc_html__( 'End Time', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'description' => esc_html__( 'Specify an end time (in seconds)', 'jupiterx-core' ),
				'condition' => [
					'media_type' => 'video',
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_responsive_control(
			'video_aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1 / 1' => '1:1',
					'1 / 2' => '2:1',
					'2 / 3' => '3:2',
					'3 / 4' => '4:3',
					'4 / 5' => '5:4',
					'3 / 5' => '5:3',
					'5 / 8' => '8:5',
					'5 / 9' => '9:5',
					'16 / 9' => '9:16',
					'7 / 10' => '10:7',
					'9 / 16' => '16:9',
					'9 / 20' => '20:9',
					'9 / 21' => '21:9',
					'9 / 25' => '25:9',
				],
				'condition' => [
					'media_type' => 'video',
				],
				'default' => '9 / 16',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .video-wrapper' => 'padding-bottom: calc( {{VALUE}} * 100% );',
				],
			]
		);

		$repeater->add_control(
			'mute',
			[
				'label' => esc_html__( 'Mute', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 1,
				'return_value' => 1,
				'condition' => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'frontend_available' => true,
				'default' => 1,
				'return_value' => 1,
				'condition' => [
					'media_type' => 'video',
					'mute' => 1,
				],
			]
		);

		$repeater->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'default' => '',
				'return_value' => 1,
				'condition' => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'player_controls',
			[
				'label' => esc_html__( 'Player Controls', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'default' => '',
				'return_value' => 1,
				'condition' => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'youtube_privacy',
			[
				'label' => esc_html__( 'Privacy Mode', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Only works for YouTube videos.', 'jupiterx-core' ),
				'return_value' => 1,
				'condition' => [
					'media_type' => 'video',
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'fallback_poster',
			[
				'label' => esc_html__( 'Background Fallback', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'media_type' => 'video',
				],
				'description' => esc_html__( 'This cover image will replace the background video in case that the video could not be loaded.', 'jupiterx-core' ),
			]
		);
	}

	protected function register_controls_content_section_tab_content( $repeater ) {
		$repeater->add_control(
			'content_type',
			[
				'label' => esc_html__( 'Content Type', 'jupiterx-core' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'options' => [
					'editor' => esc_html__( 'Editor', 'jupiterx-core' ),
					'template' => esc_html__( 'Template', 'jupiterx-core' ),
				],
				'default' => 'editor',
			]
		);

		$repeater->add_control(
			'content_heading',
			[
				'label' => esc_html__( 'Heading', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum at efficitur ipsum. In porttitor justo nec mauris tempus, sit amet mollis justo.', 'jupiterx-core' ),
				'type' => Controls_Manager::WYSIWYG,
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'content_button_text',
			[
				'label' => esc_html__( 'Button Text', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Button', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'content_button_link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'custom_template',
			[
				'label' => esc_html__( 'Choose a Template', 'jupiterx-core' ),
				'type' => 'raven_query',
				'multiple' => false,
				'label_block' => true,
				'query' => [
					'source' => Query::QUERY_SOURCE_TEMPLATE,
					'template_types' => [
						'section',
					],
				],
				'default' => false,
				'condition' => [
					'content_type' => 'template',
				],
			]
		);
	}

	protected function register_controls_content_settings() {
		$this->start_controls_section(
			'content_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'sections_space_between',
			[
				'label' => esc_html__( 'Sections Space Between', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .sticky-mode-wrapper .content-section-wrapper:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .responsive-mode-wrapper .responsive-mode-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_height',
			[
				'label' => esc_html__( 'Section Height', 'jupiterx-core' ),
				'description' => esc_html__( 'To achieve full height Container use 100vh.', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 470,
				],
				'selectors' => [
					'{{WRAPPER}} .content-section-wrapper' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .responsive-mode-wrapper .responsive-mode-item' => 'height: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label' => esc_html__( 'Content Width', 'jupiterx-core' ),
				'description' => esc_html__( 'Only applied to the sticky mode.', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 69,
				],
				'selectors' => [
					'{{WRAPPER}} .sticky-mode-wrapper .content-column' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sticky-mode-wrapper .media-column' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .responsive-mode-wrapper .responsive-mode-item' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'media_position',
			[
				'label' => esc_html__( 'Media Position', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'row',
				'options' => [
					'row' => esc_html__( 'Left', 'jupiterx-core' ),
					'row-reverse' => esc_html__( 'Right', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .sticky-mode-wrapper' => 'flex-direction: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'stick_position',
			[
				'label' => esc_html__( 'Stick Position', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'frontend_available' => true,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
			]
		);

		$this->add_control(
			'media_transition',
			[
				'label' => esc_html__( 'Media Transition', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'jupiterx-core' ),
					'slide' => esc_html__( 'Slide', 'jupiterx-core' ),
					'zoom' => esc_html__( 'Zoom', 'jupiterx-core' ),
				],
				'prefix_class' => 'media-transition-',
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => esc_html__( 'Transition Speed (ms)', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'selectors' => [
					'{{WRAPPER}}' => '--transition-speed: {{VALUE}}ms;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls_style_media() {
		$this->start_controls_section(
			'style_media',
			[
				'label' => esc_html__( 'Media', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'media_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'media_border',
				'selector' => '{{WRAPPER}} .section [class*="media-type-"] img, {{WRAPPER}} .section [class*="media-type-"] video, {{WRAPPER}} .section [class*="media-type-"] iframe',
			]
		);

		$this->add_control(
			'media_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .section .media-type-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .section .media-type-video video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .section .media-type-video iframe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'media_border_radius_hr_media_box_shadow',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'media_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .section [class*="media-type-"] img, {{WRAPPER}} .section [class*="media-type-"] video, {{WRAPPER}} .section [class*="media-type-"] iframe',
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls_style_content() {
		$this->start_controls_section(
			'style_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_tab_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'These settings are only applied to sections with the Editor content type.', 'jupiterx-core' ),
				'content_classes' => 'elementor-control-field-description',
			]
		);

		$this->add_responsive_control(
			'content_box_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .section .content-type-editor .content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .section .content-type-template' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_element_styles_heading',
			[
				'label' => esc_html__( 'Heading', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .section .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .section .title',
			]
		);

		$this->add_control(
			'content_element_styles_heading',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .section .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section .content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .section .content',
			]
		);

		$this->add_control(
			'button_element_styles_heading',
			[
				'label' => esc_html__( 'Button', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'jupiterx-core' ),
					'sm' => esc_html__( 'Small', 'jupiterx-core' ),
					'md' => esc_html__( 'Medium', 'jupiterx-core' ),
					'lg' => esc_html__( 'Large', 'jupiterx-core' ),
					'xl' => esc_html__( 'Extra Large', 'jupiterx-core' ),
				],
				'default' => 'sm',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .section .raven-sticky-media-scroller-button',
			]
		);

		$this->add_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .section .raven-sticky-media-scroller-button' => 'border-width: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .section .raven-sticky-media-scroller-button' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs(
			'button_style_tabs'
		);

		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_normal_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section .raven-sticky-media-scroller-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_normal_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .section .raven-sticky-media-scroller-button',
			]
		);

		$this->add_control(
			'button_normal_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section .raven-sticky-media-scroller-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section .raven-sticky-media-scroller-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .section .raven-sticky-media-scroller-button:hover',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section .raven-sticky-media-scroller-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .section .raven-sticky-media-scroller-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		add_filter( 'wp_kses_allowed_html', [ $this, 'allow_tags_on_wp_kses_post' ], 10, 2 );
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'widget-wrapper',
			'class',
			'raven-sticky-media-scroller'
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'widget-wrapper' ); ?>>
			<div class="sticky-mode-wrapper">
				<div class="media-column">
					<?php echo $this->render_column( $settings, 'media' ); ?>
				</div>
				<div class="content-column">
					<?php echo $this->render_column( $settings, 'content' ); ?>
				</div>
			</div>
			<div class="responsive-mode-wrapper">
				<?php echo $this->render_responsive_content_and_media(); ?>
			</div>
		</div>
		<?php
		remove_filter( 'wp_kses_allowed_html', [ $this, 'allow_tags_on_wp_kses_post' ] );
	}

	/**
	 * Add Tags to allowed wp_kses_post tags
	 *
	 * @param array  $tags Allowed tags, attributes, and/or entities.
	 * @param string $context Context to judge allowed tags by. Allowed values are 'post'.
	 *
	 * @return array
	 * @since 3.0.0
	 */
	public function allow_tags_on_wp_kses_post( $tags, $context ) {
		$custom_attributes = $this->get_all_custom_attributes();

		if ( 'post' !== $context ) {
			return $tags;
		}

		$tags['iframe']  = [
			'src' => true,
			'allow' => true,
			'title' => true,
			'width' => true,
			'height' => true,
			'frameborder' => true,
			'allowfullscreen' => true,
			'modestbranding' => true,
			'rel' => true,
			'lazy_load' => true,
			'privacy' => true,
			'style' => true,
			'data-*' => true,
		];
		$tags['video']   = [
			'muted' => true,
			'autoplay' => true,
			'controls' => true,
			'loop' => true,
			'data-*' => true,
		];
		$tags['source']  = [
			'src' => true,
			'media' => true,
			'srcset' => true,
		];
		$tags['picture'] = [
			'class' => true,
		];
		$tags['style']   = [];
		$tags['a']       = array_merge(
			[
				'class' => true,
				'target' => true,
				'rel' => true,
				'href' => true,
				'data-*' => true,
			],
			$custom_attributes
		);

		return $tags;
	}

	private function render_column( $settings, $column ) {
		ob_start();
		foreach ( $settings['sections'] as $index => $section ) {
			$css_id = ! empty( $section['css_id'] ) ? esc_attr( $section['css_id'] ) : esc_attr( 'item-' . $section['_id'] );

			$this->add_render_attribute( $column . '-section-wrapper' . $section['_id'], [
				'class' => [
					'section',
					$column . '-section-wrapper',
					0 === $index ? 'active' : '',
					'elementor-repeater-item-' . $section['_id'],
					'elementor-repeater-item-' . $column . '-type-' . $section[ $column . '_type' ],
				],
				'id' => 'raven-sticky-' . $column . '-scroller-section-' . $css_id,
				'data-section-index' => $index,
				'data-section-id' => esc_attr( $section['_id'] ),
			] );
			?>
			<div <?php echo $this->get_render_attribute_string( $column . '-section-wrapper' . $section['_id'] ); ?>>
				<?php
				if ( 'media' === $column ) {
					echo $this->render_section_media( $section );
				}

				if ( 'content' === $column ) {
					echo $this->render_section_content( $section );
				}
				?>
			</div>
			<?php
		}

		return ob_get_clean();
	}

	private function render_section_media( $section ) {
		if ( 'image' === $section['media_type'] ) {
			return $this->render_image( $section );
		}

		return $this->render_video( $section );
	}

	private function render_section_content( $section ) {
		if ( 'editor' === $section['content_type'] ) {
			return $this->render_editor_content( $section );
		}

		return $this->render_template_content( $section );
	}

	private function render_image( $section ) {
		if ( empty( $section['image']['id'] ) ) {
			$src = [
				'desktop' => $section['image']['url'],
				'tablet' => empty( $section['image_tablet']['url'] ) ? $section['image']['url'] : $section['image_tablet']['url'],
				'mobile' => empty( $section['image_mobile']['url'] ) ? $section['image']['url'] : $section['image_mobile']['url'],
			];
			$alt = esc_html__( 'Placeholder image', 'jupiterx-core' );
		} else {
			$desktop_image = Group_Control_Image_Size::get_attachment_image_src( $section['image']['id'], 'image_size', $section );
			$alt           = $section['image']['alt'];

			$src = [
				'desktop' => $desktop_image,
				'tablet' => empty( $section['image_tablet']['id'] ) ?
						$desktop_image :
						$section['image_tablet']['url'],
				'mobile' => empty( $section['image_mobile']['id'] ) ?
						$desktop_image :
						$section['image_mobile']['url'],
			];
		}

		return wp_kses_post( sprintf(
			'<div class="media-type-image">
						<picture class="raven-sticky-media-scroller-content">
							<source media="(max-width:767px)" srcset="%1$s">
							<source media="(max-width:1024px)" srcset="%2$s">
							<img src="%3$s" alt="%4$s">
						</picture>
					</div>',
			$src['mobile'],
			$src['tablet'],
			$src['desktop'],
			$alt
		) );
	}

	private function render_video( $section ) {
		$video_link  = $section['video_url']['url'];
		$video_type  = $this->get_video_type( $video_link );
		$poster      = $section['fallback_poster']['url'];
		$is_autoplay = (bool) $section['autoplay'];
		$video_html  = '';

		if ( 'hosted' === $video_type ) {
			$video_html = '<div class="video-wrapper raven-sticky-media-scroller-content self-hosted">' . $this->get_hosted_video( $section ) . '</div>';
		}

		if ( 'youtube' === $video_type ) {
			$embed_params = [
				'autoplay' => $is_autoplay ? 1 : 0,
				'mute' => $is_autoplay ? 1 : 0,
				'start' => $section['start'],
				'end' => $section['end'],
				'enablejsapi' => 1,
				'loop' => 1,
			];

			if ( $section['mute'] ) {
				$embed_params['mute'] = 1;
			}

			$embed_options = [
				'privacy' => 1 === $section['youtube_privacy'],
				'lazy_load' => false,
			];

			$embed_attrs = [
				'allow' => 'autoplay',
				'style' => "background-image: url($poster)",
			];

			$video_html = '<div class="video-wrapper raven-sticky-media-scroller-content youtube">' .
							Embed::get_embed_html( $video_link, $embed_params, $embed_options, $embed_attrs ) .
							'</div>';
		}

		if ( 'vimeo' === $video_type ) {
			$embed_params = [
				'autoplay' => $is_autoplay ? 1 : 0,
				'muted' => $is_autoplay ? 1 : 0,
				'autopause' => 0,
				'loop' => 1,
				'#t' => $section['start'] || 0,
			];

			if ( $section['mute'] ) {
				$embed_params['mute'] = 1;
			}

			$embed_options = [
				'lazy_load' => false,
			];

			$embed_attrs = [
				'allowfullscreen' => '',
				'allow' => 'autoplay',
				'style' => "background-image: url($poster)",
			];

			$video_html = '<div class="video-wrapper raven-sticky-media-scroller-content vimeo">' .
							Embed::get_embed_html( $video_link, $embed_params, $embed_options, $embed_attrs ) .
							'</div>';
		}

		return wp_kses_post( '<div class="media-type-video">' . $video_html . '</div>' );
	}

	private function get_video_type( $video_link ) {
		$youtube_pattern = '/^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.be)\/.+$/';
		$vimeo_pattern   = '/(http|https)?:\/\/(www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|)(\d+)(?:|\/\?)/';

		preg_match( $youtube_pattern, $video_link, $output );
		if ( ! empty( $output[0] ) ) {
			return 'youtube';
		}

		preg_match( $vimeo_pattern, $video_link, $output );
		if ( ! empty( $output[0] ) ) {
			return 'vimeo';
		}

		return 'hosted';
	}

	/**
	 * It will return the HTML of the hosted videos (not YouTube and Vimeo).
	 *
	 * @param array $section the repeater item of the section.
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @return string
	 * @since 3.0.0
	 */
	private function get_hosted_video( $section ) {
		$link   = $section['video_url']['url'];
		$poster = $section['fallback_poster']['url'];

		if ( isset( $section['start'] ) || isset( $section['end'] ) ) {
			$link .= sprintf(
				'#t=%1$s%2$s',
				$section['start'],
				0 === $section['end'] ? '' : ',' . $section['end']
			);
		}

		if ( $section['loop'] ) {
			$this->add_render_attribute( 'hosted-video' . $section['_id'], [
				'loop' => [],
			] );
		}

		if ( $section['player_controls'] ) {
			$this->add_render_attribute( 'hosted-video' . $section['_id'], [
				'controls' => [],
			] );
		}

		if ( $section['autoplay'] ) {
			$this->add_render_attribute( 'hosted-video' . $section['_id'], [
				'autoplay' => [],
				'muted' => [],
			] );
		}

		if ( $section['mute'] ) {
			$this->add_render_attribute( 'hosted-video' . $section['_id'], [
				'muted' => [],
			] );
		}

		if ( ! empty( $poster ) ) {
			$this->add_render_attribute( 'hosted-video' . $section['_id'], [
				'poster' => $poster,
			] );
		}

		$video_settings = [
			'start_time' => $section['start'] ?: 0,
			'autoplay' => (bool) $section['autoplay'], // If autoplay is active, attribute will have the value 1.
			'muted' => (bool) $section['mute'], // If mute is active, attribute will have the value 1.
		];

		return sprintf(
			'<video %1$s data-start-time="%2$s" data-autoplay="%3$s" data-muted="%4$s"><source src="%5$s">%6$s</video>',
			$this->get_render_attribute_string( 'hosted-video' . $section['_id'] ),
			esc_attr( $video_settings['start_time'] ),
			esc_attr( $video_settings['autoplay'] ),
			esc_attr( $video_settings['muted'] ),
			esc_url( $link ),
			esc_html__( 'Your browser does not support the video tag.', 'jupiterx-core' )
		);
	}

	private function render_editor_content( $section ) {
		$settings = $this->get_settings_for_display();

		$title   = sprintf( '<h2 class="title">%s</h2>', $section['content_heading'] );
		$content = sprintf( '<div class="content">%s</div>', $section['content'] );

		if ( ! empty( $section['content_button_link']['url'] ) ) {
			$this->add_link_attributes( 'button_' . $section['_id'], $section['content_button_link'] );
		}

		$this->add_render_attribute(
			'button_' . $section['_id'],
			'class',
			[
				'raven-sticky-media-scroller-button',
				'raven-button-size-' . $settings['button_size'],
			]
		);

		$button = '';

		if ( ! empty( $section['content_button_text'] ) ) {
			$button = sprintf(
				'<a %1$s>%2$s</a>',
				$this->get_render_attribute_string( 'button_' . $section['_id'] ),
				$section['content_button_text']
			);
		}

		return wp_kses_post( '<div class="content-type-editor"><div class="content-wrapper">' . $title . $content . $button . '</div></div>' );
	}

	/**
	 * Render template content from frontend of elementor.
	 *
	 * @param $id
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function render_template_content( $section ) {
		$frontend = Plugin::instance()->frontend;
		$template = $frontend->get_builder_content_for_display( (int) $section['custom_template'], true );

		return '<div class="content-type-template template-' . $section['custom_template'] . '">' . $template . '</div>';
	}

	private function render_responsive_content_and_media() {
		$settings = $this->get_settings_for_display();

		ob_start();
			foreach ( $settings['sections'] as $section ) {
				$css_id = ! empty( $section['css_id'] ) ? esc_attr( $section['css_id'] ) : esc_attr( 'item-' . $section['_id'] );

				$this->add_render_attribute( 'section-wrapper-responsive' . $section['_id'], [
					'class' => [
						'section',
						'elementor-repeater-item-' . $section['_id'],
						'elementor-repeater-item-media-type-' . $section['media_type'],
						'elementor-repeater-item-content-type-' . $section['content_type'],
					],
					'id' => 'raven-sticky-media-scroller-section-' . $css_id,
					'data-section-id' => $css_id,
				] );
				?>
				<div class="responsive-mode-item">
					<div <?php echo $this->get_render_attribute_string( 'section-wrapper-responsive' . $section['_id'] ); ?>>
						<?php echo $this->render_section_media( $section ); ?>
					</div>
					<div <?php echo $this->get_render_attribute_string( 'section-wrapper-responsive' . $section['_id'] ); ?>>
						<?php echo $this->render_section_content( $section ); ?>
					</div>
				</div>
				<?php
			}
			?>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get all custom attributes and return the array for wp_kses_post.
	 *
	 * @return array
	 */
	private function get_all_custom_attributes() {
		$settings          = $this->get_settings_for_display();
		$custom_attributes = [];
		$separated         = [];

		foreach ( $settings['sections'] as $section ) {
			if ( ! empty( $section['content_button_link']['custom_attributes'] ) ) {
				$custom_attributes[] = $section['content_button_link']['custom_attributes'];
			}
		}

		if ( empty( $custom_attributes ) ) {
			return $separated;
		}

		foreach ( $custom_attributes as $attribute ) {
			$attribute = explode( ',', $attribute );

			foreach ( $attribute as $attr ) {
				$separated[ explode( '|', $attr )[0] ] = true;
			}
		}

		return $separated;
	}
}
