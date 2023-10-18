<?php
namespace WprAddons\Modules\PriceList\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Price_List extends Widget_Base {
		
	public function get_name() {
		return 'wpr-price-list';
	}

	public function get_title() {
		return esc_html__( 'Price List', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-price-list';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'pricing list', 'price list', 'price menu', 'pricing menu', 'food menu', 'restaurant menu' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-price-list-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_repeater_args_prlist_image() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_prlist_link() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}
	public function add_control_prlist_position() {}
	
	public function add_control_prlist_vr_position() {}
	
	public function add_section_style_image() {}

	protected function register_controls() {
		
		// Section: Items -----------
		$this->start_controls_section(
			'section_price_list_items',
			[
				'label' => esc_html__( 'Items', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
		
		$repeater = new Repeater();

		$repeater->add_control(
			'prlist_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sweet Cakes',
			]
		);

		$repeater->add_control(
			'prlist_price',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '$30',
			]
		);

		$repeater->add_control(
			'prlist_old_price',
			[
				'label' => esc_html__( 'Old Price', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
			]
		);

		$repeater->add_control(
			'prlist_description',
			[
				'label'   	=> esc_html__( 'Description', 'wpr-addons' ),
				'type'    	=> Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Best pricing list widget.',
			]
		);

		$repeater->add_control( 'prlist_image', $this->add_repeater_args_prlist_image() );

		$repeater->add_control( 'prlist_link', $this->add_repeater_args_prlist_link() );

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$repeater->add_control(
				'price_list_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Custom Image, Image Position and Custom Link</span> options are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-price-list-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Custom Image, Image Position and Custom Link</span> options are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_control(
			'prlist_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'prlist_title' => 'Sweet Cakes',
						'prlist_price' => '$30',
						'prlist_description' => 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Best pricing list widget.',
						'prlist_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'prlist_title' => 'Fresh Vegetables',
						'prlist_price' => '$50',
						'prlist_description' => 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Best pricing list widget.',
						'prlist_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'prlist_title' => 'White Potatoes',
						'prlist_price' => '$27',
						'prlist_old_price' => '35',
						'prlist_description' => 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Best pricing list widget.',
						'prlist_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'title_field' => '{{{ prlist_title }}}',
			]
		);

		$this->add_control_prlist_position();

		$this->add_control_prlist_vr_position();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'price-list', [
			'Add Images to Menu Items',
			'Add Custom Links to Menu Items',
			'Advanced Layout Options',
		] );
		
		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'general_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-price-list-item'
			]
		);

		$this->add_responsive_control(
			'general_gutter',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
						'width' => [
							'default' => [
								'top' => '1',
								'right' => '1',
								'bottom' => '1',
								'left' => '1',
								'isLinked' => true,
							],
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-price-list-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'general_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-price-list-item',
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Image ------------
		$this->add_section_style_image();

		// Styles
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-price-list-title',
			]
		);


		$this->add_responsive_control(
			'title_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'prlist_position' => 'center',
				],
			]
		);


		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Separator ------------
		$this->start_controls_section(
			'section_style_separator',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#a8a8a8',
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-separator' => 'border-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'separator_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'dotted',
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-separator' => 'border-bottom-style: {{VALUE}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'separator_weight',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Weight', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-separator' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator_spacing',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Spacing', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-separator' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Price ------------
		$this->start_controls_section(
			'section_style_price',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'price_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-price-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'price_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Min Width', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-price-wrap' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-price-list-price, {{WRAPPER}} .wpr-price-list-old-price',
			]
		);

		$this->add_control(
			'old_price_section',
			[
				'label' => esc_html__( 'Old Price', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'old_price_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#f75959',
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-old-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'old_price_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 11,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-old-price' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'old_price_hr_position',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => esc_html__( 'Before', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => esc_html__( 'After', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-price-list-old-position-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'old_price_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'top',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-old-price' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'price_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-price-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'price_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
						'width' => [
							'default' => [
								'top' => '1',
								'right' => '1',
								'bottom' => '1',
								'left' => '1',
								'isLinked' => true,
							],
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-price-list-price-wrap',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-price-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'price_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-price-list-price-wrap',
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Description ------
		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'description_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#757575',
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-price-list-description',
			]
		);

		$this->add_control(
			'description_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
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
						'title' => esc_html__( 'Justify', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-description' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'description_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-description' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section	

	}

	public function render_pro_element_image($item, $item_count) {}

	protected function render() {
		
		$settings = $this->get_settings();
		$item_count = 0;
	
		?>

		<div class="wpr-price-list">
			
			<?php foreach ( $settings['prlist_items'] as $item ) : ?>

				<div class="wpr-price-list-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> elementor-clearfix">
							
				<?php $this->render_pro_element_image($item, $item_count); ?>

					<div class="wpr-price-list-content">
						
						<div class="wpr-price-list-heading">
							
							<?php if ( '' !== $item['prlist_title'] ) : ?>								
							<span class="wpr-price-list-title"><?php echo esc_html( $item['prlist_title'] ); ?></span>							
							<?php endif; ?>
							
							<?php if ( 'none' !== $settings['separator_style'] ) : ?>						
								<span class="wpr-price-list-separator"></span>
							<?php endif ?>

							<?php if ( '' !== $item['prlist_price'] || '' !== $item['prlist_old_price'] ) : ?>
								<span class="wpr-price-list-price-wrap">
									<?php if ( '' !== $item['prlist_price'] ) : ?>	
									<span class="wpr-price-list-price"><?php echo esc_html( $item['prlist_price'] ); ?></span>
									<?php endif; ?>

									<?php if ( '' !== $item['prlist_old_price'] ) : ?>	
									<span class="wpr-price-list-old-price"><?php echo esc_html( $item['prlist_old_price'] ); ?></span>
									<?php endif; ?>
								</span>
							<?php endif; ?>

						</div>
						
						<?php if ( '' !== $item['prlist_description'] ) : ?>
							<div class="wpr-price-list-description"><?php echo wp_kses_post( $item['prlist_description'] ); ?></div>
						<?php endif; ?>
					</div>

				</div>

				<?php
				$item_count++;
			endforeach;
			?>
		</div>
		<?php

	}
}