<?php
if(isset($this))
	$usces = &$this;

$payments = usces_get_payments_by_name($usces_entries['order']['payment_name']);
$acting_flag = '';
$rand = usces_acting_key();
$cart = $usces->cart->get_cart();
$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;

$purchase_disabled = '';
$purchase_html = '';
$checkout_button_value = apply_filters( 'usces_filter_confirm_checkout_button_value', __( 'Checkout', 'usces' ) );

if( 'acting' != substr($payments['settlement'], 0, 6) || 0 == $usces_entries['order']['total_full_price'] ){
	$purchase_html = '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
		<div class="send">
		'.apply_filters( 'usces_filter_confirm_before_backbutton', NULL, $payments, $acting_flag, $rand ).'
		<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.apply_filters('usces_filter_confirm_prebutton_value', __('Back to payment method page.', 'usces')).'"' . apply_filters('usces_filter_confirm_prebutton', NULL) . ' />
		<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"' . apply_filters('usces_filter_confirm_nextbutton', NULL) . $purchase_disabled . ' /></div>';
	$html .= apply_filters('usces_filter_confirm_inform', $purchase_html, $payments, $acting_flag, $rand, $purchase_disabled);
	$html .= '</form>';
}else{
	$send_item_code = apply_filters('usces_filter_settlement_item_code', $usces->getItemCode($cart[0]['post_id']));
	$send_item_name = apply_filters('usces_filter_settlement_item_name', $usces->getItemName($cart[0]['post_id']));
	
	$acting_flag = ( 'acting' == $payments['settlement'] ) ? $payments['module'] : $payments['settlement'];
	switch( $acting_flag ){
	
		case 'paypal.php':
			require_once($usces->options['settlement_path'] . "paypal.php");
			$lc = ( isset($usces->options['system']['currency']) && !empty($usces->options['system']['currency']) ) ? $usces->options['system']['currency'] : '';
			$currency_code = $usces->get_currency_code();
			global $usces_settings;
			$country_num = $usces_settings['country_num'][$lc];
			$tel = ltrim(str_replace('-', '', $usces_entries['customer']['tel']), '0');
			$html .= '<form id="purchase_form" action="https://' . $usces_paypal_url . '/cgi-bin/webscr" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="' . esc_attr($usces_paypal_business) . '">
				<input type="hidden" name="custom" value="' . $usces->get_uscesid(false) . '">
				<input type="hidden" name="lc" value="'.$lc.'">
				<input type="hidden" name="charset" value="UTF-8">
				<input type="hidden" name="first_name" value="'.esc_attr($usces_entries['customer']['name2']).'">
				<input type="hidden" name="last_name" value="'.esc_attr($usces_entries['customer']['name1']).'">
				<input type="hidden" name="address1" value="'.esc_attr($usces_entries['customer']['address2']).'">
				<input type="hidden" name="address2" value="'.esc_attr($usces_entries['customer']['address3']).'">
				<input type="hidden" name="city" value="'.esc_attr($usces_entries['customer']['address1']).'">
				<input type="hidden" name="state" value="'.esc_attr($usces_entries['customer']['pref']).'">
				<input type="hidden" name="zip" value="'.esc_attr($usces_entries['customer']['zipcode']).'">
				<input type="hidden" name="night_phone_a" value="'.esc_attr($country_num).'">
				<input type="hidden" name="night_phone_b" value="'.esc_attr($tel).'">
				<input type="hidden" name="night_phone_c" value="">';
			if( 1 < $cart_count ) {
				$html .= '<input type="hidden" name="item_name" value="' . esc_attr($send_item_name) . ' ' . __('Others', 'usces') . '">';
			}else{
				$html .= '<input type="hidden" name="item_name" value="' . esc_attr($send_item_name) . '">';
			}
			$html .= '<input type="hidden" name="item_number" value="">
				<input type="hidden" name="amount" value="' . usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false) . '">';

			$html .= '<input type="hidden" name="currency_code" value="'.$currency_code.'">';
			$html .= '<input type="hidden" name="no_note" value="1">';
			$html .= '<input type="hidden" name="return" value="' . apply_filters('usces_paypal_return_url', (USCES_CART_URL . $usces->delim . 'acting=paypal&acting_return=1') ) . '">
				<input type="hidden" name="cancel_return" value="' . USCES_CART_URL . $usces->delim . 'confirm=1">
				<input type="hidden" name="notify_url" value="' . USCES_PAYPAL_NOTIFY_URL . '">
				<input type="hidden" name="button_subtype" value="products">
				<input type="hidden" name="tax_rate" value="0.000">
				<input type="hidden" name="shipping" value="0">
				<input type="hidden" name="bn" value="uscons_cart_WPS_JP">
				<div class="send"><input type="image" src="https://www.paypal.com/' . ( USCES_JP ? 'ja_JP/JP' : 'en_US' ) . '/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal"' . apply_filters('usces_filter_confirm_nextbutton', NULL) . $purchase_disabled . ' />
				<img alt="" border="0" src="https://www.paypal.com/' . ( USCES_JP ? 'ja_JP' : 'en_US' ) . '/i/scr/pixel.gif" width="1" height="1"></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"' . apply_filters('usces_filter_confirm_prebutton', NULL) . ' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>';
			break;
			
		case 'epsilon.php':
			$member = $usces->get_member();
			$memid = empty($member['ID']) ? 99999999 : $member['ID'];
			$html .= '<form id="purchase_form" action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<input type="hidden" name="user_id" value="' . $memid . '">
				<input type="hidden" name="user_name" value="' . esc_attr($usces_entries['customer']['name1'] . ' ' . $usces_entries['customer']['name2']) . '">
				<input type="hidden" name="user_mail_add" value="' . esc_attr($usces_entries['customer']['mailaddress1']) . '">';
			if( 1 < $cart_count ) {
				$html .= '<input type="hidden" name="item_code" value="99999999">
					<input type="hidden" name="item_name" value="' . esc_attr(mb_substr($send_item_name, 0, 25, 'UTF-8')) . ' ' . __('Others', 'usces') . '">';
			}else{
				$html .= '<input type="hidden" name="item_code" value="' . esc_attr($send_item_code) . '">
					<input type="hidden" name="item_name" value="' . esc_attr(mb_substr($send_item_name, 0, 32, 'UTF-8')) . '">';
			}
			$html .= '<input type="hidden" name="item_price" value="' . usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false) . '">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"' . apply_filters('usces_filter_confirm_prebutton', NULL) . ' />
				<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"' . apply_filters('usces_filter_confirm_nextbutton', NULL) . $purchase_disabled . ' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			break;
			
		case 'acting_remise_card':
			$have_continue_charge = usces_have_continue_charge( $cart );
			$acting_opts = $usces->options['acting_settings']['remise'];
			$usces->save_order_acting_data($rand);
			usces_save_order_acting_data( $rand );
			$member = $usces->get_member();
			$send_url = ('public' == $acting_opts['card_pc_ope']) ? $acting_opts['send_url_pc'] : $acting_opts['send_url_pc_test'];
			$html .= '<form id="purchase_form" name="purchase_form" action="' . $send_url . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}" accept-charset="Shift_JIS">
				<input type="hidden" name="SHOPCO" value="' . esc_attr($acting_opts['SHOPCO']) . '" />
				<input type="hidden" name="HOSTID" value="' . esc_attr($acting_opts['HOSTID']) . '" />
				<input type="hidden" name="REMARKS3" value="' . $acting_opts['REMARKS3'] . '" />
				<input type="hidden" name="S_TORIHIKI_NO" value="' . $rand . '" />
				<input type="hidden" name="JOB" value="' . apply_filters('usces_filter_remise_card_job', $acting_opts['card_jb']) . '" />
				<input type="hidden" name="MAIL" value="' . esc_attr($usces_entries['customer']['mailaddress1']) . '" />
				<input type="hidden" name="ITEM" value="' . apply_filters('usces_filter_remise_card_item', '0000120') . '" />
				<input type="hidden" name="TOTAL" value="' . usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false) . '" />
				<input type="hidden" name="AMOUNT" value="' . usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false) . '" />
				<input type="hidden" name="RETURL" value="' . USCES_CART_URL . $usces->delim . 'acting=remise_card&acting_return=1" />
				<input type="hidden" name="NG_RETURL" value="' . USCES_CART_URL . $usces->delim . 'acting=remise_card&acting_return=0" />
				<input type="hidden" name="EXITURL" value="' . USCES_CART_URL . $usces->delim . 'confirm=1" />
				';
			if( 'on' == $acting_opts['payquick'] && $usces->is_member_logged_in() ){
				$pcid = $usces->get_member_meta_value('remise_pcid', $member['ID']);
				$html .= '<input type="hidden" name="PAYQUICK" value="1">';
				if( $pcid != NULL )
					$html .= '<input type="hidden" name="PAYQUICKID" value="' . $pcid . '">';
			}
			if( 'on' == $acting_opts['howpay'] && isset($usces_entries['order']['div']) && '0' !== $usces_entries['order']['div'] && !$have_continue_charge ){
				$html .= '<input type="hidden" name="div" value="' . $usces_entries['order']['div'] . '">';
				switch( $usces_entries['order']['div'] ){
					case '1':
						$html .= '<input type="hidden" name="METHOD" value="61">';
						$html .= '<input type="hidden" name="PTIMES" value="2">';
						break;
					case '2':
						$html .= '<input type="hidden" name="METHOD" value="80">';
						break;
				}
			}else{
				$html .= '<input type="hidden" name="div" value="0">';
				$html .= '<input type="hidden" name="METHOD" value="10">';
			}
			if( $have_continue_charge ){
				$frequency = $usces->getItemFrequency($cart[0]['post_id']);
				$nextdate = current_time('mysql');
				$kana = ( !empty($usces_entries['customer']['name3']) ) ? $usces_entries['customer']['name3'] : '';
				if( !empty($usces_entries['customer']['name4']) ) $kana .= $usces_entries['customer']['name4'];
				if( !empty($kana) ) {
					$kana = str_replace( "・", "", str_replace( "　", "", mb_convert_kana( $kana, "KVC", 'UTF-8' ) ) );
					$kana = mb_substr( $kana, 0, 20, 'UTF-8' );
					mb_regex_encoding( 'UTF-8' );
					if( !mb_ereg( "^[ァ-ヶー]+$", $kana ) ) $kana = '';
				}
				$html .= '
				<input type="hidden" name="AUTOCHARGE" value="1">
				<input type="hidden" name="AC_S_KAIIN_NO" value="' . $member['ID'] . '">
				<input type="hidden" name="AC_NAME" value="">
				<input type="hidden" name="AC_KANA" value="' . esc_attr($kana) . '">
				<input type="hidden" name="AC_TEL" value="' . esc_attr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['tel'], 'a', 'UTF-8'))) . '">
				<input type="hidden" name="AC_AMOUNT" value="' . $usces_entries['order']['total_full_price'] . '">
				<input type="hidden" name="AC_TOTAL" value="' . $usces_entries['order']['total_full_price'] . '">
				<input type="hidden" name="AC_NEXT_DATE" value="' . date('Ymd', dlseller_first_charging($cart[0]['post_id'], 'time')) . '">
				<input type="hidden" name="AC_INTERVAL" value="' . $frequency . 'M">
				';
			}
			$html .= '<input type="hidden" name="dummy" value="&#65533;" />';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"' . apply_filters('usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"') . $purchase_disabled . ' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"' . apply_filters('usces_filter_confirm_prebutton', NULL) . ' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>'."\n";
			break;
			
		case 'acting_remise_conv':
			if( function_exists('mb_strlen') ){
				$biko = ( 22 < mb_strlen($usces_entries['order']['note'], 'UTF-8')) ? (mb_substr($usces_entries['order']['note'], 0, 22, 'UTF-8').'...') : $usces_entries['order']['note'];
			}else{
				$biko = ( 44 < strlen($usces_entries['order']['note'])) ? (substr($usces_entries['order']['note'], 0, 44).'...') : $usces_entries['order']['note'];
			}
			$datestr = get_date_from_gmt(gmdate('Y-m-d H:i:s', time()));
			$acting_opts = $usces->options['acting_settings']['remise'];
			$usces->save_order_acting_data($rand);
			usces_save_order_acting_data( $rand );
			$send_url = ('public' == $acting_opts['conv_pc_ope']) ? $acting_opts['send_url_cvs_pc'] : $acting_opts['send_url_cvs_pc_test'];
			$kana1 = ( !empty($usces_entries['customer']['name3']) ) ? $usces_entries['customer']['name3'] : '';
			if( !empty($kana1) ) {
				$kana1 = str_replace( "・", "", str_replace( "　", "", mb_convert_kana( $kana1, "KVC", 'UTF-8' ) ) );
				$kana1 = mb_substr( $kana1, 0, 20, 'UTF-8' );
				mb_regex_encoding( 'UTF-8' );
				if( !mb_ereg( "^[ァ-ヶー]+$", $kana1 ) ) $kana1 = '';
			}
			$kana2 = ( !empty($usces_entries['customer']['name4']) ) ? $usces_entries['customer']['name4'] : '';
			if( !empty($kana2) ) {
				$kana2 = str_replace( "・", "", str_replace( "　", "", mb_convert_kana( $kana2, "KVC", 'UTF-8' ) ) );
				$kana2 = mb_substr( $kana2, 0, 20, 'UTF-8' );
				mb_regex_encoding( 'UTF-8' );
				if( !mb_ereg( "^[ァ-ヶー]+$", $kana2 ) ) $kana2 = '';
			}
			$html .= '<form id="purchase_form" name="purchase_form" action="' . $send_url . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}" accept-charset="Shift_JIS">
				<input type="hidden" name="SHOPCO" value="' . esc_attr($acting_opts['SHOPCO']) . '" />
				<input type="hidden" name="HOSTID" value="' . esc_attr($acting_opts['HOSTID']) . '" />
				<input type="hidden" name="REMARKS3" value="' . $acting_opts['REMARKS3'] . '" />
				<input type="hidden" name="S_TORIHIKI_NO" value="' . $rand . '" />
				<input type="hidden" name="NAME1" value="' . esc_attr(mb_substr($usces_entries['customer']['name1'], 0, 20, 'UTF-8')) . '" />
				<input type="hidden" name="NAME2" value="' . esc_attr(mb_substr($usces_entries['customer']['name2'], 0, 20, 'UTF-8')) . '" />
				<input type="hidden" name="KANA1" value="' . esc_attr($kana1) . '" />
				<input type="hidden" name="KANA2" value="' . esc_attr($kana2) . '" />
				<input type="hidden" name="YUBIN1" value="' . esc_attr(substr(str_replace('-', '', $usces_entries['customer']['zipcode']), 0, 3)) . '" />
				<input type="hidden" name="YUBIN2" value="' . esc_attr(substr(str_replace('-', '', $usces_entries['customer']['zipcode']), 3, 4)) . '" />
				<input type="hidden" name="ADD1" value="' . esc_attr($usces_entries['customer']['pref'] . $usces_entries['customer']['address1']) . '" />
				<input type="hidden" name="ADD2" value="' . esc_attr($usces_entries['customer']['address2']) . '" />
				<input type="hidden" name="ADD3" value="' . esc_attr($usces_entries['customer']['address3']) . '" />
				<input type="hidden" name="TEL" value="' . esc_attr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['tel'], 'a', 'UTF-8'))) . '" />
				<input type="hidden" name="MAIL" value="' . esc_attr($usces_entries['customer']['mailaddress1']) . '" />
				<input type="hidden" name="TOTAL" value="' . $usces_entries['order']['total_full_price'] . '" />
				<input type="hidden" name="TAX" value="" />
				<input type="hidden" name="S_PAYDATE" value="' . date('Ymd', mktime(0,0,0,substr($datestr, 5, 2),substr($datestr, 8, 2)+$acting_opts['S_PAYDATE'],substr($datestr, 0, 4))) . '" />
				<input type="hidden" name="SEIYAKUDATE" value="' . date('Ymd', mktime(0,0,0,substr($datestr, 5, 2),substr($datestr, 8, 2),substr($datestr, 0, 4))) . '" />
				<input type="hidden" name="BIKO" value="' . esc_attr($biko) . '" />
				';
			$mname_01 = '商品総額';
			$html .= '<input type="hidden" name="MNAME_01" value="' . $mname_01 . '" />
				<input type="hidden" name="MSUM_01" value="' . usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false) . '" />
				<input type="hidden" name="MNAME_02" value="" />
				<input type="hidden" name="MSUM_02" value="0" />
				<input type="hidden" name="MNAME_03" value="" />
				<input type="hidden" name="MSUM_03" value="0" />
				<input type="hidden" name="MNAME_04" value="" />
				<input type="hidden" name="MSUM_04" value="0" />
				<input type="hidden" name="MNAME_05" value="" />
				<input type="hidden" name="MSUM_05" value="0" />
				<input type="hidden" name="MNAME_06" value="" />
				<input type="hidden" name="MSUM_06" value="0" />
				<input type="hidden" name="MNAME_07" value="" />
				<input type="hidden" name="MSUM_07" value="0" />
				';
			$html .= '<input type="hidden" name="RETURL" value="' . USCES_CART_URL . $usces->delim . 'acting=remise_conv&acting_return=1" />
				<input type="hidden" name="NG_RETURL" value="' . USCES_CART_URL . $usces->delim . 'acting=remise_conv&acting_return=0" />
				<input type="hidden" name="OPT" value="1" />
				<input type="hidden" name="EXITURL" value="' . USCES_CART_URL . $usces->delim . 'confirm=1" />
				';
			$html .= '
				<input type="hidden" name="dummy" value="&#65533;" />
				<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"' . apply_filters('usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"') . $purchase_disabled . ' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="' . USCES_CART_URL . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"' . apply_filters('usces_filter_confirm_prebutton', NULL) . ' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>'."\n";
			break;
			
		case 'acting_jpayment_card'://クレジット決済(J-Payment)
		
			$acting_opts = $usces->options['acting_settings']['jpayment'];
			$usces->save_order_acting_data($rand);
			usces_save_order_acting_data( $rand );
			$itemName = $usces->getItemName($cart[0]['post_id']);
			if(1 < $cart_count) $itemName .= ','.__('Others', 'usces');
			if(50 < mb_strlen($itemName, 'UTF-8')) $itemName = mb_substr($itemName, 0, 50, 'UTF-8').'...';
			$quantity = 0;
			foreach($cart as $cart_row) {
				$quantity += $cart_row['quantity'];
			}
			$desc = $itemName.' '.__('Quantity','usces').':'.$quantity;

			$tx = ( 'exclude' == $usces->options['tax_mode'] ) ? $usces_entries['order']['tax'] : 0;
			$sf = ( !empty($usces_entries['order']['shipping_charge']) ) ? $usces_entries['order']['shipping_charge'] : 0;
			$am = $usces_entries['order']['total_full_price'] - $tx - $sf;
			if( 0 > $am ){
				$tx = 0;
				$sf = 0;
				$am = $usces_entries['order']['total_full_price'];
			}
			
			$purchase_html = '<form id="purchase_form" name="purchase_form" action="'.$acting_opts['send_url'].'" method="post" onKeyDown="if(event.keyCode == 13) {return false;}" >
				<input type="hidden" name="aid" value="'.$acting_opts['aid'].'" />
				<input type="hidden" name="cod" value="'.$rand.'" />
				<input type="hidden" name="jb" value="'.$acting_opts['card_jb'].'" />
				<input type="hidden" name="am" value="'.$am.'" />
				<input type="hidden" name="tx" value="'.$tx.'" />
				<input type="hidden" name="sf" value="'.$sf.'" />
				<input type="hidden" name="pt" value="1" />
				<input type="hidden" name="inm" value="'.esc_attr($desc).'" />
				<input type="hidden" name="pn" value="'.esc_attr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['tel'], 'a', 'UTF-8'))).'" />
				<input type="hidden" name="em" value="'.esc_attr($usces_entries['customer']['mailaddress1']).'" />
				<input type="hidden" name="acting" value="jpayment_card" />
				<input type="hidden" name="acting_return" value="1" />
				<input type="hidden" name="page_id" value="'.USCES_CART_NUMBER.'" />
				<input type="hidden" name="uscesid" value="' . $usces->get_uscesid(false) . '">
				';
			$purchase_html .= '<div class="send"><input name="purchase_jpayment" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', NULL).$purchase_disabled.' /></div>';
			$html .= apply_filters('usces_filter_confirm_inform', $purchase_html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			$purchase_html = '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if(event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html .= apply_filters('usces_filter_confirm_inform_back', $purchase_html);
			$html .= '</form>'."\n";
			break;
		case 'acting_jpayment_conv'://コンビニ・ペーパーレス決済(J-Payment)
			$acting_opts = $usces->options['acting_settings']['jpayment'];
			$usces->save_order_acting_data($rand);
			usces_save_order_acting_data( $rand );
			$itemName = $usces->getItemName($cart[0]['post_id']);
			if(1 < $cart_count) $itemName .= ','.__('Others', 'usces');
			if(50 < mb_strlen($itemName, 'UTF-8')) $itemName = mb_substr($itemName, 0, 50, 'UTF-8').'...';
			$quantity = 0;
			foreach($cart as $cart_row) {
				$quantity += $cart_row['quantity'];
			}
			$desc = $itemName.' '.__('Quantity','usces').':'.$quantity;

			$tx = ( 'exclude' == $usces->options['tax_mode'] ) ? $usces_entries['order']['tax'] : 0;
			$sf = ( !empty($usces_entries['order']['shipping_charge']) ) ? $usces_entries['order']['shipping_charge'] : 0;
			$am = $usces_entries['order']['total_full_price'] - $tx - $sf;
			if( 0 > $am ){
				$tx = 0;
				$sf = 0;
				$am = $usces_entries['order']['total_full_price'];
			}

			$html .= '<form id="purchase_form" name="purchase_form" action="'.$acting_opts['send_url'].'" method="post" onKeyDown="if(event.keyCode == 13) {return false;}" >
				<input type="hidden" name="aid" value="'.$acting_opts['aid'].'" />
				<input type="hidden" name="cod" value="'.$rand.'" />
				<input type="hidden" name="jb" value="CAPTURE" />
				<input type="hidden" name="am" value="'.$am.'" />
				<input type="hidden" name="tx" value="'.$tx.'" />
				<input type="hidden" name="sf" value="'.$sf.'" />
				<input type="hidden" name="pt" value="2" />
				<input type="hidden" name="inm" value="'.esc_attr($desc).'" />
				<input type="hidden" name="pn" value="'.esc_attr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['tel'], 'a', 'UTF-8'))).'" />
				<input type="hidden" name="em" value="'.esc_attr($usces_entries['customer']['mailaddress1']).'" />
				<input type="hidden" name="acting" value="jpayment_conv" />
				<input type="hidden" name="acting_return" value="1" />
				<input type="hidden" name="page_id" value="'.USCES_CART_NUMBER.'" />
				<input type="hidden" name="uscesid" value="' . $usces->get_uscesid(false) . '">
				';
			$html .= '<div class="send"><input name="purchase_jpayment" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', NULL).$purchase_disabled.' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if(event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>'."\n";
			break;
		case 'acting_jpayment_bank'://バンクチェック決済(J-Payment)
			$acting_opts = $usces->options['acting_settings']['jpayment'];
			$usces->save_order_acting_data($rand);
			usces_save_order_acting_data( $rand );
			$itemName = $usces->getItemName($cart[0]['post_id']);
			if(1 < $cart_count) $itemName .= ','.__('Others', 'usces');
			if(50 < mb_strlen($itemName, 'UTF-8')) $itemName = mb_substr($itemName, 0, 50, 'UTF-8').'...';
			$quantity = 0;
			foreach($cart as $cart_row) {
				$quantity += $cart_row['quantity'];
			}
			$desc = $itemName.' '.__('Quantity','usces').':'.$quantity;

			$tx = ( 'exclude' == $usces->options['tax_mode'] ) ? $usces_entries['order']['tax'] : 0;
			$sf = ( !empty($usces_entries['order']['shipping_charge']) ) ? $usces_entries['order']['shipping_charge'] : 0;
			$am = $usces_entries['order']['total_full_price'] - $tx - $sf;
			if( 0 > $am ){
				$tx = 0;
				$sf = 0;
				$am = $usces_entries['order']['total_full_price'];
			}

			$html .= '<form id="purchase_form" name="purchase_form" action="'.$acting_opts['send_url'].'" method="post" onKeyDown="if(event.keyCode == 13) {return false;}" >
				<input type="hidden" name="aid" value="'.$acting_opts['aid'].'" />
				<input type="hidden" name="cod" value="'.$rand.'" />
				<input type="hidden" name="jb" value="CAPTURE" />
				<input type="hidden" name="am" value="'.$am.'" />
				<input type="hidden" name="tx" value="'.$tx.'" />
				<input type="hidden" name="sf" value="'.$sf.'" />
				<input type="hidden" name="pt" value="7" />
				<input type="hidden" name="inm" value="'.esc_attr($desc).'" />
				<input type="hidden" name="pn" value="'.esc_attr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['tel'], 'a', 'UTF-8'))).'" />
				<input type="hidden" name="em" value="'.esc_attr($usces_entries['customer']['mailaddress1']).'" />
				<input type="hidden" name="acting" value="jpayment_bank" />
				<input type="hidden" name="acting_return" value="1" />
				<input type="hidden" name="page_id" value="'.USCES_CART_NUMBER.'" />
				<input type="hidden" name="uscesid" value="' . $usces->get_uscesid(false) . '">
				';
			$html .= '<div class="send"><input name="purchase_jpayment" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', NULL).$purchase_disabled.' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if(event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>'."\n";
			break;

		case 'acting_telecom_card'://テレコムクレジット
			usces_save_order_acting_data( $rand );
			$acting_opts = $usces->options['acting_settings']['telecom'];
			$member = $usces->get_member();
			if( empty($member['ID']) || 'on' != $acting_opts['oneclick'] ) {
				$memid = ( !empty($member['ID']) ) ? $member['ID'] : 99999999;
				$send_url = $acting_opts['send_url'];
			} else {
				$memid = $member['ID'];
				$oneclick = $usces->get_member_meta_value( 'telecom_oneclick', $member['ID'] );
				if( $oneclick != NULL ) {
					$send_url = $acting_opts['oneclick_send_url'];
				} else {
					$send_url = $acting_opts['send_url'];
				}
			}
			$money  = ( '$' == usces_get_cr_symbol() ) ? '$' : '';
			$money .= usces_crform( $usces_entries['order']['total_full_price'], false, false, 'return', false );
			$tel = str_replace('-', '', $usces_entries['customer']['tel']);
			$redirect_url = USCES_CART_URL.$usces->delim.'acting=telecom_card&acting_return=1&result=1&option='.$rand;
			$redirect_back_url = USCES_CART_URL.$usces->delim.'confirm=1';
			$html .= '<form id="purchase_form" action="'.$send_url.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<input type="hidden" name="clientip" value="'.$acting_opts['clientip'].'">
				<input type="hidden" name="money" value="'.apply_filters( 'usces_filter_acting_amount', $money, $acting_flag ).'">
				<input type="hidden" name="sendid" value="'.$memid.'">
				<input type="hidden" name="usrtel" value="'.$tel.'">
				<input type="hidden" name="usrmail" value="'.esc_attr($usces_entries['customer']['mailaddress1']).'">
				<input type="hidden" name="redirect_url" value="'.$redirect_url.'">
				<input type="hidden" name="redirect_back_url" value="'.$redirect_back_url.'">
				<input type="hidden" name="option" value="'.$rand.'">
				';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', NULL).$purchase_disabled.' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>';
			break;

		case 'acting_telecom_edy'://Edy決済(テレコムクレジット)
			usces_save_order_acting_data( $rand );
			$acting_opts = $usces->options['acting_settings']['telecom'];
			$member = $usces->get_member();
			$memid = empty($member['ID']) ? 99999999 : $member['ID'];
			$money  = ( '$' == usces_get_cr_symbol() ) ? '$' : '';
			$money .= usces_crform( $usces_entries['order']['total_full_price'], false, false, 'return', false );
			$redirect_back_url = USCES_CART_URL.$usces->delim.'acting=telecom_edy&acting_return=1&reg_order=1';
			$html .= '<form id="purchase_form" action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<input type="hidden" name="clientip" value="'.$acting_opts['clientip'].'">
				<input type="hidden" name="sendid" value="'.$memid.'">
				<input type="hidden" name="money" value="'.apply_filters( 'usces_filter_acting_amount', $money, $acting_flag ).'">
				<input type="hidden" name="redirect_back_url" value="'.$redirect_back_url.'">
				<input type="hidden" name="option" value="'.$rand.'">
				';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', NULL).$purchase_disabled.' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>';
			break;

		case 'acting_digitalcheck_card'://カード決済(メタップスペイメント)
			$acting_opts = $usces->options['acting_settings']['digitalcheck'];
			$sid = uniqid();
			$usces->save_order_acting_data($sid);
			usces_save_order_acting_data( $sid );
			$member = $usces->get_member();
			if( 'on' == $acting_opts['card_user_id'] && $usces->is_member_logged_in() ) {
				$ip_user_id = $usces->get_member_meta_value( 'digitalcheck_ip_user_id', $member['ID'] );
				if( empty($ip_user_id) ) {
					$ip_user_id = $member['ID'];
					$send_url = $acting_opts['send_url_card'];
					$fuka = $acting_flag.$ip_user_id;
				} else {
					$send_url = USCES_CART_URL;
					$fuka = $acting_flag;
				}
			} else {
				$ip_user_id = false;
				$send_url = $acting_opts['send_url_card'];
				$fuka = $acting_flag;
			}
			$item_name = $usces->getItemName($cart[0]['post_id']);
			if( 1 < $cart_count ) $item_name .= ','.__('Others', 'usces');
			if( 22 < mb_strlen($item_name) ) $item_name = mb_strimwidth( $item_name, 0, 44, '...', 'UTF-8' );
			$kakutei = ( empty($acting_opts['card_kakutei']) ) ? '0' : $acting_opts['card_kakutei'];
			$html .= '<form id="purchase_form" name="purchase_form" action="'.$send_url.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}" accept-charset="Shift_JIS">
				<input type="hidden" name="IP" value="'.$acting_opts['card_ip'].'" />
				<input type="hidden" name="SID" value="'.$sid.'" />
				<input type="hidden" name="N1" value="'.$item_name.'">
				<input type="hidden" name="K1" value="'.usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false).'">
				<input type="hidden" name="STORE" value="51" />
				<input type="hidden" name="KAKUTEI" value="'.$kakutei.'" />
				<input type="hidden" name="FUKA" value="'.$fuka.'" />
				<input type="hidden" name="NAME1" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['name1'], 0, 20, '', 'UTF-8')).'" />
				<input type="hidden" name="NAME2" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['name2'], 0, 20, '', 'UTF-8')).'" />
				<input type="hidden" name="KANA1" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['name3'], 0, 20, '', 'UTF-8')).'" />
				<input type="hidden" name="KANA2" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['name4'], 0, 20, '', 'UTF-8')).'" />
				<input type="hidden" name="YUBIN1" value="'.esc_attr(substr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['zipcode'], 'a', 'UTF-8')), 0, 7)).'" />
				<input type="hidden" name="TEL" value="'.esc_attr(substr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['tel'], 'a', 'UTF-8')), 0, 11)).'" />
				<input type="hidden" name="ADR1" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['pref'].$usces_entries['customer']['address1'].$usces_entries['customer']['address2'], 0, 50, '', 'UTF-8')).'" />
				<input type="hidden" name="ADR2" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['address3'], 0, 50, '', 'UTF-8')).'" />
				<input type="hidden" name="MAIL" value="'.esc_attr($usces_entries['customer']['mailaddress1']).'" />
				';
			if( $ip_user_id ) {
				$html .= '<input type="hidden" name="PASS" value="'.$acting_opts['card_pass'].'">
					<input type="hidden" name="IP_USER_ID" value="'.$ip_user_id.'">
					';
			}
			if( $usces->use_ssl ) {
				$ssl_url = $usces->options['ssl_url'].'/?page_id='.USCES_CART_NUMBER;
				$html .= '<input type="hidden" name="OKURL" value="'.$ssl_url.$usces->delim.'acting=digitalcheck_card&acting_return=1" />
					<input type="hidden" name="RT" value="'.$ssl_url.$usces->delim.'acting=digitalcheck_card&confirm=1" />
					';
			} else {
				$html .= '<input type="hidden" name="OKURL" value="'.USCES_CART_URL.$usces->delim.'acting=digitalcheck_card&acting_return=1" />
					<input type="hidden" name="RT" value="'.USCES_CART_URL.$usces->delim.'acting=digitalcheck_card&confirm=1" />
					';
			}
			$html .= '<input type="hidden" name="dummy" value="&#65533;" />';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"').$purchase_disabled.' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $sid, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>'."\n";
			break;
		case 'acting_digitalcheck_conv'://コンビニ決済(メタップスペイメント)
			$acting_opts = $usces->options['acting_settings']['digitalcheck'];
			$sid = uniqid();
			$usces->save_order_acting_data($sid);
			usces_save_order_acting_data( $sid );
			$item_name = $usces->getItemName($cart[0]['post_id']);
			if( 1 < $cart_count ) $item_name .= ','.__('Others', 'usces');
			if( 22 < mb_strlen($item_name) ) $item_name = mb_strimwidth( $item_name, 0, 44, '...', 'UTF-8' );
			$today = date( 'Y-m-d', current_time('timestamp') );
			list( $year, $month, $day ) = explode( '-', $today );
			$kigen = date( 'Ymd', mktime(0, 0, 0, (int)$month, (int)$day + (int)$acting_opts['conv_kigen'], (int)$year) );
			$conv_store = ( isset( $acting_opts['conv_store'] ) && is_array( $acting_opts['conv_store'] ) ) ? $acting_opts['conv_store'] : array();
			$store = ( 1 == count( $conv_store ) ) ? $conv_store[0] : '99';
			$html .= '<form id="purchase_form" name="purchase_form" action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}" accept-charset="Shift_JIS">
				<input type="hidden" name="IP" value="'.$acting_opts['conv_ip'].'" />
				<input type="hidden" name="SID" value="'.$sid.'" />
				<input type="hidden" name="N1" value="'.esc_attr($item_name).'">
				<input type="hidden" name="K1" value="'.usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false).'">
				<input type="hidden" name="STORE" value="'.$store.'" />
				<input type="hidden" name="FUKA" value="'.$acting_flag.'" />
				<input type="hidden" name="KIGEN" value="'.$kigen.'" />
				<input type="hidden" name="NAME1" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['name1'], 0, 20, '', 'UTF-8')).'" />
				<input type="hidden" name="NAME2" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['name2'], 0, 20, '', 'UTF-8')).'" />
				<input type="hidden" name="KANA1" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['name3'], 0, 20, '', 'UTF-8')).'" />
				<input type="hidden" name="KANA2" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['name4'], 0, 20, '', 'UTF-8')).'" />
				<input type="hidden" name="YUBIN1" value="'.esc_attr(substr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['zipcode'], 'a', 'UTF-8')), 0, 7)).'" />
				<input type="hidden" name="TEL" value="'.esc_attr(substr(str_replace('-', '', mb_convert_kana($usces_entries['customer']['tel'], 'a', 'UTF-8')), 0, 11)).'" />
				<input type="hidden" name="ADR1" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['pref'].$usces_entries['customer']['address1'].$usces_entries['customer']['address2'], 0, 50, '', 'UTF-8')).'" />
				<input type="hidden" name="ADR2" value="'.esc_attr(mb_strimwidth($usces_entries['customer']['address3'], 0, 50, '', 'UTF-8')).'" />
				<input type="hidden" name="MAIL" value="'.esc_attr($usces_entries['customer']['mailaddress1']).'" />
				';
			if( $usces->use_ssl ) {
				$ssl_url = $usces->options['ssl_url'].'/?page_id='.USCES_CART_NUMBER;
				$html .= '<input type="hidden" name="OKURL" value="'.$ssl_url.$usces->delim.'acting=digitalcheck_conv&acting_return=1" />
					<input type="hidden" name="RT" value="'.$ssl_url.$usces->delim.'acting=digitalcheck_conv&confirm=1" />
					';
			} else {
				$html .= '<input type="hidden" name="OKURL" value="'.USCES_CART_URL.$usces->delim.'acting=digitalcheck_conv&acting_return=1" />
					<input type="hidden" name="RT" value="'.USCES_CART_URL.$usces->delim.'acting=digitalcheck_conv&confirm=1" />
					';
			}
			$html .= '<input type="hidden" name="dummy" value="&#65533;" />';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"').$purchase_disabled.' /></div>';
			$html = apply_filters('usces_filter_confirm_inform', $html, $payments, $acting_flag, $sid, $purchase_disabled);
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters('usces_filter_confirm_inform_back', $html);
			$html .= '</form>'."\n";
			break;

		case 'acting_mizuho_card'://カード決済(みずほファクター)
			$acting_opts = $usces->options['acting_settings']['mizuho'];
			$send_url = ( 'public' == $acting_opts['ope'] ) ? $acting_opts['send_url'] : $acting_opts['send_url_test'];
			$p_ver = '0200';
			$stdate = date( 'Ymd' );
			$stran = usces_rand( 6 );
			usces_save_order_acting_data( $stran );
			$bkcode = 'bg01';
			$amount = apply_filters( 'usces_filter_acting_amount', usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false), $acting_flag );
			$schksum = $p_ver.$stdate.$stran.$bkcode.$acting_opts['shopid'].$acting_opts['cshopid'].$amount.$acting_opts['hash_pass'];
			$schksum = htmlspecialchars( md5( $schksum ) );
			$html .= '<form id="purchase_form" action="'.$send_url.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}" accept-charset="Shift_JIS">
				<input type="hidden" name="p_ver" value="'.$p_ver.'">
				<input type="hidden" name="stdate" value="'.$stdate.'">
				<input type="hidden" name="stran" value="'.$stran.'">
				<input type="hidden" name="bkcode" value="'.$bkcode.'">
				<input type="hidden" name="shopid" value="'.$acting_opts['shopid'].'">
				<input type="hidden" name="cshopid" value="'.$acting_opts['cshopid'].'">
				<input type="hidden" name="amount" value="'.$amount.'">
				<input type="hidden" name="schksum" value="'.$schksum.'">
				';
			$html .= '<input type="hidden" name="dummy" value="&#65533;" />';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"').$purchase_disabled.' /></div>';
			$html = apply_filters( 'usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled );
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters( 'usces_filter_confirm_inform_back', $html );
			$html .= '</form>';
			break;
		case 'acting_mizuho_conv1'://コンビニ・ウェルネット決済(みずほファクター)
		case 'acting_mizuho_conv2'://コンビニ・セブンイレブン決済(みずほファクター)
			$acting_opts = $usces->options['acting_settings']['mizuho'];
			$send_url = ( 'public' == $acting_opts['ope'] ) ? $acting_opts['send_url'] : $acting_opts['send_url_test'];
			$p_ver = '0200';
			$stdate = date( 'Ymd' );
			$stran = usces_rand( 6 );
			usces_save_order_acting_data( $stran );
			$bkcode = 'cv0'.substr( $acting_flag, -1 );
			$amount = apply_filters( 'usces_filter_acting_amount', usces_crform($usces_entries['order']['total_full_price'], false, false, 'return', false), $acting_flag );
			$custmKanji = mb_strimwidth( $usces_entries['customer']['name1'].$usces_entries['customer']['name2'], 0, 40, '', 'UTF-8' );
			$mailaddr = esc_attr( $usces_entries['customer']['mailaddress1'] );
			$tel = str_replace( '-', '', $usces_entries['customer']['tel'] );
			$schksum = $p_ver.$stdate.$stran.$bkcode.$acting_opts['shopid'].$acting_opts['cshopid'].$amount.mb_convert_encoding($custmKanji, 'SJIS', 'UTF-8').$mailaddr.$tel.$acting_opts['hash_pass'];
			$schksum = htmlspecialchars( md5( $schksum ) );
			$html .= '<form id="purchase_form" action="'.$send_url.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<input type="hidden" name="p_ver" value="'.$p_ver.'">
				<input type="hidden" name="stdate" value="'.$stdate.'">
				<input type="hidden" name="stran" value="'.$stran.'">
				<input type="hidden" name="bkcode" value="'.$bkcode.'">
				<input type="hidden" name="shopid" value="'.$acting_opts['shopid'].'">
				<input type="hidden" name="cshopid" value="'.$acting_opts['cshopid'].'">
				<input type="hidden" name="amount" value="'.$amount.'">
				<input type="hidden" name="custmKanji" value="'.$custmKanji.'">
				<input type="hidden" name="mailaddr" value="'.$mailaddr.'">
				<input type="hidden" name="tel" value="'.$tel.'">
				<input type="hidden" name="schksum" value="'.$schksum.'">
				';
			$html .= '<input type="hidden" name="dummy" value="&#65533;" />';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"').$purchase_disabled.' /></div>';
			$html = apply_filters( 'usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled );
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters( 'usces_filter_confirm_inform_back', $html );
			$html .= '</form>';
			break;

		case 'acting_anotherlane_card'://カード決済(アナザーレーン)
			$usces->save_order_acting_data( $rand );
			usces_save_order_acting_data( $rand );
			$acting_opts = $usces->options['acting_settings']['anotherlane'];
			$amount = apply_filters( 'usces_filter_acting_amount', usces_crform( $usces_entries['order']['total_full_price'], false, false, 'return', false), $acting_flag );
			$html .= '<form id="purchase_form" name="purchase_form" action="'.$acting_opts['send_url'].'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}" accept-charset="UTF-8">
				<input type="hidden" name="SiteId" value="'.$acting_opts['siteid'].'">
				<input type="hidden" name="SitePass" value="'.$acting_opts['sitepass'].'">
				<input type="hidden" name="Amount" value="'.$amount.'">
				<input type="hidden" name="TransactionId" value="">
				<input type="hidden" name="zip" value="'.esc_attr( str_replace( '-', '', $usces_entries['customer']['zipcode'] ) ).'">
				<input type="hidden" name="capital" value="'.esc_attr( $usces_entries['customer']['pref'] ).'">
				<input type="hidden" name="adr1" value="'.esc_attr( $usces_entries['customer']['address1'] ).'">
				<input type="hidden" name="adr2" value="'.esc_attr( $usces_entries['customer']['address2'] ).'">
				<input type="hidden" name="adr3" value="'.esc_attr( $usces_entries['customer']['address3'] ).'">
				<input type="hidden" name="name" value="'.esc_attr( mb_substr( $usces_entries['customer']['name1'].$usces_entries['customer']['name2'], 0, 25, 'UTF-8' ) ).'" />
				<input type="hidden" name="tel" value="'.esc_attr( str_replace( '-', '', $usces_entries['customer']['tel'] ) ).'" />
				<input type="hidden" name="mail" value="'.esc_attr( $usces_entries['customer']['mailaddress1'] ).'">
				<input type="hidden" name="rand" value="'.$rand.'">
				<input type="hidden" name="note" value="'.$rand.'">
				';
			if( 'on' == $acting_opts['quickcharge'] && $usces->is_member_logged_in() ) {
				$member = $usces->get_member();
				$html .= '<input type="hidden" name="CustomerId" value="'.esc_attr($member['ID']).'">';
			}
			$html .= '<div class="send"><input name="purchase_ali" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', ' onClick="document.charset=\'Shift_JIS\';"').$purchase_disabled.' /></div>';
			$html = apply_filters( 'usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled );
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters( 'usces_filter_confirm_inform_back', $html );
			$html .= '</form>';
			break;

		case 'acting_veritrans_card'://カード決済(ベリトランス)
		case 'acting_veritrans_conv'://コンビニ決済(ベリトランス)
			$acting_opts = $usces->options['acting_settings']['veritrans'];
			$usces->save_order_acting_data( $rand );
			usces_save_order_acting_data( $rand );
			$settlement_type = ( 'acting_veritrans_card' == $acting_flag ) ? '01' : '02';
			$amount = apply_filters( 'usces_filter_acting_amount', usces_crform( $usces_entries['order']['total_full_price'], false, false, 'return', false), $acting_flag );
			$ctx = hash_init( 'sha512' );
			$str = $acting_opts['merchanthash'].",".$acting_opts['merchant_id'].",".$settlement_type.",".$rand.",".$amount;
			hash_update( $ctx, $str );
			$hash = hash_final( $ctx, true );
			$merchanthash = bin2hex( $hash );
			$html .= '<form id="purchase_form" name="purchase_form" action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<input type="hidden" name="MERCHANTHASH" value="'.esc_attr( $merchanthash ).'">
				<input type="hidden" name="SETTLEMENT_TYPE" value="'.esc_attr( $settlement_type ).'">
				<input type="hidden" name="ORDER_ID" value="'.esc_attr( $rand ).'">
				<input type="hidden" name="AMOUNT" value="'.esc_attr( $amount ).'">
				';
			$html .= '<div class="send"><input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.apply_filters('usces_filter_confirm_nextbutton', '').$purchase_disabled.' /></div>';
			$html = apply_filters( 'usces_filter_confirm_inform', $html, $payments, $acting_flag, $rand, $purchase_disabled );
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' /></div>';
			$html = apply_filters( 'usces_filter_confirm_inform_back', $html );
			$html .= '</form>';
			break;

		default:
			$purchase_html .= '<form id="purchase_form" action="' . apply_filters('usces_filter_acting_url', USCES_CART_URL) . '" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<div class="send">
				'.apply_filters( 'usces_filter_confirm_before_backbutton', NULL, $payments, $acting_flag, $rand ).'
				<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__('Back', 'usces').'"'.apply_filters('usces_filter_confirm_prebutton', NULL).' />
				<input name="purchase" type="submit" id="purchase_button" class="checkout_button" value="'.$checkout_button_value.'"'.$purchase_disabled.' /></div>';
			$html .= apply_filters('usces_filter_confirm_inform', $purchase_html, $payments, $acting_flag, $rand, $purchase_disabled);
			$html .= '</form>';
	}
}
?>