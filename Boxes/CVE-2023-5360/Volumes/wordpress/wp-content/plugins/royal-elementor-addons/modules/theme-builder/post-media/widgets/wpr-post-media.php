<?php
namespace WprAddons\Modules\ThemeBuilder\PostMedia\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Post_Media extends Widget_Base {
	
	public function get_name() {
		return 'wpr-post-media';
	}

	public function get_title() {
		return esc_html__( 'Post Thumbnail', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-featured-image';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('single') ? [ 'wpr-theme-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'image', 'media', 'post', 'thumbnail', 'video', 'gallery' ];
	}

	public function get_script_depends() {
		return [ 'jquery-slick', 'wpr-lightgallery' ];
	}

	public function get_style_depends() {
		return [ 'wpr-lightgallery-css' ];
	}

	protected function register_controls() {

		// Get Available Meta Keys
		$post_meta_keys = Utilities::get_custom_meta_keys();

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_featured_media',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'featured_media_image_crop',
				'default' => 'full',
			]
		);

		$this->add_responsive_control(
            'featured_media_align',
            [
                'label' => esc_html__( 'Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'label_block' => false,
                'options' => [
					'left'    => [
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
					'{{WRAPPER}} .wpr-featured-media-wrap' => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->add_control(
			'featured_media_caption',
			[
				'label' => esc_html__( 'Featured Image Caption', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'featured_media_caption_hover',
			[
				'label' => esc_html__( 'Show Caption on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'hover',
				'prefix_class' => 'wpr-fm-image-caption-',
				'condition' => [
					'featured_media_caption' => 'yes',
				],
			]
		);

		// $this->add_control(
		// 	'featured_media_lightbox',
		// 	[
		// 		'label' => esc_html__( 'Enable Lightbox Popup', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'prefix_class' => 'wpr-gallery-lightbox-',
		// 		'separator' => 'before'
		// 	]
		// );

		// $this->add_control(
		// 	'featured_media_pfa_select',
		// 	[
		// 		'label' => esc_html__( 'Post Format Audio', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'default' => 'default',
		// 		'options' => [
		// 			'default' => esc_html__( 'Featured Image', 'wpr-addons' ),
		// 			'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
		// 		],
		// 		'separator' => 'before'
		// 	]
		// );

		// $this->add_control(
		// 	'featured_media_audio',
		// 	[
		// 		'label' => esc_html__( 'Audio Meta Value', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT2,
		// 		'default' => 'default',
		// 		'options' => $post_meta_keys[1],
		// 		'condition' => [
		// 			'featured_media_pfa_select' => 'meta',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'featured_media_pfv_select',
		// 	[
		// 		'label' => esc_html__( 'Post Format Video', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'default' => 'default',
		// 		'options' => [
		// 			'default' => esc_html__( 'Featured Image', 'wpr-addons' ),
		// 			'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'featured_media_video',
		// 	[
		// 		'label' => esc_html__( 'Video Meta Value', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT2,
		// 		'default' => 'default',
		// 		'options' => $post_meta_keys[1],
		// 		'condition' => [
		// 			'featured_media_pfv_select' => 'meta',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'featured_media_pfg_select',
		// 	[
		// 		'label' => esc_html__( 'Post Format Gallery', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'default' => 'default',
		// 		'options' => [
		// 			'default' => esc_html__( 'Featured Image', 'wpr-addons' ),
		// 			'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'featured_media_gallery',
		// 	[
		// 		'label' => esc_html__( 'Gallery Meta Value', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT2,
		// 		'default' => 'default',
		// 		'options' => $post_meta_keys[1],
		// 		'condition' => [
		// 			'featured_media_pfg_select' => 'meta',
		// 		],
		// 	]
		// );

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Tab: PF Audio =============
		// Section: General ----------
		$this->start_controls_section(
			'section_featured_media_audio',
			[
				'label' => esc_html__( 'Post Format Audio', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'featured_media_pfa_select' => 'meta',
				]
			]
		);

		$this->add_control(
			'audio_visual_player',
			[
				'label' => esc_html__( 'SoundCloud Visual Player', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'audio_auto_play',
			[
				'label' => esc_html__( 'SoundCloud Auto Play', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'audio_interface_color',
			[
				'label' => esc_html__( 'SoundCloud Interface Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: PF Video =============
		// Section: General ----------
		$this->start_controls_section(
			'section_featured_media_video',
			[
				'label' => esc_html__( 'Post Format Video', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'featured_media_pfv_select' => 'meta',
				]
			]
		);

		$this->add_control(
			'video_aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
				],
				'default' => '169',
				'prefix_class' => 'elementor-aspect-ratio-',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: PF Gallery ===========
		// Section: General ----------
		$this->start_controls_section(
			'section_featured_media_gallery',
			[
				'label' => esc_html__( 'Post Format Gallery', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'featured_media_pfg_select' => 'meta',
				]
			]
		);

		$this->add_control(
			'gallery_display_as',
			[
				'label' => esc_html__( 'Display As', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'slider' => esc_html__( 'Slideshow Gallery', 'wpr-addons' ),
					'stacked' => esc_html__( 'Stacked Gallery', 'wpr-addons' ),
				],
				'default' => 'slider',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'desktop_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'display:{{VALUE}} !important;',
				],
				'separator' => 'before',
				'condition' => [
					'gallery_display_as' => 'slider'
				]
			]
		);

		$this->add_control(
			'gallery_slider_nav_hover',
			[
				'label' => esc_html__( 'Show on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'fade',
				'prefix_class' => 'wpr-gallery-slider-nav-',
				'condition' => [
					'gallery_slider_nav' => 'yes',
					'gallery_display_as' => 'slider'
				]
			]
		);

		$this->add_responsive_control(
			'gallery_slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'desktop_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-table'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dots' => 'display:{{VALUE}};',
				],
				'condition' => [
					'gallery_display_as' => 'slider'
				]
			]
		);

		$this->add_control(
			'gallery_slider_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'gallery_display_as' => 'slider'
				]
			]
		);

		$this->add_control(
			'gallery_slider_autoplay_duration',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 0,
				'max' => 15,
				'step' => 0.5,
				'frontend_available' => true,
				'condition' => [
					'gallery_slider_autoplay' => 'yes',
					'gallery_display_as' => 'slider'
				],
			]
		);

		$this->add_control(
			'gallery_slider_pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'gallery_slider_autoplay' => 'yes',
					'gallery_display_as' => 'slider'
				],
			]
		);

		$this->add_control(
			'gallery_slider_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'after',
				'condition' => [
					'gallery_display_as' => 'slider'
				]
			]
		);
		
		$this->add_control(
			'gallery_slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'wpr-addons' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
				],
				'condition' => [
					'gallery_display_as' => 'slider'
				]
			]
		);

		$this->add_control(
			'gallery_slider_effect_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'gallery_display_as' => 'slider'
				]
			]
		);

		$this->add_control(
			'gallery_caption',
			[
				'label' => esc_html__( 'Gallery Image Caption', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'gallery_caption_hover',
			[
				'label' => esc_html__( 'Show Caption on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'hover',
				'prefix_class' => 'wpr-fm-gallery-caption-',
				'condition' => [
					'gallery_caption' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Lightbox Popup ---
		$this->start_controls_section(
			'section_lightbox_popup',
			[
				'label' => esc_html__( 'Lightbox Popup', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'featured_media_lightbox' => 'yes'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_autoplay',
			[
				'label' => esc_html__( 'Autoplay Slides', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_progressbar',
			[
				'label' => esc_html__( 'Show Progress Bar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_autoplay' => 'true'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_pause',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'lightbox_popup_autoplay' => 'true',
				],
			]
		);

		$this->add_control(
			'lightbox_popup_counter',
			[
				'label' => esc_html__( 'Show Counter', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_captions',
			[
				'label' => esc_html__( 'Show Captions', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_thumbnails',
			[
				'label' => esc_html__( 'Show Thumbnails', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_thumbnails_default',
			[
				'label' => esc_html__( 'Show Thumbs by Default', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_thumbnails' => 'true'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_sharing',
			[
				'label' => esc_html__( 'Show Sharing Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_zoom',
			[
				'label' => esc_html__( 'Show Zoom Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_fullscreen',
			[
				'label' => esc_html__( 'Show Full Screen Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_download',
			[
				'label' => esc_html__( 'Show Download Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Media ------------
		$this->start_controls_section(
			'section_style_media',
			[
				'label' => esc_html__( 'Media', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'media_width',
			[
				'label'   => esc_html__( 'Width', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto'=> esc_html__( 'Auto', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
				'selectors_dictionary' => [
					'auto' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-image' => 'width: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'media_custom_width',
			[
				'label' => esc_html__( 'Custom Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-image' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-featured-media-image img' => 'width: 100%;',
				],
				'condition' => [
					'media_width' => 'custom'
				]
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Image Caption ----
		$this->start_controls_section(
			'section_style_image_caption',
			[
				'label' => esc_html__( 'Image Caption', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'featured_media_caption',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'gallery_caption',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'image_caption_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_caption_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_caption_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_caption_shadow',
				'selector' => '{{WRAPPER}} .wpr-featured-media-caption span',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'image_caption_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-featured-media-caption span'
			]
		);

		$this->add_control(
			'image_caption_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_caption_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_caption_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_caption_border_type',
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
					'{{WRAPPER}} .wpr-featured-media-caption span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_caption_border_width',
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
					'{{WRAPPER}} .wpr-featured-media-caption span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'image_caption_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_caption_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'image_caption_align_vr',
            [
                'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'flex-end',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption' => 'align-items: {{VALUE}}',
				],
				'separator' => 'before',
            ]
        );

		$this->add_control(
            'image_caption_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-featured-media-caption' => 'justify-content: {{VALUE}}',
				],
            ]
        );

		$this->end_controls_section();

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'wpr__section_style_gallery_slider_nav',
			[
				'label' => esc_html__( 'Slider Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'featured_media_pfg_select' => 'meta',
					'gallery_display_as' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_gallery_slider_nav_style' );

		$this->start_controls_tab(
			'tab_gallery_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'gallery_slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_gallery_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'gallery_slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'gallery_slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'gallery_slider_nav_border_type',
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
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 0,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'gallery_slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'gallery_slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_position_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_position_left',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Left Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_position_right',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Right Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'wpr__section_style_gallery_slider_dots',
			[
				'label' => esc_html__( 'Slider Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'featured_media_pfg_select' => 'meta',
					'gallery_display_as' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_gallery_slider_dots' );

		$this->start_controls_tab(
			'tab_gallery_slider_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'gallery_slider_dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.35)',
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'gallery_slider_dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_gallery_slider_dots_active',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'gallery_slider_dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dots .slick-active .wpr-gallery-slider-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'gallery_slider_dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dots .slick-active .wpr-gallery-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'gallery_slider_dots_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'gallery_slider_dots_border_type',
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
					'{{WRAPPER}} .wpr-gallery-slider-dot' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'gallery_slider_dots_border_width',
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
					'{{WRAPPER}} .wpr-gallery-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'gallery_slider_dots_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'gallery_slider_dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit' => '%',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_dots_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_dots_hr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dots' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'gallery_slider_dots_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function get_lightbox_settings( $settings ) {
		$lightbox_settings = [
			'selector' => '.slick-slide:not(.slick-cloned) .wpr-featured-media-image, .wpr-featured-media-wrap > .wpr-featured-media-image',
			'iframeMaxWidth' => '60%',
			'hash' => false,
			'autoplay' => $settings['lightbox_popup_autoplay'],
			'pause' => $settings['lightbox_popup_pause'] * 1000,
			'progressBar' => $settings['lightbox_popup_progressbar'],
			'counter' => $settings['lightbox_popup_counter'],
			'controls' => $settings['lightbox_popup_arrows'],
			'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
			'thumbnail' => $settings['lightbox_popup_thumbnails'],
			'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
			'share' => $settings['lightbox_popup_sharing'],
			'zoom' => $settings['lightbox_popup_zoom'],
			'fullScreen' => $settings['lightbox_popup_fullscreen'],
			'download' => $settings['lightbox_popup_download'],
		];

		return json_encode( $lightbox_settings );	
	}

	// Render Post Thumbnail
	public function render_post_thumbnail( $settings, $id ) {
		$lightbox = '';
		$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'featured_media_image_crop', $settings );
		$caption = wp_get_attachment_caption( $id );

		// Lightbox
		// if ( 'yes' === $settings['featured_media_lightbox'] ) {
		// 	$lightbox = ' data-lightbox="'. esc_attr( $this->get_lightbox_settings( $settings ) ) .'"';
		// }

		// if ( $id === get_post_thumbnail_id() && false === get_post_format() ) {
		// 	$show_caption = $settings['featured_media_caption'];
		// } else {
		// 	$show_caption = $settings['gallery_caption'];
		// }

		$show_caption = $settings['featured_media_caption'];
		
		// Render Image
		if ( has_post_thumbnail() ) {
			$alt = get_post_meta($id, '_wp_attachment_image_alt', true);

			echo '<div class="wpr-featured-media-image" data-src="'. esc_url( $src ) .'">';
				if ( 'yes' === $show_caption && '' !== $caption ) {
					echo '<div class="wpr-featured-media-caption">';
						echo '<span>'. esc_html( $caption ) .'</span>';
					echo '</div>';
				}

				echo '<img src="'. esc_url( $src ) .'" alt="'. esc_attr( $alt ) .'">';
			echo '</div>';
		}
	}

	public function render_post_audio_video( $settings, $post_format ) {
		$utilities = new Utilities();
		$meta_value = get_post_meta( get_the_ID(), $settings[ 'featured_media_'. $post_format ], true );

		// Checks
		if ( '' === $meta_value ) {
			return;
		}

		// URL
		if ( false === strpos( $meta_value, '<iframe ' ) ) {
			add_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50, 3 );
				$track_url = wp_oembed_get( $meta_value );
			remove_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50 );

		// Iframe
		} else {
			$track_url = Utilities::filter_oembed_results( $meta_value );
		}

		// Video
		if ( 'video' === $post_format ) {
			$track_url = str_replace( '&auto_play=true', '', $track_url );
			echo '<div class="elementor-fit-aspect-ratio">';
				echo '<iframe src="'. esc_url( $track_url ) .'"></iframe>';
			echo '</div>';

		// Audio
		} else {
			$track_url = ( '' === $settings['audio_auto_play'] ) ? str_replace( 'auto_play=true', '', $track_url ) : $track_url;
			$audio_height = 'yes' === $settings['audio_visual_player'] ? '400' : '200';

			// SoundCloud Color
			if ( strpos( $track_url, 'ff5500' ) ) {
				$track_url = str_replace( 'ff5500', str_replace( '#', '', $settings['audio_interface_color'] ), $track_url );
			} else {
				$track_url .= str_replace( '#', 'color=', $settings['audio_interface_color'] );
			}

			echo '<iframe height="'. esc_attr($audio_height) .'" src="'. esc_url( $track_url ) .'" allow="autoplay"></iframe>';
		}
	}

	public function render_post_gallery( $settings ) {
		$meta_value = get_post_meta( get_the_ID(), $settings[ 'featured_media_gallery' ], true );
		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		// Checks
		if ( empty($meta_value) || ! is_array( $meta_value ) ) {
			return;
		}

		// Settings
		$slider_options = [
			'rtl' => $slider_is_rtl,
			'infinite' => ( $settings['gallery_slider_loop'] === 'yes' ),
			'speed' => absint( $settings['gallery_slider_effect_duration'] * 1000 ),
			'arrows' => true,
			'dots' => true,
			'autoplay' => ( $settings['gallery_slider_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['gallery_slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['gallery_slider_pause_on_hover'],
			'prevArrow' => '<div class="wpr-gallery-slider-prev-arrow wpr-gallery-slider-arrow"><i class="eicon-arrow-left"></i></div>',
			'nextArrow' => '<div class="wpr-gallery-slider-next-arrow wpr-gallery-slider-arrow"><i class="eicon-arrow-right"></i></div>',
		];

		if ( $settings['gallery_slider_effect'] === 'fade' ) {
			$slider_options['fade'] = true;
		}

		if ( 'stacked' === $settings['gallery_display_as'] ) {
			$slider_options = [];
		}

		// Slider Attributes
		$this->add_render_attribute( 'slider-settings', [
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
		] );

		// Slider HTML
		echo '<div class="wpr-gallery-slider" '. $this->get_render_attribute_string( 'slider-settings' ) .'>';
			foreach ( $meta_value as $key => $id ) {
				echo '<div class="wpr-gallery-slide">';
					$this->render_post_thumbnail( $settings, $id );
				echo '</div>';
			}
		echo '</div>';

		echo '<div class="wpr-gallery-slider-dots"></div>';
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$thumb_id = get_post_thumbnail_id();
		$post_format = 'standard';

		echo '<div class="wpr-featured-media-wrap" data-caption="'. esc_attr( $post_format ) .'">';

			// Disable Post Formats for some time
			// switch ( $post_format ) {
			// 	case 'audio':
			// 		if ( 'meta' === $settings['featured_media_pfa_select'] ) {
			// 			$this->render_post_audio_video( $settings, 'audio' );
			// 		} else {
			// 			$this->render_post_thumbnail( $settings, $thumb_id );
			// 		}
			// 		break;

			// 	case 'video':
			// 		if ( 'meta' === $settings['featured_media_pfv_select'] ) {
			// 			$this->render_post_audio_video( $settings, 'video' );
			// 		} else {
			// 			$this->render_post_thumbnail( $settings, $thumb_id );
			// 		}
			// 		break;

			// 	case 'gallery':
			// 		if ( 'meta' === $settings['featured_media_pfg_select'] ) {
			// 			$this->render_post_gallery( $settings );
			// 		} else {
			// 			$this->render_post_thumbnail( $settings, $thumb_id );
			// 		}
			// 		break;
				
			// 	default:
			// 		$this->render_post_thumbnail( $settings, $thumb_id );
			// 		break;
			// }

			$this->render_post_thumbnail( $settings, $thumb_id );

		echo '</div>';

	}
	
}