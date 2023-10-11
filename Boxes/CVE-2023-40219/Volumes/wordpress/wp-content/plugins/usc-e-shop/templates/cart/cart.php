<?php
/**
 * Cart page template
 *
 * @package Welcart
 */

global $usces_gp;

$html = '<div id="inside-cart">

<div class="usccart_navi">
<ol class="ucart">
<li class="ucart usccart usccart_cart">' . esc_html__( '1.Cart', 'usces' ) . '</li>
<li class="ucart usccustomer">' . esc_html__( '2.Customer Info', 'usces' ) . '</li>
<li class="ucart uscdelivery">' . esc_html__( '3.Deli. & Pay.', 'usces' ) . '</li>
<li class="ucart uscconfirm">' . esc_html__( '4.Confirm', 'usces' ) . '</li>
</ol>
</div>';

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_cart_page_header', $header );
$html  .= '</div>';

$html .= '<div class="error_message">' . $this->error_message . '</div>

<form action="' . esc_url( USCES_CART_URL ) . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">';

if ( usces_is_cart() ) {

	$html .= '<div id="cart">';

	$button = '<div class="upbutton">' . esc_html__( 'Press the `update` button when you change the amount of items.', 'usces' ) . '<input name="upButton" type="submit" value="' . esc_html__( 'Quantity renewal', 'usces' ) . '" onclick="return uscesCart.upCart()"  /></div>';
	$html  .= apply_filters( 'usces_filter_cart_upbutton', $button );

	$cart_table_head = '<table cellspacing="0" id="cart_table">
		<thead>
		<tr>
			<th scope="row" class="num">No.</th>
			<th class="thumbnail"> </th>
			<th class="productname">' . esc_html__( 'item name', 'usces' ) . '</th>
			<th class="unitprice">' . esc_html__( 'Unit price', 'usces' ) . '</th>
			<th class="quantity">' . esc_html__( 'Quantity', 'usces' ) . '</th>
			<th class="subtotal">' . esc_html__( 'Amount', 'usces' ) . usces_guid_tax( 'return' ) . '</th>
			<th class="stock">' . esc_html__( 'stock status', 'usces' ) . '</th>
			<th class="action"> </th>
		</tr>
		</thead>
		<tbody>';
	$html           .= apply_filters( 'usces_filter_cart_table_head', $cart_table_head );

	$html .= usces_get_cart_rows( 'return' );

	$cart_table_footer = '</tbody>
		<tfoot>
		<tr>
			<th class="num">&nbsp;</th>
			<th class="thumbnail">&nbsp;</th>
			<th colspan="3" scope="row" class="aright">' . esc_html__( 'total items', 'usces' ) . usces_guid_tax( 'return' ) . '</th>
			<th class="aright subtotal">' . usces_crform( $this->get_total_price(), true, false, 'return' ) . '</th>
			<th class="stock">&nbsp;</th>
			<th class="action">&nbsp;</th>
		</tr>
		</tfoot>
	</table>';
	$html             .= apply_filters( 'usces_filter_cart_table_footer', $cart_table_footer );

	$after_table = '<div class="currency_code">' . esc_html__( 'Currency', 'usces' ) . ' : ' . __( usces_crcode( 'return' ), 'usces' ) . '</div>';
	$html       .= apply_filters( 'usces_filter_after_cart_table', $after_table );

	if ( isset( $usces_gp ) && $usces_gp ) {
		$gp_src                 = file_exists( get_template_directory() . '/images/gp.gif' ) ? get_template_directory_uri() . '/images/gp.gif' : USCES_PLUGIN_URL . '/images/gp.gif';
		$business_pack_discount = '<div class="gp_exp"><img src="' . $gp_src . '" alt="' . esc_html__( 'Business package discount', 'usces' ) . '" /><br />' . esc_html__( 'The price with this mark applys to Business pack discount.', 'usces' ) . '</div>';
		$html                  .= apply_filters( 'usces_filter_itemGpExp_cart_message', $business_pack_discount );
	}
	$html .= '</div><!-- end of cart -->';

} else {
	$html .= '<div class="no_cart">' . esc_html__( 'There are no items in your cart.', 'usces' ) . '</div>';
}

$html .= '<div class="send">';
$html .= usces_get_cart_button( 'return' );
$html .= '</div>';
$html  = apply_filters( 'usces_filter_cart_inform', $html );
$html .= '</form>';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_cart_page_footer', $footer );
$html  .= '</div>';

$html .= '</div>';
