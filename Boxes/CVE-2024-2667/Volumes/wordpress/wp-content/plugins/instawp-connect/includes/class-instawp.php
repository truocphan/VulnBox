<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://instawp.com/
 * @since      1.0
 *
 * @package    instawp
 * @subpackage instawp/includes
 */

defined( 'ABSPATH' ) || exit;

class instaWP {

	protected $plugin_name;

	protected $version;

	public $admin;

	public $is_staging = false;

	public $is_connected = false;

	public $is_on_local = false;

	public $is_parent_on_local = false;

	public $has_unsupported_plugins = false;

	public $can_bundle = false;

	public $api_key = null;

	public $connect_id = null;

	public $tools = null;

	public function __construct() {

		$this->load_dependencies();

		$this->version                 = INSTAWP_PLUGIN_VERSION;
		$this->plugin_name             = INSTAWP_PLUGIN_SLUG;
		$this->api_key                 = InstaWP_Setting::get_api_key();
		$this->is_connected            = ! empty( $this->api_key );
		$this->is_on_local             = instawp_is_website_on_local();
		$this->connect_id              = instawp_get_connect_id();
		$this->is_staging              = (bool) InstaWP_Setting::get_option( 'instawp_is_staging', false );
		$this->is_parent_on_local      = (bool) InstaWP_Setting::get_option( 'instawp_parent_is_on_local', false );
		$this->has_unsupported_plugins = ! empty( InstaWP_Tools::get_unsupported_active_plugins() );
		$this->can_bundle              = ( class_exists( 'ZipArchive' ) || class_exists( 'PharData' ) );

		// if connect id is empty then remove all connection
//      if ( empty( $this->connect_id ) ) {
//          instawp_reset_running_migration( 'hard' );
//      }

		if ( is_admin() ) {
			$this->set_locale();
			$this->define_admin_hook();
		}

		add_action( 'init', array( $this, 'register_actions' ), 11 );
		add_action( 'instawp_prepare_large_files_list', array( $this, 'prepare_large_files_list' ) );
		add_action( 'add_option_instawp_max_file_size_allowed', array( $this, 'clear_staging_sites_list' ) );
		add_action( 'update_option_instawp_max_file_size_allowed', array( $this, 'clear_staging_sites_list' ) );
		add_action( 'instawp_clean_migrate_files', array( $this, 'clean_migrate_files' ) );
		add_action( 'add_option_instawp_enable_wp_debug', array( $this, 'toggle_wp_debug' ), 10, 2 );
		add_action( 'update_option_instawp_enable_wp_debug', array( $this, 'toggle_wp_debug' ), 10, 2 );
	}

	public function toggle_wp_debug( $old_value, $value ) {
		if ( $value === 'on' ) {
			$params = array(
				'WP_DEBUG'         => true,
				'WP_DEBUG_LOG'     => true,
				'WP_DEBUG_DISPLAY' => false,
			);
		} else {
			$params = array(
				'WP_DEBUG'         => false,
				'WP_DEBUG_LOG'     => false,
				'WP_DEBUG_DISPLAY' => false,
			);
		}

		$wp_config = new \InstaWP\Connect\Helpers\WPConfig( $params );
		$wp_config->update();
	}

	public function register_actions() {
		if ( ! as_has_scheduled_action( 'instawp_prepare_large_files_list', array(), 'instawp-connect' ) ) {
			as_schedule_recurring_action( time(), HOUR_IN_SECONDS, 'instawp_prepare_large_files_list', array(), 'instawp-connect' );
		}

		if ( ! as_has_scheduled_action( 'instawp_clean_migrate_files', array(), 'instawp-connect' ) ) {
			as_schedule_recurring_action( time(), DAY_IN_SECONDS, 'instawp_clean_migrate_files', array(), 'instawp-connect' );
		}
	}

	public function clean_migrate_files() {

		$migration_details = InstaWP_Setting::get_option( 'instawp_migration_details', array() );
		$migrate_id        = InstaWP_Setting::get_args_option( 'migrate_id', $migration_details );
		$migrate_key       = InstaWP_Setting::get_args_option( 'migrate_key', $migration_details );

		if ( empty( $migrate_id ) && empty( $migrate_key ) ) {
			instawp_reset_running_migration();
		}
	}

	public function prepare_large_files_list() {
		$maxbytes = (int) InstaWP_Setting::get_option( 'instawp_max_file_size_allowed', INSTAWP_DEFAULT_MAX_FILE_SIZE_ALLOWED );
		$maxbytes = $maxbytes ?: INSTAWP_DEFAULT_MAX_FILE_SIZE_ALLOWED;
		$maxbytes = ( $maxbytes * 1024 * 1024 );
		$path     = ABSPATH;
		$data     = array();

		if ( $path != '' && file_exists( $path ) && is_readable( $path ) ) {
			try {
				foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path, FilesystemIterator::SKIP_DOTS ) ) as $object ) {
					if ( $object->getSize() > $maxbytes && strpos( $object->getPath(), 'instawpbackups' ) === false ) {
						$data[] = array(
							'size'          => $object->getSize(),
							'path'          => wp_normalize_path( $object->getPath() ),
							'pathname'      => wp_normalize_path( $object->getPathname() ),
							'realpath'      => wp_normalize_path( $object->getRealPath() ),
							'relative_path' => str_replace( wp_normalize_path( ABSPATH ), '', wp_normalize_path( $object->getRealPath() ) ),
						);
					}
				}
			} catch ( Exception $e ) {
				error_log( 'error in prepare_large_files_list: ' . $e->getMessage() );
			}
		}

		set_transient( 'instawp_generate_large_files', true, HOUR_IN_SECONDS );
		InstaWP_Setting::update_option( 'instawp_large_files_list', $data );
	}

	public function clear_staging_sites_list() {
		delete_option( 'instawp_large_files_list' );
		do_action( 'instawp_prepare_large_files_list' );
	}

	private function set_locale() {
		load_plugin_textdomain( 'instawp-connect', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}

	private function define_admin_hook() {
		$this->admin = new InstaWP_Admin( $this->get_plugin_name(), $this->get_version() );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . 'instawp-connect.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this->admin, 'add_action_links' ) );
		add_filter( 'instawp_add_tab_page', array( $this->admin, 'instawp_add_default_tab_page' ) );
		add_action( 'admin_init', 'instawp_get_source_site_detail', 999 );
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_version() {
		return $this->version;
	}

	public function get_directory_contents( $dir, $sort_by ) {
		if ( empty( $dir ) || ! is_dir( $dir ) ) {
			return array();
		}

		$files_data = scandir( $dir );
		if ( ! $files_data ) {
			return array();
		}

		$path_to_replace = wp_normalize_path( instawp_get_root_path() . DIRECTORY_SEPARATOR );
		$files           = $folders = array();

		foreach ( $files_data as $value ) {
			$path = rtrim( $dir, '/' ) . DIRECTORY_SEPARATOR . $value;

			if ( empty( $path ) || $value == "." || $value == ".." || ! file_exists( $path ) || ! is_readable( $path ) ) {
				continue;
			}

			$normalized_path = wp_normalize_path( $path );

			try {
				if ( ! is_dir( $path ) ) {
					$size    = filesize( $path );
					$files[] = array(
						'name'          => $value,
						'relative_path' => str_replace( $path_to_replace, '', $normalized_path ),
						'full_path'     => $normalized_path,
						'size'          => $size,
						'count'         => 1,
						'type'          => 'file',
					);
				} else {
					$directory_info = $this->get_directory_info( $path );
					$folders[]      = array(
						'name'          => $value,
						'relative_path' => str_replace( $path_to_replace, '', $normalized_path ),
						'full_path'     => $normalized_path,
						'size'          => $directory_info['size'],
						'count'         => $directory_info['count'],
						'type'          => 'folder',
					);
				}
			} catch ( Exception $e ) {
			}
		}

		$files_list = array_merge( $folders, $files );

		if ( $sort_by === 'descending' ) {
			usort( $files_list, function ( $item1, $item2 ) {
				if ( $item1['size'] == $item2['size'] ) {
					return 0;
				}

				return ( $item1['size'] > $item2['size'] ) ? - 1 : 1;
			} );
		} elseif ( $sort_by === 'ascending' ) {
			usort( $files_list, function ( $item1, $item2 ) {
				if ( $item1['size'] == $item2['size'] ) {
					return 0;
				}

				return ( $item1['size'] < $item2['size'] ) ? - 1 : 1;
			} );
		}

		return $files_list;
	}

	public function get_directory_info( $path ) {
		$bytes_total = 0;
		$files_total = 0;
		try {
			if ( $path !== false && $path != '' && file_exists( $path ) ) {
				foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path, FilesystemIterator::SKIP_DOTS ) ) as $object ) {
					$bytes_total += $object->getSize();
					++ $files_total;
				}
			}
		} catch ( Exception $e ) {
		}

		return array(
			'size'  => $bytes_total,
			'count' => $files_total,
		);
	}

	public function get_directory_size( $path ) {
		$info = $this->get_directory_info( $path );

		return $info['size'];
	}

	public function get_file_size_with_unit( $size, $unit = "" ) {
		if ( ( ! $unit && $size >= 1 << 30 ) || $unit == "GB" ) {
			return number_format( $size / ( 1 << 30 ), 2 ) . " GB";
		}

		if ( ( ! $unit && $size >= 1 << 20 ) || $unit == "MB" ) {
			return number_format( $size / ( 1 << 20 ), 2 ) . " MB";
		}

		if ( ( ! $unit && $size >= 1 << 10 ) || $unit == "KB" ) {
			return number_format( $size / ( 1 << 10 ), 2 ) . " KB";
		}

		return number_format( $size ) . " B";
	}

	public function get_current_mode( $data_to_get = '' ) {
		$mode_data = array();

		if ( ! empty( INSTAWP_CONNECT_MODE ) ) {
			$mode_data['type'] = INSTAWP_CONNECT_MODE;
			$mode_data['name'] = defined( INSTAWP_CONNECT_MODE_NAME ) ? INSTAWP_CONNECT_MODE_NAME : '';
			$mode_data['link'] = defined( INSTAWP_CONNECT_MODE_LINK ) ? INSTAWP_CONNECT_MODE_LINK : '';
			$mode_data['desc'] = defined( INSTAWP_CONNECT_MODE_DESC ) ? INSTAWP_CONNECT_MODE_DESC : '';
			$mode_data['logo'] = defined( INSTAWP_CONNECT_MODE_LOGO ) ? INSTAWP_CONNECT_MODE_LOGO : '';
		}

		if ( ! empty( $data_to_get ) ) {
			return InstaWP_Setting::get_args_option( $data_to_get, $mode_data );
		}

		return $mode_data;
	}

	public static function disable_cache_elements_before_restore() {

		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$file_name_ap   = ABSPATH . 'instawp-active-plugins.json';
		$active_plugins = (array) get_option( 'active_plugins', array() );

		// Ignore instawp plugin
		if ( ( $key = array_search( INSTAWP_PLUGIN_NAME, $active_plugins ) ) !== false ) {
			unset( $active_plugins[ $key ] );
		}

		file_put_contents( $file_name_ap, json_encode( $active_plugins ) );

		// For the Breeze plugin support
		if ( in_array( 'breeze/breeze.php', $active_plugins ) ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				include ABSPATH . 'wp-admin/includes/file.php';
				include WP_CONTENT_DIR . '/plugins/breeze/inc/cache/config-cache.php';
				include WP_CONTENT_DIR . '/plugins/breeze/inc/breeze-configuration.php';
			}
		}

		deactivate_plugins( $active_plugins );
	}


	public static function enable_cache_elements_before_restore() {

		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$file_name_ap   = ABSPATH . 'instawp-active-plugins.json';
		$active_plugins = file_get_contents( $file_name_ap );
		$active_plugins = json_decode( $active_plugins, true );
		$response       = activate_plugins( $active_plugins );

		if ( ! is_wp_error( $response ) && $response ) {
			unlink( $file_name_ap );
		}

		// Flush Redis Cache
		if ( class_exists( '\RedisCachePro\Plugin' ) ) {
			\RedisCachePro\Plugin::boot()->flush();
		}
	}


	public static function get_asset_url( $asset_name ) {
		return INSTAWP_PLUGIN_URL . $asset_name;
	}

	public static function get_exclude_default_plugins() {

		$exclude_plugins = array(
			'instawp-connect',
			'wp-cerber',
			'instawp-backup-pro',
			'.',
		);

		return apply_filters( 'INSTAWP_CONNECT/Filters/get_exclude_default_plugins', $exclude_plugins );
	}

	public static function get_folder_size( $root, $size ) {
		$count = 0;
		if ( is_dir( $root ) ) {
			$handler = opendir( $root );
			if ( $handler !== false ) {
				while ( ( $filename = readdir( $handler ) ) !== false ) {
					if ( $filename != "." && $filename != ".." ) {
						++ $count;

						if ( is_dir( $root . DIRECTORY_SEPARATOR . $filename ) ) {
							$size = self::get_folder_size( $root . DIRECTORY_SEPARATOR . $filename, $size );
						} elseif ( file_exists( $filepath = $root . DIRECTORY_SEPARATOR . $filename ) && is_readable( $filepath ) ) {
							$size += filesize( $filepath );
						}
					}
				}
				if ( $handler ) {
					@closedir( $handler );
				}
			}
		}

		return $size;
	}

	public static function get_plugins_list( $options = array(), $return_type = 'plugins_included' ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins_included        = array();
		$plugins_excluded        = array();
		$list                    = get_plugins();
		$active_plugins_only     = isset( $options['migrate_settings']['active_plugins_only'] ) ? $options['migrate_settings']['active_plugins_only'] : false;
		$exclude_default_plugins = self::get_exclude_default_plugins();

		foreach ( $list as $key => $item ) {
			$dirname = dirname( $key );

			if ( in_array( $dirname, $exclude_default_plugins ) ) {
				$plugins_excluded[] = $key;
				continue;
			}

			if ( ( 'true' == $active_plugins_only || '1' == $active_plugins_only ) && ! is_plugin_active( $key ) ) {
				$plugins_excluded[] = $key;
				continue;
			}

			$plugins_included[ $dirname ]['slug'] = $dirname;
			$plugins_included[ $dirname ]['size'] = self::get_folder_size( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $dirname, 0 );
		}

		$plugins_excluded = array_map( function ( $slug ) {
			$slug_parts = explode( '/', $slug );

			return isset( $slug_parts[0] ) ? $slug_parts[0] : '';
		}, $plugins_excluded );
		$plugins          = array(
			'plugins_included' => $plugins_included,
			'plugins_excluded' => array_filter( $plugins_excluded ),
		);

		if ( empty( $return_type ) ) {
			return $plugins;
		}

		return isset( $plugins[ $return_type ] ) ? $plugins[ $return_type ] : array();
	}

	public static function get_themes_list( $options = array(), $return_type = 'themes_included' ) {

		if ( ! function_exists( 'wp_get_themes' ) ) {
			require_once ABSPATH . 'wp-includes/theme.php';
		}

		$themes_included    = array();
		$themes_excluded    = array();
		$current_theme      = wp_get_theme();
		$active_themes_only = isset( $options['migrate_settings']['active_themes_only'] ) ? $options['migrate_settings']['active_themes_only'] : false;

		foreach ( wp_get_themes() as $key => $item ) {
			if ( ( 'true' == $active_themes_only || '1' == $active_themes_only ) && ! in_array( $item->get_stylesheet(), array( $current_theme->get_stylesheet(), $current_theme->get_template() ) ) ) {
				$themes_excluded[] = $key;
				continue;
			}

			$themes_included[ $key ]['slug'] = $key;
			$themes_included[ $key ]['size'] = self::get_folder_size( get_theme_root() . DIRECTORY_SEPARATOR . $key, 0 );
		}

		$themes = array(
			'themes_included' => $themes_included,
			'themes_excluded' => $themes_excluded,
		);

		if ( empty( $return_type ) ) {
			return $themes;
		}

		return isset( $themes[ $return_type ] ) ? $themes[ $return_type ] : array();
	}

	public function instawp_check_usage_on_cloud( $total_size = 0 ) {

		// connects/<connect_id>/usage
		$api_response        = InstaWP_Curl::do_curl( "connects/{$this->connect_id}/usage", array(), array(), false, 'v1' );
		$api_response_status = InstaWP_Setting::get_args_option( 'success', $api_response, false );
		$api_response_data   = InstaWP_Setting::get_args_option( 'data', $api_response, array() );

		// send usage check log before starting the pull
		instawp_send_connect_log( 'usage-check', json_encode( $api_response ) );

		if ( ! $api_response_status ) {
			return array(
				'can_proceed'  => false,
				'connect_id'   => $this->connect_id,
				'api_response' => $api_response,
			);
		}

		$remaining_site       = (int) InstaWP_Setting::get_args_option( 'remaining_site', $api_response_data, '0' );
		$can_proceed          = $remaining_site > 0;
		$issue_for            = 'remaining_site';
		$available_disk_space = (int) InstaWP_Setting::get_args_option( 'remaining_disk_space', $api_response_data, '0' );


		$total_site_size = round( $total_size / 1048576, 2 );

		$api_response_data['require_disk_space'] = $total_site_size;

		if ( $can_proceed ) {
			$can_proceed = $total_site_size < $available_disk_space;
			$issue_for   = 'remaining_disk_space';
		}

		return array_merge( array(
			'can_proceed' => $can_proceed,
			'issue_for'   => ( $can_proceed ? '' : $issue_for ),
		), $api_response_data );
	}

	private function load_dependencies() {
		require_once INSTAWP_PLUGIN_DIR . '/admin/class-instawp-admin.php';

		require_once INSTAWP_PLUGIN_DIR . '/migrate/class-instawp-migrate.php';

		include_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-migrate-log.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-curl.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-ajax.php';
		include_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-setting.php';
		include_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-heartbeat.php';
		include_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-file-management.php';
		include_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-database-management.php';
		include_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-tools.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-rest-api.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-hooks.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/class-instawp-cli.php';

		require_once INSTAWP_PLUGIN_DIR . '/includes/sync/class-instawp-sync-db.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/sync/class-instawp-sync-helpers.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/sync/class-instawp-sync-ajax.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/sync/class-instawp-sync-apis.php';
		require_once INSTAWP_PLUGIN_DIR . '/includes/sync/class-instawp-sync-customize-setting.php';

		$files = array( 'option', 'plugin-theme', 'post', 'term', 'user', 'customizer', 'wc' );
		foreach ( $files as $file ) {
			require_once INSTAWP_PLUGIN_DIR . '/includes/sync/class-instawp-sync-' . $file . '.php';
		}

		$setting = InstaWP_Setting::get_option( 'instawp_activity_log', 'off' );
		if ( $setting === 'on' ) {
			require_once INSTAWP_PLUGIN_DIR . '/includes/activity-log/class-instawp-activity-log.php';
			$files = array( 'posts', 'attachments', 'users', 'menus', 'plugins', 'themes', 'taxonomies', 'widgets' );
			foreach ( $files as $file ) {
				require_once INSTAWP_PLUGIN_DIR . '/includes/activity-log/class-instawp-activity-log-' . $file . '.php';
			}
		}
	}
}
