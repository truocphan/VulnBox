<?php
namespace WprAddons\Modules\PricingTable\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Pricing_Table extends Widget_Base {
		
	public function get_name() {
		return 'wpr-pricing-table';
	}

	public function get_title() {
		return esc_html__( 'Pricing Table', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-price-table';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'price table', 'pricing table', 'features table' ];
	}

	public function get_style_depends() {
		return [ 'wpr-button-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-pricing-table-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_repeater_args_feature_tooltip() {
		return [
			'label' => sprintf( __( 'Show Tooltip %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
			'type' => Controls_Manager::SWITCHER,
			'classes' => 'wpr-pro-control'
		];
	}

	public function add_repeater_args_feature_tooltip_text() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_feature_tooltip_show_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_contro_stack_feature_tooltip_section() {}

	public function add_controls_group_feature_even_bg() {
		$this->add_control(
			'feature_even_bg',
			[
				'label' => sprintf( __( 'Enable Even Color %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

	protected function register_controls() {

		// Section: Elements ---------
		$this->start_controls_section(
			'section_pricing_items',
			[
				'label' => esc_html__( 'Price Table', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'pricing_table_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Feature Item Tooltip and Even/Odd Feature Item Background Color</span> options are available in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-pricing-table-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Feature Item Tooltip and Even/Odd Feature Item Background Color</span> options are available in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$repeater = new Repeater();

		$repeater->add_control(
			'type_select',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'heading',
				'options' => [
					'heading' => esc_html__( 'Heading', 'wpr-addons' ),
					'price' => esc_html__( 'Price', 'wpr-addons' ),
					'feature' => esc_html__( 'Feature', 'wpr-addons' ),
					'text' => esc_html__( 'Text', 'wpr-addons' ),
					'button' => esc_html__( 'Button', 'wpr-addons' ),
					'divider' => esc_html__( 'Divider', 'wpr-addons' ),
				],
				'separator' => 'after',
			]
		);

		$repeater->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Awesome Title',
				'condition' => [
					'type_select' => 'heading',
				],
			]
		);

		$repeater->add_control(
			'heading_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Subtitle text',
				'condition' => [
					'type_select' => 'heading',
				],
			]
		);

		$repeater->add_control(
			'heading_icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'icon' => esc_html__( 'Icon', 'wpr-addons' ),
					'image' => esc_html__( 'Image', 'wpr-addons' ),
				],
				'condition' => [
					'type_select' => 'heading',
				],

			]
		);

		$repeater->add_control(
			'heading_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type_select' => 'heading',
					'heading_icon_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'text',
			[
				'label' => '',
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' =>'Text Element',
				'condition' => [
					'type_select' => 'text',
				],
			]
		);

		$repeater->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '59',
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'sub_price',
			[
				'label' => esc_html__( 'Sub Price', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '99',
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'currency_symbol',
			[
				'label' => esc_html__( 'Currency Symbol', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'wpr-addons' ),
					'dollar' => '&#36; ' ._x( 'Dollar', 'Currency Symbol', 'wpr-addons' ),
					'euro' => '&#128; ' ._x( 'Euro', 'Currency Symbol', 'wpr-addons' ),
					'pound' => '&#163; ' ._x( 'Pound Sterling', 'Currency Symbol', 'wpr-addons' ),
					'ruble' => '&#8381; ' ._x( 'Ruble', 'Currency Symbol', 'wpr-addons' ),
					'peso' => '&#8369; ' ._x( 'Peso', 'Currency Symbol', 'wpr-addons' ),
					'krona' => 'kr ' ._x( 'Krona', 'Currency Symbol', 'wpr-addons' ),
					'lira' => '&#8356; ' ._x( 'Lira', 'Currency Symbol', 'wpr-addons' ),
					'franc' => '&#8355; ' ._x( 'Franc', 'Currency Symbol', 'wpr-addons' ),
					'baht' => '&#3647; ' ._x( 'Baht', 'Currency Symbol', 'wpr-addons' ),
					'shekel' => '&#8362; ' ._x( 'Shekel', 'Currency Symbol', 'wpr-addons' ),
					'won' => '&#8361; ' ._x( 'Won', 'Currency Symbol', 'wpr-addons' ),
					'yen' => '&#165; ' ._x( 'Yen/Yuan', 'Currency Symbol', 'wpr-addons' ),
					'guilder' => '&fnof; ' ._x( 'Guilder', 'Currency Symbol', 'wpr-addons' ),
					'peseta' => '&#8359 ' ._x( 'Peseta', 'Currency Symbol', 'wpr-addons' ),
					'real' => 'R$ ' ._x( 'Real', 'Currency Symbol', 'wpr-addons' ),
					'rupee' => '&#8360; ' ._x( 'Rupee', 'Currency Symbol', 'wpr-addons' ),
					'indian_rupee' => '&#8377; ' ._x( 'Rupee (Indian)', 'Currency Symbol', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
				'default' => 'dollar',
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'currency',
			[
				'label' => esc_html__( 'Currency', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '$',
				'condition' => [
					'type_select' => 'price',
					'currency_symbol' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'sale',
			[
				'label' => esc_html__( 'Sale', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'old_price',
			[
				'label' => esc_html__( 'Old Price', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '55',
				'condition' => [
					'type_select' => 'price',
					'sale' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'preiod',
			[
				'label' => esc_html__( 'Period', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '/Month',
				'condition' => [
					'type_select' => 'price',
				],
			]
		);

		$repeater->add_control(
			'feature_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Awesome Feature',
				'condition' => [
					'type_select' => 'feature',
				],
			]
		);

		$repeater->add_control(
			'feature_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(84,89,95,1)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-pricing-table-feature-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'feature',
				],
			]
		);

		$repeater->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button',
				'condition' => [
					'type_select' => 'button',
				],
			]
		);

		$repeater->add_control(
			'btn_id',
			[
				'label' => esc_html__( 'Button ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => 'button-id',
				'condition' => [
					'type_select' => 'button',
				],
			]
		);

		$repeater->add_control(
			'btn_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'show_label' => false,
				'condition' => [
					'type_select' => 'button',
				],
			]
		);

		$repeater->add_control(
			'btn_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Before', 'wpr-addons' ),
					'after' => esc_html__( 'After', 'wpr-addons' ),
				],
				'condition' => [
					'type_select' => 'button',
				],

			]
		);

		$repeater->add_control(
			'select_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'conditions' => [
       		    	'relation' => 'or',
					'terms' => [
						[
							'name' => 'type_select',
							'operator' => '=',
							'value' => 'feature',
						],
						[
							'name' => 'type_select',
							'operator' => '=',
							'value' => 'button',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'type_select',
									'operator' => '=',
									'value' => 'heading',
								],
								[
									'name' => 'heading_icon_type',
									'operator' => '=',
									'value' => 'icon',
								],
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'feature_linethrough',
			[
				'label' => esc_html__( 'Line Through', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'type_select' => 'feature',
				],
			]
		);

		$repeater->add_control(
			'feature_linethrough_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} span.wpr-pricing-table-ftext-line-yes span' => 'color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'feature',
					'feature_linethrough' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'feature_linethrough_color',
			[
				'label' => esc_html__( 'Line Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} span.wpr-pricing-table-ftext-line-yes' => 'color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'feature',
					'feature_linethrough' => 'yes',
				],
			]
		);

		$repeater->add_control( 'feature_tooltip', $this->add_repeater_args_feature_tooltip() );

		$repeater->add_control( 'feature_tooltip_text', $this->add_repeater_args_feature_tooltip_text() );

		$repeater->add_control( 'feature_tooltip_show_icon', $this->add_repeater_args_feature_tooltip_show_icon() );

		$repeater->add_control(
			'divider_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'dashed',		
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-divider' => 'border-top-style: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

		$repeater->add_control(
			'divider_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F9F9F9',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

		$repeater->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-pricing-table-divider' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

        $repeater->add_responsive_control(
			'divider_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-pricing-table-divider' => 'width: {{SIZE}}{{UNIT}};',
				],	
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

        $repeater->add_responsive_control(
			'divider_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-pricing-table-divider' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],	
				'condition' => [
					'type_select' => 'divider',
				],
			]
		);

		$this->add_control(
			'pricing_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'type_select' => 'heading',
						'select_icon' => [ 'value' => 'far fa-gem', 'library' => 'fa-regular' ],
					],
					[
						'type_select' => 'price',
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 1',
						'feature_linethrough' => 'yes',
						'feature_icon_color' => '#7a7a7a',
						'feature_linethrough_text_color' => '#7a7a7a',
						'feature_linethrough_color' => '#7a7a7a',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 2',
						'feature_icon_color' => 'rgba(84,89,95,1)',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 3',
						'feature_icon_color' => 'rgba(97,206,112,1)',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 4',
						'feature_icon_color' => 'rgba(97,206,112,1)',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'feature',
						'feature_text' => 'Awesome Feature 5',
						'feature_icon_color' => 'rgba(97,206,112,1)',
						'select_icon' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ],
					],
					[
						'type_select' => 'button',
						'select_icon' => '',
					],
					[
						'type_select' => 'text',
					],
				],
				'title_field' => '{{{ type_select }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Badge -----------
		$this->start_controls_section(
			'section_badge',
			[
				'label' => esc_html__( 'Badge', 'wpr-addons' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'badge_style',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'default' => 'corner',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'corner' => esc_html__( 'Corner', 'wpr-addons' ),
					'cyrcle' => esc_html__( 'Cyrcle', 'wpr-addons' ),
					'flag' => esc_html__( 'Flag', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'badge_title',
			[
				'label' => esc_html__( ' Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sale',
				'condition' => [
					'badge_style!' => 'none',
				],
			]
		);

		$this->add_control(
            'badge_hr_position',
            [
                'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
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
					'badge_style!' => 'none',
				],
            ]
        );

        $this->add_responsive_control(
			'badge_cyrcle_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-badge-cyrcle .wpr-pricing-table-badge-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],	
				'condition' => [
					'badge_style' => 'cyrcle',
					'badge_style!' => 'none',
				],
			]
		);

        $this->add_responsive_control(
			'badge_cyrcle_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-badge-cyrcle' => 'transform: translateX({{badge_cyrcle_side_distance.SIZE}}%) translateY({{SIZE}}%);',
				],	
				'condition' => [
					'badge_style' => 'cyrcle',
					'badge_style!' => 'none',
				],
			]
		);

        $this->add_responsive_control(
			'badge_cyrcle_side_distance',
			[
				'label' => esc_html__( 'Side Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-badge-cyrcle' => 'transform: translateX({{SIZE}}%) translateY({{badge_cyrcle_top_distance.SIZE}}%);',
				],	
				'condition' => [
					'badge_style' => 'cyrcle',
					'badge_style!' => 'none',
				],
			]
		);

        $this->add_responsive_control(
			'badge_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 27,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-badge-corner .wpr-pricing-table-badge-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg);',
					'{{WRAPPER}} .wpr-pricing-table-badge-flag' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'badge_style!' => [ 'none', 'cyrcle' ],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Hover Animation --
		$this->start_controls_section(
			'wpr__section_hv_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'tab' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'hv_animation',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'wpr-addons' ),
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
					'bounce' => esc_html__( 'Bounce', 'wpr-addons' ),
				],
                'prefix_class'	=> 'wpr-pricing-table-animation-',
			]
		);

		$this->add_control(
			'hv_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}}.wpr-pricing-table-animation-slide' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-pricing-table-animation-bounce' => '-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-pricing-table-animation-zoom' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'pricing-table', [
			'List Item Advanced Tooltip',
			'List Item Even/Odd Background Color',
		] );
		
		// Styles
		// Section: Heading ----------
		$this->start_controls_section(
			'wpr__section_style_heading',
			[
				'label' => esc_html__( 'Heading', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'heading_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-pricing-table-heading'
			]
		);

		$this->add_responsive_control(
			'heading_section_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 27,
					'right' => 20,
					'bottom' => 25,
					'left' => 20,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'title_section',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2d2d2d',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-pricing-table-title',
			]
		);

		$this->add_control(
			'heading_title_distance',
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'sub_title_section',
			[
				'label' => esc_html__( 'Sub Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_sub_title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#919191',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-sub-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_sub_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-pricing-table-sub-title',
			]
		);

		$this->add_control(
			'icon_section',
			[
				'label' => esc_html__( 'Icon / Image', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_icon_positon',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
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
                'prefix_class'	=> 'wpr-pricing-table-heading-',
			]
		);

		$this->add_responsive_control(
			'heading_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-pricing-table-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-pricing-table-heading-left .wpr-pricing-table-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-pricing-table-heading-center .wpr-pricing-table-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-pricing-table-heading-right .wpr-pricing-table-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Price -----------
		$this->start_controls_section(
			'wpr__section_style_price',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'price_wrap_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605be5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-pricing-table-price'
			]
		);

		$this->add_responsive_control(
			'price_wrap_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 40,
					'right' => 20,
					'bottom' => 30,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'price_section',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-pricing-table-price',
			]
		);

		$this->add_control(
			'sub_price_section',
			[
				'label' => esc_html__( 'Sub Price', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sub_price_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'size' => 19,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-sub-price' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sub_price_vr_position',
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
					'{{WRAPPER}} .wpr-pricing-table-sub-price' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'currency_section',
			[
				'label' => esc_html__( 'Currency Symbol', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'currency_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-pricing-table-currency' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'currency_hr_position',
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
			]
		);

		$this->add_control(
			'currency_vr_position',
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
					'{{WRAPPER}} .wpr-pricing-table-currency' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
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
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-old-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'old_price_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-pricing-table-old-price' => 'font-size: {{SIZE}}{{UNIT}};',
				],
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
				'default' => 'middle',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-old-price' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'period_section',
			[
				'label' => esc_html__( 'Period', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'period_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-preiod' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'period_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-pricing-table-preiod',
			]
		);

		$this->add_control(
			'period_hr_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'below' => esc_html__( 'Below', 'wpr-addons' ),
					'beside' => esc_html__( 'Beside', 'wpr-addons' ),
				],
				'default' => 'below',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Features ----------
		$this->start_controls_section(
			'wpr__section_style_features',
			[
				'label' => esc_html__( 'Features', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feature_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f9f9f9',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table section' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_controls_group_feature_even_bg();

		$this->add_responsive_control(
			'feature_section_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feature_section_top_distance',
			[
				'label' => esc_html__( 'List Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature:first-of-type' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'feature_section_bot_distance',
			[
				'label' => esc_html__( 'List Bottom Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature:last-of-type' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'feature_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature span > span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'feature_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-pricing-table-feature',
			]
		);

		$this->add_responsive_control(
			'feature_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'flex-start' => 'justify-content: flex-start; text-align: left;',
					'center' => 'justify-content: center; text-align: center;',
					'flex-end' => 'justify-content: flex-end; text-align: right;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature-inner' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'feature_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 357,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'feature_icon_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'feature_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_contro_stack_feature_tooltip_section();

		$this->add_control(
			'feature_divider',
			[
				'label' => esc_html__( 'Divider', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => esc_html__( 'Off', 'wpr-addons' ),
				'label_on' => esc_html__( 'On', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feature_divider_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d6d6d6',				
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'feature_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'feature_divider_type',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'dashed',		
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature:after' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'feature_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'feature_divider_weight',
			[
				'label' => esc_html__( 'Weight', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'feature_divider_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature:after' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_divider' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button -----------
		$this->start_controls_section(
			'wpr__section_style_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_section_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-pricing-table-button'
			]
		);

		$this->add_control(
			'btn_section_padding',
			[
				'label' => esc_html__( 'Section Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 30,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_section_padding_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#2B2B2B',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-pricing-table-btn'
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-pricing-table-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#61ce70',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-pricing-table-btn.wpr-button-none:hover, {{WRAPPER}} .wpr-pricing-table-btn:before, {{WRAPPER}} .wpr-pricing-table-btn:after'
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-pricing-table-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_section_anim_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'btn_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-button-animations',
				'default' => 'wpr-button-none',
			]
		);

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-btn' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-pricing-table-btn:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-pricing-table-btn:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'btn_animation_height',
			[
				'label' => esc_html__( 'Animation Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="wpr-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="wpr-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'btn_animation' => [ 
						'wpr-button-underline-from-left',
						'wpr-button-underline-from-center',
						'wpr-button-underline-from-right',
						'wpr-button-underline-reveal',
						'wpr-button-overline-reveal',
						'wpr-button-overline-from-left',
						'wpr-button-overline-from-center',
						'wpr-button-overline-from-right'
					]
				],
			]
		);

		$this->add_control(
			'btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-pricing-table-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 40,
					'bottom' => 10,
					'left' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_type',
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
					'{{WRAPPER}} .wpr-pricing-table-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_border_width',
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
					'{{WRAPPER}} .wpr-pricing-table-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
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
					'{{WRAPPER}} .wpr-pricing-table-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Text -------------
		$this->start_controls_section(
			'wpr__section_style_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'text_section_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-pricing-table-text'
			]
		);

		$this->add_control(
			'text_section_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 70,
					'bottom' => 30,
					'left' => 70,
				],
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#a5a5a5',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-text' => 'color: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-pricing-table-text'
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-text' => 'text-align: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Badge ------------
		$this->start_controls_section(
			'wpr__section_style_badge',
			[
				'label' => esc_html__( 'Badge', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'badge_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-badge-inner' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#e83d17',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-badge-inner' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-pricing-table-badge-flag:before' => ' border-top-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-pricing-table-badge-inner'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-pricing-table-badge-inner'
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
				'{{WRAPPER}} .wpr-pricing-table-badge .wpr-pricing-table-badge-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Wrapper ----------
		$this->start_controls_section(
			'section_style_wrapper',
			[
				'label' => esc_html__( 'Wrapper', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_wrapper_style' );

		$this->start_controls_tab(
			'tab_wrapper_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-pricing-table'
			]
		);

		$this->add_control(
			'wrapper_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-pricing-table',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_wrapper_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_bg_hover_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-pricing-table:hover'
			]
		);

		$this->add_control(
			'wrapper_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-pricing-table:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'wrapper_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'wrapper_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wrapper_border_type',
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
					'{{WRAPPER}} .wpr-pricing-table' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'wrapper_border_width',
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
					'{{WRAPPER}} .wpr-pricing-table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'wrapper_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table' => 'border-radius: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .wpr-pricing-table-item-first' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-top-right-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-pricing-table-item-last' => 'border-bottom-left-radius: {{SIZE}}{{UNIT}}; border-bottom-right-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	private function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar' => '&#36;',
			'euro' => '&#128;',
			'pound' => '&#163;',
			'ruble' => '&#8381;',
			'peso' => '&#8369;',
			'krona' => 'kr',
			'lira' => '&#8356;',
			'franc' => '&#8355;',
			'shekel' => '&#8362;',
			'baht' => '&#3647;',
			'won' => '&#8361;',
			'yen' => '&#165;',
			'guilder' => '&fnof;',
			'peseta' => '&#8359',
			'real' => 'R$',
			'rupee' => '&#8360;',
			'indian_rupee' => '&#8377;',
		];

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}
		
	protected function render() {

	$settings = $this->get_settings();
	$item_count = 0;

	if ( empty( $settings['pricing_items'] ) ) {
		return;
	}

	?>

	<div class="wpr-pricing-table">
		<?php foreach ( $settings['pricing_items'] as $key => $item ) :

			if ( ! wpr_fs()->can_use_premium_code() ) {
				$item['feature_tooltip'] = '';
				$item['feature_tooltip_text'] = '';
			}

			// Fisrt and Last Item Classes
			if ( 0 === $key ) {
				$rep_item_class = ' wpr-pricing-table-item-first';
			} elseif ( count($settings['pricing_items']) - 1 === $key ) {
				$rep_item_class = ' wpr-pricing-table-item-last';
			} else {
				$rep_item_class = '';
			}

			if ( $item['type_select'] === 'feature' ) : ?>

			<section class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?> wpr-pricing-table-item wpr-pricing-table-<?php echo esc_attr( $item['type_select'] . $rep_item_class ); ?>">
				<div class="wpr-pricing-table-feature-inner">
					<?php if ( '' !== $item['select_icon']['value'] ) : ?>
						<i class="wpr-pricing-table-feature-icon <?php echo esc_attr( $item['select_icon']['value'] ); ?>"></i>
					<?php endif; ?>
					
					<span class="wpr-pricing-table-feature-text wpr-pricing-table-ftext-line-<?php echo esc_attr( $item['feature_linethrough'] ); ?>">
						<span>
						<?php
							echo wp_kses_post($item['feature_text']);

							if ( 'yes' === $item['feature_tooltip'] && 'yes' === $item['feature_tooltip_show_icon'] ) {
								echo '&nbsp;&nbsp;<i class="far fa-question-circle"></i>';
							}

						?>
						</span>
					</span>

					<?php if ( $item['feature_tooltip'] === 'yes' && ! empty( $item['feature_tooltip_text'] ) ) : ?>
						<div class="wpr-pricing-table-feature-tooltip"><?php echo wp_kses_post($item['feature_tooltip_text']); ?></div>						
					<?php endif; ?>							
				</div>
			</section>

			<?php else : ?>

			<div class="elementor-repeater-item-<?php echo esc_html($item['_id']); ?> wpr-pricing-table-item wpr-pricing-table-<?php echo esc_attr( $item['type_select'] . $rep_item_class ); ?>">		
			
			<?php if ( $item['type_select'] === 'heading' ) : ?>

				<div class="wpr-pricing-table-headding-inner">
			
					<?php if ( $item['heading_icon_type'] === 'icon' && '' !== $item['select_icon']['value'] ) : ?>
						<div class="wpr-pricing-table-icon">
							<i class="<?php echo esc_attr( $item['select_icon']['value'] ); ?>"></i>
						</div>
					<?php elseif ( $item['heading_icon_type'] === 'image' && ! empty( $item['heading_image']['url'] ) ) : ?>
						<div class="wpr-pricing-table-icon">
							<img src="<?php echo esc_attr( $item['heading_image']['url'] ); ?>">
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $item['heading_title'] ) || ! empty( $item['heading_sub_title'] ) ) : ?>
						<div class="wpr-pricing-table-title-wrap">
							<?php if ( ! empty( $item['heading_title'] ) ) : ?>	
								<h3 class="wpr-pricing-table-title"><?php echo wp_kses_post($item['heading_title']); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $item['heading_sub_title'] ) ) : ?>	
								<span class="wpr-pricing-table-sub-title"><?php echo wp_kses_post($item['heading_sub_title']); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>		
		
			<?php elseif ( $item['type_select'] === 'price' ) : ?>

				<div class="wpr-pricing-table-price-inner">
					<?php if ( $item['sale'] === 'yes' && ! empty( $item['old_price'] ) ) : ?>	
						<span class="wpr-pricing-table-old-price"><?php echo esc_html($item['old_price']); ?></span>
					<?php endif; ?>

					<?php
						if ( 'none' !== $item['currency_symbol'] && 'custom' !== $item['currency_symbol'] && $settings['currency_hr_position'] === 'before' ) {
							echo '<span class="wpr-pricing-table-currency">'. esc_html($this->get_currency_symbol($item['currency_symbol'])) .'</span>';
						} elseif ( 'custom' === $item['currency_symbol'] ) {
							if ( ! empty( $item['currency'] ) && $settings['currency_hr_position'] === 'before' ) {
								echo '<span class="wpr-pricing-table-currency">'. esc_html($item['currency']) .'</span>';
							}
						}
					?>
					
					<?php if ( ! empty( $item['price'] ) ) : ?>	
						<span class="wpr-pricing-table-main-price"><?php echo esc_html($item['price']); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $item['sub_price'] ) ) : ?>	
						<span class="wpr-pricing-table-sub-price"><?php echo esc_html($item['sub_price']); ?></span>
					<?php endif; ?>

					<?php
						if ( 'none' !== $item['currency_symbol'] && 'custom' !== $item['currency_symbol'] && $settings['currency_hr_position'] === 'after' ) {
							echo '<span class="wpr-pricing-table-currency">'. esc_html($this->get_currency_symbol($item['currency_symbol'])) .'</span>';
						} elseif ( 'custom' === $item['currency_symbol'] ) {
							if ( ! empty( $item['currency'] ) && $settings['currency_hr_position'] === 'after' ) {
								echo '<span class="wpr-pricing-table-currency">'. esc_html($item['currency']) .'</span>';
							}
						}
					?>

					<?php if ( ! empty( $item['preiod'] ) && $settings['period_hr_position'] === 'beside' ) : ?>	
						<div class="wpr-pricing-table-preiod"><?php echo esc_html($item['preiod']); ?></div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $item['preiod'] ) && $settings['period_hr_position'] === 'below' ) : ?>	
					<div class="wpr-pricing-table-preiod"><?php echo esc_html($item['preiod']); ?></div>
				<?php endif; ?>
		
			<?php elseif ( $item['type_select'] === 'text' && ! empty( $item['text'] ) ) : ?>

				<?php echo wp_kses_post($item['text']); ?>

			<?php elseif ( $item['type_select'] === 'button' && ( ! empty( $item['btn_text'] ) || '' !== $item['select_icon']['value'] ) ) :
				
				if (  '' !== $item['btn_url']['url'] ) {
					$this->add_render_attribute( 'btn_attribute'. $item_count, 'href', $item['btn_url']['url'] );
	
					if ( $item['btn_url']['is_external'] ) :
						$this->add_render_attribute( 'btn_attribute'. $item_count, 'target', '_blank' );
					endif;
	
					if ( $item['btn_url']['nofollow'] ) :
						$this->add_render_attribute( 'btn_attribute'. $item_count, 'nofollow', '' );
					endif;
				}

				if ( '' !== $item['btn_id'] ) :
					$this->add_render_attribute( 'btn_attribute' . $item_count, 'id', esc_html( $item['btn_id']) );
				endif;

				?>

				<a class="wpr-pricing-table-btn wpr-button-effect <?php echo esc_html($this->get_settings()['btn_animation']); ?>" <?php echo $this->get_render_attribute_string( 'btn_attribute'. $item_count ); ?>>
					<span>

						<?php if ( '' !== $item['select_icon']['value'] &&  $item['btn_position'] === 'before' ) : ?>
						<i class="<?php echo esc_attr( $item['select_icon']['value'] ); ?>"></i>
						<?php endif; ?>

						<?php echo esc_html($item['btn_text']); ?>

						<?php if ( '' !== $item['select_icon']['value'] &&  $item['btn_position'] === 'after' ) : ?>
						<i class="<?php echo esc_attr( $item['select_icon']['value'] ); ?>"></i>

					</span>
					<?php endif; ?>
				</a>
				
			<?php elseif ( $item['type_select'] === 'divider' ) : ?>

				<div class="wpr-pricing-table-divider"></div>
				
			<?php endif; ?>

			</div>
			<?php

			endif; 
			$item_count++;

		endforeach;

		if ( $settings['badge_style'] !== 'none' && ! empty( $settings['badge_title'] ) ) :

			$this->add_render_attribute( 'wpr-pricing-table-badge-attr', 'class', 'wpr-pricing-table-badge wpr-pricing-table-badge-'. esc_attr($settings[ 'badge_style']) );
			if ( ! empty( $settings['badge_hr_position'] ) ) :
				$this->add_render_attribute( 'wpr-pricing-table-badge-attr', 'class', 'wpr-pricing-table-badge-'. esc_attr($settings['badge_hr_position']) );
			endif;
			
			?>
			<div <?php echo $this->get_render_attribute_string( 'wpr-pricing-table-badge-attr' ); ?>>	
				<div class="wpr-pricing-table-badge-inner"><?php echo esc_html($settings['badge_title']); ?></div>	
			</div>
		<?php endif; ?>
	</div>

	<?php
	}
}