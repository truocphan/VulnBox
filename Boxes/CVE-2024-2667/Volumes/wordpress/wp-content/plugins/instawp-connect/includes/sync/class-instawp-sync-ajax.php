<?php
/**
 * @link       https://instawp.com/
 * @since      1.0
 *
 * @package    instawp
 * @subpackage instawp/includes
 */

defined( 'ABSPATH' ) || exit;

class InstaWP_Sync_Ajax {

	private $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;

		#The wp_ajax_ hook only fires for logged-in users
		add_action( 'wp_ajax_instawp_is_event_syncing', array( $this, 'is_event_syncing' ) );
		add_action( 'wp_ajax_instawp_get_site_events', array( $this, 'get_site_events' ) );
		add_action( 'wp_ajax_instawp_handle_select2', array( $this, 'handle_select2' ) );
		add_action( 'wp_ajax_instawp_pack_events', array( $this, 'pack_events' ) );
		add_action( 'wp_ajax_instawp_sync_changes', array( $this, 'sync_changes' ) );
		add_action( 'wp_ajax_instawp_get_events_summary', array( $this, 'get_events_summary' ) );
		add_action( 'wp_ajax_instawp_delete_events', array( $this, 'delete_events' ) );
		add_action( 'wp_ajax_instawp_calculate_events', array( $this, 'calculate_events' ) );
	}

	public function is_event_syncing() {
		check_ajax_referer( 'instawp-connect', 'security' );

		if ( ! current_user_can( InstaWP_Setting::get_allowed_role() ) ) {
			$this->send_error( 'Can\'t perform this action.' );
		}

		$sync_status = ! empty( $_POST['sync_status'] ) ? intval( $_POST['sync_status'] ) : 0;
		InstaWP_Setting::update_option( 'instawp_is_event_syncing', $sync_status );

		instawp_create_db_tables();

		$message = ( $sync_status == 1 ) ? 'Syncing enabled!' : 'Syncing disabled!';
		wp_send_json( array(
			'sync_status' => $sync_status,
			'message'     => $message,
		) );
	}

	public function get_site_events() {
		check_ajax_referer( 'instawp-connect', 'security' );

		if ( ! current_user_can( InstaWP_Setting::get_allowed_role() ) ) {
			$this->send_error( 'Can\'t perform this action.' );
		}

		instawp_create_db_tables();

		$connect_id     = ! empty( $_POST['connect_id'] ) ? intval( $_POST['connect_id'] ) : 0;
		$filter_status  = ! empty( $_POST['filter_status'] ) ? sanitize_text_field( $_POST['filter_status'] ) : 'all';
		$items_per_page = 20;

		$query        = "SELECT * FROM " . INSTAWP_DB_TABLE_EVENTS;
		$staging_site = instawp_get_site_detail_by_connect_id( $connect_id, 'data' );

		if ( ! empty( $staging_site ) && isset( $staging_site['created_at'] ) ) {
			$staging_site_created = date( 'Y-m-d h:i:s', strtotime( $staging_site['created_at'] ) );
			$query                .= " WHERE `date` >= '" . $staging_site_created . "'";
		}

		$total_query = "SELECT COUNT(1) FROM ({$query}) AS combined_table ";
		$total       = $this->wpdb->get_var( $total_query );

		$page   = isset( $_POST['epage'] ) ? abs( (int) $_POST['epage'] ) : 1;
		$offset = ( $page * $items_per_page ) - $items_per_page;

		$events = $this->wpdb->get_results( $query . " ORDER BY `date` DESC, `id` DESC LIMIT {$offset}, {$items_per_page}" );
		$events = array_map( function( $event ) use ( $connect_id ) {
			$event_row = InstaWP_Sync_DB::get_sync_event_by_id( $connect_id, $event->event_hash );

			if ( $event_row ) {
				$event->status      = ! empty( $event_row->status ) ? $event_row->status : 'pending';
				$event->synced_date = ! empty( $event_row->date ) ? $event_row->date : $event->date;

				if ( $event->status === 'completed' ) {
					$event->synced_message = ! empty( $event_row->synced_message ) ? $event_row->synced_message : $event->synced_message;
					$event->log            = ! empty( $event_row->log ) ? $event_row->log : '';
				}
			}

			return $event;
		}, $events );

		if ( $filter_status !== 'all' ) {
			$events = array_filter( $events, function ( $event ) use ( $filter_status ) {
				return $filter_status === $event->status;
			} );
		}

		$totalPage = ceil( $total / $items_per_page );

		ob_start();
		include INSTAWP_PLUGIN_DIR . '/migrate/templates/ajax/part-sync-items.php';
		$data = ob_get_contents();
		ob_end_clean();

		$this->send_success( 'Event fetched.', array(
			'results'    => $data,
			'pagination' => $this->get_events_sync_list_pagination( $total, $items_per_page, $page ),
		) );
	}

	public function handle_select2() {
		if ( isset( $_GET['event'] ) ) {
			if ( $_GET['event'] == 'instawp_get_users' ) {
				$keyword = ! empty( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';
				$args    = array(
					'search'         => $keyword,
					'paged'          => 1,
					'search_columns' => array( 'user_login', 'user_nicename', 'user_email' ),
					'fields'         => array( 'id', 'user_login' ),
				);
				$users   = get_users( $args );
				$this->send_success( "Users loaded", array(
					'results' => $users,
					'opt_col' => array(
						'text' => 'user_login',
						'id'   => 'ID',
					),
				) );
			} elseif ( $_GET['event'] == 'instawp_sync_tab_roles' ) {

				$results   = array();
				$all_roles = wp_roles()->roles;
				foreach ( $all_roles as $slug => $role ) {
					$results[] = array(
						'id'   => $slug,
						'name' => $role['name'],
					);
				}
				$this->send_success( "Users loaded", array(
					'results' => $results,
					'opt_col' => array(
						'text' => 'name',
						'id'   => 'id',
					),
				) );
			}
		}
	}

	public function pack_events() {
		check_ajax_referer( 'instawp-connect', 'security' );

		if ( ! current_user_can( InstaWP_Setting::get_allowed_role() ) ) {
			$this->send_error( 'Can\'t perform this action.' );
		}

		try {
			$events = $this->pack_pending_sync_events();
			if ( ! empty( $events ) ) {
				$data = array();
				foreach ( $events as $row ) {
					if ( ! empty( $row->event_type ) ) {
						$count                    = isset( $data[ $row->event_type ] ) ? $data[ $row->event_type ] : 0;
						$data[ $row->event_type ] = $count + 1;
					}
				}
				$data['total_events'] = count( $events );
				$this->send_success( 'The data has packed successfully as JSON from WP DB', $data );
			} else {
				$this->send_error( 'The events are not available' );
			}
		} catch ( Exception $e ) {
			$this->send_error( 'Caught Exception: ' . $e->getMessage() );
		}
	}

	public function sync_changes() {
		check_ajax_referer( 'instawp-connect', 'security' );

		if ( ! current_user_can( InstaWP_Setting::get_allowed_role() ) ) {
			$this->send_error( 'Can\'t perform this action.' );
		}

		$dest_connect_id = ! empty( $_POST['dest_connect_id'] ) ? intval( $_POST['dest_connect_id'] ) : '';
		if ( empty( $dest_connect_id ) ) {
			$this->send_error( 'Invalid destination.' );
		}

		$message = isset( $_POST['sync_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sync_message'] ) ) : '';
		$data    = wp_unslash( $_POST['data'] );
		$events  = $this->get_wp_events();

		if ( isset( $events['success'] ) && $events['success'] === true ) {
			$packed_data = array(
				'encrypted_content' => wp_json_encode( $events['data']['contents'] ),
				'dest_connect_id'   => $dest_connect_id,
				'changes'           => $data,
				'upload_wp_user'    => get_current_user_id(),
				'sync_message'      => $message,
				'source_connect_id' => instawp()->connect_id,
				'source_url'        => get_site_url(),
			);

			$response = $this->sync_upload( $packed_data );
			if ( ! isset( $response['success'] ) || $response['success'] !== true ) {
				$this->send_error( $response['message'] );
			}

			$sync_id = ! empty( $response['data']['sync_id'] ) ? $response['data']['sync_id'] : '';
			if ( empty( $sync_id ) ) {
				$this->send_error( 'Sync ID missing!' );
			}

			$this->update_sync_events_status( $dest_connect_id, $sync_id );

			$batch_data      = InstaWP_Setting::get_option( 'instawp_event_batch_data' );
			$total_completed = $batch_data['total_completed'] + count( $events['data']['contents'] );
			$percentage      = round( ( $batch_data['current_batch'] * 100 ) / intval( $batch_data['total_batch'] ) );
			$next_batch      = $batch_data['current_batch'] + 1;

			$result = array(
				'count'             => $batch_data['total_events'],
				'current_batch'     => $batch_data['current_batch'],
				'next_batch'        => $next_batch,
				'total_completed'   => $total_completed,
				'percent_completed' => $percentage,
				'per_batch'         => INSTAWP_EVENTS_SYNC_PER_PAGE,
				'total_batch'       => intval( $batch_data['total_batch'] ),
				'progress_text'     => $percentage . '%' . sprintf( " Completed ( %u out of %s events)", $total_completed, intval( $batch_data['total_events'] ) ),
			);

			$batch_data['current_batch']   = $next_batch;
			$batch_data['total_completed'] = $total_completed;

			InstaWP_Setting::update_option( 'instawp_event_batch_data', $batch_data );

			$this->send_success( $response['message'], $result );
		} else {
			$this->send_error( 'No pending events found!' );
		}
	}

	public function get_events_summary() {
		check_ajax_referer( 'instawp-connect', 'security' );

		if ( ! current_user_can( InstaWP_Setting::get_allowed_role() ) ) {
			$this->send_error( 'Can\'t perform this action.' );
		}

		$where  = "`status`='completed'";
		$where2 = array();
		$connect_id = ! empty( $_POST['connect_id'] ) ? intval( $_POST['connect_id'] ) : 0;
		$entry_ids  = ! empty( $_POST['ids'] ) ? array_map( 'intval', explode( ',', $_POST['ids'] ) ) : array();

		if ( $connect_id > 0 ) {
			$where        .= " AND `connect_id`=" . $connect_id;
			$staging_site = instawp_get_site_detail_by_connect_id( $connect_id, 'data' );

			if ( ! empty( $staging_site ) && isset( $staging_site['created_at'] ) && ! instawp()->is_staging ) {
				$staging_site_created = date( 'Y-m-d h:i:s', strtotime( $staging_site['created_at'] ) );
				$where2[]             = "`date` >= '" . $staging_site_created . "'";
			}
		}

		if ( ! empty( $entry_ids ) ) {
			$entry_ids = join( ', ', $entry_ids );
			$where2[]  = " `id` IN($entry_ids)";
		}

		$where2 = empty( $where2 ) ? "1=1" : join( ' AND ', $where2 );

		$query   = "SELECT event_name, COUNT(*) as event_count FROM " . INSTAWP_DB_TABLE_EVENTS . " WHERE $where2 AND `event_hash` NOT IN (SELECT event_hash AS id FROM " . INSTAWP_DB_TABLE_EVENT_SITES . " WHERE $where) GROUP BY event_name HAVING event_count > 0";
		$results = $this->wpdb->get_results( $query );

		$html = '<ul class="list">';
		if ( ! empty( $results ) ) {
			foreach ( $results as $i => $row ) {
				$html .= '<li class="event-type-count ' . ( $i > 2 ? 'hidden' : '' ) . '">';
				$html .= sprintf( __( '%1$u %2$s', 'instawp-connect' ), $row->event_count, ucfirst( str_replace( "_", " ", $row->event_name ) ) );
				$html .= '</li>';
			}

			$html .= '<li class="event-type-count-show-more" style="display:none">';
			$html .= '<a href="javascript:void(0)" class="load-more-event-type">' . esc_html( __( 'Show more', 'instawp-connect' ) ) . '</a>';
			$html .= '</li>';

		} else {
			$results = array( 'Post', 'Page', 'Theme', 'Plugin' );
			foreach ( $results as $row ) {
				$html .= '<li class="event-type-count">';
				$html .= sprintf( __( '%1$u %2$s %3$s', 'instawp-connect' ), 0, $row, in_array( $row, array( 'Page', 'Post' ) ) ? 'modified' : 'installed' );
				$html .= '</li>';
			}
		}
		$html .= '</ul>';

		delete_option( 'instawp_event_batch_data' );

		$total_events = $this->get_total_pending_events_count();

		$this->send_success( 'Summery fetched', array(
			'html'          => $html,
			'count'         => $total_events,
			'progress_text' => sprintf(
				_n(
					__( 'Waiting for Sync to Start (%d event)', 'instawp-connect' ),
					__( 'Waiting for Sync to Start (%d events)', 'instawp-connect' ),
					$total_events
				),
				$total_events
			),
			'message'       => $total_events > 0 ? __( 'Events loaded', 'instawp-connect' ) : __( 'No pending events found!', 'instawp-connect' ),
		) );
	}

	public function delete_events() {
		check_ajax_referer( 'instawp-connect', 'security' );

		if ( ! current_user_can( InstaWP_Setting::get_allowed_role() ) ) {
			$this->send_error( 'Can\'t perform this action.' );
		}

		if ( isset( $_POST['ids'] ) && ! empty( $_POST['ids'] ) ) {
			global $wpdb;

			$ids = array_map( 'intval', explode(',', wp_unslash( $_POST['ids'] ) ) );
			$ids = join(',', $ids);

			$wpdb->query( "DELETE FROM " . INSTAWP_DB_TABLE_EVENTS . " WHERE id IN($ids)" );
			if ( isset( $_POST['connect_id'] ) && intval( $_POST['connect_id'] ) > 0 ) {
				$wpdb->query( "DELETE FROM " . INSTAWP_DB_TABLE_EVENT_SITES . " WHERE event_id IN($ids)" );
			}
			$this->send_success( 'Data deleted' );
		}
	}

	public function calculate_events() {
		check_ajax_referer( 'instawp-connect', 'security' );

		if ( ! current_user_can( InstaWP_Setting::get_allowed_role() ) ) {
			$this->send_error( 'Can\'t perform this action.' );
		}

		$total_events = $this->get_total_pending_events_count();

		if ( $total_events > 0 ) {
			delete_option( 'instawp_event_batch_data' );

			$sync_quota_response = $this->get_connect_quota_remaining_limit();
			if ( ! empty( $sync_quota_response ) ) {
				if ( $sync_quota_response['remaining'] >= $total_events ) {
					$batch_data = array(
						'per_batch'         => INSTAWP_EVENTS_SYNC_PER_PAGE,
						'total_batch'       => ceil( $total_events / INSTAWP_EVENTS_SYNC_PER_PAGE ),
						'total_events'      => $total_events,
						'current_batch'     => 1,
						'percent_completed' => 0,
						'total_completed'   => 0,
					);
					InstaWP_Setting::update_option( 'instawp_event_batch_data', $batch_data );

					$this->send_success( 'Event fetched.', array(
						'count'         => $total_events,
						'page'          => 1,
						'per_page'      => INSTAWP_EVENTS_SYNC_PER_PAGE,
						'progress_text' => '0%' . sprintf( __( ' Completed ( 0 out of %d events )', 'instawp-connect' ), $total_events ),
					) );
				} else {
					$this->send_error( sprintf( __( 'You have reached your sync limit. Current usage %1$u out of %2$u.', 'instawp-connect' ), $sync_quota_response['remaining'], $sync_quota_response['sync_quota_limit'] ) );
				}
			}
		} else {
			$this->send_error( __( 'No pending events found!', 'instawp-connect' ) );
		}
	}

	private function send_success( $message, $data = array() ) {
		wp_send_json( array(
			'success' => true,
			'message' => $message,
			'data'    => $data,
		) );
	}

	private function send_error( $message = 'Something went wrong' ) {
		wp_send_json( array(
			'success' => false,
			'message' => $message,
		) );
	}

	private function update_sync_events_status( $connect_id, $sync_id ) {
		try {
			$response = $this->get_sync_object( $sync_id );
			if ( $response['success'] === true ) {
				$sync_response = isset( $response['data']['changes']['changes']['sync_response'] ) ? $response['data']['changes']['changes']['sync_response'] : array();
				foreach ( $sync_response as $data ) {
					InstaWP_Sync_DB::insert( INSTAWP_DB_TABLE_EVENT_SITES, array(
						'event_id'       => $data['id'],
						'event_hash'     => $data['hash'],
						'connect_id'     => $connect_id,
						'status'         => $data['status'],
						'synced_message' => $data['message'],
						'date'           => current_time( 'mysql', 1 ),
					) );
				}
			}

			return $response;
		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'message' => 'Caught Exception: ' . $e->getMessage(),
			);
		}
	}

	private function get_events_sync_list_pagination( $total, $items_per_page, $page ) {
		return paginate_links( array(
			'base'      => '%_%',
			'format'    => '?page=instawp&epage=%#%',
			'prev_text' => __( '« Previous' ),
			'next_text' => __( 'Next »' ),
			'show_all'  => false,
			'total'     => ceil( $total / $items_per_page ),
			'current'   => $page,
			'type'      => 'plain',
			'prev_next' => true,
			'class'     => 'instawp_sync_event_pagination',
		) );
	}

	private function get_total_pending_events_count() {
		$where  = "`status`='completed'";
		$where2 = array();
		$connect_id = ! empty( $_POST['connect_id'] ) ? intval( $_POST['connect_id'] ) : 0;
		$entry_ids  = ! empty( $_POST['ids'] ) ? array_map( 'intval', explode( ',', $_POST['ids'] ) ) : array();

		if ( $connect_id > 0 ) {
			$where        .= " AND connect_id=" . $connect_id;
			$staging_site = instawp_get_site_detail_by_connect_id( $connect_id, 'data' );

			if ( ! empty( $staging_site ) && isset( $staging_site['created_at'] ) && ! instawp()->is_staging ) {
				$staging_site_created = date( 'Y-m-d h:i:s', strtotime( $staging_site['created_at'] ) );
				$where2[]             = "`date` >= '" . $staging_site_created . "'";
			}
		}

		if ( ! empty( $entry_ids ) ) {
			$entry_ids = join( ', ', $entry_ids );
			$where2[]  = " `id` IN($entry_ids)";
		}

		$where2 = empty( $where2 ) ? "1=1" : join( ' AND ', $where2 );

		$query = "SELECT COUNT(1) FROM " . INSTAWP_DB_TABLE_EVENTS . " WHERE $where2 AND `event_hash` NOT IN (SELECT event_hash AS id FROM " . INSTAWP_DB_TABLE_EVENT_SITES . " WHERE $where)";

		return $this->wpdb->get_var( $query );
	}

	private function sync_upload( $data = null ) {
		$connect_id = instawp_get_connect_id();

		// connects/<connect_id>/syncs
		return InstaWP_Curl::do_curl( "connects/{$connect_id}/syncs", $data );
	}

	private function get_sync_object( $sync_id = null ) {
		$connect_id = instawp_get_connect_id();

		// connects/<connect_id>/syncs
		return InstaWP_Curl::do_curl( "connects/{$connect_id}/syncs/{$sync_id}", array(), array(), false );
	}

	private function get_connect_quota_remaining_limit() {
		$connect_id   = instawp_get_connect_id();

		// connects/<connect_id>/get-sync-quota
		$api_response = InstaWP_Curl::do_curl( "connects/{$connect_id}/get-sync-quota", array(), array(), false );

		if ( $api_response['success'] && ! empty( $api_response['data'] ) ) {
			return $api_response['data'];
		}

		return false;
	}

	private function pack_pending_sync_events() {
		$where  = "`status`='completed'";
		$where2 = "1=1";
		$connect_id = ! empty( $_POST['dest_connect_id'] ) ? intval( $_POST['dest_connect_id'] ) : 0;
		$entry_ids  = ! empty( $_POST['ids'] ) ? array_map( 'intval', explode( ',', $_POST['ids'] ) ) : array();

		if ( $connect_id > 0 ) {
			$where .= " AND `connect_id`=" . $connect_id;
		}

		if ( ! empty( $entry_ids ) ) {
			$entry_ids = join( ',', $entry_ids );
			$where2    .= " AND `id` IN($entry_ids)";
		}

		$query = "SELECT * FROM " . INSTAWP_DB_TABLE_EVENTS . " WHERE $where2 AND `event_hash` NOT IN (SELECT event_hash AS id FROM " . INSTAWP_DB_TABLE_EVENT_SITES . " WHERE $where) ORDER BY date ASC, id ASC LIMIT " . INSTAWP_EVENTS_SYNC_PER_PAGE;

		return $this->wpdb->get_results( $query );
	}

	private function get_wp_events() {
		try {
			$encrypted_content = array();
			$events            = $this->pack_pending_sync_events();
			$output            = array(
				'success' => false,
				'message' => '',
			);

			if ( ! empty( $events ) && is_array( $events ) ) {
				foreach ( $events as $k => $v ) {
					$event_hash = $v->event_hash;

					if ( empty( $event_hash ) ) {
						$event_hash = InstaWP_Tools::get_random_string();
						$this->wpdb->update( INSTAWP_DB_TABLE_EVENT_SYNC_LOGS, array( 'event_hash' => $event_hash ), array( 'id' => $v->id ) );
					}

					$encrypted_content[] = array(
						'id'         => $v->id,
						'event_hash' => $event_hash,
						'details'    => json_decode( $v->details ),
						'event_name' => $v->event_name,
						'event_slug' => $v->event_slug,
						'event_type' => $v->event_type,
						'source_id'  => $v->source_id,
						'user_id'    => $v->user_id,
					);
				}

				if ( count( $encrypted_content ) > 0 ) {
					$output['success']          = true;
					$output['message']          = 'The data has packed successfully as JSON from WP DB';
					$output['data']['contents'] = $encrypted_content;
				} else {
					$output['message'] = 'No pending events found!';
				}
			}
		} catch ( Exception $e ) {
			$output['message'] = "Caught Exception: " . $e->getMessage();
		}

		return $output;
	}
}

new InstaWP_Sync_Ajax();