<?php
/**
 * Initial value settings.
 *
 * @package Welcart
 */

/**
 * Option - usces_zaiko_status
 * key(slug) => 在庫ステータス
 *
 * @var array
 */
$zaiko_status = array(
	'0' => __( 'In Stock', 'usces' ),
	'1' => __( 'A Few Stock', 'usces' ),
	'2' => __( 'Sold Out', 'usces' ),
	'3' => __( 'Out Of Stock', 'usces' ),
	'4' => __( 'Out of print', 'usces' ),
);

/**
 * Option - usces_management_status
 * key(slug) => 受注ステータス
 *
 * @var array
 */
$management_status = array(
	'estimate'    => __( 'An estimate', 'usces' ),
	'adminorder'  => __( 'Management of Note', 'usces' ),
	'noreceipt'   => __( 'unpaid', 'usces' ),
	'receipted'   => __( 'payment confirmed', 'usces' ),
	'duringorder' => __( 'temporaly out of stock', 'usces' ),
	'cancel'      => __( 'Cancel', 'usces' ),
	'completion'  => __( 'It has sent it out.', 'usces' ),
	'pending'     => __( 'Pending', 'usces' ),
);

/**
 * Option - usces_customer_status
 * key(slug) => 会員ランク
 *
 * @var array
 */
$customer_status = array(
	'0'  => __( 'notmal member', 'usces' ),
	'1'  => __( 'good member', 'usces' ),
	'2'  => __( 'VIP member', 'usces' ),
	'99' => __( 'bad member', 'usces' ),
);

/**
 * Option - usces_payment_structure
 * key(slug) => 支払方法名
 *
 * @var array
 */
$payment_structure = array(
	'acting'           => __( 'The representation supplier settlement', 'usces' ),
	'transferAdvance'  => __( 'Transfer (prepayment)', 'usces' ),
	'transferDeferred' => __( 'Transfer (postpay)', 'usces' ),
	'COD'              => __( 'COD', 'usces' ),
);

/**
 * Option - usces_display_mode
 * key(slug) => 表示モード
 *
 * @var array
 */
$display_mode = array(
	'Usualsale'       => __( 'Normal business', 'usces' ),
	'Promotionsale'   => __( 'During the campaign', 'usces' ),
	'Maintenancemode' => __( 'Under Maintenance', 'usces' ),
);

/**
 * Option - usces_shipping_rule
 * key => 発送日目安
 *
 * @var array
 */
$shipping_rule = array(
	'0' => __( '-- Select --', 'usces' ),
	'1' => __( 'immediately', 'usces' ),
	'2' => __( '1-2 days', 'usces' ),
	'3' => __( '2-3days', 'usces' ),
	'4' => __( '3-5days', 'usces' ),
	'5' => __( '4-6days', 'usces' ),
	'6' => __( 'about 1 week later', 'usces' ),
	'7' => __( 'about 2 weeks later', 'usces' ),
	'8' => __( 'about 3 weeks later', 'usces' ),
	'9' => __( 'after we get new items', 'usces' ),
);

/**
 * Option - usces_shipping_indication
 * key => 配達日数
 *
 * @var array
 */
$shipping_indication = array( 0, 0, 2, 3, 5, 6, 7, 14, 21, 0 );

/**
 * Option - usces_item_option_select
 * key => input|select属性
 *
 * @var array
 */
$item_option_select = array(
	'0' => __( 'Single-select', 'usces' ),
	'1' => __( 'Multi-select', 'usces' ),
	'2' => __( 'Text', 'usces' ),
	'3' => __( 'Radio-button', 'usces' ),
	'4' => __( 'Check-box', 'usces' ),
	'5' => __( 'Text-area', 'usces' ),
);

/**
 * Option - usces_custom_order_select
 * key => input|select属性
 *
 * @var array
 */
$custom_order_select = array(
	'0' => __( 'Single-select', 'usces' ),
	'2' => __( 'Text', 'usces' ),
	'3' => __( 'Radio-button', 'usces' ),
	'4' => __( 'Check-box', 'usces' ),
	'5' => __( 'Text-area', 'usces' ),
);

/**
 * Option - usces_custom_customer_select
 * key => input|select属性
 *
 * @var array
 */
$custom_customer_select = array(
	'0' => __( 'Single-select', 'usces' ),
	'2' => __( 'Text', 'usces' ),
	'3' => __( 'Radio-button', 'usces' ),
	'4' => __( 'Check-box', 'usces' ),
	'5' => __( 'Text-area', 'usces' ),
);

/**
 * Option - usces_custom_delivery_select
 * key => input|select属性
 *
 * @var array
 */
$custom_delivery_select = array(
	'0' => __( 'Single-select', 'usces' ),
	'2' => __( 'Text', 'usces' ),
	'3' => __( 'Radio-button', 'usces' ),
	'4' => __( 'Check-box', 'usces' ),
	'5' => __( 'Text-area', 'usces' ),
);

/**
 * Option - usces_custom_member_select
 * key => input|select属性
 *
 * @var array
 */
$custom_member_select = array(
	'0' => __( 'Single-select', 'usces' ),
	'2' => __( 'Text', 'usces' ),
	'3' => __( 'Radio-button', 'usces' ),
	'4' => __( 'Check-box', 'usces' ),
	'5' => __( 'Text-area', 'usces' ),
);

/**
 * Option - usces_custom_field_position_select
 * key(slug) => カスタムフィールドの配置場所
 *
 * @var array
 */
$custom_field_position_select = array(
	'name_pre'   => __( 'Previous the name', 'usces' ),
	'name_after' => __( 'After the name', 'usces' ),
	'fax_after'  => __( 'After the fax', 'usces' ),
);

/**
 * Option - usces_order_mail_print_fields
 * key(slug) => 各種メール
 *
 * @var array
 */
$order_mail_print_fields = array(
	'ordermail'      => array(
		'type'  => 'mail',
		'label' => __( 'Mail for confirmation of order', 'usces' ),
		'alias' => __( 'Order e-mail', 'usces' ),
	),
	'changemail'     => array(
		'type'  => 'mail',
		'label' => __( 'Mail for confiemation of change', 'usces' ),
		'alias' => __( 'Change e-mail', 'usces' ),
	),
	'receiptmail'    => array(
		'type'  => 'mail',
		'label' => __( 'Mail for confirmation of transter', 'usces' ),
		'alias' => __( 'Receipt e-mail', 'usces' ),
	),
	'mitumorimail'   => array(
		'type'  => 'mail',
		'label' => __( 'estimate mail', 'usces' ),
		'alias' => __( 'Estimate e-mail', 'usces' ),
	),
	'cancelmail'     => array(
		'type'  => 'mail',
		'label' => __( 'Cancelling mail', 'usces' ),
		'alias' => __( 'Cancel e-mail', 'usces' ),
	),
	'othermail'      => array(
		'type'  => 'mail',
		'label' => __( 'Other mail', 'usces' ),
		'alias' => __( 'Other e-mail', 'usces' ),
	),
	'completionmail' => array(
		'type'  => 'mail',
		'label' => __( 'Mail for Shipping', 'usces' ),
		'alias' => __( 'Shipping e-mail', 'usces' ),
	),
	'mitumoriprint'  => array(
		'type'  => 'print',
		'label' => __( 'print out the estimate', 'usces' ),
		'alias' => __( 'Estimate print', 'usces' ),
	),
	'nohinprint'     => array(
		'type'  => 'print',
		'label' => __( 'print out Delivery Statement', 'usces' ),
		'alias' => __( 'Delivery print', 'usces' ),
	),
	'billprint'      => array(
		'type'  => 'print',
		'label' => __( 'Print Invoice', 'usces' ),
		'alias' => __( 'Invoice print', 'usces' ),
	),
	'receiptprint'   => array(
		'type'  => 'print',
		'label' => __( 'Print Receipt', 'usces' ),
		'alias' => __( 'Receipt print', 'usces' ),
	),
);

update_option( 'usces_management_status', $management_status );
update_option( 'usces_zaiko_status', $zaiko_status );
update_option( 'usces_customer_status', $customer_status );
if ( ! get_option( 'usces_payment_structure' ) ) {
	update_option( 'usces_payment_structure', $payment_structure );
}
update_option( 'usces_display_mode', $display_mode );
update_option( 'usces_shipping_rule', $shipping_rule );
update_option( 'usces_item_option_select', $item_option_select );
update_option( 'usces_custom_order_select', $custom_order_select );
update_option( 'usces_custom_customer_select', $custom_customer_select );
update_option( 'usces_custom_delivery_select', $custom_delivery_select );
update_option( 'usces_custom_member_select', $custom_member_select );
update_option( 'usces_custom_field_position_select', $custom_field_position_select );
update_option( 'usces_order_mail_print_fields', $order_mail_print_fields );

update_option( 'usces_currency_symbol', __( '$', 'usces' ) );
if ( ! get_option( 'usces_wcid' ) ) {
	update_option( 'usces_wcid', md5( uniqid( wp_rand(), 1 ) ) );
}

$usces_op = get_option( 'usces' );
if ( ! is_array( $usces_op ) || empty( $usces_op ) ) {
	$usces_op = array();
}

$uop_init                 = array();
$uop_init['company_name'] = isset( $usces_op['company_name'] ) ? $usces_op['company_name'] : '';
$uop_init['zip_code']     = isset( $usces_op['zip_code'] ) ? $usces_op['zip_code'] : '';
$uop_init['address1']     = isset( $usces_op['address1'] ) ? $usces_op['address1'] : '';
$uop_init['address2']     = isset( $usces_op['address2'] ) ? $usces_op['address2'] : '';
$uop_init['tel_number']   = isset( $usces_op['tel_number'] ) ? $usces_op['tel_number'] : '';
$uop_init['fax_number']   = isset( $usces_op['fax_number'] ) ? $usces_op['fax_number'] : '';
$uop_init['inquiry_mail'] = isset( $usces_op['inquiry_mail'] ) ? $usces_op['inquiry_mail'] : '';

$usces_op['mail_default']['title']['thankyou']       = __( 'Confirmation of order details', 'usces' );
$usces_op['mail_default']['title']['order']          = __( 'An order report', 'usces' );
$usces_op['mail_default']['title']['inquiry']        = __( 'Your question is sent', 'usces' );
$usces_op['mail_default']['title']['returninq']      = __( 'About your question', 'usces' );
$usces_op['mail_default']['title']['membercomp']     = __( 'Comfirmation of your registration for membership', 'usces' );
$usces_op['mail_default']['title']['completionmail'] = __( 'Information for shipping of your ordered items', 'usces' );
$usces_op['mail_default']['title']['ordermail']      = __( 'Confirmation of order details', 'usces' );
$usces_op['mail_default']['title']['changemail']     = __( 'Confirmation of change for your order details', 'usces' );
$usces_op['mail_default']['title']['receiptmail']    = __( 'Confirmation mail for your transfer', 'usces' );
$usces_op['mail_default']['title']['mitumorimail']   = __( 'Estimate', 'usces' );
$usces_op['mail_default']['title']['cancelmail']     = __( 'Confirmatin of your cancellation', 'usces' );
$usces_op['mail_default']['title']['othermail']      = '[]';

$usces_op['mail_default']['header']['thankyou']        = sprintf( __( 'Thank you for choosing %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n";
$usces_op['mail_default']['header']['thankyou']       .= __( 'We have received your order. Please check following information.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['thankyou']       .= __( 'We will inform you by e-mail when we are ready to ship ordered items to you.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['order']           = sprintf( __( 'There is new order by %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n";
$usces_op['mail_default']['header']['inquiry']         = sprintf( __( 'Thank you for choosing %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n";
$usces_op['mail_default']['header']['inquiry']        .= __( 'We have received following e-mail.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['inquiry']        .= __( 'We will contact you by e-mail soon.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['returninq']       = '';
$usces_op['mail_default']['header']['membercomp']      = sprintf( __( 'Than you for registrating as %s membership.', 'usces' ), get_option( 'blogname' ) ) . "\r\n\r\n";
$usces_op['mail_default']['header']['membercomp']     .= __( "You can chek your purchase status at section 'membership information'.", 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['completionmail']  = __( 'Your ordered items have been sent today.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['completionmail'] .= __( 'It will be delivered by company xxx in couple of days.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['completionmail'] .= __( 'Please contact us in case you have any problems with receiving your items.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['ordermail']       = sprintf( __( 'Thank you for choosing %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n";
$usces_op['mail_default']['header']['ordermail']      .= __( 'We have received your order. Please check following information.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['ordermail']      .= __( 'We will inform you by e-mail when we are ready to ship ordered items to you.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['changemail']      = sprintf( __( 'Thank you for choosing %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n";
$usces_op['mail_default']['header']['changemail']     .= __( 'You have changed your order as following.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['changemail']     .= __( 'We will inform you by e-mail when we are ready to send your items.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['receiptmail']     = sprintf( __( 'Thank you for choosing %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n";
$usces_op['mail_default']['header']['receiptmail']    .= __( 'Your transfer have been made successfully.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['receiptmail']    .= __( 'We will inform you by e-mail when we are ready to send your items.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['mitumorimail']    = sprintf( __( 'Thank you for choosing %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n";
$usces_op['mail_default']['header']['mitumorimail']   .= __( 'We will send you following estimate for your items.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['mitumorimail']   .= __( 'This estimate is valid for one week.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['cancelmail']      = sprintf( __( 'Thank you for choosing %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n";
$usces_op['mail_default']['header']['cancelmail']     .= __( 'We have received your cancellation for your order.', 'usces' ) . "\r\n\r\n";
$usces_op['mail_default']['header']['othermail']       = sprintf( __( 'Thank you for choosing %s.', 'usces' ), get_option( 'blogname' ) ) . "\r\n\r\n";

$usces_op['mail_default']['footer']['thankyou']       = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['order']          = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['inquiry']        = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['returninq']      = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['membercomp']     = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['completionmail'] = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['ordermail']      = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['changemail']     = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['receiptmail']    = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['mitumorimail']   = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['cancelmail']     = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";
$usces_op['mail_default']['footer']['othermail']      = "=============================================\r\n" . get_option( 'blogname' ) . "\r\n" . $uop_init['company_name'] . "\r\n" . __( 'zip code', 'usces' ) . ' ' . $uop_init['zip_code'] . "\r\n" . $uop_init['address1'] . "\r\n" . $uop_init['address2'] . "\r\n" . 'TEL ' . $uop_init['tel_number'] . "\r\n" . 'FAX ' . $uop_init['fax_number'] . "\r\n" . __( 'contact', 'usces' ) . ' ' . $uop_init['inquiry_mail'] . "\r\n" . get_option( 'home' ) . "\r\n=============================================\r\n";

$usces_op['usces_shipping_indication'] = $shipping_indication;

/**
 * Option - usces
 */
update_option( 'usces', $usces_op );

if ( isset( $usces_op['stock_status_label'] ) && is_array( $usces_op['stock_status_label'] ) ) {
	$stock_status_label = $zaiko_status;
	foreach ( $stock_status_label as $key => $label ) {
		if ( ! empty( $usces_op['stock_status_label'][ $key ] ) && $label != $usces_op['stock_status_label'][ $key ] ) {
			$stock_status_label[ $key ] = $usces_op['stock_status_label'][ $key ];
		}
	}
	if ( $stock_status_label !== $zaiko_status ) {
		update_option( 'usces_zaiko_status', $stock_status_label );
	}
}

/**
 * Global usces_settings - language
 *
 * @var array
 */
$usces_settings['language'] = array();

/**
 * Global usces_settings - currency
 * key(Country codes alpha-2) => Country codes alpha-3, Number of decimal digits, Decimal separator, Currency separator, Currency symbol
 *
 * @var array
 */
$usces_settings['currency'] = array(
	'DZ' => array( 'DZD', 2, '.', ',', 'د.ج' ),
	'AR' => array( 'ARS', 2, '.', ',', '$' ),
	'AU' => array( 'AUD', 2, '.', ',', '$' ),
	'AT' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'AZ' => array( 'AZN', 2, '.', ',', 'man.' ),
	'BH' => array( 'BHD', 2, '.', ',', 'BD' ),
	'BB' => array( 'BBD', 2, '.', ',', '$' ),
	'BE' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'BD' => array( 'BDT', 2, '.', ',', '&#x9F3;' ),
	'BY' => array( 'BYR', 2, '.', ',', 'p.' ),
	'BT' => array( 'BTN', 2, '.', ',', 'Nu' ),
	'BW' => array( 'BWP', 2, '.', ',', 'P' ),
	'BN' => array( 'BND', 2, '.', ',', 'B$' ),
	'BR' => array( 'BRL', 2, '.', ',', '$' ),
	'BG' => array( 'BGN', 2, '.', ',', 'лв' ),
	'KH' => array( 'KHR', 2, '.', ',', '&#x17DB;' ),
	'CA' => array( 'CAD', 2, '.', ',', '$' ),
	'CL' => array( 'CLP', 2, '.', ',', '$' ),
	'CN' => array( 'CNY', 2, '.', ',', '&yen;' ),
	'CO' => array( 'COP', 2, '.', ',', '$' ),
	'CR' => array( 'CRC', 2, '.', ',', '₡' ),
	'CI' => array( 'XOF', 2, '.', ',', 'Fr' ),
	'HR' => array( 'HRK', 2, '.', ',', 'kn' ),
	'CU' => array( 'CUC', 2, '.', ',', '$' ),
	'CY' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'CZ' => array( 'CZK', 2, '.', ',', 'Kč' ),
	'DK' => array( 'DKK', 2, '.', ',', 'kr' ),
	'DJ' => array( 'DJF', 2, '.', ',', 'Fr' ),
	'DO' => array( 'DOP', 2, '.', ',', 'RD$' ),
	'SV' => array( 'USD', 2, '.', ',', '$' ),
	'EE' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'ET' => array( 'ETB', 2, '.', ',', 'Br' ),
	'FI' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'EC' => array( 'USD', 2, '.', ',', '$' ),
	'EG' => array( 'EGP', 2, '.', ',', '£' ),
	'FJ' => array( 'FJD', 2, '.', ',', '$' ),
	'FR' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'GA' => array( 'XAF', 2, '.', ',', 'Fr' ),
	'DE' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'GH' => array( 'GHC', 2, '.', ',', '&#x20B5;' ),
	'GR' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'GT' => array( 'GTQ', 2, '.', ',', 'Q' ),
	'HN' => array( 'HNL', 2, '.', ',', 'L' ),
	'HK' => array( 'HKD', 2, '.', ',', '$' ),
	'HU' => array( 'HUF', 2, '.', ',', 'Ft' ),
	'IS' => array( 'ISK', 2, '.', ',', 'kr' ),
	'IN' => array( 'INR', 2, '.', ',', '&#x20A8;' ),
	'ID' => array( 'IDR', 2, '.', ',', 'Rp' ),
	'IE' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'IQ' => array( 'IQD', 2, '.', ',', 'د.ع' ),
	'IR' => array( 'IRR', 2, '.', ',', 'R' ),
	'IL' => array( 'ILS', 2, '.', ',', '&#x20AA;' ),
	'IT' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'JP' => array( 'JPY', 0, '.', ',', '&yen;' ),
	'JM' => array( 'JMD', 2, '.', ',', '$' ),
	'JO' => array( 'JOD', 2, '.', ',', 'د.ا' ),
	'KE' => array( 'KES', 2, '.', ',', 'S' ),
	'KW' => array( 'KWD', 2, '.', ',', 'د.ك' ),
	'KZ' => array( 'KAZ', 2, '.', ',', '&#x20B8;' ),
	'LV' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'LI' => array( 'CHF', 2, '.', ',', 'S₣' ),
	'LA' => array( 'LAK', 2, '.', ',', '&#x20AD;' ),
	'LT' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'LU' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'MO' => array( 'MOP', 2, '.', ',', '$' ),
	'MK' => array( 'MKD', 2, '.', ',', 'ден' ),
	'MG' => array( 'MGA', 2, '.', ',', '' ),
	'MY' => array( 'MYR', 2, '.', ',', 'RM' ),
	'MT' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'MU' => array( 'MUR', 2, '.', ',', 'Rs' ),
	'MX' => array( 'MXN', 2, '.', ',', '$' ),
	'MV' => array( 'MVR', 2, '.', ',', '£' ),
	'MC' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'MN' => array( 'MNT', 2, '.', ',', '&#x20AE;' ),
	'MA' => array( 'MAD', 2, '.', ',', 'د.م.' ),
	'MM' => array( 'MMK', 2, '.', ',', 'Kyat' ),
	'NL' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'NP' => array( 'NPR', 2, '.', ',', 'Rs' ),
	'NZ' => array( 'NZD', 2, '.', ',', '$' ),
	'NG' => array( 'NGN', 2, '.', ',', '₦' ),
	'NO' => array( 'NOK', 2, '.', ',', 'kr' ),
	'NC' => array( 'CFP', 2, '.', ',', 'F' ),
	'OM' => array( 'OMR', 2, '.', ',', 'R' ),
	'PA' => array( 'PAB', 2, '.', ',', 'B/.' ),
	'PK' => array( 'PKR', 2, '.', ',', 'Rs' ),
	'PG' => array( 'PKG', 2, '.', ',', 'K' ),
	'PY' => array( 'PYG', 2, '.', ',', '&#x20B2;' ),
	'PE' => array( 'PEN', 2, '.', ',', 'S/.' ),
	'PH' => array( 'PHP', 2, '.', ',', 'P' ),
	'PL' => array( 'PLN', 2, '.', ',', 'zł' ),
	'PT' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'PR' => array( 'USD', 2, '.', ',', '$' ),
	'QA' => array( 'QAR', 2, '.', ',', 'R' ),
	'RO' => array( 'ROL', 2, '.', ',', 'L' ),
	'RU' => array( 'RUB', 2, '.', ',', '&#x20BD;' ),
	'RW' => array( 'RWF', 2, '.', ',', 'Fr' ),
	'SA' => array( 'SAR', 2, '.', ',', 'R' ),
	'SN' => array( 'XOF', 2, '.', ',', 'Fr' ),
	'RS' => array( 'SRB', 2, '.', ',', 'RSD' ),
	'SG' => array( 'SGD', 2, '.', ',', '$' ),
	'SK' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'SI' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'SB' => array( 'SBD', 2, '.', ',', '$' ),
	'ZA' => array( 'ZAR', 2, '.', ',', 'R' ),
	'KR' => array( 'KRW', 0, '.', ',', '&#x20A9;' ),
	'SS' => array( 'SSP', 0, '.', ',', '£' ),
	'ES' => array( 'EUR', 2, '.', ',', '&#x20AC;' ),
	'LK' => array( 'LKR', 2, '.', ',', 'SLRs' ),
	'SD' => array( 'SDG', 2, '.', ',', '£' ),
	'SY' => array( 'SYP', 2, '.', ',', '£' ),
	'SE' => array( 'SEK', 2, '.', ',', 'kr' ),
	'CH' => array( 'CHF', 2, '.', ',', 'Fr.' ),
	'TW' => array( 'TWD', 0, '.', ',', 'NT$' ),
	'TZ' => array( 'TZS', 2, '.', ',', 'Tsh' ),
	'TH' => array( 'THB', 2, '.', ',', '฿' ),
	'TG' => array( 'XOF', 2, '.', ',', 'Fr' ),
	'TT' => array( 'TTD', 2, '.', ',', '$' ),
	'TN' => array( 'TND', 2, '.', ',', 'د.ت' ),
	'TR' => array( 'TRL', 2, '.', ',', '₤' ),
	'UG' => array( 'UGX', 2, '.', ',', 'Ush' ),
	'UA' => array( 'UAH', 2, '.', ',', '&#x20B4;' ),
	'AE' => array( 'AED', 2, '.', ',', 'DH' ),
	'GB' => array( 'GBP', 2, '.', ',', '£' ),
	'US' => array( 'USD', 2, '.', ',', '$' ),
	'UY' => array( 'UYU', 2, '.', ',', '$' ),
	'VE' => array( 'VEB', 2, '.', ',', 'Bs' ),
	'VN' => array( 'VND', 2, '.', ',', '₫' ),
	'ZW' => array( 'ZWD', 2, '.', ',', '$' ),
	'NA' => array( 'NAM', 2, '.', ',', '$' ),
	'OO' => array( 'USD', 2, '.', ',', '$' ),
);

/**
 * Global usces_settings - nameform
 * key(Country codes alpha-2) => 1|0
 *
 * @var array
 */
$usces_settings['nameform'] = array(
	'DZ' => 1,
	'AR' => 1,
	'AU' => 1,
	'AT' => 1,
	'AZ' => 1,
	'BH' => 1,
	'BB' => 1,
	'BD' => 1,
	'BY' => 1,
	'BE' => 1,
	'BT' => 1,
	'BW' => 1,
	'BN' => 1,
	'BR' => 1,
	'BG' => 1,
	'KH' => 1,
	'CA' => 1,
	'CL' => 1,
	'CN' => 0,
	'CO' => 1,
	'CR' => 1,
	'CI' => 1,
	'HR' => 1,
	'CU' => 1,
	'CY' => 1,
	'CZ' => 1,
	'DK' => 1,
	'DJ' => 1,
	'DO' => 1,
	'SV' => 1,
	'EE' => 1,
	'ET' => 1,
	'FI' => 1,
	'EC' => 1,
	'EG' => 1,
	'FJ' => 1,
	'FR' => 1,
	'GA' => 1,
	'DE' => 1,
	'GH' => 1,
	'GR' => 1,
	'GT' => 1,
	'HN' => 1,
	'HK' => 1,
	'HU' => 1,
	'IS' => 1,
	'IN' => 1,
	'ID' => 1,
	'IE' => 1,
	'IQ' => 1,
	'IR' => 1,
	'IL' => 1,
	'IT' => 1,
	'JP' => 0,
	'JM' => 1,
	'JO' => 1,
	'KE' => 1,
	'KW' => 1,
	'KZ' => 1,
	'LV' => 1,
	'LI' => 1,
	'LA' => 1,
	'LT' => 1,
	'LU' => 1,
	'MO' => 1,
	'MK' => 1,
	'MG' => 1,
	'MY' => 1,
	'MT' => 1,
	'MU' => 1,
	'MX' => 1,
	'MV' => 1,
	'MV' => 1,
	'MN' => 1,
	'MA' => 1,
	'MM' => 1,
	'NL' => 1,
	'NP' => 1,
	'NZ' => 1,
	'NG' => 1,
	'NO' => 1,
	'NC' => 1,
	'OM' => 1,
	'PA' => 1,
	'PK' => 1,
	'PG' => 1,
	'PY' => 1,
	'PE' => 1,
	'PH' => 1,
	'PL' => 1,
	'PT' => 1,
	'PR' => 1,
	'QA' => 1,
	'RO' => 1,
	'RU' => 1,
	'RW' => 1,
	'SA' => 1,
	'SN' => 1,
	'RS' => 1,
	'SG' => 1,
	'SK' => 1,
	'SI' => 1,
	'SB' => 1,
	'ZA' => 1,
	'KR' => 1,
	'SS' => 1,
	'ES' => 1,
	'LK' => 1,
	'SD' => 1,
	'SY' => 1,
	'SE' => 1,
	'CH' => 1,
	'TW' => 0,
	'TZ' => 1,
	'TH' => 1,
	'TG' => 1,
	'TT' => 1,
	'TN' => 1,
	'TR' => 1,
	'UG' => 1,
	'UA' => 1,
	'AE' => 1,
	'GB' => 1,
	'US' => 1,
	'UY' => 1,
	'VE' => 1,
	'VN' => 1,
	'ZW' => 1,
	'NA' => 1,
	'OO' => 1,
);

/**
 * Global usces_settings - addressform
 * key(Country codes alpha-2) => US|JP|CN
 *
 * @var array
 */
$usces_settings['addressform'] = array(
	'DZ' => 'US',
	'AR' => 'US',
	'AU' => 'US',
	'AT' => 'US',
	'AZ' => 'US',
	'BH' => 'US',
	'BB' => 'US',
	'BD' => 'US',
	'BY' => 'US',
	'BE' => 'US',
	'BT' => 'US',
	'BW' => 'US',
	'BN' => 'US',
	'BR' => 'US',
	'BG' => 'US',
	'KH' => 'US',
	'CA' => 'US',
	'CL' => 'US',
	'CN' => 'CN',
	'CO' => 'US',
	'CR' => 'US',
	'CI' => 'US',
	'HR' => 'US',
	'CU' => 'US',
	'CY' => 'US',
	'CZ' => 'US',
	'DK' => 'US',
	'DJ' => 'US',
	'DO' => 'US',
	'FI' => 'US',
	'EC' => 'US',
	'EG' => 'US',
	'SV' => 'US',
	'EE' => 'US',
	'ET' => 'US',
	'FJ' => 'US',
	'FR' => 'US',
	'GA' => 'US',
	'DE' => 'US',
	'GH' => 'US',
	'GR' => 'US',
	'GT' => 'US',
	'HN' => 'US',
	'HK' => 'US',
	'HU' => 'US',
	'IS' => 'US',
	'IN' => 'US',
	'ID' => 'US',
	'IE' => 'US',
	'IQ' => 'US',
	'IR' => 'US',
	'IL' => 'US',
	'IT' => 'US',
	'JP' => 'JP',
	'JM' => 'US',
	'JO' => 'US',
	'KE' => 'US',
	'KW' => 'US',
	'KZ' => 'US',
	'LV' => 'US',
	'LA' => 'US',
	'LI' => 'US',
	'LT' => 'US',
	'LU' => 'US',
	'MO' => 'US',
	'MK' => 'US',
	'MG' => 'US',
	'MY' => 'US',
	'MT' => 'US',
	'MU' => 'US',
	'MX' => 'US',
	'MV' => 'US',
	'MC' => 'US',
	'MN' => 'US',
	'MA' => 'US',
	'MM' => 'US',
	'NL' => 'US',
	'NP' => 'US',
	'NZ' => 'US',
	'NG' => 'US',
	'NO' => 'US',
	'NC' => 'US',
	'OM' => 'US',
	'PA' => 'US',
	'PK' => 'US',
	'PG' => 'US',
	'PY' => 'US',
	'PE' => 'US',
	'PH' => 'US',
	'PL' => 'US',
	'PT' => 'US',
	'PR' => 'US',
	'QA' => 'US',
	'RO' => 'US',
	'RU' => 'US',
	'RW' => 'US',
	'SA' => 'US',
	'SN' => 'US',
	'RS' => 'US',
	'SG' => 'US',
	'SK' => 'US',
	'SI' => 'US',
	'SB' => 'US',
	'ZA' => 'US',
	'KR' => 'US',
	'SS' => 'US',
	'ES' => 'US',
	'LK' => 'US',
	'SD' => 'US',
	'SY' => 'US',
	'SE' => 'US',
	'CH' => 'US',
	'TW' => 'JP',
	'TZ' => 'US',
	'TH' => 'US',
	'TG' => 'US',
	'TT' => 'US',
	'TN' => 'US',
	'TR' => 'US',
	'UG' => 'US',
	'UA' => 'US',
	'AE' => 'US',
	'GB' => 'US',
	'US' => 'US',
	'UY' => 'US',
	'VE' => 'US',
	'VN' => 'US',
	'ZW' => 'US',
	'NA' => 'US',
	'OO' => 'US',
);

/**
 * Global usces_settings - country
 * key(Country codes alpha-2) => Country name
 *
 * @var array
 */
$usces_settings['country'] = array(
	'DZ' => __( 'Algeria', 'usces' ),
	'AR' => __( 'Argentina', 'usces' ),
	'AU' => __( 'Australia', 'usces' ),
	'AT' => __( 'Austria', 'usces' ),
	'AZ' => __( 'Azerbaidjan', 'usces' ),
	'BH' => __( 'Bahrain', 'usces' ),
	'BB' => __( 'Barbados', 'usces' ),
	'BD' => __( 'Bangladesh', 'usces' ),
	'BY' => __( 'Belarus', 'usces' ),
	'BE' => __( 'Belgium', 'usces' ),
	'BT' => __( 'Bhutan', 'usces' ),
	'BW' => __( 'Botswana', 'usces' ),
	'BN' => __( 'Brunei', 'usces' ),
	'BR' => __( 'Brazil', 'usces' ),
	'BG' => __( 'Bulgaria', 'usces' ),
	'KH' => __( 'Cambodia', 'usces' ),
	'CA' => __( 'Canada', 'usces' ),
	'CL' => __( 'Chile', 'usces' ),
	'CN' => __( 'China', 'usces' ),
	'CO' => __( 'Colombia', 'usces' ),
	'CR' => __( 'Costa Rica', 'usces' ),
	'CI' => __( "Cote d'lvoire", 'usces' ),
	'HR' => __( 'Croatia', 'usces' ),
	'CU' => __( 'Cuba', 'usces' ),
	'CY' => __( 'Cyprus', 'usces' ),
	'CZ' => __( 'Czech Republic', 'usces' ),
	'DK' => __( 'Denmark', 'usces' ),
	'DJ' => __( 'Djibouti', 'usces' ),
	'DO' => __( 'Dominican Republic', 'usces' ),
	'FI' => __( 'Finland', 'usces' ),
	'EC' => __( 'Ecuador', 'usces' ),
	'EG' => __( 'Egypt', 'usces' ),
	'SV' => __( 'El Salvador', 'usces' ),
	'EE' => __( 'Estonia', 'usces' ),
	'ET' => __( 'Ethiopia', 'usces' ),
	'FJ' => __( 'Fiji', 'usces' ),
	'FR' => __( 'France', 'usces' ),
	'GA' => __( 'Gabon', 'usces' ),
	'DE' => __( 'Germany', 'usces' ),
	'GH' => __( 'Ghana', 'usces' ),
	'GR' => __( 'Greece', 'usces' ),
	'GT' => __( 'Guatemala', 'usces' ),
	'HN' => __( 'Honduras', 'usces' ),
	'HK' => __( 'Hong Kong', 'usces' ),
	'HU' => __( 'Hungary', 'usces' ),
	'IS' => __( 'Iceland', 'usces' ),
	'IN' => __( 'India', 'usces' ),
	'ID' => __( 'Indonesia', 'usces' ),
	'IE' => __( 'Ireland', 'usces' ),
	'IQ' => __( 'Iraq', 'usces' ),
	'IR' => __( 'Iran', 'usces' ),
	'IL' => __( 'Israel', 'usces' ),
	'IT' => __( 'Italy', 'usces' ),
	'JP' => __( 'Japan', 'usces' ),
	'JM' => __( 'Jamaica', 'usces' ),
	'JO' => __( 'Jordan', 'usces' ),
	'KE' => __( 'Kenya', 'usces' ),
	'KW' => __( 'Kuwait', 'usces' ),
	'KZ' => __( 'Kazakhstan', 'usces' ),
	'LV' => __( 'Latvia', 'usces' ),
	'LA' => __( 'Laos', 'usces' ),
	'LI' => __( 'Liechtenstein', 'usces' ),
	'LT' => __( 'Lithuania', 'usces' ),
	'LU' => __( 'Luxembourg', 'usces' ),
	'MO' => __( 'Macau', 'usces' ),
	'MK' => __( 'Macedonia', 'usces' ),
	'MG' => __( 'Madagascar', 'usces' ),
	'MY' => __( 'Malaysia', 'usces' ),
	'MT' => __( 'Malta', 'usces' ),
	'MU' => __( 'Mauritius', 'usces' ),
	'MX' => __( 'Mexico', 'usces' ),
	'MV' => __( 'Maldives', 'usces' ),
	'MC' => __( 'Monaco', 'usces' ),
	'MN' => __( 'Mongolia', 'usces' ),
	'MA' => __( 'Morocco', 'usces' ),
	'MM' => __( 'Myanmar', 'usces' ),
	'NL' => __( 'Netherlands', 'usces' ),
	'NP' => __( 'Nepal', 'usces' ),
	'NZ' => __( 'New Zealand', 'usces' ),
	'NG' => __( 'Nigeria', 'usces' ),
	'NO' => __( 'Norway', 'usces' ),
	'NC' => __( 'New Caledonia', 'usces' ),
	'OM' => __( 'Oman', 'usces' ),
	'PA' => __( 'Panama', 'usces' ),
	'PK' => __( 'Pakistan', 'usces' ),
	'PG' => __( 'Papua New Guinea', 'usces' ),
	'PY' => __( 'Paraguay', 'usces' ),
	'PE' => __( 'Peru', 'usces' ),
	'PH' => __( 'Philippines', 'usces' ),
	'PL' => __( 'Poland', 'usces' ),
	'PT' => __( 'Portugal', 'usces' ),
	'PR' => __( 'Puerto Rico', 'usces' ),
	'QA' => __( 'Qatar', 'usces' ),
	'RO' => __( 'Romania', 'usces' ),
	'RU' => __( 'Russia', 'usces' ),
	'RW' => __( 'Rwanda', 'usces' ),
	'SA' => __( 'Saudi Arabia', 'usces' ),
	'SN' => __( 'Senegal', 'usces' ),
	'RS' => __( 'Serbia', 'usces' ),
	'SG' => __( 'Singapore', 'usces' ),
	'SK' => __( 'Slovak', 'usces' ),
	'SI' => __( 'Slovenia', 'usces' ),
	'SB' => __( 'Solomon Islands', 'usces' ),
	'ZA' => __( 'South Africa', 'usces' ),
	'KR' => __( 'South Korea', 'usces' ),
	'SS' => __( 'South Sudan', 'usces' ),
	'ES' => __( 'Spain', 'usces' ),
	'LK' => __( 'Sri Lanka', 'usces' ),
	'SD' => __( 'Sudan', 'usces' ),
	'SY' => __( 'Syria', 'usces' ),
	'SE' => __( 'Sweden', 'usces' ),
	'CH' => __( 'Switzerland', 'usces' ),
	'TW' => __( 'Taiwan', 'usces' ),
	'TZ' => __( 'Tanzania', 'usces' ),
	'TH' => __( 'Thailand', 'usces' ),
	'TG' => __( 'Togo', 'usces' ),
	'TT' => __( 'Trinidad and Tobago', 'usces' ),
	'TN' => __( 'Tunisia', 'usces' ),
	'TR' => __( 'Turkey', 'usces' ),
	'UG' => __( 'Uganda', 'usces' ),
	'UA' => __( 'Ukraine', 'usces' ),
	'AE' => __( 'United Arab Emirates', 'usces' ),
	'GB' => __( 'United Kingdom', 'usces' ),
	'US' => __( 'United States', 'usces' ),
	'UY' => __( 'Uruguay', 'usces' ),
	'VE' => __( 'Venezuela', 'usces' ),
	'VN' => __( 'Vietnam', 'usces' ),
	'ZW' => __( 'Zimbabwe', 'usces' ),
	'NA' => __( 'Republic of Namibia', 'usces' ),
	'OO' => __( 'Other', 'usces' ),
);

/**
 * Global usces_settings - country_num
 * key(Country codes alpha-2) => numeric
 *
 * @var array
 */
$usces_settings['country_num'] = array(
	'DZ' => '213',
	'AR' => '54',
	'AU' => '61',
	'AT' => '43',
	'AZ' => '7',
	'BH' => '973',
	'BB' => '1',
	'BD' => '880',
	'BY' => '375',
	'BE' => '32',
	'BT' => '975',
	'BN' => '673',
	'BR' => '55',
	'BG' => '359',
	'BW' => '267',
	'KH' => '855',
	'CA' => '1',
	'CL' => '56',
	'CN' => '86',
	'CO' => '57',
	'CR' => '506',
	'CI' => '225',
	'HR' => '385',
	'CU' => '53',
	'CY' => '357',
	'CZ' => '420',
	'DK' => '45',
	'DJ' => '253',
	'DO' => '1-809',
	'SV' => '503',
	'EE' => '372',
	'ET' => '251',
	'FI' => '358',
	'EC' => '593',
	'EG' => '20',
	'FJ' => '679',
	'FR' => '33',
	'GA' => '241',
	'DE' => '49',
	'GH' => '233',
	'GR' => '30',
	'GT' => '502',
	'HN' => '504',
	'HK' => '852',
	'HU' => '36',
	'IS' => '354',
	'IN' => '91',
	'ID' => '62',
	'IE' => '353',
	'IQ' => '964',
	'IR' => '98',
	'IL' => '972',
	'IT' => '39',
	'JP' => '81',
	'JM' => '1',
	'JO' => '962',
	'KE' => '254',
	'KW' => '965',
	'KZ' => '7',
	'LV' => '371',
	'LI' => '423',
	'LA' => '856',
	'LT' => '370',
	'LU' => '352',
	'MO' => '853',
	'MK' => '261',
	'MG' => '389',
	'MY' => '60',
	'MT' => '356',
	'MU' => '230',
	'MX' => '52',
	'MV' => '960',
	'MC' => '377',
	'MN' => '976',
	'MA' => '212',
	'MM' => '95',
	'NL' => '31',
	'NP' => '977',
	'NZ' => '64',
	'NG' => '234',
	'NO' => '47',
	'NC' => '687',
	'OM' => '968',
	'PA' => '507',
	'PK' => '92',
	'PG' => '675',
	'PY' => '595',
	'PE' => '51',
	'PH' => '63',
	'PL' => '48',
	'PT' => '351',
	'PR' => '1-787',
	'QA' => '974',
	'RO' => '40',
	'RU' => '7',
	'RW' => '250',
	'SA' => '966',
	'SN' => '221',
	'RS' => '381',
	'SG' => '65',
	'SK' => '421',
	'SI' => '386',
	'SB' => '677',
	'ZA' => '27',
	'KR' => '82',
	'SS' => '221',
	'ES' => '34',
	'LK' => '94',
	'SD' => '249',
	'SY' => '963',
	'SE' => '46',
	'CH' => '41',
	'TW' => '886',
	'TZ' => '255',
	'TH' => '66',
	'TG' => '228',
	'TT' => '1',
	'TN' => '216',
	'TR' => '90',
	'UG' => '256',
	'UA' => '380',
	'AE' => '941',
	'GB' => '44',
	'US' => '1',
	'UY' => '598',
	'VE' => '58',
	'VN' => '84',
	'ZW' => '263',
	'NA' => '264',
	'OO' => '1',
);

/**
 * Global usces_settings - country_code
 * key(Country codes alpha-2) => numeric
 * ISO 3166-1 国名コード
 *
 * @var array
 */
$usces_settings['country_code'] = array(
	'DZ' => '012',
	'AR' => '032',
	'AU' => '036',
	'AT' => '040',
	'AZ' => '031',
	'BH' => '048',
	'BB' => '052',
	'BD' => '050',
	'BY' => '112',
	'BE' => '056',
	'BT' => '064',
	'BN' => '096',
	'BR' => '076',
	'BG' => '100',
	'BW' => '072',
	'KH' => '116',
	'CA' => '124',
	'CL' => '152',
	'CN' => '156',
	'CO' => '170',
	'CR' => '188',
	'CI' => '384',
	'HR' => '191',
	'CU' => '192',
	'CY' => '196',
	'CZ' => '203',
	'DK' => '208',
	'DJ' => '262',
	'DO' => '214',
	'SV' => '222',
	'EE' => '233',
	'ET' => '231',
	'FI' => '246',
	'EC' => '218',
	'EG' => '818',
	'FJ' => '242',
	'FR' => '250',
	'GA' => '266',
	'DE' => '276',
	'GH' => '288',
	'GR' => '300',
	'GT' => '320',
	'HN' => '340',
	'HK' => '344',
	'HU' => '348',
	'IS' => '352',
	'IN' => '356',
	'ID' => '360',
	'IE' => '372',
	'IQ' => '368',
	'IR' => '364',
	'IL' => '376',
	'IT' => '380',
	'JP' => '392',
	'JM' => '388',
	'JO' => '400',
	'KE' => '404',
	'KW' => '414',
	'KZ' => '398',
	'LV' => '428',
	'LI' => '438',
	'LA' => '418',
	'LT' => '440',
	'LU' => '442',
	'MO' => '446',
	'MK' => '807',
	'MG' => '450',
	'MY' => '458',
	'MT' => '470',
	'MU' => '480',
	'MX' => '484',
	'MV' => '462',
	'MC' => '492',
	'MN' => '496',
	'MA' => '504',
	'MM' => '104',
	'NL' => '528',
	'NP' => '524',
	'NZ' => '554',
	'NG' => '566',
	'NO' => '578',
	'NC' => '540',
	'OM' => '512',
	'PA' => '591',
	'PK' => '586',
	'PG' => '598',
	'PY' => '600',
	'PE' => '604',
	'PH' => '608',
	'PL' => '616',
	'PT' => '620',
	'PR' => '630',
	'QA' => '634',
	'RO' => '642',
	'RU' => '643',
	'RW' => '646',
	'SA' => '682',
	'SN' => '686',
	'RS' => '688',
	'SG' => '702',
	'SK' => '703',
	'SI' => '705',
	'SB' => '090',
	'ZA' => '710',
	'KR' => '410',
	'SS' => '728',
	'ES' => '724',
	'LK' => '144',
	'SD' => '736',
	'SY' => '760',
	'SE' => '752',
	'CH' => '756',
	'TW' => '158',
	'TZ' => '834',
	'TH' => '764',
	'TG' => '768',
	'TT' => '780',
	'TN' => '788',
	'TR' => '792',
	'UG' => '800',
	'UA' => '804',
	'AE' => '784',
	'GB' => '826',
	'US' => '840',
	'UY' => '858',
	'VE' => '862',
	'VN' => '704',
	'ZW' => '716',
	'NA' => '516',
	'OO' => '000',
);

/**
 * Global usces_settings - lungage2country
 * key(Locale) => Country codes alpha-2
 *
 * @var array
 */
$usces_settings['lungage2country'] = array(
	'ar_DZ' => 'DZ',
	'es_AR' => 'AR',
	'en_AU' => 'AU',
	'de_AT' => 'AT',
	'az'    => 'AZ',
	'ar_BH' => 'BH',
	'en_BB' => 'BB',
	'bn'    => 'BD',
	'be'    => 'BY',
	'nl_BE' => 'BE',
	'fr_BE' => 'BE',
	'dz'    => 'BT',
	'en_BW' => 'BW',
	'tn_BW' => 'BW',
	'ms_BN' => 'BN',
	'pt_BR' => 'BR',
	'bg'    => 'BG',
	'km'    => 'KH',
	'en_CA' => 'CA',
	'fr_CA' => 'CA',
	'es_CL' => 'CL',
	'zh_CN' => 'CN',
	'zh'    => 'CN',
	'es_CO' => 'CO',
	'es_CR' => 'CR',
	'fr_CI' => 'CI',
	'hr'    => 'HR',
	'es_CU' => 'CU',
	'el_CY' => 'CY',
	'tr_CY' => 'CY',
	'cs_CZ' => 'CZ',
	'cs'    => 'CZ',
	'da'    => 'DK',
	'da_DK' => 'DK',
	'fr_DJ' => 'DJ',
	'ar_DJ' => 'DJ',
	'es_DO' => 'DO',
	'fi_FI' => 'FI',
	'fi'    => 'FI',
	'es_EC' => 'EC',
	'ar_EG' => 'EG',
	'es_SV' => 'SV',
	'et'    => 'EE',
	'am_ET' => 'ET',
	'sv_FI' => 'FI',
	'en_FJ' => 'FJ',
	'fr'    => 'FR',
	'fr_FR' => 'FR',
	'fr_GA' => 'GA',
	'de'    => 'DE',
	'de_DE' => 'DE',
	'en_GH' => 'GH',
	'el'    => 'GR',
	'el_GR' => 'GR',
	'el_GR' => 'GR',
	'es_GT' => 'GT',
	'es_HN' => 'HN',
	'zh_HK' => 'HK',
	'en_HK' => 'HK',
	'hu_HU' => 'HU',
	'hu'    => 'HU',
	'is'    => 'IS',
	'hi'    => 'IN',
	'hi_IN' => 'IN',
	'id'    => 'ID',
	'id_ID' => 'ID',
	'ga'    => 'IE',
	'ga_IE' => 'IE',
	'en_IE' => 'IE',
	'ar_IQ' => 'IQ',
	'fa_IR' => 'IR',
	'he_IL' => 'IL',
	'ar_IL' => 'IL',
	'it'    => 'IT',
	'it_IT' => 'IT',
	'ja'    => 'JP',
	'ja_JP' => 'JP',
	'en_JM' => 'JM',
	'ar_JO' => 'JO',
	'en_KE' => 'KE',
	'sw_KE' => 'KE',
	'ar_KW' => 'KW',
	'ru'    => 'KZ',
	'lv'    => 'LV',
	'de_LI' => 'LI',
	'lo'    => 'LA',
	'lt'    => 'LT',
	'fr_LU' => 'LU',
	'de_LU' => 'LU',
	'lb'    => 'LU',
	'zh_MO' => 'MO',
	'pt_MO' => 'MO',
	'mk'    => 'MK',
	'mg'    => 'MG',
	'fr_MG' => 'MG',
	'ms'    => 'MY',
	'ms_MY' => 'MY',
	'mt'    => 'MT',
	'en_MT' => 'MT',
	'fr_MU' => 'MU',
	'en_MU' => 'MU',
	'es_MX' => 'MX',
	'dv'    => 'MV',
	'fr_MC' => 'MC',
	'mn'    => 'MN',
	'ar_MA' => 'MA',
	'my_MM' => 'MM',
	'nl'    => 'NL',
	'nl_NL' => 'NL',
	'ne_NP' => 'NP',
	'en_NZ' => 'NZ',
	'mi_NZ' => 'NZ',
	'mi'    => 'NZ',
	'en_NG' => 'NG',
	'no'    => 'NO',
	'no_NO' => 'NO',
	'fr_NC' => 'NC',
	'ar_OM' => 'OM',
	'es_PA' => 'PA',
	'ur'    => 'PK',
	'en_PG' => 'PG',
	'es_PY' => 'PY',
	'gn'    => 'PY',
	'es_PE' => 'PE',
	'tl'    => 'PH',
	'tl_PH' => 'PH',
	'en_PH' => 'PH',
	'pl'    => 'PL',
	'pl_PL' => 'PL',
	'pt'    => 'PT',
	'pt_PT' => 'PT',
	'es_PR' => 'PR',
	'en_PR' => 'PR',
	'ar_QA' => 'QA',
	'ro'    => 'RO',
	'ro_RO' => 'RO',
	'ru'    => 'RU',
	'ar_SA' => 'SA',
	'fr_SN' => 'SN',
	'sr_RS' => 'RS',
	'ru_RU' => 'RU',
	'rw'    => 'RW',
	'fr_RW' => 'RW',
	'en_RW' => 'RW',
	'en_SG' => 'SG',
	'ms_SG' => 'SG',
	'zh_SG' => 'SG',
	'sk'    => 'SK',
	'sl'    => 'SI',
	'en_SB' => 'SB',
	'af_ZA' => 'ZA',
	'en_ZA' => 'ZA',
	'ko'    => 'KR',
	'ko_KR' => 'KR',
	'en_SS' => 'SS',
	'es'    => 'ES',
	'es_ES' => 'ES',
	'si'    => 'LK',
	'ar_SD' => 'SD',
	'en_SD' => 'SD',
	'ar_SY' => 'SY',
	'sv'    => 'SE',
	'sv_SE' => 'SE',
	'de_CH' => 'CH',
	'fr_CH' => 'CH',
	'it_CH' => 'CH',
	'rm_CH' => 'CH',
	'rm'    => 'CH',
	'zh_TW' => 'TW',
	'en_TZ' => 'TZ',
	'sw_TZ' => 'TZ',
	'th'    => 'TH',
	'th_TH' => 'TH',
	'fr_TG' => 'TG',
	'en_TT' => 'TT',
	'ar_TN' => 'TN',
	'tr'    => 'TR',
	'tr_TR' => 'TR',
	'en_UG' => 'UG',
	'sw_UG' => 'UG',
	'uk'    => 'UA',
	'ar'    => 'AE',
	'ar_AE' => 'AE',
	'en'    => 'GB',
	'en_GB' => 'GB',
	''      => 'US',
	'en_US' => 'US',
	'es_UY' => 'UY',
	'es_VE' => 'VE',
	'vi'    => 'VN',
	'vi_VN' => 'VN',
	'en_ZW' => 'ZW',
	'na'    => 'NA',
);

/**
 * Global usces_states - JP
 * 販売対象国 - JP
 * 都道府県
 *
 * @var array
 */
$usces_states['JP'] = array(
	__( '-- Select --', 'usces' ),
	'北海道',
	'青森県',
	'岩手県',
	'宮城県',
	'秋田県',
	'山形県',
	'福島県',
	'茨城県',
	'栃木県',
	'群馬県',
	'埼玉県',
	'千葉県',
	'東京都',
	'神奈川県',
	'新潟県',
	'富山県',
	'石川県',
	'福井県',
	'山梨県',
	'長野県',
	'岐阜県',
	'静岡県',
	'愛知県',
	'三重県',
	'滋賀県',
	'京都府',
	'大阪府',
	'兵庫県',
	'奈良県',
	'和歌山県',
	'鳥取県',
	'島根県',
	'岡山県',
	'広島県',
	'山口県',
	'徳島県',
	'香川県',
	'愛媛県',
	'高知県',
	'福岡県',
	'佐賀県',
	'長崎県',
	'熊本県',
	'大分県',
	'宮崎県',
	'鹿児島県',
	'沖縄県',
);

/**
 * Global usces_states - US
 * 販売対象国 - US
 * 州
 *
 * @var array
 */
$usces_states['US'] = array(
	'-- Select --',
	'Alabama',
	'Alaska',
	'Arizona',
	'Arkansas',
	'California',
	'Colorado',
	'Connecticut',
	'Delaware',
	'District of Columbia',
	'Florida',
	'Georgia',
	'Hawaii',
	'Idaho',
	'Illinois',
	'Indiana',
	'Iowa',
	'Kansas',
	'Kentucky',
	'Louisiana',
	'Maine',
	'Maryland',
	'Massachusetts',
	'Michigan',
	'Minnesota',
	'Mississippi',
	'Missouri',
	'Montana',
	'Nebraska',
	'Nevada',
	'New Hampshire',
	'New Jersey',
	'New Mexico',
	'New York',
	'North Carolina',
	'North Dakota',
	'Ohio',
	'Oklahoma',
	'Oregon',
	'Pennsylvania',
	'Rhode Island',
	'South Carolina',
	'South Dakota',
	'Tennessee',
	'Texas',
	'Utah',
	'Vermont',
	'Virginia',
	'Washington',
	'West Virginia',
	'Wisconsin',
	'Wyoming',
);

if ( ! get_option( 'usces_states' ) ) {
	update_option( 'usces_states', $usces_states );
}

/**
 * 必須マークをつける
 * key(slug) => 必須マークのタグ付きマークアップ
 *
 * @var array
 */
$usces_essential_mark = array(
	'name1'    => '<em>' . __( '*', 'usces' ) . '</em>',
	'name2'    => '',
	'name3'    => '',
	'name4'    => '',
	'zipcode'  => '<em>' . __( '*', 'usces' ) . '</em>',
	'country'  => '<em>' . __( '*', 'usces' ) . '</em>',
	'states'   => '<em>' . __( '*', 'usces' ) . '</em>',
	'address1' => '<em>' . __( '*', 'usces' ) . '</em>',
	'address2' => '<em>' . __( '*', 'usces' ) . '</em>',
	'address3' => '',
	'tel'      => '<em>' . __( '*', 'usces' ) . '</em>',
	'fax'      => '',
);

unset( $uop_init );

/**
 * Option - usces_noreceipt_status
 * 入金ステータスを利用し、入金通知により「未入金」「入金」を切り替える
 * key(slug)
 *
 * @var array
 */
$usces_noreceipt_status = get_option( 'usces_noreceipt_status', array() );
if ( empty( $usces_noreceipt_status ) ) {
	$usces_noreceipt_status = array(
		'transferAdvance',
		'transferDeferred',
		'acting_remise_conv',
		'acting_zeus_bank',
		'acting_zeus_conv',
		'acting_jpayment_conv',
		'acting_jpayment_bank',
		'acting_digitalcheck_conv',
		'acting_mizuho_conv1',
		'acting_mizuho_conv2',
		'acting_veritrans_conv',
		'acting_paygent_conv',
	);
	update_option( 'usces_noreceipt_status', $usces_noreceipt_status );
}

/**
 * Option - usces_available_settlement
 * Welcart で利用可能な決済
 * key(slug) => 決済代行会社名
 *
 * @var array
 */
$usces_available_settlement = get_option( 'usces_available_settlement', array() );
if ( empty( $usces_available_settlement ) ) {
	$usces_available_settlement = array(
		'zeus'         => __( 'ZEUS Japanese Settlement', 'usces' ),
		'remise'       => __( 'Remise Japanese Settlement', 'usces' ),
		'jpayment'     => 'ROBOT PAYMENT',
		'telecom'      => 'テレコムクレジット',
		'digitalcheck' => 'メタップスペイメント',
		'mizuho'       => 'みずほファクター',
		'anotherlane'  => 'アナザーレーン',
		'veritrans'    => 'ベリトランス Air-Web',
		'paygent'      => 'ペイジェント',
	);
	update_option( 'usces_available_settlement', $usces_available_settlement );
}
