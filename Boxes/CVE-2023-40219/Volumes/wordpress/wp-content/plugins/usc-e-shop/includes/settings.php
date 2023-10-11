<?php
/**
 * Including files.
 *
 * @package  Welcart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once USCES_PLUGIN_DIR . 'classes/utilities.class.php';
require_once USCES_PLUGIN_DIR . 'functions/filters.php';
require_once USCES_PLUGIN_DIR . 'functions/redirect.php';
require_once USCES_PLUGIN_DIR . 'includes/initial.php';
require_once USCES_PLUGIN_DIR . 'functions/define_function.php';
require_once USCES_PLUGIN_DIR . 'functions/calendar-com.php';
require_once USCES_PLUGIN_DIR . 'functions/utility.php';
require_once USCES_PLUGIN_DIR . 'includes/product/wel-item-class.php';
require_once USCES_PLUGIN_DIR . 'includes/product/wel-item-functions.php';
require_once USCES_PLUGIN_DIR . 'includes/member/wel-member-class.php';
require_once USCES_PLUGIN_DIR . 'includes/member/wel-member-functions.php';
require_once USCES_PLUGIN_DIR . 'includes/order/wel-order-class.php';
require_once USCES_PLUGIN_DIR . 'includes/order/wel-order-functions.php';
require_once USCES_PLUGIN_DIR . 'functions/datalist.php';
require_once USCES_PLUGIN_DIR . 'functions/item_post.php';
require_once USCES_PLUGIN_DIR . 'functions/emails.php';
require_once USCES_PLUGIN_DIR . 'functions/function.php';
require_once USCES_PLUGIN_DIR . 'functions/shortcode.php';
require_once USCES_PLUGIN_DIR . 'classes/usceshop.class.php';
require_once USCES_PLUGIN_DIR . 'functions/hoock_func.php';
// require_once USCES_PLUGIN_DIR . 'classes/httpRequest.class.php';
require_once USCES_PLUGIN_DIR . 'functions/admin_func.php';
require_once USCES_PLUGIN_DIR . 'functions/system_post.php';
if ( is_admin() ) {
	require_once USCES_PLUGIN_DIR . 'functions/admin_page.php';
	require_once USCES_PLUGIN_DIR . 'includes/update_check.php';
}
require_once USCES_PLUGIN_DIR . 'functions/settlement_func.php';
// require_once USCES_PLUGIN_DIR . 'classes/PaymentYahooWallet.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentEpsilon.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentESCOTT.main.class.php';
require_once USCES_PLUGIN_DIR . 'classes/PaymentESCOTT.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentWelcart.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentZeus.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentRemise.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentSBPS.main.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentSBPS.class.php';
require_once USCES_PLUGIN_DIR . 'classes/PaymentDSK.class.php';
// require_once USCES_PLUGIN_DIR . 'classes/paymentPayPalEC.class.php';
// require_once USCES_PLUGIN_DIR . 'classes/paymentPayPalWPP.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentJPayment.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentTelecom.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentDigitalcheck.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentMizuho.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentAnotherlane.class.php';
// require_once USCES_PLUGIN_DIR . 'classes/paymentVeritrans.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentPaygent.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentPayPalCP.class.php';
require_once USCES_PLUGIN_DIR . 'classes/paymentPaidy.class.php';
require_once USCES_PLUGIN_DIR . 'classes/settlement.class.php';
require_once USCES_PLUGIN_DIR . 'classes/tax.class.php';
require_once USCES_PLUGIN_DIR . 'classes/logger.class.php';
require_once USCES_PLUGIN_DIR . 'classes/loglist.class.php';

require USCES_PLUGIN_DIR . 'includes/database/db-update.php';
