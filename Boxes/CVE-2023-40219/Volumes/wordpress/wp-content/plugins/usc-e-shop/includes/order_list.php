<?php
/**
 * Order List Page.
 * - Old type
 *
 * @package Welcart
 */

require_once USCES_PLUGIN_DIR . '/classes/orderList.class.php';
global $wpdb;

$tableName  = $wpdb->prefix . 'usces_order'; // phpcs:ignore
if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>' ) ) {
	$arr_column = array(
		__( 'ID', 'usces' )                 => 'ID',
		__( 'Order number', 'usces' )       => 'deco_id',
		__( 'Regular ID', 'autodelivery' )  => 'reg_id',
		__( 'date', 'usces' )               => 'date',
		__( 'membership number', 'usces' )  => 'mem_id',
		__( 'name', 'usces' )               => 'name',
		__( 'Region', 'usces' )             => 'pref',
		__( 'shipping option', 'usces' )    => 'delivery_method',
		__( 'Amount', 'usces' ) . '(' . __( usces_crcode( 'return' ), 'usces' ) . ')' => 'total_price',
		__( 'payment method', 'usces' )     => 'payment_name',
		__( 'transfer statement', 'usces' ) => 'receipt_status',
		__( 'Processing', 'usces' )         => 'order_status',
		__( 'shpping date', 'usces' )       => 'order_modified',
	);
} else {
	$arr_column = array(
		__( 'ID', 'usces' )                 => 'ID',
		__( 'Order number', 'usces' )       => 'deco_id',
		__( 'date', 'usces' )               => 'date',
		__( 'membership number', 'usces' )  => 'mem_id',
		__( 'name', 'usces' )               => 'name',
		__( 'Region', 'usces' )             => 'pref',
		__( 'shipping option', 'usces' )    => 'delivery_method',
		__( 'Amount', 'usces' ) . '(' . __( usces_crcode( 'return' ), 'usces' ) . ')' => 'total_price',
		__( 'payment method', 'usces' )     => 'payment_name',
		__( 'transfer statement', 'usces' ) => 'receipt_status',
		__( 'Processing', 'usces' )         => 'order_status',
		apply_filters( 'usces_filter_admin_modified_label', __( 'shpping date', 'usces' ) ) => 'order_modified',
	);
}
$arr_column = apply_filters( 'usces_filter_order_list_column', $arr_column );

// phpcs:disable
$DT                  = new dataList( $tableName, $arr_column );
$res                 = $DT->MakeTable();
$arr_search          = $DT->GetSearchs();
$management_status   = apply_filters( 'usces_filter_management_status', get_option( 'usces_management_status' ) );
$arr_header          = $DT->GetListheaders();
$dataTableNavigation = $DT->GetDataTableNavigation();
$rows                = $DT->rows;
$status              = $DT->get_action_status();
$message             = $DT->get_action_message();
$status              = apply_filters( 'usces_order_list_action_status', $status );
$message             = apply_filters( 'usces_order_list_action_message', $message );
// phpcs:enable

$usces_admin_path = '';
$admin_perse      = explode( '/', $_SERVER['REQUEST_URI'] ); // phpcs:ignore
$apct             = count( $admin_perse ) - 1;
for ( $ap = 0; $ap < $apct; $ap++ ) {
	$usces_admin_path .= $admin_perse[ $ap ] . '/';
}

$startdate              = ( ! empty( $DT->startdate ) ) ? $DT->startdate : ''; // phpcs:ignore
$enddate                = ( ! empty( $DT->enddate ) ) ? $DT->enddate : ''; // phpcs:ignore
$period_specified_index = $DT->get_period_specified_index(); // phpcs:ignore
$pref                   = array();
$target_market          = $this->options['system']['target_market'];
foreach ( (array) $target_market as $country ) {
	$prefs = get_usces_states( $country );
	if ( is_array( $prefs ) && 0 < count( $prefs ) ) {
		$pos = strpos( $prefs[0], '--' );
		if ( false !== $pos ) {
			array_shift( $prefs );
		}
		foreach ( (array) $prefs as $state ) {
			$pref[] = $state;
		}
	}
}
$payment_name = array();
$payments     = usces_get_system_option( 'usces_payment_method', 'sort' );
foreach ( (array) $payments as $id => $payment ) { // phpcs:ignore
	$payment_name[ $id ] = $payment['name'];
}
foreach ( (array) $management_status as $key => $value ) {
	if ( 'noreceipt' == $key || 'receipted' == $key || 'pending' == $key ) {
		$receipt_status[ $key ] = $value;
	} else {
		$order_status[ $key ] = $value;
	}
}
$order_status['new'] = __( 'new order', 'usces' );
$curent_url          = urlencode( esc_url( USCES_ADMIN_URL . '?' . $_SERVER['QUERY_STRING'] ) ); // phpcs:ignore
$server_name         = $_SERVER['SERVER_NAME']; // phpcs:ignore

$csod_meta         = usces_has_custom_field_meta( 'order' );
$cscs_meta         = usces_has_custom_field_meta( 'customer' );
$csde_meta         = usces_has_custom_field_meta( 'delivery' );
$usces_opt_order   = get_option( 'usces_opt_order' );
$chk_pro           = ( isset( $usces_opt_order['chk_pro'] ) ) ? $usces_opt_order['chk_pro'] : array();
$chk_ord           = ( isset( $usces_opt_order['chk_ord'] ) ) ? $usces_opt_order['chk_ord'] : array();
$applyform         = usces_get_apply_addressform( $this->options['system']['addressform'] );
$settlement_backup = ( isset( $this->options['system']['settlement_backup'] ) ) ? $this->options['system']['settlement_backup'] : 0;
$settlement_notice = get_option( 'usces_settlement_notice' );
?>
<script type="text/javascript">
jQuery(function($){

	$("input[name='allcheck']").click(function () {
		if( $(this).prop("checked") ){
			$("input[name*='listcheck']").prop( "checked", true );
		}else{
			$("input[name*='listcheck']").prop( "checked", false );
		}
	});

	$("#searchselect").change(function () {
		operation.change_search_field();
	});

	$("#searchselectsku").change(function () {
		operation.change_search_sku_field();
	});

	$("#changeselect").change(function () {
		operation.change_collective_field();
	});

	$("#collective_change").click(function () {
		if( $("#changeselect option:selected").val() == 'none' ) {
			$("#orderlistaction").val('');
			return false;
		}
		if( $("input[name*='listcheck']:checked").length == 0 ) {
			alert("<?php esc_html_e( 'Choose the data.', 'usces' ); ?>");
			$("#orderlistaction").val('');
			return false;
		}
		var coll = $("#changeselect").val();
		var mes = '';
		if( coll == 'order_reciept' ){
			mes = <?php echo sprintf( __( "'Transfer status of the items which you have checked will be changed in to ' + %s + '. %sDo you agree?'", 'usces' ), '$("select\[name=\"change\[word\]\[order_reciept\]\"\] option:selected").html()', '\n\n' ); // phpcs:ignore ?>;
		}else if( coll == 'order_status' ){
			mes = <?php echo sprintf( __( "'Data status which you have cheked will be changed in to ' + %s + '. %sDo you agree?'", 'usces' ), '$("select\[name=\"change\[word\]\[order_status\]\"\] option:selected").html()', '\n\n' ); // phpcs:ignore ?>;
		}else if(coll == 'delete'){
			mes = '<?php esc_html_e( 'Are you sure of deleting all the checked data in bulk?', 'usces' ); ?>';
		}
		if( mes != '' ) {
			if( !confirm(mes) ){
				$("#orderlistaction").val('');
				return false;
			}
		}
		<?php do_action( 'usces_action_order_list_collective_change_js' ); ?>
		$("#orderlistaction").val('collective');
		$('#form_tablesearch').submit();
	});

	operation = {
		change_search_field :function (){
			var label = '';
			var html = '';
			var column = $("#searchselect").val();

			if( column == 'ID' ) {
				label = '<?php esc_html_e( 'key words', 'usces' ); ?>';
				html = '<input name="search[word][ID]" type="text" value="<?php echo esc_attr( isset( $arr_search['word']['ID'] ) ? $arr_search['word']['ID'] : '' ); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'deco_id' ) {
				label = '<?php esc_html_e( 'key words', 'usces' ); ?>';
				html = '<input name="search[word][deco_id]" type="text" value="<?php echo esc_attr( isset( $arr_search['word']['deco_id'] ) ? $arr_search['word']['deco_id'] : '' ); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'date' ) {
				label = '<?php esc_html_e( 'key words', 'usces' ); ?>';
				html = '<input name="search[word][date]" type="text" value="<?php echo esc_attr( isset( $arr_search['word']['date'] ) ? $arr_search['word']['date'] : '' ); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'mem_id' ) {
				label = '<?php esc_html_e( 'key words', 'usces' ); ?>';
				html = '<input name="search[word][mem_id]" type="text" value="<?php echo esc_attr( isset( $arr_search['word']['mem_id'] ) ? $arr_search['word']['mem_id'] : '' ); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'name' ) {
				label = '<?php esc_html_e( 'key words', 'usces' ); ?>';
				html = '<input name="search[word][name]" type="text" value="<?php echo esc_attr( isset( $arr_search['word']['name'] ) ? $arr_search['word']['name'] : '' ); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'order_modified' ) {
				label = '<?php esc_html_e( 'key words', 'usces' ); ?>';
				html = '<input name="search[word][order_modified]" type="text" value="<?php echo esc_attr( isset( $arr_search['word']['order_modified'] ) ? $arr_search['word']['order_modified'] : '' ); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'pref' ) {
				label = '';
				html = '<select name="search[word][pref]" class="searchselect">';
<?php
foreach ( (array) $pref as $pkey => $pvalue ) :
	if ( isset( $arr_search['word']['pref'] ) && $pvalue == $arr_search['word']['pref'] ) {
		$pselected = ' selected="selected"';
	} else {
		$pselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $pvalue ); ?>"<?php echo esc_attr( $pselected ); ?>><?php echo esc_html( $pvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';
			}else if( column == 'delivery_method' ) {
				label = '';
				html = '<select name="search[word][delivery_method]" class="searchselect">';
<?php
foreach ( (array) $this->options['delivery_method'] as $dkey => $dvalue ) :
	if ( isset( $arr_search['word']['delivery_method'] ) && $dvalue['id'] == $arr_search['word']['delivery_method'] ) {
		$dselected = ' selected="selected"';
	} else {
		$dselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $dvalue['id'] ); ?>"<?php echo esc_attr( $dselected ); ?>><?php echo esc_html( $dvalue['name'] ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';
			}else if( column == 'payment_name' ) {
				label = '';
				html = '<select name="search[word][payment_name]" class="searchselect">';
<?php
foreach ( (array) $payment_name as $pnkey => $pnvalue ) :
	if ( isset( $arr_search['word']['payment_name'] ) && $pnvalue == $arr_search['word']['payment_name'] ) {
		$pnselected = ' selected="selected"';
	} else {
		$pnselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $pnvalue ); ?>"<?php echo esc_attr( $pnselected ); ?>><?php echo esc_html( $pnvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';
			}else if( column == 'receipt_status' ) {
				label = '';
				html = '<select name="search[word][receipt_status]" class="searchselect">';
<?php
foreach ( (array) $receipt_status as $rkey => $rvalue ) :
	if ( isset( $arr_search['word']['receipt_status'] ) && $rvalue == $arr_search['word']['receipt_status'] ) {
		$rselected = ' selected="selected"';
	} else {
		$rselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $rvalue ); ?>"<?php echo esc_attr( $rselected ); ?>><?php echo esc_html( $rvalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';
			}else if( column == 'order_status' ) {
				label = '';
				html = '<select name="search[word][order_status]" class="searchselect">';
<?php
foreach ( (array) $order_status as $okey => $ovalue ) :
	if ( isset( $arr_search['word']['order_status'] ) && $ovalue == $arr_search['word']['order_status'] ) {
		$oselected = ' selected="selected"';
	} else {
		$oselected = '';
	}
	?>
				html += '<option value="<?php echo esc_attr( $ovalue ); ?>"<?php echo esc_attr( $oselected ); ?>><?php echo esc_html( $ovalue ); ?></option>';
	<?php
endforeach;
?>
				html += '</select>';
			}

			$("#searchlabel").html( label );
			$("#searchfield").html( html );
		},

		change_search_sku_field :function (){
			var label = '';
			var html = '';
			var column = $("#searchselectsku").val();

			if( column == 'item_code' ) {
				label = '<?php esc_html_e( 'key words', 'usces' ); ?>';
				html = '<input name="search[skuword][item_code]" type="text" value="<?php echo esc_attr( isset( $arr_search['skuword']['item_code'] ) ? $arr_search['skuword']['item_code'] : '' ); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'item_name' ) {
				label = '<?php esc_html_e( 'key words', 'usces' ); ?>';
				html = '<input name="search[skuword][item_name]" type="text" value="<?php echo esc_attr( isset( $arr_search['skuword']['item_name'] ) ? $arr_search['skuword']['item_name'] : '' ); ?>" class="searchword" maxlength="50" />';
			}

			$("#searchlabelsku").html( label );
			$("#searchfieldsku").html( html );
		},

		change_collective_field :function (){
			var label = '';
			var html = '';
			var column = $("#changeselect").val();

			if( column == 'order_reciept' ) {
				label = '';
				html = '<select name="change[word][order_reciept]" class="searchselect">';
<?php foreach ( (array) $receipt_status as $orkey => $orvalue ) : ?>
				html += '<option value="<?php echo esc_attr( $orkey ); ?>"><?php echo esc_html( $orvalue ); ?></option>';
<?php endforeach; ?>
				html += '</select>';
			}else if( column == 'order_status' ) {
				label = '';
				html = '<select name="change[word][order_status]" class="ksearchselect">';
<?php foreach ( (array) $order_status as $oskey => $osvalue ) : ?>
				html += '<option value="<?php echo esc_attr( $oskey ); ?>"><?php echo esc_html( $osvalue ); ?></option>';
<?php endforeach; ?>
				html += '</select>';
			}else if( column == 'delete' ) {
				label = '';
				html = '';
			}

			$("#changelabel").html( label );
			$("#changefield").html( html );
		}
	};
<?php usces_order_list_js_settlement_dialog(); ?>
<?php echo apply_filters( 'usces_filter_order_list_page_js', '', $DT ); // phpcs:ignore ?>
});

function deleteconfirm(order_id){
	if(confirm(<?php _e( "'Are you sure of deleting an order number ' + order_id + ' ?'", 'usces' ); // phpcs:ignore ?>)){
		return true;
	}else{
		return false;
	}
}

jQuery(document).ready(function($){
	(function setCookie() {
<?php
// phpcs:disable
$data_cookie                       = array();
$data_cookie['placeholder_escape'] = $DT->placeholder_escape;
$data_cookie['startRow']           = $DT->startRow;        /* 表示開始行番号 */
$data_cookie['sortColumn']         = $DT->sortColumn;      /* 現在ソート中のフィールド */
$data_cookie['totalRow']           = $DT->totalRow;        /* 全行数 */
$data_cookie['selectedRow']        = $DT->selectedRow;     /* 絞り込まれた行数 */
$data_cookie['currentPage']        = $DT->currentPage;     /* 現在のページNo */
$data_cookie['previousPage']       = $DT->previousPage;    /* 前のページNo */
$data_cookie['nextPage']           = $DT->nextPage;        /* 次のページNo */
$data_cookie['lastPage']           = $DT->lastPage;        /* 最終ページNo */
$data_cookie['userHeaderNames']    = $DT->userHeaderNames; /* 全てのフィールド */
// $data_cookie['rows']               = $DT->rows;            /* 表示する行オブジェクト */
$data_cookie['sortSwitchs']        = $DT->sortSwitchs;     /* 各フィールド毎の昇順降順スイッチ */
$data_cookie['searchSql']          = $DT->searchSql;
$data_cookie['searchSkuSql']       = $DT->searchSkuSql;
$data_cookie['arr_search']         = $DT->arr_search;
$data_cookie['searchSwitchStatus'] = $DT->searchSwitchStatus;
$data_cookie['startdate']          = $DT->startdate;
$data_cookie['enddate']            = $DT->enddate;
// phpcs:enable
?>
		$.cookie('<?php echo "{$DT->table}" . "_path"; ?>', '<?php echo esc_url( $usces_admin_path ); ?>', { path: "<?php echo esc_url( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $server_name ); ?>"});
		$.cookie('<?php echo "{$DT->table}"; ?>', '<?php echo str_replace( "'", "\'", json_encode( $data_cookie ) ); ?>', { path: "<?php echo esc_url( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $server_name ); ?>"});
	})();
	$("table#mainDataTable tr:even").addClass("rowSelection_even");
	$("table#mainDataTable tr").hover(function() {
		$(this).addClass("rowSelection_hilight");
	},
	function() {
		$(this).removeClass("rowSelection_hilight");
	});

	$(document).on( "click", "#searchVisiLink", function() {
		if( $("#searchBox").css("display") == "block" ) {
			$("#searchBox").css("display", "none");
			$("#searchVisiLink").html('<?php esc_html_e( 'Show the Operation field', 'usces' ); ?><span class="dashicons dashicons-arrow-down"></span>');
		} else {
			$("#searchBox").css("display", "block");
			$("#searchVisiLink").html('<?php esc_html_e( 'Hide the Operation field', 'usces' ); ?><span class="dashicons dashicons-arrow-up"></span>');
		}
	});

	operation.change_search_field();
	operation.change_search_sku_field();

	$("#dlProductListDialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 400,
		width: 700,
		resizable: true,
		modal: true,
		buttons: {
			'<?php esc_html_e( 'close', 'usces' ); ?>': function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});
	$('#dl_pro').click(function() {
		var args = "&search[column]="+$(':input[name="search[column]"]').val()
			+"&search[sku]="+$(':input[name="search[sku]"]').val()
			+"&search[word]["+$("#searchselect").val()+"]="+$(':input[name="search[word]['+$("#searchselect").val()+']"]').val()
			+"&search[skuword]["+$("#searchselectsku").val()+"]="+$(':input[name="search[skuword]['+$("#searchselectsku").val()+']"]').val()
			+"&search[period]="+$(':input[name="search[period]"]').val()
			+"&searchSwitchStatus="+$(':input[name="searchSwitchStatus"]').val()
			+"&ftype=csv";
		$(".check_product").each(function(i) {
			if($(this).prop('checked')) {
				args += '&check['+$(this).val()+']=on';
			}
		});
		location.href = "<?php echo esc_url( USCES_ADMIN_URL ); ?>?page=usces_orderlist&order_action=dlproductlist&noheader=true"+args;
	});
	$('#dl_productlist').click(function() {
		$('#dlProductListDialog').dialog('open');
	});

	$("#dlOrderListDialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 600,
		width: 700,
		resizable: true,
		modal: true,
		buttons: {
			'<?php esc_html_e( 'close', 'usces' ); ?>': function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});
	$('#dl_ord').click(function() {
		var args = "&search[column]="+$(':input[name="search[column]"]').val()
			+"&search[sku]="+$(':input[name="search[sku]"]').val()
			+"&search[word]["+$("#searchselect").val()+"]="+$(':input[name="search[word]['+$("#searchselect").val()+']"]').val()
			+"&search[skuword]["+$("#searchselectsku").val()+"]="+$(':input[name="search[skuword]['+$("#searchselectsku").val()+']"]').val()
			+"&search[period]="+$(':input[name="search[period]"]').val()
			+"&searchSwitchStatus="+$(':input[name="searchSwitchStatus"]').val()
			+"&ftype=csv";
		$(".check_order").each(function(i) {
			if($(this).prop('checked')) {
				args += '&check['+$(this).val()+']=on';
			}
		});
		location.href = "<?php echo esc_url( USCES_ADMIN_URL ); ?>?page=usces_orderlist&order_action=dlorderlist&noheader=true"+args;
	});
	$('#dl_orderlist').click(function() {
		$('#dlOrderListDialog').dialog('open');
	});

<?php if ( isset( $_GET['order_action'] ) && 'settlement_notice' == $_GET['order_action'] ) : ?>
	$("#searchBox").css( "display","block" );
	$("#searchSwitchStatus").val( "ON" );
	$("#searchSwitchStatus").css( "display","none" );
	$("#settlement_errorlog").trigger( "click" );
<?php endif; ?>

<?php
$set_startdate = ( ! empty( $startdate ) ) ? ', setDate: "' . $startdate . '", defaultDate: "' . $startdate . '"' : ''; // phpcs:ignore
$set_enddate   = ( ! empty( $enddate ) ) ? ', setDate: "' . $enddate . '", defaultDate: "' . $enddate . '"' : ''; // phpcs:ignore
?>
	$("#startdate").datepicker({
		dateFormat: "yy-mm-dd"<?php wel_esc_script_e( $set_startdate ); ?>
	});
	$("#enddate").datepicker({
		dateFormat: "yy-mm-dd"<?php wel_esc_script_e( $set_enddate ); ?>
	});
	$("select[name='search[period]']").change(function() {
		var period = $("select[name='search[period]'] option:selected").val();
		if( period == <?php wel_esc_script_e( $period_specified_index ); ?> ) {
			$("#period_specified").css( "display", "block" );
		} else {
			$("#period_specified").css( "display", "none" );
		}
	});
	$("select[name='search[period]']").triggerHandler("change");
<?php if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>' ) ) : ?>
	$(".regular_parent_order").removeClass( "rowSelection_even" );
	$(".regular_parent_order").css( "background-color","#e6fe9e" );
<?php endif; ?>
<?php do_action( 'usces_action_order_list_document_ready_js', $DT ); // phpcs:ignore ?>
});
</script>

<div class="wrap">
<div class="usces_admin">
<form action="<?php echo esc_url( USCES_ADMIN_URL . '?page=usces_orderlist' ); ?>" method="post" name="tablesearch" id="form_tablesearch">
<h1>Welcart Management <?php esc_html_e( 'Order List', 'usces' ); ?></h1>
<p class="version_info">Version <?php echo esc_html( USCES_VERSION ); ?></p>
<?php usces_admin_action_status( $status, $message ); ?>

<div id="datatable">
<div id="tablenavi"><?php wel_esc_script_e( $dataTableNavigation ); ?></div>

<div id="tablesearch">
<div id="searchBox">
	<table id="search_table">
		<tr>
		<td><?php esc_html_e( 'search fields', 'usces' ); ?></td>
		<td><select name="search[column]" class="searchselect" id="searchselect">
			<option value="none"> </option>
<?php
foreach ( (array) $arr_column as $key => $value ) :
	if ( 'total_price' == $value ) {
		continue;
	}
	$selected = ( $value == $arr_search['column'] ) ? ' selected="selected"' : '';
	?>
			<option value="<?php echo esc_attr( $value ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $key ); ?></option>
	<?php
endforeach;
?>
			</select>
		</td>
		<td id="searchlabel"></td>
		<td id="searchfield"></td>
		<td rowspan="2"><input name="searchIn" type="submit" class="searchbutton button" value="<?php esc_attr_e( 'Search', 'usces' ); ?>" />
		<input name="searchOut" type="submit" class="searchbutton button" value="<?php esc_attr_e( 'Cancellation', 'usces' ); ?>" />
		<input name="searchSwitchStatus" id="searchSwitchStatus" type="hidden" value="<?php echo esc_attr( $DT->searchSwitchStatus ); // phpcs:ignore ?>" />
		</td>
		</tr>
		<td><?php esc_html_e( 'search fields', 'usces' ); ?></td>
		<td><select name="search[sku]" class="searchselect" id="searchselectsku">
			<option value="none"> </option>
			<option value="item_code"<?php echo( 'item_code' == $arr_search['sku'] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'item code', 'usces' ); ?></option>
			<option value="item_name"<?php echo( 'item_name' == $arr_search['sku'] ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'item name', 'usces' ); ?></option>
		</select></td>
		<td id="searchlabelsku"></td>
		<td id="searchfieldsku"></td>
		<tr>
		</tr>
	</table>
	<table id="period_table">
		<tr>
<?php
		$period_table = '<td><div id="period_specified"><input type="text" name="startdate" id="startdate" value="' . $startdate . '">' . __( ' - ', 'usces' ) . '<input type="text" name="enddate" id="enddate" value="' . $enddate . '"></div></td>';
?>
		<?php echo apply_filters( 'usces_filter_order_list_period_table', $period_table ); // phpcs:ignore ?>
		<td><?php esc_html_e( 'Period', 'usces' ); ?></td>
		<td><select name="search[period]" class="searchselect">
<?php
foreach ( (array) $DT->arr_period as $key => $value ) : // phpcs:ignore
	$selected = ( $key == $arr_search['period'] ) ? ' selected="selected"' : '';
	?>
			<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $value ); ?></option>
	<?php
endforeach;
?>
		</select></td>
		</tr>
	</table>
	<table id="change_table">
		<tr>
		<td><?php esc_html_e( 'Oparation in bulk', 'usces' ); ?></td>
		<td><select name="allchange[column]" class="searchselect" id="changeselect">
			<option value="none"> </option>
			<option value="order_reciept"><?php esc_html_e( 'Edit the receiving money status', 'usces' ); ?></option>
			<option value="order_status"><?php esc_html_e( 'Edit of status process', 'usces' ); ?></option>
			<option value="delete"><?php esc_html_e( 'Delete in bulk', 'usces' ); ?></option>
			<?php echo apply_filters( 'usces_filter_allchange_column', '' ); // phpcs:ignore ?>
		</select></td>
		<td id="changelabel"></td>
		<td id="changefield"></td>
		<td><input name="collective_change" type="button" class="searchbutton button" id="collective_change" value="<?php esc_attr_e( 'start', 'usces' ); ?>" />
		</td>
		</tr>
	</table>
	<input name="collective" id="orderlistaction" type="hidden" />
	<table id="dl_list_table">
		<tr>
		<?php echo apply_filters( 'usces_filter_dl_list_table', '' ); // phpcs:ignore ?>
		<?php do_action( 'usces_action_dl_list_table' ); ?>
		<td><input type="button" id="dl_productlist" class="searchbutton button" value="<?php esc_attr_e( 'Download Product List', 'usces' ); ?>" /></td>
		<td><input type="button" id="dl_orderlist" class="searchbutton button" value="<?php esc_attr_e( 'Download Order List', 'usces' ); ?>" /></td>
<?php if ( ! empty( $settlement_backup ) && 1 == $settlement_backup ) : ?>
		<td><input type="button" id="settlementlog" class="searchbutton button" value="<?php esc_attr_e( 'Settlement previous log list', 'usces' ); ?>" /></td>
<?php endif; ?>
<?php if ( ! empty( $settlement_notice ) ) : ?>
		<td><input type="button" id="settlement_errorlog" class="searchbutton button" value="<?php esc_attr_e( 'Settlement error log list', 'usces' ); ?>" /></td>
<?php endif; ?>
		</tr>
	</table>
<div<?php if ( has_action( 'usces_action_order_list_searchbox_bottom' ) ) echo ' class="searchbox_bottom"'; // phpcs:ignore ?>>
<?php do_action( 'usces_action_order_list_searchbox_bottom' ); ?>
</div>
</div>
<?php do_action( 'usces_action_order_list_searchbox' ); ?>
</div>

<table id="mainDataTable" cellspacing="1">
<?php
$list_header = '<th scope="col"><input name="allcheck" type="checkbox" value="" /></th>';
foreach ( (array) $arr_header as $value ) {
	$list_header .= '<th scope="col">' . $value . '</th>';
}
$list_header .= '<th scope="col">&nbsp;</th>';
?>
	<tr>
		<?php echo apply_filters( 'usces_filter_order_list_header', $list_header, $arr_header ); // phpcs:ignore ?>
	</tr>
<?php
foreach ( (array) $rows as $data ) :
	$list_detail = '<td align="center"><input name="listcheck[]" type="checkbox" value="' . $data['ID'] . '" /></td>';
	foreach ( (array) $data as $key => $value ) {
		if ( WCUtils::is_blank( $value ) ) {
			$value = '&nbsp;';
		}
		if ( 'ID' === $key || 'deco_id' === $key ) {
			$list_detail .= '<td><a href="' . USCES_ADMIN_URL . '?page=usces_orderlist&order_action=edit&order_id=' . $data['ID'] . '&usces_referer=' . $curent_url . '&wc_nonce=' . wp_create_nonce( 'order_list' ) . '">' . esc_html( $value ) . '</a></td>';
		} elseif ( 'reg_id' == $key ) {
			if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>' ) ) {
				if ( '&nbsp;' == $value || '-' == $value ) {
					$list_detail .= '<td>' . esc_html( $value ) . '</td>';
				} else {
					$list_detail .= '<td><a href="' . USCES_ADMIN_URL . '?page=usces_regularlist&regular_action=edit&regular_id=' . $value . '&usces_referer=' . $curent_url . '">' . esc_html( $value ) . '</a></td>';
				}
			}
		} elseif ( 'date' === $key ) {
			$list_detail .= '<td>' . esc_html( $value ) . '</td>';
		} elseif ( 'mem_id' === $key ) {
			if ( WCUtils::is_zero( $value ) ) {
				$value = '&nbsp;';
			}
			$list_detail .= '<td>' . esc_html( $value ) . '</td>';
		} elseif ( 'name' === $key ) {
			switch ( $applyform ) {
				case 'JP':
					$list_detail .= '<td>' . esc_html( $value ) . '</td>';
					break;
				case 'US':
				default:
					$names        = explode( ' ', $value );
					$list_detail .= '<td>' . esc_html( $names[1] . ' ' . $names[0] ) . '</td>';
			}
		} elseif ( 'pref' === $key ) {
			if ( __( '-- Select --', 'usces' ) == $value || '-- Select --' == $value ) {
				$list_detail .= '<td>&nbsp;</td>';
			} else {
				$list_detail .= '<td>' . esc_html( $value ) . '</td>';
			}
		} elseif ( 'delivery_method' === $key ) {
			if ( -1 != $value ) {
				$delivery_method_index = $this->get_delivery_method_index( $value );
				$value                 = ( isset( $this->options['delivery_method'][ $delivery_method_index ]['name'] ) ) ? $this->options['delivery_method'][ $delivery_method_index ]['name'] : '&nbsp;';
			} else {
				$value = '&nbsp;';
			}
			$list_detail .= '<td class="green">' . esc_html( $value ) . '</td>';
		} elseif ( 'total_price' === $key ) {
			if ( $value < 0 ) {
				$value = 0;
			}
			$list_detail .= '<td class="price">' . usces_crform( $value, true, false, 'return' ) . '</td>';
		} elseif ( 'payment_name' === $key ) {
			if ( '#none#' == $value ) {
				$list_detail .= '<td>&nbsp;</td>';
			} else {
				$list_detail .= '<td>' . esc_html( $value ) . '</td>';
			}
		} elseif ( 'receipt_status' === $key ) {
			if ( __( 'unpaid', 'usces' ) == $value ) {
				$list_detail .= '<td class="red">' . esc_html( $value ) . '</td>';
			} elseif ( 'Pending' == $value ) {
				$list_detail .= '<td class="red">' . esc_html( $value ) . '</td>';
			} elseif ( __( 'payment confirmed', 'usces' ) == $value ) {
				$list_detail .= '<td class="green">' . esc_html( $value ) . '</td>';
			} else {
				$list_detail .= '<td>' . esc_html( $value ) . '</td>';
			}
		} elseif ( 'order_status' === $key ) {
			if ( __( 'It has sent it out.', 'usces' ) == $value ) {
				$process_status       = esc_html( $value );
				$process_status_class = ' class="green"';
			} else {
				$process_status       = esc_html( $value );
				$process_status_class = '';
			}
			$process_status       = apply_filters( 'usces_filter_orderlist_process_status', $process_status, $value, $management_status );
			$process_status_class = apply_filters( 'usces_filter_orderlist_process_status_class', $process_status_class, $value );
			$list_detail         .= '<td' . $process_status_class . '>' . $process_status . '</td>';
		} elseif ( 'order_modified' === $key ) {
			$list_detail .= '<td>' . esc_html( $value ) . '</td>';
		}
	}
	$list_detail .= '<td><a href="' . USCES_ADMIN_URL . '?page=usces_orderlist&order_action=delete&order_id=' . $data['ID'] . '&wc_nonce=' . wp_create_nonce( 'order_list' ) . '" onclick="return deleteconfirm(\'' . $data['ID'] . '\');"><span style="color:#FF0000; font-size:9px;">' . __( 'Delete', 'usces' ) . '</span></a></td>';
	if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
		$trclass = ( ! empty( $data['reg_parent_id'] ) ) ? ' class="regular_parent_order"' : '';
	} else {
		$trclass = '';
	}
	?>
	<tr<?php echo apply_filters( 'usces_filter_order_list_detail_trclass', $trclass, $data ); // phpcs:ignore ?>>
		<?php echo apply_filters( 'usces_filter_order_list_detail', $list_detail, $data, $curent_url ); // phpcs:ignore ?>
	</tr>
	<?php
endforeach;
?>
</table>

</div>
<!-- [memory peak usage] <?php // echo round(memory_get_peak_usage()/1048576, 1); ?>Mb -->

<div id="dlProductListDialog" title="<?php esc_attr_e( 'Download Product List', 'usces' ); ?>">
	<p><?php esc_html_e( 'Select the item you want, please press the download.', 'usces' ); ?></p>
	<input type="button" class="button" id="dl_pro" value="<?php esc_attr_e( 'Download', 'usces' ); ?>" />
	<fieldset><legend><?php esc_html_e( 'Header Information', 'usces' ); ?></legend>
		<label for="chk_pro[ID]"><input type="checkbox" class="check_product" id="chk_pro[ID]" value="ID" checked disabled /><?php esc_html_e( 'ID', 'usces' ); ?></label>
		<label for="chk_pro[deco_id]"><input type="checkbox" class="check_product" id="chk_pro[deco_id]" value="deco_id" checked disabled /><?php esc_html_e( 'order number', 'usces' ); ?></label>
		<label for="chk_pro[date]"><input type="checkbox" class="check_product" id="chk_pro[date]" value="date"<?php usces_checked( $chk_pro, 'date' ); ?> /><?php esc_html_e( 'order date', 'usces' ); ?></label>
		<label for="chk_pro[mem_id]"><input type="checkbox" class="check_product" id="chk_pro[mem_id]" value="mem_id"<?php usces_checked( $chk_pro, 'mem_id' ); ?> /><?php esc_html_e( 'membership number', 'usces' ); ?></label>
		<label for="chk_pro[name]"><input type="checkbox" class="check_product" id="chk_pro[name]" value="name"<?php usces_checked( $chk_pro, 'name' ); ?> /><?php esc_html_e( 'name', 'usces' ); ?></label>
		<label for="chk_pro[delivery_method]"><input type="checkbox" class="check_product" id="chk_pro[delivery_method]" value="delivery_method"<?php usces_checked( $chk_pro, 'delivery_method' ); ?> /><?php esc_html_e( 'shipping option', 'usces' ); ?></label>
		<label for="chk_pro[shipping_date]"><input type="checkbox" class="check_product" id="chk_pro[shipping_date]" value="shipping_date"<?php usces_checked( $chk_pro, 'shipping_date' ); ?> /><?php esc_html_e( 'shpping date', 'usces' ); ?></label>
		<?php do_action( 'usces_action_chk_pro_head', $chk_pro ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Product Information', 'usces' ); ?></legend>
		<label for="chk_pro[item_code]"><input type="checkbox" class="check_product" id="chk_pro[item_code]" value="item_code" checked disabled /><?php esc_html_e( 'item code', 'usces' ); ?></label>
		<label for="chk_pro[sku_code]"><input type="checkbox" class="check_product" id="chk_pro[sku_code]" value="sku_code" checked disabled /><?php esc_html_e( 'SKU code', 'usces' ); ?></label>
		<label for="chk_pro[item_name]"><input type="checkbox" class="check_product" id="chk_pro[item_name]" value="item_name"<?php usces_checked( $chk_pro, 'item_name' ); ?> /><?php esc_html_e( 'item name', 'usces' ); ?></label>
		<label for="chk_pro[sku_name]"><input type="checkbox" class="check_product" id="chk_pro[sku_name]" value="sku_name"<?php usces_checked( $chk_pro, 'sku_name' ); ?> /><?php esc_html_e( 'SKU display name ', 'usces' ); ?></label>
		<label for="chk_pro[options]"><input type="checkbox" class="check_product" id="chk_pro[options]" value="options"<?php usces_checked( $chk_pro, 'options' ); ?> /><?php esc_html_e( 'options for items', 'usces' ); ?></label>
		<label for="chk_pro[quantity]"><input type="checkbox" class="check_product" id="chk_pro[quantity]" value="quantity" checked disabled /><?php esc_html_e( 'Quantity', 'usces' ); ?></label>
		<label for="chk_pro[price]"><input type="checkbox" class="check_product" id="chk_pro[price]" value="price" checked disabled /><?php esc_html_e( 'Unit price', 'usces' ); ?></label>
		<label for="chk_pro[unit]"><input type="checkbox" class="check_product" id="chk_pro[unit]" value="unit"<?php usces_checked( $chk_pro, 'unit' ); ?> /><?php esc_html_e( 'unit', 'usces' ); ?></label>
		<?php do_action( 'usces_action_chk_pro_detail', $chk_pro ); ?>
	</fieldset>
</div>
<div id="dlOrderListDialog" title="<?php esc_attr_e( 'Download Order List', 'usces' ); ?>">
	<p><?php esc_html_e( 'Select the item you want, please press the download.', 'usces' ); ?></p>
	<input type="button" class="button" id="dl_ord" value="<?php esc_attr_e( 'Download', 'usces' ); ?>" />
	<fieldset><legend><?php esc_html_e( 'Customer Information', 'usces' ); ?></legend>
		<label for="chk_ord[ID]"><input type="checkbox" class="check_order" id="chk_ord[ID]" value="ID" checked disabled /><?php esc_html_e( 'ID', 'usces' ); ?></label>
		<label for="chk_ord[deco_id]"><input type="checkbox" class="check_order" id="chk_ord[deco_id]" value="deco_id" checked disabled /><?php esc_html_e( 'Order number', 'usces' ); ?></label>
		<label for="chk_ord[date]"><input type="checkbox" class="check_order" id="chk_ord[date]" value="date" checked disabled /><?php esc_html_e( 'order date', 'usces' ); ?></label>
		<label for="chk_ord[mem_id]"><input type="checkbox" class="check_order" id="chk_ord[mem_id]" value="mem_id"<?php usces_checked( $chk_ord, 'mem_id' ); ?> /><?php esc_html_e( 'membership number', 'usces' ); ?></label>
		<label for="chk_ord[email]"><input type="checkbox" class="check_order" id="chk_ord[email]" value="email"<?php usces_checked( $chk_ord, 'email' ); ?> /><?php esc_html_e( 'e-mail', 'usces' ); ?></label>
<?php
if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'name_pre' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_ord, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<label for="chk_ord[name]"><input type="checkbox" class="check_order" id="chk_ord[name]" value="name" checked disabled /><?php esc_html_e( 'name', 'usces' ); ?></label>
<?php
switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_ord[kana]"><input type="checkbox" class="check_order" id="chk_ord[kana]" value="kana"<?php usces_checked( $chk_ord, 'kana' ); ?> /><?php esc_html_e( 'furigana', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'name_after' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_ord, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}

switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_ord[zip]"><input type="checkbox" class="check_order" id="chk_ord[zip]" value="zip"<?php usces_checked( $chk_ord, 'zip' ); ?> /><?php esc_html_e( 'Zip/Postal Code', 'usces' ); ?></label>
		<label for="chk_ord[country]"><input type="checkbox" class="check_order" id="chk_ord[country]" value="country"<?php usces_checked( $chk_ord, 'country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_ord[pref]"><input type="checkbox" class="check_order" id="chk_ord[pref]" value="pref"<?php usces_checked( $chk_ord, 'pref' ); ?> /><?php esc_html_e( 'Province', 'usces' ); ?></label>
		<label for="chk_ord[address1]"><input type="checkbox" class="check_order" id="chk_ord[address1]" value="address1"<?php usces_checked( $chk_ord, 'address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_ord[address2]"><input type="checkbox" class="check_order" id="chk_ord[address2]" value="address2"<?php usces_checked( $chk_ord, 'address2' ); ?> /><?php esc_html_e( 'numbers', 'usces' ); ?></label>
		<label for="chk_ord[address3]"><input type="checkbox" class="check_order" id="chk_ord[address3]" value="address3"<?php usces_checked( $chk_ord, 'address3' ); ?> /><?php esc_html_e( 'building name', 'usces' ); ?></label>
		<label for="chk_ord[tel]"><input type="checkbox" class="check_order" id="chk_ord[tel]" value="tel"<?php usces_checked( $chk_ord, 'tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_ord[fax]"><input type="checkbox" class="check_order" id="chk_ord[fax]" value="fax"<?php usces_checked( $chk_ord, 'fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
	case 'US':
	default:
		?>
		<label for="chk_ord[address2]"><input type="checkbox" class="check_order" id="chk_ord[address2]" value="address2"<?php usces_checked( $chk_ord, 'address2' ); ?> /><?php esc_html_e( 'Address Line1', 'usces' ); ?></label>
		<label for="chk_ord[address3]"><input type="checkbox" class="check_order" id="chk_ord[address3]" value="address3"<?php usces_checked( $chk_ord, 'address3' ); ?> /><?php esc_html_e( 'Address Line2', 'usces' ); ?></label>
		<label for="chk_ord[address1]"><input type="checkbox" class="check_order" id="chk_ord[address1]" value="address1"<?php usces_checked( $chk_ord, 'address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_ord[pref]"><input type="checkbox" class="check_order" id="chk_ord[pref]" value="pref"<?php usces_checked( $chk_ord, 'pref' ); ?> /><?php esc_html_e( 'State', 'usces' ); ?></label>
		<label for="chk_ord[country]"><input type="checkbox" class="check_order" id="chk_ord[country]" value="country"<?php usces_checked( $chk_ord, 'country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_ord[zip]"><input type="checkbox" class="check_order" id="chk_ord[zip]" value="zip"<?php usces_checked( $chk_ord, 'zip' ); ?> /><?php esc_html_e( 'Zip', 'usces' ); ?></label>
		<label for="chk_ord[tel]"><input type="checkbox" class="check_order" id="chk_ord[tel]" value="tel"<?php usces_checked( $chk_ord, 'tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_ord[fax]"><input type="checkbox" class="check_order" id="chk_ord[fax]" value="fax"<?php usces_checked( $chk_ord, 'fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		if ( 'fax_after' == $entry['position'] ) {
			$cscs_key = 'cscs_' . $key;
			$checked  = usces_checked( $chk_ord, $cscs_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $cscs_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $cscs_key ) . ']" value="' . esc_attr( $cscs_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<?php do_action( 'usces_action_chk_ord_customer', $chk_ord ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Shipping address information', 'usces' ); ?></legend>
<?php
if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'name_pre' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_ord, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<label for="chk_ord[delivery_name]"><input type="checkbox" class="check_order" id="chk_ord[delivery_name]" value="delivery_name"<?php usces_checked( $chk_ord, 'delivery_name' ); ?> /><?php esc_html_e( 'name', 'usces' ); ?></label>
<?php
switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_ord[delivery_kana]"><input type="checkbox" class="check_order" id="chk_ord[delivery_kana]" value="delivery_kana"<?php usces_checked( $chk_ord, 'delivery_kana' ); ?> /><?php esc_html_e( 'furigana', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'name_after' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_ord, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}

switch ( $applyform ) {
	case 'JP':
		?>
		<label for="chk_ord[delivery_zip]"><input type="checkbox" class="check_order" id="chk_ord[delivery_zip]" value="delivery_zip"<?php usces_checked( $chk_ord, 'delivery_zip' ); ?> /><?php esc_html_e( 'Zip/Postal Code', 'usces' ); ?></label>
		<label for="chk_ord[delivery_country]"><input type="checkbox" class="check_order" id="chk_ord[delivery_country]" value="delivery_country"<?php usces_checked( $chk_ord, 'delivery_country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_ord[delivery_pref]"><input type="checkbox" class="check_order" id="chk_ord[delivery_pref]" value="delivery_pref"<?php usces_checked( $chk_ord, 'delivery_pref' ); ?> /><?php esc_html_e( 'Province', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address1]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address1]" value="delivery_address1"<?php usces_checked( $chk_ord, 'delivery_address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address2]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address2]" value="delivery_address2"<?php usces_checked( $chk_ord, 'delivery_address2' ); ?> /><?php esc_html_e( 'numbers', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address3]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address3]" value="delivery_address3"<?php usces_checked( $chk_ord, 'delivery_address3' ); ?> /><?php esc_html_e( 'building name', 'usces' ); ?></label>
		<label for="chk_ord[delivery_tel]"><input type="checkbox" class="check_order" id="chk_ord[delivery_tel]" value="delivery_tel"<?php usces_checked( $chk_ord, 'delivery_tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_ord[delivery_fax]"><input type="checkbox" class="check_order" id="chk_ord[delivery_fax]" value="delivery_fax"<?php usces_checked( $chk_ord, 'delivery_fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
	case 'US':
	default:
		?>
		<label for="chk_ord[delivery_address2]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address2]" value="delivery_address2"<?php usces_checked( $chk_ord, 'delivery_address2' ); ?> /><?php esc_html_e( 'Address Line1', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address3]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address3]" value="delivery_address3"<?php usces_checked( $chk_ord, 'delivery_address3' ); ?> /><?php esc_html_e( 'Address Line2', 'usces' ); ?></label>
		<label for="chk_ord[delivery_address1]"><input type="checkbox" class="check_order" id="chk_ord[delivery_address1]" value="delivery_address1"<?php usces_checked( $chk_ord, 'delivery_address1' ); ?> /><?php esc_html_e( 'city', 'usces' ); ?></label>
		<label for="chk_ord[delivery_pref]"><input type="checkbox" class="check_order" id="chk_ord[delivery_pref]" value="delivery_pref"<?php usces_checked( $chk_ord, 'delivery_pref' ); ?> /><?php esc_html_e( 'State', 'usces' ); ?></label>
		<label for="chk_ord[delivery_country]"><input type="checkbox" class="check_order" id="chk_ord[delivery_country]" value="delivery_country"<?php usces_checked( $chk_ord, 'delivery_country' ); ?> /><?php esc_html_e( 'Country', 'usces' ); ?></label>
		<label for="chk_ord[delivery_zip]"><input type="checkbox" class="check_order" id="chk_ord[delivery_zip]" value="delivery_zip"<?php usces_checked( $chk_ord, 'delivery_zip' ); ?> /><?php esc_html_e( 'Zip', 'usces' ); ?></label>
		<label for="chk_ord[delivery_tel]"><input type="checkbox" class="check_order" id="chk_ord[delivery_tel]" value="delivery_tel"<?php usces_checked( $chk_ord, 'delivery_tel' ); ?> /><?php esc_html_e( 'Phone number', 'usces' ); ?></label>
		<label for="chk_ord[delivery_fax]"><input type="checkbox" class="check_order" id="chk_ord[delivery_fax]" value="delivery_fax"<?php usces_checked( $chk_ord, 'delivery_fax' ); ?> /><?php esc_html_e( 'FAX number', 'usces' ); ?></label>
		<?php
		break;
}

if ( ! empty( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		if ( 'fax_after' == $entry['position'] ) {
			$csde_key = 'csde_' . $key;
			$checked  = usces_checked( $chk_ord, $csde_key, 'return' );
			$name     = $entry['name'];
			echo '<label for="chk_ord[' . esc_attr( $csde_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $csde_key ) . ']" value="' . esc_attr( $csde_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
		}
	}
}
?>
		<?php do_action( 'usces_action_chk_ord_delivery', $chk_ord ); ?>
	</fieldset>
	<fieldset><legend><?php esc_html_e( 'Order Infomation', 'usces' ); ?></legend>
		<label for="chk_ord[shipping_date]"><input type="checkbox" class="check_order" id="chk_ord[shipping_date]" value="shipping_date"<?php usces_checked( $chk_ord, 'shipping_date' ); ?> /><?php esc_html_e( 'shpping date', 'usces' ); ?></label>
		<label for="chk_ord[peyment_method]"><input type="checkbox" class="check_order" id="chk_ord[peyment_method]" value="peyment_method"<?php usces_checked( $chk_ord, 'peyment_method' ); ?> /><?php esc_html_e( 'payment method', 'usces' ); ?></label>
		<label for="chk_ord[delivery_method]"><input type="checkbox" class="check_order" id="chk_ord[delivery_method]" value="delivery_method"<?php usces_checked( $chk_ord, 'delivery_method' ); ?> /><?php esc_html_e( 'shipping option', 'usces' ); ?></label>
		<label for="chk_ord[delivery_date]"><input type="checkbox" class="check_order" id="chk_ord[delivery_date]" value="delivery_date"<?php usces_checked( $chk_ord, 'delivery_date' ); ?> /><?php esc_html_e( 'Delivery date', 'usces' ); ?></label>
		<label for="chk_ord[delivery_time]"><input type="checkbox" class="check_order" id="chk_ord[delivery_time]" value="delivery_time"<?php usces_checked( $chk_ord, 'delivery_time' ); ?> /><?php esc_html_e( 'delivery time', 'usces' ); ?></label>
		<label for="chk_ord[delidue_date]"><input type="checkbox" class="check_order" id="chk_ord[delidue_date]" value="delidue_date"<?php usces_checked( $chk_ord, 'delidue_date' ); ?> /><?php esc_html_e( 'Shipping date', 'usces' ); ?></label>
		<label for="chk_ord[status]"><input type="checkbox" class="check_order" id="chk_ord[status]" value="status"<?php usces_checked( $chk_ord, 'status' ); ?> /><?php esc_html_e( 'Status', 'usces' ); ?></label>
		<label for="chk_ord[total_amount]"><input type="checkbox" class="check_order" id="chk_ord[total_amount]" value="total_amount" checked disabled /><?php esc_html_e( 'Total Amount', 'usces' ); ?></label>
		<label for="chk_ord[getpoint]"><input type="checkbox" class="check_order" id="chk_ord[getpoint]" value="getpoint"<?php usces_checked( $chk_ord, 'getpoint' ); ?> /><?php esc_html_e( 'granted points', 'usces' ); ?></label>
		<label for="chk_ord[usedpoint]"><input type="checkbox" class="check_order" id="chk_ord[usedpoint]" value="usedpoint"<?php usces_checked( $chk_ord, 'usedpoint' ); ?> /><?php esc_html_e( 'Used points', 'usces' ); ?></label>
		<label for="chk_ord[discount]"><input type="checkbox" class="check_order" id="chk_ord[discount]" value="discount" checked disabled /><?php esc_html_e( 'Discount', 'usces' ); ?></label>
		<label for="chk_ord[shipping_charge]"><input type="checkbox" class="check_order" id="chk_ord[shipping_charge]" value="shipping_charge" checked disabled /><?php esc_html_e( 'Shipping', 'usces' ); ?></label>
		<label for="chk_ord[cod_fee]"><input type="checkbox" class="check_order" id="chk_ord[cod_fee]" value="cod_fee" checked disabled /><?php echo apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ); // phpcs:ignore ?></label>
		<label for="chk_ord[tax]"><input type="checkbox" class="check_order" id="chk_ord[tax]" value="tax" checked disabled /><?php esc_html_e( 'consumption tax', 'usces' ); ?></label>
		<label for="chk_ord[note]"><input type="checkbox" class="check_order" id="chk_ord[note]" value="note"<?php usces_checked( $chk_ord, 'note' ); ?> /><?php esc_html_e( 'Notes', 'usces' ); ?></label>
<?php
if ( ! empty( $csod_meta ) ) {
	foreach ( $csod_meta as $key => $entry ) {
		$csod_key = 'csod_' . $key;
		$checked  = usces_checked( $chk_ord, $csod_key, 'return' );
		$name     = $entry['name'];
		echo '<label for="chk_ord[' . esc_attr( $csod_key ) . ']"><input type="checkbox" class="check_order" id="chk_ord[' . esc_attr( $csod_key ) . ']" value="' . esc_attr( $csod_key ) . '"' . esc_attr( $checked ) . ' />' . esc_html( $name ) . '</label>';
	}
}
?>
		<?php do_action( 'usces_action_chk_ord_order', $chk_ord ); ?>
	</fieldset>
</div>
<?php echo apply_filters( 'usces_filter_order_list_footer', '' ); // phpcs:ignore ?>
<?php wp_nonce_field( 'order_list', 'wc_nonce' ); ?>
</form>
<?php usces_order_list_form_settlement_dialog(); ?>
<?php do_action( 'usces_action_order_list_footer' ); ?>
</div><!--usces_admin-->
</div><!--wrap-->
