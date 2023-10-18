<?php
namespace WprAddons\Modules\MagazineGrid\Widgets;

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

class Wpr_Magazine_Grid extends Widget_Base {
	
	public function get_name() {
		return 'wpr-magazine-grid';
	}

	public function get_title() {
		return esc_html__( 'Magazine Grid/Slider', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-gallery-grid';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('archive') ? [ 'wpr-theme-builder-widgets' ] : [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'blog', 'magazin grid', 'magazin slider', 'isotope', 'post tiles', 'posts tiles' ];
	}

	public function get_script_depends() {
		return [ 'wpr-isotope', 'wpr-slick' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-magazine-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_option_query_source() {
		$post_types = [];
		$post_types['post'] = esc_html__( 'Posts', 'wpr-addons' );
		$post_types['page'] = esc_html__( 'Pages', 'wpr-addons' );

		$custom_post_types = Utilities::get_custom_types_of( 'post', true );
		foreach( $custom_post_types as $slug => $title ) {
			if ( 'product' === $slug || 'e-landing-page' === $slug ) {
				continue;
			}

			if ( !wpr_fs()->can_use_premium_code() ) {
				$post_types['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Expert)';
			} else {
				$post_types[$slug] = esc_html( $title );
			}
		}

		$post_types['pro-cr'] = esc_html__( 'Current Query (Pro)', 'wpr-addons' );
		$post_types['pro-rl'] = esc_html__( 'Related Query (Pro)', 'wpr-addons' );
		
		return $post_types;
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

	public function add_control_query_randomize() {
		$this->add_control(
			'query_randomize',
			[
				'label' => sprintf( __( 'Randomize Query %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

	public function add_control_order_posts() {
        $this->add_control(
			'order_posts',
			[
				'label' => esc_html__( 'Order By', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'label_block' => false,
				'options' => [
					'date' => esc_html__( 'Date', 'wpr-addons'),
					'pro-tl' => esc_html__( 'Title (Pro)', 'wpr-addons'),
					'pro-mf' => esc_html__( 'Last Modified (Pro)', 'wpr-addons'),
					'pro-d' => esc_html__( 'Post ID', 'wpr-addons' ),
					'pro-ar' => esc_html__( 'Post Author', 'wpr-addons' ),
					'pro-cc' => esc_html__( 'Comment Count', 'wpr-addons' )
				],
				'condition' => [
					'query_randomize!' => 'rand',
				]
			]
		);
	}

	public function add_control_force_responsive_one_column() {
		$this->add_control(
			'force_responsive_one_column',
			[
				'label' => sprintf( __( 'Force Responsive One Column %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_option_element_select() {
		return [
			'title' => esc_html__( 'Title', 'wpr-addons' ),
			'content' => esc_html__( 'Content', 'wpr-addons' ),
			'excerpt' => esc_html__( 'Excerpt', 'wpr-addons' ),
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'time' => esc_html__( 'Time', 'wpr-addons' ),
			'author' => esc_html__( 'Author', 'wpr-addons' ),
			'comments' => esc_html__( 'Comments', 'wpr-addons' ),
			'read-more' => esc_html__( 'Read More', 'wpr-addons' ),
			'separator' => esc_html__( 'Separator', 'wpr-addons' ),
			'pro-lk' => esc_html__( 'Likes (Pro)', 'wpr-addons' ),
			'pro-shr' => esc_html__( 'Sharing (Pro)', 'wpr-addons' ),
			'pro-cf' => esc_html__( 'Custom Field (Expert)', 'wpr-addons' ),
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

	public function add_repeater_args_element_custom_field($meta) {
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

	public function add_repeater_args_custom_field_wrapper_html_divider1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_wrapper() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_custom_field_wrapper_html() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_custom_field_wrapper_html_divider2() {
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

	public function add_repeater_args_element_trim_text_by() {
		return [
			'word_count' => esc_html__( 'Word Count', 'wpr-addons' ),
			'pro-lc' => esc_html__( 'Letter Count (Pro)', 'wpr-addons' )
		];
	}
	
	public function add_control_overlay_animation_divider() {}
	
	public function add_control_overlay_image() {}
	
	public function add_control_overlay_image_width() {}
	
	public function add_section_slider() {
		$this->start_controls_section(
			'section_slider',
			[
				'label' => esc_html__( 'Posts Slider', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'slider_section_pro_notice',
            [
				'raw' => 'Tiled Post Slider is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-magazine-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
				// 'raw' => 'Tiled Post Slider is available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'wpr-pro-notice',
			]
        );

		$this->end_controls_section(); // End Controls Section
	}
	
	public function add_control_title_pointer_color_hr() {}
	
	public function add_control_title_pointer() {}
	
	public function add_control_title_pointer_height() {}
	
	public function add_control_title_pointer_animation() {}
	
	public function add_control_tax1_pointer_color_hr() {}
	
	public function add_control_tax1_pointer() {}
	
	public function add_control_tax1_pointer_height() {}
	
	public function add_control_tax1_pointer_animation() {}
	
	public function add_control_tax2_pointer_color_hr() {}
	
	public function add_control_tax2_pointer() {}
	
	public function add_control_tax2_pointer_height() {}
	
	public function add_control_tax2_pointer_animation() {}
	
	public function add_control_read_more_animation() {}
	
	public function add_control_read_more_animation_height() {}
	
	public function add_section_style_custom_field1() {}
	
	public function add_section_style_custom_field2() {}
	
	public function add_section_style_grid_slider_nav() {}
	
	public function add_section_style_likes() {}
	
	public function add_section_style_sharing() {}

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

		// Get Available Post Types
		$post_types = $this->add_option_query_source();

		// Remove WooCommerce
		unset( $post_types['product'] );
		unset( $post_types['e-landing-page'] );

		// Get Available Taxonomies
		$post_taxonomies = Utilities::get_custom_types_of( 'tax', false );

		// Get Available Meta Keys
		$post_meta_keys = Utilities::get_custom_meta_keys();

		$this->add_control(
			'query_source',
			[
				'label' => esc_html__( 'Source', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $post_types,
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'magazine-grid', 'query_source', ['pro-rl', 'pro-cr'] );

		if ( !wpr_fs()->is_plan( 'expert' ) ) {
			$this->add_control(
				'query_source_cpt_pro_notice',
				[
					'raw' => 'This option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-grid-upgrade-expert#purchasepro" target="_blank">Expert version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'query_source!' => ['post','page','related','current','pro-rl','pro-cr'],
					]
				]
			);
		}

		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Selection', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'wpr-addons' ),
					'manual' => esc_html__( 'Manual', 'wpr-addons' ),
				],
				'condition' => [
					'query_source!' => [ 'current', 'related' ],
				],
			]
		);

		$this->add_control_order_posts();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'order_posts', ['pro-tl', 'pro-mf', 'pro-d', 'pro-ar', 'pro-cc'] );

        $this->add_control(
			'order_direction',
			[
				'label' => esc_html__( 'Order', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'label_block' => false,
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'wpr-addons'),
					'DESC' => esc_html__( 'Descending', 'wpr-addons'),
				],
				'condition' => [
					'query_randomize!' => 'rand',
				]
			]
		);

		$this->add_control(
			'query_tax_selection',
			[
				'label' => esc_html__( 'Select Taxonomy', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $post_taxonomies,
				'condition' => [
					'query_source' => 'related',
				],
			]
		);

		$this->add_control(
			'query_author',
			[
				'label' => esc_html__( 'Authors', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_users',
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'condition' => [
					'query_source!' => [ 'current', 'related' ],
					'query_selection' => 'dynamic',
				],
			]
		);

		// Taxonomies
		foreach ( $post_taxonomies as $slug => $title ) {
			global $wp_taxonomies;
			$post_type = '';

			if ( isset($wp_taxonomies[$slug]) && isset($wp_taxonomies[$slug]->object_type[0]) ) {
				$post_type = $wp_taxonomies[$slug]->object_type[0];
			}

			$this->add_control(
				'query_taxonomy_'. $slug,
				[
					'label' => $title,
					'type' => 'wpr-ajax-select2',
					'options' => 'ajaxselect2/get_taxonomies',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $post_type,
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		// Exclude
		foreach ( $post_types as $slug => $title ) {
			$this->add_control(
				'query_exclude_'. $slug,
				[
					'label' => esc_html__( 'Exclude ', 'wpr-addons' ) . $title,
					'type' => 'wpr-ajax-select2',
					'options' => 'ajaxselect2/get_posts_by_post_type',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $slug,
						'query_source!' => [ 'current', 'related' ],
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		// Manual Selection
		foreach ( $post_types as $slug => $title ) {
			$this->add_control(
				'query_manual_'. $slug,
				[
					'label' => esc_html__( 'Select ', 'wpr-addons' ) . $title,
					'type' => 'wpr-ajax-select2',
					'options' => 'ajaxselect2/get_posts_by_post_type',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'query_source' => $slug,
						'query_selection' => 'manual',
					],
					'separator' => 'before',
				]
			);
		}

		$this->add_control(
			'query_offset',
			[
				'label' => esc_html__( 'Offset', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'condition' => [
					'query_selection' => 'dynamic',
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
					'query_selection' => 'dynamic',
					'query_source!' => 'related',
				]
			]
		);

		$this->add_control_query_randomize();

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
			'element_select_filter',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => $this->get_related_taxonomies(),
			]
		);

		$this->add_control(
			'post_meta_keys_filter',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => json_encode( $post_meta_keys[0] ),
			]
		);

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

		$this->add_control(
			'layout_select',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => '1-1-3',
				'options' => [
					'1-2' => [
						'title' => esc_html__( 'Layout 1 (3 posts) (Pro)', 'wpr-addons' ),
					],
					'1-3' => [
						'title' => esc_html__( 'Layout 2 (4 posts) (Pro)', 'wpr-addons' ),
					],
					'1-4' => [
						'title' => esc_html__( 'Layout 3 (5 posts) (Pro)', 'wpr-addons' ),
					],
					'1-1-2' => [
						'title' => esc_html__( 'Layout 4 (4 posts) (Pro)', 'wpr-addons' ),
					],
					'2-1-2' => [
						'title' => esc_html__( 'Layout 5 (5 posts) (Pro)', 'wpr-addons' ),
					],
					'1vh-3h' => [
						'title' => esc_html__( 'Layout 5.0 (4 posts) (Pro)', 'wpr-addons' ),
					],
					'1-1-1' => [
						'title' => esc_html__( 'Layout 5.1 (3 posts)', 'wpr-addons' ),
					],
					'1-1-3' => [
						'title' => esc_html__( 'Layout 5.2 (5 posts)', 'wpr-addons' ),
					],
					'2-3' => [
						'title' => esc_html__( 'Layout 6 (5 posts)', 'wpr-addons' ),
					],
					'2-h' => [
						'title' => esc_html__( 'Layout 7 (2, 4, 6 posts)', 'wpr-addons' ),
					],
					'3-h' => [
						'title' => esc_html__( 'Layout 8 (3, 6, 9 posts)', 'wpr-addons' ),
					],
					'4-h' => [
						'title' => esc_html__( 'Layout 8 (4, 8, 12 posts)', 'wpr-addons' ),
					]
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
	            'layout_select_pro_notice',
	            [
					'raw' => 'This Layout options are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-magazine-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'This Layout options are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'layout_select' => ['1-2','1-3','1-4','1-1-2','2-1-2','1vh-3h']
					]
				]
	        );
		}

		$this->add_control_force_responsive_one_column();

		// GOGA - better location
		$this->add_control_open_links_in_new_tab();

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'layout_image_crop',
				'default' => 'full',
			]
		);

		$this->add_control(
			'layout_rows_number',
			[
				'label' => esc_html__( 'Rows Number', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => '1',
				'options' => [
					'1' => esc_html__( '1 Row', 'wpr-addons' ),
					'2' => esc_html__( '2 Rows', 'wpr-addons' ),
					'3' => esc_html__( '3 Rows', 'wpr-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mgzn-grid-2-h' => 'grid-template-rows: repeat({{VALUE}}, 1fr);',
					'{{WRAPPER}} .wpr-mgzn-grid-3-h' => 'grid-template-rows: repeat({{VALUE}}, 1fr);',
					'{{WRAPPER}} .wpr-mgzn-grid-4-h' => 'grid-template-rows: repeat({{VALUE}}, 1fr);',
				],
				'condition' => [
					'layout_select' => [ '2-h', '3-h', '4-h' ]
				],
			]
		);

		$this->add_responsive_control(
			'layout_container_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Container Height', 'wpr-addons' ),
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 520,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-magazine-grid' => 'min-height: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'layout_big_post_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Featured Box Width', 'wpr-addons' ),
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mgzn-grid-1-2' => 'grid-template-columns: {{SIZE}}% 1fr; -ms-grid-columns: {{SIZE}}% 1fr;',
					'{{WRAPPER}} .wpr-mgzn-grid-1-3' => 'grid-template-columns: {{SIZE}}% 1fr 1fr; -ms-grid-columns: {{SIZE}}% 1fr 1fr;',
					'{{WRAPPER}} .wpr-mgzn-grid-1-4' => 'grid-template-columns: {{SIZE}}% 1fr 1fr; -ms-grid-columns: {{SIZE}}% 1fr 1fr;',
					'{{WRAPPER}} .wpr-mgzn-grid-1-1-2' => 'grid-template-columns: {{SIZE}}% 1fr 1fr; -ms-grid-columns: {{SIZE}}% 1fr 1fr;',
					'{{WRAPPER}} .wpr-mgzn-grid-1vh-3h' => 'grid-template-columns: {{SIZE}}% 1fr; -ms-grid-columns: {{SIZE}}% 1fr;',
					'{{WRAPPER}} .wpr-mgzn-grid-2-1-2' => 'grid-template-columns: 1fr {{SIZE}}% 1fr; -ms-grid-columns: 1fr {{SIZE}}% 1fr;',
					'{{WRAPPER}} .wpr-mgzn-grid-1-1-1' => 'grid-template-columns: 1fr {{SIZE}}% 1fr; -ms-grid-columns: 1fr {{SIZE}}% 1fr;',
				],
				'condition' => [
					'layout_select' => [ '1-2', '1-3', '1-4', '1-1-2', '1vh-3h', '2-1-2', '1-1-1' ]
				]
			]
		);

		$this->add_responsive_control(
			'layout_gutter_hr',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 4,
				],
				'widescreen_default' => [
					'size' => 4,
				],
				'laptop_default' => [
					'size' => 4,
				],
				'tablet_extra_default' => [
					'size' => 4,
				],
				'tablet_default' => [
					'size' => 4,
				],
				'mobile_extra_default' => [
					'size' => 4,
				],
				'mobile_default' => [
					'size' => 4,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-magazine-grid' => 'grid-column-gap: {{SIZE}}px;',
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
					'size' => 4,
				],
				'widescreen_default' => [
					'size' => 4,
				],
				'laptop_default' => [
					'size' => 4,
				],
				'tablet_extra_default' => [
					'size' => 4,
				],
				'tablet_default' => [
					'size' => 4,
				],
				'mobile_extra_default' => [
					'size' => 4,
				],
				'mobile_default' => [
					'size' => 4,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-magazine-grid' => 'grid-row-gap: {{SIZE}}px;',
				],
				'separator' => 'after'
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
				'options' => array_merge( $element_select, $post_taxonomies ),
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'show_last_update_date',
			[
				'label' => esc_html__( 'Show Last Update Date', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => 'date',
				]
			]
		);

		$repeater->add_control(
			'element_custom_field_info_video_tutorial',
			[
				'raw' => esc_html__( 'Watch Custom Fields ', 'wpr-addons' ) . sprintf( '<a href="%1$s" target="_blank">%2$s <span class="dashicons dashicons-video-alt3"></span></a>', 'https://www.youtube.com/watch?v=9GvpqyHF_Cs', esc_html__( 'Video Tutorial', 'wpr-addons' ) ),
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'element_select' => ['custom-field', 'pro-cf']
				]
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'magazine-grid', 'element_select', ['pro-lk', 'pro-shr']);
		Utilities::upgrade_expert_notice( $repeater, Controls_Manager::RAW_HTML, 'magazine-grid', 'element_select', ['pro-cf'] );

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
					'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-magazine-grid-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'Vertical Align option is available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
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
			]
		);

		$repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
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
			'element_dropcap',
			[
				'label' => esc_html__( 'Enable Drop Cap', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [ 'content', 'excerpt' ],
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
			'element_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [ 'author' ]
				]
			]
		);

		$repeater->add_control(
			'element_avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 32,
				'min' => 8,
				'condition' => [
					'element_select' => [ 'author' ],
					'element_show_avatar' => 'yes'
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_read_more_text',
			[
				'label' => esc_html__( 'Read More Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Read More',
				'condition' => [
					'element_select' => [ 'read-more' ],
				],
				'separator' => 'after'
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
						'content',
						'excerpt',
						'date',
						'time',
						'author',
						'comments',
						'read-more',
						'likes',
						'sharing',
						'custom-field',
						'separator',
						'post_format',
					],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_tax_style',
			[
				'label' => esc_html__( 'Select Styling', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'wpr-grid-tax-style-1',
				'options' => [
					'wpr-grid-tax-style-1' => esc_html__( 'Taxonomy Style 1', 'wpr-addons' ),
					'wpr-grid-tax-style-2' => esc_html__( 'Taxonomy Style 2', 'wpr-addons' ),
				],
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'excerpt',
						'date',
						'time',
						'author',
						'comments',
						'read-more',
						'likes',
						'sharing',
						'custom-field',
						'separator',
					],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_comments_text_1',
			[
				'label' => esc_html__( 'No Comments', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'No Comments',
				'condition' => [
					'element_select' => [ 'comments' ],
				]
			]
		);

		$repeater->add_control(
			'element_comments_text_2',
			[
				'label' => esc_html__( 'One Comment', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comment',
				'condition' => [
					'element_select' => [ 'comments' ],
				]
			]
		);

		$repeater->add_control(
			'element_comments_text_3',
			[
				'label' => esc_html__( 'Multiple Comments', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comments',
				'condition' => [
					'element_select' => [ 'comments' ],
				],
				'separator' => 'after'
			]
		);

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

		$repeater->add_control( 'element_custom_field', $this->add_repeater_args_element_custom_field($post_meta_keys[1]) );

		$repeater->add_control( 'element_custom_field_btn_link', $this->add_repeater_args_element_custom_field_btn_link() );

		$repeater->add_control( 'element_custom_field_new_tab', $this->add_repeater_args_element_custom_field_new_tab() );

		$repeater->add_control( 'custom_field_wrapper_html_divider1', $this->add_repeater_args_custom_field_wrapper_html_divider1() );

		$repeater->add_control( 'element_custom_field_wrapper', $this->add_repeater_args_element_custom_field_wrapper() );

		$repeater->add_control( 'element_custom_field_wrapper_html', $this->add_repeater_args_element_custom_field_wrapper_html() );

		$repeater->add_control( 'custom_field_wrapper_html_divider2', $this->add_repeater_args_custom_field_wrapper_html_divider2() );

		$repeater->add_control( 'element_custom_field_style', $this->add_repeater_args_element_custom_field_style() );

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
						'content',
						'excerpt',
						'read-more',
						'separator',
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
						'content',
						'excerpt',
						'read-more',
						'separator',
					],
					'element_extra_text_pos!' => 'none'
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
						'content',
						'excerpt',
						'separator',
						'likes',
						'sharing',
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
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'excerpt',
						'separator',
						'likes',
						'sharing',
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
			]
		);

		// Upgrade to Pro Notice :TODO
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'magazine-grid', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

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
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'magazine-grid', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

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
				'default' => 'medium',
				'condition' => [
					'element_animation!' => 'none',
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
						'element_select' => 'category',
						'element_align_vr' => 'bottom',
						'element_align_hr' => 'left',
					],
					[
						'element_select' => 'title',
						'element_align_vr' => 'bottom',
						'element_align_hr' => 'left',
					],
					[
						'element_select' => 'author',
						'element_align_vr' => 'bottom',
						'element_display' => 'inline',
						'element_align_hr' => 'left',
					],
					[
						'element_select' => 'separator',
						'element_align_vr' => 'bottom',
						'element_display' => 'inline',
						'element_align_hr' => 'left',
					],
					[
						'element_select' => 'date',
						'element_align_vr' => 'bottom',
						'element_display' => 'inline',
						'element_align_hr' => 'left',
					],
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Posts Slider -----
		$this->add_section_slider();

		// Tab: Content ==============
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
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
				'default' => '',
				'return_value' => 'yes',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'fade-out',
			]
		);

		// Upgrade to Pro Notice :TODO
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'magazine-grid', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'magazine-grid', 'overlay_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

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
				'default' => 'medium',
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
				'separator' => 'after',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control_overlay_animation_divider();

		$this->add_control_overlay_image();

		$this->add_control_overlay_image_width();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'magazine-grid', [
			'Random Posts Query',
			'+6 Magazine Grid Layouts',
			'Magazine Grid Slider',
			'Magazine Grid Slider Autoplay options',
			'Magazine Grid Slider Advanced Navigation Positioning',
			'Magazine Grid Slider Advanced Pagination Positioning',
			'Advanced Post Likes',
			'Advanced Post Sharing',
			'Advanced Grid Elements Positioning',
			'Unlimited Image Overlay Animations',
			'Image overlay GIF upload option',
			'Title, Category, Read More Advanced Link Hover Animations',
			'Open Links in New Tab',
			'Posts Order',
			'Trim Title & Excerpt By Letter Count',
			'Custom Fields Support (Expert)',
			'Custom Post Types Support (Expert)',
		] );
		
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
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'default' => 'gradient',
					],
					'color' => [
						'default' => 'rgba(255, 255, 255, 0)',
					],
					'color_stop' => [
						'default' => [
							'unit' => '%',
							'size' => 46,
						]
					],
					'color_b' => [
						'default' => 'rgba(96, 91, 229, 0.87)',
					]
				],
				'selector' => '{{WRAPPER}} .wpr-grid-media-hover-bg'
			]
		);

		$this->add_control(
			'overlay_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'overlay_border_type',
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
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_border_width',
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
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'overlay_border_type!' => 'none',
				],
			]
		);

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
				'default' => '#ffffff',
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
				'default' => '#FFFFFF',
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
				'default' => 0.1,
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

		$this->add_responsive_control(
			'title_featured_box_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Featured Box Title Font Size', 'wpr-addons' ),
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 38,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mgzn-grid-1-2 article:nth-child(1) .wpr-grid-item-title a' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-mgzn-grid-1-3 article:nth-child(1) .wpr-grid-item-title a' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-mgzn-grid-1-4 article:nth-child(1) .wpr-grid-item-title a' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-mgzn-grid-1-1-2 article:nth-child(1) .wpr-grid-item-title a' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-mgzn-grid-2-1-2 article:nth-child(2) .wpr-grid-item-title a' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-mgzn-grid-1vh-3h article:nth-child(1) .wpr-grid-item-title a' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-mgzn-grid-1-1-1 article:nth-child(2) .wpr-grid-item-title a' => 'font-size: {{SIZE}}px;',
				],
				'condition' => [
					'layout_select' => [ '1-2', '1-3', '1-4', '1-1-2', '2-1-2', '1vh-3h', '1-1-1' ]
				]
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
					'bottom' => 5,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

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
			'content_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-content .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_dropcap_color',
			[
				'label'  => esc_html__( 'DropCap Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3a3a3a',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-content.wpr-enable-dropcap p:first-child:first-letter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-content .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-content .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-content'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_dropcap_typography',
				'label' => esc_html__( 'Drop Cap Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-content.wpr-enable-dropcap p:first-child:first-letter'
			]
		);

		$this->add_responsive_control(
			'content_justify',
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
					'{{WRAPPER}} .wpr-grid-item-content .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'content_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-content .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-content .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
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
					'{{WRAPPER}} .wpr-grid-item-content .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_margin',
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
					'{{WRAPPER}} .wpr-grid-item-content .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-excerpt .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'excerpt_dropcap_color',
			[
				'label'  => esc_html__( 'DropCap Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3a3a3a',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-excerpt.wpr-enable-dropcap p:first-child:first-letter' => 'color: {{VALUE}}',
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_dropcap_typography',
				'label' => esc_html__( 'Drop Cap Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-excerpt.wpr-enable-dropcap p:first-child:first-letter'
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
		// Section: Date -------------
		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-date .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-date .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-date .inner-block > span' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-date .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-date .inner-block [class*="wpr-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-item-date .inner-block [class*="wpr-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-date'
			]
		);

		$this->add_control(
			'date_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-date .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-date .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'date_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-date .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-date .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_icon_spacing',
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
					'{{WRAPPER}} .wpr-grid-item-date .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-date .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'date_padding',
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
					'{{WRAPPER}} .wpr-grid-item-date .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 20,
					'left' => 10,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-date .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Time -------------
		$this->start_controls_section(
			'section_style_time',
			[
				'label' => esc_html__( 'Time', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'time_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-time .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-time .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'time_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-time .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-time .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'time_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-time .inner-block [class*="wpr-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-item-time .inner-block [class*="wpr-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'time_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-time'
			]
		);

		$this->add_control(
			'time_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-time .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'time_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-time .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'time_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'time_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-time .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-time .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'time_icon_spacing',
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
					'{{WRAPPER}} .wpr-grid-item-time .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-time .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'time_padding',
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
					'{{WRAPPER}} .wpr-grid-item-time .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'time_margin',
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
					'{{WRAPPER}} .wpr-grid-item-time .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Author -----------
		$this->start_controls_section(
			'section_style_author',
			[
				'label' => esc_html__( 'Author', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_author_style' );

		$this->start_controls_tab(
			'tab_grid_author_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'author_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'author_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_author_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'author_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'author_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'author_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'author_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-author'
			]
		);

		$this->add_control(
			'author_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'author_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'author_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'author_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-author .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_icon_spacing',
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
					'{{WRAPPER}} .wpr-grid-item-author .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-author .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'author_avatar_spacing',
			[
				'label' => esc_html__( 'Avatar Spacing', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-grid-item-author img' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author_padding',
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
					'{{WRAPPER}} .wpr-grid-item-author .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'author_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 22,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-author .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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

		$this->start_controls_tabs( 'tabs_grid_comments_style' );

		$this->start_controls_tab(
			'tab_grid_comments_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'comments_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'comments_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_comments_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'comments_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'comments_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'comments_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'comments_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'comments_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-comments'
			]
		);

		$this->add_control(
			'comments_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'comments_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'comments_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'comments_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-comments .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-comments .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'comments_icon_spacing',
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
					'{{WRAPPER}} .wpr-grid-item-comments .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-comments .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'comments_padding',
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
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'comments_margin',
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
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'comments_radius',
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
					'{{WRAPPER}} .wpr-grid-item-comments .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Read More --------
		$this->start_controls_section(
			'section_style_read_more',
			[
				'label' => esc_html__( 'Read More', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_read_more_style' );

		$this->start_controls_tab(
			'tab_grid_read_more_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'read_more_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-grid-item-read-more .inner-block a'
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'read_more_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-item-read-more .inner-block a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_read_more_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'read_more_bg_color_hr',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-grid-item-read-more .inner-block a.wpr-button-none:hover, {{WRAPPER}} .wpr-grid-item-read-more .inner-block a:before, {{WRAPPER}} .wpr-grid-item-read-more .inner-block a:after'
			]
		);

		$this->add_control(
			'read_more_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'read_more_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'read_more_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-grid-item-read-more .inner-block :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'read_more_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control_read_more_animation();

		$this->add_control(
			'read_more_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control_read_more_animation_height();

		$this->add_control(
			'read_more_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-item-read-more a'
			]
		);

		$this->add_control(
			'read_more_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'read_more_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'read_more_icon_spacing',
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
					'{{WRAPPER}} .wpr-grid-item-read-more .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-read-more .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'read_more_padding',
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
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'read_more_margin',
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
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'read_more_radius',
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
					'{{WRAPPER}} .wpr-grid-item-read-more .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles =======================
		// Section: Likes ---------------
		$this->add_section_style_likes();

		// Styles =========================
		// Section: Sharing ---------------
		$this->add_section_style_sharing();


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
				'default' => '#ffffff',
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
					'unit' => 'px',
					'size' => 10,
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
					'size' => 1,
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
					'top' => 13,
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
				'default' => '#ffffff',
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
					'top' => 13,
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
		// Section: Taxonomy Style 1 ------
		$this->start_controls_section(
			'section_style_tax1',
			[
				'label' => esc_html__( 'Taxonomy Style 1', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_tax1_style' );

		$this->start_controls_tab(
			'tab_grid_tax1_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tax1_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax1_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block [class*="wpr-grid-extra-icon"] i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block [class*="wpr-grid-extra-icon"] svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_tax1_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tax1_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax1_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_tax1_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_tax1_pointer();

		$this->add_control_tax1_pointer_height();

		$this->add_control_tax1_pointer_animation();

		$this->add_control(
			'tax1_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tax1_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-tax-style-1'
			]
		);

		$this->add_control(
			'tax1_border_type',
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
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax1_border_width',
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
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'tax1_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax1_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax1_icon_spacing',
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
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax1_gutter',
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
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax1_padding',
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
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'tax1_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tax1_radius',
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
					'{{WRAPPER}} .wpr-grid-tax-style-1 .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Taxonomy Style 2 -
		$this->start_controls_section(
			'section_style_tax2',
			[
				'label' => esc_html__( 'Taxonomy Style 2', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_tax2_style' );

		$this->start_controls_tab(
			'tab_grid_tax2_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tax2_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax2_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax2_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_tax2_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tax2_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax2_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax2_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control_tax2_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_tax2_pointer();

		$this->add_control_tax2_pointer_height();

		$this->add_control_tax2_pointer_animation();

		$this->add_control(
			'tax2_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tax2_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-tax-style-2'
			]
		);

		$this->add_control(
			'tax2_border_type',
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
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax2_border_width',
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
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'tax2_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax2_text_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax2_icon_spacing',
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
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax2_gutter',
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
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax2_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 5,
					'bottom' => 2,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'tax2_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'tax2_radius',
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
					'{{WRAPPER}} .wpr-grid-tax-style-2 .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ===============================
		// Section: Custom Field Style 1 --------
		$this->add_section_style_custom_field1();

		// Styles ===============================
		// Section: Custom Field Style 2 --------
		$this->add_section_style_custom_field2();

		// Styles =================================
		// Section: Navigation --------------------
		$this->add_section_style_grid_slider_nav();

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
				'default' => '#9c9c9c',
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
		$query = new \WP_Query( $this->get_main_query_args( 0 ) );
		$max_num_pages = intval( ceil( $query->max_num_pages ) );

		// Reset
		wp_reset_postdata();

		// $max_num_pages
		return $max_num_pages;
	}

	// Main Query Args
	public function get_main_query_args( $slide_offset ) {
		$settings = $this->get_settings();
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		if ( ! wpr_fs()->can_use_premium_code() ) {
			if ( '1-2' === $settings['layout_select'] || '1-3' === $settings['layout_select'] || '1-4' === $settings['layout_select']
				 || '1-1-2' === $settings['layout_select'] || '2-1-2' === $settings['layout_select'] || '1vh-3h' === $settings['layout_select'] ) {
				$settings['layout_select'] = '1-1-3';
			}

			$settings['slider_enable'] = '';
			$settings[ 'query_randomize' ] = '';
			$settings['order_posts'] = 'date';
		}

		if ( '1-2' === $settings['layout_select'] || '1-1-1' === $settings['layout_select'] ) {
			$query_posts_per_page = 3;
		} elseif ( '1-3' === $settings['layout_select'] || '1-1-2' === $settings['layout_select'] || '1vh-3h' === $settings['layout_select'] ) {
			$query_posts_per_page = 4;
		} elseif ( '1-4' === $settings['layout_select'] || '2-1-2' === $settings['layout_select']
		 	|| '1-1-3' === $settings['layout_select'] || '2-3' === $settings['layout_select'] ) {
			$query_posts_per_page = 5;
		} else {
			if ( '2-h' === $settings['layout_select'] ) {
				$query_posts_per_page = 2 * intval($settings['layout_rows_number']);
			} elseif ( '3-h' === $settings['layout_select'] ) {
				$query_posts_per_page = 3 * intval($settings['layout_rows_number']);
			} elseif ( '4-h' === $settings['layout_select'] ) {
				$query_posts_per_page = 4 * intval($settings['layout_rows_number']);
			}
		}
		
		if ( empty($settings['query_offset']) ) {
			$settings[ 'query_offset' ] = 0;
		}

		$offset = ( $paged - 1 ) * $query_posts_per_page + $settings[ 'query_offset' ];
		$query_order_by = '' != $settings['query_randomize'] ? $settings['query_randomize'] : $settings['order_posts'];

		if ( 'yes' === $settings['slider_enable'] ) {
			$offset = $offset + $query_posts_per_page * $slide_offset;
		}

		// Dynamic
		$args = [
			'post_type' => $settings[ 'query_source' ],
			'tax_query' => $this->get_tax_query_args(),
			'post__not_in' => $settings[ 'query_exclude_'. $settings[ 'query_source' ] ],
			'posts_per_page' => $query_posts_per_page,
			'orderby' => $query_order_by,
			'author' => $author,
			'paged' => $paged,
			'offset' => $offset
		];

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_'. $settings[ 'query_source' ] ]) ) {
				$post_ids = $settings[ 'query_manual_'. $settings[ 'query_source' ] ];
			}

			$args = [
				'post_type' => $settings[ 'query_source' ],
				'post__in' => $post_ids,
				'orderby' => $query_order_by,
				'posts_per_page' => $query_posts_per_page,
				'offset' => $query_posts_per_page * $slide_offset
			];
		}

		// Get Post Type
		if ( 'current' === $settings[ 'query_source' ] ) {
			global $wp_query;

			// GOGA - offset
			$args = $wp_query->query_vars;
			$args['posts_per_page'] = $query_posts_per_page;
			$args['orderby'] = $query_order_by;
			$args['offset'] = ( $paged - 1 ) * $query_posts_per_page + intval($settings[ 'query_offset' ]);
		}

		// Related
		if ( 'related' === $settings[ 'query_source' ] ) {
			$args = [
				'post_type' => get_post_type( get_the_ID() ),
				'tax_query' => $this->get_tax_query_args(),
				'post__not_in' => [ get_the_ID() ],
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $query_posts_per_page,
				'orderby' => $query_order_by,
				'offset' => $offset,
			];
		}

		if ( 'rand' !== $query_order_by ) {
			$args['order'] = $settings['order_direction'];
		}

		return $args;
	}

	// Taxonomy Query Args
	public function get_tax_query_args() {
		$settings = $this->get_settings();
		$tax_query = [];

		if ( 'related' === $settings[ 'query_source' ] ) {
			$tax_query = [
				[
					'taxonomy' => $settings['query_tax_selection'],
					'field' => 'term_id',
					'terms' => wp_get_object_terms( get_the_ID(), $settings['query_tax_selection'], array( 'fields' => 'ids' ) ),
				]
			];
		} else {
			foreach ( get_object_taxonomies($settings[ 'query_source' ]) as $tax ) {
				if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
					array_push( $tax_query, [
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $settings[ 'query_taxonomy_'. $tax ]
					] );
				}
			}
		}

		return $tax_query;
	}

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

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
	public function render_post_thumbnail( $settings ) {
		$id = get_post_thumbnail_id();
		$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'layout_image_crop', $settings );
		$alt = '' === wp_get_attachment_caption( $id ) ? get_the_title() : wp_get_attachment_caption( $id );

		if ( has_post_thumbnail() ) {
			echo '<div class="wpr-grid-image-wrap" data-src="'. esc_url( $src ) .'" style="background-image: url('. esc_url( $src ) .')"></div>';
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
	public function render_post_title( $settings, $class ) {
		$title_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $this->get_settings()['title_pointer'];
		$title_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $this->get_settings()['title_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['title_pointer']) && 'none' !== $this->get_settings()['title_pointer']) ? 'class="wpr-pointer-item"' : '';
		$open_links_in_new_tab = 'yes' === $this->get_settings()['open_links_in_new_tab'] ? '_blank' : '_self';

		$class .= ' wpr-pointer-'. $title_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $title_pointer_animation;

		echo '<'. esc_html($settings['element_title_tag']) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a  target="'. $open_links_in_new_tab .'" '. $pointer_item_class .' href="'. esc_url( get_the_permalink() ) .'">';
				if ( 'word_count' === $settings['element_trim_text_by'] ) {
					echo esc_html(wp_trim_words( get_the_title(), $settings['element_word_count'] ));
				} else {
					echo substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) . '...';
				}
				echo '</a>';
			echo '</div>';
		echo '</'. esc_html($settings['element_title_tag']) .'>';
	}

	// Render Post Content
	public function render_post_content( $settings, $class ) {
		$dropcap_class = 'yes' === $settings['element_dropcap'] ? ' wpr-enable-dropcap' : '';
		$class .= $dropcap_class;

		if ( '' === get_the_content() ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo wp_kses_post(get_the_content());
			echo '</div>';
		echo '</div>';
	}

	// Render Post Excerpt
	public function render_post_excerpt( $settings, $class ) {
		$dropcap_class = 'yes' === $settings['element_dropcap'] ? ' wpr-enable-dropcap' : '';
		$class .= $dropcap_class;

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

	// Render Post Date
	public function render_post_date( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
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

				// Date
				if ( 'yes' === $settings['show_last_update_date'] ) {
					echo esc_html(get_the_modified_time(get_option( 'date_format' )));
				} else {
					echo esc_html(apply_filters( 'the_date', get_the_date( '' ), get_option( 'date_format' ), '', '' ));
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
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Time
	public function render_post_time( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
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

				// Time
				echo esc_html(get_the_time( '' ));

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
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Author
	public function render_post_author( $settings, $class ) {
		$author_id =  get_post_field( 'post_author' );

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				// Author
				echo '<a href="'. esc_url( get_author_posts_url( $author_id ) ) .'">';

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="wpr-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}
					if ( 'yes' === $settings['element_show_avatar'] ) {
						echo get_avatar( $author_id, $settings['element_avatar_size'] );
					}

					echo '<span>'. esc_html(get_the_author_meta( 'display_name', $author_id )) .'</span>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();		

					echo '<span class="wpr-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				echo '</a>';

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Comments
	public function render_post_comments( $settings, $class ) {
		$count = get_comments_number();

		if ( comments_open() ) {
			if ( $count == 1 ) {
				$text = $count .'&nbsp;'. $settings['element_comments_text_2'];
			} elseif ( $count > 1 ) {
				$text = $count .'&nbsp;'. $settings['element_comments_text_3'];
			} else {
				$text = $settings['element_comments_text_1'];
			}

			echo '<div class="'. esc_attr($class) .'">';
				echo '<div class="inner-block">';
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Comments
					echo '<a href="'. esc_url( get_comments_link() ) .'">';

					// Icon: Before
					if ( 'before' === $settings['element_extra_icon_pos'] ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
						$extra_icon = ob_get_clean();
			
						echo '<span class="wpr-grid-extra-icon-left">';
							echo $extra_icon;
						echo '</span>';
					}

					echo '<span>'. esc_html($text) .'</span>';

					// Icon: After
					if ( 'after' === $settings['element_extra_icon_pos'] ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
						$extra_icon = ob_get_clean();
			
						echo '<span class="wpr-grid-extra-icon-right">';
							echo $extra_icon;
					 	echo '</span>';
					}

					echo '</a>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}
				echo '</div>';
			echo '</div>';
		}
	}

	// Render Post Read More
	public function render_post_read_more( $settings, $class ) {
		$read_more_animation = ! wpr_fs()->can_use_premium_code() ? 'wpr-button-none' : $this->get_settings()['read_more_animation'];
		$open_links_in_new_tab = 'yes' === $this->get_settings()['open_links_in_new_tab'] ? '_blank' : '_self';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a target="'. $open_links_in_new_tab .'" href="'. esc_url( get_the_permalink() ) .'" class="wpr-button-effect '. esc_attr($read_more_animation) .'">';

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="wpr-grid-extra-icon-left">';
						echo $extra_icon;
				 	echo '</span>';
				}

				// Read More Text
				echo '<span>'. esc_html( $settings['element_read_more_text'] ) .'</span>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="wpr-grid-extra-icon-right">';
						echo $extra_icon;
				 	echo '</span>';
				}

				echo '</a>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Likes
	public function render_post_likes( $settings, $class, $post_id ) {}

	// Render Post Sharing
	public function render_post_sharing_icons( $settings, $class ) {}

	// Render Post Custom Field
	public function render_post_custom_field( $settings, $class, $post_id ) {}

	// Render Post Element Separator
	public function render_post_element_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class .' '. $settings['element_separator_style']) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Render Post Taxonomies
	public function render_post_taxonomies( $settings, $class, $post_id ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		$tax1_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $this->get_settings()['tax1_pointer'];
		$tax1_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $this->get_settings()['tax1_pointer_animation'];
		$tax2_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $this->get_settings()['tax2_pointer'];
		$tax2_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $this->get_settings()['tax2_pointer_animation'];
		$pointer_item_class = (isset($this->get_settings()['tax1_pointer']) && 'none' !== $this->get_settings()['tax1_pointer']) || (isset($this->get_settings()['tax2_pointer']) && 'none' !== $this->get_settings()['tax2_pointer']) ? 'class="wpr-pointer-item"' : '';

		// Pointer Class
		if ( 'wpr-grid-tax-style-1' === $settings['element_tax_style'] ) {
			$class .= ' wpr-pointer-'. $tax1_pointer;
			$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $tax1_pointer_animation;
		} else {
			$class .= ' wpr-pointer-'. $tax2_pointer;
			$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $tax2_pointer_animation;
		}

		echo '<div class="'. esc_attr($class .' '. $settings['element_tax_style']) .'">';
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

	// Get Elements
	public function get_elements( $type, $settings, $class, $post_id ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
			case 'title':
				$this->render_post_title( $settings, $class );
				break;

			case 'content':
				$this->render_post_content( $settings, $class );
				break;

			case 'excerpt':
				$this->render_post_excerpt( $settings, $class );
				break;

			case 'date':
				$this->render_post_date( $settings, $class );
				break;

			case 'time':
				$this->render_post_time( $settings, $class );
				break;

			case 'author':
				$this->render_post_author( $settings, $class );
				break;

			case 'comments':
				$this->render_post_comments( $settings, $class );
				break;

			case 'read-more':
				$this->render_post_read_more( $settings, $class );
				break;

			case 'likes':
				$this->render_post_likes( $settings, $class, $post_id );
				break;

			case 'sharing':
				$this->render_post_sharing_icons( $settings, $class );
				break;

			case 'custom-field':
				$this->render_post_custom_field( $settings, $class, $post_id );
				break;

			case 'separator':
				$this->render_post_element_separator( $settings, $class );
				break;
			
			default:
				$this->render_post_taxonomies( $settings, $class, $post_id );
				break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $post_id ) {
		$locations = [];

		foreach ( $settings['grid_elements'] as $data ) {
			$place = 'over';
			$align_vr = $data['element_align_vr'];

			if ( ! wpr_fs()->can_use_premium_code() ) {
				$align_vr = 'bottom';
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

	public function add_slider_settings( $settings ) {
		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'slidesToShow' => 1,
			'infinite' => ( $settings['slider_loop'] === 'yes' ),
			'speed' => absint( $settings['slider_effect_duration'] * 1000 ),
			'arrows' => true,
			'autoplay' => ( $settings['slider_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['slider_pause_on_hover'],
			'prevArrow' => '#wpr-grid-slider-prev-'. $this->get_id(),
			'nextArrow' => '#wpr-grid-slider-next-'. $this->get_id(),
		];

		$this->add_render_attribute( 'slider-settings', [
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
		] );
	}

	public function render_magazine_grid( $settings, $slide_offset ) {
		// Get Posts
		$posts = new \WP_Query( $this->get_main_query_args( $slide_offset ) );

		if ( ! wpr_fs()->can_use_premium_code() ) {
			if ( '1-2' === $settings['layout_select'] || '1-3' === $settings['layout_select'] || '1-4' === $settings['layout_select']
				 || '1-1-2' === $settings['layout_select'] || '2-1-2' === $settings['layout_select'] || '1vh-3h' === $settings['layout_select'] ) {
				$settings['layout_select'] = '1-1-3';
			}
		}

		echo '<section class="wpr-magazine-grid wpr-mgzn-grid-'. esc_attr($settings['layout_select']) .' wpr-mgzn-grid-rows-'. esc_attr($settings['layout_rows_number']) .'">';

		// Loop: Start
		if ( $posts->have_posts() ) :

		while ( $posts->have_posts() ) : $posts->the_post();

			// Post Class
			$post_class = implode( ' ', get_post_class( 'wpr-mgzn-grid-item elementor-clearfix', get_the_ID() ) );

			// Grid Item
			echo '<article class="'. esc_attr( $post_class ) .'">';

			// Password Protected Form
			$this->render_password_protected_input( $settings );

			// Inner Wrapper
			echo '<div class="wpr-grid-item-inner">';

			// Media
			echo '<div class="wpr-grid-media-wrap" data-overlay-link="'. esc_attr( $settings['overlay_post_link'] ) .'">';
				// Post Thumbnail
				$this->render_post_thumbnail( $settings, get_the_ID() );

				// Media Hover
				echo '<div class="wpr-grid-media-hover wpr-animation-wrap">';
					// Media Overlay
					$this->render_media_overlay( $settings );

					// Content: Over Media
					$this->get_elements_by_location( 'over', $settings, get_the_ID() );

				echo '</div>';
			echo '</div>';

			echo '</div>'; // End .wpr-grid-item-inner

			echo '</article>'; // End .wpr-grid-item

		endwhile;

		// reset
		wp_reset_postdata();

		// No Posts Found
		else:

			echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';

		// Loop: End
		endif;

		// Grid Wrap
		echo '</section>';
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$render_attribute = '';

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['slider_enable'] = '';
			$settings['slider_effect'] = '';
		}

		// Slider Settings
		if ( 'yes' === $settings['slider_enable'] ) {
			$this->add_slider_settings( $settings );
			$render_attribute = $this->get_render_attribute_string( 'slider-settings' );
		}

		// Grid/Slider Wrap
		echo '<div class="wpr-magazine-grid-wrap" '. $render_attribute .' data-slide-effect="'. esc_attr($settings['slider_effect']) .'">';

		// Slider
		if ( 'yes' === $settings['slider_enable'] ) {

			for ( $i=0; $i < $settings['slider_amount']; $i++ ) { 
				echo '<div class="wpr-magazine-slide">';
					$this->render_magazine_grid( $settings, $i );
				echo '</div>';
			}

		// Grid
		} else {
			$this->render_magazine_grid( $settings, 0 );
		}

		// Grid/Slider Wrap
		echo '</div>';

		// Slider Navigation
		if ( 'yes' === $settings['slider_enable'] ) {
			echo '<div class="wpr-grid-slider-arrow-container">';
				echo '<div class="wpr-grid-slider-prev-arrow wpr-grid-slider-arrow" id="wpr-grid-slider-prev-'. esc_html($this->get_id()) .'">'. Utilities::get_wpr_icon( $settings['slider_nav_icon'], '' ) .'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<div class="wpr-grid-slider-next-arrow wpr-grid-slider-arrow" id="wpr-grid-slider-next-'. esc_html($this->get_id()) .'">'. Utilities::get_wpr_icon( $settings['slider_nav_icon'], '' ) .'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
		}

	}
	
}