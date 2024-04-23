<?php
/**
 * InstaWP CLI Commands
 */

use InstaWP\Connect\Helpers\WPConfig;

if ( ! class_exists( 'INSTAWP_CLI_Commands' ) ) {
	class INSTAWP_CLI_Commands {

		protected static $_instance = null;

		/**
		 * INSTAWP_CLI_Commands Constructor
		 */
		public function __construct() {
			add_action( 'cli_init', array( $this, 'add_wp_cli_commands' ) );
		}

		function cli_local_push() {

			global $wp_version;

			// Files backup
			if ( is_wp_error( $archive_path_file = InstaWP_Tools::cli_archive_wordpress_files() ) ) {
				die( $archive_path_file->get_error_message() );
			}
			WP_CLI::success( 'Files backup created successfully.' );

			InstaWP_Setting::update_option( 'instawp_parent_is_on_local', true );

			// Database backup
			$archive_path_db = InstaWP_Tools::cli_archive_wordpress_db();
			WP_CLI::success( 'Database backup created successfully.' );

			delete_option( 'instawp_parent_is_on_local' );

			// Create Site
			if ( is_wp_error( $create_site_res = InstaWP_Tools::create_insta_site() ) ) {
				die( $create_site_res->get_error_message() );
			}

			$site_id          = InstaWP_Setting::get_args_option( 'id', $create_site_res );
			$site_wp_url      = InstaWP_Setting::get_args_option( 'wp_url', $create_site_res );
			$site_wp_username = InstaWP_Setting::get_args_option( 'wp_username', $create_site_res );
			$site_wp_password = InstaWP_Setting::get_args_option( 'wp_password', $create_site_res );
			$site_s_hash      = InstaWP_Setting::get_args_option( 's_hash', $create_site_res );

			WP_CLI::success( 'Site created successfully. URL: ' . $site_wp_url );

			// Add migration entry
			$migrate_key         = InstaWP_Tools::get_random_string( 40 );
			$migrate_settings    = InstaWP_Tools::get_migrate_settings( $_POST );
			$migrate_args        = array(
				'site_id'           => $site_id,
				'mode'              => 'local-push',
				'source_connect_id' => instawp()->connect_id,
				'settings'          => $migrate_settings,
				'php_version'       => PHP_VERSION,
				'wp_version'        => $wp_version,
				'plugin_version'    => INSTAWP_PLUGIN_VERSION,
				'migrate_key'       => $migrate_key,
			);
			$migrate_res         = InstaWP_Curl::do_curl( 'migrates-v3/local-push', $migrate_args );
			$migrate_res_status  = (bool) InstaWP_Setting::get_args_option( 'success', $migrate_res, true );
			$migrate_res_message = InstaWP_Setting::get_args_option( 'message', $migrate_res );
			$migrate_res_data    = InstaWP_Setting::get_args_option( 'data', $migrate_res, array() );

			if ( ! $migrate_res_status ) {
				die( $migrate_res_message );
			}

			$migrate_id   = InstaWP_Setting::get_args_option( 'migrate_id', $migrate_res_data );
			$tracking_url = InstaWP_Setting::get_args_option( 'tracking_url', $migrate_res_data );

			WP_CLI::success( "Migration initiated with migrate_id: {$migrate_id}. Tracking URL: {$tracking_url}" );

			// Wait 10 seconds
			sleep( 10 );

			// Upload files and db using SFTP
			if ( is_wp_error( $file_upload_status = InstaWP_Tools::cli_upload_using_sftp( $site_id, $archive_path_file, $archive_path_db ) ) ) {

				// Mark the migration failed
				instawp_update_migration_stages( array( 'failed' => true ), $migrate_id, $migrate_key );

				die( $file_upload_status->get_error_message() );
			}

			// Call restore API to initiate the restore
			if ( is_wp_error( $file_upload_status = InstaWP_Tools::cli_restore_website( $site_id, $archive_path_file, $archive_path_db ) ) ) {

				// Mark the migration failed
				instawp_update_migration_stages( array( 'failed' => true ), $migrate_id, $migrate_key );

				die( $file_upload_status->get_error_message() );
			}

			// Mark the migration failed
			instawp_update_migration_stages( array( 'migration-finished' => true ), $migrate_id, $migrate_key );

			// Finish configuration of the staging website
			$finish_mig_args    = array(
				'site_id'           => $site_id,
				'parent_connect_id' => instawp()->connect_id,
			);
			$finish_mig_res     = InstaWP_Curl::do_curl( 'migrates-v3/finish-local-staging', $finish_mig_args );
			$finish_mig_status  = (bool) InstaWP_Setting::get_args_option( 'success', $finish_mig_res, true );
			$finish_mig_message = InstaWP_Setting::get_args_option( 'message', $finish_mig_res );

			if ( ! $finish_mig_status ) {
				WP_CLI::success( 'Error in configuring the staging website. Error message: ' . $finish_mig_message );
			}

			WP_CLI::success( 'Migration successful.' );
		}

		function handle_instawp_commands( $args ) {

			if ( isset( $args[0] ) && $args[0] === 'local' ) {

				if ( isset( $args[1] ) && $args[1] === 'push' ) {
					$this->cli_local_push();
				}

				return true;
			}

			if ( isset( $args[0] ) && $args[0] === 'set-waas-mode' ) {

				if ( isset( $args[1] ) ) {
					$wp_config = new WPConfig( array(
						'INSTAWP_CONNECT_MODE'     => 'WAAS_GO_LIVE',
						'INSTAWP_CONNECT_WAAS_URL' => $args[1],
					) );
					$wp_config->update();
				}

				return true;
			}

			if ( isset( $args[0] ) && $args[0] === 'reset-waas-mode' ) {
				$wp_config = new WPConfig( array( 'INSTAWP_CONNECT_MODE', 'INSTAWP_CONNECT_WAAS_URL' ) );
				$wp_config->delete();

				return true;
			}

			if ( isset( $args[0] ) && $args[0] === 'config-set' ) {
				if ( isset( $args[1] ) ) {
					if ( $args[1] === 'api-key' ) {
						InstaWP_Setting::instawp_generate_api_key( $args[2], 'true' );
					} elseif ( $args[1] === 'api-domain' ) {
						InstaWP_Setting::set_api_domain( $args[2] );
					}
				}

				if ( isset( $args[3] ) ) {
					$payload_decoded = base64_decode( $args[3] );
					$payload         = json_decode( $payload_decoded, true );

					if ( isset( $payload['mode'] ) ) {
						if ( isset( $payload['mode']['name'] ) ) {
							$wp_config = new WPConfig( array( 'INSTAWP_CONNECT_MODE' => $payload['mode']['name'] ) );
							$wp_config->update();
						}
					}
				}

				return true;
			}

			if ( isset( $args[0] ) && $args[0] === 'config-remove' ) {
				$option = new \InstaWP\Connect\Helpers\Option();
				$option->delete( array( 'instawp_api_options', 'instawp_connect_id_options' ) );

				return true;
			}

			if ( isset( $args[0] ) && $args[0] === 'hard-reset' ) {
				instawp_reset_running_migration( 'hard', false );

				return true;
			}

			if ( isset( $args[0] ) && $args[0] === 'staging-set' && ! empty( $args[1] ) ) {
				InstaWP_Setting::update_option( 'instawp_sync_connect_id', intval( $args[1] ) );
				InstaWP_Setting::update_option( 'instawp_is_staging', true );
				instawp_get_source_site_detail();

				return true;
			}

			if ( isset( $args[0] ) && $args[0] === 'reset' ) {
				if ( isset( $args[1] ) && $args[1] === 'staging' ) {
					delete_option( 'instawp_sync_connect_id' );
					delete_option( 'instawp_is_staging' );
					instawp_reset_running_migration();
				}
			}

			return true;
		}

		/**
		 * Add CLI Commands
		 *
		 * @return void
		 * @throws Exception
		 */
		public function add_wp_cli_commands() {

			WP_CLI::add_command( 'instawp', array( $this, 'handle_instawp_commands' ) );
		}

		/**
		 * @return INSTAWP_CLI_Commands
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
}

INSTAWP_CLI_Commands::instance();


