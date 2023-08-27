<?php
namespace JupiterX_Core\Raven\Modules\Nav_Menu\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Nav_Menu\Classes;
use Elementor\Core\Responsive\Responsive;

defined( 'ABSPATH' ) || die();

/**
 * Temporary supressed.
 *
 * @SuppressWarnings(ExcessiveClassLength)
 * @SuppressWarnings(ExcessiveClassComplexity)
 */
class Nav_Menu extends Base_Widget {

	public function get_script_depends() {
		if ( wp_script_is( 'smartmenus', 'registered' ) ) {
			wp_dequeue_script( 'smartmenus' );
			wp_deregister_script( 'smartmenus' );
		}
		return [ 'jupiterx-core-raven-smartmenus', 'jupiterx-core-raven-url-polyfill' ];
	}

	public function get_name() {
		return 'raven-nav-menu';
	}

	public function get_title() {
		return __( 'Navigation Menu', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-navigation';
	}

	public function get_menus() {
		$options = [];

		$menus = get_terms( 'nav_menu', [ 'hide_empty' => false ] );

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function get_user_roles() {
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
		}

		$roles = [];

		foreach ( get_editable_roles() as $role => $role_info ) {
			$roles['roles'][ $role ] = $role_info['name'];
			$roles['defaults'][]     = $role;
		}
		return $roles;
	}

	protected function register_controls() {
		$this->register_content_section();
		$this->register_settings_section();
		$this->register_menu_item_section();
		$this->register_submenu_section();
		$this->register_logo_section();
		$this->register_side_logo_section();
		$this->register_icons_section();
		$this->register_mobile_menu_section();
		$this->register_toggle_button_section();
		$this->register_menu_container_section();
		$this->register_close_button_section();
	}

	protected function register_content_section() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$menu_options = $this->get_menus();

		$this->add_control(
			'menu',
			[
				'label' => __( 'Menu', 'jupiterx-core' ),
				'type' => 'select',
				'default' => isset( array_keys( $menu_options )[0] ) ? array_keys( $menu_options )[0] : '',
				'options' => $menu_options,
				/* translators: %s: Link directky to nav menu screen */
				'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'jupiterx-core' ), admin_url( 'nav-menus.php' ) ),
			]
		);

		$this->add_control(
			'submenu_icon',
			[
				'type' => 'hidden',
				'default' => '<svg 0="fas fa-chevron-down" class="e-font-icon-svg e-fas-chevron-down">
					<use xlink:href="#fas-chevron-down">
						<symbol id="fas-chevron-down" viewBox="0 0 448 512">
							<path d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path>
						</symbol>
					</use>
				</svg>',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'enable_repeater_menu',
			[
				'label' => __( 'Add exception for menu', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'yes',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater_menu_options = array_merge( [ '' => '- None -' ], $menu_options );

		$repeater->add_control(
			'label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'type' => 'text',
				'placeholder' => __( 'Menu Name', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'menu',
			[
				'label' => __( 'Menu', 'jupiterx-core' ),
				'type' => 'select',
				'default' => isset( array_keys( $menu_options )[0] ) ? array_keys( $menu_options )[0] : '',
				'options' => $repeater_menu_options,
				/* translators: %s: Link directly to nav menu screen */
				'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'jupiterx-core' ), admin_url( 'nav-menus.php' ) ),
			]
		);

		$repeater->add_control(
			'menu_user_visibility',
			[
				'label' => __( 'Menu visible for', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'all',
				'description' => __( 'Please set the exception roles in a way that does not overlap each other.', 'jupiterx-core' ),
				'options' => [
					'all' => __( 'Everyone', 'jupiterx-core' ),
					'selected_roles' => __( 'Logged In Users', 'jupiterx-core' ),
					'guests' => __( 'Logged Out Users', 'jupiterx-core' ),
				],
			]
		);

		$roles = $this->get_user_roles();

		$repeater->add_control(
			'menu_user_visibility_roles',
			[
				'label' => __( 'Which roles can see this menu', 'jupiterx-core' ),
				'description' => __( 'Conditions does not work on Elementor editor side.', 'jupiterx-core' ),
				'type' => 'raven_checkbox',
				'label_block' => true,
				'options' => $roles['roles'],
				'default' => [],
				'condition' => [
					'menu_user_visibility' => 'selected_roles',
				],
			]
		);

		$this->add_control(
			'repeater_menu',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default' => [
					[
						'list_menu_user_visibility' => 'all',
					],
				],
				'condition' => [
					'enable_repeater_menu' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Temporary supressed.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_settings_section() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'layout',
			[
				'label' => __( 'Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'horizontal',
				'tablet_default' => 'horizontal',
				'mobile_default' => 'horizontal',
				'options' => [
					'horizontal' => __( 'Horizontal', 'jupiterx-core' ),
					'vertical' => __( 'Vertical', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'logo',
			[
				'label' => __( 'Center Logo', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'layout' => 'horizontal',
					'mobile_breakpoint!' => 'desktop',
				],
			]
		);

		$this->add_responsive_control(
			'logo_skin',
			[
				'label' => __( 'Choose Logo' ),
				'type' => 'select',
				'options' => [
					'primary'   => __( 'Primary', 'jupiterx-core' ),
					'secondary' => __( 'Secondary', 'jupiterx-core' ),
					'sticky'    => __( 'Sticky', 'jupiterx-core' ),
					'mobile'    => __( 'Mobile', 'jupiterx-core' ),
				],
				'description' => sprintf(
					/* translators: %1$s: Choose logo name | %2$s: Link to Customizer page */
					__( 'Please select or upload your <strong>Logo</strong> in the <a target="_blank" href="%1$s"><em>Customizer</em></a>.', 'jupiterx-core' ),
					add_query_arg( [ 'autofocus[section]' => 'jupiterx_logo' ], admin_url( 'customize.php' ) )
				),
				'devices' => [ 'desktop', 'tablet' ],
				'default' => 'primary',
				'tablet_default' => 'primary',
				'condition' => [
					'logo' => 'yes',
					'layout' => 'horizontal',
					'mobile_breakpoint!' => 'desktop',
				],
			]
		);

		$this->add_control(
			'logo_link',
			[
				'label' => __( 'Logo Link', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => __( 'Enter your web address', 'jupiterx-core' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'logo' => 'yes',
					'layout' => 'horizontal',
					'mobile_breakpoint!' => 'desktop',
				],
			]
		);

		$this->add_responsive_control(
			'icons',
			[
				'label' => __( 'Menu Icons', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'return_value' => 'yes',
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'classes' => 'raven-fix-responsive-label',
			]
		);

		$this->add_control(
			'dropdown_heading',
			[
				'label' => __( 'Mobile', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'full_width',
			[
				'label' => __( 'Full Width', 'jupiterx-core' ),
				'type' => 'switcher',
				'description' => __( 'Stretch the mobile menu to full width.', 'jupiterx-core' ),
				'prefix_class' => 'raven-nav-menu-',
				'return_value' => 'stretch',
				'frontend_available' => true,
				'default' => 'stretch',
				'condition' => [
					'mobile_layout' => 'dropdown',
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'message',
			[
				'type' => 'raw_html',
				'raw' => '<div class="elementor-panel-alert elementor-panel-alert-warning">' . __( '<strong>Desktop & Laptop</strong> is deprecated to keep compatibility with Elementor breakpoints. Please use another breakpoint.', 'jupiterx-core' ) . '</div>',
				'condition' => [
					'mobile_breakpoint' => 'desktop',
				],
			]
		);

		$breakpoints = Responsive::get_breakpoints();

		$this->add_control(
			'mobile_breakpoint',
			[
				'label' => __( 'Breakpoint', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'mobile',
				'options' => [
					'' => __( 'None', 'jupiterx-core' ),
					'desktop' => __( 'Desktop & Laptop (deprecated)', 'jupiterx-core' ),
					/* translators: The tablet breakpoint. */
					'tablet' => sprintf( __( 'Tablet (< %spx)', 'jupiterx-core' ), $breakpoints['lg'] ),
					/* translators: The mobile breakpoint. */
					'mobile' => sprintf( __( 'Mobile (< %spx)', 'jupiterx-core' ), $breakpoints['md'] ),
				],
				'prefix_class' => 'raven-breakpoint-',
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'mobile_layout',
			[
				'label' => __( 'Menu Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'dropdown',
				'options' => [
					'dropdown' => __( 'Dropdown', 'jupiterx-core' ),
					'full-screen' => __( 'Full Screen', 'jupiterx-core' ),
					'side' => __( 'Side Menu', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'side_menu_effect',
			[
				'label' => __( 'Effect', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'overlay',
				'options' => [
					'overlay' => __( 'Overlay', 'jupiterx-core' ),
					'push' => __( 'Push', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'condition' => [
					'mobile_layout' => 'side',
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'side_logo',
			[
				'label' => __( 'Side Menu Logo', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'layout' => 'horizontal',
					'mobile_layout' => 'side',
				],
			]
		);

		$this->add_responsive_control(
			'side_logo_skin',
			[
				'label' => __( 'Choose Logo' ),
				'type' => 'select',
				'options' => [
					'primary'   => __( 'Primary', 'jupiterx-core' ),
					'secondary' => __( 'Secondary', 'jupiterx-core' ),
					'sticky'    => __( 'Sticky', 'jupiterx-core' ),
					'mobile'    => __( 'Mobile', 'jupiterx-core' ),
				],
				'description' => sprintf(
					/* translators: %1$s: Choose logo name | %2$s: Link to Customizer page */
					__( 'Please select or upload your <strong>Logo</strong> in the <a target="_blank" href="%1$s"><em>Customizer</em></a>.', 'jupiterx-core' ),
					add_query_arg( [ 'autofocus[section]' => 'jupiterx_logo' ], admin_url( 'customize.php' ) )
				),
				'default' => 'primary',
				'tablet_default' => 'primary',
				'mobile_default' => 'primary',
				'condition' => [
					'layout' => 'horizontal',
					'side_logo' => 'yes',
				],
			]
		);

		$this->add_control(
			'side_logo_link',
			[
				'label' => __( 'Side Logo Link', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => __( 'Enter your web address', 'jupiterx-core' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'layout' => 'horizontal',
					'side_logo' => 'yes',
				],
			]
		);

		$this->add_control(
			'toggle_button_animation',
			[
				'label' => __( 'Icon Animation', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'jupiterx-core' ),
					'squeeze' => __( 'Squeeze', 'jupiterx-core' ),
					'vortex' => __( 'Vortex', 'jupiterx-core' ),
					'spin' => __( 'Spin', 'jupiterx-core' ),
					'stand' => __( 'Stand', 'jupiterx-core' ),
				],
				'condition' => [
					'layout' => 'horizontal',
					'custom_toggle_button!' => 'yes',
				],
			]
		);

		$this->add_control(
			'custom_toggle_button',
			[
				'label' => __( 'Custom Icon', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'custom_toggle_button_image',
			[
				'label' => __( 'Image', 'jupiterx-core' ),
				'type' => 'media',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'custom_toggle_button' => 'yes',
					'layout' => 'horizontal',
				],
				'description' => __( 'Please upload SVG image/icon', 'jupiterx-core' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Temporary supressed.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_menu_item_section() {
		$this->start_controls_section(
			'section_menu_items',
			[
				'label' => __( 'Menu Items', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [ 'mobile_breakpoint!' => 'desktop' ],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'menu_item_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item',
			]
		);

		$this->add_responsive_control(
			'menu_item_space_between',
			[
				'label' => __( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-horizontal .raven-nav-menu > li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-nav-menu-vertical .raven-nav-menu > li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_item_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_item_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => is_rtl() ? __( 'Right', 'jupiterx-core' ) : __( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => is_rtl() ? __( 'Left', 'jupiterx-core' ) : __( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'prefix_class' => 'raven%s-nav-menu-align-',
			]
		);

		$this->start_controls_tabs( 'menu_item_tabs' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'menu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '3',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_item_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu_item_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'menu_item_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'menu_item_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item',
			]
		);

		$this->add_control(
			'menu_item_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_menu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.raven-menu-item:hover:not(.raven-menu-item-active), {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.highlighted:not(.raven-menu-item-active)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_menu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.raven-menu-item:hover:not(.raven-menu-item-active), {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.highlighted:not(.raven-menu-item-active)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_menu_item_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_menu_item_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'hover_menu_item_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.raven-menu-item:hover:not(.raven-menu-item-active), {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.highlighted:not(.raven-menu-item-active)' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_menu_item_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.raven-menu-item:hover:not(.raven-menu-item-active), {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.highlighted:not(.raven-menu-item-active)',
			]
		);

		$this->add_control(
			'hover_menu_item_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.raven-menu-item:hover:not(.raven-menu-item-active), {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.highlighted:not(.raven-menu-item-active)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			[
				'label' => __( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'active_menu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-parent > a, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-ancestor > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_menu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-parent > a, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-ancestor > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_menu_item_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'active_menu_item_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'active_menu_item_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-parent > a, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-ancestor > a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'active_menu_item_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-parent > a, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-ancestor > a',
			]
		);

		$this->add_control(
			'active_menu_item_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-parent > a, {{WRAPPER}} .raven-nav-menu-main .raven-nav-menu > li.current-menu-ancestor > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_submenu_section() {
		$this->start_controls_section(
			'section_submenu',
			[
				'label' => __( 'Submenu', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [ 'mobile_breakpoint!' => 'desktop' ],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'submenu_item_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li > a.raven-submenu-item',
			]
		);

		$this->add_control(
			'submenu_space_between',
			[
				'label' => __( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'render_type' => 'ui',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'submenu_item_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li > a.raven-submenu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'submenu_opening_position',
			[
				'label' => __( 'Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'bottom',
				'options' => [
					'top' => __( 'Top', 'jupiterx-core' ),
					'bottom' => __( 'Bottom', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'submenu_item_divider_heading',
			[
				'label' => __( 'Divider', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submenu_item_divider_type',
			[
				'label' => __( 'Border Type', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'none' => __( 'None', 'jupiterx-core' ),
					'solid' => __( 'Solid', 'jupiterx-core' ),
					'double' => __( 'Double', 'jupiterx-core' ),
					'dotted' => __( 'Dotted', 'jupiterx-core' ),
					'dashed' => __( 'Dashed', 'jupiterx-core' ),
					'groove' => __( 'Groove', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submenu_item_divider_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li:not(:last-child)' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'submenu_item_divider_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'submenu_item_divider_width',
			[
				'label' => __( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'submenu_item_divider_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'submenu_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submenu_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'submenu_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'submenu_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-main .raven-submenu',
			]
		);

		$this->add_control(
			'submenu_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'submenu_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-nav-menu-main .raven-submenu',
			]
		);
		$this->start_controls_tabs( 'submenu_item_tabs' );

		$this->start_controls_tab(
			'tab_submenu_item_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'submenu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '3',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li > a.raven-submenu-item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submenu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li > a.raven-submenu-item' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submenu_item_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_submenu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.raven-submenu-item:hover:not(.raven-menu-item-active), {{WRAPPER}} .raven-nav-menu-main .raven-submenu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.highlighted:not(.raven-menu-item-active)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_submenu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.raven-submenu-item:hover:not(.raven-menu-item-active), {{WRAPPER}} .raven-nav-menu-main .raven-submenu > li:not(.current-menu-parent):not(.current-menu-ancestor) > a.highlighted:not(.raven-menu-item-active)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submenu_item_active',
			[
				'label' => __( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'active_submenu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-main .raven-submenu > li.current-menu-parent > a, {{WRAPPER}} .raven-nav-menu-main .raven-submenu > li.current-menu-ancestor > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_submenu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-main .raven-submenu > li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-main .raven-submenu > li.current-menu-parent > a, {{WRAPPER}} .raven-nav-menu-main .raven-submenu > li.current-menu-ancestor > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_logo_section() {
		$this->start_controls_section(
			'section_logo',
			[
				'label' => __( 'Logo', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'logo' => 'yes',
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-logo' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_margin',
			[
				'label' => __( 'Margin', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_side_logo_section() {
		$this->start_controls_section(
			'section_side_logo',
			[
				'label' => __( 'Side Logo', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'side_logo' => 'yes',
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'side_logo_width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile > .raven-side-menu-logo' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'side_logo_margin',
			[
				'label' => __( 'Margin', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile > .raven-side-menu-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_icons_section() {
		$this->start_controls_section(
			'section_icons',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'tab' => 'style',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name'     => 'icons_tablet',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name' => 'icons_mobile',
							'operator' => '===',
							'value' => 'yes',
						],
						[
							'name' => 'icons',
							'operator' => '===',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'icons_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-menu-item i._mi' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .raven-submenu-item i._mi' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .raven-menu-item .sub-arrow svg' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs( 'icons_tabs' );

		$this->start_controls_tab(
			'tab_icons_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'icons_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-menu-item i._mi' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-submenu-item i._mi' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-menu-item .sub-arrow' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icons_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_icons_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-menu-item:hover i._mi' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-submenu-item:hover i._mi' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-menu-item:hover .sub-arrow' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(ExcessiveMethodLength)
	 */
	protected function register_mobile_menu_section() {
		$this->start_controls_section(
			'section_mobile_menu',
			[
				'label' => __( 'Mobile Menu', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_menu_item_full_width',
			[
				'label' => __( 'Full Width', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'no',
				'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'mobile_layout' => 'full-screen',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'mobile_menu_item_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a',
			]
		);

		$this->add_responsive_control(
			'mobile_menu_item_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 8,
					'right' => 32,
					'bottom' => 8,
					'left' => 32,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_menu_distance',
			[
				'label' => __( 'Distance', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.raven-nav-menu-stretch) .raven-nav-menu-mobile' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-nav-menu-stretch .raven-nav-menu-mobile' => 'top: auto !important; margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mobile_layout' => 'dropdown',
				],
			]
		);

		$this->add_control(
			'mobile_menu_item_divider_heading',
			[
				'label' => __( 'Divider', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_item_divider_type',
			[
				'label' => __( 'Border Type', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => __( 'None', 'jupiterx-core' ),
					'solid' => __( 'Solid', 'jupiterx-core' ),
					'double' => __( 'Double', 'jupiterx-core' ),
					'dotted' => __( 'Dotted', 'jupiterx-core' ),
					'dashed' => __( 'Dashed', 'jupiterx-core' ),
					'groove' => __( 'Groove', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-submenu' => 'border-top-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_item_divider_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li:not(:last-child)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-submenu' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'mobile_menu_item_divider_type!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_menu_item_divider_width',
			[
				'label' => __( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-submenu' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mobile_menu_item_divider_type!' => '',
				],
			]
		);

		$this->add_control(
			'mobile_menu_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mobile_menu_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'mobile_menu_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'mobile_menu_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu',
			]
		);

		$this->add_control(
			'mobile_menu_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'mobile_menu_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu',
			]
		);

		$this->add_responsive_control(
			'mobile_menu_item_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => is_rtl() ? __( 'Right', 'jupiterx-core' ) : __( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => is_rtl() ? __( 'Left', 'jupiterx-core' ) : __( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'prefix_class' => 'raven%s-mobile-nav-menu-align-',
			]
		);

		$this->start_controls_tabs( 'mobile_menu_item_tabs' );

		$this->start_controls_tab(
			'tab_mobile_menu_item_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'mobile_menu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '3',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_mobile_menu_item_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_mobile_menu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_mobile_menu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_mobile_menu_item_active',
			[
				'label' => __( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'active_mobile_menu_item_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'scheme' => [
					'type' => 'color',
					'value' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a:active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_mobile_menu_item_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a.raven-menu-item-active, {{WRAPPER}} .raven-nav-menu-mobile .raven-nav-menu li > a:active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_menu_container_section() {
		$this->start_controls_section(
			'section_menu_container',
			[
				'label' => __( 'Menu Container', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'mobile_layout' => [ 'dropdown', 'side', 'full-screen' ],
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'side_menu_alignment',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => is_rtl() ? __( 'Right', 'jupiterx-core' ) : __( 'Left', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					],
					'right' => [
						'title' => is_rtl() ? __( 'Left', 'jupiterx-core' ) : __( 'Right', 'jupiterx-core' ),
						'icon' => is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'frontend_available' => true,
				'condition' => [ 'mobile_layout' => 'side' ],
			]
		);

		$this->add_responsive_control(
			'menu_container_width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}' => '--menu-container-width: {{SIZE}};',
				],
				'condition' => [
					'mobile_layout' => 'side',
				],
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'menu_container_background',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-nav-menu-mobile',
			]
		);

		$this->add_responsive_control(
			'menu_container_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'menu_container_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu_container_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'menu_container_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'menu_container_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-mobile',
			]
		);

		$this->add_control(
			'menu_container_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-mobile' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'menu_container_box_shadow',
				'separator' => 'before',
				'condition' => [
					'mobile_layout' => 'side',
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-mobile',
			]
		);

		$this->end_controls_section();
	}

	protected function register_close_button_section() {
		$this->start_controls_section(
			'section_close_button',
			[
				'label' => __( 'Close Button', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'mobile_layout!' => 'dropdown',
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'close_button_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-close-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'close_button_tabs' );

		$this->start_controls_tab(
			'tab_close_button_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'close_button_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-close-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_close_button_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_close_button_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-close-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_toggle_button_section() {
		$this->start_controls_section(
			'section_toggle_button',
			[
				'label' => __( 'Mobile Menu Button', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'layout' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_button_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-nav-menu-custom-icon svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hamburger .hamburger-box' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hamburger-box' => 'height: calc((({{SIZE}}{{UNIT}}/8) * 3) + calc(({{SIZE}}{{UNIT}}/4) * 2)) ;',
					'{{WRAPPER}} .hamburger-box .hamburger-inner' => 'margin-top: calc(({{SIZE}}{{UNIT}}/8) / -2) ;',
					'{{WRAPPER}} .hamburger-inner' => 'width: {{SIZE}}{{UNIT}} ;',
					'{{WRAPPER}} .hamburger-inner::before' => 'width: {{SIZE}}{{UNIT}} ;',
					'{{WRAPPER}} .hamburger-inner::after' => 'width: {{SIZE}}{{UNIT}} ;',
					'{{WRAPPER}} .hamburger-inner, {{WRAPPER}} .hamburger-inner::before, {{WRAPPER}} .hamburger-inner::after' => 'height: calc({{SIZE}}{{UNIT}} / 8) ;',
					'{{WRAPPER}} .hamburger:not(.is-active) .hamburger-inner::before' => 'top: calc((({{SIZE}}{{UNIT}}/8) + calc({{SIZE}}{{UNIT}}/4)) * -1);',
					'{{WRAPPER}} .hamburger:not(.is-active) .hamburger-inner::after' => 'bottom: calc((({{SIZE}}{{UNIT}}/8) + calc({{SIZE}}{{UNIT}}/4)) * -1);',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_button_line_thickness',
			[
				'label' => __( 'Border Thickness', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .hamburger-inner, {{WRAPPER}} .hamburger-inner::before, {{WRAPPER}} .hamburger-inner::after' => 'height: {{SIZE}}{{UNIT}} ;',
				],
				'condition' => [
					'toggle_button_animation!' => 'none',
					'custom_toggle_button!' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_button_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_button_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'toggle_button_tabs' );

		$this->start_controls_tab(
			'tab_toggle_button_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'toggle_button_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'device_args' => [
					'desktop' => [
						'scheme' => [
							'type' => 'color',
							'value' => '2',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-nav-menu-toggle-button svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .hamburger-inner, {{WRAPPER}} .hamburger-inner::after, {{WRAPPER}} .hamburger-inner::before' => 'background-color: {{VALUE}};',
				],
				'classes' => 'raven-fix-responsive-label',
			]
		);

		$this->add_responsive_control(
			'toggle_button_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button' => 'background-color: {{VALUE}};',
				],
				'classes' => 'raven-fix-responsive-label',
			]
		);

		$this->add_control(
			'toggle_button_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'toggle_button_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'toggle_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'toggle_button_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
						'responsive' => true,
					],
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-toggle-button',
			]
		);

		$this->add_responsive_control(
			'toggle_button_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_button_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'hover_toggle_button_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'device_args' => [
					'desktop' => [
						'scheme' => [
							'type' => 'color',
							'value' => '4',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-nav-menu-toggle-button:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .raven-nav-menu-toggle-button:hover .hamburger-inner, {{WRAPPER}} .raven-nav-menu-toggle-button:hover  .hamburger-inner::after, {{WRAPPER}} .raven-nav-menu-toggle-button:hover  .hamburger-inner::before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_toggle_button_background_color',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_toggle_button_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'hover_toggle_button_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'hover_toggle_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_toggle_button_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
						'responsive' => true,
					],
				],
				'selector' => '{{WRAPPER}} .raven-nav-menu-toggle-button:hover',
			]
		);

		$this->add_responsive_control(
			'hover_toggle_button_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-nav-menu-toggle-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render Menu.
	 *
	 * @return void
	 *
	 * @SuppressWarnings(CyclomaticComplexity)
	 * @SuppressWarnings(NPathComplexity)
	 */
	protected function render() {
		$menus = $this->get_menus();

		if ( empty( $menus ) ) {
			return;
		}

		$settings = $this->get_active_settings();

		$menu = $settings['menu'];

		$this->set_repeater_menu( $settings, $menu );

		if ( empty( $menu ) && ! \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			return;
		}

		if ( empty( $menu ) && \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			$menu = $settings['menu'];
		}

		$args = [
			'menu' => $menu,
			'menu_class' => 'raven-nav-menu',
			'menu_id' => 'menu-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container' => '',
			'echo' => false,
			'walker' => new Classes\Walker_Nav_Menu( $settings ),
		];

		add_filter( 'nav_menu_item_id', '__return_empty_string' );
		add_filter( 'nav_menu_link_attributes', [ $this, 'link_class' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'submenu_classes' ], 10, 3 );
		add_filter( 'jupiterx_core_raven_walker_nav_menu_logo', [ $this, 'render_center_logo' ], 10, 3 );

		$menu_html = wp_nav_menu( $args );

		remove_filter( 'jupiterx_core_raven_walker_nav_menu_logo', [ $this, 'render_center_logo' ] );

		$dropdown_html = wp_nav_menu( array_replace( $args, [ 'menu_id' => 'menu-mobile-' . $this->get_id() ] ) );

		remove_filter( 'nav_menu_item_id', '__return_empty_string' );
		remove_filter( 'nav_menu_link_attributes', [ $this, 'link_class' ] );
		remove_filter( 'nav_menu_submenu_css_class', [ $this, 'submenu_classes' ] );

		if ( empty( $menu_html ) ) {
			return;
		}

		$settings['layout_tablet'] = ! empty( $settings['layout_tablet'] ) ? $settings['layout_tablet'] : '';
		$settings['layout_mobile'] = ! empty( $settings['layout_mobile'] ) ? $settings['layout_mobile'] : '';

		$this->add_render_attribute( 'menu', 'class', [
			'raven-nav-menu-main',
			'raven-nav-menu-' . $settings['layout'],
			'raven-nav-menu-tablet-' . $settings['layout_tablet'],
			'raven-nav-menu-mobile-' . $settings['layout_mobile'],
		] );

		if ( 'yes' === $settings['logo'] ) {
			$this->add_render_attribute( 'menu', 'class', 'raven-nav-menu-has-logo' );
		}

		if ( 'yes' !== $settings['icons'] ) {
			$this->add_render_attribute( 'menu', 'class', 'raven-nav-icons-hidden-desktop' );
			$this->add_render_attribute( 'mobile_menu', 'class', 'raven-nav-icons-hidden-desktop' );
		}

		if ( empty( $settings['icons_tablet'] ) || 'yes' !== $settings['icons_tablet'] ) {
			$this->add_render_attribute( 'menu', 'class', 'raven-nav-icons-hidden-tablet' );
			$this->add_render_attribute( 'mobile_menu', 'class', 'raven-nav-icons-hidden-tablet' );
		}

		if ( empty( $settings['icons_mobile'] ) || 'yes' !== $settings['icons_mobile'] ) {
			$this->add_render_attribute( 'menu', 'class', 'raven-nav-icons-hidden-mobile' );
			$this->add_render_attribute( 'mobile_menu', 'class', 'raven-nav-icons-hidden-mobile' );
		}

		$this->add_render_attribute( 'mobile_menu', 'class', [
			'raven-nav-menu-mobile',
			'raven-nav-menu-' . $settings['mobile_layout'],
		] );

		if ( 'side' === $settings['layout'] ) {
			$this->add_render_attribute( 'mobile_menu', 'class', [
				'raven-side-menu-' . $settings['side_menu_alignment'],
			] );
		}

		if ( 'yes' === $settings['mobile_menu_item_full_width'] ) {
			$this->add_render_attribute( 'mobile_menu', 'class', [ 'raven-nav-menu-item-full-width' ] );
		}
		?>
		<nav <?php echo $this->get_render_attribute_string( 'menu' ); ?>>
			<?php echo $menu_html; ?>
		</nav>

		<div class="raven-nav-menu-toggle">

			<?php
			$custom_svg_icon_enabled = '';
			if ( 'yes' === $settings['custom_toggle_button'] ) {
				$custom_svg_icon_enabled = 'raven-nav-menu-toggle-button-svg';
			}
			?>
			<div class="raven-nav-menu-toggle-button <?php echo $custom_svg_icon_enabled; ?>">
				<?php
				if ( 'none' === $settings['toggle_button_animation'] && 'yes' !== $settings['custom_toggle_button'] ) {
					?>
				<span class="fa fa-bars"></span>
					<?php
				} elseif ( 'none' !== $settings['toggle_button_animation'] && 'yes' !== $settings['custom_toggle_button'] ) {
					?>

				<div class="hamburger hamburger--<?php echo $settings['toggle_button_animation']; ?>">
					<div class="hamburger-box">
						<div class="hamburger-inner"></div>
					</div>
				</div>
					<?php
				} else {
					$custom_burger_icon = $settings['custom_toggle_button_image']['url'];
					?>
				<div class="raven-nav-menu-custom-icon">
					<?php
					if ( 'image/svg+xml' === get_post_mime_type( $settings['custom_toggle_button_image']['id'] ) ) {
						// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
						echo file_get_contents( $custom_burger_icon );
					}
					?>
				</div>
					<?php
				}
				?>
			</div>

		</div>
		<nav <?php echo $this->get_render_attribute_string( 'mobile_menu' ); ?>>
			<?php
			if ( 'side' === $settings['mobile_layout'] ) {
				echo $this->render_side_logo();
			}
			?>
			<?php if ( 'dropdown' !== $settings['mobile_layout'] ) : ?>
				<div class="raven-nav-menu-close-button">
					<span class="raven-nav-menu-close-icon">&times;</span>
				</div>
			<?php endif; ?>
			<div class="raven-container">
				<?php echo $dropdown_html; ?>
			</div>
		</nav>
		<?php
	}

	/**
	 * Temporary supressed.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function link_class( $atts, $item, $args, $depth ) {
		if ( 'raven-nav-menu' !== $args->menu_class && 'jupiterx-widget-nav-menu-vertical' !== $args->menu_class ) {
			return $atts;
		}

		$classes  = $depth ? 'raven-submenu-item' : 'raven-menu-item';
		$classes .= ' raven-link-item ';

		// Set active only for URL without fragment.
		if (
			in_array( 'current-menu-item', $item->classes, true ) &&
			false === strpos( $item->url, '#' )
		) {
			$classes .= ' raven-menu-item-active';
		}

		$atts['class'] = $classes;

		return $atts;
	}

	public function submenu_classes( $classes, $args ) {
		if ( 'raven-nav-menu' !== $args->menu_class ) {
			return;
		}
		$classes[] = 'raven-submenu';

		return $classes;
	}

	/**
	 * Render logo for desktop view.
	 * We are using render_logo function with direct call to generate logo in side view.
	 *
	 * Temporary supressed.
	 *
	 * @SuppressWarnings(CyclomaticComplexity)
	 * @SuppressWarnings(NPathComplexity)
	 */
	public function render_center_logo( $output, $menu_items, $current_index ) {
		$settings = $this->get_active_settings();

		$current_index++;

		$middle_position = intval( ceil( $menu_items / 2 ) );

		if ( 'yes' !== $settings['logo'] || $middle_position !== $current_index ) {
			return $output;
		}

		$devices = [
			'desktop' => '',
			'tablet'  => '_tablet',
		];

		$logo_skins = [];

		foreach ( $devices as $device => $device_setting_key ) {
			$device_setting = isset( $settings[ 'logo_skin' . $device_setting_key ] ) ? $settings[ 'logo_skin' . $device_setting_key ] : '';

			if ( empty( $device_setting ) ) {
				continue;
			}

			$logo_skin = 'primary' !== $device_setting ? "jupiterx_logo_{$device_setting}" : 'jupiterx_logo';

			if ( in_array( $logo_skin, $logo_skins, true ) ) {
				$this->add_render_attribute( $logo_skins[ $logo_skin ], 'class', 'raven-nav-menu-center-logo-' . $device );
				continue;
			}

			$logo_skins[ $logo_skin ] = $logo_skin;

			$image_src = get_theme_mod( $logo_skin, '' );

			if ( empty( $image_src ) ) {
				$image_src = \Elementor\Utils::get_placeholder_image_src();

			}

			$this->add_render_attribute( $logo_skin, [
				'src' => esc_url( $image_src ),
				'alt' => get_bloginfo( 'title' ),
				'class' => 'raven-nav-menu-center-logo-' . $device,
			], '', true );

			$retina_logo_skin = 'primary' !== $device_setting ? "jupiterx_logo_retina_{$device_setting}" : 'jupiterx_logo_retina';

			$retina_image_src = get_theme_mod( $retina_logo_skin, '' );

			$image_src = get_theme_mod( $logo_skin, '' );

			if ( ! empty( $retina_image_src ) ) {
				$this->add_render_attribute( $logo_skin, 'srcset', "{$image_src} 1x, {$retina_image_src} 2x" );
			}
		}

		$link = $settings['logo_link'];

		if ( ! isset( $link['url'] ) || empty( $link['url'] ) ) {
			$link['url'] = get_bloginfo( 'url' );
		}

		if ( ! empty( $link['url'] ) ) {
			$this->add_render_attribute( 'logo_link', 'class', 'raven-nav-menu-logo-link', true );

			$this->add_render_attribute( 'logo_link', 'href', esc_url( $link['url'] ), true );

			$this->render_link_properties( $this, $link, 'logo_link', true );
		}

		ob_start();

		?>
		<li class="raven-nav-menu-logo">
			<?php if ( ! empty( $link['url'] ) ) : ?>
				<a <?php echo $this->get_render_attribute_string( 'logo_link' ); ?>>
			<?php endif; ?>
			<?php foreach ( $logo_skins as $device_logo ) : ?>
				<img <?php echo $this->get_render_attribute_string( $device_logo ); ?> />
			<?php endforeach; ?>
			<?php if ( ! empty( $link['url'] ) ) : ?>
				</a>
			<?php endif; ?>
		</li>
		<?php

		return ob_get_clean();
	}

	/**
	* @SuppressWarnings(CyclomaticComplexity)
	* @SuppressWarnings(NPathComplexity)
	*/
	public function render_side_logo() {
		$settings = $this->get_active_settings();

		if ( 'yes' !== $settings['side_logo'] ) {
			return;
		}

		$devices = [
			'desktop' => '',
			'tablet'  => '_tablet',
			'mobile'  => '_mobile',
		];

		$side_logo_skins = [];

		foreach ( $devices as $device => $device_setting_key ) {
			$device_setting = isset( $settings[ 'side_logo_skin' . $device_setting_key ] ) ? $device_setting = $settings[ 'side_logo_skin' . $device_setting_key ] : '';

			if ( empty( $device_setting ) ) {
				continue;
			}

			$side_logo_skin = 'primary' !== $device_setting ? "jupiterx_logo_{$device_setting}" : 'jupiterx_logo';

			if ( in_array( $side_logo_skin, $side_logo_skins, true ) ) {
				$this->add_render_attribute( $side_logo_skins[ $side_logo_skin ], 'class', 'raven-nav-menu-logo-' . $device );
				continue;

			}

			$side_logo_skins[ $side_logo_skin ] = $side_logo_skin;

			$image_src = get_theme_mod( $side_logo_skin, '' );

			if ( empty( $image_src ) ) {
				$image_src = \Elementor\Utils::get_placeholder_image_src();
			}

			$this->add_render_attribute( $side_logo_skin, [
				'src' => esc_url( $image_src ),
				'alt' => get_bloginfo( 'title' ),
				'class' => 'raven-nav-menu-logo-' . $device,
			], '', true );

			$retina_side_logo_skin = 'primary' !== $device_setting ? "jupiterx_logo_retina_{$device_setting}" : 'jupiterx_logo_retina';

			$retina_image_src = get_theme_mod( $retina_side_logo_skin, '' );

			if ( ! empty( $retina_image_src ) ) {
				$this->add_render_attribute( $side_logo_skin, 'srcset', "{$image_src} 1x, {$retina_image_src} 2x" );
			}
		}

		$link = $settings['side_logo_link'];

		if ( ! isset( $link['url'] ) || empty( $link['url'] ) ) {
			$link['url'] = get_bloginfo( 'url' );
		}

		if ( ! empty( $link['url'] ) ) {
			$this->add_render_attribute( 'side_logo_link', 'class', 'raven-side-menu-logo-link', true );

			$this->add_render_attribute( 'side_logo_link', 'href', esc_url( $link['url'] ), true );

			$this->render_link_properties( $this, $link, 'side_logo_link', true );
		}

		ob_start();

		?>
		<div class="raven-side-menu-logo">
			<?php if ( ! empty( $link['url'] ) ) : ?>
				<a <?php echo $this->get_render_attribute_string( 'side_logo_link' ); ?>>
			<?php endif; ?>
			<?php foreach ( $side_logo_skins as $side_device_logo ) : ?>
				<img <?php echo $this->get_render_attribute_string( $side_device_logo ); ?> />
			<?php endforeach; ?>
			<?php if ( ! empty( $link['url'] ) ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Set menu for logged in & out users based on repeater condition from bottom to top.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param array $settings
	 * @param string $menu
	 * @return void
	 *
	 * @SuppressWarnings(CyclomaticComplexity)
	 * @SuppressWarnings(NPathComplexity)
	 */
	public function set_repeater_menu( $settings, &$menu ) {
		if (
			'yes' !== $settings['enable_repeater_menu'] ||
			count( $settings['repeater_menu'] ) === 0
		) {
			return;
		}

		$logged_in_menu  = null;
		$logged_out_menu = null;

		for ( $i = count( $settings['repeater_menu'] ) - 1; $i >= 0; $i-- ) {
			$repeater_menu = $settings['repeater_menu'][ $i ];
			$visibility    = $repeater_menu['menu_user_visibility'];
			$roles         = $repeater_menu['menu_user_visibility_roles'];
			$roles         = empty( $roles ) ? [] : $roles;
			$roles         = is_array( $roles ) ? $roles : explode( ',', $roles );

			if (
				'all' === $visibility &&
				! isset( $logged_in_menu ) &&
				! isset( $logged_out_menu )
			) {
				$menu = $repeater_menu['menu'];

				return;
			}

			if ( 'all' === $visibility && ! isset( $logged_in_menu ) ) {
				$logged_in_menu = $repeater_menu;
			}

			if ( 'all' === $visibility && ! isset( $logged_out_menu ) ) {
				$logged_out_menu = $repeater_menu;
			}

			if ( 'selected_roles' === $visibility && ! isset( $logged_in_menu ) && ! empty( $roles ) ) {
				if ( is_user_logged_in() ) {
					$user = wp_get_current_user();

					if ( ! empty( array_intersect( $roles, (array) $user->roles ) ) ) {
						$logged_in_menu = $repeater_menu;
					}
				}
			}

			if ( 'guests' === $visibility && ! isset( $logged_out_menu ) ) {
				$logged_out_menu = $repeater_menu;
			}
		}

		if ( ! is_user_logged_in() && isset( $logged_out_menu ) ) {
			$menu = $logged_out_menu['menu'];
		}

		if ( is_user_logged_in() && isset( $logged_in_menu ) ) {
			$menu = $logged_in_menu['menu'];
		}
	}
}
