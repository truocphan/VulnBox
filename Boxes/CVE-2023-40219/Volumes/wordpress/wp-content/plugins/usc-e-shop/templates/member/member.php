<?php
/**
 * Member page template
 *
 * @package Welcart
 */

$usces_members        = $this->get_member();
$usces_member_history = $this->get_member_history( $usces_members['ID'] );
$colspan              = usces_is_membersystem_point() ? 9 : 7;

$html = '<div id="memberpages">

<div class="whitebox">
<div id="memberinfo">
<table id="memberdetail">
<tr>
<th scope="row">' . esc_html__( 'member number', 'usces' ) . '</th>
<td class="num">' . $usces_members['ID'] . '</td>
<td rowspan="3" class="blank_cell">&nbsp;</td>
<th>' . esc_html__( 'Strated date', 'usces' ) . '</th>
<td>' . mysql2date( __( 'Y/m/d' ), $usces_members['registered'] ) . '</td>
</tr>
<tr>
<th scope="row">' . apply_filters( 'usces_filters_memberpage_name_label', esc_html__( 'Full name', 'usces' ) ) . '</th>
<td>' . sprintf( _x( '%s', 'honorific', 'usces'), esc_html( $usces_members['name1'] . ' ' . $usces_members['name2'] ) ) . '</td>';

if ( usces_is_membersystem_point() ) {
	$html .= '<th>' . esc_html__( 'The current point', 'usces' ) . '</th>
		<td class="num">' . $usces_members['point'] . '</td>';
} else {
	$html .= '<th>&nbsp;</th>
	<td class="num">&nbsp;</td>';
}
$html        .= '</tr>
	<tr>
	<th scope="row">' . esc_html__( 'e-mail adress', 'usces' ) . '</th>
	<td>' . esc_html( $usces_members['mailaddress1'] ) . '</td>';
$html_reserve = '<th class="blank">&nbsp;</th>
	<td class="blank">&nbsp;</td>';
$html        .= apply_filters( 'usces_filter_memberinfo_page_reserve', $html_reserve, $usces_members['ID'] );
$html        .= '</tr>
	</table>' . "\n";

$html        .= '<ul class="member_submenu">' . "\n";
$submenu_list = '<li class="edit_member"><a href="#edit">' . esc_html__( 'To member information editing', 'usces' ) . '</a></li>' . "\n";
$html        .= apply_filters( 'usces_filter_member_submenu_list', $submenu_list, $usces_members );
$html        .= '<li class="logout_member">' . usces_loginout( 'return' ) . '</li>
	</ul>' . "\n";

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_memberinfo_page_header', $header );
$html  .= '</div>';

$html .= '<h3>' . esc_html__( 'Purchase history', 'usces' ) . '</h3>';
$html .= '<div class="currency_code">' . esc_html__( 'Currency', 'usces' ) . ' : ' . __( usces_crcode( 'return' ), 'usces' ) . '</div>';
$html .= usces_member_history( 'return' );
$html .= '
	<h3><a name="edit"></a>' . esc_html__( 'Member information editing', 'usces' ) . '</h3>
	<div class="error_message">' . $this->error_message . '</div>
	<form action="' . esc_url( USCES_MEMBER_URL ) . '#edit" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
	<table class="customer_form">';

$html .= uesces_addressform( 'member', $usces_members );

$html .= '<tr>
	<th scope="row">' . esc_html__( 'e-mail adress', 'usces' ) . '</th>
	<td colspan="2"><input name="member[mailaddress1]" id="mailaddress1" type="text" value="' . esc_attr( $usces_members['mailaddress1'] ) . '" /></td>
	</tr>
	<tr>
	<th scope="row">' . esc_html__( 'password', 'usces' ) . '</th>
	<td colspan="2"><input class="hidden" value=" " />
	<input name="member[password1]" id="password1" type="password" value="' . esc_attr( $usces_members['password1'] ) . '" autocomplete="off" />
	' . esc_html__( 'Leave it blank in case of no change.', 'usces' ) . '</td>
	</tr>
	<tr>
	<th scope="row">' . esc_html__( 'Password (confirm)', 'usces' ) . '</th>
	<td colspan="2"><input name="member[password2]" id="password2" type="password" value="' . esc_attr( $usces_members['password2'] ) . '" />
	' . esc_html__( 'Leave it blank in case of no change.', 'usces' ) . '</td>
	</tr>';

$html .= '</table>
	<input name="member_regmode" type="hidden" value="editmemberform" />
	<input name="member_id" type="hidden" value="' . $usces_members['ID'] . '" />
	<div class="send">
	<input name="top" type="button" value="' . esc_html__( 'Back to the top page.', 'usces' ) . '" onclick="location.href=\'' . home_url() . '\'" />
	<input name="editmember" type="submit" value="' . esc_html__( 'update it', 'usces' ) . '" />
	<input name="deletemember" type="submit" value="' . esc_html__( 'delete it', 'usces' ) . '" onclick="return confirm(\'' . esc_html__( 'All information about the member is deleted. Are you all right?', 'usces' ) . '\');" /></div>';

$noncekey = 'post_member' . $this->get_uscesid( false );
$html    .= wp_nonce_field( $noncekey, 'wc_nonce', true, false );
$html    .= '</form>';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_memberinfo_page_footer', $footer );
$html  .= '</div>';

$html .= '</div>
	</div>
	</div>';
