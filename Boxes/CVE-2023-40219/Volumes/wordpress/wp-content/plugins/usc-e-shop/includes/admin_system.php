<?php
/**
 * Admin - System Setting Page.
 *
 * @package Welcart
 */

global $usces_settings;

$divide_item                = $this->options['divide_item'];
$itemimg_anchor_rel         = $this->options['itemimg_anchor_rel'];
$fukugo_category_orderby    = $this->options['fukugo_category_orderby'];
$fukugo_category_order      = $this->options['fukugo_category_order'];
$settlement_path            = $this->options['settlement_path'];
$logs_path                  = ( isset( $this->options['logs_path'] ) ) ? $this->options['logs_path'] : '';
$use_ssl                    = $this->options['use_ssl'];
$ssl_url                    = $this->options['ssl_url'];
$ssl_url_admin              = $this->options['ssl_url_admin'];
$inquiry_id                 = $this->options['inquiry_id'];
$orderby_itemsku            = ( isset( $this->options['system']['orderby_itemsku'] ) ) ? $this->options['system']['orderby_itemsku'] : 0;
$orderby_itemopt            = ( isset( $this->options['system']['orderby_itemopt'] ) ) ? $this->options['system']['orderby_itemopt'] : 0;
$front_language             = apply_filters( 'usces_filter_front_language', $usces_settings['language'] );
$system_front_lang          = ( isset( $this->options['system']['front_lang'] ) && ! empty( $this->options['system']['front_lang'] ) ) ? $this->options['system']['front_lang'] : usces_get_local_language();
$system_currency            = ( isset( $this->options['system']['currency'] ) && ! empty( $this->options['system']['currency'] ) ) ? $this->options['system']['currency'] : usces_get_base_country();
$system_addressform         = ( isset( $this->options['system']['addressform'] ) && ! empty( $this->options['system']['addressform'] ) ) ? $this->options['system']['addressform'] : usces_get_local_addressform();
$system_target_markets      = ( isset( $this->options['system']['target_market'] ) && ! empty( $this->options['system']['target_market'] ) ) ? $this->options['system']['target_market'] : usces_get_local_target_market();
$no_cart_css                = ( isset( $this->options['system']['no_cart_css'] ) ) ? $this->options['system']['no_cart_css'] : 0;
$dec_orderid_flag           = ( isset( $this->options['system']['dec_orderID_flag'] ) ) ? $this->options['system']['dec_orderID_flag'] : 0;
$dec_orderid_prefix         = ( isset( $this->options['system']['dec_orderID_prefix'] ) ) ? $this->options['system']['dec_orderID_prefix'] : '';
$dec_orderid_digit          = ( isset( $this->options['system']['dec_orderID_digit'] ) ) ? $this->options['system']['dec_orderID_digit'] : '';
$subimage_rule              = ( isset( $this->options['system']['subimage_rule'] ) ) ? $this->options['system']['subimage_rule'] : 0;
$pdf_delivery               = ( isset( $this->options['system']['pdf_delivery'] ) ) ? $this->options['system']['pdf_delivery'] : 0;
$recaptcha_v3               = ( isset( $this->options['system']['recaptcha_v3'] ) ) ? $this->options['system']['recaptcha_v3'] : 0;
$recaptcha_site_key         = ( isset( $this->options['system']['recaptcha_site_key'] ) ) ? $this->options['system']['recaptcha_site_key'] : '';
$recaptcha_secret_key       = ( isset( $this->options['system']['recaptcha_secret_key'] ) ) ? $this->options['system']['recaptcha_secret_key'] : '';
$member_pass_rule_min       = ( isset( $this->options['system']['member_pass_rule_min'] ) ) ? $this->options['system']['member_pass_rule_min'] : 6;
$member_pass_rule_max       = ( isset( $this->options['system']['member_pass_rule_max'] ) ) && ! empty( $this->options['system']['member_pass_rule_max'] ) ? $this->options['system']['member_pass_rule_max'] : '';
$member_pass_rule_upercase  = ( isset( $this->options['system']['member_pass_rule_upercase'] ) ) ? boolval( $this->options['system']['member_pass_rule_upercase'] ) : false;
$member_pass_rule_lowercase = ( isset( $this->options['system']['member_pass_rule_lowercase'] ) ) ? boolval( $this->options['system']['member_pass_rule_lowercase'] ) : false;
$member_pass_rule_digit     = ( isset( $this->options['system']['member_pass_rule_digit'] ) ) ? boolval( $this->options['system']['member_pass_rule_digit'] ) : false;
$member_pass_rule_symbol    = ( isset( $this->options['system']['member_pass_rule_symbol'] ) ) ? boolval( $this->options['system']['member_pass_rule_symbol'] ) : false;
$csv_encode_type            = ( isset( $this->options['system']['csv_encode_type'] ) ) ? $this->options['system']['csv_encode_type'] : 0;
$csv_category_format        = ( isset( $this->options['system']['csv_category_format'] ) ) ? $this->options['system']['csv_category_format'] : 0;
$settlement_backup          = ( isset( $this->options['system']['settlement_backup'] ) ) ? $this->options['system']['settlement_backup'] : 0;
$settlement_notice          = ( isset( $this->options['system']['settlement_notice'] ) ) ? $this->options['system']['settlement_notice'] : 0;
?>
<script type="text/javascript">
jQuery(function($){

	var pre_target = '';

	operation = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false,
		},

		set_target_market: function() {
			var target = [];
			var target_text = [];
			$("#target_market option:selected").each(function () {
				target.push($(this).val());
				target_text.push($(this).text());
			});
			if(target.length == 0) {
				alert('<?php esc_html_e( 'Please select one of the country.', 'usces' ); ?>');
				return -1;
			}
			var sel = $('select_target_market_province').val();
			var name_select = '<select name="select_target_market_province" id="select_target_market_province" onchange="operation.onchange_target_market_province(this.selectedIndex);">'+"\n";
			var target_args = '';
			var c = '';
			for(var i=0; i<target.length; i++){
				name_select += '<option value="'+target[i]+'">'+target_text[i]+'</option>'+"\n";
				target_args += c+target[i];
				c = ',';
			}
			name_select += "</select>\n";
			$("#target_market_province").html(name_select);
			$("#target_market_loading").html('<img src="<?php echo esc_url( USCES_PLUGIN_URL ); ?>/images/loading-publish.gif" />');
			var s = operation.settings;
			s.data = "action=target_market_ajax&target="+target_args+"&wc_nonce=<?php echo wp_create_nonce( 'target_market_ajax' ); ?>";
			$.ajax( s ).done(function( data ) {
				$('#province_ajax').empty();
				for(var i=0; i < data.length; i++) {
					if(data[i].length > 0) {
						var state = data[i].split(',');
						var value = (state[1] === undefined || state[1] === 'undefined') ? '' : state[1];
						$('#province_ajax').append('<input type="hidden" name="province_'+state[0]+'" id="province_'+state[0]+'" value="'+ value +'">');
					}
				}
				$('#select_target_market_province').triggerHandler('change', 0);
				$("#target_market_loading").html('');
			}).fail(function( msg ){
				$("#tusces_systemarget_market_loading").html('');
			});
			return false;
		},

		onchange_target_market_province: function(index) {
			if(pre_target != '') $('#province_'+pre_target).val($("#province").val());
			var target = $("#select_target_market_province option:selected").val();
			$("#province").val('');
			$("#province").val($('#province_'+target).val());
			pre_target = target;
		},

		backup : function() {
			var s = operation.settings;
			s.data = "action=usces_admin_ajax&mode=options_backup&wc_nonce=<?php echo wp_create_nonce( 'options_backup' ); ?>";
			$.ajax( s ).done(function( data ) {
				if(data) {
					alert("<?php esc_html_e( 'Has been saved.', 'usces' ); ?>");
					$("#options_restore").prop( "disabled", false );
					$("#options_backup_date").html(data);
				} else {
					alert("<?php esc_html_e( 'I failed to save.', 'usces' ); ?>");
				}
			}).fail(function( msg ){
				alert("<?php esc_html_e( 'I failed to save.', 'usces' ); ?>");
			});
			return false;
		},

		restore : function() {
			var s = operation.settings;
			s.data = "action=usces_admin_ajax&mode=options_restore&wc_nonce=<?php echo wp_create_nonce( 'options_restore' ); ?>";
			$.ajax( s ).done(function( data ) {
				if(data) {
					location.href = "<?php echo esc_url( USCES_ADMIN_URL ); ?>?page=usces_system";
				} else {
					alert("<?php esc_html_e( 'I failed to restore.', 'usces' ); ?>");
				}
			}).fail(function( msg ){
				alert("<?php esc_html_e( 'I failed to restore.', 'usces' ); ?>");
			});
			return false;
		},

		error_bg_color : function(id) {
			$(id).css({'background-color': '#FFA'}).click(function() {
				$(id).css({'background-color': '#FFF'});
			});
		}
	};

	$('form').submit(function() {
		$('#province_'+pre_target).val($("#province").val());

		var error = 0;
		var tabs = 0;

		if( !checkAlp( $("#dec_orderID_prefix").val() ) ) {
			error++;
			$("#dec_orderID_prefix").css({'background-color': '#FFA'}).click(function() {
				$(this).css({'background-color': '#FFF'});
			});
		}
		if( !checkNum( $("#dec_orderID_digit").val() ) ) {
			error++;
			$("#dec_orderID_digit").css({'background-color': '#FFA'}).click(function() {
				$(this).css({'background-color': '#FFF'});
			});
		}
		if( !checkNum( $("#member_pass_rule_min").val() ) || $("#member_pass_rule_min").val() == false ){
			error++;
			operation.error_bg_color("#member_pass_rule_min");
		}
		if( !checkNum( $("#member_pass_rule_max").val() ) || $("#member_pass_rule_max").val() > 30 ) {
			error++;
			operation.error_bg_color("#member_pass_rule_max");
		}
		if( parseInt($("#member_pass_rule_min").val()) > parseInt($("#member_pass_rule_max").val()) ) {
			error++;
			operation.error_bg_color("#member_pass_rule_min");
			operation.error_bg_color("#member_pass_rule_max");
		}
		var target = [];
		$("#target_market option:selected").each(function() {
			target.push($(this).val());
		});
		if( target.length == 0 ) {
			error++;
			tabs = 1;
			$("#target_market").css({'background-color': '#FFA'}).click(function() {
				$(this).css({'background-color': '#FFF'});
			});

		} else {
			var province = 'OK';
			for(var i=0; i<target.length; i++) {
				if(target[i] != 'JP' && target[i] != 'US') {
					if( "" == $("#province_"+target[i]).val() ) province = 'NG';
				}
			}
			if( 'OK' != province ) {
				error++;
				tabs = 1;
				$("#province").css({'background-color': '#FFA'}).click(function() {
					$(this).css({'background-color': '#FFF'});
				});
			}
		}

		if( 0 < error ) {
			$("#aniboxStatus").removeClass("none");
			$("#aniboxStatus").addClass("error");
			$("#info_image").attr("src", "<?php echo esc_url( USCES_PLUGIN_URL ); ?>/images/list_message_error.gif");
			$("#info_massage").html("<?php esc_html_e( 'There is incomplete data.', 'usces' ); ?>");
			$("#anibox").animate({ backgroundColor: "#FFE6E6" }, 2000);
			if( $.fn.jquery < "1.10" ) {
				$('#uscestabs_system').tabs("select", tabs);
			} else {
				$('#uscestabs_system').tabs("option", "active", tabs);
			}
			return false;
		} else {
			return true;
		}
	});

	$(".hndle").click(function() {
		var inside = $(this).next();
		var key = $(this).attr("id");
		if( 'visible' == $.cookie(key) ){
			inside.hide('slow');
			$.cookie(key, 'hidden');
		}else{
			inside.show('slow');
			$.cookie(key, 'visible');
		}
	});
	$("#system_page_setting_3 .hndle").each(function(i,e) {
		var key = $(e).attr('id');
		var inside = $(e).next();
		if( 'visible' == $.cookie(key) ){
			$(inside).show();
		}else{
			$(inside).hide();
		}
	});

	$( document ).on( 'click', '#system_page_setting_3 .handlediv', function( e ) {
		var $el = $( this ),
			p = $el.closest( '.postbox' ),
			ariaExpandedValue;
		p.toggleClass( 'closed' );
		ariaExpandedValue = ! p.hasClass( 'closed' );
		$el.attr( 'aria-expanded', ariaExpandedValue );

		var key = $el.attr( 'id' );
		if ( p.hasClass( 'closed' ) ) {
			$.cookie( key, 'hidden' );
		} else {
			$.cookie( key, 'visible' );
		}
	});
	$( '#system_page_setting_3 .handlediv' ).each( function( i, e ) {
		var $el = $( this ),
			p = $el.closest( '.postbox' );

		var key = $( this ).attr( 'id' );
		if ( 'visible' == $.cookie( key ) ) {
			p.removeClass( 'closed' );
		} else {
			p.toggleClass( 'closed' );
		}
	});
});

jQuery(document).ready(function($) {
	operation.set_target_market();

	if( $.fn.jquery < "1.10" ) {
		var $tabs = $('#uscestabs_system').tabs({
			cookie: {
				// store cookie for a day, without, it would be a session cookie
				expires: 1
			}
		});
	} else {
		$( "#uscestabs_system" ).tabs({
			active: ($.cookie("uscestabs_system")) ? $.cookie("uscestabs_system") : 0
			, activate: function( event, ui ){
				$.cookie("uscestabs_system", $(this).tabs("option", "active"));
			}
		});
	}
	<?php
	$options_backup      = get_option( 'usces_backup' );
	$options_backup_date = get_option( 'usces_backup_date', '' );
	if ( empty( $options_backup ) ) :
		?>
	$("#options_restore").prop( "disabled", true );
		<?php
	endif;
	?>
	$('#options_backup').click(function() {
		operation.backup();
	});
	$('#options_restore').click(function() {
		if( !confirm("<?php esc_html_e( 'I will restore the option value. Would you like?', 'usces' ); ?>") ) return;
		operation.restore();
	});
});
</script>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php esc_html_e( 'System Setting', 'usces' ); ?></h1>
<?php usces_admin_action_status(); ?>
<div id="poststuff" class="metabox-holder">

<div class="uscestabs" id="uscestabs_system">
	<ul>
		<li><a href="#system_page_setting_1"><?php esc_html_e( 'System Setting', 'usces' ); ?></a></li>
		<li><a href="#system_page_setting_2"><?php esc_html_e( 'Language Currency Country', 'usces' ); ?></a></li>
		<li><a href="#system_page_setting_3"><?php esc_html_e( 'System extension', 'usces' ); ?></a></li>
		<li><a href="#system_page_setting_4"><?php esc_html_e( 'System Environment', 'usces' ); ?></a></li>
		<?php do_action( 'usces_action_admin_system_tab_label' ); ?>
	</ul>

<form action="" method="post" name="option_form" id="option_form1">

<div id="system_page_setting_1">
<div class="postbox">
<h3><span><?php esc_html_e( 'System Setting', 'usces' ); ?></span></h3>
<div class="inside">

<!-- <input name="usces_option_update" type="submit" class="button" value="<?php esc_html_e( 'change decision', 'usces' ); ?>" /> -->

<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility( 'ex_divide_item' );"><?php esc_html_e( 'Display Modes', 'usces' ); ?></a></th>
		<td width="10"><input name="divide_item" type="checkbox" id="divide_item" value="<?php echo esc_attr( $divide_item ); ?>"<?php checked( $divide_item, 1 ); ?> /></td>
		<td width="300"><label for="divide_item"><?php esc_html_e( 'Not display an article in blog', 'usces' ); ?></label></td>
		<td><div id="ex_divide_item" class="explanation"><?php esc_html_e( 'In the case of the loop indication that plural contributions are displayed in a shop, you can be decided display or non-display the item.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_itemimg_anchor_rel');"><?php esc_html_e( 'rel attribute', 'usces' ); ?></a></th>
		<td width="30">rel="</td>
		<td width="100"><input name="itemimg_anchor_rel" id="itemimg_anchor_rel" type="text" value="<?php echo esc_attr( $itemimg_anchor_rel ); ?>" /></td>
		<td width="10">"</td>
		<td><div id="ex_itemimg_anchor_rel" class="explanation"><?php esc_html_e( 'In item details page, you can appoint a rel attribute for anchor tag to display an image, sach as Lightbox plugin.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_fcat_orderby');"><?php esc_html_e( 'compound category sort item', 'usces' ); ?></a></th>
		<td width="10"><select name="fukugo_category_orderby" id="fukugo_category_orderby">
			<option value="ID"<?php selected( $fukugo_category_orderby, 'ID' ); ?>><?php esc_html_e( 'category ID', 'usces' ); ?></option>
			<option value="name"<?php selected( $fukugo_category_orderby, 'name' ); ?>><?php esc_html_e( 'category name', 'usces' ); ?></option>
		</select></td>
		<td><div id="ex_fcat_orderby" class="explanation"><?php esc_html_e( 'In a category to display in a compound category search page, you can choose an object to sort.', 'usces' ); ?></div></td>
	</tr>
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_fcat_order');"><?php esc_html_e( 'compound category sort order', 'usces' ); ?></a></th>
		<td width="10"><select name="fukugo_category_order" id="fukugo_category_order">
			<option value="ASC"<?php selected( $fukugo_category_order, 'ASC' ); ?>><?php esc_html_e( 'Ascending', 'usces' ); ?></option>
			<option value="DESC"<?php selected( $fukugo_category_order, 'DESC' ); ?>><?php esc_html_e( 'Descendin', 'usces' ); ?></option>
		</select></td>
		<td><div id="ex_fcat_order" class="explanation"><?php esc_html_e( 'In a category to display in a compound category search page, you can choose sort order.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_settlement_path');"><?php esc_html_e( 'settlement module path', 'usces' ); ?></a></th>
		<td><input name="settlement_path" type="text" id="settlement_path" value="<?php echo esc_attr( $settlement_path ); ?>" size="60" /></td>
		<td><div id="ex_settlement_path" class="explanation"><?php esc_html_e( 'This is Field appointing the setting path of the settlement module. The initial value is a place same as a sample, but it is deleted at the time of automatic upgrading. Therefore you must arrange a module outside a plugin folder.', 'usces' ); ?></div></td>
	</tr>
<!--	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_logs_path');"><?php esc_html_e( 'Logs path', 'usces' ); ?></a></th>
		<td><input name="logs_path" type="text" id="logs_path" value="<?php echo esc_attr( $logs_path ); ?>" size="60" />/welcart/logs/</td>
		<td><div id="ex_logs_path" class="explanation"><?php esc_html_e( 'Specify the path to save the log file. Please specify the directory that can not be viewed in a browser. Log is not saved if you do not specify.', 'usces' ); ?></div></td>
	</tr>
--></table>
<hr />
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_use_ssl');"><?php esc_html_e( 'Switching SSL', 'usces' ); ?></a></th>
		<td width="10"><input name="use_ssl" type="checkbox" id="use_ssl" value="<?php echo esc_attr( $use_ssl ); ?>"<?php checked( $use_ssl, 1 ); ?> /></td>
		<td width="300">&nbsp;</td>
		<td><div id="ex_use_ssl" class="explanation"><?php esc_html_e( 'Check in case of switching SSL and Non-SSL between cart page and member page.', 'usces' ); ?><br /><?php esc_html_e( 'Uncheck in case of the site is AOSSL.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_ssl_url_admin');"><?php esc_html_e( 'WordPress address (SSL)', 'usces' ); ?></a></th>
		<td><input name="ssl_url_admin" type="text" id="ssl_url_admin" value="<?php echo esc_attr( $ssl_url_admin ); ?>" size="60" /></td>
		<td><div id="ex_ssl_url_admin" class="explanation"><?php _e( 'https://*WordPress address*<br />You can use common use SSL.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_ssl_url');"><?php esc_html_e( 'Site address (SSL)', 'usces' ); ?></a></th>
		<td><input name="ssl_url" type="text" id="ssl_url" value="<?php echo esc_attr( $ssl_url ); ?>" size="60" /></td>
		<td><div id="ex_ssl_url" class="explanation"><?php _e( 'https://*Site address*<br />You can use common use SSL.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_inquiry_id');"><?php esc_html_e( 'The page_id of the inquiry-form', 'usces' ); ?></a></th>
		<td><input name="inquiry_id" type="text" id="inquiry_id" value="<?php echo esc_attr( $inquiry_id ); ?>" size="7" /></td>
		<td><div id="ex_inquiry_id" class="explanation"><?php _e( 'When you want to use the inquiry-form through SSL, please input the page_id.<br />When you use a permanent link, you have need to set the permanent link of this page in usces-inquiry.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_no_cart_css');"><?php esc_html_e( 'To disable usces_cart.css', 'usces' ); ?></a></th>
		<td width="10"><input name="no_cart_css" type="checkbox" id="no_cart_css" value="<?php echo esc_attr( $no_cart_css ); ?>"<?php checked( $no_cart_css, 1 ); ?> /></td>
		<td width="300">&nbsp;</td>
		<td><div id="ex_no_cart_css" class="explanation"><?php esc_html_e( 'When checked, Welcart will not output the usces_cart.css. If you want to make own usces_cart.css file, please copy and paste it in your theme folder currently in use.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_dec_orderID_flag');"><?php esc_html_e( 'Rules of order-ID numbering', 'usces' ); ?></a></th>
		<td width="10"><input name="dec_orderID_flag" id="dec_orderID_flag0" type="radio" value="0"<?php checked( $dec_orderid_flag, 0 ); ?> /></td><td width="100"><label for="dec_orderID_flag0"><?php esc_html_e( 'Sequential number', 'usces' ); ?></label></td>
		<td width="10"><input name="dec_orderID_flag" id="dec_orderID_flag1" type="radio" value="1"<?php checked( $dec_orderid_flag, 1 ); ?> /></td><td width="100"><label for="dec_orderID_flag1"><?php esc_html_e( 'Random string', 'usces' ); ?></label></td>
		<td><div id="ex_dec_orderID_flag" class="explanation"><?php esc_html_e( 'The initial value is a sequential number starting from 1000.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_dec_orderID_prefix');"><?php esc_html_e( 'Prefix of order-ID', 'usces' ); ?></a></th>
		<td><input name="dec_orderID_prefix" type="text" id="dec_orderID_prefix" value="<?php echo esc_attr( $dec_orderid_prefix ); ?>" size="7" /></td>
		<td><div id="ex_dec_orderID_prefix" class="explanation"><?php esc_html_e( 'If you do not need it, leave it blank.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_dec_orderID_digit');"><?php esc_html_e( 'Digits of order-ID', 'usces' ); ?></a></th>
		<td><input name="dec_orderID_digit" type="text" id="dec_orderID_digit" value="<?php echo esc_attr( $dec_orderid_digit ); ?>" size="7" /></td>
		<td><div id="ex_dec_orderID_digit" class="explanation"><?php esc_html_e( 'This value must be at least six digits. The prefix is not included.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<?php if ( 0 === (int) NEW_PRODUCT_IMAGE_REGISTER::$opts['switch_flag'] ) : ?>
<table class="form_table">
	<tr height="30">
		<th class="system_th" rowspan="2"><a style="cursor:pointer;" onclick="toggleVisibility('ex_subimage_rule');"><?php esc_html_e( 'Product sub-image rule', 'usces' ); ?></a></th>
		<td width="10"><input name="subimage_rule" id="subimage_rule0" type="radio" value="0"<?php checked( $subimage_rule, 0 ); ?> /></td><td width="400"><label for="subimage_rule0"><?php esc_html_e( 'Product Code Forward Matching', 'usces' ); ?></label></td>
		<td rowspan="2"><div id="ex_subimage_rule" class="explanation"><?php esc_html_e( 'Applies only when the extension "New product image registration" is disabled.', 'usces' ); ?><br><?php _e( 'If the sub-images are not applied correctly, please apply the underscore rule.<br />The file name of the sub-image should have two _ (underscores) between the product code and the sequential number.', 'usces' ); ?></div></td>
	</tr>
	<tr height="30">
		<td width="10"><input name="subimage_rule" id="subimage_rule1" type="radio" value="1"<?php checked( $subimage_rule, 1 ); ?> /></td><td width="400"><label for="subimage_rule1"><?php esc_html_e( 'Put two _ (underscores) between the product code and the sequential number', 'usces' ); ?></label></td>
	</tr>
</table>
<hr />
<?php endif; ?>
<table class="form_table">
	<tr height="30">
		<th class="system_th" rowspan="2"><a style="cursor:pointer;" onclick="toggleVisibility('ex_pdf_delivery');"><?php esc_html_e( 'Described method of invoice', 'usces' ); ?></a></th>
		<td width="10"><input name="pdf_delivery" id="pdf_delivery0" type="radio" value="0"<?php checked( $pdf_delivery, 0 ); ?> /></td><td width="300"><label for="pdf_delivery0"><?php esc_html_e( 'To address the purchaser information', 'usces' ); ?></label></td>
		<td rowspan="2"><div id="ex_pdf_delivery" class="explanation"><?php _e( "If you select the 'to address the purchaser information', delivery will be described below address of (purchaser information) when the shipping address is different from the information of the purchaser.<br />Only the information of the destination as you want it to appear on your address if you choose to 'address and the destination.'", 'usces' ); ?></div></td>
	</tr>
	<tr height="30">
		<td width="10"><input name="pdf_delivery" id="pdf_delivery1" type="radio" value="1"<?php checked( $pdf_delivery, 1 ); ?> /></td><td width="300"><label for="pdf_delivery1"><?php esc_html_e( 'To address the shipping information', 'usces' ); ?></label></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="30">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_member_pass_rule');"><?php esc_html_e( 'Character limit for membership password', 'usces' ); ?></a></th>
		<td><input name="member_pass_rule_min" type="text" id="member_pass_rule_min" value="<?php echo esc_attr( $member_pass_rule_min ); ?>" size="3" />&nbsp;<?php esc_html_e( 'or more characters', 'usces' ); ?>&nbsp;&nbsp;</td>
		<td><input name="member_pass_rule_max" type="text" id="member_pass_rule_max" value="<?php echo esc_attr( $member_pass_rule_max ); ?>" size="3" />&nbsp;<?php esc_html_e( 'characters or less', 'usces' ); ?></td>
		<td><div id="ex_member_pass_rule" class="explanation"><?php _e( '[Numeric(one or more)] The password can be set to a length of between 1 and 30 characters.<br />The lower limit must not exceed the upper limit. (10 to 8, etc.)', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
<tr height="30">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_member_character_rule');"><?php esc_html_e( 'Membership password rules', 'usces' ); ?></a></th>
		<td width="10"><input name="member_pass_rule_upercase" type="checkbox" id="member_pass_rule_upercase" value="1" <?php checked( $member_pass_rule_upercase, true ); ?> /></td>
		<td><label for="member_pass_rule_upercase"><?php esc_html_e( 'Include upper-case alphabetics', 'usces' ); ?></label></td>
		<td width="40">&nbsp;</td>
		<td width="10"><input name="member_pass_rule_lowercase" type="checkbox" id="member_pass_rule_lowercase" value="1" <?php checked( $member_pass_rule_lowercase, true ); ?> /></td>
		<td><label for="member_pass_rule_lowercase"><?php esc_html_e( 'Include lower-case alphabetics', 'usces' ); ?></label></td>
		<td width="40">&nbsp;</td>
		<td width="10"><input name="member_pass_rule_digit" type="checkbox" id="member_pass_rule_digit" value="1" <?php checked( $member_pass_rule_digit, true ); ?> /></td>
		<td><label for="member_pass_rule_digit"><?php esc_html_e( 'Include numeric character', 'usces' ); ?></label></td>
		<td width="40">&nbsp;</td>
		<td width="10"><input name="member_pass_rule_symbol" type="checkbox" id="member_pass_rule_symbol" value="1" <?php checked( $member_pass_rule_symbol, true ); ?>/></td>
		<td><label for="member_pass_rule_symbol"><?php esc_html_e( 'Include symbolic character', 'usces' ); ?></label></td>
		<td><div id="ex_member_character_rule" class="explanation"><?php esc_html_e( 'Membership password must follow these rules.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="30">
		<th class="system_th" rowspan="2"><a style="cursor:pointer;" onclick="toggleVisibility('ex_csv_encode_type');"><?php esc_html_e( 'Character code in the CSV file', 'usces' ); ?></a></th>
		<td width="10"><input name="csv_encode_type" id="csv_encode_type0" type="radio" value="0"<?php checked( $csv_encode_type, 0 ); ?> /></td><td width="300"><label for="csv_encode_type0"><?php esc_html_e( 'Shift_JIS', 'usces' ); ?></label></td>
		<td rowspan="2"><div id="ex_csv_encode_type" class="explanation"><?php esc_html_e( 'If you want to perform product registration by uploading a CSV file, please upload a CSV file of character code selected here.', 'usces' ); ?></div></td>
	</tr>
	<tr height="30">
		<td width="10"><input name="csv_encode_type" id="csv_encode_type1" type="radio" value="1"<?php checked( $csv_encode_type, 1 ); ?> /></td><td width="300"><label for="csv_encode_type1"><?php esc_html_e( 'UTF-8', 'usces' ); ?></label></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="30">
		<th class="system_th" rowspan="2"><a style="cursor:pointer;" onclick="toggleVisibility('ex_csv_category_format');"><?php esc_html_e( "'Category' of CSV product data file", 'usces' ); ?></a></th>
		<td width="10"><input name="csv_category_format" id="csv_category_format_0" type="radio" value="0"<?php checked( $csv_category_format, 0 ); ?> /></td><td width="300"><label for="csv_category_format_0"><?php esc_html_e( 'ID (tag_ID)', 'usces' ); ?></label></td>
		<td rowspan="2"><div id="ex_csv_category_format" class="explanation"><?php esc_html_e( "You can select 'Category' registration and output format.", 'usces' ); ?></div></td>
	</tr>
	<tr height="30">
		<td width="10"><input name="csv_category_format" id="csv_category_format_1" type="radio" value="1"<?php checked( $csv_category_format, 1 ); ?> /></td><td width="300"><label for="csv_category_format_1"><?php esc_html_e( 'slug (slug)', 'usces' ); ?></label></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="35">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_options_backup');"><?php esc_html_e( 'Setup data backup', 'usces' ); ?></a></th>
		<td><input name="options_backup" id="options_backup" type="button" class="button" value="<?php esc_attr_e( 'Backup options', 'usces' ); ?>" ></td>
		<td><input name="options_restore" id="options_restore" type="button" class="button" value="<?php esc_attr_e( 'Restoring a Backup', 'usces' ); ?>" ></td>
		<td><div id="options_backup_date"><?php echo esc_html( $options_backup_date ); ?></div></td>
		<td><div id="ex_options_backup" class="explanation"><?php esc_html_e( 'You can take the backup of the Welcart setup data.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="30">
		<th class="system_th" rowspan="2"><a style="cursor:pointer;" onclick="toggleVisibility('ex_settlement_backup');"><?php esc_html_e( 'Order data re-created from the settlement log', 'usces' ); ?></a></th>
		<td width="10"><input name="settlement_backup" id="settlement_backup_0" type="radio" value="0"<?php checked( $settlement_backup, 0 ); ?>/></td><td width="100"><label for="settlement_backup_0"><?php esc_html_e( 'Do not Use', 'usces' ); ?></label></td>
		<td rowspan="2"><div id="ex_settlement_backup" class="explanation"><?php esc_html_e( 'When the credit card settlement is chosen, save the purchase information in log before settlement. Create the order data from log.', 'usces' ); ?></div></td>
	</tr>
	<tr height="30">
		<td width="10"><input name="settlement_backup" id="settlement_backup_1" type="radio" value="1"<?php checked( $settlement_backup, 1 ); ?>/></td><td width="100"><label for="settlement_backup_1"><?php esc_html_e( 'Use', 'usces' ); ?></label></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr height="30">
		<th class="system_th" rowspan="2"><a style="cursor:pointer;" onclick="toggleVisibility('ex_settlement_notice');"><?php esc_html_e( 'Settlement error message', 'usces' ); ?></a></th>
		<td width="10"><input name="settlement_notice" id="settlement_notice_0" type="radio" value="0"<?php checked( $settlement_notice, 0 ); ?>/></td><td width="100"><label for="settlement_notice_0"><?php esc_html_e( 'Do not display', 'usces' ); ?></label></td>
		<td rowspan="2"><div id="ex_settlement_notice" class="explanation">
		<?php
		esc_html_e( 'A settlement error message will be displayed on the admin screen when a settlement error occurs.', 'usces' );
		if ( defined( 'WCEX_DLSELLER' ) || defined( 'WCEX_AUTO_DELIVERY' ) ) {
			esc_html_e( 'If you are using the extension plugin "WCEX DL Seller" or "WCEX Auto Delivery", it always be displayed even if you select "Do not display".', 'usces' );
		}
		?>
		</div></td>
	</tr>
	<tr height="30">
		<td width="10"><input name="settlement_notice" id="settlement_notice_1" type="radio" value="1"<?php checked( $settlement_notice, 1 ); ?>/></td><td width="100"><label for="settlement_notice_1"><?php esc_html_e( 'Display', 'usces' ); ?></label></td>
	</tr>
</table>
</div>
</div><!--postbox-->
<input name="usces_system_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
</div><!--system_page_setting_1-->

<input type="hidden" name="post_ID" value="<?php echo esc_attr( USCES_CART_NUMBER ); ?>" />
<?php wp_nonce_field( 'admin_system', 'wc_nonce' ); ?>
</form>

<form action="" method="post" name="option_form" id="option_form2">

<div id="system_page_setting_2">
<div class="postbox">
<h3><span><?php esc_html_e( 'Language Currency Country', 'usces' ); ?></span></h3>
<div class="inside">
<table class="form_table">
	<tr height="50">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_front_lang');"><?php esc_html_e( 'The language of the front-end', 'usces' ); ?></a></th>
		<td width="10"><select name="front_lang" id="front_lang">
		<?php foreach ( $front_language as $lkey => $lvalue ) : ?>
			<option value="<?php echo esc_attr( $lkey ); ?>"<?php selected( $system_front_lang, $lkey ); ?>><?php echo esc_attr( $lvalue ); ?></option>
		<?php endforeach; ?>
		</select></td>
		<td><div id="ex_front_lang" class="explanation"><?php esc_html_e( 'You can select the Front-end language. The Back-end language follows setting config.php.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr height="50">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_currency');"><?php esc_html_e( 'Currencies', 'usces' ); ?></a></th>
		<td width="10"><select name="currency" id="currency">
		<?php foreach ( $usces_settings['country'] as $ckey => $cvalue ) : ?>
			<option value="<?php echo esc_attr( $ckey ); ?>"<?php selected( $system_currency, $ckey ); ?>><?php echo esc_attr( $cvalue ); ?></option>
		<?php endforeach; ?>
			<!--<option value="manual"<?php selected( $system_currency, 'manual' ); ?>><?php esc_html_e( 'Manual', 'usces' ); ?></option>-->
		</select></td>
		<td><div id="ex_currency" class="explanation"><?php esc_html_e( 'Displays the currency symbol for each country, the amount separator, the decimal digits. This is a common item in both front end and back end.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr height="50">
		<th class="system_th"><a style="cursor:pointer;" onclick="toggleVisibility('ex_addressform');"><?php esc_html_e( 'The name and address form', 'usces' ); ?></a></th>
		<td width="10"><select name="addressform" id="addressform">
		<?php foreach ( $usces_settings['country'] as $ckey => $cvalue ) : ?>
			<option value="<?php echo esc_attr( $ckey ); ?>"<?php selected( $system_addressform, $ckey ); ?>><?php echo esc_attr( $cvalue ); ?></option>
		<?php endforeach; ?>
		</select></td>
		<td><div id="ex_addressform" class="explanation"><?php esc_html_e( 'For entry form style, choose your country.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr height="50">
		<th class="system_th">
			<a style="cursor:pointer;" onclick="toggleVisibility('ex_target_market');"><?php esc_html_e( 'Target Market', 'usces' ); ?></a>
			<div><input name="set_target_market" id="set_target_market" type="button" class="button" value="<?php esc_attr_e( 'Choose', 'usces' ); ?>" onclick="operation.set_target_market();" /></div>
		</th>
		<td width="20"><select name="target_market[]" size="10" multiple="multiple" class="multipleselect" id="target_market">
			<!--<option value="all"<?php selected( in_array( 'all', $system_target_markets ), true ); ?>><?php esc_html_e( 'All countries', 'usces' ); ?></option>-->
		<?php foreach ( $usces_settings['country'] as $ckey => $cvalue ) : ?>
			<option value="<?php echo esc_attr( $ckey ); ?>"<?php selected( in_array( $ckey, $system_target_markets ), true ); ?>><?php echo esc_attr( $cvalue ); ?></option>
		<?php endforeach; ?>
		</select></td>
		<td><div id="ex_target_market" class="explanation"><?php esc_html_e( 'Select the number of possible areas within the country. Allows multiple selections.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr height="50">
		<th class="system_th">
			<a style="cursor:pointer;" onclick="toggleVisibility('ex_province');"><?php esc_html_e( 'Province', 'usces' ); ?></a>
			<div><span id="target_market_loading"></span><span id="target_market_province"></span></div>
		</th>
		<td width="150"><textarea name="province" id="province" cols="30" rows="10"></textarea><div id="province_ajax"></div></td>
		<td><div id="ex_province" class="explanation"><?php esc_html_e( 'The district where sale is possible', 'usces' ); ?>(<?php esc_html_e( 'Province', 'usces' ); ?>) <?php esc_html_e( 'One line one by one.', 'usces' ); ?></div></td>
	</tr>
</table>
</div>
</div><!--postbox-->
<input name="usces_locale_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
</div><!--system_page_setting_2-->

<input type="hidden" name="post_ID" value="<?php echo esc_attr( USCES_CART_NUMBER ); ?>" />
<?php wp_nonce_field( 'admin_system', 'wc_nonce' ); ?>
</form>

<div id="system_page_setting_3" class="meta-box-sortables">
<?php do_action( 'usces_action_admin_system_extentions' ); ?>
</div><!--system_page_setting_3-->
<div id="system_page_setting_4">
<?php require_once 'admin_system_status.php'; ?>
<?php do_action( 'usces_action_admin_system_status' ); ?>
</div><!--system_page_setting_4-->

<?php do_action( 'usces_action_admin_system_tab_body' ); ?>

</div><!--uscestabs_system-->

</div><!--poststuff-->

</div><!--usces_admin-->
</div><!--wrap-->
