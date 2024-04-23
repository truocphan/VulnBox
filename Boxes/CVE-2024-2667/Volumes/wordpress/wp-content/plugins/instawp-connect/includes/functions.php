<?php
/**
 * All helper functions here
 */


if ( ! function_exists( 'instawp_create_db_tables' ) ) {
	/**
	 * @return void
	 */
	function instawp_create_db_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		if ( ! function_exists( 'maybe_create_table' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		$sql_create_events_table = "CREATE TABLE " . INSTAWP_DB_TABLE_EVENTS . " (
			id int(20) NOT NULL AUTO_INCREMENT,
			event_hash varchar(50) NOT NULL,
			event_name varchar(128) NOT NULL,
			event_slug varchar(128) NOT NULL,
			event_type varchar(128) NOT NULL,
			source_id varchar(30) NOT NULL,
			title text NOT NULL,
			details longtext NOT NULL,
			user_id int(20) NOT NULL,
			date datetime NOT NULL,
			prod varchar(128) NOT NULL,
			status varchar(50) NOT NULL DEFAULT 'pending',
			synced_message varchar(128),
			PRIMARY KEY (id)
        ) $charset_collate;";

		maybe_create_table( INSTAWP_DB_TABLE_EVENTS, $sql_create_events_table );

		$sql_create_sync_history_table = "CREATE TABLE " . INSTAWP_DB_TABLE_EVENT_SITES . " (
            id int(20) NOT NULL AUTO_INCREMENT,
            event_id int(20) NOT NULL,
            event_hash varchar(50) NOT NULL,
            connect_id int(20) NOT NULL,
			status varchar(50) NOT NULL DEFAULT 'pending',
			synced_message text NULL,
            date datetime NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

		maybe_create_table( INSTAWP_DB_TABLE_EVENT_SITES, $sql_create_sync_history_table );

		$sql_create_event_sites_table = "CREATE TABLE " . INSTAWP_DB_TABLE_SYNC_HISTORY . " (
            id int(20) NOT NULL AUTO_INCREMENT,
            encrypted_contents longtext NOT NULL,
            changes longtext NOT NULL,
            sync_response longtext NOT NULL,
            direction varchar(128) NOT NULL,
            status varchar(128) NOT NULL DEFAULT 'pending',
            user_id int(20) NOT NULL,
            changes_sync_id int(20) NOT NULL,
            sync_message varchar(128) NOT NULL,
            source_connect_id int(20) NOT NULL,
            source_url varchar(128),
            date datetime NOT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

		maybe_create_table( INSTAWP_DB_TABLE_SYNC_HISTORY, $sql_create_event_sites_table );

		$sql_create_event_sync_log_table = "CREATE TABLE " . INSTAWP_DB_TABLE_EVENT_SYNC_LOGS . " (
			id int(20) NOT NULL AUTO_INCREMENT,
			event_id int(20) NOT NULL,
			event_hash varchar(50) NOT NULL,
			source_url varchar(128) NOT NULL,
			data longtext NOT NULL,
			status varchar(50) NOT NULL DEFAULT 'pending',
			logs text NOT NULL,
			date datetime NOT NULL,
			PRIMARY KEY (id)
        ) $charset_collate;";

		maybe_create_table( INSTAWP_DB_TABLE_EVENT_SYNC_LOGS, $sql_create_event_sync_log_table );

		instawp_alter_db_tables();
	}
}


if ( ! function_exists( 'instawp_alter_db_tables' ) ) {
	function instawp_alter_db_tables() {
		global $wpdb;

		foreach ( array( INSTAWP_DB_TABLE_EVENTS, INSTAWP_DB_TABLE_EVENTS, INSTAWP_DB_TABLE_SYNC_HISTORY ) as $table_name ) {
			$has_col = $wpdb->get_results(
				$wpdb->prepare( "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `table_name`=%s AND `TABLE_SCHEMA`=%s AND `COLUMN_NAME`=%s", $table_name, $wpdb->dbname, 'event_hash' )
			);

			if ( empty( $has_col ) ) {
				$wpdb->query( "ALTER TABLE " . $table_name . " ADD `event_hash` varchar(50) NOT NULL AFTER `id`" );
			}
		}

		$table_name = INSTAWP_DB_TABLE_EVENT_SYNC_LOGS;
		$has_col    = $wpdb->get_results(
			$wpdb->prepare( "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `table_name`=%s AND `TABLE_SCHEMA`=%s AND `COLUMN_NAME`=%s", $table_name, $wpdb->dbname, 'status' )
		);

		if ( empty( $has_col ) ) {
			$wpdb->query( "ALTER TABLE " . $table_name . " ADD `status` varchar(50) NOT NULL DEFAULT 'pending' AFTER `data`" );
		}
	}
}


if ( ! function_exists( 'instawp' ) ) {
	/**
	 * @return instaWP
	 */
	function instawp() {
		global $instawp;

		if ( empty( $instawp ) ) {
			$instawp = new instaWP();
		}

		return $instawp;
	}
}


if ( ! function_exists( 'instawp_update_migration_stages' ) ) {
	/**
	 * Update migration stages
	 *
	 * @param array $stages
	 * @param string $migrate_id
	 * @param string $migrate_key
	 *
	 * @return bool
	 */
	function instawp_update_migration_stages( $stages = array(), $migrate_id = '', $migrate_key = '' ) {

		$migrate_id  = empty( $migrate_id ) ? InstaWP_Setting::get_option( 'migrate_id' ) : $migrate_id;
		$migrate_key = empty( $migrate_key ) ? InstaWP_Setting::get_option( 'migrate_key' ) : $migrate_key;

		if ( empty( $stages ) || ! is_array( $stages ) || empty( $migrate_id ) || empty( $migrate_key ) ) {
			return false;
		}

		$stage_args     = array(
			'migrate_key' => $migrate_key,
			'stage'       => $stages,
		);
		$stage_response = InstaWP_Curl::do_curl( 'migrates-v3/' . $migrate_id . '/update-status', $stage_args );

		return (bool) InstaWP_Setting::get_args_option( 'success', $stage_response, true );
	}
}


if ( ! function_exists( 'instawp_reset_running_migration' ) ) {
	/**
	 * Reset running migration
	 *
	 * @param string $reset_type
	 * @param bool $abort_forcefully
	 *
	 * @return bool
	 */
	function instawp_reset_running_migration( $reset_type = 'soft', $abort_forcefully = false ) {
		global $wpdb;

		$migration_details = InstaWP_Setting::get_option( 'instawp_migration_details', array() );
		$migrate_id        = InstaWP_Setting::get_args_option( 'migrate_id', $migration_details );
		$migrate_key       = InstaWP_Setting::get_args_option( 'migrate_key', $migration_details );

		// Delete migration details
		delete_option( 'instawp_migration_details' );

		$reset_type = empty( $reset_type ) ? InstaWP_Setting::get_option( 'instawp_reset_type', 'soft' ) : $reset_type;

		if ( ! in_array( $reset_type, array( 'soft', 'hard' ) ) ) {
			return false;
		}

		$instawp_backup_dir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . INSTAWP_DEFAULT_BACKUP_DIR . DIRECTORY_SEPARATOR;
		$files_to_delete    = scandir( $instawp_backup_dir );
		$files_to_delete    = ! is_array( $files_to_delete ) ? array() : $files_to_delete;
		$files_to_delete    = array_diff( $files_to_delete, array( '.', '..' ) );

		foreach ( $files_to_delete as $file ) {
			if ( is_file( $instawp_backup_dir . $file ) ) {
				@unlink( $instawp_backup_dir . $file );
			}
		}

		@unlink( ABSPATH . 'fwd.php' );
		@unlink( ABSPATH . 'dest.php' );

		$wpdb->query( "DROP TABLE IF EXISTS `iwp_db_sent`;" );
		$wpdb->query( "DROP TABLE IF EXISTS `iwp_files_sent`;" );
		$wpdb->query( "DROP TABLE IF EXISTS `iwp_options`;" );

		if ( 'hard' == $reset_type ) {
			delete_option( 'instawp_backup_part_size' );
			delete_option( 'instawp_max_file_size_allowed' );
			delete_option( 'instawp_reset_type' );
			delete_option( 'instawp_db_method' );
			delete_option( 'instawp_default_user' );
			delete_option( 'instawp_api_options' );

			delete_option( 'instawp_rm_heartbeat' );
			delete_option( 'instawp_api_heartbeat' );
			delete_option( 'instawp_rm_file_manager' );
			delete_option( 'instawp_rm_database_manager' );
			delete_option( 'instawp_rm_install_plugin_theme' );
			delete_option( 'instawp_rm_config_management' );
			delete_option( 'instawp_rm_inventory' );
			delete_option( 'instawp_rm_debug_log' );
			delete_option( 'instawp_last_heartbeat_sent' );
			delete_option( 'instawp_is_staging' );

			delete_transient( 'instawp_staging_sites' );
			delete_transient( 'instawp_migration_completed' );

			wp_clear_scheduled_hook( 'instawp_clean_file_manager' );
			wp_clear_scheduled_hook( 'instawp_clean_database_manager' );

			do_action( 'instawp_clean_file_manager' );
			do_action( 'instawp_clean_database_manager' );
		}

		if ( $abort_forcefully === true && ! empty( $migrate_id ) && ! empty( $migrate_key ) ) {

			$response = InstaWP_Curl::do_curl( "migrates-v3/{$migrate_id}/update-status",
				array(
					'migrate_key'    => $migrate_key,
					'stage'          => array( 'aborted' => true ),
					'failed_message' => esc_html__( 'Migration aborted forcefully', 'instawp-connect' ),
				)
			);

			if ( isset( $response['success'] ) && ! $response['success'] ) {
				error_log( json_encode( $response ) );
			}
		}

		return true;
	}
}


if ( ! function_exists( 'instawp_is_website_on_local' ) ) {
	/**
	 * Check if the current website is on local server or live
	 *
	 * @return bool
	 */
	function instawp_is_website_on_local() {

		if ( defined( 'INSTAWP_LOCAL_DEV' ) && INSTAWP_LOCAL_DEV === true ) {
			return false;
		}

		$http_host       = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '';
		$remote_address  = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
		$local_addresses = array(
			'127.0.0.1',
			'::1',
		);
		$local_addresses = apply_filters( 'INSTAWP_CONNECT/Filters/local_addresses', $local_addresses );

		return ( $http_host == 'localhost' || in_array( $remote_address, $local_addresses ) );
	}
}


if ( ! function_exists( 'instawp_get_connect_id' ) ) {
	/**
	 * get connect id for source site
	 *
	 * @return int
	 */
	function instawp_get_connect_id() {
		return InstaWP_Setting::get_connect_id();
	}
}


if ( ! function_exists( 'instawp_get_post_type_singular_name' ) ) {
	/**
	 * get post type singular name
	 *
	 * @param $post_type
	 *
	 * @return string
	 */
	function instawp_get_post_type_singular_name( $post_type ) {
		$post_type_object = get_post_type_object( $post_type );
		if ( ! empty( $post_type_object ) ) {
			return $post_type_object->labels->singular_name;
		}

		return '';
	}
}


if ( ! function_exists( 'instawp_get_staging_sites_list' ) ) {
	function instawp_get_staging_sites_list( $insta_only = false ) {
		$staging_sites = get_option( 'instawp_staging_sites' );

		if ( ! $staging_sites || ! is_array( $staging_sites ) ) {
			return array();
		}

		if ( $insta_only ) {
			$staging_sites = array_filter( $staging_sites, function ( $value ) {
				return ! empty( $value['is_insta_site'] );
			} );
		}

		return $staging_sites;
	}
}


if ( ! function_exists( 'instawp_set_staging_sites_list' ) ) {
	function instawp_set_staging_sites_list() {

		$api_response = InstaWP_Curl::do_curl( 'connects/' . instawp_get_connect_id() . '/staging-sites', array(), array(), false );

		if ( $api_response['success'] ) {
			$staging_sites = InstaWP_Setting::get_args_option( 'data', $api_response, array() );

			foreach ( $staging_sites as $key => $staging_site ) {
				$staging_site_data = instawp_get_connect_detail_by_connect_id( $staging_site['connect_id'] );
				if ( ! $staging_site_data ) {
					continue;
				}

				unset( $staging_site_data['site_information'], $staging_site_data['site_information_raw'] );
				$staging_sites[ $key ]['data'] = $staging_site_data;
			}

			InstaWP_Setting::update_option( 'instawp_staging_sites', $staging_sites );
		}
	}
}


if ( ! function_exists( 'instawp_get_database_details' ) ) {
	/**
	 * Get directory content.
	 */
	function instawp_get_database_details( $sort_by = false ) {
		global $wpdb;

		$tables = array();
		$rows   = $wpdb->get_results( 'SHOW TABLE STATUS', ARRAY_A );
		if ( $wpdb->num_rows > 0 ) {
			foreach ( $rows as $row ) {
				$size = $row['Data_length'] + $row['Index_length'];

				$tables[] = array(
					'name' => $row['Name'],
					'size' => $size,
					'rows' => $row['Rows'],
				);
			}

			if ( $sort_by === 'descending' ) {
				usort( $tables, function ( $item1, $item2 ) {
					if ( $item1['size'] == $item2['size'] ) {
						return 0;
					}

					return ( $item1['size'] > $item2['size'] ) ? - 1 : 1;
				} );
			} elseif ( $sort_by === 'ascending' ) {
				usort( $tables, function ( $item1, $item2 ) {
					if ( $item1['size'] == $item2['size'] ) {
						return 0;
					}

					return ( $item1['size'] < $item2['size'] ) ? - 1 : 1;
				} );
			}
		}

		return $tables;
	}
}


if ( ! function_exists( 'instawp_get_dir_contents' ) ) {
	/**
	 * Get directory content.
	 */
	function instawp_get_dir_contents( $dir = '/', $sort_by = false, $add_root_path = true ) {

		$dir = $add_root_path ? instawp_get_root_path() . $dir : $dir;

		return instawp()->get_directory_contents( $dir, $sort_by );
	}
}


if ( ! function_exists( 'instawp_get_root_path' ) ) {
	function instawp_get_root_path() {
		$root_path = $_SERVER['DOCUMENT_ROOT'];
		$root_path = rtrim( $root_path, '\\/' );

		return str_replace( '\\', '/', $root_path );
	}
}


if ( ! function_exists( 'instawp_set_whitelist_ip' ) ) {
	function instawp_set_whitelist_ip( $ip_addresses = '' ) {
		// WordFence
		instawp_set_wordfence_whitelist_ip( $ip_addresses );

		// SolidWP
		instawp_set_solid_wp_whitelist_ip( $ip_addresses );
	}
}


if ( ! function_exists( 'instawp_prepare_whitelist_ip' ) ) {
	function instawp_prepare_whitelist_ip( $ip_addresses = '' ) {
		$server_ip_addresses = array( '167.71.233.239', '159.65.64.73' );

		if ( is_string( $ip_addresses ) ) {
			$ip_addresses = trim( $ip_addresses );
			$ip_addresses = array_map( 'trim', explode( ',', $ip_addresses ) );
		}

		return array_merge( $server_ip_addresses, $ip_addresses );
	}
}


if ( ! function_exists( 'instawp_is_wordfence_whitelisted' ) ) {
	function instawp_is_wordfence_whitelisted() {
		$whitelisted = false;
		if ( class_exists( '\wfConfig' ) && method_exists( '\wfConfig', 'get' ) ) {
			$whites = \wfConfig::get( 'whitelisted', array() );
			$arr    = explode( ',', $whites );
			if ( in_array( '167.71.233.239', $arr ) && in_array( '159.65.64.73', $arr ) ) {
				$whitelisted = true;
			}
		}

		return $whitelisted;
	}
}


if ( ! function_exists( 'instawp_is_solid_wp_whitelisted' ) ) {
	function instawp_is_solid_wp_whitelisted() {
		$whitelisted = false;
		if ( class_exists( '\ITSEC_Modules' ) && method_exists( '\ITSEC_Modules', 'get_settings' ) ) {
			$whites = \ITSEC_Modules::get_setting( 'global', 'lockout_white_list', array() );
			if ( in_array( '167.71.233.239', $whites ) && in_array( '159.65.64.73', $whites ) ) {
				$whitelisted = true;
			}
		}

		return $whitelisted;
	}
}


if ( ! function_exists( 'instawp_set_wordfence_whitelist_ip' ) ) {
	function instawp_set_wordfence_whitelist_ip( $ip_addresses = '' ) {
		if ( class_exists( '\wordfence' ) && method_exists( '\wordfence', 'whitelistIP' ) ) {
			foreach ( instawp_prepare_whitelist_ip( $ip_addresses ) as $ip_address ) {
				\wordfence::whitelistIP( $ip_address );
			}
		}
	}
}


if ( ! function_exists( 'instawp_set_solid_wp_whitelist_ip' ) ) {
	function instawp_set_solid_wp_whitelist_ip( $ip_addresses = '' ) {
		if ( class_exists( '\ITSEC_Modules' ) && method_exists( '\ITSEC_Modules', 'get_settings' ) && method_exists( '\ITSEC_Modules', 'set_settings' ) ) {
			$settings = \ITSEC_Modules::get_settings( 'global' );

			$settings['lockout_white_list'] = array_unique( array_merge( $settings['lockout_white_list'], instawp_prepare_whitelist_ip( $ip_addresses ) ) );

			\ITSEC_Modules::set_settings( 'global', $settings );
		}
	}
}


if ( ! function_exists( 'instawp_whitelist_ip' ) ) {
	function instawp_whitelist_ip() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$providers = array(
			'wordfence/wordfence.php'                   => array(
				'name'     => 'WordFence',
				'function' => 'instawp_is_wordfence_whitelisted',
			),
			'better-wp-security/better-wp-security.php' => array(
				'name'     => 'Solid Security',
				'function' => 'instawp_is_solid_wp_whitelisted',
			),
		);

		$output = array(
			'can_whitelist' => false,
			'plugins'       => '',
		);

		$names = array();
		foreach ( $providers as $plugin => $details ) {
			if ( is_plugin_active( $plugin ) && ! call_user_func( $details['function'] ) ) {
				$output['can_whitelist'] = true;
				$names[]                 = $details['name'];
			}
		}

		if ( ! empty( $names ) ) {
			$output['plugins'] = join( ', ', $names );
		}

		return $output;
	}
}


if ( ! function_exists( 'instawp_get_source_site_detail' ) ) {
	function instawp_get_source_site_detail() {

		if ( ! instawp()->is_staging ) {
			return;
		}

		if ( ! empty( InstaWP_Setting::get_option( 'instawp_sync_parent_connect_data' ) ) ) {
			return;
		}

		$connect_id  = instawp_get_connect_id();
		$parent_data = instawp_get_connect_detail_by_connect_id( $connect_id );

		InstaWP_Setting::update_option( 'instawp_sync_parent_connect_data', $parent_data );
	}
}


if ( ! function_exists( 'instawp_get_connect_detail_by_connect_id' ) ) {
	/**
	 * Connect detail
	 *
	 * @param $connect_id
	 *
	 * @return array
	 */
	function instawp_get_connect_detail_by_connect_id( $connect_id ) {
		// connects/<connect_id>
		$response        = array();
		$site_connect_id = instawp_get_connect_id();
		$api_response    = InstaWP_Curl::do_curl( 'connects/' . $site_connect_id . '/connected-sites', array(), array(), false );

		if ( $api_response['success'] ) {
			$api_response = InstaWP_Setting::get_args_option( 'data', $api_response, array() );

			if ( isset( $api_response['is_parent'] ) ) {
				$response = $api_response['is_parent'] ? $api_response['parent'] : $api_response['children'];

				if ( ! $api_response['is_parent'] ) {
					$response = array_filter( $response, function ( $value ) use ( $connect_id ) {
						return $value['id'] === intval( $connect_id );
					} );
					$response = count( $response ) > 0 ? reset( $response ) : array();
				}
			}
		}

		return ( array ) $response;
	}
}


if ( ! function_exists( 'instawp_get_site_detail_by_connect_id' ) ) {
	/**
	 * Site detail
	 *
	 * @param $connect_id
	 *
	 * @return mixed
	 */
	function instawp_get_site_detail_by_connect_id( $connect_id, $field = '', $force_update = false ) {

		if ( $force_update === true ) {
			instawp_set_staging_sites_list();
		}

		$staging_sites = instawp_get_staging_sites_list();
		$site_data     = array();

		foreach ( $staging_sites as $staging_site ) {
			if ( $staging_site['connect_id'] === $connect_id ) {
				if ( ! empty( $staging_site ) && ( empty( $field ) || ! empty( $staging_site[ $field ] ) ) ) {
					$site_data = $staging_site;
				} else {
					$site_data = instawp_get_site_detail_by_connect_id( $staging_site['connect_id'], '', true );
				}
				break;
			}
		}

		if ( ! empty( $field ) ) {
			return isset( $site_data[ $field ] ) ? $site_data[ $field ] : '';
		}

		return $site_data;
	}
}


if ( ! function_exists( 'instawp_send_connect_log' ) ) {
	/**
	 * Send connect log to app
	 *
	 * @param $action
	 * @param $log_message
	 *
	 * @return bool
	 */
	function instawp_send_connect_log( $action = '', $log_message = '' ) {

		if ( empty( $action ) || empty( $log_message ) ) {
			return false;
		}

		$connect_id = instawp()->connect_id;
		$log_args   = array(
			'action' => $action,
			'logs'   => $log_message,
		);

		// connects/<connect_id>/logs
		$log_response = InstaWP_Curl::do_curl( "connects/{$connect_id}/logs", $log_args );

		if ( isset( $log_response['success'] ) && $log_response['success'] ) {
			return true;
		}

		return false;
	}
}


if ( ! function_exists( 'instawp_send_heartbeat' ) ) {
	/**
	 * Send heartbeat to InstaWP
	 *
	 * @return bool
	 */
	function instawp_send_heartbeat( $connect_id = '' ) {
		return InstaWP_Heartbeat::send_heartbeat( $connect_id );
	}
}


if ( ! function_exists( 'instawp_get_user_to_login' ) ) {
	/**
	 * Validate and Return the user to login
	 *
	 * @param string $username
	 *
	 * @return WP_Error|array
	 */
	function instawp_get_user_to_login( $username = '' ) {

		if ( username_exists( $username ) ) {
			$user_to_login = get_user_by( 'login', $username );
			$message       = esc_html__( 'Login information for the given username', 'instawp-connect' );
		} elseif ( ! empty( $default_username = InstaWP_Setting::get_option( 'instawp_default_username' ) ) && ! empty( $default_username ) ) {
			$user_to_login = get_user_by( 'login', $default_username );
			$message       = esc_html__( 'Login information for the given username didn\'t found, You are going to login with default login username.', 'instawp-connect' );
		} else {
			$admin_users   = get_users( array( 'role' => 'administrator' ) );
			$user_to_login = is_array( $admin_users ) && isset( $admin_users[0] ) ? $admin_users[0] : false;
			$message       = esc_html__( 'No login found with given username and default username, You are going to login with first admin user.', 'instawp-connect' );
		}

		if ( ! $user_to_login instanceof WP_User ) {
			return new WP_Error( 'login_user_not_found', esc_html__( 'No login information found.', 'instawp-connect' ) );
		}

		return array(
			'username' => $user_to_login->user_login,
			'message'  => $message,
		);
	}
}


if ( ! function_exists( 'instawp_add_file_to_phar' ) ) {
	/**
	 * Add files to phar
	 *
	 * @param $phar
	 * @param $sourceDir
	 * @param $file
	 * @param $localName
	 *
	 * @return void
	 */
	function instawp_add_file_to_phar( $phar, $sourceDir, $file, $localName ) {
		// Check if the file is a symbolic link
		if ( is_link( $file ) ) {
			// Resolve the path that the link points to
			$realFilePath = readlink( $file );
			// Add the resolved path file to the Phar archive
			$phar->addFile( $realFilePath, $localName );
		} else {
			// Add the file to the Phar archive
			$phar->addFile( $file, $localName );
		}
	}
}


if ( ! function_exists( 'instawp_add_dir_to_phar' ) ) {

	/**
	 * Add directories to phar
	 *
	 * @param $phar
	 * @param $sourceDir
	 * @param $skipDirs
	 * @param $dir
	 *
	 * @return void
	 */
	function instawp_add_dir_to_phar( $phar, $sourceDir, $skipDirs, $dir = '' ) {
		$fullDir = realpath( $sourceDir . '/' . $dir );

		if ( in_array( $fullDir, $skipDirs ) ) {
			return;
		}

		$handle = opendir( $fullDir );

		while ( false !== ( $entry = readdir( $handle ) ) ) {
			if ( $entry != '.' && $entry != '..' ) {
				$fullPath  = $fullDir . '/' . $entry;
				$localName = $dir . '/' . $entry;

				if ( is_dir( $fullPath ) ) {
					instawp_add_dir_to_phar( $phar, $sourceDir, $skipDirs, $localName );
				} else {
					instawp_add_file_to_phar( $phar, $sourceDir, $fullPath, $localName );
				}
			}
		}

		closedir( $handle );
	}
}


if ( ! function_exists( 'instawp_zip_folder_with_phar' ) ) {
	/**
	 * Make archive with phar
	 *
	 * @param $source
	 * @param $destination
	 * @param array $skipDirs
	 *
	 * @return void
	 * @throws Exception
	 */
	function instawp_zip_folder_with_phar( $source, $destination, array $skipDirs = array() ) {
		$source = rtrim( $source, '/' );

		if ( ! file_exists( $source ) ) {
			throw new Exception( "Source directory does not exist: $source" );
		}

		// Prepare full paths for directories to skip
		foreach ( $skipDirs as &$dir ) {
			$dir = realpath( $source . '/' . $dir );
		}

		// Initialize PharData with .tar
		$phar = new PharData( $destination . '.tar' );

		// Manually add files to Phar, taking care of symbolic links and skipping directories
		instawp_add_dir_to_phar( $phar, $source, $skipDirs );

		// Compress the .tar into .tar.gz
		$phar->compress( Phar::GZ );

		// Clean up the .tar file
		unlink( $destination . '.tar' );

		// Rename .tar.gz to .zip
		rename( $destination . '.tar.gz', $destination );
	}
}


if ( ! function_exists( 'instawp_array_recursive_diff' ) ) {

	/**
	 * Filer to get difference of recursive array data
	 *
	 * @param $array1
	 * @param $array2
	 *
	 * @return array
	 */
	function instawp_array_recursive_diff( $array1, $array2 ) {
		$diff = array();
		foreach ( $array1 as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( ! isset( $array2[ $key ] ) || ! is_array( $array2[ $key ] ) ) {
					$diff[ $key ] = $value;
				} else {
					$recursive_diff = instawp_array_recursive_diff( $value, $array2[ $key ] );
					if ( ! empty( $recursive_diff ) ) {
						$diff[ $key ] = $recursive_diff;
					}
				}
			} elseif ( ! array_key_exists( $key, $array2 ) || $array2[ $key ] !== $value ) {
				$diff[ $key ] = $value;
			}
		}

		return $diff;
	}
}