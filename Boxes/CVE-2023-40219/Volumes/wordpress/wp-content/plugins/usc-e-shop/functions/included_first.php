<?php
/**
 * Functions that should be included first
 *
 * @package Welcart
 */

/**
 * Locale
 *
 * @param string $locale Locale.
 * @return string
 */
function usces_filter_locale( $locale ) {
	$mode = array(
		'completionMail',
		'orderConfirmMail',
		'changeConfirmMail',
		'receiptConfirmMail',
		'mitumoriConfirmMail',
		'cancelConfirmMail',
		'otherConfirmMail',
	);
	if ( ! is_admin()
		|| ( isset( $_POST['action'] ) && isset( $_POST['mode'] ) && 'order_item_ajax' == $_POST['action'] && in_array( $_POST['mode'], $mode ) )
		|| ( isset( $_REQUEST['order_action'] ) && 'pdfout' == $_REQUEST['order_action'] )
	) {
		$usces_options = get_option( 'usces' );
		if ( isset( $usces_options['system']['front_lang'] ) && ! empty( $usces_options['system']['front_lang'] ) ) {
			$locale = $usces_options['system']['front_lang'];
		}
	}
	return $locale;
}

/**
 * Dual textdomain
 */
function usces_dual_textdomain() {
	$locale        = get_locale();
	$usces_options = get_option( 'usces' );
	if ( isset( $usces_options['system']['front_lang'] ) && ! empty( $usces_options['system']['front_lang'] ) ) {
		$locale = $usces_options['system']['front_lang'];
	}
	if ( file_exists( USCES_PLUGIN_DIR . '/languages/usces-' . $locale . '.mo' ) ) {
		load_textdomain( 'usces_dual', USCES_PLUGIN_DIR . '/languages/usces-' . $locale . '.mo' );
	}
}
add_action( 'usces_construct', 'usces_dual_textdomain' );
