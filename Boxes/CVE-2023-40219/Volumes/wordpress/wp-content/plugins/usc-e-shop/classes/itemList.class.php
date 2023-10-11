<?php
/**
 * Item List Class.
 *
 * @package Welcart
 */
class dataList {
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
	var $searchSwitchStatus;  /* サーチ表示スイッチ */
	var $columns;             /* データカラム */
	var $sortColumn;          /* 現在ソート中のフィールド */
	var $sortOldColumn;
	var $sortSwitchs;         /* 各フィールド毎の昇順降順スイッチ */
	var $userHeaderNames;     /* ユーザー指定のヘッダ名 */
	var $action_status, $action_message;
	var $pageLimit;           /* ページ制限 */
	var $exportMode;          /* IDのみ */
	var $data_cookie;
	var $totalRow;
	var $selectedRow;

	/**
	 * Constructor.
	 *
	 * @param string $tableName Table name.
	 * @param array  $arr_column Columns.
	 */
	public function __construct( $tableName, $arr_column ) {
		$this->table   = $tableName;
		$this->columns = $arr_column;
		$this->rows    = array();

		$this->maxRow         = apply_filters( 'usces_filter_itemlist_maxrow', 30 );
		$this->naviMaxButton  = 11;
		$this->firstPage      = 1;
		$this->action_status  = 'none';
		$this->action_message = '';
		$this->pageLimit      = 'on';
		$this->exportMode     = false;
		$this->searchSql      = '';
		$this->getCookie();
		$this->SetDefaultParam();
		$this->SetParamByQuery();
		$this->validationSearchParameters();
		$this->setSearchSql();

		$this->arr_period = array(
			__( 'This month', 'usces' ),
			__( 'Last month', 'usces' ),
			__( 'The past one week', 'usces' ),
			__( 'Last 30 days', 'usces' ),
			__( 'Last 90days', 'usces' ),
			__( 'All', 'usces' ),
		);
	}

	/**
	 * Action.
	 *
	 * @return bool
	 */
	public function MakeTable() {

		$this->SetParam();
		$this->SetTotalRow();
		switch ( $this->action ) {

			case 'searchIn':
				$res = $this->GetRows();
				break;

			case 'searchOut':
				$res = $this->GetRows();
				break;

			case 'changeSort':
				$res = $this->GetRows();
				break;

			case 'changePage':
				$res = $this->GetRows();
				break;

			case 'collective_zaiko':
				usces_all_change_zaiko( $this );
				$res = $this->GetRows();
				break;

			case 'collective_display_status':
				usces_all_change_itemdisplay( $this );
				$res = $this->GetRows();
				break;

			case 'collective_delete':
				usces_all_delete_itemdata( $this );
				$res = $this->GetRows();
				break;

			case 'refresh':
			default:
				$res = $this->GetRows();
				break;
		}

		if ( ! $this->exportMode ) {
			$this->SetNavi();
			$this->SetHeaders();
		}

		if ( $res ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Default Parameters.
	 */
	public function SetDefaultParam() {
		$this->startRow           = isset( $this->data_cookie['startRow'] ) ? $this->data_cookie['startRow'] : 0;
		$this->totalRow           = isset( $this->data_cookie['totalRow'] ) ? $this->data_cookie['totalRow'] : 0;
		$this->selectedRow        = isset( $this->data_cookie['selectedRow'] ) ? $this->data_cookie['selectedRow'] : 0;
		$this->currentPage        = isset( $this->data_cookie['currentPage'] ) ? $this->data_cookie['currentPage'] : 1;
		$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : apply_filters( 'usces_filter_item_class_sortColumn', 'post.ID' );
		$this->searchSwitchStatus = ( isset( $this->data_cookie['searchSwitchStatus'] ) ) ? $this->data_cookie['searchSwitchStatus'] : 'OFF';
		if ( isset( $this->data_cookie['arr_search'] ) ) {
			$this->arr_search = $this->data_cookie['arr_search'];
		} else {
			$arr_search       = array(
				'period' => '3',
				'column' => '',
				'word'   => array(),
			);
			$this->arr_search = apply_filters( 'usces_filter_item_class_arr_search', $arr_search, $this );
		}
		if ( isset( $this->data_cookie['sortSwitchs'] ) ) {
			$this->sortSwitchs = $this->data_cookie['sortSwitchs'];
		} else {
			foreach ( $this->columns as $value ) {
				$this->sortSwitchs[ $value ] = 'ASC';
			}
			$this->sortSwitchs[ $this->sortColumn ] = apply_filters( 'usces_filter_item_class_sortSwitchs', 'DESC' );
		}
	}

	/**
	 * Set Parameters.
	 */
	public function SetParam() {
		$this->startRow = ( $this->currentPage - 1 ) * $this->maxRow;
	}

	/**
	 * Set Parameters.
	 */
	public function SetParamByQuery() {
		if ( isset( $_REQUEST['changePage'] ) ) {

			$this->action             = 'changePage';
			$this->currentPage        = (int) $_REQUEST['changePage'];
			$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs        = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->searchSwitchStatus = ( isset( $this->data_cookie['searchSwitchStatus'] ) ) ? $this->data_cookie['searchSwitchStatus'] : $this->searchSwitchStatus;
			$this->arr_search         = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow        = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;

		} elseif ( isset( $_REQUEST['changeSort'] ) ) {

			$this->action        = 'changeSort';
			$this->sortOldColumn = $this->sortColumn;
			$this->sortSwitchs   = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			if ( 'default' === $_REQUEST['switch'] ) {
				// Restore default table.
				$this->sortColumn = 'post.ID';
				foreach ( $this->sortSwitchs as $key => $val ) {
					if ( $key === $this->sortColumn ) {
						$this->sortSwitchs[ $key ] = 'DESC';
						continue;
					}
					$this->sortSwitchs[ $key ] = 'ASC';
				}
			} else {
				$this->sortColumn                       = str_replace( '(', '', $_REQUEST['changeSort'] );
				$this->sortColumn                       = str_replace( ',', '', $this->sortColumn );
				$this->sortSwitchs[ $this->sortColumn ] = str_replace( '(', '', $_REQUEST['switch'] );
				$this->sortSwitchs[ $this->sortColumn ] = str_replace( ',', '', $this->sortSwitchs[ $this->sortColumn ] );
			}

			$this->currentPage        = ( isset( $this->data_cookie['currentPage'] ) ) ? $this->data_cookie['currentPage'] : $this->userHeaderNames;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->arr_search         = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->searchSwitchStatus = ( isset( $this->data_cookie['searchSwitchStatus'] ) ) ? $this->data_cookie['searchSwitchStatus'] : $this->searchSwitchStatus;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow        = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;

		} elseif ( isset( $_REQUEST['searchIn'] ) ) {

			$this->action               = 'searchIn';
			$this->arr_search['column'] = isset( $_REQUEST['search']['column'] ) ? str_replace( ',', '', $_REQUEST['search']['column'] ) : '';
			$this->arr_search['word']   = isset( $_REQUEST['search']['word'] ) ? $_REQUEST['search']['word'] : '';
			$this->arr_search['period'] = isset( $_REQUEST['search']['period'] ) ? (int) $_REQUEST['search']['period'] : '';
			$this->searchSwitchStatus   = 'ON';

			$this->currentPage     = 1;
			$this->sortColumn      = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs     = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->totalRow        = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;

		} elseif ( isset( $_REQUEST['searchOut'] ) ) {

			$this->action               = 'searchOut';
			$this->arr_search['column'] = '';
			$this->arr_search['word']   = '';
			$this->arr_search['period'] = ( isset( $this->data_cookie['arr_search']['period'] ) ) ? $this->data_cookie['arr_search']['period'] : $this->arr_search['period'];
			$this->searchSwitchStatus   = 'OFF';

			$this->currentPage     = 1;
			$this->sortColumn      = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs     = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->totalRow        = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;

		} elseif ( isset( $_REQUEST['refresh'] ) ) {

			$this->action = 'refresh';

			$this->currentPage        = isset( $this->data_cookie['currentPage'] ) ? $this->data_cookie['currentPage'] : $this->currentPage;
			$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs        = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->searchSwitchStatus = ( isset( $this->data_cookie['searchSwitchStatus'] ) ) ? $this->data_cookie['searchSwitchStatus'] : $this->searchSwitchStatus;
			$this->arr_search         = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow        = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;

		} elseif ( isset( $_REQUEST['collective'] ) ) {

			$this->action             = 'collective_' . str_replace( ',', '', $_POST['allchange']['column'] );
			$this->currentPage        = isset( $this->data_cookie['currentPage'] ) ? $this->data_cookie['currentPage'] : $this->currentPage;
			$this->sortColumn         = ( isset( $this->data_cookie['sortColumn'] ) ) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
			$this->sortSwitchs        = ( isset( $this->data_cookie['sortSwitchs'] ) ) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->userHeaderNames    = ( isset( $this->data_cookie['userHeaderNames'] ) ) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
			$this->searchSwitchStatus = ( isset( $this->data_cookie['searchSwitchStatus'] ) ) ? $this->data_cookie['searchSwitchStatus'] : $this->searchSwitchStatus;
			$this->arr_search         = ( isset( $this->data_cookie['arr_search'] ) ) ? $this->data_cookie['arr_search'] : $this->arr_search;
			$this->totalRow           = ( isset( $this->data_cookie['totalRow'] ) ) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->selectedRow        = ( isset( $this->data_cookie['selectedRow'] ) ) ? $this->data_cookie['selectedRow'] : $this->selectedRow;

		} else {
			$this->action = 'default';
		}
	}

	/**
	 * Validation Search Parameters.
	 */
	public function validationSearchParameters() {
		if ( 'none' != $this->arr_search['column'] && ! in_array( $this->arr_search['column'], $this->columns ) ) {
			if ( is_array( $this->arr_search['word'] ) && count( $this->arr_search['word'] ) && in_array( $key = key( $this->arr_search['word'] ), $this->columns ) ) {
				$this->arr_search['column'] = $key;
			} else {
				$this->arr_search['column'] = 'none';
			}
		}
	}

	/**
	 * Get Rows.
	 *
	 * @return array
	 */
	public function GetRows() {
		global $wpdb;
		$item_table = $wpdb->prefix . 'usces_item';
		$sku_table  = $wpdb->prefix . 'usces_skus';
		$where      = $this->GetWhere();
		$order      = ' ORDER BY ' . $this->sortColumn . ' ' . $this->sortSwitchs[$this->sortColumn];

		if ( $this->exportMode ) {

			$query = 
				"SELECT post.ID, item.itemCode AS `item_code`, item.itemName AS `item_name` 
				FROM {$this->table} AS `post`
				LEFT JOIN {$item_table} AS `item` ON post.ID = item.post_id
				LEFT JOIN {$sku_table} AS `sku` ON post.ID = sku.post_id
				LEFT JOIN $wpdb->term_relationships AS `tr` ON post.ID = tr.object_id
				LEFT JOIN $wpdb->term_taxonomy AS `tt` ON tt.term_taxonomy_id = tr.term_taxonomy_id ";
		} else {

			$query = 
				"SELECT post.ID, item.itemCode AS `item_code`, item.itemName AS `item_name`, item.itemPicts AS `pictids` 
				FROM {$this->table} AS `post`
				LEFT JOIN {$item_table} AS `item` ON post.ID = item.post_id
				LEFT JOIN {$sku_table} AS `sku` ON post.ID = sku.post_id
				LEFT JOIN $wpdb->term_relationships AS `tr` ON post.ID = tr.object_id
				LEFT JOIN $wpdb->term_taxonomy AS `tt` ON tt.term_taxonomy_id = tr.term_taxonomy_id ";
		}

		$query .= $where . $order;// . $limit;

		$rows              = $wpdb->get_results( $query, ARRAY_A );
		$this->selectedRow = ( $rows && is_array( $rows ) ) ? count( $rows ) : 0;
		if ( 'off' === $this->pageLimit ) {
			$this->rows = $rows;
		} else {
			$this->rows = array_slice( (array) $rows, $this->startRow, $this->maxRow );
		}

		return $this->rows;
	}

	/**
	 * Set Total Rows.
	 */
	public function SetTotalRow() {
		global $wpdb;
		$query          = "SELECT COUNT(ID) AS `ct` FROM {$this->table} WHERE post_mime_type = 'item' AND post_type = 'post' AND post_status <> 'trash'";
		$res            = $wpdb->get_var( $query );
		$this->totalRow = $res;
	}

	/**
	 * Where Condition.
	 *
	 * @return string
	 */
	public function GetWhere() {
		$sql_where = apply_filters( 'usces_filter_item_class_sql_where', '', $this->searchSql, $this );
		if ( '' != $this->searchSql ) {
			if ( 'display_status' == $this->arr_search['column'] ) {
				$str = "WHERE post.post_mime_type = 'item' AND post.post_type = 'post' AND " . $this->searchSql . $sql_where . ' GROUP BY post.ID';
			} else {
				$str = "WHERE post.post_mime_type = 'item' AND post.post_type = 'post' AND post.post_status <> 'trash' AND " . $this->searchSql . $sql_where . ' GROUP BY post.ID';
			}
		} else {
			$str = "WHERE post.post_mime_type = 'item' AND post.post_type = 'post' AND post.post_status <> 'trash'" . $sql_where . ' GROUP BY post.ID';
		}
		return $str;
	}

	/**
	 * Set query search sql condition base on arr_search for item list.
	 *
	 * @return dataList $searchSql string search sql condition.
	 */
	public function setSearchSql() {
		switch ( $this->arr_search['column'] ) {
			case 'post_id':
				$column            = 'post.ID';
				$have_post_id_from = ! empty( $this->arr_search['word']['post_id_from'] );
				$have_post_id_to   = ! empty( $this->arr_search['word']['post_id_to'] );

				if ( $have_post_id_from ) {
					$this->searchSql = $column . '>=' . esc_sql( $this->arr_search['word']['post_id_from'] );
				}

				if ( $have_post_id_from && $have_post_id_to ) {
					$this->searchSql .= ' AND ';
				}

				if ( $have_post_id_to ) {
					$this->searchSql .= $column . '<=' . esc_sql( $this->arr_search['word']['post_id_to'] );
				}
				break;
			case 'item_code':
				$this->searchSql =  'item.itemCode LIKE '."'%" . esc_sql( $this->arr_search['word']['item_code'] ) . "%'";
				break;
			case 'item_name':
				$this->searchSql = 'item.itemName LIKE '."'%" . esc_sql( $this->arr_search['word']['item_name'] ) . "%'";
				break;
			case 'post_title':
				$this->searchSql = 'post.post_title LIKE '."'%" . esc_sql( $this->arr_search['word']['post_title'] ) . "%'";
				break;
			case 'zaiko_num':
				$this->searchSql = "sku.stocknum = '0'";
				break;
			case 'zaiko':
				$this->searchSql = "sku.stock = '" . esc_sql( $this->arr_search['word']['zaiko'] ) . "'";
				break;
			case 'category':
				$column = 'tt.term_id';
				$this->searchSql = "tt.term_id = '" . esc_sql( $this->arr_search['word']['category'] ) . "'";
				break;
			case 'display_status':
				$this->searchSql = "post.post_status = '" . esc_sql( $this->arr_search['word']['display_status'] ) . "'";
				break;
		}
	}

	/**
	 * Set Navigation.
	 */
	public function SetNavi() {

		$this->lastPage     = ceil( $this->selectedRow / $this->maxRow );
		$this->previousPage = ( ( $this->currentPage - 1 ) == 0 ) ? 1 : $this->currentPage - 1;
		$this->nextPage     = ( ( $this->currentPage + 1 ) > $this->lastPage ) ? $this->lastPage : $this->currentPage + 1;
		$box                = array();

		for ( $i = 0; $i < $this->naviMaxButton; $i++ ) {
			if ( $i > $this->lastPage - 1 ) {
				break;
			}
			if ( $this->lastPage <= $this->naviMaxButton ) {
				$box[] = $i + 1;
			} else {
				if ( $this->currentPage <= 6 ) {
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
		$html .= '<ul class="clearfix">' . "\n";
		$html .= '<li class="rowsnum">' . $this->selectedRow . ' / ' . $this->totalRow . ' ' . __( 'cases', 'usces' ) . '' . "\n";
		if ( ( 1 == $this->currentPage ) || ( 0 == $this->selectedRow ) ) {
			$html .= '<li class="navigationStr">first&lt;&lt;</li>' . "\n";
			$html .= '<li class="navigationStr">prev&lt;</li>' . "\n";
		} else {
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_itemedit&changePage=1">first&lt;&lt;</a></li>' . "\n";
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_itemedit&changePage=' . $this->previousPage . '">prev&lt;</a></li>' . "\n";
		}
		if ( $this->selectedRow > 0 ) {
			$box_count = count( $box );
			for ( $i = 0; $i < $box_count; $i++ ) {
				if ( $box[ $i ] == $this->currentPage ) {
					$html .= '<li class="navigationButtonSelected">' . $box[ $i ] . '</li>' . "\n";
				} else {
					$html .= '<li class="navigationButton"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_itemedit&changePage=' . $box[ $i ] . '">' . $box[ $i ] . '</a></li>' . "\n";
				}
			}
		}
		if ( ( $this->currentPage == $this->lastPage ) || ( 0 == $this->selectedRow ) ) {
			$html .= '<li class="navigationStr">&gt;next</li>' . "\n";
			$html .= '<li class="navigationStr">&gt;&gt;last</li>' . "\n";
		} else {
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_itemedit&changePage=' . $this->nextPage . '">&gt;next</a></li>' . "\n";
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_itemedit&changePage=' . $this->lastPage . '">&gt;&gt;last</a></li>' . "\n";
		}
		if ( 'OFF' == $this->searchSwitchStatus ) {
			$html .= '<li class="navigationStr"><a style="cursor:pointer;" id="searchVisiLink">' . __( 'Show the Operation field', 'usces' ) . '</a>' . "\n";
		} else {
			$html .= '<li class="navigationStr"><a style="cursor:pointer;" id="searchVisiLink">' . __( 'Hide the Operation field', 'usces' ) . '</a>' . "\n";
		}

		$html .= '<li class="refresh"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_itemedit&refresh">' . __( 'updates it to latest information', 'usces' ) . '</a></li>' . "\n";
		$html .= '</ul>' . "\n";

		$this->dataTableNavigation = $html;
	}

	/**
	 * Get Cookie.
	 */
	public function getCookie() {
		$this->data_cookie = ( isset( $_COOKIE[ $this->table ] ) ) ? json_decode( str_replace( "\'", "'", str_replace( '\"', '"', $_COOKIE[ $this->table ] ) ), true ) : array();
	}

	/**
	 * Set Headers.
	 */
	public function SetHeaders() {
		foreach ( $this->columns as $key => $value ) {
			if ( $value == $this->sortColumn ) {
				if ( 'ASC' == $this->sortSwitchs[ $value ] ) {
					$str    = __( '[ASC]', 'usces' );
					$switch = 'DESC';
				} elseif ( 'DESC' == $this->sortSwitchs[ $value ] ) {
					$str    = __( '[DESC]', 'usces' );
					$switch = 'default';
				} else {
					$str    = '';
					$switch = 'ASC';
				}
				if ( ( version_compare( USCES_MYSQL_VERSION, '5.0.0', '>=' ) && ( 'item_name' == $value || 'item_code' == $value ) ) || ( version_compare( USCES_MYSQL_VERSION, '5.0.0', '<' ) && 'post_title' == $value ) ) {
					$this->headers[ $value ] = '<a href="' . site_url() . '/wp-admin/admin.php?page=usces_itemedit&changeSort=' . $value . '&switch=' . $switch . '"><span class="sortcolumn">' . $key . ' ' . $str . '</span></a>';
				} else {
					$this->headers[ $value ] = '<span class="sortcolumn">' . $key . '</span>';
				}
			} else {
				$switch = $this->sortSwitchs[ $value ];
				if ( ( version_compare( USCES_MYSQL_VERSION, '5.0.0', '>=' ) && ( 'item_name' == $value || 'item_code' == $value ) ) || ( version_compare( USCES_MYSQL_VERSION, '5.0.0', '<' ) && 'post_title' == $value ) ) {
					$this->headers[ $value ] = '<a href="' . site_url() . '/wp-admin/admin.php?page=usces_itemedit&changeSort=' . $value . '&switch=' . $switch . '"><span>' . $key . '</span></a>';
				} else {
					$this->headers[ $value ] = '<span class="sortcolumn">' . $key . '</span>';
				}
			}
		}
	}

	/**
	 * Get Search.
	 *
	 * @return string
	 */
	public function GetSearchs() {
		return $this->arr_search;
	}

	/**
	 * Get Headers.
	 *
	 * @return string
	 */
	public function GetListheaders() {
		return $this->headers;
	}

	/**
	 * Get Navigation.
	 *
	 * @return string
	 */
	public function GetDataTableNavigation() {
		return $this->dataTableNavigation;
	}

	/**
	 * Set Action Status and Action Message.
	 *
	 * @param string $status Action status.
	 * @param string $message Action message.
	 */
	public function set_action_status( $status, $message ) {
		$this->action_status  = $status;
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
