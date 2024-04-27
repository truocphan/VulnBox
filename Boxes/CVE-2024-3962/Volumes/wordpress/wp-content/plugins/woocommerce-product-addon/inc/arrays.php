<?php
/**
 * Arrays contining settings/meta detail
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Not Allowed.' );
}


/**
 * Get plugin meta
 */
function ppom_get_plugin_meta() {

	$ppom_meta = array(
		'name'               => 'PPOM',
		'dir_name'           => '',
		'shortname'          => 'nm_personalizedproduct',
		'path'               => PPOM_PATH,
		'url'                => PPOM_URL,
		'db_version'         => 3.12,
		'logo'               => PPOM_URL . '/images/logo.png',
		'menu_position'      => 90,
		'ppom_bulkquantity'  => PPOM_WP_PLUGIN_DIR . '/ppom-addon-bulkquantity/classes/input.bulkquantity.php',
		'ppom_eventcalendar' => PPOM_WP_PLUGIN_DIR . '/ppom-addon-eventcalendar/classes/input.eventcalendar.php',
		'ppom_fixedprice'    => PPOM_WP_PLUGIN_DIR . '/ppom-addon-fixedprice/classes/input.fixedprice.php',
	);

	return apply_filters( 'ppom_plugin_meta', $ppom_meta );
}

/**
 * Return cols for inputs
 */
function ppom_get_input_cols() {

	$ppom_cols = array(
		2  => '2 Col',
		3  => '3 Col',
		4  => '4 Col',
		5  => '5 Col',
		6  => '6 Col',
		7  => '7 Col',
		8  => '8 Col',
		9  => '9 Col',
		10 => '10 Col',
		11 => '11 Col',
		12 => '12 Col',
	);

	return apply_filters( 'ppom_field_cols', $ppom_cols );
}

/**
 * Return visibility options for inputs
 */
function ppom_field_visibility_options() {

	$visibility_options = array(
		'everyone' => __( 'Everyone', 'woocommerce-product-addon' ),
		'guests'   => __( 'Only Guests', 'woocommerce-product-addon' ),
		'members'  => __( 'Only Members', 'woocommerce-product-addon' ),
		'roles'    => __( 'By Role', 'woocommerce-product-addon' ),
	);

	return apply_filters( 'ppom_field_visibility_options', $visibility_options );
}

/**
 * Get timezone list
 */
function ppom_array_get_timezone_list( $selected_regions, $show_time ) {

	if ( $selected_regions == 'All' ) {
		$regions = array(
			DateTimeZone::AFRICA,
			DateTimeZone::AMERICA,
			DateTimeZone::ANTARCTICA,
			DateTimeZone::ASIA,
			DateTimeZone::ATLANTIC,
			DateTimeZone::AUSTRALIA,
			DateTimeZone::EUROPE,
			DateTimeZone::INDIAN,
			DateTimeZone::PACIFIC,
		);
	} else {
		$selected_regions = explode( ',', $selected_regions );
		$tz_regions       = array();

		foreach ( $selected_regions as $region ) {

			switch ( $region ) {
				case 'AFRICA':
					$tz_regions[] = DateTimeZone::AFRICA;
					break;
				case 'AMERICA':
					$tz_regions[] = DateTimeZone::AMERICA;
					break;
				case 'ANTARCTICA':
					$tz_regions[] = DateTimeZone::ANTARCTICA;
					break;
				case 'ASIA':
					$tz_regions[] = DateTimeZone::ASIA;
					break;
				case 'ATLANTIC':
					$tz_regions[] = DateTimeZone::ATLANTIC;
					break;
				case 'AUSTRALIA':
					$tz_regions[] = DateTimeZone::AUSTRALIA;
					break;
				case 'EUROPE':
					$tz_regions[] = DateTimeZone::EUROPE;
					break;
				case 'INDIAN':
					$tz_regions[] = DateTimeZone::INDIAN;
					break;
				case 'PACIFIC':
					$tz_regions[] = DateTimeZone::PACIFIC;
					break;
			}
		}
		$regions = $tz_regions;
	}

	$timezones = array();
	foreach ( $regions as $region ) {
		$timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
	}

	$timezone_offsets = array();
	foreach ( $timezones as $timezone ) {
		$tz                            = new DateTimeZone( $timezone );
		$timezone_offsets[ $timezone ] = $tz->getOffset( new DateTime() );
	}

	// sort timezone by timezone name
	ksort( $timezone_offsets );

	$timezone_list = array();
	foreach ( $timezone_offsets as $timezone => $offset ) {
		$offset_prefix    = $offset < 0 ? '-' : '+';
		$offset_formatted = gmdate( 'H:i', abs( $offset ) );

		$pretty_offset = "UTC$offset_prefix$offset_formatted";

		$t            = new DateTimeZone( $timezone );
		$c            = new DateTime( null, $t );
		$current_time = $c->format( 'g:i A' );

		if ( $show_time == 'on' ) {
			$timezone_list[ $timezone ] = "($pretty_offset) $timezone - $current_time";
		} else {
			$timezone_list[ $timezone ] = "($pretty_offset) $timezone";
		}
	}

	return $timezone_list;
}

/**
 * PPOM WC settings array
 */
function ppom_array_settings() {
	$ppom_fields = $ppom_settings_url = admin_url( 'admin.php?page=ppom' );

	$ppom_admin_url   = admin_url( 'admin-post.php' );
	$ppom_migrate_url = add_query_arg( array( 'action' => 'ppom_migrate_settings_panel' ), $ppom_admin_url );
	$ppom_migrate_url = wp_nonce_url( $ppom_migrate_url, 'ppom_migrate_nonce_action', 'ppom_migrate_nonce' );

	$ppom_settings = array(

		array(
			'title' => 'Settings Panel Migration',
			'type'  => 'title',
			'desc'  => __( 'Please migrate settings to new settings panel framework. <a href="' . esc_url( $ppom_migrate_url ) . '" class="page-title-action">Start Migration</a>', 'woocommerce-product-addon' ),
			'id'    => 'ppom_settings_migration',
		),

		array(
			'title' => 'PPOM Labels',
			'type'  => 'title',
			'desc'  => __( 'Following settings help you the control and customize plugin as per your need. <a href="' . esc_url( $ppom_fields ) . '">PPOM Fields Manager</a>', 'woocommerce-product-addon' ),
			'id'    => 'ppom_labels_settings',
		),

		array(
			'title'    => __( 'Option Total', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'Label For Price Table', 'woocommerce-product-addon' ),
			'default'  => __( 'Option Total', 'woocommerce-product-addon' ),
			'id'       => 'ppom_label_option_total',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),

		array(
			'title'    => __( 'Option Total Suffix', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'E.g for Tax/Va info like. Vat included', 'woocommerce-product-addon' ),
			'default'  => __( '', 'woocommerce-product-addon' ),
			'id'       => 'ppom_label_option_total_suffex',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),
		array(
			'title'    => __( 'Product Price', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'Label For Price Table', 'woocommerce-product-addon' ),
			'default'  => __( 'Product Price', 'woocommerce-product-addon' ),
			'id'       => 'ppom_label_product_price',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),
		array(
			'title'    => __( 'Total', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'Label For Price Table', 'woocommerce-product-addon' ),
			'default'  => __( 'Total', 'woocommerce-product-addon' ),
			'id'       => 'ppom_label_total',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),
		array(
			'title'    => __( 'Fixed Fee', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'Label For Price Table', 'woocommerce-product-addon' ),
			'default'  => __( 'Fixed Fee', 'woocommerce-product-addon' ),
			'id'       => 'ppom_label_fixed_fee',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),
		array(
			'title'    => __( 'Discount Price', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'Label For Price Table', 'woocommerce-product-addon' ),
			'default'  => __( 'Discount Price', 'woocommerce-product-addon' ),
			'id'       => 'ppom_label_discount_price',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),
		array(
			'title'    => __( 'Total Discount', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'Label For Price Table', 'woocommerce-product-addon' ),
			'default'  => __( 'Total Discount', 'woocommerce-product-addon' ),
			'id'       => 'ppom_label_total_discount',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),

		array(
			'title'   => __( 'Disable Bootstrap', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_disable_bootstrap',
			'desc'    => __( 'Bootstrap JS is being loaded from CDN, it will disable if your site already loading it.', 'woocommerce-product-addon' ),
		),

		array(
			'title'   => __( 'Enable Legacy Inputs Rendering', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_enable_legacy_inputs_rendering',
			'desc'    => __( 'PPOM Version 22.0 is major update, if some issues occur you can revert back to old version by this.', 'woocommerce-product-addon' ),
		),

		array(
			'title'   => __( 'Disable FontAwesome', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_disable_fontawesome',
			'desc'    => __( 'FontAwesome are being loaded from CDN, it will disable if your site already loading it.', 'woocommerce-product-addon' ),
		),
		array(
			'title'   => __( 'Enable Legacy Price Calculations', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_legacy_price',
		),
		array(
			'title'       => __( 'PPOM Permissions', 'woocommerce-product-addon' ),
			'type'        => 'ppom_multi_select',
			'label'       => __( 'Button', 'woocommerce-product-addon' ),
			'default'     => 'administrator',
			'placeholder' => 'choose role',
			'options'     => ppom_get_all_editable_roles(),
			'id'          => 'ppom_permission_mfields',
			'desc'        => __( 'You can set permissions here so PPOM fields can be managed by different roles', 'woocommerce-product-addon' ),
			'desc_tip'    => true,
		),

		array(
			'type' => 'sectionend',
			'id'   => 'ppom_labels_settings',
		),

		array(
			'name' => __( 'Advance Features (PRO)', 'woocommerce-product-addon' ),
			'type' => 'title',
			'desc' => __( 'These options will work when PRO version is installed', 'woocommerce-product-addon' ),
			'id'   => 'ppom_pro_features',
		),

		array(
			'title'   => __( 'Hide Product Price?', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_hide_product_price',
			'desc'    => __( 'Hides Product core price under price Title (When PPOM Fields attached)', 'woocommerce-product-addon' ),

		),

		array(
			'title'   => __( 'Hide Variable Product Price?', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_hide_variable_product_price',
			'desc'    => __( 'Hides Variable Product core price under price Title (When PPOM Fields attached)', 'woocommerce-product-addon' ),

		),


		array(
			'title'   => __( 'Hide Options Price?', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_hide_option_price',
			'desc'    => __( 'Hides options price in Selec/Radio/Checkbox/Image display prices with label', 'woocommerce-product-addon' ),

		),

		array(
			'title'   => __( 'Taxable Options Price?', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_taxable_option_price',
			'desc'    => __( 'Apply tax settings on option prices from WooCommerce->Tax', 'woocommerce-product-addon' ),

		),

		array(
			'title'   => __( 'Clear Fields after Add to Cart?', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_hide_clear_fields',
			'desc'    => __( 'Empty all fields on Product page after to cart.', 'woocommerce-product-addon' ),

		),

		array(
			'title'   => __( 'Enable PPOM REST API?', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_api_enable',
			'desc'    => __( 'Check this option to enable PPOM REST API', 'woocommerce-product-addon' ),
		),

		array(
			'title'   => __( 'Use optimized Price Table Caculation (BETA)', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_price_table_v2',
			'desc'    => __( 'A Fast and Optimized script to caculate price on product page in Table.', 'woocommerce-product-addon' ),
		),

		array(
			'title'   => __( 'Enable New Conditional Logic Script', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_new_conditions',
			'desc'    => __( 'A faster approach to load conditional fields. Beta version, please report bug in new conditional script.', 'woocommerce-product-addon' ),
		),

		array(
			'title'   => __( 'Do not send Product Meta to PayPal Invoice', 'woocommerce-product-addon' ),
			'type'    => 'checkbox',
			'label'   => __( 'Yes', 'woocommerce-product-addon' ),
			'default' => 'no',
			'id'      => 'ppom_disable_meta_paypal_invoice',
			'desc'    => __( 'Product meta will not be sent to PayPal invoice, only the Item name will be sent to invoice', 'woocommerce-product-addon' ),
		),

		array(
			'title'    => __( 'Select Option Label', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'Label For Price Table', 'woocommerce-product-addon' ),
			'default'  => __( 'Select Options', 'woocommerce-product-addon' ),
			'id'       => 'ppom_label_select_option',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),


		array(
			'title'    => __( 'PPOM API Secret Key', 'woocommerce-product-addon' ),
			'type'     => 'text',
			'desc'     => __( 'Enter any characters to create a secret key. This key must be set while requesting to API', 'woocommerce-product-addon' ),
			'id'       => 'ppom_rest_secret_key',
			'css'      => 'min-width:300px;',
			'desc_tip' => true,
		),

		array(
			'title'    => __( 'Delete Un-used images', 'woocommerce-product-addon' ),
			'type'     => 'select',
			'label'    => __( 'Button', 'woocommerce-product-addon' ),
			'default'  => 'daily',
			'options'  => array(
				'daily'   => __( 'Daily', 'woocommerce-product-addon' ),
				'weekly'  => __( 'Weekly', 'woocommerce-product-addon' ),
				'monthly' => __( 'Monthly', 'woocommerce-product-addon' ),
			),
			'id'       => 'ppom_remove_unused_images_schedule',
			'desc'     => __( 'Set duration to uploaded images of abandoned cart. Re-activate plugin to when update this option', 'woocommerce-product-addon' ),
			'desc_tip' => true,
		),

		array(
			'title'    => __( 'Meta Group Overrides', 'woocommerce-product-addon' ),
			'type'     => 'select',
			'label'    => __( 'Button', 'woocommerce-product-addon' ),
			'default'  => 'default',
			'options'  => array(
				'default'             => __( 'Default', 'woocommerce-product-addon' ),
				'category_override'   => __( 'Category Overrides Individual Assignment', 'woocommerce-product-addon' ),
				'individual_override' => __( 'Individual Overrides Category Assignment', 'woocommerce-product-addon' ),
			),
			'id'       => 'ppom_meta_overrides',
			'desc'     => __( 'Leave if default if not sure.', 'woocommerce-product-addon' ),
			'desc_tip' => true,
		),

		array(
			'title'    => __( 'Meta Group Priority', 'woocommerce-product-addon' ),
			'type'     => 'select',
			'label'    => __( 'Button', 'woocommerce-product-addon' ),
			'default'  => 'default',
			'options'  => array(
				'category_first'   => __( 'Category First', 'woocommerce-product-addon' ),
				'individual_first' => __( 'Individual First', 'woocommerce-product-addon' ),
			),
			'id'       => 'ppom_meta_priority',
			'desc'     => __( 'Leave if default if not sure.', 'woocommerce-product-addon' ),
			'desc_tip' => true,
		),

		array(
			'title'    => __( 'Price Table Position', 'woocommerce-product-addon' ),
			'type'     => 'select',
			'label'    => __( 'Button', 'woocommerce-product-addon' ),
			'default'  => 'after',
			'options'  => array(
				'after'  => __( 'After PPOM Fields', 'woocommerce-product-addon' ),
				'before' => __( 'Before  PPOM Fields', 'woocommerce-product-addon' ),
			),
			'id'       => 'ppom_price_table_location',
			'desc'     => __( 'Set the location to render Price Table on Front-end', 'woocommerce-product-addon' ),
			'desc_tip' => true,
		),

		array(
			'type' => 'sectionend',
			'id'   => 'ppom_pro_features',
		),

	);

	return apply_filters( 'ppom_settings_data', $ppom_settings );
}

/**
 * PPOM Scripts Vars
 * It only used for addon via this function "ppom_hooks_load_input_scripts"
 * cart-edit-addon
 * svg-addon
 */
function ppom_array_get_js_input_vars( $product, $args = null ) {

	// ppom_pa($args);
	$defaults = array(
		'wc_no_decimal'       => wc_get_price_decimals(),
		'show_price_per_unit' => false,
	);

	// Parse incoming $args into an array and merge it with $defaults
	$args                = wp_parse_args( $args, $defaults );
	$decimal_palces      = $args['wc_no_decimal'];
	$show_price_per_unit = $args['show_price_per_unit'];

	$product_id         = ppom_get_product_id( $product );
	$ppom               = new PPOM_Meta( $product_id );
	$ppom_meta_settings = $ppom->ppom_settings;
	$ppom_meta_fields   = $ppom->fields;

	if ( ! empty( $ppom_id ) ) {
		$ppom_meta_fields   = $ppom->get_fields_by_id( $ppom_id );
		$ppom_meta_settings = $ppom->get_settings_by_id( $ppom_id );
	}

	$ppom_meta_fields_updated = array();
	foreach ( $ppom_meta_fields as $index => $fields_meta ) {

		$type      = isset( $fields_meta['type'] ) ? $fields_meta['type'] : '';
		$title     = ( isset( $fields_meta['title'] ) ? $fields_meta ['title'] : '' );
		$data_name = ( isset( $fields_meta['data_name'] ) ? $fields_meta ['data_name'] : $title );

		$fields_meta['data_name'] = sanitize_key( $data_name );
		$fields_meta['title']     = stripslashes( $title );

		$fields_meta['field_type'] = apply_filters( 'ppom_js_fields', $type, $fields_meta );

		// Some field specific settings
		switch ( $type ) {

			case 'daterange':
				// Check if value is in GET 
				if ( ! empty( $_GET[ $data_name ] ) ) {

					$value                     = sanitize_text_field( $_GET[ $data_name ] );
					$to_dates                  = explode( ' - ', $value );
					$fields_meta['start_date'] = $to_dates[0];
					$fields_meta['end_date']   = $to_dates[0];
				}
				break;

			case 'color':
				// Check if value is in GET
				if ( ! empty( $_GET[ $data_name ] ) ) {

					$fields_meta['default_color'] = sanitize_text_field( $_GET[ $data_name ] );
				}
				break;

			case 'bulkquantity':
				$fields_meta['options'] = stripslashes( $fields_meta['options'] );

				// To make bulkquantity option WOOCS ready
				$bulkquantities_options     = json_decode( $fields_meta['options'], true );
				$bulkquantities_new_options = array();
				foreach ( $bulkquantities_options as $bq_opt ) {
					$bq_array = array();
					foreach ( $bq_opt as $key => $value ) {

						if ( $key != 'Quantity Range' ) {
							$bq_array[ $key ] = apply_filters( 'ppom_option_price', $value );
						} else {
							$bq_array[ $key ] = $value;
						}
					}
					$bulkquantities_new_options[] = $bq_array;
				}

				$fields_meta['options'] = json_encode( $bulkquantities_new_options );
				break;
		}

		$ppom_meta_fields_updated[] = $fields_meta;

	}


	$js_vars                              = array();
	$js_vars['ajaxurl']                   = admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) );
	$js_vars['ppom_inputs']               = $ppom_meta_fields_updated;
	$js_vars['field_meta']                = $ppom_meta_fields_updated;
	$js_vars['ppom_validate_nonce']       = wp_create_nonce( 'ppom_validating_action' );
	$js_vars['wc_thousand_sep']           = wc_get_price_thousand_separator();
	$js_vars['wc_currency_pos']           = get_option( 'woocommerce_currency_pos' );
	$js_vars['wc_decimal_sep']            = get_option( 'woocommerce_price_decimal_sep' );
	$js_vars['wc_no_decimal']             = $decimal_palces;
	$variation_id                         = '';
	$context                              = 'product';
	$js_vars['wc_product_price']          = ppom_get_product_price( $product, $variation_id, $context );
	$js_vars['wc_product_regular_price']  = ppom_get_product_regular_price( $product );
	$ppom_label_discount_price            = ppom_get_option( 'ppom_label_discount_price', __( 'Discount Price', 'woocommerce-product-addon' ) );
	$ppom_label_product_price             = ppom_get_option( 'ppom_label_product_price', __( 'Product Price', 'woocommerce-product-addon' ) );
	$ppom_label_option_total              = ppom_get_option( 'ppom_label_option_total', __( 'Option Total', 'woocommerce-product-addon' ) );
	$ppom_label_fixed_fee                 = ppom_get_option( 'ppom_label_fixed_fee', __( 'Fixed Fee', 'woocommerce-product-addon' ) );
	$ppom_label_total_discount            = ppom_get_option( 'ppom_label_total_discount', __( 'Total Discount', 'woocommerce-product-addon' ) );
	$ppom_label_total                     = ppom_get_option( 'ppom_label_total', __( 'Total', 'woocommerce-product-addon' ) );
	$js_vars['total_discount_label']      = sprintf( __( '%s', 'woocommerce-product-addon' ), $ppom_label_total_discount );
	$js_vars['price_matrix_heading']      = sprintf( __( '%s', 'woocommerce-product-addon' ), $ppom_label_discount_price );
	$js_vars['product_base_label']        = sprintf( __( '%s', 'woocommerce-product-addon' ), $ppom_label_product_price );
	$js_vars['option_total_label']        = sprintf( __( '%s', 'woocommerce-product-addon' ), $ppom_label_option_total );
	$js_vars['fixed_fee_heading']         = sprintf( __( '%s', 'woocommerce-product-addon' ), $ppom_label_fixed_fee );
	$js_vars['total_without_fixed_label'] = sprintf( __( '%s', 'woocommerce-product-addon' ), $ppom_label_total );
	$js_vars['product_quantity_label']    = __( 'Product Quantity', 'woocommerce-product-addon' );
	$js_vars['product_title']             = sprintf( __( '%s', 'woocommerce-product-addon' ), $product->get_title() );
	$js_vars['per_unit_label']            = __( 'unit', 'woocommerce-product-addon' );
	$js_vars['show_price_per_unit']       = $show_price_per_unit;
	$js_vars['text_quantity']             = __( 'Quantity', 'woocommerce-product-addon' );
	$js_vars['show_option_price']         = $ppom->price_display;
	$js_vars['is_shortcode']              = 'no';
	$js_vars['plugin_url']                = PPOM_URL;
	$js_vars['is_mobile']                 = ppom_is_mobile();
	$js_vars['product_id']                = $product_id;
	$js_vars['tax_prefix']                = ppom_tax_label_display();

	return apply_filters( 'ppom_input_vars', $js_vars, $product );
}

/**
 * Showing Tax prefix
 *
 * @since 20.5
 */
function ppom_tax_label_display() {
	/*
	if ( wc_tax_enabled() && 'excl' === get_option( 'woocommerce_tax_display_shop' ) &&  get_option( 'woocommerce_price_display_suffix' ) !== '' ) {
		return sprintf(__("%s", 'woocommerce-product-addon'), get_option( 'woocommerce_price_display_suffix' ));
	}*/

	$suffex = ppom_get_option( 'ppom_label_option_total_suffex' );
	if ( wc_tax_enabled() && $suffex !== '' ) {
		return sprintf( __( '%s', 'woocommerce-product-addon' ), $suffex );
	}
}

/**
 * Get PPOM inputs
 */
function ppom_array_all_inputs() {

	$all_inputs = array(
		'core' => array(
			'text',
			'textarea',
			'select',
			'radio',
			'checkbox',
			'email',
			'date',
			'number',
			'hidden',
			'daterange',
			'color',
			'file',
			'image',
			'timezone',
			'quantities',
			'cropper',
			'pricematrix',
			'section',
			'palettes',
			'audio',
			'measure',
			'divider',
		),
	);

	return apply_filters( 'ppom_all_inputs_array', $all_inputs );
}

/**
 * Get all PPOM addons details
 */
function ppom_array_get_addons_details() {

	$addons = array(
		array(
			'title'   => __( 'Texter', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Texter Addon is the best and simple solution for web2print business using WooCommerce. Now define a fixed position and area for Text in your Templates like on Mug, T-shirt or Visiting Cards with preset font family, size. The client will fill the text with his all of its attributes and send to cart. It’s like a smart Product Designer. Multiple templates can also be attached to one product.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#texter',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Fixed Price', 'woocommerce-product-addon' ),
			'desc'    => __( 'Sometimes prices are very complex like for a printing company, they are selling their visiting cards in Packages.So Package Price Add-on allows admin to set prices against package. It’s usage is very simple, just add quantity (package) and it’s price. There is also option to set unit like you are selling visiting cards then unit may called as “cards”.', 'woocommerce-product-addon' ),
			'actions' => array(),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Fields PopUp', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Fields PopUp wrap all PPOM fields inside a popup. A product with large number of fields can now has simple button with customized label. To enable this PopUp just one click required in product edit page. ', 'woocommerce-product-addon' ),
			'actions' => array(),
			'type' => 'feature'
		),
		array(
			'title'   => __( 'Font Picker', 'woocommerce-product-addon' ),
			'desc'    => __( 'Font selector loads fonts from Google and client can pick font and can see live preview of font effect. Admin can also filter font families and set Custom Fonts.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#fonts-picker',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Image DropDown', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Image DropDown Addon show images inside a select box. The title, description, and prices can be added along with all images. It’s best when you have a long list of images and don’t want to use Image Type input.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#image-dropdown',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Bulk Quantity Options', 'woocommerce-product-addon' ),
			'desc'    => __( 'Bulk Quantity for Options Addon allow store admin to set discount prices for each options. This Addon is best tool for companies like Printin, designing and who looking to sale products with options with different prices.', 'woocommerce-product-addon' ),
			'actions' => array(),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Cart Edit', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Cart Edit Addon allow clients to edit fields once these are added to cart on cart page. It has also option to show all meta fields in different column on cart page. Extra column can be disable from Settings -> PPOM Cart tab.', 'woocommerce-product-addon' ),
			'actions' => array(),
			'type' => 'feature'
		),
		array(
			'title'   => __( 'Domain Checker', 'woocommerce-product-addon' ),
			'desc'    => __( 'Domain Checker Addon will check any domain’s availability. Adds domain to cart if it’s not already registered. A simple solution to sell domains with WooCommerce PPOM. Customized messages for domain availability/not-availability. Ajax base script to check domain and show result.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#domain',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Quantities Pack', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Quantity Pack Add-on is a special input type which is very similar to Variation Quantities input but with a good difference. Like if you are want to sell some products with different options in specific quantities, not sure?', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#quantities-pack',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Super List', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Super List addon contains many pre-defined lists to render as Select field on product page. It includes Countries, Currencies, Months etc. ', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#super-list',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Collapse', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Field Collapsed Add-on is front-end design which groups field inside beautiful section. Like steps, and if your product has a large number of fields then it is the best add-on to a short length of your product page.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#collapse',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Text Counter', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Text Counter Add-on is special type text input field. It can restrict a total number of words or character with nice info panel below. Each word or character can be changed and the price will be added to cart.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#text-counter',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Enquiry Form', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Enquiry Form Add-on adds button on product page. A customer can ask the admin about any product with PPOM Field in email. All PPOM Meta Fields are sent with message typed by the customer. Multiple email recipients can be added for product enquiry.', 'woocommerce-product-addon' ),
			'actions' => array(),
			'type' => 'feature'
		),
		array(
			'title'   => __( 'Variation Quantity Matrix', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Variation Quantity Matrix Add-on is an advanced form of Variation Quantity Field. Variation Quantity Matrix is a super simple form when quantities need to be collected against multiple options. Like if you selling T-Shirt and need to collect Quantities against each Color of each Size, this Add-on can be used to render a Tabular/Grid. ', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#variation-matrix',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Quantity Option', 'woocommerce-product-addon' ),
			'desc'    => __( 'PPOM Quantity Option Add-on is simple ‘Number’ input type which can be used to accept option quantity BUT Price can also be set. Like for a Pizza Product, customer have a option to order more then one drink and each extra drink as price. Here this Option Quantity will do the trick.  This Add-on can be used as Name your Price.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#quantity-option',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Emojis', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'The Emoji field adds emoji support to the input (can be textarea, text, dropdown.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#emojis',
				),
				array(
					'title' => __( 'Screenshot (Front)', 'woocommerce-product-addon' ),
					'link'  => 'https://vertis.d.pr/i/vp3wRy',
				),
				array(
					'title' => __( 'Screenshot (Admin)', 'woocommerce-product-addon' ),
					'link'  => 'https://vertis.d.pr/i/9SaoOW',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Phone Input', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'The Phone field provides a formatted text input field with a country selection dropdown.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#phone-input',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Chained Input', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Allows to create chained dropdown options.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#chained-input',
				),
				array(
					'title' => __( 'Screenshot (Front)', 'woocommerce-product-addon' ),
					'link'  => 'https://vertis.d.pr/i/kFexj7',
				),
				array(
					'title' => __( 'Screenshot (Admin)', 'woocommerce-product-addon' ),
					'link'  => 'https://vertis.d.pr/i/j4ZiNf',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Conditional Images', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'That field is used to show dependent fields according to the selected image.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#conditional-images',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Select Option Quantity', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'This input allows you to display a dropdown with options along with their quantities.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#select-option',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Radio Switcher', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'The Radio Switcher input creates a more appealing view for the product page, and it allows different images for each option.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#radio-switcher',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Date Range Input', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Date Range Picker allows clients to select dates, times, or from predefined ranges.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#date-range-picker',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-date-range-input/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Color Picker', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Color Picker allows the client to select a color. Palette colors can also be defined in settings.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#color-picker-input',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-color-picker/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'File Input', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'File Input allows clients to upload files from their computers or mobile devices. Admin can set any file type and size.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#file-upload',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-file-upload-input/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Image Cropper', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Just like a File Upload but only supports images with a nice cropper using Croppie JS API.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#image-cropper',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-basic-image-cropping-input/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Timezone Input', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Timezone input is a unique form of Select Input, but it has a predefined list of all Time-zones. Different Time-zones can also be filtered with options.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#timezone-input',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-timezone-with-region/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Variation Quantity', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Variation Quantities allow clients to order different quantities against different options.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#variation-quantities',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-variation-quantities/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Images', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Image input is just like Radio/Checkbox, but images can be uploaded against each option. Price can also be set against each image option.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#image-option-input',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-images-options/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Price Matrix', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Price Matrix allows admin to offer client Less Price on Bulk/More quantities. More quantities will decrease the price set in the matrix.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#price-matrix',
				),
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-price-matrix-discount/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'HTML', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'It’s not input, just to add content/text or HTML. Added HTML is shown on product details.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#html-content',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-basic-html-with-content/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Color Palettes', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Display the variations of the products using the Color Palettes. You create the palette from the colors which you wish.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#color-palettes',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-color-palette/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Audio / Video', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Audio/Video input allows clients to select Audio/Video options uploaded by Admin in the media library. It’s similar to Image type input but with a different media type. Price can also be set against each option.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#audio-video-input',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-video-audio/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Measure Input', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Depending on the products that you provide, some of them could require some measurement specifications, and this can be done using Measure Input.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#measure-input',
				),
				array(
					'title' => __( 'Demo', 'woocommerce-product-addon' ),
					'link'  => 'https://demo-ppom-lite.vertisite.cloud/product/demo-for-measure-input/',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Divider', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'This input allows you to define each meta field better and to create a more attractive design for your products.', 'woocommerce-product-addon' ),
			'actions' => array(
				array(
					'title' => __( 'Docs', 'woocommerce-product-addon' ),
					'link'  => 'https://docs.themeisle.com/article/1702-ppom-pro-input-types#divider',
				),
			),
			'type' => 'field'
		),
		array(
			'title'   => __( 'Conditional Field Repeater', 'woocommerce-product-addon' ),
			'desc'    => esc_html__( 'Conditional Field Repeater is a feature used to repeat a PPOM field by the value of another PPOM field (number field).', 'woocommerce-product-addon' ),
			'actions' => array(),
			'type' => 'feature'
		),
	);

	return $addons;
}
