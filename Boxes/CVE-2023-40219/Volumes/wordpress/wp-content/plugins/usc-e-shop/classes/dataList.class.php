<?php
class dataList
{
	var $table;			//テーブル名
	var $rows;			//データ
	var $action;		//アクション
	var $startRow;		//表示開始行番号
	var $maxRow;		//最大表示行数
	var $currentPage;	//現在のページNo
	var $firstPage;		//最初のページNo
	var $previousPage;	//前のページNo
	var $nextPage;		//次のページNo
	var $lastPage;		//最終ページNo
	var $naviMaxButton;	//ページネーション・ナビのボタンの数
	var $dataTableNavigation;	//ナヴィゲーションhtmlコード
	var $arr_period;	//表示データ期間
	var $arr_search;	//サーチ条件
	var $searchSql;		//簡易絞込みSQL
	var $searchSwitchStatus;	//サーチ表示スイッチ
	var $columns;		//データカラム
	var $sortColumn;	//現在ソート中のフィールド
	var $sortOldColumn;
	var $sortSwitchs;	//各フィールド毎の昇順降順スイッチ
	var $userHeaderNames;	//ユーザー指定のヘッダ名
	var $pageLimit;		//ページ制限
	var $placeholder_escape;
	var $data_cookie;

	//Constructor
	function __construct($tableName, $arr_column)
	{

		$this->table = $tableName;
		$this->columns = $arr_column;
		$this->rows = array();

		$this->maxRow = apply_filters( 'usces_filter_memberlist_maxrow', 30 );
		$this->naviMaxButton = 11;
		$this->firstPage = 1;
		$this->pageLimit = 'on';

        $this->getCookie();
        $this->SetDefaultParam();
        $this->SetParamByQuery();
        $this->validationSearchParameters();

		$this->arr_period = array(__('This month', 'usces'), __('Last month', 'usces'), __('The past one week', 'usces'), __('Last 30 days', 'usces'), __('Last 90days', 'usces'), __('All', 'usces'));


	}

	function MakeTable()
	{

		$this->SetParam();

		switch ($this->action){
			case 'searchOut':
				$this->SearchOut();
				$this->SetSelectedRow();
				$res = $this->GetRows();
				break;

			case 'changeSort':
			case 'changePage':
            case 'searchIn':
			case 'refresh':
			default:
                $this->SearchIn();
                $this->SetSelectedRow();
                $res = $this->GetRows();
				break;
		}

		$this->SetNavi();
		$this->SetHeaders();

		if($res){

			return TRUE;

		}else{
			return FALSE;
		}
	}

	//DefaultParam
	function SetDefaultParam()
	{
        $this->startRow = isset($this->data_cookie['startRow']) ? $this->data_cookie['startRow'] : 0;
        $this->currentPage = isset($this->data_cookie['currentPage']) ? $this->data_cookie['currentPage'] : 1;
        $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] :'ID';
        $this->searchSql = (isset($this->data_cookie['searchSql'])) ? $this->data_cookie['searchSql'] :'';
        $this->searchSwitchStatus = (isset($this->data_cookie['searchSwitchStatus'])) ? $this->data_cookie['searchSwitchStatus'] :'OFF';
        if (isset($this->data_cookie['arr_search'])) {
            $this->arr_search = $this->data_cookie['arr_search'];
        } else {
            $this->arr_search = array('period'=>'3', 'column'=>'', 'word'=>'');
        }
        if (isset($this->data_cookie['sortSwitchs'])) {
            $this->sortSwitchs = $this->data_cookie['sortSwitchs'];
        } else {
            foreach($this->columns as $key => $value ){
                $this->sortSwitchs[$value] = 'DESC';
            }
        }
		$this->SetTotalRow();
		$this->SetSelectedRow();

	}

	function SetParam()
	{
		$this->startRow = ($this->currentPage-1) * $this->maxRow;
	}

	function SetParamByQuery()
	{
		global $wpdb;

		if(isset($_REQUEST['changePage'])){

			$this->action = 'changePage';
			$this->currentPage = (int)$_REQUEST['changePage'];
            $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->searchSql = (isset($this->data_cookie['searchSql'])) ? $this->data_cookie['searchSql'] : $this->searchSql;
            $this->arr_search = (isset($this->data_cookie['arr_search'])) ? $this->data_cookie['arr_search'] : $this->arr_search;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
            $this->selectedRow = (isset($this->data_cookie['selectedRow'])) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
            $this->placeholder_escape = (isset($this->data_cookie['placeholder_escape'])) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

		}else if(isset($_REQUEST['changeSort'])){

			$this->action = 'changeSort';
			$this->sortOldColumn = $this->sortColumn;
			$this->sortColumn = str_replace('(', '', $_REQUEST['changeSort']);
			$this->sortColumn = str_replace(',', '', $this->sortColumn);
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
			$this->sortSwitchs[$this->sortColumn] = $_REQUEST['switch'];
            $this->currentPage = (isset($this->data_cookie['currentPage'])) ? $this->data_cookie['currentPage'] : $this->currentPage;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->searchSql = (isset($this->data_cookie['searchSql'])) ? $this->data_cookie['searchSql'] : $this->searchSql;
            $this->searchSwitchStatus = (isset($this->data_cookie['searchSwitchStatus'])) ? $this->data_cookie['searchSwitchStatus'] : $this->searchSwitchStatus;
            $this->arr_search = (isset($this->data_cookie['arr_search'])) ? $this->data_cookie['arr_search'] : $this->arr_search;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
            $this->selectedRow = (isset($this->data_cookie['selectedRow'])) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
            $this->placeholder_escape = (isset($this->data_cookie['placeholder_escape'])) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

		} else if(isset($_REQUEST['searchIn'])){

			$this->action = 'searchIn';
			$this->arr_search['column'] = str_replace('`', '', $_REQUEST['search']['column']);
			$this->arr_search['word'] = $_REQUEST['search']['word'];
			$this->arr_search['period'] = isset($_REQUEST['search']['period']) ? intval($_REQUEST['search']['period']) : 0;
			$this->searchSwitchStatus = str_replace(',', '', $_REQUEST['searchSwitchStatus']);

			$this->currentPage = 1;
			$this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->placeholder_escape = $wpdb->placeholder_escape();

		}else if(isset($_REQUEST['searchOut'])){

			$this->action = 'searchOut';
			$this->arr_search['column'] = '';
			$this->arr_search['word'] = '';
			$this->arr_search['period'] = (isset($this->data_cookie['arr_search']['period'])) ? $this->data_cookie['arr_search']['period'] : $this->arr_search['period'];
			$this->searchSwitchStatus = str_replace(',', '', $_REQUEST['searchSwitchStatus']);

			$this->currentPage = 1;
            $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
			$this->placeholder_escape = '';

		}else if(isset($_REQUEST['refresh'])){

			$this->action = 'refresh';

			$this->currentPage = (isset($this->data_cookie['currentPage'])) ? $this->data_cookie['currentPage'] : $this->currentPage;
            $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->searchSql = (isset($this->data_cookie['searchSql'])) ? $this->data_cookie['searchSql'] : $this->searchSql;
            $this->searchSwitchStatus = (isset($this->data_cookie['searchSwitchStatus'])) ? $this->data_cookie['searchSwitchStatus'] : $this->searchSwitchStatus;
            $this->arr_search = (isset($this->data_cookie['arr_search'])) ? $this->data_cookie['arr_search'] : $this->arr_search;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
            $this->selectedRow = (isset($this->data_cookie['selectedRow'])) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
            $this->placeholder_escape = '';

		}else{

			$this->action = 'default';
			$this->placeholder_escape = '';
		}
	}

    function validationSearchParameters(){
        if( 'none' != $this->arr_search['column'] && !in_array($this->arr_search['column'], $this->columns)){
            $this->arr_search['column'] = reset($this->columns);
        }
    }

	//GetRows
	function GetRows()
	{
		global $wpdb;

		$member_meta_table = usces_get_tablename( 'usces_member_meta' );

		if($this->arr_search['column'] == 'none' || WCUtils::is_blank($this->arr_search['column']) || WCUtils::is_blank($this->arr_search['word']) ){
			$join = "";
		}else{
			if( 'csmb_' === substr($this->arr_search['column'], 0, 5) ){
				$join = $wpdb->prepare(" LEFT JOIN {$member_meta_table} AS `mm` ON ID=mm.member_id AND mm.meta_key = %s", esc_sql($this->arr_search['column']) );
			}else{
				$join = "";
			}
		}

		$where = $this->GetWhere();
		$switch = ( 'ASC' == $this->sortSwitchs[$this->sortColumn] ) ? 'ASC' : 'DESC';
		$order = ' ORDER BY `' . esc_sql($this->sortColumn) . '` ' . $switch;
		if($this->pageLimit == 'on') {
			$limit = $wpdb->prepare(' LIMIT %d, %d', $this->startRow, $this->maxRow);
		}else{
			$limit = '';
		}
		$mem_point = ( usces_is_membersystem_point() ) ? ', mem_point AS `point`' : '';
		$query = $wpdb->prepare("SELECT ID, CONCAT(mem_name1, ' ', mem_name2) AS `name`,
						CONCAT(mem_pref, mem_address1, mem_address2, ' ', mem_address3) AS `address`,
						mem_tel AS `tel`, mem_email AS `email`, DATE_FORMAT(mem_registered, %s) AS `date`{$mem_point}
					FROM {$this->table}",
					'%Y-%m-%d %H:%i');

		$query .= $join . $where . $order . $limit;

		if( $this->placeholder_escape ){
			add_filter( 'query', array( $this, 'remove_ph') );
		}

		$this->rows = $wpdb->get_results($query, ARRAY_A);
		return $this->rows;
	}

	public function remove_ph( $query ) {
		return str_replace( $this->placeholder_escape, '%', $query );
	}

	function SetTotalRow()
	{
		global $wpdb;
		$query = "SELECT COUNT(ID) AS `ct` FROM {$this->table}";
		$res = $wpdb->get_var($query);
		$this->totalRow = $res;
	}

	function SetSelectedRow()
	{
		global $wpdb;

		$member_meta_table = usces_get_tablename( 'usces_member_meta' );
		if($this->arr_search['column'] == 'none' || WCUtils::is_blank($this->arr_search['column']) || WCUtils::is_blank($this->arr_search['word']) ){
			$join = "";
		}else{
			if( 'csmb_' === substr($this->arr_search['column'], 0, 5) ){
				$join = $wpdb->prepare(" LEFT JOIN {$member_meta_table} AS `mm` ON ID=mm.member_id AND mm.meta_key = %s", esc_sql($this->arr_search['column']) );
			}else{
				$join = "";
			}
		}
		$where = $this->GetWhere();
		$mem_point = ( usces_is_membersystem_point() ) ? ', mem_point AS `point`' : '';
		$query = $wpdb->prepare("SELECT ID, CONCAT(mem_name1, ' ', mem_name2) AS `name`,
						CONCAT(mem_pref, mem_address1, mem_address2, ' ', mem_address3) AS `address`,
						mem_tel AS `tel`, mem_email AS `email`, DATE_FORMAT(mem_registered, %s) AS `date`{$mem_point}
					FROM {$this->table}",
					'%Y-%m-%d %H:%i');
		$query .= $join . $where;
		$rows = $wpdb->get_results($query, ARRAY_A);
		$this->selectedRow = ( $rows && is_array( $rows ) ) ? count( $rows ) : 0;

	}

	function GetWhere()
	{
		$str = '';
		$thismonth = date('Y-m-01 00:00:00');
		$lastmonth = date('Y-m-01 00:00:00', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
		$lastweek = date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m'), date('d')-7, date('Y')));
		$last30 = date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m'), date('d')-30, date('Y')));
		$last90 = date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m'), date('d')-90, date('Y')));

		if(!WCUtils::is_blank($this->searchSql)){
			if( 'csmb_' === substr($this->arr_search['column'], 0, 5) ){
				$str .= ' WHERE ' . $this->searchSql;
			}else{
				$str .= ' HAVING ' . $this->searchSql;
			}
		}

		return $str;
	}

	function SearchIn()
	{
		global $wpdb;
		if($this->arr_search['column'] == 'none' || WCUtils::is_blank($this->arr_search['column']) || WCUtils::is_blank($this->arr_search['word']) ){
			$this->searchSql = '';
		}else{
			if( 'csmb_' === substr($this->arr_search['column'], 0, 5) ){
				$this->searchSql = $wpdb->prepare('`meta_value` LIKE %s', "%".$this->arr_search['word']."%");
			}else{
				$this->searchSql = $wpdb->prepare('`' . esc_sql($this->arr_search['column']) . '` LIKE %s', "%".$this->arr_search['word']."%");
			}
		}
	}

	function SearchOut()
	{
		$this->searchSql = '';
	}

	function SetNavi()
	{

		$this->lastPage = ceil($this->selectedRow / $this->maxRow);
		$this->previousPage = ($this->currentPage - 1 == 0) ? 1 : $this->currentPage - 1;
		$this->nextPage = ($this->currentPage + 1 > $this->lastPage) ? $this->lastPage : $this->currentPage + 1;
		$box = array();

		for($i=0; $i<$this->naviMaxButton; $i++){
			if($i > $this->lastPage-1) break;
			if($this->lastPage <= $this->naviMaxButton) {
				$box[] = $i+1;
			}else{
				if($this->currentPage <= 6) {
					$label = $i + 1;
					$box[] = $label;
				}else{
					$label = $i + 1 + $this->currentPage - 6;
					$box[] = $label;
					if($label == $this->lastPage) break;
				}
			}
		}

		$html = '';
		$html .= '<ul class="clearfix">'."\n";
		$html .= '<li class="rowsnum">' . $this->selectedRow . ' / ' . $this->totalRow . ' ' . __('cases', 'usces') . '</li>' . "\n";
		if(($this->currentPage == 1) || ($this->selectedRow == 0)){
			$html .= '<li class="navigationStr">first&lt;&lt;</li>' . "\n";
			$html .= '<li class="navigationStr">prev&lt;</li>'."\n";
		}else{
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changePage=1">first&lt;&lt;</a></li>' . "\n";
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changePage=' . $this->previousPage . '">prev&lt;</a></li>'."\n";
		}
		if($this->selectedRow > 0) {
			$box_count = count( $box );
			for($i=0; $i<$box_count; $i++){
				if($box[$i] == $this->currentPage){
					$html .= '<li class="navigationButtonSelected">' . $box[$i] . '</li>'."\n";
				}else{
					$html .= '<li class="navigationButton"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changePage=' . $box[$i] . '">' . $box[$i] . '</a></li>'."\n";
				}
			}
		}
		if(($this->currentPage == $this->lastPage) || ($this->selectedRow == 0)){
			$html .= '<li class="navigationStr">&gt;next</li>'."\n";
			$html .= '<li class="navigationStr">&gt;&gt;last</li>'."\n";
		}else{
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changePage=' . $this->nextPage . '">&gt;next</a></li>'."\n";
			$html .= '<li class="navigationStr"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changePage=' . $this->lastPage . '">&gt;&gt;last</a></li>'."\n";
		}
		if($this->searchSwitchStatus == 'OFF'){
			$html .= '<li class="rowsnum"><a style="cursor:pointer;" id="searchVisiLink">' . __('Show the Operation field', 'usces') . '</a>'."\n";
		}else{
			$html .= '<li class="rowsnum"><a style="cursor:pointer;" id="searchVisiLink">' . __('Hide the Operation field', 'usces') . '</a>'."\n";
		}

		$html .= '<li class="refresh"><a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&refresh">' . __('updates it to latest information', 'usces') . '</a></li>' . "\n";
		$html .= '</ul>'."\n";

		$this->dataTableNavigation = $html;
	}


    function getCookie(){
        $this->data_cookie = (isset($_COOKIE[$this->table])) ? json_decode(str_replace("\'","'",str_replace('\"','"', $_COOKIE[$this->table])),true) : [];
    }

	function SetHeaders()
	{
		foreach ($this->columns as $key => $value){
			if($value == $this->sortColumn){
				if($this->sortSwitchs[$value] == 'ASC'){
					$str = __('[ASC]', 'usces');
					$switch = 'DESC';
				}else{
					$str = __('[DESC]', 'usces');
					$switch = 'ASC';
				}
				$this->headers[$value] = '<a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changeSort=' . $value . '&switch=' . $switch . '"><span class="sortcolumn">' . $key . ' ' . $str . '</span></a>';
			}else{
				$switch = $this->sortSwitchs[$value];
				$this->headers[$value] = '<a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changeSort=' . $value . '&switch=' . $switch . '"><span>' . $key . '</span></a>';
			}
		}
	}

	function GetSearchs()
	{
		return $this->arr_search;
	}

	function GetListheaders()
	{
		return $this->headers;
	}

	function GetDataTableNavigation()
	{
		return $this->dataTableNavigation;
	}

}
