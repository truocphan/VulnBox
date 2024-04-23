<?php
namespace InstaWP\Connect\Helpers;

use Exception;

class FileManager {

	public $file;
	public static $query_var = 'instawp-file-manager';
	public static $action = 'instawp_clean_file_manager';

    public function get() {
		$this->clean();

		$username  = Helper::get_random_string( 15 );
		$password  = Helper::get_random_string( 20 );
		$file_name = Helper::get_random_string( 20 );
		$token     = md5( $username . '|' . $password . '|' . $file_name );
		$url       = 'https://raw.githubusercontent.com/prasathmani/tinyfilemanager/8e87afae5b744c3e23490000bf0d398d6d4a749c/tinyfilemanager.php';

		$search  = [
			'Tiny File Manager',
			'CCP Programmers',
			'tinyfilemanager.github.io',
			'class="fm-login-page',
			'name="fm_usr"',
			'name="fm_pwd"',
			'</body>',
			"'translation.json'",
			'</style>',
			"'admin'",
			'$2y$10$/K.hjNr84lLNDt8fTXjoI.DBp6PpeyoJ.mGwrrLuCZfAwfSAGqhOW'
		];
		$replace = [
			'InstaWP File Manager',
			'InstaWP',
			'instawp.com',
			'class="fm-login-page<?php if ( isset( $_GET["autologin"]) ) { echo \' instawp-autologin\'; } ?>',
			'name="fm_usr" value="<?php if ( isset( $_GET["autologin"]) ) { echo "' . $username . '"; } ?>"',
			'name="fm_pwd" value="<?php if ( isset( $_GET["autologin"]) ) { echo "' . $password . '"; } ?>"',
			'<?php if ( isset( $_GET["autologin"]) ) { echo \'<script type="text/javascript">window.onload = function() {document.getElementsByClassName("form-signin")[0].submit();}</script>\'; } ?></body>',
			"__DIR__ . '/translation.json'",
			'<?php if ( file_exists( __DIR__ . "/custom.css" ) ) { echo file_get_contents( __DIR__ . "/custom.css" ); } ?></style>',
			"'$username'",
			password_hash( $password, PASSWORD_DEFAULT )
		];

		$file = file_get_contents( $url );
		$file = str_replace( $search, $replace, $file );
		$file = preg_replace( '!/\*.*?\*/!s', '', $file );

		$file_path        = self::get_file_path( $file_name );
		$file_manager_url = self::get_file_manager_url( $file_name );

		try {
			$result = file_put_contents( $file_path, $file, LOCK_EX );
			if ( false === $result ) {
				throw new Exception( esc_html( 'Failed to create the file manager file.' ) );
			}

			$file       = file( $file_path );
			$new_line   = "if ( ! defined( 'INSTAWP_PLUGIN_DIR' ) ) { die; }\ndefine('FM_SELF_URL', '$file_manager_url');\ndefine('FM_SESSION_ID', 'instawp_file_manager');";
			$first_line = array_shift( $file );
			array_unshift( $file, $new_line );
			array_unshift( $file, $first_line );

			$fp = fopen( $file_path, 'w' );
			fwrite( $fp, implode( '', $file ) );
			fclose( $fp );

			set_transient( 'instawp_file_manager_login_token', $token, ( 5 * MINUTE_IN_SECONDS ) );
			wp_schedule_single_event( time() + HOUR_IN_SECONDS, self::$action );
			flush_rewrite_rules();

			$results = [
				'login_url' => add_query_arg( [
					'action'   => 'instawp-file-manager-auto-login',
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
		return INSTAWP_PLUGIN_DIR . '/includes/file-manager/';
	}

	public static function get_file_path( $file_name ) {
		return self::get_directory() . 'instawp' . $file_name . '.php';
	}

	public static function get_file_manager_url( $file_name ) {
		return home_url( self::$query_var . '/' . $file_name );
	}
}