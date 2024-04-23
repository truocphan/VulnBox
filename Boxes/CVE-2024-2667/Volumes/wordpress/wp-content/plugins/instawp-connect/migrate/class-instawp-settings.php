<?php
/**
 * Settings Class
 */


if ( ! class_exists( 'INSTAWP_Settings' ) ) {
	class INSTAWP_Settings {

		protected static $_instance = null;

		/**
		 * INSTAWP_Settings Constructor
		 */
		public function __construct() {
		}


		public static function get_migrate_settings() {
			$settings['settings'] = array(
				array(
					'id' => '',
				),
			);
		}


		/**
		 * @return INSTAWP_Settings
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
}

INSTAWP_Settings::instance();