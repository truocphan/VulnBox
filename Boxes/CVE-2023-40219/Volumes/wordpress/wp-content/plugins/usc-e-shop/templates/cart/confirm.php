<?php
/**
 * Confirm page template
 *
 * @package Welcart
 */

global $usces_entries, $usces_carts, $usces_members;

usces_get_members();
usces_get_entries();
usces_get_carts();

$html = '<div id="info-confirm">

	<div class="usccart_navi">
	<ol class="ucart">
	<li class="ucart usccart">' . esc_html__( '1.Cart', 'usces' ) . '</li>
	<li class="ucart usccustomer">' . esc_html__( '2.Customer Info', 'usces' ) . '</li>
	<li class="ucart uscdelivery">' . esc_html__( '3.Deli. & Pay.', 'usces' ) . '</li>
	<li class="ucart uscconfirm usccart_confirm">' . esc_html__( '4.Confirm', 'usces' ) . '</li>
	</ol>
	</div>';

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_confirm_page_header', $header );
$html  .= '</div>';
$html  .= '<div class="error_message">' . $this->error_message . '</div>';

$confirm_table_head = '<div id="cart">
<div class="currency_code">' . esc_html__( 'Currency', 'usces' ) . ' : ' . __( usces_crcode( 'return' ), 'usces') . '</div>
<table cellspacing="0" id="cart_table">
		<thead>
		<tr>
			<th scope="row" class="num">' . esc_html__( 'No.', 'usces' ) . '</th>
			<th class="thumbnail">&nbsp;&nbsp;</th>
			<th class="productname">' . esc_html__( 'Items', 'usces' ) . '</th>
			<th class="unitprice">' . esc_html__( 'Unit price', 'usces' ) . '</th>
			<th class="quantity">' . esc_html__( 'Quantity', 'usces' ) . '</th>
			<th class="subtotal">' . esc_html__( 'Amount', 'usces' ) . '</th>
			<th class="action"></th>
		</tr>
		</thead>
		<tbody>';
$html              .= apply_filters( 'usces_filter_confirm_table_head', $confirm_table_head );

$member = $this->get_member();

$html .= usces_get_confirm_rows( 'return' );

$confirm_table_footer = '</tbody>
	<tfoot>
	<tr class="total_items_price">
		<th class="num">&nbsp;</th>
		<th class="thumbnail">&nbsp;</th>
		<th colspan="3" class="aright totallabel">' . esc_html__( 'total items', 'usces' ) . '</th>
		<th class="aright totalend">' . usces_crform( $usces_entries['order']['total_items_price'], true, false, 'return' ) . '</th>
		<th class="action">&nbsp;</th>
	</tr>';
if ( ! empty( $usces_entries['order']['discount'] ) ) {
	$confirm_table_footer .= '<tr class="discount">
		<td class="num">&nbsp;</td>
		<td class="thumbnail">&nbsp;</td>
		<td colspan="3" class="aright totallabel">' . apply_filters( 'usces_confirm_discount_label', esc_html__( 'Campaign discount', 'usces' ) ) . '</td>
		<td class="aright totalend" style="color:#FF0000">' . usces_crform( $usces_entries['order']['discount'], true, false, 'return' ) . '</td>
		<td class="action">&nbsp;</td>
	</tr>';
}
if ( usces_is_tax_display() && 'products' === $this->options['tax_target'] ) {
	$confirm_table_footer .= '<tr class="tax">
		<td class="num">&nbsp;</td>
		<td class="thumbnail">&nbsp;</td>
		<td colspan="3" class="aright totallabel">' . usces_tax_label( array(), 'return' ) . '</td>
		<td class="aright totalend">' . usces_tax( $usces_entries, 'return' ) . '</td>
		<td class="action">&nbsp;</td>
	</tr>';
}
if ( 'activate' === $this->options['membersystem_state'] && 'activate' === $this->options['membersystem_point'] && ! empty( $usces_entries['order']['usedpoint'] ) && 0 === (int) $this->options['point_coverage'] ) {
	$confirm_table_footer .= '<tr class="usedpoint">
		<td class="num">&nbsp;</td>
		<td class="thumbnail">&nbsp;</td>
		<td colspan="3" class="aright totallabel">' . esc_html__( 'Used points', 'usces' ) . '</td>
		<td class="aright totalend" style="color:#FF0000">' . number_format( $usces_entries['order']['usedpoint'] ) . '</td>
		<td class="action">&nbsp;</td>
	</tr>';
}
$confirm_table_footer .= '<tr class="shipping_charge">
	<td class="num">&nbsp;</td>
	<td class="thumbnail">&nbsp;</td>
	<td colspan="3" class="aright totallabel">' . esc_html__( 'Shipping', 'usces' ) . '</td>
	<td class="aright totalend">' . usces_crform( $usces_entries['order']['shipping_charge'], true, false, 'return' ) . '</td>
	<td class="action">&nbsp;</td>
	</tr>';
if ( ! empty( $usces_entries['order']['cod_fee'] ) ) {
	$confirm_table_footer .= '<tr class="cod_fee">
		<td class="num">&nbsp;</td>
		<td class="thumbnail">&nbsp;</td>
		<td colspan="3" class="aright totallabel">' . apply_filters( 'usces_filter_cod_label', esc_html__( 'COD fee', 'usces' ) ) . '</td>
		<td class="aright totalend">' . usces_crform( $usces_entries['order']['cod_fee'], true, false, 'return' ) . '</td>
		<td class="action">&nbsp;</td>
	</tr>';
}
if ( usces_is_tax_display() && 'all' === $this->options['tax_target'] ) {
	$confirm_table_footer .= '<tr class="tax">
		<td class="num">&nbsp;</td>
		<td class="thumbnail">&nbsp;</td>
		<td colspan="3" class="aright totallabel">' . usces_tax_label( array(), 'return' ) . '</td>
		<td class="aright totalend">' . usces_tax( $usces_entries, 'return' ) . '</td>
		<td class="action">&nbsp;</td>
	</tr>';
}
if ( 'activate' === $this->options['membersystem_state'] && 'activate' === $this->options['membersystem_point'] && ! empty( $usces_entries['order']['usedpoint'] ) && 1 === (int) $this->options['point_coverage'] ) {
	$confirm_table_footer .= '<tr class="usedpoint">
		<td class="num">&nbsp;</td>
		<td class="thumbnail">&nbsp;</td>
		<td colspan="3" class="aright totallabel">' . esc_html__( 'Used points', 'usces' ) . '</td>
		<td class="aright totalend" style="color:#FF0000">' . number_format( $usces_entries['order']['usedpoint'] ) . '</td>
		<td class="action">&nbsp;</td>
	</tr>';
}
$confirm_table_footer .= '<tr class="total_full_price">
	<th class="num">&nbsp;</th>
	<th class="thumbnail">&nbsp;</th>
	<th colspan="3" class="aright totallabel">' . esc_html__( 'Total Amount', 'usces' ) . '</th>
	<th class="aright totalend">' . usces_crform( $usces_entries['order']['total_full_price'], true, false, 'return' ) . '</th>
	<th class="action">&nbsp;</th>
	</tr>
	</tfoot>
	</table>';
$html                 .= apply_filters( 'usces_filter_confirm_table_footer', $confirm_table_footer );
$html                 .= apply_filters( 'usces_filter_confirm_table_after', '' );

if ( 'activate' === $this->options['membersystem_state'] && 'activate' === $this->options['membersystem_point'] && $this->is_member_logged_in() ) {
	$confirm_point_table = '<form action="' . esc_url( USCES_CART_URL ) . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
		<div class="error_message">' . $this->error_message . '</div>
		<table cellspacing="0" id="point_table">
		<tr>
		<td>' . esc_html__( 'The current point', 'usces' ) . '</td>
		<td><span class="point">' . $member['point'] . '</span>pt</td>
		</tr>
		<tr>
		<td>' . esc_html__( 'Points you are using here', 'usces' ) . '</td>
		<td><input name="offer[usedpoint]" class="used_point" type="text" value="' . esc_attr( $usces_entries['order']['usedpoint'] ) . '" />pt</td>
		</tr>
		<tr>
		<td colspan="2"><input name="use_point" type="submit" class="use_point_button" value="' . esc_html__( 'Use the points', 'usces' ) . '" /></td>
		</tr>
	</table>';
	$confirm_point_table = apply_filters( 'usces_filter_confirm_point_table', $confirm_point_table );
	$html                = apply_filters( 'usces_filter_confirm_point_inform', $html . $confirm_point_table );

	$noncekey = 'use_point' . $this->get_uscesid( false );
	$html    .= wp_nonce_field( $noncekey, 'wc_nonce', true, false );
	$html    .= '</form>';
}
$html               .= apply_filters( 'usces_filter_confirm_after_form', null );
$html               .= '</div>';
$customer_info_table = '
	<table id="confirm_table">
	<tr class="ttl">
	<td colspan="2"><h3>' . esc_html__( 'Customer Information', 'usces' ) . '</h3></td>
	</tr>
	<tr>
	<th>' . esc_html__( 'e-mail adress', 'usces' ) . '</th>
	<td>' . esc_html( $usces_entries['customer']['mailaddress1'] ) . '</td>
	</tr>';

$customer_info_table .= uesces_addressform( 'confirm', $usces_entries );

$customer_info_table .= '<tr>';
$customer_info_table .= '<td class="ttl" colspan="2"><h3>' . esc_html__( 'Others', 'usces' ) . '</h3></td>
	</tr>';
$shipping_info        = '<tr>
	<th>' . esc_html__( 'shipping option', 'usces' ) . '</th><td>' . esc_html( usces_delivery_method_name( $usces_entries['order']['delivery_method'], 'return' ) ) . '</td>
	</tr>
	<tr>
	<th>' . esc_html__( 'Delivery date', 'usces' ) . '</th><td>' . esc_html( $usces_entries['order']['delivery_date'] ) . '</td>
	</tr>
	<tr class="bdc">
	<th>' . esc_html__( 'Delivery Time', 'usces' ) . '</th><td>' . esc_html( $usces_entries['order']['delivery_time'] ) . '</td>
	</tr>';
$customer_info_table .= apply_filters( 'usces_filter_confirm_shipping_info', $shipping_info );

$customer_info_table .= '<tr>
	<th>' . esc_html__( 'payment method', 'usces' ) . '</th><td>' . esc_html( $usces_entries['order']['payment_name'] . usces_payment_detail( $usces_entries ) ) . '</td>
	</tr>';
$customer_info_table .= usces_custom_field_info( $usces_entries, 'order', '', 'return' );
$customer_info_table .= '<tr>
	<th>' . esc_html__( 'Notes', 'usces' ) . '</th><td>' . nl2br( esc_html( $usces_entries['order']['note'] ) ) . '</td>
	</tr>';
$customer_info_table .= '</table>';
$html                .= apply_filters( 'usces_filter_confirm_customer_info_table', $customer_info_table );
$html                .= apply_filters( 'usces_filter_confirm_page_notes', '' );

require USCES_PLUGIN_DIR . '/includes/purchase_button.php';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_confirm_page_footer', $footer );
$html  .= '</div>';

$html .= '</div>';
