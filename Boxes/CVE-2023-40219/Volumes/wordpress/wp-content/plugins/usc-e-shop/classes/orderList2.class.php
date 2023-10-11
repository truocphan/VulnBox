<?php
/**
 * Order List Class.
 * - New type.
 *
 * @package Welcart
 */
class WlcOrderList { // phpcs:ignore
	// phpcs:disable
	var $table;               /* テーブル名 */
	var $rows;                /* データ */
	var $action;              /* アクション */
	var $startRow;            /* 表示開始行番号 */
	var $maxRow;              /* 最大表示行数 */
	var $currentPage;         /* 現在のページNo */
	var $firstPage;           /* 最初のページNo */
	var $previousPage;        /* 前のページNo */
	var $nextPage;            /* 次のページNo */
	var $lastPage;            /* 最終ページNo */
	var $naviMaxButton;       /* ページネーション・ナビのボタンの数 */
	var $dataTableNavigation; /* ナヴィゲーションhtmlコード */
	var $arr_period;          /* 表示データ期間 */
	var $arr_search;          /* サーチ条件 */
	var $searchSql;           /* 簡易絞込みSQL */
	var $searchSkuSql;        /* SKU絞り込み */
	var $searchSwitchStatus;  /* サーチ表示スイッチ */
	var $columns;             /* データカラム */
	var $all_columns;         /* 全てのカラム */
	var $sortColumn;          /* 現在ソート中のフィールド */
	var $sortOldColumn;
	var $sortSwitchs;         /* 各フィールド毎の昇順降順スイッチ */
	var $userHeaderNames;     /* ユーザー指定のヘッダ名 */
	var $action_status, $action_message;
	var $pageLimit;           /* ページ制限 */
	var $management_status;   /* 処理ステータス */
	var $selectSql;
	var $joinTableSql;
	var $cscs_meta;
	var $csod_meta;
	var $currentPageIds;
	var $period;
	var $placeholder_escape;
	var $view_column;
	var $all_column;
	var $data_cookie;
	var $searchWhere;
	var $searchHaving;
	// phpcs:enable

	/**
	 * Constructor.
	 *
	 * @param bool $all_column Column.
	 */
	public function __construct( $all_column = false ) {
		global $wpdb;
		$this->all_column = $all_column;
		$this->cscs_meta  = usces_has_custom_field_meta( 'customer' );
		$this->csod_meta  = usces_has_custom_field_meta( 'order' );

		$this->listOption  = get_option( 'usces_orderlist_option' );
		$this->view_column = $this->listOption['view_column'];
		if ( $this->all_column ) {
			foreach ( $this->view_column as $key => $value ) {
				$this->view_column[ $key ] = 1;
			}
		}

		$this->table = $wpdb->prefix . 'usces_order';
		$this->set_all_column();
		$this->set_column();
		$this->rows = array();

		$this->maxRow         = $this->listOption['max_row'];
		$this->naviMaxButton  = 11;
		$this->firstPage      = 1;
		$this->pageLimit      = 'on';
		$this->action_status  = 'none';
		$this->action_message = '';
		$orderPeriod          = isset( $_COOKIE['orderPeriod'] ) ? $_COOKIE['orderPeriod'] : '';
		if ( empty( $orderPeriod ) ) {
			$this->period = array(
				'period' => 0,
				'start'  => '',
				'end'    => '',
			);
		} else {
			parse_str( $orderPeriod, $this->period );
		}
		$this->getCookie();
		$this->SetDefaultParam();
		$this->SetParamByQuery();
		$this->validationSearchParameters();
		$arr_period       = array(
			__( 'This month', 'usces' ),
			__( 'Last month', 'usces' ),
			__( 'The past one week', 'usces' ),
			__( 'Last 30 days', 'usces' ),
			__( 'Last 90days', 'usces' ),
			__( 'All', 'usces' ),
		);
		$this->arr_period = apply_filters( 'usces_filter_order_list_arr_period', $arr_period, $this );

		$management_status       = array(
			'duringorder'  => __( 'temporaly out of stock', 'usces' ),
			'cancel'       => __( 'Cancel', 'usces' ),
			'completion'   => __( 'It has sent it out.', 'usces' ),
			'estimate'     => __( 'An estimate', 'usces' ),
			'adminorder'   => __( 'Management of Note', 'usces' ),
			'continuation' => __( 'Continuation', 'usces' ),
			'termination'  => __( 'Termination', 'usces' ),
		);
		$this->management_status = apply_filters( 'usces_filter_management_status', $management_status, $this );

		$wpdb->query( 'SET SQL_BIG_SELECTS=1' );
	}

	/**
	 * Set Column.
	 */
	public function set_column() {

		$arr_column = array();

		if ( $this->view_column['admin_memo'] ) {
			$arr_column['admin_memo'] = __( 'Administrator Note', 'usces' );
		}

		$arr_column['ID'] = __( 'ID', 'usces' );

		if ( $this->view_column['deco_id'] ) {
			$arr_column['deco_id'] = __( 'Order number', 'usces' );
		}

		if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
			$arr_column['reg_id'] = __( 'Regular ID', 'autodelivery' );
		}

		$arr_column['order_date']      = __( 'Order date', 'usces' );
		$arr_column['order_modified']  = apply_filters( 'usces_filter_admin_modified_label', __( 'shpping date', 'usces' ) );
		$arr_column['estimate_status'] = __( 'Order type', 'usces' );
		$arr_column['process_status']  = __( 'Processing status', 'usces' );

		if ( $this->view_column['tracking_number'] ) {
			$arr_column['tracking_number'] = __( 'Tracking number', 'usces' );
		}

		$arr_column['payment_name'] = __( 'payment method', 'usces' );

		if ( $this->view_column['wc_trans_id'] ) {
			$arr_column['wc_trans_id'] = __( 'Transaction ID', 'usces' );
		}

		$arr_column['receipt_status']   = __( 'transfer statement', 'usces' );
		$arr_column['item_total_price'] = __( 'total items', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['getpoint']         = __( 'granted points', 'usces' );
		$arr_column['usedpoint']        = __( 'Used points', 'usces' );
		$arr_column['discount']         = __( 'Discount', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['shipping_charge']  = __( 'Shipping', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['cod_fee']          = __( 'Fee', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['tax']              = __( 'Tax', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['total_price']      = __( 'Total Amount', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['deli_method']      = __( 'shipping option', 'usces' );
		$arr_column['deli_name']        = __( 'Destination name', 'usces' );
		$arr_column['deli_time']        = __( 'delivery time', 'usces' );
		$arr_column['deli_date']        = __( 'Delivery date', 'usces' );
		$arr_column['delidue_date']     = __( 'Shipping date', 'usces' );

		$arr_column['mem_id']  = __( 'membership number', 'usces' );
		$arr_column['name1']   = __( 'Last Name', 'usces' );
		$arr_column['name2']   = __( 'First Name', 'usces' );
		$arr_column['name3']   = __( 'Last Furigana', 'usces' );
		$arr_column['name4']   = __( 'First Furigana', 'usces' );
		$arr_column['zipcode'] = __( 'Zip', 'usces' );

		if ( $this->view_column['country'] ) {
			$arr_column['country'] = __( 'Country', 'usces' );
		}

		$arr_column['pref']     = __( 'Province', 'usces' );
		$arr_column['address1'] = __( 'city', 'usces' );
		$arr_column['address2'] = __( 'numbers', 'usces' );
		$arr_column['address3'] = __( 'building name', 'usces' );
		$arr_column['tel']      = __( 'Phone number', 'usces' );
		$arr_column['fax']      = __( 'FAX number', 'usces' );
		$arr_column['email']    = __( 'e-mail', 'usces' );
		$arr_column['note']     = __( 'Notes', 'usces' );

		foreach ( (array) $this->cscs_meta as $key => $value ) {
			$cscs_key = 'cscs_' . $key;

			if ( isset( $this->view_column[ $cscs_key ] ) && $this->view_column[ $cscs_key ] ) {
				$cscs_name               = $value['name'];
				$arr_column[ $cscs_key ] = $cscs_name;
			}
		}

		foreach ( (array) $this->csod_meta as $key => $value ) {
			$csod_key = 'csod_' . $key;

			if ( isset( $this->view_column[ $csod_key ] ) && $this->view_column[ $csod_key ] ) {
				$csod_name               = $value['name'];
				$arr_column[ $csod_key ] = $csod_name;
			}
		}

		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			if ( $this->view_column[$key] ) {
				$arr_column[$key] = $value['label'];
			}
		}

		$arr_column    = apply_filters( 'usces_filter_orderlist_column', $arr_column, $this );
		$this->columns = $arr_column;
	}

	/**
	 * Set All Columns.
	 */
	public function set_all_column() {

		$arr_column = array();

		$arr_column['admin_memo'] = __( 'Administrator Note', 'usces' );
		$arr_column['ID']         = __( 'ID', 'usces' );
		$arr_column['deco_id']    = __( 'Order number', 'usces' );
		if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
			$arr_column['reg_id'] = __( 'Regular ID', 'autodelivery' );
		}
		$arr_column['order_date'] = __( 'Order date', 'usces' );

		$arr_column['order_modified']   = apply_filters( 'usces_filter_admin_modified_label', __( 'shpping date', 'usces' ) );
		$arr_column['estimate_status']  = __( 'Order type', 'usces' );
		$arr_column['process_status']   = __( 'Processing status', 'usces' );
		$arr_column['tracking_number']  = __( 'Tracking number', 'usces' );
		$arr_column['payment_name']     = __( 'payment method', 'usces' );
		$arr_column['wc_trans_id']      = __( 'Transaction ID', 'usces' );
		$arr_column['receipt_status']   = __( 'transfer statement', 'usces' );
		$arr_column['item_total_price'] = __( 'total items', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['getpoint']         = __( 'granted points', 'usces' );
		$arr_column['usedpoint']        = __( 'Used points', 'usces' );
		$arr_column['discount']         = __( 'Discount', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['shipping_charge']  = __( 'Shipping', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['cod_fee']          = __( 'Fee', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['tax']              = __( 'Tax', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['total_price']      = __( 'Total Amount', 'usces' ) . '( ' . __( usces_crcode( 'return' ), 'usces' ) . ')'; // phpcs:ignore
		$arr_column['deli_method']      = __( 'shipping option', 'usces' );
		$arr_column['deli_name']        = __( 'Destination name', 'usces' );
		$arr_column['deli_time']        = __( 'delivery time', 'usces' );
		$arr_column['deli_date']        = __( 'Delivery date', 'usces' );
		$arr_column['delidue_date']     = __( 'Shipping date', 'usces' );

		$arr_column['mem_id']   = __( 'membership number', 'usces' );
		$arr_column['name1']    = __( 'Last Name', 'usces' );
		$arr_column['name2']    = __( 'First Name', 'usces' );
		$arr_column['name3']    = __( 'Last Furigana', 'usces' );
		$arr_column['name4']    = __( 'First Furigana', 'usces' );
		$arr_column['zipcode']  = __( 'Zip', 'usces' );
		$arr_column['country']  = __( 'Country', 'usces' );
		$arr_column['pref']     = __( 'Province', 'usces' );
		$arr_column['address1'] = __( 'city', 'usces' );
		$arr_column['address2'] = __( 'numbers', 'usces' );
		$arr_column['address3'] = __( 'building name', 'usces' );
		$arr_column['tel']      = __( 'Phone number', 'usces' );
		$arr_column['fax']      = __( 'FAX number', 'usces' );
		$arr_column['email']    = __( 'e-mail', 'usces' );
		$arr_column['note']     = __( 'Notes', 'usces' );

		foreach ( (array) $this->cscs_meta as $key => $value ) {
			$cscs_key                = 'cscs_' . $key;
			$cscs_name               = $value['name'];
			$arr_column[ $cscs_key ] = $cscs_name;
		}

		foreach ( (array) $this->csod_meta as $key => $value ) {
			$csod_key                = 'csod_' . $key;
			$csod_name               = $value['name'];
			$arr_column[ $csod_key ] = $csod_name;
		}

		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			$arr_column[$key] = $value['label'];
		}

		$arr_column        = apply_filters( 'usces_filter_orderlist_all_column', $arr_column, $this );
		$this->all_columns = $arr_column;
	}

	/**
	 * Get Column.
	 */
	public function get_column() {
		return $this->columns;
	}

	/**
	 * Get All Columns.
	 */
	public function get_all_column() {
		return $this->all_columns;
	}

	/**
	 * Action.
	 *
	 * @return mixed
	 */
	public function MakeTable() { // phpcs:ignore
		$this->SetParam();
		switch ( $this->action ) {
			case 'searchOut':
				$this->SearchOut();
				$res = $this->GetRows();
				break;

			case 'collective_receipt_status':
				check_admin_referer( 'order_list', 'wc_nonce' );
				usces_all_change_order_reciept( $this );
				$this->SearchIn();
				$res = $this->GetRows();
				break;

			case 'collective_estimate_status':
			case 'collective_process_status':
				check_admin_referer( 'order_list', 'wc_nonce' );
				usces_all_change_order_status( $this );
				$this->SearchIn();
				$res = $this->GetRows();
				break;

			case 'collective_delete':
				check_admin_referer( 'order_list', 'wc_nonce' );
				usces_all_delete_order_data( $this );
				$this->SetTotalRow();
				$this->SearchIn();
				$res = $this->GetRows();
				break;

			case 'searchIn':
			case 'refresh':
			case 'returnList':
			case 'changeSort':
			case 'changePage':
			default:
				$this->SearchIn();
				$res = $this->GetRows();
				break;
		}

		$this->SetNavi();
		$this->SetHeaders();

		if ( $res ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Default Parameters.
	 */
	public function SetDefaultParam() { // phpcs:ignore
		$this->startRow           = isset( $this->data_cookie['startRow'] ) ? $this->data_cookie['startRow'] : 0;
		$this->currentPage        = isset( $this->data_cookie['currentPage'] ) ? $this->data_cookie['currentPage'] : 1;
		$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : 'ID';
		$this->searchWhere        = '';
		$this->searchHaving       = '';
		$this->searchSwitchStatus = ( isset( $this->data_cookie['searchSwitchStatus'] ) ) ? $this->data_cookie['searchSwitchStatus'] : 'OFF';
		if ( isset( $this->data_cookie['arr_search'] ) ) {
			$this->arr_search = $this->data_cookie['arr_search'];
		} else {
			$arr_search = array(
				'period'            => array( '', '' ),
				'order_column'      => array( '', '' ),
				'order_word'        => array( '', '' ),
				'order_word_term'   => array( 'contain', 'contain' ),
				'order_term'        => 'AND',
				'product_column'    => array( '', '' ),
				'product_word'      => array( '', '' ),
				'product_word_term' => array( 'contain', 'contain' ),
				'option_word'       => array( '', '' ),
				'product_term'      => 'AND',
			);
			$this->arr_search = apply_filters( 'usces_filter_order_list_arr_search', $arr_search, $this );
		}
		if ( isset( $this->data_cookie['sortSwitchs'] ) ) {
			$this->sortSwitchs = $this->data_cookie['sortSwitchs'];
		} else {
			$this->sortSwitchs[ $this->sortColumn ] = 'DESC';
		}
		$this->SetTotalRow();
	}

	/**
	 * Set Parameters.
	 */
	public function SetParam() { // phpcs:ignore
		$this->startRow = ( $this->currentPage - 1 ) * $this->maxRow;
	}

	/**
	 * Set Parameters.
	 */
	public function SetParamByQuery() { // phpcs:ignore
		global $wpdb;
		if ( isset( $_REQUEST['changePage'] ) ) {

			$this->action             = 'changePage';
			$this->currentPage        = (int) $_REQUEST['changePage'];
			$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs        = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->arr_search         = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow        = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
			$this->placeholder_escape = ( isset( $this->data_cookie['placeholder_escape'] ) ) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

		} elseif ( isset( $_REQUEST['returnList'] ) ) {

			$this->action             = 'returnList';
			$this->currentPage        = ( isset( $this->data_cookie['currentPage'] ) ) ? $this->data_cookie['currentPage'] : $this->currentPage;
			$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs        = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->arr_search         = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow        = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
			$this->placeholder_escape = ( isset( $this->data_cookie['placeholder_escape'] ) ) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

		} elseif ( isset( $_REQUEST['changeSort'] ) ) {

			$this->action                           = 'changeSort';
			$this->sortOldColumn                    = $this->sortColumn;
			$this->sortColumn                       = str_replace( '`', '', $_REQUEST['changeSort'] );
			$this->sortColumn                       = str_replace( ',', '', $this->sortColumn );
			$this->sortSwitchs                      = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->sortSwitchs[ $this->sortColumn ] = ( 'ASC' == $_REQUEST['switch'] ) ? 'ASC' : 'DESC';
			$this->currentPage                      = ( isset( $this->data_cookie['currentPage'] ) ) ? $this->data_cookie['currentPage'] : $this->currentPage;
			$this->userHeaderNames                  = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->arr_search                       = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->totalRow                         = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow                      = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
			$this->placeholder_escape               = ( isset( $this->data_cookie['placeholder_escape'] ) ) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

		} elseif ( isset( $_REQUEST['searchIn'] ) ) {

			$this->action                           = 'searchIn';
			$this->arr_search['order_column'][0]    = ! WCUtils::is_blank( $_REQUEST['search']['order_column'][0] ) ? str_replace( '`', '', $_REQUEST['search']['order_column'][0] ) : '';
			$this->arr_search['order_column'][1]    = ! WCUtils::is_blank( $_REQUEST['search']['order_column'][1] ) ? str_replace( '`', '', $_REQUEST['search']['order_column'][1] ) : '';
			$this->arr_search['order_word'][0]      = ! WCUtils::is_blank( $_REQUEST['search']['order_word'][0] ) ? trim( $_REQUEST['search']['order_word'][0] ) : '';
			$this->arr_search['order_word'][1]      = ! WCUtils::is_blank( $_REQUEST['search']['order_word'][1] ) ? trim( $_REQUEST['search']['order_word'][1] ) : '';
			$this->arr_search['order_word_term'][0] = isset( $_REQUEST['search']['order_word_term'][0] ) ? $_REQUEST['search']['order_word_term'][0] : 'contain';
			$this->arr_search['order_word_term'][1] = isset( $_REQUEST['search']['order_word_term'][1] ) ? $_REQUEST['search']['order_word_term'][1] : 'contain';
			if ( WCUtils::is_blank( $_REQUEST['search']['order_column'][0] ) ) {
				$this->arr_search['order_column'][1]    = '';
				$this->arr_search['order_word'][0]      = '';
				$this->arr_search['order_word'][1]      = '';
				$this->arr_search['order_word_term'][0] = 'contain';
				$this->arr_search['order_word_term'][1] = 'contain';
			}
			$this->arr_search['order_term']           = $_REQUEST['search']['order_term'];
			$this->arr_search['product_column'][0]    = ! WCUtils::is_blank( $_REQUEST['search']['product_column'][0] ) ? str_replace( '`', '', $_REQUEST['search']['product_column'][0] ) : '';
			$this->arr_search['product_column'][1]    = ! WCUtils::is_blank( $_REQUEST['search']['product_column'][1] ) ? str_replace( '`', '', $_REQUEST['search']['product_column'][1] ) : '';
			$this->arr_search['product_word'][0]      = ! WCUtils::is_blank( $_REQUEST['search']['product_word'][0] ) ? trim( $_REQUEST['search']['product_word'][0] ) : '';
			$this->arr_search['product_word'][1]      = ! WCUtils::is_blank( $_REQUEST['search']['product_word'][1] ) ? trim( $_REQUEST['search']['product_word'][1] ) : '';
			$this->arr_search['product_word_term'][0] = isset( $_REQUEST['search']['product_word_term'][0] ) ? $_REQUEST['search']['product_word_term'][0] : 'contain';
			$this->arr_search['product_word_term'][1] = isset( $_REQUEST['search']['product_word_term'][1] ) ? $_REQUEST['search']['product_word_term'][1] : 'contain';
			$this->arr_search['option_word'][0]       = ( isset( $_REQUEST['search']['option_word'][0] ) && ! WCUtils::is_blank( $_REQUEST['search']['option_word'][0] ) ) ? trim( $_REQUEST['search']['option_word'][0] ) : '';
			$this->arr_search['option_word'][1]       = ( isset( $_REQUEST['search']['option_word'][1] ) && ! WCUtils::is_blank( $_REQUEST['search']['option_word'][1] ) ) ? trim( $_REQUEST['search']['option_word'][1] ) : '';
			if ( WCUtils::is_blank( $_REQUEST['search']['product_column'][0] ) ) {
				$this->arr_search['product_column'][1]    = '';
				$this->arr_search['product_word'][0]      = '';
				$this->arr_search['product_word'][1]      = '';
				$this->arr_search['product_word_term'][0] = 'contain';
				$this->arr_search['product_word_term'][1] = 'contain';
				$this->arr_search['option_word'][0]       = '';
				$this->arr_search['option_word'][1]       = '';
			}
			$this->arr_search['product_term'] = $_REQUEST['search']['product_term'];
			$this->currentPage                = 1;
			$this->sortColumn                 = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs                = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames            = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->totalRow                   = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->placeholder_escape         = $wpdb->placeholder_escape();

		} elseif ( isset( $_REQUEST['searchOut'] ) ) {

			$this->action                             = 'searchOut';
			$this->arr_search['column']               = '';
			$this->arr_search['word']                 = '';
			$this->arr_search['order_column'][0]      = '';
			$this->arr_search['order_column'][1]      = '';
			$this->arr_search['order_word'][0]        = '';
			$this->arr_search['order_word'][1]        = '';
			$this->arr_search['order_word_term'][0]   = 'contain';
			$this->arr_search['order_word_term'][1]   = 'contain';
			$this->arr_search['order_term']           = 'AND';
			$this->arr_search['product_column'][0]    = '';
			$this->arr_search['product_column'][1]    = '';
			$this->arr_search['product_word'][0]      = '';
			$this->arr_search['product_word'][1]      = '';
			$this->arr_search['product_word_term'][0] = 'contain';
			$this->arr_search['product_word_term'][1] = 'contain';
			$this->arr_search['option_word'][0]       = '';
			$this->arr_search['option_word'][1]       = '';
			$this->arr_search['product_term']         = 'AND';

			$this->currentPage        = 1;
			$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs        = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->placeholder_escape = '';

		} elseif ( isset( $_REQUEST['refresh'] ) ) {

			$this->action             = 'refresh';
			$this->currentPage        = ( isset( $this->data_cookie['currentPage'] ) ) ? $this->data_cookie['currentPage'] : $this->currentPage;
			$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs        = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->arr_search         = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow        = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
			$this->placeholder_escape = '';

		} elseif ( isset( $_REQUEST['collective'] ) ) {

			$this->action             = 'collective_' . str_replace( ',', '', $_POST['allchange']['column'] );
			$this->currentPage        = ( isset( $this->data_cookie['currentPage'] ) ) ? $this->data_cookie['currentPage'] : $this->currentPage;
			$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs        = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->arr_search         = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow        = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
			$this->placeholder_escape = ( isset( $this->data_cookie['placeholder_escape'] ) ) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

		} else {

			$this->action             = 'default';
			$this->placeholder_escape = '';
		}
	}

	/**
	 * Validation Search Parameters.
	 */
	public function validationSearchParameters() { // phpcs:ignore
		$default_order_word_term   = [ 'contain', 'notcontain', 'equal', 'morethan', 'lessthan' ];
		$default_product_word_term = [ 'contain', 'notcontain', 'equal', 'morethan', 'lessthan' ];
		$default_order_term        = [ 'AND', 'OR' ];
		$default_product_term      = [ 'AND', 'OR' ];
		$default_product_columns   = [ 'item_code', 'item_name', 'sku_code', 'sku_name', 'item_option' ];
		if ( ! empty( $this->arr_search['order_column'][0] ) && ! array_key_exists( $this->arr_search['order_column'][0], $this->columns ) ) {
			$this->arr_search['order_column'][0] = key( $this->columns );
		}
		if ( ! empty( $this->arr_search['order_column'][1] ) && ! array_key_exists( $this->arr_search['order_column'][1], $this->columns ) ) {
			$this->arr_search['order_column'][1] = key( $this->columns );
		}

		if ( ! empty( $this->arr_search['order_word_term'][0] ) && ! in_array( $this->arr_search['order_word_term'][0], $default_order_word_term ) ) {
			$this->arr_search['order_word_term'][0] = $default_order_word_term[0];
		}
		if ( ! empty( $this->arr_search['order_word_term'][1] ) && ! in_array( $this->arr_search['order_word_term'][1], $default_order_word_term ) ) {
			$this->arr_search['order_word_term'][1] = $default_order_word_term[0];
		}
		if ( ! in_array( $this->arr_search['order_term'], $default_order_term ) ) {
			$this->arr_search['order_term'] = $default_order_term[0];
		}

		if ( ! empty( $this->arr_search['product_column'][0] ) && ! in_array( $this->arr_search['product_column'][0], $default_product_columns ) ) {
			$this->arr_search['product_column'][0] = $default_product_columns[0];
		}
		if ( ! empty( $this->arr_search['product_column'][1] ) && ! in_array( $this->arr_search['product_column'][1], $default_product_columns ) ) {
			$this->arr_search['product_column'][1] = $default_product_columns[0];
		}

		if ( ! empty( $this->arr_search['product_word_term'][0] ) && ! in_array( $this->arr_search['product_word_term'][0], $default_product_word_term ) ) {
			$this->arr_search['product_word_term'][0] = $default_product_word_term[0];
		}
		if ( ! empty( $this->arr_search['product_word_term'][1] ) && ! in_array( $this->arr_search['product_word_term'][1], $default_product_word_term ) ) {
			$this->arr_search['product_word_term'][1] = $default_product_word_term[0];
		}
		if ( ! in_array( $this->arr_search['product_term'], $default_product_term ) ) {
			$this->arr_search['product_term'] = $default_product_term[0];
		}
	}

	/**
	 * Get Rows.
	 *
	 * @return array
	 */
	public function GetRows() { // phpcs:ignore
		global $wpdb;

		$order_table          = $wpdb->prefix . 'usces_order';
		$order_meta_table     = $wpdb->prefix . 'usces_order_meta';
		$ordercart_table      = $wpdb->prefix . 'usces_ordercart';
		$ordercart_meta_table = $wpdb->prefix . 'usces_ordercart_meta';
		$regular_table        = $wpdb->prefix . 'usces_regular';

		$where  = $this->GetWhere();
		$having = $this->GetHaving();

		$join = '';
		$cscs = '';
		$csod = '';

		$tracking_key = apply_filters( 'usces_filter_tracking_meta_key', 'tracking_number' );

		if ( $this->view_column['deco_id'] ) {
			$join .= "LEFT JOIN {$order_meta_table} AS deco ON ord.ID = deco.order_id AND deco.meta_key = 'dec_order_id' ";
		}
		if ( $this->view_column['wc_trans_id'] ) {
			$join .= "LEFT JOIN {$order_meta_table} AS trans ON ord.ID = trans.order_id AND trans.meta_key = 'wc_trans_id' ";
		}
		if ( $this->view_column['country'] ) {
			$join .= "LEFT JOIN {$order_meta_table} AS country ON ord.ID = country.order_id AND country.meta_key = 'customer_country' ";
		}
		if ( $this->view_column['admin_memo'] ) {
			$join .= "LEFT JOIN {$order_meta_table} AS memo ON ord.ID = memo.order_id AND memo.meta_key = 'order_memo' ";
		}
		if ( $this->view_column['tracking_number'] ) {
			$join .= $wpdb->prepare( "LEFT JOIN {$order_meta_table} AS trac ON ord.ID = trac.order_id AND trac.meta_key = %s ", $tracking_key );
		}
		foreach ( $this->columns as $key => $value ) {
			if ( 'cscs_' === substr( $key, 0, 5 ) && $this->view_column[ $key ] ) {
				$join .= $wpdb->prepare( " LEFT JOIN {$order_meta_table} AS `p{$key}` ON ord.ID = `p{$key}`.order_id AND `p{$key}`.meta_key = %s ", $key );
				$cscs .= ', `p' . $key . '`.meta_value AS `' . $key . "`\n";
			}
		}

		foreach ( $this->columns as $key => $value ) {
			if ( 'csod_' === substr( $key, 0, 5 ) && $this->view_column[ $key ] ) {
				$join .= $wpdb->prepare( " LEFT JOIN {$order_meta_table} AS `p{$key}` ON ord.ID = `p{$key}`.order_id AND `p{$key}`.meta_key = %s ", $key );
				$csod .= ', `p' . $key . '`.meta_value AS `' . $key . "`\n";
			}
		}

		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $arr_mail_print_fields as $key => $value ) {
			if ( $this->view_column[ $key ] ) {
				$csod .= ", ord.order_check \n";
				break;
			}
		}

		if ( $where ) {
			$join .= " LEFT JOIN {$ordercart_table} AS `cart` ON ord.ID = cart.order_id ";
			$csod .= ', cart.item_code , cart.item_name , cart.sku_code , cart.sku_name ';

			$join .= " LEFT JOIN {$ordercart_meta_table} AS `itemopt` ON cart.cart_id = itemopt.cart_id AND itemopt.meta_type = 'option' ";
			$csod .= ', itemopt.meta_key, itemopt.meta_value ';
		}
		if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
			$join .= "LEFT JOIN {$order_meta_table} AS `rmeta1` ON ord.ID = rmeta1.order_id AND rmeta1.meta_key = 'regular_id' ";
			$join .= "LEFT JOIN {$order_meta_table} AS `rmeta2` ON ord.ID = rmeta2.order_id AND rmeta2.meta_key = 'acting_zeus_card' ";
			$join .= "LEFT JOIN {$regular_table} ON ord.ID = reg_order_id ";
		}
		$join = apply_filters( 'usces_filter_orderlist_sql_jointable', $join, $tracking_key, $this );

		$group  = ' GROUP BY `ID` ';
		$switch = ( isset( $this->sortSwitchs[ $this->sortColumn ] ) && 'ASC' == $this->sortSwitchs[ $this->sortColumn ] ) ? 'ASC' : 'DESC';

		$order = ' ORDER BY `' . esc_sql( $this->sortColumn ) . '` ' . $switch;
		$order = apply_filters( 'usces_filter_orderlist_sql_order', $order, $this->sortColumn, $switch, $this );

		$qstr = "SELECT \n";

		if ( $this->view_column['admin_memo'] ) {
			$qstr .= "memo.meta_value AS admin_memo, \n";
		}
		$qstr .= "ord.ID AS ID, \n";

		if ( $this->view_column['deco_id'] ) {
			$qstr .= "deco.meta_value AS deco_id, \n";
		}

		if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
			$qstr .= "IFNULL( reg_id, rmeta1.meta_value ) AS `reg_id`, \n";
		}

		$qstr .= "DATE_FORMAT( ord.order_date, %s ) AS order_date, \n";
		$qstr .= "ord.order_modified AS order_modified, \n";
		$qstr .= "ord.order_status AS estimate_status, \n";
		$qstr .= "ord.order_status AS process_status, \n";

		if ( $this->view_column['tracking_number'] ) {
			$qstr .= "trac.meta_value AS tracking_number, \n";
		}
		$qstr .= "ord.order_payment_name AS payment_name, \n";

		if ( $this->view_column['wc_trans_id'] ) {
			$qstr .= "trans.meta_value AS wc_trans_id, \n";
		}
		$qstr .= "ord.order_status AS receipt_status, \n";
		$qstr .= "ord.order_item_total_price AS item_total_price, \n";
		$qstr .= "ord.order_getpoint AS getpoint \n,";
		$qstr .= "ord.order_usedpoint AS usedpoint, \n";
		$qstr .= "ord.order_discount AS discount, \n";
		$qstr .= "ord.order_shipping_charge AS shipping_charge, \n";
		$qstr .= "ord.order_cod_fee AS cod_fee, \n";
		$qstr .= "ord.order_tax AS tax, \n";
		$qstr .= "( ord.order_item_total_price - ord.order_usedpoint + ord.order_discount + ord.order_shipping_charge + ord.order_cod_fee + ord.order_tax ) AS total_price, \n";
		$qstr .= "ord.order_delivery_method AS deli_method, \n";
		$qstr .= "ord.order_delivery AS deli_name, \n";
		$qstr .= "ord.order_delivery_time AS deli_time, \n";
		$qstr .= "ord.order_delivery_date AS deli_date, \n";
		$qstr .= "ord.order_delidue_date AS delidue_date, \n";
		$qstr .= "ord.mem_id AS mem_id, \n";
		$qstr .= "ord.order_name1 AS name1, \n";
		$qstr .= "ord.order_name2 AS name2, \n";
		$qstr .= "ord.order_name3 AS name3, \n";
		$qstr .= "ord.order_name4 AS name4, \n";
		$qstr .= "ord.order_zip AS zipcode, \n";

		if ( $this->view_column['country'] ) {
			$qstr .= "country.meta_value AS country, \n";
		}
		$qstr .= "ord.order_pref AS pref, \n";
		$qstr .= "ord.order_address1 AS address1, \n";
		$qstr .= "ord.order_address2 AS address2, \n";
		$qstr .= "ord.order_address3 AS address3, \n";
		$qstr .= "ord.order_tel AS tel, \n";
		$qstr .= "ord.order_fax AS fax, \n";
		$qstr .= "ord.order_email AS email, \n";
		$qstr .= "ord.order_note AS note \n";
		$qstr .= "{$cscs}";
		$qstr .= "{$csod}";
		$qstr  = apply_filters( 'usces_filter_orderlist_sql_after_note', $qstr, $this );

		if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
			$qstr .= ", IFNULL( reg_id, '' ) AS `reg_parent_id` \n";
			// $qstr .= "IFNULL( rmeta2.meta_value, '' ) AS `acting_zeus_card` \n";
		}

		$qstr .= "FROM {$this->table} AS ord \n";

		$query = $wpdb->prepare( $qstr, '%Y-%m-%d %H:%i:%s' );
		$query = apply_filters( 'usces_filter_orderlist_sql_select', $query, $cscs, $csod, $this );

		$aq     = $query . $join . $where . $group . $having;
		$cquery = "SELECT COUNT(*) AS ct FROM (" . $aq . ") AS temp";

		if ( 'on' == $this->pageLimit ) {
			$query .= $join . $where . $group . $having . $order . " LIMIT " . $this->startRow . ", " . $this->maxRow;
		} else {
			$query .= $join . $where . $group . $having . $order;
		}

		if ( $this->placeholder_escape ) {
			add_filter( 'query', array( $this, 'remove_ph' ) );
		}

		$ct                = $wpdb->get_var( $cquery );
		$this->selectedRow = $ct;

		$rows       = $wpdb->get_results( $query, ARRAY_A );
		$this->rows = $rows;

		if ( 'on' == $this->pageLimit ) {
			$this->currentPageIds = array();
			foreach ( $this->rows as $row ) {
				$this->currentPageIds[] = $row['ID'];
			}
		}

		return $this->rows;
	}

	/**
	 * Placeholder clear.
	 *
	 * @param  string $query Query.
	 * @return string
	 */
	public function remove_ph( $query ) {
		return str_replace( $this->placeholder_escape, '%', $query );
	}

	/**
	 * Set Total Rows.
	 */
	public function SetTotalRow() { // phpcs:ignore
		global $wpdb;
		$query          = "SELECT COUNT(ID) AS `ct` FROM {$this->table}" . apply_filters( 'usces_filter_orderlist_sql_where', '', $this );
		$query          = apply_filters( 'usces_filter_orderlist_set_total_row', $query, $this );
		$res            = $wpdb->get_var( $query );
		$this->totalRow = $res;
	}

	/**
	 * Having Condition.
	 *
	 * @return string
	 */
	public function GetHaving() { // phpcs:ignore
		global $wpdb;

		$lastmonth_s = date_i18n( 'Y-m-d H:i:s', mktime( 0, 0, 0, ( current_time( 'n' ) - 1 ), 1, current_time( 'Y' ) ) );
		$lastmonth_e = date_i18n( 'Y-m-d H:i:s', mktime( 23, 59, 59, current_time( 'n' ), 0, current_time( 'Y' ) ) );
		$thismonth   = date_i18n( 'Y-m-d H:i:s', mktime( 0, 0, 0, current_time( 'n' ), 1, current_time( 'Y' ) ) );

		$query = '';
		if ( 1 == $this->period['period'] ) {

			$query = $wpdb->prepare( " order_date >= %s ", $thismonth );

		} elseif ( 2 == $this->period['period'] ) {

			$query = $wpdb->prepare( " order_date >= %s AND order_date <= %s ", $lastmonth_s, $lastmonth_e );

		} elseif ( 3 == $this->period['period'] ) {

			$start = $this->period['start'] . ' 00:00:00';
			$end   = $this->period['end'] . '23:59:59';
			if ( ! empty( $this->period['start'] ) && ! empty( $this->period['end'] ) ) {

				$query = $wpdb->prepare( " order_date >= %s AND order_date <= %s ", $start, $end );

			} elseif ( empty( $this->period['start'] ) && ! empty( $this->period['end'] ) ) {

				$query = $wpdb->prepare( " order_date <= %s ", $end );

			} elseif ( ! empty( $this->period['start'] ) && empty( $this->period['end'] ) ) {

				$query = $wpdb->prepare( " order_date >= %s ", $start );

			}
		}

		$str = '';

		if ( ! WCUtils::is_blank( $this->searchHaving ) ) {
			if ( ! WCUtils::is_blank( $query ) ) {
				$str .= ' HAVING ' . $this->searchHaving . ' AND ' . $query;
			} else {
				$str .= ' HAVING ' . $this->searchHaving;
			}
		} else {
			if ( ! WCUtils::is_blank( $query ) ) {
				$str .= ' HAVING ' . $query;
			}
		}
		$str = apply_filters( 'usces_filter_orderlist_sql_having', $str, $query, $this->searchHaving, $this->period, $this );
		return $str;
	}

	/**
	 * Where Condition.
	 *
	 * @return string
	 */
	public function GetWhere() { // phpcs:ignore
		$str = '';
		if ( ! WCUtils::is_blank( $this->searchWhere ) ) {
			$str .= ' WHERE ' . $this->searchWhere;
		}
		$str = apply_filters( 'usces_filter_orderlist_sql_where', $str, $this );
		return $str;
	}

	/**
	 * Search.
	 */
	public function SearchIn() { // phpcs:ignore
		global $wpdb;

		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		$print_mail_column     = 'order_check';
		$this->searchWhere     = '';
		$this->searchHaving    = '';

		if ( ! empty( $this->arr_search['order_column'][0] ) && ! WCUtils::is_blank( $this->arr_search['order_word'][0] ) ) {
			$result_order_term_0 = $this->build_condition_order_word_term( $this->arr_search['order_word_term'][0], $this->arr_search['order_word'][0], $this->arr_search['order_column'][0] );
			$wordterm0           = $result_order_term_0['term'];
			$word0               = $result_order_term_0['word'];

			$this->searchHaving .= ' ( ';

			if ( 'estimate_status' == $this->arr_search['order_column'][0] && 'frontorder' == $this->arr_search['order_word'][0] ) {
				$search_estimate_status = apply_filters( 'usces_filter_orderlist_search_estimate_status', array( "%adminorder%", "%estimate%" ) );
				$and                    = '';
				$this->searchHaving    .= '( ';
				foreach ( $search_estimate_status as $status ) {
					$this->searchHaving .= $and . esc_sql( $this->arr_search['order_column'][0] ) . " NOT LIKE '" . $status . "'";
					$and                 = ' AND ';
				}
				$this->searchHaving .= ' ) ';

			} elseif ( 'process_status' == $this->arr_search['order_column'][0] && 'neworder' == $this->arr_search['order_word'][0] ) {
				$search_process_status = apply_filters( 'usces_filter_orderlist_search_process_status', array( "%duringorder%", "%cancel%", "%completion%" ) );
				$and                   = '';
				$this->searchHaving   .= '( ';
				foreach ( $search_process_status as $status ) {
					$this->searchHaving .= $and . esc_sql( $this->arr_search['order_column'][0] ) . " NOT LIKE '" . $status . "'";
					$and                 = ' AND ';
				}
				$this->searchHaving .= ' ) ';

			} elseif ( 'cscs_' == substr( $this->arr_search['order_column'][0], 0, 5 ) || 'csod_' == substr( $this->arr_search['order_column'][0], 0, 5) ) {
				$this->searchHaving .= $wpdb->prepare( '`p' . esc_sql( $this->arr_search['order_column'][0] ) . '`.meta_value' . $wordterm0, $word0 );

			} elseif ( array_key_exists( $this->arr_search['order_column'][0], $arr_mail_print_fields ) ) {
				if ( empty( $this->arr_search['order_word'][0] ) ) {
					$cond_oc_0 = $print_mail_column . ' NOT LIKE %s OR ' . $print_mail_column . ' IS NULL ';
				} else {
					$cond_oc_0 = $print_mail_column . ' LIKE %s ';
				}
				$word_oc_0 = '%' . esc_sql( $this->arr_search['order_column'][0] ) . '%';
				$this->searchHaving .= '( ' . $wpdb->prepare( $cond_oc_0, $word_oc_0 ) . ' ) ';
			} else {
				$is_date_column = $this->is_date_column( $this->arr_search['order_column'][0] );
				if ( $is_date_column ) {
					$order_column        = esc_sql( $this->arr_search['order_column'][0] );
					$date_condition      = '(STR_TO_DATE(' . $order_column . ', %s) IS NOT NULL AND ';
					$date_condition     .= $order_column . ' != "" AND ';
					$date_condition     .= 'SUBSTRING(' . $order_column . ', 1, 10)' . $wordterm0 . ')';
					$this->searchHaving .= $wpdb->prepare( $date_condition, '%Y-%m-%d', $word0 );
				} else {
					$this->searchHaving .= $wpdb->prepare( esc_sql( $this->arr_search['order_column'][0] ) . $wordterm0, $word0 );
				}
			}

			if ( ! empty( $this->arr_search['order_column'][1] ) && ! WCUtils::is_blank( $this->arr_search['order_word'][1] ) ) {
				$result_order_term_1 = $this->build_condition_order_word_term( $this->arr_search['order_word_term'][1], $this->arr_search['order_word'][1], $this->arr_search['order_column'][1] );
				$wordterm1           = $result_order_term_1['term'];
				$word1               = $result_order_term_1['word'];

				$this->searchHaving .= ' ' . $this->arr_search['order_term'] . ' ';
				if ( 'estimate_status' == $this->arr_search['order_column'][1] && 'frontorder' == $this->arr_search['order_word'][1] ) {
					$search_estimate_status = apply_filters( 'usces_filter_orderlist_search_estimate_status', array( "%adminorder%", "%estimate%" ) );
					$and                    = '';
					$this->searchHaving    .= '( ';
					foreach ( $search_estimate_status as $status ) {
						$this->searchHaving .= $and . esc_sql( $this->arr_search['order_column'][1] ) . " NOT LIKE '" . $status . "'";
						$and                 = ' AND ';
					}
					$this->searchHaving .= ' ) ';

				} elseif ( 'process_status' == $this->arr_search['order_column'][1] && 'neworder' == $this->arr_search['order_word'][1] ) {
					$search_process_status = apply_filters( 'usces_filter_orderlist_search_process_status', array( "%duringorder%", "%cancel%", "%completion%" ) );
					$and                   = '';
					$this->searchHaving   .= '( ';
					foreach ( $search_process_status as $status ) {
						$this->searchHaving .= $and . esc_sql( $this->arr_search['order_column'][1] ) . " NOT LIKE '" . $status . "'";
						$and                 = ' AND ';
					}
					$this->searchHaving .= ' ) ';

				} elseif ( 'cscs_' == substr( $this->arr_search['order_column'][1], 0, 5 ) || 'csod_' == substr( $this->arr_search['order_column'][1], 0, 5 ) ) {
					$this->searchHaving .= $wpdb->prepare( '`p' . esc_sql( $this->arr_search['order_column'][1] ) . '`.meta_value' . $wordterm1, $word1 );

				} elseif ( array_key_exists( $this->arr_search['order_column'][1], $arr_mail_print_fields ) ) {
					if ( empty( $this->arr_search['order_word'][1] ) ) {
						$cond_oc_1 = $print_mail_column . ' NOT LIKE %s OR ' . $print_mail_column . ' IS NULL ';
					} else {
						$cond_oc_1 = $print_mail_column . ' LIKE %s ';
					}
					$word_oc_1 = '%' . esc_sql( $this->arr_search['order_column'][1] ) . '%';
					$this->searchHaving .= '( ' . $wpdb->prepare( $cond_oc_1, $word_oc_1 ) . ' ) ';
				} else {
					$is_date_column = $this->is_date_column( $this->arr_search['order_column'][1] );
					if ( $is_date_column ) {
						$order_column        = esc_sql( $this->arr_search['order_column'][1] );
						$date_condition      = '(STR_TO_DATE(' . $order_column . ', %s) IS NOT NULL AND ';
						$date_condition     .= $order_column . ' != "" AND ';
						$date_condition     .= 'SUBSTRING(' . $order_column . ', 1, 10)' . $wordterm1 . ')';
						$this->searchHaving .= $wpdb->prepare( $date_condition, '%Y-%m-%d', $word1 );
					} else {
						$this->searchHaving .= $wpdb->prepare( esc_sql( $this->arr_search['order_column'][1] ) . $wordterm1, $word1 );
					}
				}
			}

			$this->searchHaving .= ' ) ';
		}

		if ( ! empty( $this->arr_search['product_column'][0] ) && ! WCUtils::is_blank( $this->arr_search['product_word'][0] ) ) {

			switch ( $this->arr_search['product_word_term'][0] ) {
				case 'notcontain':
					$prowordterm0 = ' NOT LIKE %s';
					$proword0     = "%" . $this->arr_search['product_word'][0] . "%";
					break;
				case 'equal':
					$prowordterm0 = ' = %s';
					$proword0     = $this->arr_search['product_word'][0];
					break;
				case 'morethan':
					$prowordterm0 = ' > %d';
					$proword0     = $this->arr_search['product_word'][0];
					break;
				case 'lessthan':
					$prowordterm0 = ' < %d';
					$proword0     = $this->arr_search['product_word'][0];
					break;
				case 'contain':
				default:
					$prowordterm0 = ' LIKE %s';
					$proword0     = "%" . $this->arr_search['product_word'][0] . "%";
					break;
			}

			switch ( $this->arr_search['product_word_term'][1] ) {
				case 'notcontain':
					$prowordterm1 = ' NOT LIKE %s';
					$proword1     = "%" . $this->arr_search['product_word'][1] . "%";
					break;
				case 'equal':
					$prowordterm1 = ' = %s';
					$proword1     = $this->arr_search['product_word'][1];
					break;
				case 'morethan':
					$prowordterm1 = ' > %d';
					$proword1     = $this->arr_search['product_word'][1];
					break;
				case 'lessthan':
					$prowordterm1 = ' < %d';
					$proword1     = $this->arr_search['product_word'][1];
					break;
				case 'contain':
				default:
					$prowordterm1 = ' LIKE %s';
					$proword1     = "%" . $this->arr_search['product_word'][1] . "%";
					break;
			}

			$this->searchWhere .= ' ( ';

			if ( 'item_option' == $this->arr_search['product_column'][0] ) {
				$this->searchWhere .= $wpdb->prepare( '( itemopt.meta_key LIKE %s AND itemopt.meta_value LIKE %s )' , "%" . $this->arr_search['product_word'][0] . "%" , "%" . $this->arr_search['option_word'][0] . "%" );
			} else {
				$this->searchWhere .= $wpdb->prepare( esc_sql( $this->arr_search['product_column'][0] ) . $prowordterm0, $proword0 );
			}

			if ( ! empty( $this->arr_search['product_column'][1] ) && ! WCUtils::is_blank( $this->arr_search['product_word'][1] ) ) {
				$this->searchWhere .= ' ' . $this->arr_search['product_term'] . ' ';
				if ( 'item_option' == $this->arr_search['product_column'][1] ) {
					$this->searchWhere .= $wpdb->prepare( '( itemopt.meta_key LIKE %s AND itemopt.meta_value LIKE %s )' , "%" . $this->arr_search['product_word'][1] . "%" , "%" . $this->arr_search['option_word'][1] . "%" );
				} else {
					$this->searchWhere .= $wpdb->prepare( esc_sql( $this->arr_search['product_column'][1] ) . $prowordterm1, $proword1 );
				}
			}

			$this->searchWhere .= ' ) ';
		}
	}

	/**
	 * Check if the column is date type or not.
	 *
	 * @param string $column_name the column name of order.
	 *
	 * @return bool true/false
	 */
	public function is_date_column( $column_name ) {
		return in_array( $column_name, array( 'order_date', 'order_modified', 'deli_date', 'delidue_date' ), true );
	}

	/**
	 * Check if the column is order status or not.
	 *
	 * @param string $column_name the column name of order.
	 * @return bool true/false
	 */
	public function is_status_column( $column_name ) {
		return in_array( $column_name, array( 'estimate_status', 'process_status', 'receipt_status' ), true );
	}

	/**
	 * Build condition from order word term.
	 *
	 * @param string $order_word_term type order word term.
	 * @param string $order_word value order word.
	 * @param string $order_column value order column.
	 *
	 * @return array
	 */
	public function build_condition_order_word_term( $order_word_term, $order_word, $order_column ) {
		$is_date_column = $this->is_date_column( $order_column );

		switch ( $order_word_term ) {
			case 'notcontain':
				if ( 'deli_method' == $order_column ) {
					$term = ' NOT IN (%d)';
					$word = $order_word;
				} elseif ( $is_date_column ) {
					$term = ' != %s';
					$word = $order_word;
				} else {
					$term = ' NOT LIKE %s';
					$word = '%' . $order_word . '%';
				}
				break;
			case 'equal':
				$is_status_column = $this->is_status_column( $order_column );
				if ( 'deli_method' == $order_column ) {
					$term = ' = %d ';
					$word = $order_word;
				} elseif ( $is_date_column ) {
					$term = ' = %s';
					$word = $order_word;
				} elseif ( $is_status_column ) {
					$term = ' LIKE %s';
					$word = '%' . $order_word . '%';
				} else {
					$term = ' = %s';
					$word = $order_word;
				}
				break;
			case 'morethan':
				if ( 'deli_method' == $order_column ) {
					$term = ' = %d ';
					$word = $order_word;
				} elseif ( $is_date_column ) {
					$term = ' > %s';
					$word = $order_word;
				} else {
					$term = ' > %d';
					$word = $order_word;
				}
				break;
			case 'lessthan':
				if ( 'deli_method' == $order_column ) {
					$term = ' = %d ';
					$word = $order_word;
				} elseif ( $is_date_column ) {
					$term = ' < %s';
					$word = $order_word;
				} else {
					$term = ' < %d';
					$word = $order_word;
				}
				break;
			case 'contain':
			default:
				if ( 'deli_method' == $order_column ) {
					$term = ' IN (%d)';
					$word = $order_word;
				} else {
					$term = ' LIKE %s';
					$word = '%' . $order_word . '%';
				}
				break;
		}

		return array(
			'term' => $term,
			'word' => $word,
		);
	}

	/**
	 * Search clear.
	 */
	public function SearchOut() { // phpcs:ignore
		$this->searchWhere  = '';
		$this->searchHaving = '';
	}

	/**
	 * Set Navigation.
	 */
	public function SetNavi() { // phpcs:ignore
		$this->lastPage     = ceil( $this->selectedRow / $this->maxRow );
		$this->previousPage = ( $this->currentPage - 1 == 0 ) ? 1 : $this->currentPage - 1;
		$this->nextPage     = ( $this->currentPage + 1 > $this->lastPage ) ? $this->lastPage : $this->currentPage + 1;
		$box                = array();

		for ( $i = 0; $i < $this->naviMaxButton; $i++ ) {
			if ( $i > $this->lastPage - 1 ) {
				break;
			}
			if ( $this->lastPage <= $this->naviMaxButton ) {
				$box[] = $i + 1;
			} else {
				if ( $this->currentPage <= 6) {
					$label = $i + 1;
					$box[] = $label;
				} else {
					$label = $i + 1 + $this->currentPage - 6;
					$box[] = $label;
					if ( $label == $this->lastPage ) {
						break;
					}
				}
			}
		}

		$html  = '';
		$html .= '<ul class="clearfix">';
		$html .= '<li class="rowsnum">' . $this->selectedRow . ' / ' . $this->totalRow . ' ' . __( 'cases', 'usces' ) . '</li>';
		if ( ( 1 == $this->currentPage ) || ( 0 == $this->selectedRow ) ) {
			$html .= '<li class="navigationStr">first&lt;&lt;</li>';
			$html .= '<li class="navigationStr">prev&lt;</li>';
		} else {
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_orderlist&changePage=1">first&lt;&lt;</a></li>';
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_orderlist&changePage=' . $this->previousPage . '">prev&lt;</a></li>';
		}
		if ( $this->selectedRow > 0 ) {
			$box_count = count( $box );
			for ( $i = 0; $i < $box_count; $i++ ) {
				if ( $box[ $i ] == $this->currentPage ) {
					$html .= '<li class="navigationButtonSelected"><span>' . $box[ $i ] . '</span></li>';
				} else {
					$html .= '<li class="navigationButton"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_orderlist&changePage=' . $box[ $i ] . '">' . $box[ $i ] . '</a></li>';
				}
			}
		}

		if ( ( $this->currentPage == $this->lastPage ) || ( 0 == $this->selectedRow ) ) {
			$html .= '<li class="navigationStr">&gt;next</li>';
			$html .= '<li class="navigationStr">&gt;&gt;last</li>';
		} else {
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_orderlist&changePage=' . $this->nextPage . '">&gt;next</a></li>';
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_orderlist&changePage=' . $this->lastPage . '">&gt;&gt;last</a></li>';
		}
		$html .= '</ul>';

		$this->dataTableNavigation = $html;
	}

	/**
	 * Get Cookie.
	 */
	public function getCookie() { // phpcs:ignore
		$this->data_cookie = ( isset( $_COOKIE[ $this->table ] ) ) ? json_decode( str_replace( "\'", "'", str_replace( '\"', '"', $_COOKIE[ $this->table ] ) ), true ) : array(); // phpcs:ignore
	}

	/**
	 * Set Headers.
	 */
	public function SetHeaders() { // phpcs:ignore
		$arr_mail_print_fields = get_option( 'usces_order_mail_print_fields' );
		foreach ( $this->columns as $key => $value ) {
			if ( 'admin_memo' == $key ) {
				continue;
			}
			if ( array_key_exists( $key, $arr_mail_print_fields ) ) {
				$value = $arr_mail_print_fields[$key]['alias'];
			}
			if ( $key == $this->sortColumn ) {
				if ( isset( $this->sortSwitchs[ $key ] ) && 'ASC' == $this->sortSwitchs[ $key ] ) {
					$str    = __( '[ASC]', 'usces' );
					$switch = 'DESC';
				} else {
					$str    = __( '[DESC]', 'usces' );
					$switch = 'ASC';
				}
				$this->headers[ $key ] = '<a href="' . site_url() . '/wp-admin/admin.php?page=usces_orderlist&changeSort=' . $key . '&switch=' . $switch . '"><span class="sortcolumn">' . $value . ' ' . $str . '</span></a>';
			} else {
				$switch                = isset( $this->sortSwitchs[ $key ] ) ? $this->sortSwitchs[ $key ] : 'DESC';
				$this->headers[ $key ] = '<a href="' . site_url() . '/wp-admin/admin.php?page=usces_orderlist&changeSort=' . $key . '&switch=' . $switch . '"><span>' . $value . '</span></a>';
			}
		}
	}

	/**
	 * Get Search.
	 *
	 * @return string
	 */
	public function GetSearchs() { // phpcs:ignore
		return $this->arr_search;
	}

	/**
	 * Get Headers.
	 *
	 * @return string
	 */
	public function GetListheaders() { // phpcs:ignore
		return $this->headers;
	}

	/**
	 * Get Navigation.
	 *
	 * @return string
	 */
	public function GetDataTableNavigation() { // phpcs:ignore
		return $this->dataTableNavigation;
	}

	/**
	 * Set Action Status and Action Message.
	 *
	 * @param string $status Action status.
	 * @param string $message Action message.
	 */
	public function set_action_status( $status, $message ) {
		$this->action_status = $status;
		$this->action_message = $message;
	}

	/**
	 * Get Action Status.
	 *
	 * @return string
	 */
	public function get_action_status() {
		return $this->action_status;
	}

	/**
	 * Get Action Message.
	 *
	 * @return string
	 */
	public function get_action_message() {
		return $this->action_message;
	}
}
