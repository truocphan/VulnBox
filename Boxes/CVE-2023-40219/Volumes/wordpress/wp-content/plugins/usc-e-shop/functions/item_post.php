<?php
/**
 * Welcart SKU and Options
 *
 * Functions for manipulating SKUs and Options in product registration.
 *
 * @package Welcart
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add Item SKU.
 *
 * @since  2.3.3
 * @param string  $post_id Post ID.
 * @param string  $new_sku SKU data.
 * @param boolean $check Switch of check.
 * @return New meta id.
 */
function usces_add_sku( $post_id, $new_sku, $check = true ) {
	global $wpdb, $usces;

	if ( $check ) {

		$skus = wel_get_skus( $post_id, 'sort', false );

		if ( ! empty( $skus ) ) {

			$sku_num  = count( $skus );
			$unique   = true;
			$sortnull = true;
			$sort     = array();
			foreach ( (array) $skus as $sku ) {

				if ( (string) $sku['code'] === (string) $new_sku['code'] ) {
					$unique = false;
				}
				if ( ! isset( $sku['sort'] ) ) {
					$sortnull = false;
				}
				$sort[] = $sku['sort'];
			}
			if ( ! $unique ) {
				return -1;
			}

			rsort( $sort );
			$next_number = $sort[0] + 1;
			$unique_sort = array_unique( $sort );
			if ( count( $unique_sort ) !== $sku_num || $sku_num != $next_number || ! $sortnull ) {
				// To repair the sort data.
				$i = 0;
				foreach ( (array) $skus as $sku ) {
					$sku['sort'] = $i;
					wel_update_sku_data_by_id( $sku['meta_id'], $post_id, $sku );
					$i++;
				}
			}
		}
		$new_sku['sort'] = ! empty( $sku_num ) ? $sku_num : 0;
	}
	$new_sku     = $usces->stripslashes_deep_post( $new_sku );
	$new_meta_id = wel_add_sku_data( $post_id, $new_sku );

	return $new_meta_id;
}

/**
 * Add Item SKU by ajax.
 * Ajax response when a new SKU is registered in the SKU block on the product registration screen.
 *
 * @since  2.3.3
 * @param string $post_ID Post ID.
 * @return New meta id.
 */
function add_item_sku_meta( $post_ID ) {
	global $usces;

	$post_id   = (int) $post_ID;
	$value     = array();
	$skus      = array();
	$protected = array( '_wp_attached_file', '_wp_attachment_metadata', '_wp_old_slug', '_wp_page_template' );

	$args = array(
		'newskuname'        => FILTER_DEFAULT,
		'newskucprice'      => FILTER_DEFAULT,
		'newskuprice'       => FILTER_DEFAULT,
		'newskuzaikonum'    => FILTER_DEFAULT,
		'newskuzaikoselect' => FILTER_DEFAULT,
		'newskudisp'        => FILTER_DEFAULT,
		'newskuunit'        => FILTER_DEFAULT,
		'newskugptekiyo'    => FILTER_DEFAULT,
		'newskutaxrate'     => FILTER_DEFAULT,
	);

	$inputs = filter_input_array( INPUT_POST, $args );

	$newskuname        = ( null !== $inputs['newskuname'] ) ? trim( $inputs['newskuname'] ) : '';
	$newskucprice      = ( null !== $inputs['newskucprice'] ) ? $inputs['newskucprice'] : '';
	$newskuprice       = ( null !== $inputs['newskuprice'] ) ? $inputs['newskuprice'] : '';
	$newskuzaikonum    = ( null !== $inputs['newskuzaikonum'] ) ? $inputs['newskuzaikonum'] : '';
	$newskuzaikoselect = ( null !== $inputs['newskuzaikoselect'] ) ? $inputs['newskuzaikoselect'] : '';
	$newskudisp        = ( null !== $inputs['newskudisp'] ) ? trim( $inputs['newskudisp'] ) : '';
	$newskuunit        = ( null !== $inputs['newskuunit'] ) ? trim( $inputs['newskuunit'] ) : '';
	$newskugptekiyo    = ( null !== $inputs['newskugptekiyo'] ) ? $inputs['newskugptekiyo'] : '';
	$newskutaxrate     = ( null !== $inputs['newskutaxrate'] ) ? $inputs['newskutaxrate'] : '';

	if ( ! WCUtils::is_blank( $newskuname ) && ! WCUtils::is_blank( $newskuprice ) && ! WCUtils::is_blank( $newskuzaikoselect ) ) {

		if ( in_array( $newskuname, $protected ) ) {
			return false;
		}

		$value['code']     = $newskuname;
		$value['name']     = $newskudisp;
		$value['cprice']   = $newskucprice;
		$value['price']    = $newskuprice;
		$value['unit']     = $newskuunit;
		$value['stocknum'] = $newskuzaikonum;
		$value['stock']    = $newskuzaikoselect;
		$value['gp']       = $newskugptekiyo;
		$value['taxrate']  = $newskutaxrate;

		$value = apply_filters( 'usces_filter_add_item_sku_meta_value', $value );
		$id    = usces_add_sku( $post_ID, $value, true );

		return $id;
	} else {
		return false;
	}
}

/**
 * Update Item SKU by ajax.
 * Ajax response when SKU information is updated in the SKU block of the product registration screen.
 *
 * @since  2.3.3
 * @param string $post_ID Post ID.
 * @return Query result.
 */
function up_item_sku_meta( $post_ID ) {
	global $wpdb, $usces;

	$post_id = (int) $post_ID;
	$value   = array();

	$skucode   = filter_input( INPUT_POST, 'skuname', FILTER_DEFAULT );
	$skumetaid = filter_input( INPUT_POST, 'skumetaid', FILTER_DEFAULT );

	$res = apply_filters( 'usces_filter_before_up_item_sku_meta', false, $post_ID, $skumetaid, $skucode );
	if ( false !== $res ) {
		return $res;
	}

	$args = array(
		'skucprice'   => FILTER_DEFAULT,
		'skuprice'    => FILTER_DEFAULT,
		'skuzaikonum' => FILTER_DEFAULT,
		'skuzaiko'    => FILTER_DEFAULT,
		'skudisp'     => FILTER_DEFAULT,
		'skuunit'     => FILTER_DEFAULT,
		'skugptekiyo' => FILTER_DEFAULT,
		'sort'        => FILTER_DEFAULT,
		'skutaxrate'  => FILTER_DEFAULT,
	);

	$inputs = filter_input_array( INPUT_POST, $args );

	$skucprice   = ( null !== $inputs['skucprice'] ) ? trim( $inputs['skucprice'] ) : 0;
	$skuprice    = ( null !== $inputs['skuprice'] ) ? trim( $inputs['skuprice'] ) : 0;
	$skuzaikonum = ( null !== $inputs['skuzaikonum'] ) ? trim( $inputs['skuzaikonum'] ) : 0;
	$skuzaiko    = ( null !== $inputs['skuzaiko'] ) ? (int) $inputs['skuzaiko'] : '';
	$skudisp     = ( null !== $inputs['skudisp'] ) ? trim( $inputs['skudisp'] ) : '';
	$skuunit     = ( null !== $inputs['skuunit'] ) ? trim( $inputs['skuunit'] ) : '';
	$skugptekiyo = ( null !== $inputs['skugptekiyo'] ) ? (int) $inputs['skugptekiyo'] : 0;
	$skusort     = ( null !== $inputs['sort'] ) ? $inputs['sort'] : 0;
	$skutaxrate  = ( null !== $inputs['skutaxrate'] ) ? $inputs['skutaxrate'] : '';

	$value['code']     = $skucode;
	$value['name']     = $skudisp;
	$value['cprice']   = $skucprice;
	$value['price']    = $skuprice;
	$value['unit']     = $skuunit;
	$value['stocknum'] = $skuzaikonum;
	$value['stock']    = $skuzaiko;
	$value['gp']       = $skugptekiyo;
	$value['sort']     = $skusort;
	$value['taxrate']  = $skutaxrate;

	$value = $usces->stripslashes_deep_post( $value );

	$skus = wel_get_skus( $post_id, 'meta_id', false );
	foreach ( $skus as $sku ) {
		if ( (string) $sku['code'] === (string) $skucode && $sku['meta_id'] != $skumetaid ) {
			return -1;
		}
	}

	$value = apply_filters( 'usces_filter_up_item_sku_meta_value', $value );

	if ( ! WCUtils::is_blank( $skumetaid ) && ! WCUtils::is_blank( $skucode ) && ! WCUtils::is_blank( $skuprice ) ) {

		$res = wel_update_sku_data_by_id( $skumetaid, $post_id, $value );
		return $res;

	} else {

		return false;
	}
}

/**
 * Delete Item SKU by ajax.
 * Ajax response when SKU is deleted in the SKU block of the product registration screen.
 *
 * @since  2.3.3
 * @param string $post_ID Post ID.
 * @return Result.
 */
function del_item_sku_meta( $post_ID ) {
	global $wpdb, $usces;

	$post_id = (int) $post_ID;
	$meta_id = filter_input( INPUT_POST, 'skumetaid', FILTER_VALIDATE_INT );

	$res = apply_filters( 'usces_filter_before_del_item_sku_meta', false, $post_id, $meta_id );
	if ( false !== $res ) {
		return $res;
	}

	$res = wel_delete_sku_data_by_id( $meta_id, $post_id );

	// Resort.
	$skus = wel_get_skus( $post_id, 'meta_id', false );

	if ( ! empty( $skus ) ) {
		uasort( $skus, function($a, $b) {
			return $a['sort'] <=> $b['sort'];
		});

		$i = 0;
		foreach ( $skus as $sku ) {
			$sku['sort'] = $i;
			$meta_id     = $sku['meta_id'];
			wel_update_sku_data_by_id( $meta_id, $post_id, $sku );
			$i++;
		}
	}
	return;
}

/**
 * List of Item SKUs.
 * To generate a list of SKUs in the SKU block of the product registration screen.
 *
 * @since  2.3.3
 * @param string $skus All SKUs.
 */
function list_item_sku_meta( $skus ) {

	if ( empty( $skus ) ) { // Exit if no meta.
		?>
		<table id="skulist-table" class="list" style="display: none;">
			<thead>
			<tr>
				<th class="hanldh" rowspan="2">&emsp;</th>
				<th><?php esc_html_e( 'SKU code', 'usces' ); ?></th>
				<th><?php echo apply_filters( 'usces_filter_listprice_label', __( 'normal price', 'usces' ), null, null ); ?>(<?php usces_crcode(); ?>)</th>
				<th><?php echo apply_filters( 'usces_filter_sellingprice_label', __( 'Sale price', 'usces' ), null, null ); ?>(<?php usces_crcode(); ?>)</th>
				<th><?php esc_html_e( 'stock', 'usces' ); ?></th>
				<th><?php esc_html_e( 'stock status', 'usces' ); ?></th><?php echo apply_filters( 'usces_filter_sku_meta_title1', '' ); ?>
			</tr>
			</thead>
			<tbody id="item-sku-list">
			<tr><td></td><td></td><td></td><td></td><td></td></tr>
			</tbody>
		</table>
		<?php
	} else {
		?>
		<table id="skulist-table" class="list">
			<thead>
			<tr>
				<th class="hanldh" rowspan="2">&emsp;</th>
				<th class="item-sku-key"><?php esc_html_e( 'SKU code', 'usces' ); ?></th>
				<th class="item-sku-cprice"><?php echo apply_filters( 'usces_filter_listprice_label', __( 'normal price', 'usces' ), null, null ); ?>(<?php usces_crcode(); ?>)</th>
				<th class="item-sku-price"><?php echo apply_filters( 'usces_filter_sellingprice_label', __( 'Sale price', 'usces' ), null, null ); ?>(<?php usces_crcode(); ?>)</th>
				<th class="item-sku-zaikonum"><?php esc_html_e( 'stock', 'usces' ); ?></th>
				<th class="item-sku-zaiko"><?php esc_html_e( 'stock status', 'usces' ); ?></th><?php echo apply_filters( 'usces_filter_sku_meta_title1', '' ); ?>
			</tr>
			<tr>
				<th><?php esc_html_e( 'SKU display name ', 'usces' ); ?></th>
				<th><?php esc_html_e( 'unit', 'usces' ); ?></th>
				<?php
				$advance_title = '<th colspan="2">&nbsp;</th>';
				echo apply_filters( 'usces_filter_sku_meta_form_advance_title', $advance_title );
				?>
				<th><?php esc_html_e( 'Apply business package', 'usces' ); ?></th><?php echo apply_filters( 'usces_filter_sku_meta_title2', '' ); ?>
			</tr>
			</thead>
			<tbody id="item-sku-list">
			<?php
			foreach ( $skus as $sku ) {
				echo _list_item_sku_meta_row( $sku );
			}
			?>
			</tbody>
		</table>
		<?php
	}
}

/**
 * Row of Item SKU List.
 * To generate a list of SKUs in the SKU block of the product registration screen.
 *
 * @since  2.3.3
 * @param string $sku SKU.
 */
function _list_item_sku_meta_row( $sku ) {
	$r     = '';
	$style = '';

	$key               = $sku['code'];
	$cprice            = $sku['cprice'];
	$price             = $sku['price'];
	$zaikonum          = $sku['stocknum'];
	$zaiko             = $sku['stock'];
	$skudisp           = $sku['name'];
	$skuunit           = $sku['unit'];
	$skugptekiyo       = $sku['gp'];
	$id                = (int) $sku['meta_id'];
	$zaikoselectarray  = get_option( 'usces_zaiko_status' );
	$zaikoselect_count = ( $zaikoselectarray && is_array( $zaikoselectarray ) ) ? count( $zaikoselectarray ) : 0;
	$sort              = (int) $sku['sort'];
	$sku_colspan       = apply_filters( 'usces_filter_sku_meta_colspan', '6' );

	ob_start();
	?>
	<tr class="metastuffrow"><td colspan="<?php echo esc_attr( $sku_colspan ); ?>">
		<table id="itemsku-<?php echo esc_attr( $id ); ?>" class="metastufftable">
			<tr>
				<th class="handlb" rowspan="<?php echo apply_filters( 'usces_filter_sku_meta_rowspan', '3' ); ?>">&emsp;</th>
				<td class="item-sku-key"><input name="itemsku[<?php echo esc_attr( $id ); ?>][key]" id="itemsku[<?php echo esc_attr( $id ); ?>][key]" class="skuname metaboxfield" type="text" value="<?php echo esc_attr( $key ); ?>" /></td>
				<td class="item-sku-cprice"><input name="itemsku[<?php echo esc_attr( $id ); ?>][cprice]" id="itemsku[<?php echo esc_attr( $id ); ?>][cprice]" class="skuprice metaboxfield" type="text" value="<?php echo esc_attr( $cprice ); ?>" /></td>
				<td class="item-sku-price"><input name="itemsku[<?php echo esc_attr( $id ); ?>][price]" id="itemsku[<?php echo esc_attr( $id ); ?>][price]" class="skuprice metaboxfield" type="text" value="<?php echo esc_attr( $price ); ?>" /></td>
				<td class="item-sku-zaikonum"><input name="itemsku[<?php echo esc_attr( $id ); ?>][zaikonum]" id="itemsku[<?php echo esc_attr( $id ); ?>][zaikonum]" class="skuzaikonum metaboxfield" type="text" value="<?php echo esc_attr( $zaikonum ); ?>" /></td>
				<td class="item-sku-zaiko">
					<select id="itemsku[<?php echo esc_attr( $id ); ?>][zaiko]" name="itemsku[<?php echo esc_attr( $id ); ?>][zaiko]" class="skuzaiko metaboxfield">
					<?php
					for ( $i = 0; $i < $zaikoselect_count; $i++ ) {
						?>
						<option value="<?php echo esc_attr( $i ); ?>"<?php selected( $zaiko, $i ); ?>><?php echo esc_attr( $zaikoselectarray[ $i ] ); ?></option>
						<?php
					}
					?>
					</select>
				</td><?php echo apply_filters( 'usces_filter_sku_meta_field1', '', $sku ); ?>
			</tr>
			<tr>
				<td class="item-sku-key"><input name="itemsku[<?php echo esc_attr( $id ); ?>][skudisp]" id="itemsku[<?php echo esc_attr( $id ); ?>][skudisp]" class="skudisp metaboxfield" type="text" value="<?php echo esc_attr( $skudisp ); ?>" />
				</td>
				<td class="item-sku-cprice"><input name="itemsku[<?php echo esc_attr( $id ); ?>][skuunit]" id="itemsku[<?php echo esc_attr( $id ); ?>][skuunit]" class="skuunit metaboxfield" type="text" value="<?php echo esc_attr( $skuunit ); ?>" /></td>
				<?php
				$default_field = "\n\t\t" . '<td colspan="2">&nbsp;</td>';
				echo apply_filters( 'usces_filter_sku_meta_row_advance', $default_field, $sku );
				?>
				<td class="item-sku-zaiko">
					<select id="itemsku[<?php echo esc_attr( $id ); ?>][skugptekiyo]" name="itemsku[<?php echo esc_attr( $id ); ?>][skugptekiyo]" class="skugptekiyo metaboxfield">
						<option value="0"<?php selected( $skugptekiyo, 0 ); ?>><?php esc_html_e( 'Not apply', 'usces' ); ?></option>
						<option value="1"<?php selected( $skugptekiyo, 1 ); ?>><?php esc_html_e( 'Apply', 'usces' ); ?></option>
					</select>
				</td><?php echo apply_filters( 'usces_filter_sku_meta_field2', '', $sku ); ?>
			</tr>
			<?php echo apply_filters( 'usces_filter_sku_meta_row', '', $sku ); ?>
			<tr>
				<td colspan="<?php echo esc_attr( $sku_colspan - 1 ); ?>" class="submittd">
					<div id="skusubmit-<?php echo esc_attr( $id ); ?>" class="submit">
						<input name="deleteitemsku[<?php echo esc_attr( $id ); ?>]" id="deleteitemsku[<?php echo esc_attr( $id ); ?>]" type="button" class="button" value="<?php esc_attr_e( 'Delete' ); ?>" onclick="if( jQuery('#post_ID').val() < 0 ) return; itemSku.post('deleteitemsku', <?php echo esc_attr( $id ); ?>);" />
						<input name="updateitemsku[<?php echo esc_attr( $id ); ?>]" id="updateitemsku[<?php echo esc_attr( $id ); ?>]" type="button" class="button" value="<?php esc_attr_e( 'Update' ); ?>" onclick="if( jQuery('#post_ID').val() < 0 ) return; itemSku.post('updateitemsku', <?php echo esc_attr( $id ); ?>);" />
						<input name="itemsku[<?php echo esc_attr( $id ); ?>][sort]" id="itemsku[<?php echo esc_attr( $id ); ?>][sort]" type="hidden" value="<?php echo esc_attr( $sort ); ?>" />
						<?php usces_sku_meta_row_reduced_taxrate( $sku ); ?>
					</div>
					<div id="itemsku_loading-<?php echo esc_attr( $id ); ?>" class="meta_submit_loading"></div>
				</td>
			</tr>
		</table>
	</td></tr>
	<?php
	$r = ob_get_contents();
	ob_end_clean();
	return $r;
}

/**
 * New SKU Form.
 * The form to add a new SKU in the SKU block on the product registration screen.
 *
 * @since  2.3.3
 */
function item_sku_meta_form() {
	$sku_colspan = apply_filters( 'usces_filter_sku_meta_colspan', '6' );
	?>
	<div id="sku_ajax-response"></div>
	<p><strong><?php esc_html_e( 'Add new SKU', 'usces' ); ?> : </strong></p>
	<table id="newsku">
		<thead>
		<tr>
			<th class="left"><?php esc_html_e( 'SKU code', 'usces' ); ?></th>
			<th><?php echo apply_filters( 'usces_filter_listprice_label', __( 'normal price', 'usces' ), null, null ); ?>(<?php usces_crcode(); ?>)</th>
			<th><?php echo apply_filters( 'usces_filter_sellingprice_label', __( 'Sale price', 'usces' ), null, null ); ?>(<?php usces_crcode(); ?>)</th>
			<th><?php esc_html_e( 'stock', 'usces' ); ?></th>
			<th><?php esc_html_e( 'stock status', 'usces' ); ?></th><?php echo apply_filters( 'usces_filter_sku_meta_title1', '' ); ?>
		</tr>
		<tr>
			<th><?php esc_html_e( 'SKU display name ', 'usces' ); ?></th>
			<th><?php esc_html_e( 'unit', 'usces' ); ?></th>
			<?php
			$advance_title = '<th colspan="2">&nbsp;</th>';
			echo apply_filters( 'usces_filter_sku_meta_form_advance_title', $advance_title );
			?>
			<th><?php esc_html_e( 'Apply business package', 'usces' ); ?></th><?php echo apply_filters( 'usces_filter_sku_meta_title2', '' ); ?>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td id="newskuleft" class="item-sku-key"><input type="text" id="newskuname" name="newskuname" class="newskuname metaboxfield" value="" /></td>
			<td class="item-sku-cprice"><input type="text" id="newskucprice" name="newskucprice" class="newskuprice metaboxfield" /></td>
			<td class="item-sku-price"><input type="text" id="newskuprice" name="newskuprice" class="newskuprice metaboxfield" /></td>
			<td class="item-sku-zaikonum"><input type="text" id="newskuzaikonum" name="newskuzaikonum" class="newskuzaikonum metaboxfield" /></td>
			<td class="item-sku-zaiko">
				<select id="newskuzaikoselect" name="newskuzaikoselect" class="newskuzaikoselect metaboxfield">
			<?php
			$zaikoselectarray = get_option( 'usces_zaiko_status' );
			foreach ( $zaikoselectarray as $v => $l ) {
				echo "\n" . '<option value="' . esc_attr( $v ) . '">' . esc_html( $l ) . '</option>';
			}
			?>
				</select>
			</td><?php echo apply_filters( 'usces_filter_newsku_meta_field1', '' ); ?>
		</tr>
		<tr>
			<td class="item-sku-key"><input type="text" id="newskudisp" name="newskudisp" class="newskudisp metaboxfield" /></td>
			<td class="item-sku-cprice"><input type="text" id="newskuunit" name="newskuunit" class="newskuunit metaboxfield" /></td>
			<?php
			$advance_field = '<td class="item-sku-price">&nbsp;</td><td class="item-sku-zaikonum">&nbsp;</td>';
			echo apply_filters( 'usces_filter_sku_meta_form_advance_field', $advance_field );
			?>
			<td class="item-sku-zaiko">
				<select id="newskugptekiyo" name="newskugptekiyo" class="newskugptekiyo metaboxfield">
					<option value="0"><?php esc_html_e( 'Not apply', 'usces' ); ?></option>
					<option value="1"><?php esc_html_e( 'Apply', 'usces' ); ?></option>
				</select>
			</td><?php echo apply_filters( 'usces_filter_newsku_meta_field2', '' ); ?>
		</tr>
		<?php echo apply_filters( 'usces_filter_newsku_meta_row', '' ); ?>
		<tr>
			<td colspan="<?php echo esc_attr( $sku_colspan - 1 ); ?>" class="submittd">
				<div id="newskusubmit" class="submit">
					<?php
					$add_itemsku_button = '<input name="add_itemsku" type="button" class="button" id="add_itemsku" tabindex="9" value="' . esc_html__( 'Add SKU', 'usces' ) . '" onclick="if( jQuery(\'#post_ID\').val() < 0 ) return; itemSku.post(\'additemsku\', 0);" />';
					echo apply_filters( 'usces_filter_newsku_meta_add_button', $add_itemsku_button );
					?>
					<?php usces_newsku_meta_row_reduced_taxrate(); ?>
				</div>
				<div id="newitemsku_loading" class="meta_submit_loading"></div>
			</td>
		</tr>
		</tbody>
	</table>
	<?php
}

/**
 * Get Post Meta using MetaID.
 *
 * @since  2.3.3
 * @param int $meta_id Meta ID.
 * @return MetaData|false
 */
function usces_get_post_meta_by_metaid( $meta_id ) {
	global $wpdb;

	$res = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM $wpdb->postmeta WHERE meta_id = %d",
			$meta_id
		),
		ARRAY_A
	);
	return $res;
}

/**
 * Get Post meta.
 * Get product metadata by specifying meta_key.
 *
 * @since  2.3.3
 * @param string  $post_id Post ID.
 * @param string  $key Meta key.
 * @param boolean $cache Switch of cache.
 * @return MetaData|false
 */
function usces_get_post_meta( $post_id, $key, $cache = true ) {
	global $wpdb;

	$cache_key = 'wel_post_meta_' . $post_id . '_' . $key;
	if ( true === $cache ) {
		$meta_data = wp_cache_get( $cache_key );
	} else {
		$meta_data = false;
	}
	if ( false === $meta_data || is_admin() || wp_doing_ajax() ) {
		$meta_data = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s",
				$post_id,
				$key
			),
			ARRAY_A
		);
		if ( null !== $meta_data ) {
			wp_cache_set( $cache_key, $meta_data );
		}
	}

	if ( null === $meta_data ) {
		return false;
	} else {
		return $meta_data;
	}
}

/**
 * Sort Options.
 * Ajax response when changing the order of optios in the SKU block of the product registration screen.
 *
 * @since  2.3.3
 * @param int $post_id Post ID.
 * @param int $metastr Meta ids.
 */
function usces_sort_item_opts( $post_id, $metastr ) {
	global $wpdb;

	$meta_ids = explode( ',', $metastr );
	$opts     = wel_get_opts( $post_id, 'meta_id', false );

	if ( ! empty( $meta_ids ) ) {

		$i = 0;
		foreach ( $meta_ids as $meta_id ) {

			$opt         = $opts[ $meta_id ];
			$opt['sort'] = $i;

			wel_update_opt_data_by_id( $meta_id, $post_id, $opt );

			$i++;
		}
	}
}

/**
 * Sort SKUs.
 * Ajax response when changing the order of SKUs in the SKU block of the product registration screen.
 *
 * @since  2.3.3
 * @param int $post_id Post ID.
 * @param int $metastr Meta ids.
 */
function usces_sort_item_skus( $post_id, $metastr ) {
	global $wpdb;

	$meta_ids = explode( ',', $metastr );
	$skus     = wel_get_skus( $post_id, 'meta_id', false );

	if ( ! empty( $meta_ids ) ) {

		$i = 0;
		foreach ( $meta_ids as $meta_id ) {

			$i = 0;
			foreach ( $meta_ids as $meta_id ) {

				$sku         = $skus[ $meta_id ];
				$sku['sort'] = $i;

				wel_update_sku_data_by_id( $meta_id, $post_id, $sku );

				$i++;
			}
		}
	}
}

/**
 * Get all options by post id.
 *
 * @since  2.3.3
 * @param int    $post_id Post ID.
 * @param string $keyflag Sort key.
 * @param bool   $cache Cache.
 */
function usces_get_opts( $post_id, $keyflag = 'sort', $cache = true ) {
	$opts = wel_get_opts( $post_id, $keyflag, $cache );
	return $opts;
}

/**
 * Add Item Option.
 *
 * @since  2.3.3
 * @param string  $post_id Post ID.
 * @param string  $new_value SKU data.
 * @param boolean $check Switch of check.
 * @return New meta id.
 */
function usces_add_opt( $post_id, $new_value, $check = true ) {
	global $wpdb, $usces;

	if ( $check ) {

		$opts = wel_get_opts( $post_id, 'meta_id', false );

		if ( ! empty( $opts ) && is_array( $opts ) ) {

			$meta_num = count( $opts );
			$unique   = true;
			$sortnull = true;
			$sort     = array();

			foreach ( (array) $opts as $opt ) {

				if ( $opt['name'] === $new_value['name'] ) {
					$unique = false;
				}
				if ( ! isset( $opt['sort'] ) ) {
					$sortnull = false;
				}
				$sort[] = $opt['sort'];
			}

			if ( ! $unique ) {
				return -1;
			}

			rsort( $sort );
			$next_number = reset( $sort ) + 1;
			$unique_sort = array_unique( $sort );
			if ( $meta_num !== count( $unique_sort ) || $meta_num !== $next_number || ! $sortnull ) {
				// To repair the sort data.
				$i = 0;
				foreach ( (array) $opts as $opt ) {
					$opt['sort'] = $i;
					wel_update_opt_data_by_id( $opt['meta_id'], $post_id, $opt );
					$i++;
				}
			}
		}

		$new_value['sort'] = ! empty( $meta_num ) ? $meta_num : 0;
	}

	$id = wel_add_opt_data( $post_id, $new_value );
	return $id;
}

/**
 * List item option
 *
 * @param array $opts Options.
 */
function list_item_option_meta( $opts ) {
	// Exit if no meta.
	if ( ! $opts ) {
		?>
		<table id="optlist-table" class="list" style="display: none;">
			<thead>
			<tr>
				<th class="hanldh">&emsp;</th>
				<th class="item-opt-key"><?php esc_html_e( 'option name', 'usces' ); ?></th>
				<th class="item-opt-value"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
			</tr>
			</thead>
			<tbody id="item-opt-list">
			<tr><td></td></tr>
			</tbody>
		</table>
		<?php
	} else {
		?>
		<table id="optlist-table" class="list">
			<thead>
			<tr>
				<th class="hanldh">&emsp;</th>
				<th class="item-opt-key"><?php esc_html_e( 'option name', 'usces' ); ?></th>
				<th class="item-opt-value"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
			</tr>
			</thead>
			<tbody id="item-opt-list">
		<?php
		foreach ( $opts as $opt ) {
			echo _list_item_option_meta_row( $opt );
		}
		?>
			</tbody>
		</table>
		<?php
	}
}

/**
 * Option meta row
 *
 * @param array $opt Options.
 */
function _list_item_option_meta_row( $opt ) {
	$r     = '';
	$style = '';
	$means = get_option( 'usces_item_option_select' );

	$name        = $opt['name'];
	$meansoption = '';
	foreach ( $means as $meankey => $meanvalue ) {
		$meansoption .= '<option value="' . esc_attr( $meankey ) . '"' . selected( $meankey, $opt['means'], false ) . '>' . esc_html( $meanvalue ) . "</option>\n";
	}
	$essential = ( 1 === (int) $opt['essential'] ) ? ' checked="checked"' : '';
	$value     = '';
	if ( is_array( $opt['value'] ) ) {
		foreach ( $opt['value'] as $k => $v ) {
			$value .= $v . "\n";
		}
	} else {
		$value = $opt['value'];
	}
	$value = trim( $value );
	$id    = (int) $opt['meta_id'];
	$sort  = (int) $opt['sort'];

	ob_start();
	?>
	<tr class="metastuffrow"><td colspan="3">
		<table id="itemopt-<?php echo esc_attr( $id ); ?>" class="metastufftable">
			<tr>
				<th class="handlb" rowspan="2">&emsp;</th>
				<td class="item-opt-key">
					<div><input name="itemopt[<?php echo esc_attr( $id ); ?>][name]" id="itemopt[<?php echo esc_attr( $id ); ?>][name]" class="metaboxfield" type="text" size="20" value="<?php echo esc_attr( $name ); ?>" /></div>
					<div class="optcheck">
						<select name="itemopt[<?php echo esc_attr( $id ); ?>][means]" id="itemopt[<?php echo esc_attr( $id ); ?>][means]"><?php wel_esc_script_e( $meansoption ); ?></select>
						<label for="itemopt[<?php echo esc_attr( $id ); ?>][essential]"><input name="itemopt[<?php echo esc_attr( $id ); ?>][essential]" id="itemopt[<?php echo esc_attr( $id ); ?>][essential]" type="checkbox" value="1"<?php echo esc_attr( $essential ); ?> class="metaboxcheckfield" /><?php esc_html_e( 'Required','usces' ); ?></label>
					</div>
				</td>
				<td class="item-opt-value">
					<textarea name="itemopt[<?php echo esc_attr( $id ); ?>][value]" id="itemopt[<?php echo esc_attr( $id ); ?>][value]" class="metaboxfield"><?php echo esc_html( $value ); ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="submittd">
					<div id="itemoptsubmit-<?php echo esc_attr( $id ); ?>" class="submit">
						<input name="deleteitemopt[<?php echo esc_attr( $id ); ?>]" id="deleteitemopt[<?php echo esc_attr( $id ); ?>]" type="button" class="button" value="<?php esc_attr_e( 'Delete' ); ?>" onclick="if( jQuery('#post_ID').val() < 0 ) return; itemOpt.post('deleteitemopt', <?php echo esc_attr( $id ); ?>);" />
						<input name="updateitemopt[<?php echo esc_attr( $id ); ?>]" id="updateitemopt[<?php echo esc_attr( $id ); ?>]" type="button" class="button" value="<?php esc_attr_e( 'Update' ); ?>" onclick="if( jQuery('#post_ID').val() < 0 ) return; itemOpt.post('updateitemopt', <?php echo esc_attr( $id ); ?>);" />
						<input name="itemopt[<?php echo esc_attr( $id ); ?>][sort]" id="itemopt[<?php echo esc_attr( $id ); ?>][sort]" type="hidden" value="<?php echo esc_attr( $sort ); ?>" />
					</div>
					<div id="itemopt_loading-<?php echo esc_attr( $id ); ?>" class="meta_submit_loading"></div>
				</td>
			</tr>
		</table>
	</td></tr>
	<?php
	$r = ob_get_contents();
	ob_end_clean();
	return $r;
}

/**
 * Common option meta form
 */
function common_option_meta_form() {
	$means       = get_option( 'usces_item_option_select' );
	$meansoption = '';
	foreach ( $means as $meankey => $meanvalue ) {
		$meansoption .= '<option value="' . esc_attr( $meankey ) . '">' . esc_html( $meanvalue ) . "</option>\n";
	}
	?>
	<div id="itemopt_ajax-response"></div>
	<p><strong><?php esc_html_e( 'Add a new option', 'usces' ); ?> : </strong></p>
	<table id="newmeta2">
		<thead>
		<tr>
			<th class="left"><label for="metakeyselect"><?php esc_html_e( 'option name', 'usces' ); ?></label></th>
			<th><label for="metavalue"><?php esc_html_e( 'selected amount', 'usces' ); ?></label></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="item-opt-key">
				<input type="text" id="newoptname" name="newoptname" class="metaboxfield" tabindex="7" value="" />
				<div class="optcheck">
					<select name="newoptmeans" id="newoptmeans" class="metaboxfield long"><?php wel_esc_script_e( $meansoption ); ?></select>
					<label for="newoptessential"><input name="newoptessential" type="checkbox" id="newoptessential" class="metaboxcheckfield" /><?php esc_html_e( 'Required', 'usces' ); ?></label>
				</div>
			</td>
			<td class="item-opt-value"><textarea id="newoptvalue" name="newoptvalue" class="metaboxfield"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="submittd">
				<div id="newcomoptsubmit" class="submit">
					<input name="add_comopt" type="button" class="button" id="add_comopt" tabindex="9" value="<?php esc_attr_e( 'Add common options', 'usces' ); ?>" onclick="itemOpt.post('addcommonopt', 0);" />
				</div>
				<div id="newcomopt_loading" class="meta_submit_loading"></div>
			</td>
		</tr>
		</tbody>
	</table>
	<?php
}

/**
 * Item option meta form
 */
function item_option_meta_form() {
	$limit       = (int) apply_filters( 'postmeta_form_limit', 30 );
	$cart_number = (int) get_option( 'usces_cart_number' );
	$opts        = usces_get_opts( $cart_number );
	$means       = get_option( 'usces_item_option_select' );
	$meansoption = '';
	foreach ( $means as $meankey => $meanvalue ) {
		$meansoption .= '<option value="' . esc_attr( $meankey ) . '">' . esc_html( $meanvalue ) . "</option>\n";
	}
	?>
	<div id="itemopt_ajax-response"></div>
	<p><strong><?php esc_html_e( 'Applicable product options', 'usces' ); ?> : </strong></p>
	<table id="newmeta2">
		<thead>
		<tr>
			<th class="item-opt-key"><label for="metakeyselect"><?php esc_html_e( 'option name', 'usces' ); ?></label></th>
			<th class="item-opt-value"><label for="metavalue"><?php esc_html_e( 'selected amount', 'usces' ); ?></label></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class='item-opt-key'>
			<?php if ( ! empty( $opts ) ) { ?>
				<select id="optkeyselect" name="optkeyselect" class="optkeyselect metaboxfield" tabindex="7" onchange="if( jQuery('#post_ID').val() < 0 ) return; itemOpt.post('keyselect', this.value);">
					<option value="#NONE#"><?php esc_html_e( '-- Select --', 'usces' ); ?></option>
				<?php foreach ( $opts as $opt ) { ?>
					<option value="<?php echo esc_attr( $opt['meta_id'] ); ?>"><?php echo esc_attr( $opt['name'] ); ?></option>
				<?php } ?>
				</select>
				<input type="hidden" id="newoptname" name="newoptname" class="metaboxfield" />
				<div class="optcheck">
					<select name="newoptmeans" id="newoptmeans"><?php wel_esc_script_e( $meansoption ); ?></select>
					<label for="newoptessential"><input name="newoptessential" type="checkbox" id="newoptessential" class="metaboxcheckfield" /><?php esc_html_e( 'Required', 'usces' ); ?></label>
				</div>
			<?php } else { ?>
				<p><?php esc_html_e( 'Please create a common option.', 'usces' ); ?></p>
			<?php } ?>
			</td>
			<td class="item-opt-value"><textarea id="newoptvalue" name="newoptvalue" class="metaboxfield"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="submittd">
			<?php if ( is_array( $opts ) ) { ?>
			<div id="newitemoptsubmit" class="submit">
				<input name="add_itemopt" type="button" class='button' id="add_itemopt" tabindex="9" value="<?php esc_attr_e( 'Apply an option', 'usces' ); ?>" onclick="if( jQuery('#post_ID').val() < 0 ) return; itemOpt.post('additemopt', 0);" />
			</div>
			<div id="newitemopt_loading" class="meta_submit_loading"></div>
			<?php } ?>
			</td>
		</tr>
		</tbody>
	</table>
	<?php
}

/**
 * Add item option meta
 *
 * @param int $post_ID Post ID.
 * @return mixed
 */
function add_item_option_meta( $post_ID ) {
	global $usces;

	$post_id   = (int) $post_ID;
	$value     = array();
	$opts      = array();
	$protected = array(
		'#NONE#',
		'_wp_attached_file',
		'_wp_attachment_metadata',
		'_wp_old_slug',
		'_wp_page_template',
	);

	$args            = array(
		'newoptcode'      => FILTER_SANITIZE_SPECIAL_CHARS,
		'newoptname'      => FILTER_SANITIZE_SPECIAL_CHARS,
		'newoptmeans'     => array(
			'filter'  => FILTER_VALIDATE_INT,
			'flags'   => FILTER_REQUIRE_SCALAR,
			'options' => array( 'defalut' => 0 ),
		),
		'newoptessential' => array(
			'filter'  => FILTER_VALIDATE_INT,
			'flags'   => FILTER_REQUIRE_SCALAR,
			'options' => array( 'defalut' => 0 ),
		),
		'newoptvalue'     => FILTER_DEFAULT,
	);
	$inputs          = filter_input_array( INPUT_POST, $args );
	$newoptcode      = $inputs['newoptcode'];
	$newoptname      = $inputs['newoptname'];
	$newoptmeans     = $inputs['newoptmeans'];
	$newoptessential = $inputs['newoptessential'];
	$newoptvalue     = $inputs['newoptvalue'];

	if ( ( $newoptmeans >= 2 || WCUtils::is_zero( $newoptvalue ) || ! empty ( $newoptvalue ) ) && ! empty ( $newoptname ) ) {

		if ( $newoptname ) {
			$metakey = $newoptname; // default.
		}

		if ( in_array( $metakey, $protected ) ) {
			return false;
		}

		$WelItem = new Welcart\ItemData( $post_id, false );
		$format  = $WelItem->get_opt_format();

		$format['post_id']   = $post_id;
		$format['code']      = $newoptcode;
		$format['name']      = str_replace( "\\", '', $newoptname );
		$format['means']     = $newoptmeans;
		$format['essential'] = $newoptessential;
		$format['value']     = str_replace( "\\", '', $newoptvalue );

		$value = $usces->stripslashes_deep_post( $format );

		$id = usces_add_opt( $post_id, $value );

		return $id;
	} else {
		return false;
	}
}

/**
 * Update Item Option by ajax.
 * Ajax response when Option information is updated in the Option block of the product registration screen.
 *
 * @since  2.3.3
 * @param string $post_ID Post ID.
 * @return Query result.
 */
function up_item_option_meta( $post_ID ) {
	global $usces;

	$post_id = (int) $post_ID;
	$value   = array();

	$args = array(
		'optmetaid'    => FILTER_VALIDATE_INT,
		'optcode'      => FILTER_DEFAULT,
		'optname'      => FILTER_DEFAULT,
		'optmeans'     => FILTER_VALIDATE_INT,
		'optessential' => FILTER_VALIDATE_INT,
		'sort'         => FILTER_VALIDATE_INT,
		'optvalue'     => FILTER_DEFAULT,
	);

	$inputs = filter_input_array( INPUT_POST, $args );

	$optmetaid    = ( null !== $inputs['optmetaid'] ) ? (int) $inputs['optmetaid'] : '';
	$optcode      = ( null !== $inputs['optcode'] ) ? $inputs['optcode'] : '';
	$optname      = ( null !== $inputs['optname'] ) ? $inputs['optname'] : '';
	$optmeans     = ( null !== $inputs['optmeans'] ) ? (int) $inputs['optmeans'] : 0;
	$optessential = ( null !== $inputs['optessential'] ) ? $inputs['optessential'] : 0;
	$optsort      = ( null !== $inputs['sort'] ) ? $inputs['sort'] : 0;
	$optvalue     = ( null !== $inputs['optvalue'] ) ? trim( $inputs['optvalue'] ) : '';

	$opts = wel_get_opts( $post_id, 'meta_id', false );
	$opt  = $opts[ $optmetaid ];

	$opt['meta_id']   = $optmetaid;
	$opt['code']      = $optcode;
	$opt['name']      = str_replace( "\\", '', $optname );
	$opt['means']     = $optmeans;
	$opt['essential'] = $optessential;
	$opt['value']     = str_replace( "\\", '', $optvalue );
	$opt['sort']      = $optsort;

	$value = $usces->stripslashes_deep_post( $opt );

	$res = wel_update_opt_data_by_id( $optmetaid, $post_id, $value );

	return $res;
}

/**
 * Delete item option meta
 *
 * @param int $post_id Post ID.
 */
function del_item_option_meta( $post_id ) {

	$optmetaid = filter_input( INPUT_POST, 'optmetaid', FILTER_VALIDATE_INT );
	$meta_id   = ( null !== $optmetaid ) ? $optmetaid : '';

	wel_delete_opt_data_by_id( $meta_id, $post_id );

	$opts = wel_get_opts( $post_id, 'sort', false );
	if ( ! empty( $opts ) ) {
		$i = 0;
		foreach ( $opts as $opt ) {
			$opt['sort'] = $i;
			$meta_id     = $opt['meta_id'];
			wel_update_opt_data_by_id( $meta_id, $post_id, $opt );
			$i++;
		}
	} else {
		return;
	}
}

/**
 * Select common option
 *
 * @param int $post_id Post ID.
 * @return array
 */
function select_common_option( $post_id ) {

	$meta_id = filter_input( INPUT_POST, 'meta_id', FILTER_VALIDATE_INT );
	if ( ! $meta_id ) {
		return array();
	}

	$opts = wel_get_opts( $post_id, 'meta_id', false );
	$opt  = $opts[ $meta_id ];
	$res  = array(
		'means'     => $opt['means'],
		'essential' => $opt['essential'],
		'value'     => $opt['value'],
	);
	return $res;
}

/**
 * Order item2cart ajax
 */
function order_item2cart_ajax() {
	global $usces;

	if ( 'order_item2cart_ajax' !== $_POST['action'] ) {
		die(0);
	}

	$_POST = $usces->stripslashes_deep_post( $_POST );
	$logger = Logger::start( $_POST['order_id'], 'orderedit', 'update' );
	$order_id = usces_add_ordercartdata();
	if ( ! $order_id ) {
		die( 0 );
	}

	$cart   = usces_get_ordercartdata( $order_id );
	$return = usces_get_ordercart_row( $order_id, $cart );
	$logger->flush();
	die( $return );
}

/**
 * Order item ajax
 */
function order_item_ajax() {
	global $usces;

	if ( 'order_item_ajax' !== $_POST['action'] ) {
		die(0);
	}

	$res   = false;
	$_POST = $usces->stripslashes_deep_post( $_POST );

	switch ( $_POST['mode'] ) {
		case 'completionMail':
		case 'orderConfirmMail':
		case 'changeConfirmMail':
		case 'receiptConfirmMail':
		case 'mitumoriConfirmMail':
		case 'cancelConfirmMail':
		case 'otherConfirmMail':
			$res = usces_order_confirm_message( $_POST['order_id'] );
			break;
		case 'sendmail':
			$res = usces_ajax_send_mail();
			break;
		case 'get_order_item':
			$res = get_order_item( $_POST['itemcode'] );
			break;
		case 'get_item_select_option':
			$res = usces_get_item_select_option( $_POST['cat_id'] );
			break;
		case 'ordercheckpost':
			$res = usces_update_ordercheck();
			break;
		case 'getmember':
			$res = usces_get_member_neworder();
			break;
		case 'recalculation':
			$change_taxrate = ( isset( $_POST['change_taxrate'] ) ) ? $_POST['change_taxrate'] : '';
			$res            = usces_order_recalculation( $_POST['order_id'], $_POST['mem_id'], $_POST['post_ids'], $_POST['prices'], $_POST['quants'], $_POST['cart_ids'], $_POST['upoint'], $_POST['shipping_charge'], $_POST['cod_fee'], $_POST['discount'], $change_taxrate );
			break;
		case 'recalculation_reduced':
			$change_taxrate = ( isset( $_POST['change_taxrate'] ) ) ? $_POST['change_taxrate'] : '';
			$res            = usces_order_recalculation_reduced( $_POST['order_id'], $_POST['mem_id'], $_POST['post_ids'], $_POST['prices'], $_POST['quants'], $_POST['cart_ids'], $_POST['upoint'], $_POST['shipping_charge'], $_POST['cod_fee'], $_POST['discount_standard'], $_POST['discount_reduced'], $change_taxrate );
			break;
		case 'get_settlement_log':
			$res = usces_get_settlement_log();
			break;
		case 'get_settlement_log_detail':
			$res = usces_get_settlement_log_detail( $_POST['log_key'] );
			break;
		case 'search_settlement_log':
			$res = usces_get_settlement_log( $_POST['log_key'] );
			break;
		case 'delete_settlement_log':
			usces_delete_settlement_log( $_POST['log_key'] );
			$res = usces_get_settlement_log();
			break;
		case 'delete_settlement_log_all':
			usces_delete_settlement_log();
			$res = usces_get_settlement_log();
			break;
		case 'revival_order_data':
			$res = usces_revival_order_data( $_POST['log_key'], $_POST['register_date'] );
			break;
		case 'get_settlement_error_log':
			$res = usces_get_settlement_error_log();
			break;
		case 'get_settlement_error_log_detail':
			$res = usces_get_settlement_error_log_detail( $_POST['log_id'] );
			break;
		case 'delete_settlement_error_log':
			usces_delete_settlement_error_log( $_POST['log_id'] );
			$res = usces_get_settlement_error_log();
			break;
		case 'delete_settlement_error_log_all':
			usces_delete_settlement_error_log();
			$res = usces_get_settlement_error_log();
			break;
		case 'reset_settlement_notice':
			$res = usces_reset_settlement_notice();
			break;
	}

	$res = wel_esc_script( apply_filters( 'usces_filter_order_item_ajax', $res ) );

	if ( false === $res ) {
		die(0);
	}

	die( $res );
}

/**
 * Item select field
 *
 * @param int $cat_id Category ID.
 * @return string
 */
function usces_get_item_select_option( $cat_id ) {
	global $usces;

	$number = apply_filters( 'usces_filter_item_select_numberposts', 50, $cat_id );
	$args   = array(
		'category'    => $cat_id,
		'numberposts' => $number,
		'post_status' => array( 'publish', 'private' ),
	);
	$args   = apply_filters( 'usces_filter_item_select_queryargs', $args, $cat_id );

	$items  = get_posts( $args );
	$option = '<option value="-1">' . __( 'Please select an item', 'usces' ) . '</option>' . "\n";
	foreach ( (array) $items as $item ) {
		$product   = wel_get_product( $item->ID );
		$item_name = $product['itemName'];
		$item_code = $product['itemCode'];
		$option   .= '<option value="' . urlencode( $item_code ) . '">' . $item_name . '(' . $item_code . ')</option>' . "\n";
	}
	return $option;
}

/**
 * Order Item html
 */
function usces_get_member_neworder() {
	global $wpdb;

	$wpdb->show_errors();
	$res          = '';
	$member_table = usces_get_tablename( 'usces_member' );
	$query        = $wpdb->prepare( "SELECT * FROM $member_table WHERE mem_email = %s", trim( $_POST['email'] ) );
	$value        = $wpdb->get_row( $query, ARRAY_A );

	if ( ! $value ) {
		$response = array( 'status_code' => 'none' );
		wp_send_json( $response );
	}

	$response = array(
		'status_code'        => 'ok',
		'member_id'          => $value['ID'],
		'customer[name1]'    => $value['mem_name1'],
		'customer[name2]'    => $value['mem_name2'],
		'customer[name3]'    => $value['mem_name3'],
		'customer[name4]'    => $value['mem_name4'],
		'customer[zipcode]'  => $value['mem_zip'],
		'customer[pref]'     => $value['mem_pref'],
		'customer[address1]' => $value['mem_address1'],
		'customer[address2]' => $value['mem_address2'],
		'customer[address3]' => $value['mem_address3'],
		'customer[tel]'      => $value['mem_tel'],
		'customer[fax]'      => $value['mem_fax'],
		'delivery[name1]'    => $value['mem_name1'],
		'delivery[name2]'    => $value['mem_name2'],
		'delivery[name3]'    => $value['mem_name3'],
		'delivery[name4]'    => $value['mem_name4'],
		'delivery[zipcode]'  => $value['mem_zip'],
		'delivery[pref]'     => $value['mem_pref'],
		'delivery[address1]' => $value['mem_address1'],
		'delivery[address2]' => $value['mem_address2'],
		'delivery[address3]' => $value['mem_address3'],
		'delivery[tel]'      => $value['mem_tel'],
		'delivery[fax]'      => $value['mem_fax'],
	);

	$member_metetable = usces_get_tablename( 'usces_member_meta' );
	$query            = $wpdb->prepare( "SELECT * FROM $member_metetable WHERE meta_key LIKE %s AND member_id = %d", 'csmb_%', $value['ID'] );
	$customs          = $wpdb->get_results( $query, ARRAY_A );
	if ( ! empty( $customs ) ) {
		foreach ( $customs as $cusv ) {
			$response[ 'custom_customer[' . substr( $cusv['meta_key'], 5 ) . ']'] = $cusv['meta_value'];
			$response[ 'custom_delivery[' . substr( $cusv['meta_key'], 5 ) . ']'] = $cusv['meta_value'];
		}
	}

	$response = apply_filters( 'usces_filter_get_member_neworder', $response );

	wp_send_json( $response );
}

/**
 * Add to order cart
 *
 * @return mixed
 */
function usces_add_ordercartdata() {
	global $usces, $wpdb;

	$res = 0;

	$order_id = (int) $_POST['order_id'];
	if ( ! $order_id ) {
		return $res;
	}

	$post_id  = (int) $_POST['post_id'];
	$sku_code = urldecode( $_POST['sku'] );
	$quantity = 1;

	$current_cart = usces_get_ordercartdata( $order_id );
	$temp_arr     = array();
	foreach ( $current_cart as $cv ) {
		$temp_arr[] = $cv['row_index'];
	}
	$row_index = ( 0 < count( $temp_arr ) ) ? max( $temp_arr ) + 1 : 0;

	$cart_table      = $wpdb->prefix . 'usces_ordercart';
	$cart_meta_table = $wpdb->prefix . 'usces_ordercart_meta';

	$product   = wel_get_product( $post_id );
	$item_name = $product['itemName'];
	$item_code = $product['itemCode'];
	$skus      = $usces->get_skus( $post_id, 'code' );
	$sku       = apply_filters( 'usces_filter_add_ordercart_sku', $skus[ $sku_code ], $_POST );
	$tax       = 0;

	$query = $wpdb->prepare(
		"INSERT INTO $cart_table 
		(
		order_id, row_index, post_id, item_code, item_name, 
		sku_code, sku_name, cprice, price, quantity, 
		unit, tax, destination_id, cart_serial 
		) VALUES (
		%d, %d, %d, %s, %s, 
		%s, %s, %f, %f, %d, 
		%s, %d, %d, %s 
		)",
		$order_id, $row_index, $post_id, $item_code, $item_name,
		$sku_code, $sku['name'], $sku['cprice'], $sku['price'], $quantity,
		$sku['unit'], $tax, NULL, NULL
	);
	$res     = $wpdb->query( $query );
	$cart_id = NULL;
	if ( false !== $res ) {
		$cart_id = $wpdb->insert_id;

		$item_opts = usces_get_opts( $post_id, 'name' );
		foreach ( $item_opts as $key => $iopts ) {
			if ( 3 === (int) $iopts['means'] || 4 === (int) $iopts['means'] ) {
				// POSTが無ければNULLで追加.
				if ( ! isset( $_POST['itemOption'][ $key ] ) ) {
					$query = $wpdb->prepare(
						"INSERT INTO $cart_meta_table 
						( cart_id, meta_type, meta_key, meta_value ) VALUES ( %d, %s, %s, %s )",
						$cart_id, 'option', $iopts['name'], NULL
					);
					$wpdb->query( $query );
				}
			}
		}

		if ( isset( $_POST['itemOption'] ) ) {
			foreach ( (array) $_POST['itemOption'] as $okey => $ovalue ) {
				$means = $item_opts[ $okey ]['means'];
				if ( is_array( $ovalue ) ) {
					$temp = array();
					if ( 4 === (int) $means ) {
						foreach ( $ovalue as $k => $v ) {
							$temp[] = $v;
						}
					} else {
						foreach ( $ovalue as $k => $v ) {
							$temp[ urlencode( $k ) ] = $v;
						}
					}
					$ovalue = serialize( $temp );
				} else {
					$ovalue = $ovalue;
				}
				$aquery = $wpdb->prepare(
					"INSERT INTO $cart_meta_table 
					( cart_id, meta_type, meta_key, meta_value ) VALUES (%d, %s, %s, %s)",
					$cart_id, 'option', $okey, $ovalue
				);
				$wpdb->query( $aquery );
			}
		}

		if ( $usces->is_reduced_taxrate() ) {
			if ( isset( $sku['taxrate'] ) && 'reduced' === $sku['taxrate'] ) {
				$tkey   = 'reduced';
				$tvalue = $usces->options['tax_rate_reduced'];
			} else {
				$tkey   = 'standard';
				$tvalue = $usces->options['tax_rate'];
			}
			$tquery = $wpdb->prepare(
				"INSERT INTO $cart_meta_table 
				( cart_id, meta_type, meta_key, meta_value ) VALUES ( %d, 'taxrate', %s, %s )",
				$cart_id, $tkey, $tvalue
			);
			$wpdb->query( $tquery );
		}
	}

	$res = apply_filters( 'usces_filter_add_ordercart', $res, $order_id, $cart_id );

	if ( $res ) {
		return $order_id;
	} else {
		return $res;
	}
}

/**
 * Get order item
 *
 * @param string $item_code Item code.
 * @return string
 */
function get_order_item( $item_code ) {
	global $usces, $post;

	$post_id = wel_get_id_by_item_code( $item_code );
	if ( null === $post_id ) {
		return false;
	}
	$product = wel_get_product( $post_id );
	$post    = $product['_pst'];
	$res     = apply_filters( 'usce_action_ajax_get_order_item', false, $post );
	if ( false !== $res ) {
		return $res;
	}

	$pict_id   = wel_get_main_pict_id_by_code( $item_code );
	$pict_link = wp_get_attachment_image( $pict_id, array( 150, 150 ), true );
	preg_match( "/^\<a .+\>(\<img .+\/\>)\<\/a\>$/", $pict_link, $match );
	$pict      = isset( $match[1] ) ? $match[1] : '';
	$skus      = $product['_sku'];
	$optkeys   = $usces->get_itemOptionKey( $post_id );
	$item_name = esc_html( $product['itemName'] );

	$r  = '';
	$r .= $pict_link . "\n";
	$r .= '<h3>' . $item_name . "</h3>\n";
	$r .= "<div class=\"skuform\">\n";

	$r .= "<table class=\"skumulti\">\n";
	$r .= "<thead>\n";
	$r .= "<tr>\n";
	$r .= '<th>' . __( 'SKU code', 'usces' ) . "</th>\n";
	$r .= '<th>' . __( 'SKU display name ', 'usces' ) . "</th>\n";

	$usces_listprice = __( 'List price', 'usces' ) . usces_guid_tax( 'return' );
	$r              .= '<th>' . apply_filters( 'usces_filter_listprice_label', $usces_listprice, __( 'List price', 'usces' ), usces_guid_tax( 'return' ) ) . "</th>\n";

	$usces_sellingprice = __( 'Sale price', 'usces' ) . usces_guid_tax( 'return' );
	$r                 .= '<th>' . apply_filters( 'usces_filter_sellingprice_label', $usces_sellingprice, __( 'Sale price', 'usces' ), usces_guid_tax( 'return' ) ) . "</th>\n";

	$r .= '<th>' . __( 'stock status', 'usces' ) . "</th>\n";
	$r .= '<th>' . __( 'stock', 'usces' ) . "</th>\n";
	$r .= '<th>' . __( 'unit', 'usces' ) . "</th>\n";
	$r .= "<th>&nbsp;</th>\n";
	$r .= "</tr>\n";
	$r .= "</thead>\n";
	$r .= "<tbody>\n";
	foreach ( $skus as $sku ) :
		$key      = urlencode( $sku['code'] );
		$cprice   = esc_attr( $sku['cprice'] );
		$price    = esc_attr( $sku['price'] );
		$zaiko    = esc_attr( $usces->zaiko_status[ $sku['stock'] ] );
		$zaikonum = esc_attr( $sku['stocknum'] );
		$disp     = esc_attr( $sku['name'] );
		$unit     = esc_attr( $sku['unit'] );
		$gptekiyo = $sku['gp'];
		$sort     = (int) $sku['sort'];

		$r .= "<tr>\n";
		$r .= '<td rowspan="2">' . esc_js( $sku['code'] ) . "</td>\n";
		$r .= '<td>' . $disp . "</td>\n";
		$r .= '<td><span class="cprice">' . ( ( ! empty( $cprice ) ) ? usces_crform( $cprice, true, false, 'return' ) : '' ) . "</span></td>\n";
		$r .= '<td><span class="price">' . usces_crform( $price, true, false, 'return' ) . "</span></td>\n";
		$r .= '<td>' . $zaiko . "</td>\n";
		$r .= '<td>' . $zaikonum . "</td>\n";
		$r .= '<td>' . $unit . "</td>\n";
		$r .= "<td>\n";
		$r .= "<input name=\"itemNEWName[{$post_id}][{$key}]\" type=\"hidden\" id=\"itemNEWName[{$post_id}][{$key}]\" value=\"{$item_name}\" />\n";
		$r .= "<input name=\"itemNEWCode[{$post_id}][{$key}]\" type=\"hidden\" id=\"itemNEWCode[{$post_id}][{$key}]\" value=\"{$item_code}\" />\n";
		$r .= "<input name=\"skuNEWName[{$post_id}][{$key}]\" type=\"hidden\" id=\"skuNEWName[{$post_id}][{$key}]\" value=\"{$key}\" />\n";
		$r .= "<input name=\"skuNEWCprice[{$post_id}][{$key}]\" type=\"hidden\" id=\"skuNEWCprice[{$post_id}][{$key}]\" value=\"{$cprice}\" />\n";
		$r .= "<input name=\"skuNEWDisp[{$post_id}][{$key}]\" type=\"hidden\" id=\"skuNEWDisp[{$post_id}][{$key}]\" value=\"{$disp}\" />\n";
		$r .= "<input name=\"zaikoNEWnum[{$post_id}][{$key}]\" type=\"hidden\" id=\"zaikoNEWnum[{$post_id}][{$key}]\" value=\"{$zaikonum}\" />\n";
		$r .= "<input name=\"zaiNEWko[{$post_id}][{$key}]\" type=\"hidden\" id=\"zaiNEWko[{$post_id}][{$key}]\" value=\"{$zaiko}\" />\n";
		$r .= "<input name=\"uniNEWt[{$post_id}][{$key}]\" type=\"hidden\" id=\"uniNEWt[{$post_id}][{$key}]\" value=\"{$unit}\" />\n";
		$r .= "<input name=\"gpNEWtekiyo[{$post_id}][{$key}]\" type=\"hidden\" id=\"gpNEWtekiyo[{$post_id}][{$key}]\" value=\"{$gptekiyo}\" />\n";
		$r .= "<input name=\"skuNEWPrice[{$post_id}][{$key}]\" type=\"hidden\" id=\"skuNEWPrice[{$post_id}][{$key}]\" value=\"{$price}\" />\n";
		$r .= "<input name=\"inNEWCart[{$post_id}][{$key}]\" type=\"button\" id=\"inNEWCart[{$post_id}][{$key}]\" class=\"skubutton button\" value=\"" . __( 'Add to Whish List', 'usces' ) . "\" onclick=\"orderItem.add2cart('{$post_id}', '{$key}');\" />";
		$r .= "</td>\n";
		$r .= "</tr>\n";
		$r .= "<tr>\n";
		if ( $optkeys ) :
			$r .= "<td colspan=\"7\">\n";
			foreach ( $optkeys as $optkey => $optvalue ) :
				$r .= "<div>\n";

				$name         = esc_attr( $optvalue );
				$optcode      = urlencode( $name );
				$opts         = usces_get_opts( $post_id, 'name' );
				$opt          = $opts[ $optvalue ];
				$opt['value'] = usces_change_line_break( $opt['value'] );
				$means        = (int) $opt['means'];
				$essential    = (int) $opt['essential'];

				$r .= "\n<label for=\"itemNEWOption[{$post_id}][{$key}][{$optcode}]\" class=\"iopt_label\">{$name}</label>\n";
				switch ( $means ) {
					case 0: // Single-select.
					case 1: // Multi-select.
						$selects        = explode( "\n", $opt['value'] );
						$multiple       = ( 0 === $means ) ? '' : ' multiple';
						$multiple_array = ( 0 === $means ) ? '' : '_multiple';
						$r             .= "\n<select name=\"itemNEWOption[{$post_id}][{$key}][{$optcode}]\" id=\"itemNEWOption[{$post_id}][{$key}][{$optcode}]\" class=\"iopt_select{$multiple_array}\"{$multiple}>\n";
						if ( 1 === $essential ) {
							$r .= "\t<option value=\"#NONE#\" selected=\"selected\">" . __( 'Choose', 'usces' ) . "</option>\n";
						}
						$s = 0;
						foreach ( $selects as $v ) {
							$v = trim( $v );
							if ( 0 === $s && 0 === $essential ) {
								$selected = ' selected="selected"';
							} else {
								$selected = '';
							}
							$r .= "\t<option value=\"{$v}\"{$selected}>{$v}</option>\n";
							$s++;
						}
						$r .= "</select>\n";
						break;
					case 2: // Text.
						$r .= "\n<input name=\"itemNEWOption[{$post_id}][{$key}][{$optcode}]\" type=\"text\" id=\"itemNEWOption[{$post_id}][{$key}][{$optcode}]\" class=\"iopt_text\" onKeyDown=\"if (event.keyCode == 13) {return false;}\" value=\"\" />\n";
						break;
					case 3: // Radio-button.
						$selects = explode( "\n", $opt['value'] );
						$i       = 0;
						foreach ( $selects as $v ) {
							$r .= '<label for="itemNEWOption[' . $post_id . '][' . $key . '][' . $optcode . ']' . $i . '" class="iopt_radio_label"><input name="itemNEWOption[' . $post_id . '][' . $key . '][' . $optcode . ']" type="radio" id="itemNEWOption[' . $post_id . '][' . $key . '][' . $optcode . ']' . $i . '" class="iopt_radio" value="' . urlencode($v) . '">' . esc_html($v) . "</label>\n";
							$i++;
						}
						break;
					case 4: // Check-box.
						$selects = explode( "\n", $opt['value'] );
						$i       = 0;
						foreach ( $selects as $v ) {
							$r .= '<label for="itemNEWOption[' . $post_id . '][' . $key . '][' . $optcode . ']' . $i . '" class="iopt_checkbox_label"><input name="itemNEWOption[' . $post_id . '][' . $key . '][' . $optcode . ']" type="checkbox" id="itemNEWOption[' . $post_id . '][' . $key . '][' . $optcode . ']' . $i . '" class="iopt_checkbox" value="' . urlencode($v) . '">' . esc_html($v) . "</label>\n";
							$i++;
						}
						break;
					case 5: // Text-area.
						$r .= "\n<textarea name=\"itemNEWOption[{$post_id}][{$key}][{$optcode}]\" id=\"itemNEWOption[{$post_id}][{$key}][{$optcode}]\" class=\"iopt_textarea\"></textarea>\n";
						break;
				}
				$r .= "<input name=\"optNEWCode[{$post_id}][{$key}][{$optcode}]\" type=\"hidden\" id=\"optNEWCode[{$post_id}][{$key}][{$optcode}]\" value=\"{$optcode}\" />\n";
				$r .= "<input name=\"optNEWEssential[{$post_id}][{$key}][{$optcode}]\" type=\"hidden\" id=\"optNEWEssential[{$post_id}][{$key}][{$optcode}]\" value=\"{$essential}\" />\n";
				$r .= "</div>\n";
			endforeach;
			$r .= "</td>\n";
		endif;
		$r .= "</tr>\n";
	endforeach;
	$r .= "</tbody>\n";
	$r .= "</table>\n";

	$r .= "</div>\n";

	$r = apply_filters( 'usces_filter_get_order_item', $r, $item_code, $post_id );

	return $r;
}

/**
 * Item option ajax
 */
function item_option_ajax() {

	$args = array(
		'ID'     => FILTER_VALIDATE_INT,
		'action' => FILTER_DEFAULT,
		'update' => FILTER_DEFAULT,
		'delete' => FILTER_DEFAULT,
		'meta'   => FILTER_DEFAULT,
		'select' => FILTER_DEFAULT,
		'sort'   => FILTER_VALIDATE_INT,
	);

	$inputs = filter_input_array( INPUT_POST, $args );

	check_admin_referer( 'admin_setup', 'wc_nonce' );
	if ( 4 > usces_get_admin_user_level() ) {
		die('user_level');
	}

	if ( 'item_option_ajax' !== $inputs['action'] || null === $inputs['ID'] ) {
		die(0);
	}

	$post_id = $inputs['ID'];

	if ( null !== $inputs['update'] ) {

		$id = up_item_option_meta( $post_id );

	} elseif ( null !== $inputs['delete'] ) {

		$id = del_item_option_meta( $post_id );

	} elseif ( null !== $inputs['select'] ) {

		$res = select_common_option( $post_id );
		wp_send_json( $res );

	} elseif ( null !== $inputs['sort'] ) {

		$id = usces_sort_item_opts( $post_id, $inputs['meta'] );

	} else {

		$id = add_item_option_meta( $post_id );

	}

	$opts = usces_get_opts( $post_id, 'sort', false );

	$r = '';
	foreach ( $opts as $opt ) {
		$r .= _list_item_option_meta_row( $opt );
	}

	$response = array(
		'meta_id'  => $id,
		'meta_row' => $r,
	);
	wp_send_json( $response );
}

/**
 * Item SKU ajax
 */
function item_sku_ajax() {
	global $usces;

	$id = '';
	if ( 'item_sku_ajax' !== $_POST['action'] ) {
		die(0);
	}

	$post_id = (int) $_POST['ID'];
	$msg     = '';

	if ( isset( $_POST['update'] ) ) {
		$id = up_item_sku_meta( $post_id );

	} elseif ( isset( $_POST['delete'] ) ) {
		$id = del_item_sku_meta( $post_id );

	} elseif ( isset( $_POST['select'] ) ) {
		$response = select_item_sku( $post_id );
		wp_send_json( $response );

	} elseif ( isset( $_POST['sort'] ) ) {
		$id = usces_sort_item_skus( $post_id, $_POST['meta'] );

	} else {
		$id = add_item_sku_meta( $post_id );

	}
	$msg .= apply_filters( 'usces_filter_item_sku_message', $msg, $id, $post_id );
	// $skus = $usces->get_skus( $post_id );
	$skus = wel_get_skus( $post_id, 'sort', false );

	$r = '';

	foreach ( (array) $skus as $sku ) {
		$r .= _list_item_sku_meta_row( $sku );
	}
	$response = array(
		'meta_id'  => $id,
		'meta_row' => $r,
		'meta_msg' => $msg,
	);

	wp_send_json( $response );
}

/**
 * Item save metadata
 *
 * @param int    $post_id Post ID.
 * @param object $post Post data.
 */
function item_save_metadata( $post_id, $post ) {
	global $usces, $wpdb;

	$message   = '';
	$item_data = wel_get_item( $post_id, false );
	$args      = array(
		'usces_nonce'                => FILTER_SANITIZE_SPECIAL_CHARS,
		'itemCode'                   => FILTER_SANITIZE_SPECIAL_CHARS,
		'itemName'                   => FILTER_DEFAULT,
		'itemRestriction'            => FILTER_SANITIZE_SPECIAL_CHARS,
		'itemPointrate'              => FILTER_VALIDATE_INT,
		'itemGpNum1'                 => FILTER_VALIDATE_INT,
		'itemGpNum2'                 => FILTER_VALIDATE_INT,
		'itemGpNum3'                 => FILTER_VALIDATE_INT,
		'itemGpDis1'                 => FILTER_VALIDATE_INT,
		'itemGpDis2'                 => FILTER_VALIDATE_INT,
		'itemGpDis3'                 => FILTER_VALIDATE_INT,
		'itemOrderAcceptable'        => FILTER_DEFAULT,
		'itemShipping'               => FILTER_VALIDATE_INT,
		'itemDeliveryMethod'         => array(
			'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
			'flags'  => FILTER_REQUIRE_ARRAY,
		),
		'itemShippingCharge'         => FILTER_VALIDATE_INT,
		'itemIndividualSCharge'      => FILTER_DEFAULT,
		'item_charging_type'         => FILTER_VALIDATE_INT,
		'item_division'              => FILTER_SANITIZE_SPECIAL_CHARS,
		'dlseller_date'              => FILTER_SANITIZE_SPECIAL_CHARS,
		'dlseller_file'              => FILTER_SANITIZE_SPECIAL_CHARS,
		'dlseller_interval'          => FILTER_VALIDATE_INT,
		'dlseller_validity'          => FILTER_VALIDATE_INT,
		'dlseller_version'           => FILTER_SANITIZE_SPECIAL_CHARS,
		'dlseller_author'            => FILTER_SANITIZE_SPECIAL_CHARS,
		'dlseller_purchases'         => FILTER_VALIDATE_INT,
		'dlseller_downloads'         => FILTER_VALIDATE_INT,
		'item_chargingday'           => FILTER_DEFAULT,
		'item_frequency'             => FILTER_DEFAULT,
		'wcad_regular_unit'          => FILTER_DEFAULT,
		'wcad_regular_interval'      => FILTER_DEFAULT,
		'wcad_regular_frequency'     => FILTER_DEFAULT,
		'select_sku_switch'          => FILTER_VALIDATE_INT,
		'select_sku_display'         => FILTER_VALIDATE_INT,
		'select_sku'                 => array(
			'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
			'flags'  => FILTER_REQUIRE_ARRAY,
		),
		'deferred_payment_propriety' => FILTER_VALIDATE_INT,
		'atodene_propriety'          => FILTER_VALIDATE_INT,
		'structuredDataSku'          => FILTER_SANITIZE_SPECIAL_CHARS,
		'lower_limit'                => FILTER_SANITIZE_SPECIAL_CHARS,
		'popularity'                 => FILTER_SANITIZE_SPECIAL_CHARS,
		'main_price'                 => FILTER_SANITIZE_SPECIAL_CHARS,
		'itemPicts'                  => FILTER_DEFAULT,
		'itemAdvanced'               => array(
			'filter' => FILTER_DEFAULT,
			'flags'  => FILTER_REQUIRE_ARRAY,
		),
		'itemsku'                    => array(
			'filter' => FILTER_DEFAULT,
			'flags'  => FILTER_REQUIRE_ARRAY,
		),
		'itemopt'                    => array(
			'filter' => FILTER_DEFAULT,
			'flags'  => FILTER_REQUIRE_ARRAY,
		),
	);

	$inputs = filter_input_array( INPUT_POST, $args );
	$page   = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
	// Permission check.
	if ( 'usces_itemedit' === $page || 'usces_itemnew' === $page ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			$usces->set_action_status( 'error', 'ERROR : ' . __( 'Sorry, you do not have the right to edit this post.' ) );
			return $post_id;
		}

		// Do nothing when saving auto-draft.
		if ( isset( $post->post_status ) && 'auto-draft' === $post->post_status ) {
			return $post_id;
		}
		$usces->set_item_mime( $post_id, 'item' );

	} else {

		return $post_id;

	}

	if ( ! wp_verify_nonce( $inputs['usces_nonce'], 'usc-e-shop' ) ) {
		return $post_id;
	}

	// Check if it is an automatic save routine. If so, do not submit the form (do nothing).
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Do nothing when saving other plugins.
	if ( isset( $post->post_type ) && 'post' !== $post->post_type ) {
		return $post_id;
	}

	if ( preg_match( '/[^0-9a-zA-Z\-_]/', $inputs['itemCode'] ) ) {
		// Do nothing.
	}

	if ( empty( $inputs['itemCode'] ) ) {
		$inputs['itemCode'] = '';
		$message           .= __( 'Product code has not been entered.', 'usces' ) . '<br />';
	} elseif ( $res = usces_is_same_itemcode( $post_id, $inputs['itemCode'] ) ) {
		$message .= 'post_ID ';
		foreach ( $res as $postid ) {
			$message .= $postid . ', ';
		}
		$message .= __( 'Same product code is registered here.', 'usces' ) . '<br />';
		$usces->set_action_status( 'error', 'ERROR : ' . $message );
	}
	$item_data['itemCode'] = $inputs['itemCode'];

	if ( null !== $inputs['itemName'] ) {
		$item_data['itemName'] = trim( $inputs['itemName'] );
	}
	if ( null !== $inputs['itemRestriction'] ) {
		$item_data['itemRestriction'] = trim( $inputs['itemRestriction'] );
	}
	if ( null !== $inputs['itemPointrate'] ) {
		$item_data['itemPointrate'] = (int) $inputs['itemPointrate'];
	}
	if ( null !== $inputs['itemGpNum1'] ) {
		$item_data['itemGpNum1'] = (int) $inputs['itemGpNum1'];
	}
	if ( null !== $inputs['itemGpNum2'] ) {
		$item_data['itemGpNum2'] = (int) $inputs['itemGpNum2'];
	}
	if ( null !== $inputs['itemGpNum3'] ) {
		$item_data['itemGpNum3'] = (int) $inputs['itemGpNum3'];
	}
	if ( null !== $inputs['itemGpDis1'] ) {
		$item_data['itemGpDis1'] = (int) $inputs['itemGpDis1'];
	}
	if ( null !== $inputs['itemGpDis2'] ) {
		$item_data['itemGpDis2'] = (int) $inputs['itemGpDis2'];
	}
	if ( null !== $inputs['itemGpDis3'] ) {
		$item_data['itemGpDis3'] = (int) $inputs['itemGpDis3'];
	}

	$item_data['itemOrderAcceptable'] = null !== $inputs['itemOrderAcceptable'] ? 1 : 0;

	if ( null !== $inputs['itemShipping'] ) {
		$item_data['itemShipping'] = (int) $inputs['itemShipping'];
	}
	if ( null !== $inputs['itemDeliveryMethod'] ) {
		$dmethod = array();
		foreach ( (array) $inputs['itemDeliveryMethod'] as $dmid ) {
			$dmethod[] = $dmid;
		}
		$item_data['itemDeliveryMethod'] = $dmethod;
	}
	if ( null !== $inputs['itemShippingCharge'] ) {
		$item_data['itemShippingCharge'] = (int) $inputs['itemShippingCharge'];
	}

	$item_data['itemIndividualSCharge'] = null !== $inputs['itemIndividualSCharge'] ? 1 : 0;

	$options         = get_option( 'usces' );
	$acting_settings = ( isset( $options['acting_settings'] ) ) ? $options['acting_settings'] : array();
	if ( isset( $acting_settings['welcart'] ) && 'on' === $acting_settings['welcart']['atodene_byitem'] ) {
		$item_data['atodene_propriety'] = null !== $inputs['atodene_propriety'] ? (int) $inputs['atodene_propriety'] : 0;
	}
	$options         = get_option( 'usces_ex' );
	$system_settings = $options['system'];
	if ( isset( $system_settings['atobaraicsv'] ) && 1 === (int) $system_settings['atobaraicsv']['each_item'] ) {
		$item_data['atobarai_propriety'] = null !== $inputs['deferred_payment_propriety'] ? (int) $inputs['deferred_payment_propriety'] : 0;
	}

	if ( 1 === (int) USCES_STRUCTURED_DATA_PRODUCT::$opts['status'] ) {
		$item_data['structuredDataSku'] = ( isset( $inputs['structuredDataSku'] ) ) ? trim( $inputs['structuredDataSku'] ) : '';
	}

	wel_update_item_data( $item_data, $post_id, true );
	$item_data = wel_get_item( $post_id, false );

	// SKU.
	if ( null !== $inputs['itemsku'] ) {

		$meta_ids    = array();
		$codes       = array();
		$uniq_code   = false;
		$irreg_code  = false;
		$irreg_price = false;

		$skus = wel_get_skus( $post_id, 'meta_id', false );

		foreach ( $inputs['itemsku'] as $mid => $temp ) {
			$meta_ids[] = $mid;
		}
		$meta_ids = array_unique( $meta_ids );
		foreach ( $meta_ids as $meta_id ) {

			$skucode = isset( $inputs['itemsku'][ $meta_id ]['key'] ) ? trim( $inputs['itemsku'][ $meta_id ]['key'] ) : '';
			$msgsku  = apply_filters( 'usces_filter_before_item_save_metadata_sku', null, $post_id, $meta_id, $skucode );
			if ( ! empty( $msgsku ) ) {
				$message .= $msgsku;
				continue;
			}

			$skucprice   = isset( $inputs['itemsku'][ $meta_id ]['cprice'] ) ? trim( $inputs['itemsku'][ $meta_id ]['cprice'] ) : 0;
			$skuprice    = isset( $inputs['itemsku'][ $meta_id ]['price'] ) ? trim( $inputs['itemsku'][ $meta_id ]['price'] ) : 0;
			$skustocknum = isset( $inputs['itemsku'][ $meta_id ]['zaikonum'] ) ? trim( $inputs['itemsku'][ $meta_id ]['zaikonum'] ) : 0;
			$skustock    = isset( $inputs['itemsku'][ $meta_id ]['zaiko'] ) ? (int) $inputs['itemsku'][ $meta_id ]['zaiko'] : '';
			$skuname     = isset( $inputs['itemsku'][ $meta_id ]['skudisp'] ) ? trim( $inputs['itemsku'][ $meta_id ]['skudisp'] ) : '';
			$skuunit     = isset( $inputs['itemsku'][ $meta_id ]['skuunit'] ) ? trim( $inputs['itemsku'][ $meta_id ]['skuunit'] ) : '';
			$skugp       = isset( $inputs['itemsku'][ $meta_id ]['skugptekiyo'] ) ? (int) $inputs['itemsku'][ $meta_id ]['skugptekiyo'] : 0;
			$skusort     = isset( $inputs['itemsku'][ $meta_id ]['sort'] ) ? $inputs['itemsku'][ $meta_id ]['sort'] : 0;
			$skutaxrate  = isset( $inputs['itemsku'][ $meta_id ]['applicable_taxrate'] ) ? $inputs['itemsku'][ $meta_id ]['applicable_taxrate'] : '';

			$sku = $skus[ $meta_id ];

			$sku['code']     = $skucode;
			$sku['name']     = $skuname;
			$sku['cprice']   = $skucprice;
			$sku['price']    = $skuprice;
			$sku['unit']     = $skuunit;
			$sku['stocknum'] = $skustocknum;
			$sku['stock']    = $skustock;
			$sku['gp']       = $skugp;
			$sku['sort']     = $skusort;
			if ( ! empty( $skutaxrate ) ) {
				$sku['taxrate'] = $skutaxrate;
			}
			$sku = $usces->stripslashes_deep_post( $sku );
			$sku = apply_filters( 'usces_filter_item_save_sku_metadata', $sku, $meta_id );

			wel_update_sku_data_by_id( $meta_id, $post_id, $sku );

			if ( in_array( $skucode, $codes ) ) {
				$uniq_code = true;
			}

			if ( WCUtils::is_blank( $skucode ) ) {
				$irreg_code = true;
			}

			if ( WCUtils::is_blank( $skuprice ) || preg_match( '/[^0-9.]/', $skuprice ) || 1 < substr_count( $skuprice, '.' ) ) {
				$irreg_price = true;
			}

			$codes[] = $skucode;
		}

		if ( $uniq_code ) {
			$message .= __( 'SKU code is duplicated.', 'usces' ) . '<br />';
		}
		if ( $irreg_code ) {
			$message .= __( 'SKU code is invalid.', 'usces' ) . '<br />';
		}
		if ( $irreg_price ) {
			$message .= __( 'SKU of invalid selling price exists.', 'usces' ) . '<br />';
		}
	}

	// OPT.
	if ( null !== $inputs['itemopt'] ) {
		$meta_ids    = array();
		$names       = array();
		$uniq_name   = false;
		$irreg_name  = false;
		$irreg_value = false;

		$opts = wel_get_opts( $post_id, 'meta_id', false );

		foreach ( $inputs['itemopt'] as $mid => $temp ) {
			$meta_ids[] = $mid;
		}
		$meta_ids = array_unique( $meta_ids );
		foreach ( $meta_ids as $meta_id ) {

			$optname      = isset( $inputs['itemopt'][ $meta_id ]['name'] ) ? $inputs['itemopt'][ $meta_id ]['name'] : '';
			$optmeans     = isset( $inputs['itemopt'][ $meta_id ]['means'] ) ? (int) $inputs['itemopt'][ $meta_id ]['means'] : 0;
			$optessential = isset( $inputs['itemopt'][ $meta_id ]['essential'] ) ? $inputs['itemopt'][ $meta_id ]['essential'] : 0;
			$optsort      = isset( $inputs['itemopt'][ $meta_id ]['sort'] ) ? $inputs['itemopt'][ $meta_id ]['sort'] : 0;
			$optvalue     = isset( $inputs['itemopt'][ $meta_id ]['value'] ) ? trim( $inputs['itemopt'][ $meta_id ]['value'] ) : '';

			$opt = $opts[ $meta_id ];

			$opt['name']      = str_replace( "\\", '', $optname );
			$opt['value']     = str_replace( "\\", '', $optvalue );
			$opt['means']     = $optmeans;
			$opt['essential'] = $optessential;
			$opt['sort']      = $optsort;

			$opt = $usces->stripslashes_deep_post( $opt );

			wel_update_opt_data_by_id( $meta_id, $post_id, $opt );

			if ( in_array( $optname, $names ) ) {
				$uniq_name = true;
			}

			if ( WCUtils::is_blank( $optname ) ) {
				$irreg_name = true;
			}

			if ( WCUtils::is_blank( $optvalue ) && 1 >= $optmeans ) {
				$irreg_value = true;
			}

			$names[] = $optname;
		}

		if ( $uniq_name ) {
			$message .= __( 'Commodity option option name duplicates exist.', 'usces' ) . '<br />';
		}
		if ( $irreg_name ) {
			$message .= __( 'Commodity option not entered there is the option name.', 'usces' ) . '<br />';
		}
		if ( $irreg_value ) {
			$message .= __( "If you select 'single select' and 'multi-select' the trade option, please enter the select value.", 'usces' ) . '<br />';
		}
	}

	do_action( 'usces_action_save_product', $post_id, $post );
	$message = apply_filters( 'usces_filter_save_product_message', $message, $post_id );

	if ( $message ) {
		$usces->set_action_status( 'error', 'ERROR : ' . $message );
	} else {
		$usces->set_action_status( 'success', __( 'Registration of the product is complete.', 'usces' ) );
	}
}

/**
 * Link replace
 *
 * @param string $para Parameters.
 * @return string
 */
function usces_link_replace( $para ) {
	$str = 'admin.php?page=usces_itemedit&';
	$url = preg_replace( '|post\.php\?|i', $str, $para );
	return $url;
}

/**
 * Count posts
 *
 * @param string $type Type.
 * @param string $perm perm.
 * @return object
 */
function usces_count_posts( $type = 'post', $perm = '' ) {
	global $wpdb;

	$user = wp_get_current_user();

	$cache_key = $type;

	$query = "SELECT post_status, COUNT( * ) AS `num_posts` FROM {$wpdb->posts} WHERE post_type = %s AND post_mime_type = 'item'";
	if ( 'readable' == $perm && is_user_logged_in() ) {
		if ( ! current_user_can( "read_private_{$type}s" ) ) {
			$cache_key .= '_' . $perm . '_' . $user->ID;
			$query     .= " AND (post_status != 'private' OR ( post_author = '$user->ID' AND post_status = 'private' ))";
		}
	}
	$query .= ' GROUP BY post_status';

	$count = wp_cache_get( $cache_key, 'counts' );
	if ( false !== $count ) {
		$count = $wpdb->get_results( $wpdb->prepare( $query, $type ), ARRAY_A );
	}

	$stats = array();
	foreach ( (array) $count as $row_num => $row ) {
		$stats[ $row['post_status'] ] = $row['num_posts'];
	}

	$stats = (object) $stats;
	wp_cache_set( $cache_key, $stats, 'counts' );

	return $stats;
}

/**
 * Custom order meta row
 *
 * @param string $key Key.
 * @param array  $entry Entry data.
 * @return string
 */
function _list_custom_order_meta_row( $key, $entry ) {
	$r     = '';
	$style = '';
	$key   = esc_attr( $key );

	$name        = esc_attr( $entry['name'] );
	$means       = get_option( 'usces_custom_order_select' );
	$meansoption = '';
	foreach ( $means as $meankey => $meanvalue ) {
		$meansoption .= '<option value="' . esc_attr( $meankey ) . '"' . selected( $meankey, $entry['means'], false ) . '>' . esc_html( $meanvalue ) . "</option>\n";
	}
	$essential = ( 1 === (int) $entry['essential'] ) ? ' checked="checked"' : '';
	$value     = '';
	if ( is_array( $entry['value'] ) ) {
		foreach ( $entry['value'] as $k => $v ) {
			$value .= $v . "\n";
		}
	}
	$value = esc_attr( trim( $value ) );

	$r .= "\n\t<tr id=\"csod-{$key}\" class=\"{$style}\">";
	$r .= "\n\t\t<td class=\"left\"><div><input type=\"text\" name=\"csod[{$key}][key]\" id=\"csod[{$key}][key]\" class=\"optname\" size=\"20\" value=\"{$key}\" readonly /></div>";
	$r .= "\n\t\t<div><input type=\"text\" name=\"csod[{$key}][name]\" id=\"csod[{$key}][name]\" class=\"optname\" size=\"20\" value=\"{$name}\" /></div>";
	$r .= "\n\t\t<div class=\"optcheck\"><select name=\"csod[{$key}][means]\" id=\"csod[{$key}][means]\">" . $meansoption . "</select>\n";
	$r .= "<input type=\"checkbox\" name=\"csod[{$key}][essential]\" id=\"csod[{$key}][essential]\" value=\"1\"{$essential} /><label for=\"csod[{$key}][essential]\">" . esc_html__( 'Required', 'usces' ) . '</label></div>';
	$r .= "\n\t\t<div class=\"submit\"><input type=\"button\" class=\"button\" name=\"del_csod[{$key}]\" id=\"del_csod[{$key}]\" value=\"" . esc_attr__( 'Delete' ) . "\" onclick=\"customField.delOrder('{$key}');\" />";
	$r .= "\n\t\t<input type=\"button\" class=\"button\" name=\"upd_csod[{$key}]\" id=\"upd_csod[{$key}]\" value=\"" . esc_attr__( 'Update' ) . "\" onclick=\"customField.updOrder('{$key}');\" /></div>";
	$r .= "\n\t\t<div id=\"csod_loading-{$key}\" class=\"meta_submit_loading\"></div>";
	$r .= '</td>';
	$r .= "\n\t\t<td class=\"item-opt-value\"><textarea name=\"csod[{$key}][value]\" id=\"csod[{$key}][value]\" class=\"optvalue\">{$value}</textarea></td>\n\t</tr>";
	return $r;
}

/**
 * Has custom field meta
 *
 * @param string $fieldname Field name.
 * @return mixed
 */
function usces_has_custom_field_meta( $fieldname ) {
	switch ( $fieldname ) {
		case 'order':
			$field = 'usces_custom_order_field';
			break;
		case 'customer':
			$field = 'usces_custom_customer_field';
			break;
		case 'delivery':
			$field = 'usces_custom_delivery_field';
			break;
		case 'member':
			$field = 'usces_custom_member_field';
			break;
		case 'admin_member':
			$field = 'usces_admin_custom_member_field';
			break;
		default:
			return array();
	}
	$fields = get_option( $field );
	if ( empty( $fields ) ) {
		$meta = array();
	} elseif ( is_array( $fields ) ) {
		$meta = $fields;
	} else {
		$meta = unserialize( $fields );
	}
	return $meta;
}

/**
 * Get info ajax
 *
 * @return void
 */
function usces_getinfo_ajax() {
	global $wp_version;

	$wcex_str = '';
	$res      = '';
	$wcex     = usces_get_wcex();
	foreach ( (array) $wcex as $key => $values ) {
		$wcex_str .= $key . '-' . $values['version'] . ',';
	}
	$wcex_str = rtrim( $wcex_str, ',' );
	if ( version_compare( $wp_version, '3.4', '>=' ) ) {
		$theme_ob             = wp_get_theme();
		$themedata['Name']    = $theme_ob->get( 'Name' );
		$themedata['Version'] = $theme_ob->get( 'Version' );
	} else {
		$themedata = get_theme_data( get_stylesheet_directory() . '/style.css' );
	}

	$v             = urlencode( USCES_VERSION );
	$wcid          = urlencode( get_option( 'usces_wcid' ) );
	$locale        = urlencode( get_locale() );
	$theme         = urlencode( $themedata['Name'] . '-' . $themedata['Version'] );
	$wcex          = urlencode( $wcex_str );
	$interface_url = 'http://www.welcart.com/util/welcart_information2.php';
	$wcurl         = urlencode( get_home_url() );
	$interface     = parse_url( $interface_url );

	$vars    = "v=$v&wcid=$wcid&locale=$locale&theme=$theme&wcex=$wcex&wcurl=$wcurl";
	$header  = 'POST ' . $interface_url . " HTTP/1.1\r\n";
	$header .= 'Host: ' . $_SERVER['HTTP_HOST'] . "\r\n";
	$header .= 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= 'Content-Length: ' . strlen( $vars ) . "\r\n";
	$header .= "Connection: close\r\n\r\n";
	$header .= $vars;
	$fp      = fsockopen( $interface['host'], 80, $errno, $errstr, 30 );
	if ( $fp ) {
		fwrite( $fp, $header );
		$i = 0;
		while ( ! feof( $fp ) ) {
			$scr = fgets( $fp, 10240 );
			preg_match( "/<(title|data)>(.*)<(\/title|\/data)>$/", $scr, $match );
			if ( ! empty( $match[2] ) ) {
				switch ( $match[1] ) {
					case'title':
						$res .= '<div style="text-align: center;border-bottom: 1px dotted #CCCCCC;width: 80%;margin-bottom: 10px;padding-bottom: 3px; margin-right: auto; margin-left: auto;"><stlong>' . $match[2] . '</strong></div><ul>';
						break;
					case 'data':
						$res .= '<li>' . $match[2] . '</li>';
						break;
				}
			}
			$i++;
			if ( $i > 50 ) {
				$res = 'ERROR';
				break;
			}
		}
		$res .= '</ul>';
		fclose( $fp );
	} else {
		$res = 'ERROR';
	}
	die( $res );
}

/**
 * Custom field ajax
 */
function custom_field_ajax() {
	global $usces;

	check_admin_referer( 'custom_field_ajax', 'wc_nonce' );
	$_POST = $usces->stripslashes_deep_post( $_POST );
	$data  = array(
		'status' => 'OK',
		'msg'    => '',
	);

	if ( 'custom_field_ajax' !== $_POST['action'] ) {
		$data['status'] = 'NG';
		wp_send_json( $data );
	}

	switch ( $_POST['field'] ) {
		case 'order':
			$field = 'usces_custom_order_field';
			break;
		case 'customer':
			$field = 'usces_custom_customer_field';
			break;
		case 'delivery':
			$field = 'usces_custom_delivery_field';
			break;
		case 'member':
			$field = 'usces_custom_member_field';
			break;
		case 'admin_member':
			$field = 'usces_admin_custom_member_field';
			break;
		default:
			$data['status'] = 'NG';
			wp_send_json( $data );
	}

	$meta    = usces_has_custom_field_meta( $_POST['field'] );
	$dupkey  = 0;
	$dupname = 0;

	if ( isset( $_POST['add'] ) ) {
		$newkey       = ( isset( $_POST['newkey'] ) ) ? trim( $_POST['newkey'] ) : '';
		$newname      = ( isset( $_POST['newname'] ) ) ? trim( $_POST['newname'] ) : '';
		$newmeans     = ( isset( $_POST['newmeans'] ) ) ? $_POST['newmeans'] : 0;
		$newessential = ( isset( $_POST['newessential'] ) ) ? $_POST['newessential'] : 0;
		$newposition  = ( isset( $_POST['newposition'] ) ) ? trim( $_POST['newposition'] ) : '';

		if ( 2 === (int) $newmeans || 5 === (int) $newmeans ) { // Text or Textarea.
			$newvalue       = '';
			$nv             = $newvalue;
			$required_entry = ( ! empty( $newkey ) && ! empty( $newname ) ) ? true : false;
		} else {
			$newvalue = ( isset( $_POST['newvalue'] ) ) ? explode( "\n", trim( $_POST['newvalue'] ) ) : '';
			foreach ( (array) $newvalue as $v ) {
				if ( ! WCUtils::is_blank( $v ) ) {
					$nv[] = trim( $v );
				}
			}
			$required_entry = ( ( ! empty( $newvalue ) ) && ! empty( $newkey ) && ! empty( $newname ) ) ? true : false;
		}

		if ( ! array_key_exists( $newkey, $meta ) ) {
			if ( $required_entry ) {
				$meta[ $newkey ]['name']      = $newname;
				$meta[ $newkey ]['means']     = $newmeans;
				$meta[ $newkey ]['essential'] = $newessential;
				$meta[ $newkey ]['value']     = $nv;
				if ( ! WCUtils::is_blank( $newposition ) ) {
					$meta[ $newkey ]['position'] = $newposition;
				} elseif ( ( 'admin_member' === $_POST['field'] ) && WCUtils::is_blank( $newposition ) ) {
					$meta[ $newkey ]['position'] = 'fax_after';
				}
				update_option( $field, $meta );
			}
		} else {
			$dupkey = 1;
		}

	} elseif ( isset( $_POST['update'] ) ) {
		$key       = ( isset( $_POST['key'] ) ) ? trim( $_POST['key'] ) : '';
		$name      = ( isset( $_POST['name'] ) ) ? trim( $_POST['name'] ) : '';
		$means     = ( isset( $_POST['means'] ) ) ? $_POST['means'] : 0;
		$essential = ( isset( $_POST['essential'] ) ) ? $_POST['essential'] : 0;
		$position  = ( isset( $_POST['position'] ) ) ? trim( $_POST['position'] ) : '';

		if ( 2 === (int) $means || 5 === (int) $means ) { // Text or Textarea.
			$value          = '';
			$nv             = $value;
			$required_entry = ( ! empty( $key ) && ! empty( $name ) ) ? true : false;

		} else {
			$value = ( isset( $_POST['value'] ) ) ? explode( "\n", trim( $_POST['value'] ) ) : '';
			foreach ( (array) $value as $v ) {
				if ( ! WCUtils::is_blank( $v ) ) {
					$nv[] = trim( $v );
				}
			}
			$required_entry = ( ( ! empty( $value ) ) && ! empty( $key ) && ! empty( $name ) ) ? true : false;
		}

		if ( $required_entry ) {
			$meta[ $key ]['name']      = $name;
			$meta[ $key ]['means']     = $means;
			$meta[ $key ]['essential'] = $essential;
			$meta[ $key ]['value']     = $nv;
			if ( ! WCUtils::is_blank( $position ) ) {
				$meta[ $key ]['position'] = $position;
			}
			update_option( $field, $meta );
		}

	} elseif ( isset( $_POST['delete'] ) ) {
		$key = ( isset( $_POST['key'] ) ) ? trim( $_POST['key'] ) : '';
		unset( $meta[ $key ] );
		update_option( $field, $meta );
	}

	$r = '';
	switch ( $_POST['field'] ) {
		case 'order':
			foreach ( $meta as $key => $entry ) {
				$r .= _list_custom_order_meta_row( $key, $entry );
			}
			break;
		case 'customer':
			foreach ( $meta as $key => $entry ) {
				$r .= _list_custom_customer_meta_row( $key, $entry );
			}
			break;
		case 'delivery':
			foreach ( $meta as $key => $entry ) {
				$r .= _list_custom_delivery_meta_row( $key, $entry );
			}
			break;
		case 'member':
			foreach ( $meta as $key => $entry ) {
				$r .= _list_custom_member_meta_row( $key, $entry );
			}
			break;
		case 'admin_member':
			foreach ( $meta as $key => $entry ) {
				$r .= _list_admin_custom_member_meta_row( $key, $entry );
			}
			break;
	}

	$data['list']    = $r;
	$data['dupkey']  = $dupkey;
	$data['dupname'] = $dupname;
	wp_send_json( $data );
}

/**
 * List custom customer meta row
 *
 * @param string $key Key.
 * @param array  $entry Entry data.
 * @return string
 */
function _list_custom_customer_meta_row( $key, $entry ) {
	$r     = '';
	$style = '';
	$key   = esc_attr( $key );

	$name        = esc_attr( $entry['name'] );
	$means       = get_option( 'usces_custom_customer_select' );
	$meansoption = '';
	foreach ( $means as $meankey => $meanvalue ) {
		$meansoption .= '<option value="' . esc_attr( $meankey ) . '"' . selected( $meankey, $entry['means'], false ) . '>' . esc_html( $meanvalue ) . "</option>\n";
	}
	$essential = ( 1 === (int) $entry['essential'] ) ? ' checked="checked"' : '';
	$value     = '';
	if ( is_array( $entry['value'] ) ) {
		foreach ( $entry['value'] as $k => $v ) {
			$value .= $v . "\n";
		}
	}
	$value           = esc_attr( trim( $value ) );
	$positions       = get_option( 'usces_custom_field_position_select' );
	$positionsoption = '';
	foreach ( $positions as $poskey => $posvalue ) {
		$positionsoption .= '<option value="' . esc_attr( $poskey ) . '"' . selected( $poskey, $entry['position'], false ) . '>' . esc_attr( $posvalue ) . "</option>\n";
	}

	$r .= "\n\t<tr id=\"cscs-{$key}\" class=\"{$style}\">";
	$r .= "\n\t\t<td class=\"left\"><div><input type=\"text\" name=\"cscs[{$key}][key]\" id=\"cscs[{$key}][key]\" class=\"optname\" size=\"20\" value=\"{$key}\" readonly /></div>";
	$r .= "\n\t\t<div><input type=\"text\" name=\"cscs[{$key}][name]\" id=\"cscs[{$key}][name]\" class=\"optname\" size=\"20\" value=\"{$name}\" /></div>";
	$r .= "\n\t\t<div class=\"optcheck\"><select name=\"cscs[{$key}][means]\" id=\"cscs[{$key}][means]\">" . $meansoption . "</select>\n";
	$r .= "<input type=\"checkbox\" name=\"cscs[{$key}][essential]\" id=\"cscs[{$key}][essential]\" value=\"1\"{$essential} /><label for=\"cscs[{$key}][essential]\">" . esc_html__( 'Required', 'usces' ) . "</label>\n";
	$r .= "<select name=\"cscs[{$key}][position]\" id=\"cscs[{$key}][position]\">" . $positionsoption . '</select></div>';
	$r .= "\n\t\t<div class=\"submit\"><input type=\"button\" class=\"button\" name=\"del_cscs[{$key}]\" id=\"del_cscs[{$key}]\" value=\"" . esc_attr__( 'Delete' ) . "\" onclick=\"customField.delCustomer('{$key}');\" />";
	$r .= "\n\t\t<input type=\"button\" class=\"button\" name=\"upd_cscs[{$key}]\" id=\"upd_cscs[{$key}]\" value=\"" . esc_attr__( 'Update' ) . "\" onclick=\"customField.updCustomer('{$key}');\" /></div>";
	$r .= "\n\t\t<div id=\"cscs_loading-{$key}\" class=\"meta_submit_loading\"></div>";
	$r .= '</td>';
	$r .= "\n\t\t<td class=\"item-opt-value\"><textarea name=\"cscs[{$key}][value]\" id=\"cscs[{$key}][value]\" class=\"optvalue\">{$value}</textarea></td>\n\t</tr>";
	return $r;
}

/**
 * List custom delivery meta row
 *
 * @param string $key Key.
 * @param array  $entry Entry data.
 * @return string
 */
function _list_custom_delivery_meta_row( $key, $entry ) {
	$r     = '';
	$style = '';
	$key   = esc_attr( $key );

	$name        = esc_attr( $entry['name'] );
	$means       = get_option( 'usces_custom_delivery_select' );
	$meansoption = '';
	foreach ( $means as $meankey => $meanvalue ) {
		$meansoption .= '<option value="' . esc_attr( $meankey ) . '"' . selected( $meankey, $entry['means'], false ) . '>' . esc_html( $meanvalue ) . "</option>\n";
	}
	$essential = ( 1 === (int) $entry['essential'] ) ? ' checked="checked"' : '';
	$value     = '';
	if ( is_array( $entry['value'] ) ) {
		foreach ( $entry['value'] as $k => $v ) {
			$value .= $v . "\n";
		}
	}
	$value           = esc_attr( trim( $value ) );
	$positions       = get_option( 'usces_custom_field_position_select' );
	$positionsoption = '';
	foreach ( $positions as $poskey => $posvalue ) {
		$positionsoption .= '<option value="' . esc_attr( $poskey ) . '"' . selected( $poskey, $entry['position'], false ) . '>' . esc_attr( $posvalue ) . "</option>\n";
	}

	$r .= "\n\t<tr id=\"csde-{$key}\" class=\"{$style}\">";
	$r .= "\n\t\t<td class=\"left\"><div><input type=\"text\" name=\"csde[{$key}][key]\" id=\"csde[{$key}][key]\" class=\"optname\" size=\"20\" value=\"{$key}\" readonly /></div>";
	$r .= "\n\t\t<div><input type=\"text\" name=\"csde[{$key}][name]\" id=\"csde[{$key}][name]\" class=\"optname\" size=\"20\" value=\"{$name}\" /></div>";
	$r .= "\n\t\t<div class=\"optcheck\"><select name=\"csde[{$key}][means]\" id=\"csde[{$key}][means]\">" . $meansoption . "</select>\n";
	$r .= "<input type=\"checkbox\" name=\"csde[{$key}][essential]\" id=\"csde[{$key}][essential]\" value=\"1\"{$essential} /><label for=\"csde[{$key}][essential]\">" . esc_html__( 'Required', 'usces' ) . "</label>\n";
	$r .= "<select name=\"csde[{$key}][position]\" id=\"csde[{$key}][position]\">" . $positionsoption . '</select></div>';
	$r .= "\n\t\t<div class=\"submit\"><input type=\"button\" class=\"button\" name=\"del_csde[{$key}]\" id=\"del_csde[{$key}]\" value=\"" . esc_attr__( 'Delete' ) . "\" onclick=\"customField.delDelivery('{$key}');\" />";
	$r .= "\n\t\t<input type=\"button\" class=\"button\" name=\"upd_csde[{$key}]\" id=\"upd_csde[{$key}]\" value=\"" . esc_attr__( 'Update' ) . "\" onclick=\"customField.updDelivery('{$key}');\" /></div>";
	$r .= "\n\t\t<div id=\"csde_loading-{$key}\" class=\"meta_submit_loading\"></div>";
	$r .= '</td>';
	$r .= "\n\t\t<td class=\"item-opt-value\"><textarea name=\"csde[{$key}][value]\" id=\"csde[{$key}][value]\" class=\"optvalue\">{$value}</textarea></td>\n\t</tr>";
	return $r;
}

/**
 * List custom member meta row
 *
 * @param string $key Key.
 * @param array  $entry Entry data.
 * @return string
 */
function _list_custom_member_meta_row( $key, $entry ) {
	$r     = '';
	$style = '';
	$key   = esc_attr( $key );

	$name        = esc_attr( $entry['name'] );
	$means       = get_option( 'usces_custom_member_select' );
	$meansoption = '';
	foreach ( $means as $meankey => $meanvalue ) {
		$meansoption .= '<option value="' . esc_attr( $meankey ) . '"' . selected( $meankey, $entry['means'], false ) . '>' . esc_html( $meanvalue ) . "</option>\n";
	}
	$essential = ( 1 === (int) $entry['essential'] ) ? ' checked="checked"' : '';
	$value     = '';
	if ( is_array( $entry['value'] ) ) {
		foreach ( $entry['value'] as $k => $v ) {
			$value .= $v . "\n";
		}
	}
	$value           = esc_attr( trim( $value ) );
	$positions       = get_option( 'usces_custom_field_position_select' );
	$positionsoption = '';
	foreach ( $positions as $poskey => $posvalue ) {
		$positionsoption .= '<option value="' . esc_attr( $poskey ) . '"' . selected( $poskey, $entry['position'], false ) . '>' . esc_attr( $posvalue ) . "</option>\n";
	}
	$r .= "\n\t<tr id=\"csmb-{$key}\" class=\"{$style}\">";
	$r .= "\n\t\t<td class=\"left\"><div><input type=\"text\" name=\"csmb[{$key}][key]\" id=\"csmb[{$key}][key]\" class=\"optname\" size=\"20\" value=\"{$key}\" readonly /></div>";
	$r .= "\n\t\t<div><input type=\"text\" name=\"csmb[{$key}][name]\" id=\"csmb[{$key}][name]\" class=\"optname\" size=\"20\" value=\"{$name}\" /></div>";
	$r .= "\n\t\t<div class=\"optcheck\"><select name=\"csmb[{$key}][means]\" id=\"csmb[{$key}][means]\">" . $meansoption . "</select>\n";
	$r .= "<input type=\"checkbox\" name=\"csmb[{$key}][essential]\" id=\"csmb[{$key}][essential]\" value=\"1\"{$essential} /><label for=\"csmb[{$key}][essential]\">" . esc_html__( 'Required', 'usces' ) . "</label>\n";
	$r .= "<select name=\"csmb[{$key}][position]\" id=\"csmb[{$key}][position]\">" . $positionsoption . '</select></div>';
	$r .= "\n\t\t<div class=\"submit\"><input type=\"button\" class=\"button\" name=\"del_csmb[{$key}]\" id=\"del_csmb[{$key}]\" value=\"" . esc_attr__( 'Delete' ) . "\" onclick=\"customField.delMember('{$key}');\" />";
	$r .= "\n\t\t<input type=\"button\" class=\"button\" name=\"upd_csmb[{$key}]\" id=\"upd_csmb[{$key}]\" value=\"" . esc_attr__( 'Update' ) . "\" onclick=\"customField.updMember('{$key}');\" /></div>";
	$r .= "\n\t\t<div id=\"csmb_loading-{$key}\" class=\"meta_submit_loading\"></div>";
	$r .= '</td>';
	$r .= "\n\t\t<td class=\"item-opt-value\"><textarea name=\"csmb[{$key}][value]\" id=\"csmb[{$key}][value]\" class=\"optvalue\">{$value}</textarea></td>\n\t</tr>";
	return $r;
}

/**
 * List admin custom member meta row
 *
 * @param string $key Key.
 * @param array  $entry Entry data.
 * @return string
 */
function _list_admin_custom_member_meta_row( $key, $entry ) {
	$r     = '';
	$style = '';
	$key   = esc_attr( $key );

	$name        = esc_attr( $entry['name'] );
	$means       = get_option( 'usces_custom_member_select' );
	$meansoption = '';
	foreach ( $means as $meankey => $meanvalue ) {
		$meansoption .= '<option value="' . esc_attr( $meankey ) . '"' . selected( $meankey, $entry['means'], false ) . '>' . esc_html( $meanvalue ) . "</option>\n";
	}
	$essential = ( 1 === (int) $entry['essential'] ) ? ' checked="checked"' : '';
	$value     = '';
	if ( is_array( $entry['value'] ) ) {
		foreach ( $entry['value'] as $k => $v ) {
			$value .= $v . "\n";
		}
	}
	$value = esc_attr( trim( $value ) );
	$r    .= "\n\t<tr id=\"admb-{$key}\" class=\"{$style}\">";
	$r    .= "\n\t\t<td class=\"left\"><div><input type=\"text\" name=\"admb[{$key}][key]\" id=\"admb[{$key}][key]\" class=\"optname\" size=\"20\" value=\"{$key}\" readonly /></div>";
	$r    .= "\n\t\t<div><input type=\"text\" name=\"admb[{$key}][name]\" id=\"admb[{$key}][name]\" class=\"optname\" size=\"20\" value=\"{$name}\" /></div>";
	$r    .= "\n\t\t<div class=\"optcheck\"><select name=\"admb[{$key}][means]\" id=\"admb[{$key}][means]\">" . $meansoption . "</select>\n";
	$r    .= "<input type=\"checkbox\" name=\"admb[{$key}][essential]\" id=\"admb[{$key}][essential]\" value=\"1\"{$essential} /><label for=\"admb[{$key}][essential]\">" . esc_html__( 'Required', 'usces' ) . "</label>\n";
	$r    .= '</div>';
	$r    .= "\n\t\t<div class=\"submit\"><input type=\"button\" class=\"button\" name=\"del_admb[{$key}]\" id=\"del_admb[{$key}]\" value=\"" . esc_attr__( 'Delete' ) . "\" onclick=\"customField.delAdmb('{$key}');\" />";
	$r    .= "\n\t\t<input type=\"button\" class=\"button\" name=\"upd_admb[{$key}]\" id=\"upd_admb[{$key}]\" value=\"" . esc_attr__( 'Update' ) . "\" onclick=\"customField.updAdmb('{$key}');\" /></div>";
	$r    .= "\n\t\t<div id=\"admb_loading-{$key}\" class=\"meta_submit_loading\"></div>";
	$r    .= '</td>';
	$r    .= "\n\t\t<td class=\"item-opt-value\"><textarea name=\"admb[{$key}][value]\" id=\"admb[{$key}][value]\" class=\"optvalue\">{$value}</textarea></td>\n\t</tr>";
	return $r;
}

/**
 * Change State/Prefecture
 */
function change_states_ajax() {
	global $usces, $usces_states;

	$_POST = $usces->stripslashes_deep_post( $_POST );

	$c     = $_POST['country'];
	$res   = '';
	$prefs = get_usces_states( $c );
	if ( is_array( $prefs ) && 0 < count( $prefs ) ) {
		foreach ( (array) $prefs as $state ) {
			$res .= '<option value="' . $state . '">' . $state . '</option>';
		}
	} else {
		die( 'error' );
	}
	die( $res );
}

/**
 * State/Prefecture
 *
 * @param string $country Country.
 * @return array
 */
function get_usces_states( $country ) {
	global $usces, $usces_states;

	$states = array();
	$prefs  = maybe_unserialize( $usces->options['province'] );
	if ( ! isset( $prefs[ $country ] ) || empty( $prefs[ $country ] ) ) {
		if ( $country == $usces->options['system']['base_country'] ) {
			foreach ( (array) $prefs as $state ) {
				if ( ! is_array( $state ) ) {
					array_push( $states, $state );
				}
			}
			if ( 0 === count( $states ) ) {
				if ( ! empty( $usces_states[ $country ] ) ) {
					$prefs = $usces_states[ $country ];
					if ( is_array( $prefs ) ) {
						$states = $prefs;
					}
				}
			}
		} else {
			if ( ! empty( $usces_states[ $country ] ) ) {
				$prefs = $usces_states[ $country ];
				if ( is_array( $prefs ) ) {
					$states = $prefs;
				}
			}
		}
	} else {
		$states = $prefs[ $country ];
	}
	return $states;
}

/**
 * Target market countries
 */
function target_market_ajax() {
	global $usces;

	$_POST    = $usces->stripslashes_deep_post( $_POST );
	$response = array();
	$target   = explode( ',', $_POST['target'] );
	foreach ( (array) $target as $country ) {
		$prefs = get_usces_states( $country );
		if ( is_array( $prefs ) && ! empty( $prefs ) ) {
			$pos = strpos( $prefs[0], '--' );
			if ( false !== $pos ) {
				array_shift( $prefs );
			}
			$response[] = $country . ',' . implode( "\n", $prefs );
		} else {
			$response[] = $country;
		}
	}
	wp_send_json( $response );
}

/**
 * Admin ajax
 */
function usces_admin_ajax() {
	switch ( $_POST['mode'] ) {
		case 'options_backup':
			check_admin_referer( 'options_backup', 'wc_nonce' );
			$options = get_option( 'usces' );
			$res     = true;
			if ( is_array( $options ) ) {
				$usces_backup_date = current_time( 'mysql' );
				update_option( 'usces_backup', $options );
				update_option( 'usces_backup_date', $usces_backup_date );
				$res = $usces_backup_date;
			} else {
				$res = false;
			}
			die( $res );
			break;
		case 'options_restore':
			check_admin_referer( 'options_restore', 'wc_nonce' );
			$options = get_option( 'usces_backup' );
			$res     = true;
			if ( is_array( $options ) ) {
				update_option( 'usces', $options );
			} else {
				$res = false;
			}
			die( $res );
			break;
	}
	do_action( 'usces_action_admin_ajax' );
}

/**
 * Order amount recalculation
 *
 * @param int    $order_id Order ID.
 * @param int    $mem_id Member ID.
 * @param int    $post_id Post ID.
 * @param float  $price Price.
 * @param int    $quant Quantity.
 * @param array  $cart_id Cart ID.
 * @param int    $use_point Use point.
 * @param float  $shipping_charge Shipping charge.
 * @param float  $cod_fee Fee.
 * @param float  $discount Discount.
 * @param string $change_taxrate Change applicable tax rate.
 */
function usces_order_recalculation( $order_id, $mem_id, $post_id, $price, $quant, $cart_id, $use_point, $shipping_charge, $cod_fee, $discount = 0, $change_taxrate = '' ) {
	global $usces;

	$data = array();
	$res  = 'ok';

	$cart          = array();
	$post_id_count = ( is_array( $post_id ) ) ? count( $post_id ) : 0;
	for ( $i = 0; $i < $post_id_count; $i++ ) {
		if ( $post_id[ $i ] ) {
			$cart[] = array(
				'post_id'  => $post_id[ $i ],
				'price'    => (float) $price[ $i ],
				'quantity' => (float) $quant[ $i ],
			);
		}
	}

	if ( 'change' === $change_taxrate ) {
		if ( usces_is_reduced_taxrate() ) {
			$usces_tax = Welcart_Tax::get_instance();
			$usces_tax->set_order_condition_reduced_taxrate( $order_id );
			for ( $i = 0; $i < $post_id_count; $i++ ) {
				if ( $cart_id[ $i ] ) {
					$taxrate = usces_get_ordercart_meta( 'taxrate', $cart_id[ $i ] );
					if ( ! $taxrate ) {
						$ordercart      = usces_get_ordercartdata_row( $cart_id[ $i ] );
						$sku['taxrate'] = $usces_tax->get_sku_applicable_taxrate( $post_id[ $i ], $ordercart['sku_code'] );
						$usces_tax->set_ordercart_applicable_taxrate( $cart_id[ $i ], $sku );
					}
				}
			}
			$data['status']   = $res;
			$data['tax_mode'] = 'reduced';
			wp_send_json( $data );
		}
		$condition = $usces->get_condition();
	} else {
		if ( ! empty( $order_id ) ) {
			$condition = usces_get_order_condition( $order_id );
		} else {
			$condition = $usces->get_condition();
		}
	}

	$tax_display         = ( isset( $condition['tax_display'] ) ) ? $condition['tax_display'] : usces_get_tax_display();
	$member_system       = ( isset( $condition['membersystem_state'] ) ) ? $condition['membersystem_state'] : $usces->options['membersystem_state'];
	$member_system_point = ( isset( $condition['membersystem_point'] ) ) ? $condition['membersystem_point'] : $usces->options['membersystem_point'];
	$tax_mode            = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : usces_get_tax_mode();
	$tax_target          = ( isset( $condition['tax_target'] ) ) ? $condition['tax_target'] : usces_get_tax_target();
	$point_coverage      = ( isset( $condition['point_coverage'] ) ) ? $condition['point_coverage'] : usces_point_coverage();

	$total_items_price = 0;
	foreach ( $cart as $cart_row ) {
		$total_items_price += $cart_row['price'] * $cart_row['quantity'];
	}
	$meminfo = $usces->get_member_info( $mem_id );

	if ( empty( $discount ) || 'NaN' == $discount ) {
		$discount = 0;
	}
	if ( 'change' == $change_taxrate ) {
		$discount = 0;
		if ( isset( $condition['display_mode'] ) && 'Promotionsale' === $condition['display_mode'] ) {
			if ( isset( $condition['campaign_privilege'] ) && 'discount' === $condition['campaign_privilege'] ) {
				if ( 0 === (int) $condition['campaign_category'] ) {
					$discount = (float) sprintf( '%.3f', $total_items_price * (float) $condition['privilege_discount'] / 100 );
				} else {
					foreach ( $cart as $cart_row ) {
						if ( in_category( (int) $condition['campaign_category'], $cart_row['post_id'] ) ) {
							$discount += (float) sprintf( '%.3f', $cart_row['price'] * $cart_row['quantity'] * (float) $condition['privilege_discount'] / 100 );
						}
					}
				}
			}
		}
		if ( 0 != $discount ) {
			$decimal = $usces->get_currency_decimal();
			if ( 0 == $decimal ) {
				$discount = ceil( $discount );
			} else {
				$decipad  = (int) str_pad( '1', $decimal + 1, '0', STR_PAD_RIGHT );
				$discount = ceil( $discount * $decipad ) / $decipad;
			}
			$discount = $discount * -1;
		}
	}
	$discount = apply_filters( 'usces_filter_order_discount_recalculation', $discount, $cart, $condition, $order_id );

	$point = 0;
	if ( empty( $use_point ) || 'NaN' == $use_point ) {
		$use_point = 0;
	}
	if ( 'activate' === $member_system && 'activate' === $member_system_point && ! empty( $meminfo['ID'] ) ) {
		if ( isset( $condition['display_mode'] ) && 'Promotionsale' === $condition['display_mode'] ) {
			if ( isset( $condition['campaign_privilege'] ) && 'discount' === $condition['campaign_privilege'] ) {
				foreach ( $cart as $cart_row ) {
					$cats = $usces->get_post_term_ids( $cart_row['post_id'], 'category' );
					if ( ! in_array( $condition['campaign_category'], $cats ) ) {
						$product   = wel_get_product( $cart_row['post_id'] );
						$rate      = (float) $product['itemPointrate'];
						$price     = $cart_row['price'] * $cart_row['quantity'];
						$point     = (float) sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
					}
				}
			} elseif ( isset( $condition['campaign_privilege'] ) && 'point' === $condition['campaign_privilege'] ) {
				foreach ( $cart as $cart_row ) {
					$product = wel_get_product( $cart_row['post_id'] );
					$rate    = (float) $product['itemPointrate'];
					$price   = $cart_row['price'] * $cart_row['quantity'];
					$cats    = $usces->get_post_term_ids( $cart_row['post_id'], 'category' );
					if ( in_array( $condition['campaign_category'], $cats ) ) {
						$point = sprintf( '%.3f', $point + ( $price * $rate / 100 * (float) $condition['privilege_point'] ) );
					} else {
						$point = sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
					}
				}
			}
		} else {
			foreach ( $cart as $cart_row ) {
				$product = wel_get_product( $cart_row['post_id'] );
				$rate    = (float) $product['itemPointrate'];
				$price   = $cart_row['price'] * $cart_row['quantity'];
				$point   = sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
			}
		}

		if ( 0 < $use_point ) {
			$point = (float) sprintf( '%.3f', $point - ( $point * (int) $use_point / $total_items_price ) );
			$point = ceil( $point );
			if ( 0 > $point ) {
				$point = 0;
			}
		} else {
			if ( 0 < $point ) {
				$point = ceil( $point );
			}
		}
	}
	$point = apply_filters( 'usces_filter_set_point_recalculation', $point, $condition, $cart, $meminfo, $use_point, $order_id );

	$total_price = $total_items_price - $use_point + $discount + $shipping_charge + $cod_fee;
	if ( $total_price < 0 ) {
		$total_price = 0;
	}
	$total_price = apply_filters( 'usces_filter_set_cart_fees_total_price', $total_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee ); // Deprecated.
	$total_price = apply_filters( 'usces_filter_order_total_price_recalculation', $total_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee, $cart, $order_id );
	$materials   = compact( 'total_items_price', 'shipping_charge', 'discount', 'cod_fee', 'use_point', 'condition' );
	if ( 'activate' === $tax_display ) {
		if ( 'include' === $tax_mode ) {
			$tax         = 0;
			$include_tax = usces_internal_tax( $materials, 'return' );
		} else {
			// $tax = $usces->getTax( $total_price, $materials );
			if ( 1 === (int) $point_coverage ) {
				if ( 'products' === $tax_target ) {
					$total = (float) $total_items_price + (float) $discount;
				} else {
					$total = (float) $total_items_price + (float) $discount + (float) $shipping_charge + (float) $cod_fee;
				}
			} else {
				if ( 'products' === $tax_target ) {
					$total = (float) $total_items_price + (float) $discount;
				} else {
					if ( empty( $use_point ) ) {
						$use_point = 0;
					}
					$total = (float) $total_items_price + (float) $discount - (int) $use_point + (float) $shipping_charge + (float) $cod_fee;
				}
			}
			$tax         = (float) sprintf( '%.3f', (float) $total * (float) $condition['tax_rate'] / 100 );
			$tax         = usces_tax_rounding_off( $tax, $condition['tax_method'] );
			$include_tax = 0;
		}
	} else {
		$tax         = 0;
		$include_tax = 0;
	}
	$total_full_price = $total_price + $tax;
	$total_full_price = apply_filters( 'usces_filter_set_cart_fees_total_full_price', $total_full_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee ); // Deprecated.
	$total_full_price = apply_filters( 'usces_filter_order_total_full_price_recalculation', $total_full_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee, $cart, $order_id );

	$data['status']           = $res;
	$data['tax_mode']         = 'standard';
	$data['discount']         = $discount;
	$data['tax']              = usces_crform( $tax, false, false, 'return', false );
	$data['include_tax']      = ( 0 < $include_tax ) ? '(' . usces_crform( $include_tax, false, false, 'return', true ) . ')' : '';
	$data['point']            = $point;
	$data['total_full_price'] = usces_crform( $total_full_price, false, false, 'return', false );
	wp_send_json( $data );
}

/**
 * Order amount recalculation ( for Reduced tax rate )
 *
 * @param int    $order_id Order ID.
 * @param int    $mem_id Member ID.
 * @param int    $post_id Post ID.
 * @param float  $price Price.
 * @param int    $quant Quantity.
 * @param array  $cart_id Cart ID.
 * @param int    $use_point Use point.
 * @param float  $shipping_charge Shipping charge.
 * @param float  $cod_fee Fee.
 * @param float  $discount_standard Standard tax rate discount.
 * @param float  $discount_reduced Reduced tax rate discount.
 * @param string $change_taxrate Change applicable tax rate.
 * @return void
 */
function usces_order_recalculation_reduced( $order_id, $mem_id, $post_id, $price, $quant, $cart_id, $use_point, $shipping_charge, $cod_fee, $discount_standard = 0, $discount_reduced = 0, $change_taxrate = '' ) {
	global $usces;

	$usces_tax = Welcart_Tax::get_instance();

	$data = array();
	$res  = 'ok';

	if ( 'change' === $change_taxrate ) {
		$condition = $usces->get_condition();
		$usces_tax->set_order_condition_reduced_taxrate( $order_id );
	} else {
		if ( ! empty( $order_id ) ) {
			$condition = usces_get_order_condition( $order_id );
		} else {
			$condition = $usces->get_condition();
		}
	}

	$tax_rate            = ( isset( $condition['tax_rate'] ) ) ? (float) $condition['tax_rate'] : (float) $usces->options['tax_rate'];
	$tax_rate_reduced    = ( isset( $condition['tax_rate_reduced'] ) ) ? (float) $condition['tax_rate_reduced'] : (float) $usces->options['tax_rate_reduced'];
	$tax_display         = ( isset( $condition['tax_display'] ) ) ? $condition['tax_display'] : usces_get_tax_display();
	$member_system       = ( isset( $condition['membersystem_state'] ) ) ? $condition['membersystem_state'] : $usces->options['membersystem_state'];
	$member_system_point = ( isset( $condition['membersystem_point'] ) ) ? $condition['membersystem_point'] : $usces->options['membersystem_point'];
	$tax_mode            = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : usces_get_tax_mode();
	$tax_target          = ( isset( $condition['tax_target'] ) ) ? $condition['tax_target'] : usces_get_tax_target();
	$point_coverage      = ( isset( $condition['point_coverage'] ) ) ? $condition['point_coverage'] : usces_point_coverage();

	$cart          = array();
	$post_id_count = ( is_array( $post_id ) ) ? count( $post_id ) : 0;
	for ( $i = 0; $i < $post_id_count; $i++ ) {
		if ( $post_id[ $i ] ) {
			$ordercart          = usces_get_ordercartdata_row( $cart_id[ $i ] );
			$applicable_taxrate = ( 'change' === $change_taxrate ) ? $usces_tax->get_sku_applicable_taxrate( $post_id[ $i ], $ordercart['sku_code'] ) : $usces_tax->get_ordercart_applicable_taxrate( $cart_id[ $i ], $post_id[ $i ], $ordercart['sku_code'] );
			$cart[]             = array(
				'post_id'  => $post_id[ $i ],
				'sku_code' => $ordercart['sku_code'],
				'price'    => (float) $price[ $i ],
				'quantity' => (float) $quant[ $i ],
				'taxrate'  => $applicable_taxrate,
			);
		}
	}

	$subtotal_standard = 0;
	$subtotal_reduced  = 0;
	foreach ( $cart as $cart_row ) {
		if ( 'reduced' === $cart_row['taxrate'] ) {
			$subtotal_reduced += (float) $cart_row['price'] * (float) $cart_row['quantity'];
		} else {
			$subtotal_standard += (float) $cart_row['price'] * (float) $cart_row['quantity'];
		}
	}
	$total_items_price = $subtotal_standard + $subtotal_reduced;
	$meminfo           = $usces->get_member_info( $mem_id );

	if ( 'change' === $change_taxrate ) {
		$discount          = 0;
		$discount_standard = 0;
		$discount_reduced  = 0;
		if ( isset( $condition['display_mode'] ) && 'Promotionsale' === $condition['display_mode'] ) {
			if ( isset( $condition['campaign_privilege'] ) && 'discount' === $condition['campaign_privilege'] ) {
				if ( 0 === (int) $condition['campaign_category'] ) {
					$discount_standard = (float) sprintf( '%.3f', (float) $subtotal_standard * (float) $condition['privilege_discount'] / 100 );
					$discount_reduced  = (float) sprintf( '%.3f', (float) $subtotal_reduced * (float) $condition['privilege_discount'] / 100 );
				} else {
					foreach ( $cart as $cart_row ) {
						if ( in_category( (int) $condition['campaign_category'], $cart_row['post_id'] ) ) {
							$items_discount = (float) sprintf( '%.3f', (float) $cart_row['price'] * (float) $cart_row['quantity'] * (float) $condition['privilege_discount'] / 100 );
							if ( 'reduced' === $cart_row['taxrate'] ) {
								$discount_reduced += $items_discount;
							} else {
								$discount_standard += $items_discount;
							}
						}
					}
				}
				if ( 0 != $discount_standard || 0 != $discount_reduced ) {
					$decimal = $usces->get_currency_decimal();
					if ( 0 == $decimal ) {
						$discount_standard = ceil( $discount_standard );
						$discount_reduced  = ceil( $discount_reduced );
					} else {
						$decipad           = (int) str_pad( '1', $decimal + 1, '0', STR_PAD_RIGHT );
						$discount_standard = ceil( $discount_standard * $decipad ) / $decipad;
						$discount_reduced  = ceil( $discount_reduced * $decipad ) / $decipad;
					}
					$discount_standard *= -1;
					$discount_reduced  *= -1;
					$discount           = $discount_standard + $discount_reduced;
				}
			}
		}
	}
	$discount = $discount_standard + $discount_reduced;
	$discount = apply_filters( 'usces_filter_order_discount_recalculation', $discount, $cart, $condition, $order_id );

	$point = 0;
	if ( empty( $use_point ) || 'NaN' == $use_point ) {
		$use_point = 0;
	}
	if ( 'activate' === $member_system && 'activate' === $member_system_point && ! empty( $meminfo['ID'] ) ) {
		if ( isset( $condition['display_mode'] ) && 'Promotionsale' === $condition['display_mode'] ) {
			if ( isset( $condition['campaign_privilege'] ) && 'discount' === $condition['campaign_privilege'] ) {
				foreach ( $cart as $cart_row ) {
					$cats = $usces->get_post_term_ids( $cart_row['post_id'], 'category' );
					if ( ! in_array( $condition['campaign_category'], $cats ) ) {
						$product = wel_get_product( $cart_row['post_id'] );
						$rate    = (float) $product['itemPointrate'];
						$price   = $cart_row['price'] * $cart_row['quantity'];
						$point   = (float) sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
					}
				}
			} elseif ( isset( $condition['campaign_privilege'] ) && 'point' === $condition['campaign_privilege'] ) {
				foreach ( $cart as $cart_row ) {
					$product = wel_get_product( $cart_row['post_id'] );
					$rate    = (float) $product['itemPointrate'];
					$price   = $cart_row['price'] * $cart_row['quantity'];
					$cats    = $usces->get_post_term_ids( $cart_row['post_id'], 'category' );
					if ( in_array( $condition['campaign_category'], $cats ) ) {
						$point = sprintf( '%.3f', $point + ( $price * $rate / 100 * (float) $condition['privilege_point'] ) );
					} else {
						$point = sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
					}
				}
			}
		} else {
			foreach ( $cart as $cart_row ) {
				$product = wel_get_product( $cart_row['post_id'] );
				$rate    = (float) $product['itemPointrate'];
				$price   = $cart_row['price'] * $cart_row['quantity'];
				$point   = sprintf( '%.3f', $point + ( $price * $rate / 100 ) );
			}
		}

		if ( 0 < $use_point ) {
			$point = (float) sprintf( '%.3f', $point - ( $point * (int) $use_point / $total_items_price ) );
			$point = ceil( $point );
			if ( 0 > $point ) {
				$point = 0;
			}
		} else {
			if ( 0 < $point ) {
				$point = ceil( $point );
			}
		}
	}
	$point = apply_filters( 'usces_filter_set_point_recalculation', $point, $condition, $cart, $meminfo, $use_point, $order_id );

	$total_price = $total_items_price - $use_point + $discount + $shipping_charge + $cod_fee;
	if ( $total_price < 0 ) {
		$total_price = 0;
	}
	$total_price = apply_filters( 'usces_filter_set_cart_fees_total_price', $total_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee ); // Deprecated.
	$total_price = apply_filters( 'usces_filter_order_total_price_recalculation', $total_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee, $cart, $order_id );

	$tax          = 0;
	$tax_standard = 0;
	$tax_reduced  = 0;
	$include_tax  = 0;
	if ( 'activate' === $tax_display ) {
		if ( 'all' === $tax_target ) {
			if ( 0 < $shipping_charge ) {
				$subtotal_standard += (float) $shipping_charge;
			}
			if ( 0 < $cod_fee ) {
				$subtotal_standard += (float) $cod_fee;
			}
		}

		if ( 'include' === $tax_mode ) {
			if ( 0 < $subtotal_standard ) {
				$tax_standard = (float) sprintf( '%.3f', ( (float) $subtotal_standard + (float) $discount_standard ) * $tax_rate / ( 100 + $tax_rate ) );
			}
			if ( 0 < $subtotal_reduced ) {
				$tax_reduced = (float) sprintf( '%.3f', ( (float) $subtotal_reduced + (float) $discount_reduced ) * $tax_rate_reduced / ( 100 + $tax_rate_reduced ) );
			}
		} else {
			if ( 0 < $subtotal_standard ) {
				$tax_standard = (float) sprintf( '%.3f', ( (float) $subtotal_standard + (float) $discount_standard ) * $tax_rate / 100 );
			}
			if ( 0 < $subtotal_reduced ) {
				$tax_reduced = (float) sprintf( '%.3f', ( (float) $subtotal_reduced + (float) $discount_reduced ) * $tax_rate_reduced / 100 );
			}
		}

		$tax_standard = usces_tax_rounding_off( $tax_standard, $condition['tax_method'] );
		$tax_reduced  = usces_tax_rounding_off( $tax_reduced, $condition['tax_method'] );

		$materials = compact( 'total_items_price', 'shipping_charge', 'discount', 'cod_fee', 'use_point', 'cart' );
		if ( 'include' === $tax_mode ) {
			$include_tax      = $tax_standard + $tax_reduced;
			$total_full_price = $total_price;
		} else {
			$tax              = apply_filters( 'usces_filter_order_tax_recalculation', $tax_standard + $tax_reduced, $materials );
			$total_full_price = $total_price + $tax;
		}
	} else {
		$total_full_price = $total_price;
	}
	$total_full_price = apply_filters( 'usces_filter_set_cart_fees_total_full_price', $total_full_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee ); // Deprecated.
	$total_full_price = apply_filters( 'usces_filter_order_total_full_price_recalculation', $total_full_price, $total_items_price, $use_point, $discount, $shipping_charge, $cod_fee, $cart, $order_id );

	$data['status']            = $res;
	$data['tax_mode']          = 'reduced';
	$data['discount']          = $discount;
	$data['tax']               = $tax;
	$data['point']             = $point;
	$data['total_full_price']  = $total_full_price;
	$data['subtotal_standard'] = $subtotal_standard;
	$data['subtotal_reduced']  = $subtotal_reduced;
	//if ( 'change' == $change_taxrate ) {
		$data['discount_standard'] = $discount_standard;
		$data['discount_reduced']  = $discount_reduced;
	//}
	$data['tax_standard'] = $tax_standard;
	$data['tax_reduced']  = $tax_reduced;
	$data['include_tax']  = $include_tax;
	wp_send_json( $data );
}

/**
 * Application of reduced tax rate
 *
 * @param array $sku SKU data.
 * @return void
 */
function usces_sku_meta_row_reduced_taxrate( $sku ) {
	global $usces;

	if ( $usces->is_reduced_taxrate() ) :
		$standard = $usces->options['tax_rate'];
		$reduced  = $usces->options['tax_rate_reduced'];
		$taxrate  = ( isset( $sku['taxrate'] ) ) ? $sku['taxrate'] : '';
		?>
		<select id="itemsku[<?php echo esc_attr( $sku['meta_id'] ); ?>][applicable_taxrate]" name="itemsku[<?php echo esc_attr( $sku['meta_id'] ); ?>][applicable_taxrate]" class="sku_applicable_taxrate" >
			<option value="standard"<?php selected( $taxrate, 'reduced' ); ?>><?php esc_html_e( 'Standard tax rate', 'usces' ); ?>(<?php echo esc_html( $standard ); ?>%)</option>
			<option value="reduced"<?php selected( $taxrate, 'reduced' ); ?>><?php esc_html_e( 'Reduced tax rate', 'usces' ); ?>(<?php echo esc_html( $reduced ); ?>%)</option>
		</select>
		<?php
	endif;
}

/**
 * Application of reduced tax rate
 */
function usces_newsku_meta_row_reduced_taxrate() {
	global $usces;

	if ( $usces->is_reduced_taxrate() ) :
		$standard = $usces->options['tax_rate'];
		$reduced  = $usces->options['tax_rate_reduced'];
		?>
		<select id="newsku_applicable_taxrate" name="newsku_applicable_taxrate" class="newsku_applicable_taxrate" >
			<option value="standard"><?php esc_html_e( 'Standard tax rate', 'usces' ); ?>(<?php echo esc_html( $standard ); ?>%)</option>
			<option value="reduced"><?php esc_html_e( 'Reduced tax rate', 'usces' ); ?>(<?php echo esc_html( $reduced ); ?>%)</option>
		</select>
		<?php
	endif;
}

/**
 * When deleting product data from the trash bin, also delete "_item", "_sku", and "_opt".
 *
 * @param int $post_id Post ID.
 */
function usces_delete_all_item_data( $post_id ) {
	$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS );

	if ( ! empty( $action ) && 'delete' === $action && usces_is_item( $post_id ) ) {
		wel_delete_all_sku_data( $post_id );
		wel_delete_all_opt_data( $post_id );
		wel_delete_item_data( $post_id );
	}
}
