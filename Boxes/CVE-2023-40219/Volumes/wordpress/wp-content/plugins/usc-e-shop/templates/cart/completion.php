<?php
/**
 * Complation page template
 *
 * @package Welcart
 */

global $usces_entries, $usces_carts;

usces_get_entries();
usces_get_carts();

$html = '';

$html  .= '<h3>' . esc_html__( 'It has been sent succesfully.', 'usces' ) . '</h3>';
$html  .= '<div class="post">';
$html  .= '<div class="header_explanation">';
$header = '<p>' . esc_html__( 'Thank you for shopping.', 'usces' ) . '<br />' . esc_html__( "If you have any questions, please contact us by 'Contact'.", 'usces' ) . '</p>';
$html  .= apply_filters( 'usces_filter_cartcompletion_page_header', $header, $usces_entries, $usces_carts );
$html  .= '</div><!-- header_explanation -->';

require USCES_PLUGIN_DIR . '/includes/completion_settlement.php';

$html .= apply_filters( 'usces_filter_cartcompletion_page_body', null, $usces_entries, $usces_carts );

$html  .= '<div class="footer_explanation">';
$footer = '';
$html  .= apply_filters( 'usces_filter_cartcompletion_page_footer', $footer, $usces_entries, $usces_carts );
$html  .= '</div><!-- footer_explanation -->';

$html .= '<div class="send"><a href="' . home_url() . '" class="back_to_top_button">' . esc_html__( 'Back to the top page.', 'usces' ) . '</a></div>';

$html .= '</div><!-- post -->';
$html .= apply_filters( 'usces_filter_conversion_tracking', null, $usces_entries, $usces_carts );
