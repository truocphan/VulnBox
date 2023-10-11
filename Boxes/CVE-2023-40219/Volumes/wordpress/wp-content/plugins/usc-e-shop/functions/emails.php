<?php
/**
 * Transaction of Emails
 *
 * Process all emails involved in the transaction using email templates.
 *
 * @package Welcart
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Management Mail
 * Generate the full text of the email sent from the management screen
 *
 * @param int $order_id Order ID.
 * @return text : mail ful text
 */
function usces_order_confirm_message( $order_id ) {
	global $usces;

	$data = $usces->get_order_data( $order_id, 'direct' );
	$deli = unserialize( $data['order_delivery'] );
	$cart = usces_get_ordercartdata( $order_id );

	$country  = $usces->get_order_meta_value( 'customer_country', $order_id );
	$customer = array(
		'name1'    => $data['order_name1'],
		'name2'    => $data['order_name2'],
		'name3'    => $data['order_name3'],
		'name4'    => $data['order_name4'],
		'zipcode'  => $data['order_zip'],
		'country'  => $country,
		'pref'     => $data['order_pref'],
		'address1' => $data['order_address1'],
		'address2' => $data['order_address2'],
		'address3' => $data['order_address3'],
		'tel'      => $data['order_tel'],
		'fax'      => $data['order_fax'],
	);

	$condition       = unserialize( $data['order_condition'] );
	$tax_display     = ( isset( $condition['tax_display'] ) ) ? $condition['tax_display'] : usces_get_tax_display();
	$reduced_taxrate = ( isset( $condition['applicable_taxrate'] ) && 'reduced' === $condition['applicable_taxrate'] ) ? true : false;
	$usces_tax       = null;
	if ( 'activate' === $tax_display && $reduced_taxrate ) {
		$usces_tax = Welcart_Tax::get_instance();
	}
	$member_system       = ( isset( $condition['membersystem_state'] ) ) ? $condition['membersystem_state'] : $usces->options['membersystem_state'];
	$member_system_point = ( isset( $condition['membersystem_point'] ) ) ? $condition['membersystem_point'] : $usces->options['membersystem_point'];
	$tax_mode            = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : usces_get_tax_mode();
	$tax_target          = ( isset( $condition['tax_target'] ) ) ? $condition['tax_target'] : usces_get_tax_target();
	$point_coverage      = ( isset( $condition['point_coverage'] ) ) ? $condition['point_coverage'] : usces_point_coverage();

	$total_full_price = $data['order_item_total_price'] - $data['order_usedpoint'] + $data['order_discount'] + $data['order_shipping_charge'] + $data['order_cod_fee'] + $data['order_tax'];
	if ( $total_full_price < 0 ) {
		$total_full_price = 0;
	}

	$mail_data = usces_mail_data();
	$payment   = $usces->getPayments( $data['order_payment_name'] );

	switch ( $_POST['mode'] ) {
		case 'completionMail':
			$mail_mode = 'completionmail';
			break;
		case 'orderConfirmMail':
			$mail_mode = 'ordermail';
			break;
		case 'changeConfirmMail':
			$mail_mode = 'changemail';
			break;
		case 'receiptConfirmMail':
			$mail_mode = 'receiptmail';
			break;
		case 'mitumoriConfirmMail':
			$mail_mode = 'mitumorimail';
			break;
		case 'cancelConfirmMail':
			$mail_mode = 'cancelmail';
			break;
		case 'otherConfirmMail':
			$mail_mode = 'othermail';
			break;
		default:
			$mail_mode = '';
	}

	$hook_args = compact( 'order_id', 'data', 'deli', 'cart', 'country', 'customer', 'condition', 'total_full_price', 'mail_data', 'payment', 'tax_display', 'reduced_taxrate', 'tax_mode', 'tax_target', 'usces_tax', 'point_coverage', 'member_system', 'member_system_point' );

	if ( usces_is_html_mail() ) {

		$msg_body = usces_get_adminmail_htmlbody( $hook_args );
		$msg_body = apply_filters( 'usces_filter_order_confirm_mail_bodyall', $msg_body, $data );

		$message  = '<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#eeeeee"><tbody><tr><td>';
		$message .= '<table style="font-size:15px; margin:30px auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff"><tbody>';

		if ( '' === $mail_mode ) {
			// add body.
			$message .= '<tr><td style="padding:20px 30px;">';
			$message .= apply_filters( 'usces_filter_order_confirm_mail_body_after', $msg_body, $data );
			$message .= '</td></tr>';
		} else {
			// add header.
			$message .= '<tr><td style="padding:20px 30px;">';
			$m_header = $mail_data['header'][ $mail_mode ];
			if ( 1 === (int) $usces->options['put_customer_name'] ) {
				// translators: %s: name of user.
				$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $data['order_name1'] ), trim( $data['order_name2'] ), 'return' ) );
				if ( false !== strpos( $m_header, '{customer_name}' ) ) {
					$m_header = str_replace( '{customer_name}', $dear_name, $m_header );
				} else {
					$message .= $dear_name . '<br>';
				}
			}
			$message .= do_shortcode( wpautop( $m_header ) );
			$message .= '</td></tr>';
			// add body.
			$message .= '<tr><td style="padding:20px 30px;">';
			$message .= apply_filters( 'usces_filter_order_confirm_mail_body_after', $msg_body, $data );
			$message .= '</td></tr>';
			// add footer.
			$message .= '<tr><td style="padding:20px 30px;">';
			$message .= do_shortcode( wpautop( $mail_data['footer'][ $mail_mode ] ) );
			$message .= '</td></tr>';
			$message .= '</tbody></table></td></tr></tbody></table>';
		}
	} else {
		$msg_body = usces_get_adminmail_textbody( $hook_args );
		$msg_body = apply_filters( 'usces_filter_order_confirm_mail_bodyall', $msg_body, $data );

		$message  = '';
		$m_header = $mail_data['header'][ $mail_mode ];
		if ( 1 === (int) $usces->options['put_customer_name'] ) {
			// translators: %s: name of user.
			$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $data['order_name1'] ), trim( $data['order_name2'] ), 'return' ) );
			if ( false !== strpos( $m_header, '{customer_name}' ) ) {
				$m_header = str_replace( '{customer_name}', $dear_name, $m_header );
			} else {
				$message = $dear_name . "\r\n\r\n";
			}
		}

		if ( '' === $mail_mode ) {
			$message .= apply_filters( 'usces_filter_order_confirm_mail_body_after', $msg_body, $data );
		} else {
			$message .= do_shortcode( $m_header ) . apply_filters( 'usces_filter_order_confirm_mail_body_after', $msg_body, $data ) . do_shortcode( $mail_data['footer'][ $mail_mode ] );
		}
	}

	return apply_filters( 'usces_filter_order_confirm_mail_message', $message, $data );
}

/**
 * Thanks Mail
 * Generate the full text of the email sent from the front checkout.
 *
 * @param int $order_id Order ID.
 * @return text : mail ful text
 */
function usces_send_ordermail( $order_id ) {
	global $usces;

	$data = $usces->get_order_data( $order_id, 'direct' );

	$newcart = usces_get_ordercartdata( $order_id );
	$cart    = $usces->cart->get_cart();

	$entry     = $usces->cart->get_entry();
	$mail_data = usces_mail_data();
	$payment   = $usces->getPayments( $entry['order']['payment_name'] );

	$hook_args = compact( 'order_id', 'cart', 'newcart', 'entry', 'payment', 'data' );
	$headers   = '';

	if ( usces_is_html_mail() ) {
		$headers = "Content-Type: text/html\r\n";

		$msg_body = usces_get_thanksmail_htmlbody( $hook_args );
		$message  = '<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#eeeeee"><tbody><tr><td>';
		$message .= '<table style="font-size:15px; margin:30px auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff"><tbody>';

		// add header.
		$message .= '<tr><td style="padding:20px 30px;">';
		$m_header = apply_filters( 'usces_filter_send_order_mail_header_thankyou', $mail_data['header']['thankyou'], $data );
		if ( 1 === (int) $usces->options['put_customer_name'] ) {
			// translators: %s: name of user.
			$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $data['order_name1'] ), trim( $data['order_name2'] ), 'return' ) );
			if ( false !== strpos( $m_header, '{customer_name}' ) ) {
				$m_header = str_replace( '{customer_name}', $dear_name, $m_header );
			} else {
				$message .= $dear_name . '<br>';
			}
		}
		$message .= do_shortcode( wpautop( $m_header ) );
		$message .= '</td></tr>';
		// add body.
		$message .= '<tr><td style="padding:20px 30px;">';
		$msg_body = apply_filters( 'usces_filter_send_order_mail_bodyall', $msg_body, $data );
		$message .= $msg_body . '</td></tr>';
		// add footer.
		$message .= '<tr><td style="padding:20px 30px;">';
		$m_footer = apply_filters( 'usces_filter_send_order_mail_footer_thankyou', $mail_data['footer']['thankyou'], $data );
		$message .= do_shortcode( wpautop( $m_footer ) );
		$message .= '</td></tr>';
		$message .= '</tbody></table>
			</td></tr></tbody></table>';
	} else {
		$msg_body = usces_get_thanksmail_textbody( $hook_args );
		$message  = '';
		$m_header = apply_filters( 'usces_filter_send_order_mail_header_thankyou', $mail_data['header']['thankyou'], $data );
		if ( 1 === (int) $usces->options['put_customer_name'] ) {
			// translators: %s: name of user.
			$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $data['order_name1'] ), trim( $data['order_name2'] ), 'return' ) );
			if ( false !== strpos( $m_header, '{customer_name}' ) ) {
				$m_header = str_replace( '{customer_name}', $dear_name, $m_header );
			} else {
				$message .= $dear_name . "\r\n\r\n";
			}
		}
		$m_footer = apply_filters( 'usces_filter_send_order_mail_footer_thankyou', $mail_data['footer']['thankyou'], $data );
		$msg_body = apply_filters( 'usces_filter_send_order_mail_bodyall', $msg_body, $data );
		$message .= do_shortcode( $m_header ) . $msg_body . do_shortcode( $m_footer );
	}

	// translators: %s: name of user.
	$name    = sprintf( _x( '%s', 'honorific', 'usces' ), usces_localized_name( trim( $entry['customer']['name1'] ), trim( $entry['customer']['name2'] ), 'return' ) );
	$subject = apply_filters( 'usces_filter_send_order_mail_subject_thankyou', $mail_data['title']['thankyou'], $data );

	$confirm_para = array(
		'to_name'      => $name,
		'to_address'   => $entry['customer']['mailaddress1'],
		'from_name'    => get_option( 'blogname' ),
		'from_address' => $usces->options['sender_mail'],
		'return_path'  => $usces->options['sender_mail'],
		'subject'      => $subject,
		'message'      => $message,
		'headers'      => $headers,
	);
	$confirm_para = apply_filters( 'usces_send_ordermail_para_to_customer', $confirm_para, $entry, $data );

	$res1 = usces_send_mail( $confirm_para );

	do_action( 'usces_action_after_send_ordermail_to_customer', $res1, $confirm_para, $entry, $data );

	$subject = apply_filters( 'usces_filter_send_order_mail_subject_order', ( $mail_data['title']['order'] . ' [' . $order_id . '] ' . $name ), $data );
	if ( usces_is_html_mail() ) {

		$message  = '<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#eeeeee"><tbody><tr><td>';
		$message .= '<table style="font-size:15px; margin:30px auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff"><tbody>';

		// add header.
		$message .= '<tr><td style="padding:20px 30px;">';
		$message .= do_shortcode( wpautop( $mail_data['header']['order'] ) );
		$message .= '</td></tr>';
		// add body.
		$message .= '<tr><td style="padding:20px 30px;">';
		$message .= $msg_body;
		$message .= '</td></tr>';
		// add footer.
		$message .= '<tr><td style="padding:20px 30px;">';
		$message .= do_shortcode( wpautop( $mail_data['footer']['order'] ) );
		$message .= '<p>';
		$message .= '----------------------------------------------------<br>';
		$message .= 'REMOTE_ADDR : ' . $_SERVER['REMOTE_ADDR'] . '<br>';
		$message .= '----------------------------------------------------<br>';
		$message .= '</p>';
		$message .= '</td></tr>';
		$message .= '</tbody></table></td></tr></tbody></table>';
	} else {
		$message = do_shortcode( $mail_data['header']['order'] ) . $msg_body
		. $mail_data['footer']['order']
		. "\r\n----------------------------------------------------\r\n"
		. 'REMOTE_ADDR : ' . $_SERVER['REMOTE_ADDR']
		. "\r\n----------------------------------------------------\r\n";
	}

	$order_para = array(
		'to_name'      => __( 'An order email', 'usces' ),
		'to_address'   => $usces->options['order_mail'],
		'from_name'    => get_option( 'blogname' ),
		'from_address' => $usces->options['sender_mail'],
		'return_path'  => $usces->options['sender_mail'],
		'subject'      => $subject,
		'message'      => $message,
		'headers'      => $headers,
	);

	$order_para = apply_filters( 'usces_send_ordermail_para_to_manager', $order_para, $entry, $data );

	$res2 = usces_send_mail( $order_para );

	do_action( 'usces_action_after_send_ordermail_to_manager', $res2, $order_para, $entry, $data );

	return $res2;
}

/**
 * Generate html for the body of the thanks mail
 *
 * @param array $args {
 *     The array of mail data.
 *     @type int    $order_id Order ID.
 *     @type array  $cart     Cart data.
 *     @type array  $newcart  New cart data.
 *     @type array  $entry    Entry data.
 *     @type array  $payment  Payment data.
 *     @type array  $data     Order data.
 * }
 * @return text : mail body
 */
function usces_get_thanksmail_htmlbody( $args ) {
	global $usces;
	extract( $args );

	$reduced_taxrate = usces_is_reduced_taxrate();
	if ( usces_is_tax_display() && $reduced_taxrate ) {
		$usces_tax = Welcart_Tax::get_instance();
	}

	$msg_body  = '';
	$msg_body .= apply_filters( 'usces_filter_send_order_mail_body_top', '', $args );

	$msg_body .= '<hr style="margin: 0 0 50px; border-style: none; border-top: 3px solid #777;" />';

	// Title and order number.
	$msg_body .= '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse; border: 1px solid #ddd;">';
	$msg_body .= '<caption style="background-color: #111; margin-bottom: 40px; padding: 15px; color: #fff; font-size: 15px; font-weight: 700; text-align: left;">' . __( '** Article order contents **', 'usces' ) . '</caption>';
	$msg_body .= '<tbody>';
	$msg_body .= apply_filters( 'usces_filter_send_order_mail_first', '', $data );
	$msg_body .= '<tr>';
	$msg_body .= '<td style="background-color: #f9f9f9; padding: 12px; width: 50%; border: 1px solid #ddd;">' . __( 'order date', 'usces' ) . '</td>';
	$msg_body .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . $data['order_date'] . '</td>';
	$msg_body .= '</tr>';
	$msg_body .= '<tr>';
	$msg_body .= '<td style="background-color: #f9f9f9; padding: 12px; width: 50%; border: 1px solid #ddd;">' . __( 'Order number', 'usces' ) . '</td>';
	$msg_body .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . usces_get_deco_order_id( $order_id ) . '</td>';
	$msg_body .= '</tr>';
	$msg_body .= '</tbody>';
	$msg_body .= '</table>';

	// Purchase details.
	$meisai  = '<table style="font-size: 14px; width: 100%; border-collapse: collapse; border: 1px solid #ddd;">';
	$meisai .= '<thead>';
	$meisai .= '<tr>';
	$meisai .= '<td style="text-align: center; width: 50%; padding: 12px; border: 1px solid #ddd;">' . __( 'Items', 'usces' ) . '</td>';
	$meisai .= '<td style="text-align: center; width: 25%; padding: 12px; border: 1px solid #ddd;">' . __( 'Unit price', 'usces' ) . '</td>';
	$meisai .= '<td style="text-align: center; width: 25%; padding: 12px; border: 1px solid #ddd;">' . __( 'Quantity', 'usces' ) . '</td>';
	$meisai .= '</tr>';
	$meisai .= '</thead>';
	$meisai .= '<tbody>';

	foreach ( $cart as $cart_key => $cart_row ) {
		$cart_row['cart_id'] = $newcart[ $cart_key ]['cart_id'];
		$post_id             = $cart_row['post_id'];
		$sku                 = urldecode( $cart_row['sku'] );
		$quantity            = $cart_row['quantity'];
		$options             = ( ! empty( $cart_row['options'] ) ) ? $cart_row['options'] : array();
		$cart_item_name      = $usces->getCartItemName( $post_id, $sku );
		if ( usces_is_tax_display() && $reduced_taxrate ) {
			$applicable_taxrate = $usces_tax->get_sku_applicable_taxrate( $post_id, $sku );
			if ( 'reduced' === $applicable_taxrate ) {
				$cart_item_name .= $usces_tax->reduced_taxrate_mark;
			}
		}
		$sku_price = $cart_row['price'];
		$args      = compact( 'cart', 'cart_row', 'post_id', 'sku' );
		$meisai   .= '<tr>';
		$meisai   .= '<td style="width: 50%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">';
		$meisai   .= apply_filters( 'usces_filter_cart_item_name_nl', $cart_item_name, $args ) . '<br>';
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$optstr = '';
			foreach ( $options as $key => $value ) {
				if ( ! empty( $key ) ) {
					$key = urldecode( $key );
					if ( is_array( $value ) ) {
						$c       = '';
						$optstr .= $key . ' : ';
						foreach ( $value as $v ) {
							$optstr .= $c . urldecode( $v );
							$c       = ', ';
						}
						$optstr .= '<br>';
					} else {
						$optstr .= $key . ' : ' . urldecode( $value ) . '<br>';
					}
				}
			}
			$meisai .= apply_filters( 'usces_filter_option_ordermail', $optstr, $options, $newcart[ $cart_key ] );
		}
		$meisai .= apply_filters( 'usces_filter_advance_ordermail', '', $newcart[ $cart_key ], $data );
		$meisai .= '</td>';
		$meisai .= '<td style="text-align: center; width: 25%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $sku_price, true, false, 'return' ) . '</td>';
		$meisai .= '<td style="text-align: center; width: 25%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . $cart_row['quantity'] . '</td>';
		$meisai .= '</tr>';
	}

	$meisai .= '</tbody><tfoot>';
	$meisai .= '<tr>';
	$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'total items', 'usces' ) . '</td>';
	$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $entry['order']['total_items_price'], true, false, 'return' ) . '</td>';
	$meisai .= '</tr>';

	if ( 0.0 !== (float) $entry['order']['discount'] ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . apply_filters( 'usces_confirm_discount_label', __( 'Campaign discount', 'usces' ), $order_id ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $entry['order']['discount'], true, false, 'return' ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( usces_is_tax_display() && 'products' === usces_get_tax_target() ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_mail_tax_label( $data ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_mail_tax( $entry ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( usces_is_member_system() && usces_is_member_system_point() && 0 === (int) usces_point_coverage() && 0 !== (int) $entry['order']['usedpoint'] ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'use of points', 'usces' ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . number_format( $entry['order']['usedpoint'] ) . __( 'Points', 'usces' ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( usces_have_shipped( $cart ) ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'Shipping', 'usces' ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $entry['order']['shipping_charge'], true, false, 'return' ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( 0 < $entry['order']['cod_fee'] ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $order_id ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $entry['order']['cod_fee'], true, false, 'return' ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( usces_is_tax_display() && 'all' === usces_get_tax_target() ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_mail_tax_label( $data ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_mail_tax( $entry ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( usces_is_member_system() && usces_is_member_system_point() && 1 === (int) usces_point_coverage() && 0 !== (int) $entry['order']['usedpoint'] ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'use of points', 'usces' ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . number_format( $entry['order']['usedpoint'] ) . __( 'Points', 'usces' ) . '</td>';
		$meisai .= '</tr>';
	}

	$meisai .= '<tr>';
	$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'Payment amount', 'usces' ) . '</td>';
	$meisai .= '<td style="text-align: right; width: 75%; font-weight: 700; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $entry['order']['total_full_price'], true, false, 'return' ) . '</td>';
	$meisai .= '</tr>';
	$meisai .= '</tfoot></table>';
	$meisai .= '<p style="margin-top: 10px; font-size: 13px;">(' . __( 'Currency', 'usces' ) . ' : ' . __( usces_crcode( 'return' ), 'usces' ) . ')</p>';

	if ( usces_is_tax_display() && $reduced_taxrate ) {
		$condition = $usces->get_condition();
		if ( 'include' === $condition['tax_mode'] ) {
			$po = '(';
			$pc = ')';
		} else {
			$po = '';
			$pc = '';
		}
		$meisai .= '<p style="margin-top: 10px; font-size: 13px;">';
		$meisai .= $usces_tax->reduced_taxrate_mark . __( ' is reduced tax rate', 'usces' ) . '<br>';
		$meisai .= sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) . ' : ' . usces_crform( $usces_tax->subtotal_standard + $usces_tax->discount_standard, true, false, 'return' ) . '<br>'; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) . ' : ' . $po . usces_crform( $usces_tax->tax_standard, true, false, 'return' ) . $pc . '<br>'; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) . ' : ' . usces_crform( $usces_tax->subtotal_reduced + $usces_tax->discount_reduced, true, false, 'return' ) . '<br>'; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) . ' : ' . $po . usces_crform( $usces_tax->tax_reduced, true, false, 'return' ) . $pc . '<br>'; /* translators: %s is replaced with "string" */
		$meisai .= '</p>';
	}

	$msg_body .= apply_filters( 'usces_filter_send_order_mail_meisai', $meisai, $data, $cart, $entry );
	$msg_body .= '<hr style="margin: 40px 0 30px; border-style: none; border-top: 1px solid #ddd;" />' . "\r\n";

	// Buyer information.
	$msg_body .= '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse;">';
	$msg_body .= '<caption style="font-size: 15px; font-weight: 700; text-align: left; margin-bottom: 15px;">' . __( '** Customer information **', 'usces' ) . '</caption>';
	$msg_body .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
	$msg_body .= '<table style="width: 100%;"><tbody>';

	$msg_body .= uesces_get_mail_addressform( 'order_mail_customer', $entry, $order_id );

	$msg_body .= '</tbody></table></td></tr></tbody></table>';

	// Payment information.
	$msg_body .= '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse;">';
	$msg_body .= '<caption style="font-size: 15px; font-weight: 700; text-align: left; margin-bottom: 15px;">' . __( '** Payment method **', 'usces' ) . '</caption>';
	$msg_body .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
	$msg_body .= '<table style="width: 100%;"><tbody>';

	$msg_payment = '<tr><td colspan="2" style="padding: 0 0 25px 0;">' . $payment['name'] . usces_payment_detail( $entry ) . '</td></tr>';
	if ( 'transferAdvance' === $payment['settlement'] && isset( $usces->options['transferee'] ) ) {
		$transferee   = '<td style="padding: 30px 0 10px; text-align: left; width: 100px; font-weight: normal; border-top: 1px dotted #ccc; vertical-align: text-top;">' . __( 'Transfer', 'usces' ) . '</td>';
		$transferee  .= '<td style="padding: 30px 0 10px 50px; width: calc( 100% - 100px ); border-top: 1px dotted #ccc; vertical-align: text-top;">' . wpautop( $usces->options['transferee'] ) . '</td>';
		$msg_payment .= '<tr>';
		$msg_payment .= apply_filters( 'usces_filter_mail_transferee', $transferee, $payment, $order_id );
		$msg_payment .= '</tr>';
	}
	$msg_body .= apply_filters( 'usces_filter_send_order_mail_payment', $msg_payment, $order_id, $payment, $cart, $entry, $data );
	$msg_body .= '</tbody></table></td></tr></tbody></table>';

	$msg_body .= '<hr style="margin: 40px 0 30px; border-style: none; border-top: 1px solid #ddd;" />';

	// Delivery information.
	$msg_shipping  = '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse;">';
	$msg_shipping .= '<caption style="font-size: 15px; font-weight: 700; text-align: left; margin-bottom: 15px;">' . __( '** Shipping information **', 'usces' ) . '</caption>';
	$msg_shipping .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';

	$msg_shipping .= '<table style="width: 100%;"><tbody>';

	$deli_meth = (int) $entry['order']['delivery_method'];
	if ( 0 <= $deli_meth ) {
		$deli_index = $usces->get_delivery_method_index( $deli_meth );
		if ( 0 <= $deli_index ) {
			$msg_shipping .= '<tr>';
			$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Delivery Method', 'usces' ) . '</td>';
			$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $usces->options['delivery_method'][ $deli_index ]['name'] . '</td>';
			$msg_shipping .= '</tr>';
		}
	}
	$msg_shipping .= '<tr>';
	$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Delivery date', 'usces' ) . '</td>';
	$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $entry['order']['delivery_date'] . '</td>';
	$msg_shipping .= '</tr>';
	$msg_shipping .= '<tr>';
	$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Delivery Time', 'usces' ) . '</td>';
	$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $entry['order']['delivery_time'] . '</td>';
	$msg_shipping .= '</tr>';

	$msg_shipping .= '</tbody></table>';

	$msg_shipping .= '<hr style="margin: 30px 0 30px; border-style: none; border-top: 1px dotted #ccc;" />';

	$msg_shipping .= '<table style="width: 100%;"><tbody>';
	$msg_shipping .= uesces_get_mail_addressform( 'order_mail', $entry, $order_id );
	$msg_shipping .= '</tbody></table></td></tr></tbody></table>';
	$msg_shipping .= '<hr style="margin: 40px 0 30px; border-style: none; border-top: 1px solid #ddd;" />';
	$msg_body     .= apply_filters( 'usces_filter_send_order_mail_shipping', $msg_shipping, $data, $entry );

	$csod_meta = usces_has_custom_field_meta( 'order' );
	if ( ! empty( $csod_meta ) ) {
		$msg_body .= '<table style="font-size: 14px; width: 100%; border-collapse: collapse;">';
		$msg_body .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
		$msg_body .= '<table style="width: 100%;"><tbody>';
		$msg_body .= usces_mail_custom_field_info( 'order', '', $order_id, $entry['customer']['mailaddress1'] );
		$msg_body .= '</tbody></table></td></tr></tbody></table>';
		$msg_body .= '<hr style="margin: 40px 0 30px; border-style: none; border-top: 1px solid #ddd;" />';
	}

	$msg_others  = '<table style="font-size: 14px; width: 100%; border-collapse: collapse;">';
	$msg_others .= '<caption style="font-size: 15px; font-weight: 700; text-align: left; margin-bottom: 15px;">' . __( '** Others / a demand **', 'usces' ) . '</caption>';
	$msg_others .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
	$msg_others .= '<table style="width: 100%;"><tbody>';
	$msg_others .= '<tr><td colspan="2" style="padding: 0;">' . wpautop( $entry['order']['note'] ) . '</td></tr>';
	$msg_others .= '</tbody></table></td></tr></tbody></table>';

	$msg_body .= apply_filters( 'usces_filter_send_order_mail_others', $msg_others, $data );

	$msg_body .= '<hr style="margin: 50px 0 0; border-style: none; border-top: 3px solid #777;" />';

	$msg_body .= apply_filters( 'usces_filter_send_order_mail_body', null, $data );

	return $msg_body;
}

/**
 * Generate text for the body of the thanks mail
 *
 * @param array $args {
 *     The array of mail data.
 *     @type int    $order_id Order ID.
 *     @type array  $cart     Cart data.
 *     @type array  $newcart  New cart data.
 *     @type array  $entry    Entry data.
 *     @type array  $payment  Payment data.
 *     @type array  $data     Order data.
 * }
 * @return text : mail body
 */
function usces_get_thanksmail_textbody( $args ) {
	global $usces;
	extract( $args );

	$reduced_taxrate = usces_is_reduced_taxrate();
	if ( usces_is_tax_display() && $reduced_taxrate ) {
		$usces_tax = Welcart_Tax::get_instance();
	}

	$msg_body  = '';
	$msg_body  = "\r\n\r\n\r\n" . __( '** content of ordered items **', 'usces' ) . "\r\n";
	$msg_body .= usces_mail_line( 1, $entry['customer']['mailaddress1'] ); // ********************
	$msg_body .= apply_filters( 'usces_filter_send_order_mail_first', null, $data );
	$msg_body .= uesces_get_mail_addressform( 'order_mail_customer', $entry, $order_id );
	$msg_body .= __( 'Order number', 'usces' ) . ' : ' . usces_get_deco_order_id( $order_id ) . "\r\n";
	$msg_body .= __( 'order date', 'usces' ) . ' : ' . $data['order_date'] . "\r\n";

	$meisai = __( 'Items', 'usces' ) . " :\r\n";
	foreach ( $cart as $cart_key => $cart_row ) {
		$cart_row['cart_id'] = $newcart[ $cart_key ]['cart_id'];
		$post_id             = $cart_row['post_id'];
		$sku                 = urldecode( $cart_row['sku'] );
		$quantity            = $cart_row['quantity'];
		$options             = ( ! empty( $cart_row['options'] ) ) ? $cart_row['options'] : array();
		$cart_item_name      = $usces->getCartItemName( $post_id, $sku );
		if ( usces_is_tax_display() && $reduced_taxrate ) {
			$applicable_taxrate = $usces_tax->get_sku_applicable_taxrate( $post_id, $sku );
			if ( 'reduced' === $applicable_taxrate ) {
				$cart_item_name .= $usces_tax->reduced_taxrate_mark;
			}
		}
		$sku_price = $cart_row['price'];
		$args      = compact( 'cart', 'cart_row', 'post_id', 'sku' );
		$meisai   .= usces_mail_line( 2, $entry['customer']['mailaddress1'] ); // --------------------
		$meisai   .= apply_filters( 'usces_filter_cart_item_name_nl', $cart_item_name, $args ) . "\r\n";
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$optstr = '';
			foreach ( $options as $key => $value ) {
				if ( ! empty( $key ) ) {
					$key = urldecode( $key );
					if ( is_array( $value ) ) {
						$c       = '';
						$optstr .= $key . ' : ';
						foreach ( $value as $v ) {
							$optstr .= $c . urldecode( $v );
							$c       = ', ';
						}
						$optstr .= "\r\n";
					} else {
						$optstr .= $key . ' : ' . urldecode( $value ) . "\r\n";
					}
				}
			}
			$meisai .= apply_filters( 'usces_filter_option_ordermail', $optstr, $options, $newcart[ $cart_key ] );
		}
		$meisai .= apply_filters( 'usces_filter_advance_ordermail', '', $newcart[ $cart_key ], $data );
		$meisai .= __( 'Unit price', 'usces' ) . ' ' . usces_crform( $sku_price, true, false, 'return' ) . __( ' * ', 'usces' ) . $cart_row['quantity'] . "\r\n";
	}
	$meisai .= usces_mail_line( 3, $entry['customer']['mailaddress1'] ); // ====================
	$meisai .= __( 'total items', 'usces' ) . ' : ' . usces_crform( $entry['order']['total_items_price'], true, false, 'return' ) . "\r\n";

	if ( 0.0 !== (float) $entry['order']['discount'] ) {
		$meisai .= apply_filters( 'usces_confirm_discount_label', __( 'Campaign discount', 'usces' ), $order_id ) . ' : ' . usces_crform( $entry['order']['discount'], true, false, 'return' ) . "\r\n";
	}

	if ( usces_is_tax_display() && 'products' === usces_get_tax_target() ) {
		$meisai .= usces_mail_tax_label( $data );
		if ( 'exclude' === usces_get_tax_mode() ) {
			$meisai .= ' : ' . usces_mail_tax( $entry );
		}
		$meisai .= "\r\n";
	}

	if ( usces_is_member_system() && usces_is_member_system_point() && 0 === (int) usces_point_coverage() && 0 !== (int) $entry['order']['usedpoint'] ) {
		$meisai .= __( 'use of points', 'usces' ) . ' : ' . number_format( $entry['order']['usedpoint'] ) . __( 'Points', 'usces' ) . "\r\n";
	}

	if ( usces_have_shipped( $cart ) ) {
		$meisai .= __( 'Shipping', 'usces' ) . ' : ' . usces_crform( $entry['order']['shipping_charge'], true, false, 'return' ) . "\r\n";
	}

	if ( 0 < $entry['order']['cod_fee'] ) {
		$meisai .= apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $order_id ) . ' : ' . usces_crform( $entry['order']['cod_fee'], true, false, 'return' ) . "\r\n";
	}

	if ( usces_is_tax_display() && 'all' === usces_get_tax_target() ) {
		$meisai .= usces_mail_tax_label( $data );
		if ( 'exclude' === usces_get_tax_mode() ) {
			$meisai .= ' : ' . usces_mail_tax( $entry );
		}
		$meisai .= "\r\n";
	}

	if ( usces_is_member_system() && usces_is_member_system_point() && 1 === (int) usces_point_coverage() && 0 !== (int) $entry['order']['usedpoint'] ) {
		$meisai .= __( 'use of points', 'usces' ) . ' : ' . number_format( $entry['order']['usedpoint'] ) . __( 'Points', 'usces' ) . "\r\n";
	}

	$meisai .= usces_mail_line( 2, $entry['customer']['mailaddress1'] ); // --------------------
	$meisai .= __( 'Payment amount', 'usces' ) . ' : ' . usces_crform( $entry['order']['total_full_price'], true, false, 'return' ) . "\r\n";
	$meisai .= usces_mail_line( 2, $entry['customer']['mailaddress1'] ); // --------------------
	if ( usces_is_tax_display() && $reduced_taxrate ) {
		$condition = $usces->get_condition();
		if ( 'include' === $condition['tax_mode'] ) {
			$po = '(';
			$pc = ')';
		} else {
			$po = '';
			$pc = '';
		}
		$meisai .= sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) . ' : ' . usces_crform( $usces_tax->subtotal_standard + $usces_tax->discount_standard, true, false, 'return' ) . "\r\n"; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) . ' : ' . $po . usces_crform( $usces_tax->tax_standard, true, false, 'return' ) . $pc . "\r\n"; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) . ' : ' . usces_crform( $usces_tax->subtotal_reduced + $usces_tax->discount_reduced, true, false, 'return' ) . "\r\n"; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) . ' : ' . $po . usces_crform( $usces_tax->tax_reduced, true, false, 'return' ) . $pc . "\r\n"; /* translators: %s is replaced with "string" */
		$meisai .= usces_mail_line( 2, $data['order_email'] ); // --------------------
		$meisai .= $usces_tax->reduced_taxrate_mark . __( ' is reduced tax rate', 'usces' ) . "\r\n";
	}
	$meisai .= '(' . __( 'Currency', 'usces' ) . ' : ' . __( usces_crcode( 'return' ), 'usces' ) . ")\r\n\r\n";

	$msg_body .= apply_filters( 'usces_filter_send_order_mail_meisai', $meisai, $data, $cart, $entry );

	$msg_shipping  = __( '** Shipping information **', 'usces' ) . "\r\n";
	$msg_shipping .= usces_mail_line( 1, $entry['customer']['mailaddress1'] ); // ********************

	$msg_shipping .= uesces_get_mail_addressform( 'order_mail', $entry, $order_id );

	$deli_meth = (int) $entry['order']['delivery_method'];
	if ( 0 <= $deli_meth ) {
		$deli_index = $usces->get_delivery_method_index( $deli_meth );
		if ( 0 <= $deli_index ) {
			$msg_shipping .= __( 'Delivery Method', 'usces' ) . ' : ' . $usces->options['delivery_method'][ $deli_index ]['name'] . "\r\n";
		}
	}
	$msg_shipping .= __( 'Delivery date', 'usces' ) . ' : ' . $entry['order']['delivery_date'] . "\r\n";
	$msg_shipping .= __( 'Delivery Time', 'usces' ) . ' : ' . $entry['order']['delivery_time'] . "\r\n";
	$msg_shipping .= "\r\n";
	$msg_body     .= apply_filters( 'usces_filter_send_order_mail_shipping', $msg_shipping, $data, $entry );

	$msg_payment  = __( '** Payment method **', 'usces' ) . "\r\n";
	$msg_payment .= usces_mail_line( 1, $entry['customer']['mailaddress1'] ); // ********************
	$msg_payment .= $payment['name'] . usces_payment_detail( $entry ) . "\r\n\r\n";
	if ( 'transferAdvance' === $payment['settlement'] && isset( $usces->options['transferee'] ) ) {
		$transferee   = __( 'Transfer', 'usces' ) . " : \r\n";
		$transferee  .= $usces->options['transferee'] . "\r\n";
		$msg_payment .= apply_filters( 'usces_filter_mail_transferee', $transferee, $payment, $order_id );
		$msg_payment .= "\r\n" . usces_mail_line( 2, $entry['customer']['mailaddress1'] ) . "\r\n"; // --------------------
	}

	$msg_body .= apply_filters( 'usces_filter_send_order_mail_payment', $msg_payment, $order_id, $payment, $cart, $entry, $data );

	$msg_body .= usces_mail_custom_field_info( 'order', '', $order_id );

	$msg_others  = "\r\n";
	$msg_others .= __( '** Others / a demand **', 'usces' ) . "\r\n";
	$msg_others .= usces_mail_line( 1, $entry['customer']['mailaddress1'] ); // ********************
	$msg_others .= $entry['order']['note'] . "\r\n\r\n";
	$msg_body   .= apply_filters( 'usces_filter_send_order_mail_others', $msg_others, $data );

	$msg_body .= apply_filters( 'usces_filter_send_order_mail_body', null, $data );

	return $msg_body;
}

/**
 * Generate html for the body of the admin mail
 *
 * @param array $args {
 *     The array of mail data.
 *     @type int    $order_id            Order ID.
 *     @type array  $data                Order data.
 *     @type array  $deli                Delivery data.
 *     @type array  $cart                Cart data.
 *     @type string $country             Country.
 *     @type array  $customer            Customer data.
 *     @type array  $condition           Condition data.
 *     @type float  $total_full_price    Total amount.
 *     @type array  $mail_data           Mail data.
 *     @type array  $payment             Payment data.
 *     @type bool   $tax_display         Is tax display.
 *     @type bool   $reduced_taxrate     Is reduced taxrate.
 *     @type string $tax_mode            Tax mode.
 *     @type string $tax_target          Tax target.
 *     @type object $usces_tax           Tax class.
 *     @type int    $point_coverage      Point coverage.
 *     @type string $member_system       Use member system.
 *     @type string $member_system_point Use member point.
 * }
 * @return text : mail body
 */
function usces_get_adminmail_htmlbody( $args ) {
	global $usces;
	extract( $args );

	$mail_mode = ( isset( $_POST['mode'] ) ) ? wp_unslash( $_POST['mode'] ) : '';

	$msg_body  = '';
	$msg_body .= apply_filters( 'usces_filter_order_confirm_body_top', $msg_body, $args );

	$msg_body .= '<hr style="margin: 0 0 50px; border-style: none; border-top: 3px solid #777;" />';

	// Tilt and order number.

	if ( 'mitumoriConfirmMail' === $mail_mode ) {
		$msg_body .= '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse; border: 1px solid #ddd;">';
		$msg_body .= '<caption style="background-color: #111; margin-bottom: 40px; padding: 15px; color: #fff; font-size: 15px; font-weight: 700; text-align: left;">' . __( 'Estimate', 'usces' ) . '</caption>';
		$msg_body .= '<tbody>';
		$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_first', null, $data );
		$msg_body .= '<tr>';
		$msg_body .= '<td style="background-color: #f9f9f9; padding: 12px; width: 50%; border: 1px solid #ddd;">' . __( 'estimate number', 'usces' ) . '</td>';
		$msg_body .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . usces_get_deco_order_id( $order_id ) . '</td>';
		$msg_body .= '</tr>';
		$msg_body .= '</tbody>';
		$msg_body .= '</table>';
	} else {
		$msg_body .= '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse; border: 1px solid #ddd;">';
		$msg_body .= '<caption style="background-color: #111; margin-bottom: 40px; padding: 15px; color: #fff; font-size: 15px; font-weight: 700; text-align: left;">' . __( '** Article order contents **', 'usces' ) . '</caption>';
		$msg_body .= '<tbody>';
		$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_first', null, $data );
		$msg_body .= '<tr>';
		$msg_body .= '<td style="background-color: #f9f9f9; padding: 12px; width: 50%; border: 1px solid #ddd;">' . __( 'order date', 'usces' ) . '</td>';
		$msg_body .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . $data['order_date'] . '</td>';
		$msg_body .= '</tr>';
		$msg_body .= '<tr>';
		$msg_body .= '<td style="background-color: #f9f9f9; padding: 12px; width: 50%; border: 1px solid #ddd;">' . __( 'Order number', 'usces' ) . '</td>';
		$msg_body .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . usces_get_deco_order_id( $order_id ) . '</td>';
		$msg_body .= '</tr>';
		$msg_body .= '</tbody>';
		$msg_body .= '</table>';
	}

	// Purchase details.
	$meisai = '<table style="font-size: 14px; width: 100%; border-collapse: collapse; border: 1px solid #ddd;">';
	$meisai .= '<thead>';
	$meisai .= '<tr>';
	$meisai .= '<td style="text-align: center; width: 50%; padding: 12px; border: 1px solid #ddd;">' . __( 'Items', 'usces' ) . '</td>';
	$meisai .= '<td style="text-align: center; width: 25%; padding: 12px; border: 1px solid #ddd;">' . __( 'Unit price', 'usces' ) . '</td>';
	$meisai .= '<td style="text-align: center; width: 25%; padding: 12px; border: 1px solid #ddd;">' . __( 'Quantity', 'usces' ) . '</td>';
	$meisai .= '</tr>';
	$meisai .= '</thead>';
	$meisai .= '<tbody>';

	foreach ( (array) $cart as $cart_row ) {
		$post_id        = $cart_row['post_id'];
		$sku            = urldecode( $cart_row['sku'] );
		$quantity       = $cart_row['quantity'];
		$options        = ( ! empty( $cart_row['options'] ) ) ? $cart_row['options'] : array();
		$cart_item_name = $usces->getCartItemName_byOrder( $cart_row );
		if ( 'activate' === $tax_display && $reduced_taxrate ) {
			$applicable_taxrate = $usces_tax->get_ordercart_applicable_taxrate( $cart_row['cart_id'], $post_id, $sku );
			if ( 'reduced' === $applicable_taxrate ) {
				$cart_item_name .= $usces_tax->reduced_taxrate_mark;
			}
		}
		$sku_price = $cart_row['price'];
		$args      = compact( 'cart', 'cart_row', 'post_id', 'sku' );

		$meisai .= '<tr>';
		$meisai .= '<td style="width: 50%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">';
		$meisai .= apply_filters( 'usces_filter_cart_item_name_nl', $cart_item_name, $args ) . '<br>';
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$optstr = '';
			foreach ( $options as $key => $value ) {
				if ( ! empty( $key ) ) {
					$key   = urldecode( $key );
					$value = maybe_unserialize( $value );
					if ( is_array( $value ) ) {
						$c       = '';
						$optstr .= $key . ' : ';
						foreach ( $value as $v ) {
							$optstr .= $c . rawurldecode( $v );
							$c       = ', ';
						}
						$optstr .= '<br>';
					} else {
						$optstr .= $key . ' : ' . rawurldecode( $value ) . '<br>';
					}
				}
			}
			$meisai .= apply_filters( 'usces_filter_option_adminmail', $optstr, $options, $cart_row );
		}
		$meisai .= apply_filters( 'usces_filter_advance_adminmail', '', $cart_row, $data );
		$meisai .= '</td>';
		$meisai .= '<td style="text-align: center; width: 25%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $sku_price, true, false, 'return' ) . '</td>';
		$meisai .= '<td style="text-align: center; width: 25%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . $cart_row['quantity'] . '</td>';
		$meisai .= '</tr>';
	}

	$meisai .= '</tbody><tfoot>';
	$meisai .= '<tr>';
	$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'total items', 'usces' ) . '</td>';
	$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $data['order_item_total_price'], true, false, 'return' ) . '</td>';
	$meisai .= '</tr>';

	if ( 0.0 !== (float) $data['order_discount'] ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . apply_filters( 'usces_confirm_discount_label', __( 'Campaign discount', 'usces' ), $order_id ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $data['order_discount'], true, false, 'return' ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( 'activate' === $tax_display && 'products' === $tax_target ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_mail_tax_label( $data ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_mail_tax( $data ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( 'activate' === $member_system && 'activate' === $member_system_point && 0 === (int) $point_coverage && 0 !== (int) $data['order_usedpoint'] ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'use of points', 'usces' ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . number_format( $data['order_usedpoint'] ) . __( 'Points', 'usces' ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( usces_have_shipped( $cart ) ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'Shipping', 'usces' ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $data['order_shipping_charge'], true, false, 'return' ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( 0 < $data['order_cod_fee'] ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $order_id ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $data['order_cod_fee'], true, false, 'return' ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( 'activate' === $tax_display && 'all' === $tax_target ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_mail_tax_label( $data ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_mail_tax( $data ) . '</td>';
		$meisai .= '</tr>';
	}

	if ( 'activate' === $member_system && 'activate' === $member_system_point && 1 === (int) $point_coverage && 0 !== (int) $data['order_usedpoint'] ) {
		$meisai .= '<tr>';
		$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'use of points', 'usces' ) . '</td>';
		$meisai .= '<td style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . number_format( $data['order_usedpoint'] ) . __( 'Points', 'usces' ) . '</td>';
		$meisai .= '</tr>';
	}

	$meisai .= '<tr>';
	$meisai .= '<td colspan="2" style="text-align: right; width: 75%; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . __( 'Payment amount', 'usces' ) . '</td>';
	$meisai .= '<td style="text-align: right; width: 75%; font-weight: 700; padding: 12px; border: 1px solid #ddd; vertical-align: middle;">' . usces_crform( $total_full_price, true, false, 'return' ) . '</td>';
	$meisai .= '</tr>';
	$meisai .= '</tfoot></table>';
	$meisai .= '<p style="margin-top: 10px; font-size: 13px;">(' . __( 'Currency', 'usces' ) . ' : ' . __( usces_crcode( 'return' ), 'usces' ) . ')</p>';

	if ( 'activate' === $tax_display && $reduced_taxrate ) {
		$materials = array(
			'total_items_price' => $data['order_item_total_price'],
			'discount'          => $data['order_discount'],
			'shipping_charge'   => $data['order_shipping_charge'],
			'cod_fee'           => $data['order_cod_fee'],
			'use_point'         => $data['order_usedpoint'],
			'carts'             => $cart,
			'condition'         => $condition,
			'order_id'          => $order_id,
		);
		$usces_tax->get_order_tax( $materials );
		if ( 'include' === $condition['tax_mode'] ) {
			$po = '(';
			$pc = ')';
		} else {
			$po = '';
			$pc = '';
		}
		$meisai .= '<p style="margin-top: 10px; font-size: 13px;">';
		$meisai .= $usces_tax->reduced_taxrate_mark . __( ' is reduced tax rate', 'usces' ) . '<br>';
		$meisai .= sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) . ' : ' . usces_crform( $usces_tax->subtotal_standard + $usces_tax->discount_standard, true, false, 'return' ) . '<br>'; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) . ' : ' . $po . usces_crform( $usces_tax->tax_standard, true, false, 'return' ) . $pc . '<br>'; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) . ' : ' . usces_crform( $usces_tax->subtotal_reduced + $usces_tax->discount_reduced, true, false, 'return' ) . '<br>'; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) . ' : ' . $po . usces_crform( $usces_tax->tax_reduced, true, false, 'return' ) . $pc . '<br>'; /* translators: %s is replaced with "string" */
		$meisai .= '</p>';
	}

	$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_meisai', $meisai, $data, $cart );
	$msg_body .= '<hr style="margin: 40px 0 30px; border-style: none; border-top: 1px solid #ddd;" />' . "\r\n";

	// Buyer information.
	$msg_body .= '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse;">';
	$msg_body .= '<caption style="font-size: 15px; font-weight: 700; text-align: left; margin-bottom: 15px;">' . __( '** Customer information **', 'usces' ) . '</caption>';
	$msg_body .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
	$msg_body .= '<table style="width: 100%;"><tbody>';

	$msg_body .= uesces_get_mail_addressform( 'admin_mail_customer', $customer, $order_id );

	$msg_body .= '</tbody></table></td></tr></tbody></table>';

	// Payment information.
	$msg_body .= '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse;">';
	$msg_body .= '<caption style="font-size: 15px; font-weight: 700; text-align: left; margin-bottom: 15px;">' . __( '** Payment method **', 'usces' ) . '</caption>';
	$msg_body .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
	$msg_body .= '<table style="width: 100%;"><tbody>';

	$msg_payment = '<tr><td colspan="2" style="padding: 0 0 25px 0;">' . $payment['name'] . usces_payment_detail_confirm( $data ) . '</td></tr>';
	if ( 'orderConfirmMail' === $mail_mode || 'changeConfirmMail' === $mail_mode || 'mitumoriConfirmMail' === $mail_mode || 'otherConfirmMail' === $mail_mode ) {
		if ( 'transferAdvance' === $payment['settlement'] && isset( $usces->options['transferee'] ) ) {
			$transferee   = '<td style="padding: 30px 0 10px; text-align: left; width: 100px; font-weight: normal; border-top: 1px dotted #ccc; vertical-align: text-top;">' . __( 'Transfer', 'usces' ) . '</td>';
			$transferee  .= '<td style="padding: 30px 0 10px 50px; width: calc( 100% - 100px ); border-top: 1px dotted #ccc; vertical-align: text-top;">' . wpautop( $usces->options['transferee'] ) . '</td>';
			$msg_payment .= '<tr>';
			$msg_payment .= apply_filters( 'usces_filter_mail_transferee', $transferee, $payment, $order_id );
			$msg_payment .= '</tr>';
		}
	}
	$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_payment', $msg_payment, $order_id, $payment, $cart, $data );
	$msg_body .= '</tbody></table></td></tr></tbody></table>';

	$msg_body .= '<hr style="margin: 40px 0 30px; border-style: none; border-top: 1px solid #ddd;" />';

	// Delivery information.
	$msg_shipping  = '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse;">';
	$msg_shipping .= '<caption style="font-size: 15px; font-weight: 700; text-align: left; margin-bottom: 15px;">' . __( '** Shipping information **', 'usces' ) . '</caption>';
	$msg_shipping .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';

	$msg_shipping .= '<table style="width: 100%;"><tbody>';

	$deli_meth = (int) $data['order_delivery_method'];
	if ( 0 <= $deli_meth ) {
		$deli_index = $usces->get_delivery_method_index( $deli_meth );
		if ( 0 <= $deli_index ) {
			$msg_shipping .= '<tr>';
			$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Delivery Method', 'usces' ) . '</td>';
			$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $usces->options['delivery_method'][ $deli_index ]['name'] . '</td>';
			$msg_shipping .= '</tr>';
		}
	}
	$msg_shipping .= '<tr>';
	$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Delivery date', 'usces' ) . '</td>';
	$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $data['order_delivery_date'] . '</td>';
	$msg_shipping .= '</tr>';
	$msg_shipping .= '<tr>';
	$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Delivery Time', 'usces' ) . '</td>';
	$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $data['order_delivery_time'] . '</td>';
	$msg_shipping .= '</tr>';

	if ( ! empty( $data['order_delidue_date'] ) && '#none#' !== $data['order_delidue_date'] ) {
		$msg_shipping .= '<tr>';
		$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Shipping date', 'usces' ) . '</td>';
		$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $data['order_delidue_date'] . '<p>' . __( "* A shipment due date is a day to ship an article, and it's not the arrival day.", 'usces' ) . '</p>' . '</td>';
		$msg_shipping .= '</tr>';
	}

	if ( 'completionMail' === $mail_mode ) {
		$tracking_number  = $usces->get_order_meta_value( apply_filters( 'usces_filter_tracking_meta_key', 'tracking_number' ), $order_id );
		$delivery_company = $usces->get_order_meta_value( 'delivery_company', $order_id );
		if ( ! empty( $delivery_company ) ) {
			$msg_shipping .= '<tr>';
			$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Delivery company', 'usces' ) . '</td>';
			$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $delivery_company . '</td>';
			$msg_shipping .= '</tr>';
			if ( ! empty( $tracking_number ) ) {
				$msg_shipping .= '<tr>';
				$msg_shipping .= '<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Tracking number', 'usces' ) . '</td>';
				$msg_shipping .= '<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px ); overflow-wrap: anywhere;">' . $tracking_number . '<p>' . __( '[*]', 'usces' ) . __( 'You can check delivery situation from the following URL.', 'usces' ) . '</p>';
				$msg_shipping .= '<p style="word-break: break-all;">' . usces_get_delivery_company_url( $delivery_company, $tracking_number ) . '</p>' . '</td>';
				$msg_shipping .= '</tr>';
			}
		}
	}
	$msg_shipping .= '</tbody></table>';

	$msg_shipping .= '<hr style="margin: 30px 0 30px; border-style: none; border-top: 1px dotted #ccc;" />';

	$msg_shipping .= '<table style="width: 100%;"><tbody>';
	$msg_shipping .= uesces_get_mail_addressform( 'admin_mail', $deli, $order_id );
	$msg_shipping .= '</tbody></table></td></tr></tbody></table>';
	$msg_shipping .= '<hr style="margin: 40px 0 30px; border-style: none; border-top: 1px solid #ddd;" />';
	$msg_body     .= apply_filters( 'usces_filter_order_confirm_mail_shipping', $msg_shipping, $data );

	$csod_meta = usces_has_custom_field_meta( 'order' );
	if ( ! empty( $csod_meta ) ) {
		$msg_body .= '<table style="font-size: 14px; width: 100%; border-collapse: collapse;">';
		$msg_body .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
		$msg_body .= '<table style="width: 100%;"><tbody>';
		$msg_body .= usces_mail_custom_field_info( 'order', '', $order_id, $data['order_email'] );
		$msg_body .= '</tbody></table></td></tr></tbody></table>';
		$msg_body .= '<hr style="margin: 40px 0 30px; border-style: none; border-top: 1px solid #ddd;" />';
	}

	$msg_others  = '<table style="font-size: 14px; width: 100%; border-collapse: collapse;">';
	$msg_others .= '<caption style="font-size: 15px; font-weight: 700; text-align: left; margin-bottom: 15px;">' . __( '** Others / a demand **', 'usces' ) . '</caption>';
	$msg_others .= '<tbody><tr><td style="background-color: #f9f9f9; padding: 30px;">';
	$msg_others .= '<table style="width: 100%;"><tbody>';
	$msg_others .= '<tr><td colspan="2" style="padding: 0;">' . wpautop( $data['order_note'] ) . '</td></tr>';
	$msg_others .= '</tbody></table></td></tr></tbody></table>';

	$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_others', $msg_others, $data );

	$msg_body .= '<hr style="margin: 50px 0 0; border-style: none; border-top: 3px solid #777;" />';

	$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_body', null, $data );

	return $msg_body;
}

/**
 * Generate text for the body of the admin mail
 *
 * @param array $args {
 *     The array of mail data.
 *     @type int    $order_id            Order ID.
 *     @type array  $data                Order data.
 *     @type array  $deli                Delivery data.
 *     @type array  $cart                Cart data.
 *     @type string $country             Country.
 *     @type array  $customer            Customer data.
 *     @type array  $condition           Condition data.
 *     @type float  $total_full_price    Total amount.
 *     @type array  $mail_data           Mail data.
 *     @type array  $payment             Payment data.
 *     @type bool   $tax_display         Is tax display.
 *     @type bool   $reduced_taxrate     Is reduced taxrate.
 *     @type string $tax_mode            Tax mode.
 *     @type string $tax_target          Tax target.
 *     @type object $usces_tax           Tax class.
 *     @type int    $point_coverage      Point coverage.
 *     @type string $member_system       Use member system.
 *     @type string $member_system_point Use member point.
 * }
 * @return text : mail body
 */
function usces_get_adminmail_textbody( $args ) {
	global $usces;
	extract( $args );

	$mail_mode = ( isset( $_POST['mode'] ) ) ? wp_unslash( $_POST['mode'] ) : '';

	$msg_body  = '';
	$msg_body .= apply_filters( 'usces_filter_order_confirm_body_top', $msg_body, $args );

	if ( 'mitumoriConfirmMail' === $mail_mode ) {
		$msg_body .= "\r\n\r\n\r\n" . __( 'Estimate', 'usces' ) . "\r\n";
		$msg_body .= usces_mail_line( 1, $data['order_email'] ); // ********************
		$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_first', null, $data );
		$msg_body .= uesces_get_mail_addressform( 'admin_mail_customer', $customer, $order_id );
		$msg_body .= __( 'estimate number', 'usces' ) . ' : ' . $order_id . "\r\n";
	} else {
		$msg_body .= "\r\n\r\n\r\n" . __( '** Article order contents **', 'usces' ) . "\r\n";
		$msg_body .= usces_mail_line( 1, $data['order_email'] ); // ********************
		$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_first', null, $data );
		$msg_body .= uesces_get_mail_addressform( 'admin_mail_customer', $customer, $order_id );
		$msg_body .= __( 'Order number', 'usces' ) . ' : ' . usces_get_deco_order_id( $order_id ) . "\r\n";
		$msg_body .= __( 'order date', 'usces' ) . ' : ' . $data['order_date'] . "\r\n";
	}

	$meisai = __( 'Items', 'usces' ) . " :\r\n";
	foreach ( (array) $cart as $cart_row ) {
		$post_id        = $cart_row['post_id'];
		$sku            = urldecode( $cart_row['sku'] );
		$quantity       = $cart_row['quantity'];
		$options        = ( ! empty( $cart_row['options'] ) ) ? $cart_row['options'] : array();
		$cart_item_name = $usces->getCartItemName_byOrder( $cart_row );
		if ( 'activate' === $tax_display && $reduced_taxrate ) {
			$applicable_taxrate = $usces_tax->get_ordercart_applicable_taxrate( $cart_row['cart_id'], $post_id, $sku );
			if ( 'reduced' === $applicable_taxrate ) {
				$cart_item_name .= $usces_tax->reduced_taxrate_mark;
			}
		}
		$sku_price = $cart_row['price'];
		$args      = compact( 'cart', 'cart_row', 'post_id', 'sku' );
		$meisai   .= usces_mail_line( 2, $data['order_email'] ); // --------------------
		$meisai   .= apply_filters( 'usces_filter_cart_item_name_nl', $cart_item_name, $args ) . "\r\n";
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$optstr = '';
			foreach ( $options as $key => $value ) {
				if ( ! empty( $key ) ) {
					$key   = urldecode( $key );
					$value = maybe_unserialize( $value );
					if ( is_array( $value ) ) {
						$c       = '';
						$optstr .= $key . ' : ';
						foreach ( $value as $v ) {
							$optstr .= $c . rawurldecode( $v );
							$c       = ', ';
						}
						$optstr .= "\r\n";
					} else {
						$optstr .= $key . ' : ' . rawurldecode( $value ) . "\r\n";
					}
				}
			}
			$meisai .= apply_filters( 'usces_filter_option_adminmail', $optstr, $options, $cart_row );
		}
		$meisai .= apply_filters( 'usces_filter_advance_adminmail', '', $cart_row, $data );
		$meisai .= __( 'Unit price', 'usces' ) . ' ' . usces_crform( $sku_price, true, false, 'return' ) . __( ' * ', 'usces' ) . $cart_row['quantity'] . "\r\n";
	}

	$meisai .= usces_mail_line( 3, $data['order_email'] ); // ====================
	$meisai .= __( 'total items', 'usces' ) . ' : ' . usces_crform( $data['order_item_total_price'], true, false, 'return' ) . "\r\n";

	if ( 0 !== (int) $data['order_discount'] ) {
		$meisai .= apply_filters( 'usces_confirm_discount_label', __( 'Campaign discount', 'usces' ), $order_id ) . ' : ' . usces_crform( $data['order_discount'], true, false, 'return' ) . "\r\n";
	}
	if ( 'activate' === $tax_display && 'products' === $tax_target ) {
		$meisai .= usces_mail_tax_label( $data );
		if ( 'exclude' === $tax_mode ) {
			$meisai .= ' : ' . usces_mail_tax( $data );
		}
		$meisai .= "\r\n";
	}

	if ( 'activate' === $member_system && 'activate' === $member_system_point && 0 === (int) $point_coverage && 0 !== (int) $data['order_usedpoint'] ) {
		$meisai .= __( 'use of points', 'usces' ) . ' : ' . number_format( $data['order_usedpoint'] ) . __( 'Points', 'usces' ) . "\r\n";
	}

	if ( usces_have_shipped( $cart ) ) {
		$meisai .= __( 'Shipping', 'usces' ) . ' : ' . usces_crform( $data['order_shipping_charge'], true, false, 'return' ) . "\r\n";
	}

	if ( 0 < $data['order_cod_fee'] ) {
		$meisai .= apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $order_id ) . ' : ' . usces_crform( $data['order_cod_fee'], true, false, 'return' ) . "\r\n";
	}

	if ( 'activate' === $tax_display && 'all' === $tax_target ) {
		$meisai .= usces_mail_tax_label( $data );
		if ( 'exclude' === $tax_mode ) {
			$meisai .= ' : ' . usces_mail_tax( $data );
		}
		$meisai .= "\r\n";
	}

	if ( 'activate' === $member_system && 'activate' === $member_system_point && 1 === (int) $point_coverage && 0 !== (int) $data['order_usedpoint'] ) {
		$meisai .= __( 'use of points', 'usces' ) . ' : ' . number_format( $data['order_usedpoint'] ) . __( 'Points', 'usces' ) . "\r\n";
	}

	$meisai .= usces_mail_line( 2, $data['order_email'] ); // --------------------
	$meisai .= __( 'Payment amount', 'usces' ) . ' : ' . usces_crform( $total_full_price, true, false, 'return' ) . "\r\n";
	$meisai .= usces_mail_line( 2, $data['order_email'] ); // --------------------
	if ( 'activate' === $tax_display && $reduced_taxrate ) {
		$materials = array(
			'total_items_price' => $data['order_item_total_price'],
			'discount'          => $data['order_discount'],
			'shipping_charge'   => $data['order_shipping_charge'],
			'cod_fee'           => $data['order_cod_fee'],
			'use_point'         => $data['order_usedpoint'],
			'carts'             => $cart,
			'condition'         => $condition,
			'order_id'          => $order_id,
		);
		$usces_tax->get_order_tax( $materials );
		if ( 'include' === $condition['tax_mode'] ) {
			$po = '(';
			$pc = ')';
		} else {
			$po = '';
			$pc = '';
		}
		$meisai .= sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) . ' : ' . usces_crform( $usces_tax->subtotal_standard + $usces_tax->discount_standard, true, false, 'return' ) . "\r\n"; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) . ' : ' . $po . usces_crform( $usces_tax->tax_standard, true, false, 'return' ) . $pc . "\r\n"; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) . ' : ' . usces_crform( $usces_tax->subtotal_reduced + $usces_tax->discount_reduced, true, false, 'return' ) . "\r\n"; /* translators: %s is replaced with "string" */
		$meisai .= sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) . ' : ' . $po . usces_crform( $usces_tax->tax_reduced, true, false, 'return' ) . $pc . "\r\n"; /* translators: %s is replaced with "string" */
		$meisai .= usces_mail_line( 2, $data['order_email'] ); // --------------------
		$meisai .= $usces_tax->reduced_taxrate_mark . __( ' is reduced tax rate', 'usces' ) . "\r\n";
	}
	$meisai .= '(' . __( 'Currency', 'usces' ) . ' : ' . __( usces_crcode( 'return' ), 'usces' ) . ")\r\n\r\n";

	$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_meisai', $meisai, $data, $cart );

	$msg_shipping  = __( '** Shipping information **', 'usces' ) . "\r\n";
	$msg_shipping .= usces_mail_line( 1, $data['order_email'] ); // ********************

	$msg_shipping .= uesces_get_mail_addressform( 'admin_mail', $deli, $order_id );

	if ( ! empty( $data['order_delidue_date'] ) && '#none#' !== $data['order_delidue_date'] ) {
		$msg_shipping .= __( 'Shipping date', 'usces' ) . ' : ' . $data['order_delidue_date'] . "\r\n";
		$msg_shipping .= __( "* A shipment due date is a day to ship an article, and it's not the arrival day.", 'usces' ) . "\r\n";
	}
	$msg_shipping .= "\r\n";

	$deli_meth = (int) $data['order_delivery_method'];
	if ( 0 <= $deli_meth ) {
		$deli_index = $usces->get_delivery_method_index( $deli_meth );
		if ( 0 <= $deli_index ) {
			$msg_shipping .= __( 'Delivery Method', 'usces' ) . ' : ' . $usces->options['delivery_method'][ $deli_index ]['name'] . "\r\n";
		}
	}
	$msg_shipping .= __( 'Delivery date', 'usces' ) . ' : ' . $data['order_delivery_date'] . "\r\n";
	$msg_shipping .= __( 'Delivery Time', 'usces' ) . ' : ' . $data['order_delivery_time'] . "\r\n";

	if ( 'completionMail' === $mail_mode ) {
		$tracking_number  = $usces->get_order_meta_value( apply_filters( 'usces_filter_tracking_meta_key', 'tracking_number' ), $order_id );
		$delivery_company = $usces->get_order_meta_value( 'delivery_company', $order_id );
		if ( ! empty( $delivery_company ) ) {
			$msg_shipping .= __( 'Delivery company', 'usces' ) . ' : ' . $delivery_company . "\r\n";
			if ( ! empty( $tracking_number ) ) {
				$msg_shipping .= __( 'Tracking number', 'usces' ) . ' : ' . $tracking_number . "\r\n\r\n";
				$msg_shipping .= __( '[*]', 'usces' ) . __( 'You can check delivery situation from the following URL.', 'usces' ) . "\r\n";
				$msg_shipping .= usces_get_delivery_company_url( $delivery_company, $tracking_number ) . " \r\n\r\n";
			}
		}
	}
	$msg_shipping .= "\r\n";
	$msg_body     .= apply_filters( 'usces_filter_order_confirm_mail_shipping', $msg_shipping, $data );

	$msg_payment  = __( '** Payment method **', 'usces' ) . "\r\n";
	$msg_payment .= usces_mail_line( 1, $data['order_email'] ); // ********************
	$msg_payment .= $payment['name'] . usces_payment_detail_confirm( $data ) . "\r\n\r\n";
	if ( 'orderConfirmMail' === $mail_mode || 'changeConfirmMail' === $mail_mode || 'mitumoriConfirmMail' === $mail_mode || 'otherConfirmMail' === $mail_mode ) {
		if ( 'transferAdvance' === $payment['settlement'] && isset( $usces->options['transferee'] ) ) {
			$transferee   = __( 'Transfer', 'usces' ) . " :\r\n";
			$transferee  .= $usces->options['transferee'] . "\r\n";
			$msg_payment .= apply_filters( 'usces_filter_mail_transferee', $transferee, $payment, $order_id );
			$msg_payment .= "\r\n" . usces_mail_line( 2, $data['order_email'] ) . "\r\n"; // --------------------
		}
	}
	$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_payment', $msg_payment, $order_id, $payment, $cart, $data );
	$msg_body .= usces_mail_custom_field_info( 'order', '', $order_id, $data['order_email'] );

	$msg_others  = "\r\n";
	$msg_others .= __( '** Others / a demand **', 'usces' ) . "\r\n";
	$msg_others .= usces_mail_line( 1, $data['order_email'] ); // ********************
	$msg_others .= $data['order_note'] . "\r\n\r\n";
	$msg_body   .= apply_filters( 'usces_filter_order_confirm_mail_others', $msg_others, $data );

	$msg_body .= apply_filters( 'usces_filter_order_confirm_mail_body', null, $data );

	return $msg_body;
}

/**
 * Ajax for sending emails from the admin screen
 *
 * @return string processing result.
 */
function usces_ajax_send_mail() {
	global $wpdb, $usces;

	$_POST = $usces->stripslashes_deep_post( $_POST );
	$nonce = isset( $_POST['wc_nonce'] ) ? wp_unslash( $_POST['wc_nonce'] ) : '';
	if ( ! wp_verify_nonce( $nonce, 'wc_send_mail_order_nonce' ) ) {
		$error_msg = array( 'message' => 'Your request is not valid.' );
		wp_send_json_error( $error_msg, 403 );
	}

	$attachments = '';
	if ( 1 === (int) $usces->options['email_attach_feature'] && isset( $_FILES['attachFile'] ) ) {
		$attach_file     = wp_unslash( $_FILES['attachFile'] );
		$url_attach_file = $attach_file['tmp_name'];
		if ( is_uploaded_file( $url_attach_file ) ) {
			// check validate file with current E-mail config.
			$result_check_file = usces_check_validate_attach_file( $attach_file );
			if ( $result_check_file ) {
				// upload file to folder.
				$attachments = usces_upload_file_attach( $attach_file, 'usces_logs' );
			} else {
				return 'attachFileError';
			}
		}
	}
	$headers = '';
	$message = trim( urldecode( $_POST['message'] ) );
	if ( usces_is_html_mail() ) {
		$headers = "Content-Type: text/html\r\n";
		$message = wpautop( $message );
	}
	$order_para = array(
		'to_name'      => sprintf( _x( '%s', 'honorific', 'usces' ), trim( urldecode( $_POST['name'] ) ) ),
		'to_address'   => trim( urldecode( $_POST['mailaddress'] ) ),
		'from_name'    => get_option( 'blogname' ),
		'from_address' => $usces->options['sender_mail'],
		'return_path'  => $usces->options['sender_mail'],
		'subject'      => trim( urldecode( $_POST['subject'] ) ),
		'message'      => $message,
		'headers'      => $headers,
		'attachments'  => $attachments,
	);

	$order_para = apply_filters( 'usces_ajax_send_mail_para_to_customer', $order_para );

	$res = usces_send_mail( $order_para );

	do_action( 'usces_action_ajax_after_send_mail_to_customer', $res, $order_para );

	if ( $res ) {
		if ( isset( $_POST['order_id'] ) && '' !== $_POST['order_id'] ) {
			$table_name = $wpdb->prefix . 'usces_order';
			$order_id   = wp_unslash( $_POST['order_id'] );
			$checked    = wp_unslash( $_POST['checked'] );

			$query = $wpdb->prepare( "SELECT `order_check` FROM $table_name WHERE ID = %d", $order_id );
			$res   = $wpdb->get_var( $query );

			$checkfield = unserialize( $res );
			if ( ! isset( $checkfield[ $checked ] ) ) {
				$checkfield[ $checked ] = $checked;
			}
			$logger        = Logger::start( $order_id, 'orderedit', 'update' );
			$query         = $wpdb->prepare( "UPDATE $table_name SET `order_check`=%s WHERE ID = %d", serialize( $checkfield ), $order_id );
			$order_checked = $wpdb->query( $query );
			if ( $order_checked ) {
				$logger->flush();
			}
		}

		if ( 'ja' !== $usces->options['system']['front_lang'] ) {
			// translators: %s: name of user.
			$bcc_subject = trim( urldecode( $_POST['subject'] ) ) . ' to ' . sprintf( _x( '%s', 'usces' ), trim( urldecode( $_POST['name'] ) ) );
		} else {
			// translators: %s: name of user.
			$bcc_subject = trim( urldecode( $_POST['subject'] ) ) . ' to ' . sprintf( _x( '%s', 'honorific', 'usces' ), trim( urldecode( $_POST['name'] ) ) );
		}

		$bcc_para = array(
			'to_name'      => apply_filters( 'usces_filter_bccmail_to_admin_name', 'Shop Admin' ),
			'to_address'   => $usces->options['order_mail'],
			'from_name'    => apply_filters( 'usces_filter_bccmail_from_admin_name', 'Welcart Auto BCC' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $bcc_subject,
			'message'      => $message,
			'headers'      => $headers,
			'attachments'  => $attachments,
		);

		$bcc_para = apply_filters( 'usces_ajax_send_mail_para_to_manager', $bcc_para );

		$res2 = usces_send_mail( $bcc_para );

		do_action( 'usces_action_ajax_after_send_mail_to_manager', $res2, $bcc_para );

		// delete file attach.
		if ( $attachments ) {
			wp_delete_file( $attachments );
		}
		return 'success';
	} else {
		// delete file attach.
		if ( $attachments ) {
			wp_delete_file( $attachments );
		}
		return 'error';
	}
}

/**
 * Handle get file upload move to folder
 *
 * @param array $file the array file information.
 * @param array $folder_move the folder file need move to.
 * @return string|null file path after move.
 */
function usces_upload_file_attach( $file, $folder_move ) {
	$upload_dir = wp_upload_dir();
	if ( ! empty( $upload_dir['basedir'] ) ) {
		$user_dirname = $upload_dir['basedir'] . '/' . $folder_move;
		if ( ! file_exists( $user_dirname ) ) {
			wp_mkdir_p( $user_dirname );
		}

		$filename = wp_unique_filename( $user_dirname, $file['name'] );
		$filepath = $user_dirname . '/' . $filename;
		$is_move  = move_uploaded_file( $file['tmp_name'], $filepath );
		if ( $is_move ) {
			return $filepath;
		}
	}
	return null;
}

/**
 * Check email attach file matching with rule option (File extension, File size) setting on page E-mail Setting
 *
 * @param array $file the array file information.
 * @return boolean type of outputters.
 */
function usces_check_validate_attach_file( $file ) {
	global $usces;

	$email_attach_file_extension = ( ! empty( $usces->options['email_attach_file_extension'] ) ) ? explode( ',', strtolower( $usces->options['email_attach_file_extension'] ) ) : array();
	$email_attach_file_size      = (int) $usces->options['email_attach_file_size'];
	$err_max_file_size           = false;
	$err_max_file_extension      = false;
	// check max file size.
	if ( $email_attach_file_size > 0 && isset( $file['size'] ) && $file['size'] > ( $email_attach_file_size * 1000000 ) ) {
		$err_max_file_size = true;
	}
	// check file extension.
	if ( count( $email_attach_file_extension ) > 0 && isset( $file['name'] ) && ! empty( $file['name'] ) ) {
		$arr_file_name  = explode( '.', $file['name'] );
		$count_arr_file = count( $arr_file_name );
		if ( $count_arr_file > 1 ) {
			$file_extention = strtolower( $arr_file_name[ $count_arr_file - 1 ] );
			if ( ! in_array( $file_extention, $email_attach_file_extension ) ) {
				$err_max_file_extension = true;
			}
		} else {
			$err_max_file_extension = true;
		}
	}

	if ( $err_max_file_size || $err_max_file_extension ) {
		return false;
	}
	return true;
}

/**
 * Whether HTML email options are enabled
 *
 * @return boolean
 */
function usces_is_html_mail() {
	global $usces;

	if ( isset( $usces->options['add_html_email_option'] ) && 1 === (int) $usces->options['add_html_email_option'] ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Send inquiry mail
 *
 * @return bool
 */
function usces_send_inquirymail() {
	global $usces;

	$_POST           = $usces->stripslashes_deep_post( $_POST );
	$res             = false;
	$mail_data       = usces_mail_data();
	$inq_name        = trim( $_POST['inq_name'] );
	$inq_contents    = trim( $_POST['inq_contents'] );
	$inq_mailaddress = trim( $_POST['inq_mailaddress'] );
	$reserve         = '';
	if ( isset( $_POST['reserve'] ) ) {
		foreach ( $_POST['reserve'] as $key => $value ) {
			$reserve .= $key . ' : ' . $value . "\r\n";
		}
	}
	$mats     = compact( 'inq_name', 'inq_contents', 'inq_mailaddress', 'reserve', 'mail_data' );
	$subject  = apply_filters( 'usces_filter_inquiry_subject_to_customer', $mail_data['title']['inquiry'], $mats );
	$message  = apply_filters( 'usces_filter_inquiry_header', $mail_data['header']['inquiry'], $inq_name, $inq_mailaddress ) . "\r\n\r\n";
	$message .= apply_filters( 'usces_filter_inquiry_reserve', $reserve, $inq_name, $inq_mailaddress );
	$message .= apply_filters( 'usces_filter_inq_contents', $inq_contents, $inq_name, $inq_mailaddress ) . "\r\n\r\n";
	$message .= apply_filters( 'usces_filter_inq_footer', $mail_data['footer']['inquiry'], $inq_name, $inq_mailaddress );
	if ( 1 === (int) $usces->options['put_customer_name'] ) {
		$message = sprintf( __( 'Dear %s', 'usces' ), $inq_name ) . "\r\n\r\n" . $message;
	}
	do_action( 'usces_action_presend_inquiry_mail', $message, $inq_name, $inq_mailaddress );
	$para1 = array(
		'to_name'      => sprintf( _x( '%s', 'honorific', 'usces' ), $inq_name ),
		'to_address'   => $inq_mailaddress,
		'from_name'    => get_option( 'blogname' ),
		'from_address' => $usces->options['sender_mail'],
		'return_path'  => $usces->options['sender_mail'],
		'subject'      => $subject,
		'message'      => do_shortcode( $message ),
	);
	$para1 = apply_filters( 'usces_send_inquirymail_para_to_customer', $para1, $mats );
	$res0  = usces_send_mail( $para1 );

	if ( $res0 ) {
		// translators: %s: name of user.
		$subject = apply_filters( 'usces_filter_inquiry_subject_to_manager', __( '** An inquiry **', 'usces' ) . '(' . sprintf( _x( '%s', 'honorific', 'usces' ), $inq_name ) . ')', $mats );
		$message = $reserve . $_POST['inq_contents'] . "\r\n"
			. "\r\n" . __( "Sender's e-mail address", 'usces' ) . ' : ' . $inq_mailaddress . "\r\n"
			. "\r\n----------------------------------------------------\r\n"
			. 'REMOTE_ADDR : ' . $_SERVER['REMOTE_ADDR']
			. "\r\n----------------------------------------------------\r\n";
		$para2   = array(
			'to_name'      => __( 'An inquiry email', 'usces' ),
			'to_address'   => $usces->options['inquiry_mail'],
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
		);
		$para2 = apply_filters( 'usces_send_inquirymail_para_to_manager', $para2, $mats );
		// sleep( 1 );
		$res = usces_send_mail( $para2 );
		do_action( 'usces_action_aftersend_inquiry_mail', $message, $inq_name, $inq_mailaddress );
	}

	return $res;
}

/**
 * Notification email of enrollment completion.
 *
 * @param array $user info of user.
 * @return text : mail ful text
 */
function usces_send_regmembermail( $user ) {
	global $usces;

	$res               = false;
	$mail_data         = usces_mail_data();
	$newmem_admin_mail = $usces->options['newmem_admin_mail'];

	$user_id = isset( $user['ID'] ) ? $user['ID'] : 0;
	// translators: %s: name of user.
	$name         = sprintf( _x( '%s', 'honorific', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
	$mailaddress1 = isset( $user['mailaddress1'] ) ? trim( $user['mailaddress1'] ) : '';

	$subject   = $mail_data['title']['membercomp'];
	$headers   = '';
	$hook_args = array(
		'user'         => $user,
		'name'         => $name,
		'mailaddress1' => $mailaddress1,
	);

	if ( usces_is_html_mail() ) {
		$headers = 'Content-Type: text/html';

		$msg_body = usces_get_regmembermail_htmlbody( $hook_args );

		$message  = '<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#eeeeee"><tbody><tr><td>';
		$message .= '<table style="font-size:15px; margin:30px auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff"><tbody>';

		// add header.
		$message .= '<tr><td style="padding:20px 30px;">';
		$m_header = $mail_data['header']['membercomp'];
		if ( 1 === (int) $usces->options['put_customer_name'] ) {
			// translators: %s: name of user.
			$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
			if ( false !== strpos( $m_header, '{customer_name}' ) ) {
				$m_header = str_replace( '{customer_name}', $dear_name, $m_header );
			} else {
				$message .= $dear_name . '<br>';
			}
		}
		$message .= do_shortcode( wpautop( $m_header ) );
		$message .= '</td></tr>';
		// add body.
		$message .= '<tr><td style="padding:20px 30px;">';
		$message .= $msg_body;
		$message .= '</td></tr>';
		// add footer.
		$message .= '<tr><td style="padding:20px 30px;">';
		$message .= do_shortcode( wpautop( $mail_data['footer']['membercomp'] ) );
		$message .= '</td></tr>';
		$message .= '</tbody></table>';
		$message .= '</td></tr></tbody></table>';
	} else {
		$msg_body = usces_get_regmembermail_textbody( $hook_args );
		$message  = '';
		$m_header = $mail_data['header']['membercomp'];
		if ( 1 === (int) $usces->options['put_customer_name'] ) {
			// translators: %s: name of user.
			$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
			if ( false !== strpos( $m_header, '{customer_name}' ) ) {
				$m_header = str_replace( '{customer_name}', $dear_name, $m_header );
			} else {
				$message .= $dear_name . "\r\n\r\n";
			}
		}
		$message .= $m_header;
		$message .= $msg_body;
		$message .= $mail_data['footer']['membercomp'];
	}
	$message = apply_filters( 'usces_filter_send_regmembermail_message', $message, $user );

	$para1 = array(
		'to_name'      => $name,
		'to_address'   => $mailaddress1,
		'from_name'    => get_option( 'blogname' ),
		'from_address' => $usces->options['sender_mail'],
		'return_path'  => $usces->options['sender_mail'],
		'subject'      => $subject,
		'message'      => do_shortcode( $message ),
		'headers'      => $headers,
	);
	$para1 = apply_filters( 'usces_filter_send_regmembermail_para1', $para1 );
	$res   = usces_send_mail( $para1 );

	if ( $newmem_admin_mail ) {
		$subject = __( 'New sign-in processing was completed.', 'usces' );

		if ( usces_is_html_mail() ) {
			$message  = '<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#eeeeee"><tbody><tr><td>';
			$message .= '<table style="font-size:15px; margin:30px auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff"><tbody>';

			// add header.
			$message .= '<tr><td style="padding:20px 30px;">';
			$message .= __( 'New sign-in processing was completed.', 'usces' );
			$message .= '</td></tr>';
			// add body.
			$message .= '<tr><td style="padding:20px 30px;">';
			$message .= $msg_body;
			$message .= '</td></tr>';
			// add footer.
			$message .= '<tr><td style="padding:20px 30px;">';
			$message .= do_shortcode( wpautop( $mail_data['footer']['membercomp'] ) );
			$message .= '</td></tr>';
			$message .= '</tbody></table>';
			$message .= '</td></tr></tbody></table>';
		} else {
			$msg_body = usces_get_regmembermail_textbody( $hook_args );
			$message  = __( 'New sign-in processing was completed.', 'usces' ) . "\r\n\r\n";
			$message .= $msg_body;
		}

		$message = apply_filters( 'usces_filter_send_regmembermail_notice', $message, $user );

		$para2 = array(
			'to_name'      => __( 'Notice of new sign-in', 'usces' ),
			'to_address'   => $usces->options['order_mail'],
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
			'headers'      => $headers,
		);
		$para2 = apply_filters( 'usces_filter_send_regmembermail_para2', $para2 );
		usces_send_mail( $para2 );
	}

	return $res;
}

/**
 * Notification email html of enrollment completion.
 *
 * @param array $hook_args info data.
 * @return text : mail full text
 */
function usces_get_regmembermail_htmlbody( $hook_args ) {
	$user         = isset( $hook_args['user'] ) ? $hook_args['user'] : array();
	$name         = isset( $hook_args['name'] ) ? $hook_args['name'] : '';
	$mailaddress1 = isset( $hook_args['mailaddress1'] ) ? $hook_args['mailaddress1'] : '';
	$user_id      = isset( $user['ID'] ) ? $user['ID'] : 0;

	$message  = '<hr style="margin: 0 0 50px; border-style: none; border-top: 3px solid #777;" />';
	$message .= '<table style="font-size: 14px; margin-bottom: 30px; width: 100%; border-collapse: collapse; border: 1px solid #ddd;">';
	$message .= '<caption style="background-color: #111; margin-bottom: 40px; padding: 15px; color: #fff; font-size: 15px; font-weight: 700; text-align: left;">';
	$message .= __( 'Registration contents', 'usces' );
	$message .= '</caption>';
	$message .= '<tbody><tr>';
	$message .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . __( 'Member ID', 'usces' ) . '</td>';
	$message .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . esc_attr( $user_id ) . '</td>';
	$message .= '</tr><tr>';
	$message .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . __( 'Name', 'usces' ) . '</td>';
	$message .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . esc_attr( $name ) . '</td>';
	$message .= '</tr><tr>';
	$message .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . __( 'e-mail adress', 'usces' ) . '</td>';
	$message .= '<td style="padding: 12px; width: 50%; border: 1px solid #ddd;">' . esc_attr( $mailaddress1 ) . '</td>';
	$message .= '</tr></tbody>';
	$message .= '</table>';
	$message .= '<hr style="margin: 50px 0 0; border-style: none; border-top: 3px solid #777;" />';

	return $message;
}

/**
 * Notification email text of enrollment completion.
 *
 * @param array $hook_args info data.
 * @return text : mail full text
 */
function usces_get_regmembermail_textbody( $hook_args ) {
	$user         = isset( $hook_args['user'] ) ? $hook_args['user'] : array();
	$name         = isset( $hook_args['name'] ) ? $hook_args['name'] : '';
	$mailaddress1 = isset( $hook_args['mailaddress1'] ) ? $hook_args['mailaddress1'] : '';
	$user_id      = isset( $user['ID'] ) ? $user['ID'] : 0;

	$message  = __( 'Registration contents', 'usces' ) . "\r\n";
	$message .= '--------------------------------' . "\r\n";
	$message .= __( 'Member ID', 'usces' ) . ' : ' . esc_attr( $user_id ) . "\r\n";
	$message .= __( 'Name', 'usces' ) . ' : ' . esc_attr( $name ) . "\r\n";
	$message .= __( 'e-mail adress', 'usces' ) . ' : ' . esc_attr( $mailaddress1 ) . "\r\n";
	$message .= '--------------------------------' . "\r\n\r\n";

	return $message;
}

/**
 * Notification email text update info of user.
 *
 * @param array $user info data.
 */
function usces_send_updmembermail( $user ) {
	global $usces;
	$updmem_admin_mail    = $usces->options['updmem_admin_mail'];
	$updmem_customer_mail = $usces->options['updmem_customer_mail'];
	if ( ! $updmem_admin_mail && ! $updmem_customer_mail ) {
		return;
	}

	// $mail_data    = usces_mail_data();
	// translators: %s: name of user.
	$name         = sprintf( _x( '%s', 'honorific', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
	$mailaddress1 = trim( $user['mailaddress1'] );
	$subject      = apply_filters( 'usces_filter_send_updmembermail_subject', __( 'Member update processing was completed.', 'usces' ), $user );

	if ( $updmem_customer_mail ) {
		$message  = $subject . "\r\n\r\n";
		$message .= __( 'Registration contents', 'usces' ) . "\r\n";
		$message .= '--------------------------------' . "\r\n";
		$message .= __( 'Member ID', 'usces' ) . ' : ' . $user['ID'] . "\r\n";
		$message .= __( 'Name', 'usces' ) . ' : ' . $name . "\r\n";
		$message .= __( 'e-mail adress', 'usces' ) . ' : ' . $mailaddress1 . "\r\n";
		$message .= '--------------------------------' . "\r\n\r\n";
		$message .= __( 'If you have not requested this email, sorry to trouble you, but please contact us.', 'usces' ) . "\r\n\r\n";
		$message .= get_option( 'blogname' ) . "\r\n";
		// $message .= $mail_data['footer']['membercomp'];
		if ( 1 === (int) $usces->options['put_customer_name'] ) {
			// translators: %s: name of user.
			$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
			$message   = $dear_name . "\r\n\r\n" . $message;
		}
		$message = apply_filters( 'usces_filter_send_updmembermail_message', $message, $user );

		$para1 = array(
			'to_name'      => $name,
			'to_address'   => $mailaddress1,
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
		);
		$para1 = apply_filters( 'usces_filter_send_updmembermail_para1', $para1 );
		usces_send_mail( $para1 );
	}

	if ( $updmem_admin_mail ) {
		$message  = $subject . "\r\n\r\n";
		$message .= __( 'Registration contents', 'usces' ) . "\r\n";
		$message .= '--------------------------------' . "\r\n";
		$message .= __( 'Member ID', 'usces' ) . ' : ' . $user['ID'] . "\r\n";
		$message .= __( 'Name', 'usces' ) . ' : ' . $name . "\r\n";
		$message .= __( 'e-mail adress', 'usces' ) . ' : ' . $mailaddress1 . "\r\n";
		$message .= '--------------------------------' . "\r\n\r\n";
		$message  = apply_filters( 'usces_filter_send_updmembermail_notice', $message, $user );

		$para2 = array(
			'to_name'      => __( 'Notice of new sign-in', 'usces' ),
			'to_address'   => $usces->options['order_mail'],
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
		);
		$para2 = apply_filters( 'usces_filter_send_updmembermail_para2', $para2 );
		usces_send_mail( $para2 );
	}
}

/**
 * Notification email html of delete member.
 *
 * @param array $user info data.
 */
function usces_send_delmembermail( $user ) {
	global $usces;
	$delmem_admin_mail    = $usces->options['delmem_admin_mail'];
	$delmem_customer_mail = $usces->options['delmem_customer_mail'];
	if ( ! $delmem_admin_mail && ! $delmem_customer_mail ) {
		return;
	}

	// $mail_data    = usces_mail_data();
	// translators: %s: name of user.
	$name         = sprintf( _x( '%s', 'honorific', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
	$mailaddress1 = trim( $user['mailaddress1'] );
	$subject      = apply_filters( 'usces_filter_send_delmembermail_subject', __( 'Member removal processing was completed.', 'usces' ), $user );

	if ( $delmem_customer_mail ) {
		$message = $subject . "\r\n\r\n";
		// $message .= __( 'Registration contents', 'usces' ) . "\r\n";
		$message .= '--------------------------------' . "\r\n";
		$message .= __( 'Member ID', 'usces' ) . ' : ' . $user['ID'] . "\r\n";
		$message .= __( 'Name', 'usces' ) . ' : ' . $name . "\r\n";
		$message .= __( 'e-mail adress', 'usces' ) . ' : ' . $mailaddress1 . "\r\n";
		$message .= '--------------------------------' . "\r\n\r\n";
		$message .= __( 'If you have not requested this email, sorry to trouble you, but please contact us.', 'usces' ) . "\r\n\r\n";
		$message .= get_option( 'blogname' ) . "\r\n";
		// $message .= $mail_data['footer']['membercomp'];
		if ( 1 === (int) $usces->options['put_customer_name'] ) {
			// translators: %s: name of user.
			$dear_name = sprintf( __( 'Dear %s', 'usces' ), usces_localized_name( trim( $user['name1'] ), trim( $user['name2'] ), 'return' ) );
			$message   = $dear_name . "\r\n\r\n" . $message;
		}
		$message = apply_filters( 'usces_filter_send_delmembermail_message', $message, $user );

		$para1 = array(
			'to_name'      => $name,
			'to_address'   => $mailaddress1,
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
		);
		$para1 = apply_filters( 'usces_filter_send_delmembermail_para1', $para1 );
		usces_send_mail( $para1 );
	}

	if ( $delmem_admin_mail ) {
		$message = $subject . "\r\n\r\n";
		// $message .= __( 'Registration contents', 'usces' ) . "\r\n";
		$message .= '--------------------------------' . "\r\n";
		$message .= __( 'Member ID', 'usces' ) . ' : ' . $user['ID'] . "\r\n";
		$message .= __( 'Name', 'usces' ) . ' : ' . $name . "\r\n";
		$message .= __( 'e-mail adress', 'usces' ) . ' : ' . $mailaddress1 . "\r\n";
		$message .= '--------------------------------' . "\r\n\r\n";
		$message  = apply_filters( 'usces_filter_send_delmembermail_notice', $message, $user );

		$para2 = array(
			'to_name'      => __( 'Notice of new sign-in', 'usces' ),
			'to_address'   => $usces->options['order_mail'],
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
		);
		$para2 = apply_filters( 'usces_filter_send_delmembermail_para2', $para2 );
		usces_send_mail( $para2 );
	}
}

/**
 * Email template user lost mail.
 *
 * @param  string $url change password.
 * @return string Return page.
 */
function usces_lostmail( $url ) {
	global $usces;
	$res = false;

	if ( isset( $_REQUEST['loginmail'] ) && ! empty( $_REQUEST['loginmail'] ) ) {

		$usces_lostmail = $_REQUEST['loginmail'];
		// $mail_data      = usces_mail_data();
		$subject  = apply_filters( 'usces_filter_lostmail_subject', __( 'Change password', 'usces' ) );
		$message  = __( 'Please, click the following URL, and please change a password.', 'usces' ) . "\r\n\r\n\r\n"
			. $url . "\r\n\r\n\r\n"
			. "-----------------------------------------------------\r\n"
			. __( 'If you have not requested this email please kindly ignore and delete it.', 'usces' ) . "\r\n"
			. "-----------------------------------------------------\r\n\r\n\r\n";
		$message  = apply_filters( 'usces_filter_lostmail_message', $message, $url );
		$message .= apply_filters( 'usces_filter_lostmail_footer', get_option( 'blogname' ) . "\r\n" );

		$para1 = array(
			'to_name'      => sprintf( _x( '%s', 'honorific', 'usces' ), $usces_lostmail ),
			'to_address'   => $usces_lostmail,
			'from_name'    => get_option( 'blogname' ),
			'from_address' => $usces->options['sender_mail'],
			'return_path'  => $usces->options['sender_mail'],
			'subject'      => $subject,
			'message'      => do_shortcode( $message ),
		);

		$para1 = apply_filters( 'usces_filter_send_lostmail_para1', $para1 );
		$res   = usces_send_mail( $para1 );
	}

	if ( false === $res ) {
		$usces->error_message = __( 'Error: I was not able to transmit an email.', 'usces' );
		$page                 = 'lostmemberpassword';
	} else {
		$page = 'lostcompletion';
	}

	return $page;
}

/**
 * Custom field information
 *
 * @param string $custom_field Field type.
 * @param string $position     Field position.
 * @param int    $id           Order ID.
 * @param string $mailaddress  Mail address.
 * @return string
 */
function usces_mail_custom_field_info( $custom_field, $position, $id, $mailaddress = '' ) {
	global $usces;

	$msg_body = '';
	switch ( $custom_field ) {
		case 'order':
			$field = 'usces_custom_order_field';
			$cs    = 'csod_';
			break;
		case 'customer':
			$field = 'usces_custom_customer_field';
			$cs    = 'cscs_';
			break;
		case 'delivery':
			$field = 'usces_custom_delivery_field';
			$cs    = 'csde_';
			break;
		case 'member':
			$field = 'usces_custom_member_field';
			$cs    = 'csmb_';
			break;
		default:
			return $msg_body;
	}

	$meta = usces_has_custom_field_meta( $custom_field );

	if ( ! empty( $meta ) && is_array( $meta ) ) {
		$keys = array_keys( $meta );

		if ( usces_is_html_mail() ) {

			switch ( $custom_field ) {
				case 'order':
					foreach ( $keys as $key ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $cs . $key, $id ) );
						if ( is_array( $value ) ) {
							$concatval = '';
							$c         = '';
							foreach ( $value as $v ) {
								$concatval .= $c . $v;
								$c          = ', ';
							}
							$value = $concatval;
						}
						$msg_body .= '<tr>
							<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . $meta[ $key ]['name'] . '</td>
							<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $value . '</td>
						</tr>';
					}
					break;

				case 'customer':
				case 'delivery':
					foreach ( $keys as $key ) {
						if ( $meta[ $key ]['position'] === $position ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $cs . $key, $id ) );
							if ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ', ';
								}
								$value = $concatval;
							}
							$msg_body .= '<tr>
								<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . $meta[ $key ]['name'] . '</td>
								<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $value . '</td>
							</tr>';
						}
					}
					break;

				case 'member':
					foreach ( $keys as $key ) {
						if ( $meta[ $key ]['position'] === $position ) {
							$value = maybe_unserialize( $usces->get_member_meta_value( $cs . $key, $id ) );
							if ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ', ';
								}
								$value = $concatval;
							}
							$msg_body .= '<tr>
								<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . $meta[ $key ]['name'] . '</td>
								<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $value . '</td>
							</tr>';
						}
					}
					break;
			}

		} else {

			switch ( $custom_field ) {
				case 'order':
					$msg_body .= "\r\n";
					$msg_body .= usces_mail_line( 1, $mailaddress );
					foreach ( $keys as $key ) {
						$value = maybe_unserialize( $usces->get_order_meta_value( $cs . $key, $id ) );
						if ( is_array( $value ) ) {
							$concatval = '';
							$c         = '';
							foreach ( $value as $v ) {
								$concatval .= $c . $v;
								$c          = ', ';
							}
							$value = $concatval;
						}
						$msg_body .= $meta[ $key ]['name'] . ' : ' . $value . "\r\n";
					}
					$msg_body .= usces_mail_line( 1, $mailaddress );
					break;

				case 'customer':
				case 'delivery':
					foreach ( $keys as $key ) {
						if ( $meta[ $key ]['position'] === $position ) {
							$value = maybe_unserialize( $usces->get_order_meta_value( $cs . $key, $id ) );
							if ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ', ';
								}
								$value = $concatval;
							}
							$msg_body .= $meta[ $key ]['name'] . ' : ' . $value . "\r\n";
						}
					}
					break;

				case 'member':
					foreach ( $keys as $key ) {
						if ( $meta[ $key ]['position'] === $position ) {
							$value = maybe_unserialize( $usces->get_member_meta_value( $cs . $key, $id ) );
							if ( is_array( $value ) ) {
								$concatval = '';
								$c         = '';
								foreach ( $value as $v ) {
									$concatval .= $c . $v;
									$c          = ', ';
								}
								$value = $concatval;
							}
							$msg_body .= $meta[ $key ]['name'] . ' : ' . $value . "\r\n";
						}
					}
					break;
			}
		}
	}
	$msg_body = apply_filters( 'usces_filter_mail_custom_field_info', $msg_body, $custom_field, $position, $id, $mailaddress );
	return $msg_body;
}

/**
 * Send mail
 * (unused)
 *
 * @param array $para {
 *     The array of mail data.
 *     @type string $to_name      To name.
 *     @type string $to_address   To address.
 *     @type string $from_name    From name.
 *     @type string $from_address From address.
 *     @type string $return_path  Return path.
 *     @type string $subject      Subject.
 *     @type string $message      Message.
 * }
 * @return bool
 */
function _usces_send_mail( $para ) {
	global $usces;

	$from_name    = $para['from_name'];
	$from_address = $para['from_address'];
	if ( false !== strpos( $para['from_address'], '..' ) || false !== strpos( $para['from_address'], '.@' ) ) {
		$fname = str_replace( strstr( $para['from_address'], '@' ), '', $para['from_address'] );
		if ( '"' !== substr( $fname, 0, 1 ) && '"' !== substr( $fname, -1 ) ) {
			$para['from_address'] = str_replace( $fname, '"RFC_violation"', $para['from_address'] );
			$from_name            = $para['from_name'] . '(' . $from_address . ')';
		}
	}
	$from    = htmlspecialchars( html_entity_decode( $from_name, ENT_QUOTES ) ) . " <{$para['from_address']}>";
	$header  = 'From: ' . apply_filters( 'usces_filter_send_mail_from', $from, $para ) . "\r\n";
	$header .= "Return-Path: {$para['return_path']}\r\n";

	$subject = html_entity_decode( $para['subject'], ENT_QUOTES );
	$message = $para['message'];

	ini_set( 'SMTP', "{$usces->options['smtp_hostname']}" );
	if ( ! ini_get( 'smtp_port' ) ) {
		ini_set( 'smtp_port', apply_filters( 'usces_filter_send_mail_port', 25, $para ) );
	}
	ini_set( 'sendmail_from', '' );

	$mails     = explode( ',', $para['to_address'] );
	$to_mailes = array();
	foreach ( $mails as $mail ) {
		if ( false !== strpos( $mail, '..' ) || false !== strpos( $mail, '.@' ) ) {
			$name = str_replace( strstr( $mail, '@' ), '', $mail );
			if ( '"' !== substr( $name, 0, 1 ) && '"' !== substr( $name, -1 ) ) {
				$to_mailes[] = str_replace( $name, '"' . $name . '"', $mail );
			} else {
				$to_mailes[] = $mail;
			}
		} elseif ( is_email( trim( $mail ) ) ) {
			$to_mailes[] = $mail;
		} else {
			$to_mailes[] = null;
		}
	}

	if ( ! empty( $to_mailes ) ) {
		$res = @wp_mail( $to_mailes, $subject, $message, $header );
	} else {
		$res = false;
	}

	return $res;
}

/**
 * Send mail
 *
 * @param array $para {
 *     The array of mail data.
 *     @type string $to_name      To name.
 *     @type string $to_address   To address.
 *     @type string $from_name    From name.
 *     @type string $from_address From address.
 *     @type string $return_path  Return path.
 *     @type string $subject      Subject.
 *     @type string $message      Message.
 * }
 * @return bool
 */
function usces_send_mail( $para ) {
	global $usces;

	$from_name    = $para['from_name'];
	$from_address = $para['from_address'];
	if ( strpos( $para['from_address'], '..' ) !== false || strpos( $para['from_address'], '.@' ) !== false ) {
		$fname = str_replace( strstr( $para['from_address'], '@' ), '', $para['from_address'] );
		if ( '"' != substr( $fname, 0, 1 ) && '"' != substr( $fname, -1 ) ) {
			$para['from_address'] = str_replace( $fname, '"RFC_violation"', $para['from_address'] );
			$from_name            = $para['from_name'] . '(' . $from_address . ')';
		}
	}
	$from_name = html_entity_decode( $from_name, ENT_QUOTES );
	$from_name = mb_encode_mimeheader( $from_name );
	// $from = htmlspecialchars( html_entity_decode( $from_name, ENT_QUOTES ) );
	// $para['from_name'] = $from;
	$para['from_name'] = $from_name;

	$usces->mail_para = $para;
	add_action( 'phpmailer_init', 'usces_send_mail_init', 5 );

	$subject     = html_entity_decode( $para['subject'], ENT_QUOTES );
	$message     = $para['message'];
	$attachments = isset( $para['attachments'] ) ? $para['attachments'] : array();

	$mails     = explode( ',', $para['to_address'] );
	$to_mailes = array();
	foreach ( $mails as $mail ) {
		if ( is_email( trim( $mail ) ) ) {
			$to_mailes[] = $mail;
		}
	}
	$res = false;
	foreach ( $to_mailes as $to_maile ) {
		if ( strpos( $to_maile, '..' ) !== false || strpos( $to_maile, '.@' ) !== false ) {
			$headers = 'From: ' . $from_address . "\r\n";
			if ( isset( $para['headers'] ) && ! empty( $para['headers'] ) ) {
				$headers .= $para['headers'];
			}
			$res     = @mb_send_mail( $to_maile, $subject, $message, $headers );
		} elseif ( ! empty( $to_maile ) ) {
			$headers = ( ! empty( $para['headers'] ) ) ? $para['headers'] : '';
			$res     = @wp_mail( $to_maile, $subject, $message, $headers, $attachments );
		} else {
			$res = false;
		}
	}

	remove_action( 'phpmailer_init', 'usces_send_mail_init', 5 );
	$usces->mail_para = array();
	return $res;
}

/**
 * Fires after PHPMailer is initialized.
 * phpmailer_init
 *
 * @param object $phpmailer The PHPMailer instance (passed by reference).
 */
function usces_send_mail_init( $phpmailer ) {
	global $usces;

	$phpmailer->Mailer   = 'mail';
	$phpmailer->From     = $usces->mail_para['from_address'];
	$phpmailer->FromName = apply_filters( 'usces_filter_send_mail_from', $usces->mail_para['from_name'], $usces->mail_para );
	$phpmailer->Sender   = $usces->mail_para['from_address'];

	do_action( 'usces_filter_phpmailer_init', array( &$phpmailer ) );
}

/**
 * Address format
 *
 * @param string $type Mail type.
 * @param array  $data Order data.
 * @param int    $order_id Order ID.
 * @param string $out  Return value or echo.
 * @return string|void
 */
function uesces_get_mail_addressform( $type, $data, $order_id, $out = 'return' ) {
	global $usces, $usces_settings;

	$options       = get_option( 'usces' );
	$applyform     = usces_get_apply_addressform( $options['system']['addressform'] );
	$values        = array();
	$value_default = array(
		'name1'    => null,
		'name2'    => null,
		'name3'    => null,
		'name4'    => null,
		'zipcode'  => null,
		'country'  => null,
		'pref'     => null,
		'address1' => null,
		'address2' => null,
		'address3' => null,
		'tel'      => null,
		'fax'      => null,
	);

	$formtag = '';
	switch ( $type ) {
		case 'admin_mail_customer':
			$values            = $data;
			$values['country'] = ! empty( $values['country'] ) ? $values['country'] : usces_get_local_addressform();
			$main_mode         = 'customer';
			$name_label        = __( 'Buyer', 'usces' );
			break;
		case 'admin_mail':
			$values            = $data;
			$values['country'] = ! empty( $values['country'] ) ? $values['country'] : usces_get_local_addressform();
			$main_mode         = 'delivery';
			$name_label        = __( 'A destination name', 'usces' );
			break;
		case 'order_mail_customer':
			$values            = $data['customer'];
			$values['country'] = ! empty( $values['country'] ) ? $values['country'] : usces_get_local_addressform();
			$main_mode         = 'customer';
			$name_label        = __( 'Buyer', 'usces' );
			break;
		case 'order_mail':
			$values            = $data['delivery'];
			$values['country'] = ! empty( $values['country'] ) ? $values['country'] : usces_get_local_addressform();
			$main_mode         = 'delivery';
			$name_label        = __( 'A destination name', 'usces' );
			break;
	}
	// set data value default.
	foreach ( $value_default as $key => $val ) {
		if ( ! isset( $values[ $key ] ) ) {
			$values[ $key ] = $value_default[ $key ];
		}
	}
	$pref                = ( __( '-- Select --', 'usces' ) === $values['pref'] || '-- Select --' === $values['pref'] ) ? '' : $values['pref'];
	$target_market_count = ( isset( $options['system']['target_market'] ) && is_array( $options['system']['target_market'] ) ) ? count( $options['system']['target_market'] ) : 1;

	if ( usces_is_html_mail() ) {

		switch ( $applyform ) {
			case 'JP':
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_pre', $order_id );
				if ( 'order_mail_customer' === $type || 'admin_mail_customer' === $type ) {
					$order_data  = usces_get_order_email( $order_id );
					$mem_id      = $order_data['mem_id'];
					$order_email = $order_data['order_email'];

					if ( ! empty( $mem_id ) ) {
						$formtag .= '<tr>
							<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'membership number', 'usces' ) . '</td>
							<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $mem_id . '</td>
						</tr>' . "\r\n";
					}
					if ( ! empty( $order_email ) ) {
						$formtag .= '<tr>
							<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'e-mail adress', 'usces' ) . '</td>
							<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $order_email . '</td>
						</tr>' . "\r\n";
					}
				}

				$formtag .= '<tr>
					<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . $name_label . '</td>
					<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . sprintf( _x( '%s', 'honorific', 'usces' ), ( $values['name1'] . ' ' . $values['name2'] ) ) . '</td>
				</tr>' . "\r\n";

				if ( ! empty( $values['name3'] ) || ! empty( $values['name4'] ) ) {
					$formtag .= '<tr>
						<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'furigana', 'usces' ) . '</td>
						<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $values['name3'] . ' ' . $values['name4'] . '</td>
					</tr>' . "\r\n";
				}

				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_after', $order_id );

				if ( 1 < $target_market_count ) {
					$formtag .= '<tr>
						<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Country', 'usces' ) . '</td>
						<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $usces_settings['country'][ $values['country'] ] . '</td>
					</tr>' . "\r\n";
				}

				$formtag .= '<tr>
					<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Zip/Postal Code', 'usces' ) . '</td>
					<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $values['zipcode'] . '</td>
				</tr>' . "\r\n";

				$formtag .= '<tr>
					<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Address', 'usces' ) . '</td>
					<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $pref . $values['address1'] . $values['address2'] . ' ' . $values['address3'] . '</td>
				</tr>' . "\r\n";

				$formtag .= '<tr>
					<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'Phone number', 'usces' ) . '</td>
					<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $values['tel'] . '</td>
				</tr>' . "\r\n";

				$formtag .= '<tr>
					<td style="padding: 0 0 10px; text-align: left; width: 100px; font-weight: normal; vertical-align: text-top;">' . __( 'FAX number', 'usces' ) . '</td>
					<td style="padding: 0 0 10px 50px; width: calc( 100% - 100px );">' . $values['fax'] . '</td>
				</tr>' . "\r\n";

				$formtag .= usces_mail_custom_field_info( $main_mode, 'fax_after', $order_id );
				break;

			case 'CN':
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_pre', $order_id );
				if ( 'order_mail_customer' === $type || 'admin_mail_customer' === $type ) {
					$order_data  = usces_get_order_email( $order_id );
					$mem_id      = $order_data['mem_id'];
					$order_email = $order_data['order_email'];

					$formtag .= ( ! empty( $mem_id ) ) ? __( 'membership number', 'usces' ) . ' : ' . $mem_id . "\r\n" : '';
					$formtag .= ( ! empty( $order_email ) ) ? __( 'e-mail adress', 'usces' ) . ' : ' . $order_email . "\r\n" : '';
				}
				// translators: %s: name of user.
				$formtag .= $name_label . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), ( $values['name1'] . ' ' . $values['name2'] ) ) . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_after', $order_id );
				if ( 1 < $target_market_count ) {
					$formtag .= __( 'Country', 'usces' ) . ' : ' . $usces_settings['country'][ $values['country'] ] . "\r\n";
				}
				$formtag .= __( 'State', 'usces' ) . ' : ' . $pref . "\r\n";
				$formtag .= __( 'City', 'usces' ) . ' : ' . $values['address1'] . "\r\n";
				$formtag .= __( 'Address', 'usces' ) . ' : ' . $values['address2'] . ' ' . $values['address3'] . "\r\n";
				$formtag .= __( 'Zip/Postal Code', 'usces' ) . ' : ' . $values['zipcode'] . "\r\n";
				$formtag .= __( 'Phone number', 'usces' ) . ' : ' . $values['tel'] . "\r\n";
				$formtag .= __( 'FAX number', 'usces' ) . ' : ' . $values['fax'] . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'fax_after', $order_id );
				break;

			case 'US':
			default:
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_pre', $order_id );
				if ( 'order_mail_customer' === $type || 'admin_mail_customer' === $type ) {
					$order_data  = usces_get_order_email( $order_id );
					$mem_id      = $order_data['mem_id'];
					$order_email = $order_data['order_email'];

					$formtag .= ( ! empty( $mem_id ) ) ? __( 'membership number', 'usces' ) . ' : ' . $mem_id . "\r\n" : '';
					$formtag .= ( ! empty( $order_email ) ) ? __( 'e-mail adress', 'usces' ) . ' : ' . $order_email . "\r\n" : '';
				}
				// translators: %s: name of user.
				$formtag .= $name_label . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), ( $values['name2'] . ' ' . $values['name1'] ) ) . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_after', $order_id );
				$formtag .= __( 'Address', 'usces' ) . ' : ' . $values['address2'] . ' ' . $values['address3'] . "\r\n";
				$formtag .= __( 'City', 'usces' ) . ' : ' . $values['address1'] . "\r\n";
				$formtag .= __( 'State', 'usces' ) . ' : ' . $pref . "\r\n";
				if ( 1 < $target_market_count ) {
					$formtag .= __( 'Country', 'usces' ) . ' : ' . $usces_settings['country'][ $values['country'] ] . "\r\n";
				}
				$formtag .= __( 'Zip/Postal Code', 'usces' ) . ' : ' . $values['zipcode'] . "\r\n";
				$formtag .= __( 'Phone number', 'usces' ) . ' : ' . $values['tel'] . "\r\n";
				$formtag .= __( 'FAX number', 'usces' ) . ' : ' . $values['fax'] . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'fax_after', $order_id );
				break;
		}

	} else {

		switch ( $applyform ) {
			case 'JP':
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_pre', $order_id );
				if ( 'order_mail_customer' === $type || 'admin_mail_customer' === $type ) {
					$order_data  = usces_get_order_email( $order_id );
					$mem_id      = $order_data['mem_id'];
					$order_email = $order_data['order_email'];

					$formtag .= ( ! empty( $mem_id ) ) ? __( 'membership number', 'usces' ) . ' : ' . $mem_id . "\r\n" : '';
					$formtag .= ( ! empty( $order_email ) ) ? __( 'e-mail adress', 'usces' ) . ' : ' . $order_email . "\r\n" : '';
				}
				// translators: %s: name of user.
				$formtag .= $name_label . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), ( $values['name1'] . ' ' . $values['name2'] ) ) . "\r\n";
				if ( ! empty( $values['name3'] ) || ! empty( $values['name4'] ) ) {
					$formtag .= __( 'furigana', 'usces' ) . ' : ' . $values['name3'] . ' ' . $values['name4'] . "\r\n";
				}
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_after', $order_id );
				if ( 1 < $target_market_count ) {
					$formtag .= __( 'Country', 'usces' ) . ' : ' . $usces_settings['country'][ $values['country'] ] . "\r\n";
				}
				$formtag .= __( 'Zip/Postal Code', 'usces' ) . ' : ' . $values['zipcode'] . "\r\n";
				$formtag .= __( 'Address', 'usces' ) . ' : ' . $pref . $values['address1'] . $values['address2'] . ' ' . $values['address3'] . "\r\n";
				$formtag .= __( 'Phone number', 'usces' ) . ' : ' . $values['tel'] . "\r\n";
				$formtag .= __( 'FAX number', 'usces' ) . ' : ' . $values['fax'] . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'fax_after', $order_id );
				break;

			case 'CN':
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_pre', $order_id );
				if ( 'order_mail_customer' === $type || 'admin_mail_customer' === $type ) {
					$order_data  = usces_get_order_email( $order_id );
					$mem_id      = $order_data['mem_id'];
					$order_email = $order_data['order_email'];

					$formtag .= ( ! empty( $mem_id ) ) ? __( 'membership number', 'usces' ) . ' : ' . $mem_id . "\r\n" : '';
					$formtag .= ( ! empty( $order_email ) ) ? __( 'e-mail adress', 'usces' ) . ' : ' . $order_email . "\r\n" : '';
				}
				// translators: %s: name of user.
				$formtag .= $name_label . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), ( $values['name1'] . ' ' . $values['name2'] ) ) . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_after', $order_id );
				if ( 1 < $target_market_count ) {
					$formtag .= __( 'Country', 'usces' ) . ' : ' . $usces_settings['country'][ $values['country'] ] . "\r\n";
				}
				$formtag .= __( 'State', 'usces' ) . ' : ' . $pref . "\r\n";
				$formtag .= __( 'City', 'usces' ) . ' : ' . $values['address1'] . "\r\n";
				$formtag .= __( 'Address', 'usces' ) . ' : ' . $values['address2'] . ' ' . $values['address3'] . "\r\n";
				$formtag .= __( 'Zip/Postal Code', 'usces' ) . ' : ' . $values['zipcode'] . "\r\n";
				$formtag .= __( 'Phone number', 'usces' ) . ' : ' . $values['tel'] . "\r\n";
				$formtag .= __( 'FAX number', 'usces' ) . ' : ' . $values['fax'] . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'fax_after', $order_id );
				break;

			case 'US':
			default:
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_pre', $order_id );
				if ( 'order_mail_customer' === $type || 'admin_mail_customer' === $type ) {
					$order_data  = usces_get_order_email( $order_id );
					$mem_id      = $order_data['mem_id'];
					$order_email = $order_data['order_email'];

					$formtag .= ( ! empty( $mem_id ) ) ? __( 'membership number', 'usces' ) . ' : ' . $mem_id . "\r\n" : '';
					$formtag .= ( ! empty( $order_email ) ) ? __( 'e-mail adress', 'usces' ) . ' : ' . $order_email . "\r\n" : '';
				}
				// translators: %s: name of user.
				$formtag .= $name_label . ' : ' . sprintf( _x( '%s', 'honorific', 'usces' ), ( $values['name2'] . ' ' . $values['name1'] ) ) . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'name_after', $order_id );
				$formtag .= __( 'Address', 'usces' ) . ' : ' . $values['address2'] . ' ' . $values['address3'] . "\r\n";
				$formtag .= __( 'City', 'usces' ) . ' : ' . $values['address1'] . "\r\n";
				$formtag .= __( 'State', 'usces' ) . ' : ' . $pref . "\r\n";
				if ( 1 < $target_market_count ) {
					$formtag .= __( 'Country', 'usces' ) . ' : ' . $usces_settings['country'][ $values['country'] ] . "\r\n";
				}
				$formtag .= __( 'Zip/Postal Code', 'usces' ) . ' : ' . $values['zipcode'] . "\r\n";
				$formtag .= __( 'Phone number', 'usces' ) . ' : ' . $values['tel'] . "\r\n";
				$formtag .= __( 'FAX number', 'usces' ) . ' : ' . $values['fax'] . "\r\n";
				$formtag .= usces_mail_custom_field_info( $main_mode, 'fax_after', $order_id );
				break;
		}
	}
	$res = apply_filters( 'usces_filter_apply_mail_addressform', $formtag, $type, $data, $order_id );

	if ( 'return' === $out ) {
		return $res;
	} else {
		wel_esc_script_e( $res );
	}
}

/**
 * Get order email
 *
 * @param int $order_id Order ID.
 * @return array
 */
function usces_get_order_email( $order_id ) {
	global $wpdb;

	$usces_order_table = $wpdb->prefix . 'usces_order';
	$order_data        = $wpdb->get_results( $wpdb->prepare( "SELECT mem_id, order_email FROM $usces_order_table WHERE ID = %d LIMIT 1", $order_id ) );
	$res               = array(
		'mem_id'      => isset( $order_data[0] ) ? $order_data[0]->mem_id : 0,
		'order_email' => isset( $order_data[0] ) ? $order_data[0]->order_email : null,
	);
	return $res;
}

/**
 * Lines to draw on emails
 *
 * @param int    $type Line type.
 * @param string $email E-mail address.
 * @return string
 */
function usces_mail_line( $type, $email = '' ) {
	$line = '';

	switch ( $type ) {
		case 1:
			$line = '******************************************************';
			break;
		case 2:
			$line = '------------------------------------------------------------------';
			break;
		case 3:
			$line = '=============================================';
			break;
	}

	$line = apply_filters( 'usces_filter_mail_line', $line, $type, $email );

	return $line . "\r\n";
}

/**
 * Tax label for mail
 *
 * @param array $data Order data.
 * @return string
 */
function usces_mail_tax_label( $data ) {
	global $usces;

	$tax_label = '';

	if ( empty( $data ) || ! array_key_exists( 'order_condition', $data ) ) {
		$condition       = $usces->get_condition();
		$tax_mode        = $usces->options['tax_mode'];
		$reduced_taxrate = usces_is_reduced_taxrate();
		$tax_rate        = ( ! empty( $usces->options['tax_rate'] ) && ! $reduced_taxrate ) ? '(' . $usces->options['tax_rate'] . __( '%', 'usces' ) . ')' : '';
	} else {
		$condition = maybe_unserialize( $data['order_condition'] );
		$tax_mode  = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : $usces->options['tax_mode'];
		if ( isset( $condition['applicable_taxrate'] ) ) {
			$reduced_taxrate = ( 'reduced' === $condition['applicable_taxrate'] ) ? true : false;
		} else {
			$reduced_taxrate = usces_is_reduced_taxrate();
		}
		$tax_rate = ( ! empty( $condition['tax_rate'] ) && ! $reduced_taxrate ) ? '(' . $condition['tax_rate'] . __( '%', 'usces' ) . ')' : '';
	}

	if ( 'exclude' === $tax_mode ) {
		$tax_label = __( 'consumption tax', 'usces' ) . $tax_rate;
	} else {
		if ( usces_is_html_mail() ) {
			$tax_label = __( 'Internal tax', 'usces' ) . $tax_rate;
		} else {
			if ( isset( $condition['tax_mode'] ) && ! empty( $data['ID'] ) ) {
				$materials = array(
					'total_items_price' => $data['order_item_total_price'],
					'discount'          => $data['order_discount'],
					'shipping_charge'   => $data['order_shipping_charge'],
					'cod_fee'           => $data['order_cod_fee'],
					'use_point'         => $data['order_usedpoint'],
					'carts'             => usces_get_ordercartdata( $data['ID'] ),
					'condition'         => $condition,
					'order_id'          => $data['ID'],
				);
				$tax_label = '( ' . __( 'Internal tax', 'usces' ) . $tax_rate . ' : ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
			} else {
				$tax_label = __( 'Internal tax', 'usces' ) . $tax_rate;
			}
		}
	}
	$tax_label = apply_filters( 'usces_filter_mail_tax_label', $tax_label );

	return wel_esc_script( $tax_label );
}

/**
 * Tax Calculation for mail
 *
 * @param array $data When the 'order' key in $data exist, entry data, unless order data.
 * @return string
 */
function usces_mail_tax( $data ) {
	global $usces;

	$tax_str = '';

	if ( empty( $data ) || ! array_key_exists( 'order_condition', $data ) ) {
		$condition = $usces->get_condition();
		$tax_mode  = $usces->options['tax_mode'];
	} else {
		$condition = maybe_unserialize( $data['order_condition'] );
		$tax_mode  = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : $usces->options['tax_mode'];
	}

	if ( 'exclude' === $tax_mode ) {
		if ( array_key_exists( 'order', $data ) && array_key_exists( 'tax', $data['order'] ) ) { /* from Entry */
			$tax = $data['order']['tax'];
		} elseif ( array_key_exists( 'order_tax', $data ) ) { /* from Order Data */
			$tax = $data['order_tax'];
		} else {
			$tax = 0;
		}
		$tax_str = usces_crform( $tax, true, false, 'return' );
	} else {
		if ( array_key_exists( 'order', $data ) ) { /* from Entry */
			$materials = array(
				'total_items_price' => $data['order']['total_items_price'],
				'discount'          => ( isset( $data['order']['discount'] ) ) ? $data['order']['discount'] : 0,
				'shipping_charge'   => ( isset( $data['order']['shipping_charge'] ) ) ? $data['order']['shipping_charge'] : 0,
				'cod_fee'           => ( isset( $data['order']['cod_fee'] ) ) ? $data['order']['cod_fee'] : 0,
				'use_point'         => ( isset( $data['order']['use_point'] ) ) ? $data['order']['use_point'] : 0,
			);
			$tax_str   = '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
		} elseif ( array_key_exists( 'order_tax', $data ) ) { /* from Order Data */
			$materials = array(
				'total_items_price' => $data['order_item_total_price'],
				'discount'          => $data['order_discount'],
				'shipping_charge'   => $data['order_shipping_charge'],
				'cod_fee'           => $data['order_cod_fee'],
				'use_point'         => $data['order_usedpoint'],
				'carts'             => usces_get_ordercartdata( $data['ID'] ),
				'condition'         => unserialize( $data['order_condition'] ),
			);
			$tax_str   = '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
		} else {
			$materials = array();
			$tax_str   = '( ' . usces_crform( usces_internal_tax( $materials, 'return' ), true, false, 'return' ) . ' )';
		}
	}
	$tax_str = apply_filters( 'usces_filter_mail_tax', $tax_str );

	return wel_esc_script( $tax_str );
}
