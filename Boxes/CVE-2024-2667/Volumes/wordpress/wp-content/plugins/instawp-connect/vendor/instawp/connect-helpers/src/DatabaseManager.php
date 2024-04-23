<?php
namespace InstaWP\Connect\Helpers;

use Exception;

class DatabaseManager {

	public $file;
	public static $query_var = 'instawp-database-manager';
	public static $action = 'instawp_clean_database_manager';

    public function get() {
		$this->clean();

		$file_name = Helper::get_random_string( 20 );
		$token     = md5( $file_name );
		$url       = 'https://github.com/adminerevo/adminerevo/releases/download/v4.8.3/adminer-4.8.3.php';

		$search  = [
			'/\bjs_escape\b/',
			'/\bget_temp_dir\b/',
			'/\bis_ajax\b/',
			'/\bsid\b/',
		];
		$replace = [
			'instawp_js_escape',
			'instawp_get_temp_dir',
			'instawp_is_ajax',
			'instawp_sid',
		];

		$file = file_get_contents( $url );
		$file = preg_replace( $search, $replace, $file );

		$file_path            = self::get_file_path( $file_name );
		$database_manager_url = self::get_database_manager_url( $file_name );

		try {
			$result = file_put_contents( $file_path, $file, LOCK_EX );
			if ( false === $result ) {
				throw new Exception( esc_html( 'Failed to create the database manager file.' ) );
			}

			$file       = file( $file_path );
			$new_line   = "if ( ! defined( 'INSTAWP_PLUGIN_DIR' ) ) { die; }";
			$first_line = array_shift( $file );
			array_unshift( $file, $new_line );
			array_unshift( $file, $first_line );

			$fp = fopen( $file_path, 'w' );
			fwrite( $fp, implode( '', $file ) );
			fclose( $fp );

			set_transient( 'instawp_database_manager_login_token', $token, ( 5 * MINUTE_IN_SECONDS ) );
			wp_schedule_single_event( time() + HOUR_IN_SECONDS, self::$action );
			flush_rewrite_rules();

			$results = [
				'login_url' => add_query_arg( [
					'action'   => 'instawp-database-manager-auto-login',
					'token'    => hash( 'sha256', $token ),
					'template' => base64_encode( $file_name ),
				], admin_url( 'admin-post.php' ) ),
			];
		} catch ( Exception $e ) {
			$results = [
				'success' => false,
				'message' => $e->getMessage(),
			];
		}
		
        return $results;
    }

	public function clean() {
		Helper::clean_file( self::get_directory() );

		flush_rewrite_rules();
		wp_clear_scheduled_hook( self::$action );
	}

	public static function get_directory() {
		return INSTAWP_PLUGIN_DIR . '/includes/database-manager/';
	}

	public static function get_file_path( $file_name ) {
		return self::get_directory() . 'instawp' . $file_name . '.php';
	}

	public static function get_database_manager_url( $file_name ) {
		return home_url( self::$query_var . '/' . $file_name );
	}
}