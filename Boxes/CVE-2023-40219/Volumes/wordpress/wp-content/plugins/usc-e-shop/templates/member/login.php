<?php
/**
 * Login page template
 *
 * @package Welcart
 */

$html = '<div id="memberpages">

<div class="whitebox">';

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_login_page_header', $header );
$html  .= '</div>';

if ( usces_is_error_message() ) {
	$html .= '<div class="error_message">' . $this->error_message . '</div>';
}
$html       .= '<div class="loginbox">
<form name="loginform" id="loginform" action="' . apply_filters( 'usces_filter_login_form_action', esc_url( USCES_MEMBER_URL ) ) . '" method="post">
<p>
<label>' . esc_html__( 'e-mail adress', 'usces' ) . '<br />
<input type="text" name="loginmail" id="loginmail" class="loginmail" value="' . esc_attr( usces_remembername( 'return' ) ) . '" size="20" /></label>
</p>
<p>
<label>' . esc_html__( 'password', 'usces' ) . '<br />
<input class="hidden" value=" " />
<input type="password" name="loginpass" id="loginpass" class="loginpass" size="20" autocomplete="off" /></label>
</p>
<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> ' . esc_html__( 'memorize login information', 'usces' ) . '</label></p>
<p class="submit">';
$loginbutton = '<input type="submit" name="member_login" id="member_login" value="' . esc_html__( 'Log-in', 'usces' ) . '" />';
$html       .= apply_filters( 'usces_filter_login_button', $loginbutton );
$html       .= '</p>';
$html       .= '<p class="nav">
<a href="' . esc_url( USCES_LOSTMEMBERPASSWORD_URL ) . '" title="' . esc_html__( 'Did you forget your password?', 'usces' ) . '">' . esc_html__( 'Did you forget your password?', 'usces' ) . '</a>
</p>';
$html        = apply_filters( 'usces_filter_login_inform', $html );

$noncekey = 'post_member' . $this->get_uscesid( false );
$html    .= wp_nonce_field( $noncekey, 'wel_nonce', true, false );
$html    .= '</form>

<p id="nav" class="nav">
<a href="' . esc_url( USCES_NEWMEMBER_URL ) . apply_filters( 'usces_filter_newmember_urlquery', null ) . '" title="' . esc_html__( 'New enrollment for membership.', 'usces' ) . '">' . esc_html__( 'New enrollment for membership.', 'usces' ) . '</a>
</p>

</div>';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_login_page_footer', $footer );
$html  .= '</div>';

$html .= '</div>

</div>

<script type="text/javascript">';
if ( usces_is_login() ) {
	$html .= 'setTimeout( function(){ try{
		d = document.getElementById(\'loginpass\');
		d.value = \'\';
		d.focus();
		} catch(e){}
		}, 200);';
} else {
	$html .= 'try{document.getElementById(\'loginmail\').focus();}catch(e){}';
}
$html .= '</script>';
