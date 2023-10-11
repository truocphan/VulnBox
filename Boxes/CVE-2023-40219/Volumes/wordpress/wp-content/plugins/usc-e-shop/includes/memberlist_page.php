<?php
global $usces_settings;

require_once( USCES_PLUGIN_DIR . "/classes/memberList.class.php" );
$csmb_meta = usces_has_custom_field_meta('member');
$DT = new WlcMemberList();
$arr_column = $DT->get_column();
$res = $DT->MakeTable();
$arr_search = $DT->GetSearchs();
$arr_header = $DT->GetListheaders();
$dataTableNavigation = $DT->GetDataTableNavigation();
$rows = $DT->rows;
$status = apply_filters( 'usces_member_list_action_status', $DT->get_action_status() );
$message = apply_filters( 'usces_member_list_action_message', $DT->get_action_message() );

$usces_admin_path = '';
$admin_perse = explode('/', $_SERVER['REQUEST_URI']);
$apct = count($admin_perse) - 1;
for($ap=0; $ap < $apct; $ap++){
	$usces_admin_path .= $admin_perse[$ap] . '/';
}
$list_option = get_option( 'usces_memberlist_option' );
$usces_opt_member = get_option('usces_opt_member');
$usces_opt_member = apply_filters( 'usces_filter_opt_member', $usces_opt_member );
$chk_mem = ( isset( $usces_opt_member['chk_mem'] ) ) ? $usces_opt_member['chk_mem'] : array();
$applyform = usces_get_apply_addressform($this->options['system']['addressform']);
$member_status = $this->member_status;
$member_country = $usces_settings['country'];
$curent_url = urlencode(esc_url(USCES_ADMIN_URL . '?' . $_SERVER['QUERY_STRING']));

?>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Management <?php _e('List of Members','usces'); ?></h1>
<p class="version_info">Version <?php echo USCES_VERSION; ?></p>
<?php usces_admin_action_status( $status, $message ); ?>

<?php do_action( 'usces_action_member_list_header' ); ?>

<form action="<?php echo USCES_ADMIN_URL.'?page=usces_memberlist'; ?>" method="post" name="tablesearch" id="form_tablesearch">
<div id="datatable">
<div class="usces_tablenav usces_tablenav_top">
	<?php wel_esc_script_e( $dataTableNavigation ); ?>
	<div id="searchVisiLink" class="screen-field"><?php _e('Show the Operation field', 'usces'); ?><span class="dashicons dashicons-arrow-down"></span></div>
	<div class="refresh"><a href="<?php echo site_url('/wp-admin/admin.php?page=usces_memberlist&refresh'); ?>"><span class="dashicons dashicons-update"></span><?php _e('updates it to latest information', 'usces'); ?></a></div>
</div>

<div id="tablesearch" class="usces_tablesearch">
<div id="searchBox">
	<table class="search_table">
	<tr>
		<td class="label"><?php _e( 'Member Search', 'usces' ); ?></td>
		<td>
			<div class="member_search_item search_item">
				<p class="search_item_label"><?php _e('From member information', 'usces'); ?></p>
				<p>
					<select name="search[member_column][0]" id="searchmemberselect_0" class="searchselect">
						<option value=""> </option>
					<?php foreach ((array)$arr_column as $key => $value):
							if( 'csod_' == substr($key, 0, 5) )
								continue;

							if($key == $arr_search['member_column'][0]){
								$selected = ' selected="selected"';
							}else{
								$selected = '';
							}
					?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $value ); ?></option>
					<?php endforeach; ?>
					</select>
					<span id="searchmemberword_0">
					<input name="search[member_word][0]" type="text" value="<?php echo esc_attr($arr_search['member_word'][0]); ?>" class="regular-text" maxlength="50" />
					<select name="search[member_word_term][0]" class="termselect">
						<option value="contain"<?php echo ( 'contain' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Contain', 'usces'); ?></option>
						<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Not Contain', 'usces'); ?></option>
						<option value="equal"<?php echo ( 'equal' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Equal', 'usces'); ?></option>
						<option value="morethan"<?php echo ( 'morethan' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('More than', 'usces'); ?></option>
						<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Less than', 'usces'); ?></option>
					</select>
					</span>
				</p>
				<p>
					<select name="search[member_term]" class="termselect">
						<option value="AND">AND</option>
						<option value="OR"<?php echo ( 'OR' == $arr_search['member_term'] ? ' selected="selected"' : ''); ?>>OR</option>
					</select>
				</p>
				<p>
					<select name="search[member_column][1]" id="searchmemberselect_1" class="searchselect">
						<option value=""> </option>
					<?php foreach ((array)$arr_column as $key => $value):
							if( 'csod_' == substr($key, 0, 5) )
								continue;

							if($key == $arr_search['member_column'][1]){
								$selected = ' selected="selected"';
							}else{
								$selected = '';
							}
					?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $value ); ?></option>
					<?php endforeach; ?>
					</select>
					<span id="searchmemberword_1">
					<input name="search[member_word][1]" type="text" value="<?php echo esc_attr($arr_search['member_word'][1]); ?>" class="regular-text" maxlength="50" />
					<select name="search[member_word_term][1]" class="termselect">
						<option value="contain"<?php echo ( 'contain' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Contain', 'usces'); ?></option>
						<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Not Contain', 'usces'); ?></option>
						<option value="equal"<?php echo ( 'equal' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Equal', 'usces'); ?></option>
						<option value="morethan"<?php echo ( 'morethan' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('More than', 'usces'); ?></option>
						<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Less than', 'usces'); ?></option>
					</select>
					</span>
				</p>
			</div>
			<div class="search_separate">AND</div>
			<div class="order_search_item search_item">
				<p class="search_item_label"><?php _e('From order information', 'usces'); ?></p>
				<p>
					<select name="search[order_column][0]" id="searchorderselect_0" class="searchselect">
						<option value=""> </option>
			<?php foreach ((array)$arr_column as $key => $value):
					if( 'csod_' != substr($key, 0, 5) )
						continue;

					if($key == $arr_search['order_column'][0]){
						$selected = ' selected="selected"';
					}else{
						$selected = '';
					}
			?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach; ?>
						<option value="item_code"<?php echo( 'item_code' == $arr_search['order_column'][0] ? ' selected="selected"' : ''); ?>><?php _e('item code', 'usces' ); ?></option>
						<option value="item_name"<?php echo( 'item_name' == $arr_search['order_column'][0] ? ' selected="selected"' : ''); ?>><?php _e('item name', 'usces' ); ?></option>
						<option value="sku_code"<?php echo( 'sku_code' == $arr_search['order_column'][0] ? ' selected="selected"' : ''); ?>><?php _e('SKU code', 'usces' ); ?></option>
						<option value="sku_name"<?php echo( 'sku_name' == $arr_search['order_column'][0] ? ' selected="selected"' : ''); ?>><?php _e('SKU name', 'usces' ); ?></option>


					</select>
					<span id="searchorderword_0">
					<input name="search[order_word][0]" type="text" value="<?php echo esc_attr($arr_search['order_word'][0]); ?>" class="regular-text" maxlength="50" />
					<select name="search[order_word_term][0]" class="termselect">
						<option value="contain"<?php echo ( 'contain' == $arr_search['order_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Contain', 'usces'); ?></option>
						<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['order_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Not Contain', 'usces'); ?></option>
						<option value="equal"<?php echo ( 'equal' == $arr_search['order_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Equal', 'usces'); ?></option>
						<option value="morethan"<?php echo ( 'morethan' == $arr_search['order_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('More than', 'usces'); ?></option>
						<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['order_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Less than', 'usces'); ?></option>
					</select>
					</span>
				</p>
				<p>
					<select name="search[order_term]" class="termselect">
						<option value="AND">AND</option>
						<option value="OR"<?php echo ( 'OR' == $arr_search['order_term'] ? ' selected="selected"' : ''); ?>>OR</option>
					</select>
				</p>
				<p>
					<select name="search[order_column][1]" id="searchorderselect_1" class="searchselect">
						<option value=""> </option>
			<?php foreach ((array)$arr_column as $key => $value):
					if( 'csod_' != substr($key, 0, 5) )
						continue;

					if($key == $arr_search['order_column'][1]){
						$selected = ' selected="selected"';
					}else{
						$selected = '';
					}
			?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach; ?>
						<option value="item_code"<?php echo( 'item_code' == $arr_search['order_column'][1] ? ' selected="selected"' : ''); ?>><?php _e('item code', 'usces' ); ?></option>
						<option value="item_name"<?php echo( 'item_name' == $arr_search['order_column'][1] ? ' selected="selected"' : ''); ?>><?php _e('item name', 'usces' ); ?></option>
						<option value="sku_code"<?php echo( 'sku_code' == $arr_search['order_column'][1] ? ' selected="selected"' : ''); ?>><?php _e('SKU code', 'usces' ); ?></option>
						<option value="sku_name"<?php echo( 'sku_name' == $arr_search['order_column'][1] ? ' selected="selected"' : ''); ?>><?php _e('SKU name', 'usces' ); ?></option>

					</select>
					<span id="searchorderword_1">
					<input name="search[order_word][1]" type="text" value="<?php echo esc_attr($arr_search['order_word'][1]); ?>" class="regular-text" maxlength="50" />
					<select name="search[order_word_term][1]" class="termselect">
						<option value="contain"<?php echo ( 'contain' == $arr_search['order_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Contain', 'usces'); ?></option>
						<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['order_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Not Contain', 'usces'); ?></option>
						<option value="equal"<?php echo ( 'equal' == $arr_search['order_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Equal', 'usces'); ?></option>
						<option value="morethan"<?php echo ( 'morethan' == $arr_search['order_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('More than', 'usces'); ?></option>
						<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['order_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Less than', 'usces'); ?></option>
					</select>
					</span>
				</p>
			</div>
			<div class="search_submit">
				<input name="searchIn" type="submit" class="button button-primary" value="<?php _e('Search', 'usces'); ?>" />
				<input name="searchOut" type="submit" class="button" value="<?php _e('Cancellation', 'usces'); ?>" />
			</div>
		</td>
	</tr>
	<tr>
		<td class="label"><?php _e( 'Oparation in bulk', 'usces' ); ?></td>
		<td id="change_list_table">
			<div>
				<select name="allchange[column]" class="searchselect" id="changeselect">
					<option value=""> </option>
					<option value="rank"><?php _e('Rank', 'usces'); ?></option>
					<option value="point"><?php _e('Points', 'usces'); ?></option>
					<option value="delete"><?php _e('Delete in bulk', 'usces'); ?></option>
				</select>
				<span id="changefield"></span>
				<input name="collective_change_member" type="button" class="button" id="collective_change_member" value="<?php _e('Run updating', 'usces'); ?>" />
				<input name="collective" id="memberlistaction" type="hidden" />
			</div>
		</td>
	</tr>
	<tr>
		<td class="label"><?php _e( 'Action', 'usces' ); ?></td>
		<td id="dl_list_table">
			<div class="action_button">
				<input type="button" id="dl_memberlist" class="button" value="<?php _e('Download Member List', 'usces'); ?>" />
				<?php do_action( 'usces_action_dl_member_list_table' ); ?>
			</div>
		</td>
	</tr>
	</table>
</div><!-- searchBox -->
<?php do_action( 'usces_action_member_list_searchbox' ); ?>
</div><!-- tablesearch -->

<table id="mainDataTable" class="new-table member-new-table">
<?php
	$list_header = '<th scope="col"><input name="allcheck" type="checkbox" value="" /></th>';
	foreach ( (array)$arr_header as $htkey => $value ){
		if( 'ID' != $htkey && (isset($list_option['view_column'][$htkey]) && !$list_option['view_column'][$htkey]) )
			continue;

		$list_header .= '<th scope="col">' . $value . '</th>';
	}

	$usces_serchorder_column = array( 'item_code', 'item_name', 'sku_code', 'sku_name' );
	if( in_array( $arr_search['order_column'][0], $usces_serchorder_column ) || in_array( $arr_search['order_column'][1], $usces_serchorder_column ) ){
		$list_header .= '<th scope="col">' . __('item code', 'usces' ) . '</th>' . "\n";
		$list_header .= '<th scope="col">' . __('item name', 'usces' ) . '</th>' . "\n";
		$list_header .= '<th scope="col">' . __('SKU code', 'usces' ) . '</th>' . "\n";
		$list_header .= '<th scope="col">' . __('SKU name', 'usces' ) . '</th>' . "\n";
	}
	$list_header .= '<th scope="col">&nbsp;</th>';
?>
<thead>
	<tr>
		<?php echo apply_filters('usces_filter_memberlist_header', $list_header, $arr_header); ?>
	</tr>
</thead>

<?php
foreach ( (array)$rows as $array ) :

	$list_detail = '<td align="center"><input name="listcheck[]" type="checkbox" value="' . esc_attr($array['ID']) . '" /></td>';

	foreach ( (array)$array as $key => $value ){
		if( 'ID' != $key && (isset($list_option['view_column'][$key]) && !$list_option['view_column'][$key]) )
			continue;

		if( 'meta_value' == $key )
			continue;

		if( !in_array( $arr_search['order_column'][0], $usces_serchorder_column ) && !in_array( $arr_search['order_column'][1], $usces_serchorder_column ) ){
			if( in_array( $key, $usces_serchorder_column ) )
				continue;
		}

		if( WCUtils::is_blank($value) )
			$value = '&nbsp;';

		if( 'csmb_' == substr($key, 0, 5) ){
			$multi_value = maybe_unserialize($value);
			if( is_array($multi_value) ){
				$value = '';
				foreach( $multi_value as $str ){
					$value .= $str . ' ';
				}
				trim($value);
			}
		}

		$detail = '';
		switch( $key ){

			case 'ID':
				$detail = '<td><a href="' . USCES_ADMIN_URL . '?page=usces_memberlist&member_action=edit&member_id=' . $value . '">' . $value . '</a></td>';
				break;

			case 'rank':
				$detail = '<td>' . esc_html($member_status[$value]) . '</td>';
				break;

			case 'point':
				$detail = '<td class="right">' . esc_html($value) . '</td>';
				break;

			case 'pref':
				if( $value == __('-- Select --','usces') || $value == '-- Select --' ) {
					$detail = '<td>&nbsp;</td>';
				} else {
					$detail = '<td>'.esc_html($value).'</td>';
				}
				break;

			default:
				$detail = '<td>' . esc_html($value) . '</td>';
				break;
		}

		$list_detail .= apply_filters( 'usces_filter_memberlist_detail_value', $detail, $value, $key, $array['ID'] );

	}
	$list_detail .= '<td><a href="' . USCES_ADMIN_URL . '?page=usces_memberlist&member_action=delete&member_id=' . $array['ID'] . '&wc_nonce=' . wp_create_nonce( 'delete_member' ) . '" onclick="return deleteconfirm(\'' . $array['ID'] . '\');"><span style="color:#FF0000; font-size:9px;">' . __('Delete', 'usces') . '</span></a></td>';
?>
<tbody>
	<tr<?php echo apply_filters('usces_filter_memberlist_detail_trclass', '', $array); ?>>
		<?php echo apply_filters('usces_filter_memberlist_detail', $list_detail, $array, $curent_url); ?>
	</tr>
</tbody>
<?php
endforeach;
?>
</table>
<?php wp_nonce_field( 'member_list', 'wc_nonce' ); ?>


<div class="usces_tablenav usces_tablenav_bottom" ><?php wel_esc_script_e( $dataTableNavigation ); ?></div>

</div><!-- datatable -->

</form>

<div id="dlMemberListDialog" title="<?php _e('Download Member List', 'usces'); ?>">
	<p><?php _e('Select the item you want, please press the download.', 'usces'); ?></p>
	<fieldset>
		<input type="button" class="button" id="dl_mem" value="<?php _e('Download', 'usces'); ?>" />
	</fieldset>
	<fieldset><legend><?php _e('Membership information', 'usces'); ?></legend>
<?php
	foreach( $arr_column as $key => $label ){
		if( 'csod_' == substr($key, 0, 5) )
			continue;
?>
		<label for="chk_mem[<?php echo esc_attr( $key ); ?>]"><input type="checkbox" class="check_member" id="chk_mem[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $key ); ?>"<?php usces_checked( $chk_mem, $key ); ?>/><?php echo esc_html( $label ); ?></label>
<?php
	}
?>
		<?php do_action( 'usces_action_chk_mem', $chk_mem ); ?>
	</fieldset>
</div>

<?php do_action( 'usces_action_member_list_footer' ); ?>

</div><!--usces_admin-->
</div><!--wrap-->
<script type="text/javascript">
function deleteconfirm(member_id){
	if(confirm(<?php _e("'Are you sure of deleting your membership number ' + member_id + ' ?'", 'usces'); ?>)){
		return true;
	}else{
		return false;
	}
}

jQuery(document).ready(function($){
	$('table#mainDataTable tbody input[type=checkbox]').change(
		function() {
			$('input').closest('tbody').removeClass('select');
			$(':checked').closest('tbody').addClass('select');
		}
	).trigger('change');

	$("#searchVisiLink").click(function() {
		if ( $("#searchBox").css("display") != "block" ){
			$("#searchBox").slideDown(300);
			$("#searchVisiLink").html('<?php _e('Hide the Operation field', 'usces'); ?><span class="dashicons dashicons-arrow-up"></span>');
			$.cookie("memberSearchBox", 1, { path: "<?php wel_esc_script_e( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $_SERVER['SERVER_NAME'] ); ?>"}) == true;
		}else{
			$("#searchBox").slideUp(300);
			$("#searchVisiLink").html('<?php _e('Show the Operation field', 'usces'); ?><span class="dashicons dashicons-arrow-down"></span>');
			$.cookie("memberSearchBox", 0, { path: "<?php wel_esc_script_e( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $_SERVER['SERVER_NAME'] ); ?>"}) == true;
		}
	});

    (function setCookie() {
        <?php
        $data_cookie = [];
        $data_cookie['placeholder_escape'] = $DT->placeholder_escape;
        $data_cookie['startRow'] = $DT->startRow;		//表示開始行番号
        $data_cookie['sortColumn'] = $DT->sortColumn;	//現在ソート中のフィールド
        $data_cookie['totalRow'] = $DT->totalRow;		//全行数
        $data_cookie['selectedRow'] = $DT->selectedRow;	//絞り込まれた行数
        $data_cookie['currentPage'] = $DT->currentPage;	//現在のページNo
        $data_cookie['previousPage'] = $DT->previousPage;	//前のページNo
        $data_cookie['nextPage'] = $DT->nextPage;		//次のページNo
        $data_cookie['lastPage'] = $DT->lastPage;		//最終ページNo
        $data_cookie['userHeaderNames'] = $DT->userHeaderNames;//全てのフィールド
        $data_cookie['sortSwitchs'] = $DT->sortSwitchs;	//各フィールド毎の昇順降順スイッチ
        $data_cookie['arr_search'] = $DT->arr_search;
        ?>
        $.cookie('<?php echo "{$DT->table}" . "_path"?>', '<?php wel_esc_script_e( $usces_admin_path ); ?>', { path: "<?php wel_esc_script_e( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $_SERVER['SERVER_NAME'] ); ?>"});
        $.cookie('<?php echo "{$DT->table}"?>', '<?php echo str_replace("'", "\'",json_encode($data_cookie)) ; ?>', { path: "<?php wel_esc_script_e( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $_SERVER['SERVER_NAME'] ); ?>"});
		// save list current_page_ids to localStorage.
		window.localStorage.setItem('wel_member_current_page_ids', <?php echo wp_json_encode( $DT->currentPageIds ); ?>);
    })();

	$("#dlMemberListDialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 400,
		width: 700,
		resizable: true,
		modal: true,
		buttons: {
			'<?php _e('close', 'usces'); ?>': function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});
	$('#dl_mem').click(function() {
		var args = "&ftype=csv&returnList=1";
		$('*[class=check_member]').each(function(i) {
			if($(this).prop('checked')) {
				args += '&check['+$(this).val()+']=on';
			}
		});
		location.href = "<?php echo USCES_ADMIN_URL; ?>?page=usces_memberlist&member_action=dlmembernewlist&noheader=true"+args+"&wc_nonce=<?php echo wp_create_nonce( 'dlmemberlist' ); ?>";
	});
	$('#dl_memberlist').click(function() {
		$('#dlMemberListDialog').dialog('open');
	});

	if ($.cookie("memberSearchBox") == true){
		$("#searchVisiLink").html('<?php _e('Hide the Operation field', 'usces'); ?><span class="dashicons dashicons-arrow-up"></span>');
		$("#searchBox").show();
	}else if ($.cookie("memberSearchBox") == false){
		$("#searchVisiLink").html('<?php _e('Show the Operation field', 'usces'); ?><span class="dashicons dashicons-arrow-down"></span>');
		$("#searchBox").hide();
	}

	$("input[name='allcheck']").click(function () {
		if( $(this).prop("checked") ){
			$("input[name*='listcheck']").prop({checked: true});
		}else{
			$("input[name*='listcheck']").prop({checked: false});
		}
	});

	operation = {
		change_member_search_field_0 :function (is_change = true){

			var html = '';
			var column = $("#searchmemberselect_0").val();

			if( column == 'rank' ) {

				html = '<select name="search[member_word][0]" class="searchselect">';
<?php
	foreach((array)$member_status as $dkey => $dvalue){
		if( isset($arr_search['member_word'][0]) && $dkey == $arr_search['member_word'][0]){
			$dselected = ' selected="selected"';
		}else{
			$dselected = '';
		}
?>
				html += '<option value="<?php echo esc_attr( $dkey ); ?>"<?php echo esc_attr( $dselected ); ?>><?php echo esc_html( $dvalue ); ?></option>';
<?php
	}
?>
				html += '</select>';

			}else if( column == 'country' ) {

				html = '<select name="search[member_word][0]" class="searchselect">';
<?php
	foreach((array)$member_country as $dkey => $dvalue){
		if( isset($arr_search['member_word'][0]) && $dkey == $arr_search['member_word'][0]){
			$dselected = ' selected="selected"';
		}else{
			$dselected = '';
		}
?>
				html += '<option value="<?php echo esc_attr( $dkey ); ?>"<?php echo esc_attr( $dselected ); ?>><?php echo esc_html( $dvalue ); ?></option>';
<?php
	}
?>
				html += '</select>';

			}else{

				html = '<input name="search[member_word][0]" type="text" value="'+ (!is_change ? "<?php echo esc_attr($arr_search['member_word'][0]); ?>" : '') +'" class="regular-text" maxlength="50" />';
				html += '<select name="search[member_word_term][0]" class="termselect">';
				html += '<option value="contain"<?php echo ( 'contain' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Contain', 'usces'); ?></option>';
				html += '<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Not Contain', 'usces'); ?></option>';
				html += '<option value="equal"<?php echo ( 'equal' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Equal', 'usces'); ?></option>';
				html += '<option value="morethan"<?php echo ( 'morethan' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('More than', 'usces'); ?></option>';
				html += '<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['member_word_term'][0] ? ' selected="selected"' : ''); ?>><?php _e('Less than', 'usces'); ?></option>';
				html += '</select>';

			}

			$("#searchmemberword_0").html( html );

		},

		change_member_search_field_1 :function (is_change = true){

			var html = '';
			var column = $("#searchmemberselect_1").val();

			if( column == 'rank' ) {

				html = '<select name="search[member_word][1]" class="searchselect">';
<?php
	foreach((array)$member_status as $dkey => $dvalue){
		if( isset($arr_search['member_word'][1]) && $dkey == $arr_search['member_word'][1]){
			$dselected = ' selected="selected"';
		}else{
			$dselected = '';
		}
?>
				html += '<option value="<?php echo esc_attr( $dkey ); ?>"<?php echo esc_attr( $dselected ); ?>><?php echo esc_html( $dvalue ); ?></option>';
<?php
	}
?>
				html += '</select>';

			}else if( column == 'country' ) {

				html = '<select name="search[member_word][1]" class="searchselect">';
<?php
	foreach((array)$member_country as $dkey => $dvalue){
		if( isset($arr_search['member_word'][1]) && $dkey == $arr_search['member_word'][1]){
			$dselected = ' selected="selected"';
		}else{
			$dselected = '';
		}
?>
				html += '<option value="<?php echo esc_attr( $dkey ); ?>"<?php echo esc_attr( $dselected ); ?>><?php echo esc_html( $dvalue ); ?></option>';
<?php
	}
?>
				html += '</select>';

			}else{

				html = '<input name="search[member_word][1]" type="text" value="'+ (!is_change ? "<?php echo esc_attr($arr_search['member_word'][1]); ?>" : "") +'" class="regular-text" maxlength="50" />';
				html += '<select name="search[member_word_term][1]" class="termselect">';
				html += '<option value="contain"<?php echo ( 'contain' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Contain', 'usces'); ?></option>';
				html += '<option value="notcontain"<?php echo ( 'notcontain' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Not Contain', 'usces'); ?></option>';
				html += '<option value="equal"<?php echo ( 'equal' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Equal', 'usces'); ?></option>';
				html += '<option value="morethan"<?php echo ( 'morethan' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('More than', 'usces'); ?></option>';
				html += '<option value="lessthan"<?php echo ( 'lessthan' == $arr_search['member_word_term'][1] ? ' selected="selected"' : ''); ?>><?php _e('Less than', 'usces'); ?></option>';
				html += '</select>';

			}

			$("#searchmemberword_1").html( html );

		},

		change_collective_field :function (){

			var html = '';
			var column = $("#changeselect").val();

			if( column == 'rank' ) {

				html = '<select name="change[word]" class="searchselect">';
<?php
	foreach((array)$member_status as $dkey => $dvalue){
?>
				html += '<option value="<?php echo esc_attr($dkey); ?>"><?php echo esc_html($dvalue); ?></option>';
<?php
	}
?>
				html += '</select>';

			}else if( column == 'point' ){

				html = '<input name="change[word]" type="text" value="" class="regular-text" maxlength="50" />';

			}else{

				html = '';

			}

			$("#changefield").html( html );

		}
	};

	$("#searchmemberselect_0").change(function () {
		operation.change_member_search_field_0();
	});
	$("#searchmemberselect_1").change(function () {
		operation.change_member_search_field_1();
	});
	$("#changeselect").change(function () {
		operation.change_collective_field();
	});
	operation.change_member_search_field_0(false);
	operation.change_member_search_field_1(false);
	operation.change_collective_field();

	$("#collective_change_member").click(function () {
		if( $("#changeselect option:selected").val() == '' ) {
			$("#memberlistaction").val('');
			return false;
		}
		if( $("input[name*='listcheck']:checked").length == 0 ) {
			alert("<?php _e('Choose the data.', 'usces'); ?>");
			$("#memberlistaction").val('');
			return false;
		}
		var coll = $("#changeselect").val();
		var mes = '';
		if( coll == 'rank' ){
			mes = <?php echo sprintf(__("%s + ' which you have checked will be changed in to ' + %s + '. %sDo you agree?'", 'usces'),
							'$("#changeselect").val()',
							'$("select\[name=\"change\[word\]\"\] option:selected").html()',
							'\n\n'); ?>;
		}else if( coll == 'point' ){
			mes = <?php echo sprintf(__("%s + ' which you have cheked will be changed in to ' + %s + '. %sDo you agree?'", 'usces'),
							'$("#changeselect").val()',
							'$("input\[name=\"change\[word\]\"\]").val()',
							'\n\n'); ?>;
		}else if(coll == 'delete'){
			mes = '<?php _e('Are you sure of deleting all the checked data in bulk?', 'usces'); ?>';
		}
		if( mes != '' ) {
			if( !confirm(mes) ){
				$("#memberlistaction").val('');
				return false;
			}
		}
		<?php do_action( 'usces_action_member_list_collective_change_js' ); ?>
		$("#memberlistaction").val('collective');
		$('#form_tablesearch').submit();
	});

	<?php do_action('usces_action_member_list_page_js'); ?>
});
</script>
