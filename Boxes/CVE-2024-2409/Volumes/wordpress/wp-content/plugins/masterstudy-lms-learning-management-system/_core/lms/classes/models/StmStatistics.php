<?php

namespace stmLms\Classes\Models;

use STM_LMS_Options;
use stmLms\Classes\Models\Admin\StmStatisticsListTable;

class StmStatistics {
	public $object;

	public function admin_menu() {
		add_action( 'wpcfto_screen_stm_lms_settings_added', array( $this, 'add_order_list' ), 100, 1 );
	}

	public function add_order_list() {
		$hook = add_submenu_page(
			'stm-lms-settings',
			__( 'Statistics', 'masterstudy-lms-learning-management-system' ),
			__( 'Statistics', 'masterstudy-lms-learning-management-system' ),
			'manage_options',
			'stm_lms_statistics',
			array( $this, 'render_statistics' )
		);

		add_action( "load-$hook", array( $this, 'stm_lms_statistics_screen_option' ) );
	}

	public function render_statistics() {
		stm_lms_render( STM_LMS_PATH . '/lms/views/statistics/statistics', array(), true );
	}

	public function stm_lms_statistics_screen_option() {
		$option = 'per_page';
		$args   = array(
			'label'   => 'Statistics',
			'default' => 10,
			'option'  => 'stm_lms_statistics_per_page',
		);

		add_screen_option( $option, $args );

		$this->object = new StmStatisticsListTable();
	}

	/**
	 * @return mixed
	 */
	public static function get_author_fee() {
		$author_fee = STM_LMS_Options::get_option( 'author_fee', false );

		return $author_fee ? $author_fee : 10;
	}

	/**
	 * @param $offset
	 * @param $limit
	 * @param array $params
	 *
	 * @return array
	 */
	public static function get_user_orders( $offset, $limit, $params = array() ) {
		global $wpdb;

		$prefix      = $wpdb->prefix;
		$user_orders = array();
		$query       = StmOrder::query()
			->select( ' _order.*, meta.* ' )
			->asTable( '_order' )
			->join( ' left join `' . $prefix . 'stm_lms_order_items` as lms_order_items on ( lms_order_items.`order_id` = _order.ID ) left join `' . $prefix . 'posts` as course on  (course.ID = lms_order_items.`object_id`) ' )
			->where_in( '_order.post_type', array( 'stm-orders', 'shop_order' ) );

		if ( ! empty( $params['id'] ) ) {
			$query->where( '_order.ID', $params['id'] );
		}

		if ( ! empty( trim( $params['created_date_from'] ?? '' ) ) && ! empty( trim( $params['created_date_to'] ?? '' ) ) ) {
			$query->where_raw( ' DATE(_order.post_date) >= "' . gmdate( 'Y-m-d', strtotime( $params['created_date_from'] ) ) . '" AND DATE(_order.post_date) <= "' . gmdate( 'Y-m-d', strtotime( $params['created_date_to'] ) ) . '" ' );
		}

		if ( ! empty( $params['total_price'] ) ) {
			$query->where_raw( ' ( meta.meta_key = "_order_total" AND meta.meta_value = "' . $params['total_price'] . '" ) ' );
		}

		if ( ! empty( $params['status'] ) ) {
			$query->where_raw(
				' (
					( meta.meta_key = "status" AND meta.meta_value = "' . $params['status'] . '" ) OR
					( _order.post_status = "' . $params['status'] . '" )
				) '
			);
		}

		if ( ! empty( $params['user'] ) ) {
			$ids = array( $params['user'] );
			if ( ! empty( $ids ) ) {
				$query->where_raw(
					' (
					(meta.meta_key = "user_id" AND meta.meta_value in (' . implode( ',', $ids ) . ')) OR
					(meta.meta_key = "_customer_user" AND meta.meta_value in (' . implode( ',', $ids ) . '))
				) '
				);
			}
		}

		if ( ! empty( $params['post_author'] ) ) {
			$query->where( 'course.`post_author`', (int) $params['post_author'] );
		}

		if ( ! empty( $params['orderby'] ) ) {
			$query->sort_by( esc_sql( $params['orderby'] ) )->order( ! empty( $params['order'] ) ? ' ' . esc_sql( $params['order'] ) : ' ASC' );
		} else {
			$query->sort_by( 'ID' )->order( ' DESC ' );
		}

		$query_total = clone $query;

		$user_orders['total'] = $query_total->select( ' COUNT(DISTINCT _order.ID) as count ' )->findOne()->count ?? 0;
		$query->join( ' left join ' . $prefix . 'postmeta as meta on (meta.post_id = _order.ID)' )
			->group_by( '_order.ID' )
			->limit( $limit )
			->offset( $offset );

		$user_orders['items'] = $query->find();

		return $user_orders;
	}

	/**
	 * @param $offset
	 * @param $limit
	 * @param array $params
	 *
	 * @return array
	 */
	public static function get_user_order_items( $offset, $limit, $params = array() ) {
		global $wpdb;
		$prefix      = $wpdb->prefix;
		$user_orders = array();
		$query       = StmOrderItems::query()
			->select( ' lms_order_items.*, course.post_title as name, _order.`post_date` as date_created ' )
			->asTable( 'lms_order_items' )
			->join( ' left join `' . $prefix . 'posts` as _order on ( lms_order_items.`order_id` = _order.ID ) left join `' . $prefix . 'posts` as course on  (course.ID = lms_order_items.`object_id`) ' )
			->where_in( '_order.post_type', array( 'stm-orders', 'shop_order' ) );

		if ( ! empty( $params['id'] ) ) {
			$query->where( '_order.ID', intval( $params['id'] ) );
		}

		if ( empty( trim( $params['date_from'] ?? '' ) ) && ! empty( trim( $params['date_to'] ?? '' ) ) ) {
			$query->where_raw(
				' DATE(_order.post_date) >= "' . gmdate( 'Y-m-d', strtotime( $params['date_from'] ) ) . '" AND DATE(_order.post_date) <= "' . gmdate( 'Y-m-d', strtotime( $params['date_to'] ) ) . '" '
			);
		}

		if ( ! empty( $params['total_price'] ) ) {
			$query->where_raw( ' ( meta.meta_key = "_order_total" AND meta.meta_value = "' . $params['total_price'] . '" ) ' );
		}

		if ( ! empty( $params['status'] ) ) {
			$query->where_raw(
				' (
					( meta.meta_key = "status" AND meta.meta_value = "' . $params['status'] . '" ) OR
					( _order.post_status = "' . $params['status'] . '" )
				) '
			);
		}

		if ( ! empty( $params['user'] ) ) {
			$user_id = intval( $params['user'] );
			if ( ! empty( $user_id ) ) {
				$query->where_raw(
					' (
					(meta.meta_key = "user_id" AND meta.meta_value in (' . $user_id . ')) OR
					(meta.meta_key = "_customer_user" AND meta.meta_value in (' . $user_id . '))
				) '
				);
			}
		}

		if ( ! empty( $params['course_id'] ) ) {
			$query->where( 'course.ID', intval( $params['course_id'] ) );
		}

		if ( ! empty( $params['author_id'] ) ) {
			$query->where( 'course.`post_author`', intval( $params['author_id'] ) );
		}

		if ( ! empty( $params['completed'] ) ) {
			$query->join( ' left join ' . $prefix . "postmeta as meta_status on ( meta_status.post_id = _order.ID AND _order.`post_type` = 'stm-orders' AND  meta_status.`meta_key` = 'status' AND meta_status.`meta_value` = 'completed') " )
				->join( ' left join ' . $prefix . "posts as order_status on ( lms_order_items.`order_id` = order_status.ID AND order_status.`post_status` = 'wc-completed') " )
				->where_raw( ' (  meta_status.post_id = _order.ID OR order_status.ID = _order.ID )  ' );
		}

		if ( ! empty( $params['orderby'] ) ) {
			$query->sort_by( esc_sql( $params['orderby'] ) )->order( ! empty( $params['order'] ) ? ' ' . esc_sql( $params['order'] ) : ' ASC' );
		} else {
			$query->sort_by( 'ID' )->order( ' DESC ' );
		}

		$query_total          = clone $query;
		$user_orders['total'] = $query_total->select( ' COUNT(DISTINCT lms_order_items.id) as count ' )->findOne()->count ?? 0;

		$query_total_price = clone $query;
		$query_total_price->select( ' SUM( lms_order_items.`price` * lms_order_items.`quantity`) as total_price ' );
		$total_price                = $query_total_price->findOne()->total_price ?? 0;
		$user_orders['total_price'] = ( $total_price ) ? $total_price : 0;
		$query->join( ' left join ' . $prefix . 'postmeta as meta on (meta.post_id = _order.ID)' )
			->group_by( 'lms_order_items.id' )
			->limit( $limit )
			->offset( $offset );

		$user_orders['items'] = $query->find();

		return $user_orders;
	}

	public static function get_user_orders_api() {
		check_ajax_referer( 'wp_rest', 'nonce' );

		$offset = 0;
		$limit  = 10;

		if ( ! empty( $_POST['offset'] ) ) {
			$offset = intval( $_POST['offset'] );
		}

		if ( ! empty( $_POST['limit'] ) ) {
			$limit = intval( $_POST['limit'] );
		}

		$params = $_POST;

		$params['completed'] = true;

		if ( $params['author_id'] ) {
			return self::get_user_order_items( $offset, $limit, $params );
		}
	}

	/**
	 * @param $date_start
	 * @param $date_end
	 * @param $user_id
	 * @param null $course_id
	 *
	 * @return array
	 */
	public static function get_course_statisticas( $date_start, $date_end, $user_id, $course_id = null ) {
		global $wpdb;

		$data    = array();
		$courses = StmLmsCourse::query()
			->select( ' course.ID, course.`post_title`, _order.`post_date` as date, SUM(order_items.`price` * order_items.`quantity`) as amount' )
			->asTable( 'course' )
			->join( ' left join `' . $wpdb->prefix . 'stm_lms_order_items` as order_items on order_items.`object_id` = course.ID ' )
			->join( ' left join `' . $wpdb->prefix . 'posts` _order on _order.ID = order_items.`order_id` ' )
			->join( ' left join ' . $wpdb->prefix . "postmeta as meta_status on ( meta_status.post_id = _order.ID AND _order.`post_type` = 'stm-orders' AND  meta_status.`meta_key` = 'status' AND meta_status.`meta_value` = 'completed') " )
			->where( 'course.post_author', $user_id )
			->where_raw( " ( course.post_type = 'stm-courses' OR course.post_type = 'stm-course-bundles' OR course.post_type = 'stm-orders' ) " )
			->where_raw( " (_order.`post_status` = 'wc-completed' OR meta_status.post_id = _order.ID) " )
			->where_raw( " (DATE(_order.`post_date`) BETWEEN '" . $date_start . "' AND '" . $date_end . "') " )
			->group_by( " course.ID, DATE_FORMAT(_order.post_date, '%m-%Y') " );

		if ( null !== $course_id ) {
			$courses->where( 'course.ID', $course_id )->findOne();
		}

		foreach ( $courses->find() as $course ) {
			$data[] = array(
				'id'              => $course->ID,
				'title'           => $course->post_title,
				'amount'          => $course->amount,
				'date'            => $course->date,
				'backgroundColor' => rand_color( 0.50 ),
			);
		}

		return $data;
	}

	/**
	 * @param $user_id
	 * @param null $course_id
	 */
	public static function get_course_sales_statisticas( $user_id, $course_id = null ) {
		global $wpdb;

		$data    = array();
		$courses = StmLmsCourse::query()
			->select( ' course.ID, course.`post_title`, SUM(order_items.`quantity`) as order_item_count ' )
			->asTable( 'course' )
			->join( ' left join `' . $wpdb->prefix . 'stm_lms_order_items` as order_items on order_items.`object_id` = course.ID ' )
			->join( ' left join `' . $wpdb->prefix . 'posts` _order on _order.ID = order_items.`order_id` ' )
			->join( ' left join ' . $wpdb->prefix . "postmeta as meta_status on ( meta_status.post_id = _order.ID AND _order.`post_type` = 'stm-orders' AND  meta_status.`meta_key` = 'status' AND meta_status.`meta_value` = 'completed') " )
			->where( 'course.post_author', $user_id )
			->where_raw( " ( course.post_type = 'stm-courses' OR course.post_type = 'stm-course-bundles' OR course.post_type = 'stm-orders' ) " )
			->where_raw( " (_order.`post_status` = 'wc-completed' OR meta_status.post_id = _order.ID) " )
			->group_by( ' course.ID ' );

		if ( null !== $course_id ) {
			$courses->where( 'course.ID', $course_id )->findOne();
		}

		foreach ( $courses->find() as $course ) {
			$data[] = array(
				'id'               => $course->ID,
				'title'            => $course->post_title,
				'backgroundColor'  => rand_color( 0.50 ),
				'order_item_count' => $course->order_item_count,
			);
		}

		return $data;
	}

}
