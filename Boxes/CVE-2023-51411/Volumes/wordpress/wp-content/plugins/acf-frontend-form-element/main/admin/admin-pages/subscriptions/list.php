<?php 
namespace Frontend_Admin\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
 

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/class-wp-list-table.php' );
}

if( ! class_exists( 'Frontend_Admin\Admin\Subscriptions_List' ) ) :

	class Subscriptions_List extends \WP_List_Table {

		/** Class constructor */
		public function __construct() {

			parent::__construct( [
				'singular' => __( 'Subscription', 'acf-frontend-form-element' ), //singular name of the listed records
				'plural'   => __( 'Subscriptions', 'acf-frontend-form-element' ), //plural name of the listed records
				'ajax'     => false //does this table support ajax?
			] );

		}


		/**
		 * Retrieve subscriptions data from the database
		 *
		 * @param int $per_page
		 * @param int $page_number
		 *
		 * @return mixed
		 */
		public static function get_subscriptions( $per_page = 20, $page_number = 1 ) {

			global $wpdb;

			$sql = "SELECT * FROM {$wpdb->prefix}fea_subscriptions";

			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}else{
				$sql .= ' ORDER BY ' . sanitize_sql_orderby( 'created_at DESC' );
			}

			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


			$result = $wpdb->get_results( $sql, 'ARRAY_A' );

			return $result;
		}


		/**
		 * Delete a subscription record.
		 *
		 * @param int $id subscription ID
		 */
		public static function delete_subscription( $id ) {
			global $wpdb;

			$wpdb->delete(
				"{$wpdb->prefix}fea_subscriptions",
				['id' => $id ],
				['%d']
			);
		}


		/**
		 * Returns the count of records in the database.
		 *
		 * @return null|string
		 */
		public static function record_count() {
			global $wpdb;

			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}fea_subscriptions";

			return $wpdb->get_var( $sql );
		}


		/** Text displayed when no subscription data is available */
		public function no_items() {
			_e( 'No subscriptions avaliable.', 'acf-frontend-form-element' );
		}


		/**
		 * Render a column when no column specific method exist.
		 *
		 * @param array $item
		 * @param string $column_name
		 *
		 * @return mixed
		 */
		public function column_default( $item, $column_name ) {
			switch( $column_name ){
				case 'created_at':
					$time_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
					return date( $time_format, strtotime( $item[ $column_name ] ) );
				case 'expires_after':
					if( $item['expires_after'] ){
						$time_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
						return date( $time_format, strtotime( $item[ $column_name ] ) );
					}else{
						return __( 'Never', 'acf-frontend-form-element' );
					}
				default:
					return $item[ $column_name ];
			}
		}


		/**
		 * Gets the name of the default primary column.
		 *
		 * @since 4.3.0
		 *
		 * @return string Name of the default primary column, in this case, 'title'.
		 */
		protected function get_default_primary_column_name() {
			return 'title';
		}


		/**
		 *  Associative array of columns
		 *
		 * @return array
		 */
		function get_columns() {
			$columns = [
				'title'  => __( 'Title', 'acf-frontend-form-element' ),
				'description'  => __( 'Description', 'acf-frontend-form-element' ),
			];

			return $columns;
		}


		/**
		 * Handles data query and filter, sorting, and pagination.
		 */
		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();

			/** Process bulk action */
			$this->process_bulk_action();

			$perpage     = $this->get_items_per_page( 'subscriptions_per_page', 20 );
			$current_page = $this->get_pagenum();
			$total_items  = self::record_count();

			$this->set_pagination_args( [
				'total_items' => $total_items, //WE have to calculate the total number of items
				'per_page'    => $perpage //WE have to determine how many items to show on a page
			] );

			$this->items = self::get_subscriptions( $perpage, $current_page );
			
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);
		
		}

		public function process_bulk_action() {

			//Detect when a bulk action is being triggered...
			if ( 'delete' === $this->current_action() ) {

				// In our file that handles the request, verify the nonce.
				$nonce = esc_attr( $_REQUEST['_wpnonce'] );

				if ( ! wp_verify_nonce( $nonce, 'sp_delete_subscription' ) ) {
					die( 'Go get a life script kiddies' );
				}
				else {
					self::delete_subscription( absint( $_GET['subscription'] ) );

							// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
							// add_query_arg() return the current url
							wp_redirect( esc_url_raw(add_query_arg()) );
					exit;
				}

			}

			// If the delete bulk action is triggered
			if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
				|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
			) {

				$delete_ids = esc_sql( $_POST['bulk-delete'] );

				// loop over the array of record IDs and delete them
				foreach ( $delete_ids as $id ) {
					self::delete_subscription( $id );

				}

				// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
					// add_query_arg() return the current url
					wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
		}

	}

	fea_instance()->subscriptions_list = new Subscriptions_List;

endif;
