<?php
/**
 * Add Jupiter Taxonomy meta options.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since   1.0.0
 */

acf_add_local_field_group( [
	'key' => 'group_jupiterx_taxonomy_layout',
	'location' => [
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'post_tag',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'category',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'product_tag',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'portfolio_category',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'portfolio_tag',
			],
		],
	],
	'fields' => [
		[
			'key'           => 'field_jupiterx_taxonomy_layout',
			'label'         => __( 'Layout', 'jupiterx' ),
			'name'          => 'jupiterx_layout',
			'type'          => 'select',
			'wrapper'       => [ 'width' => '100' ],
			'choices'       => JupiterX_Customizer_Utils::get_layouts( [
				'global' => __( 'Global', 'jupiterx' ),
			] ),
			'default_value' => 'global',
		],
	],
] );

// Add Order to portfolio & post category.
acf_add_local_field_group( [
	'key' => 'jupiterx_category_taxonomy',
	'location' => [
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'portfolio_category',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'category',
			],
		],
	],
	'fields' => [
		[
			'key'   => 'field_jupiterx_taxonomy_order_number',
			'name'  => 'jupiterx_taxonomy_order_number',
			'label' => __( 'Order', 'jupiterx' ),
			'type'  => 'number',
			'wrapper' => [ 'width' => '100' ],
			'default_value' => 0,
			'min'   => 0,
			'max'   => 999,
		],
	],
] );

acf_add_local_field_group( [
	'key' => 'group_jupiterx_taxonomy_thumbnail',
	'location' => [
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'post_tag',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'category',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'product_tag',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'portfolio_category',
			],
		],
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'portfolio_tag',
			],
		],
	],
	'fields' => [
		[
			'key'           => 'field_jupiterx_taxonomy_thumbnail_id',
			'name'          => 'jupiterx_taxonomy_thumbnail_id',
			'label'         => __( 'Thumbnail', 'jupiterx' ),
			'type'          => 'image',
			'return_format' => 'id',
		],
	],
] );

// Add WooCommerce meta options.
acf_add_local_field_group( [
	'key' => 'group_jupiterx_wc_taxonomy',
	'location' => [
		[
			[
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'product_cat',
			],
		],
	],
	'fields' => [
		[
			'key'           => 'field_jupiterx_taxonomy_layout',
			'label'         => __( 'Layout', 'jupiterx' ),
			'name'          => 'jupiterx_layout',
			'type'          => 'select',
			'wrapper'       => [ 'width' => '50' ],
			'choices'       => JupiterX_Customizer_Utils::get_layouts( [
				'global' => __( 'Global', 'jupiterx' ),
			] ),
			'default_value' => 'global',
		],
	],
] );
