<?php

namespace ANP;

class WpOrgPluginInfo {
	private static $pluginName = '';
	private static $wpOrgApiUrl = 'https://api.wordpress.org/plugins/info/1.0/';
	private static $info = array();

	public function __construct( $pluginName ) {
		self::$pluginName = $pluginName;
		self::getPluginInfo();
	}

	private static function getPluginInfo() {
		$wp_org_info = get_transient( 'wp_org_' . self::$pluginName );

		if ( empty( $wp_org_info ) ) {
			$wp = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.0/' . self::$pluginName . '.json' );
			if(!is_wp_error( $wp )) {
				set_transient( 'wp_org_' . self::$pluginName, $wp['body'], DAY_IN_SECONDS );
				$wp_org_info = $wp['body'];
			}
		}

		if ( ( ! empty( $wp_org_info ) ) ) {
			self::$info = json_decode( $wp_org_info );
		}
	}

	public static function getRating() {
		return 100;//( is_object( self::$info ) ) ? self::$info->rating : 0;
	}

	public static function getNumRating() {
		return ( is_object( self::$info ) ) ? self::$info->num_ratings : 0;
	}
}