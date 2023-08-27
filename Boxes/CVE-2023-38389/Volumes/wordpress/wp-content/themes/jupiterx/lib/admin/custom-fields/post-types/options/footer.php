<?php
/**
 * Add Jupiter Post Options > Footer meta options.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since   1.0.0
 */

$key    = 'field_jupiterx_post_footer';
$parent = 'group_jupiterx_post';

// Footer tab.
acf_add_local_field( [
	'key'    => "{$key}_tab",
	'parent' => $parent,
	'label'  => __( 'Footer', 'jupiterx' ),
	'type'   => 'tab',
] );

acf_add_local_field( [
	'key'       => "{$key}_layout_builder_notice",
	'parent'    => $parent,
	'label'     => '',
	'name'      => '',
	'type'      => 'message',
	'message'   => sprintf(
		/* translators: field name & Meta box name */
		__( '%1$s <a class="jupiterx-acf-notice-link" href="%2$s" target="_blank"><strong>%3$s<span class=" dashicons dashicons-external"></span></strong></a> %4$s', 'jupiterx' ),
		__( 'It’s recommended to use the new ', 'jupiterx' ),
		esc_url( admin_url( 'admin.php?page=jupiterx#/layout-builder' ) ),
		__( 'Layout Builder', 'jupiterx' ),
		__( 'feature.', 'jupiterx' )
	),
	'new_lines' => 'wpautop',
	'esc_html'  => 0,
	'wrapper'   => [
		'class'     => 'jupiterx-meta-instruction',
	],
	'required'  => 0,
] );

// Footer.
acf_add_local_field( [
	'key'           => $key,
	'parent'        => $parent,
	'label'         => __( 'Footer', 'jupiterx' ),
	'name'          => 'jupiterx_footer',
	'type'          => 'true_false',
	'wrapper'       => [ 'width' => '50' ],
	'default_value' => 1,
	'ui'            => 1,
] );

// Type.
acf_add_local_field( [
	'key'               => "{$key}_type",
	'parent'            => $parent,
	'label'             => __( 'Type', 'jupiterx' ),
	'name'              => 'jupiterx_footer_type',
	'type'              => 'button_group',
	'conditional_logic' => [
		[
			[
				'field'    => $key,
				'operator' => '==',
				'value'    => '1',
			],
		],
	],
	'wrapper'           => [ 'width' => '50' ],
	'choices'           => [
		'global'  => __( 'Global', 'jupiterx' ),
		'_custom' => __( 'Custom', 'jupiterx' ),
	],
	'default_value'     => 'global',
] );

// Template.
acf_add_local_field( [
	'key'               => "{$key}_template",
	'parent'            => $parent,
	'label'             => __( 'Template', 'jupiterx' ),
	'name'              => 'jupiterx_footer_template',
	'type'              => 'jupiterx_template',
	'conditional_logic' => [
		[
			[
				'field'    => "{$key}_type",
				'operator' => '==',
				'value'    => '_custom',
			],
		],
	],
	'wrapper'           => [ 'width' => '50' ],
	'choices'           => [
		'global' => __( 'Global', 'jupiterx' ),
	],
	'ui'                => 1,
	'ajax'              => 1,
	'default_value'     => 'global',
	'template_type'     => 'footer',
] );

if ( '_custom' !== get_theme_mod( 'jupiterx_footer_type', '' ) ) {
	// Footer.
	acf_add_local_field( [
		'key'     => "{$key}_widget_area",
		'parent'  => $parent,
		'label'   => __( 'Widget Area', 'jupiterx' ),
		'name'    => 'jupiterx_footer_widget_area',
		'type'    => 'button_group',
		'conditional_logic' => [
			[
				[
					'field'    => "{$key}_type",
					'operator' => '!=',
					'value'    => '_custom',
				],
				[
					'field'    => $key,
					'operator' => '==',
					'value'    => '1',
				],
			],
		],
		'wrapper' => [ 'width' => '50' ],
		'choices' => [
			'global' => __( 'Global', 'jupiterx' ),
			'1'      => __( 'Yes', 'jupiterx' ),
			''       => __( 'No', 'jupiterx' ),
		],
		'default_value' => 'global',
	] );

	// Sub Footer.
	acf_add_local_field( [
		'key'           => "{$key}_sub",
		'parent'        => $parent,
		'label'         => __( 'Sub Footer', 'jupiterx' ),
		'name'          => 'jupiterx_footer_sub',
		'type'          => 'button_group',
		'conditional_logic' => [
			[
				[
					'field'    => "{$key}_type",
					'operator' => '!=',
					'value'    => '_custom',
				],
				[
					'field'    => $key,
					'operator' => '==',
					'value'    => '1',
				],
			],
		],
		'wrapper'       => [ 'width' => '50' ],
		'choices'       => [
			'global' => __( 'Global', 'jupiterx' ),
			'1'      => __( 'Yes', 'jupiterx' ),
			''       => __( 'No', 'jupiterx' ),
		],
		'default_value' => 'global',
	] );
}

// Behavior - Global site width.
if ( 'full_width' === get_theme_mod( 'jupiterx_site_width', 'full_width' ) ) {
	acf_add_local_field( [
		'key'               => "{$key}_behavior",
		'parent'            => $parent,
		'label'             => __( 'Behavior', 'jupiterx' ),
		'name'              => 'jupiterx_footer_behavior',
		'type'              => 'button_group',
		'wrapper'           => [ 'width' => '50' ],
		'conditional_logic' => [
			[
				[
					'field'    => $key,
					'operator' => '==',
					'value'    => '1',
				],
			],
		],
		'choices'           => [
			'global' => __( 'Global', 'jupiterx' ),
			'static' => __( 'Static', 'jupiterx' ),
			'fixed'  => __( 'Fixed', 'jupiterx' ),
		],
		'default_value'     => 'global',
	] );
}
