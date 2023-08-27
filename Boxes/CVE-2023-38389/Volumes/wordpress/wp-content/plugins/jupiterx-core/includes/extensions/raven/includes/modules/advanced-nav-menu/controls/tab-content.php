<?php
namespace JupiterX_Core\Raven\Modules\Advanced_Nav_Menu\Controls;

use JupiterX_Core\Raven\Controls\Query;
use JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site\Internal_URL;
use Elementor\Repeater;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class Tab_Content {
	public function __construct( $widget ) {
		$this->add_section_content( $widget );
		$this->add_section_layout( $widget );
	}

	private function add_section_content( $widget ) {
		$widget->start_controls_section( 'section_content', [
			'label' => esc_html__( 'Content', 'jupiterx-core' ),
		] );

		$widget->add_control( 'menu', [
			'label'         => esc_html__( 'Menu Items', 'jupiterx-core' ),
			'type'          => 'repeater',
			'prevent_empty' => true,
			'fields'        => $this->get_menu_creator_repeater()->get_controls(),
			'title_field'   => '{{{ text }}}',
			'default'       => [
				[
					'item_type' => 'menu',
					'text'      => esc_html__( 'Menu item 1', 'jupiterx-core' ),
				],
				[
					'item_type' => 'submenu',
					'text'      => esc_html__( 'Submenu item 1', 'jupiterx-core' ),
				],
				[
					'item_type' => 'submenu',
					'text'      => esc_html__( 'Submenu item 2', 'jupiterx-core' ),
				],
				[
					'item_type' => 'submenu',
					'text'      => esc_html__( 'Submenu item 3', 'jupiterx-core' ),
				],
				[
					'item_type' => 'menu',
					'text'      => esc_html__( 'Menu item 2', 'jupiterx-core' ),
				],
				[
					'item_type' => 'menu',
					'text'      => esc_html__( 'Menu item 3', 'jupiterx-core' ),
				],
			],
		] );

		$widget->end_controls_section();
	}

	private function add_section_layout( $widget ) {
		$widget->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Layout', 'jupiterx-core' ),
		] );

		$widget->add_control( 'layout', [
			'label'          => esc_html__( 'Layout', 'jupiterx-core' ),
			'type'           => 'select',
			'prefix_class'   => 'main-layout-',
			'render_type'    => 'template',
			'default'        => 'horizontal',
			'prevent_empty'  => true,
			'options'        => [
				'horizontal' => esc_html__( 'Horizontal', 'jupiterx-core' ),
				'vertical'   => esc_html__( 'Vertical', 'jupiterx-core' ),
				'dropdown'   => esc_html__( 'Dropdown', 'jupiterx-core' ),
				'offcanvas'  => esc_html__( 'Off-Canvas', 'jupiterx-core' ),
			],
		] );

		$widget->add_control( 'alignment', [
			'label'        => esc_html__( 'Align', 'jupiterx-core' ),
			'type'         => 'choose',
			'toggle'       => false,
			'default'      => 'start',
			'prefix_class' => 'raven-nav-alignment-',
			'options'      => [
				'start' => [
					'title' => esc_html__( 'Start', 'jupiterx-core' ),
					'icon'  => is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'jupiterx-core' ),
					'icon'  => 'eicon-h-align-center',
				],
				'end' => [
					'title' => esc_html__( 'End', 'jupiterx-core' ),
					'icon'  => is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
				],
				'stretch' => [
					'title' => esc_html__( 'Stretch', 'jupiterx-core' ),
					'icon'  => 'eicon-h-align-stretch',
				],
			],
			'conditions' => [
				'terms' => [
					[
						'name'     => 'layout',
						'operator' => 'in',
						'value'    => [ 'horizontal', 'vertical' ],
					],
				],
			],
		] );

		$widget->add_control( 'center_logo', [
			'label'        => esc_html__( 'Center Logo', 'jupiterx-core' ),
			'type'         => 'switcher',
			'default'      => 'no',
			'return_value' => 'yes',
			'condition'    => [
				'layout' => 'horizontal',
			],
		] );

		$widget->add_responsive_control( 'center_logo_skin', [
			'label'          => esc_html__( 'Choose Logo' ),
			'type'           => 'select',
			'default'        => 'primary',
			'options'        => [
				'primary'   => esc_html__( 'Primary', 'jupiterx-core' ),
				'secondary' => esc_html__( 'Secondary', 'jupiterx-core' ),
				'sticky'    => esc_html__( 'Sticky', 'jupiterx-core' ),
				'mobile'    => esc_html__( 'Mobile', 'jupiterx-core' ),
			],
			'description' => sprintf(
				/* translators: %s: url of customizer page */
				__( 'Please select or upload your <strong>Logo</strong> in the <a target="_blank" href="%s"><em>Customizer</em></a>.', 'jupiterx-core' ),
				add_query_arg( [ 'autofocus[section]' => 'jupiterx_logo' ], admin_url( 'customize.php' ) )
			),
			'condition'   => [
				'center_logo' => 'yes',
				'layout'      => 'horizontal',
			],
		] );

		$widget->add_control( 'center_logo_link', [
			'label'         => esc_html__( 'Logo Link', 'jupiterx-core' ),
			'type'          => 'url',
			'placeholder'   => esc_html__( 'Enter your web address', 'jupiterx-core' ),
			'show_external' => true,
			'default'       => [
				'url'         => '',
				'is_external' => false,
				'nofollow'    => false,
			],
			'condition'    => [
				'center_logo' => 'yes',
				'layout'      => 'horizontal',
			],
		] );

		$widget->add_control( 'pointer_type', [
			'label'        => esc_html__( 'Pointer', 'jupiterx-core' ),
			'type'         => 'select',
			'default'      => 'none',
			'prefix_class' => 'pointer-',
			'options'      => [
				'none'       => esc_html__( 'None', 'jupiterx-core' ),
				'underline'  => esc_html__( 'Underline', 'jupiterx-core' ),
				'overline'   => esc_html__( 'Overline', 'jupiterx-core' ),
				'doubleline' => esc_html__( 'Double Line', 'jupiterx-core' ),
				'framed'     => esc_html__( 'Framed', 'jupiterx-core' ),
				'background' => esc_html__( 'Background', 'jupiterx-core' ),
				'text'       => esc_html__( 'Text', 'jupiterx-core' ),
			],
			'conditions' => [
				'terms' => [
					[
						'name'     => 'layout',
						'operator' => 'in',
						'value'    => [ 'horizontal', 'vertical' ],
					],
				],
			],
		] );

		$widget->add_control( 'pointer_animation', [
			'label'        => esc_html__( 'Animation', 'jupiterx-core' ),
			'type'         => 'select',
			'default'      => '',
			'options'      => [],
			'prevent_empty' => true,
			'prefix_class' => 'pointer-anim-',
			'conditions'   => [
				'terms' => [
					[
						'name'     => 'layout',
						'operator' => 'in',
						'value'    => [ 'horizontal', 'vertical' ],
					],
					[
						'name'     => 'pointer_type',
						'operator' => '!==',
						'value'    => 'none',
					],
				],
			],
		] );

		$widget->add_control( 'offcanvas_position', [
			'label'              => esc_html__( 'Position', 'jupiterx-core' ),
			'type'               => 'select',
			'frontend_available' => true,
			'render_type'        => 'template',
			'default'            => 'right',
			'options'            => [
				'right'  => is_rtl() ? esc_html__( 'Left', 'jupiterx-core' ) : esc_html__( 'Right', 'jupiterx-core' ),
				'left'   => is_rtl() ? esc_html__( 'Right', 'jupiterx-core' ) : esc_html__( 'Left', 'jupiterx-core' ),
			],
			'condition'         => [
				'layout' => 'offcanvas',
			],
		] );

		$widget->add_control( 'offcanvas_appear_effect', [
			'label'              => esc_html__( 'Appear Effect', 'jupiterx-core' ),
			'type'               => 'select',
			'frontend_available' => true,
			'render_type'        => 'template',
			'default'            => 'overlay',
			'options'            => [
				'overlay' => esc_html__( 'Overlay', 'jupiterx-core' ),
				'push'    => esc_html__( 'Push', 'jupiterx-core' ),
			],
			'condition'          => [
				'layout' => 'offcanvas',
			],
		] );

		// << Submenu >>
		$widget->add_control( 'submenu_controls_divider', [
			'type'      => 'divider',
		] );

		$widget->add_control( 'submenu_animation', [
			'label'        => esc_html__( 'Submenu Animation', 'jupiterx-core' ),
			'type'         => 'select',
			'prefix_class' => 'submenu-anim-',
			'default'      => 'fade',
			'options'      => [
				'none'        => esc_html__( 'None', 'jupiterx-core' ),
				'fade'        => esc_html__( 'Fade', 'jupiterx-core' ),
				'slide_down'  => esc_html__( 'Slide Down', 'jupiterx-core' ),
				'slide_up'    => esc_html__( 'Slide Up', 'jupiterx-core' ),
				'slide_left'  => esc_html__( 'Slide Left', 'jupiterx-core' ),
				'slide_right' => esc_html__( 'Slide Right', 'jupiterx-core' ),
				'scale_down'  => esc_html__( 'Scale Down', 'jupiterx-core' ),
			],
			'conditions'   => [
				'terms' => [
					[
						'name'     => 'layout',
						'operator' => 'in',
						'value'    => [ 'horizontal', 'vertical' ],
					],
				],
			],
		] );

		$widget->add_control( 'submenu_trigger', [
			'label'              => esc_html__( 'Show Submenu On', 'jupiterx-core' ),
			'type'               => 'select',
			'frontend_available' => true,
			'default'            => '',
			'options'            => [
				''      => esc_html__( 'Hover', 'jupiterx-core' ),
				'click' => esc_html__( 'Click', 'jupiterx-core' ),
			],
			'conditions'        => [
				'terms' => [
					[
						'name'     => 'layout',
						'operator' => 'in',
						'value'    => [ 'horizontal', 'vertical' ],
					],
					[
						'name'     => 'submenu_animation',
						'operator' => '!==',
						'value'    => [ '' ],
					],
				],
			],
		] );

		$widget->add_control( 'submenu_indicator', [
			'label'                  => esc_html__( 'Submenu Indicator', 'jupiterx-core' ),
			'type'                   => 'icons',
			'skin'                   => 'inline',
			'label_block'            => false,
			'frontend_available'     => true,
			'default'                => [
				'value'   => 'fas fa-angle-down',
				'library' => 'fa-solid',
			],
		] );

		$mobile_terms = [
			'name'     => 'layout',
			'operator' => 'in',
			'value'    => [ 'horizontal', 'vertical' ],
		];

		// << Mobile >>
		$widget->add_control( 'dropdown_heading', [
			'label'      => esc_html__( 'Mobile', 'jupiterx-core' ),
			'type'       => 'heading',
			'separator'  => 'before',
			'conditions' => [ 'terms' => [ $mobile_terms ] ],
		] );

		$widget->add_control( 'full_width', [
			'label'              => esc_html__( 'Full Width', 'jupiterx-core' ),
			'type'               => 'switcher',
			'description'        => esc_html__( 'Stretch the mobile menu to full width.', 'jupiterx-core' ),
			'prefix_class'       => 'raven-dropdown-',
			'return_value'       => 'stretch',
			'render_type'        => 'template',
			'frontend_available' => true,
			'default'            => 'stretch',
			'conditions'         => [
				'terms' => [
					$mobile_terms,
					[
						'name'     => 'mobile_layout',
						'operator' => '===',
						'value'    => 'dropdown',
					],
				],
			],
		] );

		$mobile_breakpoints = [ '' => esc_html__( 'None', 'jupiterx-core' ) ];

		foreach ( $widget->get_active_breakpoints() as $device => $data ) {
			if ( in_array( $device, [ 'widescreen', 'desktop', 'laptop' ], true ) ) {
				continue;
			}

			$mobile_breakpoints[ $device ] = "{$data['label']} (< {$data['size']}px)";
		}

		$widget->add_control( 'mobile_breakpoint', [
			'label'        => esc_html__( 'Breakpoint', 'jupiterx-core' ),
			'type'         => 'select',
			'default'      => 'mobile',
			'options'      => $mobile_breakpoints,
			'prefix_class' => 'mobile-breakpoint-',
			'render_type'  => 'template',
			'conditions'   => [ 'terms' => [ $mobile_terms ] ],
		] );

		$widget->add_control( 'mobile_layout', [
			'label'   => esc_html__( 'Menu Layout', 'jupiterx-core' ),
			'type'    => 'select',
			'default' => 'dropdown',
			'options' => [
				'dropdown'    => esc_html__( 'Dropdown', 'jupiterx-core' ),
				'full-screen' => esc_html__( 'Full Screen', 'jupiterx-core' ),
				'side'        => esc_html__( 'Side Menu', 'jupiterx-core' ),
			],
			'frontend_available' => true,
			'conditions' => [ 'terms' => [ $mobile_terms ] ],
		] );

		$widget->add_control( 'side_menu_effect', [
			'label'   => esc_html__( 'Effect', 'jupiterx-core' ),
			'type'    => 'select',
			'default' => 'overlay',
			'options' => [
				'overlay' => esc_html__( 'Overlay', 'jupiterx-core' ),
				'push'    => esc_html__( 'Push', 'jupiterx-core' ),
			],
			'frontend_available' => true,
			'conditions'         => [
				'terms' => [
					$mobile_terms,
					[
						'name'     => 'mobile_layout',
						'operator' => '===',
						'value'    => 'side',
					],
				],
			],
		] );

		$widget->add_control( 'side_logo_controls_divider', [
			'type'      => 'divider',
			'condition' => [
				'layout' => 'offcanvas',
			],
		] );

		$widget->add_control( 'side_logo', [
			'label'        => esc_html__( 'Side Menu Logo', 'jupiterx-core' ),
			'type'         => 'switcher',
			'default'      => 'no',
			'return_value' => 'yes',
			'conditions'   => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'layout',
						'operator' => '===',
						'value'    => 'offcanvas',
					],
					[
						'terms' => [
							$mobile_terms,
							[
								'name'     => 'mobile_layout',
								'operator' => '===',
								'value'    => 'side',
							],
						],
					],
				],
			],
		] );

		$widget->add_responsive_control( 'side_logo_skin', [
			'label'   => esc_html__( 'Choose Logo' ),
			'type'    => 'select',
			'options' => [
				'primary'   => esc_html__( 'Primary', 'jupiterx-core' ),
				'secondary' => esc_html__( 'Secondary', 'jupiterx-core' ),
				'sticky'    => esc_html__( 'Sticky', 'jupiterx-core' ),
				'mobile'    => esc_html__( 'Mobile', 'jupiterx-core' ),
			],
			'description' => sprintf(
				/* translators: %s: url of customizer page */
				__( 'Please select or upload your <strong>Logo</strong> in the <a target="_blank" href="%s"><em>Customizer</em></a>.', 'jupiterx-core' ),
				add_query_arg( [ 'autofocus[section]' => 'jupiterx_logo' ], admin_url( 'customize.php' ) )
			),
			'default'        => 'primary',
			'conditions'   => [
				'relation' => 'or',
				'terms'    => [
					[
						'terms' => [
							[
								'name'     => 'layout',
								'operator' => '===',
								'value'    => 'offcanvas',
							],
							[
								'name'     => 'side_logo',
								'operator' => '===',
								'value'    => 'yes',
							],
						],
					],
					[
						'terms' => [
							$mobile_terms,
							[
								'name'     => 'mobile_layout',
								'operator' => '===',
								'value'    => 'side',
							],
							[
								'name'     => 'side_logo',
								'operator' => '===',
								'value'    => 'yes',
							],
						],
					],
				],
			],
		] );

		$widget->add_control( 'side_logo_link', [
			'label'         => esc_html__( 'Side Logo Link', 'jupiterx-core' ),
			'type'          => 'url',
			'placeholder'   => esc_html__( 'Enter your web address', 'jupiterx-core' ),
			'show_external' => true,
			'default'       => [
				'url'         => '',
				'is_external' => false,
				'nofollow'    => false,
			],
			'conditions'   => [
				'relation' => 'or',
				'terms'    => [
					[
						'terms' => [
							[
								'name'     => 'layout',
								'operator' => '===',
								'value'    => 'offcanvas',
							],
							[
								'name'     => 'side_logo',
								'operator' => '===',
								'value'    => 'yes',
							],
						],
					],
					[
						'terms' => [
							$mobile_terms,
							[
								'name'     => 'mobile_layout',
								'operator' => '===',
								'value'    => 'side',
							],
							[
								'name'     => 'side_logo',
								'operator' => '===',
								'value'    => 'yes',
							],
						],
					],
				],
			],
		] );

		$widget->add_control( 'toggle_button_controls_divider', [
			'type'       => 'divider',
			'conditions' => [
				'terms' => [
					[
						'name'     => 'layout',
						'operator' => 'in',
						'value'    => [ 'dropdown', 'offcanvas' ],
					],
				],
			],
		] );

		$widget->add_control( 'toggle_button_animation', [
			'label'   => esc_html__( 'Toggle Button Animation', 'jupiterx-core' ),
			'type'    => 'select',
			'default' => 'none',
			'options' => [
				'none'    => esc_html__( 'None', 'jupiterx-core' ),
				'squeeze' => esc_html__( 'Squeeze', 'jupiterx-core' ),
				'vortex'  => esc_html__( 'Vortex', 'jupiterx-core' ),
				'spin'    => esc_html__( 'Spin', 'jupiterx-core' ),
				'stand'   => esc_html__( 'Stand', 'jupiterx-core' ),
			],
			'conditions' => [
				'terms' => [
					[
						'name'     => 'custom_toggle_button',
						'operator' => '!==',
						'value'    => 'yes',
					],
				],
			],
		] );

		$widget->add_control( 'custom_toggle_button', [
			'label'        => esc_html__( 'Custom Toggle Icon', 'jupiterx-core' ),
			'type'         => 'switcher',
			'default'      => 'no',
			'return_value' => 'yes',
		] );

		$widget->add_control( 'custom_toggle_button_icon', [
			'label'       => esc_html__( 'Icon', 'jupiterx-core' ),
			'type'        => 'icons',
			'skin'        => 'inline',
			'label_block' => false,
			'conditions'  => [
				'terms' => [
					[
						'name'     => 'custom_toggle_button',
						'operator' => '===',
						'value'    => 'yes',
					],
				],
			],
		] );

		$widget->end_controls_section();
	}

	private function get_menu_creator_repeater() {
		$repeater = new Repeater();

		$repeater->add_control( 'item_type', [
			'label'   => esc_html__( 'Item Type', 'jupiterx-core' ),
			'type'    => 'select',
			'default' => 'menu',
			'options' => [
				'menu'     => esc_html__( 'Menu', 'jupiterx-core' ),
				'submenu'  => esc_html__( 'Sub Menu', 'jupiterx-core' ),
			],
		] );

		$repeater->add_control( 'content_type', [
			'label'     => esc_html__( 'Content Type', 'jupiterx-core' ),
			'type'      => 'select',
			'default'   => 'text',
			'options'   => [
				'text'     => esc_html__( 'Text', 'jupiterx-core' ),
				'section'  => esc_html__( 'Saved Section', 'jupiterx-core' ),
				'widget'   => esc_html__( 'Saved Widget', 'jupiterx-core' ),
			],
			'condition' => [ 'item_type' => 'submenu' ],
		] );

		$repeater->add_control( 'text', [
			'label'      => esc_html__( 'Text', 'jupiterx-core' ),
			'type'       => 'text',
			'dynamic'    => [ 'active' => true ],
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'item_type',
						'operator' => '===',
						'value'    => 'menu',
					],
					[
						'name'     => 'content_type',
						'operator' => '===',
						'value'    => 'text',
					],
				],
			],
		] );

		$repeater->add_control( 'link', [
			'label'        => esc_html__( 'Link', 'jupiterx-core' ),
			'type'         => 'url',
			'label_block'  => true,
			'placeholder'  => esc_html__( 'external link URL', 'jupiterx-core' ),
			'dynamic'      => [
				'active'     => true,
				// We have set a special category for "Interal URL" dynamic tag,
				// to use it here and limit the dynamic tags available for the user.
				'categories' => [ Internal_URL::ONLY_INTERNAL_URL ],
			],
			'autocomplete' => false,
			'description'  => esc_html__( 'For internal addresses, please only use dynamic tag.', 'jupiterx-core' ),
			'conditions'   => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'item_type',
						'operator' => '===',
						'value'    => 'menu',
					],
					[
						'name'     => 'content_type',
						'operator' => '===',
						'value'    => 'text',
					],
				],
			],
		] );

		$repeater->add_control( 'hash', [
			'label'        => esc_html__( 'Anchor Hash', 'jupiterx-core' ),
			'type'         => 'text',
			'placeholder'  => esc_html__( '#element-id', 'jupiterx-core' ),
			'label_block'  => false,
			'conditions'   => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'item_type',
						'operator' => '===',
						'value'    => 'menu',
					],
					[
						'name'     => 'content_type',
						'operator' => '===',
						'value'    => 'text',
					],
				],
			],
		] );

		$repeater->add_control( 'icon', [
			'label'       => esc_html__( 'Icon', 'jupiterx-core' ),
			'type'        => 'icons',
			'label_block' => false,
			'skin'        => 'inline',
			'conditions'  => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'item_type',
						'operator' => '===',
						'value'    => 'menu',
					],
					[
						'name'     => 'content_type',
						'operator' => '===',
						'value'    => 'text',
					],
				],
			],
		] );

		$repeater->add_control( 'dropdown_width_type', [
			'label'      => esc_html__( 'Submenu Width', 'jupiterx-core' ),
			'type'       => 'select',
			'default'    => 'default',
			'options'    => [
				'default'   => esc_html__( 'Default', 'jupiterx-core' ),
				'custom'    => esc_html__( 'Custom', 'jupiterx-core' ),
				'column'    => esc_html__( 'Equal to Column', 'jupiterx-core' ),
				'container' => esc_html__( 'Equal to Container', 'jupiterx-core' ),
				'section'   => esc_html__( 'Equal to Section', 'jupiterx-core' ),
				'widget'    => esc_html__( 'Equal to Widget', 'jupiterx-core' ),
			],
			'condition'  => [ 'item_type' => 'menu' ],
		] );

		$repeater->add_control( 'dropdown_custom_width', [
			'label'      => esc_html__( 'Width (px)', 'jupiterx-core' ),
			'type'       => 'slider',
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 10,
					'max' => 1000,
				],
			],
			'default'    => [ 'size' => 200 ],
			'conditions' => [
				'terms' => [
					[
						'name'     => 'item_type',
						'operator' => '===',
						'value'    => 'menu',
					],
					[
						'name'     => 'dropdown_width_type',
						'operator' => '===',
						'value'    => 'custom',
					],
				],
			],
		] );

		$repeater->add_control( 'dropdown_position', [
			'label'      => esc_html__( 'Submenu Position', 'jupiterx-core' ),
			'type'       => 'select',
			'default'    => 'center',
			'options'    => [
				'right'   => esc_html__( 'Right', 'jupiterx-core' ),
				'center'  => esc_html__( 'Center', 'jupiterx-core' ),
				'left'    => esc_html__( 'Left', 'jupiterx-core' ),
			],
			'condition'  => [ 'item_type' => 'menu' ],
		] );

		$repeater->add_control( 'width_pos_notice', [
			'type' => 'raw_html',
			'raw'  => sprintf(
				'<span class="elementor-control-field-description">%s</span>',
				esc_html__( 'Width and Position only affect Horizontal and Vertical layouts.', 'jupiterx-core' )
			),
			'condition' => [ 'item_type' => 'menu' ],
		] );

		$repeater->add_control( 'section_template', [
			'label'       => esc_html__( 'Select Section', 'jupiterx-core' ),
			'type'        => 'raven_query',
			'options'     => [],
			'label_block' => false,
			'multiple'    => false,
			'query'       => [
				'source'         => Query::QUERY_SOURCE_TEMPLATE,
				'template_types' => [ 'section' ],
			],
			'default'     => false,
			'conditions'  => [
				'terms' => [
					[
						'name'     => 'item_type',
						'operator' => '===',
						'value'    => 'submenu',
					],
					[
						'name'     => 'content_type',
						'operator' => '===',
						'value'    => 'section',
					],
				],
			],
		] );

		$repeater->add_control( 'widget_template', [
			'label'       => esc_html__( 'Select Widget', 'jupiterx-core' ),
			'type'        => 'raven_query',
			'options'     => [],
			'label_block' => false,
			'multiple'    => false,
			'query'       => [
				'source'         => Query::QUERY_SOURCE_TEMPLATE,
				'template_types' => [ 'widget' ],
			],
			'default'     => false,
			'conditions'  => [
				'terms' => [
					[
						'name'     => 'item_type',
						'operator' => '===',
						'value'    => 'submenu',
					],
					[
						'name'     => 'content_type',
						'operator' => '===',
						'value'    => 'widget',
					],
				],
			],
		] );

		return $repeater;
	}
}
