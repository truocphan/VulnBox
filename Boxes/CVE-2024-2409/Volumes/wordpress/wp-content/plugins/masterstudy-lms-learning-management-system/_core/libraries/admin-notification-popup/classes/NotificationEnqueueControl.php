<?php

namespace ANP;

class NotificationEnqueueControl {
	public static $optionName = 'notification_enqueue';
	public static $statusNotif = 'notification_status_';

	/**
	 * $key - notification from
	 * $html - notification body
	 */
	public static function addMainItem( $key, $html ) {
		$items = self::getItems();

		self::setNotificationStatus( self::getStatusOptionName( $key ) );

		$items = array_replace_recursive( $items, array( 'main' => array( $key => $html ) ) );

		update_option( self::$optionName, $items );
	}

	/**
	 * $key - notification from
	 * $html - notification body
	 */
	public static function addSecondItem( $key, $html ) {
		$items = self::getItems();

		self::setNotificationStatus( self::getStatusOptionName( $key ) );

		$items = array_replace_recursive( $items, array( 'second' => array( $key => $html ) ) );

		update_option( self::$optionName, $items );
	}

	public static function removeItem( $priority, $key ) {
		$items = self::getItems();

		unset( $items[ $priority ][ $key ] );

		update_option( self::$optionName, $items );
	}

	public static function getItems() {
		return get_option( self::$optionName, array( 'main' => array(), 'second' => array() ) );
	}

	public static function getStatusOptionName( $key ) {
		return self::$statusNotif . $key;
	}

	public static function setNotificationStatus( $opt_name ) {
		if ( empty( get_option( $opt_name, '' ) ) || 'showed' !== get_option( $opt_name, '' ) ) {
			update_option( $opt_name, 'new' );
		}
	}

	public static function checkNotificationStatus( $key ) {
		return ( 'showed' !== get_option( self::getStatusOptionName( $key ), '' ) );
	}

	public static function getNotificationsQty() {
		global $wpdb;

		$qty = $wpdb->get_results("SELECT COUNT(option_id) as qty FROM {$wpdb->options} WHERE `option_name` LIKE '%" . self::$statusNotif . "%' AND `option_value` = 'new'");

		return ( ! is_wp_error( $qty ) ) ? $qty[0]->qty : 0;
	}

	public static function updateNotificationStatus( $key, $status = 'showed' ) {
		return update_option( self::getStatusOptionName( $key ), $status );
	}
}
