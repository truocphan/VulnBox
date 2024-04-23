<?php

use phpseclib3\Net\SFTP;

if ( ! defined( 'INSTAWP_PLUGIN_DIR' ) ) {
	die;
}

class InstaWP_Tools {

	public static function write_log( $message = '', $type = 'notice' ) {

		global $instawp_log;

		$instawp_log->WriteLog( $message, $type );
	}

	public static function create_user( $user_details ) {

		global $wpdb;

		foreach ( $user_details as $user_detail ) {

			if ( ! isset( $user_detail['username'] ) || ! isset( $user_detail['email'] ) || ! isset( $user_detail['password'] ) ) {
				continue;
			}

			if ( username_exists( $user_detail['username'] ) == null && email_exists( $user_detail['email'] ) == false && ! empty( $user_detail['password'] ) ) {

				// Create the new user
				$user_id = wp_create_user( $user_detail['username'], $user_detail['password'], $user_detail['email'] );

				// Get current user object
				$user = get_user_by( 'id', $user_id );

				// Remove role
				$user->remove_role( 'subscriber' );

				// Add role
				$user->add_role( 'administrator' );
			} elseif ( email_exists( $user_detail['email'] ) || username_exists( $user_detail['username'] ) ) {
				$user = get_user_by( 'email', $user_detail['email'] );

				if ( $user !== false ) {
					$wpdb->update(
						$wpdb->users,
						array(
							'user_login' => $user_detail['username'],
							'user_pass'  => md5( $user_detail['password'] ),
							'user_email' => $user_detail['email'],
						),
						array( 'ID' => $user->ID )
					);

					$user->remove_role( 'subscriber' );

					// Add role
					$user->add_role( 'administrator' );
				}
			}
		}
	}

	public static function create_instawpbackups_dir( $instawpbackups_dir = '' ) {

		if ( empty( $instawpbackups_dir ) ) {
			$instawpbackups_dir = WP_CONTENT_DIR . '/' . INSTAWP_DEFAULT_BACKUP_DIR;
		}

		if ( ! is_dir( $instawpbackups_dir ) ) {
			if ( mkdir( $instawpbackups_dir, 0777, true ) ) {
				return true;
			} else {
				return false;
			}
		}

		return false;
	}

	public static function clean_instawpbackups_dir( $instawpbackups_dir = '' ) {

		if ( empty( $instawpbackups_dir ) ) {
			$instawpbackups_dir = WP_CONTENT_DIR . '/' . INSTAWP_DEFAULT_BACKUP_DIR;
		}

		if ( ! is_dir( $instawpbackups_dir ) || ! $instawpbackups_dir_handle = opendir( $instawpbackups_dir ) ) {
			return false;
		}


		while ( false !== ( $file = readdir( $instawpbackups_dir_handle ) ) ) {
			if ( $file !== '.' && $file !== '..' ) {
				$file_path = $instawpbackups_dir . DIRECTORY_SEPARATOR . $file;
				if ( is_dir( $file_path ) ) {
					self::clean_instawpbackups_dir( $file_path );
				} else {
					unlink( $file_path );
				}
			}
		}

		closedir( $instawpbackups_dir_handle );

//      rmdir( $instawpbackups_dir );

		return true;
	}

	public static function generate_serve_file_response( $migrate_key, $api_signature, $migrate_settings = array() ) {

		// Process migration settings like active plugins/themes only etc
		$migrate_settings       = is_array( $migrate_settings ) ? $migrate_settings : array();
		$migrate_settings       = InstaWP_Tools::get_migrate_settings( array(), $migrate_settings );
		$options_data           = array(
			'api_signature'    => $api_signature,
			'migrate_settings' => $migrate_settings,
			'db_host'          => DB_HOST,
			'db_username'      => DB_USER,
			'db_password'      => DB_PASSWORD,
			'db_name'          => DB_NAME,
			'site_url'         => site_url(),
		);
		$options_data_str       = json_encode( $options_data );
		$options_data_encrypted = openssl_encrypt( $options_data_str, 'AES-128-ECB', $migrate_key );
		$options_data_filename  = INSTAWP_BACKUP_DIR . 'options-' . $migrate_key . '.txt';
		$options_data_stored    = file_put_contents( $options_data_filename, $options_data_encrypted );

		if ( ! $options_data_stored ) {
			return false;
		}

		// Delete `iwp_options`, `iwp_db_sent` and `iwp_files_sent` tables
		global $wpdb;

		$wpdb->query( "DROP TABLE IF EXISTS `iwp_db_sent`;" );
		$wpdb->query( "DROP TABLE IF EXISTS `iwp_files_sent`;" );
		$wpdb->query( "DROP TABLE IF EXISTS `iwp_options`;" );

		return array(
			'serve_url'        => INSTAWP_PLUGIN_URL . 'serve.php',
			'migrate_settings' => $migrate_settings,
		);
	}

	public static function generate_forwarded_file( $forwarded_path = ABSPATH, $file_name = 'fwd.php' ) {

		$forwarded_content      = '<?php
$path_structure = array(
    __DIR__,
    \'wp-content\',
    \'plugins\',
    \'instawp-connect\',
    \'serve.php\',
);
$file_path      = implode( DIRECTORY_SEPARATOR, $path_structure );

if ( ! is_readable( $file_path ) ) {
    header( \'x-iwp-status: false\' );
    header( \'x-iwp-message: File is not readable\' );
    exit( 2004 );
}

include $file_path;';
		$forwarded_file_path    = $forwarded_path . DIRECTORY_SEPARATOR . $file_name;
		$forwarded_file_created = file_put_contents( $forwarded_file_path, $forwarded_content );

		if ( $forwarded_file_created ) {
			return site_url( $file_name );
		}

		error_log( 'Could not create the forwarded file' );

		return false;
	}

	public static function get_tracking_database( $migrate_key ) {

		$options_data_filename = INSTAWP_BACKUP_DIR . 'options-' . $migrate_key . '.txt';

		if ( ! file_exists( $options_data_filename ) || ! is_readable( $options_data_filename ) ) {
			return false;
		}

		if ( ! class_exists( 'IWPDB' ) ) {
			require_once INSTAWP_PLUGIN_DIR . 'includes/class-instawp-iwpdb.php';
		}

		try {
			$tracking_db = new IWPDB( $migrate_key );
		} catch ( Exception $e ) {
			error_log( "Database creation error: {$e->getMessage()}" );

			return false;
		}

		return $tracking_db;
	}

	public static function generate_destination_file( $migrate_key, $api_signature ) {

		$data = array(
			'api_signature'       => $api_signature,
			'db_host'             => DB_HOST,
			'db_username'         => DB_USER,
			'db_password'         => DB_PASSWORD,
			'db_name'             => DB_NAME,
			'db_charset'          => DB_CHARSET,
			'db_collate'          => DB_COLLATE,
			'instawp_api_options' => serialize( InstaWP_Setting::get_option( 'instawp_api_options' ) ),
		);

		if ( defined( 'WP_SITEURL' ) ) {
			$data['site_url'] = WP_SITEURL;
		}

		if ( defined( 'WP_HOME' ) ) {
			$data['home_url'] = WP_HOME;
		}

		$jsonString     = json_encode( $data );
		$dest_file_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . INSTAWP_DEFAULT_BACKUP_DIR . DIRECTORY_SEPARATOR . $migrate_key . '.json';

		if ( file_put_contents( $dest_file_path, $jsonString, LOCK_EX ) ) {
			$dest_url = INSTAWP_PLUGIN_URL . 'dest.php';

			if ( ! self::is_migrate_file_accessible( $dest_url ) ) {
				$forwarded_content      = '<?php
$path_structure = array(
	__DIR__,
	\'wp-content\',
	\'plugins\',
	\'instawp-connect\',
	\'dest.php\', 
);
$file_path      = implode( DIRECTORY_SEPARATOR, $path_structure );

if ( ! is_readable( $file_path ) ) {
	header( \'x-iwp-status: false\' );
	header( \'x-iwp-message: File is not readable\' );
	exit( 2004 );
}

include $file_path;';
				$file_name              = 'dest.php';
				$forwarded_file_path    = ABSPATH . $file_name;
				$forwarded_file_created = file_put_contents( $forwarded_file_path, $forwarded_content, LOCK_EX );

				if ( $forwarded_file_created ) {
					return site_url( $file_name );
				}
			}

			return $dest_url;
		}

		return false;
	}

	public static function is_migrate_file_accessible( $file_url ) {

		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => INSTAWP_API_DOMAIN_PROD . '/public/check/?url=' . urlencode( $file_url ),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_FRESH_CONNECT  => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_POSTFIELDS     => array(),
		) );

		$response = curl_exec( $curl );

		if ( curl_errno( $curl ) ) {
			error_log( 'Curl Error: ' . curl_error( $curl ) );
		}

		$status_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		curl_close( $curl );

		return $status_code === 200;
	}

	public static function process_migration_settings( $migrate_settings = array() ) {

		$options      = InstaWP_Setting::get_args_option( 'options', $migrate_settings, array() );
		$relative_dir = str_replace( ABSPATH, '', WP_CONTENT_DIR );

		// Check if db.sql should keep or not after migration
		if ( 'on' == InstaWP_Setting::get_option( 'instawp_keep_db_sql_after_migration', 'off' ) ) {
			$migrate_settings['options'][] = 'keep_db_sql';
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Skip index.html file forcefully
		$migrate_settings['excluded_paths'][] = 'index.html';
		$migrate_settings['excluded_paths'][] = '.user.ini';

		// Skip cache folders
		$migrate_settings['excluded_paths'][] = $relative_dir . '/cache';

		// Skip htaccess from inside wp-content
		$migrate_settings['excluded_paths'][] = $relative_dir . '/.htaccess';

		// Skip wp object cache forcefully
		if ( file_exists( $relative_dir . '/mu-plugins/redis-cache-pro.php' ) ) {
			$migrate_settings['excluded_paths'][] = $relative_dir . '/mu-plugins/redis-cache-pro.php';
		}

		// Skip object-cache-iwp file if exists forcefully
		if ( file_exists( $relative_dir . '/object-cache-iwp.php' ) ) {
			$migrate_settings['excluded_paths'][] = $relative_dir . '/object-cache-iwp.php';
		}

		if ( in_array( 'active_plugins_only', $options ) ) {
			foreach ( get_plugins() as $plugin_slug => $plugin_info ) {
				if ( ! is_plugin_active( $plugin_slug ) ) {
					$migrate_settings['excluded_paths'][] = $relative_dir . '/plugins/' . strstr( $plugin_slug, '/', true );
				}
			}
		}

		if ( in_array( 'active_themes_only', $options ) ) {
			$active_theme = wp_get_theme();
			foreach ( wp_get_themes() as $theme_slug => $theme_info ) {
				if ( ! in_array( $theme_info->get_stylesheet(), array( $active_theme->get_stylesheet(), $active_theme->get_template() ), true ) ) {
					$migrate_settings['excluded_paths'][] = $relative_dir . '/themes/' . $theme_slug;
				}
			}
		}

		if ( in_array( 'skip_media_folder', $options ) ) {
			$upload_dir      = wp_upload_dir();
			$upload_base_dir = isset( $upload_dir['basedir'] ) ? $upload_dir['basedir'] : '';

			if ( ! empty( $upload_base_dir ) ) {
				$migrate_settings['excluded_paths'][] = str_replace( ABSPATH, '', $upload_base_dir );
			}
		}

		return apply_filters( 'INSTAWP_CONNECT/Filters/process_migration_settings', $migrate_settings );
	}

	public static function get_unsupported_active_plugins() {

		$active_plugins             = InstaWP_Setting::get_option( 'active_plugins', array() );
		$unsupported_plugins        = InstaWP_Setting::get_unsupported_plugins();
		$unsupported_active_plugins = array();

		foreach ( $unsupported_plugins as $plugin_data ) {
			if ( isset( $plugin_data['slug'] ) && in_array( $plugin_data['slug'], $active_plugins ) ) {
				$unsupported_active_plugins[] = $plugin_data;
			}
		}

		return $unsupported_active_plugins;
	}

	public static function get_total_sizes( $type = 'files', $migrate_settings = array() ) {

		if ( $type === 'files' ) {

			$total_size_to_skip = 0;
			$total_files        = instawp_get_dir_contents( '/' );
			$total_files_sizes  = array_map( function ( $data ) {
				return isset( $data['size'] ) ? $data['size'] : 0;
			}, $total_files );
			$total_files_size   = array_sum( $total_files_sizes );

			if ( empty( $migrate_settings ) ) {
				return $total_files_size;
			}

			if ( isset( $migrate_settings['excluded_paths'] ) && is_array( $migrate_settings['excluded_paths'] ) ) {
				foreach ( $migrate_settings['excluded_paths'] as $path ) {
					$dir_contents      = instawp_get_dir_contents( $path, false, false );
					$dir_contents_size = array_map( function ( $dir_info ) {
						return isset( $dir_info['size'] ) ? $dir_info['size'] : 0;
					}, $dir_contents );

					$total_size_to_skip += array_sum( $dir_contents_size );
				}
			}

			return $total_files_size - $total_size_to_skip;
		}

		if ( $type === 'db' ) {
			$tables       = instawp_get_database_details();
			$tables_sizes = array_map( function ( $data ) {
				return isset( $data['size'] ) ? $data['size'] : 0;
			}, $tables );

			return array_sum( $tables_sizes );
		}

		return 0;
	}

	public static function is_wp_root_available( $find_with_files = 'wp-load.php', $find_with_dir = '' ) {

		$is_find_root_dir = true;
		$searching_tier   = 10;

		if ( ! empty( $find_with_files ) ) {
			$level            = 0;
			$root_path_dir    = __DIR__;
			$root_path        = __DIR__;
			$is_find_root_dir = true;

			while ( ! file_exists( $root_path . DIRECTORY_SEPARATOR . $find_with_files ) ) {
				++ $level;

				$path_parts = explode( DIRECTORY_SEPARATOR, $root_path );
				array_pop( $path_parts ); // Remove the last directory
				$root_path = implode( DIRECTORY_SEPARATOR, $path_parts );

				if ( $level > $searching_tier ) {
					$is_find_root_dir = false;
					break;
				}
			}
		}

		if ( ! empty( $find_with_dir ) ) {
			$level            = 0;
			$root_path_dir    = __DIR__;
			$root_path        = __DIR__;
			$is_find_root_dir = true;
			while ( ! is_dir( $root_path . DIRECTORY_SEPARATOR . $find_with_dir ) ) {
				++ $level;
				$path_parts = explode( DIRECTORY_SEPARATOR, $root_path );
				array_pop( $path_parts ); // Remove the last directory
				$root_path = implode( DIRECTORY_SEPARATOR, $path_parts );

				if ( $level > $searching_tier ) {
					$is_find_root_dir = false;
					break;
				}
			}
		}

		return $is_find_root_dir;
	}

	public static function get_pull_pre_check_response( $migrate_key, $migrate_settings = array() ) {

		$is_wp_root_available = self::is_wp_root_available();

		if ( ! $is_wp_root_available ) {
			$is_wp_root_available = self::is_wp_root_available( '', 'flywheel-config' );
		}

		if ( ! $is_wp_root_available ) {
			return new WP_Error( 404, esc_html__( 'Root directory for this WordPress installation could not find.', 'instawp-connect' ) );
		}

		// Create InstaWP backup directory
		self::create_instawpbackups_dir();

		// Clean InstaWP backup directory
		self::clean_instawpbackups_dir();

		$api_signature = hash( 'sha512', $migrate_key . current_time( 'U' ) );

		// Generate serve file in instawpbackups directory

		$serve_file_response = self::generate_serve_file_response( $migrate_key, $api_signature, $migrate_settings );
		$serve_file_url      = InstaWP_Setting::get_args_option( 'serve_url', $serve_file_response );
		$migrate_settings    = InstaWP_Setting::get_args_option( 'migrate_settings', $serve_file_response );
		$tracking_db         = self::get_tracking_database( $migrate_key );

		if ( ! $tracking_db ) {
			new WP_Error( 404, esc_html__( 'Tracking database could not found.', 'instawp-connect' ) );
		}

		if (
			empty( $tracking_db->get_option( 'api_signature' ) ) ||
			empty( $tracking_db->get_option( 'db_host' ) ) ||
			empty( $tracking_db->get_option( 'db_username' ) ) ||
			//          empty( $tracking_db->get_option( 'db_password' ) ) ||
			empty( $tracking_db->get_option( 'db_name' ) )
		) {
			return new WP_Error( 404, esc_html__( 'API Signature and others data could not set properly', 'instawp-connect' ) );
		}

		// Check accessibility of serve file
		if ( empty( $serve_file_url ) || ! self::is_migrate_file_accessible( $serve_file_url ) ) {

			$serve_file_url = self::generate_forwarded_file();

			if ( empty( $serve_file_url ) ) {
				return new WP_Error( 403, esc_html__( 'Could not create the forwarded file.', 'instawp-connect' ) );
			}

			if ( ! self::is_migrate_file_accessible( $serve_file_url ) ) {
				return new WP_Error( 403, esc_html__( 'InstaWP could not access the forwarded file due to security issue.', 'instawp-connect' ) );
			}
		}

		$iwpdb_main_path = INSTAWP_PLUGIN_DIR . 'includes/class-instawp-iwpdb.php';

		if ( ! file_exists( $iwpdb_main_path ) || ! is_readable( $iwpdb_main_path ) ) {
			return new WP_Error( 403,
				sprintf( '%s <a class="underline" href="%s">%s</a>',
					esc_html__( 'InstaWP could not access or read required files from your WordPress directory due to file permission issue.', 'instawp-connect' ),
					INSTAWP_DOCS_URL_PLUGIN,
					esc_html__( 'Learn more.', 'instawp-connect' )
				)
			);
		}

		return array(
			'serve_url'        => $serve_file_url,
			'api_signature'    => $api_signature,
			'migrate_settings' => $migrate_settings,
		);
	}

	public static function get_log_tables_to_exclude( $with_prefix = true ) {

		$log_tables = array(
			'actionscheduler_logs',
		);
		$log_tables = apply_filters( 'INSTAWP_CONNECT/Filters/log_tables', $log_tables );

		if ( $with_prefix ) {
			return array_map( function ( $table_name ) {
				global $wpdb;

				return $wpdb->prefix . $table_name;
			}, $log_tables );
		}

		return $log_tables;
	}

	public static function get_migrate_settings( $posted_data = array(), $migrate_settings = array() ) {

		global $wpdb;

		if ( empty( $migrate_settings ) ) {
			$settings_str = isset( $posted_data['settings'] ) ? $posted_data['settings'] : '';

			parse_str( $settings_str, $settings_arr );

			$migrate_settings = InstaWP_Setting::get_args_option( 'migrate_settings', $settings_arr, array() );
		}

		// remove unnecessary settings
		if ( isset( $migrate_settings['screen'] ) ) {
			unset( $migrate_settings['screen'] );
		}

		// Exclude two-way-sync tables
		$excluded_tables   = InstaWP_Setting::get_args_option( 'excluded_tables', $migrate_settings, array() );
		$excluded_tables[] = INSTAWP_DB_TABLE_EVENTS;
		$excluded_tables[] = INSTAWP_DB_TABLE_SYNC_HISTORY;
		$excluded_tables[] = INSTAWP_DB_TABLE_EVENT_SITES;
		$excluded_tables[] = INSTAWP_DB_TABLE_EVENT_SYNC_LOGS;

		$migrate_settings['excluded_tables'] = $excluded_tables;

		// Remove instawp connect options
		$excluded_tables_rows = InstaWP_Setting::get_args_option( 'excluded_tables_rows', $migrate_settings, array() );

		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:instawp_api_options';
		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:instawp_connect_id_options';
		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:instawp_sync_parent_connect_data';
		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:instawp_migration_details';
		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:instawp_api_key_config_completed';
		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:instawp_is_event_syncing';
		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:_transient_instawp_staging_sites';
		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:_transient_timeout_instawp_staging_sites';
		$excluded_tables_rows["{$wpdb->prefix}options"][] = 'option_name:instawp_is_staging';

		$migrate_settings['excluded_tables_rows'] = $excluded_tables_rows;

		return self::process_migration_settings( $migrate_settings );
	}

	public static function get_admin_username() {
		$username = '';

		foreach (
			get_users( array(
				'role__in' => array( 'administrator' ),
				'fields'   => array( 'user_login' ),
			) ) as $admin
		) {
			if ( empty( $username ) && isset( $admin->user_login ) ) {
				$username = $admin->user_login;
				break;
			}
		}

		return $username;
	}

	/**
	 * Returns the random string based on length.
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	public static function get_random_string( $length = 6 ) {
		try {
			$length        = ceil( absint( $length ) / 2 );
			$bytes         = function_exists( 'random_bytes' ) ? random_bytes( $length ) : openssl_random_pseudo_bytes( $length );
			$random_string = bin2hex( $bytes );
		} catch ( Exception $e ) {
			$random_string = substr( hash( 'sha256', wp_generate_uuid4() ), 0, absint( $length ) );
		}

		return $random_string;
	}


	/**
	 * Reset permalink structure
	 *
	 * @param $hard
	 *
	 * @return void
	 */
	public static function instawp_reset_permalink( $hard = true ) {

		global $wp_rewrite;

		if ( get_option( 'permalink_structure' ) == '' ) {
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
		}

		flush_rewrite_rules( $hard );
	}


	/**
	 * Write htaccess rules
	 *
	 * @return false
	 */
	public static function write_htaccess_rule() {

		if ( is_multisite() ) {
			return false;
		}

		if ( ! function_exists( 'get_home_path' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$migration_settings = InstaWP_Setting::get_option( 'instawp_migration_settings', array() );
		$parent_domain      = InstaWP_Setting::get_args_option( 'parent_domain', $migration_settings );
		$skip_media_folder  = InstaWP_Setting::get_args_option( 'skip_media_folder', $migration_settings, false );

		if ( $skip_media_folder && ! empty( $parent_domain ) ) {

			$htaccess_file    = get_home_path() . '.htaccess';
			$htaccess_content = array(
				'## BEGIN InstaWP Connect',
				'<IfModule mod_rewrite.c>',
				'RewriteEngine On',
				'RedirectMatch 301 ^/wp-content/uploads/(.*)$ ' . $parent_domain . '/wp-content/uploads/$1',
				'</IfModule>',
				'## END InstaWP Connect',
			);
			$htaccess_content = implode( "\n", $htaccess_content );
			$htaccess_content = $htaccess_content . "\n\n\n" . file_get_contents( $htaccess_file );

			file_put_contents( $htaccess_file, $htaccess_content );
		}

		return false;
	}


	/**
	 * Auto login page HTML code.
	 */
	public static function auto_login_page( $fields, $url, $title ) {
		?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="author" content="InstaWP">
            <meta name="robots" content="noindex, nofollow">
            <meta name="googlebot" content="noindex">
            <link href="https://cdn.jsdelivr.net/npm/reset-css@5.0.1/reset.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
			<?php wp_site_icon(); ?>
            <title><?php printf( __( 'Launch %s', 'instawp-connect' ), esc_html( $title ) ); ?></title>
            <style>
                body {
                    background-color: #f3f4f6;
                    width: calc(100vw + 0px);
                    overflow-x: hidden;
                    font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", Segoe UI Symbol, "Noto Color Emoji";
                }

                .instawp-auto-login-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                }

                .instawp-logo svg {
                    width: 100%;
                }

                .instawp-details {
                    padding: 5rem;
                    border-radius: 0.5rem;
                    max-width: 42rem;
                    box-shadow: 0 0 #0000, 0 0 #0000, 0 4px 6px -1px rgb(0 0 0 / .1), 0 2px 4px -2px rgb(0 0 0 / .1);
                    background-color: rgb(255 255 255 / 1);
                    margin-top: 1.5rem;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 2.75rem;
                }

                .instawp-details-title {
                    font-weight: 600;
                    text-align: center;
                    line-height: 1.75;
                }

                .instawp-details-info {
                    text-align: center;
                    font-size: 1.125rem;
                    line-height: 1.75rem;
                    font-size: 1rem;
                }

                .instawp-details-info svg {
                    height: 1.5rem;
                    width: 1.5rem;
                    display: inline;
                    vertical-align: middle;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    100% {
                        transform: rotate(-360deg);
                    }
                }
            </style>
        </head>
        <body>
        <div class="instawp-auto-login-container">
            <div class="instawp-logo">
                <img class="instawp-logo-image" src="https://app.instawp.io/images/insta-logo-image.svg" alt="InstaWP Logo">
            </div>
            <div class="instawp-details">
                <h3 class="instawp-details-title"><?php echo esc_url( site_url() ); ?></h3>
                <p class="instawp-details-info">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-spin inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    You are being redirected to the <?php echo esc_html( $title ); ?>.
                </p>
            </div>
        </div>
		<?php echo $fields; ?>
        </body>
        </html>
		<?php
	}

	public static function get_localize_data() {
		return array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'trans'    => array(
				'create_staging_site_txt' => __( 'Please create staging sites first.', 'instawp-connect' ),
			),
			'security' => wp_create_nonce( 'instawp-connect' ),
		);
	}

	public static function cli_archive_wordpress_db() {

		$archive_dir  = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		$archive_name = 'wordpress_db_backup_' . date( 'Y-m-d_H-i-s' );
		$db_file_name = $archive_dir . $archive_name . '.sql';

		WP_CLI::runcommand( 'db export ' . $db_file_name );

		return $db_file_name;
	}

	public static function cli_archive_wordpress_files( $type = 'zip', $dirs_to_skip = array() ) {

		$archive_dir         = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		$archive_name        = 'wordpress_backup_' . date( 'Y-m-d_H-i-s' );
		$directories_to_skip = array_merge(
			$dirs_to_skip,
			array(
				'wp-content/plugins/instawp-connect/',
				'wp-content/instawpbackups/',
			)
		);

		if ( $type == 'tgz' && class_exists( 'PharData' ) ) {
			try {
				$archive_path = $archive_dir . $archive_name . '.tgz';

				instawp_zip_folder_with_phar( ABSPATH, $archive_path, $directories_to_skip );

				return $archive_path;
			} catch ( Exception $e ) {
				return new WP_Error( 'backup_failed', $e->getMessage() );
			}
		}

		if ( $type == 'zip' && class_exists( 'ZipArchive' ) ) {

			$archive_path = $archive_dir . $archive_name . '.zip';
			$zip          = new ZipArchive();

			if ( $zip->open( $archive_path, ZipArchive::CREATE ) !== true ) {
				return new WP_Error( 'zip_is_not_opening', esc_html__( 'Zip archive is not opening.', 'instawp-connect' ) );
			}

			$skip_folders     = array(
				'wp-content' . DIRECTORY_SEPARATOR . 'instawpbackups',
				'wp-content' . DIRECTORY_SEPARATOR . 'upgrade',
				'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'instawp-connect',
				'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'instawp-helper',
				'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'iwp-migration',
			);
			$filter_directory = function ( SplFileInfo $file, $key, RecursiveDirectoryIterator $iterator ) use ( $skip_folders ) {

				$relative_path = ! empty( $iterator->getSubPath() ) ? $iterator->getSubPath() . DIRECTORY_SEPARATOR . $file->getBasename() : $file->getBasename();

				if ( in_array( $relative_path, $skip_folders ) ) {
					return false;
				}

				return ! in_array( $iterator->getSubPath(), $skip_folders );
			};
			$directory        = new RecursiveDirectoryIterator( ABSPATH, RecursiveDirectoryIterator::SKIP_DOTS | RecursiveDirectoryIterator::FOLLOW_SYMLINKS );
			$iterator         = new RecursiveIteratorIterator( new RecursiveCallbackFilterIterator( $directory, $filter_directory ), RecursiveIteratorIterator::LEAVES_ONLY, RecursiveIteratorIterator::CATCH_GET_CHILD );

			try {
				$limitedIterator = new LimitIterator( $iterator );
			} catch ( Exception $e ) {
				return new WP_Error( 'limited_worker_is_not_working', $e->getMessage() );
			}

			foreach ( $limitedIterator as $file ) {
				if ( ! $file->isDir() ) {
					$filePath     = $file->getRealPath();
					$relativePath = str_replace( ABSPATH, '', str_replace( '\\', '/', $filePath ) );

					$zip->addFile( $filePath, $relativePath );
				}
			}

			$zip->close();

			return $archive_path;
		}

		return new WP_Error( 'no_method_find', esc_html__( 'No compression method find.' ) );
	}

	public static function create_insta_site() {

		// Creating new blank site
		$sites_args        = array(
			'wp_version'  => '6.3',
			'php_version' => '7.4',
			'is_reserved' => false,
		);
		$sites_res         = InstaWP_Curl::do_curl( 'sites', $sites_args );
		$sites_res_status  = (bool) InstaWP_Setting::get_args_option( 'success', $sites_res, true );
		$sites_res_message = InstaWP_Setting::get_args_option( 'message', $sites_res, true );

		if ( ! $sites_res_status ) {
			return new WP_Error( 'could_not_create_site', $sites_res_message );
		}

		$sites_res_data = InstaWP_Setting::get_args_option( 'data', $sites_res, array() );
		$sites_task_id  = InstaWP_Setting::get_args_option( 'task_id', $sites_res_data );
		$site_id        = InstaWP_Setting::get_args_option( 'id', $sites_res_data );

		if ( ! empty( $sites_task_id ) ) {
			while ( true ) {
				$status_res = InstaWP_Curl::do_curl( "tasks/{$sites_task_id}/status", array(), array(), false );

				if ( ! InstaWP_Setting::get_args_option( 'success', $status_res, true ) ) {
					continue;
				}

				$status_res_data = InstaWP_Setting::get_args_option( 'data', $status_res, array() );
				$restore_status  = InstaWP_Setting::get_args_option( 'status', $status_res_data, 0 );

				if ( $restore_status == 'completed' ) {
					break;
				}

				sleep( 5 );
			}
		}

		if ( empty( $site_id ) ) {
			return new WP_Error( 'site_id_not_found', esc_html__( 'Site ID not found in site create response.', 'instawp-connect' ) );
		}

		return (array) $sites_res_data;
	}

	public static function cli_upload_using_sftp( $site_id, $file_path, $db_path ) {

		// Enabling SFTP
		$sftp_enable_res = InstaWP_Curl::do_curl( "sites/{$site_id}/update-sftp-status", array( 'status' => 1 ) );

		if ( ! InstaWP_Setting::get_args_option( 'success', $sftp_enable_res, true ) ) {
			return new WP_Error( 'sftp_enable_failed', InstaWP_Setting::get_args_option( 'message', $sftp_enable_res ) );
		}

		WP_CLI::success( 'SFTP enabled for the website.' );


		// Getting SFTP details of $site_id
		$sftp_details_res = InstaWP_Curl::do_curl( "sites/{$site_id}/sftp-details", array(), array(), false );

		if ( ! InstaWP_Setting::get_args_option( 'success', $sftp_details_res, true ) ) {
			return new WP_Error( 'sftp_enable_failed', InstaWP_Setting::get_args_option( 'message', $sftp_details_res ) );
		}

		WP_CLI::success( 'SFTP details fetched successfully.' );

		$sftp_details_res_data = InstaWP_Setting::get_args_option( 'data', $sftp_details_res, array() );
		$sftp_host             = InstaWP_Setting::get_args_option( 'host', $sftp_details_res_data );
		$sftp_username         = InstaWP_Setting::get_args_option( 'username', $sftp_details_res_data );
		$sftp_password         = InstaWP_Setting::get_args_option( 'password', $sftp_details_res_data );
		$sftp_port             = InstaWP_Setting::get_args_option( 'port', $sftp_details_res_data );

		// Connecting to SFTP
		$sftp = new SFTP( $sftp_host, $sftp_port );

		try {
			if ( ! $sftp->login( $sftp_username, $sftp_password ) ) {
				return new WP_Error( 'sftp_login_failed', esc_html__( 'SFTP login failed.', 'instawp-connect' ) );
			}
		} catch ( Exception $e ) {
			return new WP_Error( 'sftp_login_failed', $e->getMessage() );
		}

		WP_CLI::success( 'SFTP login successful to the server.' );

		$sftp_file_upload_status = $sftp->put( "web/$sftp_host/public_html/" . basename( $file_path ), $file_path, SFTP::SOURCE_LOCAL_FILE );

		if ( ! $sftp_file_upload_status ) {
			return new WP_Error( 'sftp_file_upload_failed', esc_html__( 'SFTP upload failed for files.', 'instawp-connect' ) );
		}

		WP_CLI::success( 'File uploaded successfully using SFTP.' );

		$sftp_db_upload_status = $sftp->put( "web/$sftp_host/public_html/" . basename( $db_path ), $db_path, SFTP::SOURCE_LOCAL_FILE );

		if ( ! $sftp_db_upload_status ) {
			return new WP_Error( 'sftp_db_upload_failed', esc_html__( 'SFTP upload failed for database.', 'instawp-connect' ) );
		}

		WP_CLI::success( 'Database uploaded successfully using SFTP.' );

		return true;
	}

	public static function cli_restore_website( $site_id, $file_path, $db_path ) {

		$restore_args = array(
			'file_bkp'      => basename( $file_path ),
			'db_bkp'        => basename( $db_path ),
			'source_domain' => str_replace( array( 'https://', 'http://' ), '', site_url() ),
		);
		$restore_res  = InstaWP_Curl::do_curl( "sites/{$site_id}/restore-raw", $restore_args, array(), 'put' );

		if ( ! InstaWP_Setting::get_args_option( 'success', $restore_res, true ) ) {
			return new WP_Error( 'restore_raw_api_failed', InstaWP_Setting::get_args_option( 'message', $restore_res, true ) );
		}

		$restore_res_data = InstaWP_Setting::get_args_option( 'data', $restore_res, array() );
		$restore_task_id  = InstaWP_Setting::get_args_option( 'task_id', $restore_res_data );

		WP_CLI::success( 'Restore initiated. Task id: ' . $restore_task_id );

		while ( true ) {

			$status_res = InstaWP_Curl::do_curl( "tasks/{$restore_task_id}/status", array(), array(), false );

			if ( ! InstaWP_Setting::get_args_option( 'success', $status_res, true ) ) {
				continue;
			}

			$status_res_data     = InstaWP_Setting::get_args_option( 'data', $status_res, array() );
			$percentage_complete = InstaWP_Setting::get_args_option( 'percentage_complete', $status_res_data, 0 );
			$restore_status      = InstaWP_Setting::get_args_option( 'status', $status_res_data );

			error_log( "local_push_migration_progress: {$percentage_complete}" );

			if ( $restore_status === 'completed' ) {
				break;
			}

			sleep( 5 );
		}

		return true;
	}
}