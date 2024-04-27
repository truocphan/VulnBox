<?php
/**
 * Plugin Admin Settings
 */

/* 
**========== Block direct access =========== 
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*** Check if class exist */
if ( ! class_exists( 'PPOM_SettingsFramework' ) ) {
	return;
}

/**
 * Register Panel Fieds Against Panel ID
 * --------------------------------------
 */
$core_settings = array(
	'ppom_general_section1'               => array(
		'type'  => 'section',
		'title' => __( 'Basic Settings', 'woocommerce-product-addon' ),
	),
	'ppom_disable_bootstrap'              => array(
		'type'  => 'checkbox',
		'title' => __( 'Disable Bootstrap', 'woocommerce-product-addon' ),
		'desc'  => __( 'Bootstrap JS is being loaded from CDN, it will disable if your site already loading it.', 'woocommerce-product-addon' ),
		'label' => __( 'Yes', 'woocommerce-product-addon' ),
	),
	'ppom_enable_legacy_inputs_rendering' => array(
		'type'  => 'checkbox',
		'title' => __( 'Legacy Inputs Rendering', 'woocommerce-product-addon' ),
		'desc'  => __( 'PPOM Version 22.0 is major update, if some issues occur you can revert back to old version by this.', 'woocommerce-product-addon' ),
	),
	'ppom_new_conditions'                 => array(
		'type'  => 'checkbox',
		'title' => __( 'Legacy Conditions Script', 'woocommerce-product-addon' ),
		'desc'  => __( 'If you found any issue in conditions you may enable this option.', 'woocommerce-product-addon' ),
	),
	'ppom_legacy_price'                   => array(
		'type'  => 'checkbox',
		'title' => __( 'Enable Legacy Price Calculations', 'woocommerce-product-addon' ),
// 'reference' => array(
// 'ref_title' => __( 'See reference', 'woocommerce-product-addon' ),
// 'ref_link'  => 'https://najeebmedia.com/blog/ppom-version-18-0-better-price-manipulation-currency-switcher/',
			// 'ref_video_title' => __( 'Quick Video', 'woocommerce-product-addon' ),
			// 'ref_video_link'  => 'https://www.youtube.com/watch?v=0wCC3aLXdOw',
// ),
	),
	'ppom_permission_mfields'             => array(
		'type'        => 'select',
		'title'       => __( 'PPOM Permissions', 'woocommerce-product-addon' ),
		'desc'        => __( 'You can set permissions here so PPOM fields can be managed by different roles', 'woocommerce-product-addon' ),
		'default'     => 'administrator',
		'placeholder' => __( 'choose role', 'woocommerce-product-addon' ),
		'options'     => ppom_get_all_editable_roles(),
		'style'       => 'multiselect',
	),
	'ppom_restricted_file_type'           => array(
		'type'    => 'text',
		'title'   => __( 'Restricted file types here', 'woocommerce-product-addon' ),
		'default' => __( 'php,php4,php5,php6,php7,phtml,exe,shtml', 'woocommerce-product-addon' ),
	),
	'ppom_general_section2'               => array(
		'type'  => 'section',
		'title' => __( 'Label Settings', 'woocommerce-product-addon' ),
	),
	'ppom_label_option_total'             => array(
		'type'    => 'text',
		'title'   => __( 'Option Total Label inside Price Table', 'woocommerce-product-addon' ),
		'default' => __( 'Option Total', 'woocommerce-product-addon' ),
	),
	'ppom_label_product_price'            => array(
		'type'    => 'text',
		'title'   => __( 'Product Price Label inside Price Table', 'woocommerce-product-addon' ),
		'default' => __( 'Product Price', 'woocommerce-product-addon' ),
	),
	'ppom_label_total'                    => array(
		'type'    => 'text',
		'title'   => __( 'Total Label inside Price Table', 'woocommerce-product-addon' ),
		'default' => __( 'Total', 'woocommerce-product-addon' ),
	),
	'ppom_label_total_discount'           => array(
		'type'    => 'text',
		'title'   => __( 'Total Discount Label inside Price Table', 'woocommerce-product-addon' ),
		'default' => __( 'Total Discount', 'woocommerce-product-addon' ),
	),
	'ppom_label_option_total_suffex'      => array(
		'type'  => 'text',
		'title' => __( 'Option Total Suffix', 'woocommerce-product-addon' ),
		'desc'  => __( 'E.g for Tax/Va info like. Vat included', 'woocommerce-product-addon' ),
	),
);

/**
 * Register Panel Against Tab ID
 * -------------------------------
 */
$panel_meta = array(
	'ppom_admin_core_settings' => array(
		'id'          => 'ppom_admin_core_settings',
		'title'       => 'General Settings',
		'desc'        => '',
		'is_sabpanel' => true,
		'active'      => 'yes',
	),
);


/**
 * Register Main Tabs
 * --------------------------
 */
$register_tabs = array(
	'ppom_general_tab' => array(
		'tab_id'  => 'ppom_general_tab',
		'title'   => 'PPOM',
		'icon'    => '',
		'classes' => array( 'active' ),
		'enable'  => true,
	),
);


/**
 * Register Settings Panel
 * --------------------------
 */
PPOMSETTINGS()->register_tabs( $register_tabs )->register_panel( 'ppom_general_tab', $panel_meta );
PPOMSETTINGS()->register_setting( 'ppom_admin_core_settings', $core_settings );
