<?php
/**
 * Delivery info page template
 *
 * @package Welcart
 */

global $usces_entries, $usces_carts;

usces_get_entries();
usces_get_carts();

$html = '';

$html .= '<div id="delivery-info">

	<div class="usccart_navi">
	<ol class="ucart">
	<li class="ucart usccart">' . esc_html__( '1.Cart', 'usces' ) . '</li>
	<li class="ucart usccustomer">' . esc_html__( '2.Customer Info', 'usces' ) . '</li>
	<li class="ucart uscdelivery usccart_delivery">' . esc_html__( '3.Deli. & Pay.', 'usces' ) . '</li>
	<li class="ucart uscconfirm">' . esc_html__( '4.Confirm', 'usces' ) . '</li>
	</ol>
	</div>';

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_delivery_page_header', $header );
$html  .= '</div>';

$html .= '<div class="error_message">' . $this->error_message . '</div>';

$html .= '<form action="' . esc_url( USCES_CART_URL ) . '" method="post">';

$html .= '<table class="customer_form">
	<tr>
	<th rowspan="2" scope="row">' . esc_html__( 'shipping address', 'usces' ) . '</th>
	<td><input name="delivery[delivery_flag]" type="radio" id="delivery_flag1" onclick="document.getElementById(\'delivery_table\').style.display = \'none\';" value="0"';
if ( 0 === (int) $usces_entries['delivery']['delivery_flag'] ) {
	$html .= ' checked="checked"';
}
$html .= ' onKeyDown="if (event.keyCode == 13) {return false;}" /> <label for="delivery_flag1">' . esc_html__( 'same as customer information', 'usces' ) . '</label></td>
	</tr>
	<tr>
	<td><input name="delivery[delivery_flag]" id="delivery_flag2" onclick="document.getElementById(\'delivery_table\').style.display = \'table\'" type="radio" value="1"';
if ( 1 === (int) $usces_entries['delivery']['delivery_flag'] ) {
	$html .= ' checked="checked"';
}
$html .= ' onKeyDown="if (event.keyCode == 13) {return false;}" /> <label for="delivery_flag2">' . esc_html__( 'Chose another shipping address.', 'usces' ) . '</label></td>
	</tr>
	</table>';
$html  = apply_filters( 'usces_filter_delivery_flag', $html );
$html .= '<table class="customer_form" id="delivery_table">';

$html .= uesces_addressform( 'delivery', $usces_entries );

$html                .= '</table>';
$html                .= '<table class="customer_form" id="time">';
$cart_delivery_field  = '<tr>
	<th scope="row">' . esc_html__( 'shipping option', 'usces' ) . '</th>
	<td colspan="2">' . usces_the_delivery_method( $usces_entries['order']['delivery_method'], 'return' ) . '</td>
	</tr>
	<tr>
	<th scope="row">' . esc_html__( 'Delivery date', 'usces' ) . '</th>
	<td colspan="2">' . usces_the_delivery_date( $usces_entries['order']['delivery_date'], 'return' ) . '</td>
	</tr>
	<tr>
	<th scope="row">' . esc_html__( 'Delivery Time', 'usces' ) . '</th>
	<td colspan="2">' . usces_the_delivery_time( $usces_entries['order']['delivery_time'], 'return' ) . '</td>
	</tr>';
$cart_delivery_field .= '<tr>
	<th scope="row"><em>' . esc_html__( '*', 'usces' ) . '</em>' . __( 'payment method', 'usces' ) . '</th>
	<td colspan="2">' . usces_the_payment_method( $usces_entries['order']['payment_name'], 'return' ) . '</td>
	</tr>';
$html                .= apply_filters( 'usces_filter_cart_delivery_field', $cart_delivery_field, $usces_entries );
$html                .= '</table>';

require USCES_PLUGIN_DIR . '/includes/delivery_secure_form.php';

$meta = usces_has_custom_field_meta( 'order' );
if ( ! empty( $meta ) && is_array( $meta ) ) {
	$html .= '
	<table class="customer_form" id="custom_order">';

	$html .= usces_custom_field_input( $usces_entries, 'order', '', 'return' );

	$html .= '
	</table>';
}

$entry_order_note = empty( $usces_entries['order']['note'] ) ? apply_filters( 'usces_filter_default_order_note', null ) : $usces_entries['order']['note'];
$html            .= '<table class="customer_form" id="notes_table">
	<tr>
	<th scope="row">' . esc_html__( 'Notes', 'usces' ) . '</th>
	<td colspan="2"><textarea name="offer[note]" id="note" class="notes">' . esc_html( $entry_order_note ) . '</textarea></td>
	</tr>
	</table>

	<div class="send"><input name="offer[cus_id]" type="hidden" value="" />
	<input name="backCustomer" type="submit" class="back_to_customer_button" value="' . esc_html__( 'Back', 'usces' ) . '"' . apply_filters( 'usces_filter_deliveryinfo_prebutton', null ) . ' />&nbsp;&nbsp;
	<input name="confirm" type="submit" class="to_confirm_button" value="' . esc_html__( ' Next ', 'usces' ) . '"' . apply_filters( 'usces_filter_deliveryinfo_nextbutton', null ) . ' /></div>
	</form>';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_delivery_page_footer', $footer );
$html  .= '</div>';

$html .= '</div>';
