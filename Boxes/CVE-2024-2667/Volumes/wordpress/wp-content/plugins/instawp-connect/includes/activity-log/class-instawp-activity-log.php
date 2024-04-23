<?php
/**
 * Class for heartbeat
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'InstaWP_Activity_Log' ) ) {
	class InstaWP_Activity_Log {

		private $table_name;
		private $wpdb;

		public function __construct() {
			global $wpdb;

			$this->wpdb       = $wpdb;
			$this->table_name = INSTAWP_DB_TABLE_ACTIVITY_LOGS;

			add_action( 'init', array( $this, 'create_table' ) );
			add_action( 'instawp_handle_critical_logs', array( $this, 'send_data' ) );
		}

		public function send_data() {
			$connect_id = instawp_get_connect_id();
			if ( ! $connect_id ) {
				return;
			}

			$log_ids    = $logs = array();
			$table_name = INSTAWP_DB_TABLE_ACTIVITY_LOGS;
			$results    = $this->wpdb->get_results(
				$this->wpdb->prepare( "SELECT * FROM {$table_name} WHERE severity=%s", 'critical' )
			);

			foreach ( $results as $result ) {
				$logs[ $result->action ] = array(
					'data_type' => current( explode( '_', $result->action ) ),
					'count'     => ! empty( $logs[ $result->action ]['count'] ) ? $logs[ $result->action ]['count'] + 1 : 1,
					'meta'      => array(),
					'data'      => array( ( array ) $result ),
				);
				$log_ids[] = $result->id;
			}

			$success = false;
			for ( $i = 0; $i < 10; $i ++ ) {
				$response = InstaWP_Curl::do_curl( "connects/{$connect_id}/activity-log", array( 'activity_logs' => $logs ) );
				if ( $response['code'] == 200 ) {
					$success = true;
					break;
				}
			}

			if ( $success ) {
				$placeholders = implode( ',', array_fill( 0, count( $log_ids ), '%d' ) );
				$this->wpdb->query(
					$this->wpdb->prepare( "DELETE FROM {$table_name} WHERE id IN ($placeholders)", $log_ids )
				);
			}
		}

		public function create_table() {
			$charset_collate = $this->wpdb->get_charset_collate();

			if ( ! function_exists( 'maybe_create_table' ) ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}

			$sql_query  = "CREATE TABLE " . $this->table_name . " (
				id int(20) NOT NULL AUTO_INCREMENT,
				action varchar(255) NOT NULL,
				severity varchar(20) NOT NULL DEFAULT 'low',
				object_type varchar(255) NOT NULL,
				object_subtype varchar(255) NOT NULL DEFAULT '',
				object_id varchar(50) NOT NULL DEFAULT '0',
				object_name varchar(50) NOT NULL,
				user_id int(50) NOT NULL DEFAULT '0',
				user_name varchar(255) NOT NULL DEFAULT '',
				user_caps varchar(70) NOT NULL DEFAULT 'guest',
				user_ip varchar(55) NOT NULL DEFAULT '127.0.0.1',
				timestamp datetime NOT NULL,
				PRIMARY KEY (id)
	        ) $charset_collate;";

			maybe_create_table( $this->table_name, $sql_query );
		}

		/**
		 * @param array $args
		 * @return void
		 */
		private function insert( array $args ) {
			$args = wp_parse_args( $args, array(
				'action'         => '',
				'object_type'    => '',
				'object_subtype' => '',
				'object_name'    => '',
				'object_id'      => '',
				'user_ip'        => $this->get_ip_address(),
				'timestamp'      => current_time( 'mysql', 1 ),
			) );

			$args['severity'] = $this->get_severity( $args['action'] );
			$args             = $this->setup_userdata( $args );

			$this->wpdb->insert(
				$this->table_name,
				array(
					'action'         => $args['action'],
					'severity'       => $args['severity'],
					'object_type'    => $args['object_type'],
					'object_subtype' => $args['object_subtype'],
					'object_name'    => $args['object_name'],
					'object_id'      => $args['object_id'],
					'user_id'        => $args['user_id'],
					'user_name'      => $args['user_name'],
					'user_caps'      => $args['user_caps'],
					'user_ip'        => $args['user_ip'],
					'timestamp'      => $args['timestamp'],
				),
				array( '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s' )
			);

			if ( 'critical' === $args['severity'] ) {
				wp_unschedule_hook( 'instawp_handle_critical_logs' );
				wp_schedule_single_event( time() + 10, 'instawp_handle_critical_logs' );
			}
		}

		private function setup_userdata( array $args ) {
			$user = function_exists( 'get_user_by' ) ? get_user_by( 'id', get_current_user_id() ) : false;

			if ( $user ) {
				$args['user_caps'] = strtolower( key( $user->caps ) );
				$args['user_name'] = ! empty( $user->user_login ) ? $user->user_login : $user->display_name;
				if ( empty( $args['user_id'] ) ) {
					$args['user_id'] = $user->ID;
				}
			} else {
				$args['user_caps'] = 'guest';
				$args['user_name'] = '';
				if ( empty( $args['user_id'] ) ) {
					$args['user_id'] = 0;
				}
			}

			if ( empty( $args['user_caps'] ) || 'bbp_participant' === $args['user_caps'] ) {
				$args['user_caps'] = 'administrator';
			}

			return $args;
		}

		private function get_severity( $action ) {
			$severity_list = $this->event_severity();
			$severity      = 'low';

			foreach ( array_keys( $severity_list ) as $severity_item ) {
				if ( in_array( $action, $severity_list[ $severity_item ], true ) ) {
					$severity = $severity_item;
					break;
				}
			}

			return $severity;
		}

		private function event_severity() {
			return array(
				'low'      => array(
					'post_updated',
					'attachment_uploaded',
					'menu_created',
					'menu_updated',
					'user_logged_in',
					'user_logged_out',
					'term_created',
					'term_updated',
					'theme_installed',
					'theme_updated',
					'widget_updated',
				),
				'medium'   => array(
					'post_restored',
					'attachment_updated',
					'user_updated',
					'theme_updated',
					'widget_deleted',
					'plugin_activated',
					'plugin_deactivated',
				),
				'high'     => array(
					'post_created',
					'post_trashed',
					'attachment_deleted',
					'user_registered',
					'user_failed_login',
					'menu_deleted',
					'term_deleted',
					'theme_file_updated',
					'theme_activated',
					'plugin_installed',
					'plugin_updated',
					'plugin_file_updated',
				),
				'critical' => array(
					'post_deleted',
					'user_deleted',
					'theme_deleted',
					'plugin_deleted',
				),
			);
		}

		private function get_ip_address() {
			$header_key = InstaWP_Setting::get_option( 'instawp_log_visitor_ip_source' );

			if ( empty( $header_key ) ) {
				$header_key = 'no-collect-ip';
			}

			if ( 'no-collect-ip' === $header_key ) {
				return '';
			}

			$visitor_ip_address = '';
			if ( ! empty( $_SERVER[ $header_key ] ) ) {
				$visitor_ip_address = $_SERVER[ $header_key ];
			}

			$remote_address = apply_filters( 'INSTAWP_CONNECT/Filters/get_ip_address', $visitor_ip_address );

			if ( ! empty( $remote_address ) && filter_var( $remote_address, FILTER_VALIDATE_IP ) ) {
				return $remote_address;
			}

			return '127.0.0.1';
		}

		public static function insert_log( $args ) {
			$class = new self();

			$class->insert( $args );
		}
	}
}

new InstaWP_Activity_Log();