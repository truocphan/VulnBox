<?php
/**
 * Customer info page template
 *
 * @package Welcart
 */

$usces_entries = $this->cart->get_entry();
global $member_regmode;
$member_regmode = isset( $_SESSION['usces_entry']['member_regmode'] ) ? $_SESSION['usces_entry']['member_regmode'] : 'none';

$html = '<div id="customer-info">

<div class="usccart_navi">
<ol class="ucart">
<li class="ucart usccart">' . esc_html__( '1.Cart', 'usces' ) . '</li>
<li class="ucart usccustomer usccart_customer">' . esc_html__( '2.Customer Info', 'usces' ) . '</li>
<li class="ucart uscdelivery">' . esc_html__( '3.Deli. & Pay.', 'usces' ) . '</li>
<li class="ucart uscconfirm">' . esc_html__( '4.Confirm', 'usces' ) . '</li>
</ol>
</div>';

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_customer_page_header', $header );
$html  .= '</div>';

$html .= '<div class="error_message">' . $this->error_message . '</div>';

if ( usces_is_membersystem_state() ) {
	$html .= '<h5>' . esc_html__( 'The member please enter at here.', 'usces' ) . '</h5>
	<form action="' . esc_url( USCES_CART_URL ) . '" method="post" name="customer_loginform" onKeyDown="if (event.keyCode == 13) {return false;}">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="customer_form">
	<tr>
	<th scope="row">' . esc_html__( 'e-mail adress', 'usces' ) . '</th>
	<td><input name="loginmail" id="loginmail" type="text" value="' . esc_attr( $usces_entries['customer']['mailaddress1'] ) . '" /></td>
	</tr>
	<tr>
	<th scope="row">' . esc_html__( 'password', 'usces' ) . '</th>
	<td><input name="loginpass" id="loginpass" type="password" value="" autocomplete="new-password" /></td>
	</tr>
	</table>
	<div class="send"><input name="customerlogin" type="submit" value="' . esc_html__( ' Next ', 'usces' ) . '" /></div>';
	$html  = apply_filters( 'usces_filter_customer_page_member_inform', $html, $usces_entries );

	$noncekey = 'post_member' . $this->get_uscesid( false );
	$html    .= wp_nonce_field( $noncekey, 'wel_nonce', true, false );
	$html    .= '</form>
	<h5>' . esc_html__( 'The nonmember please enter at here.', 'usces' ) . '</h5>';
}

$html .= '<form action="' . esc_url( USCES_CART_URL ) . '" method="post" name="customer_form" onKeyDown="if (event.keyCode == 13) {return false;}">
<table border="0" cellpadding="0" cellspacing="0" class="customer_form">
<tr>
<th scope="row"><em>' . esc_html__( '*', 'usces' ) . '</em>' . esc_html__( 'e-mail adress', 'usces' ) . '</th>';

$html .= '<td colspan="2"><input name="customer[mailaddress1]" id="mailaddress1" type="text" value="' . esc_attr( $usces_entries['customer']['mailaddress1'] ) . '" /></td>
</tr>
<tr>
<th scope="row"><em>' . esc_html__( '*', 'usces' ) . '</em>' . esc_html__( 'e-mail adress', 'usces' ) . '(' . esc_html__( 'Re-input', 'usces' ) . ')</th>
<td colspan="2"><input name="customer[mailaddress2]" id="mailaddress2" type="text" value="' . esc_attr( $usces_entries['customer']['mailaddress2'] ) . '" /></td>
</tr>';

if ( usces_is_membersystem_state() ) {
	$html .= '<tr><th scope="row">';
	if ( 'editmemberfromcart' === $member_regmode ) {
		$html .= '<em>' . esc_html__( '*', 'usces' ) . '</em>';
	}
	$html .= esc_html__( 'password', 'usces' ) . '</th>
	<td colspan="2"><input name="customer[password1]" style="width:100px" type="password" value="' . esc_attr( $usces_entries['customer']['password1'] ) . '" autocomplete="new-password" />';
	if ( 'editmemberfromcart' !== $member_regmode ) {
		$html .= esc_html__( 'When you enroll newly, please fill it out.', 'usces' );
	}
	$html .= '</td></tr>';
	$html .= '<tr><th scope="row">';
	if ( 'editmemberfromcart' === $member_regmode ) {
		$html .= '<em>' . esc_html__( '*', 'usces' ) . '</em>';
	}
	$html .= esc_html__( 'Password (confirm)', 'usces' ) . '</th>
	<td colspan="2"><input name="customer[password2]" style="width:100px" type="password" value="' . esc_attr( $usces_entries['customer']['password2'] ) . '" autocomplete="new-password" />';
	if ( 'editmemberfromcart' !== $member_regmode ) {
		$html .= esc_html__( 'When you enroll newly, please fill it out.', 'usces' );
	}
	$html .= '</td></tr>';
}

$html .= uesces_addressform( 'customer', $usces_entries );

$html    .= '</table>
<input name="member_regmode" type="hidden" value="' . $member_regmode . '" />
<input name="member_id" type="hidden" value="' . usces_memberinfo( 'ID', 'return' ) . '" />
<div class="send">';
$html    .= usces_get_customer_button( 'return' );
$html    .= '</div>';
$html    .= usces_agree_member_field( 'return' );
$noncekey = 'post_member' . $this->get_uscesid( false );
$html    .= wp_nonce_field( $noncekey, 'wc_nonce', true, false );
$html    .= apply_filters( 'usces_filter_customer_inform', null );
$html    .= '</form>';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_customer_page_footer', $footer );
$html  .= '</div>';
$html  .= '</div>';
