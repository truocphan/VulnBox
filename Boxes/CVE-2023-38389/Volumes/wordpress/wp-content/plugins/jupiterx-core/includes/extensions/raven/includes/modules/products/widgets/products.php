<?php
namespace JupiterX_Core\Raven\Modules\Products\Widgets;

use Elementor\Controls_Manager;
use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Products\Module;
use JupiterX_Core\Raven\Controls\Query as Control_Query;
use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Plugin as RavenPlugin;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Products extends Base_Widget {

	public function get_name() {
		return 'raven-wc-products';
	}

	public function get_title() {
		return esc_html__( 'Products', 'jupiterx-core' );
	}

	public static function is_active() {
		return RavenPlugin::is_active( 'products' ) && function_exists( 'WC' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-products';
	}

	public function get_script_depends() {
		return [
			'imagesloaded',
			'raven-pagination',
			'jupiterx-core-raven-object-fit',
			'jupiterx-core-raven-isotope',
			'jupiterx-core-raven-packery',
		];
	}

	public function get_style_depends() {
		return [ 'e-animations' ];
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_layout();
		$this->register_section_elements_order();
		$this->register_section_warning();
		$this->register_section_widget_title();
		$this->register_section_box();
		$this->register_section_image();
		$this->register_section_rating();
		$this->register_section_categories();
		$this->register_section_title();
		$this->register_section_price();
		$this->register_section_add_to_cart();
		$this->register_section_pagination();
		$this->register_section_sorting_result_count();
		$this->register_overlay_style();
		$this->register_section_sale_badge();
		$this->register_section_wishlist();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
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
				'frontend_available' => true,
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
				'frontend_available' => true,
				'condition' => [
					'query_orderby!' => [ 'rand', '_wc_average_rating' ],
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

		$this->add_control(
			'widget_fallback_title',
			[
				'label' => esc_html__( 'Widget Fallback Title', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter your fallback title', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'query_filter',
							'operator' => '!in',
							'value' => [
								'all',
								'ids',
								'categories_tags',
							],
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_layout() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Choose Layout & Style Source', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => true,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Theme Default From Customizer', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_all_products',
			[
				'label' => esc_html__( 'Show All Products', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'frontend_available' => true,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'view_as',
			[
				'label' => esc_html__( 'View As', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'column',
				'options' => [
					'column' => esc_html__( 'Column', 'jupiterx-core' ),
				],
				'condition' => [
					'layout' => 'custom',
				],
				'frontend_available' => true,
				'classes' => 'jupiterx-hide-elementor-control',
			]
		);

		$this->add_control(
			'general_layout',
			[
				'label' => esc_html__( 'General Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid', 'jupiterx-core' ),
					'masonry' => esc_html__( 'Masonry', 'jupiterx-core' ),
					'matrix' => esc_html__( 'Matrix', 'jupiterx-core' ),
					'metro' => esc_html__( 'Metro', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'condition' => [
					'layout' => 'custom',
				],
				'prefix_class' => 'raven-products-gerenal-layout-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'content_layout',
			[
				'label' => esc_html__( 'Content Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'under-image',
				'options' => [
					'overlay' => esc_html__( 'Content Overlay', 'jupiterx-core' ),
					'under-image' => esc_html__( 'Content Under Image', 'jupiterx-core' ),
					'side' => esc_html__( 'Content on the Side', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-products-content-layout-',
				'frontend_available' => true,
				'condition' => [
					'general_layout' => [ 'grid', 'masonry' ],
					'layout' => 'custom',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'horizontal_alignment',
			[
				'label' => esc_html__( 'Horizontal Alignment', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'jupiterx-core' ),
					'right' => esc_html__( 'Right', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-products-side-horizontal-',
				'condition' => [
					'content_layout' => 'side',
					'general_layout' => [ 'grid', 'masonry' ],
					'layout' => 'custom',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'vertical_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'center',
				'options' => [
					'flex-start' => esc_html__( 'Top', 'jupiterx-core' ),
					'center' => esc_html__( 'Middle', 'jupiterx-core' ),
					'end' => esc_html__( 'Bottom', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-content-layout-side .jupiterx-product-container' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'content_layout' => 'side',
					'general_layout' => [ 'grid', 'masonry' ],
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'metro_matrix_content_layout',
			[
				'label' => esc_html__( 'Content Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'under-image',
				'options' => [
					'overlay' => esc_html__( 'Content Overlay', 'jupiterx-core' ),
					'under-image' => esc_html__( 'Content Under Image', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-products-content-layout-',
				'frontend_available' => true,
				'condition' => [
					'general_layout' => [ 'metro', 'matrix' ],
					'layout' => 'custom',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'layout!' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'columns_custom',
			[
				'label' => esc_html__( 'Columns', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'view_as',
									'operator' => '===',
									'value' => 'column',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'masonry', 'grid' ],
								],
							],
						],
					],
				],
				'frontend_available' => true,
				'prefix_class' => 'raven-products-columns-%s-count-',
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'rows',
			[
				'label' => esc_html__( 'Rows', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'conditions' => [
					'terms' => [
						[
							'name' => 'show_all_products',
							'value' => '',
						],
						[
							'name' => 'view_as',
							'value' => 'column',
						],
						[
							'name' => 'general_layout',
							'operator' => 'in',
							'value' => [ 'masonry', 'grid' ],
						],
					],
				],
			]
		);

		$this->add_control(
			'number_of_products',
			[
				'label' => __( 'Number of Products', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 6,
				'min' => 1,
				'max' => 50,
				'frontend_available' => true,
				'conditions' => [
					'terms' => [
						[
							'name' => 'show_all_products',
							'value' => '',
						],
						[
							'name' => 'view_as',
							'value' => 'column',
						],
						[
							'name' => 'general_layout',
							'operator' => 'in',
							'value' => [ 'matrix', 'metro' ],
						],
					],
				],
			]
		);

		$this->add_control(
			'posts_per_view',
			[
				'label' => esc_html__( 'Posts per View', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'condition' => [
					'view_as' => 'carousel',
				],
				'render_type' => 'template',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'condition' => [
					'view_as' => 'carousel',
				],
				'render_type' => 'template',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_responsive_control(
			'stroke_width',
			[
				'label' => esc_html__( 'Media Aspect Ratio', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-image-fit' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'masonry', 'grid' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'side',
								],
							],

						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => '===',
									'value' => 'grid',
								],
								[
									'name' => 'content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],
						],
					],
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
					'{{WRAPPER}} .raven-image-fit img' => '-o-object-position: {{VALUE}}; object-position: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'masonry', 'grid' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'side',
								],
							],

						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => '===',
									'value' => 'grid',
								],
								[
									'name' => 'content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'metro_matrix_large_aspect_ratio',
			[
				'label' => esc_html__( 'Large Media Aspect Ratio', 'jupiterx-core' ),
				'type' => 'slider',
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} li.product.raven-product-full-width .raven-image-fit' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
				'condition' => [
					'layout' => 'custom',
					'general_layout' => [ 'matrix', 'metro' ],
					'metro_matrix_content_layout!' => 'overlay',
				],
			]
		);

		$this->add_responsive_control(
			'large_media_position',
			[
				'label' => esc_html__( 'Large Media Position', 'jupiterx-core' ),
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
					'{{WRAPPER}} li.product.raven-product-full-width .raven-image-fit img' => '-o-object-position: {{VALUE}}; object-position: {{VALUE}};',
				],
				'condition' => [
					'layout' => 'custom',
					'general_layout' => [ 'matrix', 'metro' ],
					'metro_matrix_content_layout!' => 'overlay',
				],
			]
		);

		$this->add_responsive_control(
			'metro_matrix_small_aspect_ratio',
			[
				'label' => esc_html__( 'Small Media Aspect Ratio', 'jupiterx-core' ),
				'type' => 'slider',
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} li.product:not(.raven-product-full-width) .raven-image-fit' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
				'condition' => [
					'layout' => 'custom',
					'general_layout' => [ 'matrix', 'metro' ],
					'metro_matrix_content_layout!' => 'overlay',
				],
			]
		);

		$this->add_responsive_control(
			'small_media_position',
			[
				'label' => esc_html__( 'Small Media Position', 'jupiterx-core' ),
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
					'{{WRAPPER}} li.product:not(.raven-product-full-width) .raven-image-fit img' => '-o-object-position: {{VALUE}}; object-position: {{VALUE}};',
				],
				'condition' => [
					'layout' => 'custom',
					'general_layout' => [ 'matrix', 'metro' ],
					'metro_matrix_content_layout!' => 'overlay',
				],
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'image',
				'default' => 'woocommerce_thumbnail',
				'frontend_available' => true,
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'equal_height',
			[
				'label' => esc_html__( 'Equal Columns Height', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-grid .raven-wc-products-custom li.product' => 'height: auto',
					'{{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom li.product' => 'height: auto',
				],
				'condition' => [
					'general_layout' => 'grid',
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'block_hover',
			[
				'label' => esc_html__( 'Block hover', 'jupiterx-core' ),
				'type' => 'raven_hover_effect',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'swap_effect',
			[
				'label' => esc_html__( 'Featured Image Hover', 'jupiterx-core' ),
				'description' => esc_html__( "Zoom and Gallery effects don't work in here (editor). Check them out on frontend.", 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => true,
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'zoom_hover' => esc_html__( 'Zoom', 'jupiterx-core' ),
					'fade_hover' => esc_html__( 'Fade and Swap', 'jupiterx-core' ),
					'flip_hover' => esc_html__( 'Flip and Swap', 'jupiterx-core' ),
					'enlarge_hover' => esc_html__( 'Enlarge on Hover', 'jupiterx-core' ),
					'gallery_arrows' => esc_html__( 'Gallery Slide with Arrows', 'jupiterx-core' ),
					'gallery_pagination' => esc_html__( 'Gallery Slide with Pagination', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-swap-effect-',
				'render_type' => 'template',
				'frontend_available' => true,
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],

						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],
						],
					],
				],
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
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'show_overlay_on_hover',
			[
				'label' => esc_html__( 'Show Overlay Content On Hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'prefix_class' => 'raven-product-show-overlay-hover-',
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],

						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'display_elements_heading',
			[
				'label' => esc_html__( 'Display Elements', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'categories',
			[
				'label' => esc_html__( 'Categories', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-categories-',
				'condition' => [
					'layout' => 'custom',
				],
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
				'prefix_class' => 'raven-title-',
				'condition' => [
					'layout' => 'custom',
				],
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
				'prefix_class' => 'raven-price-',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'rating',
			[
				'label' => esc_html__( 'Rating', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-rating-',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'atc_button',
			[
				'label' => esc_html__( 'Add to Cart Button', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-atc-button-',
				'render_type' => 'template',
				'condition' => [
					'layout' => 'custom',
				],
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
				'prefix_class' => 'raven-sale-badge-',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'oos_badge',
			[
				'label' => esc_html__( 'Out of Stock Badge', 'jupiterx-core' ),
				'type' => 'hidden',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-oos-badge-',
				'condition' => [
					'layout' => 'custom',
				],
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
				'condition' => [
					'layout' => 'custom',
				],
				'frontend_available' => true,
			]
		);

		if (
			! class_exists( 'YITH_WCWL' )
		) {
			$this->add_control(
				'wishlist_warning',
				[
					'raw' => esc_html__( 'In order to use Wishlist feature, you need to install YITH WooCommerce Wishlist plugin.', 'jupiterx-core' ),
					'type' => 'raw_html',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'conditions' => [
						'terms' => [
							[
								'name' => 'layout',
								'operator' => '==',
								'value' => 'custom',
							],
							[
								'name' => 'wishlist',
								'operator' => '==',
								'value' => 'show',
							],
						],
					],
				]
			);
		}

		$this->add_control(
			'quick_view',
			[
				'label' => esc_html__( 'Quick View', 'jupiterx-core' ),
				'type' => 'hidden',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-quick-view-',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		if ( class_exists( 'Sellkit_Pro' ) ) {
			$this->add_control(
				'attribute_swatches',
				[
					'label' => esc_html__( 'Attribute Swatches', 'jupiterx-core' ),
					'type' => 'switcher',
					'default' => 'show',
					'return_value' => 'show',
					'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
					'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
					'prefix_class' => 'raven-attribute-swatches-',
					'render_type' => 'template',
					'condition' => [
						'layout' => 'custom',
					],
				]
			);
		}

		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Pagination', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'show_all_products' => '',
				],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'View Pagination As', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'page_based',
				'options' => [
					'page_based' => esc_html__( 'Page Based', 'jupiterx-core' ),
					'load_more' => esc_html__( 'Load More', 'jupiterx-core' ),
					'infinite_load' => esc_html__( 'Infinite Load', 'jupiterx-core' ),
				],
				'condition' => [
					'show_pagination' => 'yes',
					'show_all_products' => '',
				],
				'prefix_class' => 'raven-pagination-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'allow_ordering',
			[
				'label' => esc_html__( 'Show Sorting Dropdown', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-allow-ordering-',
				'condition' => [
					'show_pagination' => 'yes',
					'show_all_products' => '',
				],
			]
		);

		$this->add_control(
			'show_result_count',
			[
				'label' => esc_html__( 'Show Result Count', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'show',
				'return_value' => 'show',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'raven-result-count-',
				'condition' => [
					'show_pagination' => 'yes',
					'show_all_products' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_elements_order() {
		$this->start_controls_section(
			'section_elements_order',
			[
				'label' => esc_html__( 'Elements Order', 'jupiterx-core' ),
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_control(
			'elements_order_categories',
			[
				'label' => esc_html__( 'Categories', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 1,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-product-container .posted_in' => 'order: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'elements_order_title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 2,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-product-container .woocommerce-loop-product__title' => 'order: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'elements_order_rating',
			[
				'label' => esc_html__( 'Rating', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 3,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-product-container .rating-wrapper' => 'order: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'elements_order_price',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 4,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-product-container .price' => 'order: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'elements_order_atc_button',
			[
				'label' => esc_html__( 'Add To Cart', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 5,
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' =>
						[
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'pc_atc_button_location_overlay',
										'operator' => '===',
										'value' => 'outside',
									],
									[
										'name' => 'general_layout',
										'operator' => 'in',
										'value' => [ 'matrix', 'metro' ],
									],
									[
										'name' => 'metro_matrix_content_layout',
										'operator' => '===',
										'value' => 'overlay',
									],
								],
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'pc_atc_button_location_overlay',
										'operator' => '===',
										'value' => 'outside',
									],
									[
										'name' => 'general_layout',
										'operator' => 'in',
										'value' => [ 'grid', 'masonry' ],
									],
									[
										'name' => 'content_layout',
										'operator' => '===',
										'value' => 'overlay',
									],
								],
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'pc_atc_button_location',
										'operator' => '===',
										'value' => 'outside',
									],
									[
										'name' => 'general_layout',
										'operator' => 'in',
										'value' => [ 'grid', 'masonry' ],
									],
									[
										'name' => 'content_layout',
										'operator' => '!==',
										'value' => 'overlay',
									],
								],
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'pc_atc_button_location',
										'operator' => '===',
										'value' => 'outside',
									],
									[
										'name' => 'general_layout',
										'operator' => 'in',
										'value' => [ 'matrix', 'metro' ],
									],
									[
										'name' => 'metro_matrix_content_layout',
										'operator' => '!==',
										'value' => 'overlay',
									],
								],
							],
						],
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-product-container .add_to_cart_button' => 'order: {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();
	}


	private function register_section_warning() {
		$this->start_controls_section(
			'section_style_warning',
			[
				'label' => esc_html__( 'Styles', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout!' => 'custom',
				],
			]
		);

		$this->add_control(
			'style_warning',
			[
				'raw' => esc_html__( "In order to style this widget, you need to choose 'Custom' from Layout options in the Content tab, otherwise you can only edit pagination and rest of styles can be edited from Customizer options.", 'jupiterx-core' ),
				'type' => 'raw_html',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_widget_title() {
		$this->start_controls_section(
			'section_style_widget_title',
			[
				'label' => esc_html__( 'Widget Title', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'widget_title_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'left',
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
					'{{WRAPPER}} .raven-wc-products-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'widget_title_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'widget_title_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-wc-products-title',
			]
		);

		$this->add_responsive_control(
			'widget_title_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_box() {
		$this->start_controls_section(
			'section_style_box',
			[
				'label' => esc_html__( 'Block', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'box_columns_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom li.product' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 ); padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom ul.products' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
				],
				'condition' => [
					'general_layout' => [ 'grid', 'masonry' ],
				],
			]
		);

		$this->add_responsive_control(
			'box_rows_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom li.product' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'general_layout' => [ 'grid', 'masonry' ],
				],
			]
		);

		$this->add_responsive_control(
			'metro_matrix_rows_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'render_type' => 'template',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '0',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix ul.products' => ' margin-right: calc( -{{SIZE}}{{UNIT}}*2 ) !important; margin-top: -{{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix li.product' => 'padding-right: {{SIZE}}{{UNIT}} !important;margin-top: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.raven-products-gerenal-layout-metro ul.products' => ' margin-right: calc( -{{SIZE}}{{UNIT}}*2 ) !important; margin-top: -{{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.raven-products-gerenal-layout-metro li.product' => 'padding-right: {{SIZE}}{{UNIT}} !important;margin-top: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'general_layout' => [ 'metro', 'matrix' ],
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'box_border',
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
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products li.product .jupiterx-product-container',
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products li.product .jupiterx-product-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_box' );

		$this->start_controls_tab(
			'tabs_box_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'box_box_shadow',
				'selector' => '{{WRAPPER}}.raven-products-gerenal-layout-grid .raven-wc-products-custom ul.products li.product .jupiterx-product-container, {{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom .jupiterx-product-container, {{WRAPPER}}.raven-products-gerenal-layout-matrix .raven-wc-products-custom .jupiterx-product-container, {{WRAPPER}}.raven-products-gerenal-layout-metro .raven-wc-products-custom .jupiterx-product-container',
			]
		);

		$this->add_control(
			'box_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-grid .raven-wc-products-custom ul.products li.product .jupiterx-product-container' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom .jupiterx-product-container' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix .raven-wc-products-custom .jupiterx-product-container' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-metro .raven-wc-products-custom .jupiterx-product-container' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-grid .raven-wc-products-custom ul.products li.product .jupiterx-product-container' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-product-container' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'box_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_box_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'box_box_shadow_hover',
				'selector' => '{{WRAPPER}}.raven-products-gerenal-layout-grid .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container, {{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom li.product:hover .jupiterx-product-container, {{WRAPPER}}.raven-products-gerenal-layout-metro .raven-wc-products-custom li.product:hover .jupiterx-product-container, {{WRAPPER}}.raven-products-gerenal-layout-matrix .raven-wc-products-custom li.product:hover .jupiterx-product-container',
			]
		);

		$this->add_control(
			'box_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-grid .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-metro .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'box_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-grid .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-metro .raven-wc-products-custom ul.products li.product:hover .jupiterx-product-container' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'box_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'product_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'box_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
				'prefix_class' => 'box-alignment-',
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products li.product' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .raven-wc-products-custom ul.products li.product .rating-wrapper' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'block_content_v_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'label_block' => false,
				'default' => '',
				'options' => [
					'flex-start' => [
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
				'condition' => [
					'general_layout' => [ 'matrix', 'metro' ],
					'metro_matrix_content_layout' => 'overlay',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-metro .jupiterx-product-container' => 'align-items: {{VALUE}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix .jupiterx-product-container' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-grid.raven-products-content-layout-under-image .raven-wc-products-custom ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}}.raven-products-content-layout-overlay .raven-wc-products-custom ul.products .jupiterx-product-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}}.raven-products-gerenal-layout-grid.raven-products-content-layout-side .raven-wc-products-custom ul.products .jupiterx-product-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}}.raven-products-gerenal-layout-masonry .raven-wc-products-custom .jupiterx-product-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix .raven-wc-products-custom .jupiterx-product-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.raven-products-gerenal-layout-metro .raven-wc-products-custom .jupiterx-product-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'custom',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_image() {
		$this->start_controls_section(
			'section_style_product_image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'featured_image_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px', 'vh' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'default' => [
					'size' => '50',
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => '50',
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => '50',
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-content-layout-side .jupiterx-wc-loop-product-image-wrapper' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-products-content-layout-side.raven-products-add-to-cart-button-outside .jupiterx-product-container > .woocommerce-LoopProduct-link' => 'width: {{SIZE}}{{UNIT}};',
				],
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'side',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '===',
									'value' => 'side',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'section_featured_background_position',
			[
				'label' => esc_html__( 'Background Position', 'jupiterx-core' ),
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
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-content-layout-overlay .jupiterx-wc-loop-product-image img' => '-o-object-position: {{VALUE}}; object-position: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_image_background_size',
			[
				'label' => esc_html__( 'Background Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'cover',
				'options' => [
					'auto' => esc_html__( 'Auto', 'jupiterx-core' ),
					'cover' => esc_html__( 'Cover', 'jupiterx-core' ),
					'contain' => esc_html__( 'Contain', 'jupiterx-core' ),
				],
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-content-layout-overlay .jupiterx-wc-loop-product-image img' => '-o-object-fit: {{VALUE}}; object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'featured_image_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px', 'vh' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'default' => [
					'size' => '100',
					'unit' => 'vh',
				],
				'tablet_default' => [
					'size' => '100',
					'unit' => 'vh',
				],
				'mobile_default' => [
					'size' => '100',
					'unit' => 'vh',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix li.product.raven-product-full-width' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'general_layout' => 'matrix',
					'metro_matrix_content_layout' => 'overlay',
				],
			]
		);

		$this->add_responsive_control(
			'featured_small_image_height',
			[
				'label' => esc_html__( 'Small Images Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px', 'vh' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'default' => [
					'size' => '65',
					'unit' => 'vh',
				],
				'tablet_default' => [
					'size' => '65',
					'unit' => 'vh',
				],
				'mobile_default' => [
					'size' => '65',
					'unit' => 'vh',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-gerenal-layout-matrix li.product:not(.raven-product-full-width)' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'general_layout' => 'matrix',
					'metro_matrix_content_layout' => 'overlay',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'pc_image_border',
				'placeholder' => '1px',
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
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],
						],
					],
				],
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wc-loop-product-image',
			]
		);

		$this->add_responsive_control(
			'pc_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wc-loop-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'featured_image_tabs' );

		$this->start_controls_tab(
			'featured_image_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'featured_image_opacity',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}}:not(.raven-products-content-layout-overlay) .jupiterx-wc-loop-product-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'featured_image_hover',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'featured_image_opacity_hover',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-products-content-layout-overlay li.product:hover .jupiterx-wc-loop-product-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pc_image_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'conditions'   => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wc-loop-product-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'pc_image_box_shadow',
				'selector' => '{{WRAPPER}} .jupiterx-wc-loop-product-image',
			]
		);

		$this->add_responsive_control(
			'pc_image_object_fit',
			[
				'label' => esc_html__( 'Object Fit', 'jupiterx-core' ),
				'type' => 'select',
				'condition' => [
					'general_layout' => [ 'matrix', 'metro' ],
					'metro_matrix_content_layout' => [ 'under-image' ],
				],
				'options' => [
					'fill' => esc_html__( 'Fill', 'jupiterx-core' ),
					'cover' => esc_html__( 'Cover', 'jupiterx-core' ),
					'contain' => esc_html__( 'Contain', 'jupiterx-core' ),
				],
				'default' => 'cover',
				'selectors' => [
					'{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_rating() {
		$this->start_controls_section(
			'section_style_product_rating',
			[
				'label' => esc_html__( 'Rating', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'custom',
					'rating' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_rating_view',
			[
				'label' => esc_html__( 'View as', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'stacked',
				'render_type' => 'template',
				'options' => [
					'stacked' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'inline' => esc_html__( 'Inline', 'jupiterx-core' ),
				],
				'selectors_dictionary' => [
					'stacked' => 'width: 100%; display: flex;',
					'inline' => 'width:fit-content; display: inline-flex; padding: 0.4em 0;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .rating-wrapper' => '{{VALUE}}; padding: 0.5em 0; margin: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'pc_rating_size',
			[
				'label' => esc_html__( 'Icon size', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pc_rating_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'render_type' => 'template',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs( 'rating_tabs' );

		$this->start_controls_tab(
			'rating_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'rating_normal_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .star-rating::before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rating_active_tab',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'rating_active_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .star-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_section_categories() {
		$this->start_controls_section(
			'section_style_product_categories',
			[
				'label' => esc_html__( 'Categories', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'custom',
					'categories' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_categories_view',
			[
				'label' => esc_html__( 'View as', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'stacked',
				'render_type' => 'template',
				'options' => [
					'stacked' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'inline' => esc_html__( 'Inline', 'jupiterx-core' ),
				],
				'selectors_dictionary' => [
					'stacked' => 'width: 100%; display: block;',
					'inline' => 'width:fit-content; display: inline-block;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .posted_in' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pc_categories_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#656565',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .posted_in' => 'color: {{VALUE}};',
				],
				'condition' => [
					'categories' => 'show',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'pc_categories_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .posted_in',
			]
		);

		$this->add_responsive_control(
			'pc_categories_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .posted_in' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_title() {
		$this->start_controls_section(
			'section_style_product_title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'custom',
					'title' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_title_view',
			[
				'label' => esc_html__( 'View as', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'stacked',
				'render_type' => 'template',
				'options' => [
					'stacked' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'inline' => esc_html__( 'Inline', 'jupiterx-core' ),
				],
				'selectors_dictionary' => [
					'stacked' => 'width: 100%; display: block;',
					'inline' => 'width:fit-content; display: inline-block;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .woocommerce-loop-product__title' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pc_title_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .woocommerce-loop-product__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'pc_title_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .woocommerce-loop-product__title',
			]
		);

		$this->add_responsive_control(
			'pc_title_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'render_type' => 'template',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'metro_products_title_heading',
			[
				'label' => esc_html__( 'Metro Product Title', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'general_layout' => 'metro',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'metro_products_title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}}.raven-products-gerenal-layout-metro li.product:not(.raven-product-full-width) .woocommerce-loop-product__title',
				'condition' => [
					'general_layout' => 'metro',
				],
			]
		);

		$this->add_control(
			'matrix_products_title_heading',
			[
				'label' => esc_html__( 'Matrix Product Title', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'general_layout' => 'matrix',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'matrix_products_title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}}.raven-products-gerenal-layout-matrix li.product:not(.raven-product-full-width) .woocommerce-loop-product__title',
				'condition' => [
					'general_layout' => 'matrix',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_price() {
		$this->start_controls_section(
			'section_style_product_price',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'custom',
					'price' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_price_view',
			[
				'label' => esc_html__( 'View as', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'stacked',
				'render_type' => 'template',
				'options' => [
					'stacked' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'inline' => esc_html__( 'Inline', 'jupiterx-core' ),
				],
				'selectors_dictionary' => [
					'stacked' => 'width: 100%; display: block;',
					'inline' => 'width:fit-content; display: inline-block;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .price' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pc_price_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'pc_price_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .price, {{WRAPPER}} .raven-wc-products-custom ul.products .price .amount bdi',
			]
		);

		$this->add_control(
			'pc_price_regular_heading',
			[
				'label' => esc_html__( 'Price Regular', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pc_price_regular_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .price del' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'pc_price_regular_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .price del, {{WRAPPER}} .raven-wc-products-custom ul.products .price del .amount bdi',
			]
		);

		$this->add_responsive_control(
			'pc_price_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'render_type' => 'template',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_add_to_cart() {
		$this->start_controls_section(
			'section_style_product_add_to_cart',
			[
				'label' => esc_html__( 'Add to Cart Button', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'custom',
					'atc_button' => 'show',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_location',
			[
				'label' => esc_html__( 'Location', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'inside',
				'frontend_available' => true,
				'render_type' => 'template',
				'prefix_class' => 'raven-products-add-to-cart-button-',
				'options' => [
					'inside' => esc_html__( 'Inside Image', 'jupiterx-core' ),
					'outside' => esc_html__( 'Outside Image', 'jupiterx-core' ),
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'masonry', 'grid' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'side',
								],
							],

						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],

						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '!==',
									'value' => 'overlay',
								],
							],

						],
					],
				],
			]
		);

		$this->add_control(
			'pc_atc_button_location_overlay',
			[
				'label' => esc_html__( 'Location', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'inside',
				'frontend_available' => true,
				'render_type' => 'template',
				'prefix_class' => 'raven-products-add-to-cart-button-',
				'options' => [
					'inside' => esc_html__( 'Slide Over Image', 'jupiterx-core' ),
					'outside' => esc_html__( 'Under Product Content', 'jupiterx-core' ),
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
								[
									'name' => 'metro_matrix_content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '===',
									'value' => 'overlay',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'pc_atc_button_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'frontend_available' => true,
			]
		);

		$this->start_controls_tabs( 'tabs_pc_atc_button' );

		$this->start_controls_tab(
			'tabs_pc_atc_button_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'pc_atc_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button svg' => 'fill: {{VALUE}};',
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'pc_atc_button_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .button',
			]
		);

		if ( 'active' === get_option( 'elementor_experiment-e_font_icon_svg' ) ) {
			$this->add_responsive_control(
				'pc_atc_button_icon_size',
				[
					'label' => __( 'Size', 'jupiterx-core' ),
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
						'{{WRAPPER}} .raven-wc-products-custom ul.products .button svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
		}

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_pc_atc_button_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'pc_atc_button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button:hover svg' => 'fill: {{VALUE}};',

				],
			]
		);

		$this->add_control(
			'pc_atc_button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pc_atc_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'pc_atc_button_typography_hover',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .button:hover',
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
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .button',
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pc_atc_button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pc_atc_button_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	private function register_section_pagination() {
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '!==',
									'value' => 'custom',
								],
								[
									'name' => 'show_all_products',
									'operator' => '==',
									'value' => '',
								],
								[
									'name' => 'pagination_type',
									'operator' => '==',
									'value' => 'load_more',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'show_all_products',
									'operator' => '==',
									'value' => '',
								],
								[
									'name' => 'pagination_type',
									'operator' => '!==',
									'value' => 'infinite_load',
								],
							],

						],
					],
				],
			]
		);

		// Page based.
		$page_based_condition = [
			'terms' => [
				[
					'name' => 'layout',
					'operator' => '==',
					'value' => 'custom',
				],
				[
					'name' => 'pagination_type',
					'operator' => '==',
					'value' => 'page_based',
				],
			],
		];

		$this->add_responsive_control(
			'page_based_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'page_based_border',
				'placeholder' => '1px',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
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
					'color' => [
						'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
					],
				],
				'conditions' => $page_based_condition,
				'selector' => '{{WRAPPER}} .woocommerce-pagination .page-numbers .page-numbers',
			]
		);

		$this->add_responsive_control(
			'page_based_space_between',
			[
				'label' => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
					'size' => -1,
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination .page-numbers li' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'page_based_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination .page-numbers .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'paged_based_typography',
				'scheme' => '3',
				'conditions' => $page_based_condition,
				'selector' => '{{WRAPPER}} .woocommerce-pagination .page-numbers',
			]
		);

		$this->start_controls_tabs( 'tabs_page_based' );

		$this->start_controls_tab(
			'tabs_page_based_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'conditions' => $page_based_condition,
			]
		);

		$this->add_control(
			'page_based_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000',
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination .page-numbers' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'page_based_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination .page-numbers .page-numbers' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_page_based_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'conditions' => $page_based_condition,
			]
		);

		$this->add_control(
			'page_based_color_hover',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination .page-numbers .page-numbers:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'page_based_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination .page-numbers .page-numbers:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_page_based_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
				'conditions' => $page_based_condition,
			]
		);

		$this->add_control(
			'page_based_color_active',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#fff',
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination .page-numbers .page-numbers.current' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'page_based_background_color_active',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000',
				'conditions' => $page_based_condition,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination .page-numbers .page-numbers.current' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Load more.
		$load_more_condition = [
			'pagination_type' => 'load_more',
		];

		$this->add_control(
			'load_more_text',
			[
				'label' => esc_html__( 'Button Label', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Load More', 'jupiterx-core' ),
				'frontend_available' => true,
				'condition' => $load_more_condition,
			]
		);

		$this->add_responsive_control(
			'load_more_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_align',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_load_more' );

		$this->start_controls_tab(
			'tabs_load_more_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'condition' => $load_more_condition,
			]
		);

		$this->add_control(
			'load_more_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'load_more_typography',
				'scheme' => '3',
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button',
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'load_more_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
					],
				],
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button',
			]
		);

		$this->add_control(
			'load_more_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => $load_more_condition,
			]
		);

		$this->add_control(
			'load_more_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'load_more_border_border!' => '',
					'pagination_type' => 'load_more',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'load_more_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button',
			]
		);

		$this->add_responsive_control(
			'load_more_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'load_more_box_shadow',
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_load_more_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'condition' => $load_more_condition,
			]
		);

		$this->add_control(
			'hover_load_more_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'hover_load_more_typography',
				'scheme' => '3',
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button:hover',
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'hover_load_more_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
					],
				],
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button:hover',
			]
		);

		$this->add_control(
			'hover_load_more_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => $load_more_condition,
			]
		);

		$this->add_control(
			'hover_load_more_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'hover_load_more_border_border!' => '',
					'pagination_type' => 'load_more',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_load_more_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button:hover',
			]
		);

		$this->add_responsive_control(
			'hover_load_more_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_load_more_box_shadow',
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	private function register_overlay_style() {
		$this->start_controls_section(
			'section_overlay',
			[
				'label' => esc_html__( 'Overlay', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'matrix', 'metro' ],
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout',
									'operator' => '===',
									'value' => 'custom',
								],
								[
									'name' => 'general_layout',
									'operator' => 'in',
									'value' => [ 'grid', 'masonry' ],
								],
								[
									'name' => 'content_layout',
									'operator' => '!==',
									'value' => 'side',
								],
							],

						],
					],
				],
			]
		);

		$this->start_controls_tabs( 'overlay_tabs' );

		$this->start_controls_tab(
			'overlay_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'posts_featured_image_overlay_normal',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Color Type', 'jupiterx-core' ),
						'default' => 'classic',
					],
					'color' => [
						'label' => esc_html__( 'Color', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-wc-products-custom li.product .raven-product-image-overlay',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'overlay_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'posts_featured_image_overlay_hover',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Color Type', 'jupiterx-core' ),
						'default' => 'classic',
					],
					'color' => [
						'label' => esc_html__( 'Color', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-wc-products-custom li.product:hover .raven-product-image-overlay',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'products_featured_image_overlay_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 0.5,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom li.product .raven-product-image-overlay' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_sorting_result_count() {
		$result_count_conditions = [
			'relation' => 'and',
			'terms' => [
				[
					'name' => 'show_pagination',
					'operator' => '!==',
					'value' => '',
				],
				[
					'name' => 'show_all_products',
					'operator' => '===',
					'value' => '',
				],
				[
					'name' => 'show_result_count',
					'operator' => '===',
					'value' => 'show',
				],
			],
		];

		$dropdown_conditions = [
			'relation' => 'and',
			'terms' => [
				[
					'name' => 'show_pagination',
					'operator' => '!==',
					'value' => '',
				],
				[
					'name' => 'show_all_products',
					'operator' => '===',
					'value' => '',
				],
				[
					'name' => 'allow_ordering',
					'operator' => '===',
					'value' => 'show',
				],
			],
		];

		$this->start_controls_section(
			'section_style_sorting_result_count',
			[
				'label' => esc_html__( 'Sorting & Result Count', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'show_pagination',
									'operator' => '!==',
									'value' => '',
								],
								[
									'name' => 'show_all_products',
									'operator' => '===',
									'value' => '',
								],
								[
									'name' => 'allow_ordering',
									'operator' => '===',
									'value' => 'show',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'show_pagination',
									'operator' => '!==',
									'value' => '',
								],
								[
									'name' => 'show_all_products',
									'operator' => '===',
									'value' => '',
								],
								[
									'name' => 'show_result_count',
									'operator' => '===',
									'value' => 'show',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'sorting_result_count_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px' ],
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '16',
					'left' => '0',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-ordering-result-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-result-count' => 'margin: 0 !important;',
					'{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-ordering' => 'margin: 0 !important;',
				],
			]
		);

		$this->add_control(
			'result_count_heading',
			[
				'label' => esc_html__( 'Result Count', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'conditions' => $result_count_conditions,
			]
		);

		$this->add_control(
			'result_count_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'a-b',
				'label_block' => true,
				'options' => [
					'a-b' => esc_html__( 'Showing A-B of X results', 'jupiterx-core' ),
					'x' => esc_html__( 'X Products', 'jupiterx-core' ),
				],
				'render_type' => 'template',
				'conditions' => $result_count_conditions,
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'result_count_typography',
				'selector' => '{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-result-count',
				'conditions' => $result_count_conditions,
			]
		);

		$this->add_control(
			'result_count_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-result-count' => 'color: {{VALUE}};',
				],
				'conditions' => $result_count_conditions,
			]
		);

		$this->add_control(
			'sorting_heading',
			[
				'label' => esc_html__( 'Sorting', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_responsive_control(
			'sorting_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-ordering select.orderby' => 'padding: {{TOP}}{{UNIT}} calc( {{RIGHT}}{{UNIT}} + 12px ) {{BOTTOM}}{{UNIT}} calc( {{LEFT}}{{UNIT}} + 25px ) !important;background-position: left {{LEFT}}{{UNIT}}  top 50% !important;',
					'{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-ordering:before' => 'right: {{RIGHT}}{{UNIT}};',
				],
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'sorting_typography',
				'selector' => '{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-ordering select.orderby',
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_control(
			'sorting_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-ordering select.orderby' => 'color: {{VALUE}};',
				],
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_control(
			'sorting_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'sorting_border',
				'selector' => '{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-ordering select.orderby',
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_responsive_control(
			'sorting_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-products-ordering-result-wrapper .woocommerce-ordering select.orderby' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_control(
			'dropdown_arrow_heading',
			[
				'label' => esc_html__( 'Dropdown Arrow', 'jupiterx-core' ),
				'type' => 'heading',
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_control(
			'dropdown_arrow_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .raven-products-ordering-wrapper svg' => 'fill: {{VALUE}};',
				],
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_responsive_control(
			'dropdown_arrow_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-ordering-wrapper svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $dropdown_conditions,
			]
		);

		$this->add_responsive_control(
			'dropdown_arrow_offset',
			[
				'label' => esc_html__( 'Horizontal Offset', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-products-ordering-wrapper svg' => 'transform: translateY(-50%) translateX({{SIZE}}{{UNIT}});',
				],
				'conditions' => $dropdown_conditions,
			]
		);

		$this->end_controls_section();
	}

	private function register_section_sale_badge() {
		$this->start_controls_section(
			'section_style_sale_badge',
			[
				'label' => esc_html__( 'Sale Badge', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => [
					'terms' => [
						[
							'name' => 'layout',
							'operator' => '==',
							'value' => 'custom',
						],
						[
							'name' => 'sale_badge',
							'operator' => '==',
							'value' => 'show',
						],
					],
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
				'selectors_dictionary' => [
					'left' => 'left: 0; right: auto;',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .onsale' => '{{VALUE}};',
				],
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
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .onsale',
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sale_badge_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .onsale' => 'color: {{VALUE}};border-color: transparent;',
				],
			]
		);

		$this->add_control(
			'sale_badge_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .onsale' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .onsale' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'sale_badge_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-wc-products-custom ul.products .onsale',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_wishlist() {
		$this->start_controls_section(
			'section_style_wishlist',
			[
				'label' => esc_html__( 'Wishlist', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => [
					'terms' => [
						[
							'name' => 'layout',
							'operator' => '==',
							'value' => 'custom',
						],
						[
							'name' => 'wishlist',
							'operator' => '==',
							'value' => 'show',
						],
					],
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
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist' => '{{VALUE}};',
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wishlist_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wishlist_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wishlist_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wishlist_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
				],
			]
		);

		$this->add_responsive_control(
			'wishlist_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
				],
			]
		);

		$this->add_responsive_control(
			'wishlist_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
				],
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .raven-wc-products-custom ul.products .jupiterx-wishlist' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 */
	private function register_section_quick_view() {
		$this->start_controls_section(
			'section_style_quick_view',
			[
				'label' => esc_html__( 'Quick View', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'quick_view' => 'show',
				],
			]
		);

		$this->add_control(
			'quick_view_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'default' => [
					'value' => 'fas fa-search-plus',
				],
			]
		);

		$this->add_control(
			'quick_view_position',
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
			]
		);

		$this->start_controls_tabs( 'tabs_quick_view' );

		$this->start_controls_tab(
			'tabs_quick_view_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'quick_view_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
			]
		);

		$this->add_control(
			'quick_view_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
			]
		);

		$this->add_control(
			'quick_view_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_quick_view_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'quick_view_color_hover',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
			]
		);

		$this->add_control(
			'quick_view_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
			]
		);

		$this->add_control(
			'quick_view_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'quick_view_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'dimensions',
				'separator' => 'before',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
			]
		);

		$this->add_responsive_control(
			'quick_view_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
			]
		);

		$this->add_responsive_control(
			'quick_view_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
			]
		);

		$this->add_responsive_control(
			'quick_view_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render() {
		$settings                 = $this->get_settings_for_display();
		$is_current_archive_query = 'current_archive_query' === $settings['query_filter'];

		if ( $is_current_archive_query && ! Module::is_editor_or_preview() && ! is_tax() ) {
			return;
		}

		$query         = Module::query( $this, $settings );
		$products      = $query->get_content();
		$query_results = $products['query_results'];
		$layout        = empty( $settings['layout'] ) ? 'default' : $settings['layout'];

		$loop_data = [
			'layout' => ! empty( $settings['layout'] ) ? $settings['layout'] : 'default',
			'total_pages' => 'yes' === $settings['show_pagination'] ? (int) $query_results->total_pages : 1,
			'image_size' => ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'woocommerce_thumbnail',
		];

		$query_array = wp_json_encode( (array) $query );
		?>
		<div
			class="raven-wc-products-wrapper raven-wc-products-<?php echo esc_attr( $layout ); ?>"
			data-settings="<?php echo esc_attr( wp_json_encode( $loop_data ) ); ?>"
			data-query="<?php echo esc_attr( $query_array ); ?>"
			<?php echo $this->archive_query_parameters(); ?>
		>
			<?php
				echo $this->get_widget_title( $settings, $query, $query_results );
				$disable_pagination = 1 === $query_results->total_pages ? true : false;
				Module::get_pagination( $settings, $disable_pagination );

				$content = $query->get_content();
				echo $content['data'];
			?>
		</div>
		<?php

		if ( 'custom' === $settings['layout'] ) {
			Module::remove_custom_layout_hooks( $settings );
		}

		Module::remove_custom_ordering_count();
	}

	private function get_widget_title( $settings, $query, $query_results ) {
		$text  = $settings['widget_title'];
		$total = ! empty( $query_results->total ) ? $query_results->total : 0;

		if ( ! empty( $query->fallback_filter ) ) {
			$text = $settings['widget_fallback_title'];
		}

		if ( 0 === (int) $total ) {
			return;
		}

		echo "<h2 class='raven-wc-products-title'>{$text}</h2>";
	}

	/**
	 * Return the taxonomy and term as an attribute for the widget.
	 *
	 * @return string
	 * @since 2.5.3
	 */
	private function archive_query_parameters() {
		global $wp_query;

		if ( ! is_archive() ) {
			return '';
		}

		$archive_query = $wp_query->get_queried_object();
		$json_query    = wp_json_encode( [
			'taxonomy' => ! empty( $archive_query->taxonomy ) ? $archive_query->taxonomy : '',
			'term'     => ! empty( $archive_query->slug ) ? $archive_query->slug : '',
		] );

		return sprintf( 'data-raven-archive-query="%s"', esc_attr( $json_query ) );
	}
}
