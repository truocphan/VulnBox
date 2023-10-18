<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMedia\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Media extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-media';
	}

	public function get_title() {
		return esc_html__( 'Product Media', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-images';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product media', 'product', 'image', 'media' ];
	}

	public function get_script_depends() {
		return [ 'flexslider', 'zoom', 'wc-single-product', 'photoswipe', 'photoswipe-ui-default', 'wpr-lightgallery'];
	}

	public function get_style_depends() {
		return [ 'woocommerce_prettyPhoto_css', 'photoswipe', 'photoswipe-default-skin', 'wpr-lightgallery-css'];
	}

	public function add_control_gallery_slider_thumbs() {
		$this->add_control(
			'gallery_slider_thumbs_type',
			[
				'label' => esc_html__( 'Display Thumbs As', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'stacked' => esc_html__( 'Stacked', 'wpr-addons' ),
					'pro-sl' => esc_html__( 'Slider (Pro)', 'wpr-addons' )
				],
				'default' => 'stacked',
				'render_type' => 'template',
				'prefix_class' => 'wpr-product-media-thumbs-',
				'selectors' => [
					'{{WRAPPER}}.wpr-product-media-thumbs-none .wpr-product-media-wrap .flex-control-nav' => 'display: none;',
					'{{WRAPPER}}.wpr-product-media-thumbs-stacked .wpr-product-media-wrap .flex-control-nav' => 'display: grid;',
					'{{WRAPPER}}.wpr-product-media-thumbs-slider .wpr-product-media-wrap .flex-control-nav' => 'display: flex;',
                ],
                'condition' => [
                    'gallery_slider_thumbs' => 'yes'
                ]
			]
		);

		// $this->add_control(
		// 	'gallery_slider_thumbs_type',
		// 	[
		// 		'type' => \Elementor\Controls_Manager::HIDDEN,
		// 		'default' => 'stacked',
		// 		'prefix_class' => 'wpr-product-media-thumbs-',
		// 		'selectors' => [
		// 			'{{WRAPPER}}.wpr-product-media-thumbs-stacked .wpr-product-media-wrap .flex-control-nav' => 'display: grid;',
		// 		],
        //         'condition' => [
        //             'gallery_slider_thumbs' => 'yes'
        //         ]
		// 	]
		// );
	}

	public function add_controls_group_gallery_slider_thumbs() {}

	public function add_control_gallery_slider_thumbs_to_slide() {}
	
	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'product_media_sales_badge',
			[
				'label' => esc_html__( 'Show Sale Badge', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'product_media_sales_badge_text',
			[
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Sale Badge Text', 'wpr-addons' ),
				'default' => 'Sale!',
				'separator' => 'after',
				'condition' => [
					'product_media_sales_badge' => 'yes'
				]
			]
		);

		$this->add_control(
			'product_media_lightbox',
			[
				'label' => esc_html__( 'Enable Lightbox', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'wpr-gallery-lightbox-',
				'selectors' => [
					'{{WRAPPER}}.wpr-gallery-lightbox-yes .wpr-product-media-wrap .woocommerce-product-gallery__trigger' => 'display: block !important;'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Image Gallery ========
		// Section: General ----------
		$this->start_controls_section(
			'section_product_media_gallery',
			[
				'label' => esc_html__( 'Image Gallery', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'gallery_slider_nav_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Main Image', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav',
			[
				'label' => esc_html__( 'Show Navigation Arrows', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'display:{{VALUE}} !important;',
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
					'gallery_slider_nav' => 'yes'
				]
			]
		);

		$this->add_control(
			'gallery_slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => 'wpr-arrow-icons',
				'default' => 'svg-angle-1-left',
				'condition' => [
					'gallery_slider_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'gallery_slider_thumb_nav_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Gallery Thumbnails', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_thumbs',
			[
				'label' => esc_html__( 'Show Thumbnail Images', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'grid'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-wrap .flex-control-nav' => 'display: {{VALUE}};',
				]
			]
		);

		$this->add_control_gallery_slider_thumbs();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'query_source', ['pro-rl'] );

		$this->add_controls_group_gallery_slider_thumbs();

		$this->add_control(
			'gallery_slider_thumb_cols',
			[
				'label' => esc_html__( 'Thumbnails Per Row', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'default' => 4,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}.wpr-product-media-thumbs-stacked .wpr-product-media-wrap .flex-control-thumbs' => 'grid-template-columns: repeat({{VALUE}}, auto);',
					'{{WRAPPER}}.wpr-product-media-thumbs-slider .wpr-product-media-thumbs-horizontal.wpr-product-media-wrap .flex-control-thumbs li' => 'width: calc(100%/{{VALUE}}) !important;',
					'{{WRAPPER}}.wpr-product-media-thumbs-slider.wpr-product-media-thumbs-vertical .wpr-product-media-wrap .flex-control-thumbs li' => 'height: calc(100%/{{VALUE}}) !important;'
				],
				'condition' => [
					'gallery_slider_thumbs' => 'yes',
					'gallery_slider_thumbs_type' => ['slider', 'stacked'],
				],

			]
		);

		$this->add_control_gallery_slider_thumbs_to_slide();

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Lightbox Popup ---
		$this->start_controls_section(
			'section_lightbox_popup',
			[
				'label' => esc_html__( 'Lightbox Popup', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'product_media_lightbox' => 'yes'
				]
			]
		);

		$this->add_control(
			'lightbox_extra_icon',
			[
				'label' => esc_html__( 'Lightbox Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'choose_lightbox_extra_icon',
			[
				'label' => __( 'Icon', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'solid',
				],
				'condition' => [
					'lightbox_extra_icon' => 'yes'
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

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'product-media', [
			'Convert Thumbnails to Carousel',
		] );


		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_gallery_arrows_nav',
			[
				'label' => esc_html__( 'Main Image Arrows', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				// 'condition' => [
				// 	'gallery_display_as' => 'slider',
				// ],
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
				'default' => '#FFFFFFCC',
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-gallery-slider-arrow svg' => 'fill: {{VALUE}};',
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
				'default' => '#FFFFFFCC',
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
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-gallery-slider-arrow:hover svg' => 'fill: {{VALUE}};'
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
				'default' => 0.5,
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
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-gallery-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 31,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .flex-direction-nav li' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .flex-direction-nav li a.flex-prev' => 'display: block; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .flex-direction-nav li a.flex-next' => 'display: block; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .flex-direction-nav li a.flex-prev:before' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .flex-direction-nav li a.flex-next:after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'gallery_slider_nav_position_horizontal',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 4,
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .wpr-gallery-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .flex-direction-nav li.flex-nav-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-gallery-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .flex-direction-nav li.flex-nav-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
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
					'bottom' => 2,
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

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'section_style_gallery_thumb_nav',
			[
				'label' => esc_html__( 'Gallery Thumbnails', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				// 'condition' => [
				// 	'gallery_display_as' => 'slider',
				// ],
			]
		);

		$this->add_responsive_control(
			'gallery_thumb_nav_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-wrap .flex-control-nav' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .wpr-fcn-wrap' => 'max-width: {{SIZE}}{{UNIT}};'
				],
				// 'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'gallery_thumb_nav_gutter_hr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-product-media-thumbs-stacked .wpr-product-media-wrap .flex-control-nav' => 'grid-column-gap: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.wpr-product-media-thumbs-slider .wpr-product-media-wrap .flex-control-nav li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
				// 'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'gallery_thumb_nav_gutter_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-product-media-thumbs-stacked .wpr-product-media-wrap .flex-control-nav' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-product-media-thumbs-slider.wpr-product-media-thumbs-vertical .wpr-product-media-wrap .flex-control-nav li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'gallery_slider_thumbs_type' => 'stacked'
				]
			]
		);

		$this->add_responsive_control(
			'product_media_vertical_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}:not(.wpr-product-media-thumbs-vertical) .wpr-product-media-wrap .flex-viewport' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		// $this->add_responsive_control(
		// 	'product_media_horizontal_distance',
		// 	[
		// 		'type' => Controls_Manager::SLIDER,
		// 		'label' => esc_html__( 'Horizontal Distance', 'wpr-addons' ),
		// 		'size_units' => [ 'px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 50,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 12,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}}.wpr-product-media-thumbs-vertical .wpr-product-media-wrap .wpr-fcn-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
		// 		]
		// 	]
		// );

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_style_thumbnail_arrows_nav',
			[
				'label' => esc_html__( 'Gallery Thumbnails Arrows', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'gallery_slider_thumbs_type' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_thumbnail_slider_nav_style' );

		$this->start_controls_tab(
			'tab_thumbnail_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFFCC',
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumbnail_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'thumbnail_slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'thumbnail_slider_nav_font_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'thumbnail_slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};', // remove line-height if not needed
					// '{{WRAPPER}} .wpr-thumbnail-slider-arrows-wrap' => 'height: {{SIZE}}{{UNIT}};', // remove line-height if not needed
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_slider_nav_position_horizontal',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-thumbnail-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_border_type',
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
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);	$this->add_control(
			'thumbnail_slider_nav_border_width',
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
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'thumbnail_slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'thumbnail_slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_product_sales_badge_styles',
			[
				'label' => esc_html__( 'Sales Badge', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sales_badge_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge span' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'sales_badge_background',
			[
				'label'     => esc_html__( 'Background color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sales_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sales_badge_typography',
				'selector' => '{{WRAPPER}} .wpr-product-sales-badge span',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'sales_badge_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-product-sales-badge span',
			]
		);

		$this->add_responsive_control(
			'sales_badge_padding',
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
					'{{WRAPPER}} .wpr-product-sales-badge span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sales_badge_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'sales_badge_border_type',
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
					'{{WRAPPER}} .wpr-product-sales-badge span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sales_badge_border_width',
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
					'{{WRAPPER}} .wpr-product-sales-badge span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'sales_badge_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'sales_badge_border_radius',
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
					'{{WRAPPER}}  .wpr-product-sales-badge span'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Lightbox Icon -------
		$this->start_controls_section(
			'section_style_lightbox_icon',
			[
				'label' => esc_html__( 'Lightbox Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'product_media_lightbox' => 'yes'
				],
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-lightbox i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-media-lightbox svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'product_lightbox_shadow',
				'selector' => '{{WRAPPER}} .wpr-product-media-lightbox',
			]
		);

		$this->add_control(
			'lightbox_tr_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-lightbox i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'lightbox_border_type',
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
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lightbox_border_width',
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
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'lightbox_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-lightbox svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'lightbox_icon_box_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .woocommerce-product-gallery__trigger' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-media-wrap .woocommerce-product-gallery__trigger' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-lightbox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}
	/** 
	 * Filer WooCommerce Flexslider options - Add Navigation Arrows
	 */
	public function wpr_update_woo_flexslider_options( $options ) {
	
		$options['directionNav'] = true;
	
		return $options;
	}

	public function get_lightbox_settings( $settings ) {
		$lightbox_settings = [
			'selector' => '.woocommerce-product-gallery__image',
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

	public function wpr_remove_woo_default_lightbox() {	 	 
	   remove_theme_support( 'wc-product-gallery-lightbox' );	 	 
	}
	
	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		
		// Get Product
		$product = wc_get_product();
		// $this->get_hoodies();

		if ( ! $product ) {
			return;
		}

		// Product ID
		$post = get_post( $product->get_id() );
		$gallery_images = $product->get_gallery_image_ids();
		
		add_action( 'wp', [$this, 'wpr_remove_woo_default_lightbox'], 99 );

		$this->add_render_attribute(
			'thumbnails_attributes',
			[
				'class' => ['wpr-product-media-wrap', 'wpr-product-media-thumbs-horizontal'],
				'data-slidestoshow' => $settings['gallery_slider_thumb_cols'],
				'data-slidestoscroll' => isset($settings['gallery_slider_thumbs_to_slide']) ? $settings['gallery_slider_thumbs_to_slide'] : '',
			]
		);

		// Lightbox
		if ( 'yes' === $settings['product_media_lightbox'] ) {
			$lightbox = ' data-lightbox="'. esc_attr( $this->get_lightbox_settings( $settings ) ) .'"';
		} else { 
			$lightbox = '';
		}

		// Output
		echo '<div '.  $this->get_render_attribute_string( 'thumbnails_attributes' ) .' '. $lightbox .'>';

		// Sales Badge
		if ( $product->is_on_sale() && 'yes' === $settings['product_media_sales_badge'] ) {
			echo '<div class="wpr-product-sales-badge">';
				echo apply_filters( 'woocommerce_sale_flash', '<span>' . wp_kses_post($settings['product_media_sales_badge_text']) . '</span>', $post, $product );
			echo '</div>';
		}

		// Lightbox Icon
		if ( 'yes' === $settings['product_media_lightbox'] && 'yes' === $settings['lightbox_extra_icon'] && '' !== $settings['choose_lightbox_extra_icon'] ) {
			
			echo '<div class="wpr-product-media-lightbox">';
				\Elementor\Icons_Manager::render_icon( $settings['choose_lightbox_extra_icon'], [ 'aria-hidden' => 'true' ] );
			echo '</div>';

		}

		// Slider Arrows
		if ( !empty($gallery_images) && 'on' === get_option('wpr_enable_woo_flexslider_navigation', 'on') ) {
			if ( 'yes' === $settings['gallery_slider_nav'] && 'none' !== $settings['gallery_slider_nav_icon']) {
				echo '<div class="wpr-gallery-slider-arrows-wrap">';
					echo '<div class="wpr-gallery-slider-prev-arrow wpr-gallery-slider-arrow">'. Utilities::get_wpr_icon( $settings['gallery_slider_nav_icon'], 'left' ) .'</div>';
					echo '<div class="wpr-gallery-slider-next-arrow wpr-gallery-slider-arrow">'. Utilities::get_wpr_icon( $settings['gallery_slider_nav_icon'], 'right' ) .'</div>';
				echo '</div>';
			}
		}
		
		// Thumbnail Slider Arrows
		if ( 'slider' === $settings['gallery_slider_thumbs_type'] && 'yes' === $settings['thumbnail_slider_nav'] && 'none' !== $settings['thumbnail_slider_nav_icon']) {
				echo '<div class="wpr-thumbnail-slider-prev-arrow wpr-tsa-hidden wpr-thumbnail-slider-arrow">'. Utilities::get_wpr_icon( $settings['thumbnail_slider_nav_icon'], 'left' ) .'</div>';
				echo '<div class="wpr-thumbnail-slider-next-arrow wpr-tsa-hidden wpr-thumbnail-slider-arrow">'. Utilities::get_wpr_icon( $settings['thumbnail_slider_nav_icon'], 'right' ) .'</div>';
		}

        wc_get_template('single-product/product-image.php');
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<script>
				jQuery( '.woocommerce-product-gallery' ).each( function () {
					jQuery( this ).wc_product_gallery();
				} );
			</script>
			<?php
		}
	}
	
}