<?php
/**
 * Member page template
 *
 * @package Welcart
 */

global $usces;
$usces_members = $this->get_member();

$html = '<div id="memberpages">

<div id="newmember">';

$html  .= '<div class="header_explanation">';
$header = '<ul>
<li>' . esc_html__( 'All your personal information  will be protected and handled with carefull attention.', 'usces' ) . '</li>
<li>' . esc_html__( 'Your information is entrusted to us for the purpose of providing information and respond to your requests, but to be used for any other purpose. More information, please visit our Privacy  Notice.', 'usces' ) . '</li>
<li>' . esc_html__( 'The items marked with *, are mandatory. Please complete.', 'usces' ) . '</li>
<li>' . esc_html__( 'Please use Alphanumeric characters for numbers.', 'usces' ) . '</li>
</ul>';
$html  .= apply_filters( 'usces_filter_newmember_page_header', $header );
$html  .= '</div>';

$html .= '<div class="error_message">' . $this->error_message . '</div>
<form action="' . apply_filters( 'usces_filter_newmember_form_action', esc_url( USCES_MEMBER_URL ) ) . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
<table border="0" cellpadding="0" cellspacing="0" class="customer_form">';
$html .= '<tr>
<th scope="row"><em>' . esc_html__( '*', 'usces' ) . '</em>' . esc_html__( 'e-mail adress', 'usces' ) . '</th>
<td colspan="2"><input name="member[mailaddress1]" id="mailaddress1" type="text" value="' . esc_attr( $usces_members['mailaddress1'] ) . '" /></td>
</tr>
<tr>
<th scope="row"><em>' . esc_html__( '*', 'usces' ) . '</em>' . esc_html__( 'E-mail address (for verification)', 'usces' ) . '</th>
<td colspan="2"><input name="member[mailaddress2]" id="mailaddress2" type="text" value="' . esc_attr( $usces_members['mailaddress2'] ) . '" /></td>
</tr>
<tr>
<th scope="row"><em>' . esc_html__( '*', 'usces' ) . '</em>' . esc_html__( 'password', 'usces' ) . '</th>
<td colspan="2"><input class="hidden" value=" " /><input name="member[password1]" id="password1" type="password" value="' . esc_attr( $usces_members['password1'] ) . '" autocomplete="off" /></td>
</tr>
<tr>
<th scope="row"><em>' . esc_html__( '*', 'usces' ) . '</em>' . esc_html__( 'Password (confirm)', 'usces' ) . '</th>
<td colspan="2"><input name="member[password2]" id="password2" type="password" value="' . esc_attr( $usces_members['password2'] ) . '" /></td>
</tr>';
$html           .= uesces_addressform( 'member', $usces_members );
$html           .= '</table>';
$html           .= usces_agree_member_field( 'return' );
$html           .= '<div class="send">';
$newmemberbutton = '<input name="member_regmode" type="hidden" value="' . esc_attr( $member_regmode ) . '" />
<input name="regmember" type="submit" value="' . esc_html__( 'transmit a message', 'usces' ) . '" />';
$html           .= apply_filters( 'usces_filter_newmember_button', $newmemberbutton );
$html           .= '</div>';
$html            = apply_filters( 'usces_filter_newmember_inform', $html );

$noncekey = 'post_member' . $this->get_uscesid( false );
$html    .= wp_nonce_field( $noncekey, 'wc_nonce', true, false );

$html .= '</form>';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_newmember_page_footer', $footer );
$html  .= '</div>';

$html .= '</div>

</div>';
