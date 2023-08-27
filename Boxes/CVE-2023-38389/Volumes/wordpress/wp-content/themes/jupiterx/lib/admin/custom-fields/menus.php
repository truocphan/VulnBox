<?php
/**
 * Add Jupiter Mega Menu options.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since   1.10.0
 */

acf_add_local_field_group(array(
	'key' => 'megamenu_section_template',
	'title' => 'Mega Menu',
	'fields' => [
		[
			'key'               => 'menu_template',
			'label'             => __( 'Mega Menu Template', 'jupiterx' ),
			'name'              => 'jupiterx_mega_template',
			'type'              => 'jupiterx_template',
			'wrapper'           => [ 'width' => '100' ],
			'choices'           => [
				'global' => __( 'None', 'jupiterx' ),
			],
			'ui'                => 1,
			'ajax'              => 1,
			'default_value'     => 'global',
			'template_type'     => 'section',
		],
	],
	'location' => array(
		array(
			array(
				'param' => 'nav_menu_item',
				'operator' => '==',
				'value' => 'all',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));
