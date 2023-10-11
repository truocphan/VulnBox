<?php
/**
 * Member Edit Screen
 *
 * @package Welcart
 */

if ( 'new' === $member_action ) {
	$page         = 'usces_membernew';
	$oa           = 'newpost';
	$member_id    = null;
	$member_metas = array();
	$post_data    = wp_unslash( $_POST );
	$data         = array(
		'ID'                => '',
		'mem_email'         => ( isset( $post_data['member']['email'] ) ) ? $post_data['member']['email'] : '',
		'mem_pass'          => ( isset( $post_data['member']['password'] ) ) ? $post_data['member']['password'] : '',
		'mem_status'        => ( isset( $post_data['member']['status'] ) ) ? $post_data['member']['status'] : 0,
		'mem_cookie'        => '',
		'mem_point'         => ( isset( $post_data['member']['point'] ) ) ? $post_data['member']['point'] : 0,
		'mem_name1'         => ( isset( $post_data['member']['name1'] ) ) ? $post_data['member']['name1'] : '',
		'mem_name2'         => ( isset( $post_data['member']['name2'] ) ) ? $post_data['member']['name2'] : '',
		'mem_name3'         => ( isset( $post_data['member']['name3'] ) ) ? $post_data['member']['name3'] : '',
		'mem_name4'         => ( isset( $post_data['member']['name4'] ) ) ? $post_data['member']['name4'] : '',
		'mem_zip'           => ( isset( $post_data['member']['zipcode'] ) ) ? usces_convert_zipcode( $post_data['member']['zipcode'] ) : '',
		'mem_pref'          => ( isset( $post_data['member']['pref'] ) ) ? $post_data['member']['pref'] : '',
		'mem_address1'      => ( isset( $post_data['member']['address1'] ) ) ? $post_data['member']['address1'] : '',
		'mem_address2'      => ( isset( $post_data['member']['address2'] ) ) ? $post_data['member']['address2'] : '',
		'mem_address3'      => ( isset( $post_data['member']['address3'] ) ) ? $post_data['member']['address3'] : '',
		'mem_tel'           => ( isset( $post_data['member']['tel'] ) ) ? $post_data['member']['tel'] : '',
		'mem_fax'           => ( isset( $post_data['member']['fax'] ) ) ? $post_data['member']['fax'] : '',
		'mem_delivery_flag' => '',
		'mem_delivery'      => '',
		'mem_registered'    => '',
		'mem_nicename'      => '',
	);

	$usces_member_history = array();

	$csmb_meta = usces_has_custom_field_meta( 'member' );
	if ( is_array( $csmb_meta ) ) {
		$keys = array_keys( $csmb_meta );
		foreach ( $keys as $key ) {
			if ( isset( $post_data['custom_member'][ $key ] ) ) {
				$csmb_meta[ $key ]['data'] = $post_data['custom_member'][ $key ];
			}
		}
	}
	$admb_meta = usces_has_custom_field_meta( 'admin_member' );
	if ( is_array( $admb_meta ) ) {
		$keys = array_keys( $admb_meta );
		foreach ( $keys as $key ) {
			if ( isset( $post_data['admin_custom_member'][ $key ] ) ) {
				$admb_meta[ $key ]['data'] = $post_data['admin_custom_member'][ $key ];
			}
		}
	}
	$navibutton = '';
} else {
	$page         = 'usces_memberlist';
	$oa           = 'editpost';
	$member_id    = wp_unslash( $_REQUEST['member_id'] );
	$member_metas = $this->get_member_meta( $member_id );
	if ( ! $member_metas ) {
		$member_metas = array();
	}
	ksort( $member_metas );
	global $wpdb;

	$member_table = usces_get_tablename( 'usces_member' );
	$query        = $wpdb->prepare( "SELECT * FROM $member_table WHERE `ID` = %d", $member_id );
	$data         = $wpdb->get_row( $query, ARRAY_A );

	$usces_member_history = $this->get_member_history( $member_id );
	if ( ! $usces_member_history ) {
		$usces_member_history = array();
	}
	$csmb_meta = usces_has_custom_field_meta( 'member' );
	if ( is_array( $csmb_meta ) ) {
		$keys = array_keys( $csmb_meta );
		foreach ( $keys as $key ) {
			$csmb_meta[ $key ]['data'] = maybe_unserialize( $this->get_member_meta_value( 'csmb_' . $key, $member_id ) );
		}
	}
	$admb_meta = usces_has_custom_field_meta( 'admin_member' );
	if ( is_array( $admb_meta ) ) {
		$keys = array_keys( $admb_meta );
		foreach ( $keys as $key ) {
			$admb_meta[ $key ]['data'] = maybe_unserialize( $this->get_member_meta_value( 'admb_' . $key, $member_id ) );
		}
	}

	$exopt = get_option( 'usces_ex', array() );
	if ( isset( $exopt['system']['datalistup']['memberlist_flag'] ) && $exopt['system']['datalistup']['memberlist_flag'] ) {
		$navibutton  = '';
		$navibutton .= '<a javascript:; class="prev-page"  style="display:none"><span class="dashicons dashicons-arrow-left-alt2"></span>' . __( 'to prev page', 'usces' ) . '</a>';
		$navibutton .= '<a href="' . admin_url( 'admin.php?page=usces_memberlist&returnList=1' ) . '" class="back-list"><span class="dashicons dashicons-list-view"></span>' . __( 'to member list', 'usces' ) . '</a>';
		$navibutton .= '<a javascript:; class="next-page" style="display:none">' . __( 'to next page', 'usces' ) . '<span class="dashicons dashicons-arrow-right-alt2"></span></a>';
	} else {
		$navibutton = '<a href="' . admin_url( 'admin.php?page=usces_memberlist&returnList=1' ) . '" class="back-list"><span class="dashicons dashicons-list-view"></span>' . __( 'to member list', 'usces' ) . '</a>';
	}
}

$mem_registered = ( ! empty( $data['mem_registered'] ) ) ? sprintf( __( '%2$s %3$s, %1$s', 'usces' ), substr( $data['mem_registered'], 0, 4 ), substr( $data['mem_registered'], 5, 2 ), substr( $data['mem_registered'], 8, 2 ) ) : '';

$curent_url = urlencode( esc_url( USCES_ADMIN_URL . '?' . $_SERVER['QUERY_STRING'] ) );
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	// load show nav prev, next link.
	var sub_uri_link = '<?php echo esc_url_raw( USCES_ADMIN_URL ) . '?page=usces_memberlist&member_action=edit&member_id='; ?>';
	welPageNav.checkShowNextprevPage( 'wel_member_current_page_ids', sub_uri_link, <?php echo esc_attr( $member_id ); ?> );
});
function addComma(str) {
	cnt = 0;
	n   = "";
	for (i=str.length-1; i>=0; i--) {
		n = str.charAt(i) + n;
		cnt++;
		if (((cnt % 3) == 0) && (i != 0)) n = ","+n;
	}
	return n;
};
</script>
<div class="wrap">
<div class="usces_admin">
<form action="<?php echo esc_url( USCES_ADMIN_URL . '?page=' . $page . '&member_action=' . $oa ); ?>" method="post" name="editpost">
<?php if ( 'new' === $member_action ) : ?>
<h1>Welcart Management <?php esc_html_e( 'New Membership Registration', 'usces' ); ?></h1>
<?php else : ?>
<h1>Welcart Management <?php esc_html_e( 'Edit membership data', 'usces' ); ?></h1>
<?php endif; ?>
<p class="version_info">Version <?php echo esc_html( USCES_VERSION ); ?></p>
<?php usces_admin_action_status(); ?>
<?php
if ( $navibutton ) {
	echo '<div class="edit_pagenav">' . wp_kses_post( $navibutton ) . '</div>';
}
?>
<div class="usces_tablenav usces_tablenav_top">
<div class="ordernavi"><input name="upButton" class="button button-primary" type="submit" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" /><?php esc_html_e( "When you change amount, please click 'Edit' before you finish your process.", 'usces' ); ?></div>
</div>

<div class="info_head">
<table class="mem_wrap">
<tr>
<td class="label"><?php esc_html_e( 'membership number', 'usces' ); ?></td><td class="col1"><div class="rod large short"><?php echo esc_html( $data['ID'] ); ?></div></td>
<td colspan="2" rowspan="5" class="mem_col2">
<table class="mem_info">
		<tr>
			<td class="label">e-mail</td>
			<td><input name="member[email]" type="text" class="text long" value="<?php echo esc_attr( $data['mem_email'] ); ?>" /></td>
		</tr>
<?php if ( 'new' === $member_action ) : ?>
		<tr>
			<td class="label"><?php esc_html_e( 'password', 'usces' ); ?></td>
			<td><input name="member[password]" type="text" class="text" value="<?php echo esc_attr( $data['mem_pass'] ); ?>" autocomplete="off" /></td>
		</tr>
<?php endif; ?>
<?php
uesces_get_admin_addressform( 'member', $data, $csmb_meta, 'echo' );
usces_admin_custom_field_input( $admb_meta, 'admin_member', 'fax_after' );
?>
</table>
</td>
<td colspan="2" rowspan="5" class="mem_col3">
<table class="mem_info">
<?php do_action( 'usces_action_admin_member_info', $data, $member_metas, $usces_member_history ); ?>
</table>


</td>
	</tr>
<tr>
<td class="label"><?php esc_html_e( 'Rank', 'usces' ); ?></td><td class="col1"><select name="member[status]">
<?php foreach ( (array) $this->member_status as $rk => $rv ) : ?>
	<option value="<?php echo esc_attr( $rk ); ?>"<?php selected( $data['mem_status'], $rk ); ?>><?php echo esc_html( $rv ); ?></option>
<?php endforeach; ?>
</select></td>
</tr>
<?php if ( usces_is_membersystem_point() ) : ?>
<tr>
<td class="label"><?php esc_html_e( 'current point', 'usces' ); ?></td><td class="col1"><input name="member[point]" type="text" class="text right short num" value="<?php echo esc_attr( $data['mem_point'] ); ?>" /></td>
</tr>
<?php endif; ?>
<tr>
<td class="label"><?php esc_html_e( 'Strated date', 'usces' ); ?></td><td class="col1"><div class="rod shortm"><?php echo esc_html( $mem_registered ); ?></div></td>
</tr>
<tr>
<td colspan="2"><?php do_action( 'usces_action_member_edit_form_left_blank', $member_id, $data, $csmb_meta ); ?></td>
</tr>
</table>
<?php if ( ! usces_is_membersystem_point() ) : ?>
<input name="member[point]" type="hidden" value="<?php echo esc_attr( $data['mem_point'] ); ?>" />
<?php endif; ?>
</div>
<div id="member_history">
<table>
<?php if ( 0 === count( $usces_member_history ) ) : ?>
<tr>
<td><?php esc_html_e( 'There is no purchase history for this moment.', 'usces' ); ?></td>
</tr>
<?php endif; ?>
<?php
$management_status = apply_filters( 'usces_filter_management_status', get_option( 'usces_management_status' ) );
ob_start();
foreach ( (array) $usces_member_history as $umhs ) :
	$cart        = $umhs['cart'];
	$order_id    = $umhs['ID'];
	$total_price = $umhs['total_items_price'] - $umhs['usedpoint'] + $umhs['discount'] + $umhs['shipping_charge'] + $umhs['cod_fee'] + $umhs['tax'];
	if ( $total_price < 0 ) {
		$total_price = 0;
	}
	$condition           = $umhs['condition'];
	$tax_display         = ( isset( $condition['tax_display'] ) ) ? $condition['tax_display'] : usces_get_tax_display();
	$tax_mode            = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : usces_get_tax_mode();
	$tax_target          = ( isset( $condition['tax_target'] ) ) ? $condition['tax_target'] : usces_get_tax_target();
	$tax_label           = ( 'exclude' === $tax_mode ) ? __( 'consumption tax', 'usces' ) : __( 'Internal tax', 'usces' );
	$member_system_point = ( isset( $condition['membersystem_point'] ) && 'activate' === $condition['membersystem_point'] ) ? true : usces_is_membersystem_point();
	$point_coverage      = ( isset( $condition['point_coverage'] ) ) ? $condition['point_coverage'] : usces_point_coverage();

	$value = $umhs['order_status'];

	$p_status = '';
	if ( $this->is_status( 'duringorder', $value ) ) {
		$p_status = isset( $management_status['duringorder'] ) ? esc_html( $management_status['duringorder'] ) : '';
	} elseif ( $this->is_status( 'cancel', $value ) ) {
		$p_status = isset( $management_status['cancel'] ) ? esc_html( $management_status['cancel'] ) : '';
	} elseif ( $this->is_status( 'completion', $value ) ) {
		$p_status = isset( $management_status['completion'] ) ? esc_html( $management_status['completion'] ) : '';
	} else {
		$p_status = esc_html( __( 'new order', 'usces' ) );
	}
	$p_status = apply_filters( 'usces_filter_orderlist_process_status', $p_status, $value, $management_status, $umhs['ID'] );

	$r_status = '';
	if ( $this->is_status( 'noreceipt', $value ) ) {
		$r_status = isset( $management_status['noreceipt'] ) ? esc_html( $management_status['noreceipt'] ) : '';
	} elseif ( $this->is_status( 'pending', $value ) ) {
		$r_status = isset( $management_status['pending'] ) ? esc_html( $management_status['pending'] ) : '';
	} elseif ( $this->is_status( 'receipted', $value ) ) {
		$r_status = isset( $management_status['receipted'] ) ? esc_html( $management_status['receipted'] ) : '';
	}
	$r_status = apply_filters( 'usces_filter_orderlist_receipt_status', $r_status, $value, $management_status, $umhs['ID'] );

	$shipping             = usces_have_shipped( $cart );
	$delivery_company     = '';
	$tracking_number      = '';
	$delivery_company_url = '';
	if ( $shipping ) {
		$tracking_number      = $this->get_order_meta_value( apply_filters( 'usces_filter_tracking_meta_key', 'tracking_number' ), $umhs['ID'] );
		$delivery_company     = $this->get_order_meta_value( 'delivery_company', $umhs['ID'] );
		$delivery_company_url = usces_get_delivery_company_url( $delivery_company, $tracking_number );
	}
	?>
<tr>
<table><tr>
<th class="historyrow"><?php esc_html_e( 'Purchase date', 'usces' ); ?></th>
<th class="historyrow"><?php esc_html_e( 'Order number', 'usces' ); ?></th>
<th class="historyrow"><?php esc_html_e( 'Processing status', 'usces' ); ?></th>
	<?php if ( ! empty( $r_status ) ) : ?>
<th class="historyrow"><?php esc_attr_e( 'transfer statement', 'usces' ); ?></th>
	<?php endif; ?>
<th class="historyrow"><?php esc_html_e( 'Purchase price', 'usces' ); ?></th>
<th class="historyrow"><?php echo esc_html( apply_filters( 'usces_member_discount_label', __( 'Special Price', 'usces' ), $umhs['ID'] ) ); ?></th>
	<?php if ( $tax_display && 'products' === $tax_target ) : ?>
<th class="historyrow"><?php echo esc_html( $tax_label ); ?></th>
	<?php endif; ?>
	<?php if ( $member_system_point && 0 === (int) $point_coverage ) : ?>
<th class="historyrow"><?php esc_html_e( 'Used points', 'usces' ); ?></th>
	<?php endif; ?>
<th class="historyrow"><?php esc_html_e( 'Shipping', 'usces' ); ?></th>
<th class="historyrow"><?php echo esc_html( apply_filters( 'usces_filter_member_history_cod_label', __( 'C.O.D', 'usces' ), $umhs['ID'] ) ); ?></th>
	<?php if ( $tax_display && 'all' === $tax_target ) : ?>
<th class="historyrow"><?php echo esc_html( $tax_label ); ?></th>
	<?php endif; ?>
	<?php if ( $member_system_point && 1 === (int) $point_coverage ) : ?>
<th class="historyrow"><?php esc_html_e( 'Used points', 'usces' ); ?></th>
	<?php endif; ?>
	<?php if ( $member_system_point ) : ?>
<th class="historyrow"><?php esc_html_e( 'Acquired points', 'usces' ); ?></th>
	<?php endif; ?>
	<?php if ( ! empty( $tracking_number ) ) : ?>
<th class="historyrow"><?php esc_html_e( 'Tracking number', 'usces' ); ?></th>
	<?php endif; ?>
</tr>
<tr>
<td><?php echo esc_html( $umhs['date'] ); ?></td>
<td><a href="<?php echo esc_url( USCES_ADMIN_URL ); ?>?page=usces_orderlist&order_action=edit&order_id=<?php echo esc_attr( $order_id ); ?>&usces_referer=<?php echo esc_url( $curent_url ); ?>"><?php echo esc_attr( usces_get_deco_order_id( $order_id ) ); ?></a></td>
<td><?php echo wp_kses_post( $p_status ); ?></td>
	<?php if ( ! empty( $r_status ) ) : ?>
<td><?php echo wp_kses_post( $r_status ); ?></td>
	<?php endif; ?>
<td class="rightnum"><?php usces_crform( $total_price, true, false ); ?></td>
<td class="rightnum"><?php usces_crform( $umhs['discount'], true, false ); ?></td>
	<?php if ( $tax_display && 'products' === $tax_target ) : ?>
<td class="rightnum"><?php echo esc_html( usces_order_history_tax( $umhs, $tax_mode ) ); ?></td>
	<?php endif; ?>
	<?php if ( $member_system_point && 0 === (int) $point_coverage ) : ?>
<td class="rightnum"><?php echo number_format( $umhs['usedpoint'] ); ?></td>
	<?php endif; ?>
<td class="rightnum"><?php usces_crform( $umhs['shipping_charge'], true, false ); ?></td>
<td class="rightnum"><?php usces_crform( $umhs['cod_fee'], true, false ); ?></td>
	<?php if ( $tax_display && 'all' === $tax_target ) : ?>
<td class="rightnum"><?php echo esc_html( usces_order_history_tax( $umhs, $tax_mode ) ); ?></td>
	<?php endif; ?>
	<?php if ( $member_system_point && 1 === (int) $point_coverage ) : ?>
<td class="rightnum"><?php echo number_format( $umhs['usedpoint'] ); ?></td>
<?php endif; ?>
	<?php if ( $member_system_point ) : ?>
<td class="rightnum"><?php echo number_format( $umhs['getpoint'] ); ?></td>
	<?php endif; ?>
	<?php
	if ( ! empty( $tracking_number ) ) :
		if ( ! empty( $delivery_company_url ) ) :
			?>
			<td><a href="<?php echo esc_url( $delivery_company_url ); ?>" target="_blank"><?php echo esc_html( $tracking_number ); ?></a></td>
		<?php else : ?>
			<td><?php echo esc_html( $tracking_number ); ?></td>
			<?php
		endif;
	endif;
	?>
</tr></table>
</tr>
<tr>
<td class="retail">
	<table id="retail_table">
	<tr>
	<th scope="row" class="num"><?php esc_html_e( 'No.', 'usces' ); ?></th>
	<th class="thumbnail">&nbsp;</th>
	<th><?php esc_html_e( 'Items', 'usces' ); ?></th>
	<th class="price "><?php esc_html_e( 'Unit price', 'usces' ); ?>(<?php usces_crcode(); ?>)</th>
	<th class="quantity"><?php esc_html_e( 'Quantity', 'usces' ); ?></th>
	<th class="subtotal"><?php esc_html_e( 'Amount', 'usces' ); ?>(<?php usces_crcode(); ?>)</th>
	</tr>
	<?php
	$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
	for ( $i = 0; $i < $cart_count; $i++ ) :
		$cart_row     = $cart[ $i ];
		$ordercart_id = $cart_row['cart_id'];
		$post_id      = $cart_row['post_id'];
		$sku          = urldecode( $cart_row['sku'] );
		$quantity     = $cart_row['quantity'];
		$options      = $cart_row['options'];
		$advance      = usces_get_ordercart_meta( 'advance', $ordercart_id );
		$itemCode     = $cart_row['item_code'];
		$itemName     = $cart_row['item_name'];
		$cartItemName = $this->getCartItemName_byOrder( $cart_row );
		$skuPrice     = $cart_row['price'];
		$pictid       = (int) $this->get_mainpictid( $itemCode );
		$optstr       = '';
		foreach ( (array) $options as $key => $value ) {
			if ( ! empty( $key ) ) {
				$key   = urldecode( $key );
				$value = maybe_unserialize( $value );
				if ( is_array( $value ) ) {
					$c       = '';
					$optstr .= esc_html( $key ) . ' : ';
					foreach ( $value as $v ) {
						$optstr .= $c . nl2br( esc_html( urldecode( $v ) ) );
						$c       = ', ';
					}
					$optstr .= "<br />\n";
				} else {
					$optstr .= esc_html( $key ) . ' : ' . nl2br( esc_html( urldecode( $value ) ) ) . "<br />\n";
				}
			}
		}
		$materials = compact( 'i', 'cart_row', 'post_id', 'sku', 'quantity', 'options', 'advance', 'itemCode', 'itemName', 'cartItemName', 'skuPrice', 'pictid', 'order_id' );
		$optstr    = apply_filters( 'usces_filter_member_edit_form_row', $optstr, $cart, $materials );

		$cart_item_name = apply_filters( 'usces_filter_admin_cart_item_name', $cartItemName, $materials ) . '<br />' . $optstr;
		$cart_item_name = apply_filters( 'usces_filter_admin_history_cart_item_name', $cart_item_name, $cartItemName, $optstr, $cart_row, $i );

		$cart_thumbnail = ( ! empty( $pictid ) ) ? wp_get_attachment_image( $pictid, array( 60, 60 ), true ) : usces_get_attachment_noimage( array( 60, 60 ), $itemCode );
		$cart_thumbnail = apply_filters( 'usces_filter_cart_thumbnail', $cart_thumbnail, $post_id, $pictid, $i, $cart_row );
		?>
	<tr>
	<td><?php echo esc_html( $i + 1 ); ?></td>
	<td><?php echo wp_kses_post( $cart_thumbnail ); ?></td>
	<td class="aleft"><?php echo wp_kses_post( $cart_item_name ); ?></td>
	<td class="rightnum"><?php usces_crform( $skuPrice, true, false ); ?></td>
	<td class="rightnum"><?php echo number_format( $cart_row['quantity'] ); ?></td>
	<td class="rightnum"><?php usces_crform( $skuPrice * $cart_row['quantity'], true, false ); ?></td>
	</tr>
		<?php
	endfor;
	?>
	<?php do_action( 'usces_action_admin_member_history_row', $member_id, $umhs ); ?>
	</table>
</td>
</tr>
	<?php
endforeach;
$admin_history = ob_get_contents();
ob_end_clean();
echo apply_filters( 'usces_filter_admin_history', $admin_history, $usces_member_history );
?>
</table>
</div>
<?php if( 'new' !== $member_action ) : ?>
<div class="metabox-holder wp-core-ui ">
	<?php do_meta_boxes( 'member', 'edit', $data ); ?>
</div>
<?php endif; ?> 
<?php do_action( 'usces_action_member_edit_footer' ); ?>
<input name="member_action" type="hidden" value="<?php echo esc_attr( $oa ); ?>" />
<input name="member_id" id="member_id" type="hidden" value="<?php echo esc_attr( $data['ID'] ); ?>" />
<div id="mailSendAlert" title="">
	<div id="order-response"></div>
	<fieldset>
	</fieldset>
</div>
<?php wp_nonce_field( 'post_member', 'wc_nonce' ); ?>
</form>

</div><!--usces_admin-->
</div><!--wrap-->
