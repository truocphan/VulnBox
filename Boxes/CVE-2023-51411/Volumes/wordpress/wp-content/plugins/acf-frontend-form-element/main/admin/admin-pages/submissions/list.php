<?php
namespace Frontend_Admin\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . '/wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'Submissions_List' ) ) :

	class Submissions_List extends \WP_List_Table {


		/**
		 *
		 * Class constructor
		 */
		public function __construct() {
			parent::__construct(
				array(
					'singular' => __( 'Submission', 'acf-frontend-form-element' ), // singular name of the listed records
					'plural'   => __( 'Submissions', 'acf-frontend-form-element' ), // plural name of the listed records
					'ajax'     => false, // does this table support ajax?
				)
			);

		}


		/**
		 *
		 * Text displayed when no submission data is available
		 */
		public function no_items() {
			esc_html_e( 'No submissions avaliable.', 'acf-frontend-form-element' );
		}



		function column_cb( $item ) {
			return sprintf( '<input type="checkbox" name="submissions[]" value="%d" />', $item['id'] );
		}

		/**
		 * Render a column when no column specific method exist.
		 *
		 * @param array  $item
		 * @param string $column_name
		 *
		 * @return mixed
		 */
		public function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'created_at':
					$time_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
					return date( $time_format, strtotime( $item[ $column_name ] ) );
				case 'title':
					if ( $item[ $column_name ] ) {
						$title = $item[ $column_name ];
					} else {
						$title = sprintf( 'Submission #%d', $item['id'] );
					}

					$page    = sanitize_text_field( $_REQUEST['page'] );
					$title   = sprintf( '<a href="?page=%s&action=%s&id=%s">%s</a>', $page, 'edit', $item['id'], $title );
					$actions = array(
						'edit'   => sprintf( '<a href="?page=%s&action=%s&id=%s">%s</a>', $page, 'edit', $item['id'], __( 'Review', 'acf-frontend-form-element' ) ),
						'delete' => sprintf( '<a href="?page=%s&action=%s&id=%s&nonce=%s">%s</a>', $page, 'delete', $item['id'], wp_create_nonce( 'frontend_admin_delete_submission' ), __( 'Delete', 'acf-frontend-form-element' ) ),
					);
					return sprintf( '%1$s %2$s', $title, $this->row_actions( $actions ) );
				case 'user':
					$user = get_user_by( 'ID', $item[ $column_name ] );
					if ( is_object( $user ) ) {
						$title = $user->display_name . ' (' . $user->user_login . ')';
					} else {
						$title = __( 'Anonymous', 'acf-frontend-form-element' );
					}
					return $title;
				case 'status':
					$statuses = explode( ',', $item[ $column_name ] );
					$state    = '';
					foreach ( $statuses as $status ) {
						$state .= fea_instance()->submissions_handler->get_status_label( $status ) . '<br>';
					}
					return $state;
				case 'form':
					$form = fea_instance()->form_display->get_form( $item[ $column_name ] );
					
					if ( isset( $form['ID'] ) ) {
						$form_title = get_post_field( 'post_title', $form['ID'] );
						if ( isset( $form['elementor_widget'] ) && ! empty( $form['title'] ) ) {
							$form_title .= ' ' . $form['title'];
						}

						$permalink = get_the_permalink( $form['ID'] );
						return '<a target="_blank" href="' . $permalink . '">' . $form_title . '</a>';
					}
						

					return $item[ $column_name ];

				default:
					if ( isset( $item[ $column_name ] ) ) {
						return $item[ $column_name ];
					} else {
						return apply_filters( 'frontend_admin/submissions/admin_column_render', $item, $column_name );
					}
			}
		}

		function get_bulk_actions() {
			$actions = array(
				'bulk-delete'  => __( 'Delete', 'acf-frontend-form-element' ),
				'bulk-approve' => __( 'Approve', 'acf-frontend-form-element' ),
			);

			return $actions;
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

		public function get_sortable_columns() {
			return array(
				'created_at' => array( 'created_at', false ),
				'title'      => array( 'title', false ),
				'form'       => array( 'form', false ),
				'user'       => array( 'user', false ),
			);

		}

		/**
		 *  Associative array of columns
		 *
		 * @return array
		 */
		function get_columns() {
			$columns = array(
				'cb'         => '<input type="checkbox" />',
				'title'      => __( 'Title', 'acf-frontend-form-element' ),
				'user'       => __( 'Submitted By', 'acf-frontend-form-element' ),
				'form'       => __( 'Form', 'acf-frontend-form-element' ),
				'status'     => __( 'Status', 'acf-frontend-form-element' ),
				'created_at' => __( 'Date', 'acf-frontend-form-element' ),
			);

			$columns = apply_filters( 'frontend_admin/submissions/admin_columns', $columns );

			return $columns;
		}


		/**
		 * Handles data query and filter, sorting, and pagination.
		 */
		public function prepare_items() {
			$this->_column_headers = $this->get_column_info();

			/**

	   * Process bulk action
*/
			$this->process_bulk_action();

			$perpage      = $this->get_items_per_page( 'submissions_per_page', 20 );
			$current_page = $this->get_pagenum();
			$total_items  = fea_instance()->submissions_handler->record_count();

			if ( $total_items ) {
				$submits_count = get_option( 'frontend_admin_submissions_all_time', 0 );

				if ( ! $submits_count ) {
					$submits_count = $total_items;
					update_option( 'frontend_admin_submissions_all_time', $submits_count );
				}
			}

			$this->set_pagination_args(
				array(
					'total_items' => $total_items, // WE have to calculate the total number of items
					'per_page'    => $perpage, // WE have to determine how many items to show on a page
				)
			);

			$this->items = fea_instance()->submissions_handler->get_submissions(
				array(
					'per_page'     => $perpage,
					'current_page' => $current_page,
				)
			);

			$columns               = $this->get_columns();
			$hidden                = array();
			$sortable              = $this->get_sortable_columns();
			$this->_column_headers = array( $columns, $hidden, $sortable );

		}

		public function process_bulk_action() {
			if ( empty( $_POST['submissions'] ) ) {
				return;
			}

			$submissions = sanitize_post( $_POST['submissions'] );

			// If the delete bulk action is triggered
			if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
				|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
			) {
				// loop over the array of record IDs and delete them
				foreach ( esc_sql( $submissions ) as $id ) {
					fea_instance()->submissions_handler->delete_submission( absint( $id ) );
				}
			}
			if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-approve' )
				|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-approve' )
			) {

				// loop over the array of record IDs and delete them
				foreach ( esc_sql( $submissions ) as $id ) {
					fea_instance()->submissions_handler->approve_submission( absint( $id ) );

				}
			}
		}

	}
	fea_instance()->submissions_list = new Submissions_List();
endif;
