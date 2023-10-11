<?php
class WlcMemberList
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
	var $searchWhere;		//オーダーカラム
	var $searchHaving;		//メンバーカラム
	var $columns;		//データカラム
	var $sortColumn;	//現在ソート中のフィールド
	var $sortOldColumn;
	var $sortSwitchs;	//各フィールド毎の昇順降順スイッチ
	var $userHeaderNames;	//ユーザー指定のヘッダ名
	var $pageLimit;		//ページ制限
	var $listOption;		//ページ制限
	var $csmb_meta;
	var $admb_meta;
	var $csod_meta;
	var $action_status;
	var $action_message;
	var $currentPageIds;
	var $placeholder_escape;
    var $data_cookie;

	//Constructor
	function __construct(){
		global $wpdb;
		$this->csmb_meta = usces_has_custom_field_meta('member');
		$this->admb_meta = usces_has_custom_field_meta( 'admin_member' );
		$this->csod_meta = usces_has_custom_field_meta('order');

		$this->listOption = get_option( 'usces_memberlist_option' );

		$this->table = usces_get_tablename( 'usces_member' );
		$this->set_column();
		$this->rows = array();

		$this->maxRow = $this->listOption['max_row'];
		$this->naviMaxButton = 11;
		$this->firstPage = 1;
		$this->pageLimit = 'on';
		$this->action_status = 'none';
		$this->action_message = '';

        $this->getCookie();
        $this->SetDefaultParam();
		$this->SetParamByQuery();
        $this->validationSearchParameters();
		$this->arr_period = array(__('This month', 'usces'), __('Last month', 'usces'), __('The past one week', 'usces'), __('Last 30 days', 'usces'), __('Last 90days', 'usces'), __('All', 'usces'));

		$wpdb->query( 'SET SQL_BIG_SELECTS=1' );
	}

	function set_column(){

		$arr_column = array();
		$arr_column['ID'] = __('membership number', 'usces');
		$arr_column['name1'] = __('Last Name', 'usces');
		$arr_column['name2'] = __('First Name', 'usces');
		$arr_column['name3'] = __('Last Furigana', 'usces');
		$arr_column['name4'] = __('First Furigana', 'usces');
		$arr_column['zipcode'] = __('Zip', 'usces');
		$arr_column['country'] = __('Country', 'usces');
		$arr_column['pref'] = __('Province', 'usces');
		$arr_column['address1'] = __('city', 'usces');
		$arr_column['address2'] = __('numbers', 'usces');
		$arr_column['address3'] = __('building name', 'usces');
		$arr_column['tel'] = __('Phone number', 'usces');
		$arr_column['fax'] = __('FAX number', 'usces');
		$arr_column['email'] = __('e-mail', 'usces');
		$arr_column['entrydate'] = __('Entry date', 'usces');
		$arr_column['rank'] = __('Rank', 'usces');
		$arr_column['point'] = __('current point', 'usces');


		foreach ( (array)$this->csmb_meta as $key => $value ){
			$csmb_key = 'csmb_' . $key;
			$csmb_name = $value['name'];
			$arr_column[$csmb_key] = $csmb_name;
		}

		foreach ( (array) $this->admb_meta as $key => $value ) {
			$admb_key                = 'admb_' . $key;
			$admb_name               = $value['name'];
			$arr_column[ $admb_key ] = $admb_name;
		}

		foreach ( (array)$this->csod_meta as $key => $value ){
			$csod_key = 'csod_' . $key;
			$csod_name = $value['name'];
			$arr_column[$csod_key] = $csod_name;
		}


		$arr_column = apply_filters( 'usces_filter_memberlist_column', $arr_column, $this );
		$this->columns = $arr_column;
	}

	function get_column(){
		return $this->columns;
	}

    function MakeTable(){

        $this->SetParam();

        switch ($this->action){

            case 'searchOut':
                $this->SearchOut();
                $res = $this->GetRows();
                break;

            case 'collective_rank':
                check_admin_referer( 'member_list', 'wc_nonce' );
                usces_all_change_member_rank($this);
				$this->SearchIn();
                $res = $this->GetRows();
                break;

            case 'collective_point':
                check_admin_referer( 'member_list', 'wc_nonce' );
                usces_all_change_member_point($this);
				$this->SearchIn();
                $res = $this->GetRows();
                break;

            case 'collective_delete':
                check_admin_referer( 'member_list', 'wc_nonce' );
                usces_all_delete_member_data($this);
				$this->SearchIn();
                $res = $this->GetRows();
                break;
            case 'searchIn':
            case 'returnList':
            case 'changeSort':
            case 'changePage':
            case 'refresh':
            default:
                $this->SearchIn();
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
    function SetDefaultParam(){
        $this->startRow = isset($this->data_cookie['startRow']) ? $this->data_cookie['startRow'] : 0;
        $this->currentPage = isset($this->data_cookie['currentPage']) ? $this->data_cookie['currentPage'] : 1;
        $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] :'ID';
        $this->searchWhere = (isset($this->data_cookie['searchWhere'])) ? $this->data_cookie['searchWhere'] :'';
        $this->searchHaving = (isset($this->data_cookie['searchHaving'])) ? $this->data_cookie['searchHaving'] :'';
        if (isset($this->data_cookie['arr_search'])) {
            $this->arr_search = $this->data_cookie['arr_search'];
        } else {
            $this->arr_search = array('period'=>'3', 'member_column'=>array('',''), 'member_word'=>array('',''), 'member_word_term'=>array('contain','contain'), 'member_term'=>'AND', 'order_column'=>array('',''), 'order_word'=>array('',''), 'order_word_term'=>array('contain','contain'), 'order_term'=>'AND' );
        }
        if (isset($this->data_cookie['sortSwitchs'])) {
            $this->sortSwitchs = $this->data_cookie['sortSwitchs'];
			foreach($this->csmb_meta as $key => $value){
				$csmb_key = 'csmb_' . $key;
				if(!isset($this->sortSwitchs[$csmb_key])) $this->sortSwitchs[$csmb_key] = 'DESC';
			}
			foreach ( $this->admb_meta as $key => $value ) {
				$admb_key = 'admb_' . $key;
				if ( ! isset( $this->sortSwitchs[ $admb_key ] ) ) {
					$this->sortSwitchs[ $admb_key ] = 'DESC';
				}
			}
        } else {
			$this->sortSwitchs[ $this->sortColumn ] = 'DESC';
        }

        $this->SetTotalRow();
    }

	function SetParam(){

		$this->startRow = ($this->currentPage-1) * $this->maxRow;
	}

    function SetParamByQuery(){
        global $wpdb;
        if(isset($_REQUEST['changePage'])){

            $this->action = 'changePage';
            $this->currentPage = (int)$_REQUEST['changePage'];
            $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->arr_search = (isset($this->data_cookie['arr_search'])) ? $this->data_cookie['arr_search'] : $this->arr_search;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
            $this->selectedRow = (isset($this->data_cookie['selectedRow'])) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
            $this->placeholder_escape = (isset($this->data_cookie['placeholder_escape'])) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

        }else if(isset($_REQUEST['returnList'])){

            $this->action = 'returnList';
            $this->currentPage = (isset($this->data_cookie['currentPage'])) ? $this->data_cookie['currentPage'] : $this->currentPage;
            $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
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
            $this->sortSwitchs[$this->sortColumn] = ('ASC' == $_REQUEST['switch']) ? 'ASC' : 'DESC';
            $this->currentPage = (isset($this->data_cookie['currentPage'])) ? $this->data_cookie['currentPage'] : $this->currentPage;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->arr_search = (isset($this->data_cookie['arr_search'])) ? $this->data_cookie['arr_search'] : $this->arr_search;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
            $this->selectedRow = (isset($this->data_cookie['selectedRow'])) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
            $this->placeholder_escape = (isset($this->data_cookie['placeholder_escape'])) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

        } else if(isset($_REQUEST['searchIn'])){

            $this->action = 'searchIn';
            $this->arr_search['member_column'][0] = !WCUtils::is_blank($_REQUEST['search']['member_column'][0]) ? str_replace('`', '', $_REQUEST['search']['member_column'][0]) : '';
            $this->arr_search['member_column'][1] = !WCUtils::is_blank($_REQUEST['search']['member_column'][1]) ? str_replace('`', '', $_REQUEST['search']['member_column'][1]) : '';
            $this->arr_search['member_word'][0] = !WCUtils::is_blank($_REQUEST['search']['member_word'][0]) ? trim($_REQUEST['search']['member_word'][0]) : '';
            $this->arr_search['member_word'][1] = !WCUtils::is_blank($_REQUEST['search']['member_word'][1]) ? trim($_REQUEST['search']['member_word'][1]) : '';
            $this->arr_search['member_word_term'][0] = isset($_REQUEST['search']['member_word_term'][0]) ? $_REQUEST['search']['member_word_term'][0] : 'contain';
            $this->arr_search['member_word_term'][1] = isset($_REQUEST['search']['member_word_term'][1]) ? $_REQUEST['search']['member_word_term'][1] : 'contain';
            if( WCUtils::is_blank($_REQUEST['search']['member_column'][0]) ){
                $this->arr_search['member_column'][1] = '';
                $this->arr_search['member_word'][0] = '';
                $this->arr_search['member_word'][1] = '';
                $this->arr_search['member_word_term'][0] = 'contain';
                $this->arr_search['member_word_term'][1] = 'contain';
            }
            $this->arr_search['member_term'] = $_REQUEST['search']['member_term'];
            $this->arr_search['order_column'][0] = !WCUtils::is_blank($_REQUEST['search']['order_column'][0]) ? str_replace('`', '', $_REQUEST['search']['order_column'][0]) : '';
            $this->arr_search['order_column'][1] = !WCUtils::is_blank($_REQUEST['search']['order_column'][1]) ? str_replace('`', '', $_REQUEST['search']['order_column'][1]) : '';
            $this->arr_search['order_word'][0] = !WCUtils::is_blank($_REQUEST['search']['order_word'][0]) ? trim($_REQUEST['search']['order_word'][0]) : '';
            $this->arr_search['order_word'][1] = !WCUtils::is_blank($_REQUEST['search']['order_word'][1]) ? trim($_REQUEST['search']['order_word'][1]) : '';
            $this->arr_search['order_word_term'][0] = isset($_REQUEST['search']['order_word_term'][0]) ? $_REQUEST['search']['order_word_term'][0] : 'contain';
            $this->arr_search['order_word_term'][1] = isset($_REQUEST['search']['order_word_term'][1]) ? $_REQUEST['search']['order_word_term'][1] : 'contain';
            if( WCUtils::is_blank($_REQUEST['search']['order_column'][0]) ){
                $this->arr_search['order_column'][1] = '';
                $this->arr_search['order_word'][0] = '';
                $this->arr_search['order_word'][1] = '';
                $this->arr_search['order_word_term'][0] = 'contain';
                $this->arr_search['order_word_term'][1] = 'contain';
            }
            $this->arr_search['order_term'] = $_REQUEST['search']['order_term'];
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
            $this->arr_search['member_column'][0] = '';
            $this->arr_search['member_column'][1] = '';
            $this->arr_search['member_word'][0] = '';
            $this->arr_search['member_word'][1] = '';
            $this->arr_search['member_word_term'][0] = 'contain';
            $this->arr_search['member_word_term'][1] = 'contain';
            $this->arr_search['member_term'] = 'AND';
            $this->arr_search['order_column'][0] = '';
            $this->arr_search['order_column'][1] = '';
            $this->arr_search['order_word'][0] = '';
            $this->arr_search['order_word'][1] = '';
            $this->arr_search['order_word_term'][0] = 'contain';
            $this->arr_search['order_word_term'][1] = 'contain';
            $this->arr_search['order_term'] = 'AND';
            $this->currentPage = 1;
            $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
            $this->placeholder_escape = '';

        }else if(isset($_REQUEST['collective'])){

            $this->action = 'collective_' . str_replace(',', '', $_POST['allchange']['column']);
            $this->currentPage = (isset($this->data_cookie['currentPage'])) ? $this->data_cookie['currentPage'] : $this->currentPage;
            $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
            $this->arr_search = (isset($this->data_cookie['arr_search'])) ? $this->data_cookie['arr_search'] : $this->arr_search;
            $this->totalRow = (isset($this->data_cookie['totalRow'])) ? $this->data_cookie['totalRow'] : $this->totalRow;
            $this->selectedRow = (isset($this->data_cookie['selectedRow'])) ? $this->data_cookie['selectedRow'] : $this->selectedRow;
            $this->selectedRow = (isset($this->data_cookie['placeholder_escape'])) ? $this->data_cookie['placeholder_escape'] : $this->placeholder_escape;

        }else if(isset($_REQUEST['refresh'])){

            $this->action = 'refresh';
            $this->currentPage = (isset($this->data_cookie['currentPage'])) ? $this->data_cookie['currentPage'] : $this->currentPage;
            $this->sortColumn = (isset($this->data_cookie['sortColumn'])) ? $this->data_cookie['sortColumn'] : $this->sortColumn;
            $this->sortSwitchs = (isset($this->data_cookie['sortSwitchs'])) ? $this->data_cookie['sortSwitchs'] : $this->sortSwitchs;
            $this->userHeaderNames = (isset($this->data_cookie['userHeaderNames'])) ? $this->data_cookie['userHeaderNames'] : $this->userHeaderNames;
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
        $default_member_word_term = ['contain', 'notcontain', 'equal', 'morethan', 'lessthan'];
        $default_order_word_term = ['contain', 'notcontain', 'equal', 'morethan', 'lessthan'];
        $default_order_term = ['AND', 'OR'];
        $default_member_term = ['AND', 'OR'];
        $default_order_columns = ['item_code', 'item_name', 'sku_code', 'sku_name','item_option'];
        if(!empty($this->arr_search['member_column'][0]) && !array_key_exists($this->arr_search['member_column'][0], $this->columns)){
            $this->arr_search['member_column'][0] = key($this->columns);
        }
        if(!empty($this->arr_search['member_column'][1]) && !array_key_exists($this->arr_search['member_column'][1], $this->columns)){
            $this->arr_search['member_column'][1] = key($this->columns);
        }

        if(!empty($this->arr_search['member_word_term'][0]) && !in_array($this->arr_search['member_word_term'][0], $default_member_word_term)){
            $this->arr_search['member_word_term'][0] = $default_order_word_term[0];
        }
        if(!empty($this->arr_search['member_word_term'][1]) && !in_array($this->arr_search['member_word_term'][1], $default_order_word_term)){
            $this->arr_search['member_word_term'][1] = $default_order_word_term[0];
        }
        if(!in_array($this->arr_search['member_term'], $default_member_term)){
            $this->arr_search['member_term'] = $default_member_term[0];
        }

        if(!empty($this->arr_search['order_column'][0]) && !in_array($this->arr_search['order_column'][0], $default_order_columns)){
            $this->arr_search['order_column'][0] = $default_order_columns[0];
        }
        if(!empty($this->arr_search['order_column'][1]) && !in_array($this->arr_search['order_column'][1], $default_order_columns)){
            $this->arr_search['order_column'][1] = $default_order_columns[0];
        }

        if(!empty($this->arr_search['order_word_term'][0]) && !in_array($this->arr_search['order_word_term'][0], $default_order_word_term)){
            $this->arr_search['order_word_term'][0] = $default_order_word_term[0];
        }
        if(!empty($this->arr_search['order_word_term'][1]) && !in_array($this->arr_search['order_word_term'][1], $default_order_word_term)){
            $this->arr_search['order_word_term'][1] = $default_order_word_term[0];
        }
        if(!in_array($this->arr_search['order_term'], $default_order_term)){
            $this->arr_search['order_term'] = $default_order_term[0];
        }
    }

	//GetRows
	function GetRows(){
		global $wpdb;

		$member_meta_table = usces_get_tablename( 'usces_member_meta' );
		$order_table = $wpdb->prefix.'usces_order';
		$order_meta_table = $wpdb->prefix.'usces_order_meta';
		$ordercart_table = $wpdb->prefix.'usces_ordercart';
		$ordercart_meta_table = $wpdb->prefix.'usces_ordercart_meta';

		$where = $this->GetWhere();
		$having = $this->GetHaving();

		$join = "";
		$csmb = "";
        $admb = "";
		$csod = "";
		foreach($this->columns as $key => $value ){
			if( 'csmb_' === substr($key, 0, 5) ){
				$join .= $wpdb->prepare(" LEFT JOIN {$member_meta_table} AS `p{$key}` ON mem.ID = `p{$key}`.member_id AND `p{$key}`.meta_key = %s ", $key ) . "\n";
				$csmb .= ', `p' . $key . '`.meta_value AS `' . $key . "`\n";
			}
            if( 'admb_' === substr( $key, 0, 5 ) ){
                $join .= $wpdb->prepare( " LEFT JOIN {$member_meta_table} AS `p{$key}` ON mem.ID = `p{$key}`.member_id AND `p{$key}`.meta_key = %s ", $key ) . "\n";
                $admb .= ', `p' . $key . '`.meta_value AS `' . $key . "`\n";
            }
		}

		if( $where ){
			$join .= " LEFT JOIN {$order_table} AS `ord` ON mem.ID = ord.mem_id " . "\n";

			foreach($this->columns as $key => $value ){
				if( 'csod_' === substr($key, 0, 5) ){
					$join .= $wpdb->prepare(" LEFT JOIN {$order_meta_table} AS `p{$key}` ON ord.ID = `p{$key}`.order_id AND `p{$key}`.meta_key = %s ", $key ) . "\n";
					$csod .= ', `p' . $key . '`.meta_value ' . "\n";
				}
			}

			$join .= " LEFT JOIN {$ordercart_table} AS `cart` ON ord.ID = cart.order_id " . "\n";
			$csod .= ', cart.item_code , cart.item_name , cart.sku_code , cart.sku_name ' . "\n";
		}
		$join = apply_filters( 'usces_filter_memberlist_sql_jointable', $join, $this );

		$group = ' GROUP BY `ID` ';
		$switch = ( isset( $this->sortSwitchs[$this->sortColumn] ) && 'ASC' == $this->sortSwitchs[$this->sortColumn] ) ? 'ASC' : 'DESC';

		$order = ' ORDER BY `' . esc_sql($this->sortColumn) . '` ' . $switch;
		$order = apply_filters( 'usces_filter_memberlist_sql_order', $order, $this->sortColumn, $switch, $this );

		$query = $wpdb->prepare("SELECT 
			mem.ID AS `ID`, 
			mem.mem_name1 AS `name1`, 
			mem.mem_name2 AS `name2`, 
			mem.mem_name3 AS `name3`, 
			mem.mem_name4 AS `name4`, 
			mem.mem_zip AS `zipcode`, 
			country.meta_value AS `country`, 
			mem.mem_pref AS `pref`, 
			mem.mem_address1 AS `address1`, 
			mem.mem_address2 AS `address2`, 
			mem.mem_address3 AS `address3`, 
			mem.mem_tel AS `tel`, 
			mem.mem_fax AS `fax`, 
			mem.mem_email AS `email`, 
			DATE_FORMAT(mem.mem_registered, %s) AS `entrydate`, 
			mem.mem_status AS `rank`, 
			mem.mem_point AS `point` 
			{$csmb}
			{$admb}
			{$csod}
			FROM {$this->table} AS `mem` 
			LEFT JOIN {$member_meta_table} AS `country` ON mem.ID = country.member_id AND country.meta_key = %s ", '%Y-%m-%d %H:%i', 'customer_country');
		$query = apply_filters( 'usces_filter_memberlist_sql_select', $query, $csmb, $csod, $this );

		$query .= $join . $where . $group . $having . $order;

		if( $this->placeholder_escape ){
			add_filter( 'query', array( $this, 'remove_ph') );
		}

		$rows = $wpdb->get_results($query, ARRAY_A);

		$this->selectedRow = ( $rows && is_array( $rows ) ) ? count( $rows ) : 0;
		if($this->pageLimit == 'on') {
			$this->rows = array_slice($rows, $this->startRow, $this->maxRow);
			$this->currentPageIds = array();
			foreach($this->rows as $row){
				$this->currentPageIds[] = $row['ID'];
			}
		}else{
			$this->rows = $rows;
		}
		return $this->rows;
	}

	public function remove_ph( $query ) {
		return str_replace( $this->placeholder_escape, '%', $query );
	}

	function SetTotalRow(){
		global $wpdb;
		$query = "SELECT COUNT(ID) AS `ct` FROM {$this->table}";
		$res = $wpdb->get_var($query);
		$this->totalRow = $res;
	}

	function GetHaving(){
		global $wpdb;

		$str = '';
		if(!WCUtils::is_blank($this->searchHaving)){
			$str .= ' HAVING ' . $this->searchHaving;
		}
		$str = apply_filters( 'usces_filter_memberlist_sql_having', $str, $this->searchHaving, $this );
		return $str;
	}

	function GetWhere(){
		$str = '';
		if(!WCUtils::is_blank($this->searchWhere)){
			$str .= ' WHERE ' . $this->searchWhere;
		}
		$str = apply_filters( 'usces_filter_memberlist_sql_where', $str, $this->searchWhere, $this );
		return $str;
	}

	function SearchIn(){
		global $wpdb;

		$this->searchWhere = '';
		$this->searchHaving = '';

		if( !empty($this->arr_search['order_column'][0]) && !WCUtils::is_blank($this->arr_search['order_word'][0]) ){
			switch( $this->arr_search['order_word_term'][0] ){
				case 'notcontain':
					$wordterm0 = ' NOT LIKE %s';
					$word0 = "%".$this->arr_search['order_word'][0]."%";
					break;
				case 'equal':
					$wordterm0 = ' = %s';
					$word0 = $this->arr_search['order_word'][0];
					break;
				case 'morethan':
					$wordterm0 = ' > %d';
					$word0 = $this->arr_search['order_word'][0];
					break;
				case 'lessthan':
					$wordterm0 = ' < %d';
					$word0 = $this->arr_search['order_word'][0];
					break;
				case 'contain':
				default:
					$wordterm0 = ' LIKE %s';
					$word0 = "%".$this->arr_search['order_word'][0]."%";
					break;
			}

			switch( $this->arr_search['order_word_term'][1] ){
				case 'notcontain':
					$wordterm1 = ' NOT LIKE %s';
					$word1 = "%".$this->arr_search['order_word'][1]."%";
					break;
				case 'equal':
					$wordterm1 = ' = %s';
					$word1 = $this->arr_search['order_word'][1];
					break;
				case 'morethan':
					$wordterm1 = ' > %d';
					$word1 = $this->arr_search['order_word'][1];
					break;
				case 'lessthan':
					$wordterm1 = ' < %d';
					$word1 = $this->arr_search['order_word'][1];
					break;
				case 'contain':
				default:
					$wordterm1 = ' LIKE %s';
					$word1 = "%".$this->arr_search['order_word'][1]."%";
					break;
			}

			$this->searchWhere .= ' ( ';

			if( 'csod_' == substr($this->arr_search['order_column'][0], 0, 5) ){
				$this->searchWhere .= $wpdb->prepare('`p' . esc_sql($this->arr_search['order_column'][0]) . '`.meta_value LIKE %s', "%".$this->arr_search['order_word'][0]."%");
			}else{
				$this->searchWhere .= '`' . $wpdb->prepare( esc_sql($this->arr_search['order_column'][0]) . '`' . $wordterm0, $word0);
			}
			if( !empty($this->arr_search['order_column'][1]) && !WCUtils::is_blank($this->arr_search['order_word'][1]) ){
				$this->searchWhere .= ' ' . $this->arr_search['order_term'] . ' ';
				if( 'csod_' == substr($this->arr_search['order_column'][1], 0, 5) ){
					$this->searchWhere .= $wpdb->prepare('`p' . esc_sql($this->arr_search['order_column'][1]) . '`.meta_value LIKE %s', "%".$this->arr_search['order_word'][1]."%");
				}else{
					$this->searchWhere .= '`' . $wpdb->prepare( esc_sql($this->arr_search['order_column'][1]) . '`' . $wordterm1, $word1);
				}
			}


			$this->searchWhere .= ' ) ';
		}

		if( !empty($this->arr_search['member_column'][0]) && !WCUtils::is_blank($this->arr_search['member_word'][0]) ){

			switch( $this->arr_search['member_word_term'][0] ){
				case 'notcontain':
					$wordterm0 = ' NOT LIKE %s';
					$word0 = "%".$this->arr_search['member_word'][0]."%";
					break;
				case 'equal':
					$wordterm0 = ' = %s';
					$word0 = $this->arr_search['member_word'][0];
					break;
				case 'morethan':
					$wordterm0 = ' > %d';
					$word0 = $this->arr_search['member_word'][0];
					break;
				case 'lessthan':
					$wordterm0 = ' < %d';
					$word0 = $this->arr_search['member_word'][0];
					break;
				case 'contain':
				default:
					$wordterm0 = ' LIKE %s';
					$word0 = "%".$this->arr_search['member_word'][0]."%";
					break;
			}

			switch( $this->arr_search['member_word_term'][1] ){
				case 'notcontain':
					$wordterm1 = ' NOT LIKE %s';
					$word1 = "%".$this->arr_search['member_word'][1]."%";
					break;
				case 'equal':
					$wordterm1 = ' = %s';
					$word1 = $this->arr_search['member_word'][1];
					break;
				case 'morethan':
					$wordterm1 = ' > %d';
					$word1 = $this->arr_search['member_word'][1];
					break;
				case 'lessthan':
					$wordterm1 = ' < %d';
					$word1 = $this->arr_search['member_word'][1];
					break;
				case 'contain':
				default:
					$wordterm1 = ' LIKE %s';
					$word1 = "%".$this->arr_search['member_word'][1]."%";
					break;
			}

			$this->searchHaving .= ' ( ';
			$this->searchHaving .= '`' . $wpdb->prepare( esc_sql($this->arr_search['member_column'][0]) . '`' . $wordterm0, $word0);

			if( !empty($this->arr_search['member_column'][1]) && !WCUtils::is_blank($this->arr_search['member_word'][1]) ){
				$this->searchHaving .= ' ' . $this->arr_search['member_term'] . ' ';
				$this->searchHaving .= '`' . $wpdb->prepare( esc_sql($this->arr_search['member_column'][1]) . '`' . $wordterm1, $word1);
			}
			$this->searchHaving .= ' ) ';
		}
	}

	function SearchOut(){
		$this->searchWhere = '';
		$this->searchHaving = '';
	}

	function SetNavi(){

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
					$html .= '<li class="navigationButtonSelected"><span>' . $box[$i] . '</span></li>'."\n";
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

		$html .= '</ul>'."\n";

		$this->dataTableNavigation = $html;
	}

    function getCookie(){
        $this->data_cookie = (isset($_COOKIE[$this->table])) ? json_decode(str_replace("\'","'",str_replace('\"','"', $_COOKIE[$this->table])),true) : [];
    }

	function SetHeaders(){

		foreach ($this->columns as $key => $value){
			if( 'csod_' == substr($key, 0, 5) )
				continue;

			if($key == $this->sortColumn){
				if( isset( $this->sortSwitchs[$key] ) && $this->sortSwitchs[$key] == 'ASC'){
					$str = __('[ASC]', 'usces');
					$switch = 'DESC';
				}else{
					$str = __('[DESC]', 'usces');
					$switch = 'ASC';
				}
				$this->headers[$key] = '<a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changeSort=' . $key . '&switch=' . $switch . '"><span class="sortcolumn">' . $value . ' ' . $str . '</span></a>';
			}else{
				$switch = isset( $this->sortSwitchs[$key] ) ? $this->sortSwitchs[$key] : 'DESC';
				$this->headers[$key] = '<a href="' . site_url() . '/wp-admin/admin.php?page=usces_memberlist&changeSort=' . $key . '&switch=' . $switch . '"><span>' . $value . '</span></a>';
			}
		}
	}

	function GetSearchs(){
		return $this->arr_search;
	}

	function GetListheaders(){
		return $this->headers;
	}

	function GetDataTableNavigation()	{
		return $this->dataTableNavigation;
	}

	function set_action_status($status, $message)
	{
		$this->action_status = $status;
		$this->action_message = $message;
	}

	function get_action_status()
	{
		return $this->action_status;
	}

	function get_action_message()
	{
		return $this->action_message;
	}
}
