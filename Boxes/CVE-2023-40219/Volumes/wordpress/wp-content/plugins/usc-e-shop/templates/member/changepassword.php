<?php
/**
 * Change password page template
 *
 * @package Welcart
 */

$html = '<div id="memberpages">';

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_changepass_page_header', $header );
$html  .= '</div>';

if ( '' !== $this->error_message ) {
	$html .= '<div class="error_message">' . $this->error_message . '</div>';
}
$html .= '<div class="loginbox">
<form name="loginform" id="loginform" action="' . esc_url( USCES_MEMBER_URL ) . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
	<p>
		<label>' . esc_html__( 'password', 'usces' ) . '<br />
		<input type="password" name="loginpass1" id="loginpass1" class="loginpass" value="" size="20" autocomplete="off" /></label>
	</p>
	<p>
		<label>' . esc_html__( 'Password (confirm)', 'usces' ) . '<br />
		<input type="password" name="loginpass2" id="loginpass2" class="loginpass" value="" size="20" autocomplete="off" /></label>
	</p>
	<p class="submit">
		<input type="submit" name="changepassword" id="member_login" value="' . esc_html__( 'Register', 'usces' ) . '" />
	</p>';
$html  = apply_filters( 'usces_filter_changepassword_inform', $html );

$noncekey = 'post_member' . $this->get_uscesid( false );
$html    .= wp_nonce_field( $noncekey, 'wc_nonce', true, false );
$html    .= '</form>
</div>';

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_changepass_page_footer', $footer );
$html  .= '</div>';

$html .= '</div>
<script type="text/javascript">
try{document.getElementById(\'loginpass1\').focus();}catch(e){}
</script>';
