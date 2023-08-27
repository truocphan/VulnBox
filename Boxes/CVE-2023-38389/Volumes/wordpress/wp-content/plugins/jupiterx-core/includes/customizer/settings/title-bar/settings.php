<?php
/**
 * Add Jupiter settings for Title Bar > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_title_bar';

// Type.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_title_bar_type',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Type', 'jupiterx-core' ),
	'default'  => '',
	'choices'  => [
		'' => [
			'label' => __( 'Default', 'jupiterx-core' ),
		],
		'_custom' => [
			'label' => __( 'Custom', 'jupiterx-core' ),
			'pro'   => true,
		],
	],
] );

// Full Width.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-toggle',
	'settings'        => 'jupiterx_title_bar_full_width',
	'section'         => $section,
	'box'             => 'settings',
	'label'           => __( 'Full Width', 'jupiterx-core' ),
	'default'         => false,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_title_bar_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Title HTML Tag.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-select',
	'settings'        => 'jupiterx_title_bar_title_tag',
	'section'         => $section,
	'box'             => 'settings',
	'label'           => __( 'Title HTML Tag', 'jupiterx-core' ),
	'default'         => 'h1',
	'choices'         => [
		'h1'   => 'h1',
		'h2'   => 'h2',
		'h3'   => 'h3',
		'h4'   => 'h4',
		'h5'   => 'h5',
		'h6'   => 'h6',
		'div'  => 'div',
		'span' => 'span',
		'p'    => 'p',
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_title_bar_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Content.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-choose',
	'settings'        => 'jupiterx_title_bar_elements',
	'section'         => $section,
	'box'             => 'settings',
	'label'           => __( 'Elements', 'jupiterx-core' ),
	'multiple'        => true,
	'default'         => [ 'page_title', 'page_subtitle', 'breadcrumb' ],
	'choices'         => [
		'page_title'    => __( 'Title', 'jupiterx-core' ),
		'page_subtitle' => __( 'Subtitle', 'jupiterx-core' ),
		'breadcrumb'    => __( 'Breadcrumb', 'jupiterx-core' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_title_bar_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-pro-box',
	'settings'        => 'jupiterx_title_bar_custom_pro_box',
	'section'         => $section,
	'box'             => 'settings',
	'active_callback' => [
		[
			'setting'  => 'jupiterx_title_bar_type',
			'operator' => '===',
			'value'    => '_custom',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-divider',
	'settings'      => 'jupiterx_title_bar_divider',
	'section'       => $section,
	'box'           => 'settings',
	'control_attrs' => [
		'style' => 'margin-top: 10px',
	],
] );

// Exceptions.
$jupiterx_post_types = [
	'post'      => __( 'Blog', 'jupiterx-core' ),
	'page'      => __( 'Page', 'jupiterx-core' ),
	'portfolio' => __( 'Portfolio', 'jupiterx-core' ),
];

if ( function_exists( 'jupiterx_get_post_types' ) ) {
	$jupiterx_post_types = array_merge( $jupiterx_post_types, jupiterx_get_post_types( 'labels' ) );
}

$archive_post_types = [
	'archive' => __( 'Archive', 'jupiterx-core' ),
	'search'  => __( 'Search', 'jupiterx-core' ),
	'product' => __( 'Shop', 'jupiterx-core' ),
];

if ( function_exists( 'jupiterx_get_post_types_archives' ) ) {
	$archive_post_types = array_merge( $archive_post_types, jupiterx_get_post_types_archives() );
}

$choices = array_merge( $jupiterx_post_types, $archive_post_types );

$fields = [];

foreach ( $choices as $jupiterx_post_type_item => $label ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$field = [
		'label'   => $label,
		'options' => [
			'type' => [
				'type'     => 'jupiterx-choose',
				'label'    => __( 'Type', 'jupiterx-core' ),
				'default'  => '',
				'choices'  => [
					'' => [
						'label' => __( 'Default', 'jupiterx-core' ),
					],
					'_custom' => [
						'label' => __( 'Custom', 'jupiterx-core' ),
						'pro'   => true,
					],
				],
			],
			'full_width' => [
				'type'    => 'jupiterx-toggle',
				'label'   => __( 'Full Width', 'jupiterx-core' ),
				'default' => false,
			],
			'title_tag' => [
				'type'    => 'jupiterx-select',
				'label'   => __( 'Title HTML Tag', 'jupiterx-core' ),
				'default' => 'h1',
				'choices' => [
					'h1'   => 'h1',
					'h2'   => 'h2',
					'h3'   => 'h3',
					'h4'   => 'h4',
					'h5'   => 'h5',
					'h6'   => 'h6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
			],
			'elements' => [
				'type'     => 'jupiterx-choose',
				'label'    => __( 'Content', 'jupiterx-core' ),
				'default'  => [ 'page_title', 'page_subtitle', 'breadcrumb' ],
				'multiple' => true,
				'choices'  => [
					'page_title'    => [ 'label' => __( 'Title', 'jupiterx-core' ) ],
					'page_subtitle' => [ 'label' => __( 'Subtitle', 'jupiterx-core' ) ],
					'breadcrumb'    => [ 'label' => __( 'Breadcrumb', 'jupiterx-core' ) ],
				],
			],
			'pro_box' => [
				'type' => 'jupiterx-pro-box',
			],
		],
	];

	$fields[ $jupiterx_post_type_item ] = $field;
}

JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-exceptions',
	'settings' => 'jupiterx_title_bar_exceptions',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Conditions', 'jupiterx-core' ),
	'default'  => [],
	'fields'   => $fields,
	'transport' => '',
] );
