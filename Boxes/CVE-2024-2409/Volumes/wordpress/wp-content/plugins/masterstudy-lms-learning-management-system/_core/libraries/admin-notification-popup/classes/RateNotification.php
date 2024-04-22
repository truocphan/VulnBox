<?php

use ANP\Popup\Plugin\ItemRate;
use ANP\NotificationEnqueueControl;

class RateNotification {

	public static $step         = 0;
	public static $first_period = 3;
	public static $conditions   = array(
		'sure'    => array(),
		'later'   => array( 3, 3, 14 ),
		'decline' => array( 14 ),
		'discard' => array( 50000 ),
	);
	public static $plugin_data  = array();
	public static $plugin_name  = '';

	public static function init( $plugin_data ) {

		if ( ! isset( $plugin_data['plugin_name'] ) || ! isset( $plugin_data['plugin_file'] ) ) {
			return;
		}
		self::$plugin_data[ $plugin_data['plugin_name'] ] = self::init_data( $plugin_data );
		self::$plugin_name                                = $plugin_data['plugin_name'];

		register_activation_hook( $plugin_data['plugin_file'], array( self::class, 'plugin_activation_hook' ) );

		add_action( 'wp_ajax_stm_ajax_admin_notice', array( self::class, 'ajax_admin_notice' ) );
		add_action( 'updated_stm_admin_notice_transient', array( self::class, 'update_transient_data' ), 10, 2 );
		add_action( 'anp_popup_items', array( self::class, 'stm_add_popup_notification_item' ), 10, 1 );
	}

	public static function init_data( $plugin_data ) {

		$plugin_data['title']       = '';
		$plugin_data['content']     = '';
		$plugin_data['logo']        = ! empty( $plugin_data['logo'] ) ? $plugin_data['logo'] : '';
		$plugin_data['submit_link'] = 'https://wordpress.org/support/plugin/' . $plugin_data['plugin_name'] . '/reviews/?filter=5#new-post';
		$plugin_data['plugin_url']  = plugin_dir_url( $plugin_data['plugin_file'] );

		return $plugin_data;
	}

	public static function plugin_activation_hook() {
		$transient_name = self::get_transient_name();

		if ( empty( get_transient( $transient_name ) ) ) {
			self::update_transient_data( $transient_name, self::$first_period, self::$step );
		}
	}

	public static function get_transient_name( $plugin_name = '', $event = '' ) {

		$plugin_name = ! empty( $plugin_name ) ? $plugin_name : self::$plugin_name;
		$event       = ! empty( $event ) ? '_' . $event : '';

		return 'stm_' . $plugin_name . $event . '_notice_setting';
	}

	public static function update_transient_data( $transient_name, $period, $step = 0, $action = '' ) {
		if ( $period > 0 ) {
			$show_time = DAY_IN_SECONDS * $period + time();
			set_transient(
				$transient_name,
				array(
					'show_time'   => $show_time,
					'step'        => $step,
					'prev_action' => $action,
				)
			);
		} else {
			delete_transient( $transient_name );
		}
	}

	public static function stm_add_popup_notification_item() {
		foreach ( self::$plugin_data as $k => $plugin ) {
			$plugin_name = $plugin['plugin_name'];
			$notice      = get_transient( self::get_transient_name( $plugin_name ) );

			if ( ! empty( $notice ) && $notice['show_time'] <= time() ) {

				$skipKey = sprintf( '%s_rate', str_replace( '-', '_', $plugin['plugin_name'] ) );

				$rateItem = new ItemRate(
					$plugin['plugin_name'],
					$plugin['logo'],
					'Thank you for using ' . $plugin['plugin_title'] . '!',
					'Please rate us!',
					$plugin['submit_link'],
					$skipKey
				);

				NotificationEnqueueControl::addSecondItem( $skipKey, $rateItem->createHtml() );
			}

			$notice_single = get_transient( self::get_transient_name( $plugin_name, 'single' ) );

			if ( ! empty( $notice_single ) ) {

				$data_single = apply_filters( 'stm_admin_notice_rate_' . $plugin_name . '_single', array() );
				$plugin      = array_merge( $plugin, $data_single );

				$skipKey = sprintf( '%s_rate_single', str_replace( '-', '_', $plugin['plugin_name'] ) );

				$rateItem = new ItemRate(
					$plugin['plugin_name'],
					$plugin['logo'],
					$plugin['title'],
					$plugin['content'],
					$plugin['submit_link'],
					$skipKey,
					'single'
				);

				NotificationEnqueueControl::addSecondItem( $skipKey, $rateItem->createHtml() );
			}
		}
	}

	public static function ajax_admin_notice() {

		if ( isset( $_POST['type'] ) && isset( $_POST['pluginName'] ) && isset( $_POST['pluginEvent'] ) ) {
			$type         = sanitize_text_field( $_POST['type'] );
			$key_btn      = sanitize_text_field( $_POST['key_btn'] );
			$plugin_name  = sanitize_text_field( $_POST['pluginName'] );
			$plugin_event = sanitize_text_field( $_POST['pluginEvent'] );

			$transient_name = self::get_transient_name( $plugin_name, $plugin_event );

			$notice = get_transient( $transient_name );

			if ( $plugin_event !== 'single' && ! isset( self::$conditions[ $type ] ) ) {
				return;
			}

			$step = $notice['step'] ?? 0;

			if ( ( $plugin_event === 'single' ) ||
				 ( ! empty( $notice['prev_action'] ) && ! isset( self::$conditions[ $notice['prev_action'] ][ $step ] ) )
			) {
				$period = 0;
			} else {
				ANP\NotificationEnqueueControl::updateNotificationStatus( $key_btn, '' );
				$period = isset( self::$conditions[ $type ][ $step ] ) ? self::$conditions[ $type ][ $step ] : 0;
			}

			$step ++;

			self::update_transient_data( $transient_name, $period, $step, $type );

		}

	}

}
