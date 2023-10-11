<?php
/**
 * Complation page template
 *
 * @package Welcart
 */

$member_compmode = $this->page;

$html = '<div id="memberpages">

<div class="post">';

$html  .= '<div class="header_explanation">';
$header = '';
$html  .= apply_filters( 'usces_filter_membercompletion_page_header', $header );
$html  .= '</div>';

if ( 'newcompletion' === $member_compmode ) {
	$html .= '<p>' . esc_html__( 'Thank you in new membership.', 'usces' ) . '</p>';
} elseif ( 'editcompletion' === $member_compmode ) {
	$html .= '<p>' . esc_html__( 'Membership information has been updated.', 'usces' ) . '</p>';
} elseif ( 'lostcompletion' === $member_compmode ) {
	$html .= '<p>' . esc_html__( 'I transmitted an email.', 'usces' ) . '</p>
		<p>' . esc_html__( 'Change your password by following the instruction in this mail.', 'usces' ) . '</p>';
} elseif ( 'changepasscompletion' === $member_compmode ) {
	$html .= '<p>' . esc_html__( 'Password has been changed.', 'usces' ) . '</p>';
}

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_membercompletion_page_footer', $footer );
$html  .= '</div>';

$html .= '<p><a href="' . esc_url( USCES_MEMBER_URL ) . '">' . esc_html__( 'to vist membership information page', 'usces' ) . '</a></p>';
$html .= '<div class="send"><a href="' . home_url() . '" class="back_to_top_button">' . esc_html__( 'Back to the top page.', 'usces' ) . '</a></div>';

$html .= '</div>

</div>';
