<?php
namespace WprAddons\Modules\NavMenu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Nav_Menu extends Widget_Base {

	protected $nav_menu_index = 1;
	
	public function get_name() {
		return 'wpr-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'nav menu', 'header', 'navigation menu', 'horizontal menu', 'horizontal navigation', 'vertical menu', 'vertical navigation', 'burger menu', 'hamburger menu', 'mobile menu', 'responsive menu' ];
	}

	public function get_style_depends() {
		return [ 'wpr-link-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-nav-menu-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function on_export( $element ) {
		unset( $element['settings']['menu'] );
		return $element;
	}

	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	public function add_control_menu_layout() {
		$this->add_control(
			'menu_layout',
			[
				'label' => esc_html__( 'Menu Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'pro-vr' => esc_html__( 'Vertical (Pro)', 'wpr-addons' ),
				],
				'frontend_available' => true,
			]
		);
	}

	public function add_control_menu_items_pointer() {
		$this->add_control(
			'menu_items_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
					'double-line' => esc_html__( 'Double Line', 'wpr-addons' ),
					'pro-bd' => esc_html__( 'Border (Pro)', 'wpr-addons' ),
					'pro-bg' => esc_html__( 'Background (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-pointer-',
			]
		);
	}

	public function add_control_pointer_animation_line() {
		$this->add_control(
			'pointer_animation_line',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'pro-sl' => 'Slide (Pro)',
					'pro-gr' => 'Grow (Pro)',
					'pro-dr' => 'Drop (Pro)',
				],
				'prefix_class' => 'wpr-pointer-line-fx wpr-pointer-fx-',
				'condition' => [
					'menu_items_pointer' => [ 'underline', 'overline', 'double-line' ],
				],
			]
		);
	}

	public function add_control_pointer_animation_border() {}

	public function add_control_pointer_animation_background() {}

	public function add_control_menu_items_submenu_entrance() {
		$this->add_control(
			'menu_items_submenu_entrance',
			[
				'label' => esc_html__( 'Sub Menu Entrance', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'pro-sl' => esc_html__( 'Slide (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-sub-menu-fx-',
			]
		);
	}

	public function add_control_mob_menu_display() {
		$breakpoints = Responsive::get_breakpoints();

		$this->add_control(
			'mob_menu_display',
			[
				'label' => esc_html__( 'Show On', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'mobile',
				'options' => [
					/* translators: %d: Breakpoint number. */
					'mobile' => sprintf( esc_html__( 'Mobile (< %dpx)', 'wpr-addons' ), $breakpoints['md'] ),
					/* translators: %d: Breakpoint number. */
					'tablet' => sprintf( esc_html__( 'Tablet (< %dpx)', 'wpr-addons' ), $breakpoints['lg'] ),
					'pro-nn' => esc_html__( 'Don\'t Show (Pro)', 'wpr-addons' ),
					'pro-al' => esc_html__( 'All Devices (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-nav-menu-bp-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_toggle_btn_style() {
		$this->add_control(
			'toggle_btn_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hamburger',
				'options' => [
					'hamburger' => esc_html__( 'Hamburger', 'wpr-addons' ),
					'pro-tx' => esc_html__( 'Text (Pro)', 'wpr-addons' ),
				],
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);
	}

	public function add_control_sub_menu_width() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Menu -------------
		$this->start_controls_section(
			'section_menu',
			[
				'label' => 'Menu <a href="#" onclick="window.open(\'https://www.youtube.com/watch?v=at0CPKtklF0\',\'_blank\').focus()">Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
		
		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu_select',
				[
					'label' => esc_html__( 'Select Menu', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'separator' => 'after',
					'description' => sprintf( __( 'Go to <a href="%s" target="_blank">Appearance > Menus</a> to manage your menus.', 'wpr-addons' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu_select',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( '<strong>No menus found!</strong><br><a href="%s" target="_blank">Click Here</a> to create a new Menu.', 'wpr-addons' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control_menu_layout();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_layout', ['pro-vr'] );

		$this->add_responsive_control(
			'menu_align',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'widescreen_default' => 'left',
				'laptop_default' => 'left',
				'tablet_extra_default' => 'left',
				'tablet_default' => 'left',
				'mobile_extra_default' => 'left',
				'mobile_default' => 'left',
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
				'prefix_class' => 'wpr-main-menu-align-%s',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Menu Items -------
		$this->start_controls_section(
			'section_menu_items',
			[
				'label' => esc_html__( 'Menu Items', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_menu_items_pointer();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_items_pointer', ['pro-bd', 'pro-bg'] );

		$this->add_control_pointer_animation_line();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'pointer_animation_line', ['pro-sl', 'pro-dr', 'pro-gr']);

		$this->add_control_pointer_animation_border();

		$this->add_control_pointer_animation_background();

		$this->add_control(
			'pointer_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-item.wpr-pointer-item' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-menu-item.wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-menu-item.wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'menu_items_submenu_icon',
			[
				'label' => esc_html__( 'Sub Menu Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'caret-down',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'caret-down' => esc_html__( 'Triangle', 'wpr-addons' ),
					'angle-down' => esc_html__( 'Angle', 'wpr-addons' ),
					'chevron-down' => esc_html__( 'Chevron', 'wpr-addons' ),
					'plus' => esc_html__( 'Plus', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-sub-icon-',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'menu_items_submenu_position',
			[
				'label' => esc_html__( 'Sub Menu Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'absolute' => esc_html__( 'Absolute', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-sub-menu-position-',
				'condition' => [
					'menu_layout' => 'vertical',
				],
			]
		);

		$this->add_control(
			'menu_items_submenu_trigger',
			[
				'label' => esc_html__( 'Sub Menu Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'hover' => esc_html__( 'on Mouse Over', 'wpr-addons' ),
					'click' => esc_html__( 'on Mouse Click', 'wpr-addons' ),
				],
			]
		);

		$this->add_control_menu_items_submenu_entrance();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'menu_items_submenu_entrance', ['pro-sl'] );

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu ------
		$this->start_controls_section(
			'section_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_mob_menu_display();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'mob_menu_display', ['pro-nn', 'pro-al'] );

		$this->add_control(
			'mob_menu_stretch',
			[
				'label' => esc_html__( 'Stretch', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'full-width',
				'options' => [
					'auto-width' => esc_html__( 'None', 'wpr-addons' ),
					'full-width' => esc_html__( 'Full Width', 'wpr-addons' ),
					'custom-width' => esc_html__( 'Custom Width', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-mobile-menu-',
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'mob_menu_stretch_width',
			[
				'label' => esc_html__( 'Dropdown Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'tablet_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-mobile-menu-custom-width .wpr-mobile-nav-menu' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mob_menu_stretch' => 'custom-width',
				],
			]
		);

		$this->add_control(
			'mob_menu_drdown_align',
			[
				'label' => esc_html__( 'Dropdown Align', 'wpr-addons' ),
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
				'prefix_class' => 'wpr-mobile-menu-drdown-align-',
				'condition' => [
					'mob_menu_display!' => 'none',
					'mob_menu_stretch' => [ 'custom-width', 'auto-width' ],
				],
			]
		);

		$this->add_control(
			'mob_menu_item_align',
			[
				'label' => esc_html__( 'Item Align', 'wpr-addons' ),
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
				'prefix_class' => 'wpr-mobile-menu-item-align-',
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->add_control(
			'heading_toggle_button',
			[
				'label' => esc_html__( 'Toggle Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->add_control_toggle_btn_style();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'nav-menu', 'toggle_btn_style', ['pro-tx'] );

		$this->add_control(
			'toggle_btn_burger',
			[
				'label' => esc_html__( 'Toggle Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'v1',
				'options' => [
					'v1' => esc_html__( 'Icon 1', 'wpr-addons' ),
					'v2' => esc_html__( 'Icon 2', 'wpr-addons' ),
					'v3' => esc_html__( 'Icon 3', 'wpr-addons' ),
					'v4' => esc_html__( 'Icon 4', 'wpr-addons' ),
					'v5' => esc_html__( 'Icon 5', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-mobile-toggle-',
				'condition' => [
					'mob_menu_display!' => 'none',
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_txt_1',
			[
				'label' => esc_html__( 'Toggle Open Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Menu', 'wpr-addons' ),
				'condition' => [
					'mob_menu_display!' => 'none',
					'toggle_btn_style' => 'text',
				],
			]
		);

		$this->add_control(
			'toggle_btn_txt_2',
			[
				'label' => esc_html__( 'Toggle Close Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Close', 'wpr-addons' ),
				'condition' => [
					'mob_menu_display!' => 'none',
					'toggle_btn_style' => 'text',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_btn_align',
			[
				'label' => esc_html__( 'Toggle Align', 'wpr-addons' ),
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
				'selectors_dictionary' => [
					'left' => 'text-align: left',
					'center' => 'text-align: center',
					'right' => 'text-align: right',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle-wrap' => '{{VALUE}}',
				],
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'nav-menu', [
			'Vertical Layout',
			'Advanced Link Hover Effects: Slide, Grow, Drop',
			'SubMenu Entrance Slide Effect',
			'SubMenu Width option',
			'Advanced Display Conditions',
			'Mobile Menu Display Custom Conditions',
			'Mobile Menu Button Custom Text option',
		] );
		
		// Tab: Styles ===============
		// Section: Menu Items -------
		$this->start_controls_section(
			'section_style_menu_items',
			[
				'label' => esc_html__( 'Menu Items', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'menu_item_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-nav-menu .wpr-menu-item,
					 {{WRAPPER}} .wpr-nav-menu > .menu-item-has-children > .wpr-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'menu_item_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-nav-menu .wpr-menu-item:hover,
					 {{WRAPPER}} .wpr-nav-menu > .menu-item-has-children:hover > .wpr-sub-icon,
					 {{WRAPPER}} .wpr-nav-menu .wpr-menu-item.wpr-active-menu-item,
					 {{WRAPPER}} .wpr-nav-menu > .menu-item-has-children.current_page_item > .wpr-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pointer_color_hover',
			[
				'label' => esc_html__( 'Pointer Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}.wpr-pointer-line-fx .wpr-menu-item:before,
					 {{WRAPPER}}.wpr-pointer-line-fx .wpr-menu-item:after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.wpr-pointer-border-fx .wpr-menu-item:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.wpr-pointer-background-fx .wpr-menu-item:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'menu_item_highlight',
			[
				'label' => esc_html__( 'Highlight Active Item', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'menu_items_sub_icon_size',
			[
				'label' => esc_html__( 'Sub Menu Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .menu-item-has-children .wpr-sub-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-pointer-background:not(.wpr-sub-icon-none) .wpr-nav-menu-horizontal .menu-item-has-children .wpr-pointer-item' => 'padding-right: calc({{SIZE}}px + {{menu_items_padding_hr.SIZE}}px);',
					'{{WRAPPER}}.wpr-pointer-border:not(.wpr-sub-icon-none) .wpr-nav-menu-horizontal .menu-item-has-children .wpr-pointer-item' => 'padding-right: calc({{SIZE}}px + {{menu_items_padding_hr.SIZE}}px);',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_items_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .wpr-nav-menu .wpr-menu-item,{{WRAPPER}} .wpr-mobile-nav-menu a,{{WRAPPER}} .wpr-mobile-toggle-text',
			]
		);

		$this->add_control(
			'pointer_height',
			[
				'label' => esc_html__( 'Pointer Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => [ self::RESPONSIVE_DESKTOP, self::RESPONSIVE_TABLET ],
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-pointer-underline .wpr-menu-item:after,
					 {{WRAPPER}}.wpr-pointer-overline .wpr-menu-item:before,
					 {{WRAPPER}}.wpr-pointer-double-line .wpr-menu-item:before,
					 {{WRAPPER}}.wpr-pointer-double-line .wpr-menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-pointer-border-fx .wpr-menu-item:before' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_hr',
			[
				'label' => esc_html__( 'Inner Horizontal Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 7,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-nav-menu .wpr-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-pointer-background:not(.wpr-sub-icon-none) .wpr-nav-menu-vertical .menu-item-has-children .wpr-sub-icon' => 'text-indent: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-pointer-border:not(.wpr-sub-icon-none) .wpr-nav-menu-vertical .menu-item-has-children .wpr-sub-icon' => 'text-indent: -{{SIZE}}{{UNIT}};',

				]
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_bg_hr',
			[
				'label' => esc_html__( 'Outer Horizontal Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-nav-menu > .menu-item' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-menu' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-main-menu-align-left .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-main-menu-align-right .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_items_padding_vr',
			[
				'label' => esc_html__( 'Vertical Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-nav-menu .wpr-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_items_border',
				'fields_options' => [
					'border' => [
						'default' => '',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-menu-item',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Sub Menu ---------
		$this->start_controls_section(
			'section_style_sub_menu',
			[
				'label' => esc_html__( 'Sub Menu', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_sub_menu_style' );

		$this->start_controls_tab(
			'tab_sub_menu_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'sub_menu_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item,
					 {{WRAPPER}} .wpr-sub-menu > .menu-item-has-children .wpr-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_menu_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sub_menu_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'sub_menu_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item:hover,
					 {{WRAPPER}} .wpr-sub-menu > .menu-item-has-children .wpr-sub-menu-item:hover .wpr-sub-icon,
					 {{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item.wpr-active-menu-item,
					 {{WRAPPER}} .wpr-sub-menu > .menu-item-has-children.current_page_item .wpr-sub-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_menu_color_bg_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item:hover,
					 {{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item.wpr-active-menu-item' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_menu_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item'
			]
		);

		$this->add_control_sub_menu_width();

		$this->add_responsive_control(
			'sub_menu_padding_hr',
			[
				'label' => esc_html__( 'Horizontal Padding', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-sub-menu .wpr-sub-icon' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-main-menu-align-right .wpr-nav-menu-vertical .wpr-sub-menu .wpr-sub-icon' => 'left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'sub_menu_padding_vr',
			[
				'label' => esc_html__( 'Vertical Padding', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 13,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-sub-menu .wpr-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_menu_offset',
			[
				'label' => esc_html__( 'Sub Menu Offset', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-nav-menu-horizontal .wpr-nav-menu > li > .wpr-sub-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'sub_menu_divider',
			[
				'label' => esc_html__( 'Item Divider', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpr-sub-divider-',
				'default' => 'yes',
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'sub_menu_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}}.wpr-sub-divider-yes .wpr-sub-menu li:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'sub_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'sub_menu_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-sub-divider-yes .wpr-sub-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sub_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'sub_menu_divider_ctrl',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sub_menu_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-sub-menu',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sub_menu_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .wpr-sub-menu',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Mobile Menu -------
		$this->start_controls_section(
			'section_style_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_mobile_menu_style' );

		$this->start_controls_tab(
			'tab_mobile_menu_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'mobile_menu_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu a,
					 {{WRAPPER}} .wpr-mobile-nav-menu .menu-item-has-children > a:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu li' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_mobile_menu_focus',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'mobile_menu_color_focus',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_4,
				// ],
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu li a:hover,
					 {{WRAPPER}} .wpr-mobile-nav-menu .menu-item-has-children > a:hover:after,
					 {{WRAPPER}} .wpr-mobile-nav-menu li a.wpr-active-menu-item,
					 {{WRAPPER}} .wpr-mobile-nav-menu .menu-item-has-children.current_page_item > a:hover:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_bg_color_focus',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => Color::get_type(),
				// 	'value' => Color::COLOR_3,
				// ],
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu a:hover,
					 {{WRAPPER}} .wpr-mobile-nav-menu a.wpr-active-menu-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'mobile_menu_highlight',
			[
				'label' => esc_html__( 'Highlight Active Item', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'mobile_menu_padding_hr',
			[
				'label' => esc_html__( 'Item Horizontal Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-mobile-nav-menu .menu-item-has-children > a:after' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_padding_vr',
			[
				'label' => esc_html__( 'Item Vertical Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu .wpr-mobile-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'mobile_menu_divider',
			[
				'label' => esc_html__( 'Item Divider', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpr-mobile-divider-',
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}}.wpr-mobile-divider-yes .wpr-mobile-nav-menu a' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'mobile_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'mobile_menu_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-mobile-divider-yes .wpr-mobile-nav-menu a' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mobile_menu_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'mobile_menu_sub_font_size',
			[
				'label' => esc_html__( 'Sub Item Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu .wpr-mobile-sub-menu-item' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_sub_padding_vr',
			[
				'label' => esc_html__( 'Sub Item Vertical Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu .wpr-mobile-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'mobile_menu_offset',
			[
				'label' => esc_html__( 'Dropdown Offset', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'px' => [
					'min' => 1,
					'min' => 50,
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-nav-menu' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Toggle Button ----
		$this->start_controls_section(
			'section_style_toggle_button',
			[
				'label' => esc_html__( 'Toggle Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'tab_toggle_btn_style_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'toggle_btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-mobile-toggle-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-mobile-toggle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_btn_style_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'toggle_btn_color_hover',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-mobile-toggle:hover .wpr-mobile-toggle-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-mobile-toggle:hover .wpr-mobile-toggle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_btn_lines_height',
			[
				'label' => esc_html__( 'Lines Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle-line' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_line_space',
			[
				'label' => esc_html__( 'Space Between Lines', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle-line' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'toggle_btn_style' => ['hamburger', 'pro-tx'],
				],
			]
		);

		$this->add_control(
			'toggle_btn_width',
			[
				'label' => esc_html__( 'Button Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 150,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_btn_padding',
			[
				'label' => esc_html__( 'Button Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_border_width',
			[
				'label' => esc_html__( 'Button Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle' => 'border-width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_border_radius',
			[
				'label' => esc_html__( 'Button Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-mobile-toggle' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function custom_menu_item_classes( $atts, $item, $args, $depth ) {
		$settings = $this->get_active_settings();

		// Main or Mobile
		if ( strpos( $args->menu_id, 'mobile-menu' ) === false ) {
		    $main 	= 'wpr-menu-item wpr-pointer-item';
		    $sub 	= 'wpr-sub-menu-item';
		    $active = $settings['menu_item_highlight'] === 'yes' ? ' wpr-active-menu-item' : '';
		} else {
		    $main 	= 'wpr-mobile-menu-item';
		    $sub 	= 'wpr-mobile-sub-menu-item';
		    $active = $settings['mobile_menu_highlight'] === 'yes' ? ' wpr-active-menu-item' : '';
		}

		$classes = $depth ? $sub : $main;

		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$classes .= $active;
		}

		if ( empty( $atts['class'] ) ) {
			$atts['class'] = $classes;
		} else {
			$atts['class'] .= ' '. $classes;
		}

		return $atts;
	}

	public function custom_sub_menu_class( $classes ) {
		$classes[] = 'wpr-sub-menu';

		return $classes;
	}

	public function custom_menu_items( $output, $item, $depth, $args ) {
		$settings = $this->get_active_settings();

		if ( strpos( $args->menu_class, 'wpr-nav-menu' ) !== false ) {
			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				$item_class = 'wpr-menu-item wpr-pointer-item';

				if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
					$item_class .= ' wpr-active-menu-item';
				}

				// Sub Menu Classes
				if ( $depth > 0 ) {
					$item_class = 'wpr-sub-menu-item';

					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_class .= ' wpr-active-menu-item';
					}
				}

				// Add Sub Menu Icon
				$output  ='<a href="'. esc_url($item->url) .'" class="'. esc_attr($item_class) .'">'. esc_html($item->title);
				if ( $depth > 0 ) {
					if ( 'inline' === $settings['menu_items_submenu_position'] ) {
						$output .='<i class="wpr-sub-icon fas" aria-hidden="true"></i>';
					} else {
						$output .='<i class="wpr-sub-icon fas wpr-sub-icon-rotate" aria-hidden="true"></i>';
					}
				} else {
					if ( 'absolute' === $settings['menu_items_submenu_position'] ) {
						$output .='<i class="wpr-sub-icon fas wpr-sub-icon-rotate" aria-hidden="true"></i>';
					} else {
						$output .='<i class="wpr-sub-icon fas" aria-hidden="true"></i>';
					}
				}

				$output .='</a>';		
			}
		}

		return $output;
	}

	protected function render() {
		$available_menus = $this->get_available_menus();
	
		if ( ! $available_menus ) {
			return;
		}

		// Get Settings
		$settings = $this->get_active_settings();

		$args = [
			'echo' => false,
			'menu' => $settings['menu_select'],
			'menu_class' => 'wpr-nav-menu',
			'menu_id' => 'menu-'. $this->get_nav_menu_index() .'-'. $this->get_id(),
			'container' => '',
			'fallback_cb' => '__return_empty_string',
		];

		// Custom Menu Items
		add_filter( 'walker_nav_menu_start_el', [ $this, 'custom_menu_items' ], 10, 4 );

		// Add Custom Filters
		add_filter( 'nav_menu_link_attributes', [ $this, 'custom_menu_item_classes' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'custom_sub_menu_class' ] );
		add_filter( 'nav_menu_item_id', '__return_empty_string' );

		// Generate Menu HTML
		$menu_html = wp_nav_menu( $args );

		// Generate Mobile Menu HTML
		$args['menu_id'] 	= 'mobile-menu-'. $this->get_nav_menu_index() .'-'. $this->get_id();
		$args['menu_class'] = 'wpr-mobile-nav-menu';
		$moible_menu_html 	= wp_nav_menu( $args );

		// Remove Custom Filters
		remove_filter( 'nav_menu_link_attributes', [ $this, 'custom_menu_item_classes' ] );
		remove_filter( 'nav_menu_submenu_css_class', [ $this, 'custom_sub_menu_class' ] );
		remove_filter( 'walker_nav_menu_start_el', [ $this, 'custom_menu_items' ] );
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );

		if ( empty( $menu_html ) ) {
			return;
		}

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['menu_layout'] = 'horizontal';
			$settings['toggle_btn_style'] = 'hamburger';
		}

		// Main Menu
		echo '<nav class="wpr-nav-menu-container wpr-nav-menu-'. esc_attr($settings['menu_layout']) .'" data-trigger="'. esc_attr($settings['menu_items_submenu_trigger']) .'">';
			echo ''. $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</nav>';

		// Mobile Menu
		echo '<nav class="wpr-mobile-nav-menu-container">';

			// Toggle Button
			echo '<div class="wpr-mobile-toggle-wrap">';
				echo '<div class="wpr-mobile-toggle">';
					if ( $settings['toggle_btn_style'] === 'hamburger' ) {
						echo '<span class="wpr-mobile-toggle-line"></span>';
						echo '<span class="wpr-mobile-toggle-line"></span>';
						echo '<span class="wpr-mobile-toggle-line"></span>';
					} else {
						echo '<span class="wpr-mobile-toggle-text">'. esc_html($settings['toggle_btn_txt_1']) .'</span>';
						echo '<span class="wpr-mobile-toggle-text">'. esc_html($settings['toggle_btn_txt_2']) .'</span>';
					}
				echo '</div>';
			echo '</div>';

			// Menu
			echo ''. $moible_menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo '</nav>';
	}
	
}