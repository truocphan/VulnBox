<?php


namespace ANP\Popup;

use ANP\NotificationEnqueueControl;
use \ANP\ThemeInfoProvider;
use \ANP\Popup\Theme\ItemUpdateTheme;

class DefaultHooks {
	public static function init() {
		add_action( 'wp_ajax_stm_anp_notice_viewed', array( self::class, 'noticeUpdateViewedStatus' ) );
		add_action( 'wp_ajax_stm_anp_notice_empty', array( self::class, 'noticeEmpty' ) );
	}

	public static function checkEnvatoPlugin() {
	    $plugin_active = is_plugin_active( 'envato-market/envato-market.php' );

		if ( ! $plugin_active || empty( envato_market()->get_option( 'token' ) ) ) {

			$btnTitle  = 'Install & Activate';
			$btnAction = admin_url( 'plugins.php' );

			if ( $plugin_active ) {
				$btnTitle  = 'Activate';
				$btnAction = admin_url( 'admin.php?page=envato-market' );
			}

			$rateItem = new \ANP\Popup\Plugin\ItemEnvato(
				'https://secure.gravatar.com/avatar/b7705e63e44c3f80245bab3823f4c506?s=32&d=mm&r=g',
				'Please set Envato API Personal Token.',
				'To get automatic theme updates please set Envato API Personal Token.',
				$btnTitle,
				$btnAction,
				'envato_install'
			);

			NotificationEnqueueControl::addMainItem( 'envato_install', $rateItem->createHtml() );
		}
	}

	public static function noticeUpdateViewedStatus() {
		check_ajax_referer( 'anp_nonce', 'security');

		$notifyKey = sanitize_text_field($_POST['item_key']);

		NotificationEnqueueControl::updateNotificationStatus($notifyKey);
	}

	public static function noticeEmpty() {
		check_ajax_referer( 'anp_nonce', 'security');
		wp_send_json( array( 'html' => NotificationPopupHtml::getEmptyItem( '' ) ) );
	}
}
