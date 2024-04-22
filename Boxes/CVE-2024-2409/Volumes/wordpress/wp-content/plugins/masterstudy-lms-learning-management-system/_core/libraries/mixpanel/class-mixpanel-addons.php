<?php

namespace MasterStudy\Lms\Libraries;

use MasterStudy\Lms\Plugin\Addons;

class Mixpanel_Addons extends Mixpanel {

	public static function register_data() {
		if ( defined( 'STM_LMS_PRO_PATH' ) ) {
			$all_addons = Addons::list();

			foreach ( $all_addons as $slug => $addon ) {
				self::add_data( $slug, is_ms_lms_addon_enabled( $slug ) );
			}

			if ( is_ms_lms_addon_enabled( 'sequential_drip_content' ) ) {
				self::add_data( 'Lock Lessons Sequentially Enabled', self::get_lock_sequentially_settings( 'locked' ) );
				self::add_data( 'Lock Lesson Before it Starts Enabled', self::get_lock_sequentially_settings( 'lock_before_start' ) );
			}
			if ( is_ms_lms_addon_enabled( 'email_manager' ) ) {
				if ( defined( 'STM_LMS_PLUS_ENABLED' ) ) {
					self::add_data( 'Email Branding Used', self::get_email_manager_usage( true ) );
				}
				self::add_data( 'Email Manager Used', self::get_email_manager_usage() );
			}
		}
	}

	public static function get_lock_sequentially_settings( $slug ) {
		return true === get_option( 'stm_lms_sequential_drip_content_settings' )[ $slug ];
	}

	public static function get_email_manager_usage( $email_branding = false ) {
		$email_settings = get_option( 'stm_lms_email_manager_settings', array() );

		if ( true === $email_branding ) {
			return ! empty( $email_settings['stm_lms_email_template_branding'] );
		} else {
			return ! empty( $email_settings );
		}
	}
}
