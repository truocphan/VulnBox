<?php
require_once( USCES_PLUGIN_DIR . "/classes/dataList.class.php" );
global $wpdb;

$tableName = usces_get_tablename( 'usces_member' );
$arr_column = array(
			__('membership number', 'usces') => 'ID',
			__('name', 'usces') => 'name',
			__('Address', 'usces') => 'address',
			__('Phone number', 'usces') => 'tel',
			__('e-mail', 'usces') => 'email',
			__('Strated date', 'usces') => 'date',
			__('current point', 'usces') => 'point');
if( !usces_is_membersystem_point() ) {
	array_pop($arr_column);
}

$DT = new dataList($tableName, $arr_column);
$res = $DT->MakeTable();
$arr_search = $DT->GetSearchs();
$arr_header = $DT->GetListheaders();
$dataTableNavigation = $DT->GetDataTableNavigation();
$rows = $DT->rows;

$usces_admin_path = '';
$admin_perse = explode('/', $_SERVER['REQUEST_URI']);
$apct = count($admin_perse) - 1;
for($ap=0; $ap < $apct; $ap++){
    $usces_admin_path .= $admin_perse[$ap] . '/';
}

$csmb_meta = usces_has_custom_field_meta('member');
$usces_opt_member = get_option('usces_opt_member');
$chk_mem = (isset($usces_opt_member['chk_mem'])) ? $usces_opt_member['chk_mem'] : [];
$applyform = usces_get_apply_addressform($this->options['system']['addressform']);
?>
<script type="text/javascript">
function deleteconfirm(member_id){
	if(confirm(<?php _e("'Are you sure of deleting your membership number ' + member_id + ' ?'", 'usces'); ?>)){
		return true;
	}else{
		return false;
	}
}

jQuery(document).ready(function($){
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
			$("#searchVisiLink").html('<?php _e('Show the Operation field', 'usces'); ?>');
		} else {
			$("#searchBox").css("display", "block");
			$("#searchVisiLink").html('<?php _e('Hide the Operation field', 'usces'); ?>');
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
        $data_cookie['searchSql'] = $DT->searchSql;
        $data_cookie['arr_search'] = $DT->arr_search;
        $data_cookie['searchSwitchStatus'] = $DT->searchSwitchStatus;
        ?>
        $.cookie('<?php echo esc_attr( "{$DT->table}_path" ); ?>', '<?php echo esc_attr( $usces_admin_path ); ?>', { path: "<?php echo esc_attr( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $_SERVER['SERVER_NAME'] ); ?>"});
        $.cookie('<?php echo esc_attr( "{$DT->table}" );?>', '<?php echo str_replace( "'", "\'", json_encode( $data_cookie ) ); ?>', { path: "<?php echo esc_attr( $usces_admin_path ); ?>", domain: "<?php echo esc_attr( $_SERVER['SERVER_NAME'] ); ?>"});
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
		var args = "&search[column]="+$(':input[name="search[column]"]').val()
			+"&search[word]="+$(':input[name="search[word]"]').val()
			+"&searchSwitchStatus="+$(':input[name="searchSwitchStatus"]').val()
			+"&ftype=csv";
		$('*[class=check_member]').each(function(i) {
			if($(this).prop('checked')) {
				args += '&check['+$(this).val()+']=on';
			}
		});
		location.href = "<?php echo USCES_ADMIN_URL; ?>?page=usces_memberlist&member_action=dlmemberlist&noheader=true"+args+"&wc_nonce=<?php echo wp_create_nonce( 'dlmemberlist' ); ?>";
	});
	$('#dl_memberlist').click(function() {
		$('#dlMemberListDialog').dialog('open');
	});


	<?php do_action('usces_action_member_list_page_js'); ?>
});
</script>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Management <?php _e('List of Members','usces'); ?></h1>
<p class="version_info">Version <?php echo esc_html( USCES_VERSION ); ?></p>
<?php usces_admin_action_status(); ?>

<div id="datatable">
<div id="tablenavi"><?php wel_esc_script_e( $dataTableNavigation ); ?></div>

<div id="tablesearch">
<div id="searchBox">
	<form action="<?php echo USCES_ADMIN_URL . '?page=usces_memberlist'; ?>" method="post" name="tablesearch">
		<table id="search_table">
		<tr>
		<td><?php _e('search fields', 'usces'); ?></td>
		<td><select name="search[column]" class="searchselect">
		    <option value="none"> </option>
<?php foreach ((array)$arr_column as $key => $value):
		if($value == $arr_search['column']){
			$selected = ' selected="selected"';
		}else{
			$selected = '';
		}
?>
			<option value="<?php echo esc_attr( $value ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $key ); ?></option>
<?php endforeach; ?>

<?php foreach ((array)$csmb_meta as $key => $value):
		$csmb_key = 'csmb_'.$key;
		if($csmb_key == $arr_search['column']){
			$selected = ' selected="selected"';
		}else{
			$selected = '';
		}
?>
		    <option value="<?php echo esc_attr($csmb_key); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html($value['name']); ?></option>
<?php endforeach; ?>

    	</select></td>
		<td><?php _e('key words', 'usces'); ?></td>
		<td><input name="search[word]" type="text" value="<?php echo esc_attr($arr_search['word']); ?>" class="searchword" maxlength="50" /></td>
		<td><input name="searchIn" type="submit" class="searchbutton button" value="<?php _e('Search', 'usces'); ?>" />
		<input name="searchOut" type="submit" class="searchbutton button" value="<?php _e('Cancellation', 'usces'); ?>" />
		<input name="searchSwitchStatus" id="searchSwitchStatus" type="hidden" value="<?php echo esc_attr($DT->searchSwitchStatus); ?>" />
		</td>
		</tr>
		</table>
		<table id="dl_list_table">
		<tr>
		<?php do_action( 'usces_action_dl_member_list_table' ); ?>
		<td><input type="button" id="dl_memberlist" class="searchbutton button" value="<?php _e('Download Member List', 'usces'); ?>" /></td>
		</tr>
		</table>
	</form>
</div>
</div>

<table id="mainDataTable" cellspacing="1">
	<tr>
<?php foreach ( (array)$arr_header as $value ) : ?>
		<th scope="col"><?php wel_esc_script_e( $value ); ?></th>
<?php endforeach; ?>
		<th scope="col">&nbsp;</th>
	</tr>
<?php foreach ( (array)$rows as $array ) : ?>
	<tr>
	<?php foreach ( (array)$array as $key => $value ) : ?>
		<?php if( WCUtils::is_blank($value) ) $value = '&nbsp;'; ?>
		<?php if( $key == 'ID' ): ?>
		<td><a href="<?php echo esc_url( USCES_ADMIN_URL . '?page=usces_memberlist&member_action=edit&member_id=' . $value ); ?>"><?php echo esc_html( $value ); ?></a></td>
		<?php elseif( $key == 'name' ): ?>
		<td>
		<?php
			$names = explode(' ', $value);
			usces_localized_name( esc_html($names[0]), esc_html($names[1]));
		?>
		</td>
		<?php elseif( $key == 'address' ):
			$pos = strpos( $value, __('-- Select --','usces') );
			if( $pos !== false ) $value = '&nbsp;';
		?>
		<td><?php echo esc_html($value); ?></td>
		<?php elseif( $key == 'point' ): ?>
		<td class="right"><?php echo esc_html($value); ?></td>
		<?php else: ?>
		<td><?php echo esc_html($value); ?></td>
		<?php endif; ?>
<?php endforeach; ?>
	<td><a href="<?php echo esc_url( USCES_ADMIN_URL ); ?>?page=usces_memberlist&member_action=delete&member_id=<?php echo esc_html( $array['ID'] ); ?>&wc_nonce=<?php echo wp_create_nonce( 'delete_member' ); ?>" onclick="return deleteconfirm('<?php echo esc_js( $array['ID'] ); ?>');"><span style="color:#FF0000; font-size:9px;"><?php _e( 'Delete', 'usces' ); ?></span></a></td>
	</tr>
<?php endforeach; ?>
</table>

</div>
<div id="dlMemberListDialog" title="<?php _e('Download Member List', 'usces'); ?>">
	<p><?php _e('Select the item you want, please press the download.', 'usces'); ?></p>
	<fieldset>
		<input type="button" class="button" id="dl_mem" value="<?php _e('Download', 'usces'); ?>" />
	</fieldset>
	<fieldset><legend><?php _e('Membership information', 'usces'); ?></legend>
		<label for="chk_mem[ID]"><input type="checkbox" class="check_member" id="chk_mem[ID]" value="ID" checked disabled /><?php _e('membership number', 'usces'); ?></label>
		<label for="chk_mem[email]"><input type="checkbox" class="check_member" id="chk_mem[email]" value="email"<?php usces_checked($chk_mem, 'email'); ?> /><?php _e('e-mail', 'usces'); ?></label>
<?php
	if(!empty($csmb_meta)) {
		foreach($csmb_meta as $key => $entry) {
			if($entry['position'] == 'name_pre') {
				$csmb_key = 'csmb_'.$key;
				$checked = usces_checked( $chk_mem, $csmb_key, 'return' );
				$name = $entry['name'];
				echo '<label for="chk_mem['.$csmb_key.']"><input type="checkbox" class="check_member" id="chk_mem['.esc_attr($csmb_key).']" value="'.esc_attr($csmb_key).'"'.$checked.' />'.esc_html($name).'</label>'."\n";
			}
		}
	}
?>
		<label for="chk_mem[name]"><input type="checkbox" class="check_member" id="chk_mem[name]" value="name" checked disabled /><?php _e('name', 'usces'); ?></label>
<?php
	switch($applyform) {
	case 'JP':
?>
		<label for="chk_mem[kana]"><input type="checkbox" class="check_member" id="chk_mem[kana]" value="kana"<?php usces_checked($chk_mem, 'kana'); ?> /><?php _e('furigana','usces'); ?></label>
<?php
		break;
	}

	if(!empty($csmb_meta)) {
		foreach($csmb_meta as $key => $entry) {
			if($entry['position'] == 'name_after') {
				$csmb_key = 'csmb_'.$key;
				$checked = usces_checked( $chk_mem, $csmb_key, 'return' );
				$name = $entry['name'];
				echo '<label for="chk_mem['.esc_attr($csmb_key).']"><input type="checkbox" class="check_member" id="chk_mem['.esc_attr($csmb_key).']" value="'.esc_attr($csmb_key).'"'.$checked.' />'.esc_html($name).'</label>'."\n";
			}
		}
	}

	switch($applyform) {
	case 'JP':
?>
		<label for="chk_mem[zip]"><input type="checkbox" class="check_member" id="chk_mem[zip]" value="zip"<?php usces_checked($chk_mem, 'zip'); ?> /><?php _e('Zip/Postal Code', 'usces'); ?></label>
		<label for="chk_mem[country]"><input type="checkbox" class="check_member" id="chk_mem[country]" value="country" checked disabled /><?php _e('Country', 'usces'); ?></label>
		<label for="chk_mem[pref]"><input type="checkbox" class="check_member" id="chk_mem[pref]" value="pref" checked disabled /><?php _e('Province', 'usces'); ?></label>
		<label for="chk_mem[address1]"><input type="checkbox" class="check_member" id="chk_mem[address1]" value="address1" checked disabled /><?php _e('city', 'usces'); ?></label>
		<label for="chk_mem[address2]"><input type="checkbox" class="check_member" id="chk_mem[address2]" value="address2" checked disabled /><?php _e('numbers', 'usces'); ?></label>
		<label for="chk_mem[address3]"><input type="checkbox" class="check_member" id="chk_mem[address3]" value="address3" checked disabled /><?php _e('building name', 'usces'); ?></label>
		<label for="chk_mem[tel]"><input type="checkbox" class="check_member" id="chk_mem[tel]" value="tel"<?php usces_checked($chk_mem, 'tel'); ?> /><?php _e('Phone number', 'usces'); ?></label>
		<label for="chk_mem[fax]"><input type="checkbox" class="check_member" id="chk_mem[fax]" value="fax"<?php usces_checked($chk_mem, 'fax'); ?> /><?php _e('FAX number', 'usces'); ?></label>
<?php
		break;
	case 'US':
	default:
?>
		<label for="chk_mem[address2]"><input type="checkbox" class="check_member" id="chk_mem[address2]" value="address2" checked disabled /><?php _e('Address Line1', 'usces'); ?></label>
		<label for="chk_mem[address3]"><input type="checkbox" class="check_member" id="chk_mem[address3]" value="address3" checked disabled /><?php _e('Address Line2', 'usces'); ?></label>
		<label for="chk_mem[address1]"><input type="checkbox" class="check_member" id="chk_mem[address1]" value="address1" checked disabled /><?php _e('city', 'usces'); ?></label>
		<label for="chk_mem[pref]"><input type="checkbox" class="check_member" id="chk_mem[pref]" value="pref" checked disabled /><?php _e('State', 'usces'); ?></label>
		<label for="chk_mem[country]"><input type="checkbox" class="check_member" id="chk_mem[country]" value="country" checked disabled /><?php _e('Country', 'usces'); ?></label>
		<label for="chk_mem[zip]"><input type="checkbox" class="check_member" id="chk_mem[zip]" value="zip"<?php usces_checked($chk_mem, 'zip'); ?> /><?php _e('Zip', 'usces'); ?></label>
		<label for="chk_mem[tel]"><input type="checkbox" class="check_member" id="chk_mem[tel]" value="tel"<?php usces_checked($chk_mem, 'tel'); ?> /><?php _e('Phone number', 'usces'); ?></label>
		<label for="chk_mem[fax]"><input type="checkbox" class="check_member" id="chk_mem[fax]" value="fax"<?php usces_checked($chk_mem, 'fax'); ?> /><?php _e('FAX number', 'usces'); ?></label>
<?php
		break;
	}

	if(!empty($csmb_meta)) {
		foreach($csmb_meta as $key => $entry) {
			if($entry['position'] == 'fax_after') {
				$csmb_key = 'csmb_'.$key;
				$checked = usces_checked( $chk_mem, $csmb_key, 'return' );
				$name = $entry['name'];
				echo '<label for="chk_mem['.esc_attr($csmb_key).']"><input type="checkbox" class="check_member" id="chk_mem['.esc_attr($csmb_key).']" value="'.esc_attr($csmb_key).'"'.$checked.' />'.esc_html($name).'</label>'."\n";
			}
		}
	}
?>
		<label for="chk_mem[date]"><input type="checkbox" class="check_member" id="chk_mem[date]" value="date"<?php usces_checked($chk_mem, 'date'); ?> /><?php _e('Strated date','usces'); ?></label>
		<label for="chk_mem[point]"><input type="checkbox" class="check_member" id="chk_mem[point]" value="point"<?php usces_checked($chk_mem, 'point'); ?> /><?php _e('current point','usces'); ?></label>
		<label for="chk_mem[rank]"><input type="checkbox" class="check_member" id="chk_mem[rank]" value="rank"<?php usces_checked($chk_mem, 'rank'); ?> /><?php _e('Rank', 'usces'); ?></label>
		<?php do_action( 'usces_action_chk_mem', $chk_mem ); ?>
	</fieldset>
</div>
<?php do_action( 'usces_action_member_list_footer' ); ?>
</div><!--usces_admin-->
</div><!--wrap-->
