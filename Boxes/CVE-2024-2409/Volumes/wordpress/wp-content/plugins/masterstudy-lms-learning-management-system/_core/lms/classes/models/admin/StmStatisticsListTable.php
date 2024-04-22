<?php

namespace stmLms\Classes\Models\Admin;

use stmLms\Classes\Models\StmOrderItems;
use WP_User_Query;
use \WP_List_Table;
use stmLms\Classes\Vendor\Query;
use stmLms\Classes\Models\StmOrder;

class StmStatisticsListTable extends WP_List_Table {

	public $total_price = 0;

	public function __construct() {
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );
		parent::__construct(
			array(
				'singular' => __( 'Order', 'masterstudy-lms-learning-management-system' ),
				'plural'   => __( 'Orders', 'masterstudy-lms-learning-management-system' ),
				'ajax'     => false,
			)
		);
		$this->bulk_action_handler();
		$this->prepare_items();
		add_action( 'wp_print_scripts', array( __CLASS__, 'list_table_css' ) );
	}

	/**
	 * @param $status
	 * @param $option
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function prepare_items() {
		global $wpdb;
		$this->_column_headers = $this->get_column_info();
		$per_page              = $this->get_items_per_page( 'stm_lms_statistics_per_page', 10 );
		$current_page          = $this->get_pagenum();
		$this->items           = $this->getList( $per_page, $current_page );

		$all_items = $this->getList( 99999999, 1 );
		$orders    = wp_list_pluck( $all_items, 'post_id' );
		$total     = 0;
		foreach ( $orders as $order ) {
			$status = get_post_meta( $order, 'status', true );

			if ( ! empty( $status ) && 'completed' === $status ) {
				$order_total = get_post_meta( $order, '_order_total', true );
				if ( ! empty( $order_total ) ) {
					$total += floatval( $order_total );
				}
			}
		}

		$this->total_price = $total;
		$total_items       = count( $all_items );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items, //WE have to calculate the total number of items
				'per_page'    => $per_page, //WE have to determine how many items to show on a page
			)
		);
	}

	/**
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return array|int|null|object
	 */
	public function getList( $per_page = 5, $page_number = 0 ) {
		global $wpdb;
		$filter = $_GET['filter'] ?? array(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$prefix = $wpdb->prefix;
		$query  = StmOrder::query()
						->select( ' _order.*, meta.*' )
						->asTable( '_order' )
						->join( ' left join `' . $prefix . 'stm_lms_order_items` as lms_order_items on ( lms_order_items.`order_id` = _order.ID ) left join `' . $prefix . 'posts` as course on  (course.ID = lms_order_items.`object_id`) ' )
						->where_in( '_order.post_type', array( 'stm-orders', 'shop_order' ) )
						->where_in( '_order.post_status', array( 'publish', 'wc-completed' ) );

		if ( isset( $filter['id'] ) && ! empty( $filter['id'] ) ) {
			$query->where( '_order.ID', $filter['id'] );
		}

		if ( isset( $filter['created_date_from'] ) && ! empty( trim( $filter['created_date_from'] ) ) && isset( $filter['created_date_to'] ) && ! empty( trim( $filter['created_date_to'] ) ) ) {
			$query->where_raw( ' DATE(_order.post_date) >= "' . gmdate( 'Y-m-d', strtotime( $filter['created_date_from'] ) ) . '" AND DATE(_order.post_date) <= "' . gmdate( 'Y-m-d', strtotime( $filter['created_date_to'] ) ) . '" ' );
		}

		if ( isset( $filter['user'] ) && ! empty( $filter['user'] ) ) {
			$ids = array( $filter['user'] );
			if ( ! empty( $ids ) ) {
				$query->where_raw(
					' (
								(meta.meta_key = "user_id" AND meta.meta_value in (' . implode( ',', $ids ) . ')) OR
								(meta.meta_key = "_customer_user" AND meta.meta_value in (' . implode( ',', $ids ) . '))
							) '
				);
			}
		}

		if ( isset( $filter['post_author'] ) && ! empty( $filter['post_author'] ) ) {
			$query->where( 'course.`post_author`', (int) $filter['post_author'] );
		}

		$query_total_price = clone $query;

		$query_total_price->select( ' SUM( lms_order_items.`price` * lms_order_items.`quantity`) as total_price ' );

		$one = $query_total_price->findOne();

		$this->total_price = ( ! empty( $one ) ) ? $one->total_price : 0;

		$request_order_by = $_REQUEST['orderby'] ?? null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$request_order    = $_REQUEST['order'] ?? null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! empty( $request_order_by ) ) {
			$query->sort_by( esc_sql( $request_order_by ) )->order( ! empty( $request_order ) ? ' ' . esc_sql( $request_order ) : ' ASC' );
		} else {
			$query->sort_by( 'ID' )->order( ' DESC ' );
		}

		$query->join( ' left join ' . $prefix . 'postmeta as meta on (meta.post_id = _order.ID)' )
			->group_by( '_order.ID' )
			->limit( $per_page )
			->offset( ( $page_number > 1 ) ? ( ( $page_number - 1 ) * $per_page ) : 0 );

		return $query->find( false, Query::OUTPUT_OBJECT );
	}

	/**
	 * @return array
	 */
	public function get_columns() {
		return array(
			'ID'        => 'ID',
			'type'      => __( 'Order type', 'masterstudy-lms-learning-management-system' ),
			'items'     => __( 'Items', 'masterstudy-lms-learning-management-system' ),
			'user'      => __( 'User', 'masterstudy-lms-learning-management-system' ),
			'price'     => __( 'Price', 'masterstudy-lms-learning-management-system' ),
			'post_date' => __( 'Created date', 'masterstudy-lms-learning-management-system' ),
		);
	}

	/**
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'ID'        => array( 'ID', 'desc' ),
			'post_date' => array( 'post_date', 'desc' ),
		);
	}

	protected function get_bulk_actions() {
		return false;
	}

	/**
	 * @param string $which
	 */
	public function extra_tablenav( $which ) {
		if ( 'bottom' === $which ) {
			return;
		}

		$filter = $_GET['filter'] ?? array(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$id                = ( isset( $filter['id'] ) ) ? $filter['id'] : null;
		$status            = ( isset( $filter['status'] ) ) ? $filter['status'] : null;
		$total_price       = ( isset( $filter['total_price'] ) ) ? $filter['total_price'] : null;
		$created_date_from = ( isset( $filter['created_date_from'] ) ) ? $filter['created_date_from'] : null;
		$created_date_to   = ( isset( $filter['created_date_to'] ) ) ? $filter['created_date_to'] : null;

		$author  = array();
		$user    = array();
		$_author = __( 'Search by Author', 'masterstudy-lms-learning-management-system' );
		$_user   = __( 'Search by User', 'masterstudy-lms-learning-management-system' );

		$extra_tablenav = '
						<hr>
						<div class="alignleft actions">
							<input class="form-control" type="text" style="width: 100px" name="filter[id]" value="' . $id . '" placeholder="' . __( 'Search by ID', 'masterstudy-lms-learning-management-system' ) . '">
							<div id="stm-user-search" class="stm-user-search--app">
								<stm-user-search key="search_by_user" class="stm-user-search"  inline-template :user="user" v-on:stm-user-search="selectUser">
									<div>
										<v-select label="name"  v-model="user" :filterable="false" :options="options" @search="onSearch"placeholder="' . $_user . '">
											<template slot="no-options">
												' . __( 'Type the name or email to search for the user', 'masterstudy-lms-learning-management-system' ) . '
											</template>
											<template slot="option" slot-scope="option">
												<div class="d-center">
													{{option.id}} {{option.name}} {{option.email}}
												</div>
											</template>
											<template slot="selected-option" scope="option">
												<div class="selected d-center">
													{{option.id}} {{option.name}} {{option.email}}
												</div>
											</template>
										</v-select>
									</div>
								</stm-user-search>
								<input v-if="user" name="filter[user]" type="hidden" v-model="user.id">
							</div>
							<div id="stm-author-search" class="stm-user-search--app">
								<stm-user-search key="search_by_author"  class="stm-user-search"  inline-template :user="user" v-on:stm-user-search="selectUser">
									<div>
										<v-select label="name"  v-model="user" :filterable="false" :options="options" @search="onSearch" placeholder="' . $_author . '">
											<template slot="no-options">
												' . __( 'Type the name or email to search for the author', 'masterstudy-lms-learning-management-system' ) . '
											</template>
											<template slot="option" slot-scope="option">
												<div class="d-center">
													{{option.id}} {{option.name}} {{option.email}}
												</div>
											</template>
											<template slot="selected-option" scope="option">
												<div class="selected d-center">
													{{option.id}} {{option.name}} {{option.email}}
												</div>
											</template>
										</v-select>
									</div>
								</stm-user-search>
								<input v-if="user" name="filter[post_author]" type="hidden" v-model="user.id">
							</div>



							<span class="form-control"> from :</span> <input class="form-control" style="width: 140px" type="date" name="filter[created_date_from]" value="' . $created_date_from . '" class="stm_plan_user_filter_date" />
							<span class="form-control"> to :</span> <input class="form-control" style="width: 140px" type="date" name="filter[created_date_to]" value="' . $created_date_to . '" class="stm_plan_user_filter_date" />

							<button class="button">' . esc_html__( 'Apply', 'masterstudy-lms-learning-management-system' ) . '</button>
						</div>
						<div style="clear: both"></div>
						<hr>
						';

		echo stm_lms_filtered_output( $extra_tablenav ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public static function list_table_css() {
		$filter = $_GET['filter'] ?? array(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		?>
		<style>
			.column-ID {
				width: 80px;
			}

			.column-items {
				width: 350px;
			}

			.tablenav .actions {
				overflow: visible;
				padding: 0
			}

			.stm-user-search--app {
				float: left;
			}

			.stm-user-search--app .stm-user-search {
				width: 200px;
				margin: 0px 1px;
				float: left;
			}

			.form-control {
				box-shadow: none !important;
				padding: 6px 5px;
				margin-top: 0px;
				height: 32px;
				float: left;
			}
		</style>
		<script>
			var user = <?php // phpcs:ignore Squiz.PHP.EmbeddedPhp
			if ( isset( $filter['user'] ) ) {
				$user = get_userdata( $filter['user'] );
				echo wp_json_encode(
					array(
						'id'    => $user->data->ID,
						'name'  => $user->data->display_name,
						'email' => $user->data->user_email,
					)
				);
			} else {
				echo 'null';
			}
			?>

			var author = <?php // phpcs:ignore Squiz.PHP.EmbeddedPhp
			if ( isset( $filter['post_author'] ) ) {
				$post_author = get_userdata( $filter['post_author'] );
				echo wp_json_encode(
					array(
						'id'    => $post_author->data->ID,
						'name'  => $post_author->data->display_name,
						'email' => $post_author->data->user_email,
					)
				);
			} else {
				echo 'null';
			}
			?>

			document.addEventListener('DOMContentLoaded', function () {
				new Vue({
					el: '#stm-user-search',
					data: {
						user: null
					},
					created() {
						if (user) {
							this.user = user;
						}
					},
					methods: {
						selectUser: function (user) {
							this.user = user;
						}
					}
				})

				new Vue({
					el: '#stm-author-search',
					data: {
						user: null
					},
					created() {
						if (author) {
							this.user = author;
						}
					},
					methods: {
						selectUser: function (user) {
							this.user = user;
						}
					}
				})
			});
		</script>
		<?php
	}

	/**
	 * @param object $item
	 * @param string $colname
	 *
	 * @return null|string|void
	 */
	public function column_default( $item, $colname ) {
		global $wpdb;
		$user  = null;
		$price = null;
		$type  = null;
		$meta  = get_post_meta( $item->ID );
		$items = StmOrderItems::query()->where( 'order_id', $item->ID )->find();

		if ( 'stm-orders' === $item->post_type ) {
			$type            = 'Lms';
			$user            = get_userdata( $meta['user_id'][0] );
			$_order_currency = ( isset( $meta['_order_currency'] ) ) ? $meta['_order_currency'][0] : null;
			$price           = ( isset( $meta['_order_total'] ) && isset( $meta['_order_total'][0] ) ) ? $meta['_order_total'][0] . ' ' . $_order_currency : 0;
		}

		if ( 'shop_order' === $item->post_type ) {
			$type  = 'WooCommerce';
			$user  = get_userdata( $meta['_customer_user'][0] );
			$price = $meta['_order_total'][0] . ' ' . $meta['_order_currency'][0];
		}

		switch ( $colname ) {
			case 'ID':
				return '#' . $item->$colname;
			case 'items':
				$content = '';
				foreach ( $items as $item ) {
					$item_post = $item->get_items_posts();
					if ( ! $item_post ) {
						continue;
					}
					$author = $item->get_items_author( $type );

					$payout   = ( $item->transaction ) ? 'Yes' : 'No';
					$content .= '<strong>' . __( 'Course', 'masterstudy-lms-learning-management-system' ) . ' </strong>: ' . $item_post->post_title . ' <br>
							     <strong>' . __( 'Quantity', 'masterstudy-lms-learning-management-system' ) . ' </strong>: ' . $item->quantity . ' <br>
							     <strong>' . __( 'Price', 'masterstudy-lms-learning-management-system' ) . ' </strong>: ' . $item->price . ' <br>
							     <strong>' . __( 'Author', 'masterstudy-lms-learning-management-system' ) . ' </strong>: ' . ( ! empty( $author ) ? "{$author->ID} {$author->user_email} {$author->user_firstname}" : '' ) . ' <br>
							     <strong>' . __( 'Payout', 'masterstudy-lms-learning-management-system' ) . ' </strong>: ' . $payout;
					$content .= '<hr>';
				}

				return $content;
			case 'user':
				if ( $user ) {
					return '(' . $user->ID . ')' . $user->user_firstname . ' ' . $user->user_lastname . ' <strong> (' . $user->user_email . ') </strong>';
				} else {
					return 'Not found';
				}
			case 'type':
				return $type;
			case 'price':
				return $price;
			case 'post_date':
				return date_i18n( get_option( 'date_format' ), strtotime( $item->$colname ) ) . ' <br> ' . date_i18n( get_option( 'time_format' ), strtotime( $item->$colname ) );
			default:
				return $item->$colname;
		}
	}

	private function bulk_action_handler() {
		if ( empty( $_POST['licids'] ) || empty( $_POST['_wpnonce'] ) ) {
			return;
		}
		if ( ! $this->current_action() ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
			wp_die( 'nonce error' );
		}
	}

}


