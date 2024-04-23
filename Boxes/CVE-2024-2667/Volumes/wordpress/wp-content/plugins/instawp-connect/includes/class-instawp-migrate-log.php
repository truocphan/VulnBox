<?php

if ( ! defined( 'INSTAWP_PLUGIN_DIR' ) ) {
	die;
}

class InstaWP_Migrate_Log {
	public static $log_file;
	public static $log_file_handle;

	public static function write( $migrate_id, $log = '' ) {
		self::$log_file        = self::get_path() . '/migrate-' . $migrate_id . '.txt';
		self::$log_file_handle = self::create();

		if ( self::$log_file_handle ) {
			$offset = get_option( 'gmt_offset' );
			$time   = date( "Y-m-d H:i:s", time() + $offset * 60 * 60 );
			$text   = '[' . $time . '] ' . $log . "\n";
			fwrite( self::$log_file_handle, $text );
		}
	}

	public static function create() {
		$log_file_handle = fopen( self::$log_file, 'a' );

		if ( filesize( self::$log_file ) == 0 && $log_file_handle ) {
			$offset = get_option( 'gmt_offset' );
			$time   = date( "Y-m-d H:i:s", time() + $offset * 60 * 60 );
			$text   = 'Log created: ' . $time . "\n";
			$text   .= '--------------------' . "\n";

			fwrite( $log_file_handle, $text );
		}

		return $log_file_handle;
	}

	public static function get_path() {
		$path = WP_CONTENT_DIR . '/instawpbackups/migration-log';

		if ( ! is_dir( $path ) ) {
			@mkdir( $path, 0777, true );
			@fopen( $path . '/index.html', 'x' );
			$tempfile = @fopen( $path . '/.htaccess', 'x' );
			if ( $tempfile ) {
				$text = "deny from all";
				fwrite( $tempfile, $text );
			}
		}

		return $path;
	}
}