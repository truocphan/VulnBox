<?php
/**
 * Lost password page template
 *
 * @package Welcart
 */

$html = '<div id="memberpages">

<div class="whitebox">';

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_newpass_page_header', $header );
$html  .= '</div>';

if ( usces_is_error_message() ) {
	$html .= '<div class="error_message">' . $this->error_message . '</div>';
}
$html .= '<div class="loginbox">
<form name="loginform" id="loginform" action="' . esc_url( USCES_MEMBER_URL ) . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
<p>
<label>' . esc_html__( 'e-mail adress', 'usces' ) . '<br />
<input type="text" name="loginmail" id="loginmail" class="loginmail" value="" size="20" /></label>
</p>
<p class="submit">
<input type="submit" name="lostpassword" id="member_login" value="' . esc_html__( 'Obtain new password', 'usces' ) . '" />
</p>';

$noncekey = 'post_member' . $this->get_uscesid( false );
$html    .= wp_nonce_field( $noncekey, 'wc_nonce', true, false );
$html    .= '</form>
<div>' . esc_html__( 'Change your password by following the instruction in this mail.', 'usces' ) . '</div>
<p id="nav">';

if ( ! usces_is_login() ) {
	$html .= '<a href="' . esc_url( USCES_LOGIN_URL ) . '" title="' . esc_html__( 'Log-in', 'usces' ) . '">' . esc_html__( 'Log-in', 'usces' ) . '</a>';
}
$html .= '</p>

</div>';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_newpass_page_footer', $footer );
$html  .= '</div>';

$html .= '</div>

</div>

<script type="text/javascript">
try{document.getElementById(\'loginmail\').focus();}catch(e){}
</script>';
