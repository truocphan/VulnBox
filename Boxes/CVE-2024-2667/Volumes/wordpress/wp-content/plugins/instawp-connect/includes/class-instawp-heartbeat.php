<?php
/**
 * Class for heartbeat
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'InstaWP_Heartbeat' ) ) {
	class InstaWP_Heartbeat {

		public function __construct() {
			add_action( 'init', array( $this, 'register_events' ) );
			add_action( 'add_option_instawp_api_heartbeat', array( $this, 'clear_heartbeat_action' ) );
			add_action( 'update_option_instawp_api_heartbeat', array( $this, 'clear_heartbeat_action' ) );
			add_action( 'add_option_instawp_rm_heartbeat', array( $this, 'clear_heartbeat_action' ) );
			add_action( 'update_option_instawp_rm_heartbeat', array( $this, 'clear_heartbeat_action' ) );
			add_action( 'instawp_handle_heartbeat', array( $this, 'send_heartbeat_data' ) );
			add_action( 'instawp_send_heartbeat', array( $this, 'send_heartbeat_data' ) );
			add_action( 'instawp_handle_heartbeat_status', array( $this, 'handle_heartbeat_status' ) );
		}

		public function register_events() {
			if ( ! as_has_scheduled_action( 'instawp_send_heartbeat', array(), 'instawp-connect' ) ) {
				as_schedule_recurring_action( time(), DAY_IN_SECONDS, 'instawp_send_heartbeat', array(), 'instawp-connect' );
			}

			if ( ! as_has_scheduled_action( 'instawp_handle_heartbeat_status', array(), 'instawp-connect' ) ) {
				as_schedule_recurring_action( time(), DAY_IN_SECONDS, 'instawp_handle_heartbeat_status', array(), 'instawp-connect' );
			}

			$heartbeat = InstaWP_Setting::get_option( 'instawp_rm_heartbeat', 'on' );
			$heartbeat = empty( $heartbeat ) ? 'on' : $heartbeat;

			if ( 'on' === $heartbeat ) {
				$interval = InstaWP_Setting::get_option( 'instawp_api_heartbeat', 15 );
				$interval = empty( $interval ) ? 15 : (int) $interval;

				if ( ! as_has_scheduled_action( 'instawp_handle_heartbeat', array(), 'instawp-connect' ) ) {
					as_schedule_recurring_action( time(), ( $interval * MINUTE_IN_SECONDS ), 'instawp_handle_heartbeat', array(), 'instawp-connect' );
				}
			}
		}

		public function clear_heartbeat_action() {
			as_unschedule_all_actions( 'instawp_handle_heartbeat', array(), 'instawp-connect' );
		}

		public function send_heartbeat_data() {
			self::send_heartbeat();
		}

		public function handle_heartbeat_status() {
			$disabled = get_option( 'instawp_rm_heartbeat_failed' );
			if ( ! $disabled ) {
				return;
			}

			if ( self::send_heartbeat() ) {
				InstaWP_Setting::update_option( 'instawp_rm_heartbeat', 'on' );
			}
		}

		public static function prepare_data() {
			if ( ! class_exists( 'WP_Debug_Data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
			}

			$sizes_data   = WP_Debug_Data::get_sizes();
			$wp_version   = get_bloginfo( 'version' );
			$php_version  = phpversion();
			$active_theme = wp_get_theme()->get( 'Name' );
			$count_posts  = wp_count_posts();
			$posts        = $count_posts->publish;
			$count_pages  = wp_count_posts( 'page' );
			$pages        = $count_pages->publish;
			$count_users  = count_users();
			$users        = $count_users['total_users'];

			$post_data = array();
			foreach ( get_post_types() as $post_type ) {
				$post_data[ $post_type ] = ( array ) wp_count_posts( $post_type );
			}

			$inventory = new \InstaWP\Connect\Helpers\Inventory();
			$site_data = $inventory->fetch();

			return array(
				'wp_version'        => $wp_version,
				'php_version'       => $php_version,
				'plugin_version'    => INSTAWP_PLUGIN_VERSION,
				'total_size'        => $sizes_data['total_size']['raw'],
				'file_size'         => $sizes_data['total_size']['raw'] - $sizes_data['database_size']['raw'],
				'db_size'           => $sizes_data['database_size']['raw'],
				'theme'             => $active_theme,
				'posts'             => $posts,
				'pages'             => $pages,
				'users'             => $users,
				'core'              => $site_data['core'],
				'themes'            => $site_data['theme'],
				'plugins'           => $site_data['plugin'],
				'mu_plugins'        => $site_data['mu_plugin'],
				'consolidated_data' => array(
					'users' => $count_users['avail_roles'],
					'posts' => $post_data,
				),
			);
		}

		public static function send_heartbeat( $connect_id = null ) {
			global $wpdb;

			if ( defined( 'INSTAWP_DEBUG_LOG' ) && true === INSTAWP_DEBUG_LOG ) {
				error_log( "HEARTBEAT RAN AT : " . date( 'd-m-Y, H:i:s, h:i:s' ) );
			}

			if ( empty( $connect_id ) ) {
				$connect_id = instawp()->connect_id;
			}

			$last_sent_data = get_option( 'instawp_heartbeat_sent_data', array() );
			$heartbeat_data = self::prepare_data();

			$setting = InstaWP_Setting::get_option( 'instawp_activity_log', 'off' );
			if ( $setting === 'on' ) {
				$log_ids    = $logs = array();
				$table_name = INSTAWP_DB_TABLE_ACTIVITY_LOGS;
				$results    = $wpdb->get_results(
					$wpdb->prepare( "SELECT * FROM {$table_name} WHERE severity!=%s", 'critical' )
				);

				foreach ( $results as $result ) {
					$logs[ $result->action ] = array(
						'data_type' => current( explode( '_', $result->action ) ),
						'count'     => ! empty( $logs[ $result->action ]['count'] ) ? $logs[ $result->action ]['count'] + 1 : 1,
						'meta'      => array(),
						'data'      => array( ( array ) $result ),
					);
					$log_ids[]               = $result->id;
				}

				$heartbeat_data['activity_logs'] = $logs;
			}

			$heartbeat_body = base64_encode( wp_json_encode( array(
				'site_information' => $heartbeat_data,
				'new_changes'      => instawp_array_recursive_diff( $heartbeat_data, $last_sent_data ),
			) ) );

			$success = false;
			for ( $i = 0; $i < 10; $i ++ ) {
				$heartbeat_response = InstaWP_Curl::do_curl( "connects/{$connect_id}/heartbeat", $heartbeat_body, array(), true, 'v1' );
				$response_code      = InstaWP_Setting::get_args_option( 'code', $heartbeat_response );

				if ( $response_code == 200 ) {
					$success = true;
					break;
				}
			}

			if ( ! $success ) {
				InstaWP_Setting::update_option( 'instawp_rm_heartbeat', 'off' );
				InstaWP_Setting::update_option( 'instawp_rm_heartbeat_failed', true );
				as_unschedule_all_actions( 'instawp_handle_heartbeat', array(), 'instawp-connect' );

				if ( $response_code == 404 ) {
					instawp_reset_running_migration( 'hard' );
				}
			} else {
				delete_option( 'instawp_rm_heartbeat_failed' );
				InstaWP_Setting::update_option( 'instawp_heartbeat_sent_data', $heartbeat_data );

				if ( $setting === 'on' ) {
					$placeholders = implode( ',', array_fill( 0, count( $log_ids ), '%d' ) );
					$wpdb->query(
						$wpdb->prepare( "DELETE FROM {$table_name} WHERE id IN ($placeholders)", $log_ids )
					);
				}
			}

			if ( defined( 'INSTAWP_DEBUG_LOG' ) && INSTAWP_DEBUG_LOG ) {
				error_log( "Print Heartbeat API Curl Response Start" );
				error_log( wp_json_encode( $heartbeat_response, true ) );
				error_log( "Print Heartbeat API Curl Response End" );
			}

			return $success;
		}
	}
}

new InstaWP_Heartbeat();
