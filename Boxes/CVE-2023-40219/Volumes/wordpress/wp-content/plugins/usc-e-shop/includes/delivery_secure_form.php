<?php
if(isset($this))
	$usces = &$this;

$payments = usces_get_system_option( 'usces_payment_method', 'sort' );
$payments = apply_filters( 'usces_filter_available_payment_method', $payments );
foreach ( (array)$payments as $id => $payment ) {
	if( !empty( $payment['settlement'] ) ){

		switch( $payment['settlement'] ){
			case 'acting_remise_card':
				$paymod_id = 'remise';
				$have_continue_charge = usces_have_continue_charge();
				$have_regular_order = usces_have_regular_order();

				if( 'on' != $usces->options['acting_settings'][$paymod_id]['card_activate'] 
					|| 'on' != $usces->options['acting_settings'][$paymod_id]['howpay'] 
					|| 'on' != $usces->options['acting_settings'][$paymod_id]['activate']
					|| 'activate' != $payment['use'] 
					|| ( $have_continue_charge || $have_regular_order ) ){
					break;
				}

				$div = isset( $_POST['div'] ) ? esc_html($_POST['div']) : '0';

				$html .= '
				<table class="customer_form" id="' . $paymod_id . '">
					<tr>
					<th scope="row">'.__('payment method', 'usces').'</th>
					<td colspan="2">
					<select name="offer[div]" id="div_remise">
						<option value="0"' . (('0' === $div) ? ' selected="selected"' : '') . '>'.__('Single payment', 'usces').'</option>
						<option value="1"' . (('1' === $div) ? ' selected="selected"' : '') . '>2'.__('-time payment', 'usces').'</option>
						<option value="2"' . (('2' === $div) ? ' selected="selected"' : '') . '>'.__('Libor Funding pay', 'usces').'</option>
					</select>
					</td>
					</tr>
				</table>';
				break;
		}
		$html .= apply_filters( 'usces_filter_delivery_secure_form_loop', '', $payment );
	}
}
$html = apply_filters( 'usces_filter_delivery_secure_form', $html, $payments );
$html .= wp_nonce_field( 'wc_delivery_secure_nonce', 'wc_nonce', false, false );
