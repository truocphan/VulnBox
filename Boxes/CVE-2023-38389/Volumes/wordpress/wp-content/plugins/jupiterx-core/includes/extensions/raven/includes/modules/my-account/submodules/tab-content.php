<?php
namespace JupiterX_Core\Raven\Modules\My_Account\Submodules;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Controls\Query;

class Tab_Content {
	public static function add_section_content( Base_Widget $widget ) {
		$widget->start_controls_section(
			'section_content_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tab_name',
			[
				'label'   => esc_html__( 'Tab Name', 'jupiterx-core' ),
				'type'    => 'text',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'is_default',
			[
				'type'    => 'hidden',
				'default' => 'no',
			]
		);

		$repeater->add_control(
			'custom_template_enabled',
			[
				'label'     => esc_html__( 'Custom Tab Content', 'jupiterx-core' ),
				'type'      => 'switcher',
				'default'   => 'yes',
				'condition' => [
					'is_default' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'custom_template',
			[
				'label'       => esc_html__( 'Choose a template', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'options'     => [],
				'label_block' => true,
				'multiple'    => false,
				'query'       => [
					'source'         => Query::QUERY_SOURCE_TEMPLATE,
					'template_types' => [
						'section',
					],
				],
				'default'     => false,
				'condition'   => [
					'custom_template_enabled' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hide_tab',
			[
				'label'   => esc_html__( 'Hide Tab', 'jupiterx-core' ),
				'type'    => 'switcher',
				'default' => 'no',
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'tab_icon',
			[
				'label'       => esc_html__( 'Icon', 'jupiterx-core' ),
				'type'        => 'icons',
				'skin'        => 'inline',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'order_display_description',
			[
				'raw'             => esc_html__( 'Note: By default, only your last order is displayed while editing the orders section. You can see other orders on your live site or in the WooCommerce orders section', 'jupiterx-core' ),
				'type'            => 'raw_html',
				'content_classes' => 'elementor-descriptor',
				'conditions'      => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'custom_template_enabled',
									'operator' => '===',
									'value'    => 'yes',
								],
								[
									'name'     => 'custom_template',
									'operator' => '===',
									'value'    => 'orders',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'custom_template_enabled',
									'operator' => '!==',
									'value'    => 'yes',
								],
								[
									'name'     => 'field_key',
									'operator' => '===',
									'value'    => 'orders',
								],
							],
						],
					],
				],
			]
		);

		$widget->add_control(
			'tabs',
			[
				'label'        => '',
				'type'         => 'repeater',
				'fields'       => $repeater->get_controls(),
				'item_actions' => [
					'add'       => true,
					'duplicate' => false,
					'remove'    => true,
					'sort'      => true,
				],
				'default'      => self::get_default_tabs(),
				'title_field' => '{{{ tab_name }}}',
			]
		);

		$widget->end_controls_section();
	}

	public static function add_section_tabs( Base_Widget $widget ) {
		$widget->start_controls_section(
			'section_content_tabs',
			[
				'label' => esc_html__( 'Tabs', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'tabs_layout',
			[
				'label'        => esc_html__( 'Layout', 'jupiterx-core' ),
				'type'         => 'select',
				'options'      => [
					'vertical'   => esc_html__( 'Vertical', 'jupiterx-core' ),
					'horizontal' => esc_html__( 'Horizontal', 'jupiterx-core' ),
				],
				'default'      => 'vertical',
				'render_type'  => 'template',
			]
		);

		$widget->add_responsive_control(
			'tabs_content_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--tab-content-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			'tabs_position',
			[
				'label'                => esc_html__( 'Tabs Position', 'jupiterx-core' ),
				'type'                 => 'choose',
				'options'              => [
					'start'   => [
						'title' => esc_html__( 'Start', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-center',
					],
					'end'     => [
						'title' => esc_html__( 'End', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'condition'            => [
					'tabs_layout' => 'horizontal',
				],
				'selectors'            => [
					'{{WRAPPER}}' => '{{VALUE}};',
				],
				'selectors_dictionary' => [
					'start'   => '--nav-justify: flex-start;    --nav-ul-width: fit-content; --nav-li-width: auto; --nav-li-flex-grow: 0;',
					'center'  => '--nav-justify: center;        --nav-ul-width: fit-content; --nav-li-width: auto; --nav-li-flex-grow: 0;',
					'end'     => '--nav-justify: flex-end;      --nav-ul-width: fit-content; --nav-li-width: auto; --nav-li-flex-grow: 0;',
					'stretch' => '--nav-justify: space-between; --nav-ul-width: 100%;        --nav-li-width: 100%; --nav-li-flex-grow: 1;',
				],
			]
		);

		$widget->add_responsive_control(
			'tabs_alignment',
			[
				'label'      => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type'       => 'choose',
				'default'    => 'flex-start',
				'toggle'     => false,
				'options'    => [
					'flex-start'    => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'       => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'      => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-right',
					],
					'space-between' => [
						'title'   => esc_html__( 'Spaced', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors'  => [
					'{{WRAPPER}}'  => '--atag-content-alignment: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'tabs_layout',
							'operator' => '!in',
							'value'    => [
								'horizontal',
							],
						],
						[
							'name'     => 'tabs_position',
							'operator' => '!in',
							'value'    => [
								'start',
								'center',
								'end',
							],
						],
					],
				],
			]
		);

		$widget->end_controls_section();
	}

	private static function get_default_tabs() {
		$result = [];

		foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
			$result[] = [
				'field_key'               => $endpoint,
				'tab_name'                => $label,
				'is_default'              => 'yes',
				'custom_template_enabled' => 'no',
			];
		}

		return $result;
	}
}
