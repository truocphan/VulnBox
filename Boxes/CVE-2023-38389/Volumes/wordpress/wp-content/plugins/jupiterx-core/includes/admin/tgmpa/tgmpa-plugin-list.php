<?php
/**
 * Add Jupiter X pro plugins.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 1.9.0
 */

add_filter( 'jupiterx_tgmpa_plugins', 'jupiterx_pro_plugins' );

/**
 * Add Jupiter X Pro plugins.
 *
 * @since 1.9.0
 *
 * @param array $plugins Array of free Jupiter x plugins.
 * @return array Array af free and pro plugins.
 */
function jupiterx_pro_plugins( $plugins ) {
	$pro_plugins = [
		[
			'name' => __( 'Raven', 'jupiterx-core' ),
			'slug' => 'raven',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jupiter Donut', 'jupiterx-core' ),
			'slug' => 'jupiter-donut',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet Elements', 'jupiterx-core' ),
			'slug' => 'jet-elements',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet Blog', 'jupiterx-core' ),
			'slug' => 'jet-blog',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet Menu', 'jupiterx-core' ),
			'slug' => 'jet-menu',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet Popup', 'jupiterx-core' ),
			'slug' => 'jet-popup',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet Tabs', 'jupiterx-core' ),
			'slug' => 'jet-tabs',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet WooBuilder', 'jupiterx-core' ),
			'slug' => 'jet-woo-builder',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet Tricks', 'jupiterx-core' ),
			'slug' => 'jet-tricks',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet Engine', 'jupiterx-core' ),
			'slug' => 'jet-engine',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Jet SmartFilters', 'jupiterx-core' ),
			'slug' => 'jet-smart-filters',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Advanced Custom Fields PRO', 'jupiterx-core' ),
			'slug' => 'advanced-custom-fields-pro',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Slider Revolution', 'jupiterx-core' ),
			'slug' => 'revslider',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Master Slider', 'jupiterx-core' ),
			'slug' => 'masterslider',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Layer Slider', 'jupiterx-core' ),
			'slug' => 'layerslider',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'WPBakery Page Builder', 'jupiterx-core' ),
			'slug' => 'raven',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => true,
			'source' => 'external',
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Customizer Reset', 'jupiterx-core' ),
			'slug' => 'customizer-reset-by-wpzoom',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => false,
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Customizer Export/Import', 'jupiterx-core' ),
			'slug' => 'customizer-export-import',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => false,
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Sellkit Pro', 'jupiterx-core' ),
			'slug' => 'sellkit-pro',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => false,
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
		[
			'name' => __( 'Sellkit', 'jupiterx-core' ),
			'slug' => 'sellkit',
			'required' => false,
			'force_activation' => false,
			'force_deactivation' => false,
			'pro' => false,
			'label_type' => __( 'Optional', 'jupiterx-core' ),
		],
	];

	return array_merge( $pro_plugins, $plugins );
}
