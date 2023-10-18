<?php
namespace WprAddons\Modules\WooGrid\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;
use WprAddons\Classes\Modules\WPR_Post_Likes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Woo_Grid extends Widget_Base {
	
	public function get_name() {
		return 'wpr-woo-grid';
	}

	public function get_title() {
		return esc_html__( 'Woo Grid/Slider/Carousel', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-gallery-grid';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_archive') ? [ 'wpr-woocommerce-builder-widgets' ] : ['wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'shop grid', 'product grid', 'woocommerce', 'product slider', 'product carousel', 'isotope', 'massonry grid', 'filterable grid' ];
	}

	public function get_script_depends() {
		return [ 'wpr-isotope', 'wpr-slick', 'wpr-lightgallery' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-woo-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_secondary_img_on_hover() {
		$this->add_control(
			'secondary_img_on_hover',
			[
				'label' => sprintf( __( '2nd Image on Hover %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

	public function add_control_open_links_in_new_tab() {
		$this->add_control(
			'open_links_in_new_tab',
			[
				'label' => sprintf( __( 'Open Links in New Tab %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

	public function add_control_query_selection() {
		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Query Products', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'wpr-addons' ),
					'manual' => esc_html__( 'Manual', 'wpr-addons' ),
					'current' => esc_html__( 'Current Query', 'wpr-addons' ),
					'pro-fr' => esc_html__( 'Featured (Pro)', 'wpr-addons' ),
					'pro-os' => esc_html__( 'On Sale (Pro)', 'wpr-addons' ),
					'pro-us' => esc_html__( 'Upsell (Pro)', 'wpr-addons' ),
					'pro-cs' => esc_html__( 'Cross-sell (Pro)', 'wpr-addons' ),
				],
			]
		);
	}
	
	public function add_control_query_orderby() {
		$this->add_control(
			'query_orderby',
			[
				'label' => esc_html__( 'Order By', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'wpr-addons' ),
					'sales' => esc_html__( 'Sales', 'wpr-addons' ),
					'rating' => esc_html__( 'Rating', 'wpr-addons' ),
					'price-low' => esc_html__( 'Price - Low to High', 'wpr-addons' ),
					'price-high' => esc_html__( 'Price - High to Low', 'wpr-addons' ),
					'pro-rn' => esc_html__( 'Random (Pro)', 'wpr-addons' ),
				],
				'condition' => [
					'query_selection' => [ 'dynamic', 'onsale', 'featured', 'upsell', 'cross-sell' ],
				],
			]
		);
	}
	
	public function add_control_layout_select() {
		$this->add_control(
			'layout_select',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fitRows',
				'options' => [
					'fitRows' => esc_html__( 'FitRows - Equal Height', 'wpr-addons' ),
					'list' => esc_html__( 'List Style', 'wpr-addons' ),
					'slider' => esc_html__( 'Slider / Carousel', 'wpr-addons' ),
					'pro-ms' => esc_html__( 'Masonry - Unlimited Height (Pro)', 'wpr-addons' ),
				],
				'label_block' => true
			]
		);
	}

	public function add_control_layout_columns() {
		$this->add_responsive_control(
			'layout_columns',
			[
				'label' => esc_html__( 'Columns', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 3,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'wpr-addons' ),
					2 => esc_html__( 'Two', 'wpr-addons' ),
					3 => esc_html__( 'Three', 'wpr-addons' ),
					'pro-4' => esc_html__( 'Four (Pro)', 'wpr-addons' ),
					'pro-5' => esc_html__( 'Five (Pro)', 'wpr-addons' ),
					'pro-6' => esc_html__( 'Six (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-columns-%s',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				]
			]
		);
	}
	
	public function add_control_layout_animation() {
		$this->add_control(
			'layout_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'zoom' => esc_html__( 'Zoom', 'wpr-addons' ),
					'pro-fd' => esc_html__( 'Fade (Pro)', 'wpr-addons' ),
					'pro-fs' => esc_html__( 'Fade + SlideUp (Pro)', 'wpr-addons' ),
				],
				'selectors_dictionary' => [
					'default' => '',
					'zoom' => 'opacity: 0; transform: scale(0.01)',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-inner' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);
	}
	
	public function add_control_sort_and_results_count() {}
	
	public function add_section_grid_sorting() {}
	
	public function add_section_style_sort_and_results() {}

	public function add_control_layout_slider_amount() {
		$this->add_responsive_control(
			'layout_slider_amount',
			[
				'label' => esc_html__( 'Columns (Carousel)', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 2,
				'widescreen_default' => 2,
				'laptop_default' => 2,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'wpr-addons' ),
					2 => esc_html__( 'Two', 'wpr-addons' ),
					'pro-3' => esc_html__( 'Three (Pro)', 'wpr-addons' ),
					'pro-4' => esc_html__( 'Four (Pro)', 'wpr-addons' ),
					'pro-5' => esc_html__( 'Five (Pro)', 'wpr-addons' ),
					'pro-6' => esc_html__( 'Six (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_layout_slider_nav_hover() {}
	
	public function add_control_layout_slider_dots_position() {
		$this->add_control(
			'layout_slider_dots_position',
			[
				'label' => esc_html__( 'Pagination Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'pro-vr' => esc_html__( 'Vertical (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-slider-dots-',
				'render_type' => 'template',
				'condition' => [
					'layout_slider_dots' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_stack_layout_slider_autoplay() {}

	public function add_option_element_select() {
		return [
			'title' => esc_html__( 'Title', 'wpr-addons' ),
			'excerpt' => esc_html__( 'Excerpt', 'wpr-addons' ),
			'product_cat' => esc_html__( 'Categories', 'wpr-addons' ),
			'product_tag' => esc_html__( 'Tags', 'wpr-addons' ),
			'pro-cfa' => esc_html__( 'Custom Fields/Attributes', 'wpr-addons' ),
			'status' => esc_html__( 'Status', 'wpr-addons' ),
			'price' => esc_html__( 'Price', 'wpr-addons' ),
			'pro-sd' => esc_html__( 'Sale Dates (Pro)', 'wpr-addons' ),
			'rating' => esc_html__( 'Rating', 'wpr-addons' ),
			'add-to-cart' => esc_html__( 'Add to Cart', 'wpr-addons' ),
			'pro-ws' => esc_html__( 'Wishlist Button (Expert)', 'wpr-addons' ),
			'pro-cm' => esc_html__( 'Compare Button (Expert)', 'wpr-addons' ),
			'lightbox' => esc_html__( 'Lightbox', 'wpr-addons' ),
			'separator' => esc_html__( 'Separator', 'wpr-addons' ),
			'pro-lk' => esc_html__( 'Likes (Pro)', 'wpr-addons' ),
			'pro-shr' => esc_html__( 'Sharing (Pro)', 'wpr-addons' ),
		];
	}

	public function add_repeater_args_element_custom_field() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_btn_link() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_new_tab() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_style() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_text() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_like_show_count() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_6() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_action() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_direction() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_tooltip() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_show_added_tc_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_show_added_to_wishlist_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_show_added_to_compare_popup() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_trim_text_by() {
		return [
			'word_count' => esc_html__( 'Word Count', 'wpr-addons' ),
			'pro-lc' => esc_html__( 'Letter Count (Pro)', 'wpr-addons' )
		];
	}
	
	public function add_control_overlay_animation_divider() {}
	
	public function add_control_overlay_image() {}
	
	public function add_control_overlay_image_width() {}
	
	public function add_control_image_effects() {
		$this->add_control(
			'image_effects',
			[
				'label' => esc_html__( 'Select Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'pro-zi' => esc_html__( 'Zoom In (Pro)', 'wpr-addons' ),
					'pro-zo' => esc_html__( 'Zoom Out (Pro)', 'wpr-addons' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'wpr-addons' ),
					'pro-go' => esc_html__( 'Grayscale Out (Pro)', 'wpr-addons' ),
					'blur-in' => esc_html__( 'Blur In', 'wpr-addons' ),
					'pro-bo' => esc_html__( 'Blur Out (Pro)', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'default' => 'none',
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails() {}
	
	public function add_control_lightbox_popup_thumbnails_default() {}
	
	public function add_control_lightbox_popup_sharing() {}
	
	public function add_control_filters_deeplinking() {}
	
	public function add_control_filters_animation() {
		$this->add_control(
			'filters_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'zoom' => esc_html__( 'Zoom', 'wpr-addons' ),
					'pro-fd' => esc_html__( 'Fade (Pro)', 'wpr-addons' ),
					'pro-fs' => esc_html__( 'Fade + SlideUp (Pro)', 'wpr-addons' ),
				],
				'separator' => 'before',
			]
		);
	}
	
	public function add_control_filters_icon() {}
	
	public function add_control_filters_icon_align() {}

	public function add_control_filters_count() {}
	
	public function add_control_filters_count_superscript() {}
	
	public function add_control_filters_count_brackets() {}
	
	public function add_control_filters_default_filter() {}
	
	public function add_control_pagination_type() {
		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'Select Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'load-more',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'numbered' => esc_html__( 'Numbered', 'wpr-addons' ),
					'load-more' => esc_html__( 'Load More Button', 'wpr-addons' ),
					'pro-is' => esc_html__( 'Infinite Scrolling (Pro)', 'wpr-addons' ),
				],
				'separator' => 'after'
			]
		);
	}

	public function add_section_added_to_cart_popup() {}
	
	public function add_section_style_likes() {}
	
	public function add_section_style_sharing() {}
	
	public function add_section_style_custom_field1() {}
	
	public function add_section_style_custom_field2() {}
	
	public function add_control_grid_item_even_bg_color() {}
	
	public function add_control_grid_item_even_border_color() {}
	
	public function add_control_overlay_color() {
		$this->add_control(
			'overlay_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.25)',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'background-color: {{VALUE}}',
				],
			]
		);
	}
	
	public function add_control_overlay_blend_mode() {}
	
	public function add_control_overlay_border_color() {}
	
	public function add_control_overlay_border_type() {}
	
	public function add_control_overlay_border_width() {}
	
	public function add_control_title_pointer_color_hr() {}
	
	public function add_control_title_pointer() {}
	
	public function add_control_title_pointer_height() {}
	
	public function add_control_title_pointer_animation() {}
	
	public function add_control_categories_pointer_color_hr() {}
	
	public function add_control_categories_pointer() {}
	
	public function add_control_categories_pointer_height() {}
	
	public function add_control_categories_pointer_animation() {}
	
	public function add_control_tags_pointer_color_hr() {}
	
	public function add_control_tags_pointer() {}
	
	public function add_control_tags_pointer_height() {}
	
	public function add_control_tags_pointer_animation() {}
	
	public function add_control_add_to_cart_animation() {}
	
	public function add_control_add_to_cart_animation_height() {}
	
	public function add_control_filters_pointer_color_hr() {}
	
	public function add_control_filters_pointer() {}
	
	public function add_control_filters_pointer_height() {}
	
	public function add_control_filters_pointer_animation() {}
	
	public function add_control_stack_grid_slider_nav_position() {}
	
	public function add_control_grid_slider_dots_hr() {}

	public function add_control_atc_popup_repeater() {

	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Query ------------
		$this->start_controls_section(
			'section_grid_query',
			[
				'label' => esc_html__( 'Query', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'woo_grid_notice_video_tutorial',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Build WooCommerce Shop <strong>completely with Elementor and Royal Addons !</strong> <ul><li><a href="https://www.youtube.com/watch?v=f_3tNiBC3dw" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'wpr-addons' ),
				'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control_query_selection();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'query_selection', ['pro-fr','pro-os','pro-us','pro-cs'] );

		$this->add_control_query_orderby();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'query_orderby', ['pro-rn'] );

		// Categories
		$this->add_control(
			'query_taxonomy_product_cat',
			[
				'label' => esc_html__( 'Categories', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_taxonomies',
				'query_slug' => 'product_cat',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection' => [ 'dynamic', 'onsale', 'featured' ],
				],
			]
		);

		// Tags
		$this->add_control(
			'query_taxonomy_product_tag',
			[
				'label' => esc_html__( 'Tags', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_taxonomies',
				'query_slug' => 'product_tag',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection' => [ 'dynamic', 'onsale', 'featured' ],
				],
			]
		);

		// Exclude
		$this->add_control(
			'query_exclude_products',
			[
				'label' => esc_html__( 'Exclude Products', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_posts_by_post_type',
				'query_slug' => 'product',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection!' => [ 'manual', 'onsale', 'current', 'upsell', 'cross-sell' ],
				],
			]
		);

		// Manual Selection
		$this->add_control(
			'query_manual_products',
			[
				'label' => esc_html__( 'Select Products', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_posts_by_post_type',
				'query_slug' => 'product',
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_selection' => 'manual',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'query_posts_per_page',
			[
				'label' => esc_html__( 'Products Per Page', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 9,
				'min' => 0,
				'condition' => [
					'query_selection!' => 'current',
				],
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label' => esc_html__( 'Offset', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'condition' => [
					'query_selection' => [ 'dynamic', 'current' ],
				]
			]
		);

		$this->add_control(
			'query_not_found_text',
			[
				'label' => esc_html__( 'Not Found Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No Posts Found!',
				'condition' => [
					'query_selection' => [ 'dynamic', 'current' ],
				]
			]
		);

		$this->add_control(
			'query_randomize',
			[
				'label' => esc_html__( 'Randomize Query', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'rand',
				'condition' => [
					'query_selection' => [ 'manual', 'current' ],
				]
			]
		);

		$this->add_control(
			'query_exclude_no_images',
			[
				'label' => esc_html__( 'Exclude Items without Thumbnail', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);		

		$this->add_control(
			'query_exclude_out_of_stock',
			[
				'label' => esc_html__( 'Exclude Out Of Stock', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$this->add_control(
			'current_query_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf( __( 'To set <strong>Posts per Page</strong> for all <strong>Shop Pages</strong>, navigate to <strong><a href="%s" target="_blank">Royal Addons > Settings<a></strong>.', 'wpr-addons' ), admin_url( '?page=wpr-addons&tab=wpr_tab_settings' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'query_selection' => 'current',
				],
			]
		);

		// if ( Utilities::is_new_free_user() && ! wpr_fs()->can_use_premium_code() ) {
		// 	$this->add_control(
		// 		'limit_grid_items_pro_notice',
		// 		[
		// 			'type' => Controls_Manager::RAW_HTML,
		// 			'raw' => 'More than <strong>12 Items</strong> in total<br> are available in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-woo-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
		// 			// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
		// 			'content_classes' => 'wpr-pro-notice',
		// 		]
		// 	);
		// }

		// $this->add_control(
		// 	'post_meta_keys_filter',
		// 	[
		// 		'type' => Controls_Manager::HIDDEN,
		// 		'default' => json_encode( $post_meta_keys[0] ),
		// 	]
		// );

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Layout -----------
		$this->start_controls_section(
			'section_grid_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_layout_select();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_select', ['pro-ms'] );

		$this->add_control(
			'stick_last_element_to_bottom',
			[
				'label' => esc_html__( 'Last Element to Bottom', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'render_type' => 'template',
				// 'separator' => 'before',
				'condition' => [
					'layout_select' => 'fitRows',
				]
			]
		);

		$this->add_control(
            'last_element_position',
            [
                'label' => esc_html__( 'Last Element Position', 'wpr-addons' ),
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
				'selectors_dictionary' => [
					'left' => 'left: 0; right: auto;',
					'center' => 'left: 50%; transform: translateX(-50%);',
					'right' => 'left: auto; right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-grid-last-element-yes .wpr-grid-item-below-content>div:last-child' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'layout_image_crop',
				'default' => 'full',
			]
		);

		$this->add_control_layout_columns();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'grid_columns_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Grid Columns</span> option is fully supported<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-woo-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Grid Columns</span> option is fully supported<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		// Media
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Media', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'list',
				],
			]
		);

		$this->add_control(
			'layout_list_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'zigzag' => esc_html__( 'ZigZag', 'wpr-addons' ),
				],
				'condition' => [
					'layout_select' => 'list',
				],
			]
		);

		$this->add_control(
			'layout_list_media_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout_select' => 'list',
				]
			]
		);

		$this->add_control(
			'layout_list_media_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout_select' => 'list',
				]
			]
		);

		$this->add_responsive_control(
			'layout_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'widescreen_default' => [
					'size' => 20,
				],
				'laptop_default' => [
					'size' => 20,
				],
				'tablet_extra_default' => [
					'size' => 20,
				],
				'tablet_default' => [
					'size' => 20,
				],
				'mobile_extra_default' => [
					'size' => 20,
				],
				'mobile_default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'layout_gutter_vr',
			[
				'label' => esc_html__( 'Vertical Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'widescreen_default' => [
					'size' => 30,
				],
				'laptop_default' => [
					'size' => 30,
				],
				'tablet_extra_default' => [
					'size' => 30,
				],
				'tablet_default' => [
					'size' => 30,
				],
				'mobile_extra_default' => [
					'size' => 30,
				],
				'mobile_default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				],
			]
		);

		$this->add_control_sort_and_results_count();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'sort_and_results_count_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Grid Sorting</span> option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-woo-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_responsive_control(
			'layout_filters',
			[
				'label' => esc_html__( 'Show Filters', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters' => 'display:{{VALUE}};',
				],
				'render_type' => 'template',
				// 'separator' => 'before',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);

		$this->add_control(
			'layout_pagination',
			[
				'label' => esc_html__( 'Show Pagination', 'wpr-addons' ),
				'description' => esc_html__('Please note that Pagination doesn\'t work in editor', 'wpr-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'render_type' => 'template',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);

		$this->add_control_open_links_in_new_tab();

		$this->add_control_layout_animation();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_animation', ['pro-fd', 'pro-fs'] );

		$this->add_control(
			'layout_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'layout_animation!' => 'default',
					'layout_select!' => 'slider',
				],
			]
		);

		$this->add_control(
			'layout_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.05,
				'condition' => [
					'layout_animation!' => 'default',
					'layout_select!' => 'slider',
				],
			]
		);

		$this->add_control_layout_slider_amount();

		$this->add_control(
			'layout_slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'frontend_available' => true,
				'default' => 2,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],			
				'selectors' => [
					'{{WRAPPER}} .wpr-grid .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'layout_slider_amount!' => '1',
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'display:{{VALUE}} !important;',
				],
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				]
			]
		);

		$this->add_control_layout_slider_nav_hover();

		$this->add_control(
			'layout_slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'separator' => 'after',
				'condition' => [
					'layout_slider_nav' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_responsive_control(
			'layout_slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-table'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-dots' => 'display:{{VALUE}};',
				],
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_control_layout_slider_dots_position();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'layout_slider_dots_position', ['pro-vr'] );

		$this->add_control_stack_layout_slider_autoplay();

		$this->add_control(
			'layout_slider_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'after',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
		
		$this->add_control(
			'layout_slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'wpr-addons' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
				],
				'condition' => [
					'layout_slider_amount' => 1,
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'layout_slider_effect_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'layout_slider_amount' => 1,
					'layout_select' => 'slider',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Upsell / Cross-sell Title
		$this->start_controls_section(
			'section_grid_linked_products',
			[
				'label' => esc_html__( 'Upsell / Cross-sell Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'query_selection' => ['upsell', 'cross-sell'],
					// 'layout_select!' => 'slider'
				]
			]
		);

		$this->add_control(
			'grid_linked_products_heading',
			[
				'label' => esc_html__( 'Heading', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'You may be interested in...',
				'condition' => [
					'query_selection' => ['upsell', 'cross-sell'],
				]
			]
		);

		$this->add_control(
			'grid_linked_products_heading_tag',
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
				'default' => 'h2',
				'condition' => [
					'query_selection' => ['upsell'],
					'grid_linked_products_heading!' => ''
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_grid_elements',
			[
				'label' => esc_html__( 'Elements', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$element_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => $element_select,
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'wishlist_compare_video_tutorial',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( '<ul><li><a href="https://www.youtube.com/watch?v=wis1rQTn1tg" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'wpr-addons' ),
				'separator' => 'after',
				// 'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'element_select' => ['wishlist-button', 'compare-button']
				]
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_select', ['pro-lk', 'pro-shr', 'pro-sd'] );

		Utilities::upgrade_expert_notice( $repeater, Controls_Manager::RAW_HTML, 'grid', 'element_select', ['pro-ws', 'pro-cm'] );

		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'above' => esc_html__( 'Above Media', 'wpr-addons' ),
					'over' => esc_html__( 'Over Media', 'wpr-addons' ),
					'below' => esc_html__( 'Below Media', 'wpr-addons' ),
				]
			]
		);

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'block' => esc_html__( 'Seperate Line', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom Width', 'wpr-addons' ),
				],
			]
		);

		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$repeater->add_control(
	            'element_align_pro_notice',
	            [
					'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-woo-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'Vertical Align option is available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'element_location' => 'over',
					],
				]
	        );
		}

		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
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
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$repeater->add_control(
			'element_title_tag',
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
				'default' => 'h2',
				'condition' => [
					'element_select' => 'title',
				]
			]
		);

		$repeater->add_control(
			'element_trim_text_by',
			[
				'label' => esc_html__( 'Trim Text By', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'word_count',
				'options' => $this->add_repeater_args_element_trim_text_by(),
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
				]
			]
		);

		$repeater->add_control(
			'element_word_count',
			[
				'label' => esc_html__( 'Word Count', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
					'element_trim_text_by' => 'word_count'
				]
			]
		);

		$repeater->add_control(
			'element_letter_count',
			[
				'label' => esc_html__( 'Letter Count', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 40,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'title', 'excerpt' ],
					'element_trim_text_by' => 'letter_count'
				]
			]
		);

		$repeater->add_control(
			'element_tax_sep',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => ', ',
				'condition' => [
					'element_select!' => [
						'title',
						'likes',
						'sharing',
						'lightbox',
						'separator',
						'post_format',
						'status',
						'price',
						'rating',
						'add-to-cart',
						'wishlist-button',
						'compare-button'
					],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_sale_dates_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'block' => esc_html__( 'Block', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => [
						'sale_dates',
					]
				]
			]
		);

		$repeater->add_control(
			'element_sale_dates_sep',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => ' - ',
				'condition' => [
					'element_select' => [
						'sale_dates',
					],
					'element_sale_dates_layout' => 'inline'
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control( 'element_custom_field', $this->add_repeater_args_element_custom_field() );

		$repeater->add_control( 'element_custom_field_btn_link', $this->add_repeater_args_element_custom_field_btn_link() );

		$repeater->add_control( 'element_custom_field_style', $this->add_repeater_args_element_custom_field_style() );

		$repeater->add_control( 'element_custom_field_new_tab', $this->add_repeater_args_element_custom_field_new_tab() );

		$repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		$repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		$repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

		$repeater->add_control( 'element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1() );

		$repeater->add_control( 'element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2() );

		$repeater->add_control( 'element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3() );

		$repeater->add_control( 'element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4() );

		$repeater->add_control( 'element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5() );

		$repeater->add_control( 'element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6() );

		$repeater->add_control( 'element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger() );

		$repeater->add_control( 'element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon() );

		$repeater->add_control( 'element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action() );

		$repeater->add_control( 'element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction() );

		$repeater->add_control( 'element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip() );

		$repeater->add_control(
			'element_lightbox_pfa_select',
			[
				'label' => esc_html__( 'Post Format Audio', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfa_meta',
			[
				'label' => esc_html__( 'Audio Meta Value', 'wpr-addons' ),
				// 'type' => Controls_Manager::SELECT2,
				'type' => 'wpr-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				// 'options' => $post_meta_keys[1],
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'query_slug' => 'product_cat',
				'condition' => [
					'element_select' => 'lightbox',
					'element_lightbox_pfa_select' => 'meta',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_select',
			[
				'label' => esc_html__( 'Post Format Video', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_meta',
			[
				'label' => esc_html__( 'Video Meta Value', 'wpr-addons' ),
				// 'type' => Controls_Manager::SELECT2,
				'type' => 'wpr-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				// 'options' => $post_meta_keys[1],
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'query_slug' => 'product_cat',
				'condition' => [
					'element_select' => 'lightbox',
					'element_lightbox_pfv_select' => 'meta',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'lightbox' ],
				],
			]
		);

		$repeater->add_control(
			'element_separator_style',
			[
				'label' => esc_html__( 'Select Styling', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'wpr-grid-sep-style-1',
				'options' => [
					'wpr-grid-sep-style-1' => esc_html__( 'Separator Style 1', 'wpr-addons' ),
					'wpr-grid-sep-style-2' => esc_html__( 'Separator Style 2', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => 'separator',
				]
			]
		);

		$repeater->add_control(
			'element_rating_style',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => 'Icon 1',
					'style-2' => 'Icon 2',
				],
				'default' => 'style-2',
				'condition' => [
					'element_select' => 'rating',
				],
			]
		);

		$repeater->add_control(
			'element_rating_score',
			[
				'label' => esc_html__( 'Show Score', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => 'rating',
				],
			]
		);

		$repeater->add_control(
			'element_rating_unmarked_style',
			[
				'label' => esc_html__( 'Unmarked Style', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'wpr-addons' ),
						'icon' => 'eicon-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'wpr-addons' ),
						'icon' => 'eicon-star-o',
					],
				],
				'default' => 'outline',
				'condition' => [
					'element_select' => 'rating',
					'element_rating_score!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'element_status_offstock',
			[
				'label' => esc_html__( 'Show Out of Stock Badge', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => 'status',
				],
			]
		);

		$repeater->add_control(
			'element_status_featured',
			[
				'label' => esc_html__( 'Show Featured Badge', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_select' => 'status',
				],
			]
		);

		$repeater->add_control(
			'element_addcart_simple_txt',
			[
				'label' => esc_html__( 'Simple Item Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Add to Cart',
				'condition' => [
					'element_select' => 'add-to-cart',
				]
			]
		);

		$repeater->add_control(
			'element_addcart_grouped_txt',
			[
				'label' => esc_html__( 'Grouped Item Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Select Options',
				'condition' => [
					'element_select' => 'add-to-cart',
				]
			]
		);

		$repeater->add_control(
			'element_addcart_variable_txt',
			[
				'label' => esc_html__( 'Variable Item Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'View Products',
				'separator' => 'after',
				'condition' => [
					'element_select' => 'add-to-cart',
				]
			]
		);

		$repeater->add_control( 'element_show_added_tc_popup', $this->add_repeater_args_element_show_added_tc_popup() );

		$repeater->add_control( 'element_show_added_to_wishlist_popup', $this->add_repeater_args_element_show_added_to_wishlist_popup() );

		$repeater->add_control( 'element_show_added_to_compare_popup', $this->add_repeater_args_element_show_added_to_compare_popup() );

		$repeater->add_control(
			'element_open_links_in_new_tab',
			[
				'label' => esc_html__( 'Open Links in New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'element_select' => ['wishlist-button', 'compare-button']
				]
			]
		);

		$repeater->add_control(
			'element_extra_text_pos',
			[
				'label' => esc_html__( 'Extra Text Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'status',
						'price',
						'sale_dates',
						'rating',
						'add-to-cart',
						'wishlist-button',
						'compare-button',
						'excerpt'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'status',
						'price',
						'sale_dates',
						'rating',
						'add-to-cart',
						'wishlist-button',
						'compare-button',
						'excerpt'
					],
					'element_extra_text_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'show_sale_starts_date',
			[
				'label' => esc_html__( 'Sale Starts Date', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [
						'sale_dates'
					]
				],
			]
		);

		$repeater->add_control(
			'element_sale_starts_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'element_select' => [
						'sale_dates'
					],
					'show_sale_starts_date' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'show_sale_ends_date',
			[
				'label' => esc_html__( 'Sale Ends Date', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [
						'sale_dates'
					]
				],
			]
		);

		$repeater->add_control(
			'element_sale_ends_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'element_select' => [
						'sale_dates'
					],
					'show_sale_ends_date' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon_pos',
			[
				'label' => esc_html__( 'Extra Icon Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'likes',
						'sharing',
						'status',
						'price',
						'sale_dates',
						'rating',
						'excerpt',
						'wishlist-button',
						'compare-button'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select!' => [
						'title',
						'separator',
						'likes',
						'sharing',
						'status',
						'price',
						'rating',
						'wishlist-button',
						'compare-button'
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'element_select' => [
						'wishlist-button',
						'compare-button'
					]
				]
			]
		);

		$repeater->add_control(
			'show_text',
			[
				'label' => esc_html__( 'Show Text', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [
						'wishlist-button',
						'compare-button'
					]
				]
			]
		);

		$repeater->add_control(
			'add_to_wishlist_text',
			[
				'label' => esc_html__( 'Add Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Add to Wishlist',
				'condition' => [
					'element_select' => [
						'wishlist-button'
					]
				]
			]
		);

		$repeater->add_control(
			'add_to_compare_text',
			[
				'label' => esc_html__( 'Add Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Add to Compare',
				'condition' => [
					'element_select' => [
						'compare-button'
					]
				]
			]
		);

		$repeater->add_control(
			'remove_from_wishlist_text',
			[
				'label' => esc_html__( 'Remove Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Remove from Wishlist',
				'condition' => [
					'element_select' => [
						'wishlist-button'
					]
				]
			]
		);

		$repeater->add_control(
			'remove_from_compare_text',
			[
				'label' => esc_html__( 'Remove Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Remove from Compare',
				'condition' => [
					'element_select' => [
						'compare-button'
					]
				]
			]
		);

		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt',] );

		$repeater->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
			'element_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'woo-grid', 'element_animation_timing', Utilities::wpr_animation_timing_pro_conditions() );

		$repeater->add_control(
			'element_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'large',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_disable_mobile',
			[
				'label' => esc_html__( 'Disable on Mobile/Tablet', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_responsive_control(
			'element_show_on',
			[
				'label' => esc_html__( 'Show on this Device', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'grid_elements',
			[
				'label' => esc_html__( 'Grid Elements', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => 'status',
						'element_location' => 'over',
						'element_align_vr' => 'middle',
						'element_align_hr' => 'middle',
						'element_animation' => 'fade-in',
					],
					[
						'element_select' => 'product_cat',
					],
					[
						'element_select' => 'title',
					],
					[
						'element_select' => 'rating',
					],
					[
						'element_select' => 'price',
					],
					[
						'element_select' => 'add-to-cart',
					],
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Overlay Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Overlay Hegiht', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-grid-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-grid-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-grid-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-grid-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_post_link',
			[
				'label' => esc_html__( 'Link to Single Page', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'fade-in',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt',] );

		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-animation-wrap:hover .wpr-grid-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'overlay_animation_timing', Utilities::wpr_animation_timing_pro_conditions() );

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control_overlay_animation_divider();

		$this->add_control_overlay_image();

		$this->add_control_overlay_image_width();

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Image Effects ----
		$this->start_controls_section(
			'section_image_effects',
			[
				'label' => esc_html__( 'Image Effects', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_secondary_img_on_hover();

		$this->add_control(
			'secondary_img_notice_video_tutorial',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( '<ul><li><a href="https://youtu.be/-Xfbtkl4fkQ" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'wpr-addons' ),
				'separator' => 'after',
				// 'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control_image_effects();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'] );

		$this->add_control(
			'image_effects_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-wrap img' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-wrap:hover img' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'image_effects_animation_timing', Utilities::wpr_animation_timing_pro_conditions() );

		$this->add_control(
			'image_effects_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'medium',
				'condition' => [
					'image_effects!' => ['none', 'slide'],
				]
			]
		);

		$this->add_control(
			'image_effects_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
				],
				'default' => 'bottom',
				'condition' => [
					'image_effects!' => 'none',
					'image_effects' => 'slide'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Lightbox Popup ----
		$this->start_controls_section(
			'section_lightbox_popup',
			[
				'label' => esc_html__( 'Lightbox Popup', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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

		$this->add_control_lightbox_popup_thumbnails();

		$this->add_control_lightbox_popup_thumbnails_default();

		$this->add_control_lightbox_popup_sharing();

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

		$this->add_control(
			'lightbox_popup_description',
			[
				'raw' => sprintf(__( 'You can change Lightbox Popup styling options globaly. Navigate to <strong>Dashboard > %s > Settings</strong>.', 'wpr-addons' ), Utilities::get_plugin_name()),
				'type' => Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->add_section_grid_sorting();

		// Tab: Content ==============
		// Section: Filters ----------
		$this->start_controls_section(
			'section_grid_filters',
			[
				'label' => esc_html__( 'Filters', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_filters' => 'yes',
				],
			]
		);

		$this->add_control(
			'filters_select',
			[
				'label' => esc_html__( 'Select Taxonomy', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'product_cat' => esc_html__( 'Categories', 'wpr-addons' ),
					'product_tag' => esc_html__( 'Tags', 'wpr-addons' ),
				],
				'default' => 'product_cat',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'filters_linkable',
			[
				'label' => esc_html__( 'Set Linkable Filters', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'filters_hide_empty',
			[
				'label' => esc_html__( 'Hide Empty Filters', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control_filters_deeplinking();

		$this->add_control(
			'filters_all',
			[
				'label' => esc_html__( 'Show "All" Filter', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control(
			'filters_all_text',
			[
				'label' => esc_html__( '"All" Filter Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'All',
				'condition' => [
					'filters_all' => 'yes',
					'filters_linkable!' => 'yes',
				],
			]
		);

		$this->add_control_filters_count();

		$this->add_control_filters_count_superscript();

		$this->add_control_filters_count_brackets();

		$this->add_control_filters_default_filter();

		$this->add_control_filters_icon();

		$this->add_control_filters_icon_align();

		$this->add_control(
			'filters_separator',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'filters_separator_align',
			[
				'label' => esc_html__( 'Separator Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'condition' => [
					'filters_separator!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'filters_align',
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
					'{{WRAPPER}} .wpr-grid-filters' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_filters_animation();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'filters_animation', ['pro-fd', 'pro-fs'] );

		$this->add_control(
			'filters_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'condition' => [
					'filters_animation!' => 'default',
				],
			]
		);

		$this->add_control(
			'filters_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.05,
				'condition' => [
					'filters_animation!' => 'default'
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Pagination -------
		$this->start_controls_section(
			'section_grid_pagination',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_pagination' => 'yes',
				],
			]
		);

		$this->add_control_pagination_type();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'woo-grid', 'pagination_type', ['pro-is'] );

		$this->add_control(
			'pagination_older_text',
			[
				'label' => esc_html__( 'Older Posts Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Older Posts',
				'condition' => [
					'pagination_type' => 'default',
				],
			]
		);

		$this->add_control(
			'pagination_newer_text',
			[
				'label' => esc_html__( 'Newer Posts Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Newer Posts',
				'condition' => [
					'pagination_type' => 'default',
				]
			]
		);

		$this->add_control(
			'pagination_on_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'condition' => [
					'pagination_type' => 'default'
				],
			]
		);

		$this->add_control(
			'pagination_prev_next',
			[
				'label' => esc_html__( 'Previous & Next Buttons', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => 'numbered',
				],
			]
		);

		$this->add_control(
			'pagination_prev_text',
			[
				'label' => esc_html__( 'Prev Page Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Previous Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_next_text',
			[
				'label' => esc_html__( 'Next Page Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Next Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes',
				]
			]
		);

		$this->add_control(
			'pagination_pn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_prev_next' => 'yes'
				],
			]
		);

		$this->add_control(
			'pagination_first_last',
			[
				'label' => esc_html__( 'First & Last Buttons', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => 'numbered',
				],
			]
		);

		$this->add_control(
			'pagination_first_text',
			[
				'label' => esc_html__( 'First Page Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'First Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_last_text',
			[
				'label' => esc_html__( 'Last Page Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Last Page',
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes',
				]
			]
		);

		$this->add_control(
			'pagination_fl_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'condition' => [
					'pagination_type' => 'numbered',
					'pagination_first_last' => 'yes'
				],
			]
		);

		$this->add_control(
			'pagination_disabled_arrows',
			[
				'label' => esc_html__( 'Show Disabled Buttons', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => [ 'default', 'numbered' ],
				],
			]
		);

		$this->add_control(
			'pagination_range',
			[
				'label' => esc_html__( 'Range', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'min' => 1,
				'condition' => [
					'pagination_type' => 'numbered',
				]
			]
		);

		$this->add_control(
			'pagination_load_more_text',
			[
				'label' => esc_html__( 'Load More Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Load More',
				'condition' => [
					'pagination_type' => 'load-more',
				]
			]
		);

		$this->add_control(
			'pagination_finish_text',
			[
				'label' => esc_html__( 'Finish Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'End of Content.',
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
			]
		);

		$this->add_control(
			'pagination_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'loader-1',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'loader-1' => esc_html__( 'Loader 1', 'wpr-addons' ),
					'loader-2' => esc_html__( 'Loader 2', 'wpr-addons' ),
					'loader-3' => esc_html__( 'Loader 3', 'wpr-addons' ),
					'loader-4' => esc_html__( 'Loader 4', 'wpr-addons' ),
					'loader-5' => esc_html__( 'Loader 5', 'wpr-addons' ),
					'loader-6' => esc_html__( 'Loader 6', 'wpr-addons' ),
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'prefix_class' => 'wpr-grid-pagination-',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'pagination_type!' => 'infinite-scroll',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'woo-grid', [
			'Grid Columns 1,2,3,4,5,6',
			'Masonry Layout',
			'Products Slider Columns (Carousel) 1,2,3,4,5,6',
			'Secondary Featured Image',
			'Current Page Query, Random Products Query',
			'Infinite Scrolling Pagination',
			'Products Slider Autoplay options',
			'Products Slider Advanced Navigation Positioning',
			'Products Slider Advanced Pagination Positioning',
			'Advanced Products Likes',
			'Advanced Products Sharing',
			'Advanced Grid Loading Animations (Fade in & Slide Up)',
			'Advanced Grid Elements Positioning',
			'Unlimited Image Overlay Animations',
			'Image overlay GIF upload option',
			'Image Overlay Blend Mode',
			'Image Effects: Zoom, Grayscale, Blur',
			'Lightbox Thumbnail Gallery, Lightbox Image Sharing Button',
			'Grid Category Filter Deeplinking',
			'Grid Category Filter Icons select',
			'Grid Category Filter Count',
			'Grid Item Even/Odd Background Color',
			'Title, Category, Read More Advanced Link Hover Animation',
			'Open Links in New Tab',
			'Custom Fields/Attributes Support (Expert)',
			'Wishlist & Compare Buttons (Expert)'
		] );
		
		// Styles ====================
		// Section: Grid Item --------
		$this->start_controls_section(
			'section_style_grid_item',
			[
				'label' => esc_html__( 'Grid Item', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'grid_item_styles_selector',
			[
				'label' => esc_html__( 'Apply Styles To', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inner' => esc_html__( 'Inner Elements', 'wpr-addons' ),
					'wrapper' => esc_html__( 'Wrapper', 'wpr-addons' )
				],
				'default' => 'inner',
				'prefix_class' => 'wpr-item-styles-'
			]
		);

		$this->add_control(
			'grid_item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-above-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-item-below-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-item-styles-wrapper .wpr-grid-item' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'grid_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.wpr-item-styles-inner .wpr-grid-item-above-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-item-styles-inner .wpr-grid-item-below-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-item-styles-wrapper .wpr-grid-item' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control_grid_item_even_bg_color();

		$this->add_control_grid_item_even_border_color();

		$this->add_control(
			'grid_item_border_type',
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
					'{{WRAPPER}}.wpr-item-styles-inner .wpr-grid-item-above-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.wpr-item-styles-inner .wpr-grid-item-below-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.wpr-item-styles-wrapper .wpr-grid-item' => 'border-style: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_item_border_width',
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
					'{{WRAPPER}}.wpr-item-styles-inner .wpr-grid-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-item-styles-inner .wpr-grid-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-item-styles-wrapper .wpr-grid-item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'grid_item_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'grid_item_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-item-styles-wrapper .wpr-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'grid_item_radius',
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
					'{{WRAPPER}} .wpr-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-above-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-below-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'grid_item_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-item',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Grid Media -------
		$this->start_controls_section(
			'section_style_grid_media',
			[
				'label' => esc_html__( 'Grid Media', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'grid_media_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-image-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'grid_media_border_type',
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
					'{{WRAPPER}} .wpr-grid-image-wrap' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_media_border_width',
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
					'{{WRAPPER}} .wpr-grid-image-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'grid_media_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_media_radius',
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
					'{{WRAPPER}} .wpr-grid-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_control_overlay_color();

		$this->add_control_overlay_blend_mode();

		$this->add_control_overlay_border_color();

		$this->add_control_overlay_border_type();

		$this->add_control_overlay_border_width();

		$this->add_control(
			'overlay_radius',
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
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_title_style' );

		$this->start_controls_tab(
			'tab_grid_title_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_title_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'title_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_title_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_title_pointer();

		$this->add_control_title_pointer_height();

		$this->add_control_title_pointer_animation();

		$this->add_control(
			'title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-item-title .wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-item-title .wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-title a'
			]
		);

		$this->add_control(
			'title_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'title_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Excerpt ----------
		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'excerpt_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'excerpt_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-excerpt'
			]
		);

		$this->add_responsive_control(
			'excerpt_justify',
			[
				'label' => esc_html__( 'Justify Text', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => '',
				'laptop_default' => '',
				'tablet_extra_default' => '',
				'tablet_default' => '',
				'mobile_extra_default' => '',
				'mobile_default' => '',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'text-align: justify;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'excerpt_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'excerpt_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'excerpt_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Categories -------
		$this->start_controls_section(
			'section_style_categories',
			[
				'label' => esc_html__( 'Categories', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_categories_style' );

		$this->start_controls_tab(
			'tab_grid_categories_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'categories_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'categories_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block [class*="wpr-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block [class*="wpr-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_categories_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'categories_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'categories_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'categories_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_categories_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_categories_pointer();

		$this->add_control_categories_pointer_height();

		$this->add_control_categories_pointer_animation();

		$this->add_control(
			'categories_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'categories_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-product-categories'
			]
		);

		$this->add_control(
			'categories_border_type',
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
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'categories_border_width',
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
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'categories_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'categories_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'categories_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'categories_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'categories_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'categories_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'categories_radius',
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
					'{{WRAPPER}} .wpr-grid-product-categories .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Tags -------------
		$this->start_controls_section(
			'section_style_tags',
			[
				'label' => esc_html__( 'Tags', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_tags_style' );

		$this->start_controls_tab(
			'tab_grid_tags_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tags_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tags_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tags_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tags_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tags_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block [class*="wpr-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block [class*="wpr-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_tags_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tags_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tags_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tags_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_tags_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_tags_pointer();

		$this->add_control_tags_pointer_height();

		$this->add_control_tags_pointer_animation();

		$this->add_control(
			'tags_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tags_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-product-tags'
			]
		);

		$this->add_control(
			'tags_border_type',
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
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tags_border_width',
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
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'tags_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tags_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tags_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tags_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tags_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'tags_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tags_radius',
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
					'{{WRAPPER}} .wpr-grid-product-tags .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Rating -----------
		$this->start_controls_section(
			'section_style_product_rating',
			[
				'label' => esc_html__( 'Rating', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'product_rating_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D2CDCD',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_score_color',
			[
				'label' => esc_html__( 'Score Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-woo-rating span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_rating_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-woo-rating span'
			]
		);

		$this->add_responsive_control(
			'product_rating_margin',
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
					'{{WRAPPER}} .wpr-grid-item-rating .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Status -----------
		$this->start_controls_section(
			'section_style_product_status',
			[
				'label' => esc_html__( 'Status', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'product_status_os_color',
			[
				'label'  => esc_html__( 'On Sale Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-onsale' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_os_bg_color',
			[
				'label'  => esc_html__( 'On Sale BG Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-onsale' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_os_border_color',
			[
				'label'  => esc_html__( 'On Sale Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-onsale' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'product_status_ft_color',
			[
				'label'  => esc_html__( 'Featured Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-featured' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_ft_bg_color',
			[
				'label'  => esc_html__( 'Featured BG Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-featured' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_ft_border_color',
			[
				'label'  => esc_html__( 'Featured Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-featured' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'product_status_oos_color',
			[
				'label'  => esc_html__( 'Out of Stock Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-outofstock' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_oos_bg_color',
			[
				'label'  => esc_html__( 'Out of Stock BG Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-outofstock' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_status_oos_border_color',
			[
				'label'  => esc_html__( 'Out of Stock Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > .wpr-woo-outofstock' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_status_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-status .inner-block > span'
			]
		);

		$this->add_control(
			'product_status_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_status_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_status_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'product_status_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 10,
					'bottom' => 3,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'product_status_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 5,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'product_status_radius',
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
					'{{WRAPPER}} .wpr-grid-item-status .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'product_status_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-item-status .inner-block > span',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Price ------------
		$this->start_controls_section(
			'section_style_product_price',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_price_old_color',
			[
				'label'  => esc_html__( 'Old Price Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span del' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_price_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_price_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_price_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-price .inner-block > span'
			]
		);

		$this->add_control(
			'product_price_old_font_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Old Price Font Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span del' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'product_price_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_price_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_price_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'product_price_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'product_price_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'product_price_radius',
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
					'{{WRAPPER}} .wpr-grid-item-price .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'product_price_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-item-price .inner-block > span',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Sale Dates ------------
		$this->start_controls_section(
			'section_style_product_sale_dates',
			[
				'label' => esc_html__( 'Sale Dates', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_control(
			'product_sale_dates_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'product_sale_dates_old_color',
			[
				'label'  => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block span.wpr-grid-extra-text-left' => 'color: {{VALUE}} !important',
				],
			]
		);
		
		$this->add_control(
			'product_sale_dates_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > span' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'product_sale_dates_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > span.wpr-sale-dates' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_sale_dates_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > .wpr-sale-dates'
			]
		);
		
		$this->add_control(
			'product_sale_dates_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > .wpr-sale-dates' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'product_sale_dates_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > .wpr-sale-dates' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_sale_dates_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);
		
		$this->add_responsive_control(
			'product_sale_dates_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > .wpr-sale-dates' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'product_sale_dates_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > .wpr-sale-dates' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);
		
		$this->add_control(
			'product_sale_dates_radius',
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
					'{{WRAPPER}} .wpr-grid-item-sale_dates .inner-block > .wpr-sale-dates' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		
		$this->end_controls_section();

		// Styles ====================
		// Section: Add to Cart ------
		$this->start_controls_section(
			'section_style_add_to_cart',
			[
				'label' => esc_html__( 'Add to Cart', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_grid_add_to_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_add_to_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a.wpr-button-none:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a.added_to_cart:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a:after' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'add_to_cart_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control_add_to_cart_animation();

		$this->add_control(
			'add_to_cart_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control_add_to_cart_animation_height();

		$this->add_control(
			'add_to_cart_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-add-to-cart a'
			]
		);

		$this->add_control(
			'add_to_cart_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_to_cart_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_to_cart_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'add_to_cart_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'add_to_cart_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'add_to_cart_radius',
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
					'{{WRAPPER}} .wpr-grid-item-add-to-cart .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Button Styles ------------
		$this->start_controls_section(
			'section_wishlist_button_styles',
			[
				'label' => esc_html__( 'Add to Wishlist', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_styles' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-add i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-wishlist-add, {{WRAPPER}} .wpr-wishlist-remove',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-wishlist-add span, {{WRAPPER}} .wpr-wishlist-add i, .wpr-wishlist-remove span, {{WRAPPER}} .wpr-wishlist-remove i',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-wishlist-add span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-wishlist-add i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-wishlist-remove' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-wishlist-remove span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-wishlist-remove i' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-add:hover span' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'btn_hover_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-wishlist-add:hover, WRAPPER}} .wpr-wishlist-remove:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_remove_btn',
			[
				'label' => esc_html__( 'Remove', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'remove_btn_text_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-remove i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-remove:hover span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-remove:hover i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'remove_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-remove:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'remove_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-remove:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
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
					'{{WRAPPER}} .wpr-wishlist-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-wishlist-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ), 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					// '{{WRAPPER}} .wpr-wishlist-add' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-wishlist-remove' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-wishlist-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'button_border_type',
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
					'{{WRAPPER}} .wpr-wishlist-add' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-wishlist-remove' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-wishlist-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-wishlist-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();
		
		// Tab: Style ==============
		// Section: Button Styles ------------
		$this->start_controls_section(
			'section_compare_button_styles',
			[
				'label' => esc_html__( 'Add to Compare', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'comp_tabs_btn_styles' );

		$this->start_controls_tab(
			'comp_tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'comp_btn_text_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-add i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comp_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-compare-add, {{WRAPPER}} .wpr-compare-remove',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'comp_btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-compare-add span, {{WRAPPER}} .wpr-compare-add i, .wpr-compare-remove span, {{WRAPPER}} .wpr-compare-remove i',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'comp_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-add span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-add i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-remove' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-remove span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-remove i' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'comp_tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'comp_btn_hover_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-add:hover span' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_btn_hover_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_btn_hover_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'comp_btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-compare-add:hover, WRAPPER}} .wpr-compare-remove:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'comp_tab_remove_btn',
			[
				'label' => esc_html__( 'Remove', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'comp_remove_btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4400',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-remove span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-remove i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-remove:hover span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-remove:hover i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_remove_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-remove' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-remove:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'comp_remove_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-remove' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-remove:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'comp_button_padding',
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
					'{{WRAPPER}} .wpr-compare-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-compare-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comp_button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ), 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					// '{{WRAPPER}} .wpr-compare-add' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-compare-remove' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-compare-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'comp_button_border_type',
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
					'{{WRAPPER}} .wpr-compare-add' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-compare-remove' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comp_button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-compare-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'comp_button_border_radius',
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
					'{{WRAPPER}} .wpr-compare-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-compare-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		$this->add_section_added_to_cart_popup();

		// Styles =======================
		// Section: Likes ---------------
		$this->add_section_style_likes();

		// Styles =========================
		// Section: Sharing ---------------
		$this->add_section_style_sharing();

		// Styles ====================
		// Section: Lightbox ---------
		$this->start_controls_section(
			'section_style_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_lightbox_style' );

		$this->start_controls_tab(
			'tab_grid_lightbox_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'lightbox_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-item-lightbox i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_lightbox_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'lightbox_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'lightbox_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'lightbox_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lightbox_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-lightbox'
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
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
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
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'lightbox_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'lightbox_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-lightbox .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'lightbox_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'lightbox_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-grid-item-lightbox .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Separator Style 1 
		$this->start_controls_section(
			'section_style_separator1',
			[
				'label' => esc_html__( 'Separator Style 1', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator1_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-1 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator1_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-1:not(.wpr-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-sep-style-1.wpr-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator1_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
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
					'size' => 2,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-1 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_border_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-1 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator1_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator1_radius',
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
					'{{WRAPPER}} .wpr-grid-sep-style-1 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Separator Style 2 
		$this->start_controls_section(
			'section_style_separator2',
			[
				'label' => esc_html__( 'Separator Style 2', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator2_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-2 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator2_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-2:not(.wpr-grid-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-sep-style-2.wpr-grid-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator2_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
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
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-2 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_border_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-2 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator2_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sep-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_radius',
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
					'{{WRAPPER}} .wpr-grid-sep-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Navigation -------
		$this->start_controls_section(
			'wpr__section_style_grid_slider_nav',
			[
				'label' => esc_html__( 'Slider Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_slider_nav_style' );

		$this->start_controls_tab(
			'tab_grid_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'grid_slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-slider-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'grid_slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-slider-arrow svg' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'grid_slider_nav_border_type',
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
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_width',
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
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'grid_slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control_stack_grid_slider_nav_position();

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'wpr__section_style_grid_slider_dots',
			[
				'label' => esc_html__( 'Slider Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_slider_dots' );

		$this->start_controls_tab(
			'tab_grid_slider_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'grid_slider_dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.35)',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_slider_dots_active',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'grid_slider_dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-dots .slick-active .wpr-grid-slider-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-dots .slick-active .wpr-grid-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'grid_slider_dots_width',
			[
				'label' => esc_html__( 'Box Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-dot' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'grid_slider_dots_height',
			[
				'label' => esc_html__( 'Box Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-slider-dot' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'grid_slider_dots_border_type',
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
					'{{WRAPPER}} .wpr-grid-slider-dot' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_slider_dots_border_width',
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
					'{{WRAPPER}} .wpr-grid-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'grid_slider_dots_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'grid_slider_dots_border_radius',
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
					'{{WRAPPER}} .wpr-grid-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'grid_slider_dots_gutter',
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
					'{{WRAPPER}}.wpr-grid-slider-dots-horizontal .wpr-grid-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-grid-slider-dots-vertical .wpr-grid-slider-dot' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control_grid_slider_dots_hr();
		
		$this->add_responsive_control(
			'grid_slider_dots_vr',
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
					'{{WRAPPER}} .wpr-grid-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Upsell / Cross-sell Title
		$this->start_controls_section(
			'section_style_linked_products',
			[
				'label' => esc_html__( 'Upsell / Cross-sell Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'query_selection' => ['upsell', 'cross-sell'],
					// 'layout_select!' => 'slider'
				]
			]
		);

		$this->add_control(
			'linked_products_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-linked-products-heading' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'linked_products',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-linked-products-heading *'
			]
		);

		$this->add_responsive_control(
			'linked_products_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 3,
					'right' => 15,
					'bottom' => 3,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-linked-products-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'linked_products_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-linked-products-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
				// 'separator' => 'before'
			]
		);

		$this->add_control(
			'linked_products_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
					]
				],
				'default' => 'left',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-linked-products-heading *' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->add_section_style_sort_and_results();

		// Styles ====================
		// Section: Filters ----------
		$this->start_controls_section(
			'section_style_filters',
			[
				'label' => esc_html__( 'Filters', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_filters' => 'yes',
				],
			]
		);

		$this->add_control(
			'active_styles_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Apply active filter styles from the hover tab.', 'wpr-addons'),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
			]
		);

		$this->start_controls_tabs( 'tabs_grid_filters_style' );

		$this->start_controls_tab(
			'tab_grid_filters_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'filters_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filters_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li > a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'filters_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li > a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filters_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-filters li > a, {{WRAPPER}} .wpr-grid-filters li > span',
			]
		);

		$this->add_control(
			'filters_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_filters_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'filters_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li > a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li > span:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li > .wpr-active-filter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filters_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li > a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li > span:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li > .wpr-active-filter' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'filters_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li > a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li > span:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters li > .wpr-active-filter' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control_filters_pointer_color_hr();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filters_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-grid-filters li > a:hover, {{WRAPPER}} .wpr-grid-filters li > span:hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_filters_pointer();

		$this->add_control_filters_pointer_height();

		$this->add_control_filters_pointer_animation();

		$this->add_control(
			'filters_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li > a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-filters li > span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'filters_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-filters li'
			]
		);

		$this->add_control(
			'filters_border_type',
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
					'{{WRAPPER}} .wpr-grid-filters li > a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-filters li > span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'filters_border_width',
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
					'{{WRAPPER}} .wpr-grid-filters li > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-filters li > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'filters_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'filters_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'filters_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-filters-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 5,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 3,
					'right' => 15,
					'bottom' => 3,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-filters li > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filters_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'filters_radius',
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
					'{{WRAPPER}} .wpr-grid-filters li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-filters li > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'layout_select!' => 'slider',
					'layout_pagination' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_pagination_style' );

		$this->start_controls_tab(
			'tab_grid_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-pagination-finish' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-pagination a, {{WRAPPER}} .wpr-grid-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-double-bounce .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wave .wpr-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-chasing-dots .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-three-bounce .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-fading-circle .wpr-circle:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ]
				]
			]
		);

		$this->add_control(
			'pagination_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination a:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-grid-pagination a:hover, {{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-pagination'
			]
		);

		$this->add_responsive_control(
			'pagination_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_type',
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
					'{{WRAPPER}} .wpr-grid-pagination a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_width',
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
					'{{WRAPPER}} .wpr-grid-pagination a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'pagination_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .wpr-grid-pagination a' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination .wpr-prev-post-link i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-next-post-link i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-first-page i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-prev-page i' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-next-page i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-last-page i' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-prev-post-link svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-next-post-link svg' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-first-page svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-prev-page svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-next-page svg' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination .wpr-last-page svg' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_radius',
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
					'{{WRAPPER}} .wpr-grid-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Password Protected
		$this->start_controls_section(
			'section_style_pwd_protected',
			[
				'label' => esc_html__( 'Password Protected', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'pwd_protected_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-protected' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pwd_protected_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-protected' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pwd_protected_input_color',
			[
				'label'  => esc_html__( 'Input Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-protected input' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pwd_protected_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-protected p'
			]
		);

		$this->end_controls_section();

		// Styles =======================
		// Section: Custom Field Style 1
		$this->add_section_style_custom_field1();

		// Styles =======================
		// Section: Custom Field Style 2
		$this->add_section_style_custom_field2();
	}


	// Get Taxonomies Related to Post Type
	public function get_related_taxonomies() {
		$relations = [];
		$post_types = Utilities::get_custom_types_of( 'post', false );

		foreach ( $post_types as $slug => $title ) {
			$relations[$slug] = [];

			foreach ( get_object_taxonomies( $slug ) as $tax ) {
				array_push( $relations[$slug], $tax );
			}
		}

		return json_encode( $relations );
	}

	// Get Max Pages
	public function get_max_num_pages( $settings ) {
		$query = new \WP_Query( $this->get_main_query_args() );
		$max_num_pages = intval( ceil( $query->max_num_pages ) );

		// Reset
		wp_reset_postdata();

		// $max_num_pages
		return $max_num_pages;
	}

	// Main Query Args
	public function get_main_query_args() {
		$settings = $this->get_settings();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['query_selection'] = 'pro-cr' == $settings['query_selection'] ? 'dynamic' : $settings['query_selection'];
			$settings['query_orderby'] = 'pro-rn' == $settings['query_orderby'] ? 'date' : $settings['query_orderby'];
		}

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		
		if ( empty($settings['query_offset']) ) {
			$settings[ 'query_offset' ] = 0;
		}
		
		$query_posts_per_page = $settings['query_posts_per_page'];
		if ( empty($query_posts_per_page) ) {
			$query_posts_per_page = -1;
		}
		
		$offset = ( $paged - 1 ) * $query_posts_per_page + $settings[ 'query_offset' ];

		// Dynamic
		$args = [
			'post_type' => 'product',
			'tax_query' => $this->get_tax_query_args(),
			'meta_query' => $this->get_meta_query_args(),
			'post__not_in' => $settings[ 'query_exclude_products' ],
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => 'date',
			'paged' => $paged,
			'offset' => $offset
		];

		// Featured
		if ( 'featured' === $settings['query_selection'] ) {
			$args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => wc_get_product_visibility_term_ids()['featured'],
			];
		}

		// On Sale
		if ( 'onsale' === $settings['query_selection'] ) {
			// $args['post__in'] = wc_get_product_ids_on_sale();
			$args['meta_query'] = array(
				'relation' => 'OR',
				array( // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array( // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			);
		}
		
		if ( 'upsell' === $settings['query_selection'] ) {
			// Get Product
			$product = wc_get_product();
	
			if ( ! $product ) {
				return;
			}
	
			$meta_query = WC()->query->get_meta_query();
	
			$this->my_upsells = $product->get_upsell_ids();
			
			if ( !empty($this->my_upsells) ) {
				$args = array(
					'post_type' => 'product',
					'post__not_in' => $settings[ 'query_exclude_products' ],
					'ignore_sticky_posts' => 1,
					// 'no_found_rows' => 1,
					'posts_per_page' => $settings['query_posts_per_page'],
					'orderby' => 'post__in',
					'order' => 'asc',
					'paged' => $paged,
					'post__in' => $this->my_upsells,
					'meta_query' => $meta_query
				);
			} else {
				$args['post_type'] = ['none'];
			}
		}

		if ( 'cross-sell' === $settings['query_selection'] ) {
			// Get Product
			$this->crossell_ids = [];
			
			if( is_cart() ) {
				$items = WC()->cart->get_cart();
	
				foreach($items as $item => $values) {
					$product = $values['data'];
					$cross_sell_products = $product->get_cross_sell_ids();
					foreach($cross_sell_products as $cs_product) {
						array_push($this->crossell_ids, $cs_product);
					}
				  }
			}

			if ( is_single() ) {
				$product = wc_get_product();
		
				if ( ! $product ) {
					return;
				}

				$this->crossell_ids = $product->get_cross_sell_ids();
			}
	
			// $meta_query = WC()->query->get_meta_query();
			
			if ( !empty($this->crossell_ids) ) {
				$args = [
					'post_type' => 'product',
					'post__not_in' => $settings[ 'query_exclude_products' ],
					'tax_query' => $this->get_tax_query_args(),
					'ignore_sticky_posts' => 1,
					// 'no_found_rows' => 1,
					'posts_per_page' => $settings['query_posts_per_page'],
					// 'orderby' => 'post__in',
					'order' => 'asc',
					'paged' => $paged,
					'post__in' => $this->crossell_ids,
					// 'meta_query' => $meta_query
				];
			} else {
				$args['post_type'] = 'none';
			}
		}

		// Default Order By
		if ( 'sales' === $settings['query_orderby'] ) {
			$args['meta_key'] = 'total_sales';
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'rating' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_wc_average_rating';
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'price-low' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_price';
			$args['order'] = 'ASC';
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'price-high' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_price';
			$args['order'] = 'DESC';
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'random' === $settings['query_orderby'] ) {
			$args['orderby']  = 'rand';
		} else {
			$args['orderby']  = 'date';
		}

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}

		// Exclude Out Of Stock
		if ( 'yes' === $settings['query_exclude_out_of_stock'] ) {
			$args['meta_query'] = [
				[
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => 'NOT LIKE',
				]
			];
		}

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_products' ]) ) {
				$post_ids = $settings[ 'query_manual_products' ];
			}

			$args = [
				'post_type' => 'product',
				'post__in' => $post_ids,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $settings[ 'query_randomize' ],
				'paged' => $paged,
			];
		}

		// Get Post Type
		if ( 'current' === $settings[ 'query_selection' ] && true !== \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			global $wp_query;

			// Products Per Page
			if ( is_product_category() ) {
				$posts_per_page = intval(get_option('wpr_woo_shop_cat_ppp', 9));
			} elseif ( is_product_tag() ) {
				$posts_per_page = intval(get_option('wpr_woo_shop_tag_ppp', 9));
			} else {
				$posts_per_page = intval(get_option('wpr_woo_shop_ppp', 9));
			}
			$args = $wp_query->query_vars;
			$args['tax_query'] = $this->get_tax_query_args();
			$args['meta_query'] = $this->get_meta_query_args();
			$args['posts_per_page'] = $posts_per_page;
			$args['orderby'] = $settings['query_randomize'];
		}

		// Sorting
		if ( isset( $_GET['orderby'] ) ) {
			if ( 'popularity' === $_GET['orderby'] ) {
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'rating' === $_GET['orderby'] ) {
				$args['meta_key'] = '_wc_average_rating';
				$args['order'] = 'DESC';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'price' === $_GET['orderby'] ) {
				$args['meta_key'] = '_price';
				$args['order'] = 'ASC';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'price-desc' === $_GET['orderby'] ) {
				$args['meta_key'] = '_price';
				$args['order'] = 'DESC';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'random' === $_GET['orderby'] ) {
				$args['orderby']  = 'rand';
			} elseif ( 'date' === $_GET['orderby'] ) {
				$args['orderby']  = 'date';
			} else if ( 'title' === $_GET['orderby'] ){
				$args['orderby']  = 'title';
				$args['order'] = 'ASC';
			} else if ( 'title-desc' === $_GET['orderby'] ) {
				$args['orderby']  = 'title';
				$args['order'] = 'DESC';
			} else {
				$args['order'] = 'ASC';
				$args['orderby']  = 'menu_order';
			}
		}

		// Search
		if ( isset( $_GET['psearch'] ) ) {
			$args['s'] = $_GET['psearch'];
		}

		return $args;
	}

	// Taxonomy Query Args
	public function get_tax_query_args() {
		$tax_query = [];

		// Filters Query
		if ( isset($_GET['wprfilters']) ) {
			$selected_filters = WC()->query->get_layered_nav_chosen_attributes();

			if ( !empty($selected_filters) ) {
				foreach ( $selected_filters as $taxonomy => $data ) {
					array_push($tax_query, [
						'taxonomy' => $taxonomy,
						'field' => 'slug',
						'terms' => $data['terms'],
						'operator' => 'and' === $data['query_type'] ? 'AND' : 'IN',
						'include_children' => false,
					]);
				}
			}

			// Product Categories
			if ( isset($_GET['filter_product_cat']) ) {
				array_push($tax_query, [
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => explode( ',', $_GET['filter_product_cat'] ),
					'operator' => 'IN',
					'include_children' => true, // test this needed or not for hierarchy
				]);
			}

			// Product Tags
			if ( isset($_GET['filter_product_tag']) ) {
				array_push($tax_query, [
					'taxonomy' => 'product_tag',
					'field' => 'slug',
					'terms' => explode( ',', $_GET['filter_product_tag'] ),
					'operator' => 'IN',
					'include_children' => true, // test this needed or not for hierarchy
				]);
			}
		// Grid Query
		} else {
			$settings = $this->get_settings();

			if ( isset($_GET['wpr_select_product_cat']) ) {
				if ( $_GET['wpr_select_product_cat'] != '0' ) {
					// Get category from URL
					$category = sanitize_text_field($_GET['wpr_select_product_cat']);
				
					array_push( $tax_query, [
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => $category
					] );
				}
			}

			if ( isset($_GET['product_cat']) ) {
				if ( $_GET['product_cat'] != '0' ) {
					// Get category from URL
					$category = sanitize_text_field($_GET['product_cat']);
				
					array_push( $tax_query, [
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => $category
					] );
				}
			} else {
				foreach ( get_object_taxonomies( 'product' ) as $tax ) {
					if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
						array_push( $tax_query, [
							'taxonomy' => $tax,
							'field' => 'id',
							'terms' => $settings[ 'query_taxonomy_'. $tax ]
						] );
					}
				}
			}
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		// Filter by rating.
		if ( isset( $_GET['filter_rating'] ) ) {

			$product_visibility_terms  = wc_get_product_visibility_term_ids();
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$filter_rating = array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['filter_rating'] ) ) ) );
			$rating_terms  = array();
			for ( $i = 1; $i <= 5; $i ++ ) {
				if ( in_array( $i, $filter_rating, true ) && isset( $product_visibility_terms[ 'rated-' . $i ] ) ) {
					$rating_terms[] = $product_visibility_terms[ 'rated-' . $i ];
				}
			}
			if ( ! empty( $rating_terms ) ) {
				$tax_query[] = array(
					'taxonomy'      => 'product_visibility',
					'field'         => 'term_taxonomy_id',
					'terms'         => $rating_terms,
					'operator'      => 'IN',
				);
			}
		}

		return $tax_query;
	}

	// Meta Query Args
	public function get_meta_query_args(){
        $meta_query = WC()->query->get_meta_query();

		// Price Filter Args
        if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
            $meta_query = array_merge( ['relation' => 'AND'], $meta_query );
            $meta_query[] = [
                [
                    'key' => '_price',
                    'value' => [ $_GET['min_price'], $_GET['max_price'] ],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ],
            ];
        }

		return $meta_query;
    }

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Disable Animation on Mobile
		if ( 'overlay' !== $object ) {
			if ( 'yes' === $data[$object .'_animation_disable_mobile'] && wp_is_mobile() ) {
				return $class;
			}
		}

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' wpr-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' wpr-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' wpr-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' wpr-anim-transparency';
			}
		}

		return $class;
	}

	// Get Image Effect Class
	public function get_image_effect_class( $settings ) {
		$class = '';

		if ( ! wpr_fs()->can_use_premium_code() ) {
			if ( 'pro-zi' ==  $settings['image_effects'] || 'pro-zo' ==  $settings['image_effects'] || 'pro-go' ==  $settings['image_effects'] || 'pro-bo' ==  $settings['image_effects'] ) {
				$settings['image_effects'] = 'none';
			}
		}

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' wpr-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' wpr-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' wpr-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}

	// Render Password Protected Input
	public function render_password_protected_input( $settings ) {
		if ( ! post_password_required() ) {
			return;
		}

		add_filter( 'the_password_form', function () {
			$output  = '<form action="'. esc_url(home_url( 'wp-login.php?action=postpass' )) .'" method="post">';
			$output .= '<i class="fas fa-lock"></i>';
			$output .= '<p>'. esc_html(get_the_title()) .'</p>';
			$output .= '<input type="password" name="post_password" id="post-'. esc_attr(get_the_id()) .'" placeholder="'. esc_html__( 'Type and hit Enter...', 'wpr-addons' ) .'">';
			$output .= '</form>';

			return $output;
		} );

		echo '<div class="wpr-grid-item-protected wpr-cv-container">';

			echo '<div class="wpr-cv-outer">';
				echo '<div class="wpr-cv-inner">';
					echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Thumbnail
	public function render_product_thumbnail( $settings ) {
		$id = get_post_thumbnail_id();
		$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'layout_image_crop', $settings );
		$alt = '' === wp_get_attachment_caption( $id ) ? get_the_title() : wp_get_attachment_caption( $id );
		
		if ( get_post_meta(get_the_ID(), 'wpr_secondary_image_id') && !empty(get_post_meta(get_the_ID(), 'wpr_secondary_image_id')) ) {
			$src2 = Group_Control_Image_Size::get_attachment_image_src( get_post_meta(get_the_ID(), 'wpr_secondary_image_id')[0], 'layout_image_crop', $settings );
		} else {
			$src2 = '';
		}

		if ( has_post_thumbnail() ) {
			echo '<div class="wpr-grid-image-wrap" data-src="'. esc_url( $src ) .'"  data-img-on-hover="'. $settings['secondary_img_on_hover'] .'" data-src-secondary="'. esc_url( $src2 ) .'">';
				echo '<img src="'. esc_url( $src ) .'" alt="'. esc_attr( $alt ) .'" class="wpr-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
				if ( 'yes' == $settings['secondary_img_on_hover'] ) {
					echo '<img src="'. esc_url( $src2 ) . '" alt="'. esc_attr( $alt ) .'" class="wpr-hidden-img wpr-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
				}
			echo '</div>';
		}
	}

	// Render Media Overlay
	public function render_media_overlay( $settings ) {
		echo '<div class="wpr-grid-media-hover-bg '. esc_attr($this->get_animation_class( $settings, 'overlay' )) .'" data-url="'. esc_url( get_the_permalink( get_the_ID() ) ) .'">';

			if ( wpr_fs()->can_use_premium_code() ) {
				if ( '' !== $settings['overlay_image']['url'] ) {
					echo '<img src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
				}
			}

		echo '</div>';
	}

	// Render Post Title
	public function render_product_title( $settings, $class ) {
		$title_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $this->get_settings()['title_pointer'];
		$title_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $this->get_settings()['title_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['title_pointer']) && 'none' !== $this->get_settings()['title_pointer']) ? 'class="wpr-pointer-item"' : '';
		$open_links_in_new_tab = 'yes' === $this->get_settings()['open_links_in_new_tab'] ? '_blank' : '_self';

		$class .= ' wpr-pointer-'. $title_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $title_pointer_animation;

		echo '<'. esc_attr($settings['element_title_tag']) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a target="'. $open_links_in_new_tab .'"  '. $pointer_item_class .' href="'. esc_url( get_the_permalink() ) .'">';
				if ( 'word_count' === $settings['element_trim_text_by'] ) {
					echo esc_html(wp_trim_words( get_the_title(), $settings['element_word_count'] ));
				} else {
					echo substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...';
				}
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr($settings['element_title_tag']) .'>';
	}

	// Render Post Excerpt
	public function render_product_excerpt( $settings, $class ) {
		if ( '' === get_the_excerpt() ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			if ( 'word_count' === $settings['element_trim_text_by']) {
			  echo '<p>'. esc_html(wp_trim_words( get_the_excerpt(), $settings['element_word_count'] )) .'</p>';
			} else {
			  // echo '<p>'. substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...' . '</p>';
			  echo '<p>'. esc_html(implode('', array_slice( str_split(get_the_excerpt()), 0, $settings['element_letter_count'] ))) .'...' .'</p>';
			}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Categories
	public function render_product_categories( $settings, $class, $post_id ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		// Pointer Class
		$categories_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $this->get_settings()['categories_pointer'];
		$categories_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $this->get_settings()['categories_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['categories_pointer']) && 'none' !== $this->get_settings()['categories_pointer']) ? 'class="wpr-pointer-item"' : '';

		$class .= ' wpr-pointer-'. $categories_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $categories_pointer_animation;

		echo '<div class="'. esc_attr($class) .' wpr-grid-product-categories">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="wpr-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Taxonomies
				foreach ( $terms as $term ) {
					echo '<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
						if ( ++$count !== count( $terms ) ) {
							echo '<span class="tax-sep">'. esc_html($settings['element_tax_sep']) .'</span>';
						}
					echo '</a>';
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="wpr-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Tags
	public function render_product_tags( $settings, $class, $post_id ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		// Pointer Class
		$tags_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $this->get_settings()['tags_pointer'];
		$tags_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $this->get_settings()['tags_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['tags_pointer']) && 'none' !== $this->get_settings()['tags_pointer']) ? 'class="wpr-pointer-item"' : '';

		$class .= ' wpr-pointer-'. $tags_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $tags_pointer_animation;

		echo '<div class="'. esc_attr($class) .' wpr-grid-product-tags">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="wpr-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Taxonomies
				foreach ( $terms as $term ) {
					echo '<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
						if ( ++$count !== count( $terms ) ) {
							echo '<span class="tax-sep">'. esc_html($settings['element_tax_sep']) .'</span>';
						}
					echo '</a>';
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="wpr-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Custom Fields/Attributes
	public function render_product_custom_fields( $settings, $class, $post_id ) {

	}

	// Render Post Likes
	public function render_product_likes( $settings, $class, $post_id ) {}

	// Render Post Sharing
	public function render_product_sharing_icons( $settings, $class ) {}

	// Render Post Lightbox
	public function render_product_lightbox( $settings, $class, $post_id ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = get_the_post_thumbnail_url( $post_id );

				// Audio Post Type
				if ( 'audio' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfa_select'] ) {
						$utilities = new Utilities();
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfa_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							add_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50, 3 );
								$track_url = wp_oembed_get( $meta_value );
							remove_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50 );

						// Iframe
						} else {
							$track_url = Utilities::filter_oembed_results( $meta_value );
						}

						$lightbox_source = $track_url;
					}

				// Video Post Type
				} elseif ( 'video' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfv_select'] ) {
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfv_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							$video = \Elementor\Embed::get_video_properties( $meta_value );

						// Iframe
						} else {
							$video = \Elementor\Embed::get_video_properties( Utilities::filter_oembed_results($meta_value) );
						}

						// Provider URL
						if ( 'youtube' === $video['provider'] ) {
							$video_url = '//www.youtube.com/embed/'. $video['video_id'] .'?feature=oembed&autoplay=1&controls=1';
						} elseif ( 'vimeo' === $video['provider'] ) {
							$video_url = 'https://player.vimeo.com/video/'. $video['video_id'] .'?autoplay=1#t=0';
						}

						// Add Lightbox Attributes
						if ( isset( $video_url ) ) {
							$lightbox_source = $video_url;
						}
					}
				}

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="wpr-grid-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public function render_product_element_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class .' '. $settings['element_separator_style']) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Render Status
	public function render_product_status( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';

			// Sale
			 if ( $product->is_on_sale() ) {
				echo '<span class="wpr-woo-onsale">'. esc_html__( 'Sale', 'wpr-addons' ) .'</span>';
			}

			// Stock Status
			if ( 'yes' === $settings['element_status_offstock'] && $product->is_in_stock() == false && 
				 ! ( $product->is_type( 'variable' ) && $product->get_stock_quantity() > 0 ) ) {
				echo '<span class="wpr-woo-outofstock">'. esc_html__( 'Out of Stock', 'wpr-addons' ) .'</span>';
			}

			// Featured
			if ( 'yes' === $settings['element_status_featured'] && $product->is_featured() ) {
				echo '<span class="wpr-woo-featured">'. esc_html__( 'Featured', 'wpr-addons' ) .'</span>';
			}

			echo '</div>';
		echo '</div>';
	}

	// Render Add To Cart
	public function render_product_add_to_cart( $settings, $class ) {
		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		// Get Button Class
		$button_class = implode( ' ', array_filter( [
			'product_type_'. $product->get_type(),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
		] ) );

		$add_to_cart_animation = ! wpr_fs()->can_use_premium_code() ? 'wpr-button-none' : $this->get_settings()['add_to_cart_animation'];

		$popup_notification_animation = isset($this->get_settings_for_display()['popup_notification_animation']) ? $this->get_settings_for_display()['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($this->get_settings_for_display()['popup_notification_fade_out_in']) ? $this->get_settings_for_display()['popup_notification_fade_out_in'] : '';
		$popup_notification_animation_duration = isset($this->get_settings_for_display()['popup_notification_animation_duration']) ? $this->get_settings_for_display()['popup_notification_animation_duration'] : '';

		$attributes = [
			'rel="nofollow"',
			'class="'. esc_attr($button_class) .' wpr-button-effect '. esc_attr($add_to_cart_animation) .' '. (!$product->is_in_stock() && 'simple' === $product->get_type() ? 'wpr-atc-not-clickable' : '').'"',
			'aria-label="'. esc_attr($product->add_to_cart_description()) .'"',
			'data-product_id="'. esc_attr($product->get_id()) .'"',
			'data-product_sku="'. esc_attr($product->get_sku()) .'"',
			'data-atc-popup="'. $settings['element_show_added_tc_popup']  .'"',
			'data-atc-animation="'. $popup_notification_animation  .'"',
			'data-atc-fade-out-in="'. $popup_notification_fade_out_in  .'"',
			'data-atc-animation-time="'. $popup_notification_animation_duration  .'"'
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();

		// Icon: Before
		if ( 'before' === $settings['element_extra_icon_pos'] ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
			$extra_icon = ob_get_clean();

			$button_HTML .= '<span class="wpr-grid-extra-icon-left">'. $extra_icon .'</span>';
		}

		// Button Text
		if ( 'simple' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_simple_txt'];

			if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
				array_push( $attributes, 'href="'. esc_url( get_permalink( $page_id ) .'/?add-to-cart='. get_the_ID() ) .'"' );
			} else {
				array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
			}
		} elseif ( 'grouped' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_grouped_txt'];
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} elseif ( 'variable' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_variable_txt'];
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else if ( 'pw-gift-card' === $product->get_type() ) {
			$button_HTML .= esc_html__('Select Amount', 'wpr-addons');
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else {
			array_push( $attributes, 'href="'. esc_url( $product->get_product_url() ) .'"' );
			$button_HTML .= get_post_meta( get_the_ID(), '_button_text', true ) ? get_post_meta( get_the_ID(), '_button_text', true ) : 'Buy Product';
		}

		// Icon: After
		if ( 'after' === $settings['element_extra_icon_pos'] ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
			$extra_icon = ob_get_clean();

			$button_HTML .= '<span class="wpr-grid-extra-icon-right">'. $extra_icon .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';

			// Button HTML
			echo '<a '. implode( ' ', $attributes ) .'><span>'. $button_HTML .'</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			echo '</div>';
		echo '</div>';
	}

	// Add two new functions for handling cookies
	public function get_wishlist_from_cookie() {
        if (isset($_COOKIE['wpr_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist']), true);
        } else if ( isset($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']), true);
        }
        return array();
	}

	// Render Wishlist Button
	public function render_product_wishlist_button( $settings, $class ) {
		global $product;
		
		if ( !wpr_fs()->is_plan( 'expert' ) ) {
			return;
		}

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $user_id = get_current_user_id();
		
		if ($user_id > 0) {
			$wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
		}
		
		if ( ! $wishlist ) {
			$wishlist = array();
		}

		$popup_notification_animation = isset($this->get_settings_for_display()['popup_notification_animation']) ? $this->get_settings_for_display()['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($this->get_settings_for_display()['popup_notification_fade_out_in']) ? $this->get_settings_for_display()['popup_notification_fade_out_in'] : '';
		$popup_notification_animation_duration = isset($this->get_settings_for_display()['popup_notification_animation_duration']) ? $this->get_settings_for_display()['popup_notification_animation_duration'] : '';

		$wishlist_attributes = [
			'data-wishlist-url' => get_option('wpr_wishlist_page') ? get_option('wpr_wishlist_page') : '',
			'data-atw-popup="'. $settings['element_show_added_to_wishlist_popup']  .'"',
			'data-atw-animation="'. $popup_notification_animation  .'"',
			'data-atw-fade-out-in="'. $popup_notification_fade_out_in  .'"',
			'data-atw-animation-time="'. $popup_notification_animation_duration  .'"',
			'data-open-in-new-tab="'. $settings['element_open_links_in_new_tab'] .'"'
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();
		
		$button_add_title = '';
		$button_remove_title = '';
		$add_to_wishlist_content = '';
		$remove_from_wishlist_content = '';
		

		if ( 'yes' === $settings['show_icon'] ) {
			$add_to_wishlist_content .= '<i class="far fa-heart"></i>';
			$remove_from_wishlist_content .= '<i class="fas fa-heart"></i>';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$add_to_wishlist_content .= ' <span>'. esc_html__($settings['add_to_wishlist_text']) .'</span>';
		} else {
			$button_add_title = 'title="'. esc_html__($settings['add_to_wishlist_text']) .'"';
			$button_remove_title = 'title="'. esc_html__($settings['remove_from_wishlist_text']) .'"';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$remove_from_wishlist_content .= ' <span>'. esc_html__($settings['remove_from_wishlist_text']) .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
	
			$remove_button_hidden = !in_array( $product->get_id(), $wishlist ) ? 'wpr-button-hidden' : '';
			$add_button_hidden = in_array( $product->get_id(), $wishlist ) ? 'wpr-button-hidden' : '';
		
			// '. implode( ' ', $wishlist_attributes ) .'
			echo '<button class="wpr-wishlist-add '. $add_button_hidden .'" '. $button_add_title .' data-product-id="' . $product->get_id() . '"'. ' ' . implode( ' ', $wishlist_attributes ) .' >'. $add_to_wishlist_content .'</button>';
			echo '<button class="wpr-wishlist-remove '. $remove_button_hidden .'" '. $button_remove_title .' data-product-id="' . $product->get_id() . '">'. $remove_from_wishlist_content .'</button>';

			echo '</div>';
		echo '</div>';
	}
	
	// Add two new functions for handling cookies
	public function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        } else if ( isset($_COOKIE['wpr_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
	}

	// Render Compare Button
	public function render_product_compare_button( $settings, $class ) {
		global $product;
		
		if ( !wpr_fs()->is_plan( 'expert' ) ) {
			return;
		}

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $user_id = get_current_user_id();
		
		if ($user_id > 0) {
			$compare = get_user_meta(  $user_id, 'wpr_compare', true );
		
			if ( ! $compare ) {
				$compare = array();
			}
		} else {
			$compare = $this->get_compare_from_cookie();
		}

		$popup_notification_animation = isset($this->get_settings_for_display()['popup_notification_animation']) ? $this->get_settings_for_display()['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($this->get_settings_for_display()['popup_notification_fade_out_in']) ? $this->get_settings_for_display()['popup_notification_fade_out_in'] : '';
		$popup_notification_animation_duration = isset($this->get_settings_for_display()['popup_notification_animation_duration']) ? $this->get_settings_for_display()['popup_notification_animation_duration'] : '';

		$compare_attributes = [
			'data-compare-url' => get_option('wpr_compare_page') ? get_option('wpr_compare_page') : '',
			'data-atcompare-popup="'. $settings['element_show_added_to_compare_popup']  .'"',
			'data-atcompare-animation="'. $popup_notification_animation  .'"',
			'data-atcompare-fade-out-in="'. $popup_notification_fade_out_in  .'"',
			'data-atcompare-animation-time="'. $popup_notification_animation_duration  .'"',
			'data-open-in-new-tab="'. $settings['element_open_links_in_new_tab'] .'"'
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();
		
		$add_to_compare_content = '';
		$remove_from_compare_content = '';
		$button_add_title = '';
		$button_remove_title = '';
		

		if ( 'yes' === $settings['show_icon'] ) {
			$add_to_compare_content .= '<i class="fas fa-exchange-alt"></i>';
			$remove_from_compare_content .= '<i class="fas fa-exchange-alt"></i>';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$add_to_compare_content .= ' <span>'. esc_html__($settings['add_to_compare_text']) .'</span>';
		} else {
			$button_add_title = 'title="'. esc_html__($settings['add_to_compare_text']) .'"';
			$button_remove_title = 'title="'. esc_html__($settings['remove_from_compare_text']) .'"';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$remove_from_compare_content .= ' <span>'. esc_html__($settings['remove_from_compare_text']) .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
	
			$remove_button_hidden = !in_array( $product->get_id(), $compare ) ? 'wpr-button-hidden' : '';
			$add_button_hidden = in_array( $product->get_id(), $compare ) ? 'wpr-button-hidden' : '';
		
			// '. implode( ' ', $compare_attributes ) .'
			echo '<button class="wpr-compare-add '. $add_button_hidden .'" '. $button_add_title .' data-product-id="' . $product->get_id() . '"'. ' ' . implode( ' ', $compare_attributes ) .' >'. $add_to_compare_content .'</button>';
			echo '<button class="wpr-compare-remove '. $remove_button_hidden .'" '. $button_remove_title .' data-product-id="' . $product->get_id() . '">'. $remove_from_compare_content .'</button>';

			echo '</div>';
		echo '</div>';
	}

	// Render Rating
	public function render_product_rating( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		$rating_amount = floatval( $product->get_average_rating() );
		$round_rating = (int)$rating_amount;
		$rating_icon = '&#xE934;';

		if ( 'style-1' === $settings['element_rating_style'] ) {
			$style_class = ' wpr-woo-rating-style-1';
			if ( 'outline' === $settings['element_rating_unmarked_style'] ) {
				$rating_icon = '&#xE933;';
			}
		} elseif ( 'style-2' === $settings['element_rating_style'] ) {
			$rating_icon = '&#9733;';
			$style_class = ' wpr-woo-rating-style-2';

			if ( 'outline' === $settings['element_rating_unmarked_style'] ) {
				$rating_icon = '&#9734;';
			}
		}

		echo '<div class="'. esc_attr($class . $style_class) .'">';
			echo '<div class="inner-block">';

				echo '<div class="wpr-woo-rating">';

				if ( 'yes' === $settings['element_rating_score'] ) {
					if ( $rating_amount == 1 || $rating_amount == 2 || $rating_amount == 3 || $rating_amount == 4 || $rating_amount == 5 )  {
						$rating_amount = $rating_amount .'.0';
					}

					echo '<i class="wpr-rating-icon-10">'. esc_html($rating_icon) .'</i>';
					echo '<span>'. esc_html($rating_amount) .'</span>';
				} else {
					for ( $i = 1; $i <= 5; $i++ ) {
						if ( $i <= $rating_amount ) {
							echo '<i class="wpr-rating-icon-full">'. esc_html($rating_icon) .'</i>';
						} elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
							echo '<i class="wpr-rating-icon-'. esc_attr((( $rating_amount - $round_rating ) * 10)) .'">'. esc_html($rating_icon) .'</i>';
						} else {
							echo '<i class="wpr-rating-icon-empty">'. esc_html($rating_icon) .'</i>';
						}
			     	}
				}

				echo '</div>';

			echo '</div>';
		echo '</div>';
	}

	// Render Price
	public function render_product_price( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';

			echo '<span>'. wp_kses_post($product->get_price_html()) .'</span>';
			$sale_price_dates_to    = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
			echo $sale_price_dates_to;

			echo '</div>';
		echo '</div>';
	}


	public function render_product_sale_dates( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		// $sale_price_dates_from  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		// $sale_price_dates_to  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		$sale_price_dates_from  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n(get_option('date_format'), $date ) : '';
		$sale_price_dates_to  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n(get_option('date_format'), $date ) : '';
		
		if ( ( 'yes' == $settings['show_sale_starts_date'] && !empty($sale_price_dates_from) ) || ( 'yes' == $settings['show_sale_ends_date'] && !empty($sale_price_dates_to) ) ) {
			echo '<div class="'. esc_attr($class) .'">';
				echo '<div class="inner-block">';

					echo '<span class="wpr-sale-dates">';
		
						// Text: Before
						if ( '' !== $settings['element_sale_starts_text'] && !empty($sale_price_dates_from) ) {
							echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_sale_starts_text'] ) .'</span> ';
						}
						
						if ( !empty($sale_price_dates_from) ) {
							echo  '<span>'. $sale_price_dates_from .'</span>';
						}


						if ( !empty($settings['element_sale_dates_sep']) && 'inline' == $settings['element_sale_dates_layout'] ) {
							if ( !empty($sale_price_dates_from) && !empty($sale_price_dates_to) ) {
								echo $settings['element_sale_dates_sep'];
							}
						}

						if ( 'block' == $settings['element_sale_dates_layout'] && !empty($sale_price_dates_form) && !empty($sale_price_dates_to) ) {
							echo '<br>';
						}
		
						// Text: Before
						if ( '' !== $settings['element_sale_ends_text'] && !empty($sale_price_dates_to) ) {
							echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_sale_ends_text'] ) .'</span> ';
						}

						if ( !empty($sale_price_dates_to) ) {
							echo  '<span>'. $sale_price_dates_to .'</span>';
						}

					echo '</span>';
	
				echo '</div>';
			echo '</div>';
		}
	}

	// Get Elements
	public function get_elements( $type, $settings, $class, $post_id ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-sd' == $type || 'pro-ws' == $type || 'pro-cm' == $type || 'pro-cfa' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
			case 'title':
				$this->render_product_title( $settings, $class );
				break;

			case 'excerpt':
				$this->render_product_excerpt( $settings, $class );
				break;

			case 'product_cat':
				$this->render_product_categories( $settings, $class, $post_id );
				break;

			case 'product_tag':
				$this->render_product_tags( $settings, $class, $post_id );
				break;

			case 'likes':
				$this->render_product_likes( $settings, $class, $post_id );
				break;

			case 'sharing':
				$this->render_product_sharing_icons( $settings, $class );
				break;

			case 'lightbox':
				$this->render_product_lightbox( $settings, $class, $post_id );
				break;

			case 'separator':
				$this->render_product_element_separator( $settings, $class );
				break;

			case 'status':
				$this->render_product_status( $settings, $class );
				break;

			case 'price':
				$this->render_product_price( $settings, $class );
				break;

			case 'sale_dates':
				$this->render_product_sale_dates( $settings, $class );
				break;

			case 'rating':
				$this->render_product_rating( $settings, $class );
				break;

			case 'add-to-cart':
				$this->render_product_add_to_cart( $settings, $class );
				break;
			case 'wishlist-button':
				if ( wpr_fs()->is_plan( 'expert' ) ) {
					$this->render_product_wishlist_button( $settings, $class );
				}
				break;
			case 'compare-button':
				if ( wpr_fs()->is_plan( 'expert' ) ) {
					$this->render_product_compare_button( $settings, $class );
				}
				break;
			case 'custom-field':
				$this->render_product_custom_fields( $settings, $class, $post_id );
				break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $post_id ) {
		$locations = [];

		foreach ( $settings['grid_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

			if ( ! wpr_fs()->can_use_premium_code() ) {
				$align_vr = 'middle';
			}

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $elements ) {
					if ( 'middle' === $align ) {
						echo '<div class="wpr-cv-container"><div class="wpr-cv-outer"><div class="wpr-cv-inner">';
					}

					echo '<div class="wpr-grid-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $elements as $data ) {
							
							// Get Class
							$class  = 'wpr-grid-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' wpr-grid-item-display-'. $data['element_display'];
							$class .= ' wpr-grid-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $post_id );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				echo '<div class="wpr-grid-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'wpr-grid-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' wpr-grid-item-display-'. $data['element_display'];
						$class .= ' wpr-grid-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $post_id );
					}
				echo '</div>';
			}

		}
	}

	// Render Sort & Results
	public function render_grid_sorting( $settings, $posts ) {}

	// Render Grid Filters
	public function render_grid_filters( $settings ) {
		$taxonomy = $settings['filters_select'];

		// Return if Disabled
		if ( '' === $taxonomy || ! isset( $settings[ 'query_taxonomy_'. $taxonomy ] ) ) {
			return;
		}

		// Get Custom Filters
		$custom_filters = $settings[ 'query_taxonomy_'. $taxonomy ];

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['filters_default_filter'] = '';
			$settings['filters_icon_align'] = '';
			$settings['filters_count'] = '';
			$settings['filters_pointer'] = 'none';
			$settings['filters_pointer_animation'] = 'none';
		}

		// Icon
		$left_icon = 'left' === $settings['filters_icon_align'] ? '<i class="'. esc_attr($settings['filters_icon']['value']) .' wpr-grid-filters-icon-left"></i>' : '';
		$right_icon = 'right' === $settings['filters_icon_align'] ? '<i class="'. esc_attr($settings['filters_icon']['value']) .' wpr-grid-filters-icon-right"></i>' : '';
		
		// Separator
		$left_separator = 'left' === $settings['filters_separator_align'] ? '<em class="wpr-grid-filters-sep">'. esc_html($settings['filters_separator']) .'</em>' : '';
		$right_separator = 'right' === $settings['filters_separator_align'] ? '<em class="wpr-grid-filters-sep">'. esc_html($settings['filters_separator']) .'</em>' : '';

		// Count
		$post_count = 'yes' === $settings['filters_count'] ? '<sup data-brackets="'. esc_attr($settings['filters_count_brackets']) .'"></sup>' : '';

		// Pointer Class
		$pointer_class  = ' wpr-pointer-'. $settings['filters_pointer'];
		$pointer_class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $settings['filters_pointer_animation'];
		$pointer_item_class = (isset($settings['filters_pointer']) && 'none' !== $settings['filters_pointer']) ? 'class="wpr-pointer-item"' : '';
		$pointer_item_class_name = (isset($settings['filters_pointer']) && 'none' !== $settings['filters_pointer']) ? 'wpr-pointer-item' : '';

		// Filters List
		echo '<ul class="wpr-grid-filters elementor-clearfix wpr-grid-filters-sep-'. esc_attr($settings['filters_separator_align']) .'">';

		// All Filter
		if ( 'yes' === $settings['filters_all'] && 'yes' !== $settings['filters_linkable'] ) {
			echo '<li class="'. esc_attr($pointer_class) .'">';
			echo '<span data-filter="*" class="wpr-active-filter '. $pointer_item_class_name .'">'. $left_icon . esc_html($settings['filters_all_text']) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</li>';
		}

		// Custom Filters
		if ( $settings['query_selection'] === 'dynamic' && ! empty( $custom_filters ) ) {
			$parent_filters = [];
			
			foreach ( $custom_filters as $key => $term_id ) {
				$filter = get_term_by( 'id', $term_id, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $filter->slug : $taxonomy .'-'. $filter->slug;

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';

					echo '<li'. $data_role .' class="'. esc_attr($pointer_class) .'">';
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo ''. $left_separator .'<span '. $pointer_item_class .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo ''. $left_separator .'<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</a>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					echo '</li>';

				// Get Sub Filters
				} else {
					array_push( $parent_filters, $filter->parent );
				}
			}

		// All Filters
		} else {
			$all_filters = get_terms( $taxonomy );
			$parent_filters = [];

			foreach ( $all_filters as $key => $filter ) {
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $filter->slug : $taxonomy .'-'. $filter->slug;

				// Parent Filters
				if ( 0 === $filter->parent ) {
					$children = get_term_children( $filter->term_id, $taxonomy );
					$data_role = ! empty($children) ? ' data-role="parent"' : '';
					$hidden_class = $this->get_hidden_filter_class($filter->slug, $settings);

					echo '<li'. $data_role .' class="'. esc_attr($pointer_class) . esc_attr($hidden_class) .'">';
						if ( 'yes' !== $settings['filters_linkable'] ) {
							echo ''. $left_separator .'<span '. $pointer_item_class .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo ''. $left_separator .'<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $filter->term_id, $taxonomy )) .'" data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($filter->name) . $right_icon . $post_count .'</a>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					echo '</li>';

				// Get Sub Filters
				} else {
					array_push( $parent_filters, $filter->parent );
				}
			}
		}

		// Sub Filters
		if ( 'yes' !== $settings['filters_linkable'] ) {
			foreach ( array_unique( $parent_filters ) as $key => $parent_filter ) {
				$parent = get_term_by( 'id', $parent_filter, $taxonomy );
				$children = get_term_children( $parent_filter, $taxonomy );
				$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $parent->slug : $taxonomy .'-'. $parent->slug;

				echo '<ul data-parent=".'. esc_attr(urldecode($data_attr)) .'" class="wpr-sub-filters">';

				echo '<li data-role="back" class="'. esc_attr($pointer_class) .'">';
					echo '<span class="wpr-back-filter" data-filter=".'. esc_attr(urldecode( $data_attr )) .'">';
						echo '<i class="fas fa-long-arrow-alt-left"></i>&nbsp;&nbsp;'. esc_html__( 'Back', 'wpr-addons' );
					echo '</span>';
				echo '</li>';

				foreach ( $children as $child ) {
					$sub_filter = get_term_by( 'id', $child, $taxonomy );
					$data_attr = 'post_tag' === $taxonomy ? 'tag-'. $sub_filter->slug : $taxonomy .'-'. $sub_filter->slug;

					echo '<li data-role="sub" class="'. esc_attr($pointer_class) .'">';
						echo ''. $left_separator .'<span '. $pointer_item_class .' data-filter=".'. esc_attr(urldecode($data_attr)) .'">'. $left_icon . esc_html($sub_filter->name) . $right_icon . $post_count .'</span>'. $right_separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '</li>';
				}

				echo '</ul>';
			}
		}

		echo '</ul>';
	}

	public function get_hidden_filter_class($slug, $settings) {
		$posts = new \WP_Query( $this->get_main_query_args() );
		$visible_categories = [];

		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$categories = get_the_category();

				foreach ($categories as $key => $category) {
					array_push($visible_categories, $category->slug);
				}
			}

			$visible_categories = array_unique($visible_categories);

			wp_reset_postdata();
		}

		return ( ! in_array($slug, $visible_categories) && 'yes' == $settings['filters_hide_empty'] ) ? ' wpr-hidden-element' : '';
	}

	// Render Grid Pagination
	public function render_grid_pagination( $settings ) {
		// Return if Disabled
		if ( 'yes' !== $settings['layout_pagination'] || 1 === $this->get_max_num_pages( $settings ) || 'slider' === $settings['layout_select'] ) {
			return;
		}
		
		if ( (isset($this->my_upsells) && (count($this->my_upsells) <= $settings['query_posts_per_page'])) || (isset($this->crossell_ids) && (count($this->crossell_ids) <= $settings['query_posts_per_page'])) ) {
			return;
		}

		global $paged;
		$pages = $this->get_max_num_pages( $settings );
		$paged = empty( $paged ) ? 1 : $paged;

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['pagination_type'] = 'pro-is' == $settings['pagination_type'] ? 'default' : $settings['pagination_type'];
		}

		echo '<div class="wpr-grid-pagination elementor-clearfix wpr-grid-pagination-'. esc_attr($settings['pagination_type']) .'">';

		// Default
		if ( 'default' === $settings['pagination_type'] ) {
			if ( $paged < $pages ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-prev-post-link">';
					echo Utilities::get_wpr_icon( $settings['pagination_on_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo esc_html($settings['pagination_older_text']);
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="wpr-prev-post-link wpr-disabled-arrow">';
					echo Utilities::get_wpr_icon( $settings['pagination_on_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo esc_html($settings['pagination_older_text']);
				echo '</span>';
			}

			if ( $paged > 1 ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="wpr-next-post-link">';
					echo esc_html($settings['pagination_newer_text']);
					echo Utilities::get_wpr_icon( $settings['pagination_on_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="wpr-next-post-link wpr-disabled-arrow">';
					echo esc_html($settings['pagination_newer_text']);
					echo Utilities::get_wpr_icon( $settings['pagination_on_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</span>';
			}

		// Numbered
		} elseif ( 'numbered' === $settings['pagination_type'] ) {
			$range = $settings['pagination_range'];
			$showitems = ( $range * 2 ) + 1;

			if ( 1 !== $pages ) {

			    if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
			    	echo '<div class="wpr-grid-pagi-left-arrows">';

				    if ( 'yes' === $settings['pagination_first_last'] ) {
				    	if ( $paged >= 2 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( 1, true )) .'" class="wpr-first-page">';
					    	// echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-first-page">';
					    	// echo '<a href="'. esc_url(substr(get_pagenum_link( $paged + 1, true ), 0, strpos(get_pagenum_link( $paged + 1, true ), '?orderby'))) .'" class="wpr-first-page">';
					    		echo Utilities::get_wpr_icon( $settings['pagination_fl_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    		echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="wpr-first-page wpr-disabled-arrow">';
					    		echo Utilities::get_wpr_icon( $settings['pagination_fl_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    		echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
					    	echo '</span>';
				    	}
				    }

				    if ( 'yes' === $settings['pagination_prev_next'] ) {
				    	if ( $paged > 1 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="wpr-prev-page">';
					    		echo Utilities::get_wpr_icon( $settings['pagination_pn_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    		echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="wpr-prev-page wpr-disabled-arrow">';
					    		echo Utilities::get_wpr_icon( $settings['pagination_pn_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    		echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
					    	echo '</span>';
				    	}
				    }

				    echo '</div>';
			    }

			    for ( $i = 1; $i <= $pages; $i++ ) {
			        if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
						if ( $paged === $i ) {
							echo '<span class="wpr-grid-current-page">'. esc_html($i) .'</span>';
						} else {
							// var_dump(get_pagenum_link( $i, true ), substr(get_pagenum_link( $i, true ), 0, strpos(get_pagenum_link( $i, true ), '?orderby')));
							echo '<a href="'. esc_url(get_pagenum_link( $i, true )) .'">'. esc_html($i) .'</a>';
						}
			        }
			    }

			    if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
			    	echo '<div class="wpr-grid-pagi-right-arrows">';

				    if ( 'yes' === $settings['pagination_prev_next'] ) {
				    	if ( $paged < $pages ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-next-page">';
					    		echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
					    		echo Utilities::get_wpr_icon( $settings['pagination_pn_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="wpr-next-page wpr-disabled-arrow">';
					    		echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
					    		echo Utilities::get_wpr_icon( $settings['pagination_pn_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    	echo '</span>';
				    	}
				    }

				    if ( 'yes' === $settings['pagination_first_last'] ) {
				    	if ( $paged <= $pages - 1 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $pages, true )) .'" class="wpr-last-page">';
					    		echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
					    		echo Utilities::get_wpr_icon( $settings['pagination_fl_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="wpr-last-page wpr-disabled-arrow">';
					    		echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
					    		echo Utilities::get_wpr_icon( $settings['pagination_fl_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    	echo '</span>';
				    	}
				    }

				    echo '</div>';
			    }
			}

		// Load More / Infinite Scroll
		} else {
			echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-load-more-btn" data-e-disable-page-transition>';
			// echo '<a href="'. esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ) .'" class="wpr-load-more-btn" data-e-disable-page-transition>';
			// echo '<a href="'. esc_url(get_next_posts_page_link()) .'" class="wpr-load-more-btn" data-e-disable-page-transition>';
				echo esc_html($settings['pagination_load_more_text']);
			echo '</a>';

			echo '<div class="wpr-pagination-loading">';
				switch ( $settings['pagination_animation'] ) {
					case 'loader-1':
						echo '<div class="wpr-double-bounce">';
							echo '<div class="wpr-child wpr-double-bounce1"></div>';
							echo '<div class="wpr-child wpr-double-bounce2"></div>';
						echo '</div>';
						break;
					case 'loader-2':
						echo '<div class="wpr-wave">';
							echo '<div class="wpr-rect wpr-rect1"></div>';
							echo '<div class="wpr-rect wpr-rect2"></div>';
							echo '<div class="wpr-rect wpr-rect3"></div>';
							echo '<div class="wpr-rect wpr-rect4"></div>';
							echo '<div class="wpr-rect wpr-rect5"></div>';
						echo '</div>';
						break;
					case 'loader-3':
						echo '<div class="wpr-spinner wpr-spinner-pulse"></div>';
						break;
					case 'loader-4':
						echo '<div class="wpr-chasing-dots">';
							echo '<div class="wpr-child wpr-dot1"></div>';
							echo '<div class="wpr-child wpr-dot2"></div>';
						echo '</div>';
						break;
					case 'loader-5':
						echo '<div class="wpr-three-bounce">';
							echo '<div class="wpr-child wpr-bounce1"></div>';
							echo '<div class="wpr-child wpr-bounce2"></div>';
							echo '<div class="wpr-child wpr-bounce3"></div>';
						echo '</div>';
						break;
					case 'loader-6':
						echo '<div class="wpr-fading-circle">';
							echo '<div class="wpr-circle wpr-circle1"></div>';
							echo '<div class="wpr-circle wpr-circle2"></div>';
							echo '<div class="wpr-circle wpr-circle3"></div>';
							echo '<div class="wpr-circle wpr-circle4"></div>';
							echo '<div class="wpr-circle wpr-circle5"></div>';
							echo '<div class="wpr-circle wpr-circle6"></div>';
							echo '<div class="wpr-circle wpr-circle7"></div>';
							echo '<div class="wpr-circle wpr-circle8"></div>';
							echo '<div class="wpr-circle wpr-circle9"></div>';
							echo '<div class="wpr-circle wpr-circle10"></div>';
							echo '<div class="wpr-circle wpr-circle11"></div>';
							echo '<div class="wpr-circle wpr-circle12"></div>';
						echo '</div>';
						break;
					
					default:
						break;
				}
			echo '</div>';

			echo '<p class="wpr-pagination-finish">'. esc_html($settings['pagination_finish_text']) .'</p>';
		}

		echo '</div>';
	}

	// Grid Settings
	public function add_grid_settings( $settings ) {
		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['layout_select'] = 'pro-ms' == $settings['layout_select'] ? 'fitRows' : $settings['layout_select'];
			$settings['filters_deeplinking'] = '';
			$settings['filters_count'] = '';
			$settings['filters_default_filter'] = '';

			if ( 'pro-fd' == $settings['filters_animation'] || 'pro-fs' == $settings['filters_animation'] ) {
				$settings['filters_animation'] = 'zoom';
			}
		}

		if ( 'fitRows' == $settings['layout_select'] ) {
			$stick_last_element_to_bottom = $settings['stick_last_element_to_bottom'];
		} else {
			$stick_last_element_to_bottom = 'no';
		}

		$gutter_hr_widescreen = isset($settings['layout_gutter_hr_widescreen']['size']) ? $settings['layout_gutter_hr_widescreen']['size'] : $settings['layout_gutter_hr']['size'];
		$gutter_hr_desktop = $settings['layout_gutter_hr']['size'];
		$gutter_hr_laptop = isset($settings['layout_gutter_hr_laptop']['size']) ? $settings['layout_gutter_hr_laptop']['size'] : $gutter_hr_desktop;
		$gutter_hr_tablet_extra = isset($settings['layout_gutter_hr_tablet_extra']['size']) ? $settings['layout_gutter_hr_tablet_extra']['size'] : $gutter_hr_laptop;
		$gutter_hr_tablet = isset($settings['layout_gutter_hr_tablet']['size']) ? $settings['layout_gutter_hr_tablet']['size'] : $gutter_hr_tablet_extra;
		$gutter_hr_mobile_extra = isset($settings['layout_gutter_hr_mobile_extra']['size']) ? $settings['layout_gutter_hr_mobile_extra']['size'] : $gutter_hr_tablet;
		$gutter_hr_mobile = isset($settings['layout_gutter_hr_mobile']['size']) ? $settings['layout_gutter_hr_mobile']['size'] : $gutter_hr_mobile_extra;

		$gutter_vr_widescreen = isset($settings['layout_gutter_vr_widescreen']['size']) ? $settings['layout_gutter_vr_widescreen']['size'] : $settings['layout_gutter_vr']['size'];
		$gutter_vr_desktop = $settings['layout_gutter_vr']['size'];
		$gutter_vr_laptop = isset($settings['layout_gutter_vr_laptop']['size']) ? $settings['layout_gutter_vr_laptop']['size'] : $gutter_vr_desktop;
		$gutter_vr_tablet_extra = isset($settings['layout_gutter_vr_tablet_extra']['size']) ? $settings['layout_gutter_vr_tablet_extra']['size'] : $gutter_vr_laptop;
		$gutter_vr_tablet = isset($settings['layout_gutter_vr_tablet']['size']) ? $settings['layout_gutter_vr_tablet']['size'] : $gutter_vr_tablet_extra;
		$gutter_vr_mobile_extra = isset($settings['layout_gutter_vr_mobile_extra']['size']) ? $settings['layout_gutter_vr_mobile_extra']['size'] : $gutter_vr_tablet;
		$gutter_vr_mobile = isset($settings['layout_gutter_vr_mobile']['size']) ? $settings['layout_gutter_vr_mobile']['size'] : $gutter_vr_mobile_extra;

		$layout_settings = [
			'layout' => $settings['layout_select'],
			'stick_last_element_to_bottom' => $stick_last_element_to_bottom,
			'columns_desktop' => $settings['layout_columns'],
			'gutter_hr' => $gutter_hr_desktop,
			'gutter_hr_mobile' => $gutter_hr_mobile,
			'gutter_hr_mobile_extra' => $gutter_hr_mobile_extra,
			'gutter_hr_tablet' => $gutter_hr_tablet,
			'gutter_hr_tablet_extra' => $gutter_hr_tablet_extra,
			'gutter_hr_laptop' => $gutter_hr_laptop,
			'gutter_hr_widescreen' => $gutter_hr_widescreen,
			'gutter_vr' => $gutter_vr_desktop,
			'gutter_vr_mobile' => $gutter_vr_mobile,
			'gutter_vr_mobile_extra' => $gutter_vr_mobile_extra,
			'gutter_vr_tablet' => $gutter_vr_tablet,
			'gutter_vr_tablet_extra' => $gutter_vr_tablet_extra,
			'gutter_vr_laptop' => $gutter_vr_laptop,
			'gutter_vr_widescreen' => $gutter_vr_widescreen,
			'animation' => $settings['layout_animation'],
			'animation_duration' => $settings['layout_animation_duration'],
			'animation_delay' => $settings['layout_animation_delay'],
			'deeplinking' => $settings['filters_deeplinking'],
			'filters_linkable' => $settings['filters_linkable'],
			'filters_default_filter' => $settings['filters_default_filter'],
			'filters_count' => $settings['filters_count'],
			'filters_hide_empty' => $settings['filters_hide_empty'],
			'filters_animation' => $settings['filters_animation'],
			'filters_animation_duration' => $settings['filters_animation_duration'],
			'filters_animation_delay' => $settings['filters_animation_delay'],
			'pagination_type' => $settings['pagination_type'],
			'pagination_max_pages' => $this->get_max_num_pages( $settings ),
		];

		if ( 'list' === $settings['layout_select'] ) {
			$layout_settings['media_align'] = $settings['layout_list_align'];
			$layout_settings['media_width'] = $settings['layout_list_media_width']['size'];
			$layout_settings['media_distance'] = $settings['layout_list_media_distance']['size'];
		}

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		$layout_settings['lightbox'] = [
			'selector' => '.wpr-grid-image-wrap',
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

		$this->add_render_attribute( 'grid-settings', [
			'data-settings' => wp_json_encode( $layout_settings ),
		] );
	}

	public function add_slider_settings( $settings ) {
		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['layout_slider_autoplay'] = '';
			$settings['layout_slider_autoplay_duration'] = 0;
			$settings['layout_slider_pause_on_hover'] = '';
		}

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'infinite' => ( $settings['layout_slider_loop'] === 'yes' ),
			'speed' => absint( $settings['layout_slider_effect_duration'] * 1000 ),
			'arrows' => true,
			'dots' => true,
			'autoplay' => ( $settings['layout_slider_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['layout_slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['layout_slider_pause_on_hover'],
			'prevArrow' => '#wpr-grid-slider-prev-'. $this->get_id(),
			'nextArrow' => '#wpr-grid-slider-next-'. $this->get_id(),
			'sliderSlidesToScroll' => +$settings['layout_slides_to_scroll'],
		];

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		// Lightbox Settings
		$slider_options['lightbox'] = [
			'selector' => 'article:not(.slick-cloned) .wpr-grid-image-wrap',
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

		if ( $settings['layout_slider_amount'] === 1 && $settings['layout_slider_effect'] === 'fade' ) {
			$slider_options['fade'] = true;
		}

		$this->add_render_attribute( 'slider-settings', [
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
		] );
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<h2>'. esc_html__( 'WooCommerce is NOT active!', 'wpr-addons' ) .'</h2>';
			return;
		}
		// Get Posts
		$posts = new \WP_Query( $this->get_main_query_args() );

		// Loop: Start
		if ( $posts->have_posts() ) :

		$post_index = 0;

		if ( ('upsell' === $settings['query_selection'] && '' !== $settings['grid_linked_products_heading']) || ('cross-sell' === $settings['query_selection'] && '' !== $settings['grid_linked_products_heading']) ) {
			echo '<div class="wpr-grid-linked-products-heading">';
				echo '<'. $settings['grid_linked_products_heading_tag'] .'>'. esc_html( $settings['grid_linked_products_heading'] ) .'</'. $settings['grid_linked_products_heading_tag'] .'>';
			echo '</div>';
		}
		
		// Grid Settings
		if ( 'slider' !== $settings['layout_select'] ) {
			
			if ( 'upsell' !== $settings['query_selection'] && 'cross-sell' !== $settings['query_selection'] ) {
				// Sort & Results
				$this->render_grid_sorting( $settings, $posts );

				if ( !((is_product_category() || is_product_tag()) && !wpr_fs()->can_use_premium_code()) ) {
					// Filters
					$this->render_grid_filters( $settings );
				}
			}

			$this->add_grid_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'grid-settings' );

		// Slider Settings
		} else {
			$this->add_slider_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'slider-settings' );
		}

		// Grid Wrap
		echo '<section class="wpr-grid elementor-clearfix" '. $render_attribute .' data-found-posts = '. $posts->found_posts .'>';


		while ( $posts->have_posts() ) : $posts->the_post();
			// $post_index++;
			// if ( Utilities::is_new_free_user() && $post_index > 12 ) {
			// 	return;
			// }

			// Post Class
			$post_class = implode( ' ', get_post_class( 'wpr-grid-item elementor-clearfix', get_the_ID() ) );

			// Grid Item
			echo '<article class="'. esc_attr( $post_class ) .'">';

			// Password Protected Form
			$this->render_password_protected_input( $settings );

			// Inner Wrapper
			echo '<div class="wpr-grid-item-inner">';

			// Content: Above Media
			$this->get_elements_by_location( 'above', $settings, get_the_ID() );

			// Media
			echo '<div class="wpr-grid-media-wrap'. esc_attr($this->get_image_effect_class( $settings )) .' " data-overlay-link="'. esc_attr( $settings['overlay_post_link'] ) .'">';
				// Post Thumbnail
				$this->render_product_thumbnail( $settings, get_the_ID() );

				// Media Hover
				echo '<div class="wpr-grid-media-hover wpr-animation-wrap">';
					// Media Overlay
					$this->render_media_overlay( $settings );

					// Content: Over Media
					$this->get_elements_by_location( 'over', $settings, get_the_ID() );

				echo '</div>';
			echo '</div>';

			// Content: Below Media
			$this->get_elements_by_location( 'below', $settings, get_the_ID() );

			echo '</div>'; // End .wpr-grid-item-inner

			echo '</article>'; // End .wpr-grid-item

		endwhile;

		// reset
		wp_reset_postdata();

		// Grid Wrap
		echo '</section>';

		if ( 'slider' === $settings['layout_select'] ) {
			if ( $posts->found_posts > (int) $settings['layout_slider_amount'] &&  (int) $settings['layout_slider_amount'] < $settings['query_posts_per_page'] ) {
				// Slider Navigation
				echo '<div class="wpr-grid-slider-arrow-container">';
					echo '<div class="wpr-grid-slider-prev-arrow wpr-grid-slider-arrow" id="wpr-grid-slider-prev-'. esc_attr($this->get_id()) .'">'. Utilities::get_wpr_icon( $settings['layout_slider_nav_icon'], '' ) .'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<div class="wpr-grid-slider-next-arrow wpr-grid-slider-arrow" id="wpr-grid-slider-next-'. esc_attr($this->get_id()) .'">'. Utilities::get_wpr_icon( $settings['layout_slider_nav_icon'], '' ) .'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';

				// Slider Dots
				echo '<div class="wpr-grid-slider-dots"></div>';
			}
		}


		// Pagination
		$this->render_grid_pagination( $settings );

		// No Posts Found
		else:

			if ('upsell' !== $settings['query_selection'] && 'cross-sell' !== $settings['query_selection']) {
				echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
			}

		// Loop: End
		endif;
	}
	
}