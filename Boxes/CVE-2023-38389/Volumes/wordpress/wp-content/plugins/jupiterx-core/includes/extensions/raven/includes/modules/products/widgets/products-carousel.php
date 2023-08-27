<?php
namespace JupiterX_Core\Raven\Modules\Products\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Products\Module;
use JupiterX_Core\Raven\Controls\Query as Control_Query;
use Elementor\Plugin as Elementor;
use JupiterX_Core\Raven\Plugin as RavenPlugin;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class Products_Carousel extends Base_Widget {

	public function get_name() {
		return 'raven-products-carousel';
	}

	public function get_title() {
		return esc_html__( 'Products Carousel', 'jupiterx-core' );
	}

	public static function is_active() {
		return RavenPlugin::is_active( 'products-carousel' ) && function_exists( 'WC' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-products-carousel';
	}

	public function get_script_depends() {
		return [
			'swiper',
			'jupiterx-core-raven-object-fit',
			'elementor-waypoints',
		];
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_layout_controls();
		$this->register_settings_controls();
		$this->register_block_style_controls();
		$this->register_image_style_controls();
		$this->register_rating_style_controls();
		$this->register_category_style_controls();
		$this->register_title_style_controls();
		$this->register_price_style_controls();
		$this->register_add_to_cart_button_style_controls();
		$this->register_overlay_style_controls();
		$this->register_sale_badge_style_controls();
		$this->register_arrows_style_controls();
		$this->register_pagination_style_controls();
		$this->register_wishlist_style_controls();
	}

	protected function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		// To prevent PHP errors in Module::apply_button_location() this control is added.
		$this->add_control(
			'layout',
			[
				'type' => 'hidden',
				'default' => 'custom',
			]
		);

		// To prevent PHP errors in Module this control is added.
		$this->add_control(
			'general_layout',
			[
				'type' => 'hidden',
				'default' => 'grid',
			]
		);

		// To prevent PHP errors in Module::raven_before_shop_loop_item() this control is added.
		$this->add_control(
			'is_products_carousel',
			[
				'type' => 'hidden',
				'default' => true,
			]
		);

		$this->add_control(
			'query_filter',
			[
				'label' => esc_html__( 'Filter', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => true,
				'default' => 'all',
				'options' => Module::get_filters(),
			]
		);

		$this->add_control(
			'query_fallback_filter',
			[
				'label' => esc_html__( 'Fallback Filter', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => true,
				'default' => '',
				'options' => array_merge(
					[ '' => esc_html__( 'None', 'jupiterx-core' ) ],
					Module::get_filters()
				),
				'conditions' => [
					'terms' => [
						[
							'name' => 'query_filter',
							'operator' => '!in',
							'value' => [
								'all',
								'ids',
								'categories_tags',
								'current_archive_query',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'query_product_includes',
			[
				'label' => esc_html__( 'Search & Select Products', 'jupiterx-core' ),
				'type' => 'raven_query',
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'query_filter',
							'operator' => '==',
							'value' => 'ids',
						],
						[
							'name' => 'query_fallback_filter',
							'operator' => '==',
							'value' => 'ids',
						],
					],
				],
				'query' => [
					'source' => Control_Query::QUERY_SOURCE_POST,
					'post_type' => 'product',
				],
			]
		);

		$this->add_control(
			'query_filter_categories',
			[
				'label' => esc_html__( 'Search & Select Product Categories', 'jupiterx-core' ),
				'type' => 'raven_query',
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'query_filter',
							'operator' => '==',
							'value' => 'categories_tags',
						],
						[
							'name' => 'query_fallback_filter',
							'operator' => '==',
							'value' => 'categories_tags',
						],
					],
				],
				'query' => [
					'source'   => Control_Query::QUERY_SOURCE_TAX,
					'taxonomy' => 'product_cat',
				],
			]
		);

		$this->add_control(
			'query_filter_tags',
			[
				'label' => esc_html__( 'Search & Select Product Tags', 'jupiterx-core' ),
				'type' => 'raven_query',
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'query_filter',
							'operator' => '==',
							'value' => 'categories_tags',
						],
						[
							'name' => 'query_fallback_filter',
							'operator' => '==',
							'value' => 'categories_tags',
						],
					],
				],
				'query' => [
					'source' => Control_Query::QUERY_SOURCE_TAX,
					'taxonomy' => 'product_tag',
				],
			]
		);

		$this->add_control(
			'query_filter_by',
			[
				'label' => esc_html__( 'Filter By', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'featured' => esc_html__( 'Featured Products', 'jupiterx-core' ),
					'sale' => esc_html__( 'Products on Sale', 'jupiterx-core' ),
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'query_filter',
							'operator' => '!==',
							'value' => 'ids',
						],
						[
							'name' => 'query_filter',
							'operator' => '!==',
							'value' => 'current_archive_query',
						],
					],
				],
			]
		);

		$this->add_control(
			'query_orderby',
			[
				'label' => esc_html__( 'Sort By', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'date',
				'options' => [
					'price' => esc_html__( 'Price', 'jupiterx-core' ),
					'popularity' => esc_html__( 'Popularity', 'jupiterx-core' ),
					'_wc_average_rating' => esc_html__( 'Average Rating', 'jupiterx-core' ),
					'date' => esc_html__( 'Date', 'jupiterx-core' ),
					'title' => esc_html__( 'Title', 'jupiterx-core' ),
					'menu_order' => esc_html__( 'Menu Order', 'jupiterx-core' ),
					'rand' => esc_html__( 'Random', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'query_order',
			[
				'label' => esc_html__( 'Sort', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__( 'Low to High', 'jupiterx-core' ),
					'DESC' => esc_html__( 'High to Low', 'jupiterx-core' ),
				],
				'condition' => [
					'query_orderby!' => 'rand',
				],
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => esc_html__( 'Offset', 'jupiterx-core' ),
				'description' => esc_html__( 'Use this setting to skip over posts (e.g. \'4\' to skip over 4 posts).', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 0,
				'min' => 0,
				'max' => 100,
				'frontend_available' => true,
				'condition' => [
					'query_filter!' => 'ids',
					'query_orderby!' => 'rand',
				],
			]
		);

		$this->add_control(
			'query_excludes',
			[
				'label' => esc_html__( 'Excludes', 'jupiterx-core' ),
				'type' => 'select2',
				'multiple' => true,
				'label_block' => true,
				'default' => [ 'current_post' ],
				'options' => [
					'current_post' => esc_html__( 'Current Product', 'jupiterx-core' ),
					'manual_selection' => esc_html__( 'Manual Selection', 'jupiterx-core' ),
				],
				'condition' => [
					'query_filter!' => 'ids',
				],
			]
		);

		$this->add_control(
			'query_excludes_ids',
			[
				'label' => esc_html__( 'Search & Select Products', 'jupiterx-core' ),
				'type' => 'raven_query',
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'query_excludes' => 'manual_selection',
				],
				'query' => [
					'source' => Control_Query::QUERY_SOURCE_POST,
					'post_type' => 'product',
				],
			]
		);

		$this->add_control(
			'widget_title',
			[
				'label' => esc_html__( 'Widget Title', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter your title', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => esc_html__( 'How many posts?', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 10,
				'min' => 1,
				'max' => 50,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_view',
			[
				'label' => esc_html__( 'Posts per View', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'jupiterx-core' ),
				'type' => 'select',
				'desktop_default' => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'content_layout',
			[
				'label' => esc_html__( 'Content Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'content_under_image',
				'options' => [
					'content_overlay' => esc_html__( 'Content Overlay', 'jupiterx-core' ),
					'content_under_image' => esc_html__( 'Content Under Image', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'prefix_class' => 'raven-content-layout-',
			]
		);

		$this->add_responsive_control(
			'aspect_ratio',
			[
				'label' => esc_html__( 'Media Aspect Ratio', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0.01,
						'max' => 3,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1.2',
				],
				'tablet_default' => [
					'size' => '1.2',
				],
				'mobile_default' => [
					'size' => '1.2',
				],
				'selectors' => [
					'{{WRAPPER}} li.product .jupiterx-wc-loop-product-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);

		$this->add_responsive_control(
			'media_position',
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
					'{{WRAPPER}} li.product .jupiterx-wc-loop-product-image img' => '-o-object-position: {{VALUE}}; object-position: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'image',
				'default' => 'large',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => esc_html__( 'Block Hover', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'jupiterx-core' ),
					'grow' => esc_html__( 'Grow', 'jupiterx-core' ),
					'shrink' => esc_html__( 'Shrink', 'jupiterx-core' ),
					'pulse' => esc_html__( 'Pulse', 'jupiterx-core' ),
					'pop' => esc_html__( 'Pop', 'jupiterx-core' ),
					'grow-rotate' => esc_html__( 'Grow Rotate', 'jupiterx-core' ),
					'wobble-skew' => esc_html__( 'Wobble Skew', 'jupiterx-core' ),
					'buzz-out' => esc_html__( 'Buzz Out', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'swap_effect',
			[
				'label' => esc_html__( 'Featured Image Hover', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'zoom_hover' => esc_html__( 'Zoom', 'jupiterx-core' ),
					'fade_hover' => esc_html__( 'Fade and Swap', 'jupiterx-core' ),
					'flip_hover' => esc_html__( 'Flip and Swap', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-swap-effect-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'load_effect',
			[
				'label' => esc_html__( 'Load Effect', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'fade-in' => esc_html__( 'Fade In', 'jupiterx-core' ),
					'slide-down' => esc_html__( 'Slide Down', 'jupiterx-core' ),
					'slide-up' => esc_html__( 'Slide Up', 'jupiterx-core' ),
					'slide-right' => esc_html__( 'Slide Left', 'jupiterx-core' ),
					'slide-left' => esc_html__( 'Slide Right', 'jupiterx-core' ),
					'scale-up' => esc_html__( 'Scale Up', 'jupiterx-core' ),
					'scale-down' => esc_html__( 'Scale Down', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_overlay_content_on_hover',
			[
				'label' => esc_html__( 'Show Overlay Content On Hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-content-hover-',
				'condition' => [
					'content_layout' => 'content_overlay',
					'swap_effect!' => 'zoom_hover',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => esc_html__( 'Transition Duration', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 500,
				'min' => 100,
				'max' => 10000,
				'step' => 50,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'enable_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 2000,
				'min' => 100,
				'max' => 10000,
				'step' => 50,
				'condition' => [
					'enable_autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'enable_infinite_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'overflow_visible',
			[
				'label' => esc_html__( 'Overflow Visible', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'ui',
				'frontend_available' => true,
				'prefix_class' => 'raven-overflow-visible-',
			]
		);

		$this->add_control(
			'arrows',
			[
				'label' => esc_html__( 'Arrows', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'ui',
				'prefix_class' => 'raven-arrows-',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Pagination', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'template',
				'selectors_dictionary' => [
					'yes' => 'display: block;',
					'' => 'display: none !important;',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => '{{VALUE}}',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'frontend_available' => true,
				'condition' => [
					'enable_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'View Pagination As', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'bullets',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'bullets' => esc_html__( 'Dots', 'jupiterx-core' ),
					'fraction' => esc_html__( 'Fraction', 'jupiterx-core' ),
					'progressbar' => esc_html__( 'Progress', 'jupiterx-core' ),
				],
				'condition' => [
					'show_pagination' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'elements_heading_divider',
			[
				'label' => esc_html__( 'Elements', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'categories',
			[
				'label' => esc_html__( 'Categories', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'ui',
				'prefix_class' => 'raven-categories-',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'ui',
				'prefix_class' => 'raven-title-',
			]
		);

		$this->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'ui',
				'prefix_class' => 'raven-price-',
			]
		);

		$this->add_control(
			'rating',
			[
				'label' => esc_html__( 'Rating', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'ui',
				'prefix_class' => 'raven-rating-',
			]
		);

		$this->add_control(
			'atc_button',
			[
				'label' => esc_html__( 'Add to Cart Button', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'ui',
				'prefix_class' => 'raven-atc-button-',
			]
		);

		$this->add_control(
			'sale_badge',
			[
				'label' => esc_html__( 'Sale Badge', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'render_type' => 'ui',
				'prefix_class' => 'raven-sale-badge-',
			]
		);

		$this->add_control(
			'wishlist',
			[
				'label' => esc_html__( 'Wishlist', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-wishlist-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		if ( ! class_exists( 'YITH_WCWL' ) ) {
			$this->add_control(
				'wishlist_warning',
				[
					'raw' => esc_html__( 'In order to use Wishlist feature, you need to install YITH WooCommerce Wishlist plugin.', 'jupiterx-core' ),
					'type' => 'raw_html',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				]
			);
		}

		$this->end_controls_section();
	}

	protected function register_block_style_controls() {
		$this->start_controls_section(
			'section_block_style',
			[
				'label' => esc_html__( 'Block', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'columns_space_between',
			[
				'label' => esc_html__( 'Columns Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'desktop_default' => [
					'size' => 44,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'block_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product .jupiterx-product-container',
			]
		);

		$this->add_control(
			'block_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'px',
					'top' => 4,
					'right' => 4,
					'bottom' => 4,
					'left' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .jupiterx-product-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'block_style_tabs'
		);

		$this->start_controls_tab(
			'block_style_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'block_background_normal',
				'label' => esc_html__( 'Background Type', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product .jupiterx-product-container',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'block_box_shadow_normal',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}}  .raven-products-carousel ul.products li.product',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'block_style_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'block_background_hover',
				'label' => esc_html__( 'Background Type', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product:hover .jupiterx-product-container',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'block_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}}  .raven-products-carousel ul.products li.product:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'block_content_style_heading',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'block_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'end' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .raven-product-data' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'content_layout' => 'content_overlay',
				],
			]
		);

		$this->add_responsive_control(
			'block_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .raven-product-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; inset: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function register_image_style_controls() {
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product .jupiterx-wc-loop-product-image img',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .jupiterx-wc-loop-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .jupiterx-wc-loop-product-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .jupiterx-wc-loop-product-image:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'block_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel li.product .jupiterx-wc-loop-product-image-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_rating_style_controls() {
		$this->start_controls_section(
			'section_rating_style',
			[
				'label' => esc_html__( 'Rating', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'rating' => 'show',
				],
			]
		);

		$this->add_responsive_control(
			'rating_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs(
			'rating_style_tabs'
		);

		$this->start_controls_tab(
			'rating_style_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'rating_color_normal',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffc000',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .star-rating::before' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rating_style_tab_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'rating_color_active',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffc000',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .star-rating span' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .star-rating span::before' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_category_style_controls() {
		$this->start_controls_section(
			'section_category_style',
			[
				'label' => esc_html__( 'Categories', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'categories' => 'show',
				],
			]
		);

		$this->add_control(
			'categories_view_as',
			[
				'label' => esc_html__( 'View as', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'block',
				'options' => [
					'block' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'inline-block' => esc_html__( 'Inline', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product span.posted_in' => 'display: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'category_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#656565',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product span.posted_in' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'category_typography',
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product span.posted_in',
			]
		);

		$this->add_responsive_control(
			'category_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product span.posted_in' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_title_style_controls() {
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'title' => 'show',
				],
			]
		);

		$this->add_control(
			'title_view_as',
			[
				'label' => esc_html__( 'View as', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'block',
				'options' => [
					'block' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'inline-block' => esc_html__( 'Inline', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .woocommerce-loop-product__title' => 'display: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product .woocommerce-loop-product__title',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_price_style_controls() {
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'price' => 'show',
				],
			]
		);

		$this->add_control(
			'price_view_as',
			[
				'label' => esc_html__( 'View as', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'stacked',
				'options' => [
					'stacked' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'inline' => esc_html__( 'Inline', 'jupiterx-core' ),
				],
				'render_type' => 'ui',
				'prefix_class' => 'raven-price-view-',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#888888',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product span.price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-products-carousel ul.products li.product span.price ins .woocommerce-Price-amount' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .raven-products-carousel ul.products li.product span.price ins .woocommerce-Price-amount *' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product span.price ins .woocommerce-Price-amount, {{WRAPPER}} .raven-products-carousel ul.products li.product span.price ins .woocommerce-Price-amount *, {{WRAPPER}} .raven-products-carousel ul.products li.product span.price :not(ins), {{WRAPPER}} .raven-products-carousel ul.products li.product span.price :not(del)',
			]
		);

		$this->add_control(
			'regular_price',
			[
				'label' => esc_html__( 'Price Regular', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'regular_price_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#a0a0a0',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products li.product span.price del' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'regular_price_typography',
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product span.price del, {{WRAPPER}} .raven-products-carousel ul.products li.product span.price del span',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_add_to_cart_button_style_controls() {
		$this->start_controls_section(
			'section_atc_style',
			[
				'label' => esc_html__( 'Add to Cart Button', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_heading',
			[
				'label' => esc_html__( 'Add to Cart Button', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_location',
			[
				'label' => esc_html__( 'Location', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'outside',
				'frontend_available' => true,
				'options' => [
					'inside' => esc_html__( 'Inside Image', 'jupiterx-core' ),
					'outside' => esc_html__( 'Outside Image', 'jupiterx-core' ),
				],
				'prefix_class' => 'atc-button-location-',
				'render_type' => 'template',
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'frontend_available' => true,
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_pc_atc_button' );

		$this->start_controls_tab(
			'tabs_pc_atc_button_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-products-carousel ul.products .button svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'default' => '#000',
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .button' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'pc_atc_button_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products .button',
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		if ( 'active' === get_option( 'elementor_experiment-e_font_icon_svg' ) ) {
			$this->add_responsive_control(
				'pc_atc_button_icon_size',
				[
					'label' => esc_html__( 'Size', 'jupiterx-core' ),
					'type' => 'slider',
					'default' => [
						'size' => 14,
					],
					'range' => [
						'px' => [
							'min' => 6,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .raven-products-carousel ul.products .button svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
		}

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_pc_atc_button_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-products-carousel ul.products .button:hover svg' => 'fill: {{VALUE}};',

				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .button:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'pc_atc_button_typography_hover',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products .button:hover',
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			'border',
			[
				'name' => 'pc_atc_button_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'separator' => 'before',
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
						'default' => [
							'unit'   => 'px',
							'top'    => '1',
							'left'   => '1',
							'right'  => '1',
							'bottom' => '1',
						],
					],
				],
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products li.product .button',
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.atc-button-location-inside .raven-products-carousel ul.products li.product:hover .button' => 'bottom: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}}.atc-button-location-inside .raven-products-carousel ul.products li.product .button' => 'top: auto; right: {{RIGHT}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.atc-button-location-outside .raven-products-carousel ul.products .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'atc_button' => 'show',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_overlay_style_controls() {
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Overlay', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'tabs_overlay' );

		$this->start_controls_tab(
			'tabs_overlay_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'overlay_normal_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}}.raven-content-layout-content_under_image li.product .jupiterx-wc-loop-product-image:after, {{WRAPPER}}.raven-content-layout-content_under_image li.product .woocommerce-loop-product__link:after, {{WRAPPER}} li.product .raven-product-image-overlay',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_overlay_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'overlay_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}}.raven-content-layout-content_under_image li.product:hover .jupiterx-wc-loop-product-image:after, {{WRAPPER}}.raven-content-layout-content_under_image li.product .woocommerce-loop-product__link:after, {{WRAPPER}} li.product:hover .raven-product-image-overlay',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_sale_badge_style_controls() {
		$this->start_controls_section(
			'section_style_sale_badge',
			[
				'label' => esc_html__( 'Sale Badge', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);

		$this->add_control(
			'sale_badge_position',
			[
				'label'  => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'raven-sale-badge-location-',
				'render_type' => 'ui',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'sale_badge_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
						'default' => [
							'unit'   => 'px',
							'top'    => '1',
							'left'   => '1',
							'right'  => '1',
							'bottom' => '1',
						],
					],
				],
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products .onsale',
			]
		);

		$this->add_control(
			'sale_badge_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top'    => '0',
					'left'   => '0',
					'right'  => '0',
					'bottom' => '0',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_badge_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top'    => '5',
					'left'   => '10',
					'right'  => '10',
					'bottom' => '5',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_badge_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top'    => '10',
					'left'   => '10',
					'right'  => '10',
					'bottom' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sale_badge_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .onsale' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sale_badge_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .onsale' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sale_badge_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .onsale' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'sale_badge_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-products-carousel ul.products .onsale',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_arrows_style_controls() {
		$this->start_controls_section(
			'section_style_arrows',
			[
				'label' => esc_html__( 'Arrows', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'tabs_arrows' );

		$this->start_controls_tab(
			'tabs_arrows_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:before, {{WRAPPER}} .raven-products-carousel .swiper-button-next:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev, {{WRAPPER}} .raven-products-carousel .swiper-button-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:before, {{WRAPPER}} .raven-products-carousel .swiper-button-next:before' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => 8,
					'left' => 8,
					'right' => 8,
					'bottom' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev, {{WRAPPER}} .raven-products-carousel .swiper-button-next' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => -40,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-products-carousel .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'arrows_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'arrows_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev, {{WRAPPER}} .raven-products-carousel .swiper-button-next' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'arrows_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-products-carousel .swiper-button-prev, {{WRAPPER}} .raven-products-carousel .swiper-button-next',
			]
		);

		$this->add_control(
			'arrows_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev, {{WRAPPER}} .raven-products-carousel .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'arrows_box_shadow',
				'selector' => '{{WRAPPER}} .raven-products-carousel .swiper-button-prev, {{WRAPPER}} .raven-products-carousel .swiper-button-next',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_arrows_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_arrows_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover:before, {{WRAPPER}} .raven-products-carousel .swiper-button-next:hover:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_arrows_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover, {{WRAPPER}} .raven-products-carousel .swiper-button-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover:before, {{WRAPPER}} .raven-products-carousel .swiper-button-next:hover:before' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover, {{WRAPPER}} .raven-products-carousel .swiper-button-next:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-products-carousel .swiper-button-next:hover' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_arrows_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_arrows_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover, {{WRAPPER}} .raven-products-carousel .swiper-button-next:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_arrows_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover, {{WRAPPER}} .raven-products-carousel .swiper-button-next:hover',
			]
		);

		$this->add_control(
			'hover_arrows_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover, {{WRAPPER}} .raven-products-carousel .swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_arrows_box_shadow',
				'selector' => '{{WRAPPER}} .raven-products-carousel .swiper-button-prev:hover, {{WRAPPER}} .raven-products-carousel .swiper-button-next:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_pagination_style_controls() {
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'show_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'outside',
				'options' => [
					'outside' => esc_html__( 'Outside', 'jupiterx-core' ),
					'inside' => esc_html__( 'Inside', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-pagination-position-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'progressbar_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
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
				'condition' => [
					'pagination_type' => 'progressbar',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_space_between',
			[
				'label' => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'size' => 22,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 calc( {{SIZE}}{{UNIT}} / 2 );',
				],
				'condition' => [
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 100,
					],
				],
				'default' => [
					'px' => [
						'size' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}; bottom: calc(0px - {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->register_bullets_controls();

		$this->register_progressbar_controls();

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_bullets_controls() {

		$this->start_controls_tabs( 'tabs_dots' );

		$this->update_control(
			'tabs_dots',
			[
				'condition' => [
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->start_controls_tab(
			'tabs_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'condition' => [
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#e4e4e4',
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'pagination_type' => 'bullets',
					'dots_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_dots_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
				'condition' => [
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->add_control(
			'active_dots_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#cdcdcd',
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'active_dots_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'active_dots_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->add_control(
			'active_dots_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'pagination_type' => 'bullets',
					'active_dots_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'active_dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selector' => '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_dots_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'condition' => [
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->add_control(
			'hover_dots_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_dots_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active:hover' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_dots_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'pagination_type' => 'bullets',
				],
			]
		);

		$this->add_control(
			'hover_dots_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'pagination_type' => 'bullets',
					'hover_dots_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => [
					'pagination_type' => 'bullets',
				],
				'selector' => '{{WRAPPER}} .swiper-pagination-bullet:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_progressbar_controls() {
		$this->start_controls_tabs( 'tabs_progressbar' );

		$this->update_control(
			'tabs_progressbar',
			[
				'condition' => [
					'pagination_type' => 'progressbar',
				],
			]
		);

		$this->start_controls_tab(
			'tabs_progressbar_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'condition' => [
					'pagination_type' => 'progressbar',
				],
			]
		);

		$this->add_control(
			'progressbar_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'pagination_type' => 'progressbar',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'progressbar_thickness',
			[
				'label' => esc_html__( 'Thickness', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'pagination_type' => 'progressbar',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_progressbar_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
				'condition' => [
					'pagination_type' => 'progressbar',
				],
			]
		);

		$this->add_control(
			'active_progressbar_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'pagination_type' => 'progressbar',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'active_progressbar_thickness',
			[
				'label' => esc_html__( 'Thickness', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'pagination_type' => 'progressbar',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_progressbar_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'condition' => [
					'pagination_type' => 'progressbar',
				],
			]
		);

		$this->add_control(
			'hover_progressbar_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'pagination_type' => 'progressbar',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar-fill:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_progressbar_thickness',
			[
				'label' => esc_html__( 'Thickness', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'pagination_type' => 'progressbar',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar:hover' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination-progressbar-fill:hover' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination-progressbar:hover .swiper-pagination-progressbar-fill' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	protected function register_wishlist_style_controls() {
		$this->start_controls_section(
			'section_style_wishlist',
			[
				'label' => esc_html__( 'Wishlist', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'wishlist' => 'show',
				],
			]
		);

		$this->add_control(
			'wishlist_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'default' => [
					'value' => 'far fa-heart',
					'library' => 'fa-regular',
				],
			]
		);

		$this->add_control(
			'wishlist_icon_remove',
			[
				'label' => esc_html__( 'Icon Active', 'jupiterx-core' ),
				'type' => 'icons',
				'default' => [
					'value' => 'fas fa-heart',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'wishlist_position',
			[
				'label'  => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => 'left: 0; right: auto;',
					'right' => 'right: 0; left: auto;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist' => '{{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_wishlist' );

		$this->start_controls_tab(
			'tabs_wishlist_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'wishlist_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wishlist_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wishlist_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_wishlist_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'wishlist_color_hover',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wishlist_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wishlist_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'wishlist_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'dimensions',
				'separator' => 'before',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
				],
			]
		);

		$this->add_control(
			'wishlist_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
				],
			]
		);

		$this->add_control(
			'wishlist_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
				],
			]
		);

		$this->add_control(
			'wishlist_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => '10',
					'left' => '10',
					'right' => '10',
					'bottom' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-carousel ul.products .jupiterx-wishlist' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings                 = $this->get_settings();
		$is_current_archive_query = 'current_archive_query' === $settings['query_filter'];

		if ( $is_current_archive_query && ! Module::is_editor_or_preview() && ! is_tax() ) {
			return;
		}

		$display_settings = $this->get_settings_for_display();
		$widget_title     = $display_settings['widget_title'];
		$query            = Module::query( $this, $settings );
		$products         = $query->get_content();

		// Return when we don't have any products to show.
		if ( $products['query_results']->total < 1 ) {
			echo $products['data'];

			return;
		}

		if ( ! empty( $widget_title ) ) {
			echo sprintf( '<h2 class="raven-products-carousel-title">%s</h2>', esc_html( $widget_title ) );
		}
		?>
		<div class="raven-products-carousel raven-swiper-slider">
			<?php
			echo wp_kses_post( $this->modify_html_for_swiper( $products['data'] ) );
			echo wp_kses_post( $this->render_pagination() );
			?>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
		<?php
	}

	protected function modify_html_for_swiper( $html ) {
		$swiper_settings = wp_json_encode( [
			'rtl' => is_rtl() ? true : false,
		] );

		$slides_view  = $this->get_settings( 'slides_view' );
		$hover_effect = $this->get_settings( 'hover_effect' );
		$load_effect  = $this->get_settings( 'load_effect' );

		$patterns = [
			'/<div class="woocommerce/', //Carousel wrapper.
			'/<ul class="products columns-/', //Slides wrapper.
			'/<li class="product/', //Slide wrapper.
			'/<\/li>/', // End product data.
		];

		$swiper_class = Elementor::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$replacements = [
			'<div class="woocommerce raven-products-carousel-swiper-container ' . $swiper_class . ' ', //Carousel wrapper.
			sprintf(
				'<ul data-swiper="%1$s" class="swiper-wrapper swiper-columns-%2$s %3$s products ',
				esc_attr( $swiper_settings ),
				esc_attr( $slides_view ),
				! empty( $load_effect ) ? 'raven-products-load-effect' : ''
			), //Slides wrapper.
			sprintf(
				'<li class="swiper-slide raven-block-hover-animation-%1$s product',
				esc_attr( $hover_effect )
			), //Slide wrapper.
			'</li>', // End product data, Close div.raven-product-data.
		];

		$swiper_ready_html = preg_replace( $patterns, $replacements, $html );

		return $swiper_ready_html;
	}

	protected function render_pagination() {
		$pagination_type = $this->get_settings( 'pagination_type' );

		return sprintf( '<div class="swiper-pagination %s-pagination-type"></div>', $pagination_type );
	}
}
